<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/27
 * Time: 下午9:54
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
require_once dirname(__FILE__) . '/../activity/SqActivityService.php';
require_once dirname(__FILE__) . '/../player/SqPlayerService.php';
class SqRecordService extends FlashCommonService
{
    private $playerService;
    private $activityService;
    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";
        $this->table_name = 'lonaking_super_question_record';
        $this->columns = 'id,uniacid,record_number,type,player_id,openid,uid,is_captain,`right`,`wrong`,question_ids,right_ids,wrong_ids,score,max,activity_id,answer_seconds,shared,is_help,help_record_id,create_time,update_time';

        //record_number:记录唯一id  openid 作者 type:类型，1普通模式 2 帮助好友答题 3 团队模式 is_captain:是否是队长  help_score:好友帮助得分  is_help:是否帮助别人答题 help_record_id:帮助答题的记录id


    }
    /**
     * 准备service
     * @param array $serviceArray
     */
    private function prepareService(array $serviceArray){
        if(in_array("playerService",$serviceArray)){
            $this->playerService = new SqPlayerService();
        }
        if(in_array("activityService",$serviceArray)){
            $this->activityService = new SqActivityService();
        }
    }

    /**
     * 初始化一条活动记录
     * @param unknown $openid
     * @param unknown $questionIds
     * @param unknown $activity
     * @param number $isHelp
     */
    public function initRecordByOpenid($openid, $questionIds,$activity,$isHelp = 0){
        global $_GPC,$_W;
        $player = $this->playerService->selectPlayerByOpenid($openid);
        $record = array(
            'uniacid' => $_W['uniacid'],
            'record_number' => date('Ymd').time().$_W['uniacid'].rand(0,9),
            'openid' => $openid,
            'player_id' => $player['id'],
            'type' => 1,//1普通模式 2.好友帮助 3团队模式
            'uid' =>$player['uid'],
            'is_captain' => 0,
            'right' => 0,//答错
            'wrong' => 0,//答错
            'right_ids' => '',
            'wrong_ids' => '',
            'score' => 0,
            'max' => 0,
            'activity_id' => $activity['id'],
            'activity_name' => $activity['name'],
            'answer_seconds' => 0,
            'shared' => false,
            'is_help' => $isHelp,
            'create_time'=> time(),
            'update_time' => time()
        );
        return $this->insertData($record);
    }

    public function selectByPlayerId($playerId){
        $records = $this->selectAllOrderBy("AND player_id={$playerId}","create_time ASC,");
        return $records;
    }

    public function noteOneGameRecord($recordForm){

    }

    public function getRecordByUserIdAndActivityId($userId,$activityId){

    }


    /**
     * check the count of activity limit
     * @param $openid
     * @param $activityId
     * @param int $limit
     * @param int $limitType
     * @return int
     */
    public function checkPlayLimit($openid,$activityId,$limit = 1,$limitType = 1){
        $count = 0;
        if($limitType == 1){// 天
            $todayStart = strtotime(date('Y-m-d', time()));
            $count = $this->count("AND openid='{$openid}' AND activity_id={$activityId} AND create_time>{$todayStart}");
        }else if($limitType == 2){//2.按活动
            $count = $this->count("AND openid='{$openid}' AND activity_id={$activityId} ");
        }
        $limit = ($limit - $count > 0) ? $limit-$count : 0;
        return array(
            'limit' => $limit,
            'count' => $count
        );;
    }

    public function getHelpRecordList($openid, $recordId){
        return $this->selectAll("AND openid='{$openid}' AND help_record_id={$recordId} AND is_help=1");
    }
    private function getAllRecordByOpenidAndActivityId($openid, $activityId,$max = true){
        if($max){
            return $this->selectAll("AND openid='{$openid}' AND activity_id={$activityId} AND max=1");
        }else{
            return $this->selectAll("AND openid='{$openid}' AND activity_id={$activityId}");
        }
    }

    public function getMaxRecordByOpenidAndActivityId($openid, $activityId){
        return $this->selectOne("AND openid='{$openid}' AND activity_id={$activityId} AND max=1");
    }

    /**
     * 删除一个玩家的所有游戏记录
     * @param $playerId
     */
    public function deleteAllRecordByPlayerId($playerId){
        pdo_delete($this->table_name,array("player_id"=>$playerId));
    }

    /**
     * 检测活动是否产生活动记录，若产生活动记录，则无法删除
     * @param integer $activityId
     * @throws Exception 当存在活动记录，则不能删除
     */
    public function checkActivityIsInUse($activityId){
        $activity = $this->selectOne("AND activity_id={$activityId}");
        if(!empty($activity)){
            throw new Exception("该活动已产生活动记录，不能删除");
        }
    }
}