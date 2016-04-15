<?php
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
class LCEnrollService extends FlashCommonService
{
    public function __construct()
    {
        $this->plugin_name = 'lonaking_activity';
        $this->table_name = 'lonaking_activity_enroll';
        $this->columns = 'id,uniacid,activity_id,order_num,openid,pic,uid,name,mobile,status,verificate_time,create_time,update_time';
    }


    public function selectByOrderNum($orderNum){
        $enrollRecord = $this->selectOne("AND order_num={$orderNum}");
        return $enrollRecord;
    }
}