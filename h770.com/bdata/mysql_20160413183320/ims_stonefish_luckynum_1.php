<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_luckynum`;");
E_C("CREATE TABLE `ims_stonefish_luckynum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `isshow` tinyint(1) DEFAULT '0',
  `title` varchar(100) DEFAULT '',
  `shareimg` varchar(255) DEFAULT '',
  `sharetitle` varchar(100) DEFAULT '',
  `sharedesc` varchar(300) DEFAULT '',
  `show_instruction` varchar(100) DEFAULT '',
  `time_instruction` varchar(100) DEFAULT '',
  `limit_instruction` varchar(100) DEFAULT '',
  `end_instruction` varchar(100) DEFAULT '',
  `awardnum_instruction` varchar(100) DEFAULT '',
  `award_instruction` varchar(100) DEFAULT '',
  `luckynumstart` int(10) unsigned NOT NULL DEFAULT '0',
  `luckynumfilter` varchar(100) NOT NULL DEFAULT '',
  `awardprompt` varchar(200) NOT NULL DEFAULT '',
  `currentprompt` varchar(200) NOT NULL DEFAULT '',
  `limittype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '参与限制0为无限制1为只能一次',
  `awardnum` tinyint(1) NOT NULL DEFAULT '0' COMMENT '中奖限制次数',
  `ticketinfo` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要',
  `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要',
  `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要',
  `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要',
  `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称',
  `sponsors1` varchar(50) DEFAULT '',
  `sponsors1link` varchar(255) DEFAULT '',
  `sponsors2` varchar(50) DEFAULT '',
  `sponsors2link` varchar(255) DEFAULT '',
  `sponsors3` varchar(50) DEFAULT '',
  `sponsors3link` varchar(255) DEFAULT '',
  `sponsors4` varchar(50) DEFAULT '',
  `sponsors4link` varchar(255) DEFAULT '',
  `sponsors5` varchar(50) DEFAULT '',
  `sponsors5link` varchar(255) DEFAULT '',
  `ruletext` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>