<?php
/**
 * 加粉神器（扫码版）模块微站定义
 *
 * @author 华轩科技
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_qrModuleSite extends WeModuleSite {
	public $table_reply = 'hx_qr_reply';
	public function doWebLog() {
		//这个操作被定义用来呈现 规则列表
		global $_W,$_GPC;
		$reply_id = intval($_GPC['reply_id']);
		$reply = pdo_fetch("SELECT *  from ".tablename($this->table_reply)." where id='{$reply_id}'");
		if (empty($reply)) {
			message("活动不存在或已经被删除");
		}
		load()->model('mc');
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$where = " WHERE `uniacid` = '{$_W['uniacid']}' AND reply_id = '{$reply_id}' AND `status` = '1'";
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('hx_qr_user') . $where);
		$list = pdo_fetchall("SELECT *,{$reply['click_credit']}*first_level+{$reply['sub_click_credit']}*secend_level+{$reply['newbie_credit']} num FROM " . tablename('hx_qr_user') . $where . " ORDER BY num DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);
		//print_r($list);
		$pager = pagination($total, $pindex, $psize);
		include $this->template('log');
	}
	private function getusernum($reply_id){
		return pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hx_qr_user') . " WHERE reply_id = '{$reply_id}'");
	}
	public function doWebList() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->table_reply)." WHERE uniacid = '{$_W['uniacid']}' ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_reply) . " WHERE uniacid = '{$_W['uniacid']}'");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('list');
	}
	public function doWebCredit() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W, $_GPC;
		$uniacid=$_W["uniacid"];
		$where="";
		if ($_GPC['s'] != 0) {
			$where .= "AND status = {$_GPC['s']}";
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("SELECT *  from ".tablename('hx_qr_apply')." where uniacid='{$uniacid}' {$where} order by id asc LIMIT ". ($pindex -1) * $psize . ',' .$psize );
		$total = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename('hx_qr_apply')." where uniacid='{$uniacid}' {$where} order by id asc");
		$pager = pagination($total, $pindex, $psize);
		load()->func('tpl');
		include $this->template('clist');
	}
	public function doWebDelete(){
		global $_GPC, $_W;
		if(!empty($id)){
			$set = pdo_delete('hx_qr_apply', array('id' => $_GPC['id']));	
			message('成功删除本条记录！', referer(), 'success');	
		}
	}
	public function doWebManager(){
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$item = pdo_fetch("SELECT *  from ".tablename('hx_qr_apply')." where id='{$id}'");
		load()->func('tpl');
		$reason = iunserializer($item['remark']);
		$r = $reason[$item['status']];
		if (checksubmit('submit')) {
			if ($_GPC['status'] == '-2' && empty($_GPC['reason'])) {
				message('请输入审核失败原因');
			}
			$data['user'] = $_GPC['user'];
			$data['time'] = time();
			$data['reason'] = $_GPC['reason'];
			$reason[$_GPC['status']] = $data;
			pdo_update('hx_qr_apply',array('status'=>$_GPC['status'],'remark'=>iserializer($reason)),array('id'=>$id));
			message('操作成功',$this->createWebUrl('list'),'success');
		}
		include $this->template('manager');
	}
	public function doMobileMy() {
		//这个操作被定义用来呈现 微站个人中心导航
		global $_W,$_GPC;
		load()->model('mc');
		$profile = mc_fetch($_W['member']['uid']);
		$openid = $_W['openid'];
		$credit = mc_credit_fetch(mc_openid2uid($openid,'credit2'));
		$apply = pdo_fetch("SELECT *  from ".tablename('hx_qr_apply')." ORDER BY id DESC LIMIT 1");
		$log = pdo_fetch("SELECT * FROM ".tablename('hx_qr_user') . " WHERE uniacid = '{$_W['uniacid']}' AND openid = '{$openid}'");
		if (empty($log)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->table_reply)." WHERE uniacid='{$_W['uniacid']}' order by id desc");
			message('抱歉，您尚未参加推广活动',$this->createMobileUrl('log',array('reply_id'=>$reply['id'])),'error');
		}
		$reply_id = $log['reply_id'];
		include $this->template('main');
	}
	public function doMobileLog() {
		global $_W,$_GPC;
		$reply_id = intval($_GPC['reply_id']);
		$reply = pdo_fetch("SELECT *  from ".tablename($this->table_reply)." where id='{$reply_id}'");
		if (empty($reply)) {
			message("活动不存在或已经被删除");
		}
		load()->model('mc');
		$openid = $_W['openid'];
		$credit = mc_credit_fetch(mc_openid2uid($openid,'credit2'));
		$my = pdo_fetch("SELECT * FROM ".tablename('hx_qr_user') . " WHERE uniacid = '{$_W['uniacid']}' AND openid = '{$openid}'");
		load()->model('mc');
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$where = " WHERE `uniacid` = '{$_W['uniacid']}' AND reply_id = '{$reply_id}' AND `status` = '1'";
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('hx_qr_user') . $where);
		$list = pdo_fetchall("SELECT *,{$reply['click_credit']}*first_level+{$reply['sub_click_credit']}*secend_level+{$reply['newbie_credit']} num FROM " . tablename('hx_qr_user') . $where . " ORDER BY num DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);
		$pager = pagination($total, $pindex, $psize);
		include $this->template('log');
	}
	public function doMobileApply() {
		//这个操作被定义用来呈现 微站个人中心导航
		global $_W, $_GPC;
		load()->model('mc');
		$openid = $_W['fans']['from_user'];
		$uid = mc_openid2uid($openid);
		$minnum = '100.00';
		$credit_type = 'credit2';
		$yue = mc_credit_fetch($uid);
		$ff_log = pdo_fetch("SELECT * FROM " . tablename('hx_qr_user') . " WHERE `uniacid`='{$_W['uniacid']}' AND `openid`='{$openid}'");
		$profile = mc_fetch($openid);
		if (checksubmit('submit')) {
			if ($_GPC['type'] == '1' && empty($_GPC['alipay'])) {
				message('参数错误，请返回修改');
			}
			if ($_GPC['type'] == '2' && empty($_GPC['cardid'])) {
				message('参数错误，请返回修改');
			}
			$remark['1']['user'] = $_GPC['realname'];
			$remark['1']['time'] = time();
			$remark['1']['reason'] = '';
			$data = array(
				'uniacid' => $_W['uniacid'],
				'uid' => $uid,
				'realname' => $_GPC['realname'],
				'qq' => $_GPC['qq'],
				'type' => intval($_GPC['type']),
				'alipay' => $_GPC['alipay'],
				'cardid' => $_GPC['cardid'],
				'cardfrom' => $_GPC['cardfrom'],
				'cardname' => $_GPC['cardname'],
				'credit2' => $_GPC['credit2'],
				'mobile' => $_GPC['mobile'],
				'createtime' => time(),
				'status' => '1',//1.申请提现 2.已审核 -2 审核失败 3.审核通过待支付 4.已支付
				'remark' => iserializer($remark),
				);
			pdo_insert('hx_qr_apply',$data);
			mc_credit_update($uid,$credit_type,'-'.$_GPC['credit2'],array('1','申请提现'));
			message('提现成功',$this->createMobileUrl('myapply'),'success');
		}
		include $this->template('apply');
	}

	public function doMobileMyapply() {
		//这个操作被定义用来呈现 微站个人中心导航
		global $_W, $_GPC;
		$uniacid=$_W["uniacid"];
		load()->model('mc');
		$openid = $_W['fans']['from_user'];
		$uid = mc_openid2uid($openid);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("SELECT *  from ".tablename('hx_qr_apply')." where uniacid='{$uniacid}' AND uid = '{$uid}' order by id asc LIMIT ". ($pindex -1) * $psize . ',' .$psize );
		$total = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename('hx_qr_apply')." where uniacid='{$uniacid}' AND uid = '{$uid}' order by id asc");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('myapply');
	}

}