<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_auction_goodslist`;");
E_C("CREATE TABLE `ims_auction_goodslist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `title` varchar(100) DEFAULT NULL COMMENT '商品标题',
  `sh_price` int(10) DEFAULT '0' COMMENT '起拍金额',
  `add_price` int(10) DEFAULT '0' COMMENT '默认加价金额',
  `st_price` int(10) DEFAULT '0' COMMENT '成交金额',
  `bond` int(10) DEFAULT '0' COMMENT '保证金',
  `picarr` text COMMENT '商品图片',
  `categoryid` int(10) NOT NULL,
  `content` mediumtext COMMENT '商品详情',
  `start_time` int(10) unsigned DEFAULT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned DEFAULT NULL COMMENT '结束时间',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `pos` tinyint(4) unsigned DEFAULT '0' COMMENT '出价次数',
  `status` int(11) NOT NULL COMMENT '1:已付余款',
  `g_status` int(11) NOT NULL COMMENT '2:上架；1：下架',
  `q_uid` varchar(10) DEFAULT NULL COMMENT '成交人昵称',
  `q_user` varchar(50) DEFAULT NULL COMMENT '成交人from_user',
  `send_state` int(4) unsigned NOT NULL COMMENT '1为已发货',
  `send` int(4) unsigned NOT NULL COMMENT '是否需要快递1为需要',
  `express` varchar(20) DEFAULT NULL COMMENT '快递公司',
  `expressn` char(20) DEFAULT NULL COMMENT '快递单',
  `send_time` char(20) DEFAULT NULL COMMENT '发货时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `send_state` (`send_state`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>