<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_goods`;");
E_C("CREATE TABLE `ims_cate_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sort_id` int(10) unsigned DEFAULT '0',
  `area_id` int(10) unsigned DEFAULT '0',
  `brand_id` int(10) unsigned DEFAULT '0',
  `sn` varchar(50) DEFAULT '',
  `title` varchar(100) DEFAULT '',
  `title_color` varchar(20) DEFAULT '+',
  `descriptions` varchar(255) DEFAULT NULL,
  `content` mediumtext,
  `img` varchar(255) DEFAULT NULL,
  `number` smallint(5) unsigned DEFAULT '0',
  `unit` varchar(20) DEFAULT '',
  `market_price` decimal(10,2) unsigned DEFAULT '0.00',
  `shop_price` decimal(10,2) unsigned DEFAULT '0.00',
  `is_on_sale` tinyint(1) unsigned DEFAULT '1',
  `is_best` tinyint(1) unsigned DEFAULT '0',
  `is_new` tinyint(1) unsigned DEFAULT '0',
  `is_hot` tinyint(1) unsigned DEFAULT '0',
  `indate` bigint(18) unsigned DEFAULT '0',
  `update` bigint(18) unsigned DEFAULT NULL,
  `inorder` int(10) unsigned DEFAULT '100',
  `view` int(10) unsigned DEFAULT '0',
  `pingfen` int(10) DEFAULT '0',
  `pingfennum` int(10) unsigned DEFAULT '0',
  `salesnum` int(10) unsigned DEFAULT '0',
  `del` tinyint(3) unsigned DEFAULT '0',
  `setting` text,
  `selectattr` text,
  `selectattrstock` text,
  `payprint` tinyint(3) unsigned DEFAULT '0',
  `autoconfirm` tinyint(3) unsigned DEFAULT '0',
  `autosend` tinyint(3) unsigned DEFAULT '0',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `goods_sn` (`sn`),
  KEY `cat_id` (`sort_id`),
  KEY `brand_id` (`brand_id`),
  KEY `goods_number` (`number`),
  KEY `sort_order` (`inorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 商品'");

require("../../inc/footer.php");
?>