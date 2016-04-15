<?php
/**
 * QQ:214983937
 */
defined('IN_IA') or exit ('Access Denied');

class Amouse_tel114ModuleSite extends WeModuleSite{

    //微信入口
    public function doMobileIndex()  {
        global $_GPC, $_W;

        $weid=$_W['uniacid'];
        $set= $this->get_sysset($weid);
		$followed = !empty($_W['openid']);
        /*if ($followed) {
            $mf = pdo_fetch("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid limit 1", array(":openid" => $_W['openid']));
            $followed = $mf['follow']==1;
        }
        if(!$followed){
			$followurl = $set['followurl'];
            header("location:$followurl");
		}*/

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
           echo "本页面仅支持微信访问!非微信浏览器禁止浏览!";
            exit;
        }

        $openid=$_W['openid'];
        $cid=intval($_GPC['cid']);
        $fls= pdo_fetchall("SELECT * FROM ".tablename('amouse_tel114_fl')." WHERE weid='{$weid}' ORDER BY displayorder ASC");
        $advs= pdo_fetchall("SELECT * FROM ".tablename('amouse_tel114_adv')." WHERE weid='{$weid}' ORDER BY displayorder DESC");
        $pindex= max(1, intval($_GPC['page']));
        $psize= 15;

        $condition= " WHERE weid='{$weid}'";
        if(!empty($_GPC['keyword'])){
            $keyword.= "%{$_GPC['keyword']}%";
            $condition.= " AND mobile LIKE '{$keyword}' OR title LIKE '{$keyword}' ";
        }
        if($cid>0){
            $condition.=" and cid=$cid ";
            if($set['isopen']==0){
                $condition.=" and status=0";
            }
            $list= pdo_fetchall('SELECT * FROM '.tablename('amouse_tel114')." $condition ORDER BY id DESC,displayorder desc LIMIT ".($pindex -1) * $psize.','.$psize); //分页
            $total= pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename('amouse_tel114').$condition);
            $pager = pagination($total, $pindex, $psize);
            $pageend=ceil($total/$psize);
            if($total/$psize!=0 && $total>=$psize){
                $pageend++;
            }
        }else{
            if($set['isopen']==0){
                $condition.=" and status=0";
            }
            $list= pdo_fetchall('SELECT * FROM '.tablename('amouse_tel114')." $condition ORDER BY id DESC, displayorder DESC LIMIT ".($pindex -1) * $psize.','.$psize); //分页
            $total= pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename('amouse_tel114').$condition);
            $pager = pagination($total, $pindex, $psize);
            $pageend=ceil($total/$psize);
            if($total/$psize!=0 && $total>=$psize){
                $pageend++;
            }
        }

        $shareimg =!empty($set['shareicon'])? toimage($set['shareicon']):$_W['siteroot'].'addons/amouse_tel114/template/mobile/images/tel.png';
        $sharetitle =empty($set['sharetitle']) ?'电话114，本地生活好帮手，你的日常所需。' : $set['sharetitle'];
        $sharedesc = empty($set['sharedesc']) ? '电话114，本地生活好帮手，你的日常所需。' : $set['sharedesc'];
        $shareurl= $_W['siteroot']."app/".substr($this->createMobileUrl('index',array('cid'=>0,'openid'=>$openid),true), 2);
        $navs=pdo_fetchall("SELECT * FROM ".tablename('amouse_tel114_nav')." WHERE weid=:weid ORDER BY displayorder DESC ", array(':weid'=>$weid));
        include $this->template('index');
    }


    public function doMobileNew(){
        global $_W,$_GPC;
        $weid=$_W['uniacid'];
        $set= $this->get_sysset($weid);
        $fls= pdo_fetchall("SELECT * FROM ".tablename('amouse_tel114_fl')." WHERE weid='{$weid}' ORDER BY displayorder ASC ");
        include $this->template('new');
    }

    public function doMobileSubAjax()  {
        global $_GPC, $_W;
        $weid=$_W['uniacid'];
        $data = array(
            'weid'=> $_W['uniacid'],
            'title'=> trim($_GPC['title']),
            'mobile'=> trim($_GPC['mobile']),
            'cid' => intval($_GPC['cid']),
            'location_p' => trim($_GPC['location_p']),
            'location_c' => trim($_GPC['location_c']),
            'location_a' => trim($_GPC['location_a']),
            'place' => trim($_GPC['place']),
            'lng' => trim($_GPC['lng']),
            'lat' => trim($_GPC['lat']),
        );
        if($_GPC['isopen']==0){
            $data['status']=1;
            $txt="提交成功!";
        }else{
            $data['status']=0;
            $txt="提交成功，请等待审核!";
        }
        pdo_insert('amouse_tel114',$data);

        return $this->tel114Json(1, '',$txt);
        exit;
    }

    public function tel114Json($resultCode,$resultData, $resultMsg) {
        $jsonArray = array(
            'resultCode' => $resultCode,
            'resultData' => $resultData,
            'resultMsg' => $resultMsg
        );
        $jsonStr = json_encode($jsonArray);
        return $jsonStr;
    }

    public function doCheckedMobile() {
        global $_GPC, $_W;
        $servername = $_SERVER['SERVER_NAME'];
        $useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if (strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false) {
            message('非法访问，请通过微信打开！');
        }
    }

    //分类管理
    public function doWebFl()  {
        global $_GPC, $_W;
        $op = $_GPC['op'] ? $_GPC['op'] : 'display';
        $weid = $_W['uniacid'];
        if ($op == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;
            if (!empty($_GPC['displayorder'])) {
                foreach ($_GPC['displayorder'] as $id => $displayorder) {
                    $update = array('displayorder' => $displayorder);
                    pdo_update('amouse_tel114_fl', $update, array('id' => $id));
                }
                message('分类排序更新成功！', 'refresh', 'success');
            }
            $condition="WHERE weid='{$weid}' " ;
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND title LIKE '%" . $_GPC['keyword'] . "%'";
            }
            $list = pdo_fetchall('SELECT * FROM ' . tablename('amouse_tel114_fl') . " $condition ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize); //分页
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('amouse_tel114_fl') . $condition, array());

            $pager = pagination($total, $pindex, $psize);
        } elseif ($op == 'post') {
            $id = intval($_GPC['id']);
            if ($id > 0) {
                $item = pdo_fetch('SELECT * FROM '.tablename('amouse_tel114_fl')." WHERE id=:id",array(':id' => $id));
            }
            if (checksubmit('submit')) {
                $insert = array(
                    'weid'=>$weid,
                    'title' => $_GPC['title'],
                    'displayorder' => intval($_GPC['displayorder']),
                    'createdtime' => TIMESTAMP
                );

                if (empty($id)) {
                    pdo_insert('amouse_tel114_fl', $insert);
                } else {
                    if (pdo_update('amouse_tel114_fl', $insert, array('id' => $id)) === false) {
                        message('更新分类数据失败, 请稍后重试.', 'error');
                    }
                }
                message('更新分类数据成功！', $this->createWebUrl('fl', array('op' => 'display', 'name' => 'amouse_tel114')), 'success');
            }
        } elseif ($op == 'del') {
            $id = intval($_GPC['id']);
            $temp = pdo_delete("amouse_tel114_fl", array('id' => $id));
            if ($temp == false) {
                message('抱歉，删除分类数据失败！', '', 'error');
            } else {
                message('删除分类成功！', $this->createWebUrl('fl', array('op' => 'display', 'name' => 'amouse_tel114')), 'success');
            }
        }

        include $this->template('fl_list');
    }
  

    //错误题目
    public function doWebTel() {
        global $_W, $_GPC;
        $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $weid= $_W['uniacid'] ;
        load()->func('tpl');
        $fls= pdo_fetchall("SELECT * FROM ".tablename('amouse_tel114_fl')." WHERE weid='{$weid}' ORDER BY displayorder ASC, id ASC ", array(), 'id');

        if ($op == 'display') {
            if (!empty($_GPC['displayorder'])) {
                foreach ($_GPC['displayorder'] as $id => $displayorder) {
                    $update = array('displayorder' => $displayorder);
                    pdo_update('amouse_tel114', $update, array('id' => $id));
                }
                message('号码排序更新成功！', 'refresh', 'success');
            }

            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $status = $_GPC['status'];

            $condition = '';
            $params = array();
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND title LIKE :keyword";
                $params[':keyword'] = "%{$_GPC['keyword']}%";
            }

            if (!empty($_GPC['cid'])) {
                $cid = intval($_GPC['cid']);
                $condition .= " AND cid = '{$cid}'";
            }
            if ($status != '') {
                $condition .= " AND status = '" .$status. "'";
            }
            $list = pdo_fetchall("SELECT * FROM ".tablename('amouse_tel114')." WHERE weid = '{$weid}' $condition ORDER BY id DESC,displayorder desc  LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('amouse_tel114') . " WHERE weid = '{$weid}'");

            $pager = pagination($total, $pindex, $psize);

        } elseif ($op == 'post') {
            $id = intval($_GPC['id']);
            load()->func('tpl');
            $pcate2 = $_GPC['pcate'];
            if ($id>0) {
                $item = pdo_fetch("SELECT * FROM ".tablename('amouse_tel114')." WHERE id = :id" , array(':id' => $id));
                if (empty($item)) {
                    message('抱歉，号码不存在或是已经删除！', '', 'error');
                }
                $pcate2 = $item['cid'];
            }
            if (checksubmit('submit')) {
                $pcate = $_GPC['pcate'];
                empty($_GPC['title']) ? message('亲,标题不能为空') : $title= $_GPC['title'];
                $data = array(
                    'weid' => $_W['uniacid'],
                    'cid' => intval($pcate),
                    'title' => $title,
                    'displayorder' => intval($_GPC['displayorder']),
                    'mobile' => $_GPC['mobile'],
                    'outlink'=>trim($_GPC['outlink']),
                    'location_p' => $_GPC['district']['province'],
                    'location_c' => $_GPC['district']['city'],
                    'location_a' => $_GPC['district']['district'],
                    'lng' => $_GPC['baidumap']['lng'],
                    'lat' => $_GPC['baidumap']['lat'],
                    'place'=> $_GPC['place'],
                );

                if (empty($id)) {
                    pdo_insert('amouse_tel114', $data);
                } else {
                    pdo_update('amouse_tel114', $data, array('id' => $id));
                }
                message('电话号码更新成功！', $this->createWebUrl('tel', array('op' => 'display')), 'success');
            }
        }elseif($op=='delete') {
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM ".tablename('amouse_tel114')." WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，电话号码不存在或是已经被删除！');
            }
            pdo_delete('amouse_tel114', array('id' => $id));
            message('删除成功！', referer(), 'success');
        }elseif($op=='vervify'){
            $id= intval($_GPC['id']);
            $recommed=$_GPC['status'];
            if($recommed==0){
                $msg='审核';
            }
            if($id > 0) {
                pdo_update('amouse_tel114',array('status' =>$recommed), array('id' => $id)) ;
                message($msg.'成功！', $this->createWebUrl('tel', array('op' => 'display')), 'success');
            }
        }
        include $this->template('tel');
    }

    //导航
    public function doWebDaohang() {
        global $_W, $_GPC;
        $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $weid= $_W['uniacid'] ;

        if ($op == 'display') {
            if (!empty($_GPC['displayorder'])) {
                foreach ($_GPC['displayorder'] as $id => $displayorder) {
                    $update = array('displayorder' => $displayorder);
                    pdo_update('amouse_tel114_nav', $update, array('id' => $id));
                }
                message('排序更新成功！', 'refresh', 'success');
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $condition = "WHERE weid = '{$weid}'";
            $params = array();
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND title LIKE :keyword";
                $params[':keyword'] = "%{$_GPC['keyword']}%";
            }
            $list = pdo_fetchall("SELECT * FROM ".tablename('amouse_tel114_nav')." $condition ORDER BY displayorder desc  LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('amouse_tel114_nav') . " $condition ");
            $pager = pagination($total, $pindex, $psize);

        } elseif ($op == 'post') {
            $id = intval($_GPC['id']);
            if ($id>0) {
                $item = pdo_fetch("SELECT * FROM ".tablename('amouse_tel114_nav')." WHERE id = :id" , array(':id' => $id));
                if (empty($item)) {
                    message('抱歉，导航不存在或是已经删除！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                empty($_GPC['title']) ? message('亲,标题不能为空') : $title= $_GPC['title'];
                $data = array(
                    'weid' => $_W['uniacid'],
                    'title' => $title,
                    'displayorder' => intval($_GPC['displayorder']),
                    'followurl'=>trim($_GPC['followurl']),
                );

                if (empty($id)) {
                    pdo_insert('amouse_tel114_nav', $data);
                } else {
                    pdo_update('amouse_tel114_nav', $data, array('id' => $id));
                }
                message('导航更新成功！', $this->createWebUrl('daohang', array('op' => 'display')), 'success');
            }
        } elseif ($op == 'delete') {
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM ".tablename('amouse_tel114_nav')." WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，导航不存在或是已经被删除！');
            }
            pdo_delete('amouse_tel114_nav', array('id' => $id));
            message('删除成功！', referer(), 'success');
        }

        include $this->template('daohang');
    }


    public function doWebAdv() {
        global $_W, $_GPC;
        $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $weid= $_W['uniacid'] ;

        if ($op == 'display') {
            if (!empty($_GPC['displayorder'])) {
                foreach ($_GPC['displayorder'] as $id => $displayorder) {
                    $update = array('displayorder' => $displayorder);
                    pdo_update('amouse_tel114_adv', $update, array('id' => $id));
                }
                message('排序更新成功！', 'refresh', 'success');
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $condition = "WHERE weid = '{$weid}'";
            $list = pdo_fetchall("SELECT * FROM ".tablename('amouse_tel114_adv')." $condition ORDER BY displayorder desc  LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('amouse_tel114_adv') . " $condition ");
            $pager = pagination($total, $pindex, $psize);

        } elseif ($op == 'post') {
            load()->func('tpl');
            $id = intval($_GPC['id']);
            if ($id>0) {
                $item = pdo_fetch("SELECT * FROM ".tablename('amouse_tel114_adv')." WHERE id = :id" , array(':id' => $id));
                if (empty($item)) {
                    message('抱歉，广告不存在或是已经删除！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                $data = array(
                    'weid' => $_W['uniacid'],
                    'displayorder' => intval($_GPC['displayorder']),
                    'thumb' => $_GPC['thumb'],
                    'followurl'=>trim($_GPC['followurl']),
                );

                if(!empty($_FILES['thumb']['tmp_name'])) {
                    file_delete($_GPC['thumb-old']);
                    $upload= file_upload($_FILES['thumb']);
                    if(is_error($upload)) {
                        message($upload['message'], '', 'error');
                    }
                    $data['thumb']= $upload['path'];
                }
                if (empty($id)) {
                    pdo_insert('amouse_tel114_adv', $data);
                } else {
                    pdo_update('amouse_tel114_adv', $data, array('id' => $id));
                }
                message('广告更新成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
            }
        } elseif ($op == 'delete') {
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM ".tablename('amouse_tel114_adv')." WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，广告不存在或是已经被删除！');
            }
            pdo_delete('amouse_tel114_adv', array('id' => $id));
            message('删除成功！', referer(), 'success');
        }

        include $this->template('adv');
    }

	//参数设置
    public function doWebSysset(){
        global $_W, $_GPC;
        $weid= $_W['uniacid'];
        load()->func('tpl');
        $set= $this->get_sysset($weid);
        if(checksubmit('submit')) {
            $data= array(
                'weid' => $weid,  
                'copyright'=>$_GPC['copyright'],
                'comurl'=>$_GPC['comurl'],
                'comdate'=>$_GPC['comdate'],
                'followurl'=>$_GPC['followurl'],
                'thumb'=>$_GPC['thumb'],
                'sharetitle'=>$_GPC['sharetitle'],
                'sharedesc'=>$_GPC['sharedesc'],
                'shareicon'=>$_GPC['shareicon'],
                'isopen'=>$_GPC['isopen'],
                'logo'=>$_GPC['logo'],
            );

            if(!empty($set)) {
                pdo_update('amouse_tel114_sysset', $data, array('id' => $set['id']));
            } else {
                pdo_insert('amouse_tel114_sysset', $data);
            }
            message('更新参数设置成功！', 'refresh');
        }

        include $this->template('sysset');
    }


    public function get_sysset($weid = 0){
        global $_W;
        return pdo_fetch("SELECT * FROM " . tablename('amouse_tel114_sysset') . " WHERE weid=:weid limit 1", array(':weid' => $weid));
    }


}