<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/10/15
 * Time: 下午2:43
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
class SqActivityQuestionService extends FlashCommonService
{
    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";
        $this->table_name = 'lonaking_super_question_activity_question';
        $this->columns = 'id,uniacid,activity_id,question_id,question_score';
    }

    /**
     * 取出一个活动的所有问题id列表
     * @param $activityId
     * @return array
     * @throws Exception
     */
    public function getAllQuestionIdsByActivityId($activityId){
        $allRelation = $this->selectAll("AND activity_id={$activityId}");
        if(empty($allRelation) || sizeof($allRelation) == 0){
            throw new Exception("该活动没有绑定问题");
        }
        $questionIds = array();
        foreach($allRelation as $r){
            $questionIds[] = $r['question_id'];
        }
        return $questionIds;
    }

    /**
     * 根据问题删除所有活动、问题绑定记录
     * @param $questionId
     * @return array 返回被删除的活动id列表
     */
    public function deleteAllRelationByQuestionId($questionId){
        global $_W;
        $uniacid = $_W['uniacid'];
        $relationArray = $this->selectAll("AND question_id={$questionId}");
        $activityIdArr = array();
        foreach($relationArray as $r){
            $activityIdArr[] = $r['activity_id'];
        }
        pdo_delete($this->table_name,array('uniacid'=>$uniacid,'question_id'=>$questionId));
        return $activityIdArr;
    }

    /**
     * 根据活动id删除所有活动、问题绑定记录
     * @param $activityId
     * @return array 返回被删除的问题id列表
     */
    public function deleteAllRelationByActivityId($activityId){
        global $_W;
        $uniacid = $_W['uniacid'];
        $relationArray = $this->selectAll("AND activity_id={$activityId}");
        $questionIdArr = array();
        foreach($relationArray as $r){
            $questionIdArr[] = $r['question_id'];
        }
        pdo_delete($this->table_name,array('uniacid'=>$uniacid,'activity_id'=>$activityId));
        return $questionIdArr;
    }
}