<?php
/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/2
 * Time: 下午4:46
 * Exception 5开头
 */
require_once 'CommonService.php';
require 'QrcodeService.php';
require 'Logger.php';
require 'Assert.php';
require 'InviteService.php';
class UserService extends CommonService{

    private $qrcodeService;

    private $inviteService;

    private $logger;
    /**
     * UserService constructor.
     * @param string $table_name
     * @param string $columns
     */
    public function __construct()
    {
        $this->table_name = 'lonaking_supertask_user';
        $this->columns = 'id,acid,uniacid,fanid,openid,pid,agree_duty,qrcode,qrcode_updatetime,scene_id,qrcid,name,follow_times,unfollow_times,second_shareman,score,share_score,createtime,updatetime,share_times';
        $this->qrcodeService = new QrcodeService();
        $this->logger = new Logger();
        $this->inviteService = new InviteService();
    }


    /**
     * 更新user积分
     * @param $score 积分增长或减少值
     * @param $user_or_id 被更新的用户id 或者 user对象
     */
    public function updateUserScore($score, $user_or_id,$config){
        $user = $user_or_id;
        if(!is_array($user_or_id) || empty($user_or_id['id'])){
            // 若传入的参数不是数组 则查询一次
            $user = $this->selectUserById($user_or_id);
        }
        //1. 更新猪八戒积分
        $sql = "UPDATE ".tablename($this->table_name). " SET score=score+{$score} WHERE id = {$user['id']}";
        if($score < 0){
            $score = $score * -1;
            $sql = "UPDATE ".tablename($this->table_name). " SET score=score-{$score} WHERE id =  {$user['id']}";
            $score = $score * -1;
        }
        pdo_query($sql);
        //2 判断是否同步微擎积分
        if($config['score_we7']){//
            load()->model('mc');
            $uid = mc_openid2uid($user['openid']);
            //'credit1','credit2'  1=> 积分 2=>金额
            mc_credit_update($uid,'credit1',$score);
        }
    }

    /**
     * 奖励引导关注积分 当用户关注之后就会记录此积分
     * @param $user_or_id
     * @param $config
     * @throws Exception
     */
    public function chargeFollowScore($user_or_id, $config){
        $user = $user_or_id;
        if(!is_array($user_or_id) || empty($user_or_id['id'])){
            // 若传入的参数不是数组 则查询一次
            $user = $this->selectUserById($user_or_id);
        }
        $follow_score = $config['follow_score'];
        pdo_query("UPDATE ".tablename($this->table_name). " SET follow_times = follow_times+1,score = score+". $config['follow_score'] ." WHERE id = :id",array(':id'=>$user['id']));
        //2 判断是否同步微擎积分
        if($config['score_we7']){//
            load()->model('mc');
            $uid = mc_openid2uid($user['openid']);
            //'credit1','credit2'  1=> 积分 2=>金额
            mc_credit_update($uid,'credit1',$config['follow_score']);
        }
    }

    /**
     * 取消关注 TODO
     * @param $user_or_id
     * @param $config
     * @throws Exception
     */
    public function reduceUnfollowScore($user_or_id,$config){
        $user = $user_or_id;
        if(!is_array($user_or_id) || empty($user_or_id['id'])){
            // 若传入的参数不是数组 则查询一次
            $user = $this->selectUserById($user_or_id);
        }
        $invite_score = $config['second_shareman_score'];

    }

    /**
     * 更新某个人的推广人邀请积分 本方法会同时更新邀请次数和邀请积分 当用户成为推广人的时候才记此积分
     * @param $user_or_id 用户或者用户的id
     * @throws Exception
     */
    public function updateInviteScore($user_or_id,$config){
        $user = $user_or_id;
        if(!is_array($user_or_id) || empty($user_or_id['id'])){
            // 若传入的参数不是数组 则查询一次
            $user = $this->selectUserById($user_or_id);
        }
        $invite_score = $config['second_shareman_score'];
        pdo_query("UPDATE ".tablename($this->table_name). " SET second_shareman = second_shareman+1,score = score+". $invite_score ." WHERE id = :id",array(':id'=>$user['id']) );
        //2 判断是否同步微擎积分
        if($config['score_we7']){//
            load()->model('mc');
            $uid = mc_openid2uid($user['openid']);
            //'credit1','credit2'  1=> 积分 2=>金额
            mc_credit_update($uid,'credit1',$invite_score);
        }
    }

