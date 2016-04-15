<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/4
 * Time: 上午1:19
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
//require_once 'UserService.php';
class TcInviteService extends FlashCommonService
{

    /**
     * 构造
     */
    public function __construct()
    {
        $this->plugin_name = 'lonaking_taskcenter';
        $this->table_name = 'lonaking_supertask_invite';
        $this->columns = 'id,uniacid,fanid,openid,invite_id';
    }


    /**
     * 查询一条邀请纪录
     * @param null $openid
     * @param null $uniacid
     * @return bool
     * @throws Exception
     */
    public function selectInviteInfoByOpenid($openid=null,$uniacid=null){
        global $_W;
        $openid = is_null($openid) ? $_W['openid'] : $openid;
        $uniacid = is_null($uniacid) ? $_W['uniacid'] : $uniacid;
        $invite_info = pdo_fetch("SELECT ".$this->columns." FROM ".tablename($this->table_name)." WHERE uniacid =:uniacid AND openid=:openid",array(':uniacid'=>$uniacid, ':openid' => $openid));
        if(empty($invite_info) || is_null($invite_info)){
            throw new Exception("没有查询到邀请记录存在",8401);
        }
        return $invite_info;
    }
}