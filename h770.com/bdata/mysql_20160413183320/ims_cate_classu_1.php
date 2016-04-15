<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_classu`;");
E_C("CREATE TABLE `ims_cate_classu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `classid` int(10) unsigned DEFAULT '0',
  `type` varchar(20) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `title_color` varchar(20) DEFAULT NULL,
  `descriptions` text,
  `fare` decimal(10,2) unsigned DEFAULT '0.00',
  `cash` varchar(10) DEFAULT '否' COMMENT '是否货到付款',
  `inorder` int(10) unsigned DEFAULT '0',
  `status` varchar(10) DEFAULT '使用中',
  `setting` text,
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 配送方式、支付方式 (用户)'");

require("../../inc/footer.php");
?>