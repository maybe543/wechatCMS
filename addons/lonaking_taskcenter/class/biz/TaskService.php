<?php
/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/3
 * Time: 下午12:22
 * Exception 6开头
 */
require_once 'CommonService.php';
require_once 'TaskAdService.php';
require_once 'AdService.php';
class TaskService extends CommonService{

    private $adService;
    private $taskAdService;
    /**
     * TaskService constructor.
     */
    public function __construct()
    {
        $this->table_name = 'lonaking_supertask_task';
        $this->columns = 'id,uniacid,name,`desc`,`template`,status,click_score,follow_score,share_score,click_times,web_title,web_copyright,web_must_follow,web_follow_url,web_music,createtime,updatetime,share_logo,share_times,type,template_url,recommend,total_score,open_mode,share_record_charge_limit';
        $this->taskAdService = new TaskAdService();
        $this->adService = new AdService();
    }


    /**
     * 更新task的点击次数 ＋1
     * @param $task_id
     */
    public function addClickTimes($task_id){
        pdo_query("UPDATE ".tablename($this->table_name). " SET click_times = click_times+1 WHERE id = :id",array(':id'=>$task_id) );
    }

    /**
     *
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function selectTaskById($id){
        $task = $this->selectById($id);
        $ads = array();
        try{
            $temp_ads = $this->adService->selectAll();
            $task['ads'] = $temp_ads;
            $select_ads = $this->taskAdService->selectAll("AND task_id={$id}");
            //将所有的已经选择的广告id取出来
            $ad_ids = array();
            foreach($select_ads as $ad_task){
                $ad_ids[] = $ad_task['ad_id'];
            }
            foreach($temp_ads as $ad){
                if(in_array($ad['id'],$ad_ids)){
                    $ad['checked'] = true;
                }else{
                    $ad['checked'] = false;
                }
                $ads[] = $ad;
            }
        }catch (Exception $e){
            return $task;
        }
        $task['ads'] = $ads;
        return $task;
    }

    public function insertOrUpdate($param){
        if($param['id']){
            return $this->updateData($param);
        }else{
            return $this->insertData($param);
        }
    }
}