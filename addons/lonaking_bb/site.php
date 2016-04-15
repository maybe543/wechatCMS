<?php

defined('IN_IA') or exit('Access Denied');
require 'util/LonakingBBSqlHelper.class.php';

class Lonaking_bbModuleSite extends WeModuleSite
{

    private $table = array();
    private $web_config = array();

    /* 初始化一下table */
    function __construct()
    {
        $this->table = LonakingBBSqlHelper::$table;
    }

    /**
     * 准备参数
     */
    private function prepare_web_config(){
        global $_W, $_GPC;
        $this->web_config = $this->module['config'];
    }

    /**
     * 返回需要的一些url 所有的url都在这里，为了方便
     * @return array
     */
    private function prepare_urls(){
        return array(
            'pipei' => $this->createMobileUrl('call'),
            'add_tag' => $this->createMobileUrl('addTag'),
            'remove_tag' => $this->createMobileUrl('removeTag'),
            'hang_up' => $this->createMobileUrl('hangUp'),
            'follow_url' => $this->web_config['follow_url'],
        );
    }
    public function doWebUserManage()
    {
        // 这个操作被定义用来呈现 管理中心导航菜单
    }

    public function doWebRelationManage()
    {
        // 这个操作被定义用来呈现 管理中心导航菜单
    }

    public function doWebTagManage()
    {
        global $_W, $_GPC;
        $data = $this->select_all_tag_config(false);
        include $this->template('tag_manage');
    }

    public function doWebTagEdit(){
        global $_GPC;
        checkaccount();//
        $id = $_GPC['id'];
        if (!empty($_GPC['submit'])) {//提交表单
            $tag = $_GPC['tag'];
            $tag = $this->save_or_update_tag_config($tag);
            if ($tag) {
                return message("信息保存成功", "", "success");
            } else {
                return message("信息保存失败", "", "error");
            }
        }else{
            $tag = null;
            $option = "添加标签";
            if(!is_null($id)){
                $tag = $this->select_tag_config_by_id($id);
                $option = "修改标签";
            }
            load()->func('tpl');
            include $this->template('tag_edit');
        }
    }

    /**
     * 删除tag
     */
    public function doWebDeleteTag(){
        global $_GPC, $_W;
        checkaccount();
        $id = $_GPC['id'];
        try{
            $this->delete_tag_config($id);
            return $this->return_json(200,'删除成功',null);
        }catch (Exception $e){
            return $this->return_json($e->getCode(),$e->getMessage(),null);
        }
    }
    /**
     * 添加标签
     */
    public function doMobileAddTag()
    {
        global $_W, $_GPC;
        $tag = $_GPC['tag'];
        try {
            $insert = array(
                'value' => $tag,
            );
            $insert_tag = $this->add_tag($insert);
            return $this->return_json(200, '添加成功', $insert_tag);
        }catch (Exception $e){
            return $this->return_json($e->getCode(),$e->getMessage(),null);
        }
    }
    /**
     * 删除
     */
    public function doMobileRemoveTag(){
        global $_W, $_GPC;
        $tag = $_GPC['tag'];
        try{
            //查找tag
            $exist_tag = $this->select_tag_by_value_and_openid($tag);
            //删除tag
            $this->delete_tag_by_id($exist_tag['id']);
            return $this->return_json(200,'删除成功',null);
        }catch (Exception $e){
            return $this->return_json($e->getCode(),$e->getMessage(),null);
        }
    }
    
    /**
     * 关系建立页面 这个页面添加标签之类的
     */
    public function doMobileRelation(){
        global $_W, $_GPC;
        $this->prepare_web_config();
        //查询所有的标签
        $tags = null;
        $relation = null;
        $relation_user = null;
        $fans_info = mc_fansinfo($_W['openid']);
        $follow_status = 1;
        try{
            $this->check_follow();
        }catch (Exception $e){
            $follow_status = 0;
        }
        try{
            $tags = $this->fetch_tags_group();
            $relation = $this->fetch_relation_openid();
            $relation_user = $this->fetch_user_info_by_openid($relation['relation_openid']);
            $over_time = ceil(($relation['expire_time'] - $relation['update_time'])/60);
            $relation_user['over_minute'] = $over_time;
            $relation_user['address'] = $relation_user['tag']['province'].$relation_user['tag']['city'].'['.$relation_user['tag']['country'].']';
            if(strpos($relation_user['address'],'[]')){
                $relation_user['address'] == '火星';
            }
            //查询当前用户是否有匹配的对象
        }catch (Exception $e){

        }
        //准备url
        $url = $this->prepare_urls();
        $html = array(
            'url' => $url,
            'title' => $this->web_config['title'],
            'tags' => $tags,
            'share_title' => $this->web_config['title'],
            'share_url' => $_W['siteroot'].'app'.substr($this->createMobileUrl('relation',array('share_id'=>$_W['openid'])),1),
            'share_content' => $this->web_config['share_content'],
            'share_logo' => $_W['attachurl'].$this->web_config['share_logo'],
            'jsconfig' => $_W['account']['jssdkconfig'],
            'relation' => $relation,
            'relation_user' => $relation_user,
            'openid'=> $_W['openid'],
            'follow_status' => $follow_status
        );
        include $this->template('relation');
    }
    
