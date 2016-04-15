<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_dayu_yuyuepay`;");
E_C("CREATE TABLE `ims_dayu_yuyuepay` (
  `reid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL,
  `content` text NOT NULL,
  `information` varchar(500) NOT NULL DEFAULT '',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `inhome` tinyint(4) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `kaishi` int(11) NOT NULL DEFAULT '1',
  `jieshu` int(11) NOT NULL DEFAULT '22',
  `tianshu` int(11) NOT NULL DEFAULT '15',
  `pretotal` int(10) unsigned NOT NULL DEFAULT '1',
  `pay` int(10) unsigned NOT NULL DEFAULT '1',
  `xmshow` int(10) unsigned NOT NULL DEFAULT '0',
  `xmname` varchar(50) NOT NULL DEFAULT '',
  `yuyuename` varchar(50) NOT NULL DEFAULT '',
  `noticeemail` varchar(50) NOT NULL DEFAULT '',
  `k_templateid` varchar(50) NOT NULL DEFAULT '',
  `kfid` varchar(50) NOT NULL DEFAULT '',
  `m_templateid` varchar(50) NOT NULL DEFAULT '',
  `accountsid` varchar(50) NOT NULL DEFAULT '',
  `tokenid` varchar(50) NOT NULL DEFAULT '',
  `appId` varchar(50) NOT NULL DEFAULT '',
  `templateId` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `mname` varchar(10) NOT NULL DEFAULT '',
  `skins` varchar(20) NOT NULL DEFAULT 'submit',
  `kfirst` varchar(100) NOT NULL COMMENT '客服模板页头',
  `kfoot` varchar(100) NOT NULL COMMENT '客服模板页尾',
  `mfirst` varchar(100) NOT NULL COMMENT '客户模板页头',
  `mfoot` varchar(100) NOT NULL COMMENT '客户模板页尾',
  `share_url` varchar(100) NOT NULL DEFAULT '',
  `follow` tinyint(1) DEFAULT '0',
  `code` tinyint(1) DEFAULT '0',
  `is_time` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`reid`),
  KEY `weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>