<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fineness_comment`;");
E_C("CREATE TABLE `ims_fineness_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `aid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `author` varchar(255) NOT NULL COMMENT '昵称',
  `openid` varchar(255) NOT NULL COMMENT '昵称',
  `thumb` varchar(500) NOT NULL COMMENT '头像',
  `js_cmt_input` varchar(500) NOT NULL COMMENT '留言内容',
  `js_cmt_reply` varchar(500) NOT NULL COMMENT '回复内容',
  `status` varchar(2) NOT NULL COMMENT '是否显示',
  `praise_num` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='文章评价'");

require("../../inc/footer.php");
?>