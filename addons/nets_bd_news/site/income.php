<?php
global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$uid=$_W["uid"];
		$uniacid=$_W['uniaccount']['uniacid'];
		if ($operation == 'add') {
			if(!empty($_GPC['id'])){
				$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_incom')." WHERE id=:id",array("id"=>$_GPC['id']));
			}
			include $this->template('income');
		}elseif($operation=='display'){
			$record=pdo_fetchall("SELECT * FROM ".tablename('netsbd_incom')." WHERE uniacid=".$uniacid." ORDER BY id DESC");
			include $this->template('income');
		}elseif($operation=='divide'){
			$r=pdo_fetch("SELECT * FROM ".tablename('netsbd_incom')." WHERE id=:id",array(":id"=>$_GPC['id']));
			if($r["remark"]!="已分成"){
				//$beginTime=strtotime(date("Y-m-d",$r["createtime"])." 00:00:00");
				//$endTime=strtotime(date("Y-m-d",$r["createtime"])." 23:59:59");
				//该应用的会员总数
				$total_members_sql="SELECT SUM(credit1) FROM  ims_mc_members WHERE uniacid=".$uniacid;
				$total_members=pdo_fetchcolumn($total_members_sql);
				//(1)分成的sql语句,先根据每日分成的真实金额按积分分成给会员
				//(2)在把会员积分清零
				//公式 (credit1/(SELECT SUM(credit1) FROM  ims_mc_members WHERE uniacid=288))* 10
				$divide_sql="UPDATE ims_mc_members SET credit2= credit2+(credit1/".$total_members."*".$r["real_income"].") WHERE uniacid=".$uniacid.";
				INSERT ims_mc_credits_record(uid,uniacid,credittype,num,operator,createtime,remark)
				SELECT uid,uniacid,'credit2',(credit1 / ".$total_members." * ".$r["real_income"]." ) AS 'num','0' AS 'operator','".TIMESTAMP."' AS 'createtime','今日分成获得金额' AS 'remark' FROM ims_mc_members  WHERE uniacid=".$uniacid." AND credit1 >0;
				INSERT ims_mc_credits_record(uid,uniacid,credittype,num,operator,createtime,remark)
				SELECT uid,uniacid,'credit1',(credit1 / ".$total_members." * ".$r["real_income"]." * -1 ) AS 'num','0' AS 'operator','".TIMESTAMP."' AS 'createtime','今日分成积分清零' AS 'remark' FROM ims_mc_members  WHERE uniacid=".$uniacid." AND credit1 >0;
				UPDATE ims_mc_members SET credit1= 0 WHERE uniacid=".$uniacid;
				//print($divide_sql);
				$i=pdo_fetchall($divide_sql);
				if($i>0){
					$r["remark"]="已分成";
					pdo_update("netsbd_incom",$r,array("id"=>$r["id"]));
				}
			}
			
			$record=pdo_fetchall("SELECT * FROM ".tablename('netsbd_incom')." WHERE  uniacid=".$uniacid."  ORDER BY id DESC");
			
			include $this->template('income');
		}elseif($operation=='del'){
			$i=pdo_delete("netsbd_incom",array("id" => $_GPC['id']));
			if($i>0){
				message('删除成功！', $this->createWebUrl('Hxsincome', array('op' => 'display')), 'success');
			}else{
				message('删除失败，请联系管理员！', $this->createWebUrl('Hxsincome', array('op' => 'display')), 'success');
			}
		}elseif ($operation == 'post') {
				$r["real_income"]=$_GPC['real_income'];
				$r["false_income"]=$_GPC['false_income'];
				$r["remark"]=$_GPC['remark'];
				$r["createtime"]=TIMESTAMP;
			if(empty($_GPC['id'])){
				$r["uid"]=$uid;
				$r["uniacid"]=$uniacid;
				pdo_insert("netsbd_incom",$r);
			}else{
				$r["createtime"]=TIMESTAMP;
				pdo_update("netsbd_incom",$r,array('id' => $_GPC['id']));
			}
			message('保存成功！', $this->createWebUrl('Hxsincome', array('op' => 'display')), 'success');
		}
?>