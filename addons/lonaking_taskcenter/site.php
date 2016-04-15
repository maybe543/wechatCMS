<?php
/**
 * 猪八戒推广中心模块
 *
 * @author lonaking
 * @url http://bbs.we7.cc/thread-8992-1-1.html
 */
defined('IN_IA') or exit('Access Denied');
require_once 'utils/TableResource.class.php';
require_once 'class/task/TcTaskService.php';
require_once 'class/biz/UserService.php';
require_once 'class/gift/TcGiftService.php';
require_once 'class/order/TcGiftOrderService.php';
require_once 'class/ad/TcAdService.php';
require_once 'class/gift/TcGiftAdminService.php';
require_once 'class/tpl/TcTplConfigService.php';
require_once 'pay/WxHongBaoException.php';
require_once 'pay/WxHongBaoHelper.php';
require_once 'pay/CommonUtil.php';
require_once 'class/record/TcShareRecordService.php';
class lonaking_taskcenterModuleSite extends WeModuleSite
{

    private $userService;

    private $taskService;

    private $giftService;

    private $giftOrderService;

    private $adService;

    private $taskAdService;

    private $giftAdminService;

    private $tplConfigService;

    private $shareRecordService;

    private $urls;
    /**
     * lonaking_taskcenterModuleSite constructor.
     */
    public function __construct()
    {
        $this->userService = new UserService();
        $this->taskService = new TcTaskService();
        $this->giftService = new TcGiftService();
        $this->giftOrderService = new TcGiftOrderService();
        $this->giftAdminService = new TcGiftAdminService();
        $this->adService = new TcAdService();
        $this->taskAdService = new TcTaskAdService();
        $this->tplConfigService = new TcTplConfigService();
        $this->shareRecordService = new TcShareRecordService();
    }

    private function prepareUrls(){
        $this->urls = array(

        );
    }

    /**
     * check the qrcode is overtime
     * @param unknown $user
     * @return number
     */
    private function checkQrcode($user){
        global $_GPC, $_W;
        //check the qrcode's source people is overtime
        if(!empty($user['qrcode']) ){ // 公众号无权限生成二维码
            if(time() - $user['qrcode_updatetime'] > 600000){//过期
                //do qrcode
                $barcode = array(
                    'expire_seconds' => 604800, // 二维码的有效时间, 单位 秒.
                    'action_name' => 'QR_SCENE',
                    'action_info' => array(
                        'scene' => array(
                            'scene_id' => $user['scene_id']
                        )
                    )
                );
                load()->classs('weixin.account');
                $account = WeiXinAccount::create($_W['account']['acid']);
                $qrcode = $account->barCodeCreateDisposable($barcode);
                if($qrcode['errno'] != -1){//有权限生成二维码
                    $qrcode_url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($qrcode['ticket']);
                    $user['qrcode'] = $qrcode_url;
                    $user['qrcode_updatetime'] = time();
                    $user['updatetime'] = time();
                    pdo_update(TableResource::$table['user']['name'],$user,array('id'=>$user['id']));//更新二维码
                }else{
                    $user['qrcode'] = '';
                    $user['qrcode_updatetime'] = '';
                }
            }
        }
        return $user;
    }
    /**
     * 公用的路由 读取分享任务
     */
    public function doMobileShare(){
        global $_GPC, $_W;
        $is_share_man = $_GPC['is_share_man'];
        load()->model('mc');
        //mc_oauth_userinfo();
        $web_config = $this->module['config'];
        $task_id = $_GPC['task_id'];
        $share_uid = $_GPC['share_uid'];
        $open_mode = $_GPC['open_mode'];
        if(empty($task_id) || is_null($task_id)) return $this->return_json(400,'非法访问',null);//不能为空
        try{
            $this->safecheck();//不合格的时候会抛出异常
            $task = $this->taskService->selectTaskById($task_id);
            if(empty($task['template_url']) && empty($task['template'])){
                //无template 出错
                return $this->return_json(400,'无模板,请在开发者的帮助下设置',null);
            }
            //是否是第一次点击
            $firstclick = $this->is_firstclick();
            //更新点击次数
            $this->taskService->addClickTimes($task_id);
            //查询直接分享人
            try{
                pdo_begin();
                $user = $this->userService->selectUserById($share_uid);
                // 更新分享人积分
                if($firstclick && $_W['openid'] != $user['openid'] && ($task['total_score']-$task['click_score']) >=0 ){
                    //更新点击积分
                    $this->userService->updateUserScore($task['click_score'],$user['id'],$web_config);
                    $this->taskService->columnReduceCount('total_score',$task['click_score'],$task['id']);
                }
                //检测二维码
                $this->checkQrcode($user);
                $new_name = urldecode($_GPC['new_name']);//临时变量
                $html = array(
                    "editname_api_action" => $this->createMobileUrl("forward", null, true),
                    'task' => $task,
                    'user' => $user,
                    'title' => $task['web_title'],
                    'desc' => $task['desc'],
                    'share_title' => $task['web_title'],
                    'share_logo' => $_W['attachurl'].$task['share_logo'],
                    'share_url' => $_W['siteroot'].'app'.substr($this->createMobileUrl('share',array('share_uid'=>$user['id'],'task_id'=>$task['id'],'new_name'=>$new_name)),1),
                    'share_content' => $task['desc'],
                    'new_name' => $new_name,
                    'qrcode_url' => !empty($user['qrcode']) ? $user['qrcode'] : $_W['account']['qrcode'],
                    'follow_url' => $task['web_follow_url'],//不能扫描二维码点击此按钮进行关注
                    'account_name' => $_W['uniaccount']['name'],
                    'copyright' => !empty($task['web_copyright']) ? $task['web_copyright'] : "本页面由" . $_W['uniaccount']['name'] . "制作",
                    'openid' => $_W['openid'],
                    'music' => $_W['attachurl'].$task['web_music'],
                    'jsconfig' => $_W['account']['jssdkconfig'],
                );
                //准备用户打开的url 也就是分享的url
                $html_template = $task['template'];
                //1. 判断是否是分享人
                if($is_share_man){
                    // 判断任务的打开模式
                    if($task['open_mode'] == 1){
                        //1. 常规模式  返回真实页面
                        if($task['type'] == 0){
                            //1. 链接任务
                            $html_template =  'iframe_task';
                            if(strpos($task['template_url'],'mp.weixin.qq.com') > 0){//直接跳转
                                $html_template = 'edge_mode';
                            }
                        }else if($task['type'] == 1){
                            //2. 模板任务
                            $html_template = $task['template'];
                        }
                    }elseif($task['open_mode'] == 2){
                        //2. 边缘模式  返回任务详情页面
                        $html_template =  'edge_mode';
                    }
                }else{
                    if($task['type'] == 0){//判断是否是链接任务
                        if(strpos($task['template_url'],'mp.weixin.qq.com') > 0){//微信链接直接跳转
                            pdo_commit();//如果是微信链接提前提交事务
                            header("Location:".$task['template_url']);
                            exit();
                        }else{//不是微信
                            $html_template =  'iframe_task';
                        }
                    }else if($task['type'] == 1){//模板任务
                        $html_template = $task['template'];
                    }
                }
                pdo_commit();
                include $this->template($html_template);//返回对应的页面
            }catch (Exception $e){
                //抛出异常 则表示没有分享人 则不做处理
                pdo_rollback();
                return $this->return_json($e->getCode(),$e->getMessage(),null);
            }
        }catch (Exception $e){
            return $this->return_json($e->getCode(),$e->getMessage());
        }
    }
	/**
	 * 分享后回调页面
	 */
    public function doMobileShareCallBack(){
    	global $_GPC, $_W;
    	$task_id = $_GPC['task_id'];
    	$share_uid = $_GPC['share_uid'];
        $return_message = '';
        if(!empty($task_id)){
            $task = $this->taskService->selectById($task_id);
            $user = $this->userService->selectById($share_uid);
            if(empty($user) || $user['id'] < 1){
                return $this->return_json(400,'非法操作:没有这个推广人',null);
            }
            if(empty($task)){
                return $this->return_json(400,"没有找到该任务",null);
            }else{
                $this->taskService->columnAddCount('share_times',1,$task['id']);
                //计算出该任务已经消耗的总积分
                if(($task['total_score']-$task['share_score']) < 0 ){
                    return $this->return_json(200,"很抱歉，该任务的可消耗积分不足以支付本次分享,您将不能获得积分奖励,您可以参与分享其他任务哦",null);
                }else{
                    //更新 用户积分等数据
                    $charge = $this->shareRecordService->checkSharedInLimit($user,$task);
                    if($charge){
                        //奖励积分
                        $this->userService->updateUserScore($task['share_score'],$user['id'],$this->module['config']);
                        $this->taskService->columnReduceCount('total_score',$task['share_score'],$task['id']);
                        $return_message = "分享成功,好友点击后,您将获得".$task['share_score']."分享积分";
                        pdo_query("UPDATE ".tablename(TableResource::$table['user']['name'])." SET share_times = share_times+1, share_score = share_score+". $task['share_score'] ." WHERE id =:id",array(':id'=>$share_uid));
                    }else{
                        pdo_query("UPDATE ".tablename(TableResource::$table['user']['name'])." SET share_times = share_times+1 WHERE id =:id",array(':id'=>$share_uid));
                        $return_message = '该任务每人可分享'.$task['share_record_charge_limit'].'次，您已经参与过分享，本次分享将不奖励积分';
                    }
                    //加入到分享记录中
                    $this->shareRecordService->updateUserTaskShareRecord($user,$task);

                    return $this->return_json(200,$return_message,null);
                }

            }
        }
    }
    
