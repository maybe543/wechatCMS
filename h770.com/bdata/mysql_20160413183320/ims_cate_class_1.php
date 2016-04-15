<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_class`;");
E_C("CREATE TABLE `ims_cate_class` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `title_en` varchar(20) DEFAULT NULL,
  `cash` varchar(10) DEFAULT NULL,
  `formhtml` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 配送方式、支付方式 (系统)'");
E_D("replace into `ims_cate_class` values('1',0xe9858de98081e696b9e5bc8f,0xe5bd93e99da2e4baa4e69893,'',0xe698af,'');");
E_D("replace into `ims_cate_class` values('2',0xe9858de98081e696b9e5bc8f,0xe5bfabe98092e9858de98081,'',0xe590a6,'');");
E_D("replace into `ims_cate_class` values('3',0xe694afe4bb98e696b9e5bc8f,0xe78eb0e98791e4bb98e6acbe,0x63617368,0xe698af,'');");
E_D("replace into `ims_cate_class` values('4',0xe694afe4bb98e696b9e5bc8f,0xe5beaee4bfa1e59ca8e7babfe694afe4bb98,0x77656978696e,0xe590a6,0x3c74723e0d0a3c74643e3c2f74643e0d0a3c74643ee99c80e8a681e59ca8efbc9ae58a9fe883bde98089e68ba93e3ee694afe4bb98e58f82e695b020e9878ce99da2e8aebee7bdae3c2f74643e0d0a3c2f74723e);");
E_D("replace into `ims_cate_class` values('5',0xe694afe4bb98e696b9e5bc8f,0xe4bd99e9a29de694afe4bb98,0x6f766572,0xe590a6,0x3c74723e0d0a3c74643e3c2f74643e0d0a3c74643ee99c80e8a681e59ca8efbc9ae58a9fe883bde98089e68ba93e3ee694afe4bb98e58f82e695b020e9878ce99da2e8aebee7bdae3c2f74643e0d0a3c2f74723e);");

require("../../inc/footer.php");
?>