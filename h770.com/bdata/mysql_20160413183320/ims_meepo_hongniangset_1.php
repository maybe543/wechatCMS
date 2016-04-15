<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_hongniangset`;");
E_C("CREATE TABLE `ims_meepo_hongniangset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `share_title` varchar(100) NOT NULL,
  `share_link` varchar(300) NOT NULL,
  `share_content` varchar(300) NOT NULL,
  `share_logo` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `headtitle` varchar(200) NOT NULL,
  `logo` varchar(60) NOT NULL,
  `weid` int(11) NOT NULL,
  `url` varchar(200) NOT NULL,
  `hnages` varchar(200) NOT NULL,
  `pay_height` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看身高消费积分',
  `pay_weight` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看体重消费积分',
  `pay_telephone` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看手机号码消费积分',
  `pay_carhouse` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看车房状态',
  `pay_Descrip` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看自我介绍',
  `pay_uitsOthers` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看理想的另一半',
  `pay_uheight` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看对象的身高',
  `pay_uage` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看对象的年龄',
  `pay_all` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看所有',
  `pay_occupation` varchar(10) NOT NULL DEFAULT '0' COMMENT '查看职业',
  `pay_revenue` varchar(10) NOT NULL DEFAULT '0' COMMENT '查看月收入',
  `pay_qq` varchar(10) NOT NULL DEFAULT '0' COMMENT '查看qq',
  `pay_wechat` varchar(10) NOT NULL DEFAULT '0' COMMENT '查看微信',
  `pay_affectivestatus` varchar(10) NOT NULL DEFAULT '0' COMMENT '查看他的情感状态',
  `pay_lxxingzuo` varchar(10) NOT NULL DEFAULT '0' COMMENT '查看理想星座',
  `share_jifen` varchar(10) NOT NULL DEFAULT '0' COMMENT '分享奖励积分',
  `header_ads` varchar(100) NOT NULL COMMENT '前台广告',
  `header_adsurl` varchar(200) NOT NULL COMMENT '首页图片链接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>