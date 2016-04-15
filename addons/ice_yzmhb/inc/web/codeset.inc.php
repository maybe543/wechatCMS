<?php
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
load()->func('tpl');
//这里来展示设置项表单
$modulelist = uni_modules(false);
$name = 'ice_yzmhb';
$module = $modulelist[$name];
if(empty($module)) {
	message('抱歉，你操作的模块不能被访问！');
}
define('CRUMBS_NAV', 1);
$ptr_title = '参数设置';
$module_types = module_types();
define('ACTIVE_FRAME_URL', url('home/welcome/ext', array('m' => $name)));
$settings = $module['config'];
$foo ='codeset';
// if(empty($_GPC['id']))
// 	$hbid = $_GPC['hbid'];
// else
// 	$hbid = pdo_fetchcolumn('select id from '.tablename('ice_yzmhb').' where rid =:rid',array(':rid' =>$_GPC['id']));
$hbid = 0;

if(checksubmit()) {
	
	$count = $_GPC['count'];
	$type = $_GPC['type'];
	$param =array(
		'uniacid' =>$_W['uniacid'],
		'hbid' =>$hbid,
		'count' =>$_GPC['count'],
		'type' => $type,
		'time' =>time('Ymd'),
		);
	if(pdo_insert('ice_yzmhb_codenum',$param))
	{
		$pcid = pdo_insertid();	
		getcode($pcid,$_GPC['count'],$type,$hbid);
		message('验证码生成成功','','success'); // 保存成功
	}
	
}


$pindex = max(1, intval($_GPC['page']));
$psize = 20;


$sql = 'select * from '.tablename('ice_yzmhb_codenum').'where uniacid = :uniacid LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;

$prarm = array(
	':uniacid' =>$_W['uniacid'],
	);
$list = pdo_fetchall($sql,$prarm);
$count = pdo_fetchcolumn('select count(*) from '.tablename('ice_yzmhb_codenum').'where uniacid = :uniacid',$prarm);
$pager = pagination($count, $pindex, $psize);
include $this->template('codesettings');

function getcode($pcid,$count,$type,$hbid){
	global $_W;
	if(intval($count)>0)
	{
		for($i=0;$i<$count;$i++)
		{
// 			$code =chr(mt_rand(33, 126));
			
			do{
				$code1 = genkeyword(6);
			}while (pdo_fetchcolumn("select id from ".tablename("ice_yzmhb_code")." where code = :code limit 1",array(":code"=>$code1)));
			
			$code = array(
				'uniacid' => $_W['uniacid'],
				'piciid' =>$pcid,
				'yzmhbid' => $hbid,
				'code' =>$code1,
				'type' => $type,
				'time' =>time('Ymd')
				);
// 							$code = array(
// 								'uniacid' => $_W['uniacid'],
// 								'piciid' =>$pcid,
// 								'yzmhbid' => $hbid,
// 								'code' =>genkeyword(10),
// 								'type' => $type,
// 								'time' =>time('Ymd')
// 								);
			if(!pdo_insert('ice_yzmhb_code',$code))
				return false;
				
		}
	}
	
}

function  genkeyword($length)  
{  
    $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 
    'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's', 
    't', 'u', 'v', 'w', 'x', 'y','z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    // 在 $chars 中随机取 $length 个数组元素键名
    $password = '';
    for($i = 0; $i < $length; $i++)
    {
    $keys = array_rand($chars,1);
        // 将 $length 个数组元素连接成字符串
        $password .= $chars[$keys];
    }
    return $password;
}



















