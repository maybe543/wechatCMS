<?php
/**
 * @author codeMonkey
 * qq:631872807
 */
defined('IN_IA') or exit('Access Denied');
define("YYZF_MODULENAME", "mon_yyzf");
define("YYZF_RES", "../addons/" . YYZF_MODULENAME . "/template/");
require_once IA_ROOT . "/addons/" . YYZF_MODULENAME . "/CRUD.class.php";
require IA_ROOT . "/addons/" . YYZF_MODULENAME . "/oauth2.class.php";


class Mon_yyzfModuleSite extends WeModuleSite
{



    public $weid;
    public $oauth;
    public function __construct() {
        global $_W;
        $this->weid = IMS_VERSION<0.6?$_W['weid']:$_W['uniacid'];

        $this->oauth = new Oauth2("", "");

    }


    /**
     * author: codeMonkey QQ:631872807
     * 祝福
     */
    public function  doMobileZf(){

        global $_W,$_GPC;

        $is_follow = false;;
        $yid = $_GPC['yid'];

        $yy=CRUD::findById(CRUD::$table_mon_yyzf,$yid);
        if(empty($yy)){
            message("语音祝福删除或不存在!");
        }

        $openid = $_W['fans']['from_user'];
        if (empty($openid)) {
            $openid = $_GPC['openid'];
        }




        if (!empty($openid)) {
            $userInfo = $this->setClientUserInfo($openid);

        }
        if (empty($userInfo)) {
            $userInfo = $this->getClientUserInfo();// 从cookie中取

        }

        if (!empty($userInfo)&&!empty($userInfo['nickname'])) {
            $is_follow = true;
        }

        if(!$is_follow){//没有关注。。。。。
            $follow_url=$yy['follow_url'];
            header ( "location: $follow_url" );
        }


        include $this->template("zf");

    }


    /**
     * author: codeMonkey QQ:631872807
     * 记录
     */
    public function  doMobileZFRecord(){

        global $_GPC,$_W;

        $yid=$_GPC['yid'];

        $yy=CRUD::findById(CRUD::$table_mon_yyzf,$yid);

        $res=array();
        if(empty($yy)){
            $res['code']=500;
            $res['msg']='语音祝福删除或不存在';
            echo json_encode($res);
            exit;

        }
        $userInfo=$this->getClientUserInfo();

        if(empty($userInfo)){
            $res['code']=501;
            $res['msg']='请关注微信公众账号';
            echo json_encode($res);
            exit;
        }

        $serverId=$_GPC['serverId'];

        if(empty($serverId)){
            $res['code']=502;
            $res['msg']='语音记录不能为空';
            echo json_encode($res);
            exit;
        }


        $data=array(
            'yid'=>$yid,
            'openid'=>$userInfo['openid'],
            'nickname'=>$userInfo['nickname'],
            'headimgurl'=>$userInfo['headimgurl'],
            'wish'=>'',
            'serverId'=>$serverId,
            'createtime'=>TIMESTAMP


        );


        CRUD::create(CRUD::$table_mon_yyzf_record,$data);
        $yrid=pdo_insertid();

        $res['code']=200;
        $res['yrid']=$yrid;

        $res['url']=$_W['siteroot'].'app'.str_replace('./','/',$this->createMobileUrl('Play',array('yrid'=>$yrid),true));

        echo json_encode($res);



    }

    /**
     * author: codeMonkey QQ:631872807
     * 播放页面

     */
    public function  doMobilePlay(){
        global $_GPC,$_W;

        $yrid=$_GPC['yrid'];

        $zr=CRUD::findById(CRUD::$table_mon_yyzf_record,$yrid);
        if(empty($zr)){
            message("祝福语音信息删除或不存在");
        }
        $yy=CRUD::findById(CRUD::$table_mon_yyzf,$zr['yid']);

        if(empty($yy)){
            message("语音祝福删除或不存在！");
        }

        include $this->template("play");

    }




    /**
     * author: codeMonkey QQ:631872807
     * 祝福管理
     */
    public function  doWebZf(){

        global $_W,$_GPC;

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';


        if ($operation == 'display') {

            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("SELECT * FROM " . tablename(CRUD::$table_mon_yyzf) . " WHERE weid =:weid  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $this->weid));
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_mon_yyzf) . " WHERE weid =:weid ", array(':weid' => $this->weid));
            $pager = pagination($total, $pindex, $psize);

        } else if ($operation == 'delete') {


            $id = $_GPC['id'];

            pdo_delete(CRUD::$table_mon_yyzf_record, array("yid" => $id));
            pdo_delete(CRUD::$table_mon_yyzf, array('id' => $id));

            message('删除成功！', referer(), 'success');
        }

        include $this->template("yy_manage");

    }




    public function  doWebZR()
    {

        global $_GPC,$_W;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $yid = $_GPC['yid'];
        $keywords=$_GPC['keywords'];

        if(!empty($keywords)){

            $where="and nickname like '%".$keywords."%'";
        }

        if ($operation == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("SELECT * FROM " . tablename(CRUD::$table_mon_yyzf_record) . " WHERE yid =:yid ".$where." ORDER BY  id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':yid' => $yid));
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_mon_yyzf_record) . " WHERE yid =:yid ".$where, array(':yid' => $yid));
            $pager = pagination($total, $pindex, $psize);

        } else if ($operation == 'delete') {

            $id = $_GPC['id'];
            pdo_delete(CRUD::$table_mon_yyzf_record, array('id' => $id));


        }


        include $this->template("yy_user");


    }












    /**
     * author: codeMonkey QQ:631872807
     * @param $openid
     */
    public function setClientUserInfo($openid)
    {

        if (!empty($openid)) {


            load()->model('account');
            $token = WeAccount::token(WeAccount::TYPE_WEIXIN);

            if (empty($token)) {
                message("获取accessToken失败");
            }
            $userInfo = $this->oauth->getUserInfo($token, $openid);
            if (!empty($userInfo)) {
                $cookie = array();
                $cookie['openid'] = $userInfo['openid'];
                $cookie['nickname'] = $userInfo['nickname'];
                $cookie['headimgurl'] = $userInfo['headimgurl'];
                $session = base64_encode(json_encode($cookie));
                isetcookie('__yyzfuser', $session, 24 * 3600 * 365);

            }

            return $userInfo;
        }


    }


    /**
     * author:codeMonkey QQ 631872807
     * 获取哟规划信息
     * @return array|mixed|stdClass
     */
    public function  getClientUserInfo()
    {
        global $_GPC;
        $session = json_decode(base64_decode($_GPC['__yyzfuser']), true);
        return $session;

    }



}