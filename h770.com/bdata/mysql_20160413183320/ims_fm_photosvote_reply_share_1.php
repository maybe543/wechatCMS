<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_reply_share`;");
E_C("CREATE TABLE `ims_fm_photosvote_reply_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `subscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否强制需要关注公众号才能参与',
  `shareurl` varchar(255) NOT NULL COMMENT '活动网址',
  `sharetitle` varchar(50) NOT NULL COMMENT '分享标题',
  `sharephoto` varchar(225) NOT NULL COMMENT 'fx图片',
  `sharecontent` varchar(100) NOT NULL COMMENT '分享简介',
  `subscribedes` varchar(200) NOT NULL COMMENT '分享提示语',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>