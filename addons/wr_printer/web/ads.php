<?php
$id = intval($_GPC['id']);
if ($_GPC['op'] == 'delete') {
    $pic = pdo_fetch('SELECT rid,pic FROM ' . tablename('wr_printer_ads') . ' where id=' . $id);
    $rid = $pic['rid'];
    load()->func('file');
    file_delete($pic['pic']);
    pdo_delete('wr_printer_ads', array(
        'id' => $id
    ));
    message('删除成功！', $this->createWebUrl('ads', array(
        'id' => $rid
    )), 'success');
}
if (checksubmit('submit')) {
    $rid = intval($_GPC['id']);
    if (!empty($_GPC['ads_new'])) {
        foreach ($_GPC['ads_new'] as $index => $row) {
            if (empty($row)) {
                continue;
            }
            $data = array(
                'rid' => $rid,
                'pic' => $_GPC['ads_new'][$index],
                'url' => $_GPC['url-new'][$index],
                'listorder' => $_GPC['listorder-new'][$index]
            );
            pdo_insert('wr_printer_ads', $data);
        }
    }
    if (!empty($_GPC['attachment'])) {
        foreach ($_GPC['attachment'] as $index => $row) {
            if (empty($row)) {
                continue;
            }
            $data = array(
                'rid' => $rid,
                'pic' => $_GPC['attachment'][$index],
                'url' => $_GPC['url'][$index],
                'listorder' => $_GPC['listorder'][$index],
                'isshow' => $_GPC['isshow'][$index]
            );
            pdo_update('wr_printer_ads', $data, array(
                'id' => $index
            ));
        }
    }
    message('广告图片更新成功！', $this->createWebUrl('ads', array(
        'id' => $rid
    )), 'success');
}
$photos = pdo_fetchall("SELECT * FROM " . tablename('wr_printer_ads') . " WHERE rid = :rid ORDER BY listorder", array(
    ':rid' => $id
));
include $this->template('ads');
?>