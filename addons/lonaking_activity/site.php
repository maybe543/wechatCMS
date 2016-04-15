<?php
/**
 * 活动管理平台模块微站定义
 *
 * @author lonaking
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

require_once dirname(__FILE__).'/class/activity/LCActivityService.php';
require_once dirname(__FILE__).'/class/enroll/LCEnrollService.php';
require_once dirname(__FILE__).'/../lonaking_flash/FlashUserService.php';
class Lonaking_activityModuleSite extends WeModuleSite {

    private $activityService;

    private $enrollService;

    private $flashUserService;

    public function __construct()
    {
        $this->activityService = new LCActivityService();
        $this->enrollService = new LCEnrollService();
        $this->flashUserService = new FlashUserService();
    }

    public function doWebActivityManage() {
        global $_GPC;
        $activityList = $this->activityService->selectAll();
        $d = $activity = $activityList['0'];//only one
        include $this->template('activity_list');
    }

    public function doWebActivityEdit(){
        global $_W,$_GPC;
        checkaccount();//
        $id = $_GPC['id'];
        $data = $_GPC['data'];
        $data['uniacid'] = $_W['uniacid'];
        if (!empty($_GPC['submit'])) {//提交表单
            if(empty($data['id'])){
                //check
                $activityList = $this->activityService->selectAll();
                if($activityList){
                    return message("您已经添加了一个活动",$this->createWebUrl("ActivityManage"),"error");
                }
                $data['create_time'] = time();
            }
            $data['content'] = htmlspecialchars_decode($data['content']);
            $data['update_time'] = time();
            try{
                $this->activityService->insertOrUpdate($data);
                return message("保存成功", $this->createWebUrl("ActivityManage"), "success");
            }catch (Exception $e){
                return message("保存失败", "", "error");
            }
        }else{
            if(!is_null($id)){
                $data = $this->activityService->selectById($id);
                $data['content'] = htmlspecialchars_decode($data['content']);
            }
            load()->func('tpl');
            include $this->template('activity_edit');
        }
    }

	public function doWebActivityRecord() {
        global $_GPC;
        $activityId = $_GPC['activity_id'];
        $activitylist = $this->activityService->selectAll();

        if(empty($activitylist)){
            return message("请创建活动后从活动管理中进入",$this->createWebUrl("ActivityManage"),"error");
        }

        $activityId = $activitylist[0]['id'];
        $page = $this->enrollService->selectPageOrderBy("AND activity_id={$activityId}","create_time DESC,");
        include $this->template("enroll_record_list");
	}

    public function doMobileIndex() {
        global $_W,$_GPC;
        $activityList = $this->activityService->selectAll();
        $user = $this->flashUserService->authFansInfo();
        if($activityList == null || sizeof($activityList) == 0){
            return message("没有进行中的活动","","error");
        }
        $activity = $activityList[0];
        //是否报名
        $enrollRecord = $this->enrollService->selectOne("AND activity_id={$activity['id']} AND openid='{$_W['openid']}'");
        $enrollRecordList = $this->enrollService->selectAll("AND activity_id={$activity['id']}");
        $this->activityService->columnAddCount('click',1,$activity['id']);//浏览次数
        if(!empty($enrollRecord)){
            $enrollRecord['qrcode'] = $_W['siteroot']."attachment/lonaking_activity/".$enrollRecord['order_num'].".png";
        }
        $html = array(
            'activity' => $activity,
            'enroll_status' => empty($enrollRecord) ? false : true,
            'enroll_record' => $enrollRecord,
            'enroll_list' => $enrollRecordList,
            'config' => $this->module['config'],
            'jsconfig' => $_W['account']['jssdkconfig'],
        );
        $urls = array(
            'enroll_list_api' => $this->createMobileUrl("EnrollRecordApi"),
            'enroll_api' => $this->createMobileUrl("EnrollApi",array('activity_id'=> $activity['id'])),
            'share_api' => $this->createMobileUrl("ShareCallback",array('activity_id'=> $activity['id']))
        );
        $share = array(
            'share_title' =>$activity['share_title'],
            'share_logo' => tomedia($activity['share_logo']),
            'share_url' => $_W['siteroot'].'app'.substr($this->createMobileUrl('Index'),1),
            'share_description' => $activity['share_description']
        );
        return include $this->template("index");
    }

    public function doMobileShareCallback(){
        global $_GPC,$_W;
        $activityId = $_GPC['activity_id'];
        try{
            $activity = $this->activityService->selectById($activityId);
            $this->activityService->updateColumn("share",1,$activityId);
            return $this->return_json(200,'分享成功',null);
        }catch (Exception $e){
            return $this->return_json(400,'活动不存在',null);
        }
    }
    public function doMobileEnrollRecordApi(){
        global $_W,$_GPC;
        $activityId = $_GPC['activity_id'];
        try{
            $activity = $this->activityService->selectById($activityId);
            $page = $this->enrollService->selectPageOrderBy("AND activity_id={$activityId}","create_time Desc,");
            $html = array(
                'enroll_list' => $page['data']
            );
            $page['pager'] = null;
            return $this->return_json(200,'success',$page);
        }catch (Exception $e){
            return $this->return_json(400,'活动不存在',null);
        }

    }
    public function doMobileEnrollApi(){
        global $_W,$_GPC;
        $openId = $_W['openid'];

        if(empty($openId)){
            return $this->return_json(400,"请在微信浏览器中操作",null);
        }
        $activityId = $_GPC['activity_id'];
        $name = $_GPC['name'];
        $mobile = $_GPC['mobile'];
        if(empty($name) || empty($mobile)){
            return $this->return_json(400,"姓名和手机均不能为空",null);
        }

        if(!preg_match("/^1[34578]\d{9}$/", $mobile)){
            return $this->return_json(400,"请输入正确的手机号码");
        }

        $enrollRecord = $this->enrollService->selectOne("AND activity_id={$activityId} AND openid='{$openId}'");
        if(!empty($enrollRecord)){
            return $this->return_json(400,"您已经参加了此活动，无需再次报名",null);
        }
        try{
            $activity = $this->activityService->selectById($activityId);
            if($activity['enroll_count'] >= $activity['enroll_limit']){
                return $this->return_json(400,"活动报名人数已满,谢谢您的支持",null);
            }
        }catch (Exception $e){
            return $this->return_json(400,'活动不存在',null);
        }
        $user = $this->flashUserService->fetchFansInfo($openId);
        $enroll = array(
            'uniacid' => $_W['uniacid'],
            'activity_id' => $activityId,
            'order_num' => $activityId.time().rand(0,999),
            'openid' => $openId,
            'uid' => $user['uid'],
            'pic' => $user['tag']['avatar'],
            'name' => $name,
            'mobile' => $mobile,
            'status' => 0,//0
            'create_time' => time(),
            'update_time' =>time(),
        );
        $enroll = $this->enrollService->insertData($enroll);
        $enroll['qrcode'] = $_W['siteroot']."attachment/lonaking_activity/".$enroll['order_num'].".png";
        $this->makeQrcodeFile($enroll['order_num']);
        $this->activityService->columnAddCount('enroll_count',1,$activityId);
        return $this->return_json(200,"报名成功",$enroll);
    }

    /**
     * 核销接口
     */
    public function doMobileCheck(){
        global $_GPC;
        $appid = $_GPC['appid'];
        $secret = $_GPC['secret'];
        $code = $_GPC['code'];
        $time = $_GPC['time'];
        $sign = $_GPC['sign'];
        //
        if(empty($appid)){
            return $this->returnCheckResult(401,'核销机具appid不能为空',null);
        }
        if($appid != $this->module['config']['appid']){
            return $this->returnCheckResult(404,'核销机具appid出错',null);
        }
        if(empty($secret)){
            return $this->returnCheckResult(402,'核销机具secret不能为空',null);
        }
        if($secret != $this->module['config']['secret']){
            return $this->returnCheckResult(405,'核销机具secret出错',null);
        }
        if(empty($time)){

        }
        if(empty($sign)){

        }else{
            if(!$this->checkSign($sign)){

            }
        }

        if(empty($code)){
            return $this->return_json(403,'二维码内容不能为空',null);
        }
        $enrollRecord = null;
        $activity = null;
        try{
            $code = $this->handleCode($code);
            $enrollRecord = $this->checkOneCode($code);
            $activity = $this->activityService->selectById($enrollRecord['activity_id']);
            //准备返回参数
            $meetingResult = array(
                'title' => $activity['name'],
                'content' => $activity['name'],
                'paidAmount' => 0,
                'extraAmount' => 0,
                'times' => 1,
                'people' => 1,
                'hotel' => "无",
                'meetingStartTime' => $activity['start'],
                'meetingEndTime' => $activity['end'],
                'enrollTime' => date("Y-m-d H:i:s",$enrollRecord['create_time']),
                'expireTime' => '',
                'invalid' => $enrollRecord['status'] == 0?0:1,
                'resource' => $this->module['config']['platform_name']
            );
            return $this->returnCheckResult(200,'签到成功',$meetingResult);
        }catch (Exception $e){
            $code = $this->handleCode($code);
            if(is_null($enrollRecord)){
                $enrollRecord = $this->enrollService->selectByOrderNum($code);
            }
            if(is_null($activity)){
                $activity = $this->activityService->selectById($enrollRecord['activity_id']);
            }
            $meetingResult = array(
                'title' => $activity['name'],
                'content' => $activity['name'],
                'paidAmount' => 0,
                'extraAmount' => 0,
                'times' => 1,
                'people' => 1,
                'hotel' => "无",
                'meetingStartTime' => $activity['start'],
                'meetingEndTime' => $activity['end'],
                'enrollTime' => date("Y-m-d H:i:s",$enrollRecord['create_time']),
                'expireTime' => null,
                'invalid' => $enrollRecord['status'] == 0?0:1,
                'resource' => $this->module['config']['platform_name']
            );
            return $this->returnCheckResult($e->getCode(),$e->getMessage(),$meetingResult);
        }
    }
    private function handleCode($code){
        //判断是否有:
        $index = stripos($code,":");
        if($index == false){
            return $code;
        }

        $prefix = $this->module['config']['prefix'];
        $subResult = explode(":",$code);

        if($subResult[0] != $prefix){
            throw new Exception("不支持的二维码格式",410);
        }else{
            return $subResult['1'];
        }
    }


    private function checkOneCode($code){
        //去掉前缀
        $code = str_replace($this->module['config']['prefix'].":", '',$code);
        // 从数据库中取出
        $enrollRecord = $this->enrollService->selectByOrderNum($code);

        if(empty($enrollRecord)){
            throw new Exception("不存在该报名记录",408);
        }else{//存在
            if($enrollRecord['status'] == 1){
                //throw new Exception("");
                throw new Exception("失败:该码已在[".date('Y年m月d日 H:i',$enrollRecord['verificate_time'])."]核销",409);
            }
            $enrollRecord['status'] = 1;
            $enrollRecord['verificate_time'] = time();
            $enrollRecord['update_time'] = time();
            $this->enrollService->updateData($enrollRecord);
            return $enrollRecord;
        }

    }
    /**
     * 校验
     * @param $sign
     * @return bool
     */
    private function checkSign($sign){
        return true;
    }

    /**
     * @param int $status
     * @param string $message
     * @param int $type
     * @param null $data
     */
    private function returnCheckResult($status = 200,$message = 'success',$data = null){
        exit(json_encode(
            array(
                'code' => $status,
                'msg' => $message,
                'type' => 2,//
                'data' => $data
            )
        )
        );
    }
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
     * 生成二维码
     * @param $coupon_record
     * @return array|bool
     */
    private function makeQrcodeFile($order_num){
        global $_GPC, $_W;
        $prefix = $this->module['config']['prefix'];
        require (IA_ROOT.'/framework/library/qrcode/phpqrcode.php');
        //判断文件夹是否存在
        load()->func('file');
        if(!file_exists(ATTACHMENT_ROOT.'/lonaking_activity')){
            mkdirs(ATTACHMENT_ROOT.'/lonaking_activity');
        }
        $filename = ATTACHMENT_ROOT.'/lonaking_activity/'.$order_num.'.png';
        //生成二维码
        QRcode::png($prefix.":".$order_num,$filename,'L',6,2);
        //$qrcode_url = $_W['attachurl'].$filename;
    }

    /**
     * 获取验证二维码地址
     * @param $order_num
     * @return string
     */
    private function getCheckQrcodeUrl($order_num){
        global $_W;
        return $_W['attachurl'].'/lonaking_activity/'.$order_num.'.png';
    }
}