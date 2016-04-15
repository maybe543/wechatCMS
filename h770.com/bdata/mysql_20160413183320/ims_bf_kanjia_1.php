<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_bf_kanjia`;");
E_C("CREATE TABLE `ims_bf_kanjia` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '表id',
  `rid` int(10) NOT NULL COMMENT '规则id',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `uniacid` int(10) NOT NULL COMMENT '公号id',
  `title` varchar(100) NOT NULL COMMENT '标题|商品名称',
  `cover` varchar(255) NOT NULL COMMENT '封面',
  `starttime` int(10) NOT NULL COMMENT '开始时间',
  `endtime` int(10) NOT NULL COMMENT '结束时间',
  `tel` varchar(20) NOT NULL COMMENT '客服电话',
  `buy_type` tinyint(1) NOT NULL COMMENT '购买模式',
  `follow_url` varchar(500) NOT NULL COMMENT '关注链接',
  `follow_must` tinyint(1) NOT NULL COMMENT '强制关注',
  `max_help` int(10) NOT NULL COMMENT '最大帮砍次数',
  `notice` varchar(2000) NOT NULL COMMENT '须知',
  `rules` varchar(2000) NOT NULL COMMENT '规则',
  `product_name` varchar(255) NOT NULL COMMENT '商品名称',
  `product_image` varchar(255) NOT NULL COMMENT '产品图片',
  `product_price` decimal(10,2) NOT NULL COMMENT '原价',
  `product_pricelow` decimal(10,2) NOT NULL COMMENT '底价',
  `product_inventory` int(10) NOT NULL COMMENT '库存',
  `product_sold` int(10) NOT NULL DEFAULT '0' COMMENT '已售',
  `product_detail` varchar(5000) NOT NULL COMMENT '详情',
  `product_url` varchar(500) NOT NULL COMMENT '产品链接',
  `share_title` varchar(255) NOT NULL COMMENT '分享标题',
  `share_link` varchar(255) NOT NULL COMMENT '分享链接',
  `share_imgUrl` varchar(255) NOT NULL COMMENT '分享图片',
  `share_desc` varchar(255) NOT NULL COMMENT '分享介绍',
  `number_join` int(10) NOT NULL DEFAULT '0' COMMENT '参与人数',
  `number_help` int(10) NOT NULL DEFAULT '0' COMMENT '助力人数',
  `footer` varchar(5000) NOT NULL COMMENT '页面底部',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `blacklist_nickname` varchar(2000) NOT NULL COMMENT '粉丝昵称数组',
  `blacklist_openid` varchar(2000) NOT NULL COMMENT '粉丝编号数组',
  `blacklist_notice` varchar(200) NOT NULL COMMENT '黑名单提示',
  `follow_must_help` tinyint(1) NOT NULL COMMENT '帮砍强制关注',
  `ip_max` int(10) NOT NULL COMMENT 'ip限制次数',
  `join_url` varchar(500) NOT NULL COMMENT '入驻链接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>