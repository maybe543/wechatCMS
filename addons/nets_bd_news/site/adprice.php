<?php
global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$uid=$_W["uid"];
		$uniacid=$_W['uniaccount']['uniacid'];
		
		$record=pdo_fetchall("SELECT * FROM ".tablename('netsbd_ad_price')." WHERE uniacid=".$uniacid." order by click_price");
		if ($operation == 'display') {
			include $this->template('adprice');
		} elseif ($operation == 'del') {
			$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_ad_price')." WHERE uniacid=".$uniacid." AND id=".$_GPC["id"]);
			if(!empty($record)){
				pdo_delete("netsbd_ad_price",array("id"=>$_GPC["id"]));
				message('删除成功！', $this->createWebUrl('Hxsadprice', array('op' => 'display')), 'success');
			}
		}
		elseif ($operation == 'post') {
			$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_ad_price')." WHERE uniacid=".$uniacid." AND id=".$_GPC["id"]);
			if(empty($record)){
				$r["uid"]=$uid;
				$r["uniacid"]=$uniacid;
				$r["click_price"]=$_GPC["click_price"];
				pdo_insert("netsbd_ad_price",$r);
			}else{
				$r["click_price"]=$_GPC["click_price"];
				$r["createtime"]=TIMESTAMP;
				pdo_update("netsbd_ad_price",$r,array('id' => $record["id"]));
			}
			message('保存成功！', $this->createWebUrl('Hxsadprice', array('op' => 'display')), 'success');
			include $this->template('adprice');
		}
?>