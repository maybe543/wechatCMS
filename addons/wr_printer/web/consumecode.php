<?php
global $_GPC, $_W;
checklogin();
$id = intval($_GPC['id']);
if (checksubmit('delete') && !empty($_GPC['select'])) {
    pdo_delete('wr_printer_consumecode', " id  IN  ('" . implode("','", $_GPC['select']) . "')");
    message('删除成功！', $this->createWebUrl('add_consumecode', array(
        'id' => $id,
        'page' => $_GPC['page']
    )));
}
$where  = 'where rid=:rid and weid=:weid';
$params = array(
    ':rid' => $id,
    ':weid' => $_W['weid']
);
if (!empty($_GPC['status'])) {
    if ($_GPC['status'] < 3) {
        $where .= ' and status=:status';
        $params[':status'] = $_GPC['status'] - 1;
    } else {
        $where .= ' and stype=:status';
        $params[':status'] = $_GPC['status'] - 3;
    }
}
if (!empty($_GPC['keywords'])) {
    $where .= ' and consumecode like :keywords';
    $params[':keywords'] = "%{$_GPC['keywords']}%";
}
$pindex = max(1, intval($_GPC['page']));
$psize  = 30;
$list   = pdo_fetchall("SELECT a.id,a.rid,a.consumecode,a.stype,a.status,a.create_time,a.use_time FROM " . tablename('wr_printer_consumecode') . " AS a " . $where . " ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}", $params);
if (!empty($list)) {
    $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wr_printer_consumecode') . $where . "", $params);
    $total1 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wr_printer_consumecode') . $where . " and status=1", $params);
    $total0 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wr_printer_consumecode') . $where . " and status=0", $params);
    $pager  = pagination($total, $pindex, $psize);
}
$num1 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('wr_printer_consumecode') . " WHERE rid = '{$id}'");
$num2 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('wr_printer_consumecode') . " WHERE rid = '{$id}' and status=1");
$num3 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('wr_printer_consumecode') . " WHERE rid = '{$id}' and status=0");
include $this->template('consumecode');
