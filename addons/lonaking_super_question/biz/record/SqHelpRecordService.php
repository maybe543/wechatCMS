<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/10/18
 * Time: 下午3:09
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
class SqHelpRecordService extends FlashCommonService
{
    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";
        $this->table_name = 'lonaking_super_question_help_record';
        $this->columns = 'id,uniacid,type,openid,uid,`right`,`wrong`,right_ids,wrong_ids,help_score,question_id,activity_id,answer_seconds,create_time,update_time';
        //openid 作者  help_score:好友帮助得分  type:
    }

}