<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/27
 * Time: 下午9:54
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
require_once dirname(__FILE__) . '/../../../lonaking_flash/utils/FlashHelper.php';
require_once dirname(__FILE__) . '/../record/SqRecordService.php';
require_once dirname(__FILE__) . '/../activity_question/SqActivityQuestionService.php';
require_once dirname(__FILE__) . '/../question/SqQuestionService.php';
class SqActivityService extends FlashCommonService
{

    private $recordService;
    private $activityQuestionService;
    private $questionService;
    private $adService;

    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";//question_bg_pic,end_page_bg_pic
        $this->table_name = 'lonaking_super_question_activity';//activity_type 1:普通答题，单人模式  2:普通答题，求助模式  3 团队答题，现场模式
        $this->columns = 'id,uniacid,activity_num,name,activity_type,pic,start_time,end_time,play_times,play_limit,limit_type,help_limit,limit_seconds,virtual_times,share_times,question_count,random_count,score,copyright,rule,score_rule,analyse_message,ad_end_page,bg_pic,theme_pic,logo_pic,current,share_logo,create_time,update_time';
    }

    public function activitySave($activityDto){

    }
    /**
     * 获取档期哪的活动
     * @param $currentId
     * @throws Exception
     */
    public function checkCurrentActivity($currentId){
        $activity = $this->selectById($currentId);
        if($activity['current'] == false){
            // check the old activity gone
            $this->updateColumnByWhere('current',0,' AND current=true');
            // set new activity currently
            $this->updateColumn('current',1,$currentId);
        }
    }

    /**
     * 准备service
     * @param unknown $serviceArray
     */
    private function prepareService($serviceArray){
        if(in_array("recordService",$serviceArray)){
            $this->recordService = new SqRecordService();
        }
        if(in_array("activityQuestionService",$serviceArray)){
            $this->activityQuestionService = new SqActivityQuestionService();
        }
        if(in_array("questionService", $serviceArray)){
            $this->questionService = new SqQuestionService();
        }
        if(in_array("adService", $serviceArray)){
            $this->adService = new SqAdService();
        }
    }
    /**
     * 删除一个活动，在删除之前会检测该活动是否有人已经参加，如果没有则会删除
     * @param $activityId
     * @throws Exception 当有人参加此活动，并产生活动记录的时候，该活动无法删除，抛出此异常
     */
    public function deleteActivityById($activityId){
        $this->prepareService(array("recordService","activityQuestionService"));
        $activity = $this->selectById($activityId);
        if(empty($activity)){
            throw new Exception("活动不存在，无法删除",400);
        }
        $this->recordService->checkActivityIsInUse($activityId);
        $this->activityQuestionService->deleteAllRelationByActivityId($activityId);
        $this->deleteById($activityId);
    }

    /**
     * 问题删除的时候，级联修改与问题有关活动的问题总数
     * @param $activityIdArray
     */
    public function questionDeleteToUpdateActivityQuestionCount($activityIdArray){
        global $_W;
        if(empty($activityIdArray) || sizeof($activityIdArray) == 0){
            return ;
        }
        $inWhere = implode(",",$activityIdArray);
        $inWhere = "(".$inWhere.")";
        $sql = "UPDATE ".tablename($this->table_name)." SET question_count=question_count-1 WHERE uniacid={$_W['uniacid']} AND 1=1 AND id in {$inWhere}";
        pdo_query($sql);
    }

    /**
     * 获取活动信息详情 查询出来结果会包含活动、问题、广告等信息
     * @param null $activityId
     * @return bool
     * @throws Exception
     */
    public function getActivityAndRandomQuestionsById($activityId = null){
        $this->prepareService(array("questionService","activityQuestionService","adService"));
        global $_GPC, $_W;
        $where = "AND current=1";
        if(!is_null($activityId) && is_numeric($activityId)){
            $where = "AND id={$activityId}";
        }
        $activity = $this->selectOne($where);
        if(empty($activity)){
            throw new Exception("活动不存在，或未设置默认活动");
        }
        $activity_questions = $this->activityQuestionService->selectAll("AND activity_id={$activity['id']}");
        if(empty($activity_questions)){
            throw new Exception("该活动没有任何问题，无法开启", 400);
        }
        //取出所有问题的id 进行查询
        $questionIds = FlashHelper::fetchColumnArray($activity_questions,'question_id',true);
        //随机: 当用户设置了随机问题数量、并且随机数量小于总数的时候，取出随机问题
        if($activity['random_count'] > 0 && $activity['random_count'] < sizeof($questionIds)){
            $questionIndexIds = array_rand($questionIds,$activity['random_count']);
            $tmpQuestionIds = array();
            if(is_array($questionIndexIds)){
                foreach ($questionIndexIds as $index){
                    $tmpQuestionIds[] = $questionIds[$index];
                }
            }elseif (is_numeric($questionIndexIds)){
                $tmpQuestionIds[] = $questionIds[$questionIndexIds];
            }
            $questionIds = $tmpQuestionIds;
            
        }
        $questions = $this->questionService->selectByIds($questionIds);
        $ad_ids = FlashHelper::fetchColumnArray($questions,'ad_id',true);
        if(!empty($ad_ids)){
            $ad_list = $this->adService->selectByIds($ad_ids);
            $new_questions = array();
            foreach($questions as $q){
                foreach($ad_list as $ad){
                    if($q['ad_id'] == $ad['id']){
                        $q['ad'] = $ad;
                        break;
                    }
                }
                $new_questions[] = $q;
            }
            $questions = $new_questions;
        }
        $activity['questions'] = $questions;
        $activity['question_ids'] = implode(",", $questionIds);
        //初始化游戏记录
//         $record = $this->recordService->initRecordByOpenid($_W['openid'], $questionIds,$activity);
//         $activity['record'] = $record;
        return $activity;
    }
}