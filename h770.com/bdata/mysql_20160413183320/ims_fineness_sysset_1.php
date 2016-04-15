<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fineness_sysset`;");
E_C("CREATE TABLE `ims_fineness_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `guanzhuUrl` varchar(255) DEFAULT '' COMMENT '引导关注',
  `guanzhutitle` varchar(255) DEFAULT '' COMMENT '引导关注名称',
  `historyUrl` varchar(255) DEFAULT '' COMMENT '历史记录外链',
  `copyright` varchar(255) DEFAULT '' COMMENT '版权',
  `cnzz` varchar(800) DEFAULT NULL,
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  `logo` varchar(255) DEFAULT '' COMMENT 'logo',
  `tjgzh` varchar(255) DEFAULT '1' COMMENT '推荐公众号图片',
  `tjgzhUrl` varchar(255) DEFAULT '1' COMMENT '推荐公众号引导关注',
  `isopen` varchar(1) DEFAULT '1',
  `title` varchar(255) DEFAULT '',
  `footlogo` varchar(255) DEFAULT '',
  `iscomment` varchar(1) DEFAULT '1',
  `isget` varchar(1) DEFAULT '0',
  `mchid` varchar(255) DEFAULT '',
  `shkey` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>