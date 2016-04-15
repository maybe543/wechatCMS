<?php
defined('IN_IA') or exit('Access Denied');
define('FMURL', '../addons/fm_photosvote/template/');
class Fm_photosvoteModuleSite extends WeModuleSite
{
    public $title = '女神来了！';
    public $table_reply = 'fm_photosvote_reply';
    public $table_reply_share = 'fm_photosvote_reply_share';
    public $table_reply_huihua = 'fm_photosvote_reply_huihua';
    public $table_reply_display = 'fm_photosvote_reply_display';
    public $table_reply_vote = 'fm_photosvote_reply_vote';
    public $table_reply_body = 'fm_photosvote_reply_body';
    public $table_users = 'fm_photosvote_provevote';
    public $table_tags = 'fm_photosvote_tags';
    public $table_users_picarr = 'fm_photosvote_provevote_picarr';
    public $table_users_voice = 'fm_photosvote_provevote_voice';
    public $table_users_name = 'fm_photosvote_provevote_name';
    public $table_log = 'fm_photosvote_votelog';
    public $table_bbsreply = 'fm_photosvote_bbsreply';
    public $table_banners = 'fm_photosvote_banners';
    public $table_advs = 'fm_photosvote_advs';
    public $table_gift = 'fm_photosvote_gift';
    public $table_data = 'fm_photosvote_data';
    public $table_iplist = 'fm_photosvote_iplist';
    public $table_iplistlog = 'fm_photosvote_iplistlog';
    public $table_announce = 'fm_photosvote_announce';
    public $table_templates = 'fm_photosvote_templates';
    public $table_designer = 'fm_photosvote_templates_designer';
    public function __web($f_name)
    {
        global $_GPC, $_W;
        checklogin();
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        $rid     = intval($_GPC['rid']);
        include_once 'fmweb/' . strtolower(substr($f_name, 5)) . '.php';
    }
    public function __mobile($f_name)
    {
        global $_GPC, $_W;
        if ($_GPC['uniacid']) {
            $uniacid = $_GPC['uniacid'];
        } else {
            $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        }
        $rid        = $_GPC['rid'];
        $tfrom_user = !empty($_GPC['tfrom_user']) ? $_GPC['tfrom_user'] : $_COOKIE["user_tfrom_user_openid"];
        $fromuser   = !empty($_GPC['fromuser']) ? $_GPC["fromuser"] : $_COOKIE["user_fromuser_openid"];
        $cfg        = $this->module['config'];
        if ($cfg['oauthtype'] == 1) {
            if ($_GPC['do'] != 'shareuserview' && $_GPC['do'] != 'shareuserdata' && $_GPC['do'] != 'treg' && $_GPC['do'] != 'tregs' && $_GPC['do'] != 'tvotestart' && $_GPC['do'] != 'tbbs' && $_GPC['do'] != 'tbbsreply' && $_GPC['do'] != 'saverecord' && $_GPC['do'] != 'saverecord1' && $_GPC['do'] != 'subscribeshare' && $_GPC['do'] != 'pagedata' && $_GPC['do'] != 'listentry' && $_GPC['do'] != 'code' && $_GPC['do'] != 'reguser' && $_GPC['do'] != 'phdata') {
                $oauthuser = $this->FM_checkoauth();
            }
            $from_user = empty($oauthuser['from_user']) ? $_GPC['from_user'] : $oauthuser['from_user'];
            $avatar    = $oauthuser['avatar'];
            $nickname  = $oauthuser['nickname'];
            $follow    = $oauthuser['follow'];
        } else {
            $from_user = empty($_COOKIE["user_oauth2_openid"]) ? $_W['openid'] : $_COOKIE["user_oauth2_openid"];
            $avatar    = $_COOKIE["user_oauth2_avatar"];
            $nickname  = $_COOKIE["user_oauth2_nickname"];
            $sex       = $_COOKIE["user_oauth2_sex"];
            $unionid   = $_COOKIE["user_oauth2_unionid"];
            if ($cfg['oauthtype'] == 2 && !empty($unionid)) {
                $f         = pdo_fetch("SELECT follow,openid FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = '{$uniacid}' AND `unionid` = '{$unionid}' ");
                $from_user = $f['openid'];
                $follow    = $f['follow'];
            } else {
                $f      = pdo_fetch("SELECT follow FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = '{$uniacid}' AND `openid` = '{$from_user}' ");
                $follow = !empty($_W['fans']['follow']) ? $_W['fans']['follow'] : $f['follow'];
            }
            if ($from_user != 'fromUser') {
                if (empty($from_user) || empty($nickname) || empty($avatar)) {
                    if ($cfg['oauthtype'] == 2) {
                        if (empty($_COOKIE["user_oauth2_openid"]) || empty($_COOKIE["user_oauth2_unionid"])) {
                            if ($_GPC['do'] != 'shareuserview' && $_GPC['do'] != 'shareuserdata' && $_GPC['do'] != 'treg' && $_GPC['do'] != 'tregs' && $_GPC['do'] != 'tvotestart' && $_GPC['do'] != 'tbbs' && $_GPC['do'] != 'tbbsreply' && $_GPC['do'] != 'saverecord' && $_GPC['do'] != 'saverecord1' && $_GPC['do'] != 'subscribeshare' && $_GPC['do'] != 'pagedata' && $_GPC['do'] != 'listentry' && $_GPC['do'] != 'code' && $_GPC['do'] != 'reguser' && $_GPC['do'] != 'phdata') {
                                $this->checkoauth2($rid, $_COOKIE["user_oauth2_openid"], $_COOKIE["user_oauth2_unionid"]);
                            }
                        }
                    } else {
                        if (empty($_COOKIE["user_oauth2_openid"])) {
                            if ($_GPC['do'] != 'shareuserview' && $_GPC['do'] != 'shareuserdata' && $_GPC['do'] != 'treg' && $_GPC['do'] != 'tregs' && $_GPC['do'] != 'tvotestart' && $_GPC['do'] != 'tbbs' && $_GPC['do'] != 'tbbsreply' && $_GPC['do'] != 'saverecord' && $_GPC['do'] != 'saverecord1' && $_GPC['do'] != 'subscribeshare' && $_GPC['do'] != 'pagedata' && $_GPC['do'] != 'listentry' && $_GPC['do'] != 'code' && $_GPC['do'] != 'reguser' && $_GPC['do'] != 'phdata') {
                                $this->checkoauth2($rid, $_COOKIE["user_oauth2_openid"]);
                            }
                        }
                    }
                }
            }
        }
        if (!empty($rid)) {
            $rbasic   = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $rshare   = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_share) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $rhuihua  = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_huihua) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $rdisplay = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_display) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $rvote    = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_vote) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $rbody    = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_body) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $reply    = array_merge($rbasic, $rshare, $rhuihua, $rdisplay, $rvote, $rbody);
            $qiniu    = iunserializer($reply['qiniu']);
            $now      = time();
            if ($_GPC['do'] == 'photosvote' || $_GPC['do'] == 'tuser' || $_GPC['do'] == 'tuserphotos' || $_GPC['do'] == 'des' || $_GPC['do'] == 'reg' || $_GPC['do'] == 'paihang') {
                if ($now - $reply['xuninum_time'] > $reply['xuninumtime']) {
                    pdo_update($this->table_reply_display, array(
                        'xuninum_time' => $now,
                        'xuninum' => $reply['xuninum'] + mt_rand($reply['xuninuminitial'], $reply['xuninumending'])
                    ), array(
                        'rid' => $rid
                    ));
                }
                if (!empty($tfrom_user)) {
                    $user = pdo_fetch("SELECT id, from_user, hits FROM " . tablename($this->table_users) . " WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(
                        ':uniacid' => $uniacid,
                        ':from_user' => $tfrom_user,
                        ':rid' => $rid
                    ));
                    if ($user) {
                        $yuedu = $tfrom_user . $from_user . $rid . $uniacid;
                        if ($_COOKIE["user_yuedu"] != $yuedu) {
                            pdo_update($this->table_users, array(
                                'hits' => $user['hits'] + 1
                            ), array(
                                'rid' => $rid,
                                'from_user' => $tfrom_user
                            ));
                            setcookie('user_yuedu', $yuedu, time() + 3600 * 24);
                        }
                    }
                } else {
                    pdo_update($this->table_reply_display, array(
                        'hits' => $reply['hits'] + 1
                    ), array(
                        'rid' => $rid
                    ));
                }
            }
            if ($_GPC['do'] == 'photosvote' || $_GPC['do'] == 'tuser' || $_GPC['do'] == 'tuserphotos' || $_GPC['do'] == 'des' || $_GPC['do'] == 'reg') {
                if ($reply['status'] == 0) {
                    $stopurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('stop', array(
                        'status' => '0',
                        'rid' => $rid
                    ));
                    header("location:$stopurl");
                    exit;
                }
                if ($now < $reply['start_time']) {
                    $stopurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('stop', array(
                        'status' => '-1',
                        'rid' => $rid
                    ));
                    header("location:$stopurl");
                    exit;
                }
                if ($now > $reply['end_time']) {
                    $stopurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('stop', array(
                        'status' => '1',
                        'rid' => $rid
                    ));
                    header("location:$stopurl");
                    exit;
                }
            }
            if ($reply['isipv'] == 1) {
                if ($_GPC['do'] == 'photosvote' || $_GPC['do'] == 'tuser' || $_GPC['do'] == 'tuserphotos' || $_GPC['do'] == 'des' || $_GPC['do'] == 'reg') {
                    $this->stopip($rid, $uniacid, $from_user, getip(), $_GPC['do'], $reply['ipturl'], $reply['limitip']);
                }
            }
        }
        define('FMFILE', IA_ROOT . '/addons/fm_photosvote/template/mobile/');
        include_once 'fmmobile/' . strtolower(substr($f_name, 8)) . '.php';
    }
    private function templatec($templatename, $filename)
    {
        global $_GPC, $_W;
        $tf     = 'templates/' . $templatename . '/' . $filename;
        $toye   = $this->_stopllq($tf);
        $tmfile = FMFILE . $tf . '.html';
        if (!file_exists($tmfile) || $templatename == 'default') {
            $tf = $filename;
        }
        return $tf;
    }
    private function _stopllq($turl)
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            return $turl;
        } else {
            return $turl;
        }
    }
    public function doMobilelisthome()
    {
        $this->doMobilelistentry();
    }
    public function gettiles($keyword = '')
    {
        global $_GPC, $_W;
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        $urls    = array();
        $list    = pdo_fetchall("SELECT id FROM " . tablename('rule') . " WHERE uniacid = " . $uniacid . " and module = 'fm_photosvote'" . (!empty($keyword) ? " AND name LIKE '%{$keyword}%'" : ''));
        if (!empty($list)) {
            foreach ($list as $row) {
                $reply  = pdo_fetch("SELECT title FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                    ':rid' => $row['id']
                ));
                $urls[] = array(
                    'title' => $reply['title'],
                    'url' => $_W['siteroot'] . 'app/' . $this->createMobileUrl('photosvote', array(
                        'rid' => $row['id']
                    ))
                );
            }
        }
        return $urls;
    }
    function fmqnimages($nfilename, $qiniu, $mid, $username)
    {
        $fmurl      = 'http://demo.012wz.com/api/qiniu/api.php?';
        $hosts      = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        $host       = base64_encode($hosts);
        $visitorsip = base64_encode(getip());
        $fmimages   = array(
            'nfilename' => $nfilename,
            'qiniu' => $qiniu,
            'mid' => $mid,
            'username' => $username
        );
        $fmimages   = base64_encode(base64_encode(iserializer($fmimages)));
        $fmpost     = $fmurl . 'host=' . $host . "&visitorsip=" . $visitorsip . "&webname=" . $webname . "&fmimages=" . $fmimages;
        load()->func('communication');
        $content = ihttp_get($fmpost);
        $fmmv    = @json_decode($content['content'], true);
        if ($mid == 0) {
            $fmdata = array(
                "success" => $fmmv['success'],
                "msg" => $fmmv['msg']
            );
            $fmdata['mid'] == 0;
            $fmdata['imgurl'] = $fmmv['imgurl'];
            return $fmdata;
            exit;
        } else {
            $fmdata                   = array(
                "success" => $fmmv['success'],
                "msg" => $fmmv['msg']
            );
            $fmdata['picarr_' . $mid] = $fmmv['picarr_' . $mid];
            return $fmdata;
            exit;
        }
    }
    function fmqnaudios($nfilename, $qiniu, $upmediatmp, $audiotype, $username)
    {
        $fmurl    = 'http://demo.012wz.com/api/qiniu/api.php?';
        $host     = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        $host     = base64_encode($host);
        $clientip = base64_encode($_W['clientip']);
        $fmaudios = array(
            'nfilename' => $nfilename,
            'qiniu' => $qiniu,
            'upmediatmp' => $upmediatmp,
            'audiotype' => $audiotype,
            'username' => $username
        );
        $fmaudios = base64_encode(base64_encode(iserializer($fmaudios)));
        $fmpost   = $fmurl . 'host=' . $host . "&visitorsip=" . $clientip . "&fmaudios=" . $fmaudios;
        load()->func('communication');
        $content            = ihttp_get($fmpost);
        $fmmv               = @json_decode($content['content'], true);
        $fmdata             = array(
            "msg" => $fmmv['msg'],
            "success" => $fmmv['success'],
            "nfilenamefop" => $fmmv['nfilenamefop']
        );
        $fmdata[$audiotype] = $fmmv[$audiotype];
        return $fmdata;
        exit();
    }
    public function doMobilelistentry()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileStop()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileStopip()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileStopllq()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePhotosvote()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePagedata()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePagedatab()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePhdatabase()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePhdata()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileTuser()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileSubscribe()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileSubscribeshare()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileTvote()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileTvotestart()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileTbbs()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileTbbsreply()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileTuserphotos()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilereg()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileSaverecord()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileTreg()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilereguser()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePaihang()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileDes()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileshareuserview()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileshareuserdata()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileMiaoxian()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileComment()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileCommentdata()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileCmzan()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePreview()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileGetuserinfo()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileQrcode()
    {
        global $_GPC, $_W;
        $id    = $_GPC['tid'];
        $users = pdo_fetch("SELECT * FROM " . tablename($this->table_users) . " WHERE id = :id", array(
            ':id' => $id
        ));
        if ($users['ewm'] && file_exists($users['ewm'])) {
            $fmdata = array(
                "success" => 1,
                "linkurl" => $users['ewm'],
                "msg" => '生成成功'
            );
        } else {
            load()->func('file');
            file_delete($users['ewm']);
            $ewmurl = $this->fm_qrcode($_GPC['url'], $users['tfrom_user'], $id, $users['avatar']);
            if ($ewmurl) {
                pdo_update($this->table_users, array(
                    'ewm' => $ewmurl
                ), array(
                    'id' => $id
                ));
                $fmdata = array(
                    "success" => 1,
                    "linkurl" => $ewmurl,
                    "msg" => '生成成功'
                );
            } else {
                $fmdata = array(
                    "success" => -1,
                    "msg" => '生成失败'
                );
            }
        }
        echo json_encode($fmdata);
        exit;
    }
    function dwz($url)
    {
        load()->func('communication');
        $dc = ihttp_post('http://dwz.cn/create.php', array(
            'url' => $url
        ));
        $t  = @json_decode($dc['content'], true);
        return $t['tinyurl'];
    }
    function get_share($uniacid, $rid, $from_user, $title)
    {
        if (!empty($rid)) {
            $reply     = pdo_fetch("SELECT xuninum,hits FROM " . tablename($this->table_reply_display) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $csrs      = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_users) . " WHERE rid= " . $rid . "");
            $listtotal = $csrs + $reply['hits'] + pdo_fetchcolumn("SELECT sum(hits) FROM " . tablename($this->table_users) . " WHERE rid= " . $rid . "") + pdo_fetchcolumn("SELECT sum(xnhits) FROM " . tablename($this->table_users) . " WHERE rid= " . $rid . "") + $reply['xuninum'];
            $ljtp      = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_log) . " WHERE rid= " . $rid . "") + pdo_fetchcolumn("SELECT sum(xnphotosnum) FROM " . tablename($this->table_users) . " WHERE rid= " . $rid . "");
        }
        if (!empty($from_user)) {
            $userinfo = pdo_fetch("SELECT uid, nickname,realname FROM " . tablename($this->table_users) . " WHERE uniacid= :uniacid AND rid= :rid AND from_user= :from_user", array(
                ':uniacid' => $uniacid,
                ':rid' => $rid,
                ':from_user' => $from_user
            ));
            $nickname = empty($userinfo['realname']) ? $userinfo['nickname'] : $userinfo['realname'];
            $userid   = $userinfo['uid'];
        }
        $str    = array(
            '#编号#' => $userid,
            '#参赛人数#' => $csrs,
            '#参与人数#' => $listtotal,
            '#参与人名#' => $nickname,
            '#累计票数#' => $ljtp
        );
        $result = strtr($title, $str);
        return $result;
    }
    public function stopip($rid, $uniacid, $from_user, $mineip, $do, $ipturl = '0', $limitip = '2')
    {
        $starttime = mktime(0, 0, 0);
        $endtime   = mktime(23, 59, 59);
        $times     = '';
        $times .= ' AND createtime >=' . $starttime;
        $times .= ' AND createtime <=' . $endtime;
        $iplist  = pdo_fetchall('SELECT * FROM ' . tablename($this->table_iplist) . ' WHERE uniacid= :uniacid  AND  rid= :rid order by `createtime` desc ', array(
            ':uniacid' => $uniacid,
            ':rid' => $rid
        ));
        $totalip = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_log) . ' WHERE uniacid= :uniacid  AND rid= :rid AND ip = :ip ' . $times . '  order by `ip` desc ', array(
            ':uniacid' => $uniacid,
            ':rid' => $rid,
            ':ip' => $mineip
        ));
        if ($totalip > $limitip && $ipturl == 1) {
            $ipurl = $_W['siteroot'] . $this->createMobileUrl('stopip', array(
                'from_user' => $from_user,
                'rid' => $rid
            ));
            header("location:$ipurl");
            exit();
        }
        $totalip = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_log) . ' WHERE uniacid= :uniacid  AND rid= :rid AND ip = :ip  ' . $times . ' order by `ip` desc ', array(
            ':uniacid' => $uniacid,
            ':rid' => $rid,
            ':ip' => $mineip
        ));
        $mineipz = sprintf("%u", ip2long($mineip));
        foreach ($iplist as $i) {
            $iparrs  = iunserializer($i['iparr']);
            $ipstart = sprintf("%u", ip2long($iparrs['ipstart']));
            $ipend   = sprintf("%u", ip2long($iparrs['ipend']));
            if ($mineipz >= $ipstart && $mineipz <= $ipend) {
                $ipdate          = array(
                    'rid' => $rid,
                    'uniacid' => $uniacid,
                    'avatar' => $avatar,
                    'nickname' => $nickname,
                    'from_user' => $from_user,
                    'ip' => $mineip,
                    'hitym' => $do,
                    'createtime' => time()
                );
                $ipdate['iparr'] = getiparr($ipdate['ip']);
                pdo_insert($this->table_iplistlog, $ipdate);
                if ($ipturl == 1) {
                    $ipurl = $_W['siteroot'] . $this->createMobileUrl('stopip', array(
                        'from_user' => $from_user,
                        'rid' => $rid
                    ));
                    header("location:$ipurl");
                    exit();
                }
                break;
            }
        }
    }
    private function FM_checkoauth()
    {
        global $_GPC, $_W;
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        load()->model('mc');
        $openid   = '';
        $nickname = '';
        $avatar   = '';
        $follow   = '';
        if (!empty($_W['member']['uid'])) {
            $member = mc_fetch(intval($_W['member']['uid']), array(
                'avatar',
                'nickname'
            ));
            if (!empty($member)) {
                $avatar   = $member['avatar'];
                $nickname = $member['nickname'];
            }
        }
        if (empty($avatar) || empty($nickname)) {
            $fan = mc_fansinfo($_W['openid']);
            if (!empty($fan)) {
                $avatar   = $fan['avatar'];
                $nickname = $fan['nickname'];
                $openid   = $fan['openid'];
                $follow   = $fan['follow'];
            }
        }
        if (empty($avatar) || empty($nickname) || empty($openid) || empty($follow)) {
            $userinfo = mc_oauth_userinfo();
            if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['avatar'])) {
                $avatar = $userinfo['avatar'];
            }
            if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['nickname'])) {
                $nickname = $userinfo['nickname'];
            }
            if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['openid'])) {
                $openid = $userinfo['openid'];
            }
            if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['follow'])) {
                $follow = $userinfo['follow'];
            }
        }
        if ((empty($avatar) || empty($nickname)) && !empty($_W['member']['uid'])) {
        }
        $oauthuser              = array();
        $oauthuser['avatar']    = $avatar;
        $oauthuser['nickname']  = $nickname;
        $oauthuser['from_user'] = $openid;
        $oauthuser['follow']    = !empty($follow) ? $follow : $_W['fans']['follow'];
        return $oauthuser;
    }
    function downloadImage($mediaid, $filename)
    {
        global $_W;
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        load()->func('file');
        $access_token = WeAccount::token();
        $url          = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
        $fileInfo     = $this->downloadWeixinFile($url);
        $updir        = '../attachment/images/' . $uniacid . '/' . date("Y") . '/' . date("m") . '/';
        if (!is_dir($updir)) {
            mkdirs($updir);
        }
        $filename = $updir . $filename . ".jpg";
        $this->saveWeixinFile($filename, $fileInfo["body"]);
        return $filename;
    }
    function downloadVoice($mediaid, $filename, $savetype = 0)
    {
        global $_W;
        load()->func('file');
        $uniacid      = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        $access_token = WeAccount::token();
        $url          = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
        $fileInfo     = $this->downloadWeixinFile($url);
        $updir        = '../attachment/audios/' . $uniacid . '/' . date("Y") . '/' . date("m") . '/';
        if (!is_dir($updir)) {
            mkdirs($updir);
        }
        $filename = $updir . $filename . ".amr";
        $this->saveWeixinFile($filename, $fileInfo["body"]);
        if ($savetype == 1) {
            return $qimedia;
        } else {
            return $filename;
        }
    }
    function downloadThumb($mediaid, $filename)
    {
        global $_W;
        load()->func('file');
        $uniacid      = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        $access_token = WeAccount::token();
        $url          = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
        $fileInfo     = $this->downloadWeixinFile($url);
        $updir        = '../attachment/images/' . $uniacid . '/' . date("Y") . '/' . date("m") . '/';
        if (!is_dir($updir)) {
            mkdirs($updir);
        }
        $filename = $updir . $filename . ".jpg";
        $this->saveWeixinFile($filename, $fileInfo["body"]);
        return $filename;
    }
    function downloadWeixinFile($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package  = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        $imageAll = array_merge(array(
            'header' => $httpinfo
        ), array(
            'body' => $package
        ));
        return $imageAll;
    }
    function saveWeixinFile($filename, $filecontent)
    {
        $local_file = fopen($filename, 'w');
        if (false !== $local_file) {
            if (false !== fwrite($local_file, $filecontent)) {
                fclose($local_file);
            }
        }
    }
    function getpicarr($uniacid, $rid, $from_user, $isfm = 0)
    {
        if ($isfm == 1) {
            $photo = pdo_fetch("SELECT photos FROM " . tablename($this->table_users_picarr) . " WHERE uniacid = :uniacid AND from_user = :from_user AND rid = :rid AND isfm = :isfm LIMIT 1", array(
                ':uniacid' => $uniacid,
                ':from_user' => $from_user,
                ':rid' => $rid,
                ':isfm' => $isfm
            ));
        } else {
            $photo = pdo_fetch("SELECT photos FROM " . tablename($this->table_users_picarr) . " WHERE uniacid = :uniacid AND from_user = :from_user AND rid = :rid ORDER BY createtime DESC LIMIT 1", array(
                ':uniacid' => $uniacid,
                ':from_user' => $from_user,
                ':rid' => $rid
            ));
        }
        return $photo;
    }
	public function fm_qrcode($value='http://012wz.com', $filename='',$pathname='', $logo, $scqrcode = array('errorCorrectionLevel' => 'H', 'matrixPointSize' => '4', 'margin' => '5')) {	
        global $_W;
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        require_once '../framework/library/qrcode/phpqrcode.php';
        load()->func('file');
        $filename = empty($filename) ? date("YmdHis") . '' . random(10) : date("YmdHis") . '' . random(istrlen($filename));
        if (!empty($pathname)) {
            $dfileurl = 'attachment/images/' . $uniacid . '/qrcode/cache/' . date("Ymd") . '/' . $pathname;
            $fileurl  = '../' . $dfileurl;
        } else {
            $dfileurl = 'attachment/images/' . $uniacid . '/qrcode/cache/' . date("Ymd");
            $fileurl  = '../' . $dfileurl;
        }
        mkdirs($fileurl);
        $fileurl = empty($pathname) ? $fileurl . '/' . $filename . '.png' : $fileurl . '/' . $filename . '.png';
        QRcode::png($value, $fileurl, $scqrcode['errorCorrectionLevel'], $scqrcode['matrixPointSize'], $scqrcode['margin']);
        $dlogo = $_W['attachurl'] . 'headimg_' . $uniacid . '.jpg?uniacid=' . $uniacid;
        if (!$logo) {
            $logo = toimage($dlogo);
        }
        $QR = $_W['siteroot'] . $dfileurl . '/' . $filename . '.png';
        if ($logo !== FALSE) {
            $QR             = imagecreatefromstring(file_get_contents($QR));
            $logo           = imagecreatefromstring(file_get_contents($logo));
            $QR_width       = imagesx($QR);
            $QR_height      = imagesy($QR);
            $logo_width     = imagesx($logo);
            $logo_height    = imagesy($logo);
            $logo_qr_width  = $QR_width / 5;
            $scale          = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width     = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        if (!empty($pathname)) {
            $dfileurllogo = 'attachment/images/' . $uniacid . '/qrcode/fm_qrcode/' . date("Ymd") . '/' . $pathname;
            $fileurllogo  = '../' . $dfileurllogo;
        } else {
            $dfileurllogo = 'attachment/images/' . $uniacid . '/qrcode/fm_qrcode';
            $fileurllogo  = '../' . $dfileurllogo;
        }
        mkdirs($fileurllogo);
        $fileurllogo = empty($pathname) ? $fileurllogo . '/' . $filename . '_logo.png' : $fileurllogo . '/' . $filename . '_logo.png';
        imagepng($QR, $fileurllogo);
        return $fileurllogo;
    }
    public function doWebsendMobileQfMsg()
    {
        global $_GPC, $_W;
        $groupid = $_GPC['gid'];
        $id      = $_GPC['id'];
        $rid     = $_GPC['rid'];
        $url     = urldecode($_GPC['url']);
        $uniacid = $_W['uniacid'];
        if (!empty($groupid) || $groupid <> 0) {
            $w = " AND id = '{$groupid}'";
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
        $a      = $item = pdo_fetch("SELECT * FROM " . tablename('site_article') . " WHERE id = :id", array(
            ':id' => $id
        ));
        if ($groupid == -1) {
            $userinfo = pdo_fetchall("SELECT openid FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY updatetime DESC, fanid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . " WHERE uniacid = '{$_W['uniacid']}'");
        } elseif ($groupid == -2) {
            $userinfo = pdo_fetchall("SELECT from_user FROM " . tablename('fm_photosvote_provevote') . " WHERE uniacid = '{$_W['uniacid']}' AND rid = '{$rid}' ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_photosvote_provevote') . " WHERE uniacid = '{$_W['uniacid']}' AND rid = '{$rid}' ");
        } elseif ($groupid == -3) {
            $userinfo = pdo_fetchall("SELECT distinct(from_user) FROM " . tablename('fm_photosvote_votelog') . " WHERE uniacid = '{$_W['uniacid']}' AND rid = '{$rid}'  ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_photosvote_votelog') . " WHERE uniacid = '{$_W['uniacid']}' AND rid = '{$rid}' ");
        } else {
            $userinfo = pdo_fetchall("SELECT openid FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY updatetime DESC, fanid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . " WHERE uniacid = '{$_W['uniacid']}'");
        }
        $pager        = pagination($total, $pindex, $psize);
        $fmqftemplate = pdo_fetch("SELECT fmqftemplate FROM " . tablename($this->table_reply_huihua) . " WHERE rid = :rid LIMIT 1", array(
            ':rid' => $rid
        ));
        foreach ($userinfo as $mid => $u) {
            if (empty($u['from_user'])) {
                $from_user = $u['openid'];
            } else {
                $from_user = $u['from_user'];
            }
            include 'mtemplate/fmqf.php';
            if (!empty($template_id)) {
                $this->sendtempmsg($template_id, $url, $data, '#FF0000', $from_user);
            }
            if (($psize - 1) == $mid) {
                $mq   = round((($pindex - 1) * $psize / $total) * 100);
                $msg  = '正在发送，目前：<strong style="color:#5cb85c">' . $mq . ' %</strong>';
                $page = $pindex + 1;
                $to   = $this->createWebUrl('sendMobileQfMsg', array(
                    'gid' => $groupid,
                    'rid' => $rid,
                    'id' => $id,
                    'url' => $url,
                    'page' => $page
                ));
                message($msg, $to);
            }
        }
        message('发送成功！', $this->createWebUrl('fmqf', array(
            'rid' => $rid
        )));
    }
    private function sendMobileRegMsg($from_user, $rid, $uniacid)
    {
        global $_GPC, $_W;
        $reply    = pdo_fetch("SELECT regmessagetemplate FROM " . tablename($this->table_reply_huihua) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $userinfo = pdo_fetch("SELECT * FROM " . tablename($this->table_users) . " WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(
            ':uniacid' => $uniacid,
            ':from_user' => $from_user,
            ':rid' => $rid
        ));
        include 'mtemplate/regvote.php';
        $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('tuser', array(
            'rid' => $rid,
            'from_user' => $from_user,
            'tfrom_user' => $from_user
        ));
        if (!empty($template_id)) {
            $this->sendtempmsg($template_id, $url, $data, '#FF0000', $from_user);
        }
    }
    private function sendMobileVoteMsg($tuservote, $tousers, $template_id = '')
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $rid     = $tuservote['rid'];
        $reply   = pdo_fetch("SELECT title, start_time,templates FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $u       = pdo_fetch("SELECT uid,realname, nickname, from_user, photosnum, xnphotosnum FROM " . tablename($this->table_users) . " WHERE uniacid = :uniacid  AND from_user = :from_user AND rid = :rid", array(
            ':uniacid' => $uniacid,
            ':from_user' => $tuservote['tfrom_user'],
            ':rid' => $rid
        ));
        include 'mtemplate/vote.php';
        if ($reply['templates'] == 'stylebase') {
            $tdo = 'photosvote';
        } else {
            $tdo = 'tuser';
        }
        $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($tdo, array(
            'rid' => $rid,
            'from_user' => $tousers,
            'tfrom_user' => $tuservote['tfrom_user']
        ));
        if (!empty($template_id)) {
            $this->sendtempmsg($template_id, $url, $data, '#FF0000', $tousers);
        }
        include 'mtemplate/tvote.php';
        $turl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('paihang', array(
            'rid' => $rid,
            'votelog' => '1',
            'tfrom_user' => $tuservote['tfrom_user']
        ));
        if (!empty($template_id)) {
            $this->sendtempmsg($template_id, $turl, $tdata, '#FF0000', $tuservote['tfrom_user']);
        }
    }
    private function sendMobileHsMsg($from_user, $rid, $uniacid)
    {
        global $_GPC, $_W;
        $reply    = pdo_fetch("SELECT title FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $replyhh  = pdo_fetch("SELECT shmessagetemplate FROM " . tablename($this->table_reply_huihua) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $userinfo = pdo_fetch("SELECT * FROM " . tablename($this->table_users) . " WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(
            ':uniacid' => $uniacid,
            ':from_user' => $from_user,
            ':rid' => $rid
        ));
        include 'mtemplate/shenhe.php';
        $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('tuser', array(
            'rid' => $rid,
            'from_user' => $from_user,
            'tfrom_user' => $from_user
        ));
        if (!empty($template_id)) {
            $this->sendtempmsg($template_id, $url, $data, '#FF0000', $from_user);
        }
    }
    private function sendMobileMsgtx($from_user, $rid, $uniacid)
    {
        global $_GPC, $_W;
        $msgtemplate = pdo_fetch("SELECT msgtemplate FROM " . tablename($this->table_reply_huihua) . " WHERE rid = :rid LIMIT 1", array(
            ':rid' => $rid
        ));
        $msgs        = pdo_fetch('SELECT from_user,tfrom_user, content,createtime FROM ' . tablename($this->table_bbsreply) . ' WHERE uniacid= :uniacid and rid = :rid AND from_user = :from_user  LIMIT 1', array(
            ':uniacid' => $uniacid,
            ':rid' => $rid,
            ':from_user' => $from_user
        ));
        include 'mtemplate/msg.php';
        $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('comment', array(
            'rid' => $rid,
            'tfrom_user' => $msgs['tfrom_user']
        ));
        if (!empty($template_id)) {
            $this->sendtempmsg($template_id, $url, $data, '#FF0000', $msgs['tfrom_user']);
        }
    }
    public function sendtempmsg($template_id, $url, $data, $topcolor, $tousers = '')
    {
        $access_token = WeAccount::token();
        if (empty($access_token)) {
            return;
        }
        $postarr = '{"touser":"' . $tousers . '","template_id":"' . $template_id . '","url":"' . $url . '","topcolor":"' . $topcolor . '","data":' . $data . '}';
        $res     = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token, $postarr);
        return true;
    }
    public function doMobileoauth2()
    {
        global $_GPC, $_W;
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        $rid     = $_GPC['rid'];
        load()->func('communication');
        $fromuser  = $_GPC['fromuser'];
        $putonghao = $_GPC['putonghao'];
        $serverapp = $_W['account']['level'];
        $cfg       = $this->module['config'];
        if ($serverapp == 4) {
            $appid  = $_W['account']['key'];
            $secret = $_W['account']['secret'];
        } else {
            $appid  = $cfg['appid'];
            $secret = $cfg['secret'];
        }
        if ($_GPC['code'] == "authdeny") {
            $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('oauth2shouquan', array(
                'rid' => $rid
            ));
            header("location:$url");
            exit;
        }
        if ($cfg['oauthtype'] == 2) {
            load()->func('communication');
            $d  = base64_decode("aHR0cDovL2FwaS5mbW9vbnMuY29tL2luZGV4LnBocD8md2VidXJsPQ==") . $_SERVER['HTTP_HOST'] . "&visitorsip=" . $_W['clientip'] . "&modules=fm_openoauth";
            $dc = ihttp_get($d);
            $t  = @json_decode($dc['content'], true);
            if ($t['config']) {
                if (isset($_GPC['code'])) {
                    $code        = $_GPC['code'];
                    $grant_type  = 'authorization_code';
                    $oauth2_code = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret . '&code=' . $code . '&grant_type=' . $grant_type . '';
                    $content     = ihttp_get($oauth2_code);
                    $token       = @json_decode($content['content'], true);
                    if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
                        echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
                        exit;
                    }
                    $openid       = $token['openid'];
                    $access_token = $token['access_token'];
                    $oauth2_url   = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
                    $content      = ihttp_get($oauth2_url);
                    $info         = @json_decode($content['content'], true);
                    if (empty($info) || !is_array($info) || empty($info['openid']) || empty($info['nickname'])) {
                        echo '<h1>获取微信公众号授权失败[无法取得info], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
                        exit;
                    }
                    $unionid    = $token['unionid'];
                    $realopenid = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = '{$uniacid}' AND `unionid` = '{$unionid}'");
                    if (!empty($realopenid)) {
                        $openid = $realopenid['openid'];
                        $follow = $realopenid['follow'];
                        setcookie('user_oauth2_follow', $follow, time() + 3600 * 24 * 7);
                        setcookie('user_oauth2_unionid', $unionid, time() + 3600 * 24 * 7);
                    }
                    $avatar   = $info['headimgurl'];
                    $nickname = $info['nickname'];
                    $sex      = $info['sex'];
                    setcookie('user_oauth2_avatar', $avatar, time() + 3600 * 24 * 7);
                    setcookie('user_oauth2_nickname', $nickname, time() + 3600 * 24 * 7);
                    setcookie('user_oauth2_sex', $sex, time() + 3600 * 24 * 7);
                    setcookie('user_oauth2_openid', $openid, time() + 3600 * 24 * 7);
                    if (!empty($fromuser) && !isset($_COOKIE["user_fromuser_openid"])) {
                        setcookie("user_fromuser_openid", $fromuser, time() + 3600 * 24 * 7 * 30);
                    }
                    if (!empty($putonghao) && !isset($_COOKIE["user_putonghao_openid"])) {
                        setcookie("user_putonghao_openid", $putonghao, time() + 3600 * 24 * 7);
                    }
                    if ($fromuser && $_GPC['duli']) {
                        $photosvoteviewurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('shareuserdata', array(
                            'rid' => $rid,
                            'fromuser' => $fromuser,
                            'duli' => $_GPC['duli'],
                            'tfrom_user' => $_GPC['tfrom_user']
                        ));
                    } else {
                        $photosvoteviewurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('photosvote', array(
                            'rid' => $rid,
                            'from_user' => $openid
                        ));
                    }
                    header("location:$photosvoteviewurl");
                    exit;
                } else {
                    echo '<h1>不是高级认证号或网页授权域名设置出错!</h1>';
                    exit;
                }
            } else {
                $msg = $t['modulesname'] . $t['m'];
                message($msg);
                exit;
            }
        } else {
            if (isset($_GPC['code'])) {
                $code        = $_GPC['code'];
                $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code";
                $content     = ihttp_get($oauth2_code);
                $token       = @json_decode($content['content'], true);
                if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
                    echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
                    exit;
                }
                $openid       = $token['openid'];
                $access_token = $token['access_token'];
                $oauth2_url   = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
                $content      = ihttp_get($oauth2_url);
                $info         = @json_decode($content['content'], true);
                if (empty($info) || !is_array($info) || empty($info['openid']) || empty($info['nickname'])) {
                    echo '<h1>获取微信公众号授权失败[无法取得info], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
                    exit;
                }
                $avatar   = $info['headimgurl'];
                $nickname = $info['nickname'];
                $sex      = $info['sex'];
                setcookie('user_oauth2_avatar', $avatar, time() + 3600 * 24 * 7);
                setcookie('user_oauth2_nickname', $nickname, time() + 3600 * 24 * 7);
                setcookie('user_oauth2_sex', $sex, time() + 3600 * 24 * 7);
                setcookie('user_oauth2_openid', $openid, time() + 3600 * 24 * 7);
                if (!empty($fromuser) && !isset($_COOKIE["user_fromuser_openid"])) {
                    setcookie("user_fromuser_openid", $fromuser, time() + 3600 * 24 * 7 * 30);
                }
                if (!empty($putonghao) && !isset($_COOKIE["user_putonghao_openid"])) {
                    setcookie("user_putonghao_openid", $putonghao, time() + 3600 * 24 * 7);
                }
                if ($fromuser && $_GPC['duli']) {
                    $photosvoteviewurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('shareuserdata', array(
                        'rid' => $rid,
                        'fromuser' => $fromuser,
                        'duli' => $_GPC['duli'],
                        'tfrom_user' => $_GPC['tfrom_user']
                    ));
                } else {
                    $photosvoteviewurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('photosvote', array(
                        'rid' => $rid,
                        'from_user' => $openid
                    ));
                }
                header("location:$photosvoteviewurl");
                exit;
            } else {
                echo '<h1>不是高级认证号或网页授权域名设置出错!</h1>';
                exit;
            }
        }
    }
    public function doMobileoauth2shouquan()
    {
        global $_GPC, $_W;
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        $rid     = $_GPC['rid'];
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT shareurl FROM " . tablename($this->table_share) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $url   = $reply['shareurl'];
            header("location:$url");
            exit;
        }
    }
    private function checkoauth2($rid, $oauthopenid, $oauthopenid = '', $fromuser = '')
    {
        global $_W;
        load()->model('account');
        $uniacid   = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        $serverapp = $_W['account']['level'];
        if ($serverapp == 4) {
            $appid  = $_W['account']['key'];
            $secret = $_W['account']['secret'];
        } else {
            $cfg    = $this->module['config'];
            $appid  = $cfg['appid'];
            $secret = $cfg['secret'];
        }
        if (empty($oauthopenid) || empty($_COOKIE["user_oauth2_unionid"])) {
            if ($serverapp == 4) {
                $appid       = $_W['account']['key'];
                $url         = $_W['siteroot'] . 'app/' . $this->createMobileUrl('oauth2', array(
                    'rid' => $rid,
                    'fromuser' => $fromuser
                ));
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
                header("location:$oauth2_code");
                exit;
            } else {
                if (!empty($appid)) {
                    $url         = $_W['siteroot'] . 'app/' . $this->createMobileUrl('oauth2', array(
                        'rid' => $rid,
                        'fromuser' => $fromuser
                    ));
                    $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
                    header("location:$oauth2_code");
                    exit;
                } else {
                    $reguser = $_W['siteroot'] . 'app/' . $this->createMobileUrl('reguser', array(
                        'rid' => $rid
                    ));
                    header("location:$reguser");
                    exit;
                }
            }
        }
    }
    public function doWebIndex()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebDeleteAll()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebDelete()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebMembers()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebDeletefans()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebDeletemsg()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebDeletevote()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebProvevote()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebupaudios()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebupimages()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebAddProvevote()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebVotelog()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebMessage()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebFmqf()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebAnnounce()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebAddMessage()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebIplist()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebdeletealllog()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebdeleteallmessage()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebRankinglist()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebstatus()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebBanner()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebAdv()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebGetunionid()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebTemplates()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebTags()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebSettuijian()
    {
        global $_GPC, $_W;
        $id   = intval($_GPC['id']);
        $data = intval($_GPC['data']);
        $type = $_GPC['type'];
        if (in_array($type, array(
            'tuijian'
        ))) {
            $data = ($data == 1 ? '0' : '1');
            pdo_update($this->table_users, array(
                'istuijian' => $data
            ), array(
                "id" => $id,
                "uniacid" => $_W['uniacid']
            ));
            die(json_encode(array(
                'result' => 1,
                'data' => $data
            )));
        }
        if (in_array($type, array(
            'limitsd'
        ))) {
            pdo_update($this->table_users, array(
                'limitsd' => $data
            ), array(
                "id" => $id,
                "uniacid" => $_W['uniacid']
            ));
            die(json_encode(array(
                'result' => 1,
                'data' => $data
            )));
        }
        die(json_encode(array(
            'result' => 0
        )));
    }
    public function getData($page)
    {
        global $_W;
        if (!empty($page['datas'])) {
            $data     = htmlspecialchars_decode($page['datas']);
            $d        = json_decode($data, true);
            $usersids = array();
            foreach ($d as $k1 => &$dd) {
                if ($dd['temp'] == 'photosvote') {
                    foreach ($dd['data'] as $k2 => $ddd) {
                        $usersids[] = array(
                            'id' => $ddd['usersid'],
                            'k1' => $k1,
                            'k2' => $k2
                        );
                    }
                } elseif ($dd['temp'] == 'richtext') {
                    $dd['content'] = $this->unescape($dd['content']);
                }
            }
            unset($dd);
            $arr = array();
            foreach ($usersids as $a) {
                $arr[] = $a['id'];
            }
            if (count($arr) > 0) {
                $usersinfo = pdo_fetchall("SELECT id,rid,from_user,nickname,realname,uid,avatar,photosnum,hits,xnphotosnum,xnhits,sharenum FROM " . tablename($this->table_users) . " WHERE id in ( " . implode(',', $arr) . ") AND uniacid= :uniacid AND status=:status AND rid =:rid ORDER BY uid ASC", array(
                    ':uniacid' => $_W['uniacid'],
                    ':status' => '1',
                    ':rid' => 34
                ), 'id');
                $usersinfo = $this->set_medias($usersinfo, 'avatar');
                foreach ($d as $k1 => &$dd) {
                    if ($dd['temp'] == 'photosvote') {
                        foreach ($dd['data'] as $k2 => &$ddd) {
                            $cdata            = $usersinfo[$ddd['usersid']];
                            $ddd['name']      = !empty($cdata['nickname']) ? $cdata['nickname'] : $cdata['realname'];
                            $ddd['uid']       = $cdata['uid'];
                            $ddd['from_user'] = $cdata['from_user'];
                            $ddd['piaoshu']   = $cdata['photosnum'] + $cdata['xnphotosnum'];
                            $ddd['img']       = $cdata['avatar'];
                            $ddd['renqi']     = $cdata['hits'] + $cdata['xnhits'];
                            $ddd['sharenum']  = $cdata['sharenum'];
                        }
                        unset($ddd);
                    }
                }
                unset($dd);
            }
            $data = json_encode($d);
            $data = rtrim($data, "]");
            $data = ltrim($data, "[");
        }
        $pageinfo     = htmlspecialchars_decode($page['pageinfo']);
        $p            = json_decode($pageinfo, true);
        $page_title   = empty($p[0]['params']['title']) ? "未设置页面标题" : $p[0]['params']['title'];
        $page_desc    = empty($p[0]['params']['desc']) ? "未设置页面简介" : $p[0]['params']['desc'];
        $page_img     = empty($p[0]['params']['img']) ? "" : tomedia($p[0]['params']['img']);
        $page_keyword = empty($p[0]['params']['kw']) ? "" : $p[0]['params']['kw'];
        $shopset      = array(
            'name' => '女神来了',
            'logo' => '11'
        );
        $users        = $this->getMember($from_user);
        $system       = array(
            'tusertop' => array(
                'name' => $users['realname'],
                'logo' => tomedia($users['avatar'])
            )
        );
        $system       = json_encode($system);
        return array(
            'page' => $page,
            'data' => $data,
            'share' => array(
                'title' => $page_title,
                'desc' => $page_desc,
                'imgUrl' => $page_img
            ),
            'footermenu' => intval($p[0]['params']['footer']),
            'system' => $system
        );
    }
    public function unescape($str)
    {
        $ret = '';
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            if ($str[$i] == '%' && $str[$i + 1] == 'u') {
                $val = hexdec(substr($str, $i + 2, 4));
                if ($val < 0x7f)
                    $ret .= chr($val);
                else if ($val < 0x800)
                    $ret .= chr(0xc0 | ($val >> 6)) . chr(0x80 | ($val & 0x3f));
                else
                    $ret .= chr(0xe0 | ($val >> 12)) . chr(0x80 | (($val >> 6) & 0x3f)) . chr(0x80 | ($val & 0x3f));
                $i += 5;
            } else if ($str[$i] == '%') {
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            } else
                $ret .= $str[$i];
        }
        return $ret;
    }
    function is_array2($array)
    {
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                return is_array($v);
            }
            return false;
        }
        return false;
    }
    function set_medias($list = array(), $fields = null)
    {
        if (empty($fields)) {
            foreach ($list as &$row) {
                $row = tomedia($row);
            }
            return $list;
        }
        if (!is_array($fields)) {
            $fields = explode(',', $fields);
        }
        if ($this->is_array2($list)) {
            foreach ($list as $key => &$value) {
                foreach ($fields as $field) {
                    if (is_array($value) && isset($value[$field])) {
                        $value[$field] = tomedia($value[$field]);
                    }
                }
            }
            return $list;
        } else {
            foreach ($fields as $field) {
                if (isset($list[$field])) {
                    $list[$field] = tomedia($list[$field]);
                }
            }
            return $list;
        }
    }
    public function _getip($rid, $ip, $uniacid = '')
    {
        global $_GPC, $_W;
        if (empty($uniacid)) {
            $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        }
        $iparrs = pdo_fetch("SELECT iparr FROM " . tablename($this->table_log) . " WHERE uniacid = :uniacid and  rid = :rid and ip = :ip ", array(
            ':uniacid' => $uniacid,
            ':rid' => $rid,
            ':ip' => $ip
        ));
        $iparr  = iunserializer($iparrs['iparr']);
        return $iparr;
    }
    public function _getuser($rid, $tfrom_user, $uniacid = '')
    {
        global $_GPC, $_W;
        if (empty($uniacid)) {
            $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        }
        return pdo_fetch('SELECT avatar, nickname FROM ' . tablename($this->table_users) . " WHERE uniacid = :uniacid and  rid = :rid and from_user = :tfrom_user ", array(
            ':uniacid' => $uniacid,
            ':rid' => $rid,
            ':tfrom_user' => $tfrom_user
        ));
    }
    public function getMember($from_user)
    {
        global $_GPC, $_W;
        if (empty($uniacid)) {
            $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        }
        if (empty($from_user)) {
            $from_user = 'fromUser';
        }
        return pdo_fetch('SELECT * FROM ' . tablename($this->table_users) . " WHERE uniacid = :uniacid and  from_user = :from_user ORDER BY id DESC LIMIT 1", array(
            ':uniacid' => $uniacid,
            ':from_user' => $from_user
        ));
    }
    public function _auser($rid, $afrom_user, $uniacid = '')
    {
        global $_GPC, $_W;
        load()->model('mc');
        if (empty($uniacid)) {
            $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        }
        $auser = pdo_fetch("SELECT avatar, nickname FROM " . tablename($this->table_users) . " WHERE uniacid = :uniacid and  rid = :rid and from_user = :afrom_user ", array(
            ':uniacid' => $uniacid,
            ':rid' => $rid,
            ':afrom_user' => $afrom_user
        ));
        if (empty($auser)) {
            $auser = pdo_fetch("SELECT avatar, nickname FROM " . tablename($this->table_data) . " WHERE uniacid = :uniacid and  rid = :rid and from_user = :afrom_user ", array(
                ':uniacid' => $uniacid,
                ':rid' => $rid,
                ':afrom_user' => $afrom_user
            ));
            if (empty($auser)) {
                $auser = mc_fansinfo($row['afrom_user']);
            }
        }
        return $auser;
    }
    public function getsharenum($uniacid, $tfrom_user, $rid, $sharenum)
    {
        global $_W, $_GPC;
        return pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_data) . " WHERE uniacid = :uniacid and tfrom_user = :tfrom_user and rid = :rid", array(
            ':uniacid' => $uniacid,
            ':tfrom_user' => $tfrom_user,
            ':rid' => $rid
        )) + pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_data) . " WHERE uniacid = :uniacid and fromuser = :fromuser and rid = :rid", array(
            ':uniacid' => $uniacid,
            ':fromuser' => $tfrom_user,
            ':rid' => $rid
        )) + $sharenum;
    }
    public function gettagtitle($tagid, $rid)
    {
        $tags = pdo_fetch("SELECT title FROM " . tablename($this->table_tags) . " WHERE rid = :rid AND id = :id ORDER BY id DESC", array(
            ':rid' => $rid,
            ':id' => $tagid
        ));
        return $tags['title'];
    }
    public function getphotos($photo, $avatar, $picture, $is = '')
    {
        if ($is) {
            if (!empty($avatar)) {
                $photos = toimage($avatar);
            } elseif (!empty($photo)) {
                $photos = toimage($photo);
            } else {
                $photos = toimage($picture);
            }
        } else {
            if (!empty($photo)) {
                $photos = toimage($photo);
            } elseif (!empty($avatar)) {
                $photos = toimage($avatar);
            } else {
                $photos = toimage($picture);
            }
        }
        return $photos;
    }
    public function getusernames($realname, $nickname, $limit = '6', $from_user = '')
    {
        if (!empty($realname)) {
            $name = cutstr($realname, $limit);
        } elseif (!empty($nickname)) {
            $name = cutstr($nickname, $limit);
        } elseif (!empty($from_user)) {
            $name = cutstr($from_user, $limit);
        } else {
            $name = '';
        }
        return $name;
    }
    public function getcommentnum($rid, $uniacid, $tfrom_user)
    {
        $num = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_bbsreply) . " WHERE rid= " . $rid . " AND uniacid= " . $uniacid . " AND tfrom_user= '" . $tfrom_user . "' ");
        return $num;
    }
    public function getphotosnum($rid, $uniacid, $tfrom_user)
    {
        $num = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_users_picarr) . " WHERE rid= " . $rid . " AND uniacid= " . $uniacid . " AND from_user= '" . $tfrom_user . "' ");
        return $num;
    }
    public function fmvipleavel($rid, $uniacid, $tfrom_user)
    {
        $user = pdo_fetch("SELECT photosnum,xnphotosnum,hits,xnhits,yaoqingnum,zans FROM " . tablename($this->table_users) . " WHERE rid= " . $rid . " AND uniacid= " . $uniacid . " AND from_user= '" . $tfrom_user . "' ");
        if (!empty($user)) {
            $userps = $user['photosnum'] + $user['xnphotosnum'] + $user['hits'] + $user['xnhits'] + $user['yaoqingnum'] + $user['zans'];
        } else {
            $userps = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_log) . " WHERE uniacid= " . $uniacid . " AND from_user= '" . $tfrom_user . "' ");
        }
        $userps = intval($userps);
        if ($userps > 0 && $userps <= 1) {
            $level = 1;
        } elseif ($userps > 1 && $userps <= 5) {
            $level = 2;
        } elseif ($userps > 5 && $userps <= 15) {
            $level = 3;
        } elseif ($userps > 15 && $userps <= 30) {
            $level = 4;
        } elseif ($userps > 30 && $userps <= 50) {
            $level = 5;
        } elseif ($userps > 50 && $userps <= 100) {
            $level = 6;
        } elseif ($userps > 100 && $userps <= 200) {
            $level = 7;
        } elseif ($userps > 200 && $userps <= 400) {
            $level = 8;
        } elseif ($userps > 400 && $userps <= 800) {
            $level = 9;
        } elseif ($userps > 800 && $userps <= 2000) {
            $level = 10;
        } elseif ($userps > 2000 && $userps <= 3000) {
            $level = 11;
        } elseif ($userps > 3000 && $userps <= 5000) {
            $level = 12;
        } elseif ($userps > 5000 && $userps <= 8000) {
            $level = 13;
        } elseif ($userps > 8000 && $userps <= 15000) {
            $level = 14;
        } elseif ($userps > 15000 && $userps <= 30000) {
            $level = 15;
        } elseif ($userps > 30000 && $userps <= 60000) {
            $level = 16;
        } elseif ($userps > 60000 && $userps <= 100000) {
            $level = 17;
        } elseif ($userps > 100000 && $userps <= 500000) {
            $level = 18;
        }
        return $level;
    }
    public function emotion($text)
    {
        $smile_popo  = '<span class="smile_popo" style="background-position-y: ';
        $smile_popoe = 'px;display: inline-block;  width: 30px"></span>';
        $str         = array(
            '(#呵呵)' => $smile_popo . '-0' . $smile_popoe,
            '(#哈哈)' => $smile_popo . '-30' . $smile_popoe,
            '(#吐舌)' => $smile_popo . '-60' . $smile_popoe,
            '(#啊)' => $smile_popo . '-90' . $smile_popoe,
            '(#酷)' => $smile_popo . '-120' . $smile_popoe,
            '(#怒)' => $smile_popo . '-150' . $smile_popoe,
            '(#开心)' => $smile_popo . '-180' . $smile_popoe,
            '(#汗)' => $smile_popo . '-210' . $smile_popoe,
            '(#泪)' => $smile_popo . '-240' . $smile_popoe,
            '(#黑线)' => $smile_popo . '-270' . $smile_popoe,
            '(#鄙视)' => $smile_popo . '-300' . $smile_popoe,
            '(#不高兴)' => $smile_popo . '-330' . $smile_popoe,
            '(#真棒)' => $smile_popo . '-360' . $smile_popoe,
            '(#钱)' => $smile_popo . '-390' . $smile_popoe,
            '(#疑问)' => $smile_popo . '-420' . $smile_popoe,
            '(#阴险)' => $smile_popo . '-450' . $smile_popoe,
            '(#吐)' => $smile_popo . '-480' . $smile_popoe,
            '(#咦)' => $smile_popo . '-510' . $smile_popoe,
            '(#委屈)' => $smile_popo . '-540' . $smile_popoe,
            '(#花心)' => $smile_popo . '-570' . $smile_popoe,
            '(#呼~)' => $smile_popo . '-600' . $smile_popoe,
            '(#笑眼)' => $smile_popo . '-630' . $smile_popoe,
            '(#冷)' => $smile_popo . '-660' . $smile_popoe,
            '(#太开心)' => $smile_popo . '-690' . $smile_popoe,
            '(#滑稽)' => $smile_popo . '-720' . $smile_popoe,
            '(#勉强)' => $smile_popo . '-750' . $smile_popoe,
            '(#狂汗)' => $smile_popo . '-780' . $smile_popoe,
            '(#乖)' => $smile_popo . '-810' . $smile_popoe,
            '(#睡觉)' => $smile_popo . '-840' . $smile_popoe,
            '(#惊哭)' => $smile_popo . '-870' . $smile_popoe,
            '(#升起)' => $smile_popo . '-900' . $smile_popoe,
            '(#惊讶)' => $smile_popo . '-930' . $smile_popoe,
            '(#喷)' => $smile_popo . '-960' . $smile_popoe,
            '(#爱心)' => $smile_popo . '-990' . $smile_popoe,
            '(#心碎)' => $smile_popo . '-1020' . $smile_popoe,
            '(#玫瑰)' => $smile_popo . '-1050' . $smile_popoe,
            '(#礼物)' => $smile_popo . '-1080' . $smile_popoe,
            '(#彩虹)' => $smile_popo . '-1110' . $smile_popoe,
            '(#星星月亮)' => $smile_popo . '-1140' . $smile_popoe,
            '(#太阳)' => $smile_popo . '-1170' . $smile_popoe,
            '(#钱币)' => $smile_popo . '-1200' . $smile_popoe,
            '(#灯泡)' => $smile_popo . '-1230' . $smile_popoe,
            '(#茶杯)' => $smile_popo . '-1260' . $smile_popoe,
            '(#蛋糕)' => $smile_popo . '-1290' . $smile_popoe,
            '(#音乐)' => $smile_popo . '-1320' . $smile_popoe,
            '(#haha)' => $smile_popo . '-1350' . $smile_popoe,
            '(#胜利)' => $smile_popo . '-1380' . $smile_popoe,
            '(#大拇指)' => $smile_popo . '-1410' . $smile_popoe,
            '(#弱)' => $smile_popo . '-1440' . $smile_popoe,
            '(#OK)' => $smile_popo . '-1470' . $smile_popoe
        );
        $content     = strtr($text, $str);
        return $content;
    }
    public function doWebdownload()
    {
        require_once 'download.php';
    }
    public function doWebtpdownload()
    {
        require_once 'tpdownload.php';
    }
    public function doWebdownloadph()
    {
        require_once 'downloadph.php';
    }
    public function webmessage($error, $url = '', $errno = -1)
    {
        $data          = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;
        echo json_encode($data);
        exit;
    }
    public function doWebFmoth()
    {
        $this->__web(__FUNCTION__);
    }
}
if (!function_exists('paginationm')) {
    function paginationm($tcount, $pindex, $psize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => ''))
    {
        global $_W;
        $pdata = array(
            'tcount' => 0,
            'tpage' => 0,
            'cindex' => 0,
            'findex' => 0,
            'pindex' => 0,
            'nindex' => 0,
            'lindex' => 0,
            'options' => ''
        );
        if ($context['ajaxcallback']) {
            $context['isajax'] = true;
        }
        $pdata['tcount'] = $tcount;
        $pdata['tpage']  = ceil($tcount / $psize);
        if ($pdata['tpage'] <= 1) {
            return '';
        }
        $cindex          = $pindex;
        $cindex          = min($cindex, $pdata['tpage']);
        $cindex          = max($cindex, 1);
        $pdata['cindex'] = $cindex;
        $pdata['findex'] = 1;
        $pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
        $pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
        $pdata['lindex'] = $pdata['tpage'];
        if ($context['isajax']) {
            if (!$url) {
                $url = $_W['script_name'] . '?' . http_build_query($_GET);
            }
            $pdata['faa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['paa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['naa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['laa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', ' . $context['ajaxcallback'] . ')"';
        } else {
            if ($url) {
                $pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
                $pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
                $pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
                $pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
            } else {
                $_GET['page'] = $pdata['findex'];
                $pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['pindex'];
                $pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['nindex'];
                $pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['lindex'];
                $pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
            }
        }
        $html = '<div class="pagination pagination-centered"><ul class="pagination pagination-centered">';
        if ($pdata['cindex'] > 1) {
            $html .= "<li><a {$pdata['faa']} class=\"pager-nav\">首页</a></li>";
            $html .= "<li><a {$pdata['paa']} class=\"pager-nav\">&laquo;上一页</a></li>";
        }
        if (!$context['before'] && $context['before'] != 0) {
            $context['before'] = 5;
        }
        if (!$context['after'] && $context['after'] != 0) {
            $context['after'] = 4;
        }
        if ($context['after'] != 0 && $context['before'] != 0) {
            $range          = array();
            $range['start'] = max(1, $pdata['cindex'] - $context['before']);
            $range['end']   = min($pdata['tpage'], $pdata['cindex'] + $context['after']);
            if ($range['end'] - $range['start'] < $context['before'] + $context['after']) {
                $range['end']   = min($pdata['tpage'], $range['start'] + $context['before'] + $context['after']);
                $range['start'] = max(1, $range['end'] - $context['before'] - $context['after']);
            }
            for ($i = $range['start']; $i <= $range['end']; $i++) {
                if ($context['isajax']) {
                    $aa = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $i . '\', ' . $context['ajaxcallback'] . ')"';
                } else {
                    if ($url) {
                        $aa = 'href="?' . str_replace('*', $i, $url) . '"';
                    } else {
                        $_GET['page'] = $i;
                        $aa           = 'href="?' . http_build_query($_GET) . '"';
                    }
                }
                $html .= ($i == $pdata['cindex'] ? '<li class="active"><a href="javascript:;">' . $i . '</a></li>' : "<li><a {$aa}>" . $i . '</a></li>');
            }
        }
        if ($pdata['cindex'] < $pdata['tpage']) {
            $html .= "<li><a {$pdata['naa']} class=\"pager-nav\">下一页&raquo;</a></li>";
            $html .= "<li><a {$pdata['laa']} class=\"pager-nav\">尾页</a></li>";
        }
        $html .= '</ul></div>';
        return $html;
    }
}
if (!function_exists('getDistance')) {
    function getDistance($lng1, $lat1, $lng2, $lat2)
    {
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a       = $radLat1 - $radLat2;
        $b       = $radLng1 - $radLng2;
        $s       = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
        return sprintf('%.2f', $s / 1000);
    }
}
if (!function_exists('mobilelimit')) {
    function mobilelimit($mobile)
    {
        $phone  = $mobile;
        $mphone = substr($phone, 3, 4);
        $lphone = str_replace($mphone, "****", $phone);
        return $lphone;
    }
}
if (!function_exists('getrealip')) {
    function getrealip()
    {
        $unknown = 'unknown';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (false !== strpos($ip, ','))
            $ip = reset(explode(',', $ip));
        return $ip;
    }
}
if (!function_exists('GetIpLookup')) {
    function GetIpLookup($ip = '')
    {
        if (empty($ip)) {
            $ip = getip();
        }
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
        if (empty($res)) {
            return false;
        }
        $jsonMatches = array();
        preg_match('#\{.+?\}#', $res, $jsonMatches);
        if (!isset($jsonMatches[0])) {
            return false;
        }
        $json = json_decode($jsonMatches[0], true);
        if (isset($json['ret']) && $json['ret'] == 1) {
            $json['ip'] = $ip;
            unset($json['ret']);
        } else {
            return false;
        }
        return $json;
    }
}
if (!function_exists('getiparr')) {
    function getiparr($ip)
    {
        $ip    = GetIpLookup($row['ip']);
        $iparr = array();
        $iparr['country'] .= $ip['country'];
        $iparr['province'] .= $ip['province'];
        $iparr['city'] .= $ip['city'];
        $iparr['district'] .= $ip['district'];
        $iparr['ist'] .= $ip['ist'];
        $iparr = iserializer($iparr);
        return $iparr;
    }
}