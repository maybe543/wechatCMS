<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
		$vfrom = $_GPC['do'];
		if ($reply['ipannounce'] == 1) {
			$announce = pdo_fetchall("SELECT nickname,content,createtime,url FROM " . tablename($this->table_announce) . " WHERE uniacid= '{$uniacid}' AND rid= '{$rid}' ORDER BY id DESC");
		}
		//赞助商
		if ($reply['isvotexq'] == 1) {
			$advs = pdo_fetchall("SELECT advname,link,thumb FROM " . tablename($this->table_advs) . " WHERE enabled=1 AND ismiaoxian = 0 AND uniacid= '{$uniacid}'  AND rid= '{$rid}' ORDER BY displayorder ASC");
		}
	
		//查询自己是否参与活动
		if(!empty($from_user)) {
		    $mygift = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid));
		   
			
		}
		
		
		
		//查询是否参与活动
		if(!empty($tfrom_user)) {
		    $user = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $tfrom_user,':rid' => $rid));
		  	if ($user['status'] != 1 && $tfrom_user != $from_user) {
				$urlstatus =  $_W['siteroot'] .'app/'.$this->createMobileUrl('photosvote',array('rid'=> $rid));
				echo "<script>alert('ID:".$user['uid']." 号选手正在审核中，请查看其他选手，谢谢！');location.href='".$urlstatus."';</script>";     
				die();
		  		//message('该选手正在审核中，请查看其他选手，谢谢！',$this->createMobileUrl('photosvote',array('rid'=> $rid)),'error');
		  	}

			$str = array('&'=>'%26');
			$url = $_W['siteroot'] .'app/'.$this->createMobileUrl('shareuserview', array('rid' => $rid,'duli'=> '1', 'fromuser' => $from_user, 'tfrom_user' => $tfrom_user));
			
			$url = $this->dwz($url);			
			$uavatar = toimage($user['avatar']);
			$ewmavatar = $_W['siteroot'] . 'attachment/headimg_'.$_W['acid'].'.jpg';
		
		  if ($user) {	
				$yuedu = $tfrom_user.$from_user.$rid.$uniacid;
//setcookie("user_yuedu", -10000);	
				if ($_COOKIE["user_yuedu"] != $yuedu) {
					 pdo_update($this->table_users, array('hits' => $user['hits']+1), array('rid' => $rid, 'from_user' => $tfrom_user));
					 setcookie("user_yuedu", $yuedu, time()+3600*24);
				}
				//print_r($tfrom_user);
		    }else{
				$url = $_W['siteroot'] .'app/'.$this->createMobileUrl('photosvote', array('rid' => $rid));
				header("location:$url");
				exit;
			}
			
		}
		$sharenum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->table_data)." WHERE uniacid = :uniacid and tfrom_user = :tfrom_user and rid = :rid", array(':uniacid' => $uniacid,':tfrom_user' => $tfrom_user,':rid' => $rid)) + pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->table_data)." WHERE uniacid = :uniacid and fromuser = :fromuser and rid = :rid", array(':uniacid' => $uniacid,':fromuser' => $tfrom_user,':rid' => $rid)) + $user['sharenum'];
		
		//$picarr = $this->getpicarr($uniacid,$reply['tpxz'],$tfrom_user,$rid);
		$fmimage = $this->getpicarr($uniacid,$rid, $tfrom_user,1);
		$picarrs =  pdo_fetchall("SELECT id, photos,from_user FROM ".tablename($this->table_users_picarr)." WHERE uniacid = :uniacid AND from_user = :from_user AND rid = :rid ORDER BY isfm DESC", array(':uniacid' => $uniacid,':from_user' => $user['from_user'],':rid' => $rid));
		$level = $this->fmvipleavel($rid, $uniacid, $user['from_user']);
		$starttime=mktime(0,0,0);//当天：00：00：00
		$endtime = mktime(23,59,59);//当天：23：59：59
		$times = '';
		$times .= ' AND createtime >=' .$starttime;
		$times .= ' AND createtime <=' .$endtime;
		
		$uservote = pdo_fetch("SELECT * FROM ".tablename($this->table_log)." WHERE uniacid = :uniacid AND from_user = :from_user  AND tfrom_user = :tfrom_user AND rid = :rid", array(':uniacid' => $uniacid,':from_user' => $from_user,':tfrom_user' => $tfrom_user,':rid' => $rid));
		$uallonetp = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND tfrom_user = :tfrom_user AND rid = :rid  ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from_user, ':tfrom_user' => $tfrom_user,':rid' => $rid));
		
		$udayonetp = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND tfrom_user = :tfrom_user AND rid = :rid '.$times.' ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from_user, ':tfrom_user' => $tfrom_user,':rid' => $rid));
		if ($reply['isvoteusers']) {
			$voteuserlist = pdo_fetchall('SELECT avatar,nickname FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND rid = :rid  AND tfrom_user = :tfrom_user GROUP BY `nickname` ORDER BY `id` DESC LIMIT 5', array(':uniacid' => $uniacid,':rid' => $rid,':tfrom_user' => $tfrom_user));
		}

		if ($reply['isbbsreply'] == 1) {//开启评论
			//取得用户列表
			$bbsreply = pdo_fetchall("SELECT avatar,nickname,from_user,content,createtime FROM ".tablename($this->table_bbsreply)." WHERE uniacid = :uniacid AND tfrom_user = :tfrom_user AND rid = :rid ORDER BY `id` DESC LIMIT 10",  array(':uniacid' => $uniacid,':tfrom_user' => $tfrom_user,':rid' => $rid));
			$btotal = $this->getcommentnum($rid,$uniacid,$tfrom_user);
		}		
		$votetime = $reply['votetime']*3600*24;
		$isvtime = TIMESTAMP - $user['createtime'];
		$ttime = $votetime - $isvtime;
		
		if ($ttime > 0) {
			$totaltime = $ttime;
		} else {
			$totaltime = 0;
		}
		//每个奖品的位置
		//虚拟人数据配置
		$now = time();
		if($now-$reply['xuninum_time']>$reply['xuninumtime']){
		    pdo_update($this->table_reply_display, array('xuninum_time' => $now,'xuninum' => $reply['xuninum']+mt_rand($reply['xuninuminitial'],$reply['xuninumending'])), array('rid' => $rid));
		}
		//虚拟人数据配置
		//参与活动人数
		$totals = $reply['xuninum'] + pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_users).' WHERE uniacid=:uniacid and rid=:rid', array(':uniacid' => $uniacid,':rid' => $rid));
		//参与活动人数
		//查询分享标题以及内容变量
		$reply['sharetitle']= $this->get_share($uniacid,$rid,$from_user,$reply['sharetitle']);
		$reply['sharecontent']= $this->get_share($uniacid,$rid,$from_user,$reply['sharecontent']);
		//整理数据进行页面显示
		$myavatar = $avatar;
		$mynickname = $nickname;
		$shareurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('shareuserview', array('rid' => $rid,'duli'=> '1', 'fromuser' => $from_user, 'tfrom_user' => $tfrom_user));//分享URL
		$shouquan = base64_encode($_SERVER ['HTTP_HOST'].'anquan_ma_photosvote');
		//$title = $unrname . ' 的投票详情！';
		$unrname = !empty($user['realname']) ? $user['realname'] : $user['nickname'] ;
		
		$title = $unrname . '正在参加'. $reply['title'] .'，快来为'.$unrname.'投票及拉票吧！';
		
		$sharetitle = $unrname . '正在参加'. $reply['title'] .'，快来为'.$unrname.'投票及拉票吧！';
		$sharecontent = $unrname . '正在参加'. $reply['title'] .'，快来为'.$unrname.'投票及拉票吧！';
		$picture =  toimage($reply['sharephoto']);
		
		
		
		$_share['link'] =$_W['siteroot'] .'app/'.$this->createMobileUrl('shareuserview', array('rid' => $rid,'duli'=> '1', 'fromuser' => $from_user, 'tfrom_user' => $tfrom_user));//分享URL
		 $_share['title'] = $unrname . '正在参加'. $reply['title'] .'，快来为'.$unrname.'投票及拉票吧！';
		$_share['content'] = $unrname . '正在参加'. $reply['title'] .'，快来为'.$unrname.'投票及拉票吧！';
		//$_share['imgUrl'] = !empty($user['photo']) ? toimage($user['photo']) : toimage($user['avatar']);
		$_share['imgUrl'] = !empty($user['avatar']) ? toimage($user['avatar']) : toimage($user['photo']);
		
		
		
		$templatename = $reply['templates'];
		$toye = $this->templatec($templatename,$_GPC['do']);
		include $this->template($toye);
