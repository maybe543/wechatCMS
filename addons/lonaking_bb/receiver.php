<?php

defined('IN_IA') or exit('Access Denied');
require 'util/LonakingBBSqlHelper.class.php';

class Lonaking_bbModuleReceiver extends WeModuleReceiver {

	private $table = array();
	/* 初始化一下table */
	function __construct()
	{
		$this->table = LonakingBBSqlHelper::$table;
	}
	public function receive() {
		global $_W,$_GPC;
		$type = $this->message['type'];
		$openid = $_W['openid'];
		if($this->message['event'] == 'unsubscribe'){
			$this->log('用户取消关注,删除其relation和tags,openid='.$openid);
			$this->delete_relation($openid);
			$this->delete_tag_by_openid($openid);
		}
	}

	/**
	 * @param $openid 一个openid
	 * @return bool
	 * @throws Exception 当用户没有聊天对象的时候抛出此异常
	 */
	private function fetch_relation($openid = null){
		global $_W;
		$openid = empty($openid) ? $_W['openid'] : $openid;
		$uniacid = $_W['uniacid'];
		$relation = pdo_fetch("select ". $this->table['relation']['columns'] ." from ".tablename($this->table['relation']['name'])." where uniacid = :uniacid and openid = :openid or openid_o = :openid_o ", array(
			':uniacid' => $uniacid,
			':openid' => $openid,
			':openid_o' => $openid
		));
		if(empty($relation)){
			throw new Exception("您还没有聊天对象,请点击配对",6401);
		}
		return $relation;
	}

	/**
	 * 获取当前用户的relation 其中正与他聊天的用户的openid为 $relation['relation_openid']
	 * @param null $openid
	 * @return bool
	 * @throws Exception
	 */
	private function fetch_relation_openid($openid = null)
	{
		global $_W;
		$openid = empty($openid) ? $_W['openid'] : $openid;
		try{
			$relation = $this->fetch_relation();
			//判断过期时间
			$to_user_openid = $relation['openid'] == $openid ? $relation['openid_o'] : $relation['openid'];
			$relation['relation_openid'] = $to_user_openid;
			return $relation;
		}catch (Exception $e){
			throw new Exception($e->getMessage(),$e->getCode());
		}
	}
	/**
	 * 删除所有聊天中的关系
	 * @param $openid
	 */
	private function delete_relation($openid = null) {
		global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
		if($openid == null) $openid = $_W['openid'];
		$relation = $this->fetch_relation_openid($openid);
		pdo_query("DELETE FROM ". tablename($this->table['relation']['name']) ." WHERE uniacid =:uniacid AND ( openid =:openid OR openid_o =:openid_o)", array(
			':uniacid' => $uniacid,
			':openid' => $openid,
			':openid_o' => $openid
		));
		$this->check_buzy_status($openid, 0);
		$this->check_buzy_status($relation['relation_openid'],0);
	}

	/**
	 * 删除一个标签
	 * @param $id
	 * @throws Exception 删除失败抛出此异常
	 */
	private function delete_tag_by_openid($openid = null){
		global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
		if($openid == null){
			$openid = $_W['openid'];
		}
		$i = pdo_delete($this->table['tags']['name'],array(
			'openid' => $openid,
			'uniacid' => $uniacid
		));
		if(!$i){
			throw new Exception('删除失败，或许是您没有添加此标签',5407);
		}
	}

	/**
	 * 切换用户忙碌状态
	 * @param $openid
	 * @param int $buzy
	 */
	private function check_buzy_status($openid = null, $buzy = 1){
		global $_W, $_GPC;
		$uniacid = $_W['uniaccount']['uniacid'];
		$openid = empty($openid) ? $_W['openid'] : $openid;
		pdo_update($this->table['tags']['name'],array( 'buzy' => $buzy ),array( 'uniacid' => $uniacid, 'openid' => $openid));
	}
	/**
	 * 日志操作函数
	 * @param $log_content
	 */
	private function log($log_content){
		load()->func('logging');
		logging_run($log_content,'normal','lonaking_bb',true);
	}
}