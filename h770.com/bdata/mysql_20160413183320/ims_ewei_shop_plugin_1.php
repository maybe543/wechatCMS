<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_shop_plugin`;");
E_C("CREATE TABLE `ims_ewei_shop_plugin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `displayorder` int(11) DEFAULT '0',
  `identity` varchar(50) DEFAULT '',
  `name` varchar(50) DEFAULT '',
  `version` varchar(10) DEFAULT '',
  `author` varchar(20) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `category` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_displayorder` (`displayorder`),
  FULLTEXT KEY `idx_identity` (`identity`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");
E_D("replace into `ims_ewei_shop_plugin` values('1','1',0x71696e6975,0xe4b883e7899be5ad98e582a8,0x312e30,0xe5ae98e696b9,'1',0x746f6f6c);");
E_D("replace into `ims_ewei_shop_plugin` values('2','2',0x74616f62616f,0xe6b798e5ae9de58aa9e6898b,0x312e30,0xe5ae98e696b9,'1',0x746f6f6c);");
E_D("replace into `ims_ewei_shop_plugin` values('3','3',0x636f6d6d697373696f6e,0xe4babae4babae58886e99480,0x312e30,0xe5ae98e696b9,'1',0x62697a);");
E_D("replace into `ims_ewei_shop_plugin` values('4','4',0x706f73746572,0xe8b685e7baa7e6b5b7e68aa5,0x312e32,0xe5ae98e696b9,'1',0x73616c65);");
E_D("replace into `ims_ewei_shop_plugin` values('5','5',0x766572696679,0x4f324fe6a0b8e99480,0x312e30,0xe5ae98e696b9,'1',0x62697a);");
E_D("replace into `ims_ewei_shop_plugin` values('6','6',0x746d657373616765,0xe4bc9ae59198e7bea4e58f91,0x312e30,0xe5ae98e696b9,'1',0x746f6f6c);");
E_D("replace into `ims_ewei_shop_plugin` values('7','7',0x7065726d,0xe58886e69d83e7b3bbe7bb9f,0x312e30,0xe5ae98e696b9,'0',0x68656c70);");
E_D("replace into `ims_ewei_shop_plugin` values('8','8',0x73616c65,0xe890a5e99480e5ae9d,0x312e30,0xe5ae98e696b9,'0',0x73616c65);");
E_D("replace into `ims_ewei_shop_plugin` values('9','9',0x64657369676e6572,0xe5ba97e993bae8a385e4bfae,0x312e30,0xe5ae98e696b9,'0',0x68656c70);");
E_D("replace into `ims_ewei_shop_plugin` values('10','10',0x63726564697473686f70,0xe7a7afe58886e59586e59f8e,0x312e30,0xe5ae98e696b9,'0',0x62697a);");
E_D("replace into `ims_ewei_shop_plugin` values('11','11',0x7669727475616c,0xe8999ae68b9fe789a9e59381,0x312e30,0xe5ae98e696b9,'0',0x62697a);");
E_D("replace into `ims_ewei_shop_plugin` values('12','11',0x61727469636c65,0xe69687e7aba0e890a5e99480,0x312e30,0xe5ae98e696b9,'0',0x68656c70);");
E_D("replace into `ims_ewei_shop_plugin` values('13','13',0x636f75706f6e,0xe8b685e7baa7e588b8,0x312e30,0xe5ae98e696b9,'0',0x73616c65);");
E_D("replace into `ims_ewei_shop_plugin` values('14','14',0x706f7374657261,0xe6b4bbe58aa8e6b5b7e68aa5,0x312e30,0xe5ae98e696b9,'0',0x73616c65);");

require("../../inc/footer.php");
?>