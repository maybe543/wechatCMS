<?php

require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
class LCActivityService extends FlashCommonService
{
    public function __construct()
    {
        $this->plugin_name = 'lonaking_activity';
        $this->table_name = 'lonaking_activity_activity';
        $this->columns = 'id,uniacid,name,admin_name,admin_pic,start,end,address,enroll_stop,enroll_count,enroll_limit,content,click,share,share_logo,share_title,share_description,create_time,update_time';
    }
}