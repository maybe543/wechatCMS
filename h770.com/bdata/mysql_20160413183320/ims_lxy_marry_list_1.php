<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lxy_marry_list`;");
E_C("CREATE TABLE `ims_lxy_marry_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `art_pic` varchar(255) DEFAULT NULL,
  `bg_pic` varchar(255) NOT NULL COMMENT '背景图片',
  `donghua_pic` varchar(255) DEFAULT NULL,
  `suolue_pic` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `xl_name` varchar(255) DEFAULT NULL,
  `xn_name` varchar(255) DEFAULT NULL,
  `is_front` varchar(255) DEFAULT '1' COMMENT '1:新郎名字在前 2:新娘名字在前',
  `tel` varchar(25) DEFAULT NULL,
  `hy_time` datetime DEFAULT NULL COMMENT '婚宴日期',
  `dist` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `hy_addr` varchar(255) DEFAULT NULL COMMENT '婚宴地址',
  `jw_addr` varchar(255) DEFAULT NULL COMMENT '经纬地址',
  `lng` varchar(12) DEFAULT '116.403694',
  `lat` varchar(12) DEFAULT '39.916042',
  `video` varchar(255) DEFAULT '/res/weiXiTie/mp4.mp4',
  `music` varchar(255) DEFAULT '/res/weiXiTie/youGotMe.mp3',
  `hs_pic` text COMMENT '婚纱图片',
  `pwd` varchar(255) DEFAULT NULL,
  `word` varchar(500) DEFAULT NULL,
  `erweima_pic` varchar(255) DEFAULT NULL COMMENT '二维码图片',
  `copyright` varchar(512) DEFAULT NULL COMMENT '版权',
  `createtime` int(11) unsigned DEFAULT NULL,
  `sendtitle` varchar(255) NOT NULL DEFAULT '',
  `senddescription` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微喜帖设置'");

require("../../inc/footer.php");
?>