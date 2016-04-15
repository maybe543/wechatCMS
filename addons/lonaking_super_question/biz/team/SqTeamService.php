<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/10/25
 * Time: 上午1:26
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
class SqTeamService extends FlashCommonService
{

    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";
        $this->table_name = 'lonaking_super_question_team';
        $this->columns = 'id,uniacid,name,member_count,logo,captain_id,activity_times,score,question_count,start_time,create_time,update_time';
    }
}