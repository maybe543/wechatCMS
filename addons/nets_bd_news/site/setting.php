<?php
global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$uid=$_W["uid"];
		$uniacid=$_W['uniaccount']['uniacid'];
		$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_set')." WHERE uniacid=".$uniacid);
		if ($operation == 'display') {
			include $this->template('setting');
		} elseif ($operation == 'post') {
				$r["integral_eq_blance"]=$_GPC['integral_eq_blance'];
				$r["share_eq_integral"]=$_GPC['share_eq_integral'];
				$r["click_eq_integral"]=$_GPC['click_eq_integral'];
				$r["beclick_eq_integral"]=$_GPC['beclick_eq_integral'];
				$r["max_beclick"]=$_GPC['max_beclick'];
				$r["good_eq_integral"]=$_GPC['good_eq_integral'];
				$r["comment_eq_integral"]=$_GPC['comment_eq_integral'];
				$r["begood_eq_integral"]=$_GPC['begood_eq_integral'];
				$r["max_begood"]=$_GPC['max_begood'];
				$r["becomment_eq_integral"]=$_GPC['becomment_eq_integral'];
				$r["login_eq_integral"]=$_GPC['login_eq_integral'];
				$r["reregster_eq_integral"]=$_GPC['reregster_eq_integral'];
				$r["max_share_today"]=$_GPC['max_share_today'];
				$r["max_click_today"]=$_GPC['max_click_today'];
				$r["max_good_today"]=$_GPC['max_good_today'];
				$r["max_comment_today"]=$_GPC['max_comment_today'];
				$r["max_becomment"]=$_GPC['max_becomment'];
				$r["today_maxregister"]=$_GPC['today_maxregister'];
				$r["clickad_eq_integral"]=$_GPC['clickad_eq_integral'];
				$r["today_maxclickad"]=$_GPC['today_maxclickad'];
				$r["cashshare_eq_integral"]=$_GPC['cashshare_eq_integral'];
				$r["today_maxshare"]=$_GPC['today_maxshare'];
				$r["changegood_eq_integral"]=$_GPC['changegood_eq_integral'];
				$r["today_maxchange"]=$_GPC['today_maxchange'];
				$r["palygame_eq_integral"]=$_GPC['palygame_eq_integral'];
				$r["today_maxpalygame"]=$_GPC['today_maxpalygame'];
				$r["today_income"]=$_GPC['today_income'];
				$r["register_eq_money"]=$_GPC['register_eq_money'];
				$r["member_level1dis"]=$_GPC['member_level1dis'];
				$r["member_level2dis"]=$_GPC['member_level2dis'];
				$r["member_level3dis"]=$_GPC['member_level3dis'];
				
				$r["template_msg1"]=$_GPC['template_msg1'];
				$r["template_msg2"]=$_GPC['template_msg2'];
				$r["template_msg3"]=$_GPC['template_msg3'];
				$r["template_msg4"]=$_GPC['template_msg4'];
				
				$r["min_cashmoney"]=$_GPC['min_cashmoney'];
				$r["createtime"]=TIMESTAMP;
			if(empty($record)){
				$r["uid"]=$uid;
				$r["uniacid"]=$uniacid;
				pdo_insert("netsbd_set",$r);
			}else{
				$r["createtime"]=TIMESTAMP;
				pdo_update("netsbd_set",$r,array('id' => $record["id"]));
			}
			message('保存成功！', $this->createWebUrl('Hxsset', array('op' => 'display')), 'success');
			include $this->template('setting');
		}
?>