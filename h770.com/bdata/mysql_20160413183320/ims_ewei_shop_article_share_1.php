<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_shop_article_share`;");
E_C("CREATE TABLE `ims_ewei_shop_article_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL DEFAULT '0',
  `share_user` int(11) NOT NULL DEFAULT '0' COMMENT '分享人',
  `click_user` int(11) NOT NULL DEFAULT '0' COMMENT '点击人',
  `click_date` varchar(20) NOT NULL DEFAULT '' COMMENT '执行时间',
  `add_credit` int(11) NOT NULL DEFAULT '0' COMMENT '添加的积分',
  `add_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '添加的余额',
  `uniacid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_aid` (`aid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>