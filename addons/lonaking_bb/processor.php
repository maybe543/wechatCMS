<?php

defined('IN_IA') or exit('Access Denied');
require 'util/LonakingBBSqlHelper.class.php';

class Lonaking_bbModuleProcessor extends WeModuleProcessor
{

    private $table = array();
    private $config = array();
    /* 初始化一下table */
    function __construct()
    {
        $this->table = LonakingBBSqlHelper::$table;
    }

    /**
     * 准备模块配置参数
     */
    private function prepare_module_config(){
        global $_W, $_GPC;
        $this->config = $this->module['config'];
    }
    public function respond()
    {
        global $_W;
        //$web_config = $this->module['config'];
        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];
        $content = $this->message['content'];
        $is_text = true;
        if($this->message['event'] == 'CLICK'){
            $is_text = false;
        }
        $this->update_tag_update_time($openid);
        if($this->message['type'] =='text' && $is_text){
            if (! $this->inContext){
                $this->beginContext(1800);
            }
            if ($content == '退出') {
                $this->endContext();
                $this->delete_relation();
                $this->endContext();
                return $this->respText('您已经退出');
            }
            try{
                //1. 寻找当前是否有聊天对象
                $relation = $this->fetch_relation_openid();
                //判断过期时间
                try{
                    $this->check_chat_minute_limit($relation);
                }catch (Exception $e){
                    $this->send_text_message($relation['relation_openid'],$e->getMessage());
                }
                $to_user_openid = $relation['relation_openid'];
                // 3. 发送客服消息
                $this->send_text_message($to_user_openid,$content);
                exit();
            }catch (Exception $e){
                return $this->respText($e->getMessage());
            }
        }
    }

    /**
     * @param $openid 一个openid
     * @return bool
     * @throws Exception 当用户没有聊天对象的时候抛出此异常
     */
    private function fetch_relation($openid = null){
        global $_W;
        $this->prepare_module_config();
        $openid = empty($openid) ? $_W['openid'] : $openid;
        $uniacid = $_W['uniacid'];
        $relation = pdo_fetch("select ". $this->table['relation']['columns'] ." from ".tablename($this->table['relation']['name'])." where uniacid = :uniacid and openid = :openid or openid_o = :openid_o ", array(
            ':uniacid' => $uniacid,
            ':openid' => $openid,
            ':openid_o' => $openid
        ));
        if(empty($relation)){
            throw new Exception($this->config['default_message'],6401);
        }
        return $relation;
    }
    /**
     * 获取当前用户的relation 其中正与他聊天的用户的openid为 $relation['relation_openid']
     * @param null $openid
     * @return bool
     * @throws Exception
     */
    private function fetch_relation_openid($openid = null)
    {
        global $_W;
        $openid = empty($openid) ? $_W['openid'] : $openid;
        try{
            $relation = $this->fetch_relation();

            $to_user_openid = $relation['openid'] == $openid ? $relation['openid_o'] : $relation['openid'];
            $relation['relation_openid'] = $to_user_openid;
            return $relation;
        }catch (Exception $e){
            throw new Exception($e->getMessage(),$e->getCode());
        }
    }

    /**
     * 挂断、结束聊天
     */
    private function delete_relation()
    {
        global $_W;
        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];
        pdo_query('delete from ims_lonaking_bb_relation where uniacid = :uniacid and ( openid = :openid or openid_o = :openid_o)', array(
            ':uniacid' => $uniacid,
            ':openid' => $openid,
            ':openid_o' => $openid
        ));
    }

    /**
     * 检测用户聊天是否此超时
     * @param $relation
     * @throws Exception 当聊天超时的时候会抛出此异常
     */
    private function check_chat_minute_limit($relation){
        $this->prepare_module_config();
        $notice_message = empty($this->config['chat_timeout_message']) ? '每个人限制聊天1小时,您可以重新匹配哦' : $this->config['chat_timeout_message'];
        if($relation['expire_time'] < time()){
            $this->delete_relation($relation['openid']);
            $this->check_buzy_status($relation['openid'],0);
            $this->check_buzy_status($relation['openid_o'],0);
            throw new Exception($notice_message,6401);
        }

    }

    /**
     * 切换用户忙碌状态
     * @param $openid
     * @param int $buzy
     */
    private function check_buzy_status($openid = null, $buzy = 1){
        global $_W, $_GPC;
        $uniacid = $_W['uniaccount']['uniacid'];
        $openid = empty($openid) ? $_W['openid'] : $openid;
        pdo_update($this->table['tags']['name'],array( 'buzy' => $buzy ),array( 'uniacid' => $uniacid, 'openid' => $openid));
    }
    private function update_tag_update_time($openid = null){
        global $_W, $_GPC;
        $uniacid = $_W['uniaccount']['uniacid'];
        $openid = empty($openid) ? $_W['openid'] : $openid;
        pdo_update($this->table['tags']['name'],array( 'update_time' => time() ),array( 'uniacid' => $uniacid, 'openid' => $openid));
    }
    /**
     * 发送消息
     * 
     * @param unknown $send            
     */
    private function send_text_message($to_user_openid,$content)
    {
        global $_W;
        $send = array(
            'msgtype' => 'text',
            'touser' => $to_user_openid,
            'text' => array(
                'content' => urlencode($content)
            )
        );
        load()->classs('weixin.account');
        $account = WeiXinAccount::create($_W['account']['acid']);
        return $account->sendCustomNotice($send);
    }
}