    /**
     * 获取tags并分组
     * @param unknown $uniacid
     */
    private function fetch_tags_group(){
        global $_W, $_GPC;
        //取出当前用户的tag
        $user_tags = array();
        try {
            $user_tags = $this->select_user_tags_by_openid();
        }catch (Exception $e){

        }
        //取出系统配置的所有tag
        $tags = $this->select_all_tag_config(false);
        //数据处理
        $i = 0;
        $group = 0;
        $new_tags = array();
        foreach ($tags as $tag){
            foreach ($user_tags as $user_tag){
                if ($tag['tag'] == $user_tag['value']){
                    $tag['selected'] = true;
                }
            }
            if($i <= 3){
                $new_tags[$group][$i] = $tag;
                $i++;
            }else{
                $i = 0;
                $group = $group + 1;
                $new_tags[$group][$i] = $tag;
                $i++;
            }
        }
        return $new_tags;
    }
    /**
     * 链接起来两个人
     */
    public function doMobileCall()
    {
        global $_W, $_GPC;
        $this->prepare_web_config();
        $uniacid = $_W['uniaccount']['uniacid'];
        $openid = $_W['openid'];
        try{
            $this->check_follow();
        }catch (Exception $e){
            return $this->return_json($e->getCode(),$e->getMessage(),null);
        }
        try{
            //超时会抛出异常 当不存在也会抛出异常
            $relation = $this->fetch_relation_openid();
            if(!empty($this->web_config['pipei_success_me'])){
                $this->send_text_message($openid, $this->web_config['pipei_success_me']);
            }
            $to = $this->fetch_user_info_by_openid($relation['relation_openid']);
            $over_time = ceil(($relation['expire_time'] - $relation['update_time'])/60);
            $to['over_minute'] = $over_time;
            return $this->return_json(200,'匹配成功,请关闭此页面，发送消息给公众平台即可与对方聊天',$to);
        }catch (Exception $e){
            $relation_user = null;
            try{
                $relation_user = $this->fetch_one_relation();
            }catch (Exception $e){
                $this->log($e->getMessage());
                return $this->return_json($e->getCode(),$e->getMessage(),null);
            }
            $to = $this->fetch_user_info_by_openid($relation_user['openid']);
            try{
                pdo_begin();
                $relation = $this->create_relation($relation_user['openid']);
                // 提醒对方用户，已经被匹配了
                $this->send_text_message($to['openid'], $this->web_config['pipei_success']);
                //将双方的状态改为忙碌
                $this->check_buzy_status($openid);
                $this->check_buzy_status($to['openid']);
                if(!empty($this->web_config['pipei_success_me'])){
                    $this->send_text_message($openid, $this->web_config['pipei_success_me']);
                }
                pdo_commit();
                $to['over_minute'] = $this->web_config['chat_limit_minute'];
                return $this->return_json(200,'匹配成功,请关闭此页面，发送消息给公众平台即可与对方聊天',$to);
            }catch (Exception $e){
                $this->log('匹配失败，对方已经被别人匹配到');
                pdo_rollback();
                return $this->return_json($e->getCode(),$e->getMessage(),null);
            }
        }
    }

    /**
     * 根据用户的标签来获取与之匹配的一个用户
     */
    private function fetch_one_relation(){
        global $_W, $_GPC;
        $uniacid = $_W['uniaccount']['uniacid'];
        $openid = $_W['openid'];
        //取出该用户所有的tag
        try{
            $tags = $this->select_user_tags_by_openid();
            $where = "";
            for ($i = 0;$i < sizeof($tags);$i++){
                if($i == 0){
                    $where = $where."( value='{$tags[$i]['value']}'";
                }else{
                    $where = $where." OR value='{$tags[$i]['value']}'";
                }
            }
            $where = $where.")";
            $last_update_time = time() - 6500;
            $relation_user_list = pdo_fetchall("select * from ". tablename($this->table['tags']['name']) ." where uniacid={$uniacid} AND buzy = 0 AND update_time>{$last_update_time} AND openid != '{$openid}' AND {$where}");
            //处理这些数据,这些数据不能与正在聊天的人重复 也就是剔除正在聊天的人

            if(empty($relation_user_list)){
                throw new Exception('没有找到与您匹配的用户，您可以尝试切换标签',4601);
            }else{
                //随机取出一个
                $index = array_rand($relation_user_list);
                return $relation_user_list[$index];
            }
        }catch (Exception $e){
            throw new Exception($e->getMessage(),4602);
        }
    }
    
