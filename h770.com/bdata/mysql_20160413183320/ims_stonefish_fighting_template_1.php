<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_fighting_template`;");
E_C("CREATE TABLE `ims_stonefish_fighting_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `title` varchar(20) DEFAULT '' COMMENT '模板名称',
  `thumb` varchar(255) DEFAULT '' COMMENT '模板缩略图',
  `fontsize` varchar(2) DEFAULT '12' COMMENT '文字大小',
  `bgimg` varchar(255) DEFAULT '' COMMENT '背景图',
  `bgcolor` varchar(7) DEFAULT '' COMMENT '背景色',
  `textcolor` varchar(7) DEFAULT '' COMMENT '文字色',
  `textcolorlink` varchar(7) DEFAULT '' COMMENT '链接文字色',
  `buttoncolor` varchar(7) DEFAULT '' COMMENT '按钮色',
  `buttontextcolor` varchar(7) DEFAULT '' COMMENT '按钮文字色',
  `rulecolor` varchar(7) DEFAULT '' COMMENT '规则框背景色',
  `ruletextcolor` varchar(7) DEFAULT '' COMMENT '规则框文字色',
  `navcolor` varchar(7) DEFAULT '' COMMENT '导航色',
  `navtextcolor` varchar(7) DEFAULT '' COMMENT '导航文字色',
  `navactioncolor` varchar(7) DEFAULT '' COMMENT '导航选中文字色',
  `watchcolor` varchar(7) DEFAULT '' COMMENT '弹出框背景色',
  `watchtextcolor` varchar(7) DEFAULT '' COMMENT '弹出框文字色',
  `awardcolor` varchar(7) DEFAULT '' COMMENT '兑奖框背景色',
  `awardtextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框文字色',
  `awardscolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功背景色',
  `awardstextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功文字色',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>