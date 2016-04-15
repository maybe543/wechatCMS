<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/27
 * Time: 下午9:54
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
require_once dirname(__FILE__)."/../activity_question/SqActivityQuestionService.php";
require_once dirname(__FILE__)."/../activity/SqActivityService.php";
class SqQuestionService extends FlashCommonService
{

    private $activityQuestionService;
    private $activityService;

    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";
        $this->table_name = 'lonaking_super_question_question';
        $this->columns = 'id,uniacid,question_num,title,pic,bg_pic,option_a,option_b,option_c,option_d,option_e,right_answer,score,de_score,ad_id,class_id,create_time,update_time';
    }

    /**
     * 准备service
     * @param array $serviceArray
     */
    private function prepareService(array $serviceArray){
        /**
        if(in_array("playerService",$serviceArray)){
            $this->playerService = new SqPlayerService();
        }*/
        if(in_array("activityService",$serviceArray)){
            $this->activityService = new SqActivityService();
        }
        if(in_array("activityQuestionService",$serviceArray)){
            $this->activityQuestionService = new SqActivityQuestionService();
        }
    }

    /**
     * 删除一个问题，会级联删除掉问题、活动关联记录,使用该问题的活动问题总数会减去1
     * @param $questionId
     * @throws Exception
     */
    public function deleteQuestionById($questionId){
        $this->prepareService(array("activityService","activityQuestionService"));
        $question = $this->selectById($questionId);
        $activityIdArray = $this->activityQuestionService->deleteAllRelationByQuestionId($questionId);
        $this->activityService->questionDeleteToUpdateActivityQuestionCount($activityIdArray);
        $this->deleteById($questionId);
    }


    /**
     * 删除广告，级联将广告id为即将删除的id的所有的问题广告设置为空
     * @param $adId
     * @throws Exception
     */
    public function updateAdIdEmptyByAdId($adId){
        $this->updateColumnByWhere("ad_id",null,"AND ad_id={$adId}");
    }

    /**
     * 获取一批问题总积分
     * @param $questionIds
     * @return int
     * @throws Exception
     */
    public function getTotalScoreByQuestionIds($questionIds){
        $questions = $this->selectByIds($questionIds);
        $totalScore = 0;
        if(sizeof($questions) > 0){
            foreach($questions as $q){
                $totalScore = $totalScore + $q['score'];
            }
        }
        return $totalScore;
    }
}