    /**
     * 获取推广排名列表 返回的数据中 current 为当前用户
     * @param string $openid
     * @param null $uniacid
     * @param int $limit
     * @return array 该数组中 包含user和users
     */
    public function fetchUserListSortByScore($openid = '',$uniacid = null, $limit = 10){
        global $_W;
        $openid = ( empty($openid) || is_null($openid) ) ? $_W['openid'] : $openid;
        $uniacid = (empty($uniacid) || is_null($uniacid))? $_W['uniacid'] : $uniacid;
        $limit = $limit < 10 ? 10 : $limit;
        //查询当前的用户信息
        $user = null;
        if(!empty($openid)){
            $user = $this->selectByOpenid($openid, $uniacid);
        }
        //获取排名信息
        $users = pdo_fetchall( "SELECT ".$this->columns." FROM ".tablename($this->table_name)." WHERE uniacid = :uniacid ORDER BY score DESC LIMIT 0,{$limit}",array(":uniacid"=>$uniacid));
        $sort_users = array();
        $result = array();
        for ($i = 0; $i<sizeof($users);$i++){
            $tmp_user = $users[$i];
            $tmp_user['sort'] = $i+1;
            $sort_users[$i] = $tmp_user;
            if($tmp_user['id'] == $user['id']){
                $user['sort'] = $i+1;
            }
        }
        // 若用户不在前十名 则排名未知
        if(empty($user['sort']) ){
            $user['sort'] = '未知';
        }
        $result['user'] = $user;
        $result['users'] = $sort_users;
        return $result;
    }






    /////////////////////////////////基础的增删改查////////////////////////////////////////
    /**
     * 新建一个用户
     * @param $user
     * @return mixed
     * @Exception 54开头
     */
    public function createUser($user,$config){
        //检查数据正确性
        Assert::not_empty($user['openid'],"您没有关注本微信公众平台",5411);
        Assert::not_empty($user['acid']);
        Assert::not_empty($user['uniacid']);
        //1. 检查同一个openid的用户是否存在
        $exist_user = $this->selectByOpenid($user['openid']);
        if($exist_user){
            $this->logger->log("用户".$user['name']."已经存在了，插入失败");
            throw new Exception("注册失败,用户已经存在",5410);
        }
        try{
            //2. 生成用户对应的二维码
            $qrcode = $this->qrcodeService->generateQrcode();
            $user['qrcode'] = $qrcode['qrcode'];
            $user['qrcode_updatetime'] = $qrcode['qrcode_updatetime'];
            $user['scene_id'] = $qrcode['scene_id'];
        }catch (Exception $e){
            //没有权限生成二维码
            $this->logger->log("该微信公众平台没有权限生成二维码");
            $user['qrcode'] = "";
        }
        // 3.准备其他的注册数据
        $user['createtime'] = time();
        $user['updatetime'] = time();
        $user['agree_duty'] = 1;
        $user['follow_times'] = 0;
        $user['unfollow_times'] = 0;
        $user['score'] = 0;
        $user['share_score'] = 0;
        $user['share_times'] = 0;
        //4.检验是否有邀请人
        try{
            $this->logger->log("开始检测是否有邀请人");
            $invite_info = $this->inviteService->selectInviteInfoByOpenid($user['openid']);
            $this->logger->log("查询到邀请记录:".json_encode($invite_info));
            $invite_user = $this->selectById($invite_info['invite_id']);
            $this->selectUserById($invite_info['invite_id']);
            $this->logger->log("检查是否有邀请人结束，检查结果,邀请人信息:".json_encode($invite_user));
            $user['pid'] = $invite_info['invite_id'];
            //4.1更新邀请人积分 TODO
            $this->updateInviteScore($invite_info['invite_id'],$config);
        }catch (Exception $e){
            $this->logger->log("该用户没有邀请人,用户openid".$user['openid'].",异常描述:".$e->getMessage());
        }
        //4. 插入数据
        $this->logger->log("开始插入数据".json_encode($user));
        return $this->insertData($user);
    }


