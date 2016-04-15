<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_j_activity_msgrecord`;");
E_C("CREATE TABLE `ims_j_activity_msgrecord` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `aid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `from_user` varchar(50) NOT NULL COMMENT '用户openid',
  `content` varchar(500) NOT NULL COMMENT '发送内容',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启',
  `remark` varchar(500) NOT NULL COMMENT '发送问题',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>