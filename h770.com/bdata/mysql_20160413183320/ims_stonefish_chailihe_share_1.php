<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_chailihe_share`;");
E_C("CREATE TABLE `ims_stonefish_chailihe_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `acid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '子公众号ID',
  `help_url` varchar(255) DEFAULT '' COMMENT '帮助关注引导页',
  `share_url` varchar(255) DEFAULT '' COMMENT '参与关注引导页',
  `share_open_close` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启作用',
  `share_title` varchar(50) DEFAULT '' COMMENT '分享标题',
  `share_desc` varchar(100) DEFAULT '' COMMENT '分享简介',
  `share_txt` text NOT NULL COMMENT '参与活动规则',
  `share_img` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图',
  `share_anniu` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈按钮或文字',
  `share_firend` varchar(255) NOT NULL COMMENT '助力按钮',
  `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片',
  `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语',
  `share_confirmurl` varchar(255) DEFAULT '' COMMENT '分享成功跳转URL',
  `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语',
  `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语',
  `sharetimes` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为每天次数2为总次数',
  `sharetype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分享赠送类型0分享立即赠送1分享成功赠送',
  `sharenumtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分享赠送机会类型0单独赠送机会1每人赠送机会2分享共计赠送',
  `sharenum` int(11) DEFAULT '0' COMMENT '分享赠送礼盒基数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_acid` (`acid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>