    /**
     * 根据id删除用户
     * @param $id
     */
    public function deleteUserById($id){
        try{
            $this->deleteById($id);
        }catch (Exception $e){
            throw new Exception('用户不存在',5402);
        }
    }

    /**
     * 根据user的id查询user
     * @param $id
     * @param null $uniacid 可以为空
     * @return bool
     */
    public function selectUserById($id){
        global $_W;
        $sql = null;
        $select_param = array(
            ':id' => $id
        );
        $sql = "SELECT ".$this->columns." FROM ".tablename($this->table_name)." WHERE id =:id";
        $invite_user = pdo_fetch($sql,$select_param);
        //若平台无生成二维码的权限
        if(empty($invite_user['qrcode'])){
            $user['qrcode'] = $_W['account']['qrcode'];
        }

        if(empty($invite_user) || is_null($invite_user)){
            throw new Exception("分享人没有找到",5401);
        }
        return $invite_user;
    }

    /**
     * 根据openid获取用户信息
     * @param $openid
     * @param $uniacid
     * @return bool
     */
    private function selectByOpenid($openid = null, $uniacid = null){
        global $_W;
        $select_param = array(
            ':openid' => empty($openid) ? $_W['openid'] : $openid,
            ':uniacid' => empty($uniacid) ? $_W['uniacid'] : $uniacid
        );
        $sql = "SELECT ".$this->columns." FROM ".tablename($this->table_name)." WHERE openid =:openid AND uniacid=:uniacid";
        $invite_user = pdo_fetch($sql,$select_param);

        if(empty($invite_user['qrcode']) && !empty($invite_user)){
            $invite_user['qrcode'] = $_W['account']['qrcode'];
        }
        return $invite_user;
    }


    /**
     * 根据fensiid获取用户信息
     * @param $fanid
     * @param $uniacid
     * @return bool
     */
    public function selectByFanid($fanid = null, $uniacid = null){
        global $_W;
        $select_param = array(
            ':fanid' => is_null($fanid) ? $_W['fans']['fanid'] : $fanid,
            ':uniacid' => is_null($uniacid) ? $_W['uniacid'] : $uniacid
        );
        $sql = "SELECT ".$this->columns." FROM ".tablename($this->table_name)." WHERE fanid =:fanid AND uniacid =:uniacid";
        $invite_user = pdo_fetch($sql,$select_param);
        if(empty($invite_user['qrcode'])){
            $user['qrcode'] = $_W['account']['qrcode'];
        }
        return $invite_user;
    }

    /**
     * 根据openid 或者fanid查询用户信息 带用户的微信信息
     * @param $openidOrFanid
     * @param $config
     * @return bool
     */
    public function selectUserByOpenidOrFanidAndFansInfo($openidOrFanid,$config){
        return $this->selectUserByOpendiOrFanid($openidOrFanid,$config,true);
    }
    /**
     * 根据openid 或者fanid查询用户信息
     */
    public function selectUserByOpendiOrFanid($openidOrFanid,$config,$fans_info = false){
        global $_W;
        $user = null;
        if(is_numeric($openidOrFanid)){
            $user = $this->selectByFanid($openidOrFanid);
        }else{
            $user = $this->selectByOpenid($openidOrFanid);
        }
        if($fans_info){
            load()->model('mc');
            $fans_info = mc_fansinfo($user['openid']);
            $user['fans_info'] = $fans_info;
        }
        if($config['score_we7']){//
            load()->model('mc');
            $uid = mc_openid2uid($user['openid']);
            //'credit1','credit2'  1=> 积分 2=>金额
            $credit_we7 = mc_credit_fetch($uid);
            $user['score'] = $credit_we7['credit1'];
            $this->log('查询出来用户的积分为'.$user['score']);
            $user['money'] = $credit_we7['credit2'];

        }else{
            $this->log('未从微擎积分中获取用户数据');
            $user['money'] = 0;
        }
        $this->log($user);
        $this->log('获取用户信息完成');
        return $user;
    }

}