<?php

/*
 * Created on 2014��12��23��
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 *  shizhongying qq:214983937
 */

global $_GPC, $_W;
$op = $_GPC['op'] ? $_GPC['op'] : 'display';
$weid = $_W['uniacid'];
$brands = pdo_fetchall("SELECT * FROM " . tablename('amouse_valuation_mobile_model') . " WHERE `weid` = :weid  ORDER BY `id` DESC", array(':weid' => $weid));
load()->func('tpl');
if ($op == 'display') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = 30; //每页显示
    $condition = "WHERE `weid` = $weid";
    if (!empty($_GPC['keyword'])) {
        $condition .= " AND title LIKE '%" . $_GPC['keyword'] . "%'";
    }
    if (!empty($_GPC['modelid'])) {
        $cid = intval($_GPC['modelid']);
        $condition .= " AND moid = '{$cid}'";
    }
    $list = pdo_fetchall('SELECT * FROM ' . tablename('amouse_valuation_mobile_version') . " $condition  ORDER BY id DESC  LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

    $brandsArr = array();
    foreach ($brands as $v) {
        $brandsArr[$v['id']] = $v['title'];
    }

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('amouse_valuation_mobile_version') . $condition);
    $pager = pagination($total, $pindex, $psize);
} elseif ($op == 'post') {
    $id = intval($_GPC['id']);
    if ($id > 0) {
        $item = pdo_fetch('SELECT * FROM ' . tablename('amouse_valuation_mobile_version') . " WHERE weid=:weid AND id=:id", array(':weid' => $weid, ':id' => $id));
    }

    if (checksubmit('submit')) {
        $bid = intval($_GPC['bid']) ? intval($_GPC['bid']) : message('请选择所属机型！');
        $title = trim($_GPC['title']) ? trim($_GPC['title']) : message('请填写型号名称！');
        $logo = trim($_GPC['thumb']) ? trim($_GPC['thumb']) : message('请上传型号图片！');
        $price = $_GPC['price'];
        $insert = array('moid' => $bid,
            'title' => $title,
            'weid' => $weid,
            'logo' => $logo,
            'price' => $price,
            'createtime' => TIMESTAMP);

        if (empty($id)) {
            pdo_insert('amouse_valuation_mobile_version', $insert);
            !pdo_insertid() ? message('保存手机型号数据失败, 请稍后重试.', 'error') : '';
        } else {
            if (pdo_update('amouse_valuation_mobile_version', $insert, array('id' => $id)) === false) {
                message('更新手机型号数据失败, 请稍后重试.', 'error');
            }
        }
        message('更新手机型号数据成功！', $this->createWebUrl('version', array('op' => 'display', 'name' => 'amouse_valuation')), 'success');
    }
} elseif ($op == 'del') { //删除
    if (isset($_GPC['delete'])) {
        $ids = implode(",", $_GPC['delete']);
        $sqls = "delete from  " . tablename('amouse_valuation_mobile_version') . "  where id in(" . $ids . ")";
        pdo_query($sqls);
        message('删除成功！', referer(), 'success');
    }

    $id = intval($_GPC['id']);
    $temp = pdo_delete("amouse_valuation_mobile_version", array("weid" => $weid, 'id' => $id));
    if ($temp == false) {
        message('抱歉，删除数据失败！', '', 'error');
    } else {
        message('删除手机型号数据成功！', $this->createWebUrl('version', array('op' => 'display', 'name' => 'amouse_valuation')), 'success');
    }

} elseif ($op == 'parameter') { //添加参数
    $vid = intval($_GPC['vid']); //型号
    if (!empty($vid)) {
        $parameter1 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='1'  ");
        $parameter2 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='2'  ");
        $parameter3 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='3'  ");
        $parameter4 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='4'  ");
        $parameter5 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='5'  ");
        $parameter6 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='6'  ");
        $parameter7 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='7'  ");
        $parameter8 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='8'  ");
        $parameter9 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='9'  ");
        $parameter10 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='10'  ");
        $parameter11 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='11'  ");
        $parameter12 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='12'  ");
        $parameter13 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='13'  ");
        $parameter14 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='14'  ");
        $parameter15 = pdo_fetch(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=" . $vid . " AND `ptype`='15' ");

        if (empty($parameter1)) {
            $parameter1 = array(
                'txt' => '机身颜色', 'optionA' => '银色', 'priceA' => '0', 'optionB' => '金色', 'priceB' => '0', 'optionC' => '灰色', 'priceC' => '0', 'ptype' => '1',
            );
        }

        if (empty($parameter2)) {
            $parameter2 = array('txt' => '能否正常开关机', 'optionA' => '正常开机', 'priceA' => '0',
                'optionB' => '不能正常开机', 'priceB' => '-3000', 'ptype' => '2',);
        }
        if (empty($parameter3)) {
            $parameter3 = array('txt' => 'ID、密码是否解除', 'optionA' => 'ID和密码都已解除', 'priceA' => '0', 'ptype' => '3',);
        }
        if (empty($parameter4)) {
            $parameter4 = array('txt' => '能否正常接打电话', 'optionA' => '接打电话正常', 'priceA' => '0', 'ptype' => '4',);
        }
        if (empty($parameter5)) {
            $parameter5 = array('txt' => '电池', 'optionA' => '电池正常', 'priceA' => '0',
                'optionB' => '电池损坏', 'priceB' => '-400',
                'optionC' => '电池鼓包', 'priceC' => '-400', 'ptype' => '5',);
        }
        if (empty($parameter6)) {
            $parameter6 = array('txt' => '屏幕功能', 'optionA' => '触摸和显示都正常', 'priceA' => '0',
                'optionB' => '显示异常/有亮点/花屏/蓝屏', 'priceB' => '-2000',
                'optionC' => '触摸失灵', 'priceC' => '-2000', 'ptype' => '6',);
        }
        if (empty($parameter7)) {
            $parameter7 = array('txt' => '屏幕外观', 'optionA' => '屏幕无磨损', 'priceA' => '0',
                'optionB' => '小磨损/小划伤', 'priceB' => '-800',
                'optionC' => '大的划伤/硬伤/破裂/翘边/非原装', 'priceC' => '-2000', 'ptype' => '7');
        }
        if (empty($parameter8)) {
            $parameter8 = array('txt' => '后壳成色', 'optionA' => '无磕伤', 'priceA' => '0',
                'optionB' => '小磕伤/划伤', 'priceB' => '-800',
                'optionC' => '大磕伤/硬伤/断开/变形/非原装', 'priceC' => '-1800', 'ptype' => '8');
        }
        if (empty($parameter9)) {
            $parameter9 = array('txt' => '无线连接类', 'optionA' => 'wifi/蓝牙/GPS都正常', 'priceA' => '0',
                'optionB' => 'WIFI打不开', 'priceB' => '-400',
                'optionC' => '蓝牙打不开', 'priceC' => '-400',
                'optionD' => 'GPS打不开/不能定位', 'priceD' => '-400', 'ptype' => '9');
        }
        if (empty($parameter10)) {
            $parameter10 = array('txt' => '相机类', 'optionA' => '正常', 'priceA' => '0',
                'optionB' => '前置相机坏', 'priceB' => '-500',
                'optionC' => '后置相机坏', 'priceC' => '-600', 'ptype' => '10');
        }
        if (empty($parameter11)) {
            $parameter11 = array('txt' => '送话、受话类', 'optionA' => '听筒/送话都正常', 'priceA' => '0',
                'optionB' => '听筒无声', 'priceB' => '-300',
                'optionC' => '不送话/声音小', 'priceC' => '-300', 'optionD' => '光线感应坏', 'priceD' => '-300', 'ptype' => '11');
        }
        if (empty($parameter12)) {
            $parameter12 = array('txt' => '按键类', 'optionA' => '按键全部正常', 'priceA' => '0',
                'optionB' => 'home键失灵', 'priceB' => '-400', 'optionC' => '电源键坏', 'priceC' => '-300',
                'optionD' => '音量键坏', 'priceD' => '-300', 'optionE' => '静音键坏', 'priceE' => '-300', 'ptype' => '12');
        }

        if (empty($parameter13)) {
            $parameter13 = array('txt' => '铃声、充电类', 'optionA' => '铃声/震动/充电全部正常', 'priceA' => '0',
                'optionB' => '扬声器无声', 'priceB' => '-400', 'optionC' => '无来电铃声', 'priceC' => '-300',
                'optionD' => '无震动', 'priceD' => '-300', 'optionE' => '不充电', 'priceE' => '-400', 'ptype' => '13');
        }
        if (empty($parameter14)) {
            $parameter14 = array('txt' => '是否进水', 'optionA' => '无进水', 'priceA' => '0',
                'optionB' => '已进水', 'priceB' => '-1000', 'ptype' => '14');
        }
        if (empty($parameter15)) {
            $parameter15 = array('txt' => '是否维修和私拆过', 'optionA' => '没有修理或拆过机', 'priceA' => '0',
                'optionB' => '已维修过或者私拆过', 'priceB' => '-1000', 'ptype' => '15');
        }
    }

    if(checksubmit('submit')) {
        $title1 = array(
            'txt' => $_GPC['parameter1txt'],
            'optionA' => $_GPC['parameter1optionA'],
            'priceA' => $_GPC['parameter1priceA'],
            'optionB' => $_GPC['parameter1optionB'],
            'priceB' => $_GPC['parameter1priceB'],
            'optionC' => $_GPC['parameter1optionC'],
            'priceC' => $_GPC['parameter1priceC'],
            'optionD' => $_GPC['parameter1optionD'],
            'priceD' => $_GPC['parameter1priceD'],
            'optionE' => $_GPC['parameter1optionE'],
            'priceE' => $_GPC['parameter1priceE'],
            'optionF' => $_GPC['parameter1optionF'],
            'priceF' => $_GPC['parameter1priceF'],
            'weid' => $_W['uniacid'],
            'vid' => $_GPC['vid'],
            'ptype'=>'1'
        );

        $title2 = array(
            'txt' => $_GPC['parameter2txt'],
            'optionA' => $_GPC['parameter2optionA'],
            'priceA' => $_GPC['parameter2priceA'],
            'optionB' => $_GPC['parameter2optionB'],
            'priceB' => $_GPC['parameter2priceB'],
            'optionC' => $_GPC['parameter2optionC'],
            'priceC' => $_GPC['parameter2priceC'],
            'optionD' => $_GPC['parameter2optionD'],
            'priceD' => $_GPC['parameter2priceD'],
            'optionE' => $_GPC['parameter2optionE'],
            'priceE' => $_GPC['parameter2priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'2'
        );
		
		$title3 = array(
            'txt' => $_GPC['parameter3txt'],
            'optionA' => $_GPC['parameter3optionA'],
            'priceA' => $_GPC['parameter3priceA'],
            'optionB' => $_GPC['parameter3optionB'],
            'priceB' => $_GPC['parameter3priceB'],
            'optionC' => $_GPC['parameter3optionC'],
            'priceC' => $_GPC['parameter3priceC'],
            'optionD' => $_GPC['parameter3optionD'],
            'priceD' => $_GPC['parameter3priceD'],
            'optionE' => $_GPC['parameter3optionE'],
            'priceE' => $_GPC['parameter3priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'3'
        );
		$title4 = array(
            'txt' => $_GPC['parameter4txt'],
            'optionA' => $_GPC['parameter4optionA'],
            'priceA' => $_GPC['parameter4priceA'],
            'optionB' => $_GPC['parameter4optionB'],
            'priceB' => $_GPC['parameter4priceB'],
            'optionC' => $_GPC['parameter4optionC'],
            'priceC' => $_GPC['parameter4priceC'],
            'optionD' => $_GPC['parameter4optionD'],
            'priceD' => $_GPC['parameter4priceD'],
            'optionE' => $_GPC['parameter4optionE'],
            'priceE' => $_GPC['parameter4priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'4'
        );
		
		$title5 = array(
            'txt' => $_GPC['parameter5txt'],
            'optionA' => $_GPC['parameter5optionA'],
            'priceA' => $_GPC['parameter5priceA'],
            'optionB' => $_GPC['parameter5optionB'],
            'priceB' => $_GPC['parameter5priceB'],
            'optionC' => $_GPC['parameter5optionC'],
            'priceC' => $_GPC['parameter5priceC'],
            'optionD' => $_GPC['parameter5optionD'],
            'priceD' => $_GPC['parameter5priceD'],
            'optionE' => $_GPC['parameter5optionE'],
            'priceE' => $_GPC['parameter5priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'5'
        );
		
		$title6 = array(
            'txt' => $_GPC['parameter6txt'],
            'optionA' => $_GPC['parameter6optionA'],
            'priceA' => $_GPC['parameter6priceA'],
            'optionB' => $_GPC['parameter6optionB'],
            'priceB' => $_GPC['parameter6priceB'],
            'optionC' => $_GPC['parameter6optionC'],
            'priceC' => $_GPC['parameter6priceC'],
            'optionD' => $_GPC['parameter6optionD'],
            'priceD' => $_GPC['parameter6priceD'],
            'optionE' => $_GPC['parameter6optionE'],
            'priceE' => $_GPC['parameter6priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'6'
        );
		$title7 = array(
            'txt' => $_GPC['parameter7txt'],
            'optionA' => $_GPC['parameter7optionA'],
            'priceA' => $_GPC['parameter7priceA'],
            'optionB' => $_GPC['parameter7optionB'],
            'priceB' => $_GPC['parameter7priceB'],
            'optionC' => $_GPC['parameter7optionC'],
            'priceC' => $_GPC['parameter7priceC'],
            'optionD' => $_GPC['parameter7optionD'],
            'priceD' => $_GPC['parameter7priceD'],
            'optionE' => $_GPC['parameter7optionE'],
            'priceE' => $_GPC['parameter7priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'7'
        );
		$title8 = array(
            'txt' => $_GPC['parameter8txt'],
            'optionA' => $_GPC['parameter8optionA'],
            'priceA' => $_GPC['parameter8priceA'],
            'optionB' => $_GPC['parameter8optionB'],
            'priceB' => $_GPC['parameter8priceB'],
            'optionC' => $_GPC['parameter8optionC'],
            'priceC' => $_GPC['parameter8priceC'],
            'optionD' => $_GPC['parameter8optionD'],
            'priceD' => $_GPC['parameter8priceD'],
            'optionE' => $_GPC['parameter8optionE'],
            'priceE' => $_GPC['parameter8priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'8'
        );
		
		$title9 = array(
            'txt' => $_GPC['parameter9txt'],
            'optionA' => $_GPC['parameter9optionA'],
            'priceA' => $_GPC['parameter9priceA'],
            'optionB' => $_GPC['parameter9optionB'],
            'priceB' => $_GPC['parameter9priceB'],
            'optionC' => $_GPC['parameter9optionC'],
            'priceC' => $_GPC['parameter9priceC'],
            'optionD' => $_GPC['parameter9optionD'],
            'priceD' => $_GPC['parameter9priceD'],
            'optionE' => $_GPC['parameter9optionE'],
            'priceE' => $_GPC['parameter9priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'9'
        );
		$title10 = array(
            'txt' => $_GPC['parameter10txt'],
            'optionA' => $_GPC['parameter10optionA'],
            'priceA' => $_GPC['parameter10priceA'],
            'optionB' => $_GPC['parameter10optionB'],
            'priceB' => $_GPC['parameter10priceB'],
            'optionC' => $_GPC['parameter10optionC'],
            'priceC' => $_GPC['parameter10priceC'],
            'optionD' => $_GPC['parameter10optionD'],
            'priceD' => $_GPC['parameter10priceD'],
            'optionE' => $_GPC['parameter10optionE'],
            'priceE' => $_GPC['parameter10priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'10'
        );
		$title11 = array(
            'txt' => $_GPC['parameter11txt'],
            'optionA' => $_GPC['parameter11optionA'],
            'priceA' => $_GPC['parameter11priceA'],
            'optionB' => $_GPC['parameter11optionB'],
            'priceB' => $_GPC['parameter11priceB'],
            'optionC' => $_GPC['parameter11optionC'],
            'priceC' => $_GPC['parameter11priceC'],
            'optionD' => $_GPC['parameter11optionD'],
            'priceD' => $_GPC['parameter11priceD'],
            'optionE' => $_GPC['parameter11optionE'],
            'priceE' => $_GPC['parameter11priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'11'
        );
		$title12 = array(
            'txt' => $_GPC['parameter12txt'],
            'optionA' => $_GPC['parameter12optionA'],
            'priceA' => $_GPC['parameter12priceA'],
            'optionB' => $_GPC['parameter12optionB'],
            'priceB' => $_GPC['parameter12priceB'],
            'optionC' => $_GPC['parameter12optionC'],
            'priceC' => $_GPC['parameter12priceC'],
            'optionD' => $_GPC['parameter12optionD'],
            'priceD' => $_GPC['parameter12priceD'],
            'optionE' => $_GPC['parameter12optionE'],
            'priceE' => $_GPC['parameter12priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'12'
        );
		
		$title13 = array(
            'txt' => $_GPC['parameter13txt'],
            'optionA' => $_GPC['parameter13optionA'],
            'priceA' => $_GPC['parameter13priceA'],
            'optionB' => $_GPC['parameter13optionB'],
            'priceB' => $_GPC['parameter13priceB'],
            'optionC' => $_GPC['parameter13optionC'],
            'priceC' => $_GPC['parameter13priceC'],
            'optionD' => $_GPC['parameter13optionD'],
            'priceD' => $_GPC['parameter13priceD'],
            'optionE' => $_GPC['parameter13optionE'],
            'priceE' => $_GPC['parameter13priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'13'
        );
		$title14 = array(
            'txt' => $_GPC['parameter14txt'],
            'optionA' => $_GPC['parameter14optionA'],
            'priceA' => $_GPC['parameter14priceA'],
            'optionB' => $_GPC['parameter14optionB'],
            'priceB' => $_GPC['parameter14priceB'],
            'optionC' => $_GPC['parameter14optionC'],
            'priceC' => $_GPC['parameter14priceC'],
            'optionD' => $_GPC['parameter14optionD'],
            'priceD' => $_GPC['parameter14priceD'],
            'optionE' => $_GPC['parameter14optionE'],
            'priceE' => $_GPC['parameter14priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'14'
        );
		$title15 = array(
            'txt' => $_GPC['parameter15txt'],
            'optionA' => $_GPC['parameter15optionA'],
            'priceA' => $_GPC['parameter15priceA'],
            'optionB' => $_GPC['parameter15optionB'],
            'priceB' => $_GPC['parameter15priceB'],
            'optionC' => $_GPC['parameter15optionC'],
            'priceC' => $_GPC['parameter15priceC'],
            'optionD' => $_GPC['parameter15optionD'],
            'priceD' => $_GPC['parameter15priceD'],
            'optionE' => $_GPC['parameter15optionE'],
            'priceE' => $_GPC['parameter15priceE'],
            'weid' => $weid,
            'vid' => $_GPC['vid'],
            'ptype'=>'15'
        );

        $yixiutemp = pdo_fetch(" SELECT * FROM ".tablename('amouse_valuation_mobile_parameter')." WHERE `vid`=".$vid."   ");
        if($yixiutemp == null && $_GPC['parameter1txt']!=null){
            pdo_insert('amouse_valuation_mobile_parameter',$title1);
            pdo_insert('amouse_valuation_mobile_parameter',$title2);
            pdo_insert('amouse_valuation_mobile_parameter',$title3);
            pdo_insert('amouse_valuation_mobile_parameter',$title4);
            pdo_insert('amouse_valuation_mobile_parameter',$title5);
            pdo_insert('amouse_valuation_mobile_parameter',$title6);
            pdo_insert('amouse_valuation_mobile_parameter',$title7);
            pdo_insert('amouse_valuation_mobile_parameter',$title8);
            pdo_insert('amouse_valuation_mobile_parameter',$title9);
            pdo_insert('amouse_valuation_mobile_parameter',$title10);
            pdo_insert('amouse_valuation_mobile_parameter',$title11);
            pdo_insert('amouse_valuation_mobile_parameter',$title12);
            pdo_insert('amouse_valuation_mobile_parameter',$title13);
            pdo_insert('amouse_valuation_mobile_parameter',$title14);
            pdo_insert('amouse_valuation_mobile_parameter',$title15);
            message('新增机型参数数据成功！', $this->createWebUrl('version', array('op' => 'parameter','vid'=>$vid, 'name' => 'amouse_valuation')), 'success');
        }else{
            pdo_update('amouse_valuation_mobile_parameter',$title1,array('vid'=>$vid, 'ptype'=>'1'));
            pdo_update('amouse_valuation_mobile_parameter',$title2,array('vid'=>$vid, 'ptype'=>'2'));
            pdo_update('amouse_valuation_mobile_parameter',$title3,array('vid'=>$vid, 'ptype'=>'3'));
            pdo_update('amouse_valuation_mobile_parameter',$title4,array('vid'=>$vid, 'ptype'=>'4'));
            pdo_update('amouse_valuation_mobile_parameter',$title5,array('vid'=>$vid, 'ptype'=>'5'));
            pdo_update('amouse_valuation_mobile_parameter',$title6,array('vid'=>$vid, 'ptype'=>'6'));
            pdo_update('amouse_valuation_mobile_parameter',$title7,array('vid'=>$vid, 'ptype'=>'7'));
            pdo_update('amouse_valuation_mobile_parameter',$title8,array('vid'=>$vid, 'ptype'=>'8'));
            pdo_update('amouse_valuation_mobile_parameter',$title9,array('vid'=>$vid, 'ptype'=>'9'));
            pdo_update('amouse_valuation_mobile_parameter',$title10,array('vid'=>$vid, 'ptype'=>'10'));
            pdo_update('amouse_valuation_mobile_parameter',$title11,array('vid'=>$vid, 'ptype'=>'11'));
            pdo_update('amouse_valuation_mobile_parameter',$title12,array('vid'=>$vid, 'ptype'=>'12'));
            pdo_update('amouse_valuation_mobile_parameter',$title13,array('vid'=>vid, 'ptype'=>'13'));
            pdo_update('amouse_valuation_mobile_parameter',$title14,array('vid'=>vid, 'ptype'=>'14'));
            pdo_update('amouse_valuation_mobile_parameter',$title15,array('vid'=>$vid, 'ptype'=>'15'));

            message('更新机型参数数据成功！', $this->createWebUrl('version', array('op' => 'parameter','vid'=>$vid, 'name' => 'amouse_valuation')), 'success');
        }

    }

}

include $this->template('web/version');

?>
