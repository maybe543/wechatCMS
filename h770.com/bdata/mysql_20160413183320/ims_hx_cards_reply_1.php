<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hx_cards_reply`;");
E_C("CREATE TABLE `ims_hx_cards_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `groupid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '可参与的用户组',
  `thumb` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `need_type` varchar(10) NOT NULL,
  `need_num` int(10) unsigned NOT NULL,
  `give_type` varchar(10) NOT NULL,
  `give_num` int(10) unsigned NOT NULL DEFAULT '0',
  `onlynone` tinyint(1) NOT NULL DEFAULT '0',
  `awardnum` int(10) unsigned NOT NULL,
  `playnum` int(10) unsigned NOT NULL,
  `dayplaynum` int(10) unsigned NOT NULL,
  `sharenum` int(5) NOT NULL DEFAULT '0' COMMENT '最多分享数',
  `zfcs` int(10) unsigned NOT NULL COMMENT '转发次数',
  `zjcs` int(10) unsigned NOT NULL,
  `tips` varchar(255) NOT NULL,
  `noprize` text NOT NULL,
  `remark` varchar(255) NOT NULL,
  `share_title` varchar(100) NOT NULL,
  `share_img` varchar(255) NOT NULL,
  `share_url` varchar(255) NOT NULL,
  `share_content` varchar(255) NOT NULL,
  `rate` int(10) unsigned NOT NULL,
  `prizes` text NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0结束1正常2暂停',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>