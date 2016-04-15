<?php

global $_W, $_GPC;
$pageid = $_GPC['pageid'];
if (!empty($pageid)) {
	$page = pdo_fetch("SELECT * FROM " . tablename($this->table_designer) . " WHERE uniacid= :uniacid and id=:id", array(':uniacid' => $_W['uniacid'], ':id' => $pageid));
	$pagedata = $this->getData($page);
	extract($pagedata);

		/**$datauser = htmlspecialchars_decode($page['datas']);
		$datauser = json_decode($datauser, true);
		
		if (!empty($datauser)) {
			foreach ($datauser as $i1 => &$dd) {
				if ($dd['temp'] == 'photosvote') {
					foreach ($dd['data'] as $i2 => &$ddd) {
						$usersinfo = pdo_fetchall("SELECT id,rid,from_user,nickname,realname,uid,avatar,photosnum,hits,xnphotosnum,xnhits,sharenum FROM " . tablename($this->table_users) . " WHERE uniacid= :uniacid AND status=:status AND rid =:rid ORDER BY uid ASC", array(':uniacid' => $_W['uniacid'], ':status' => '1', ':rid' => 34));
						$usersinfo = $this->set_medias($usersinfo, 'avatar');
						//print_r($usersinfo);
						
						if (!empty($usersinfo)) {
							foreach ($usersinfo as $key => $value) {
								if ($ddd['from_user'] == $value['from_user']) {
									$datauser[$i1]['data'][$i2]['name'] = !empty($value['nickname']) ? $value['nickname'] : $value['realname'] ;
									$datauser[$i1]['data'][$i2]['uid'] = $value['uid'];
									$datauser[$i1]['data'][$i2]['from_user'] = $value['from_user'];
									$datauser[$i1]['data'][$i2]['img'] = $value['avatar'];
									$datauser[$i1]['data'][$i2]['piaoshu'] = $value['photosnum'] + $value['xnphotosnum'];
									$datauser[$i1]['data'][$i2]['renqi'] = $value['hits'] + $value['xnhits'];
									$datauser[$i1]['data'][$i2]['sharenum'] = $value['sharenum'];
								}
							}
							
						}
					}
					unset($ddd);
				} 
				
			}
			unset($dd);
			$datauser = json_encode($datauser);

		}
		$datauser = rtrim($datauser, "]");
		$datauser = ltrim($datauser, "[");**/
}


//$guide = $this->model->getGuide($system, $pageinfo);
$sharelink = './app'.$this->createMobileUrl('preview', array('pageid' => $page['id']));
if ($page['pagetype'] == 1 && $page['setdefault'] == 1) {
	$sharelink = $this->createMobileUrl('photosvote');
}
$_W['shopshare'] = array('title' => $share['title'], 'imgUrl' => $share['imgUrl'], 'desc' => $share['desc'], 'link' => $sharelink);
$users = $this->getMember($from_user);
$system = array('tusertop' => array('name' => $users['realname'], 'logo' => tomedia($users['avatar'])));
$system = json_encode($system);

//if (p('commission')) {
//	$set = p('commission')->getSet();
	//if (!empty($set['level'])) {
		if (!empty($_GPC['preview'])) {
			$openid = 'fromUser';
			$this->footer['first'] = array('text' => '首页', 'ico' => 'home', 'url' => $this->createMobileUrl('shop'));
			$this->footer['second'] = array('text' => '分类', 'ico' => 'list', 'url' => $this->createMobileUrl('shop/category'));
		} else {
			$openid = $from_user;
		}
		$member = $this->getMember($openid);
		if (!empty($member) && $member['status'] == 1) {
			$_W['shopshare']['link'] = $this->createMobileUrl('preview', array('pageid' => $page['id'], 'mid' => $member['id']));
			if ($page['pagetype'] == 1 && $page['setdefault'] == 1) {
				$_W['shopshare']['link'] = $this->createMobileUrl('shop', array('mid' => $member['id']));
			}
		} else if (!empty($_GPC['mid'])) {
			$_W['shopshare']['link'] = $this->createMobileUrl('preview', array('pageid' => $page['id'], 'mid' => $_GPC['mid']));
			if ($page['pagetype'] == 1 && $page['setdefault'] == 1) {
				$_W['shopshare']['link'] = $this->createMobileUrl('shop', array('mid' => $_GPC['mid']));
			}
		}
	//}
//}
include $this->template('preview/index');