<?php
global $_GPC, $_W;
checklogin();
$id    = intval($_GPC['id']);
$stype = $_GPC['stype'];
if ((!empty($id)) && ($stype == 0 || $stype == 1)) {
    for ($i = 0; $i < 30; $i++) {
        $insert = array(
            'rid' => $id,
            'weid' => $_W['weid'],
            'consumecode' => random(5, true),
            'status' => 0,
            'stype' => $stype,
            'create_time' => time()
        );
        $ids    = pdo_insert('wr_printer_consumecode', $insert);
    }
    message('消费码生成成功！', $this->createWebUrl('consumecode', array(
        'id' => $id,
        'page' => $_GPC['page']
    )));
}