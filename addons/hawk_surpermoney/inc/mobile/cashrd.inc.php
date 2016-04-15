<?php
global $_W,$_GPC;
require_once HK_ROOT . '/module/Cashrecord.class.php';
$cashrd = new Cashrecord();
$data = $cashrd->getAll();
include $this->template('cashrd');