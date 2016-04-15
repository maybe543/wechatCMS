<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
		if ($cfg['ismiaoxian'] && $cfg['mxnexttime'] != 0) {
			if (!isset($_COOKIE["fm_miaoxian"])) {
				setcookie("fm_miaoxian", 'startmiaoxian', time()+$cfg['mxnexttime']);
				$mxurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('miaoxian', array('rid' => $rid));
				header("location:$mxurl");
				exit;
			}
		}	
		//幻灯片
        $banners = pdo_fetchall("SELECT bannername,link,thumb FROM " . tablename($this->table_banners) . " WHERE enabled=1 AND uniacid= '{$uniacid}' AND rid= '{$rid}' ORDER BY displayorder ASC");
		if ($reply['ipannounce'] == 1) {
			$announce = pdo_fetchall("SELECT nickname,content,createtime,url FROM " . tablename($this->table_announce) . " WHERE uniacid= '{$uniacid}' AND rid= '{$rid}' ORDER BY id DESC");
		}
		//赞助商
		if ($reply['isindex'] == 1) {
			$advs = pdo_fetchall("SELECT advname,link,thumb FROM " . tablename($this->table_advs) . " WHERE enabled=1 AND ismiaoxian = 0 AND uniacid= '{$uniacid}'  AND rid= '{$rid}' ORDER BY displayorder ASC");
		}
		
		$pindex = max(1, intval($_GPC['page']));
		$psize = empty($reply['indextpxz']) ? 10 : $reply['indextpxz'];
		//取得用户列表
		$where = '';
		$keyword = $_GPC['keyword'];
		$tagid = $_GPC['tagid'];
		if (!empty($keyword)) {				
				if (is_numeric($keyword)) 
					$where .= " AND uid = '".$keyword."'";
				else 				
					$where .= " AND (nickname LIKE '%{$keyword}%' OR realname LIKE '%{$keyword}%' )";
		}
		
		$where .= " AND status = '1'";
		if (!empty($tagid)) {
			$where .= " AND tagid = '".$tagid."'";
		}
		
		if ($_GPC['indexorder'] == 4) {
				$where .= " ORDER BY `hits` + `xnhits` DESC";
		}else {
			if ($reply['indexorder'] == '1') {
				$where .= " ORDER BY `createtime` DESC";
			}elseif ($reply['indexorder'] == '11') {
				$where .= " ORDER BY `createtime` ASC";
			}elseif ($reply['indexorder'] == '2') {
				$where .= " ORDER BY `uid` DESC";
			}elseif ($reply['indexorder'] == '22') {
				$where .= " ORDER BY `uid` ASC";
			}elseif ($reply['indexorder'] == '3') {
				$where .= " ORDER BY `photosnum` + `xnphotosnum` DESC";
			}elseif ($reply['indexorder'] == '33') {
				$where .= " ORDER BY `photosnum` + `xnphotosnum` ASC";
			}elseif ($reply['indexorder'] == '4') {
				$where .= " ORDER BY `hits` + `xnhits` DESC";
			}elseif ($reply['indexorder'] == '44') {
				$where .= " ORDER BY `hits` + `xnhits` ASC";
			}elseif ($reply['indexorder'] == '5') {
				$where .= " ORDER BY `vedio` DESC, `music` DESC, `uid` DESC";
			}else {
				$where .= " ORDER BY `uid` DESC";
			}
		}
		if ($reply['templates'] == 'stylebase') {
			$userlist = pdo_fetchall('SELECT * FROM '.tablename($this->table_users).' WHERE uniacid= :uniacid and rid = :rid  '.$where.'', array(':uniacid' => $uniacid,':rid' => $rid) );
		}else{
			$userlist = pdo_fetchall('SELECT * FROM '.tablename($this->table_users).' WHERE uniacid= :uniacid and rid = :rid  '.$where.' LIMIT  ' . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid' => $uniacid,':rid' => $rid) );
		}
		
		

		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_users).' WHERE uniacid= :uniacid and rid = :rid '.$where.'', array(':uniacid' => $uniacid,':rid' => $rid));
		$total_pages = ceil($total/$psize);
		$pager = pagination($total, $pindex, $psize, '', array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
		if (!empty($fromuser)) {
			$titem = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid AND rid = :rid AND from_user = :from_user LIMIT 1", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $fromuser));
			$tcommentnum = $this->getcommentnum($rid, $uniacid,$fromuser);
		}
		

		//$votelogs = pdo_fetchall('SELECT * FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid and rid = :rid ORDER BY id DESC', array(':uniacid' => $uniacid,':rid' => $rid) );
		
		//查询自己是否参与活动
		if(!empty($from_user)) {
		    $mygift = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid));
		     $mcommentnum = $this->getcommentnum($rid, $uniacid,$from_user);
			//此处更新一下分享量和邀请量
			if(!empty($mygift)){
			    $yql = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->table_data)." WHERE uniacid = :uniacid and fromuser = :fromuser and rid = :rid ", array(':uniacid' => $uniacid,':fromuser' => $from_user,':rid' => $rid));
			    $fxl = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->table_data)." WHERE uniacid = :uniacid and fromuser = :fromuser and rid = :rid", array(':uniacid' => $uniacid,':fromuser' => $from_user,':rid' => $rid));
				//$hits = $mygift['hits'] + 1;
				pdo_update($this->table_users,array('sharenum' => $fxl,'yaoqingnum' => $yql),array('uid' => $mygift['uid']));
			}	
		}
			
		//统计
		$csrs = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->table_users)." WHERE rid= ".$rid." AND uniacid= ".$uniacid." AND status= 1 ");//参赛人数
		$ljtp = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->table_log)." WHERE rid= ".$rid."") + pdo_fetchcolumn("SELECT sum(xnphotosnum) FROM ".tablename($this->table_users)." WHERE rid= ".$rid."");//累计投票
		$cyrs = $csrs + $reply['hits'] + pdo_fetchcolumn("SELECT sum(hits) FROM ".tablename($this->table_users)." WHERE rid= ".$rid."") + pdo_fetchcolumn("SELECT sum(xnhits) FROM ".tablename($this->table_users)." WHERE rid= ".$rid."") + $reply['xuninum'];//点击
		

		$tags = pdo_fetchall("SELECT * FROM ".tablename($this->table_tags)." WHERE uniacid = :uniacid AND rid = :rid ORDER BY id DESC", array(':uniacid' => $uniacid, ':rid' => $rid));
		//虚拟人数据配置
		//参与活动人数
		//$total = $reply['xuninum'] + pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_users).' WHERE uniacid=:uniacid and rid=:rid', array(':uniacid' => $uniacid,':rid' => $rid));
		//参与活动人数
		//查询分享标题以及内容变量
		$reply['sharetitle'] = $this->get_share($uniacid,$rid,$from_user,$reply['sharetitle']);
		$reply['sharecontent'] = $this->get_share($uniacid,$rid,$from_user,$reply['sharecontent']);
		
		//整理数据进行页面显示
		$myavatar = $avatar;
		$mynickname = $nickname;
		//$shareurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('shareuserview', array('rid' => $rid,'fromuser' => $from_user));//分享URL
		$regurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('reg', array('rid' => $rid));//关注或借用直接注册页
		$title = $reply['title'] . ' ';
		
		
		$_share['link'] = $_W['siteroot'] .'app/'.$this->createMobileUrl('shareuserview', array('rid' => $rid,'fromuser' => $from_user,'tfrom_user' => $from_user));//分享URL
		 $_share['title'] = $reply['sharetitle'];
		$_share['content'] =  $reply['sharecontent'];
		$_share['imgUrl'] = toimage($reply['sharephoto']);



		$templatename = $reply['templates'];
		$toye = $this->templatec($templatename,$_GPC['do']);
		//echo $toye;
		//exit;
		include $this->template($toye);
		
