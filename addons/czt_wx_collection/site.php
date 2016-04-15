<?php
defined('IN_IA') or exit('Access Denied');

class Czt_wx_collectionModuleSite extends WeModuleSite
{
	public function doMobileSelf_collection()
	{
		global $_GPC, $_W;
		include $this->template('self_collection');
	}

	public function doMobileScan_collection()
	{
		global $_GPC, $_W;
		$scan_class = pdo_fetchall('select * from ' . tablename('czt_wx_collection_scan_class') . ' WHERE status=1 and  uniacid=' . $_W['uniacid']);
		include $this->template('scan_collection');
	}

	public function doWebScan_class()
	{
		global $_W, $_GPC;
		$op = $_GPC['op'] ? $_GPC['op'] : 'display';
		if ($op == 'display') {
			$list = pdo_fetchall('select * from ' . tablename('czt_wx_collection_scan_class') . ' WHERE uniacid=' . $_W['uniacid']);
		} elseif ($op == 'edit') {
			$id = intval($_GPC['id']);
			$sql = 'SELECT * FROM ' . tablename('czt_wx_collection_scan_class') . ' WHERE  id=:id';
			$result = pdo_fetch($sql, array(':id' => $id));
			if (checksubmit()) {
				$data['name'] = $_GPC['name'];
				$data['status'] = $_GPC['status'];
				if (empty($_GPC['name'])) {
					message('名称必须填写！', '', 'error');
				}
				pdo_update('czt_wx_collection_scan_class', $data, array('id' => $id));
				message('更新成功', $this->createWebUrl('scan_class'), 'success');
			}
		} elseif ($op == 'new') {
			if (checksubmit()) {
				$data['name'] = $_GPC['name'];
				$data['status'] = $_GPC['status'];
				$data['uniacid'] = $_W['uniacid'];
				if (empty($_GPC['name'])) {
					message('名称必须填写！', '', 'error');
				}
				pdo_insert('czt_wx_collection_scan_class', $data);
				message('新增成功', $this->createWebUrl('scan_class'), 'success');
			}
		} elseif ($op == 'delete') {
			$id = intval($_GPC['id']);
			if ($id) {
				if (pdo_delete('czt_wx_collection_scan_class', array('id' => $id))) {
					message('删除成功', $this->createWebUrl('scan_class'), 'success');
				}
			}
		}
		include $this->template('scan_class');
	}

	public function doMobileMake_scan_qr()
	{
		global $_GPC, $_W;
		$fee = floatval($_GPC['fee']);
		if ($_W['isajax'] && $fee > 0) {
			$class_id = intval($_GPC['class_id']);
			$settings = $this->module['config'];
			$scan_type = $settings['scan_type'];
			$scan_type = $scan_type ? $scan_type : 1;
			if ($scan_type == 1) {
				exit($this->bizpay($fee, $class_id));
			}
			if ($scan_type == 2) {
				$tid = time() . rand(1000, 9999);
				$create_time = time();
				$code_url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('scan_pay', array('tid' => $tid, 'fee' => $fee));
				$data = array('fee' => $fee, 'openid' => '', 'tid' => $tid, 'uniacid' => $_W['uniacid'], 'create_time' => $create_time, 'status' => 2, 'class_id' => $class_id, 'scan_type' => 2, 'founder_openid' => $_W['openid'], 'code_url' => $code_url,);
				pdo_insert('czt_wx_collection_scan_record', $data);
				exit($code_url . '|' . $tid);
			}
		}
	}

