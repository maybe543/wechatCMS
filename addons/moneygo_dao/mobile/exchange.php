<?php
	if (empty($_GPC['id'])) {
        message('抱歉，参数错误！', '', 'error');
    }
	
 function getshu(){
   	global $_W;
	$xinxi = pdo_fetch("SELECT * FROM".tablename('moneygo_wechat')."WHERE uniacid='{$_W['uniacid']}'");
	return $xinxi['jishu'];
   }
 
  $jishu = getshu();
	
	$id = intval($_GPC['id']);
	$uniacid=$_W['uniacid'];
	$goods = pdo_fetch("SELECT * FROM ".tablename('moneygo_goodslist')." WHERE uniacid = '{$uniacid}' and id = '{$id}' ");
	include $this->template('exchange');
?>