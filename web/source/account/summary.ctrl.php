<?php
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$acid = intval($_GPC['acid']);
$uniacid = intval($_GPC['uniacid']);
$account = account_fetch($acid);
if(empty($account)) {
	message('公众号不存在或已被删除', '', 'error');
}
uni_update_yesterday_stat();

$_W['page']['title'] = $account['name'] . ' - 公众号详细信息';
$starttime = $_GPC['datelimit']['start'] ? strtotime($_GPC['datelimit']['start']) : date('Ymd', strtotime('-7day'));
$endtime = $_GPC['datelimit']['end'] ? strtotime($_GPC['datelimit']['end']) : date('Ymd');
$yesterday = date('Ymd', strtotime('-1 days'));
$today = date('Ymd');

$type = intval($_GPC['type']) ? intval($_GPC['type']) : 1;

if($_W['isajax']) {
	$days = array();
	$datasets = array();
	$stat = pdo_fetchall("SELECT * FROM ".tablename('stat_fans')." WHERE date >= '$starttim' AND date <= '$endtime' AND uniacid = '{$_W['uniacid']}' ORDER BY date ASC", array(), 'date');
	for ($i = strtotime($starttime); $i <= strtotime($endtime); $i+=86400) {
		$day = date('Ymd', $i);
		if ($day == $today) {
			$stat[$day]['cumulate'] = intval($stat[$day]['cumulate']) + intval($stat[$yesterday]['cumulate']);
		}
		$shuju['label'][] = date('m-d', strtotime($day));
		$shuju['datasets']['new'][] = intval($stat[$day]['new']);
		$shuju['datasets']['cancel'][] =  intval($stat[$day]['cancel']);
		$shuju['datasets']['increase'][] =  intval($stat[$day]['new']) - intval($stat[$day]['cancel']);
		$shuju['datasets']['cumulate'][] =  intval($stat[$day]['cumulate']);
	}
	exit(json_encode($shuju));
}

$scroll = intval($_GPC['scroll']);
$yesterday_stat = pdo_get('stat_fans', array('date' => $yesterday, 'uniacid' => $_W['uniacid']));
$today_stat = pdo_get('stat_fans', array('date' => date('Ymd'), 'uniacid' => $_W['uniacid']));
$today_stat['cumulate'] = intval($today_stat['cumulate']) + intval($yesterday_stat['cumulate']);

template('account/summary');