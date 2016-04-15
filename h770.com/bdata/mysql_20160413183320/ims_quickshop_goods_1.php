<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickshop_goods`;");
E_C("CREATE TABLE `ims_quickshop_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `pgoodsid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父类商品ID，用于多规格场景',
  `support_delivery` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1为支持货到付款',
  `goodstype` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `sendtype` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1为快递，2为自提',
  `credittype` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '返现类型, 1为credit1，2为credit2',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `unit` varchar(5) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `cover_content` text NOT NULL COMMENT '分销模式下在封面页显示的内容',
  `goodssn` varchar(50) NOT NULL DEFAULT '',
  `productsn` varchar(50) NOT NULL DEFAULT '',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售价',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `costprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成本价',
  `total` int(10) NOT NULL DEFAULT '0',
  `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `spec` varchar(4096) NOT NULL COMMENT '商品规格描述字符串',
  `createtime` int(10) unsigned NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit` int(11) DEFAULT '0',
  `maxbuy` int(11) DEFAULT '0',
  `hasoption` int(11) DEFAULT '0',
  `dispatch` int(11) DEFAULT '0',
  `thumb_url` text,
  `isnew` int(11) DEFAULT '0',
  `ishot` int(11) DEFAULT '0',
  `isdiscount` int(11) DEFAULT '0',
  `isrecommend` int(11) DEFAULT '0',
  `istime` int(11) DEFAULT '0',
  `isminimode` int(11) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `viewcount` int(11) DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `timelinetitle` varchar(50) NOT NULL,
  `timelinedesc` varchar(1000) NOT NULL,
  `timelinethumb` varchar(1000) NOT NULL DEFAULT '',
  `killenable` tinyint(2) DEFAULT '1' COMMENT '砍价开关',
  `killdiscount` decimal(10,2) DEFAULT '0.00' COMMENT '最高单次折扣',
  `killmindiscount` decimal(10,2) DEFAULT '0.00' COMMENT '最低单次折扣',
  `killmaxtime` int(10) DEFAULT '0' COMMENT '最多砍价次数',
  `killtotaldiscount` decimal(10,2) DEFAULT '0.00' COMMENT '最大折扣,达到此值后不能再砍',
  `rate1` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT '一级代理佣金,取值为0.000到1.000',
  `rate2` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT '一级代理佣金,取值为0.000到1.000',
  `rate3` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT '一级代理佣金,取值为0.000到1.000',
  `max_coupon_credit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '购买本商品最多可用抵扣积分数',
  `min_buy_level` int(10) DEFAULT '0' COMMENT '最低购买级别，低于这个级别的用户无法购买商品',
  `min_visible_level` int(10) DEFAULT '0' COMMENT '最低显示级别，低于这个级别的用户看不到该商品',
  `dealeropenid` varchar(50) NOT NULL DEFAULT '' COMMENT '客户下单后立即提醒店主有新单',
  `dealerid` int(10) DEFAULT '0' COMMENT '本地商户ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>