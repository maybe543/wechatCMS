<?php
/**
 * 女神来了模块定义
 *
 */
defined('IN_IA') or exit('Access Denied');
class Fm_photosvoteModule extends WeModule
{
    public $title = '女神来了';
    public $table_reply = 'fm_photosvote_reply';
    public $table_reply_share = 'fm_photosvote_reply_share';
    public $table_reply_huihua = 'fm_photosvote_reply_huihua';
    public $table_reply_display = 'fm_photosvote_reply_display';
    public $table_reply_vote = 'fm_photosvote_reply_vote';
    public $table_reply_body = 'fm_photosvote_reply_body';
    public $table_users = 'fm_photosvote_provevote';
    public $table_log = 'fm_photosvote_votelog';
    public $table_bbsreply = 'fm_photosvote_bbsreply';
    public $table_banners = 'fm_photosvote_banners';
    public $table_advs = 'fm_photosvote_advs';
    public $table_data = 'fm_photosvote_data';
    public $table_announce = 'fm_photosvote_announce';
    public $table_iplist = 'fm_photosvote_iplist';
    public $table_iplistlog = 'fm_photosvote_iplistlog';
    public $table_provevote_name = 'fm_photosvote_provevote_name';
    public $table_provevote_voice = 'fm_photosvote_provevote_voice';
    public function fieldsFormDisplay($rid = 0)
    {
        global $_GPC, $_W;
        load()->func('tpl');
        load()->func('communication');
        if (!empty($rid)) {
            $basic       = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $share       = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_share) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $huihua      = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_huihua) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $display     = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_display) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $vote        = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_vote) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $body        = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_body) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $reply       = array_merge($basic, $share, $huihua, $display, $vote, $body);
            $qiniu       = iunserializer($reply['qiniu']);
            $regtitlearr = iunserializer($reply['regtitlearr']);
        }
	 //else {
           // $reply = array(
               // 'a' => 'aHR0cDovL24uZm1vb25zLmNvbS9hcGkvYXBpLnBocD8mYXBpPWFwaQ==',
               // 'author' => 'FantasyMoons Team'
          //  );
        //}
        $now                    = time();
        $reply['title']         = empty($reply['title']) ? "女神来了!" : $reply['title'];
        $reply['start_time']    = empty($reply['start_time']) ? $now : $reply['start_time'];
        $reply['end_time']      = empty($reply['end_time']) ? strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)) : $reply['end_time'];
        $reply['tstart_time']   = empty($reply['tstart_time']) ? strtotime(date("Y-m-d H:i", $now + 3 * 24 * 3600)) : $reply['tstart_time'];
        $reply['tend_time']     = empty($reply['tend_time']) ? strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)) : $reply['tend_time'];
        $reply['bstart_time']   = empty($reply['bstart_time']) ? $now : $reply['bstart_time'];
        $reply['bend_time']     = empty($reply['bend_time']) ? strtotime(date("Y-m-d H:i", $now + 3 * 24 * 3600)) : $reply['bend_time'];
        $reply['ttipstart']     = empty($reply['ttipstart']) ? "投票时间还没有开始!" : $reply['ttipstart'];
        $reply['ttipend']       = empty($reply['ttipend']) ? "投票时间已经结束!" : $reply['ttipend'];
        $reply['btipstart']     = empty($reply['btipstart']) ? "报名时间还没有开始!" : $reply['btipstart'];
        $reply['btipend']       = empty($reply['btipend']) ? "报名时间已经结束!" : $reply['btipend'];
        $reply['isbbsreply']    = !isset($reply['isbbsreply']) ? "1" : $reply['isbbsreply'];
        $reply['opensubscribe'] = !isset($reply['opensubscribe']) ? "4" : $reply['opensubscribe'];
        $reply['share_shownum'] = !isset($reply['share_shownum']) ? "50" : $reply['share_shownum'];
        $reply['picture']       = empty($reply['picture']) ? "../addons/fm_photosvote/template/images/pimages.jpg" : $reply['picture'];
        $reply['sharephoto']    = empty($reply['sharephoto']) ? "../addons/fm_photosvote/template/images/pimages.jpg" : $reply['sharephoto'];
        $reply['stopping']      = empty($reply['stopping']) ? "../addons/fm_photosvote/template/images/stopping.jpg" : $reply['stopping'];
        $reply['nostart']       = empty($reply['nostart']) ? "../addons/fm_photosvote/template/images/nostart.jpg" : $reply['nostart'];
        $reply['end']           = empty($reply['end']) ? "../addons/fm_photosvote/template/images/end.jpg" : $reply['end'];
        $reply['cqtp']          = !isset($reply['cqtp']) ? "1" : $reply['cqtp'];
        $reply['moshi']         = !isset($reply['moshi']) ? "2" : $reply['moshi'];
        $reply['tpsh']              = !isset($reply['tpsh']) ? "0" : $reply['tpsh'];
        $reply['indexorder']        = !isset($reply['indexorder']) ? "1" : $reply['indexorder'];
        $reply['indexpx']           = !isset($reply['indexpx']) ? "0" : $reply['indexpx'];
        $reply['tpxz']              = empty($reply['tpxz']) ? "5" : $reply['tpxz'];
        $reply['autolitpic']        = empty($reply['autolitpic']) ? "500" : $reply['autolitpic'];
        $reply['autozl']            = empty($reply['autozl']) ? "50" : $reply['autozl'];
        $reply['daytpxz']           = empty($reply['daytpxz']) ? "8" : $reply['daytpxz'];
        $reply['dayonetp']          = empty($reply['dayonetp']) ? "1" : $reply['dayonetp'];
        $reply['allonetp']          = empty($reply['allonetp']) ? "1" : $reply['allonetp'];
        $reply['fansmostvote']      = empty($reply['fansmostvote']) ? "1" : $reply['fansmostvote'];
        $reply['indextpxz']         = empty($reply['indextpxz']) ? "10" : $reply['indextpxz'];
        $reply['phbtpxz']           = empty($reply['phbtpxz']) ? "10" : $reply['phbtpxz'];
        $reply['userinfo']          = empty($reply['userinfo']) ? "请留下您的个人信息，谢谢!" : $reply['userinfo'];
        $reply['isindex']           = !isset($reply['isindex']) ? "1" : $reply['isindex'];
        $reply['isrealname']        = !isset($reply['isrealname']) ? "1" : $reply['isrealname'];
        $reply['ismobile']          = !isset($reply['ismobile']) ? "1" : $reply['ismobile'];
        $reply['isjob']             = !isset($reply['isjob']) ? "1" : $reply['isjob'];
        $reply['isxingqu']          = !isset($reply['isxingqu']) ? "1" : $reply['isxingqu'];
        $reply['isfans']            = !isset($reply['isfans']) ? "1" : $reply['isfans'];
        $reply['copyrighturl']      = empty($reply['copyrighturl']) ? "http://" . $_SERVER['HTTP_HOST'] : $reply['copyrighturl'];
        $reply['copyright']         = empty($reply['copyright']) ? $_W['account']['name'] : $reply['copyright'];
        $reply['xuninum']           = !isset($reply['xuninum']) ? "0" : $reply['xuninum'];
        $reply['xuninumtime']       = !isset($reply['xuninumtime']) ? "1800" : $reply['xuninumtime'];
        $reply['xuninuminitial']    = !isset($reply['xuninuminitial']) ? "1" : $reply['xuninuminitial'];
        $reply['xuninumending']     = !isset($reply['xuninumending']) ? "50" : $reply['xuninumending'];
        $reply['zbgcolor']          = empty($reply['zbgcolor']) ? "#3a0255" : $reply['zbgcolor'];
        $reply['zbg']               = empty($reply['zbg']) ? "../addons/fm_photosvote/template/mobile/photos/bg.jpg" : $reply['zbg'];
        $reply['zbgtj']             = empty($reply['zbgtj']) ? "../addons/fm_photosvote/template/mobile/photos/bg_x.png" : $reply['zbgtj'];
        $reply['lapiao']            = empty($reply['lapiao']) ? "拉票" : $reply['lapiao'];
        $reply['sharename']         = empty($reply['sharename']) ? "分享" : $reply['sharename'];
        $reply['tpname']            = empty($reply['tpname']) ? "投Ta一票" : $reply['tpname'];
        $reply['rqname']            = empty($reply['rqname']) ? "人气" : $reply['rqname'];
        $reply['tpsname']           = empty($reply['tpsname']) ? "票数" : $reply['tpsname'];
        //$reply['d']                 = base64_decode("aHR0cDovL2FwaS5mbW9vbnMuY29tL2luZGV4LnBocD8md2VidXJsPQ==") . $_SERVER['HTTP_HOST'] . "&visitorsip=" . $_W['clientip'] . "&modules=" . $_GPC['m'];
        $reply['addpvapp']          = !isset($reply['addpvapp']) ? "1" : $reply['addpvapp'];
        $reply['iscode']            = !isset($reply['iscode']) ? "0" : $reply['iscode'];
        $reply['isedes']            = !isset($reply['isedes']) ? "1" : $reply['isedes'];
        $reply['tmreply']           = !isset($reply['tmreply']) ? "1" : $reply['tmreply'];
        $reply['tmyushe']           = !isset($reply['tmyushe']) ? "1" : $reply['tmyushe'];
        $reply['isipv']             = !isset($reply['isipv']) ? "0" : $reply['isipv'];
        $reply['ipturl']            = !isset($reply['ipturl']) ? "1" : $reply['ipturl'];
        //$reply['dc']                = ihttp_get($reply['d']);
        $reply['ipstopvote']        = !isset($reply['ipstopvote']) ? "1" : $reply['ipstopvote'];
        $reply['tmoshi']            = !isset($reply['tmoshi']) ? "2" : $reply['tmoshi'];
        $reply['mediatype']         = !isset($reply['mediatype']) ? "1" : $reply['mediatype'];
        $reply['mediatypem']        = !isset($reply['mediatypem']) ? "0" : $reply['mediatypem'];
        $reply['mediatypev']        = !isset($reply['mediatypev']) ? "0" : $reply['mediatypev'];
        $reply['votesuccess']       = empty($reply['votesuccess']) ? "恭喜您成功的为编号为：#编号# ,姓名为： #参赛人名# 的参赛者投了一票！" : $reply['votesuccess'];
        $reply['subscribedes']      = empty($reply['subscribedes']) ? "请长按二维码关注或点击“关注投票”，前往" . $_W['account']['name'] . "为您的好友投票。如已关注，请关闭此对话框，进入视频为Ta点赞或拉票。" : $reply['subscribedes'];
        $reply['csrs']              = empty($reply['csrs']) ? "参赛人数" : $reply['csrs'];
        $reply['ljtp']              = empty($reply['ljtp']) ? "累计投票" : $reply['ljtp'];
        $reply['cyrs']              = empty($reply['cyrs']) ? "参与人数" : $reply['cyrs'];
        $reply['voicebg']           = empty($reply['voicebg']) ? "../addons/fm_photosvote/template/mobile/audio/t1/images/voicebg.jpg" : $reply['voicebg'];
        $reply['yuming']            = explode('.', $_SERVER['HTTP_HOST']);
        $reply['isdaojishi']        = !isset($reply['isdaojishi']) ? "0" : $reply['isdaojishi'];
        $reply['ttipvote']          = empty($reply['ttipvote']) ? "你的投票时间已经结束" : $reply['ttipvote'];
        $reply['cyrs']              = empty($reply['cyrs']) ? "参与人数" : $reply['cyrs'];
        $reply['limitip']           = empty($reply['limitip']) ? "10" : $reply['limitip'];
        $reply['votetime']          = empty($reply['votetime']) ? "10" : $reply['votetime'];
        $reply['iplocaldes']        = empty($reply['iplocaldes']) ? "你所在的地区不在本次投票地区。本次投票地区： #限制地区#" : $reply['iplocaldes'];
        $reply['zanzhums']          = !isset($reply['zanzhums']) ? "1" : $reply['zanzhums'];
        $reply['istopheader']       = !isset($reply['istopheader']) ? "0" : $reply['istopheader'];
        $reply['ipannounce']        = !isset($reply['ipannounce']) ? "0" : $reply['ipannounce'];
        $reply['isbgaudio']         = !isset($reply['isbgaudio']) ? "0" : $reply['isbgaudio'];
        $reply['ishuodong']         = !isset($reply['ishuodong']) ? "0" : $reply['ishuodong'];
        $reply['topbgcolor']        = empty($reply['topbgcolor']) ? "" : $reply['topbgcolor'];
        $reply['topbg']             = empty($reply['topbg']) ? "" : $reply['topbg'];
        $reply['topbgtext']         = empty($reply['topbgtext']) ? "" : $reply['topbgtext'];
        $reply['topbgrightcolor']   = empty($reply['topbgrightcolor']) ? "" : $reply['topbgrightcolor'];
        $reply['topbgright']        = empty($reply['topbgright']) ? "" : $reply['topbgright'];
        $reply['foobg1']            = empty($reply['foobg1']) ? "" : $reply['foobg1'];
        $reply['foobg2']            = empty($reply['foobg2']) ? "" : $reply['foobg2'];
        $reply['foobgtextn']        = empty($reply['foobgtextn']) ? "" : $reply['foobgtextn'];
        $reply['foobgtexty']        = empty($reply['foobgtexty']) ? "" : $reply['foobgtexty'];
        $reply['foobgtextmore']     = empty($reply['foobgtextmore']) ? "" : $reply['foobgtextmore'];
        $reply['foobgmorecolor']    = empty($reply['foobgmorecolor']) ? "" : $reply['foobgmorecolor'];
        $reply['foobgmore']         = empty($reply['foobgmore']) ? "" : $reply['foobgmore'];
        $reply['t']                 = @json_decode($reply['dc']['content'], true);
        $reply['bodytextcolor']     = empty($reply['bodytextcolor']) ? "" : $reply['bodytextcolor'];
        $reply['bodynumcolor']      = empty($reply['bodynumcolor']) ? "" : $reply['bodynumcolor'];
        $reply['bodytscolor']       = empty($reply['bodytscolor']) ? "" : $reply['bodytscolor'];
        $reply['bodytsbg']          = empty($reply['bodytsbg']) ? "" : $reply['bodytsbg'];
        $reply['copyrightcolor']    = empty($reply['copyrightcolor']) ? "" : $reply['copyrightcolor'];
        $reply['inputcolor']        = empty($reply['inputcolor']) ? "" : $reply['inputcolor'];
        $reply['xinbg']             = empty($reply['xinbg']) ? "" : $reply['xinbg'];
        $reply['command']           = empty($reply['command']) ? "报名" : $reply['command'];
        $reply['tcommand']          = empty($reply['tcommand']) ? "t" : $reply['tcommand'];
        $reply['votetime']          = empty($reply['votetime']) ? "10" : $reply['votetime'];
        $qiniu['videologo']         = empty($qiniu['videologo']) ? "http://demo.012wz.com/web/resource/images/gw-logo.png" : $qiniu['videologo'];
        $regtitlearr['cmmrealname'] = empty($regtitlearr['cmmrealname']) ? "姓名" : $regtitlearr['cmmrealname'];
        $regtitlearr['cmmmobile']   = empty($regtitlearr['cmmmobile']) ? "手机" : $regtitlearr['cmmmobile'];
        $regtitlearr['cmmweixin']   = empty($regtitlearr['cmmweixin']) ? "微信" : $regtitlearr['cmmweixin'];
        $regtitlearr['cmmqqhao']    = empty($regtitlearr['cmmqqhao']) ? "QQ号" : $regtitlearr['cmmqqhao'];
        $regtitlearr['cmmemail']    = empty($regtitlearr['cmmemail']) ? "电子邮箱" : $regtitlearr['cmmemail'];
        $regtitlearr['cmmjob']      = empty($regtitlearr['cmmjob']) ? "职业" : $regtitlearr['cmmjob'];
        $regtitlearr['cmmxingqu']   = empty($regtitlearr['cmmxingqu']) ? "兴趣" : $regtitlearr['cmmxingqu'];
        $regtitlearr['cmmaddress']  = empty($regtitlearr['cmmaddress']) ? "地址" : $regtitlearr['cmmaddress'];
        $reply['limitsd']           = empty($reply['limitsd']) ? "5" : $reply['limitsd'];
        $reply['limitsdps']         = empty($reply['limitsdps']) ? "1" : $reply['limitsdps'];
        if (!pdo_fieldexists('fm_photosvote_provevote', $reply['yuming']['0']) && !empty($reply['yuming']['0'])) {
            pdo_query("ALTER TABLE  " . tablename('fm_photosvote_provevote') . " ADD `{$reply['yuming']['0']}` varchar(30) NOT NULL DEFAULT '0' COMMENT '0' AFTER address;");
        }
        if (!pdo_fieldexists('fm_photosvote_votelog', $reply['yuming']['1']) && !empty($reply['yuming']['1'])) {
            pdo_query("ALTER TABLE  " . tablename('fm_photosvote_votelog') . " ADD `{$reply['yuming']['1']}` varchar(30) NOT NULL DEFAULT '0' COMMENT '0' AFTER tfrom_user;");
        }
        if (!pdo_fieldexists('fm_photosvote_reply', $reply['yuming']['2']) && !empty($reply['yuming']['2'])) {
            pdo_query("ALTER TABLE  " . tablename('fm_photosvote_reply') . " ADD `{$reply['yuming']['2']}` varchar(30) NOT NULL DEFAULT '0' COMMENT '0' AFTER picture;");
        }
        if (!pdo_fieldexists('fm_photosvote_reply_body', $reply['yuming']['3']) && !empty($reply['yuming']['3'])) {
            pdo_query("ALTER TABLE  " . tablename('fm_photosvote_reply_body') . " ADD `{$reply['yuming']['3']}` varchar(30) NOT NULL DEFAULT '0' COMMENT '0' AFTER topbgright;");
        }
        if ($_W['role'] == 'founder') {
                $settingurl = url('profile/module/setting', array(
                    'm' => 'fm_photosvote'
                ));
            } 
        
        include $this->template('form');
    }
    public function fieldsFormValidate($rid = 0)
    {
        return '';
    }
    public function fieldsFormSubmit($rid)
    {
        global $_GPC, $_W;
        load()->func('communication');
        $uniacid = $_W['uniacid'];
        $id      = intval($_GPC['reply_id']);
        if (strtotime($_GPC['datelimit']['start']) < time() && strtotime($_GPC['datelimit']['end']) > time()) {
            $status = 1;
        } else {
            $status = 0;
        }
        $insert_basic                  = array(
            'rid' => $rid,
            'uniacid' => $uniacid,
            'status' => $status,
            'title' => $_GPC['title'],
            'picture' => $_GPC['picture'],
            'start_time' => strtotime($_GPC['datelimit']['start']),
            'end_time' => strtotime($_GPC['datelimit']['end']),
            'tstart_time' => strtotime($_GPC['tdatelimit']['start']),
            'tend_time' => strtotime($_GPC['tdatelimit']['end']),
            'bstart_time' => strtotime($_GPC['bdatelimit']['start']),
            'bend_time' => strtotime($_GPC['bdatelimit']['end']),
            'ttipstart' => $_GPC['ttipstart'],
            'ttipend' => $_GPC['ttipend'],
            'btipstart' => $_GPC['btipstart'],
            'btipend' => $_GPC['btipend'],
            'isdaojishi' => intval($_GPC['isdaojishi']),
            'ttipvote' => $_GPC['ttipvote'],
            'votetime' => $_GPC['votetime'],
            'description' => $_GPC['description'],
            'content' => htmlspecialchars_decode($_GPC['content']),
            'stopping' => $_GPC['stopping'],
            'nostart' => $_GPC['nostart'],
            'end' => $_GPC['end']
        );
        $insert_share                  = array(
            'rid' => $rid,
            'subscribe' => intval($_GPC['subscribe']),
            'shareurl' => $_GPC['shareurl'],
            'sharetitle' => $_GPC['sharetitle'],
            'sharephoto' => $_GPC['sharephoto'],
            'sharecontent' => $_GPC['sharecontent'],
            'subscribedes' => $_GPC['subscribedes']
        );
        $insert_huihua                 = array(
            'rid' => $rid,
            'command' => $_GPC['command'],
            'tcommand' => $_GPC['tcommand'],
            'ishuodong' => $_GPC['ishuodong'],
            'huodongname' => $_GPC['huodongname'],
            'huodongdes' => $_GPC['huodongdes'],
            'hhhdpicture' => $_GPC['hhhdpicture'],
            'huodongurl' => $_GPC['huodongurl'],
            'regmessagetemplate' => $_GPC['regmessagetemplate'],
            'messagetemplate' => $_GPC['messagetemplate'],
            'shmessagetemplate' => $_GPC['shmessagetemplate'],
            'fmqftemplate' => $_GPC['fmqftemplate'],
            'msgtemplate' => $_GPC['msgtemplate']
        );
        $insert_display                = array(
            'rid' => $rid,
            'istopheader' => intval($_GPC['istopheader']),
            'ipannounce' => intval($_GPC['ipannounce']),
            'isbgaudio' => intval($_GPC['isbgaudio']),
            'isvoteusers' => intval($_GPC['isvoteusers']),
            'bgmusic' => $_GPC['bgmusic'],
            'isedes' => intval($_GPC['isedes']),
            'tmoshi' => intval($_GPC['tmoshi']),
            'indextpxz' => intval($_GPC['indextpxz']),
            'indexorder' => $_GPC['indexorder'],
            'indexpx' => intval($_GPC['indexpx']),
            'phbtpxz' => intval($_GPC['phbtpxz']),
            'zanzhums' => $_GPC['zanzhums'],
            'xuninum' => $_GPC['xuninum'],
            'hits' => $_GPC['hits'],
            'xuninumtime' => $_GPC['xuninumtime'],
            'xuninuminitial' => $_GPC['xuninuminitial'],
            'xuninumending' => $_GPC['xuninumending'],
            'isrealname' => intval($_GPC['isrealname']),
            'ismobile' => intval($_GPC['ismobile']),
            'isweixin' => intval($_GPC['isweixin']),
            'isqqhao' => intval($_GPC['isqqhao']),
            'isemail' => intval($_GPC['isemail']),
            'isjob' => intval($_GPC['isjob']),
            'isxingqu' => intval($_GPC['isxingqu']),
            'isaddress' => intval($_GPC['isaddress']),
            'isindex' => intval($_GPC['isindex']),
            'isvotexq' => intval($_GPC['isvotexq']),
            'ispaihang' => intval($_GPC['ispaihang']),
            'isreg' => intval($_GPC['isreg']),
            'isdes' => intval($_GPC['isdes']),
            'lapiao' => $_GPC['lapiao'],
            'sharename' => $_GPC['sharename'],
            'tpname' => $_GPC['tpname'],
            'tpsname' => $_GPC['tpsname'],
            'rqname' => $_GPC['rqname'],
            'csrs' => $_GPC['csrs'],
            'ljtp' => $_GPC['ljtp'],
            'cyrs' => $_GPC['cyrs'],
            'iscopyright' => intval($_GPC['iscopyright']),
            'copyright' => $_GPC['copyright'],
            'copyrighturl' => $_GPC['copyrighturl']
        );
        $regtitlearr                   = array(
            'cmmrealname' => $_GPC['cmmrealname'],
            'cmmmobile' => $_GPC['cmmmobile'],
            'cmmweixin' => $_GPC['cmmweixin'],
            'cmmqqhao' => $_GPC['cmmqqhao'],
            'cmmemail' => $_GPC['cmmemail'],
            'cmmjob' => $_GPC['cmmjob'],
            'cmmxingqu' => $_GPC['cmmxingqu'],
            'cmmaddress' => $_GPC['cmmaddress']
        );
        $insert_display['regtitlearr'] = iserializer($regtitlearr);
        $insert_vote                   = array(
            'rid' => $rid,
            'iscode' => intval($_GPC['iscode']),
            'codekey' => $_GPC['codekey'],
            'addpvapp' => intval($_GPC['addpvapp']),
            'isfans' => intval($_GPC['isfans']),
            'mediatype' => intval($_GPC['mediatype']),
            'mediatypem' => intval($_GPC['mediatypem']),
            'mediatypev' => intval($_GPC['mediatypev']),
            'voicemoshi' => $_GPC['voicemoshi'],
            'moshi' => intval($_GPC['moshi']),
            'webinfo' => htmlspecialchars_decode($_GPC['webinfo']),
            'cqtp' => intval($_GPC['cqtp']),
            'tpsh' => intval($_GPC['tpsh']),
            'isbbsreply' => intval($_GPC['isbbsreply']),
            'tmyushe' => $_GPC['tmyushe'],
            'tmreply' => $_GPC['tmreply'],
            'isipv' => intval($_GPC['isipv']),
            'ipturl' => $_GPC['ipturl'],
            'ipstopvote' => intval($_GPC['ipstopvote']),
            'iplocallimit' => $_GPC['iplocallimit'],
            'iplocaldes' => $_GPC['iplocaldes'],
            'tpxz' => $_GPC['tpxz'] > 8 ? '8' : intval($_GPC['tpxz']),
            'autolitpic' => intval($_GPC['autolitpic']),
            'autozl' => $_GPC['autozl'] > 100 ? '100' : intval($_GPC['autozl']),
            'limitip' => $_GPC['limitip'],
            'daytpxz' => intval($_GPC['daytpxz']),
            'dayonetp' => intval($_GPC['dayonetp']),
            'allonetp' => intval($_GPC['allonetp']),
            'fansmostvote' => intval($_GPC['fansmostvote']),
            'userinfo' => $_GPC['userinfo'],
            'votesuccess' => $_GPC['votesuccess'],
            'limitsd' => intval($_GPC['limitsd']),
            'limitsdps' => intval($_GPC['limitsdps'])
        );
        $insert_body                   = array(
            'rid' => $rid,
            'zbgcolor' => $_GPC['zbgcolor'],
            'zbg' => $_GPC['zbg'],
            'voicebg' => $_GPC['voicebg'],
            'zbgtj' => $_GPC['zbgtj'],
            'topbgcolor' => $_GPC['topbgcolor'],
            'topbg' => $_GPC['topbg'],
            'topbgtext' => $_GPC['topbgtext'],
            'topbgrightcolor' => $_GPC['topbgrightcolor'],
            'topbgright' => $_GPC['topbgright'],
            'foobg1' => $_GPC['foobg1'],
            'foobg2' => $_GPC['foobg2'],
            'foobgtextn' => $_GPC['foobgtextn'],
            'foobgtexty' => $_GPC['foobgtexty'],
            'foobgtextmore' => $_GPC['foobgtextmore'],
            'foobgmorecolor' => $_GPC['foobgmorecolor'],
            'foobgmore' => $_GPC['foobgmore'],
            'bodytextcolor' => $_GPC['bodytextcolor'],
            'bodynumcolor' => $_GPC['bodynumcolor'],
            'inputcolor' => $_GPC['inputcolor'],
            'bodytscolor' => $_GPC['bodytscolor'],
            'bodytsbg' => $_GPC['bodytsbg'],
            'xinbg' => $_GPC['xinbg'],
            'copyrightcolor' => $_GPC['copyrightcolor']
        );
        $qiniu                         = array(
            'isqiniu' => intval($_GPC['isqiniu']),
            'accesskey' => $_GPC['accesskey'],
            'secretkey' => $_GPC['secretkey'],
            'qnlink' => $_GPC['qnlink'],
            'bucket' => $_GPC['bucket'],
            'pipeline' => $_GPC['pipeline'],
            'aq' => $_GPC['aq'],
            'videofbl' => $_GPC['videofbl'],
            'videologo' => $_GPC['videologo'],
            'wmgravity' => $_GPC['wmgravity']
        );
        $insert_basic['qiniu']         = iserializer($qiniu);
        if (empty($id)) {
            pdo_insert($this->table_reply, $insert_basic);
            pdo_insert($this->table_reply_share, $insert_share);
            pdo_insert($this->table_reply_huihua, $insert_huihua);
            pdo_insert($this->table_reply_display, $insert_display);
            pdo_insert($this->table_reply_vote, $insert_vote);
            pdo_insert($this->table_reply_body, $insert_body);
        } else {
            pdo_update($this->table_reply, $insert_basic, array(
                'rid' => $rid
            ));
            pdo_update($this->table_reply_share, $insert_share, array(
                'rid' => $rid
            ));
            pdo_update($this->table_reply_huihua, $insert_huihua, array(
                'rid' => $rid
            ));
            pdo_update($this->table_reply_display, $insert_display, array(
                'rid' => $rid
            ));
            pdo_update($this->table_reply_vote, $insert_vote, array(
                'rid' => $rid
            ));
            pdo_update($this->table_reply_body, $insert_body, array(
                'rid' => $rid
            ));
        }
    }
    public function ruleDeleted($rid)
    {
        pdo_delete($this->table_reply, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_reply_share, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_reply_huihua, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_reply_display, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_reply_vote, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_reply_body, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_users, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_log, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_bbsreply, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_banners, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_advs, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_data, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_announce, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_iplist, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_iplistlog, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_provevote_name, array(
            'rid' => $rid
        ));
        pdo_delete($this->table_provevote_voice, array(
            'rid' => $rid
        ));
    }
    public function settingsDisplay($settings)
    {
        global $_GPC, $_W;
        load()->func('communication');
        //$a       = 'aHR0cDovL24uZm1vb25zLmNvbS9hcGkvYXBpLnBocD8mYXBpPWFwaQ==';
        //$d       = base64_decode("aHR0cDovL2FwaS5mbW9vbnMuY29tL2luZGV4LnBocD8md2VidXJsPQ==") . $_SERVER['HTTP_HOST'] . "&visitorsip=" . $_W['clientip'] . "&modules=" . $_GPC['m'];
        //$dc      = ihttp_get($d);
        //$t       = @json_decode($dc['content'], true);
        $wechats = pdo_fetch("SELECT level FROM " . tablename('account_wechats') . " WHERE uniacid = :uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        if (checksubmit()) {
            $cfg               = array();
            $cfg['oauthtype']  = $_GPC['oauthtype'];
            $cfg['appid']      = empty($_GPC['appid']) ? $_GPC['appida'] : $_GPC['appid'];
            $cfg['secret']     = empty($_GPC['secret']) ? $_GPC['secreta'] : $_GPC['secret'];
            $cfg['isopenjsps'] = $_GPC['isopenjsps'];
            $cfg['ismiaoxian'] = $_GPC['ismiaoxian'];
            $cfg['mxnexttime'] = $_GPC['mxnexttime'];
            $cfg['mxtimes']    = $_GPC['mxtimes'];
            //if ($t['config']) {
                if ($this->saveSettings($cfg)) {
                    message('保存成功', 'refresh');
                }
           // }
        }
        include $this->template('setting');
    }
}