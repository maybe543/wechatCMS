<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickcredit_goods`;");
E_C("CREATE TABLE `ims_quickcredit_goods` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `deadline` datetime NOT NULL,
  `per_user_limit` int(11) NOT NULL DEFAULT '0',
  `cost` int(11) NOT NULL DEFAULT '0',
  `cost_type` int(11) NOT NULL DEFAULT '1' COMMENT '1系统积分 2会员积分 4,8等留作扩展',
  `price` int(11) NOT NULL DEFAULT '100',
  `vip_require` int(10) NOT NULL DEFAULT '0' COMMENT '兑换最低VIP级别',
  `content` text NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '是否需要填写收货地址,1,实物需要填写地址,0虚拟物品不需要填写地址',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>