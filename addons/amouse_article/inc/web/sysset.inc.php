<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 15-3-21
 * Time: 下午12:59
 * To change this template use File | Settings | File Templates.
 */

global $_W, $_GPC;
$weid= $_W['uniacid'];
$op = empty($_GPC['op']) ? 'other' : trim($_GPC['op']);
$set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' => $weid));
load()->func('tpl');

if(checksubmit('submit')) {
    $data= array('weid' => $weid);
    if ($op == 'tpl') {
        $data['tpl'] =$_GPC['tpl'];
        $data['paytpl']=trim($_GPC['paytpl']);
    }else if ($op == 'pay') {
        $data['appsecret']=$_GPC['appsecret'];
        $data['appid']=$_GPC['appid'];
        $data['mchid']=$_GPC['mchid'];//mchid
        $data['shkey']=$_GPC['shkey'];//shkey
    }else if ($op == 'other') {
        $data= array(
            'weid' => $weid,
            'guanzhuUrl'=>$_GPC['guanzhuUrl'],
            'guanzhutitle'=>$_GPC['guanzhutitle'],
            'historyUrl' => $_GPC['historyUrl'] ,
            'copyright'=> $_GPC['copyright'] ,
            'cnzz'=> $_GPC['cnzz'] ,
            'logo' =>$_GPC['logo'] ,
            'footlogo'=>$_GPC['footlogo'],
            'tjgzh' =>$_GPC['tjgzh'] ,
            'isopen' =>$_GPC['isopen'] ,
            'title' =>$_GPC['title'] ,
            'tjgzhUrl' =>$_GPC['tjgzhUrl'] ,
            'iscomment'=>$_GPC['iscomment'],
            'isget'=>$_GPC['isget']
        );
    }

    if(!empty($set)) {
        pdo_update('fineness_sysset', $data, array('id' => $set['id']));
    } else {
        pdo_insert('fineness_sysset', $data);
    }
    message('更新系统设置成功！', 'refresh');
}
include $this->template('sysset_'.$op);
