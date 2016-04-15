<?php

defined('IN_IA') or exit('Access Denied');
require_once 'biz/player/FjPlayerService.php';
require_once 'biz/record/FjRecordService.php';
class Lonaking_fishjoyModuleSite extends WeModuleSite {

	private $playerService;
	private $recordService;

	public function __construct()
	{
		$this->playerService = new FjPlayerService();
		$this->recordService = new FjRecordService();
	}

	/**
	 * game portal
	 */
	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
		load()->model('mc');
		mc_oauth_userinfo();
		$player = $this->playerService->checkPlayerRegister();
		$html = array(
			'config' => $this->module['config']
		);
		$url = array(
			'score_lottery' => $this->createMobileUrl('scoreLotteryApi'),
			'gift_shop_url' => $this->module['config']['gift_shop_url'],
			'config' => $this->createMobileUrl('configApi'),
		);

		include $this->template('index');
	}

	/**
	 * config api
	 */
	public function doMobileConfigApi(){
		$config = $this->module['config'];
		$player = $this->playerService->checkPlayerRegister();
		$record = $this->recordService->getTodayRecordList();
		$todayPlayTimes = sizeof($record);
		$config['token'] = $player['token'];
		$config['createTime'] = time();
		$config['playTimePrivilege'] = $config['playTimePrivilege'] - $todayPlayTimes;
		return $this->return_json(200,'success',$config);
	}

	/**
	 * score lottery
	 */
	public function doMobileScoreLotteryApi(){
		global $_W,$_GPC;
		if(empty($_w['openid'])){

		}
		$score = $_GPC['score'];
		if($score < 0){
			return $this->return_json(400,'非法操作',null);
		}
		$player = $this->playerService->checkPlayerRegister();
		if(empty($player)){
			return $this->return_json(400,'玩家不存在',null);
		}
		//增加游戏次数和成绩
		$this->playerService->AddScore($score);
		$this->playerService->columnAddCount('play_times',1,$player['id']);
		//增加游戏记录
		$this->recordService->addPlayerPlayRecord($score);

		return $this->return_json(200,'操作成功',array('tips'=>$score."积分"));

	}

	/**
	 * manage the players
	 */
	public function doWebPlayerManage() {
		//这个操作被定义用来呈现 管理中心导航菜单
		$page = $this->playerService->selectPage("ORDER BY create_time ASC");

		include $this->template('player_list');
	}

	/**
	 * manage records
	 */
	public function doWebRecordManage(){
		//这个操作被定义用来呈现 管理中心导航菜单
		$page = $this->recordService->selectPage("ORDER BY create_time ASC");
		include $this->template("record_list");
	}


	//return json data
	private function return_json($code = 200,$msg = 'success',$data = null){
		exit(json_encode(
			array(
				'code' => $code,
				'msg' => $msg,
				'data' => $data
			)
		)
		);
	}
}