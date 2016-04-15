<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_bf_kanjia_help`;");
E_C("CREATE TABLE `ims_bf_kanjia_help` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '行id',
  `uniacid` int(10) NOT NULL COMMENT '公号id',
  `rid` int(10) NOT NULL COMMENT '砍价记录id',
  `openid` varchar(50) NOT NULL COMMENT '粉丝openid',
  `nickname` varchar(50) NOT NULL COMMENT '粉丝名称',
  `headimgurl` varchar(255) NOT NULL COMMENT '头像',
  `price` decimal(10,2) NOT NULL COMMENT '砍掉的价格',
  `createtime` int(10) NOT NULL COMMENT '插入时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>