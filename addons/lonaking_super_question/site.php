<?php
/**
 * 超级答题模块微站定义
 *
 * @author lonaking
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
require_once 'biz/activity/SqActivityService.php';
require_once 'biz/question/SqQuestionService.php';
require_once 'biz/record/SqRecordService.php';
require_once 'biz/activity_question/SqActivityQuestionService.php';
require_once 'biz/ad/SqAdService.php';
require_once 'biz/team/SqTeamService.php';
require_once 'biz/player/SqPlayerService.php';
require_once 'biz/player/SqPlayerTeamService.php';
require_once 'biz/class/SQClassService.php';
require_once dirname(__FILE__) .'/../lonaking_flash/utils/FlashHelper.php';
require_once dirname(__FILE__) .'/../lonaking_flash/FlashUserService.php';
class Lonaking_super_questionModuleSite extends WeModuleSite {

	private $activityService;
	private $questionService;
	private $recordService;
	private $activityQuestionService;
	private $adService;
	private $flashUserService;
	private $teamService;
	private $playerService;
	private $playerTeamService;

	public function __construct()
	{
		$this->activityService = new SqActivityService();
		$this->questionService = new SqQuestionService();
		$this->recordService = new SqRecordService();
		$this->activityQuestionService = new SqActivityQuestionService();
		$this->adService = new SqAdService();
		$this->flashUserService = new FlashUserService();
		$this->teamService = new SqTeamService();
		$this->playerService = new SqPlayerService();
		$this->playerTeamService = new SqPlayerTeamService();
	}

	/**
	 * delete everything
	 */
	public function doWebHardRemove(){
		global $_GPC;
		checkaccount();//
		$id = $_GPC['id'];
		$opt = $_GPC['opt'];
		if(empty($opt)){
			return $this->return_json(400,'未提供此功能,请联系开发者',null);
		}
		try{
			if($opt == 'activity'){
				$this->activityService->deleteActivityById($id);
			}elseif($opt == 'question'){
				$this->questionService->deleteQuestionById($id);
			}elseif($opt == 'ad'){
				$this->adService->deleteAdById($id);
			}elseif($opt == 'team'){
				$this->teamService->deleteById($id);
			}elseif($opt == 'player'){
				$this->playerService->deletePlayerById($id);
			}elseif($opt == 'team'){
				//TODO 删除队伍
			}elseif($opt == 'player'){
				//TODO 删除人员
			}elseif($opt == 'class'){
				$classService = new SQClassService();
				$classService->deleteClassById($id);
			}else{//未指定操作
				return $this->return_json(400,'未知操作,请联系开发者',null);
			}
			return $this->return_json(200,"删除成功",null);
		}catch (Exception $e){
			return $this->return_json($e->getCode(),$e->getMessage(),null);
		}
	}
	/**
	 * manage activity
	 */
	public function doWebActivityManage(){
		global $_GPC,$_W;
		$this->activityService->checkRegister($this->module);
		$page_index = max(1, intval($_GPC['page']));
		$page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
		$page = $this->activityService->selectPage("ORDER BY current DESC,create_time DESC");
		include $this->template('activity_list');
	}

	/**
	 * set the activity currently
	 */
	public function doWebSetCurrentActivity(){
		global $_GPC;
		$id = $_GPC['id'];
		try{
			$this->activityService->checkCurrentActivity($id);
			return $this->return_json();
		}catch (Exception $e){
			//return error message
			return $this->return_json($e->getCode(),$e->getMessage());
		}
	}

	/**
	 * 团队管理
	 */
	public function doWebTeamManage(){
		global $_GPC;
		$page_index = max(1, intval($_GPC['page']));
		$page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
		$page = $this->teamService->selectPage("ORDER BY create_time ASC");
		if($page['count'] > 0){
			$captain_ids = FlashHelper::fetchModelArrayIds($page['data'],'captain');
			$captains = $this->playerService->selectByIds($captain_ids);
			$tmp_teams = array();
			foreach($page['data'] as $t){
				$tmp_t = $t;
				foreach($captains as $c){
					if($t['captain_id'] == $c['id']){}
					$tmp_t['captain'] = $c;
					break;
				}
				$tmp_teams[] = $tmp_t;
			}
			$page['data'] = $tmp_teams;
		}
		include $this->template('team_list');
	}

	/**
	 * 玩家管理
	 */
	public function doWebPlayerManage(){
		global $_GPC;
		$page_index = max(1, intval($_GPC['page']));
		$page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
		$page = $this->playerService->selectPage("ORDER BY create_time DESC");
		include $this->template('player_list');
	}

	/**
	 * 活动编辑/添加
	 */
	public function doWebActivityEdit(){
		global $_W,$_GPC;
		checkaccount();//
		$id = $_GPC['id'];
		$data = $_GPC['data'];
		$data['uniacid'] = $_W['uniacid'];
		if (!empty($_GPC['submit'])) {//提交表单
			if(empty($data['id'])){
				$data['activity_num'] = 'ACTIVITY_'.date("Ymd",time()).time().$_W['uniacid'].rand(1,9);
				$data['create_time'] = time();
			}
			$data['rule'] = htmlspecialchars_decode($data['rule']);
			$data['update_time'] = time();
			try{
				$this->activityService->insertOrUpdate($data);
				return message("保存成功", $this->createWebUrl("ActivityManage"), "success");
			}catch (Exception $e){
				return message("保存失败", "", "error");
			}
		}else{
			if(!is_null($id)){
				$data = $this->activityService->selectById($id);
				$data['rule'] = htmlspecialchars_decode($data['rule']);
			}
			load()->func('tpl');
			include $this->template('activity_edit');
		}
	}

	/**
	 * 问题管理
	 */
	public function doWebQuestionManage(){
		global $_GPC,$_W;
		$this->activityService->checkRegister($this->module);
		$where = "";
		$class = $_GPC['class'];
		if(!empty($class)){
			if(!is_array($class)){
				$where = "AND class_id={$class}";
			}else{
				$classStr = explode(",",$class);
				$where = "AND class_id in {$classStr}";
			}
		}
		$result_type = $_GPC['result_type'];
		$page_index = max(1, intval($_GPC['page']));
		$page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
		$page = $this->questionService->selectPage("{$where} ORDER BY create_time ASC");
		$option_arr = array('a'=>'选项一','b'=>'选项二', 'c'=>'选项三', 'd'=>'选项四', 'e'=>'选项五');
		if($result_type == 'json'){
			return $this->return_json(200,'success',$page);
		}
		$classService = new SQClassService();
		$class_list = $classService->selectAll();
		include $this->template('question_list');
	}


	/**
	 * 问题描述
	 */
	public function doWebQuestionEdit(){
		global $_W,$_GPC;
		checkaccount();//
		$id = $_GPC['id'];
		$data = $_GPC['data'];
		$data['uniacid'] = $_W['uniacid'];
		if (!empty($_GPC['submit'])) {//提交表单
			//检验选项是否存在
			$option_arr = array('a'=>'选项一','b'=>'选项二','c'=>'选项三','d'=>'选项四','e'=>'选项五');
			if(empty($data['option_'.$data['right_answer']])){
				return message("正确选项没有填写任何内容",'','error');
			}

			if(empty($data['id'])){
				$data['question_num'] = 'QUESTION_'.date("Ymd",time()).time().$_W['uniacid'].rand(1,9);
				$data['create_time'] = time();
			}

			$data['update_time'] = time();
			if($data['ad_id'] == 0){
				$data['ad_id'] == '';
			}
			try{
				$this->questionService->insertOrUpdate($data);
				return message("保存成功",  $this->createWebUrl("QuestionManage"), "success");
			}catch (Exception $e){
				return message("保存失败", "", "error");
			}
		}else{
			if(!is_null($id)){
				$data = $this->questionService->selectById($id);
				$classService = new SQClassService();
				$data['class_list'] = $classService->selectAll();
				//广告
				$ads = $this->adService->selectAll();
			}
			load()->func('tpl');
			include $this->template('question_edit');
		}
	}

	/**
	 * 设置问题
	 */
	public function doWebActivityQuestionManage(){
		global $_W,$_GPC;
		checkaccount();//
		$id = $_GPC['id'];
		$where = "";
		$class = $_GPC['class'];
		if(!empty($class)){
			if(!is_array($class)){
				$where = "AND class_id={$class}";
			}else{
				$classStr = explode(",",$class);
				$where = "AND class_id in {$classStr}";
			}

		}
		$activity = $this->activityService->selectById($id);
		//所有的question
		$question_page = $this->questionService->selectPage("{$where} ORDER BY create_time ASC");
		//取出所有已经选择的记录
		$activity_questions = $this->activityQuestionService->selectAll("AND activity_id={$activity['id']}");
		$result_question_page = $question_page;
		$new_data = array();
		foreach($question_page['data'] as $q){
			$tmp_q = $q;
			foreach($activity_questions as $selected){
				if($q['id'] == $selected['question_id']){
					$tmp_q['selected'] = true;
					break;
				}
			}
			$new_data[] = $tmp_q;
		}
		$result_question_page['data'] = $new_data;
		$option_arr = array('a'=>'选项一','b'=>'选项二', 'c'=>'选项三', 'd'=>'选项四', 'e'=>'选项五');
		$classService = new SQClassService();
		$class_list = $classService->selectAll();
		include $this->template('activity_question_manage');
	}

	/**
	 * 选择一个问题
	 */
	public function doWebActivityQuestionSelect(){
		global $_GPC,$_W;
		global $_W,$_GPC;
		checkaccount();//
		$activity_id = $_GPC['activity_id'];
		$question_id = $_GPC['question_id'];
		$uniacid = $_W['uniacid'];
		if(empty($question_id) || empty($activity_id)){
			return $this->return_json(400,'非法操作',null);
		}
		//取出activity
		$activity = $this->activityService->selectById($activity_id);
		$question = $this->questionService->selectById($question_id);
		if(empty($activity) || empty($question)){
			return $this->return_json(400,'任务或问题不存在',null);
		}
		//检测记录是否存在
		$activity_question = $this->activityQuestionService->selectOne("AND activity_id={$activity_id} AND question_id={$question_id}");
		pdo_begin();
		if(empty($activity_question)){//存储
			$data = array(
				'uniacid' => $uniacid,
				'activity_id' => $activity_id,
				'question_id' => $question_id,
				'question_score' => $question['score']
			);
			$this->activityQuestionService->insertData($data);
			$this->activityService->columnAddCount('question_count',1,$activity_id);
		}else{//删除
			$this->activityQuestionService->deleteById($activity_question['id']);
			$this->activityService->columnReduceCount('question_count',1,$activity_id);
		}
		pdo_commit();
		return $this->return_json(200,'success',null);
	}

	/**
	 * 答题记录 暂时先这么写
	 */
	public function doWebRecordManage(){
		global $_GPC,$_W;
		$page_index = max(1, intval($_GPC['page']));
		$page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
		if(empty($page_index)){
			$page_index = max(1, intval($_GPC['page']));
		}
		if(empty($page_size)){
			$page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
		}
		$where = ($page_index -1) * $page_size . ',' .$page_size;
		$orderBy = "r.create_time DESC,";
		$tmpData = pdo_fetchall("select r.id,r.uniacid,r.record_number,r.type,r.player_id,r.openid,r.uid,r.is_captain,r.right,r.wrong,r.right_ids,r.wrong_ids,r.score,r.activity_id,r.answer_seconds,r.shared,r.is_help,r.help_record_id,r.create_time,r.update_time,a.name activity_name,a.limit_seconds,p.headimgurl,p.nickname FROM ".tablename($this->recordService->table_name) ." r LEFT JOIN ".tablename($this->activityService->table_name)." a ON r.activity_id=a.id LEFT JOIN ".tablename($this->playerService->table_name)." p ON r.player_id=p.id WHERE r.uniacid={$_W['uniacid']} ORDER BY {$orderBy}r.id DESC LIMIT {$where} ");
		$tmpCount = $this->recordService->count();
		$pager = pagination($tmpCount, $page_index, $page_size);
		$page = array(
			'data' => $tmpData,//数据
			'count' => $tmpCount,//总数量
			'pager' => $pager,//分页插件
			'page_index' => $page_index,
			'page_size' => $page_size
		);
		//$page = $this->recordService->selectPage("ORDER BY create_time ASC");
		include $this->template('record_list');
	}

	/**
	 * 广告管理
	 * @throws Exception
	 */
	public function doWebAdManage(){
		global $_W,$_GPC;
		$result_type = $_GPC['result_type'];
		$page = $this->adService->selectPage("ORDER BY create_time ASC");
		if($result_type == 'json'){
			return $this->return_json(200,'success',$page);
		}
		include $this->template('ad_list');
	}

	/**
	 * 广告添加或者修改
	 * @throws Exception
	 */
	public function doWebAdEdit(){
		global $_GPC, $_W;
		checkaccount();//
		$id = $_GPC['id'];
		if (!empty($_GPC['submit'])) {//提交表单
			$data = $_GPC['data'];
			$data['uniacid'] = $_W['uniacid'];
			$data['delay'] = 5;
			$data['type'] = 1;
			$data['update_time'] = time();
			try{
				if(empty($data['id'])){
					$data['create_time'] = time();
				}
				$this->adService->insertOrUpdate($data);
				return message("信息保存成功",  $this->createWebUrl("AdManage"), "success");
			}catch (Exception $e){
				return message("信息保存失败", "", "error");
			}
		}else{
			$data = null;
			if(!is_null($id)){
				$data = $this->adService->selectById($id);
			}
			load()->func('tpl');
			include $this->template('ad_edit');
		}
	}

	public function doWebClassManage(){
		global $_W,$_GPC;
		$result_type = $_GPC['result_type'];
		$classService = new SQClassService();
		$page = $classService->selectPage("ORDER BY create_time ASC");
		if($result_type == 'json'){
			return $this->return_json(200,'success',$page);
		}
		$html = array(
			'data' =>  $page['data'],
		);
		include $this->template('class_manage');
	}

	public function doWebClassEdit(){
		global $_GPC, $_W;
		checkaccount();//
		$id = $_GPC['id'];
		$classService = new SQClassService();
		if (!empty($_GPC['submit'])) {//提交表单
			$data = $_GPC['data'];
			$data['uniacid'] = $_W['uniacid'];
			$data['update_time'] = time();
			try{
				if(empty($data['id'])){
					$data['create_time'] = time();
					$classService->createClass($data['name']);
				}else{
					$classService->updateData($data);
				}
				return message("信息保存成功",  $this->createWebUrl("ClassManage"), "success");
			}catch (Exception $e){
				return message("信息保存失败", "", "error");
			}
		}else{
			$data = null;
			if(!is_null($id)){
				$data = $classService->selectById($id);
			}
			load()->func('tpl');
			include $this->template('class_edit');
		}
	}

	/**
	 * 移动端首页
	 */
	public function doMobileIndex() {
		global $_GPC, $_W;
		if(empty($_W['openid'])){
			return message("请在微信中打开此页面","","error");
		}
		$this->flashUserService->authFansInfo();
		$player = $this->playerService->checkPlayerRegister();
		try{
			$activity = $this->activityService->getActivityAndRandomQuestionsById();
			$urls = array(
				'send_result_url' => $this->createMobileUrl('RecordNote'),
				'share_callback' => $this->createMobileUrl('ShareCallback'),
				'check_limit_url' => $this->createMobileUrl("CheckPlayLimit"),
			    'rank_page' => $this->createMobileUrl('ActivityRankPage',array('activity_id'=> $activity['id'])),
			    'follow_url' => $this->module['config']['follow_url']
			);
			$html = array(
				'jsconfig' => $_W['account']['jssdkconfig'],
				'config' => $this->module['config'],
				'questions' => $activity['questions'],
				'activity' => $activity,
				'question_count' => sizeof($activity['questions']),
			    'follow' => $player['fans_info']['follow'],
			);
			//share content
			$share = array(
				'share_title' => "",
				'share_logo' => tomedia($activity['share_logo']),
				'share_url' => $_W['siteroot'].'app'.substr($this->createMobileUrl('Index',array('openid'=>$_W['openid'])),1),
				'share_description' => $activity['share_description'],
			);
			include $this->template('index');
		}catch (Exception $e){
			return message($e->getMessage(),"","error");
		}


	}

	/**
	 * check the player limit
	 */
	public function doMobileCheckPlayLimit(){
		global $_GPC, $_W;
		if(empty($_W['openid'])){
			return message("请在微信中打开此页面","","error");
		}
		$openid = $_W['openid'];
		try{
			$player = $this->playerService->selectPlayerByOpenid($openid);
			$activity = $this->activityService->selectById($_GPC['activity_id']);
			$limit = $this->recordService->checkPlayLimit($openid,$activity['id'],$activity['play_limit'],$activity['limit_type']);
			$result = array(
				'limit' => $limit['limit'],
				'count' => $limit['count'],
				'message' => $this->preparePlayLimitMessage($activity['name'],$player['nickname'],$limit['count'],$activity['play_limit'])
			);
			return $this->return_json(200,$result['message'],$result);
		}catch (Exception $e){
			return $this->return_json($e->getCode(),$e->getMessage(),null);
		}
	}


	public function doMobileCheckHelpPlayLimit(){
		global $_GPC, $_W;
		if(empty($_W['openid'])){
			return message("请在微信中打开此页面","","error");
		}
		try{
			$player = $this->playerService->selectPlayerByOpenid($_W['openid']);
			$activity = $this->activityService->selectById($_GPC['activity_id']);
			$helpRecordList = $this->recordService->getHelpRecordList($_W['openid'],$_GPC['help_record_id']);
			$count = sizeof($helpRecordList);
			$limit = $activity['help_limit'] > $count;
			$result = array(
				'limit' => $limit,
				'count' => $count,
				'message' => "本活动每位用户仅限帮助好友答题{$activity['help_limit']}次，您已经答了{$count}次了，分享给好友让他们帮助朋友答题吧！"
			);
			return $this->return_json(200,$result['message'],$result);
		}catch (Exception $e){
			return $this->return_json($e->getCode(),$e->getMessage(),null);
		}

	}
	/**
	 * prepare message for help index page
	 * @param $activity
	 * @param $helpPlayer
	 * @param $record
	 * @param $questions
	 * @return string
	 */
	private function prepareHelpMessage($activity,$helpPlayer,$record,$questions){
		$result = $this->module['config']['help_message'];
		if(strpos($result,"[name]") != false){
			$tmp = explode("[name]",$result);
			$result = $tmp[0].$helpPlayer['nickname'].$tmp[1];
		}
		//activity title
		if(strpos($result,"[activity_title]") != false){
			$tmp = explode("[activity_title]",$result);
			$result = $tmp[0].$activity['name'].$tmp[1];
		}
		//wrong question count
		if(strpos($result,"[wrong]") != false){
			$tmp = explode("[wrong]",$result);
			$result = $tmp[0].$record['wrong'].$tmp[1];
		}
		//right question count
		if(strpos($result,"[right]") != false){
			$tmp = explode("[right]",$result);
			$result = $tmp[0].$record['right'].$tmp[1];
		}
		//
		if(strpos($result,"[score]") != false){
			$tmp = explode("[score]",$result);
			$result = $tmp[0].$record['score'].$tmp[1];
		}
		$helpScore = 0;
		$helpDeScore = 0;
		foreach($questions as $q){
			$helpScore = $helpScore + $q['score'];
			$helpDeScore = $helpDeScore + $q['de_score'];
		}
		//
		if(strpos($result,"[help_score]") != false){
			$tmp = explode("[help_score]",$result);
			$result = $tmp[0].$helpScore.$tmp[1];
		}
		//
		if(strpos($result,"[help_de_score]") != false){
			$tmp = explode("[help_de_score]",$result);
			$result = $tmp[0].$helpDeScore.$tmp[1];
		}
		return $result;
	}

	private function prepareShareContent($content,$activityTitle,$playerName,$wrongCount,$rightCount){
		//player name
		$tmp = "";
		$result = $content;
		if(strpos($content,"[name]") != false){
			$tmp = explode("[name]",$result);
			$result = $tmp[0].$playerName.$tmp[1];
		}
		//activity title
		if(strpos($result,"[activity_title]") != false){
			$tmp = explode("[activity_title]",$result);
			$result = $tmp[0].$activityTitle.$tmp[1];
		}
		//wrong question count
		if(strpos($result,"[wrong]") != false){
			$tmp = explode("[wrong]",$result);
			$result = $tmp[0].$wrongCount.$tmp[1];
		}
		//right question count
		if(strpos($result,"[right]") != false){
			$tmp = explode("[right]",$result);
			$result = $tmp[0].$rightCount.$tmp[1];
		}
		return $result;
	}
	/**
	 * @param $record
	 * @param $player
	 * @param $activity
	 * @return array
	 */
	private function prepareShareContentWithRecord($record,$player,$activity){
		global $_W;
		$titleType = $this->module['config']['share_title_default'];
		$descriptionType = $this->module['config']['share_description_default'];
		$shareUrl = $_W['siteroot'].'app'.substr($this->createMobileUrl('RecordHelpIndex',array('openid'=>$_W['openid'],'record_id'=>$record['id'])),1);//default is there are some question wrong
		if($record['right'] == 0){// when all right
			$titleType = $this->module['config']['share_title_all_wrong'];
			$descriptionType = $this->module['config']['share_description_all_wrong'];
		}
		if($record['wrong'] == 0){////when all wrong
			$titleType = $this->module['config']['share_title_all_right'];
			$descriptionType = $this->module['config']['share_description_all_right'];
			$shareUrl = $_W['siteroot'].'app'.substr($this->createMobileUrl('Index',array('openid'=>$_W['openid'])),1);//all of questions were right
		}else{// default

		}
		$shareTitle = $this->prepareShareContent($titleType,$activity['name'],$player['nickname'],$record['wrong'],$record['right']);
		$shareDescription = $this->prepareShareContent($descriptionType,$activity['name'],$player['nickname'],$record[wrong],$record[right]);
		$helpWillGotScore = 0;
		if($record['wrong'] > 0){
			$helpWillGotScore = $this->questionService->getTotalScoreByQuestionIds(explode(",",$record['wrong_ids']));
		}
		$result = array(
			'share' => array(
				'share_logo' => $activity['share_logo'],
				'share_title' => $shareTitle,
				'share_description' => $shareDescription,
				'share_url'=> $shareUrl,
			),
			"record" => $record,
			"activity" => $activity,
			"player" => $player,
			//TODO 各种提示
			'result_analyse' => $this->analyseResult($record['score'],$activity),
			'score' => $record['score'],
			'tip' => " 答错".$record['wrong']."道题,转发朋友圈求助圈里好友,让他/她帮你答题,答对全部可获".$helpWillGotScore."积分哦",
		);
		if($record['answer_seconds'] > $activity['limit_seconds']){
			$result['tip'] = "[时间到]:".$result['tip'];
		}
		return $result;
	}

	private function analyseResult($score,$activity){
		$analyseMessage = $activity['analyse_message'];
		//do with message
		$result = "";
		$arr = explode("\n",$analyseMessage);
		foreach($arr as $a){
			$startMessageIndexOf = stripos($a,"]");
			$message = substr($a, $startMessageIndexOf+1);
			$quJian = substr($a,1,$startMessageIndexOf-1);
			$quJianArr = explode(",",$quJian);
			$min = $quJianArr[0];
			$max = $quJianArr[1];
			if($score >= $min && $score <= $max){
				$result = $message;
				return $result;
			}
		}
		return $result;
	}

	public function doWebCheckAnalyse(){
		$activity = $this->activityService->selectById(2);
		global $_GPC;
		$score = $_GPC['score'];
		$message = $this->analyseResult($score,$activity);
		echo $message;
	}

	/**
	 * prepare the message for user, when he have no chance to play the game
	 * @param $activityTitle
	 * @param $playerName
	 * @param $playCount
	 * @param $playLimit
	 * @return string
	 */
	private function preparePlayLimitMessage($activityTitle,$playerName,$playCount,$playLimit){
		$content = $this->module['config']['play_limit_message'];
		$result = $content;
		if(strpos($content,"[name]") != false){
			$tmp = explode("[name]",$result);
			$result = $tmp[0].$playerName.$tmp[1];
		}
		//activity title
		if(strpos($result,"[activity_title]") != false){
			$tmp = explode("[activity_title]",$result);
			$result = $tmp[0].$activityTitle.$tmp[1];
		}
		//wrong question count
		if(strpos($result,"[play_limit]") != false){
			$tmp = explode("[play_limit]",$result);
			$result = $tmp[0]."$playLimit".$tmp[1];
		}
		//right question count
		if(strpos($result,"[play_count]") != false){
			$tmp = explode("[play_count]",$result);
			$result = $tmp[0]."$playCount".$tmp[1];
		}
		return $result;
	}
	/**
	 * 移动端 活动
	 */
	public function doMobileActivity(){
		global $_GPC, $_W;
		$activity_id = $_GPC['activity_id'];
		$this->activityService->selectOne("AND current=1");
		$activity = $this->activityService->selectById($activity_id);
		//取出所有的问题记录
		$activity_questions = $this->activityQuestionService->selectAll("AND activity_id={$activity['id']}");
		$question_ids = array();
		foreach($activity_questions as $aq){
			$question_ids[] = $aq['question_id'];
		}
		$questions = $this->questionService->selectByIds($question_ids);

		$result = array(
			'questions' => $questions,
			'activity' => $activity,
		);

		$urls = array(
			'send_result_url' => $this->createMobileUrl('RecordNote'),
			'share_callback' => $this->createMobileUrl('ShareCallback')
		);

		$html = array(
			'jsconfig' => $_W['account']['jssdkconfig'],
		);
		include $this->template('index');
	}


	public function doMobileRecordHelpIndex(){
		global $_GPC, $_W;
		if(empty($_W['openid'])){
			return message("请在微信中打开此页面","","error");
		}
		$recordId = $_GPC['record_id'];
		//取出最初玩家的游戏记录
		$record = $this->recordService->selectById($recordId);
		$player = $this->playerService->selectById($record['player_id']);
		$currentPlayer = $this->playerService->checkPlayerRegister();
		//取出玩的活动
		$activity = $this->activityService->selectById($record['activity_id']);
		if(empty($activity) || empty($record['wrong_ids'])){
			$locationUrl = $this->createMobileUrl("Index");
			header("Location:".$locationUrl);
			//return message('该玩家已经成功答对所有题目','','error');
		}
		$wrong_question_ids = explode(',',$record['wrong_ids']);
		//取出没有答对的问题
		$questions = $this->questionService->selectByIds($wrong_question_ids);
		$html = array(
			'config' => $this->module['config'],
			'questions' => $questions,
			'question_count' => sizeof($questions),
			'activity' => $activity,
			//TODO 成绩记录的url
			'from_player' => $player,
			'help_message' => $this->prepareHelpMessage($activity,$player,$record,$questions),
			'follow' => $currentPlayer['fans_info']['follow'],
		);
		$urls = array(
			'send_result_url' => $this->createMobileUrl('HelpRecordNote',array('help_record_id'=>$recordId)),
			'share_callback' => $this->createMobileUrl('ShareCallback'),
			'check_limit_url' => $this->createMobileUrl("CheckHelpPlayLimit",array('help_record_id'=>$recordId)),
			'start_game' => $this->createMobileUrl('Index'),
			'follow_url' => $this->module['config']['follow_url']
		);
		$share = array(
			'jsconfig' => $_W['account']['jssdkconfig'],
			'share_title' => "",
			'share_logo' => tomedia($activity['share_logo']),
			'share_url' => $_W['siteroot'].'app'.substr($this->createMobileUrl('Index',array('openid'=>$_W['openid'])),1),
			'share_description' => $activity['share_description'],
		);
		include $this->template('help-index');
	}

	/**
	 * 根据回答正确的id数组字符串、总问题数组字符串过虑出错误数组
	 * @param $allQuestionIds
	 * @param $rightIds
	 * @param string $re array 返回数组  str 返回字符串
	 * @return array|string
	 */
	private function filterWrongIds($allQuestionIds, $rightIds,$re = "array"){
		$allQuestionIdsArray = null;
		$rightIdsArray = null;
		if(is_array($allQuestionIds)){
			$allQuestionIdsArray = $allQuestionIds;
		}else{
			$allQuestionIdsArray = explode(",",$allQuestionIds);
		}

		if(is_array($rightIds)){
			$rightIdsArray = $rightIds;
		}else{
			$rightIdsArray = explode(",",$rightIds);
		}
		$wrongIdsArray = array_diff($allQuestionIdsArray,$rightIdsArray);
		if($re == "array"){
			return $wrongIdsArray;
		}elseif($re == "str"){
			return implode(",",$wrongIdsArray);
		}
	}
	/**
	 * 答题结束，普通的成绩记录api
	 */
	public function doMobileRecordNote(){
		global $_W,$_GPC;
		$activityId = $_GPC['activity_id'];
		$openid = $_W['openid'];
		$questionIds = $_GPC['question_ids'];
		if(empty($openid)){
			return $this->return_json(400,'非法操作',null);
		}
		try{
			$uid = $this->flashUserService->fetchUid($openid);
			$currentPlayer = $this->playerService->selectPlayerByOpenid($openid);
			$activity = $this->activityService->selectById($activityId);
			$rightIds = (empty($_GPC['right_ids']))? null : implode(",",$_GPC['right_ids']);
			//$wrongIds = (empty($_GPC['wrong_ids']))? null : implode(",",$_GPC['wrong_ids']);
			$wrongIds = $this->filterWrongIds($questionIds,$rightIds,"str");
			//TODO 错误id后台判断
			$record = array(
				'uniacid' => $_W['uniacid'],
				'record_number' => date('Ymd').time().$_W['uniacid'].rand(0,9),
				'openid' => $openid,
				'player_id' => $currentPlayer['id'],
				'type' => 1,//1普通模式 2.好友帮助 3团队模式
				'uid' =>$uid,
				'is_captain' => 0,
				'right' => $_GPC['right'],//答错
				'wrong' => $_GPC['wrong'],//答错
				'question_ids' => $questionIds,//所有问题
				'right_ids' => $rightIds,
				'wrong_ids' => $wrongIds,
				'score' => $_GPC['score'],
				'max' => 0,
				'activity_id' => $activity['id'],
				'activity_name' => $activity['name'],
				'answer_seconds' => $_GPC['answer_seconds'],
				'shared' => false,
				'is_help' => 0,
				'create_time'=> time(),
				'update_time' => time()
			);
			$maxRecord = $this->recordService->getMaxRecordByOpenidAndActivityId($openid,$activityId);
			if($maxRecord['score'] <= $record['score']){
				$record['max'] = 1;
				$this->recordService->updateColumn("max",0,$maxRecord['id']);
			}
			$this->playerService->chargeScore($currentPlayer['id'],$_GPC['score']);
			$this->activityService->columnAddCount("play_times",1,$activity['id']);
			if($_GPC['score'] > 0){
				$this->activityService->columnReduceCount("score",$_GPC['score'],$activity['id']);
			}
			$this->playerService->columnAddCount("play_times",1,$currentPlayer['id']);
			$record = $this->recordService->insertData($record);
			$result = $this->prepareShareContentWithRecord($record,$currentPlayer,$activity);
			return $this->return_json(200,'success',$result);
		}catch (Exception $e){
			return $this->return_json($e->getCode(),$e->getMessage());
		}
	}

	public function doMobileHelpRecordNote(){
		global $_W,$_GPC;
		$helpRecordId = $_GPC['help_record_id'];
		$activityId = $_GPC['activity_id'];
		$openid = $_W['openid'];
		if(empty($openid)){
			return $this->return_json(400,'非法操作',null);
		}
		
		try{
		    $helpRecord = $this->recordService->selectById($helpRecordId);
			if(empty($helpRecord['wrong_ids'])){
                return $this->return_json(400,"该记录已完成，感谢您的参与",null);
            }
		    if(empty($helpRecord)){
		        return $this->return_json(400,'不存在该游戏记录',null);
		    }
			$uid = $this->flashUserService->fetchUid($openid);
			$currentPlayer = $this->playerService->selectPlayerByOpenid($openid);
			$activity = $this->activityService->selectById($activityId);
			$rightIds = (empty($_GPC['right_ids']))? null : implode(",",$_GPC['right_ids']);
			//$wrongIds = (empty($_GPC['wrong_ids']))? null : implode(",",$_GPC['wrong_ids']);
			$wrongIds = $this->filterWrongIds($helpRecord['wrong_ids'],$rightIds,"str");
			$record = array(
				'uniacid' => $_W['uniacid'],
				'record_number' => date('Ymd').time().$_W['uniacid'].rand(0,9),
				'openid' => $openid,
				'player_id' => $currentPlayer['id'],
				'type' => 1,//1普通模式 2.好友帮助 3团队模式
				'uid' =>$uid,
				'is_captain' => 0,
				'right' => $_GPC['right'],
				'wrong' => $_GPC['wrong'],
				'right_ids' => $rightIds,
				'wrong_ids' => $wrongIds,
			    'question_ids' => $helpRecord['wrong_ids'],//
				'score' => $_GPC['score'],
				'max' => 0,
				'activity_id' => $activity['id'],
				'activity_name' => $activity['name'],
				'answer_seconds' => $_GPC['answer_seconds'],
				'shared' => false,
				'is_help' => 1,
				'help_record_id' => $helpRecordId,
				'create_time'=> time(),
				'update_time' => time()
			);
			$realPlayer = $this->playerService->selectById($helpRecord['player_id']);
			$this->playerService->chargeHelpScore($realPlayer['id'],$currentPlayer['id'],$_GPC['score']);
			//修改原记录
			if($record['right'] > 0){
				//$helpRecord['right'] = $helpRecord['right'] + $record['right'];
				//$helpRecord['wrong'] = $helpRecord['wrong'] - $record['right']; //TODO the coutn of wrong questions can not be done
				if($record['score'] > 0){//when score rather than zero
					$helpRecord['score'] = $helpRecord['score'] + $record['score'];
				}
				// update new wrong ids
				$helpRecord['wrong_ids'] = $record['wrong_ids'];
				$this->recordService->updateData($helpRecord);
			}
			$this->activityService->columnAddCount("play_times",1,$activity['id']);
			$this->activityService->columnAddCount("score",$_GPC['score'],$activity['id']);
			$this->playerService->columnAddCount("play_times",1,$currentPlayer['id']);
			$record = $this->recordService->insertData($record);
			$result = $this->prepareShareContentWithRecord($record,$currentPlayer,$activity);
			return $this->return_json(200,'success',$result);
		}catch (Exception $e){
			return $this->return_json($e->getCode(),$e->getMessage());
		}
	}
	/**
	 * 分享回调
	 */
	public function doMobileShareCallback(){
		global $_W,$_GPC;
		$activityId = $_GPC['activity_id'];
		$recordId = $_GPC['record_id'];
		try{
			$this->activityService->columnAddCount('share_times',1,$activityId);
			$record = $this->recordService->columnAddCount('shared',1,$recordId);
			$this->playerService->columnAddCount("share_times",1,$record['player_id']);
			return $this->return_json();
		}catch (Exception $e){
			return $this->return_json($e->getCode(),$e->getMessage(),null);
		}


	}
	/**
	 * show the player's history game play,show the score list
	 */
	public function doMobileMyHistoryScore(){
		global $_W;
		//获取游戏记录
		$player = $this->playerService->selectPlayerByOpenid($_W['openid']);
		$records = $this->recordService->selectByPlayerId($player['id']);
		//获取用户信息
		$fans_info = $this->flashUserService->fetchFansInfo($_W['openid']);
		$player['fan_info'] = $fans_info;
		include $this->template('my_score');
	}

	public function doMobileActivityRankPage(){
		global $_W,$_GPC;
		$activity = $this->activityService->selectById($_GPC['activity_id']);
		$user =  $this->playerService->selectPlayerByOpenid($_W['openid']);
		$record = $this->recordService->getMaxRecordByOpenidAndActivityId($_W['openid'],$activity['id']);
		$user['rank'] = $this->recordService->rankOne($record['id'],"AND a.max=1 AND a.activity_id={$activity['id']} ORDER BY a.score DESC");
		$html = array(
			'activity' => $activity,
			'user' => $user,
			'record' => $record
		);
		$urls = array(
			'load_activity_ranking_url' => $this->createMobileUrl("ActivityRank",array("activity_id"=> $activity['id']))
		);
		include $this->template('activity-ranking');
	}

	public function doMobileActivityRank(){
		global $_W,$_GPC;
		$activity = null;
		try{
			$activity = $this->activityService->selectById($_GPC['activity_id']);
			$page_index = max(1, intval($_GPC['page']));
			$page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
			if(empty($page_index)){
				$page_index = max(1, intval($_GPC['page']));
			}
			if(empty($page_size)){
				$page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
			}
			$limit = ($page_index -1) * $page_size . ',' .$page_size;
			$where = "AND r.uniacid={$_W['uniacid']} AND r.max=1 ";
			$countWhere = "AND max=1 ";
			if(!empty($activity)){
				$where = $where."AND r.activity_id={$activity['id']}";
				$countWhere = $countWhere."AND activity_id={$activity['id']}";
			}
			$orderBy = "r.score DESC,r.answer_seconds ASC,r.create_time DESC,";
			$tmpData = pdo_fetchall("select r.id,r.uniacid,r.record_number,r.type,r.player_id,r.openid,r.uid,r.is_captain,r.right,r.wrong,r.right_ids,r.wrong_ids,r.score,r.activity_id,r.answer_seconds,r.shared,r.is_help,r.help_record_id,r.create_time,r.update_time,a.name activity_name,p.headimgurl,p.nickname FROM ".tablename($this->recordService->table_name) ." r LEFT JOIN ".tablename($this->activityService->table_name)." a ON r.activity_id=a.id LEFT JOIN ".tablename($this->playerService->table_name)." p ON r.player_id=p.id WHERE 1=1 {$where} ORDER BY {$orderBy}r.id DESC LIMIT {$limit} ");
			$tmpCount = $this->recordService->count($countWhere);
			$pager = pagination($tmpCount, $page_index, $page_size);
			$page = array(
				'data' => $tmpData,//数据
				'count' => $tmpCount,//总数量
				'pager' => $pager,//分页插件
				'page_index' => $page_index,
				'page_size' => $page_size
			);
			return $this->return_json(200,'success',$page);
		}catch (Exception $e){
			return $this->return_json($e->getCode(),$e->getMessage());
		}
	}

	public function doMobilePersonRankPage(){
		global $_W;
		$user =  $this->playerService->selectPlayerByOpenid($_W['openid']);
		$user['rank'] = $this->playerService->rankOne($user['id']);
		$html = array(
			'user' => $user,
		);
		$urls = array(
			'load_person_ranking_url' => $this->createMobileUrl("PersonRank")
		);
		include $this->template('person-ranking');
	}


	/**
	 * person ranking
	 */
	public function doMobilePersonRank(){
		$page = $this->playerService->selectPageOrderBy("","total_score DESC,play_times DESC,");
		return $this->return_json(200,'success',$page);
	}
	/**
	 * add team api
	 */
	public function doMobileTeamAdd(){
		global $_GPC,$_W;
		$data = $_GPC['data'];//html will submit name,member count,and team logo
		if(empty($data['name'])){
			return $this->return_json(400,'团队名不能为空');
		}
		$data['member_count'] = empty($data['member_count'])?10:$data['member_count'];
		$data['uniacid'] = $_W['uniacid'];
		$data['captain'] = $_W['openid'];
		$data['activity_times'] = 0;
		$data['score'] = 0;
		$data['question_count'] = 0;
		$data['start_time'] = time();
		$data['create_time'] = time();
		$data['update_time'] = time();
		$team = $this->teamService->insertData($data);
		return $this->return_json(200,'success',$team);
	}

	/**
	 * invite wechat user join the team
	 */
	public function doMobileTeamInvite(){
		global $_GPC,$_W;
		$team_id = $_GPC['id'];
		$team = $this->teamService->selectById($team_id);
		$players = $this->playerService->selectPlayersByTeamId($team_id);
		$captain = $this->playerService->selectById($team['team_captain']);
		include $this->template('team_invite_page');
	}

	/**
	 * players can join one or more them by their self
	 */
	public function doMobileTeamJoin(){
		global $_GPC,$_W;
		$team_id = $_GPC['team_id'];
		$team = $this->teamService->selectById($team_id);
		$player = $this->playerService->selectPlayerByOpenid($_W['openid']);

		$captain = $this->playerService->selectById($team['team_captain']);
		//TODO here should notice the captain with template notice.


		include $this->template('team_invite_page');
	}

	/**
	 * the captain can make any player to leave his or her team
	 */
	public function doMobileOutPlayer(){
		global $_GPC,$_W;
		$team_id = $_GPC['team_id'];
		$player_id = $_GPC['player_id'];
		$captain = $this->playerService->selectPlayerByOpenid($_W['openid']);
		$team = $this->teamService->selectById($team_id);
		if($team['captain'] != $captain['id']){
			return $this->return_json(400,'您没有权限，因为您不是队长',null);
		}
		$player = $this->playerService->selectPlayerByOpenid($_W['openid']);
		//TODO here should check the player if exists
		$this->playerTeamService->removePlayerById($player['id'],$team_id);
		return $this->return_json(200,'success',null);
	}
	/**
	 * this page can update the system by your self
	 */
	public function doWebUpdatePage(){
		$urls = array(
			'update_url' => $this->createWebUrl('updateLonaking'),
		);
		include $this->template('update_page');
	}
	/**
	 * the api which update the system by your self
	 */
	public function doWebUpdateLonaking(){

		return $this->return_json(200,'更新成功',null);
	}
	/**
	 * return json data to web
	 */
	private function return_json($status = 200,$message = 'success',$data = null){
		exit(json_encode(
			array(
				'status' => $status,
				'message' => $message,
				'data' => $data
			)
		)
		);
	}

}