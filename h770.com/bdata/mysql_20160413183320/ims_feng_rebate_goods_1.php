<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_feng_rebate_goods`;");
E_C("CREATE TABLE `ims_feng_rebate_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gname` varchar(45) NOT NULL,
  `thumb` varchar(115) NOT NULL,
  `price` varchar(45) NOT NULL,
  `num` int(11) NOT NULL,
  `status` int(4) NOT NULL,
  `gdesc` varchar(1024) NOT NULL COMMENT '商品描述',
  `hours` int(11) NOT NULL COMMENT '限时抢购',
  `uniacid` int(11) NOT NULL,
  `createtime` varchar(45) NOT NULL,
  `goodsn` varchar(45) NOT NULL COMMENT '商品每日上架标识',
  `uptime` varchar(45) NOT NULL COMMENT '上架时间',
  `fanli` varchar(45) NOT NULL COMMENT '返利倍数',
  `fanli_num` varchar(45) NOT NULL COMMENT '返利倍数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>