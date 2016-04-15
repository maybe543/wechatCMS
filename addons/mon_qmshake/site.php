<?php
/**
 * 微赞科技
 */
defined('IN_IA') or exit('Access Denied');
define("MON_QMSHAKE", "mon_qmshake");
define("MON_QMSHAKE_RES", "../addons/" . MON_QMSHAKE . "/");
require_once IA_ROOT . "/addons/" . MON_QMSHAKE . "/dbutil.class.php";
require IA_ROOT . "/addons/" . MON_QMSHAKE . "/oauth2.class.php";
require_once IA_ROOT . "/addons/" . MON_QMSHAKE . "/value.class.php";
require_once IA_ROOT . "/addons/" . MON_QMSHAKE . "/monUtil.class.php";

/**
 * Class Mon_BatonModuleSite
 */
class Mon_QmShakeModuleSite extends WeModuleSite
{
	public $weid;
	public $acid;
	public $oauth;
	public static $USER_COOKIE_KEY = "__shakeuserv8";
	public static $USER_CB_PAGE_SIZE = 10;


	function __construct()
	{
		global $_W;
		$this->weid = $_W['uniacid'];
		$this->oauth = new Oauth2('', '');
	}

	public function doWebQmshakeManage()
	{
		global $_W, $_GPC;
		$where = '';
		$params = array();
		$params[':weid'] = $this->weid;
		if (isset($_GPC['keyword'])) {
			$where .= ' AND `title` LIKE :keywords';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_QMSHAKE) . " WHERE weid =:weid " . $where . " ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE) . " WHERE weid =:weid " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
		    pdo_delete(DBUtil::$TABLE_QMSHAKE_SHARE, array("sid" => $id));
			pdo_delete(DBUtil::$TABLE_QMSHAKE_RECORD, array("sid" => $id));
			pdo_delete(DBUtil::$TABLE_QMSHAKE_PRIZE, array("sid" => $id));
			pdo_delete(DBUtil::$TABLE_QMSHAKE_USER, array("sid" => $id));
			pdo_delete(DBUtil::$TABLE_QMSHAKE, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template("shake_manage");
	}

	/**
	 * 接力用户
	 */
	public function  doWebuserList()
	{
		global $_W, $_GPC;
		$sid=$_GPC['sid'];
		$shake = DBUtil::findById(DBUtil::$TABLE_QMSHAKE,$sid);
		$where='';
		$params = array();
		$params[':sid'] =$sid;
		if (isset($_GPC['keyword'])) {
			$where .= ' AND (`tel` LIKE :keywords or nickname Like :keywords)';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_QMSHAKE_USER). " WHERE sid =:sid ".$where." ORDER BY createtime desc  LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_USER) . " WHERE sid =:sid ".$where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
		    pdo_delete(DBUtil::$TABLE_QMSHAKE_SHARE, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_QMSHAKE_RECORD, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_QMSHAKE_USER, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("user_list");

	}

	/**
	 * author: 
	 * 删除用户
	 */
	public function doWebDeleteUser() {
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $uid) {
			$id = intval($uid);
			if ($id == 0)
				continue;
			 pdo_delete(DBUtil::$TABLE_QMSHAKE_SHARE, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_QMSHAKE_RECORD, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_QMSHAKE_USER, array("id" => $id));
		}

		echo json_encode(array('code'=>200));
	}


	/**
	 * author: 
	 *
	 */
	public function doWebdhAll() {

		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $bid) {
			$id = intval($bid);

			if ($id != 0) {

				$record = DBUtil::findById(DBUtil::$TABLE_QMSHAKE_RECORD, $id);

				if ($record['status'] == 1) {
					$prize = DBUtil::findById(DBUtil::$TABLE_QMSHAKE_PRIZE, $record['pid']);
					if ($prize['ptype'] != 0) {
						$dbjf = $this->doDHCredit($record['openid'], $prize['jfye'], $prize['ptype']);
						if ($dbjf) {
							DBUtil::updateById(DBUtil::$TABLE_QMSHAKE_RECORD,array("status"=>2,'djtime'=>TIMESTAMP), $id);
						}

					} else {
						DBUtil::updateById(DBUtil::$TABLE_QMSHAKE_RECORD,array("status"=>2,'djtime'=>TIMESTAMP),$id);
					}
				}
			}

		}

		echo json_encode(array('code' => 200));

	}


