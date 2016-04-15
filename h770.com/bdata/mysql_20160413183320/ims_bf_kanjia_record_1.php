<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_bf_kanjia_record`;");
E_C("CREATE TABLE `ims_bf_kanjia_record` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '行id',
  `uniacid` int(10) NOT NULL COMMENT '公号id',
  `acid` int(10) NOT NULL COMMENT '子公号id',
  `kid` int(10) NOT NULL COMMENT '砍价id',
  `uid` int(10) NOT NULL COMMENT '粉丝会员id',
  `openid` varchar(50) NOT NULL COMMENT '粉丝id',
  `nickname` varchar(50) NOT NULL COMMENT '粉丝名称',
  `headimgurl` varchar(255) NOT NULL COMMENT '粉丝头像',
  `price` decimal(10,2) NOT NULL COMMENT '剩下的价格',
  `number_help` int(10) NOT NULL DEFAULT '0' COMMENT '助力人次',
  `createtime` int(10) NOT NULL COMMENT '插入的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>