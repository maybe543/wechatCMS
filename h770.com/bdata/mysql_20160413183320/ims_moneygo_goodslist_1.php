<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_moneygo_goodslist`;");
E_C("CREATE TABLE `ims_moneygo_goodslist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `sid` int(10) unsigned NOT NULL COMMENT '同一个商品id',
  `title` varchar(100) DEFAULT NULL COMMENT '商品标题',
  `price` int(10) DEFAULT '0' COMMENT '金额',
  `zongrenshu` int(10) unsigned DEFAULT '0' COMMENT '总需人数',
  `canyurenshu` int(10) unsigned DEFAULT '0' COMMENT '已参与人数',
  `shengyurenshu` int(10) unsigned DEFAULT NULL COMMENT '剩余人数',
  `periods` smallint(6) unsigned DEFAULT '0' COMMENT '期数',
  `maxperiods` smallint(5) unsigned DEFAULT '1' COMMENT ' 最大期数',
  `picarr` text COMMENT '商品图片',
  `content` mediumtext COMMENT '商品详情',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `pos` tinyint(4) unsigned DEFAULT NULL COMMENT '是否推荐',
  `status` int(11) NOT NULL COMMENT '1:下架, 2: 上架',
  `scale` int(10) unsigned DEFAULT NULL COMMENT '比例',
  `q_uid` varchar(10) DEFAULT NULL COMMENT '中奖人昵称',
  `q_user` varchar(50) DEFAULT NULL COMMENT '中奖人from_user',
  `q_user_code` char(20) DEFAULT NULL COMMENT '中奖码',
  `q_end_time` char(20) DEFAULT NULL COMMENT '揭晓时间',
  `send_state` int(4) unsigned NOT NULL COMMENT '1为已发货',
  `send` int(4) unsigned NOT NULL COMMENT '是否需要快递1为需要',
  `express` varchar(20) DEFAULT NULL COMMENT '快递公司',
  `expressn` char(20) DEFAULT NULL COMMENT '快递单',
  `send_time` char(20) DEFAULT NULL COMMENT '发货时间',
  `zongji` int(11) NOT NULL,
  `danjia` int(111) unsigned NOT NULL,
  `cid` int(11) DEFAULT NULL,
  `showstatus` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`),
  KEY `status` (`status`),
  KEY `shenyurenshu` (`shengyurenshu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>