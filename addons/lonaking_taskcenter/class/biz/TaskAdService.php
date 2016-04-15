<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/6
 * Time: 下午12:11
 */
require_once 'CommonService.php';
class TaskAdService extends CommonService
{

    /**
     * TaskAd constructor.
     */
    public function __construct()
    {
        $this->table_name = 'lonaking_supertask_task_ad';
        $this->columns = 'task_id,ad_id';
    }

    /**
     * 查询所有数据
     * @param string $where 查询条件，必须以AND开头 可以为空这个where
     * @return array 返回查询结果
     * @throws Exception 不存在的时候抛出此异常
     */
    public function selectAll($where = ''){
        $data_list = pdo_fetchall("SELECT ".$this->columns." FROM ".tablename($this->table_name)." WHERE 1=1 {$where}");
        if(empty($data_list)){
            throw new Exception("没有查到相关数据",405);
        }
        return $data_list;
    }

    /**
     * 更新一个task的广告
     * @param $task_id
     * @param null $new_ad_ids
     */
    public function updateTaskAd($task_id,$new_ad_ids=null){
        $this->deleteAdByTask($task_id);
        foreach($new_ad_ids as $ad_id){
            $array = array(
                'task_id' => $task_id,
                'ad_id' => $ad_id
            );
            $this->insertData($array);
        }
    }

    /**
     * 删除一个task的所有广告
     * @param $task_id
     */
    public function deleteAdByTask($task_id){
        pdo_query("DELETE FROM ".tablename($this->table_name)." WHERE task_id=:task_id",array(
            ':task_id' => $task_id
        ));
    }

    public function deleteByAdId($ad_id){
        pdo_query("DELETE FROM ".tablename($this->table_name)." WHERE ad_id=:ad_id",array(
            'ad_id' => $ad_id
        ));
    }
}