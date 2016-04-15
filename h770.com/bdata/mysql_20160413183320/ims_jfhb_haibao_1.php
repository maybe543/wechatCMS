<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jfhb_haibao`;");
E_C("CREATE TABLE `ims_jfhb_haibao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(3) NOT NULL,
  `title` varchar(200) NOT NULL COMMENT '海报标题',
  `hb_img` varchar(200) NOT NULL COMMENT '海报图片',
  `qrleft` int(3) NOT NULL COMMENT '左边距',
  `qrtop` int(3) NOT NULL COMMENT '上边距',
  `qrwidth` int(3) NOT NULL COMMENT '二维码宽度',
  `qrheight` int(3) NOT NULL COMMENT '二维码高度',
  `avatarleft` int(3) NOT NULL COMMENT '头像左边距',
  `avatartop` int(3) NOT NULL COMMENT '头像上边距',
  `avatarwidth` int(3) NOT NULL COMMENT '头像宽度',
  `avatarheight` int(3) NOT NULL COMMENT '头像高度',
  `avatarenable` int(1) NOT NULL COMMENT '是否显示头像',
  `nameleft` int(3) NOT NULL COMMENT '名称左边距',
  `nametop` int(3) NOT NULL COMMENT '名称上边距',
  `namesize` int(3) NOT NULL COMMENT '名称宽度',
  `nameenable` int(1) NOT NULL COMMENT '是否显示名称',
  `namecolor` varchar(20) NOT NULL COMMENT '名称颜色',
  `status` int(1) NOT NULL COMMENT '0,默认，1非默认',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>