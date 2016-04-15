<?php
	$profile = pdo_fetch('SELECT * FROM '.tablename('hc_hunxiao_member')." WHERE  weid = :weid  AND from_user = :from_user" , array(':weid' => $weid,':from_user' => $from_user));
	$id = $profile['id'];
	if(intval($profile['id']) && $profile['status']==0){
		include $this->template('forbidden');
		exit;
	}
	if(empty($profile)){
		$url = $this->createMobileUrl('register');
		header("location:$url");
	} else {
		if(empty($profile['headimg'])){
			$this->CheckCookie();
		}
	}
	if(!empty($profile['shareid'])){
		$highmember = pdo_fetch("SELECT realname, headimg FROM ".tablename('hc_hunxiao_member')." WHERE id = ".$profile['shareid']);
	}

	$starttime = strtotime(date('Y-m-d 00:00:00'));
	$endtime = strtotime(date('Y-m-d 23:59:59'));
	$credit = pdo_fetchcolumn("select credit from ".tablename('hc_hunxiao_credit')." where flag = 0 and weid = ".$weid." and mid = ".$id." and createtime >= ".$starttime." and createtime <= ".$endtime);
	$credit = empty($credit)?-1:$credit;
	if($op=='report'){
		$follow = pdo_fetch("select uid, follow from ".tablename('mc_mapping_fans')." where uniacid = ".$weid." and openid = '".$_W['openid']."'");
		if(empty($follow) || $follow['follow']==0){
			echo -1;
			exit;
		}
		$credit1 = pdo_fetchcolumn("select credit from ".tablename('hc_hunxiao_rules')." where weid = ".$weid);
		$creditrule = explode(",", $credit1);
		$low = intval($creditrule[0]);
		$high = intval($creditrule[1]);
		$credit1 = mt_rand($low, $high);
		$credits = array(
			'weid'=>$weid,
			'mid'=>$id,
			'credit'=>$credit1,
			'flag'=>0,
			'status'=>0,
			'total'=>1,
			'orderid'=>0,
			'goodsid'=>0,
			'createtime'=>time()
		);
		if($credit == -1){
			pdo_insert('hc_hunxiao_credit', $credits);
			$fcredit = mc_fetch($follow['uid'], array('credit1'));
			pdo_update('mc_members', array('credit1'=>$fcredit['credit1']+$credit1), array('uid'=>$follow['uid']));
			//pdo_update('hc_hunxiao_member', array('credit'=>$profile['credit']+$credit1), array('id'=>$id));
			echo $credit1;
			exit;
		} else {
			echo 0;
			exit;
		}
	}
	
	$imgname = $weid."share$id.png";
	$imgurl = "../addons/hc_hunxiao/style/images/share/$imgname";
	if(!file_exists($imgurl)){
		include "phpqrcode.php";//引入PHP QR库文件
		$value = $_W['siteroot'].'app/'.$this->createMobileUrl('index',array('mid'=>$id));
		$errorCorrectionLevel = "L";
		$matrixPointSize = "4";
		QRcode::png($value, $imgurl, $errorCorrectionLevel, $matrixPointSize);
	}
	$gzurl = pdo_fetch("select gzurl, qrpicture, description from ".tablename('hc_hunxiao_rules')." where weid = ".$weid);
	
	if($op=='shareqrcode'){
		$profile = pdo_fetch('SELECT * FROM '.tablename('hc_hunxiao_member')." WHERE id = ".$_GPC['mid']);
		$id = $profile['id'];
		include $this->template('myqrcode');
		exit;
	}
	
	if($op=='display'){
		$follow = pdo_fetch("select uid from ".tablename('mc_mapping_fans')." where uniacid = ".$weid." and openid = '".$_W['openid']."'");
		if(!empty($follow)){
			$fcredit = mc_fetch($follow['uid'], array('credit1'));
			$fcredit = $fcredit['credit1']*100/100;
		} else {
			$fcredit = 0;
		}
		$allordernum = pdo_fetchcolumn("select count(id) from ".tablename('hc_hunxiao_order')." where weid = ".$weid." and status > -1 and from_user = '".$from_user."'");
		$userdefault = pdo_fetchcolumn("select userdefault from ".tablename('hc_hunxiao_rules')." where weid = ".$weid);
		if(empty($userdefault)){
			$userdefault = 1;
		}
		$fanslevel = array();
		$lowfansid = '('.$id.')';
		$allteamid = -1;
		$lowfansids = array();
		$lowfansnum = array();
		$alllowfansnum = 0;
		$allteamnum = 0;
		$allteamnum3 = 0;
		for($i=1; $i<=$userdefault; $i++){
			$lowfansnum[$i] = 0;
			$lowfansids[$i] = $lowfansid;
			$fanslevel[$i] = pdo_fetchall("select * from ".tablename('hc_hunxiao_member')." where shareid in ".$lowfansid." and status = 1 and from_user != '".$from_user."'");
			$lowfansid = '';
			if(!empty($fanslevel[$i])){
				$alllowfansnum = sizeof($fanslevel[$i]) + $alllowfansnum;
				if($i<=3){
					//前三级下线总数
					$allteamnum3 = sizeof($fanslevel[$i]) + $allteamnum3;
				} else {
					foreach($fanslevel[$i] as $f){
						$allteamid = $f['shareid'].','.$allteamid;
					}
				}
				$lowfansnum[$i] = sizeof($fanslevel[$i]);
				foreach($fanslevel[$i] as $f){
					$lowfansid = $lowfansid.$f['id'].',';
				}
				$lowfansid = '('.trim($lowfansid, ',').')';
			} else {
				for($k=$i; $k<=$userdefault; $k++){
					$fanslevel[$k] = 0;
					$lowfansnum[$k] = 0;
				}
				break;
			}
		}
		//我的团队总数
		$allteamnum = $alllowfansnum - $allteamnum3;
		$allteamid = '('.trim($allteamid, ',').')';
		$list = array();
		$alllowordernum = 0;
		$lowordernum = array();
		$allteamordernum = 0;
		$allteamordernum3 = 0;
		for($i=1; $i<=$userdefault; $i++){
			$list[$i] = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_memberrelative') . " WHERE shareid = ".$id." and userdefault = ".$i." and weid = ".$weid." ORDER BY createtime DESC LIMIT 11");
			if(empty($list[$i])){
				for($k=$i; $k<=$userdefault; $k++){
					$list[$k] = 0;
					$lowordernum[$k] = 0;
				}
				break;
			} else {
				$alllowordernum = $alllowordernum + sizeof($list[$i]);
				if($i<=3){
					$allteamordernum3 = $allteamordernum3 + sizeof($list[$i]);
				}
			}
			$lowordernum[$i] = sizeof($list[$i]);
		}
		$allteamordernum = $alllowordernum - $allteamordernum3;
		// 总佣金
		$allcommission = pdo_fetchcolumn("select sum(commission) from ".tablename('hc_hunxiao_commission')." where flag = 0 and mid = ".$id." and weid = ".$weid);
		$allcommission = empty($allcommission)?0.00:$allcommission;
		// 已结佣
		$commissioned = pdo_fetchcolumn("select sum(commission) from ".tablename('hc_hunxiao_commission')." where (flag = 1 or flag = 2)and mid = ".$id." and weid = ".$weid);
		$commissioned = empty($commissioned)?0.00:$commissioned;
		// 可结佣
		$commissioning = $allcommission - $commissioned;
	}

	if($op=='loworder'){
		$level = heihei(intval($_GPC['level']));
		if($level >= 4){
			$level = 4;
			$list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_memberrelative') . " WHERE shareid = ".$id." and userdefault >= 4 and weid = ".$weid." ORDER BY createtime DESC");
		} else {
			$list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_memberrelative') . " WHERE shareid = ".$id." and userdefault = ".$_GPC['level']." and weid = ".$weid." ORDER BY createtime DESC");
		}
		$goods = pdo_fetchall("select id, title from ".tablename('hc_hunxiao_goods'). " where weid = ".$weid. " and status = 1");
		$orders = pdo_fetchall("select id, status from ".tablename('hc_hunxiao_order'). " where weid = ".$weid);
		$good = array();
		$order = array();
		foreach($goods as $g){
			$good[$g['id']] = $g['title'];
		}
		foreach($orders as $g){
			$order[$g['id']] = $g['status'];
		}
		include $this->template('fansorder');
		exit;
	}
	
	if($op=='lowfans'){
		$level = heihei(intval($_GPC['level']));
		if($level >= 4){
			$level = 4;
		}
		$lowfansids = $_GPC['lowfansids'];
		if(empty($lowfansids)){
			$lowfansids = "(".'-1'.")";
		}
		$fanslevel = pdo_fetchall("select * from ".tablename('hc_hunxiao_member')." where shareid in ".$lowfansids." and status = 1");
		include $this->template('myfans');
		exit;
	}
	
	include $this->template('fansindex');
?>