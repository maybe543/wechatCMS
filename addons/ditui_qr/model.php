<?php
class record{

	public static $tb = 'ditui_records';
	

	//获取指定地推人员的成绩
	public function staff($staffId,$index=1,$size=1){
		$wxModel = new wxInfo();
		$res = pdo_fetchall("SELECT `openid`,`scan_time` FROM " . tablename(self::$tb) . " WHERE `staff_id`=:staff GROUP BY `openid` ORDER BY `scan_time` DESC LIMIT ".($index-1)*$size .", {$size}",array(':staff'=>$staffId));
		if(!empty($res)){
			foreach ($res as $key => $item) {
				$res[$key]['wx'] = $wxModel->openid($item['openid']);
			}
		}
		return $res;
	}

	public function count_staff($staff){
		return count(pdo_fetchall("SELECT DISTINCT `openid` FROM ".tablename(self::$tb)." WHERE `staff_id`=:staff  GROUP BY `openid` ORDER BY `scan_time` DESC",array(':staff'=>$staff)));
	}


	//删除指定地推人员的推广记录
	public function del_staff($staff){
		return pdo_delete(self::$tb, array('staff_id'=>$staff));
	}

	//获取指定地推活动的成绩
	public function activity($activityId,$index=1,$size=1){
		$wxModel = new wxInfo();
		$res = pdo_fetchall("SELECT `openid`,`scan_time` FROM " . tablename(self::$tb) . " WHERE `activity_id`=:activity AND `staff_id`!=0 GROUP BY `openid` ORDER BY `scan_time` DESC LIMIT ".($index-1)*$size .", {$size}",array(':activity'=>$activityId));
		if(!empty($res)){
			foreach ($res as $key => $item) {
				$res[$key]['wx'] = $wxModel->openid($item['openid']);
			}
		}
		return $res;
	}

	public function count_activity($activity){
		return count(pdo_fetchall("SELECT DISTINCT `openid` FROM ".tablename(self::$tb)." WHERE `activity_id`=:activity AND `staff_id`!=0  GROUP BY `openid` ORDER BY `scan_time` DESC",array(':activity'=>$activity)));
	}


	//删除指定推广活动的推广记录
	public function del_activity($activity){
		return pdo_delete(self::$tb, array('activity_id'=>$activity));
	}

}

class wxInfo{
	//根据OPENID返回用户信息
	public function openid($openid){
		global $_W;
		return pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE `uniacid`=:uniacid AND `openid`=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
		
	}
}

class activity{
	public static $tb = 'ditui_activity';

	public function add_new($activity){
		global $_W;
		$activity['uniacid'] = $_W['uniacid'];

		return pdo_insert(self::$tb, $activity);
	}

	public function lists(){
		global $_W;
		return pdo_fetchall("SELECT * FROM " . tablename(self::$tb) . " WHERE `uniacid`=:uniacid",array(':uniacid'=>$_W['uniacid']));
	}

	public function del($id){
		global $_W;
		return pdo_delete(self::$tb, array('activity_id'=>$id,'uniacid'=>$_W['uniacid']));
	}

	public function item($activityId){
		global $_W;
		return pdo_fetch("SELECT * FROM ".tablename(self::$tb)." WHERE `uniacid`=:uniacid AND `activity_id`=:activityid",array(':uniacid'=>$_W['uniacid'],':activityid'=>$activityId));
	}

	public function modify($activity,$id){
		global $_W;
		return pdo_update(self::$tb, $activity, array('activity_id'=>$id));
	}
}

class staff{

	public static $tb = 'ditui_staff';

	//获取属于自己的QR
	public function mk_qr($openid){
		$QrModel = new qr();
		$keyword = 'ditui_'.$openid;
		//根据关键词，创建二维码
		$res = $QrModel->createQr($keyword);
		if(is_array($res)){
			message("公众平台返回接口错误. <br />错误代码为: {$res['errorcode']} <br />错误信息为: {$res['message']}");
			break;
		}else{
			//根据关键词，创建指定规则
			$ruleModel = new ruleModel();
			$res = $ruleModel->mkRule($keyword);
			if(false === $res){
				message('回复规则保存失败','','error');
				break;
			}
			
		}
	}

	public function exists($staff){
		$res = pdo_fetch("SELECT * FROM ".tablename(self::$tb)." WHERE `openid`=:openid AND `activity_id`=:activityid",array(':openid'=>$staff['openid'],':activityid'=>$staff['activity_id']));
		return !empty($res);
	}

	public function add_new($staff){
		global $_W;

		return pdo_insert(self::$tb, $staff);
	}

	//根据活动ID，返回其地推人员信息
	public function activity($activityId){
		return pdo_fetchall("SELECT * FROM " . tablename(self::$tb) . " WHERE `activity_id`=:activityid",array(':activityid'=>$activityId));
	}

	public function item($staffId){
		global $_W;
		$res =  pdo_fetch("SELECT * FROM ".tablename(self::$tb)." WHERE `staff_id`=:staff",array(':staff'=>$staffId));
		if(!empty($res)){
			$wxModel = new wxInfo();
			$res['wx'] = $wxModel->openid($res['openid']);
		}
		return $res;
	}

	public function del($staff){
		return pdo_delete(self::$tb, array('staff_id'=>$staff));

	}


}