	/**
	 * author: 
	 * 记录
	 */
	public function  doWebRecord_list()
	{
		global $_W, $_GPC;
		$sid = $_GPC['sid'];
		$shake = DBUtil::findById(DBUtil::$TABLE_QMSHAKE,$sid);
		$pid = $_GPC['pid'];
		$prizes = pdo_fetchall("select * from ".tablename(DBUtil::$TABLE_QMSHAKE_PRIZE)." where sid=:sid",array(":sid"=>$sid));
		$where = '';
		$params = array();
		$params[':sid'] = $sid;
		$status = $_GPC['status'];
		if ($_GPC['uid']!='')
		{
			$where .= ' AND r.uid =:uid';
			$params[':uid'] = $_GPC['uid'];

		}
		if (!empty($pid))
		{
			$where .= ' AND r.pid =:pid';
			$params[':pid'] = $pid;

		}

        if (!empty($status))
		{
			$where .= ' AND r.status =:status';
			$params[':status'] = $status;
		}

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT r.*,r.id as rid,u.uname,u.tel,u.nickname,u.headimgurl, u.udefine  FROM " . tablename(DBUtil::$TABLE_QMSHAKE_RECORD) . " r left join " . tablename(DBUtil::$TABLE_QMSHAKE_USER) . " u  on r.uid=u.id  WHERE r.sid =:sid " . $where . " ORDER BY r.createtime DESC, r.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_RECORD) . " r WHERE r.sid =:sid " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_QMSHAKE_RECORD, array("id" => $id));
			message('删除成功！', referer(), 'success');
		} else if ($operation == 'dh') {
			$id = $_GPC['id'];
			$record = DBUtil::findById(DBUtil::$TABLE_QMSHAKE_RECORD, $id);
			$prize = DBUtil::findById(DBUtil::$TABLE_QMSHAKE_PRIZE, $record['pid']);
			if ($prize['ptype'] != 0) {
				$dbjf = $this->doDHCredit($record['openid'], $prize['jfye'], $prize['ptype']);
				if ($dbjf) {
					DBUtil::updateById(DBUtil::$TABLE_QMSHAKE_RECORD,array("status"=>2,'djtime'=>TIMESTAMP), $id);
					message('兑换成功！', referer(), 'success');
				} else {
					message('兑换失败');
				}

			} else {
				DBUtil::updateById(DBUtil::$TABLE_QMSHAKE_RECORD,array("status"=>2,'djtime'=>TIMESTAMP),$id);
				message('兑换成功！', referer(), 'success');
			}

		}

		include $this->template("record_list");

	}


	/**
	 * author:
	 * 记录
	 */
	public function  doWebShareList()
	{
		global $_W, $_GPC;
		$sid = $_GPC['sid'];
		$shake = DBUtil::findById(DBUtil::$TABLE_QMSHAKE,$sid);
		$pid = $_GPC['pid'];

		$where = '';
		$params = array();
		$params[':sid'] = $sid;

		if ($_GPC['openid']!='')
		{
			$where .= ' AND s.openid =:openid';
			$params[':openid'] = $_GPC['openid'];

		}

		if (isset($_GPC['keyword'])) {
			$where .= ' AND (u.`tel` LIKE :keywords or u.nickname Like :keywords)';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT s.*,u.nickname nickname,u.tel as tel  FROM " . tablename(DBUtil::$TABLE_QMSHAKE_SHARE) . " s left join " . tablename(DBUtil::$TABLE_QMSHAKE_USER) . " u  on s.openid=u.openid WHERE s.sid =:sid  and u.sid=:sid " . $where . " ORDER BY s.createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_SHARE) . " s left join".tablename(DBUtil::$TABLE_QMSHAKE_USER)." u on s.openid=u.openid WHERE s.sid =:sid and u.sid=:sid " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_QMSHAKE_SHARE, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("share_list");

	}



	/**
	 * author: 
	 * 删除摇一摇
	 */
	public function doWebDeleteShake()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $bid) {
			$id = intval($bid);
			if ($id == 0)
				continue;
			 pdo_delete(DBUtil::$TABLE_QMSHAKE_SHARE, array("sid" => $id));
			pdo_delete(DBUtil::$TABLE_QMSHAKE_RECORD, array("sid" => $id));
			pdo_delete(DBUtil::$TABLE_QMSHAKE_USER, array("sid" => $id));
			pdo_delete(DBUtil::$TABLE_QMSHAKE_PRIZE, array("sid" => $id));
			pdo_delete(DBUtil::$TABLE_QMSHAKE, array("id" => $id));
		}
		echo json_encode(array('code' => 200));
	}

	public function doWebDeleteRecord()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $bid) {
			$id = intval($bid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_QMSHAKE_RECORD, array("id" => $id));

		}

		echo json_encode(array('code' => 200));
	}


	/**
	 * author: 
	 * 删除奖品
	 */
	public function  doWebDeletePrize()
	{
		global $_GPC;
		$pid = $_GPC['pid'];
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_RECORD) . " WHERE  pid=:pid", array(':pid' => $pid));
		$res = array();
		if ($count > 0) {

			$res['code'] = 500;
		} else {
			$res['code'] = 200;
		}
		echo json_encode($res);

	}

	/**
	 * author: 
	 * 删除分享
	 */
	public function  doWebDeleteShare()
	{
		global $_GPC;
		foreach ($_GPC['idArr'] as $k => $bid) {
			$id = intval($bid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_QMSHAKE_SHARE, array("id" => $id));

		}

		echo json_encode(array('code' => 200));
	}



	/**
	 * author: 
	 * 用户信息导出
	 */
	public function  doWebUDownload()
	{

		require_once 'udownload.php';
	}

	public function  doWebRDownload()
	{

		require_once 'rdownload.php';
	}


	/**********************************************************
	手机
	 */
	/**
	 * author: 微赞科技
	 * 初始化
	 */
	public function doMobileAjaxShake()
	{
		global $_W, $_GPC;
		$sid = $_GPC['sid'];
		$shake = DBUtil::findById(DBUtil::$TABLE_QMSHAKE,$sid);
		$res = array();
		$luckSize= $shake['randking_count'];
		$goods = pdo_fetchall("select pname as goods_name, p_summary as summary, pimg as goods_img,price as price, tgs as tgs, tgs_url as tgs_url,pcount as  remain, virtual_count as v_count from " . tablename(DBUtil::$TABLE_QMSHAKE_PRIZE) . " where sid=:sid order by  display_order desc", array(":sid" => $sid));
		$luck_lists = pdo_fetchall("select (select tel  from " . tablename(DBUtil::$TABLE_QMSHAKE_USER) . " u where u.id=r.uid) as phone ,p.pname as goods_name,p.price as price  from " . tablename(DBUtil::$TABLE_QMSHAKE_RECORD) . " r left join ".tablename(DBUtil::$TABLE_QMSHAKE_PRIZE)." p on r.pid= p.id where r.sid=:sid and status<>0 limit 0,".$luckSize , array(":sid" => $sid));
		foreach ($luck_lists as $luckKey=>$luck)
		{
			$luck_lists[$luckKey]['phone'] = substr($luck['phone'],0,7)."****";
			//$luck['phone'] = substr($luck['phone'],0,7)."****";
		}

		foreach($goods as $gkey =>$good) {
			$goods[$gkey]['remain'] = $good['remain'] + $good['v_count'];
		}

		$res['apistatus'] = 1;
		$resResult = array();
		//$openid = $_W['fans']['from_user'];
        $openid = $this->getOpenId();



		$userTotalRecord = $this->findUserRecordCount($sid,$openid);
		if ($userTotalRecord >= $shake['total_limit']) {
			$resResult['free'] = 0;
		} else {

//
			$userDayRecord = $this ->findUserDayRecordCount($sid,$openid);

			//没有启用分享功能
			if ( $shake['share_enable'] == 0) {
				$resResult['free'] = $shake['shake_day_limit'] - $userDayRecord;
				if ($resResult['free'] <=0) {
					$resResult['free'] = 0;
				}
				$resResult['leftShare'] = 0;
				$resResult['awardCount'] =0;
			}


			$user_DayShare = $this->findUserDayShareCount($sid,$openid);
			//今天用完，并且启用了分享,用户还没有分享
			if (($shake['shake_day_limit'] <= $userDayRecord) && $shake['share_enable'] ==1 && $user_DayShare ==0) {
				$resResult['free'] = 0;
				$resResult['leftShare'] = $shake['share_times'];
				$resResult['awardCount'] =$shake['share_award_count'];
			}

			if (($shake['shake_day_limit'] > $userDayRecord) && $shake['share_enable'] ==1 && $user_DayShare ==0) {
				$resResult['free'] = $shake['shake_day_limit'] - $userDayRecord;
				$resResult['leftShare'] = 0;
				$resResult['awardCount'] =0;
			}


			//今天用完，并且启用了分享,用户还没有分享
			if ($shake['share_enable'] ==1 && $user_DayShare > 0) {
				$userAwardDay = $shake['share_times']*$shake['share_award_count'];
				if ($userDayRecord >= $shake['shake_day_limit'] + $userAwardDay) { //每天限制，奖励都用完
					$resResult['free'] = 0;
					$resResult['leftShare'] = 0;
					$resResult['awardCount'] =0;
				} else {
					$resResult['free'] = ($shake['shake_day_limit'] + $user_DayShare*$shake['share_award_count']) -$userDayRecord;
					$resResult['leftShare'] = $shake['share_times'] -$user_DayShare ;
					$resResult['awardCount'] =$shake['share_award_count'];
				}
			}
		}


		$resResult['slot_machine'] = array("items" => $goods);
		$resResult['lucky_guy_list'] = $luck_lists;
		$res['result'] = $resResult;
		echo json_encode($res);
	}


	public function  getOpenId() {
		global $_W, $_GPC;

		$authFrom = json_decode(base64_decode($_GPC['authFrom']), true);

		if (!empty($authFrom)) {
			$from = $authFrom['fm'];
			if ($from == 'fm_photosvote') { //来自投票
			   return $authFrom['oid'];
			} else {
				return $_W['fans']['from_user'];
			}
		}

		return $_W['fans']['from_user'];
	}


	/**
	 * author: 
	 * 摇奖
	 */
	public function doMobileGetGift()
	{
		global $_W, $_GPC;

		$sid = $_GPC['sid'];
		$res = array();
		$shake = DBUtil::findById(DBUtil::$TABLE_QMSHAKE, $sid);
		if (empty($shake)) {
			$res['code'] = 500;
			$res['msg'] = "摇一摇活动删除或不存在!";
			die(json_encode($res));
		}

		if (TIMESTAMP < $shake['starttime']) {
			$res['code'] = 509;
			$res['msg'] = "活动还未开始哦!";
			die(json_encode($res));
		}

		if (TIMESTAMP > $shake['endtime']) {
			$res['code'] = 501;
			$res['msg'] = "摇一摇活动已结束，下次再来吧!";
			die(json_encode($res));
		}

		//$openid = $_W['fans']['from_user'];

		$openid = $this->getOpenId();
		if (empty($openid)) {
			$res['code'] = 503;
			$res['msg'] = "请登录授权后再参与，获取用户信息失败！";
			die(json_encode($res));
		}

		$already_playCount = $this->findUserRecordCount($sid, $openid);


		$leftTotalCount = $shake['total_limit'] - $already_playCount;

		if ($leftTotalCount <= 0) {
			$res['code'] = 504;
			$res['msg'] = "您已经没有机会了下次再来吧!";
			die(json_encode($res));
		}

		$user_day_play_count = $this->findUserDayRecordCount($sid, $openid);
        //今天用完，并且没有启用分享奖励功能
		if (($shake['shake_day_limit'] == $user_day_play_count) && $shake['share_enable'] == 0) {
			$res['code'] = 505;
			$res['msg'] = "您今天的摇一摇机会已用完!";
			die(json_encode($res));
		}
		$user_DayShare = $this->findUserDayShareCount($sid,$openid);
	    //今天用完，并且启用了分享,用户还没有分享
		if (($shake['shake_day_limit'] == $user_day_play_count) && $shake['share_enable'] ==1 && $user_DayShare ==0) {
			$res['code'] = 506;
			$res['msg'] = "您还有".$shake['share_times']."分享奖励机会，每次分享奖励".$shake['share_award_count']."次机会!赶快分享吧！";
			die(json_encode($res));
		}

		//今天用完，并且启用了分享,用户还没有分享
		if ($shake['share_enable'] ==1 && $user_DayShare > 0) {
			$userAwardDay = $shake['share_times']*$shake['share_award_count'];
			if ($user_day_play_count >= $shake['shake_day_limit'] + $userAwardDay) { //每天限制，奖励都用完
				$res['code'] = 507;
				$res['msg'] = "您今天的摇一摇机会已用完!";
				die(json_encode($res));
			}
		}

		$res['code'] = 200;

		$dbUser = DBUtil::findUnique(DBUtil::$TABLE_QMSHAKE_USER, array(":sid" => $sid, ":openid" => $openid));

		if (empty($dbUser)) {

			$authFrom = json_decode(base64_decode($_GPC['authFrom']), true);

			if (!empty($authFrom)) {
				$from = $authFrom['fm'];
				if ($from == 'fm_photosvote') { //来自投票
					$userInfo['nickname']="<font color='red'>来自女神投票用户</font>";
				} else {
					$userInfo = $this->setClientUserInfo($openid);
				}
			}


			$userData = array(
				'sid' => $shake['id'],
				'openid' => $openid,
				'nickname' => $userInfo['nickname'],
				'headimgurl' => $userInfo['headimgurl'],
				'createtime' => TIMESTAMP
			);
			DBUtil::create(DBUtil::$TABLE_QMSHAKE_USER, $userData);
			$uid = pdo_insertid();
		} else {
			$uid = $dbUser['id'];
		}

          $userPrizeCount = $this->findUserPrizeCount($sid, $openid);
		if ($userPrizeCount >= $shake ['prize_limit']) {
			$this->createRecord($uid, $sid, 0, 0, $openid,'');
			$res['flag'] = 2;
			$resLeft = $this->getLeftCount($shake,$openid);
			$res['leftCount'] = $resLeft[0];
			$res['leftShare'] = $resLeft[1];
			$res['awardCount'] = $resLeft[2];
			die(json_encode($res));
		}
			$prizes = pdo_fetchall("select * from " . tablename(DBUtil::$TABLE_QMSHAKE_PRIZE) . " where sid=:sid  order by pb asc ", array(":sid" => $sid));
			$arrayRand = array();
			$totalRand = 0;
			for ($index = 0; $index < count($prizes); $index++) {
				$arrayRand[$index] = $prizes[$index]['pb'];
				$totalRand += $arrayRand[$index];
			}

			$arrayRand[count($prizes)] = 10000 - $totalRand;//不中奖概率计算
			$pIndex = $this->get_rand($arrayRand);//随机

			if ($pIndex == count($prizes)) { //没有中奖
				$this->createRecord($uid, $sid, 0, 0, $openid,'');
				$res['flag'] = 3;
				$resLeft = $this->getLeftCount($shake,$openid);
				$res['leftCount'] = $resLeft[0];
				$res['leftShare'] = $resLeft[1];
				$res['awardCount'] = $resLeft[2];
				die(json_encode($res));
			} else {//中奖
				$prize = $prizes[$pIndex];
				$przie_count = $this->findPrizeAwardCount($sid,$prize['id']);
				if ($przie_count >= $prize['pcount'] ) { //超过数量了
					$this->createRecord($uid, $sid, 0, 0, $openid,'');
					$res['flag'] = 4;
					$resLeft = $this->getLeftCount($shake,$openid);
					$res['leftCount'] = $resLeft[0];
					$res['leftShare'] = $resLeft[1];
					$res['awardCount'] = $resLeft[2];
					die(json_encode($res));
				} else {

					$this->createRecord($uid, $sid, $prize['id'], 1, $openid,$prize['pname']);
					$rid = pdo_insertid();
					$res['flag'] = 1;
					$resLeft = $this->getLeftCount($shake,$openid);
					$res['leftCount'] = $resLeft[0];
					$res['leftShare'] = $resLeft[1];
					$res['awardCount'] = $resLeft[2];
					$res['uid'] = $uid;
					$res['goodname'] = $prize['pname'];
					$res['price'] = $prize['price'];
					$res['img'] = MonUtil::getpicurl($prize['pimg']);

					$this->sendTemplateMsg($shake,  $prize['pname'], $openid);

					if ($shake['jfye_auto_dh'] == 1 && $prize['ptype'] != 0) {
						$jfdu = $this->doDHCredit($openid, $prize['jfye'], $prize['ptype']);
						if ($jfdu) {
							DBUtil::updateById(DBUtil::$TABLE_QMSHAKE_RECORD,array("status"=>2,'djtime'=>TIMESTAMP), $rid);
						}

					}

					die(json_encode($res));
				}

			}
	}

	public  function  getLeftCount($shake,$openid) {

		$userTotalRecord = $this->findUserRecordCount($shake['id'],$openid);
		if ($userTotalRecord >= $shake['total_limit']) {
			return array(0,0,0);
		} else {
			$userDayRecord = $this ->findUserDayRecordCount($shake['id'],$openid);
			$countArray =array();
			//没有启用分享功能
			if ( $shake['share_enable'] == 0) {

				$countArray[0] =  $shake['shake_day_limit'] - $userDayRecord;
				if ($countArray[0]  <=0) {
					$countArray[0] = 0;
				}
				$countArray[1] = 0;
				$countArray[2] = 0;

				return $countArray;
			}

			$user_DayShare = $this->findUserDayShareCount($shake['id'],$openid);
			//今天用完，并且启用了分享,用户还没有分享
			if (($shake['shake_day_limit'] <= $userDayRecord) && $shake['share_enable'] ==1 && $user_DayShare ==0) {
				$countArray[0] = 0;
				$countArray[1] = $shake['share_times'];
				$countArray[2] = $shake['share_award_count'];
				return $countArray;
			}

			if (($shake['shake_day_limit'] > $userDayRecord) && $shake['share_enable'] ==1 && $user_DayShare ==0) {
				$countArray[0] = $shake['shake_day_limit'] - $userDayRecord;
				$countArray[1] = 0;
				$countArray[2] =0;
				return $countArray;
			}


			//今天用完，并且启用了分享,用户还没有分享
			if ($shake['share_enable'] ==1 && $user_DayShare > 0) {
				$userAwardDay = $shake['share_times']*$shake['share_award_count'];
				if ($userDayRecord >= $shake['shake_day_limit'] + $userAwardDay) { //每天限制，奖励都用完
					$countArray[0] = 0;
					$countArray[1] = 0;
					$countArray[2] =0;

				} else {
					$countArray[0] = ($shake['shake_day_limit'] + $user_DayShare*$shake['share_award_count']) -$userDayRecord;
					$countArray[1] = $shake['share_times'] -$user_DayShare ;
					$countArray[2] = $shake['share_award_count'];
				}

				return $countArray;
			}

			return array(0,0,0);
		}


	}
	public function createRecord($uid, $sid, $pid, $status, $openid,$pname)
	{
		$recordData = array(
			'sid' => $sid,
			'pid' => $pid,
			'uid' => $uid,
			'openid' => $openid,
			'status' => $status,
			'pname' => $pname,
			'createtime' => TIMESTAMP
		);
		DBUtil::create(DBUtil::$TABLE_QMSHAKE_RECORD, $recordData);

	}

	public function setClientUserInfo($openid)
	{
		global $_W;
		if (!empty($openid) && ($_W['account']['level'] == 3 || $_W['account']['level'] == 4)) {
			load()->classs('weixin.account');
			$accObj = WeixinAccount::create($_W['acid']);
			$access_token = $accObj->fetch_token();

			if (empty($access_token)) {
				message("获取accessToken失败");
			}
			$userInfo = $this->oauth->getUserInfo($access_token, $openid);
			MonUtil::setClientCookieUserInfo($userInfo, $this::$USER_COOKIE_KEY);
			return $userInfo;
		}
	}

	/**
	 * 概率计算
	 *
	 * @param unknown $proArr
	 * @return Ambigous <string, unknown>
	 */
	function get_rand($proArr)
	{
		$result = '';
		// 概率数组的总概率精度
		$proSum = array_sum($proArr);
		// 概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum); // 抽取随机数
			if ($randNum <= $proCur) {
				$result = $key; // 得出结果
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset($proArr);
		return $result;
	}


	/**
	 * author: 
	 * @param $sid
	 * @param $openid
	 * @return bool
	 * 总次数
	 */
	public function  findUserRecordCount($sid, $openid)
	{
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_RECORD) . " WHERE  sid=:sid and openid=:openid ", array(':sid' => $sid, ":openid" => $openid));
		return $count;
	}

	/**
	 * author: 
	 * @param $sid
	 * @param $openid
	 * @return bool 查找分享次数
	 */
	public function  findUserDayShareCount($sid, $openid)
	{
		$today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
		$today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));

		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_SHARE) . " WHERE  sid=:sid and openid=:openid and createtime<=:endtime and  createtime>=:starttime ", array(':sid' => $sid, ":openid" => $openid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
		return $count;
	}

	/**
	 * author: 
	 * @param $sid
	 * @param $openid
	 * @return bool
	 */
	public function  findUserDayAward($sid, $openid)
	{
		$today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
		$today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));
		$count = pdo_fetchcolumn('SELECT sum(award_count) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_SHARE) . " WHERE  sid=:sid and openid=:openid and createtime<=:endtime and  createtime>=:starttime ", array(':sid' => $sid, ":openid" => $openid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
		return $count;
	}

	/**
	 * author: 
	 * @param $sid
	 * @param $pid
	 * @return bool中奖次数
	 */
	public function  findPrizeAwardCount($sid, $pid)
	{
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_RECORD) . " WHERE  sid=:sid and pid=:pid ", array(':sid' => $sid, ":pid" => $pid));
		return $count;
	}

	/**
	 * author: 
	 * @param $sid
	 * @param $openid
	 * @return bool
	 * 中奖次数
	 */
	public function  findUserPrizeCount($sid, $openid)
	{
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_RECORD) . " WHERE  sid=:sid and openid=:openid and (status=2 or status=1)", array(':sid' => $sid, ":openid" => $openid));
		return $count;
	}

	/**
	 * author: 
	 * @param $sid
	 * @param $openid
	 * @return bool每天次数
	 */
	public function  findUserDayRecordCount($sid, $openid)
	{

		$today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
		$today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));

		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_RECORD) . " WHERE  sid=:sid and openid=:openid and createtime<=:endtime and  createtime>=:starttime ", array(':sid' => $sid, ":openid" => $openid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
		return $count;
	}


	/**
	 * author: 
	 * 中奖html
	 */
	public function doMobileWin_prize()
	{
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$sid = $_GPC['sid'];
		$shake = DBUtil::findById(DBUtil::$TABLE_QMSHAKE,$sid);
		$openid =  $this->getOpenId();
		//$openid = $_W['fans']['from_user'];
		if (empty($openid)) {
			message("请授权登录后再进行抽奖");
		}


		$user = DBUtil::findUnique(DBUtil::$TABLE_QMSHAKE_USER,array(":openid"=>$openid,":sid"=>$sid));
		if (empty($user)) {
			message("用户还没有注册");
		}
		if (!empty($user) && !empty($user['uname']) && !empty($user['tel'])) {
			$bind = 1;
		} else {
			$bind = 0;
		}

		$from = $_GPC['from'];
		$oid = $_GPC['oid'];

		include $this->template("win_prize");
	}

	/**
	 * author: 
	 * 兑换
	 */
	public function doMobileDuiHuan() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$rid =$_GPC['rid'];

		$record = DBUtil::findById(DBUtil::$TABLE_QMSHAKE_RECORD,$rid);
		$shake = DBUtil::findById(DBUtil::$TABLE_QMSHAKE,$record['sid']);
		if ($record['status'] == 2) {
			message("该奖品已兑换过!");
		}
		$prize = DBUtil::findById(DBUtil::$TABLE_QMSHAKE_PRIZE,$record['pid']);
		$from = $_GPC['from'];
		$oid = $_GPC['oid'];
		include $this->template("duihuan");
	}

     public function doMobileAjaxDuijiang()
	 {
		 global $_W, $_GPC;
		 $rid =$_GPC['rid'];
		 $dpassword = $_GPC['dpassword'];
		 $record = DBUtil::findById(DBUtil::$TABLE_QMSHAKE_RECORD,$rid);

		 $res =array();
		 if (empty($record)) {
			 $res['code'] = 500;
			 $res['msg'] = "记录删除或不存在";
			 die(json_encode($res));
		 }
		 $shake = DBUtil::findById(DBUtil::$TABLE_QMSHAKE,$record['sid']);
		 if ($record['status'] == 2) {
			 $res['code'] = 500;
			 $res['msg'] = "奖品已经兑换过";
			 die(json_encode($res));
		 }

		if ($shake['dpassword'] == $dpassword) {//密码正确

            $prize = DBUtil::findById(DBUtil::$TABLE_QMSHAKE_PRIZE, $record['pid']);

			if ($prize['ptype'] != 0) {
				$dbjf = $this->doDHCredit($record['openid'], $prize['jfye'], $prize['ptype']);
				if ($dbjf) {
					DBUtil::updateById(DBUtil::$TABLE_QMSHAKE_RECORD,array("status"=>2,'djtime'=>TIMESTAMP),$rid);
				} else {
					$res['code'] = 503;
					$res['msg'] = "兑换积分或奖品出错，请联系开发人员查看日志";
					die(json_encode($res));
				}

			} else {
				DBUtil::updateById(DBUtil::$TABLE_QMSHAKE_RECORD,array("status"=>2,'djtime'=>TIMESTAMP),$rid);
			}

			$res['code'] = 200;
			die(json_encode($res));
		} else {
			$res['code'] = 500;
			$res['msg'] = "密码错误";
			die(json_encode($res));
		}

	 }


	public function doDHCredit($openid, $credit, $credit_type) {
		if ($credit == 0) return false;
	     load()->model('mc');
	     $uid = mc_openid2uid($openid);
	     if ($credit_type == 1) {
			 $result = mc_credit_update($uid, 'credit1', $credit, array($uid,'每天摇一摇兑换积分奖品'));
		 } else if ($credit_type == 2) {
			 $result = mc_credit_update($uid, 'credit2', $credit, array($uid,'每天摇一摇兑换余额奖品'));
		 }

		if ($result == true) {
			return true;
		} else {
			WeUtility::logging('info',"兑换结果".$result);
			return false;
		}
    }

	/**
	 * author: 
	 * 用户分享
	 */
	public  function doMobileUserShare() {
		global $_W, $_GPC;
		$sid = $_GPC['sid'];
		$shake = DBUtil::findById(DBUtil::$TABLE_QMSHAKE,$sid);
		$openid = $this->getOpenId();
		if (!empty($shake) && $shake['share_enable'] == 1 && !empty($openid)) {
			$user =DBUtil::findUnique(DBUtil::$TABLE_QMSHAKE_USER,array(":sid"=>$sid,":openid" => $openid)) ;
			if (!empty($user)) {
				$user_DayShare = $this->findUserDayShareCount($shake['id'],$openid);
				if ($user_DayShare <$shake['share_times']) {
						$shareData = array(
							'sid' =>$sid,
							'uid' =>$user['id'],
							'openid' =>$openid,
							'award_count' =>$shake['share_award_count'],
							'createtime' => TIMESTAMP
						);
					DBUtil::create(DBUtil::$TABLE_QMSHAKE_SHARE,$shareData);

					die(json_encode(array('code'=>250,'leftShare'=>$shake['share_times']-1-$user_DayShare,'awardCount'=>$shake['share_award_count'])));
				}
			}

		}

		die(json_encode(array('code'=>200)));
	}
	/**
	 * author: 
	 * 绑定用户
	 */
	public function doMobileBindUser()
	{
		global $_W, $_GPC;

		$uid = $_GPC['uid'];
		$tel = $_GPC['tel'];
		$uname = $_GPC['uname'] ;
		$udefine = $_GPC['udefine'];

		$user = DBUtil::findById(DBUtil::$TABLE_QMSHAKE_USER,$uid);
		$res =array();
		if (empty($user)) {
			$res['code'] = 500;
			$res['msg'] = "用户不存在";
			die(json_encode($res));
		}
		DBUtil::updateById(DBUtil::$TABLE_QMSHAKE_USER,array('tel'=>$tel,'uname' => $uname, 'udefine'=>$udefine),$uid);
		$res['code'] = 200;
		die(json_encode($res));
	}
	/***************************函数********************************/
	/**
	 * author:
	 * @param $kid
	 * @param $status
	 * @return bool数量
	 */

	function  encode($value)
	{
		return $value;
		return iconv("utf-8", "gb2312", $value);

	}

	public  function  getAccessToken () {
		global $_W;
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($_W['acid']);
		$access_token = $accObj->fetch_token();
		return $access_token;
	}


	public function sendTemplateMsg($shake, $pname, $openid) {
		$templateMsg = array();
		if ($shake['tmpenable'] == 1) {
			$templateMsg['template_id'] = $shake['tmpId'];
			$templateMsg['touser'] = $openid;
			$templateMsg['url'] = MonUtil::str_murl($this->createMobileUrl('shake',array('topenid'=>$openid, 'sid'=>$shake['id']),true));
			$templateMsg['topcolor'] = '#FF0000';
			$data = array();
			$data['first'] = array('value'=>"恭喜您参与的活动中奖了!", 'color'=>'#173177');
			$data['keyword1'] = array('value'=> $shake['title'], 'color'=>'#173177');
			$data['keyword2'] = array('value'=> $pname, 'color'=>'#173177');
			$data['remark'] = array('value'=>"感谢您的参与!", 'color'=>'#173177');
			$templateMsg['data'] = $data;
			$jsonData = json_encode($templateMsg);
			WeUtility::logging('info',"发送模板消息每天摇一摇".$jsonData);
			load()->func('communication');
			$acessToken = $this->getAccessToken();
			$apiUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$acessToken;
			$result = ihttp_request($apiUrl, $jsonData);
			WeUtility::logging('info',"发送模板消息每天摇一摇".$result);

		}
	}

}