<?php
global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$uid=$_W["uid"];
		$uniacid=$_W['uniaccount']['uniacid'];
		if ($operation == 'add') {
			$record["begintime"]=date("Y-m-d",time());
			$record["endtime"]=date("Y-m-d",time());
			if(!empty($_GPC['id'])){
				$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_integral_game_set')." WHERE id=".$_GPC['id']);
			}
			include $this->template('game');
		}elseif($operation=='display'){
			$record=pdo_fetchall("SELECT * FROM ".tablename('netsbd_integral_game_set')." WHERE uniacid=".$uniacid);
			if(!empty($_GPC["partin"])){
				$gameid=$_GPC["partin"];
				$game=pdo_fetch("SELECT * FROM ".tablename('netsbd_integral_game_set')." WHERE uniacid=".$uniacid." AND id=".$gameid);
				$check_sql="SELECT * FROM ims_netsbd_integral_game_record WHERE state=1 AND gameid=:gameid";
				$check_record=pdo_fetch($check_sql,array(":gameid"=>$gameid));
				//如果不存在中奖记录 在执行开奖
				if(empty($check_record)){
					//这里随机取出一个参与该活动的会员
					$partin_record=pdo_fetch("SELECT r1.* FROM ims_netsbd_integral_game_record AS r1 JOIN 
					( SELECT ROUND(RAND() * (SELECT MAX(id) FROM ims_netsbd_integral_game_record)) AS id) AS r2 
					WHERE r1.id >= r2.id AND r1.uniacid=:uniacid AND r1.gameid=:gameid LIMIT 0,1",array(":uniacid"=>$uniacid,"gameid"=>$gameid));
					//随机出来的用户修改为中奖状态
					$partin_record1["state"]="1";
					$i=pdo_update("netsbd_integral_game_record",$partin_record1,array("id"=>$partin_record['id']));
				}
				//重新查询出记录
				$partin_recordnew=pdo_fetchall("SELECT r.*,m.nickname,m.realname,m.email,m.avatar from ims_netsbd_integral_game_record AS r LEFT JOIN ims_mc_members AS m on m.uid=r.uid WHERE r.uniacid=:uniacid AND r.gameid=:gameid",array(":uniacid"=>$uniacid,":gameid"=>$gameid));
			}
			if(!empty($_GPC["partin_show"])){
				$gameid=$_GPC["partin_show"];
				$game=pdo_fetch("SELECT * FROM ".tablename('netsbd_integral_game_set')." WHERE uniacid=".$uniacid." AND id=".$gameid);
				$check_sql="SELECT * FROM ims_netsbd_integral_game_record WHERE state=1 AND gameid=:gameid";
				$check_record=pdo_fetch($check_sql,array("gameid"=>$gameid));
				$partin_recordnew=pdo_fetchall("SELECT r.*,m.nickname,m.realname,m.email,m.avatar from ims_netsbd_integral_game_record AS r LEFT JOIN ims_mc_members AS m on m.uid=r.uid WHERE r.uniacid=:uniacid AND r.gameid=:gameid",array(":uniacid"=>$uniacid,":gameid"=>$gameid));
			}
			include $this->template('game');
		}elseif($operation=='del'){
			$i=pdo_delete("netsbd_integral_game_set",array("id" => $_GPC['id']));
			if($i>0){
				message('删除成功！', $this->createWebUrl('Hxsgame', array('op' => 'display')), 'success');
			}else{
				message('删除失败，请联系管理员！', $this->createWebUrl('Hxsgame', array('op' => 'display')), 'success');
			}
		}elseif ($operation == 'post') {
				$r["title"]=$_GPC['title'];
				$r["picture"]=$_GPC['picture'];
				$r["content"]=$_GPC['content'];
				$r["integral_eq_game"]=$_GPC['integral_eq_game'];
				$r["num_eq_result"]=$_GPC['num_eq_result'];
				$r["prize"]=$_GPC['prize'];
				$r["begintime"]=$_GPC['begintime'];
				$r["endtime"]=$_GPC['endtime'];
				$r["state"]=1;
				$r["ishome"]=$_GPC['ishome'];
				$r["createtime"]=TIMESTAMP;
			if(empty($_GPC['id'])){
				$r["uid"]=$uid;
				$r["uniacid"]=$uniacid;
				pdo_insert("netsbd_integral_game_set",$r);
			}else{
				$r["createtime"]=TIMESTAMP;
				pdo_update("netsbd_integral_game_set",$r,array('id' => $_GPC['id']));
			}
			message('保存成功！', $this->createWebUrl('Hxsgame', array('op' => 'display')), 'success');
		}
?>