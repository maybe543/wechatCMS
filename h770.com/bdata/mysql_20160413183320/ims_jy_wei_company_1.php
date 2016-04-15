<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jy_wei_company`;");
E_C("CREATE TABLE `ims_jy_wei_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `url` varchar(255) NOT NULL COMMENT '公司URL',
  `title` varchar(255) NOT NULL COMMENT '网站title',
  `name` varchar(255) NOT NULL COMMENT '公司名称',
  `shortname` varchar(255) NOT NULL COMMENT '公司名称简写',
  `banner` varchar(255) NOT NULL COMMENT 'Banner',
  `logo` varchar(255) NOT NULL COMMENT 'Logo',
  `propagenda` varchar(255) NOT NULL COMMENT '一句话公司宣传语',
  `description` varchar(255) NOT NULL COMMENT '简介',
  `shareimage` varchar(255) NOT NULL COMMENT '分享图片',
  `sharetitle` varchar(255) NOT NULL COMMENT '分享标题',
  `sharedescription` varchar(255) NOT NULL COMMENT '分享描述',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>