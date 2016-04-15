<?php
 class Responser{
    private static $WECHAT_MEDIA_EXPIRE_SEC = 255600;
    function __construct(){
    }
    public function respondText($weid, $from_user, $channel_id, $rule){
        WeUtility :: logging('step1', '');
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quickcenter', 'fans');
        yload() -> classs('quicklink', 'channel');
        yload() -> classs('quicklink', 'scene');
        $weapi = new WechatAPI();
        $_channel = new Channel();
        $_scene = new Scene();
        $_fans = new Fans();
        $fans = $_fans -> get($weid, $from_user);
        $ch = $_channel -> get($weid, $channel_id);
        $qr = $_scene -> getQR($weid, $from_user, $channel_id);
        if (intval($ch['vip_limit']) > 0 and intval($fans['vip']) < intval($ch['vip_limit'])){
            $ret = $weapi -> sendText($from_user, $ch['genqr_vip_limit_info']);
            exit(0);
        }else if (empty($qr) or ($qr['createtime'] < $ch['createtime']) or ($qr['createtime'] + self :: $WECHAT_MEDIA_EXPIRE_SEC < time())){
            if (!empty($ch['genqr_info1'])){
                $ret = $weapi -> sendText($from_user, $ch['genqr_info1']);
            }
            $scene_id = $_scene -> getNextAvaliableSceneID($weid);
            $media_id = $this -> genImage($weid, $from_user, $weapi, $scene_id, $channel_id);
            if (!empty($media_id) and !empty($ch['genqr_info2'])){
                $ret = $weapi -> sendText($from_user, $ch['genqr_info2']);
            }
            if (empty($media_id)){
                $ret = $weapi -> sendText($from_user, '生成二维码传单失败, 请联系我们解决. ScID:' . $scene_id);
            }else if (!empty($scene_id)){
                WeUtility :: logging('begin setQR', array($scene_id));
                $_scene -> newQR($weid, $from_user, $scene_id, '', $media_id, $channel_id, $rule);
                WeUtility :: logging('end setQR', '');
            }
        }else{
            $media_id = $qr['media_id'];
            if (!empty($media_id) and !empty($ch['genqr_info3'])){
                $ret = $weapi -> sendText($from_user, $ch['genqr_info3']);
            }
        }
        WeUtility :: logging('step4', $media_id);
        if (!empty($media_id)){
            $ret = $weapi -> sendImage($from_user, $media_id);
        }else{
            $ret = $weapi -> sendText($from_user, "您的专属二维码已经生成过啦, 相信您已经保存起来了吧？你之前保存过的专属二维码依然有效，直接转发就可以啦。");
        }
        exit(0);
    }
    private function genImage($weid , $from_user, $weapi, $scene_id, $channel){
        global $_W;
        $rand_file = $from_user . rand() . '.jpg';
        $att_target_file = 'qr-image-' . $rand_file;
        $att_qr_cache_file = 'raw-qr-image-' . $rand_file;
        $att_head_cache_file = 'head-image-' . $rand_file;
        $target_file = ATTACH_DIR . $att_target_file;
        $target_file_url = $_W['attachurl'] . $att_target_file;
        $head_cache_file = ATTACH_DIR . $att_head_cache_file;
        $qr_cache_file = ATTACH_DIR . $att_qr_cache_file;
        $qr_file = $weapi -> getLimitQR($scene_id);
        yload() -> classs('quicklink', 'channel');
        $_channel = new Channel();
        $ch = $_channel -> get($weid, $channel);
        $enableHead = $ch['avatarenable'];
        $enableName = $ch['nameenable'];
        if (empty($ch)){
            $ret = $weapi -> sendText($from_user, "您所请求的二维码已经失效, 请联系客服人员");
            exit(0);
        }else if (empty($ch['bgimages'])){
            $bg_file = MODULE_ROOT . 'quicklink/images/bg.jpg';
        }else if (is_array($ch['bgimages'])){
            $cnt = count($ch['bgimages']);
            if ($cnt > 0){
                srand(TIMESTAMP);
                $ridx = rand(0, $cnt - 1);
                $rand_bg = $ch['bgimages'][$ridx];
                $bg_file = $_W['attachurl'] . $rand_bg;
            }else{
                $bg_file = MODULE_ROOT . 'quicklink/images/bg.jpg';
            }
        }else{
            $bg_file = MODULE_ROOT . 'quicklink/images/bg.jpg';
        }
        WeUtility :: logging('step merge 1', '');
        $this -> mergeImage($bg_file, $qr_file, $target_file, array('left' => $ch['qrleft'], 'top' => $ch['qrtop'], 'width' => $ch['qrwidth'], 'height' => $ch['qrheight'], 'quality' => $ch['qrquality']));
        WeUtility :: logging('step merge 1 done', '');
        if (1){
            $fans = fans_search($from_user, array('nickname', 'avatar'));
            if (!empty($fans)){
                if ($enableName){
                    if (strlen($fans['nickname']) > 0){
                        WeUtility :: logging('step wirte text 1', '');
                        $this -> writeText($target_file, $target_file, $fans['nickname'], array('size' => $ch['namesize'], 'left' => $ch['nameleft'], 'top' => $ch['nametop'], 'color' => $ch['namecolor']));
                        WeUtility :: logging('step wirte text 1 done', '');
                    }
                }
                if ($enableHead){
                    if (strlen($fans['avatar']) > 10){
                        $head_file = $fans['avatar'];
                        $head_file = preg_replace('/\/0$/i', '/96', $head_file);
                        $url = WechatUtil :: curl_file_get_contents($head_file);
                        $fp = fopen($head_cache_file, 'wb');
                        fwrite($fp, $url);
                        fclose($fp);
                        $this -> mergeImage($target_file, $head_cache_file, $target_file, array('left' => $ch['avatarleft'], 'top' => $ch['avatartop'], 'width' => $ch['avatarwidth'], 'height' => $ch['avatarheight'], 'quality' => 100));
                        WeUtility :: logging('IamInMergeFile', $target_file . $head_file);
                    }
                }
            }
        }
        $media_id = $weapi -> uploadImage($target_file);
        if (!empty($media_id)){
            global $_W;
            $nowtime = time();
            pdo_query("INSERT INTO " . tablename('core_attachment') . " (uniacid, uid, filename,attachment,type,createtime) VALUES " . "({$weid}, {$weid}, 'head_cache', '{$att_head_cache_file}', 1, {$nowtime})," . "({$weid}, {$weid}, 'qr_cache', '{$att_qr_cache_file}', 1, {$nowtime})," . "({$weid}, {$weid}, 'post_cache', '{$att_target_file}', 1, {$nowtime})");
        }else{
            $ret = $weapi -> sendText($from_user, "哎哟，没有成功地把图片通过微信推送给你，不过没关系哦，点击这里:<a href='$target_file_url'>打开您的专属二维码</a>,保存到手机后转发.");
        }
        return $media_id;
    }
    private function imagecreate($bg){
        $bgImg = @imagecreatefromjpeg($bg);
        if (FALSE == $bgImg){
            $bgImg = @imagecreatefrompng($bg);
        }
        if (FALSE == $bgImg){
            $bgImg = @imagecreatefromgif($bg);
        }
        return $bgImg;
    }
    private function mergeImage($bg, $qr, $out, $param){
        extract($param);
        $bgImg = $this -> imagecreate($bg);
        $qrImg = $this -> imagecreate($qr);
        list($bgWidth, $bgHeight) = array(imagesx($bgImg), imagesy($bgImg));
        list($qrWidth, $qrHeight) = array(imagesx($qrImg), imagesy($qrImg));
        imagecopyresized($bgImg, $qrImg, $left, $top, 0, 0, $width, $height, $qrWidth, $qrHeight);
        ob_start();
        imagejpeg($bgImg, NULL, $quality);
        $contents = ob_get_contents();
        ob_end_clean();
        imagedestroy($bgImg);
        imagedestroy($qrImg);
        $fh = fopen($out, "w+");
        fwrite($fh, $contents);
        fclose($fh);
    }
    private function writeText($bg, $out, $text, $param = array()){
        list($bgWidth, $bgHeight) = getimagesize($bg);
        extract($param);
        $im = imagecreatefromjpeg($bg);
        $black = imagecolorallocate($im, 0, 0, 0);
        $font = APP_FONT . 'msyhbd.ttf';
        list($red, $green, $blue) = $this -> hex2rgb($color);
        $rgbcolor = imagecolorallocate($im, $red, $green, $blue);
        imagettftext($im, $size, 0, $left, $top + $size / 2, $rgbcolor, $font, $text);
        ob_start();
        imagejpeg($im, NULL, 80);
        $contents = ob_get_contents();
        ob_end_clean();
        imagedestroy($im);
        $fh = fopen($out, "w+");
        fwrite($fh, $contents);
        fclose($fh);
    }
    private function hex2rgb($colour){
        if ($colour[0] == '#'){
            $colour = substr($colour, 1);
        }
        if (strlen($colour) == 6){
            list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
        }elseif (strlen($colour) == 3){
            list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
        }else{
            list($r, $g, $b) = array('00', '00', '00');
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return array($r, $g, $b);
    }
}
