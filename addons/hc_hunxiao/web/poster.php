<?php
	$id = intval($_GPC['id']);
    $item = pdo_fetch('SELECT * FROM ' . tablename('hc_hunxiao_poster') . ' WHERE uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
    $id = $item['id'];
    if (checksubmit('submit')) {
        load()->model('account');
        $acid = pdo_fetchcolumn('select acid from ' . tablename('account_wechats') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
        $data = array(
			'uniacid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'keyword' => $_GPC['keyword'],
			'bg' => $_GPC['bg'],
			'data' => htmlspecialchars_decode($_GPC['data']),
			'waittext' => $_GPC['waittext'],
			'createtime' => time(),
		);
		if($item['data']!=htmlspecialchars_decode($_GPC['data']) || $item['bg']!=$_GPC['bg']){
			$members = pdo_fetchall('SELECT id FROM ' . tablename('hc_hunxiao_member') . ' WHERE weid=:uniacid', array(':uniacid' => $_W['uniacid']));
			foreach($members as $m){
				pdo_update('hc_hunxiao_member', array('ischange'=>1), array('id' => $m['id']));
			}
		}
        if (!empty($id)) {
            pdo_update('hc_hunxiao_poster', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
        } else {
            pdo_insert('hc_hunxiao_poster', $data);
            $id = pdo_insertid();
        }
        $rule = pdo_fetch('select * from ' . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'hc_hunxiao', ':name' => $item['title']));
		if (empty($rule)) {
            $rule_data = array('uniacid' => $_W['uniacid'], 'name' => $_GPC['title'], 'module' => 'hc_hunxiao', 'displayorder' => 0, 'status' => 1);
            pdo_insert('rule', $rule_data);
            $rid = pdo_insertid();
            $keyword_data = array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'module' => 'hc_hunxiao', 'content' => $_GPC['keyword'], 'type' => 1, 'displayorder' => 0, 'status' => 1);
            pdo_insert('rule_keyword', $keyword_data);
        } else {
            pdo_update('rule_keyword', array('content' => $_GPC['keyword']), array('rid' => $rule['id']));
            pdo_update('rule', array('name' => $_GPC['title']), array('id' => $rule['id']));
        }
        message('更新海报成功！', $this->createWebUrl('poster', array('op' => 'display')), 'success');
    }
	if (!empty($item)) {
        $data = json_decode(str_replace('&quot;', '\'', $item['data']), true);
    }
	include $this->template('poster/poster');
?>