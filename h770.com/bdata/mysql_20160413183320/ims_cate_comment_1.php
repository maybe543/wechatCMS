<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_comment`;");
E_C("CREATE TABLE `ims_cate_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `wxname` varchar(100) DEFAULT NULL COMMENT '用户微信名称',
  `anonyname` varchar(100) DEFAULT NULL COMMENT '昵称，前台显示（匿名处理）',
  `cartid` int(10) unsigned DEFAULT '0' COMMENT '订单ID',
  `goodsid` int(10) unsigned DEFAULT '0' COMMENT '商品ID',
  `goodstitle` varchar(255) DEFAULT NULL,
  `score` tinyint(3) unsigned DEFAULT '0' COMMENT '评价等级',
  `content` text COMMENT '评论内容',
  `recon` text COMMENT '回复内容',
  `attr` varchar(255) DEFAULT NULL COMMENT '购买配置',
  `anony` tinyint(3) unsigned DEFAULT '0' COMMENT '是否匿名，1为匿名',
  `indate` bigint(18) unsigned DEFAULT '0',
  `inip` varchar(15) DEFAULT NULL COMMENT 'IP',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 评论'");

require("../../inc/footer.php");
?>