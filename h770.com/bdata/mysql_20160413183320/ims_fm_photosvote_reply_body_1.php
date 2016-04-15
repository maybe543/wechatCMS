<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_reply_body`;");
E_C("CREATE TABLE `ims_fm_photosvote_reply_body` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `zbgcolor` varchar(10) NOT NULL COMMENT '背景色',
  `zbg` varchar(125) NOT NULL COMMENT '背景图',
  `voicebg` varchar(125) NOT NULL COMMENT '录音室背景',
  `zbgtj` varchar(125) NOT NULL COMMENT '背景图',
  `topbgcolor` varchar(10) NOT NULL COMMENT '背景图',
  `topbg` varchar(125) NOT NULL COMMENT '背景图',
  `topbgtext` varchar(125) NOT NULL COMMENT '背景图',
  `topbgrightcolor` varchar(10) NOT NULL COMMENT '背景图',
  `topbgright` varchar(125) NOT NULL COMMENT '背景图',
  `foobg1` varchar(125) NOT NULL COMMENT '背景图',
  `foobg2` varchar(125) NOT NULL COMMENT '背景图',
  `foobgtextn` varchar(125) NOT NULL COMMENT '背景图',
  `foobgtexty` varchar(125) NOT NULL COMMENT '背景图',
  `foobgtextmore` varchar(125) NOT NULL COMMENT '背景图',
  `foobgmorecolor` varchar(10) NOT NULL COMMENT '背景图',
  `foobgmore` varchar(125) NOT NULL COMMENT '背景图',
  `bodytextcolor` varchar(10) NOT NULL COMMENT '背景图',
  `bodynumcolor` varchar(10) NOT NULL COMMENT '背景图',
  `inputcolor` varchar(10) NOT NULL COMMENT '背景图',
  `bodytscolor` varchar(10) NOT NULL COMMENT '背景图',
  `bodytsbg` varchar(125) NOT NULL COMMENT '背景图',
  `xinbg` varchar(125) NOT NULL COMMENT '背景图',
  `copyrightcolor` varchar(10) NOT NULL COMMENT '背景图',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>