<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_feng_rebate_record`;");
E_C("CREATE TABLE `ims_feng_rebate_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(45) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `createtime` varchar(45) NOT NULL,
  `goodsid` int(11) NOT NULL COMMENT '商品id',
  `yqm` varchar(45) NOT NULL COMMENT '邀请码',
  `cjm` varchar(11) NOT NULL COMMENT '抽奖码1',
  `goodsn` varchar(45) NOT NULL COMMENT '商品上架标识',
  `ordersn` varchar(45) NOT NULL,
  `cjmfromopenid` varchar(45) NOT NULL COMMENT '抽奖码来自的openid',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>