    /**
     * 生成跳转地址接口
     */
    public function doMobileForward()
    {
        global $_GPC, $_W;
        $do = 'share';
        $param = array(
            'task_id' => $_GPC['task_id'],
            'share_uid' => $_GPC['share_uid'],
            'new_name' => urldecode($_GPC['new_name'])
        );
        $data = array(
            "status" => "200",
            "msg" => "ok",
            "data" => $this->createMobileUrl($do, $param)
        );
        exit(json_encode($data));
    }

    /**
     * 检查老版的是否有用户注册,这个方法针对一个bug 请勿乱使用
     */
    private function check_old_register_data($fan_id = null,$openid = null){
        try{
            global $_GPC, $_W;
            $fan_id = empty($fan_id) ? $_W['fans']['fanid'] : $fan_id;
            $openid = empty($openid) ? $_W['openid'] : $openid;
            $uniacid = $_W['uniacid'];
            if(!empty($fan_id)){
                //$exist_user = $this->userService->selectByFanid();
                $exist_user_list = $this->userService->selectAll(" AND fanid ={$fan_id}");
                if(sizeof($exist_user_list) > 1){
                    //已经产生了脏数据 删除旧数据 留下新数据 并且将积分相加
                    $save_user = null;
                    $delete_user = null;
                    if(!empty($exist_user_list[0]['openid'])){
                        $save_user = $exist_user_list[0];
                        $delete_user = $exist_user_list[1];
                    }else{
                        $save_user = $exist_user_list[1];
                        $delete_user = $exist_user_list[0];
                    }
                    $save_user['follow_times'] = $save_user['follow_times'] + $delete_user['follow_times'];
                    $save_user['unfollow_times'] = $save_user['unfollow_times'] + $delete_user['unfollow_times'];
                    $save_user['second_shareman'] = $save_user['second_shareman'] + $delete_user['second_shareman'];
                    $save_user['score'] = $save_user['score'] + $delete_user['score'];
                    $save_user['share_score'] = $save_user['share_score'] + $delete_user['share_score'];
                    $save_user['share_times'] = $save_user['share_times'] + $delete_user['share_times'];
                    $this->userService->updateData($save_user);
                    $this->userService->deleteUserById($delete_user['id']);
                }else if(sizeof($exist_user_list) == 1){
                    $exist_user = $exist_user_list[0];
                    if($exist_user && empty($exist_user['openid'])){
                        $this->userService->updateColumn('openid',$openid,$exist_user['id']);
                    }
                }
            }
        }catch (Exception $e){
            throw new UserExitsException();
        }
    }
    /**
     * 推广中心页面
     */
    public function doMobileCenter()
    {
        global $_GPC, $_W;
        $go = 'share';//这里先在后台写上 以便日后要做活 TODO
        $openid = $_W['openid'];
        //进行安全校验，不允许屏幕大的终端访问，不允许非微信浏览器访问、不允许无cookie的终端访问、不允许
        try{
            //安全检测
            $this->safecheck();
//            $oauth_user = mc_oauth_userinfo();
            //检测是否已经存在该用户
            $this->check_old_register_data();
            $user = $this->userService->selectUserByOpenidOrFanidAndFansInfo($openid,$this->module['config']);
            $sort_users = $this->userService->fetchUserListSortByScore($openid,$_W['uniacid'],10);
            //获取在售的礼品
            $gifts = $this->giftService->selectAll("AND del = false AND status = 1 AND hide=0 ORDER BY price ASC");
            //获取历史兑换记录
            $gift_orders = $this->giftOrderService->getHistoryGiftOrders($openid);
            $success_order_times = 0;
            foreach ($gift_orders as $o){
                if($o['status'] == 1) $success_order_times++;
            }
            //获取任务信息
            $tasks = pdo_fetchall("SELECT ".TableResource::$table['task']['columns'] ." FROM ". tablename(TableResource::$table['task']['name']) ." WHERE uniacid =:uniacid AND status=0 ORDER BY createtime DESC",array(':uniacid'=>$_W['uniacid']));
            //对tasks遍历 取出推荐的部分
            $ads = $this->adService->selectAll(" AND type=1");
            // 准备url
            $urls = array(
                'index' => $this->createMobileUrl('center'),
                'userSort' => $this->createMobileUrl('userSort'),
                'show' =>$this->createMobileUrl('show'),
                'center' => $this->createMobileUrl('center'),
                'api' => $this->createMobileUrl('urlsapi'),
                'adduserapi' => $this->createMobileUrl('adduserapi'),
                'giftorderapi' => $this->createMobileUrl('giftorderapi'),
                'follow_url' => $this->module['config']['follow_url'],
                'my_gifts_api_url' => $this->createMobileUrl('myGifts'),
                'my_gift_order_detail_api' => $this->createMobileUrl('myGiftOrderDetail')
            );
            $html = array(
                'config' => $this->module['config'],
                'user' => $user,
                'sort_users' => $sort_users,
                'gifts' => $gifts,
                'gift_orders' => $gift_orders,
                'tasks' => $tasks,
                'account_name' => $_W['uniaccount']['name'],
                'jsconfig' => $_W['account']['jssdkconfig'],
                'ads' => $ads,
                'follow' => 0
            );
            if(!empty($openid)){
                load()->model('mc');
                $fans_info = mc_fansinfo($openid,$_W['account']['acid']);
                $html['follow']= $fans_info['follow'];
            }
            //返回模板
            if( $this->module['config']['template'] == 'old'){
                include $this->template('taskcenter');
            }elseif($this->module['config']['template'] == 'new'){
                include $this->template('index');
            }else{
                include $this->template('index');
            }

        }catch (Exception $e){
            return $this->return_json($e->getCode(),$e->getMessage(),null);
        }

    }

    /**
     * 邀请分享关注页面 2.4添加
     */
    public function doMobileSharePage(){
        global $_GPC, $_W;
        $openid = $_GPC['openid'];
        $uniacid = $_GPC['uniacid'];
        $user = $this->userService->selectUserByOpendiOrFanid($openid,$uniacid);
        $html = array(
            'user' => $user,
            'title' => $user['name'].'邀请您关注'.$_W['uniaccount']['name'],
            'invite_tip' => $user['name'].'邀请您关注'.$_W['uniaccount']['name'],
        );
        include $this->template('share-page');
    }

