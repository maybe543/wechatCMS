<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_house`;");
E_C("CREATE TABLE `ims_amouse_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(25) NOT NULL COMMENT '标题',
  `price` varchar(100) NOT NULL COMMENT '租金总价',
  `square_price` varchar(100) NOT NULL COMMENT '每平方价格',
  `area` varchar(100) NOT NULL COMMENT '面积',
  `house_type` varchar(100) NOT NULL COMMENT '户型',
  `floor` varchar(100) NOT NULL COMMENT '楼层',
  `orientation` varchar(100) NOT NULL COMMENT '朝向',
  `type` varchar(2) NOT NULL COMMENT '0：出租；1：求租；2：出售/3：求购',
  `status` varchar(2) NOT NULL COMMENT '是否显示/审核',
  `recommed` int(1) NOT NULL COMMENT '推荐 0未推荐 1推荐',
  `contacts` varchar(100) NOT NULL COMMENT '联系人',
  `phone` varchar(13) NOT NULL COMMENT '联系电话',
  `introduction` text NOT NULL COMMENT '详细描述',
  `openid` varchar(25) NOT NULL COMMENT '微信OPENID',
  `createtime` int(10) NOT NULL,
  `thumb3` varchar(1000) NOT NULL DEFAULT '',
  `thumb4` varchar(1000) NOT NULL DEFAULT '',
  `thumb1` varchar(1000) NOT NULL DEFAULT '',
  `thumb2` varchar(1000) NOT NULL DEFAULT '',
  `place` varchar(1000) NOT NULL DEFAULT '',
  `lat` varchar(1000) NOT NULL DEFAULT '0.0000000000',
  `lng` varchar(1000) NOT NULL DEFAULT '0.0000000000',
  `location_p` varchar(1000) NOT NULL DEFAULT '',
  `location_c` varchar(1000) NOT NULL DEFAULT '',
  `location_a` varchar(1000) NOT NULL DEFAULT '',
  `brokerage` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='租房出售'");

require("../../inc/footer.php");
?>