	public function bizpay($fee, $class_id)
	{
		global $_GPC, $_W;
		$tid = time() . rand(1000, 9999);
		$module = $this->modulename;
		$moduleid = pdo_fetchcolumn("SELECT mid FROM " . tablename('modules') . " WHERE name = :name", array(':name' => $module));
		$moduleid = empty($moduleid) ? '000000' : sprintf("%06d", $moduleid);
		$params = array();
		$params['title'] = $_W['account']['name'] . ' - 扫码付款';
		$params['uniontid'] = date('YmdHis') . $moduleid . random(8, 1);
		$params['fee'] = $fee;
		$setting = uni_setting($_W['uniacid'], array('payment'));
		if (!is_array($setting['payment'])) {
			exit('没有设定支付参数.');
		}
		$wechat = $setting['payment']['wechat'];
		$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
		$row = pdo_fetch($sql, array(':acid' => $wechat['account']));
		$wechat['appid'] = $row['key'];
		$wechat['secret'] = $row['secret'];
		$code_url = wechat_build($params, $wechat);
		$module = $this->modulename;
		$moduleid = pdo_fetchcolumn("SELECT mid FROM " . tablename('modules') . " WHERE name = :name", array(':name' => $module));
		$moduleid = empty($moduleid) ? '000000' : sprintf("%06d", $moduleid);
		$record = array();
		$record['uniacid'] = $_W['uniacid'];
		$record['openid'] = '';
		$record['module'] = $module;
		$record['type'] = 'wechat';
		$record['tid'] = $tid;
		$record['uniontid'] = $params['uniontid'];
		$record['fee'] = $fee;
		$record['status'] = '0';
		$record['is_usecard'] = 0;
		$record['card_id'] = 0;
		$record['card_fee'] = $fee;
		$record['encrypt_code'] = '';
		$record['acid'] = $_W['acid'];
		if (!pdo_insert('core_paylog', $record)) {
			exit('core_paylog error');
		}
		$create_time = time();
		$data = array('fee' => $fee, 'openid' => '', 'tid' => $tid, 'uniacid' => $_W['uniacid'], 'create_time' => $create_time, 'status' => 2, 'class_id' => $class_id, 'scan_type' => 1, 'type' => 'wechat', 'founder_openid' => $_W['openid'], 'code_url' => $code_url,);
		if (!pdo_insert('czt_wx_collection_scan_record', $data)) {
			exit('czt_wx_collection_scan_record error');
		}
		return $code_url . '|' . $tid;
	}

	public function doWebSelf_record()
	{
		global $_GPC, $_W;
		load()->func('tpl');
		$pindex = max(1, intval($_GPC['page']));
		$psize = 30;
		$condition = " uniacid = :uniacid";
		$paras = array(':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['tid'])) {
			$condition .= " AND tid = :tid";
			$paras[':tid'] = $_GPC['tid'];
		} else {
			if (!empty($_GPC['time'])) {
				if ($_GPC['time']['start'] != '1970-01-01' && $_GPC['time']['end'] != '1970-01-01') {
					$starttime = strtotime($_GPC['time']['start']);
					$endtime = strtotime($_GPC['time']['end']) + 86399;
					$condition .= " AND create_time >= :starttime AND create_time <= :endtime ";
					$paras[':starttime'] = $starttime;
					$paras[':endtime'] = $endtime;
				}
			}
		}
		$sql = 'SELECT COUNT(*) FROM ' . tablename('czt_wx_collection_self_record') . ' WHERE ' . $condition;
		$total = pdo_fetchcolumn($sql, $paras);
		if ($total > 0) {
			$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
			$sql = 'SELECT * FROM ' . tablename('czt_wx_collection_self_record') . ' WHERE  ' . $condition . '  ORDER BY `create_time` DESC ' . $limit;
			$list = pdo_fetchall($sql, $paras);
			$pager = pagination($total, $pindex, $psize);
			load()->model('mc');
			load()->model('account');
			foreach (uni_accounts($_W['uniacid']) as $key => $value) {
				$acid = $value['acid'];
				break;
			}
			$openids = array();
			foreach ($list as $key => $value) {
				$openids[$value['openid']] = array();
			}
			foreach ($openids as $key => $value) {
				$result = mc_fansinfo($key, $acid, $_W['uniacid']);
				$openids[$key]['nickname'] = isset($result['tag']['nickname']) ? $result['tag']['nickname'] : '';
				$openids[$key]['avatar'] = isset($result['tag']['avatar']) ? $result['tag']['avatar'] : '';
			}
			foreach ($list as $key => &$value) {
				$list[$key]['nickname'] = $openids[$value['openid']]['nickname'];
			}
			if ($_GPC['export'] != '') {
				$html = "\xEF\xBB\xBF";
				$filter = array('create_time' => '时间', 'tid' => '系统单号', 'ordersn' => '小票单号', 'store' => '收款门店', 'nickname' => '昵称', 'openid' => 'openid', 'fee' => '金额',);
				foreach ($filter as $key => $title) {
					$html .= $title . "\t,";
				}
				$html .= "\n";
				foreach ($list as $k => $v) {
					if ($v['status']) {
						foreach ($filter as $key => $title) {
							if ($key == 'create_time') {
								$html .= date('Y-m-d H:i:s', $v[$key]) . "\t, ";
							} else {
								$html .= $v[$key] . "\t, ";
							}
						}
						$html .= "\n";
					}
				}
				header('Content-type:text/csv');
				header('Content-Disposition:attachment; filename=全部数据.csv');
				echo $html;
				exit();
			}
		}
		include $this->template('self_record');
	}

