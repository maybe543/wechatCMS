<?php
/**
 * table 静态工具类
 * @author leon
 *
 */
class TableResource
{
    static $table = array(
        'setting'=>array(
            'name' => 'lonaking_supertask',
            'columns' => 'id,uniacid,setting'
        ),
        'config' => array(
            'name' => 'lonaking_supertask_config',
            'columns' => 'id,acid,uniacid,title,duty_content,gift_type,follow_score,unfollow_score,second_shareman_score,qrcode_type'
        ),
        'gift' => array(
            'name' => 'lonaking_supertask_gift',
            'columns' => 'id,uniacid,name,price,type,num,status,del,pic,mode,send_price,createtime,updatetime,mobile_fee_money,hongbao_money,ziling_address,ziling_mobile,description'
        ),
        'gift_order' => array(
            'name' => 'lonaking_supertask_gift_order',
            'columns' => 'id,uniacid,openid,order_num,gift,status,name,mobile,target,createtime,updatetime,pay_method,pay_status,trans_num,send_price'
        ),
        'user' => array(
            'name' => 'lonaking_supertask_user',
            'columns' => 'id,acid,uniacid,fanid,openid,pid,agree_duty,qrcode,qrcode_updatetime,scene_id,qrcid,name,follow_times,unfollow_times,second_shareman,score,share_score,createtime,updatetime,share_times'
        ),
        //TODO 这个该删除掉
        'share' => array(
            'name' => 'lonaking_supertask_user',
            'columns' => 'id,acid,uniacid,fanid,pid,agree_duty,qrcode,qrcode_updatetime,scene_id,qrcid,name,follow_times,unfollow_times,second_shareman,score,createtime,updatetime'
        ),
        //暂时没有用到
        'qrcode' => array(
            'name' =>'lonaking_supertask_qrcode',
            'columns' =>'scene_id,user_id,url,createtime,updatetime'
        ),
        'max_scene' => array(
            'name' => 'lonaking_supertask_max_scene',
            'columns' => 'uniacid,max_scene_id'
        ),
        'invite' => array(
            'name' => 'lonaking_supertask_invite',
            'columns' => "id,uniacid,fanid,openid,invite_id"
        ),
        'task' => array(
            'name' => 'lonaking_supertask_task',
            'columns' => 'id,uniacid,name,`desc`,`template`,status,click_score,follow_score,share_score,click_times,web_title,web_copyright,web_must_follow,web_follow_url,web_music,createtime,updatetime,share_logo,share_times,type,template_url,recommend,total_score,open_mode,share_record_charge_limit'
        ),
        'ad' => array(
            'name' => 'lonaking_supertask_ad',
            'columns' => 'id,uniacid,title,image,url,type,delay,createtime,updatetime'
        ),
        'task_ad' => array(
            'name' => 'lonaking_supertask_task_ad',
            'columns' => 'task_id,ad_id'
        ),
        'gift_admin' => array(
            'name' => 'lonaking_supertask_gift_admin',
            'columns' => 'id,uniacid,openid,gift_id'
        ),
        'tpl_config' => array(
            'name' => 'lonaking_supertask_tpl_template_config',
            'columns' => 'id,uniacid,get_notice,check_status_access_notice,check_status_refuse_notice,send_notice,invite_score_notice'
        ),
        'share_record' => array(
            'name' => 'lonaking_supertask_share_record',
            'columns' => 'id,uniacid,openid,user_id,task_id,share_times,share_score,createtime,updatetime'
        ),
    );
}
