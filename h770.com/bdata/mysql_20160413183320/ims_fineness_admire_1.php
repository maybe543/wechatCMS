<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fineness_admire`;");
E_C("CREATE TABLE `ims_fineness_admire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `aid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `author` varchar(255) NOT NULL COMMENT '昵称',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `ordersn` varchar(255) NOT NULL COMMENT '订单号',
  `thumb` varchar(500) NOT NULL COMMENT '头像',
  `status` varchar(2) NOT NULL COMMENT '是否显示',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '赞赏价格',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>