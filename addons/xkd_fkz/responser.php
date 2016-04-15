<?php
defined('IN_IA') or exit('Access Denied');

define('JX_ROOT', str_replace("\\", '/', dirname(__FILE__)));

require_once APP_PHP . 'wechatapi.php';
require_once APP_PHP . 'usermanager.php';

class QRResponser
{

    private $mod_poster;
    private $mod_qr;

    function __construct()
    {
        $this->loadMod('poster');
        $this->mod_poster = new poster();

        $this->loadMod('qr');
        $this->mod_qr = new qr();
    }

    public function respondText($openid)
    {

        global $_W;

        WeUtility::logging('step1', '');
        $weapi = new WechatAPI();

        $qr_mgr = new UserManager($openid);

        $poster = $this->mod_poster->get_poster_by_uniacid();
        $poster_id = $poster['poster_id'];

        $qr = $this->mod_qr->get_qr($poster_id, $openid);

        WeUtility::logging('step3', $qr['createtime'] . '<' . $poster['createtime']);
        if (empty($qr) || $qr['createtime'] < $poster['createtime']) {
            WeUtility::logging('step3.0', $openid);
            if (!empty($poster['genqr_info1'])) {
                $ret = $weapi->sendText($openid, $poster['genqr_info1']);
            }

            WeUtility::logging('step3.1', '');
            $scene_id = $this->mod_poster->get_next_avaliable_scene_id();
            list($media_id, $target_file_url) = $this->genImage($weapi, $scene_id, $poster_id, $openid);
            if (!empty($media_id) and !empty($poster['genqr_info2'])) {
                $ret = $weapi->sendText($openid, $poster['genqr_info2']);
            }

            WeUtility::logging('begin setQR', '');
            $this->mod_qr->add_qr($scene_id, $target_file_url, $media_id, $poster_id, $openid);
            WeUtility::logging('end setQR', '');

        } else {
            if (!empty($poster['genqr_info3'])) {
                $ret = $weapi->sendText($openid, $poster['genqr_info3']);
            }

            WeUtility::logging('step3.2', '');
            $media_id = $qr['media_id'];
            $target_file_url = $qr['qr_url'];
        }

        WeUtility::logging('step4', $media_id);

        if (!empty($media_id)) {
            $ret = $weapi->sendImage($openid, $media_id);
        } else {
            $ret = $weapi->sendText($openid, "您的名片已生成成功,打开后长按图片保存到手机后转发到朋友圈或微信群就能发展会员啦!之前保存的名片依然有效，直接转发即可！");
            $ret = $weapi->sendText($openid, "<a href='$target_file_url'>【点击这里查看您的名片】</a>");
        }

        exit(0);
    }

    private function genImage($weapi, $scene_id, $poster_id, $openid)
    {
        global $_W;

        $rand_file = $openid . rand() . '.jpg';
        $att_target_file = 'qr-image-' . $rand_file;
        $att_head_cache_file = 'head-image-' . $rand_file;
        $target_file = ATTACH_DIR . $att_target_file;
        $target_file_url = $_W['attachurl'] . $att_target_file;
        $head_cache_file = ATTACH_DIR . $att_head_cache_file;
        $qr_file = $weapi->getLimitQR($scene_id);

        $poster = $this->mod_poster->get_poster($poster_id);

        $enableHead = $poster['avatarenable'];
        $enableName = $poster['nameenable'];

        if (empty($poster)) {
            $ret = $weapi->sendText($openid, "您所请求的名片二维码已经失效, 请联系客服人员");
            exit;
        } else if (empty($poster['bg'])) {
            $bg_file = APP_PHP . 'images/bg.jpg';
        } else {
            $bg_file = $_W['attachurl'] . $poster['bg'];
        }

        WeUtility::logging('step merge 1', "merge bgfile {$bg_file} and qrfile {$qr_file}");
        $this->mergeImage($bg_file, $qr_file, $target_file, array('left' => $poster['qrleft'], 'top' => $poster['qrtop'], 'width' => $poster['qrwidth'], 'height' => $poster['qrheight']));
        WeUtility::logging('step merge 1 done', '');

        $fans = WechatUtil::fans_search($openid, array('nickname', 'avatar'));
        if (!empty($fans)) {
            if ($enableName) {
                if (strlen($fans['nickname']) > 0) {
                    WeUtility::logging('step wirte text 1', $fans);
                    $this->writeText($target_file, $target_file, $fans['nickname'], array('size' => $poster['namesize'], 'left' => $poster['nameleft'], 'top' => $poster['nametop']));
                    WeUtility::logging('step wirte text 1 done', '');
                }
            }
            if ($enableHead) {
                if (strlen($fans['avatar']) > 10) {
                    $head_file = $fans['avatar'];
                    $head_file = preg_replace('/\/0$/i', '/96', $head_file);
                    WeUtility::logging('step merge 2', $head_file);
                    $this->mergeImage2($target_file, $head_file, $target_file, array('left' => $poster['avatarleft'], 'top' => $poster['avatartop'], 'width' => $poster['avatarwidth'], 'height' => $poster['avatarheight']));
                    WeUtility::logging('step merge 2 done', '');
                    WeUtility::logging('IamInMergeFile', $target_file . $head_file);
                } else {
                    WeUtility::logging('NoAvatarFile', $fans['avatar']);
                }
            }
        } else {
            WeUtility::logging('NOT merge avatar and nickname', $openid);
        }

        WeUtility::logging('step upload 1', '');
        $media_id = $weapi->uploadImage($target_file);
        WeUtility::logging('step upload 1 done', '');
        WeUtility::logging('genImage', $media_id);
        if (!empty($media_id)) {
            $nowtime = time();
            pdo_query("INSERT INTO " . tablename('core_attachment') . " (uniacid,uid,filename,attachment,type,createtime) VALUES "
                . "({$_W['weid']}, {$_W['weid']}, 'head_cache', '{$att_head_cache_file}', 1, {$nowtime}),"
                . "({$_W['weid']}, {$_W['weid']}, 'post_cache', '{$att_target_file}', 1, {$nowtime})");
        } else {
            $ret = $weapi->sendText($openid, "名片已经生成, 点击这里:<a href='$target_file_url'>查看您的专属二维码</a>, 保存到手机后转发给好友就能拿话费!");
        }
        return array($media_id, $target_file_url);
    }

