<?php
//投递简历,加入投递表
global $_W,$_GPC;
$openid=$_W['openid'];
$uniacid=$_W['uniacid'];
$pid=$_GPC['pid'];
//企业信息
$com=pdo_fetch("select * from ".tablename('enjoy_recuit_culture')." where uniacid = '{$_W['uniacid']}'");
//先查询是否已经存在并且七天前已经投递过
$ctime=pdo_fetchcolumn("select createtime from ".tablename('enjoy_recuit_deliver')." where uniacid=".$uniacid." and openid='".$openid."' and pid=".$pid." order by createtime desc");
$time7=7*24*60*60;
$time=Intval($ctime)+$time7;
$com=pdo_fetch("select * from ".tablename('enjoy_recuit_culture')." where uniacid = '{$_W['uniacid']}'");
//发邮件
//$email='dj_zhaopin@dejiplaza.com';
$email=$com['email'];

$mylist=pdo_fetch("select uname,sex,age,ed,mobile,email,avatar,present,birth,height,weight,register,address,marriage,school from ".tablename('enjoy_recuit_basic')." as a left join ".tablename('enjoy_recuit_info')." as b on a.openid=b.openid
				where a.openid='".$openid."' and a.uniacid=".$uniacid."");

//循环遍历出工作经验
$myexpers=pdo_fetchall("select * from ".tablename('enjoy_recuit_exper')." where openid='".$openid."' and uniacid=".$uniacid."");
$mylist['exper']=$myexpers;
//循环遍历出证书
$mycard=pdo_fetchall("select * from ".tablename('enjoy_recuit_card')." where openid='".$openid."' and uniacid=".$uniacid."");
$mylist['card']=$mycard;
$mylist['position']=pdo_fetchcolumn("select pname from ".tablename('enjoy_recuit_position')."  where uniacid=".$uniacid." and id=".$pid."");
//变量处理
$mylist[sex]=($mylist[sex]==1)?"男":"女";
$mylist[height]=empty($mylist[height])?"":$mylist[height]."cm";
$mylist[weight]=empty($mylist[weight])?"":$mylist[weight]."kg";
$mylist[marriage]=($mylist[marriage]==0)?"未婚":"已婚";
switch ($mylist['ed']){
	case 1:
		$mylist['ed']='初中';
		break;
	case 2:
		$mylist['ed']='高中';
		break;
	case 3:
		$mylist['ed']='中技';
		break;
	case 4:
		$mylist['ed']='中专';
		break;
	case 5:
		$mylist['ed']='大专';
		break;
	case 6:
		$mylist['ed']='本科';
		break;
	case 7:
		$mylist['ed']='硕士';
		break;
	case 8:
		$mylist['ed']='博士';
		break;
}
switch ($mylist['present']){
	case 1:
		$mylist['present']='待业';
		break;
	case 2:
		$mylist['present']='准备辞职';
		break;
	case 3:
		$mylist['present']='在职';
		break;
	case 4:
		$mylist['present']='个体自营';
		break;
}

//工作经验
foreach ($mylist['exper'] as $v){
	switch ($v['salary']){
		case 0:
			$v['salary']='1000-3000';
			break;
		case 1:
			$v['salary']='3000-5000';
			break;
		case 2:
			$v['salary']='5000-8000';
			break;
		case 3:
			$v['salary']='8000-12000';
			break;
		case 4:
			$v['salary']='12000-20000';
			break;
		case 5:
			$v['salary']='20000以上';
			break;
	}
	$exper.="                    <tr>
                      <td valign='top' width='1%' nowrap=''></td>
                      <td style='WIDTH: 462px; WORD-WRAP: break-word' class='line150' align='left'>
                       时间：".$v['stime']."--".$v['etime']."<tr>
                      <td></tr>

                    <tr>
                      <td></td>
                      <td align='left'> 单位：".$v['company']." </td></tr>
                    <tr>
                      <td></td>
                      <td class='resume_p' align='left'>职务：".$v['position']."</td></tr>
                    <tr>
                    <tr>
                      <td></td>
                      <td class='resume_p' align='left'>薪资：".$v['salary']."元</td></tr>
                    <tr>
                    <tr>
                      <td></td>
                      <td class='resume_p' align='left'>描述：".$v['descript']."</td></tr>
                    <tr>
                      <td height='18' colspan='2'></td></tr>
                  ";
}
//证书
foreach ($mylist['card'] as $v){

	$card.="<tr>
                      <td valign='top' width='1%' nowrap=''>证书名称:</td>
                      <td style='WORD-WRAP: break-word; WORD-BREAK: break-all' align='left'>".$v['cname']."</td></tr>";

}

$subject = "(".$com['cname'].") 应聘 ".$mylist['position']."-".$mylist['uname']."";

$body = "<!DOCTYPE html><html lang='zh-cmn-Hans'><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<title>简历</title>
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no'>
		<meta name='format-detection' content='telephone=no'>
		<link rel='stylesheet' href='../addons/recruit/template/mobile/css/global.css?v=3125e0f738b1f44a65ad321699cef79ff47017a1'>
		
		<link rel='stylesheet' href='../addons/recruit/template/mobile/css/editresume_main.min.css?v=6a8316efbe2876d5c7436ada47b9b5e4de6d17a9'>
		<link href='../addons/recruit/template/mobile/css/navmenu.min.css' type='text/css' rel='stylesheet'>
		</head>
		<body>
		<table border='0' cellspacing='0' cellpadding='0' width='600'>
		  <tbody>
		  <tr>
		    <td style='BORDER-BOTTOM: #d6d3ce 1px solid; BORDER-LEFT: #d6d3ce 1px solid; BORDER-TOP: #d6d3ce 1px solid; BORDER-RIGHT: #d6d3ce 1px solid'>
		      <table border='0' cellspacing='0' cellpadding='0' width='600' bgcolor='#ffffff'>
		        <tbody>
		        <tr>
		          <td>
		            <table border='0' cellspacing='0' cellpadding='0' width='580' align='center'><tbody>
		              <tr>
		                <td height='10' colspan='3'></td></tr>
		              <tr>
		                <td valign='top' width='1%' nowrap=''><span style='COLOR: #000000; FONT-SIZE: 40px; FONT-WEIGHT: bold'>".$mylist[uname]."</span></td>
		                <td valign='top' align='right'>".$mylist[sex]."| ".$mylist[marriage]." | ".$mylist[birth]."生 | 籍贯：".$mylist[register]." | 
		                  现住".$mylist[address]." <br> ".$mylist[school]."|".$mylist[ed]."<br>".$mylist[present]."<br>mobile:".$mylist[mobile]."<br>身高：".$mylist[height]."|体重：".$mylist[weight]."<br>E-mail: <a href='mailto:".$mylist[email]."'>".$mylist[email]."</a> </td>
		                <td width='1%' nowrap=''>
		                  <div style='PADDING-BOTTOM: 5px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; PADDING-TOP: 0px' class='photo'><a href='#' target='_blank'><img style='VERTICAL-ALIGN: middle' border='0' src='".$mylist[avatar]."' width='70' height='90'></a></div></td></tr>
		              <tr>
		                <td height='10' colspan='3'></td></tr></tbody></table></td></tr>
		              <tr>
		                <td><br>
		                  <table border='0' cellspacing='0' cellpadding='2' width='580' bgcolor='#f6f7f8'>
		                    <tbody>
		                    <tr>
		                      <td style='BORDER-BOTTOM: #e7e7e7 1px solid; BORDER-LEFT: #e7e7e7 1px solid; BORDER-TOP: #e7e7e7 1px solid; BORDER-RIGHT: #e7e7e7 1px solid'>&nbsp;&nbsp;<span style='COLOR: #8866ff; FONT-SIZE: 14px'>工作经历</span></td></tr></tbody></table><br>
		                  <table border='0' cellspacing='0' cellpadding='0'>
		                    <tbody>
								".$exper."
		</tbody></table></td></tr>

		              <tr>
		                <td><br>
		                  <table border='0' cellspacing='0' cellpadding='2' width='580' bgcolor='#f6f7f8'>
		                    <tbody>
		                    <tr>
		                      <td style='BORDER-BOTTOM: #e7e7e7 1px solid; BORDER-LEFT: #e7e7e7 1px solid; BORDER-TOP: #e7e7e7 1px solid; BORDER-RIGHT: #e7e7e7 1px solid'>&nbsp;&nbsp;<span style='COLOR: #8866ff; FONT-SIZE: 14px'>证书</span></td></tr></tbody></table><br>
		                  <table border='0' cellspacing='0' cellpadding='0' width='580'>
		                    <tbody>
							".$card."
							 <tr>
		                <td height='10' colspan='3'></td></tr>
		   </tr></tbody></table></td></tr>

		</tbody></table></td></tr></tbody></table></td></tr></tbody></table>



		</body></html>";


if($time<=TIMESTAMP){
	//可以投递
	$data=array(
			'uniacid'=>$uniacid,
			'openid'=>$openid,
			'pid'=>$pid,
			'createtime'=>TIMESTAMP
	);
	$res=pdo_insert('enjoy_recuit_deliver',$data);
	if($res==1){
		//投递成功,职位投递数目++
		pdo_query("update ".tablename('enjoy_recuit_position')." set deliveries=deliveries+1 where uniacid = '{$_W['uniacid']}' and id=".$pid);
		//发邮件通知HR
		load()->func('communication');
		$result = ihttp_email($email, $subject, $body);
		$results['flag']=1;
	}else{
		$results['flag']=0;
	}
		
}else{
	//不能投递，提示7天内不能重复投递同一职位

	$results['flag']=-1;
		
		
}

echo json_encode($results);



