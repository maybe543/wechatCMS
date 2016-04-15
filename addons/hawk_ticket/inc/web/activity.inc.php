<?php
global $_W, $_GPC;
$foo = $_GPC['foo'];
$foos = array('list', 'create', 'modify', 'delete', 'order','change','log');
$foo = in_array($foo, $foos) ? $foo : 'list';
require_once HK_ROOT . '/module/Activity.class.php';
require_once HK_ROOT . '/module/Order.class.php';
require_once HK_ROOT . '/module/Excel.class.php';
require_once HK_ROOT . '/module/Log.class.php';
$a = new Activity();
$o = new Order();
$l = new Log();
if($foo == 'create') {
$group = pdo_fetchall('SELECT groupid,title FROM ' . tablename('mc_groups') . " WHERE uniacid = '{$_W['uniacid']}' ");
    if($_W['ispost']) {
        $input = $_GPC;
        $input['content'] = htmlspecialchars_decode($input['content']);
        $input['starttime'] = strtotime($input['time']['start'] . ':00');
        $input['endtime'] = strtotime($input['time']['end'] . ':59');
        $input['groups'] = serialize($input['group']);
        $ret = $a->create($input);
        if(is_error($ret)) {
            message($ret['message']);
        } else {
            message("活动添加成功", $this->createWebUrl('activity'));
        }
    }
    load()->func('tpl');
    include $this->template('activity-form');
}

if($foo == 'modify') {
    $id = $_GPC['id'];
    $id = intval($id);
    $activity = $a->getOne($id);
    if(empty($activity)) {
        message('访问错误',referfer,'error');
    }
    $grouparr = unserialize($activity['groups']);
	$group = pdo_fetchall('SELECT groupid,title FROM ' . tablename('mc_groups') . " WHERE uniacid = '{$_W['uniacid']}' ");
	if(!empty($grouparr)) {
			foreach($group as &$g){
				if(in_array($g['groupid'], $grouparr)) {
					$g['groupid_select'] = 1;
				}
			}
	}
    if($_W['ispost']) {
        $input = $_GPC;
        $input['content'] = htmlspecialchars_decode($input['content']);
        $input['starttime'] = strtotime($input['time']['start'] . ':00');
        $input['endtime'] = strtotime($input['time']['end'] . ':59');
        $input['groups'] = serialize($input['group']);
        $ret = $a->modify($id, $input);
        if(is_error($ret)) {
            message($ret['message']);
        } else {
            message("活动更新成功", $this->createWebUrl('activity'));
        }
    }
    $time['start'] = date('Y-m-d H:i', $activity['starttime']);
    $time['end'] = date('Y-m-d H:i', $activity['endtime']);
    load()->func('tpl');
    include $this->template('activity-form');
}

if($foo == 'order') {
    load()->model('mc');
    $id = $_GPC['id'];
    $id = intval($id);
    $activity = $a->getOne($id);

    if(empty($activity)) {
        message('访问错误',referfer,'error');
    }
    $filters = array();
    $filters['actid'] = $id;
	//$filters['scanown'] = $_GPC['scanown'];
    $filters['openid'] = $_GPC['openid'];
	$filters['status'] = $_GPC['status'];

    $pindex = intval($_GPC['page']);
    $pindex = max($pindex, 1);
    $psize = 15;
    $total = 0;
    $ds = $o->getAll($filters, $pindex, $psize, $total);
//    echo "<pre>";
//    print_r($ds);
//    echo "</pre>";
    $pager = pagination($total, $pindex, $psize);

	if ($_GPC['export'] != '') {
		$elem = array('id','openid','actid','fee','status','remark','createtime');
		$excel = new Excel();
		$file = array(
			'title'=>'票据表',
			'编号',
			'OPENID',
			'活动编号',
			'门票费用',
			'票据状态',
			'领取日志',
			'购票时间'
		);
		$excel->down($file,$ds,$elem);
	}

    include $this->template('orders');
}

if($foo == 'change') {
    $id = $_GPC['id'];
    $openid = $_GPC['opid'];
    if(empty($id) || empty($openid)){
        message('票据错误',referfer,'error');
        exit();
    }
    $id = intval($id);
    $orderdata = $o->checkOrder($id,$openid);
    $update=array();
    if($orderdata){
        if($orderdata['status']==2){
            $update['status'] = 3;
            $update['remark'] = '系统管理员后台验票';
            $update['scantime'] = TIMESTAMP;
        }elseif($orderdata['status']==3){
            $update['status'] = 2;
            //删除验票记录
            $l->remove($id);
            //增加后台操作记录
            $input = array();
            $input['orderid'] = $id;
            $input['actid'] = $orderdata['actid'];
            $input['type']=2;
            $input['scanown'] = '1';
            $l->create($input);
        }
        $res = $o->modify($id,$update);
        if($res){
            message('操作成功',$this->createWebUrl('activity',array('foo'=>'order','id'=>$orderdata['actid'])),'success');
            exit();
        }else{
            message('操作失败',$this->createWebUrl('activity',array('foo'=>'order','id'=>$orderdata['actid'])),'error');
            exit();
        }
    }

}
if($foo == 'delete') {
    $id = $_GPC['id'];
    $id = intval($id);
    $ret = $a->remove($id);
    if(is_error($ret)) {
        message($ret['message']);
    } else {
        message('删除操作成功', $this->createWebUrl('activity'));
    }
}

if($foo == 'list') {
    $pindex = intval($_GPC['page']);
    $pindex = max($pindex, 1);
    $psize = 20;
    $total = 0;
    $filters=array();
	$ds = $a->getAll($filters,$pindex, $psize, $total);
	$pager = pagination($total, $pindex, $psize);
    if(is_array($ds)) {
        foreach($ds as &$row) {
            $row['count'] = $a->calcCount($row['id']);
            $row['remains'] = $row['tlimit'] - $row['count']['total'];
			$url = $this->createMobileUrl('detail', array('id' => $row['id']));
            $row['surl'] = $url;
            $url = substr($url, 2);
            $url = $_W['siteroot'] . 'app/' . $url;
            $row['url'] = $url;
        }
        unset($row);
    }
    
    include $this->template('activity-list');
}

if($foo == 'log') {
    load()->model('mc');
    $id = $_GPC['id'];
    $id = intval($id);
    $orderinfo = $o->getOne($id,'');
    //print_r($orderinfo);
    if(empty($orderinfo)) {
        message('访问错误',referfer,'error');
    }
    $filters = array();
	$filters['orderid'] = $id;
    $pindex = intval($_GPC['page']);
    $pindex = max($pindex, 1);
    $psize = 15;
    $total = 0;
    $ds = $l->getMylog($filters, $pindex, $psize, $total);
//    echo "<pre>";
//    print_r($ds);
//    echo "</pre>";
    $pager = pagination($total, $pindex, $psize);
    include $this->template('log');
}
?>