    /**
     * 挂断 删除两个人之间的关联
     */
    public function doMobileHangUp(){
        global $_W, $_GPC;
        $this->prepare_web_config();
        $uniacid = $_W['uniaccount']['uniacid'];
        $openid = $_W['openid'];
        $relation = $this->fetch_relation();
        if($relation){
            $to_openid = $relation['openid'] == $openid ? $relation['openid_o'] : $relation['openid'];
            pdo_delete($this->table['relation']['name'],array(
                'id' => $relation['id']
            ));
            // 提醒对方用户，已经被挂断
            $this->send_text_message($to_openid, $this->web_config['hang_up']);
            $this->send_text_message($openid, $this->web_config['hang_up_me']);
            $this->check_buzy_status($openid,0);
            $this->check_buzy_status($to_openid,0);
        }else{
            $this->send_text_message($openid, $this->web_config['hang_up_me']);
        }
        return $this->return_json(200,'您已经成功挂断与对方的聊天',null);
    }

    /**
     * 发送消息
     *
     * @param unknown $send            
     */
    private function send_text_message($to_user_openid, $content)
    {
        global $_W;
        $send = array(
            'touser' => $to_user_openid,
            'msgtype' => 'text',
            'text' => array(
                'content' => urlencode($content)
            )
        );
        load()->classs('weixin.account');
        $account = WeiXinAccount::create($_W['uniaccount']['uniacid']);
        return $account->sendCustomNotice($send);
    }
    /**
     * 返回json给前端
     * @param unknown $status
     * @param unknown $message
     * @param unknown $data
     */
    private function return_json($status = 200,$message = 'success',$data = null){
        exit(json_encode(
            array(
                'status' => $status,
                'message' => $message,
                'data' => $data
            )
        )
        );
    }

    /**
     * 日志操作函数
     * @param $log_content
     */
    private function log($log_content){
        load()->func('logging');
        logging_run($log_content,'normal','lonaking_bb',true);
    }

    //////////////////////////TAG_CONFIG//////////////////////////////////////
    /**
     * 根据id查找tag
     * @param $id
     * @return bool
     * @throws Exception
     */
    private function select_tag_config_by_id($id){
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $tag = pdo_fetch("SELECT ". $this->table['tag_config']['columns'] ." FROM " . tablename($this->table['tag_config']['name']) . " WHERE id=:id AND uniacid=:uniacid",array(":id"=>$id,'uniacid'=>$uniacid));
        if(empty($tag)){
            throw new Exception("找不到id为".$id."到tag",4404);
        }
        return $tag;
    }

    /**
     * @param $pagination 是否分页 默认分页
     */
    private function select_all_tag_config($pagination = true){
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $page = null;
        if($pagination){

        }else{
            $page = pdo_fetchall("select ". $this->table['tag_config']['columns'] ." from ". tablename($this->table['tag_config']['name']) ." where uniacid=:uniacid",array(
                ':uniacid' => $uniacid
            ));
        }
        return $page;
    }
    /**
     * 添加一个tag
     * @param $tag
     */
    private function save_or_update_tag_config($tag){
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $new_tag = array(
            'uniacid' => $uniacid,
            'tag' => $tag['tag'],
            'color' => empty($tag['color'] ) ? 'color-pink' : $tag['color'],
            'create_time' => empty($tag['create_time']) ? time() : $tag['create_time'],
            'update_time' => time(),
        );
        if(empty($tag['id'])){
            //insert
            pdo_insert($this->table['tag_config']['name'],$new_tag);
            $new_tag['id'] = pdo_insertid();
        }else{
            //update
            $new_tag['id'] = $tag['id'];
            pdo_update($this->table['tag_config']['name'],$new_tag,array('id'=> $new_tag['id']));
        }
        return $new_tag;
    }

