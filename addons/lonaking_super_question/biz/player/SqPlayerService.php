<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/10/25
 * Time: 上午1:30
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashUserService.php';
require_once dirname(__FILE__) . '/../record/SqRecordService.php';
class SqPlayerService extends FlashCommonService
{
    private $playerTeamService;
    private $flashUserService;
    private $recordService;
    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";
        $this->table_name = 'lonaking_super_question_player';
        $this->columns = 'id,uniacid,openid,nickname,headimgurl,uid,play_times,share_times,total_score,create_time,update_time';
        $this->flashUserService = new FlashUserService();
    }

    /**
     * 准备service
     * @param unknown $serviceArray
     */
    private function prepareService($serviceArray){
        if(in_array("recordService",$serviceArray)){
            $this->recordService = new SqRecordService();
        }
//         if(in_array("activityQuestionService",$serviceArray)){
//             $this->activityQuestionService = new SqActivityQuestionService();
//         }
//         if(in_array("questionService", $serviceArray)){
//             $this->questionService = new SqQuestionService();
//         }
//         if(in_array("adService", $serviceArray)){
//             $this->adService = new SqAdService();
//         }
        if(in_array("playerTeamService", $serviceArray)){
            $this->playerTeamService = new SqPlayerTeamService();
        }
    }
    public function initPlayerInfo(){
        global $_W;
        $fansInfo = $this->flashUserService->authFansInfo($_W['openid']);
        $headImageUrl = (empty($fansInfo['tag']['avatar'])) ? $fansInfo['avatar'] : $fansInfo['tag']['avatar'];
        $player = array(
            'uniacid' => $_W['uniacid'],
            'openid' => $_W['openid'],
            'nickname' => $fansInfo['nickname'],
            'headimgurl' => $headImageUrl,
            'uid' => $fansInfo['uid'],
            'play_times' => 0,
            'total_score' => 0,
            'create_time' => time(),
            'update_time' => time()
        );
        $player = $this->insertData($player);
        $player['fans_info'] = $fansInfo;
        return $player;
    }

    public function selectPlayersByTeamId($teamId){
        $this->prepareService(array("playerTeamService"));
        $playersIds = $this->playerTeamService->getPlayerIdsByTeamId($teamId);
        $playerList = $this->selectByIds($playersIds);
        return $playerList;
    }



    public function selectPlayerByOpenid($openid){
        global $_W;
        $player = $this->selectOne("AND uniacid={$_W['uniacid']} AND openid='{$openid}'");
        if(empty($player)){
            throw new Exception("不存在该玩家信息",5040);
        }
        return $player;
    }

    public function checkPlayerRegister(){
        global $_W;
        try{
            $player = $this->selectPlayerByOpenid($_W['openid']);
            $fansInfo = $this->flashUserService->authFansInfo($player['openid']);
            $player['fans_info'] = $fansInfo;
            return $player;
        }catch (Exception $e){
            return $this->initPlayerInfo();
        }
    }

    public function chargeHelpScore($playerId,$helperId,$score){
        $player = $this->selectById($playerId);
        $this->updateColumn("total_score",$player['total_score'] + $score,$playerId);
        if($score > 0){
            $this->flashUserService->addUserScore($score,$player['openid'],"好友帮助答题积分奖励");
        }else{//帮助答题就不减分
            //$this->flashUserService->reduceUserScore($score,$player['openid'],"好友帮助答题错误惩罚");
        }
    }

    public function chargeScore($playerId,$score){
        $player = $this->selectById($playerId);
        $this->updateColumn("total_score",$player['total_score'] + $score,$player['id']);
        $scoreLog = "";
        if($score > 0){
            $scoreLog = "答题积分奖励";
        }else{
            $scoreLog = "答题错误惩罚";
        }
        $this->flashUserService->updateUserScore($score,$player['openid'],$scoreLog);
    }

    /**
     * 删除玩家 会级联删除该玩家所有信息
     * @param $playerId
     * @throws Exception
     */
    public function deletePlayerById($playerId){
        $this->prepareService(array("recordService"));
        $this->deleteById($playerId);
        $this->recordService->deleteAllRecordByPlayerId($playerId);
    }
}