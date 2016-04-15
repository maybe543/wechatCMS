<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jfhb_qrcode`;");
E_C("CREATE TABLE `ims_jfhb_qrcode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(3) NOT NULL,
  `haibao_id` int(3) NOT NULL COMMENT '海报id',
  `scene_id` int(10) NOT NULL COMMENT '二维码场景id',
  `nickname` varchar(200) NOT NULL COMMENT '用户昵称',
  `openid` varchar(50) NOT NULL COMMENT 'openid',
  `qr_img` varchar(200) NOT NULL COMMENT 'qrcode图像',
  `status` int(1) NOT NULL COMMENT '默认状态',
  `media_id` varchar(200) DEFAULT NULL COMMENT '微信后台的media_id',
  `media_time` int(10) DEFAULT NULL COMMENT '生成media_id时间',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `jfhb_qrcode_index1` (`uniacid`),
  KEY `jfhb_qrcode_ground1` (`uniacid`,`openid`),
  KEY `idxjfhb_scene_id` (`uniacid`,`scene_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>