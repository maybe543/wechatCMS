<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_impress_record`;");
E_C("CREATE TABLE `ims_amouse_impress_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT 'uid',
  `oid` varchar(100) NOT NULL COMMENT '微信会员openID',
  `vote` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赞',
  `nickname` varchar(200) NOT NULL COMMENT '用户昵称',
  `realname` varchar(200) NOT NULL COMMENT '昵称',
  `content` varchar(200) NOT NULL COMMENT '印象内容',
  `createtime` int(10) unsigned NOT NULL COMMENT '评论时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='印象记录'");

require("../../inc/footer.php");
?>