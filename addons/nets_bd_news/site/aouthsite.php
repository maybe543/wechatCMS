<?php
global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$uid=$_W["uid"];
		$uniacid=$_W['uniaccount']['uniacid'];
		
		$record=pdo_fetchall("SELECT * FROM ".tablename('netsbd_authsite')." order by id DESC");
		if ($operation == 'display') {
			include $this->template('aouthsite');
		} elseif ($operation == 'del') {
			$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_authsite')." WHERE id=".$_GPC["id"]);
			if(!empty($record)){
				pdo_delete("netsbd_authsite",array("id"=>$_GPC["id"]));
				message('删除成功！', $this->createWebUrl('Hxsadprice', array('op' => 'display')), 'success');
			}
		}
		elseif ($operation == 'post') {
			$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_authsite')." WHERE id=".$_GPC["id"]);
			if(empty($record)){
				$r["site_name"]=$_GPC["site_name"];
				$r["site_url"]=$_GPC["site_url"];
				$r["site_ip"]=$_GPC["site_ip"];
				$r["site_state"]=$_GPC["site_state"];
				$r["site_createtime"]=TIMESTAMP;
				pdo_insert("netsbd_authsite",$r);
			}else{
				$r["site_name"]=$_GPC["site_name"];
				$r["site_url"]=$_GPC["site_url"];
				$r["site_ip"]=$_GPC["site_ip"];
				$r["site_state"]=$_GPC["site_state"];
				$r["site_createtime"]=TIMESTAMP;
				pdo_update("netsbd_authsite",$r,array('id' => $record["id"]));
			}
			message('保存成功！', $this->createWebUrl('Hxsaouthsite', array('op' => 'display')), 'success');
			include $this->template('aouthsite');
		}
?>