	public function doWebScan_record()
	{
		global $_GPC, $_W;
		load()->func('tpl');
		$scan_class = pdo_fetchall('select * from ' . tablename('czt_wx_collection_scan_class') . ' WHERE  uniacid=' . $_W['uniacid']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 30;
		$condition = " record.uniacid = :uniacid";
		$paras = array(':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['tid'])) {
			$condition .= " AND record.tid = :tid";
			$paras[':tid'] = $_GPC['tid'];
		} else {
			if (!empty($_GPC['time'])) {
				if ($_GPC['time']['start'] != '1970-01-01' && $_GPC['time']['end'] != '1970-01-01') {
					$starttime = strtotime($_GPC['time']['start']);
					$endtime = strtotime($_GPC['time']['end']) + 86399;
					$condition .= " AND record.create_time >= :starttime AND record.create_time <= :endtime ";
					$paras[':starttime'] = $starttime;
					$paras[':endtime'] = $endtime;
				}
			}
			if ($_GPC['status'] != '') {
				$condition .= " AND record.status = :status";
				$paras[':status'] = $_GPC['status'];
			}
			if (!empty($_GPC['scan_class'])) {
				$condition .= " AND record.class_id = :scan_class";
				$paras[':scan_class'] = intval($_GPC['scan_class']);
			}
			if (!empty($_GPC['scan_type'])) {
				$condition .= " AND record.scan_type = :scan_type";
				$paras[':scan_type'] = intval($_GPC['scan_type']);
			}
		}
		$sql = 'SELECT COUNT(*) FROM ' . tablename('czt_wx_collection_scan_record') . 'as record WHERE ' . $condition;
		$total = pdo_fetchcolumn($sql, $paras);
		if ($total > 0) {
			$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
			$sql = 'SELECT record.*,class.name class_name FROM ' . tablename('czt_wx_collection_scan_record') . 'as record left join ' . tablename('czt_wx_collection_scan_class') . 'as class on record.class_id=class.id     WHERE  ' . $condition . '  ORDER BY `create_time` DESC ' . $limit;
			$list = pdo_fetchall($sql, $paras);
			$pager = pagination($total, $pindex, $psize);
			load()->model('mc');
			load()->model('account');
			foreach (uni_accounts($_W['uniacid']) as $key => $value) {
				$acid = $value['acid'];
				break;
			}
			$openids = array();
			foreach ($list as $key => $value) {
				$openids[$value['openid']] = array();
			}
			foreach ($openids as $key => $value) {
				$result = mc_fansinfo($key, $acid, $_W['uniacid']);
				$openids[$key]['nickname'] = isset($result['tag']['nickname']) ? $result['tag']['nickname'] : '';
				$openids[$key]['avatar'] = isset($result['tag']['avatar']) ? $result['tag']['avatar'] : '';
			}
			foreach ($list as $key => &$value) {
				$list[$key]['nickname'] = $openids[$value['openid']]['nickname'];
			}
		}
		include $this->template('scan_record');
	}

	public function doMobileScan_query()
	{
		global $_GPC, $_W;
		$tid = $_GPC['tid'];
		if ($_W['isajax'] && !empty($tid)) {
			$r = pdo_fetch("SELECT * FROM " . tablename('czt_wx_collection_scan_record') . " WHERE uniacid = :uniacid and tid = :tid", array(':tid' => $tid, ':uniacid' => $_W['uniacid']));
			if ($r) {
				exit($r['status']);
			}
		}
	}

	public function doMobileScan_pay()
	{
		global $_GPC, $_W;
		$fee = floatval($_GPC['fee']);
		$tid = $_GPC['tid'];
		if ($fee <= 0) {
			message('支付错误, 金额小于0');
		}
		if (empty($tid)) {
			message('tid错误');
		}
		$params = array('tid' => $tid, 'ordersn' => $tid, 'title' => $_W['account']['name'] . ' - 扫码付款', 'fee' => $fee, 'user' => $_W['member']['uid'],);
		include $this->template('pay');
	}

	public function doMobileSelf_pay()
	{
		global $_GPC, $_W;
		$fee = floatval($_GPC['fee']);
		$tid = $_GPC['tid'];
		if ($fee <= 0) {
			message('支付错误, 金额小于0');
		}
		if (empty($tid)) {
			message('tid错误');
		}
		$params = array('tid' => $tid, 'ordersn' => $tid, 'title' => $_W['account']['name'] . ' - 自助付款', 'fee' => $fee, 'user' => $_W['member']['uid'],);
		include $this->template('pay');
	}

	public function payResult($params)
	{
		global $_GPC, $_W;
		if ($params['from'] == 'notify' && $params['type'] != 'credit') {
			$status = $params['result'] == 'success' ? 1 : 0;
			$is_scan = fasle;
			$r = pdo_fetch("SELECT * FROM " . tablename('czt_wx_collection_scan_record') . " WHERE uniacid = :uniacid and tid = :tid", array(':tid' => $params['tid'], ':uniacid' => $params['uniacid']));
			if ($r) {
				$is_scan = true;
				pdo_update('czt_wx_collection_scan_record', array('openid' => $params['user'], 'type' => $params['type'], 'status' => $status), array('id' => $r['id']));
				$create_time = $r['create_time'];
			} else {
				$r = pdo_fetch("SELECT * FROM " . tablename('czt_wx_collection_self_record') . " WHERE uniacid = :uniacid and tid = :tid", array(':tid' => $params['tid'], ':uniacid' => $params['uniacid']));
				if ($r) {
					pdo_update('czt_wx_collection_self_record', array('openid' => $params['user'], 'status' => $status), array('id' => $r['id']));
					$create_time = $r['create_time'];
				} else {
					$create_time = time();
					$data = array('fee' => $params['fee'], 'openid' => $params['user'], 'tid' => $params['tid'], 'uniacid' => $params['uniacid'], 'create_time' => $create_time, 'type' => $params['type'], 'status' => $status,);
					pdo_insert('czt_wx_collection_self_record', $data);
				}
			}
			if ($status == 1) {
				$settings = $this->module['config'];
				$tpl_id = $settings['tpl_id'];
				$credit = intval($settings['credit']);
				$fee = floor($params['fee']);
				if ($is_scan && !empty($tpl_id)) {
					load()->classs('weixin.account');
					$account = WeiXinAccount::create($params['tag']['acid']);
					$AccountInfo = $account->fetchAccountInfo();
					$postdata['first'] = array('value' => '该订单已支付成功', 'color' => '#173177');
					$postdata['keyword1'] = array('value' => $AccountInfo['name'], 'color' => '#173177');
					$postdata['keyword2'] = array('value' => $params['fee'], 'color' => '#173177');
					$postdata['keyword3'] = array('value' => date('Y-m-d h:i:s', $create_time), 'color' => '#173177');
					$postdata['keyword4'] = array('value' => $params['tid'], 'color' => '#173177');
					$account->sendTplNotice($r['founder_openid'], $tpl_id, $postdata, '');
				}
				$is_send_credit = false;
				if ($fee && $credit) {
					load()->model('mc');
					$uid = mc_openid2uid($params['user']);
					if ($uid) {
						$is_send_credit = mc_credit_update($uid, 'credit1', $fee * $credit, array('1', '微信支付送积分'));
					}
				}
				if (empty($tpl_id)) {
					return;
				}
				if (!$is_scan) {
					load()->classs('weixin.account');
					$account = WeiXinAccount::create($params['tag']['acid']);
					$AccountInfo = $account->fetchAccountInfo();
				}
				$postdata['first'] = array('value' => '您好，您已支付成功', 'color' => '#173177');
				$postdata['keyword1'] = array('value' => $AccountInfo['name'], 'color' => '#173177');
				$postdata['keyword2'] = array('value' => $params['fee'], 'color' => '#173177');
				$postdata['keyword3'] = array('value' => date('Y-m-d h:i:s', $create_time), 'color' => '#173177');
				$postdata['keyword4'] = array('value' => $params['tid'], 'color' => '#173177');
				$postdata['remark'] = array('value' => ($is_send_credit ? '恭喜，此次消费系统赠送了' . $fee * $credit . '个积分给您。' : '') . '欢迎再次光临！', 'color' => '#173177');
				$account->sendTplNotice($params['user'], $tpl_id, $postdata, '');
			}
		}
		if ($params['from'] == 'return') {
			if ($params['result'] == 'success') {
				if ($params['type'] == 'credit') {
					$is_scan = fasle;
					$r = pdo_fetch("SELECT * FROM " . tablename('czt_wx_collection_scan_record') . " WHERE uniacid = :uniacid and tid = :tid", array(':tid' => $params['tid'], ':uniacid' => $params['uniacid']));
					if ($r) {
						$is_scan = true;
						pdo_update('czt_wx_collection_scan_record', array('openid' => $params['user'], 'type' => $params['type'], 'status' => 1), array('id' => $r['id']));
						$create_time = $r['create_time'];
					} else {
						$create_time = time();
						$data = array('fee' => $params['fee'], 'openid' => $params['user'], 'tid' => $params['tid'], 'uniacid' => $params['uniacid'], 'create_time' => $create_time, 'type' => $params['type'], 'status' => 1,);
						pdo_insert('czt_wx_collection_self_record', $data);
					}
					$settings = $this->module['config'];
					$tpl_id = $settings['tpl_id'];
					$credit = intval($settings['credit']);
					$fee = floor($params['fee']);
					if ($is_scan && !empty($tpl_id)) {
						load()->classs('weixin.account');
						$account = WeiXinAccount::create($_W['acid']);
						$AccountInfo = $account->fetchAccountInfo();
						$postdata['first'] = array('value' => '该订单已支付成功', 'color' => '#173177');
						$postdata['keyword1'] = array('value' => $AccountInfo['name'], 'color' => '#173177');
						$postdata['keyword2'] = array('value' => $params['fee'], 'color' => '#173177');
						$postdata['keyword3'] = array('value' => date('Y-m-d h:i:s', $create_time), 'color' => '#173177');
						$postdata['keyword4'] = array('value' => $params['tid'], 'color' => '#173177');
						$account->sendTplNotice($r['founder_openid'], $tpl_id, $postdata, '');
					}
					$is_send_credit = false;
					if ($fee && $credit) {
						load()->model('mc');
						$uid = $params['user'];
						if ($uid) {
							$is_send_credit = mc_credit_update($uid, 'credit1', $fee * $credit, array('1', '微信支付送积分'));
						}
					}
					if (!empty($tpl_id)) {
						if (!$fee || !$credit1) {
							load()->model('mc');
							$uid = $params['user'];
						}
						$fansinfo = mc_fansinfo($uid, $_W['acid'], $_W['uniacid']);
						if (!empty($fansinfo['openid'])) {
							if (!$is_scan) {
								load()->classs('weixin.account');
								$account = WeiXinAccount::create($fansinfo['acid']);
							}
							$postdata['first'] = array('value' => '您好，您已支付成功', 'color' => '#173177');
							$postdata['keyword1'] = array('value' => $_W['account']['name'], 'color' => '#173177');
							$postdata['keyword2'] = array('value' => $params['fee'], 'color' => '#173177');
							$postdata['keyword3'] = array('value' => date('Y-m-d h:i:s', $create_time), 'color' => '#173177');
							$postdata['keyword4'] = array('value' => $params['tid'], 'color' => '#173177');
							$postdata['remark'] = array('value' => ($is_send_credit ? '恭喜，此次消费系统赠送了' . $fee * $credit . '个积分给您。' : '') . '欢迎再次光临！', 'color' => '#173177');
							$account->sendTplNotice($fansinfo['openid'], $tpl_id, $postdata, '');
						}
					}
				}
				include $this->template('success');
			} else {
				message('支付失败！', '', 'error');
			}
		}
	}
}

function dump($vars, $label = '', $return = false)
{
	if (ini_get('html_errors')) {
		$content = "<pre>\n";
		if ($label != '') {
			$content .= "<strong>{$label} :</strong>\n";
		}
		$content .= htmlspecialchars(print_r($vars, true));
		$content .= "\n</pre>\n";
	} else {
		$content = $label . " :\n" . print_r($vars, true);
	}
	if ($return) {
		return $content;
	}
	echo $content;
	return null;
}

function wechat_build($params, $wechat)
{
	global $_W;
	load()->func('communication');
	$wOpt = array();
	$package = array();
	$package['appid'] = $wechat['appid'];
	$package['mch_id'] = $wechat['mchid'];
	$package['nonce_str'] = random(8);
	$package['body'] = $params['title'];
	$package['attach'] = $_W['uniacid'];
	$package['out_trade_no'] = $params['uniontid'];
	$package['total_fee'] = $params['fee'] * 100;
	$package['spbill_create_ip'] = CLIENT_IP;
	$package['time_start'] = date('YmdHis', TIMESTAMP);
	$package['time_expire'] = date('YmdHis', TIMESTAMP + 1800);
	$package['notify_url'] = $_W['siteroot'] . 'payment/wechat/notify.php';
	$package['trade_type'] = 'NATIVE';
	ksort($package, SORT_STRING);
	$string1 = '';
	foreach ($package as $key => $v) {
		if (empty($v)) {
			continue;
		}
		$string1 .= "{$key}={$v}&";
	}
	$string1 .= "key={$wechat['signkey']}";
	$package['sign'] = strtoupper(md5($string1));
	$dat = array2xml($package);
	$response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $dat);
	if (is_error($response)) {
		exit(json_encode($response));
	}
	$xml = @isimplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
	if (strval($xml->return_code) == 'FAIL') {
		exit(json_encode(error(-1, strval($xml->return_msg))));
	}
	if (strval($xml->result_code) == 'FAIL') {
		exit(json_encode(error(-1, strval($xml->err_code) . ': ' . strval($xml->err_code_des))));
	}
	$code_url = $xml->code_url;
	return $code_url;
}