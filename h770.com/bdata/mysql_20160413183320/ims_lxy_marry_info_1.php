<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lxy_marry_info`;");
E_C("CREATE TABLE `ims_lxy_marry_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `weid` bigint(20) unsigned DEFAULT NULL,
  `fromuser` varchar(32) DEFAULT NULL,
  `sid` bigint(20) unsigned DEFAULT NULL COMMENT 'micro_xitie_set id',
  `name` varchar(25) DEFAULT NULL,
  `tel` varchar(25) DEFAULT NULL,
  `rs` smallint(1) DEFAULT NULL COMMENT '赴宴人数',
  `zhufu` varchar(255) DEFAULT NULL COMMENT '收到祝福',
  `ctime` datetime DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1' COMMENT '1:赴宴 2：祝福',
  PRIMARY KEY (`id`),
  KEY `idx_sid_openid` (`sid`,`fromuser`),
  KEY `idx_sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微喜帖信息列表'");

require("../../inc/footer.php");
?>