<?php
global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$uid=$_W["uid"];
		$uniacid=$_W['uniaccount']['uniacid'];
		if ($operation == 'add') {
			if(!empty($_GPC['id'])){
				$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_news_category')." WHERE id=".$_GPC['id']." ORDER BY sort,id");
			}
			include $this->template('category');
		}elseif($operation=='display'){
			$record=pdo_fetchall("SELECT * FROM ".tablename('netsbd_news_category')." WHERE uniacid=".$uniacid." ORDER BY sort,id");
			include $this->template('category');
		}elseif($operation=='del'){
			$i=pdo_delete("netsbd_news_category",array("id" => $_GPC['id']));
			if($i>0){
				message('删除成功！', $this->createWebUrl('Hxscategory', array('op' => 'display')), 'success');
			}else{
				message('删除失败，请联系管理员！', $this->createWebUrl('Hxscategory', array('op' => 'display')), 'success');
			}
		}elseif ($operation == 'post') {
				$r["name"]=$_GPC['name'];
				$r["remark"]=$_GPC['remark'];
				$r["sort"]=$_GPC['sort'];
				$r["ismenu"]=$_GPC['ismenu'];
				$r["ishide"]=$_GPC['ishide'];
				$r["createtime"]=TIMESTAMP;
			if(empty($_GPC['id'])){
				$r["uid"]=$uid;
				$r["uniacid"]=$uniacid;
				pdo_insert("netsbd_news_category",$r);
			}else{
				$r["createtime"]=TIMESTAMP;
				pdo_update("netsbd_news_category",$r,array('id' => $_GPC['id']));
			}
			message('保存成功！', $this->createWebUrl('Hxscategory', array('op' => 'display')), 'success');
		}
?>