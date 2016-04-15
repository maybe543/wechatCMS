<?php
global $_W, $_GPC;
$foo = $_GPC['foo'];
$foos = array('list', 'create', 'modify', 'delete', 'records');
$foo = in_array($foo, $foos) ? $foo : 'list';
require_once HK_ROOT . '/module/Money.class.php';
require_once HK_ROOT . '/module/Excel.class.php';
require_once HK_ROOT . '/module/Fan.class.php';
$a = new Money();
if($foo == 'create') {
    if($_W['ispost']) {
        $input = $_GPC;
        $input['content'] = htmlspecialchars_decode($input['content']);
        $input['starttime'] = strtotime($input['time']['start'] . ':00');
        $input['endtime'] = strtotime($input['time']['end'] . ':59');
        $ret = $a->create($input);
        if(is_error($ret)) {
            message($ret['message']);
        } else {
            message("内容添加成功", $this->createWebUrl('money'));
        }
    }
    load()->func('tpl');
    include $this->template('money-form');
}

if($foo == 'modify') {
    $id = $_GPC['id'];
    $id = intval($id);
    $article = $a->getOne($id);
    if(empty($article)) {
        $this->error('访问错误');
    }
    if($_W['ispost']) {
        $input = $_GPC;
        $input['content'] = htmlspecialchars_decode($input['content']);
        $input['starttime'] = strtotime($input['time']['start'] . ':00');
        $input['endtime'] = strtotime($input['time']['end'] . ':59');
        $ret = $a->modify($id, $input);
        if(is_error($ret)) {
            message($ret['message']);
        } else {
            message("文章更新成功", $this->createWebUrl('money'));
        }
    }
    $time['start'] = date('Y-m-d H:i', $article['starttime']);
    $time['end'] = date('Y-m-d H:i', $article['endtime']);
    load()->func('tpl');
    include $this->template('money-form');
}

if($foo == 'records') {
    load()->model('mc');
    $id = $_GPC['id'];
    $id = intval($id);
    $article = $a->getOne($id);

    if(empty($article)) {
        $this->error('访问错误');
    }
    $filters = array();
    $filters['articleid'] = $id;
	$filters['nickname'] = $_GPC['nickname'];
    $filters['openid'] = $_GPC['openid'];
	$filters['status'] = $_GPC['status'];

    $pindex = intval($_GPC['page']);
    $pindex = max($pindex, 1);
    $psize = 15;
    $total = 0;
	$ds = "";

    $ds = $a->getRecords($filters, $pindex, $psize, $total);
    $pager = pagination($total, $pindex, $psize);

	if ($_GPC['export'] != '') {
		$elem = array('id','openid','nickname','money','status','log');
		$excel = new Excel();
		$file = array(
			'title'=>'领奖表',
			'编号',
			'OPENID',
			'昵称',
			'领取金额',
			'领取状态',
			'领取日志'
		);
		$excel->down($file,$ds,$elem);
	}

    include $this->template('money-records');
}


if($foo == 'delete') {
    $id = $_GPC['id'];
    $id = intval($id);
    $ret = $a->remove($id);
    if(is_error($ret)) {
        message($ret['message']);
    } else {
        message('删除操作成功', $this->createWebUrl('money'));
    }
}

if($foo == 'list') {
    $a = new Money();
	$ds = $a->getAll(array());
    if(is_array($ds)) {
        foreach($ds as &$row) {
            $row['count'] = $a->calcCount($row['id']);
            $row['remains'] = $row['limit'] - $row['count']['total'];
			$url = $this->createMobileUrl('article', array('id' => $row['id']));
            $row['surl'] = $url;
            $url = substr($url, 2);
            $url = $_W['siteroot'] . 'app/' . $url;
            $row['url'] = $url;
        }
        unset($row);
    }
    
    include $this->template('money-list');
}
?>