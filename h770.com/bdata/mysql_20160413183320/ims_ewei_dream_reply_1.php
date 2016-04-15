<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_dream_reply`;");
E_C("CREATE TABLE `ims_ewei_dream_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `dreams` text,
  `punishments` text,
  `views` int(11) DEFAULT '0',
  `shares` int(11) DEFAULT '0',
  `follow_url` varchar(255) DEFAULT '',
  `follow_need` int(11) DEFAULT '0',
  `diy_bgcolor` varchar(255) DEFAULT '',
  `diy_fontcolor` varchar(255) DEFAULT '',
  `diy_topimg` varchar(255) DEFAULT '',
  `diy_btncolor` varchar(255) DEFAULT '',
  `diy_btnfontcolor` varchar(255) DEFAULT '',
  `diy_btntext` varchar(255) DEFAULT '',
  `diy_title1` varchar(255) DEFAULT '',
  `diy_title2` varchar(255) DEFAULT '',
  `diy_title3` varchar(255) DEFAULT '',
  `diy_title4` varchar(255) DEFAULT '',
  `diy_title5` varchar(255) DEFAULT '',
  `diy_audio` varchar(255) DEFAULT '',
  `diy_topimgshare` varchar(255) DEFAULT '',
  `diy_inputcolor` varchar(255) DEFAULT '',
  `diy_inputtextcolor` varchar(255) DEFAULT '',
  `diy_paperimg` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `copyright` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>