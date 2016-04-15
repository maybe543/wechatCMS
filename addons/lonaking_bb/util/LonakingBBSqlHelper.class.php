<?php

/**
 * 数据库辅助类
 * User: leon
 * Date: 8/25/15
 * Time: 10:01 AM
 */
class LonakingBBSqlHelper
{
    static $table = array(
        'relation' => array(
            'name' => 'lonaking_bb_relation',
            'columns' => 'id,uniacid,openid,openid_o,create_time,update_time,expire_time'
        ),
        'tag_config' => array(
            'name' => 'lonaking_bb_tag_config',
            'columns' => 'id,uniacid,tag,color,create_time,update_time'
        ),
        'tags' => array(
            'name' => 'lonaking_bb_tags',
            'columns' => 'id,uniacid,fanid,openid,value,buzy,create_time,update_time'
        ),
    );
}