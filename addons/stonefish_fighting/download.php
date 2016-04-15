<?php
/**
 * 模块定义：导出功能
 *
 * @author 石头鱼
 * @url http://www.00393.com/
 */
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$rid= intval($_GPC['rid']);
$data= $_GPC['data'];
if(empty($rid)){
    message('抱歉，传递的参数错误！','', 'error');              
}
$reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_fighting_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
$exchange = pdo_fetch("select * FROM ".tablename("stonefish_fighting_exchange")." where rid = :rid", array(':rid' => $rid));
if(empty($reply)){
    message('抱歉，活动不存在！','', 'error');              
}
$isfansname = explode(',',$exchange['isfansname']);

if($data=='fansdata'){
    $statustitle='全部用户';
	$list = pdo_fetchall("SELECT * FROM ".tablename('stonefish_fighting_fans')."  WHERE rid = :rid and uniacid=:uniacid  ORDER BY id DESC" , array(':rid' => $rid,':uniacid'=>$_W['uniacid']));
	$tableheader = array('ID');
	$ziduan = array('realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position');
	$k = 0;
	foreach ($ziduan as $ziduans) {
		if($exchange['is'.$ziduans]){
			$tableheader[]=$isfansname[$k];
		}
		$k++;
	}
	$tableheader[]='参与者微信码';
	if($reply['issubscribe']==4){
		$tableheader[]='所属分组';
	}elseif($reply['issubscribe']==5){
		$tableheader[]='部门科室';
	}
	$tableheader[]='参与次数';
	$tableheader[]='今日得分';
	$tableheader[]='总得分';
	$tableheader[]='参与时间';
    $html = "\xEF\xBB\xBF";
    foreach ($tableheader as $value) {
	    $html .= $value . "\t ,";
    }
    $html .= "\n";
    foreach ($list as $value) {
	    $html .= $value['id'] . "\t ,";	   
	    foreach ($ziduan as $ziduans) {
			if($exchange['is'.$ziduans]){
				if($ziduans=='gender'){
					if($value[$ziduans]==0){
						$html .= "保密\t ,";	
					}
					if($value[$ziduans]==1){
						$html .= "男\t ,";	
					}
					if($value[$ziduans]==2){
						$html .= "女\t ,";	
					}
				}else{
					$html .= $value[$ziduans] . "\t ,";	
				}
			}
		}
	    $html .= $value['from_user'] . "\t ,";
		if($reply['issubscribe']>=4){
			$uid = pdo_fetchcolumn("select uid FROM ".tablename('mc_mapping_fans') ." where openid=:openid and uniacid=:uniacid",array(":openid"=>$value['from_user'],":uniacid"=>$_W['uniacid']));
			$members = pdo_fetch("select `groupid`,`departmentid` FROM ".tablename('stonefish_member')." where `uniacid`=:uniacid AND `uid` = :uid",array(':uniacid' => $_W['uniacid'],':uid' => $uid));
		}
		if($reply['issubscribe']==4){
			$group = pdo_fetchcolumn("select gname FROM ".tablename('stonefish_member_group') ." where id=:groupid",array(":groupid"=>$members['groupid']));
		    $html .= $group . "\t ,";
	    }elseif($reply['issubscribe']==5){
		    $department = pdo_fetchcolumn("select gname FROM ".tablename('stonefish_member_department') ." where id=:departmentid",array(":departmentid"=>$members['departmentid']));
			$html .= $department . "\t ,";
	    }
		$html .= $value['totalnum'] . "\t ,";
		$html .= $value['day_credit'] . "\t ,";
		$html .= $value['last_credit'] . "\t ,";
	    $html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";
    }
}elseif($data=='rankdata'){
    $rank = $_GPC['rank'];
	if(!empty($rank)){        
	    if($rank == 'sharenum'){
		    $statustitle='分享值排行榜';
			$ORDER ='sharenum';
	    }elseif($rank == 'day'){
		    $statustitle='今日排行榜';
			$ORDER ='day_credit';
			$nowtime = strtotime(date('Y-m-d'));
		}elseif($rank == 'rank'){
		    $statustitle='总排行榜';
			$ORDER ='last_credit';
		}
    }else{
        $statustitle='总排行榜';
		$ORDER ='last_credit';
    }
	$statustitle.='排名';
	if($rank == 'day'){
		$list = pdo_fetchall("SELECT * FROM ".tablename('stonefish_fighting_fans')."  WHERE rid = :rid and uniacid=:uniacid and last_time>=:last_time ORDER BY ".$ORDER." DESC,id asc" , array(':rid' => $rid,':uniacid'=>$_W['uniacid'],':last_time'=>$nowtime));
	}else{
		$list = pdo_fetchall("SELECT * FROM ".tablename('stonefish_fighting_fans')."  WHERE rid = :rid and uniacid=:uniacid ORDER BY ".$ORDER." DESC,id asc" , array(':rid' => $rid,':uniacid'=>$_W['uniacid']));
	}	
	$tableheader = array('ID', '名次');
	$ziduan = array('realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position');
	$k = 0;
	foreach ($ziduan as $ziduans) {
		if($exchange['is'.$ziduans]){
			$tableheader[]=$isfansname[$k];
		}
		$k++;
	}
	$tableheader[]='中奖者微信码';
	if($reply['issubscribe']==4){
		$tableheader[]='所属分组';
	}elseif($reply['issubscribe']==5){
		$tableheader[]='部门科室';
	}
	$tableheader[]='参与次数';
	$tableheader[]='今日得分';
	$tableheader[]='总得分';
	$tableheader[]='参与时间';
	$tableheader[]='最后参与时间';
    $html = "\xEF\xBB\xBF";
    foreach ($tableheader as $value) {
	    $html .= $value . "\t ,";
    }
    $html .= "\n";
	$i = 1;
    foreach ($list as $value) {
	    $html .= $value['id'] . "\t ,";
		$html .= $i . "\t ,";	   
	    foreach ($ziduan as $ziduans) {
			if($exchange['is'.$ziduans]){
				if($ziduans=='gender'){
					if($value[$ziduans]==0){
						$html .= "保密\t ,";	
					}
					if($value[$ziduans]==1){
						$html .= "男\t ,";	
					}
					if($value[$ziduans]==2){
						$html .= "女\t ,";	
					}
				}else{
					$html .= $value[$ziduans] . "\t ,";	
				}
			}
		}
	    $html .= $value['from_user'] . "\t ,";
		if($reply['issubscribe']>=4){
			$uid = pdo_fetchcolumn("select uid FROM ".tablename('mc_mapping_fans') ." where openid=:openid and uniacid=:uniacid",array(":openid"=>$value['from_user'],":uniacid"=>$_W['uniacid']));
			$members = pdo_fetch("select `groupid`,`departmentid` FROM ".tablename('stonefish_member')." where `uniacid`=:uniacid AND `uid` = :uid",array(':uniacid' => $_W['uniacid'],':uid' => $uid));
		}
		if($reply['issubscribe']==4){
			$group = pdo_fetchcolumn("select gname FROM ".tablename('stonefish_member_group') ." where id=:groupid",array(":groupid"=>$members['groupid']));
		    $html .= $group . "\t ,";
	    }elseif($reply['issubscribe']==5){
		    $department = pdo_fetchcolumn("select gname FROM ".tablename('stonefish_member_department') ." where id=:departmentid",array(":departmentid"=>$members['departmentid']));
			$html .= $department . "\t ,";
	    }
		$html .= $value['totalnum'] . "\t ,";
		$html .= $value['day_credit'] . "\t ,";
		$html .= $value['last_credit'] . "\t ,";
	    $html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,";
		$html .= date('Y-m-d H:i:s', $value['last_time']) . "\n";
		$i++;
    }
}
header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=".$statustitle.$award."数据_".$rid.".csv");
echo $html;
exit();