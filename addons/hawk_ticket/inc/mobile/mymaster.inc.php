<?php
global $_W,$_GPC;
$this->checkMobile();
require_once(MODULE_ROOT.'/module/Activity.class.php');
require_once(MODULE_ROOT.'/module/Order.class.php');
require_once(MODULE_ROOT.'/module/Log.class.php');
$log = new Log();
$filters = array();
$filters['scanown'] = $_W['fans']['from_user'];
$filters['type'] = 1;
$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 20;
$total = 0;
$ds = $log->getMylog($filters,$pindex, $psize, $total);
$pager = pagination($total, $pindex, $psize);
include $this->template('mymaster');