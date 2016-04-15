<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_valuation_mobile_parameter`;");
E_C("CREATE TABLE `ims_amouse_valuation_mobile_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `txt` varchar(255) NOT NULL COMMENT '机身颜色',
  `optionA` varchar(255) NOT NULL,
  `optionB` varchar(255) NOT NULL,
  `optionC` varchar(255) NOT NULL,
  `optionD` varchar(255) NOT NULL,
  `optionE` varchar(255) NOT NULL,
  `optionF` varchar(255) NOT NULL,
  `priceA` varchar(100) NOT NULL COMMENT '价格1',
  `priceB` varchar(100) NOT NULL COMMENT '价格2',
  `priceC` varchar(100) NOT NULL COMMENT '价格3',
  `priceD` varchar(100) NOT NULL COMMENT '价格4',
  `priceE` varchar(100) NOT NULL COMMENT '价格5',
  `priceF` varchar(100) NOT NULL COMMENT '价格5',
  `ptype` varchar(5) NOT NULL COMMENT '奖品顺序，从1-15',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评估参数'");

require("../../inc/footer.php");
?>