<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_cart`;");
E_C("CREATE TABLE `ims_cate_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ordernum` varchar(20) DEFAULT NULL COMMENT '订单号，下单以后生成',
  `batch` int(10) unsigned DEFAULT '0' COMMENT '订单批次',
  `userid` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `wxname` varchar(100) DEFAULT NULL COMMENT '用户微信名称',
  `anonyname` varchar(100) DEFAULT NULL,
  `is_cart` tinyint(3) unsigned DEFAULT '0' COMMENT '0是购物车，1是立即购买的订单',
  `title` varchar(255) DEFAULT NULL COMMENT '产品标题',
  `goodsid` int(10) unsigned DEFAULT '0' COMMENT '产品ID',
  `goodsimg` varchar(255) DEFAULT NULL COMMENT '产品缩略图',
  `number` int(10) unsigned DEFAULT '0' COMMENT '购买数量',
  `unit` varchar(20) DEFAULT '' COMMENT '数量单位',
  `attr` varchar(255) DEFAULT NULL COMMENT '购买配置 组',
  `attrval` varchar(255) DEFAULT NULL COMMENT '购买配置 词',
  `price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '单价',
  `message` text COMMENT '购买备注',
  `delivery` int(10) unsigned DEFAULT '0' COMMENT '配送方式ID',
  `deliveryfare` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '运费',
  `payment` int(10) unsigned DEFAULT '0' COMMENT '支付方式ID',
  `anonymous` tinyint(3) unsigned DEFAULT '0' COMMENT '1 为匿名',
  `address` text COMMENT '收货地址',
  `indate` bigint(18) unsigned DEFAULT '0' COMMENT '下单时间',
  `update` bigint(18) unsigned DEFAULT '0' COMMENT '付款时间（提交订单时间）',
  `shipdate` bigint(18) unsigned DEFAULT '0' COMMENT '发货时间',
  `cache` longtext COMMENT '快照',
  `ismerge` tinyint(3) unsigned DEFAULT '0' COMMENT '是否合并订单 1是0不是',
  `mergearr` longtext COMMENT '合并订单组',
  `status` varchar(20) DEFAULT NULL COMMENT '待付款、\r\n等待商家确认(非在线支付)、\r\n商家已确认(等待货到付款)、\r\n已付款(在线支付)、\r\n商家已发货、\r\n交易关闭、\r\n交易成功（评价、未评价）',
  `paystatus` varchar(20) DEFAULT NULL COMMENT '付款状态，已付款',
  `paydate` bigint(18) unsigned DEFAULT '0' COMMENT '付款时间',
  `printsetting` text COMMENT '最后打印时间和状态(打印信息)',
  `comment` tinyint(3) unsigned DEFAULT '0' COMMENT '是否已经评论，1为已经评论',
  `canceltext` varchar(255) DEFAULT NULL COMMENT '关闭订单的原因',
  `card` text,
  `setting` text,
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 订单（购物车）'");

require("../../inc/footer.php");
?>