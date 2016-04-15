<?php
global $_W,$_GPC;
require_once HK_ROOT . '/module/Money.class.php';
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$total= 0;
$m = new Money();
$filters['status'] = 0;
$ds= $m->getAll($filters,$pindex,$psize,$total);
if(is_array($ds)){
    foreach($ds as $k=>&$v){
        $nums = count($v['records']);
        $v['remainder'] = $v['totalmoney'] - $nums * $v['money'];
    }
}
$pager = pagination($total, $pindex, $psize);
$low = $this->module['config']['api']['low'];
$low = $low ? $low/100 : 2;
include $this->template('list');