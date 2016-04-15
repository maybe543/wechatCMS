<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jfhb_user_all`;");
E_C("CREATE TABLE `ims_jfhb_user_all` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(3) NOT NULL,
  `openid` varchar(100) NOT NULL COMMENT '微信id',
  `jyopenid` varchar(100) NOT NULL COMMENT '借用的微信id',
  `nickname` varchar(200) NOT NULL COMMENT '昵称',
  `sex` int(1) NOT NULL COMMENT '性别',
  `city` varchar(20) NOT NULL COMMENT '城市',
  `province` varchar(10) NOT NULL COMMENT '省份',
  `subscribe` int(1) NOT NULL COMMENT '是否关注',
  `headimgurl` varchar(200) NOT NULL COMMENT '头像',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '推荐金额',
  `parent_openid` varchar(100) NOT NULL COMMENT '上级_openid',
  `sj_parent_openid` varchar(100) NOT NULL COMMENT '上级_的上级',
  `createtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>