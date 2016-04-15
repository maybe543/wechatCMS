<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_baoliao`;");
E_C("CREATE TABLE `ims_baoliao` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `weid` int(11) DEFAULT NULL COMMENT '公众号id',
  `rid` int(11) DEFAULT NULL COMMENT '规则id',
  `bltype` int(1) DEFAULT NULL COMMENT '报料类型',
  `name` varchar(50) DEFAULT NULL COMMENT '昵称',
  `fromuser` varchar(100) DEFAULT NULL COMMENT '用户openid',
  `tel` varchar(20) DEFAULT NULL COMMENT '电话',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `content` varchar(300) DEFAULT NULL COMMENT '内容',
  `pics` varchar(200) DEFAULT NULL COMMENT '报料图片',
  `uptime` varchar(10) DEFAULT NULL COMMENT '报料时间',
  `reply` varchar(300) DEFAULT NULL COMMENT '回复内容',
  `replytime` varchar(10) DEFAULT NULL COMMENT '回复时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>