    /**
     * 删除一个tag
     * @param $id
     * @throws Exception 当id不存在的时候抛出此异常 4404
     */
    private function delete_tag_config($id){
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        if(empty($id)){
            throw new Exception('非法操作，id不能为空',4404);
        }
        //检查tag
        try{
            $tag = $this->select_tag_config_by_id($id);
        }catch (Exception $e){
            throw new Exception($e->getMessage(),4404);
        }
        $flag = pdo_delete($this->table['tag_config']['name'],array(
            'id' => $id
        ));
        if(!$flag){
            throw new Exception('删除失败,可能id为'.$id.'的标签不存在',4404);
        }
    }

    //////////////////////////TAGS//////////////////////////////////////

    /**
     * 添加标签
     * @param $tag
     * @throws Exception 当已经添加过此标签的时候抛出此异常
     */
    private function add_tag($tag,$before_check_exists = true){
        global $_W, $_GPC;
        if(empty($tag['value'])){
            throw new Exception('标签名称不能为空',5410);
        }
        $openid = empty($tag['openid']) ? $_W['openid'] : $tag['openid'];
        try{
            if($before_check_exists) {
                $this->check_exist_tag_by_value_and_openid($tag['value'], $openid);
            }
            $new_tag = array(
                'uniacid' => $_W['uniacid'],
                'fanid' => empty($tag['fanid']) ? $_W['member']['uid'] : $tag['fanid'],
                'openid' => $openid,
                'value' => $tag['value'],
                'create_time' => time(),
                'buzy' => 0,//默认为0
                'update_time' => time(),
            );
            pdo_insert($this->table['tags']['name'],$new_tag);
            $new_tag['id'] = pdo_insertid();
            return $new_tag;
        }catch (Exception $e){
            throw new Exception('您已经添加了标签['.$tag.']',5411);
        }
    }

    /**
     * 根据openid查询用户的所有标签
     * @param null $openid 为空则取出当前用户的openid
     * @throws Exception 当用户无标签的时候抛出此异常
     * @return array 返回用户的所有标签
     */
    private function select_user_tags_by_openid($openid = null){
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        if(empty($openid)){
            $openid = $_W['openid'];
        }
        $user_tags = pdo_fetchall("select ". $this->table['tags']['columns'] ." from ". tablename($this->table['tags']['name']) ." where uniacid=:uniacid and openid=:openid",array(
            ':uniacid' => $uniacid,
            ':openid' => $openid,
        ));
        if(empty($user_tags)){
            $this->log('当前用户没有添加任何标签,openid='.$openid);
            throw new Exception('您未添加任何标签',5404);
        }
        return $user_tags;

    }


    private function select_tag_by_value($tag_value){
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $exist_tag = pdo_fetch("select * from ". tablename($this->table['tags']['name']) ." where uniacid=:uniacid and value=:value",array(
            ':uniacid' => $uniacid,
            ':value' => $tag_value
        ));
        if(empty($exist_tag)){
            throw new Exception('找不到名为'.$tag_value.'的标签',5407);
        }
        return $exist_tag;
    }

    /**
     *
     * @param $tag_value
     * @param null $openid
     * @return bool
     * @throws Exception
     */
    private function select_tag_by_value_and_openid($tag_value, $openid = null){
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        if(empty($openid)){
            $openid = $_W['openid'];
        }
        $exist_tag = pdo_fetch("select * from ". tablename($this->table['tags']['name']) ." where uniacid=:uniacid and openid=:openid and value=:value",array(
            ':uniacid' => $uniacid,
            ':openid' => $openid,
            ':value' => $tag_value
        ));
        if(empty($exist_tag)){
            throw new Exception('您还没有添加['.$tag_value.']标签',5405);
        }
        return $exist_tag;
    }

    /**
     * 检测用户是否有某一个标签 当存在这个标签的时候抛出异常
     * @param $tag_value
     * @param null $openid
     */
    private function check_exist_tag_by_value_and_openid($tag_value,$openid = null){
        try{
            $tag = $this->select_tag_by_value_and_openid($tag_value,$openid);
            throw new Exception('用户已经存在了名为'.$tag['value'].'的标签',5406);
        }catch (Exception $e){

        }
    }

