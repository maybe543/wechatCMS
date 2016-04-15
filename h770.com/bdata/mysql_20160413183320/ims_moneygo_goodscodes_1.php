<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_moneygo_goodscodes`;");
E_C("CREATE TABLE `ims_moneygo_goodscodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众账号',
  `s_id` int(10) unsigned NOT NULL COMMENT '商品ID',
  `s_cid` smallint(5) unsigned NOT NULL,
  `s_len` smallint(5) DEFAULT NULL COMMENT '长度',
  `s_codes` longtext COMMENT '商品码',
  `s_codes_tmp` longtext COMMENT '商品码备份',
  PRIMARY KEY (`id`),
  KEY `s_id` (`s_id`),
  KEY `uniacid` (`uniacid`),
  KEY `s_len` (`s_len`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>