class qr{

	/**
	 * 根据提供信息，使用系统内置生成二维码的方式来生成二维码
	 * 生成的二维码详细信息依然保存在系统二维码表qrcode中
	 * @param $keyWord string 二维码关键词
	 * */
	public function createQr($keyword){
		global $_W,$_GPC;
		$acid = pdo_fetchcolumn("SELECT `acid` FROM ".tablename('account')." WHERE `uniacid`=:uniacid",array(':uniacid'=>$_W['uniacid']));
		
		load()->func('communication');
		$res = pdo_fetch("SELECT * FROM ".tablename('qrcode')." WHERE `keyword`=:keyword AND `acid` = :acid AND `uniacid` = :uniacid",array(':keyword'=>$keyword,':acid' => $acid, ':uniacid'=>$_W['uniacid']));
		if(!empty($res)){
			//关键词已存在，不重复制作二维码
			return true;
		}

		$barcode = array(
				'expire_seconds' => '',
				'action_name' => '',
				'action_info' => array(
					'scene' => array('scene_id' => ''),
				),
		);
		
		$uniacccount = WeAccount::create($acid);
		
		$qrcid = pdo_fetchcolumn("SELECT `qrcid` FROM ".tablename('qrcode')." WHERE `acid` = :acid AND `model` = '2' ORDER BY `qrcid` DESC", array(':acid' => $acid));
		$barcode['action_info']['scene']['scene_id'] = !empty($qrcid) ? ($qrcid+1) : 1;
		if ($barcode['action_info']['scene']['scene_id'] > 100000) {
			message('抱歉，永久二维码已经生成最大数量，请先删除一些。');
		}
		$barcode['action_name'] = 'QR_LIMIT_SCENE';
		$result = $uniacccount->barCodeCreateFixed($barcode);
		
		if(!is_error($result)) {
			$insert = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $acid,
				'qrcid' => $barcode['action_info']['scene']['scene_id'],
				'keyword' => $keyword,
				'name' => '地推二维码',
				'model' => 2,//永久型二维码
				'ticket' => $result['ticket'],
				'expire' => $result['expire_seconds'],
				'createtime' => TIMESTAMP,
				'status' => '1',
			);
			$fieldExists = pdo_fieldexists('qrcode', 'type');
			if($fieldExists){
				$insert['type'] = 'scene';
			}
			pdo_insert('qrcode', $insert);
			
			return true;
		} else {
			return $result;
		}
	}


	/**
	 * 判断是否存在对应关键字的二维码
	 * @return boolean 
	 * */
	public function isValidKeyword($keyword){
		global $_W;
		$res = pdo_fetch("SELECT * FROM ".tablename('qrcode')." WHERE `keyword`=:keyword  AND `uniacid` = :uniacid",array(':keyword'=>$keyword, ':uniacid'=>$_W['uniacid']));
		if(!empty($res)){
			return $res;
		}
		return false;
	}

}

class ruleModel{
	public function mkRule($keyword,$rid=''){
		load()->model('module');
		global $_W;
		$keywords = @json_decode(htmlspecialchars_decode('[{"type":1,"content":"'.$keyword.'"}]'), true);
		$rule = array(
			'uniacid' => $_W['uniacid'],
			'name' => '地推二维码',
			'module' => 'ditui_qr',
			'status' => 1,
			'displayorder' => 1,
		);

		$module = WeUtility::createModule('scan_videopro');
		
		if(empty($module)) {
			message('抱歉，模块不存在请重新安装模块！');
		}
		$msg = $module->fieldsFormValidate();
		
		if(is_string($msg) && trim($msg) != '') {
			message($msg);
		}
		// if (!empty($rid)) {
		// 	$result = pdo_update('rule', $rule, array('id' => $rid));
		// } else {
			$result = pdo_insert('rule', $rule);
			$rid = pdo_insertid();
		// }
		// if (!empty($rid)) {
			$delrid = pdo_fetchcolumn("SELECT `rid` FROM " . tablename('rule_keyword') . " WHERE `content`=:content AND `uniacid`=:uniacid",array(':content'=>$keyword,':uniacid'=>$_W['uniacid']));
			
			$sql = 'DELETE FROM '. tablename('rule') . ' WHERE `id`=:rid';
			$pars = array();
			$pars[':rid'] = $delrid;
			pdo_query($sql, $pars);
	
			$sql = 'DELETE FROM '. tablename('rule_keyword') . ' WHERE `rid`=:rid';
			$pars = array();
			$pars[':rid'] = $delrid;
			pdo_query($sql, $pars);
	
			$rowtpl = array(
				'rid' => $rid,
				'uniacid' => $_W['uniacid'],
				'module' => $rule['module'],
				'status' => $rule['status'],
				'displayorder' => $rule['displayorder'],
			);
			foreach($keywords as $kw) {
				$krow = $rowtpl;
				$krow['type'] = range_limit($kw['type'], 1, 4);
				$krow['content'] = $kw['content'];
				pdo_insert('rule_keyword', $krow);
			}
			// $rowtpl['incontent'] = $_GPC['incontent'];
			$module->fieldsFormSubmit($rid);
			return true;
		// } else {
		// 	return false;
			
		// }
	
	}
}


