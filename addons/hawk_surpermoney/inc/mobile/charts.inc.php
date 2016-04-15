<?php
global $_W,$_GPC;
require_once HK_ROOT . '/module/Fan.class.php';
$fan = new Fan();
$data = $fan->getAll();
include $this->template('charts');