    private function imagecreate($bg)
    {
        $bgImg = @imagecreatefromjpeg($bg);
        if (FALSE == $bgImg) {
            $bgImg = @imagecreatefrompng($bg);
        }
        if (FALSE == $bgImg) {
            $bgImg = @imagecreatefromgif($bg);
        }
        return $bgImg;
    }

    //加入二维码
    private function mergeImage($bg, $qr, $out, $param)
    {

        list($bgWidth, $bgHeight) = getimagesize($bg);
        list($qrWidth, $qrHeight) = getimagesize($qr);
        extract($param);
        $bgImg = $this->imagecreate($bg);
        $qrImg = $this->imagecreate($qr);
        imagecopyresized($bgImg, $qrImg, $left, $top, 0, 0, $width, $height, $qrWidth, $qrHeight);
        ob_start();

        imagejpeg($bgImg, NULL, 100);
        $contents = ob_get_contents();
        ob_end_clean();
        imagedestroy($bgImg);
        imagedestroy($qrImg);
        $fh = fopen($out, "w+");
        fwrite($fh, $contents);
        fclose($fh);
    }

    private function mergeImage2($bg, $qr, $out, $param)
    {
        list($bgWidth, $bgHeight) = getimagesize($bg);
        //list($qrWidth, $qrHeight) = getimagesize($qr);
        $qrWidth = 96;
        $qrHeight = 96;
        extract($param);
        $bgImg = $this->imagecreate($bg);
        $qrImg = $this->imagecreate($qr);
        imagecopyresized($bgImg, $qrImg, $left, $top, 0, 0, $width, $height, $qrWidth, $qrHeight);
        ob_start();

        //80为品质，建议设置80以下
        imagejpeg($bgImg, NULL, 80);
        $contents = ob_get_contents();
        ob_end_clean();
        imagedestroy($bgImg);
        imagedestroy($qrImg);
        $fh = fopen($out, "w+");
        fwrite($fh, $contents);
        fclose($fh);
    }

    private function writeText($bg, $out, $text, $param = array())
    {
        list($bgWidth, $bgHeight) = getimagesize($bg);
        extract($param);
        $im = imagecreatefromjpeg($bg);
        $black = imagecolorallocate($im, 0, 0, 0);
        $font = APP_FONT . 'msyhbd.ttf';

        $white = imagecolorallocate($im, 255, 255, 255);
        imagettftext($im, $size, 0, $left, $top + $size / 2, $white, $font, $text);
        ob_start();

        imagejpeg($im, NULL, 100);
        $contents = ob_get_contents();
        ob_end_clean();
        imagedestroy($im);
        $fh = fopen($out, "w+");
        fwrite($fh, $contents);
        fclose($fh);
    }

    private function loadMod($class)
    {
        require_once JX_ROOT . '/mod/' . $class . '.mod.php';
    }
}
