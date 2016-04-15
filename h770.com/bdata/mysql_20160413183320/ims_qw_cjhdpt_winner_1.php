<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_qw_cjhdpt_winner`;");
E_C("CREATE TABLE `ims_qw_cjhdpt_winner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `weid` int(10) NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `openid` varchar(50) NOT NULL COMMENT '借用身份',
  `mobile` varchar(20) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `gender` smallint(1) NOT NULL DEFAULT '0',
  `realname` varchar(20) NOT NULL,
  `parama` varchar(1000) NOT NULL COMMENT '参数',
  `status` smallint(1) NOT NULL DEFAULT '0',
  `attend` smallint(1) NOT NULL DEFAULT '0',
  `endtime` varchar(10) NOT NULL,
  `createtime` varchar(10) NOT NULL,
  `message` varchar(200) NOT NULL COMMENT '通知',
  `remark` varchar(200) NOT NULL COMMENT '备注',
  `ordersn` varchar(50) NOT NULL COMMENT '订单号',
  `paystatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否付款',
  `paytime` int(10) NOT NULL DEFAULT '0' COMMENT '付款时间',
  `transid` varchar(50) NOT NULL COMMENT '支付订单号',
  `reloadmsg` varchar(200) NOT NULL COMMENT '签到回调信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>