    /**
     * 删除一个标签
     * @param $id
     * @throws Exception 删除失败抛出此异常
     */
    private function delete_tag_by_id($id){
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $i = pdo_delete($this->table['tags']['name'],array(
            'id' => $id,
            'uniacid' => $uniacid
        ));
        if(!$i){
            throw new Exception('删除失败，或许是您没有添加此标签',5407);
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

    /**
     * 更新用户的所有标签的更新时间
     * @param null $openid
     */
    private function update_tag_update_time($openid = null){
        global $_W, $_GPC;
        $uniacid = $_W['uniaccount']['uniacid'];
        $openid = empty($openid) ? $_W['openid'] : $openid;
        pdo_update($this->table['tags']['name'],array( 'update_time' => time() ),array( 'uniacid' => $uniacid, 'openid' => $openid));
    }

    //////////////////////////////////Relation//////////////////////////////////////////

    /**
     * @param $openid 一个openid
     * @return bool
     * @throws Exception 当用户没有聊天对象的时候抛出此异常
     */
    private function fetch_relation($openid = null){
        global $_W;
        $openid = empty($openid) ? $_W['openid'] : $openid;
        $uniacid = $_W['uniacid'];
        $relation = pdo_fetch("select ". $this->table['relation']['columns'] ." from ".tablename($this->table['relation']['name'])." where uniacid = :uniacid and openid = :openid or openid_o = :openid_o ", array(
            ':uniacid' => $uniacid,
            ':openid' => $openid,
            ':openid_o' => $openid
        ));
        if(empty($relation)){
            throw new Exception("您还没有聊天对象,请点击配对",6401);
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
            //判断过期时间
            $this->check_chat_minute_limit($relation);
            $to_user_openid = $relation['openid'] == $openid ? $relation['openid_o'] : $relation['openid'];
            $relation['relation_openid'] = $to_user_openid;
            return $relation;
        }catch (Exception $e){
            throw new Exception($e->getMessage(),$e->getCode());
        }
    }
    /**
     * 插入一条关系纪录 默认3600s
     * @param $openid_o
     * @param null $openid
     * @return array 返回新的 relation
     */
    private function create_relation($openid_o, $openid = null){
        global $_W, $_GPC;
        $this->prepare_web_config();
        $chat_second_limit = empty($this->web_config['chat_limit_minute']) ? 3600000 : $this->web_config['chat_limit_minute'] * 60;
        $uniacid = $_W['uniacid'];
        $openid = empty($openid) ? $_W['openid'] : $openid;
        $relation = array(
            'uniacid' => $uniacid,
            'openid' => $openid,
            'openid_o' => $openid_o,
            'create_time' => time(),
            'update_time' => time(),
            'expire_time' => time() + $chat_second_limit//每个人仅可以聊天3600秒
        );
        //先确定对方没有聊天
        try{
            $this->fetch_relation($openid_o);
            throw new Exception('好不容易匹配到了一个对象，他却被别人抢跑了,点击确定重新匹配一下可好？',6400);
        }catch (Exception $e){
            pdo_insert('lonaking_bb_relation', $relation);
            $relation['id'] = pdo_insertid();
            return $relation;
        }
    }

    /**
     * 检测用户聊天是否此超时
     * @param $relation
     * @throws Exception 当聊天超时的时候会抛出此异常
     */
    private function check_chat_minute_limit($relation){
        $this->prepare_web_config();
        $notice_message = empty($this->config['chat_timeout_message']) ? '每个人限制聊天1小时,您可以重新匹配哦' : $this->config['chat_timeout_message'];
        if($relation['expire_time'] < time()){
            $this->delete_relation($relation['openid']);
            $this->check_buzy_status($relation['openid'],0);
            $this->check_buzy_status($relation['openid_o'],0);
            throw new Exception($notice_message,6401);
        }

    }

    /**
     * 删除所有聊天中的关系
     * @param $openid
     */
    private function delete_relation($openid) {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        pdo_query("DELETE FROM ". tablename($this->table['relation']['name']) ." WHERE uniacid =:uniacid AND ( openid =:openid OR openid_o =:openid_o)", array(
            ':uniacid' => $uniacid,
            ':openid' => $openid,
            ':openid_o' => $openid
        ));
    }

    //////////////////////////////others//////////////////////////////////////////

    /**
     * 根据openid获取用户信息
     * @param $openid
     * @return bool
     */
    private function fetch_user_info_by_openid($openid){
        load()->model('mc');
        $to = mc_fansinfo($openid);

        return $to;
    }

    /**
     * 检测用户是否关注
     * @param $openid
     * @throws Exception
     */
    private function check_follow($openid = null){
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $openid = $openid == null ? $_W['openid'] : $openid;
        if(empty($openid)){
            throw new Exception("您没有关注本微信平台,点击确认前往关注",4509);
        }
        $fans_info = mc_fansinfo($_W['openid']);
        if(empty($fans_info) || $fans_info['follow'] == 0){
            throw new Exception("您没有关注本微信平台,点击确认前往关注",4510);
        }
    }
}