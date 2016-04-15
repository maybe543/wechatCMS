<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_chailihe_lihestyle`;");
E_C("CREATE TABLE `ims_stonefish_chailihe_lihestyle` (
  `liheid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `title` varchar(20) DEFAULT '' COMMENT '样式名称',
  `thumb1` varchar(255) DEFAULT '' COMMENT '礼盒展示图',
  `thumb2` varchar(255) DEFAULT '' COMMENT '礼盒拆开图',
  `thumb3` varchar(255) DEFAULT '' COMMENT '礼盒显示图',
  `shangjialogo` varchar(255) DEFAULT '' COMMENT '商家LOGO',
  `music` varchar(2) DEFAULT '' COMMENT '礼盒声音',
  PRIMARY KEY (`liheid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>