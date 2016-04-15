<?php
/**
 * 女神来了模块定义
 */
defined('IN_IA') or exit('Access Denied');

        if (empty($tfrom_user)) {
            die(json_encode(array("result" => 0, "error" => "参数错误,未发现该参赛者")));
        } 
        if (empty($from_user)) {
            die(json_encode(array("result" => 0, "error" => "参数错误，你的信息有误，请重新打开")));
        }
        $zans = pdo_fetch("SELECT uid,zans FROM " . tablename($this->table_users) . " WHERE rid=:rid AND uniacid = :uniacid AND from_user =:from_user LIMIT 1", array(":rid" => $rid,":uniacid" => $uniacid,":from_user" => $tfrom_user));
        if (empty($zans)) {
            die(json_encode(array("result" => 0, "error" => "参数错误，未发现该参赛者")));
        }
		
        $zan = pdo_fetch("SELECT * from " . tablename($this->table_bbsreply) . " WHERE from_user=:from_user AND tfrom_user=:tfrom_user AND rid=:rid  AND uniacid=:uniacid AND zan = 1 limit 1", array(":from_user" => $from_user, ":tfrom_user" => $tfrom_user, ":rid" => $rid, ":uniacid" => $uniacid));
		 $status = intval($zan['zan']);
		//die(json_encode(array("result" => 0, "error" => $status)));
		if ($status) {
			$status = $status - 1;
			$zans = intval($zans['zans']) - 1;
			pdo_update($this->table_users, array("zans" =>$zans), array("from_user" => $tfrom_user, "rid" =>$rid, "uniacid" =>$uniacid));
			pdo_delete($this->table_bbsreply, array("from_user" => $from_user, "tfrom_user" =>$tfrom_user, 'zan' => 1, "rid" =>$rid, "uniacid" =>$uniacid));
			die(json_encode(array("result" => 1, "status" => $status,"zans" => $zans,"flag" => 0)));		
		}else{
			$data = array(
				'rid' => $rid,
				'uniacid' => $uniacid,
				'tid' => $zans['uid'],//帖子的ID
				'from_user' => $from_user,
				'tfrom_user' => $tfrom_user,
				'nickname' => $nickname,
				'avatar' => $avatar,
				'ip' => getip(),
				'zan' => 1,
				'createtime' => time(),
			);
			$data['iparr'] = getiparr($data['ip']);
			pdo_insert($this->table_bbsreply, $data);
			$zans = intval($zans['zans']) + 1;
			pdo_update($this->table_users, array("zans" =>$zans), array("from_user" => $tfrom_user, "rid" =>$rid, "uniacid" =>$uniacid));

			$reply_id = pdo_insertid();

			pdo_update($this->table_bbsreply, array('storey' => $reply_id), array('uniacid' => $uniacid, 'rid' => $rid, 'id' => $reply_id ));
			die(json_encode(array("result" => 1, "zans" => $zans,"flag" => 1)));
		}
		