<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_netsbd_news_comment`;");
E_C("CREATE TABLE `ims_netsbd_news_comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `newsid` int(10) DEFAULT NULL,
  `comment_content` varchar(255) DEFAULT NULL,
  `type` int(1) DEFAULT NULL COMMENT '1是赞\r\n            2是评论\r\n            3是转发',
  `like_num` int(10) DEFAULT NULL,
  `ishide` int(11) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='新闻评论表\r\n包括赞、转发也在里面'");

require("../../inc/footer.php");
?>