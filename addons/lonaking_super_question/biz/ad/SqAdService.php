<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/10/17
 * Time: 上午9:03
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
require_once dirname(__FILE__)."/../question/SqQuestionService.php";
class SqAdService extends FlashCommonService
{

    private $questionService;
    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";
        $this->table_name = 'lonaking_super_question_ad';
        $this->columns = 'id,uniacid,title,image,url,type,delay,create_time,update_time';
    }
    /**
     * 准备service
     * @param unknown $serviceArray
     */
    private function prepareService($serviceArray){
//         if(in_array("recordService",$serviceArray)){
//             $this->recordService = new SqRecordService();
//         }
//         if(in_array("activityQuestionService",$serviceArray)){
//             $this->activityQuestionService = new SqActivityQuestionService();
//         }
        if(in_array("questionService", $serviceArray)){
            $this->questionService = new SqQuestionService();
        }
//         if(in_array("adService", $serviceArray)){
//             $this->adService = new SqAdService();
//         }
    }
    /**
     * 删除一个广告，会级联删除所有与该广告建立关联的活动、问题等
     * @param $adId
     */
    public function deleteAdById($adId){
        $this->prepareService("questionService");
        $this->deleteById($adId);
        $this->questionService->updateAdIdEmptyByAdId($adId);
    }
}