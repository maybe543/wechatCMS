<?php
/**
 * 切粽子模块处理程序
 *
 * @author desmond
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Ditui_qrModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$content = $this->message['content'];
		$conts = explode('_',$content);
		$record['staff_id'] = $conts[1];
		$record['scan_time'] = time();
		$record['openid'] = $_W['openid'];
		$activityId = pdo_fetchcolumn("SELECT `activity_id` FROM ".tablename('ditui_staff')." WHERE `staff_id`=:staffid",array(':staffid'=>$record['staff_id']));
		$record['activity_id'] = $activityId;
		$exists = pdo_fetch("SELECT * FROM ".tablename('ditui_records')." WHERE `openid`=:openid AND `activity_id`=:activity",array(':openid'=>$_W['openid'],':activity'=>$activityId));
		if(!$exists){
			pdo_insert('ditui_records', $record);
		}
		
		
		$msg = pdo_fetch("SELECT * FROM ".tablename('ditui_activity')." WHERE `activity_id`=:activityid",array(':activityid'=>$activityId));
		$new['title'] = $msg['msg_title'];
		$new['description'] = $msg['msg_remark'];
		$new['picurl'] = tomedia($msg['msg_thumb']);
		$new['url'] = $msg['msg_link'];
		$news[] = $new;
		return $this->respNews($news);
	}
}