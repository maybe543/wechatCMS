<?php
/**
 * 模块定义：规则触发
 *
 * @author 石头鱼
 * @url http://www.00393.com/
 */
defined('IN_IA') or exit('Access Denied');

class Stonefish_fightingModuleProcessor extends WeModuleProcessor {
	
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$from_user= $this->message['from'];
		$uniacid = $_W['uniacid'];
		
		//查询活动
		$sql = "SELECT title,description,start_picurl,isshow,starttime,endtime,end_title,end_description,end_picurl FROM " . tablename('stonefish_fighting_reply') . " WHERE `uniacid` = :uniacid and `rid` = :rid LIMIT 1";
		$row = pdo_fetch($sql, array(':uniacid' => $uniacid, ':rid' => $rid));
		if($row == false){
            return $this->respText("活动已取消...");
        }

        if($row['isshow'] == 0){
            return $this->respText($row['title']."－活动暂停，请稍后...");
        }

        if($row['starttime'] > time()){
            return $this->respText("活动未开始，请等待...请于".date("Y-m-d H:i:s", $row['starttime']) ."参加活动");
        }
		//查询活动
		//查询是否被屏蔽
		$lists = pdo_fetch("SELECT status FROM ".tablename('stonefish_fighting_fans')." WHERE uniacid = :uniacid and rid= :rid and from_user = :from_user", array(':uniacid' => $uniacid, ':rid' => $rid, ':from_user' => $from_user));
		if(!empty($lists)){//查询是否有记录
			if($lists['status']==0){
				$message = "亲，".$row['title']."活动中您可能有作弊行为已被管理员暂停了！请联系[".$_W['account']['name']."]管理员";
				return $this->respText($message);					
			}
		}
		//查询是否被屏蔽
		//推送分享图文内容
        if($row['endtime'] < time()){
            return $this->respNews(array(
                'Title' => $row['end_title'],
                'description' => $row['end_description'],
                'PicUrl' => toimage($row['end_picurl']),
                'Url' => $this->createMobileUrl('entry', array('rid' => $rid,'entrytype' => 'index')),
            ));
        }else{
            return $this->respNews(array(
                'Title' => $row['title'],
                'description' => $row['description'],
                'PicUrl' => toimage($row['start_picurl']),
                'Url' => $this->createMobileUrl('entry', array('rid' => $rid,'entrytype' => 'index')),
            ));
        }
		//推送分享图文内容
	}
}