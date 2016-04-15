<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fwei_nearshop`;");
E_C("CREATE TABLE `ims_fwei_nearshop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `thumb` varchar(100) NOT NULL DEFAULT '' COMMENT '宣传图',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '介绍',
  `phone` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT 'QQ',
  `province` varchar(50) NOT NULL DEFAULT '' COMMENT '省',
  `city` varchar(50) NOT NULL DEFAULT '' COMMENT '市',
  `dist` varchar(50) NOT NULL DEFAULT '' COMMENT '区',
  `address` varchar(500) NOT NULL DEFAULT '' COMMENT '详细地址',
  `lng` varchar(10) NOT NULL DEFAULT '' COMMENT '百度地图坐标',
  `lat` varchar(10) NOT NULL DEFAULT '' COMMENT '百度地图坐标',
  `soso_lng` varchar(10) NOT NULL DEFAULT '' COMMENT '腾讯地图坐标',
  `soso_lat` varchar(10) NOT NULL DEFAULT '' COMMENT '腾讯地图坐标',
  `industry1` varchar(10) NOT NULL DEFAULT '' COMMENT '行业信息',
  `industry2` varchar(10) NOT NULL DEFAULT '' COMMENT '行业信息',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `outlink` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_lat_lng` (`lng`,`lat`),
  KEY `idx_soso_lat_lng` (`soso_lng`,`soso_lat`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>