<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
$pindex = max ( 1, intval ( $_GPC ['page'] ) );
$psize = 10;
$list = pdo_fetchall ( 'SELECT * FROM ' . tablename ( $this->table_reply ) . ' WHERE uniacid=:uniacid ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array (
		':uniacid' => $_W ['uniacid'] 
) );
$total = pdo_fetchcolumn ( 'SELECT COUNT(1) FROM ' . tablename ( $this->table_reply ) . ' WHERE uniacid=:uniacid', array (
		':uniacid' => $_W ['uniacid'] 
) );
$page = pagination ( $total, $pindex, $psize );
include $this->template ( 'web/activity' );
?>