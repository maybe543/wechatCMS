<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jfhb_user_log`;");
E_C("CREATE TABLE `ims_jfhb_user_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(3) NOT NULL,
  `openid` varchar(100) NOT NULL COMMENT '微信id',
  `child_openid` varchar(100) NOT NULL COMMENT '取消关注以后的下线openid',
  `nickname` varchar(200) NOT NULL COMMENT '昵称',
  `headimgurl` varchar(200) NOT NULL COMMENT '头像',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '获得的总金额',
  `type` int(1) NOT NULL DEFAULT '0' COMMENT '获得积分类型 0 关注，1,邀请关注，2取消关注，3,提现',
  `createtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>