    /**
     * 系统页面
     */
    public function doWebUpdatePage(){
        global $_GPC, $_W;
        $urls = array(
            'update_url' => $this->createWebUrl('updateLonaking'),
        );
        include $this->template('update_page');
    }
    /**
     * 手动更新接口
     */
    public function doWebUpdateLonaking(){
        try{
            pdo_begin();
            if(!pdo_fieldexists(TableResource::$table['gift']['name'],'pic')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['gift']['name']) . " ADD `pic` varchar(255) NOT NULL COMMENT '礼品图片'");
            }
            //2.1
            if(!pdo_fieldexists(TableResource::$table['invite']['name'],'openid')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['invite']['name']) . " ADD `openid` varchar(100) NOT NULL COMMENT 'openid'");
            }
            //2.5 增加每个任务可以设置总积分 每个任务可以设置点击或者分享多少次后就停止
            if(!pdo_fieldexists(TableResource::$table['task']['name'],'total_score')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['task']['name']) . " ADD `total_score` int(11) default '0' COMMENT '任务总积分'");
            }

            //2.6 增加礼品订单状态
            if(!pdo_fieldexists(TableResource::$table['gift_order']['name'],'pay_method')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['gift_order']['name']) . " ADD `pay_method` tinyint(1) default '1' COMMENT '1微信支付 2货到支付'");
            }

            if(!pdo_fieldexists(TableResource::$table['gift_order']['name'],'pay_status')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['gift_order']['name']) . " ADD `pay_status` tinyint(1) default '0' COMMENT '0 未支付 1已支付'");
            }

            if(!pdo_fieldexists(TableResource::$table['gift_order']['name'],'trans_num')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['gift_order']['name']) . " ADD `trans_num` varchar(100) default '0' COMMENT '快递单号'");
            }

            //增加礼品中的字段
            if(!pdo_fieldexists(TableResource::$table['gift']['name'],'mobile_fee_money')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['gift']['name']) . " ADD `mobile_fee_money` int(10) default '0' COMMENT '话费金额'");
            }

            if(!pdo_fieldexists(TableResource::$table['gift']['name'],'hongbao_money')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['gift']['name']) . " ADD `hongbao_money` int(10) default '0' COMMENT '红包金额'");
            }

            if(!pdo_fieldexists(TableResource::$table['gift']['name'],'ziling_address')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['gift']['name']) . " ADD `ziling_address` varchar(255) default '' COMMENT '自领礼品地址'");
            }
            if(!pdo_fieldexists(TableResource::$table['gift']['name'],'ziling_mobile')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['gift']['name']) . " ADD `ziling_mobile` varchar(11) default '' COMMENT '自领礼品联系电话'");
            }
            if(!pdo_fieldexists(TableResource::$table['gift']['name'],'check_password')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['gift']['name']) . " ADD `check_password` varchar(255) default '' COMMENT '自领礼品核销密码'");
            }
            if(!pdo_fieldexists(TableResource::$table['gift_order']['name'],'order_num')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['gift_order']['name']) . " ADD `order_num` varchar(255) default '' COMMENT '订单编号'");
            }
            if(!pdo_fieldexists(TableResource::$table['task']['name'],'open_mode')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['task']['name']) . " ADD `open_mode` tinyint(1) default '1' COMMENT '任务展示模式  1:常规模式，打开任务 2.边缘模式'");
            }
            // 增加限制的表
            if (! pdo_tableexists(TableResource::$table['gift_admin']['name'])) {
                $tablename = tablename(TableResource::$table['gift_admin']['name']);
                pdo_query("
                    CREATE TABLE ". $tablename ." (
                      `id` int(11) NOT NULL auto_increment,
                      `uniacid` int(11) NOT NULL,
                      `openid` varchar(40) NOT NULL default '' COMMENT '管理员id',
                      `gift_id` int(11) NOT NULL,
                      PRIMARY KEY  (`id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
                ");
            }
            if(!pdo_fieldexists(TableResource::$table['gift_order']['name'],'send_price')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['gift_order']['name']) . " ADD `send_price` decimal(10,1) default '0.0' COMMENT '配送费'");
            }
            // 增加模板消息的表
            if (! pdo_tableexists(TableResource::$table['tpl_config']['name'])) {
                $tablename = tablename(TableResource::$table['tpl_config']['name']);
                pdo_query("
                CREATE TABLE ". $tablename ." (
                  `id` int(11) NOT NULL auto_increment,
                  `uniacid` int(11) NOT NULL,
                  `get_notice` varchar(255) default '' COMMENT '礼品兑换成功（所有礼品兑换都使用此模板）',
                  `check_status_access_notice` varchar(255) default '' COMMENT '礼品兑换审核通知',
                  `check_status_refuse_notice` varchar(255) default '' COMMENT '礼品兑换拒绝通知',
                  `send_notice` varchar(255) default '' COMMENT '发货通知',
                  `invite_score_notice` varchar(255) default '' COMMENT '邀请关注积分奖励通知',
                  PRIMARY KEY  (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ");
            }

            if(!pdo_fieldexists(TableResource::$table['task']['name'],'share_record_charge_limit')){
                pdo_query("ALTER TABLE " . tablename (TableResource::$table['task']['name']) . " ADD `share_record_charge_limit` int(11) default '1' COMMENT '分享指定次数后就不给奖励了'");
            }
            //增加限制的表
            if (! pdo_tableexists(TableResource::$table['share_record']['name'])) {
                $tablename = tablename(TableResource::$table['share_record']['name']);
                pdo_query("
                    CREATE TABLE ". $tablename ." (
                      `id` int(11) NOT NULL auto_increment,
                      `uniacid` int(11) NOT NULL,
                      `openid` varchar(255) NOT NULL default '',
                      `user_id` int(11) default '0',
                      `task_id` int(11) default '0',
                      `share_times` int(11) default '0',
                      `share_score` int(11) default NULL,
                      `createtime` int(11) default NULL,
                      `updatetime` int(11) default NULL,
                      PRIMARY KEY  (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                ");
            }
            pdo_commit();
            /*更新订单号*/
            pdo_query('UPDATE '.tablename(TableResource::$table['gift_order']['name']).' SET order_num=createtime where order_num=:order_num',array(':order_num'=>''));

            if(!pdo_fieldexists('lonaking_supertask_gift','description')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `description` text COMMENT '描述'");
            }

            if(!pdo_fieldexists('lonaking_supertask_gift','hide')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `hide` tinyint(1) default '0' COMMENT '是否隐藏 1隐藏 0不隐藏'");
            }
            if(!pdo_fieldexists('lonaking_supertask_gift','sold')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `sold` int(11) default '0' COMMENT '已售出数量'");
            }
            if(!pdo_fieldexists('lonaking_supertask_gift','limit_num')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `limit_num` int(11) default '0' COMMENT '限制领取次数'");
            }
            if(!pdo_fieldexists('lonaking_supertask_gift','raffle')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `raffle` tinyint(1) default '0' COMMENT '是否是抽奖:0普通模式 1抽奖'");
            }
            if(!pdo_fieldexists('lonaking_supertask_gift_order','raffle_status')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift_order') . " ADD `raffle_status` tinyint(1) default '0' COMMENT '是否中奖:0未中奖 1中奖'");
            }
            if(!pdo_fieldexists('lonaking_supertask_gift','hongbao_mode')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `hongbao_mode` tinyint(1) default '1' COMMENT '1定额红包 2随机红包'");
            }
            if(!pdo_fieldexists('lonaking_supertask_gift_order','order_mode')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift_order') . " ADD `order_mode` tinyint(1) default '0' COMMENT '0.默认正常模式 1抽奖模式'");
            }
            if(!pdo_fieldexists('lonaking_supertask_gift_order','order_hongbao_money')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift_order') . " ADD `order_hongbao_money` int(11) default '0' COMMENT '红包金额'");
            }

            if(!pdo_fieldexists('lonaking_supertask_gift','hongbao_min')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `hongbao_min` int(11) default '0' COMMENT '红包随机下限'");
            }

            if(!pdo_fieldexists('lonaking_supertask_gift','hongbao_max')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `hongbao_max` int(11) default '0' COMMENT '红包随机上限'");
            }
            if(!pdo_fieldexists('lonaking_supertask_gift','hongbao_send_num')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `hongbao_send_num` varchar(255) default '' COMMENT '随机红包命中随机数'");
            }

            if(!pdo_fieldexists('lonaking_supertask_gift','raffle_min')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `raffle_min` int(11) default '0' COMMENT '随机下限'");
            }

            if(!pdo_fieldexists('lonaking_supertask_gift','raffle_max')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `raffle_max` int(11) default '0' COMMENT '随机上限'");
            }
            if(!pdo_fieldexists('lonaking_supertask_gift','raffle_send_num')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `raffle_send_num` varchar(255) default '' COMMENT '中奖号码'");
            }

            if(!pdo_fieldexists('lonaking_supertask_gift','auto_success')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift') . " ADD `auto_success` tinyint(1) default '0' COMMENT '是否自动审核 0:否 1:是'");
            }
            //`order_price` decimal(10,1) NOT NULL default '0.0' COMMENT '订单价格',
            if(!pdo_fieldexists('lonaking_supertask_gift_order','order_price')){
                pdo_query("ALTER TABLE " . tablename ('lonaking_supertask_gift_order') . " ADD `order_price` decimal(10,1) default '0.0' COMMENT '订单价格'");
            }

            return $this->return_json(200,'更新成功',null);
        }catch (Exception $e){
            pdo_rollback();
            return $this->return_json(400,'更新失败',$e->getMessage());
        }

    }
    /**
     * 手动更新更新openid
     */
    public function doWebUpdateOpenid(){
        global $_GPC, $_W;
        $users = $this->userService->fetchUserListSortByScore('',$_W['uniacid'],1000);
        foreach($users as $user){
            if(empty($user['openid'])){
                load()->model('mc');
                $fans_info = mc_fansinfo($user['fanid'],$_W['uniacid']);
                if(empty($fans_info['openid'])){
                    pdo_delete('lonaking_supertask_user',array('id' => $user['id']));
                }else{
                    $user['openid'] = $fans_info['openid'];
                    pdo_update('lonaking_supertask_user',array('openid'=>$fans_info['openid']),array('id'=>$user['id']));
                }
            }
        }
    }
    /**
     * 查看推广排名 重构成功
     */
    public function doMobileUserSort(){
        try{
            global $_GPC, $_W;
            $openid = $_W['openid'];
            $result = $this->userService->fetchUserListSortByScore($openid,$_W['uniacid'],10);
            return $this->return_json(200, 'success', $result);
        }catch (Exception $e){
            return $this->return_json($e->getCode(),$e->getMessage(),null);
        }

    }
    /**
     * api urls
     */
    public function doMobileUrlsApi(){
        $urls = array(
            'show' =>$this->createMobileUrl('show'),
            'center' => $this->createMobileUrl('center'),
            'api' => $this->createMobileUrl('urlsapi'),
            'adduserapi' => $this->createMobileUrl('adduserapi'),
        );
    }
    
    /**
     * 添加 保存推广人信息MOBILE 重构成功
     */
    public function doMobileAddUserApi(){
        global $_GPC, $_W;
        $openid = $_W['openid'];
        $fanid = $_W['fans']['fanid'];
        pdo_begin();
        try{
            $user['acid'] = $_W['uniacid'];
            $user['uniacid'] = $_W['uniacid'];
            $user['fanid'] = $fanid;
            $user['openid'] = $_W['openid'];
            $user['name'] = $_GPC['name'];
            $user = $this->userService->createUser($user,$this->module['config']);
            pdo_commit();
            // 通知邀请人
            try{
                $share_user = $this->userService->selectUserById($user['pid']);
                if($share_user){
                    $this->tplConfigService->sendInviteSecondSharemanTplNotice($this->module['config']['second_shareman_score'],$share_user['openid'],$user['name']);
                }
            }catch (Exception $e){

            }
        }catch(Exception $e){
        	pdo_rollback();
        	exit($this->return_json($e->getCode(),$e->getMessage(),null));
        }
        $result = pdo_fetch("SELECT " .TableResource::$table['user']['columns']." FROM ".tablename(TableResource::$table['user']['name']) ." WHERE fanid =:fanid AND uniacid =:uniacid",array(':fanid'=>$fanid,':uniacid'=>$user['uniacid']));

        exit(json_encode(array('status'=>200,'message'=>'success','data'=>$result)));
    }
    
    /**
     * 商品兑换api
     */
    public function doMobileGiftOrderApi(){
        global $_GPC, $_W;
        $current_user = $this->userService->selectUserByOpendiOrFanid($_W['openid'],$this->module['config']);
        //$current_user = pdo_fetch("SELECT " .TableResource::$table['user']['columns']." FROM ".tablename(TableResource::$table['user']['name']) ." WHERE fanid =:fanid AND uniacid =:uniacid",array(':fanid'=>$_W['fans']['fanid'],':uniacid'=>$_W['uniacid']));
        if(empty($current_user)){
            return $this->return_json(400,'您不是推广员,请先注册',null);
        }

        $gift = $this->giftService->selectById($_GPC['gift_id']);
        //存数据到数据库
        if($gift['num'] <= 0){
            return $this->return_json(400,$gift['name']."存货不足！");
        }
        //检查是否兑换过
        $extra_gift_orders = $this->giftOrderService->getCustomGiftOrder($gift['id']);
        if(sizeof($extra_gift_orders) > $gift['limit_num'] && $gift['limit_num'] != 0){
            return $this->return_json(400,"该礼品每人仅允许兑换{$gift['limit_num']}次,您已经兑换了{$gift['limit_num']}次",null);
        }
        if($gift['price'] > $current_user['score']){
            return $this->return_json(400,"积分不足");
        }
        if($gift['uniacid'] != $_W['uniacid']){
            return $this->return_json(400,"非法操作");
        }

        $order_info = array(
            'uniacid' => $current_user['uniacid'],
            'openid' => $_W['openid'],
            'uid' => $current_user['id'],
            'order_num' => time().$current_user['id'],
            'gift' => $_GPC['gift_id'],
            'status' => 0,//状态 0进行中 1成功 2失败
            'name' =>$_GPC['name'],
            'mobile' =>$_GPC['mobile'],
            'target' => $_GPC['target'],//送货地址
            'createtime' => time(),
            'updatetime' => time(),
            'pay_method' => $_GPC['pay_method'],
            'pay_status' => 0,//默认邮费未支付
            'trans_num' => 0,//默认运单号为0
            'send_price' => $gift['send_price']
        );
        if($gift['mode'] == 1){
            //微信红包
            $order_info['target'] = '';
            $order_info['name'] = $current_user['name'];
            $order_info['mobile'] = '';
            $order_info['pay_method'] = $_GPC['pay_method'];
            $this->validate_post_data($order_info,array('gift'=>"非法操作"));
        }elseif($gift['mode'] == 2){
            //话费充值
            $order_info['target'] = '';
            $order_info['name'] = $current_user['name'];
            $this->validate_post_data($order_info,array('gift'=>"非法操作",'mobile'=>'手机号不能为空'));
        }elseif($gift['mode'] == 3){
            //实物礼品
            $this->validate_post_data($order_info,array('gift'=>"非法操作",'name'=>"姓名不能为空",'mobile'=>'手机号不能为空','target'=>'领奖信息不能为空'));

        }elseif($gift['mode'] == 4){
            //自领礼品
            $order_info['target'] = '';
            $this->validate_post_data($order_info,array('gift'=>"非法操作",'name'=>"姓名不能为空",'mobile'=>'手机号不能为空'));
            //生成核销二维码
            $this->makeQrcodeFile($order_info['order_num']);
        }
        pdo_begin();
        try {
            $order_info = $this->giftOrderService->insertData($order_info);
            //扣除用户积分
            $price = $gift['price'];
            $this->userService->updateUserScore($price*-1,$current_user,$this->module['config']);
            //增加已售出数量
            $this->giftService->columnAddCount('sold',1,$order_info['gift']);
            if($gift['raffle']){
                //进行抽奖
                if(!$this->randomRaffle($gift)){
                    //没抽中奖
                    pdo_commit();
                    $order_info['raffle_status'] == 0;
                    $this->giftOrderService->updateData($order_info);
                    return $this->return_json(400,"很遗憾，您没有抽到",null);
                }else{
                    //抽中奖 修改抽奖状态
                    $order_info['raffle_status'] == 1;
                    $this->giftOrderService->updateColumn('raffle_status',1,$order_info['id']);
                }
            }
            //减少礼品数量
            $this->giftService->columnReduceCount('num',1,$order_info['gift']);
            pdo_commit();
            $result = array(
                'order' => $order_info
            );
            if($gift['mode'] == 3 && $order_info['pay_method'] == 1){
                $result['pay_redirect'] = $_W['siteroot'].'app'.substr($this->createMobileUrl('giftOrderPay',array('order_num'=>$order_info['order_num'])),1);
            }
            if($gift['mode'] == 1 || $gift['mode'] == 2 || $gift['mode'] == 4){
                $this->tplConfigService->sendGetGiftSuccessTplNotice($order_info['id'],$this->module['config']);
            }elseif($gift['mode'] == 3){
                if($order_info['pay_method'] == 2){
                    $this->tplConfigService->sendGetGiftSuccessTplNotice($order_info['id'],$this->module['config']);
                }
            }
            //自动审核则继续通知审核通过
//            if($gift['auto_success'] == 1){
//                $this->accessGiftOrder($order_info['id']);
//            }
            if($gift['raffle']){
                return $this->return_json(200, "恭喜您中奖啦,管理员审核后将会为您发放礼品", $result);
            }else{
                return $this->return_json(200, "兑换成功,管理员审核后将会发放礼品", $result);
            }
        } catch (Exception $e) {
            pdo_rollback();
            exit($this->return_json(400, '兑换失败，未知异常，请联系管理员',null));
        }
    }

    /**
     * 随机抽奖
     */
    private function randomRaffle($gift){
        $send_num = $gift['raffle_send_num'];
        $sendArr= explode(',',$send_num);
        $rand=rand($gift['raffle_min'],$gift['raffle_max']);
        $isInclude=in_array($rand,$sendArr);
        if($isInclude){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 支付方法
     */
    public function doMobileGiftOrderPay(){
        global $_W, $_GPC;
        $order_num = $_GPC['order_num'];
        $order_info = $this->giftOrderService->selectByOrderNum($order_num);
        if(empty($order_info)){
            return message("非法操作，订单不存在");
        }
        if($order_info['pay_method'] == 1){
            $pay_data = array(
                'tid' => $order_info['order_num'],      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
                'ordersn' => $order_info['order_num'],  //收银台中显示的订单号
                'title' => '礼品的运费',           //收银台中显示的标题
                'fee' => $order_info['send_price'],      //收银台中显示需要支付的金额,只能大于 0
                'user' => $_W['member']['uid'],     //付款用户, 付款的用户名(选填项)
            );
            $this->pay($pay_data);
        }

    }

    /**
     * 支付回调
     */
    public function payResult($params){
        /*
         * $params 结构
         *
         * weid 公众号id 兼容低版本
         * uniacid 公众号id
         * result 支付是否成功 failed/success
         * type 支付类型 credit 积分支付 alipay 支付宝支付 wechat 微信支付  delivery 货到付款
         * tid 订单号
         * user 用户id
         * fee 支付金额
         *
         * 注意：货到付款会直接返回支付失败，请在订单中记录货到付款的订单。然后发货后收取货款
         */
        $fee = intval($params['fee']);
        $data = array('status' => $params['result'] == 'success' ? 1 : 0);
        //如果是微信支付，需要记录transaction_id。
        if ($params['type'] == 'wechat') {
            $data['transid'] = $params['tag']['transaction_id'];
        }
        try{
            $order_info = $this->giftOrderService->selectByOrderNum($params['tid']);
            //支付后将订单支付状态改成成功
            $this->giftOrderService->updateColumn('pay_status',1,$order_info['id']);
            //支付后自动通过
            $this->giftOrderService->updateColumn('status',1,$order_info['id']);
            $gift = $this->giftService->selectById($order_info['gift']);
            if($gift['mode'] == 4){
                $this->tplConfigService->sendGetGiftSuccessTplNotice($order_info,$this->module['config']);
            }
            message('支付成功！', $this->createMobileUrl('center'), 'success');
        }catch (Exception $e){
            message($e->getMessage(),$this->createMobileUrl('index1'), 'error');
        }
    }
    /**
     * 查看我的礼品
     */
    public function doMobileMyGiftOrderDetail(){
        global $_GPC, $_W;
        $id = $_GPC['id'];
        $gift_order = $this->giftOrderService->selectGiftOrdersDetail($id);
        $gift_order['status_text'] = $this->getGiftOrderStatusText($gift_order);
        $gift_order['create_time_text'] = date('m月d日 H:i',$gift_order['createtime']);
        $gift_order['pay_status_text'] = $gift_order['pay_status'] == 1 ? '已支付' : '未支付';
        if($gift_order['pay_method'] == 1){
            $gift_order['pay_method_text'] = '微信支付';
        }elseif($gift_order['pay_method'] == 2){
            $gift_order['pay_method_text'] = '货到付款';
        }
        if($gift_order['mode'] == 4 && $gift_order['status'] == 1){//自领礼品 已经同意
            $gift_order['check_qrcode'] = $this->getCheckQrcodeUrl($gift_order['order_num']);
        }
        if($gift_order['mode'] == 3 && $gift_order['status'] == 0){
            $gift_order['pay_url'] =  $_W['siteroot'].'app'.substr($this->createMobileUrl('giftOrderPay',array('order_num'=>$gift_order['order_num'])),1);
        }
        return $this->return_json(200,'success',$gift_order);
    }

    /**
     * 绑定管理员信息接口
     */
    public function doMobileBindGiftAdmin(){
        global $_W, $_GPC;
        $password = $_GPC['password'];
        $openid = $_GPC['openid'];
        $this->validate_post_data($_GPC,array('password'=>"密码不能为空",'openid'=>"非法操作"));
        $gift = $this->giftService->selectByCheckPassword($password);
        if(empty($gift)){
            return $this->return_json(400,'不存在此核销密码,请检查是否输错',null);
        }else{
            //更新
            $admin = array(
                //id,uniacid,openid,shop_id
                'uniacid' => $_W['uniacid'],
                'openid' => $openid,
                'gift_id' => $gift['id']
            );
            //pdo_insert(LonakingCouponSQLHelper::$table['admin']['name'],$admin);
            $admin = $this->giftAdminService->insertData($admin);
            return $this->return_json(200,'您已成功绑定成为['. $gift['name']. ']的核销管理员',null);
        }
    }

    /**
     * 核销页面
     */
    public function doMobileGiftOrderCheckPage(){
        global $_W, $_GPC;
        $openid = $_W['openid'];
        $admins = $this->giftAdminService->selectByOpenid($openid);
        $html = array(
            'bind_gift_url' => $this->createMobileUrl('bindGiftAdmin'),
            'use_url' => $this->createMobileUrl('zilingGiftOrderCheck'),
            'jsconfig' => $_W['account']['jssdkconfig'],
            'openid' => $_W['openid'],
            'sao' => true
        );
        if($admins){
            $html['admins'] = $admins;
        }
        if($_W['account']['level'] != 4){
            $html['sao'] = false;
        }
        include $this->template('scan');
    }
    /**
     * 核销一个自领礼品
     */
    public function doMobileZilingGiftOrderCheck(){
        global $_W, $_GPC;
        $gift_order_num = $_GPC['order_num'];
        $admin_openid = $_GPC['openid'];
        if(empty($admin_openid)){
            return $this->return_json(400,'非法操作',"admin_openid can not be none!");
        }
        $gift_order = $this->giftOrderService->selectByOrderNum($gift_order_num);
        if(empty($gift_order)){
            return $this->return_json(400,'非法操作,不存在此条兑换记录',null);
        }
        $admin = $this->giftAdminService->selectByOpenidAndGiftId($admin_openid,$gift_order['gift']);
        if(empty($admin)){
            return $this->return_json(400,'非法操作,你并不是管理员',null);
        }
        //4 检验核销管理员是否对此商铺有权限操作
        if($admin['gift_id'] != $gift_order['gift']){
            return $this->return_json(400,'无权限,您无法核销此条兑换记录',$admin);
        }
        //5. 检验完毕,通过检验 进行消费
        try{
            $this->giftOrderService->updateColumn('status',5,$gift_order['id']);
            return $this->return_json(200,'核销成功',null);
        }catch(Exception $e){
            return $this->return_json(400,'系统异常',null);
        }
    }


    /**
     * 我的礼品 用户查看自己兑换的礼品列表
     */
    public function doMobileMyGifts(){
        global $_GPC, $_W;
        date_default_timezone_set('Asia/Shanghai');
        $openid = $_W['openid'];
        $my_gift_orders = $this->giftOrderService->selectMyOrdersWithGiftInfo($openid);
        if(empty($my_gift_orders)){
            return $this->return_json(5004,'你还没有兑换任何礼品',null);
        }
        $result = array();
        $new_result = array();
        $succes_result = array();
        for($i=0;$i<sizeof($my_gift_orders);$i++){
            $tmp_gift_order = $my_gift_orders[$i];
            $status_text = $this->getGiftOrderStatusText($tmp_gift_order);
            $tmp_gift_order['status_text'] = $status_text;
            $tmp_gift_order['create_time_text'] = date('m月d日 H:i',$tmp_gift_order['createtime']);
            if($tmp_gift_order['status'] == 0){
                //新的
                $new_result[] = $tmp_gift_order;
            }elseif($tmp_gift_order['status'] == 1 || $tmp_gift_order['status'] == 5 || $tmp_gift_order['status'] == 2){
                //完成
                $succes_result[] = $tmp_gift_order;
            }
        }
        $result['new_gifts'] = $new_result;
        if(empty($new_result)){
            $result['new_gifts'] = null;
        }
        $result['success_gifts'] = $succes_result;
        if(empty($succes_result)){
            $result['success_gifts'] = null;
        }
        return $this->return_json(200,'success',$result);
    }


    /**
     * 获取礼品订单的状态
     * @param $giftOrder
     * @return string
     */
    private function getGiftOrderStatusText($giftOrder){
        $mode = $giftOrder['mode'];
        $status = $giftOrder['status'];
        $status_text = '待审核';
        //如果status=2的话直接就是未通过
        if($status == 2){
            return '未通过(积分已退回)';
        }
        //如果status＝0的话直接就是待审核
        if($status == 0){
            return '待审核';
        }
        if($mode == 1){
            //微信红包
            if($status == 1){
                $status_text = '红包已发放';
            }
        }elseif($mode == 2){
            //充值
            if($status == 1){
                $status_text = '已充值';
            }
        }elseif($mode == 3){
            //实物礼品
            if($status == 1){
                if($giftOrder['trans_num'] == 0){
                    $status_text = '待发货';
                }else{
                    $status_text = '已发货';
                }
            }
        }elseif($mode == 4){
            //自领礼品
            if($status == 1){
                $status_text = '已审核';
            }elseif($status == 5){
                $status_text = '已领取';
            }
        }
        return $status_text;

    }
    /**
     * 准备数据
     * @param unknown $arr
     */
    private function prepare($arr){
        global $_GPC, $_W;
        if(!empty($arr)){
            foreach ($arr as $a){
                if($a == "config"){
                    $this->config = pdo_fetch("SELECT ".TableResource::$table['config']['columns']." FROM ".tablename(TableResource::$table['config']['name'])." WHERE uniacid = :uniacid",array(":uniacid" => $_W['uniacid']));
                }
            }
        }
    }
    /*检测是否第一次点击*/
    private function is_firstclick(){
    	global $_GPC, $_W;
    	$task_id = $_GPC['task_id'];
    	//1 读取cookie
    	$task_ids = $_COOKIE['__task_click_history'];
    	$task_ids_arr = explode(",", $task_ids);
    	if(in_array($task_id, $task_ids_arr)){
    		return false;//不是第一次点击
    	}else{
	        $task_ids = $task_ids.",".$task_id;
    		setcookie("__task_click_history",$task_ids,time()+(3600*24*30),"/");
    		return true;//是第一次点击
    	}
    }
    
    /**
     * 安全检测
     */
    private function safecheck(){
        global $_GPC, $_W;
        //1. 判断useragent
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if(strpos($user_agent, 'MicroMessenger') == false){
                //设置cookie 危险
                setcookie("__d","1",time()+(3600*24*30),"/");
                throw new Exception("请在微信浏览器中打开",5040);
            }
        //2 .判断cookie
        $danger = $_COOKIE['__d'];
        if($danger == 1){
            throw new Exception("请在微信浏览器中打开",5040);
        }
    }
    /**
     * 校验 表单的内容是否为空，效率一般
     */
    private function validate_post_data($validate_info, $validate_options){
        foreach ($validate_info as $info_key => $info_value) {//key : 表单字段名 $value:值
            foreach ($validate_options as $opt_key => $opt_message) {//$opt_key : 要验证的字段名 $opt_value:提示
                if($info_key == $opt_key){
                    if(empty($info_value)){
                        exit(json_encode(array('status' => 400,'message' =>$opt_message,'data'=>null)));
                    }
                }
            }
        }
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
     * 配置中心WEB
     */
    public function doWebConfig(){
        global $_GPC, $_W;
        $config = pdo_fetch("SELECT ".TableResource::$table['config']['columns']." FROM ".tablename(TableResource::$table['config']['name'])." WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $new_config = $config;
        if (!empty($_GPC['submit'])) {//提交表单
            //d,acid,uniacid,title,duty_content,gift_type,follow_score,unfollow_score,second_shareman_score,qrcode_type";
            $data = $_GPC['data'];
            $new_config['title'] = $data['title'];
            $new_config['acid'] = $_W['uniacid'];
            $new_config['uniacid'] = $_W['uniacid'];
            //$config['duty_content'] = $data['duty_content'];
            
            $new_config['follow_score'] = (isset($data['follow_score']) || $data['follow_score'] <= 0 )? $data['follow_score'] : 2;
            $new_config['unfollow_score'] = (isset($data['unfollow_score']) || $data['unfollow_score'] <= 0) ? $data['unfollow_score'] : 1; 
            $new_config['second_shareman_score'] = (isset($data['second_shareman_score'])|| $data['second_shareman_score'] <= 0 ) ? $data['second_shareman_score'] : 3;
            $new_config['qrcode_type'] = 1;
            $new_config['gift_type'] = $data['gift_type'];
            $flag = null;
            if($config == $new_config){
                return message("没有任何修改","","danger");
            }
            if(empty($data['id'])){
                $flag = pdo_insert(TableResource::$table['config']['name'],$new_config);
            }else{
                $flag = pdo_update(TableResource::$table['config']['name'],$new_config,array('id'=>$data['id']));
            }
            if ($flag) {
                return message("信息保存成功", "", "success");
            } else {
                return message("信息保存失败", "", "error");
            }
        }
        load()->func('tpl');
        include $this->template('config');
    }
    /**
     * 礼品列表
     */
    public function doWebGifts(){
        
        global $_W, $_GPC;
        $uniacid=$_W["uniacid"];
        $where="";
        $status = -1;
        if(!is_null($_GPC['s'])){
            $status = $_GPC['s'];
        }
        if (!is_null($_GPC['s'])) {
            $where .= "AND status = {$status}";
        }
        $orderby = "";
        if(!is_null($_GPC['orderby'])){
            $sort =  (is_null($_GPC['sort'])) ? 'DESC' : $_GPC['sort'];
            $orderby = ", ". $_GPC['orderby'] ." ".$sort;
        }
        $page_index = max(1, intval($_GPC['page']));
        $page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
        $gifts = pdo_fetchall("SELECT ".$this->giftService->columns ." FROM ". tablename($this->giftService->table_name) ." WHERE uniacid ='{$uniacid}' AND del = false AND 1=1  {$where} ORDER BY id DESC {$orderby} LIMIT ". ($page_index -1) * $page_size . ',' .$page_size);
        $total = pdo_fetchcolumn("SELECT COUNT(1) FROM ". tablename($this->giftService->table_name) ." WHERE uniacid='{$uniacid}' AND del = false AND 1=1  {$where}");
        $pager = pagination($total, $page_index, $page_size);
        load()->func('tpl');
        include $this->template('gifts');
    }
    /**
     * 礼品详情/添加/修改礼品页面
     */
    public function doWebGift(){
        global $_GPC, $_W;
        checkaccount();//
        $id = $_GPC['id'];
        if (!empty($_GPC['submit'])) {//提交表单
            $gift = $_GPC['gift'];
            $gift['uniacid'] = $_W['uniacid'];
            try{
                $gift['description'] = htmlspecialchars_decode($gift['description']);
                $this->giftService->insertOrUpdate($gift);
                return message("信息保存成功", "", "success");
            }catch (Exception $e){
                return message("信息保存失败", "", "error");
            }
        }else{
            $gift = null;
            $option = "礼品添加";
            if(!is_null($id)){
                $gift = $this->giftService->selectById($id);
                $gift['description'] = htmlspecialchars_decode($gift['description']);
                $option = "礼品信息修改";
            }
            $gift_type = explode(",",$this->module['config']['gift_type']);
            load()->func('tpl');
            include $this->template('gift_edit');
        }
    }
    /**
     * 商品删除
     */
    public function doWebGift_Remove(){
        global $_GPC, $_W;
        checkaccount();
        $id = $_GPC['id'];
        try{
            Assert::not_empty($id,"非法操作");
            $this->giftService->updateColumn('del',1,$id);
            return message("删除成功","","success");
        }catch (Exception $e){
            return message("删除失败:".$e->getMessage(),"","danger");
        }
    }
    /**
     * 礼品兑换记录
     */
    public function doWebGiftOrders(){
        global $_W, $_GPC;
        $uniacid=$_W["uniacid"];
        $where="";
        $status = -1;
        if(!is_null($_GPC['s'])){
            $status = $_GPC['s'];
        }
        if (!is_null($_GPC['s'])) {
            $where .= "AND o.status = {$status}";
        }
        $page_index = max(1, intval($_GPC['page']));
        $page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
        $gift_orders = pdo_fetchall("SELECT o.id id,o.uniacid uniacid,o.uid uid,o.openid openid,o.gift gift ,o.status status,o.name real_name,o.mobile mobile, o.target target,o.createtime createtime, o.updatetime updatetime,o.pay_status,o.pay_method,o.trans_num,g.name gift_name,g.mode,g.send_price,g.mobile_fee_money,g.hongbao_money,g.ziling_address,u.name user_name,u.openid user_openid FROM ". tablename(TableResource::$table['gift_order']['name']) ." o LEFT JOIN ". tablename(TableResource::$table['gift']['name']) ." g ON o.gift=g.id LEFT JOIN ".tablename(TableResource::$table['user']['name'])." u ON o.uid=u.id WHERE o.uniacid='{$uniacid}' AND 1=1  {$where} ORDER BY o.createtime DESC LIMIT ". ($page_index -1) * $page_size . ',' .$page_size);
        //$gift_orders = pdo_fetchall("SELECT o.id id,o.uniacid uniacid,o.uid uid,o.openid openid,o.gift gift ,o.status status,o.name real_name,o.mobile mobile, o.target target,o.createtime createtime, o.updatetime updatetime,g.id g_id,g.name gift_name,g.mode,g.send_price,u.id user_id,u.name user_name,u.openid user_openid FROM ". tablename(TableResource::$table['gift']['name']) ." g,".tablename(TableResource::$table['user']['name'])." u RIGHT JOIN ". tablename(TableResource::$table['gift_order']['name']) ." o ON (o.gift=g.id AND o.uid=u.id) WHERE o.uniacid='{$uniacid}' AND 1=1  {$where} ORDER BY o.createtime DESC LIMIT ". ($page_index -1) * $page_size . ',' .$page_size);
        $total = pdo_fetchcolumn("SELECT COUNT(1) FROM ". tablename(TableResource::$table['gift_order']['name']) ." o WHERE o.uniacid='{$uniacid}' AND 1=1  {$where}");
        $pager = pagination($total, $page_index, $page_size);
        load()->func('tpl');
        include $this->template('giftorders');
    }

    /**
     * 核销记录
     */
    public function doWebCheckRecordManage(){
        global $_W, $_GPC;
        $uniacid=$_W["uniacid"];
        $where="";//核销成功 并且是自领的
        $page_index = max(1, intval($_GPC['page']));
        $page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
        $gift_orders = pdo_fetchall("SELECT o.id id,o.uniacid uniacid,o.openid openid,o.gift gift ,o.status status,o.name real_name,o.mobile mobile,o.raffle_status,o.target target,o.createtime createtime, o.updatetime updatetime,o.pay_status,o.pay_method,o.trans_num,o.order_num,o.send_price,o.order_price,o.order_mode,o.order_hongbao_money,g.name gift_name,g.mode,g.send_price,g.mobile_fee_money,g.hongbao_money,g.ziling_address FROM ". tablename($this->giftOrderService->table_name) ." o LEFT JOIN ". tablename($this->giftService->table_name) ." g ON o.gift=g.id WHERE o.uniacid='{$uniacid}' AND 1=1  {$where} AND o.status=5 AND g.mode=4 ORDER BY {$orderby} o.createtime DESC LIMIT ". ($page_index -1) * $page_size . ',' .$page_size);
        $total = pdo_fetchcolumn("SELECT count(1) FROM ". tablename($this->giftOrderService->table_name) ." o LEFT JOIN ". tablename($this->giftService->table_name) ." g ON o.gift=g.id WHERE o.uniacid={$uniacid} AND 1=1  {$where} AND o.status=5 AND g.mode=4");
        $pager = pagination($total, $page_index, $page_size);
        load()->func('tpl');
        include $this->template('check_record');
    }

    /**
     * 新接口 切换礼品状态
     */
    public function doWebOptionGiftOrder(){
        global $_W, $_GPC;
        checkaccount();
        $order_id = $_GPC['id'];
        $option = $_GPC['opt'];
        $opts = array('refuse','ok');
        try{
            $gift_order = $this->giftOrderService->selectGiftOrdersDetail($order_id);
            $user = $this->userService->selectUserByOpendiOrFanid($gift_order['openid'],$this->module['config']);
            if(!in_array($option, $opts)){
                return $this->return_json(400,"非法的操作",null);
            }
            if($gift_order['status'] == 2){
                return $this->return_json(400,'该兑换记录已经被拒绝，状态无法更改',null);
            }

            if($option == 'refuse'){
                //拒绝
                $this->giftOrderService->updateColumn('status',2,$order_id);
                //将积分退回
                $this->userService->updateUserScore($gift_order['price'],$user,$this->module['config']);
                //礼品数量恢复
                $this->giftService->columnAddCount('num',1,$gift_order['gift']);
                //TODO 发送模板消息拒绝
                $this->tplConfigService->sendGiftOrderCheckStatusRefuseNotice($gift_order);
                return $this->return_json();
            }
            //拿到对应的礼品的模式
            $gift = $this->giftService->selectById($gift_order['gift']);
            pdo_begin();
            if($gift_order['status'] == 1){
                return $this->return_json(400,'该兑换记录已经审核,无需重复审核',null);
            }

            //更改状态
            $this->giftOrderService->updateColumn('status',1,$order_id);

            $gift_mode = $gift['mode'];
            //根据不同的礼品模式来进行不同的操作，拒绝不考虑 1微信红包 2充值 3实物礼品 4自领礼品
            if($gift_mode == 1){
                //1微信红包 发红包
                $hongbao_money = $gift['hongbao_money'];
                $toopenid = $gift_order['openid'];
                $this->sendRedpack($toopenid,$hongbao_money,false);//TODO 传入参数

                $this->sendGiftOrderNotice();//TODO 通知用户
            }elseif($gift_mode == 2){
                //2充值
                $this->sendGiftOrderNotice();//TODO 通知用户充值成功的信息
            }elseif($gift_mode == 3){
                //3实物礼品 准备快递单号之类的数据
                $this->sendGiftOrderNotice();//TODO 通知用户快递单号之类的信息
            }elseif($gift_mode == 4){
                //4自领礼品 核销

                $this->sendGiftOrderNotice();//TODO 通知用户核销二维码
            }
            //发送模板消息通知

            pdo_commit();
            $this->tplConfigService->sendGiftOrderCheckStatusAccessNotice($gift_order);
            return $this->return_json(200,'成功',null);
        }catch(WxHongBaoException $e){
            pdo_rollback();
            return $this->return_json($e->getErrorCode(),$e->getErrorMessage(),null);
        }
    }

    /**
     * 发送订单状态更新的模板消息通知
     */
    private function sendGiftOrderNotice(){
        //发送模版消息
    }

    /**
     * 模板消息配置
     */
    public function doWebTplNoticeConfig(){
        global $_GPC, $_W;
        checkaccount();//
        $form = $_GPC['tpl_config'];
        $uniacid = $_W['uniacid'];
        if (!empty($_GPC['submit'])) {//提交表单
            try{
                $form['uniacid'] = $uniacid;
                $this->tplConfigService->updateTplConfigByUniacit($form);
                return message("信息保存成功", "", "success");
            }catch (Exception $e){
                return message("信息保存失败", "", "error");
            }
        }else{
            $tpl_config = $this->tplConfigService->checkConfigByUniacid($uniacid);
            include $this->template('tpl_config');
        }
    }

    /**
     * 更新快递单号
     */
    public function doWebUpdateTransNum(){
        global $_W, $_GPC;
        $num = $_GPC['num'];
        $id = $_GPC['order_id'];
        if(!empty($num)){
            try{
                $this->giftOrderService->updateColumn('trans_num',$num,$id);
                $this->tplConfigService->sendGiftSendUpTplNotice($id);
                return $this->return_json(200,'更新成功',null);
            }catch (Exception $e){
                return $this->return_json(400,'错误',null);
            }
        }
    }
    /**
     * 广告管理
     * @throws Exception
     */
    public function doWebAds(){
        global $_W,$_GPC;
        try{
            $ads = $this->adService->selectAll();
            $html = array(
                'ads' => $ads,
            );
            include $this->template('ads');
        }catch (Exception $e){
            include $this->template('ads');
        }
    }

    /**
     * 广告添加或者修改
     * @throws Exception
     */
    public function doWebAd(){
        global $_GPC, $_W;
        checkaccount();//
        $id = $_GPC['id'];
        if (!empty($_GPC['submit'])) {//提交表单
            $ad = $_GPC['ad'];
            $ad['uniacid'] = $_W['uniacid'];
            try{
                $this->adService->insertOrUpdate($ad);
                return message("信息保存成功", "", "success");
            }catch (Exception $e){
                return message("信息保存失败", "", "error");
            }
        }else{
            $ad = null;
            $option = "广告添加";
            if(!is_null($id)){
                $ad = $this->adService->selectById($id);
                $option = "广告信息修改";
            }
            load()->func('tpl');
            include $this->template('ad_edit');
        }
    }

    /**
     * 删除一个广告
     */
    public function doWebAdRemove(){
        global $_GPC, $_W;
        checkaccount();//
        $id = $_GPC['id'];
        try{
            $this->adService->deleteById($id);
            return $this->return_json(200,"删除成功",null);
        }catch (Exception $e){
            return $this->return_json($e->getCode(),$e->getMessage(),null);
        }

    }
    /**
     * 推广人
     */
    public function doWebUsers(){
        global $_W, $_GPC;
        $uniacid=$_W["uniacid"];
        $where="";
        if (!empty($_GPC['s'])) {
            $where .= " AND u.status = {$_GPC['s']}";
        }
        /*准备排序参数*/
        $orderby = "";
        if(!is_null($_GPC['orderby'])){
            $sort =  (is_null($_GPC['sort'])) ? 'DESC' : $_GPC['sort'];
            if($_GPC['orderby'] == 'score'){
                if(!$this->module['config']['score_we7']){
                    $orderby = 'score '.$sort;
                }else{//同步微擎积分
                    $orderby = 'm.credit1 '.$sort;
                }
            }elseif($_GPC['orderby'] == 'follow_times'){
                if(!$this->module['config']['score_we7']){
                    $orderby = 'follow_times '.$sort;
                }else{//同步微擎积分
                    $orderby = 'u.follow_times '.$sort;
                }

            }elseif($_GPC['orderby'] == 'second_shareman'){

                if(!$this->module['config']['score_we7']){
                    $orderby = 'second_shareman '.$sort;
                }else{//同步微擎积分
                    $orderby = 'u.second_shareman '.$sort;
                }
            }
        }else{
            if(!$this->module['config']['score_we7']){
                $orderby = 'createtime ASC';
            }else{//同步微擎积分
                $orderby = 'u.createtime ASC';
            }
        }
        /*/准备排序参数*/
        /*准备分页以及搜索参数*/
        $page_index = max(1, intval($_GPC['page']));
        if(!empty($_GPC['name_like'])){
            if(!$this->module['config']['score_we7']){
                $where = $where .= " AND name like '%{$_GPC['name_like']}%'";
            }else{//同步微擎积分
                $where = $where .= " AND u.name like '%{$_GPC['name_like']}%'";
            }

            $page_index = 1;
        }
        $page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
        /*/准备分页以及搜索参数*/
        $users = array();
        if(!$this->module['config']['score_we7']){
            $users = pdo_fetchall("SELECT ".TableResource::$table['user']['columns'] ." FROM ". tablename(TableResource::$table['user']['name']) ." WHERE uniacid ='{$uniacid}' AND 1=1  {$where} ORDER BY  {$orderby} LIMIT ". ($page_index -1) * $page_size . ',' .$page_size);
        }else{
            $users = pdo_fetchall("SELECT u.id,u.acid,u.uniacid,u.fanid,u.openid,u.pid,u.agree_duty,u.qrcode,u.qrcode_updatetime,u.scene_id,u.qrcid,u.name,u.follow_times,u.unfollow_times,u.second_shareman,u.score,u.share_score,u.createtime,u.updatetime,u.share_times,f.fanid fan_id,f.uid,f.openid f_openid,m.credit1,m.credit2 FROM ". tablename(TableResource::$table['user']['name']) ." u LEFT JOIN ". tablename('mc_mapping_fans') ." f ON u.fanid=f.fanid LEFT JOIN ". tablename('mc_members') . " m ON m.uid=f.uid  WHERE u.uniacid ='{$uniacid}' AND 1=1  {$where} ORDER BY {$orderby} LIMIT ". ($page_index -1) * $page_size . ',' .$page_size);
        }
        $total = pdo_fetchcolumn("SELECT COUNT(1) FROM ". tablename(TableResource::$table['user']['name']) ." WHERE uniacid='{$uniacid}' AND 1=1  {$where}");
        $pager = pagination($total, $page_index, $page_size);
        load()->func('tpl');
        include $this->template('users');
    }


    /**
     * 删除一个推广人
     */
    public function doWebDelUserApi(){
        global $_GPC, $_W;
        $id = $_GPC['id'];

        try{
            $this->userService->deleteUserById($id);
            return $this->return_json(200,'删除成功',null);
        }catch (Exception $e){
            return $this->return_json($e->getCode(),$e->getMessage(),null);
        }
    }

    /**
     * 推广任务列表
     */
    public function doWebTasks(){
        global $_W, $_GPC;
        $uniacid=$_W["uniacid"];
        $where="";
        $status = -1;
        if(!is_null($_GPC['s'])){
            $status = $_GPC['s'];//0 进行中 1暂停 2完成 -1 不过滤
        }
        if (!is_null($_GPC['s'])) {
            $where .= "AND status = {$_GPC['s']}";
        }
        $page_index = max(1, intval($_GPC['page']));
        $page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0 )? 10 : $_GPC['size'];
        $tasks = pdo_fetchall("SELECT ".TableResource::$table['task']['columns'] ." FROM ". tablename(TableResource::$table['task']['name']) ." WHERE uniacid ='{$uniacid}' AND 1=1  {$where} ORDER BY createtime ASC LIMIT ". ($page_index -1) * $page_size . ',' .$page_size);
        $total = pdo_fetchcolumn("SELECT COUNT(1) FROM ". tablename(TableResource::$table['task']['name']) ." WHERE uniacid='{$uniacid}' AND 1=1  {$where}");
        $pager = pagination($total, $page_index, $page_size);
        load()->func('tpl');
        $this->taskService->checkRegister($this->module);
        include $this->template('tasks');
    }
    /**
     * 添加任务/更新任务/
     */
    public function doWebTask(){
        global $_GPC, $_W;
        checkaccount();//
        $id = $_GPC['id'];//辨识更新的是哪一个
        $ads = null;
        try{
            $ads = $this->adService->selectAll();
        }catch (Exception $e){

        }
        if (!empty($_GPC['submit'])) {//提交表单
            $task = $_GPC['task'];
            $task['uniacid'] = $_W['uniacid'];
            $task['createtime'] = time();
            $task['updatetime'] = time();
            try{
                $new_task = $this->taskService->insertOrUpdate($task);
                if(!empty($_GPC['ad'])){
                    $this->taskAdService->updateTaskAd($new_task['id'],$_GPC['ad']);
                }else{
                    $this->taskAdService->deleteAdByTask($new_task['id']);
                }
                return message("任务保存成功", "", "success");
            }catch (Exception $e){
                return message("任务保存失败".$e->getMessage(), "", "error");
            }
        }else{
            $task = null;
            $option_title = "任务添加";
            $option = "add";
            if(!is_null($id)){
                $task = $this->taskService->selectTaskById($id);
                $option_title = "任务信息修改";
                $option = "update";
            }
            load()->func('tpl');
            include $this->template('task_edit');
        }
    }
    /**
     * 操作任务状态
     */
    public function doWebOption_task(){
        global $_W, $_GPC;
        checkaccount();
        $option = $_GPC['opt'];
        $opts = array('start','pause','finish','delete');
        try{
            if(!in_array($option, $opts)){
                $this->return_json(400,"非法的操作",null);
            }else if($option == 'delete'){
                $this->taskService->deleteById($_GPC['id']);
                return $this->return_json(200,'success','delete');
            }else{
                $status = 0;
                if($option == "start") $status = 0;
                if($option == "pause") $status = 1;
                if($option == "finish") $status = 2;
                $this->taskService->updateColumn('status',$status,$_GPC['id']);
                return $this->return_json();

            }
        }catch (Exception $e){
            return $this->return_json($e->getCode(),$e->getMessage(),null);
        }
    }

    /**
     * 发放红包
     * @param $param_openid
     * @return string
     */
    private function sendRedpack($param_openid,$money = 0,$is_random = false){
        $old_money = $money;
        define('DS', DIRECTORY_SEPARATOR);
        define('SIGNTYPE', "sha1");
        define('APPID',$this->module['config']['appid']);
        define('MCHID',$this->module['config']['mchid']);
        define('PARTNERKEY',$this->module['config']['partner']);
        define('NICK_NAME',$this->module['config']['nick_name']);
        define('SEND_NAME',$this->module['config']['send_name']);
        define('WISHING',$this->module['config']['wishing']);//祝福语
        define('ACT_NAME',$this->module['config']['act_name']);
        define('REMARK',$this->module['config']['remark']);

        define('apiclient_cert',$this->module['config']['apiclient_cert']);
        define('apiclient_key',$this->module['config']['apiclient_key']);
        define('rootca',$this->module['config']['rootca']);//证书

        define('money',$this->module['config']['money']);
        define('money_extra',$this->module['config']['money_extra']);
        define('min',$this->module['config']['randmin']);
        define('max',$this->module['config']['randmax']);
        define('sendNum',$this->module['config']['sendnum']);
        // 1. 随机红包金额 {固定金额 随即红包}
        $isInclude = false;

        if($is_random){
            //随机红包
            $money=money+rand(0,money_extra);
            $min=min;
            $max=max;
            $sendNum=sendnum;
            $sendArr= explode(',',sendNum);
            $rand=rand(min,max);
            $isInclude=in_array($rand,$sendArr);
        }else{
            //固定红包
            $money = $old_money;

        }
        if($isInclude || !$is_random){

            $mch_billno=MCHID.date('YmdHis').rand(1000, 9999);//订单号
            $commonUtil = new CommonUtil();
            $wxHongBaoHelper = new WxHongBaoHelper();
            $wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串，不长于32位
            $wxHongBaoHelper->setParameter("mch_billno", $mch_billno);//订单号
            $wxHongBaoHelper->setParameter("mch_id", MCHID);//商户号
            $wxHongBaoHelper->setParameter("wxappid", APPID);
            $wxHongBaoHelper->setParameter("nick_name",NICK_NAME);//提供方名称
            $wxHongBaoHelper->setParameter("send_name", SEND_NAME);//红包发送者名称
            $wxHongBaoHelper->setParameter("re_openid", $param_openid);//相对于医脉互通的openid
            $wxHongBaoHelper->setParameter("total_amount", $money);//付款金额，单位分
            $wxHongBaoHelper->setParameter("min_value", $money);//最小红包金额，单位分
            $wxHongBaoHelper->setParameter("max_value", $money);//最大红包金额，单位分
            $wxHongBaoHelper->setParameter("total_num", 1);//红包发放总人数
            $wxHongBaoHelper->setParameter("wishing",WISHING );//红包祝福诧
            $wxHongBaoHelper->setParameter("client_ip", '127.0.0.1');//调用接口的机器 Ip 地址
            $wxHongBaoHelper->setParameter("act_name", ACT_NAME);//活劢名称
            $wxHongBaoHelper->setParameter("remark",REMARK);//备注信息

            $postXml = $wxHongBaoHelper->create_hongbao_xml();

            $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

            $responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
            $responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
            $return_code=$responseObj->return_code;
            $result_code=$responseObj->result_code;

            if($return_code=='SUCCESS'){
                if($result_code=='SUCCESS'){
                    $total_amount=$responseObj->total_amount*1.0/100;
                    return "红包发放成功！金额为：".$total_amount."元！";
                }else{
                    if($responseObj->err_code=='NOTENOUGH'){
                        //return "您来迟了，红包已经发完！！！";
                        throw new WxHongBaoException('您来迟了，红包已经发完！！！',10401);
                    }else if($responseObj->err_code=='TIME_LIMITED'){
                        //return "现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取";
                        throw new WxHongBaoException('现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取',10402);
                    }else if($responseObj->err_code=='SYSTEMERROR'){
                        //return "系统繁忙，请稍后再试！";
                        throw new WxHongBaoException('系统繁忙，请稍后再试!',10403);
                    }else if($responseObj->err_code=='DAY_OVER_LIMITED'){
                        //return "今日红包已达上限，请明日再试！";
                        throw new WxHongBaoException('今日红包已达上限，请明日再试!',10404);
                    }else if($responseObj->err_code=='SECOND_OVER_LIMITED'){
                        //return "每分钟红包已达上限，请稍后再试！";
                        throw new WxHongBaoException('每分钟红包已达上限，请稍后再试!',10405);
                    }

                    //return "红包发放失败！".$responseObj->return_msg."！请稍后再试！";
                    throw new WxHongBaoException("红包发放失败！".$responseObj->return_msg."！请稍后再试！",10406);
                }
            }else{

                if($responseObj->err_code=='NOTENOUGH'){
                    //return "您来迟了，红包已经发放完！！!";
                    throw new WxHongBaoException('您来迟了，红包已经发完！！！',10401);
                }else if($responseObj->err_code=='TIME_LIMITED'){
                    //return "现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取";
                    throw new WxHongBaoException('现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取',10402);
                }else if($responseObj->err_code=='SYSTEMERROR'){
                    //return "系统繁忙，请稍后再试！";
                    throw new WxHongBaoException('系统繁忙，请稍后再试!',10403);
                }else if($responseObj->err_code=='DAY_OVER_LIMITED'){
                    //return "今日红包已达上限，请明日再试！";
                    throw new WxHongBaoException('今日红包已达上限，请明日再试!',10404);
                }else if($responseObj->err_code=='SECOND_OVER_LIMITED'){
                    //return "每分钟红包已达上限，请稍后再试！";
                    throw new WxHongBaoException('每分钟红包已达上限，请稍后再试!',10405);
                }
                //return "红包发放失败！".$responseObj->return_msg."！请稍后再试！";
                throw new WxHongBaoException("红包发放失败！".$responseObj->return_msg."！请稍后再试！",10406);
            }
        }else{
            //return "很遗憾，您没有抢到红包！感谢您的参与！";
            throw new WxHongBaoException("很遗憾，您没有抢到红包！感谢您的参与！",10407);
        }
    }

    /**
     * 生成二维码
     * @param $coupon_record
     * @return array|bool
     */
    private function makeQrcodeFile($order_num){
        global $_GPC, $_W;
        require (IA_ROOT.'/framework/library/qrcode/phpqrcode.php');
        //判断文件夹是否存在
        load()->func('file');
        if(!file_exists(ATTACHMENT_ROOT.'/lonaking_taskcenter')){
            mkdirs(ATTACHMENT_ROOT.'/lonaking_taskcenter');
        }
        $filename = ATTACHMENT_ROOT.'/lonaking_taskcenter/'.$order_num.'.png';
        //生成二维码
        QRcode::png($order_num,$filename,'L',6,2);
        //$qrcode_url = $_W['attachurl'].$filename;
    }

    /**
     * 获取验证二维码地址
     * @param $order_num
     * @return string
     */
    private function getCheckQrcodeUrl($order_num){
        global $_W;
        return $_W['attachurl'].'/lonaking_taskcenter/'.$order_num.'.png';
    }
}
