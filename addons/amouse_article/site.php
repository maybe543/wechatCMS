<?php
/**
 * 微官网模块微站定义
 *
 * @author 史中营 qq:800083075
 * @url http://w.mamani.cn
 */
defined('IN_IA') or exit('Access Denied');
include_once IA_ROOT . '/addons/amouse_article/model.php';
define("AMOUSE_ARTICLE", "amouse_article");
define("RES", "../addons/".AMOUSE_ARTICLE."/style/");
require_once IA_ROOT."/addons/amouse_article/WxPayPubHelper/WxPayPubHelper.php";

function get_timelineauction($pubtime){
    $time=time();
    /** 如果不是同一年 */
    if(idate('Y', $time) != idate('Y', $pubtime)){
        return date('Y-m-d', $pubtime);
    }
    /** 以下操作同一年的日期 */
    $seconds=$time-$pubtime;
    $days=idate('z', $time)-idate('z', $pubtime);
    /** 如果是同一天 */
    if($days == 0){
        /** 如果是一小时内 */
        if($seconds < 3600){
            /** 如果是一分钟内 */
            if($seconds < 60){
                if(3 > $seconds){
                    return '刚刚';
                } else {
                    return $seconds.'秒前';
                }
            }
            return intval($seconds / 60).'分钟前';
        }
        return idate('H', $time)-idate('H', $pubtime).'小时前';
    }
    /** 如果是昨天 */
    if($days == 1){
        return '昨天 '.date('H:i', $pubtime);
    }
    /** 如果是前天 */
    if($days == 2){
        return '前天 '.date('H:i', $pubtime);
    }
    /** 如果是7天内 */
    if($days < 7){
        return $days.'天前';
    }
    /** 超过7天 */
    return date('n-j H:i', $pubtime);
}

class Amouse_articleModuleSite extends WeModuleSite {


    public function __app($f_name){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $set = $this->getSysett($weid);
        include_once 'inc/app/'.strtolower(substr($f_name, 8)).'.inc.php';
    }


    public function doMobileIndex() {
        $weid=$_W['uniacid'];
        $this->__app(__FUNCTION__);
    }

    public function doMobileSecond() {
        global $_GPC, $_W;
        $weid=$_W['uniacid'];
        $this->__app(__FUNCTION__);
    }


    public function doMobileDetail() {
        $this->__app(__FUNCTION__);
    }

    public function doMobileJubao() {
        include $this->template('jubao');
    }

    //评论
    public function doMobileComment() {
        $this->__app(__FUNCTION__);
    }
    //赞赏
    public function doMobileAdmire() {
        $this->__app(__FUNCTION__);
    }


    public  function doMobileLike(){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $record_id=$_GPC['articleid'];
        $like =$_GPC['like"'];
        //  $like = $like==0 ? -1 : 1 ;
        $record=pdo_fetch("SELECT * FROM ".tablename('fineness_article')." WHERE id= $record_id ");
        if(empty($record)){
            $res['ret']=501;
            return json_encode($res);
        }
        if(pdo_update('fineness_article',array('zanNum'=>$record['zanNum']+1), array('id'=>$record_id))){
            $res['ret']=0;
            return json_encode($res);
        }
    }

    //评价
    public  function doMobileAjaxcomment(){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $aid=$_GPC['articleid'];
        $set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' => $weid));
        $follow_url = $set['guanzhuUrl'];
        $is_follow = false;
        $record=pdo_fetch("SELECT * FROM ".tablename('fineness_article')." WHERE id= $aid ");
        if(empty($record)){
            $res['code']=501;
            $res['msg']="文章不存在或者已经被删除。";
            return json_encode($res);
        }
        load()->model('mc');
        $userInfo = mc_oauth_userinfo();
        if (empty($userInfo) && empty($userInfo['nickname'])) {//已关注过
            $res['code']=202;
            $res['msg']="您还没有关注，请关注后参与。";
            return json_encode($res);
        }
        if($set && $set['iscomment']==1) {//审核
            $status=1;
        }
        $data = array(
            'weid' => $weid,
            'js_cmt_input' => $_GPC['js_cmt_input'],
            'status' =>0,
            'aid' => $aid,
            'author' => $userInfo['nickname'],
            'thumb' => $userInfo['headimgurl'],
            'openid' => $userInfo['openid'],
            'createtime' => time()
        );
        pdo_insert('fineness_comment', $data);
        $res['code']=200;
        $res['msg']="评论成功，由公众帐号筛选后显示！";
        return json_encode($res);
    }
    //删除评价
    public  function doMobileDelComment(){
        global $_W, $_GPC;
        $commentid=$_GPC['commentid'];
        $record=pdo_fetch("SELECT * FROM ".tablename('fineness_comment')." WHERE id= $commentid ");
        if(empty($record)){
            $res['code']=501;
            $res['msg']="记录不存在或者已经被删除。";
            return json_encode($res);
        }
        $temp= pdo_delete("fineness_comment", array('id' => $commentid));
        $res['code']=200;
        $res['msg']='删除成功';
        return json_encode($res);
    }

