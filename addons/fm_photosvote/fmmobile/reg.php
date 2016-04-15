<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
		$regtitlearr = iunserializer($reply['regtitlearr']);
		$photosarrid = pdo_fetch("SELECT id FROM ".tablename($this->table_users_picarr)." WHERE uniacid = :uniacid and rid = :rid ORDER BY id DESC LIMIT 1", array(':uniacid' => $uniacid,':rid' => $rid));
		$mid = $photosarrid['id'] + 1;
		$addmid = $mid + 1;
		$photosarrnum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->table_users_picarr)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid));
		if ($_GPC['delete']) {
			if ($reply['subscribe'] && !$follow) {
				$fmdata = array(
					"success" => -1,
					"flag" => 5,
					"msg" => '请先关注',
				);
				echo json_encode($fmdata);
				exit();	
			}

			$photo = pdo_fetch("SELECT id,isfm, photoname FROM ".tablename($this->table_users_picarr)." WHERE uniacid = :uniacid AND from_user = :from_user AND rid = :rid AND id = :id", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid,':id' => $_GPC['photoid']));
			if (empty($photo)) {
				$fmdata = array(
					"success" => -1,
					"flag" => -1,
					"msg" => '未找到你要删除的图片',
				);
				echo json_encode($fmdata);
				exit();		
			}
			if ($photo['isfm']) {
				$fmdata = array(
					"success" => -1,
					"flag" => -1,
					"msg" => '该图片目前作为投票封面使用,请设置其他图片作为投票封面.然后再删除',
				);
				echo json_encode($fmdata);
				exit();		
			}
			load()->func('file');
			$updir = '../attachment/images/'.$uniacid.'/'.date("Y").'/'.date("m").'/'.$photo['photoname'];
			file_delete($updir);
			pdo_delete($this->table_users_picarr, array('id' => intval($_GPC['photoid']), 'rid' => $rid, 'from_user' => $from_user));
			
			$fmdata = array(
				"success" => 1,
				"flag" => 1,
				"lastmid" => $photosarrid,
				"addlastmid" => $mid,
				"photosarrnum" => $photosarrnum,
				"msg" => '删除成功'
			);
			echo json_encode($fmdata);
			exit();	
		}
		if ($_GPC['deletev']) {
			if ($reply['subscribe'] && !$follow) {
				$fmdata = array(
					"success" => -1,
					"flag" => 5,
					"msg" => '请先关注',
				);
				echo json_encode($fmdata);
				exit();	
			}
			$vedio = pdo_fetch("SELECT id, vedio FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid AND from_user = :from_user AND rid = :rid AND vedio = :vedio", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid,':vedio' => $_GPC['vedio']));
			if (empty($vedio)) {
				$fmdata = array(
					"success" => -1,
					"flag" => -1,
					"msg" => '未找到你要删除的视频',
				);
				echo json_encode($fmdata);
				exit();		
			}
			load()->func('file');
			$updir = '../attachment/audios/'.$uniacid.'/'.date("Y").'/'.date("m").'/'.$vedio['vedio'];
			file_delete($updir);
			pdo_update($this->table_users, array('vedio' => ''), array('id' => intval($vedio['id']), 'rid' => $rid, 'from_user' => $from_user));
			
			$fmdata = array(
				"success" => 1,
				"flag" => 1,
				"mid" => $vedio['id'],
				"msg" => '删除成功'
			);
			echo json_encode($fmdata);
			exit();	
		}

		if ($_GPC['deletem']) {
			if ($reply['subscribe'] && !$follow) {
				$fmdata = array(
					"success" => -1,
					"flag" => 5,
					"msg" => '请先关注',
				);
				echo json_encode($fmdata);
				exit();	
			}
			$music = pdo_fetch("SELECT id, music FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid AND from_user = :from_user AND rid = :rid AND music = :music", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid,':music' => $_GPC['music']));
			if (empty($music)) {
				$fmdata = array(
					"success" => -1,
					"flag" => -1,
					"msg" => '未找到你要删除的音频',
				);
				echo json_encode($fmdata);
				exit();		
			}
			load()->func('file');
			$updir = '../attachment/audios/'.$uniacid.'/'.date("Y").'/'.date("m").'/'.$music['music'];
			file_delete($updir);
			pdo_update($this->table_users, array('music' => ''), array('id' => intval($music['id']), 'rid' => $rid, 'from_user' => $from_user));
			
			$fmdata = array(
				"success" => 1,
				"flag" => 1,
				"mid" => $music['id'],
				"msg" => '删除成功'
			);
			echo json_encode($fmdata);
			exit();	
		}
		$photosarr = pdo_fetchall("SELECT * FROM ".tablename($this->table_users_picarr)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid and status = :status ORDER BY id ASC", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid,':status' => 1));//显示所有图片
		if ($_GPC['setfm']) {
			if ($reply['subscribe'] && !$follow) {
				$fmdata = array(
					"success" => -1,
					"flag" => 5,
					"msg" => '请先关注',
				);
				echo json_encode($fmdata);
				exit();	
			}
			$photo = pdo_fetch("SELECT id,isfm, photoname FROM ".tablename($this->table_users_picarr)." WHERE uniacid = :uniacid AND from_user = :from_user AND rid = :rid AND id = :id", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid,':id' => $_GPC['photoid']));
			if (empty($photo)) {
				$fmdata = array(
					"success" => -1,
					"flag" => -1,
					"msg" => '未找到你要设置封面的图片',
				);
				echo json_encode($fmdata);
				exit();		
			}
			if ($photo['isfm']) {
				$fmdata = array(
					"success" => -1,
					"flag" => -1,
					"msg" => '该图片已经是投票封面,请设置其他图片作为投票封面。',
				);
				echo json_encode($fmdata);
				exit();		
			}
			foreach ($photosarr as $key => $value) {
				if ($value['isfm'] == 1) {
					$delmid = $value['id'];
				}
				pdo_update($this->table_users_picarr,array('isfm' => 0), array('id' => intval($value['id'])));
			}
			pdo_update($this->table_users_picarr,array('isfm' => 1), array('id' => intval($_GPC['photoid']), 'rid' => $rid, 'from_user' => $from_user));
			
			$fmdata = array(
				"success" => 1,
				"flag" => 1,
				"lastmid" => $photosarrid,
				"addlastmid" => $mid,
				"delmid" => $delmid,
				"msg" => '设置成功！'
			);
			echo json_encode($fmdata);
			exit();	
		}
		if ($reply['ipannounce'] == 1) {
			$announce = pdo_fetchall("SELECT nickname,content,createtime,url FROM " . tablename($this->table_announce) . " WHERE uniacid= '{$uniacid}' AND rid= '{$rid}' ORDER BY id DESC");
		}
		//赞助商
		if ($reply['isreg'] == 1) {
			$advs = pdo_fetchall("SELECT advname,link,thumb FROM " . tablename($this->table_advs) . " WHERE enabled=1 AND ismiaoxian = 0 AND uniacid= '{$uniacid}'  AND rid= '{$rid}' ORDER BY displayorder ASC");
		}
		
		//查询是否参与活动
		$where = '';
		$mygift = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid));			
		

		

		
		

		$fmimage = $this->getpicarr($uniacid,$rid, $mygift['from_user'],1);
		//$picarr = $this->getpicarr($uniacid,$reply['tpxz'],$from_user,$rid);
		
		$tags = pdo_fetchall("SELECT * FROM ".tablename($this->table_tags)." WHERE rid = :rid ORDER BY id DESC" , array(':rid' => $rid));
		
		$reply['sharetitle']= $this->get_share($uniacid,$rid,$from_user,$reply['sharetitle']);
		$reply['sharecontent']= $this->get_share($uniacid,$rid,$from_user,$reply['sharecontent']);
		//整理数据进行页面显示
		$myavatar = $avatar;
		$mynickname = $nickname;
		$title = $reply['sharetitle'] . '报名';
		
		$_share['link'] = $_W['siteroot'] .'app/'.$this->createMobileUrl('shareuserview', array('rid' => $rid,'fromuser' => $from_user));//分享URL
		 $_share['title'] = $reply['sharetitle'];
		$_share['content'] =  $reply['sharecontent'];
		$_share['imgUrl'] = toimage($reply['sharephoto']);
		
		
		$templatename = $reply['templates'];
		$toye = $this->templatec($templatename,$_GPC['do']);
		include $this->template($toye);
		