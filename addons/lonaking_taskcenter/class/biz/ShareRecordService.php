<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/29
 * Time: 下午5:22
 */
require_once 'CommonService.php';
class ShareRecordService extends CommonService
{

    /**
     * ShareRecordService constructor.
     */
    public function __construct()
    {
        $this->table_name = 'lonaking_supertask_share_record';
        $this->columns = 'id,uniacid,openid,user_id,task_id,share_times,share_score,createtime,updatetime';
    }

    /**
     * 初始化一条记录
     * @param $user
     * @param $task
     */
    public function initOneRecord($user,$task){
        global $_W;
        $uniacid = $_W['uniacid'];
        $record = array(
            'uniacid' => $uniacid,
            'openid' => $user['openid'],
            'user_id' => $user['id'],
            'task_id' => $task['id'],
            'share_times' => 0,
            'share_score' => $task['share_score'],
            'createtime' => time(),
            'updatetime' => time()
        );
        return $this->insertData($record);
    }

    /**
     * 更新某个推广人的某个任务的分享记录，如果不存在这条记录，则生成一条出来
     * @param $user
     * @param $task
     * @return bool|null
     * @throws Exception
     */
    public function updateUserTaskShareRecord($user, $task){
        if(!is_array($user) || !is_array($task)){
            throw new Exception('参数类型错误',400);
        }
        $record = $this->fetchUserTaskShareRecord($user,$task);
        if($task['share_record_charge_limit'] > $record['share_times']){
            $record['share_score'] = $record['share_score'] + $task['share_score'];
        }
        $record['share_times'] = $record['share_times'] + 1;
        $this->updateData($record);
        return $record;
    }

    /**
     * 检测是否在奖励范围内 返回true 则需要奖励  返回false则不需要奖励
     * @param $user
     * @param $task
     * @return bool
     * @throws Exception
     */
    public function checkSharedInLimit($user, $task){
        if(!is_array($user) || !is_array($task)){
            throw new Exception('参数类型错误',400);
        }
        $record = $this->fetchUserTaskShareRecord($user,$task);
        if($task['share_record_charge_limit'] > $record['share_times']){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取用户的分享记录
     * @param $user
     * @param $task
     * @return bool|null
     * @throws Exception
     */
    public function fetchUserTaskShareRecord($user,$task){
        global $_W;
        $uniacid = $_W['uniacid'];
        if(!is_array($user) || !is_array($task)){
            throw new Exception('参数类型错误',400);
        }
        try{
            $record = $this->selectOne("AND uniacid='{$uniacid}' AND openid='{$user['openid']}' AND task_id='{$task['id']}'");
            return $record;
        }catch (Exception $e){
            return $this->initOneRecord($user,$task);
        }

    }
}