    public  function doMobileAjaxpraise(){
        global $_W, $_GPC;
        $commentid=$_GPC['commentid'];
        $record=pdo_fetch("SELECT * FROM ".tablename('fineness_comment')." WHERE id= $commentid ");
        if(empty($record)){
            $res['code']=501;
            $res['msg']="记录不存在或者已经被删除。";
            return json_encode($res);
        }
        $temp= pdo_update("fineness_comment",array('praise_num'=>$record['praise_num']+1), array('id' => $commentid));
        $res['code']=200;
        return json_encode($res);
    }
//
    public function doMobileAjaxPay() {
        global $_W, $_GPC ;
        $price =  $_GPC['price'];
        if($price==0){
            $price=0.01;
        }
        $uniacid=$_W['uniacid'];
        $set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' => $uniacid));
        if (empty($set)) {

            $res['code']=501;
            $res['msg']="抱歉，基本参数没设置";
            return json_encode($res);
        }
        load()->model('mc');
        $userInfo = mc_oauth_userinfo();

        $jsApi = new JsApi_pub($set);

        $jsApi->setOpenId($userInfo['openid']);

        $unifiedOrder = new UnifiedOrder_pub($set);
        $unifiedOrder->setParameter("openid",$userInfo['openid']);//商品描述
        $unifiedOrder->setParameter("body", "赞赏");//商品描述

        $timeStamp = time();
        $out_trade_no = $set['appid']."$timeStamp";
        $unifiedOrder->setParameter("out_trade_no", $out_trade_no);//商户订单号
        $unifiedOrder->setParameter("total_fee", $price*100);//总金额
        $notifyUrl = $_W['siteroot'] . "addons/" . AMOUSE_ARTICLE . "/notify.php";
        $unifiedOrder->setParameter("notify_url", $notifyUrl);//通知地址
        $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
        $prepay_id = $unifiedOrder->getPrepayId();
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();
        $res['code']=200;
        $res['msg']=$jsApiParameters;
        return json_encode($res);
    }

    public function doMobilePaySuccess() {
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $price=$_GPC['price'];
        $aid=$_GPC['aid'];
        $article=pdo_fetch('select * from '.tablename('fineness_article').' where weid=:weid AND id=:id',array(':weid'=>$uniacid,':id'=>$aid));
        load()->model('mc');
        $userInfo = mc_oauth_userinfo();
        if (empty($userInfo) && empty($userInfo['nickname'])) {//已关注过
            $res['code']=202;
            $res['msg']="您还没有关注，请关注后参与。";
            return json_encode($res);
        }
        load()->func('logging');

        if(!empty($article)) {
            $data = array(
                'weid' => $uniacid,
                'price' =>$price,
                'aid' => $aid,
                'author' => $userInfo['nickname'],
                'thumb' => $userInfo['avatar'],
                'openid' => $userInfo['openid'],
                'createtime' => time()
            );
            pdo_insert('fineness_admire', $data);
            // $this->sendOrderSuccessTplMsg($oid,$meal['title']);
        }
        $res['code']=200;
        $res['msg']='sucess';
        return json_encode($res);
    }

    //一键关注
    public function doMobileTuijian() {
        global $_GPC, $_W;
        $weid=$_W['uniacid'];
        $cfg = $this->module['config'];
        $list = pdo_fetchall("SELECT * FROM ".tablename('wx_tuijian')." WHERE weid=:weid ORDER BY createtime DESC ", array(':weid' => $weid)) ;
        include $this->template('tuijian');
    }

    //后台程序 inc/web文件夹下
    public function __web($f_name){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        include_once 'inc/web/'.strtolower(substr($f_name, 5)).'.inc.php';
    }

    //分类关联
    public function doWebCategory() {
        $this->__web(__FUNCTION__);
    }

    //文章关联
    public function doWebPaper() {
        $this->__web(__FUNCTION__);
    }

    public function doWebComment() {
        $this->__web(__FUNCTION__);
    }

    //系统设置
    public function doWebSysset() {
        $this->__web(__FUNCTION__);
    }

    //一键关注设置
    public function doWebHutui() {
        $this->__web(__FUNCTION__);
    }

    //幻灯片管理
    public function doWebSlide(){
        $this->__web(__FUNCTION__);
    }

    public function getSysett($weid){
        return pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1",array(':weid'=>$weid));
    }
    //广告管理
    public function doWebAdv() {
        $this->__web(__FUNCTION__);
    }

    public function doWebjiaocheng() {
        include $this->template('help');
    }


}
