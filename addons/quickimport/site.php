<?php
/**
 * @author WeEngine Team
 */
defined('IN_IA') or exit('Access Denied');

include 'define.php';
require_once(IA_ROOT . '/addons/quickcenter/loader.php');


class QuickImportModuleSite extends WeModuleSite {
	public function doWebImportBaijia() {
    global $_W, $_GPC;
		$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];

    $bj_prefix = empty($_GPC['bj_prefix']) ? 'ims_' : trim($_GPC['bj_prefix']);
    $xc_prefix = empty($_GPC['xc_prefix']) ? 'ims_' : trim($_GPC['xc_prefix']);

    if ('post' == $op) {
      if (checksubmit('submit')) {

        ignore_user_abort(true);
        if ($bj_prefix == $xc_prefix) {
          echo "前缀相同，不正确";
          exit(0);
        }

        // 检查目标表格是否存在
        $check_fans_exits    = "SHOW TABLES LIKE '" . $bj_prefix . "fans'";
        $ret = pdo_fetch($check_fans_exits);
        if (empty($ret)) { exit($check_fans_exits . ' FAIL!'); }

        $check_wechats_exits = "SHOW TABLES LIKE '" . $bj_prefix . "wechats'";
        $ret = pdo_fetch($check_wechats_exits);
        if (empty($ret)) { exit($check_wechats_exits. ' FAIL!'); }

        $check_members_exits = "SHOW TABLES LIKE '" . $bj_prefix . "members'";
        $ret = pdo_fetch($check_members_exits);
        if (empty($ret)) { exit($check_members_exits. ' FAIL!'); }

        $check_follow_exits  = "SHOW TABLES LIKE '" . $bj_prefix . "bj_qmjf_follow'";
        $ret = pdo_fetch($check_follow_exits);
        if (empty($ret)) { exit($check_follow_exits. ' FAIL!'); }


        // fans表导入
        $fans_import_sql =
          'INSERT INTO ' . $xc_prefix . 'fans'
          . '  (`id`,`weid`,`from_user`,`salt`,`follow`,`credit1`,`credit2`,`createtime`,`realname`,`nickname`,`avatar`,`wechat_avatar`, `qq`,`mobile`,`fakeid`,`vip`,`gender`,`birthyear`,`birthmonth`,`birthday`,`constellation`,`zodiac`,`telephone`,`idcard`,`studentid`,`grade`,`address`,`zipcode`,`nationality`,`resideprovince`,`residecity`,`residedist`) '
          . ' SELECT  `id`,`weid`,`from_user`,`salt`,`follow`,`credit1`,`credit2`,`createtime`,`realname`,`nickname`,`avatar`,`avatar`,`qq`,`mobile`,`fakeid`,`vip`,`gender`,`birthyear`,`birthmonth`,`birthday`,`constellation`,`zodiac`,`telephone`,`idcard`,`studentid`,`grade`,`address`,`zipcode`,`nationality`,`resideprovince`,`residecity`,`residedist` FROM ' . $bj_prefix . 'fans';

        //$ret = pdo_query($fans_import_sql);
        echo '<p>import fans result:' . json_encode($ret) . '</p>';


        // follow表导入
        $follow_import_sql = 'insert into ' . $xc_prefix . 'quickspread_follow'
          . '  (`weid`, `leader`, `follower`, `channel`, `credit`, `createtime`) '
          . ' SELECT `weid`, `leader`, `follower`, `channel`, `credit`, `createtime` FROM  ' . $bj_prefix . 'bj_qmjf_follow';
        //$ret = pdo_query($follow_import_sql);
        echo '<p>import follow result:' . json_encode($ret) . '</p>';


        // member表导入
        $wechats_import_sql = 'INSERT INTO ' . $xc_prefix . 'wechats'
          . ' (`weid`, `hash`, `type`, `uid`, `token`, `EncodingAESKey`, `access_token`, `level`, `name`, `account`, `original`, `signature`, `country`, `province`, `city`, `username`, `password`, `welcome`, `default`, `default_message`, `default_period`, `lastupdate`, `key`, `secret`, `styleid`, `payment`, `shortcuts`, `quickmenu`, `parentid`, `subwechats`, `siteinfo`, `menuset`, `jsapi_ticket`) '
          .  ' SELECT `weid`, `hash`, `type`, `uid`, `token`, `EncodingAESKey`, `access_token`, `level`, `name`, `account`, `original`, `signature`, `country`, `province`, `city`, `username`, `password`, `welcome`, `default`, `default_message`, `default_period`, `lastupdate`, `key`, `secret`, `styleid`, `payment`, `shortcuts`, `quickmenu`, `parentid`, `subwechats`, `siteinfo`, `menuset`, `jsapi_ticket`  FROM '  . $bj_prefix . 'wechats'
	  . ' WHERE weid != 1';
         $ret = pdo_query($wechats_import_sql);
        echo '<p>import wechats account result:' . json_encode($ret) . '</p>';


        // account表导入
        $members_import_sql = 'INSERT INTO ' . $xc_prefix . 'members'
          . ' (`uid`, `groupid`, `username`, `password`, `salt`, `status`, `joindate`, `joinip`, `lastvisit`, `lastip`, `remark` ) '
          . ' SELECT `uid`, `groupid`, `username`, `password`, `salt`, `status`, `joindate`, `joinip`, `lastvisit`, `lastip`, `remark`  FROM ' . $bj_prefix . 'members '
          . ' WHERE `uid` != 0';
        //$ret = pdo_query($members_import_sql);
        echo '<p>import members account result:' . json_encode($ret) . '</p>';

      }
    } else if ($operation == 'display') {
      echo "display";
		}
		include $this->template('baijia');
	}
}
