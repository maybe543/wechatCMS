<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_album`;");
E_C("CREATE TABLE `ims_cate_album` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) unsigned DEFAULT '0',
  `img_desc` varchar(255) DEFAULT NULL,
  `img_url` varchar(255) DEFAULT NULL,
  `img_file` varchar(255) DEFAULT NULL,
  `indate` bigint(18) unsigned DEFAULT '0',
  `inorder` int(10) unsigned DEFAULT '0',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 商品相册'");

require("../../inc/footer.php");
?>