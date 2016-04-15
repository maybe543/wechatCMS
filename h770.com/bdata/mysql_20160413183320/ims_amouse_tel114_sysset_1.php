<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_tel114_sysset`;");
E_C("CREATE TABLE `ims_amouse_tel114_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `copyright` varchar(255) DEFAULT '' COMMENT '版权',
  `comurl` varchar(500) DEFAULT '' COMMENT '公司网址',
  `comdate` varchar(255) DEFAULT '' COMMENT '公司年份',
  `followurl` varchar(1000) DEFAULT '' COMMENT '版权',
  `sharetitle` varchar(1000) DEFAULT '' COMMENT '分享标题',
  `sharedesc` varchar(1000) DEFAULT '' COMMENT '分享描述',
  `shareicon` varchar(1000) DEFAULT '' COMMENT '分享缩略图',
  `thumb` varchar(1000) DEFAULT '' COMMENT '底部图片',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  `isopen` varchar(1) DEFAULT '0' COMMENT '是否审核',
  `logo` varchar(500) DEFAULT '' COMMENT '首页LOGO',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>