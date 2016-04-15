<?php
global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$uid=$_W["uid"];
		$uniacid=$_W['uniaccount']['uniacid'];
		if ($operation == 'add') {
			if(!empty($_GPC['id'])){
				$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_adlist')." WHERE id=".$_GPC['id']);
			}
			include $this->template('adlist');
		}elseif($operation=='display'){
			$record=pdo_fetchall("SELECT * FROM ".tablename('netsbd_adlist')." WHERE uniacid=".$uniacid);
			include $this->template('adlist');
		}elseif($operation=='del'){
			$r=pdo_fetch("SELECT * FROM ".tablename('netsbd_adlist')." WHERE id=".$_GPC['id']);
			if(!empty($r)){
				$i=pdo_delete("netsbd_adlist",array("id" => $_GPC['id']));
				if($i>0){
					message('删除成功！', $this->createWebUrl('Hxsadlist', array('op' => 'display')), 'success');
				}else{
					message('删除失败，请联系管理员！', $this->createWebUrl('Hxsadlist', array('op' => 'display')), 'success');
				}
			}
		}elseif($operation=='off'){
			$r=pdo_fetch("SELECT * FROM ".tablename('netsbd_adlist')." WHERE id=".$_GPC['id']);
			if($r["state"]==1){
				$r["state"]=0;
			}else{
				$r["state"]=1;
			}
			$i=pdo_update("netsbd_adlist",$r,array("id" => $_GPC['id']));
			if($i>0){
				message('设置成功！', $this->createWebUrl('Hxsadlist', array('op' => 'display')), 'success');
			}else{
				message('设置失败，请联系管理员！', $this->createWebUrl('Hxsadlist', array('op' => 'display')), 'success');
			}
		}elseif ($operation == 'post') {
				$r["name"]=$_GPC['name'];
				$r["remark"]=$_GPC['remark'];
				$r["ismenu"]=$_GPC['ismenu'];
				$r["ishide"]=$_GPC['ishide'];
				$r["createtime"]=TIMESTAMP;
			if(empty($_GPC['id'])){
				$r["uid"]=$uid;
				$r["uniacid"]=$uniacid;
				pdo_insert("netsbd_adlist",$r);
			}else{
				$r["createtime"]=TIMESTAMP;
				pdo_update("netsbd_adlist",$r,array('id' => $_GPC['id']));
			}
			message('保存成功！', $this->createWebUrl('Hxsadlist', array('op' => 'display')), 'success');
		}
?>