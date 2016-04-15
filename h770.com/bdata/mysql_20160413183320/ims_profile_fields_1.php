<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_profile_fields`;");
E_C("CREATE TABLE `ims_profile_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `field` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `displayorder` smallint(6) NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `unchangeable` tinyint(1) NOT NULL DEFAULT '0',
  `showinregister` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8");
E_D("replace into `ims_profile_fields` values('1',0x7265616c6e616d65,'1',0xe79c9fe5ae9ee5a793e5908d,'','0','1','1','1');");
E_D("replace into `ims_profile_fields` values('2',0x6e69636b6e616d65,'1',0xe698b5e7a7b0,'','1','1','0','1');");
E_D("replace into `ims_profile_fields` values('3',0x617661746172,'1',0xe5a4b4e5838f,'','1','0','0','0');");
E_D("replace into `ims_profile_fields` values('4',0x7171,'1',0x5151e58fb7,'','0','0','0','1');");
E_D("replace into `ims_profile_fields` values('5',0x6d6f62696c65,'1',0xe6898be69cbae58fb7e7a081,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('6',0x766970,'1',0x564950e7baa7e588ab,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('7',0x67656e646572,'1',0xe680a7e588ab,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('8',0x626972746879656172,'1',0xe587bae7949fe7949fe697a5,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('9',0x636f6e7374656c6c6174696f6e,'1',0xe6989fe5baa7,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('10',0x7a6f64696163,'1',0xe7949fe88296,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('11',0x74656c6570686f6e65,'1',0xe59bbae5ae9ae794b5e8af9d,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('12',0x696463617264,'1',0xe8af81e4bbb6e58fb7e7a081,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('13',0x73747564656e746964,'1',0xe5ada6e58fb7,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('14',0x6772616465,'1',0xe78fade7baa7,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('15',0x61646472657373,'1',0xe982aee5af84e59cb0e59d80,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('16',0x7a6970636f6465,'1',0xe982aee7bc96,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('17',0x6e6174696f6e616c697479,'1',0xe59bbde7b18d,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('18',0x72657369646570726f76696e6365,'1',0xe5b185e4bd8fe59cb0e59d80,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('19',0x67726164756174657363686f6f6c,'1',0xe6af95e4b89ae5ada6e6a0a1,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('20',0x636f6d70616e79,'1',0xe585ace58fb8,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('21',0x656475636174696f6e,'1',0xe5ada6e58e86,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('22',0x6f636375706174696f6e,'1',0xe8818ce4b89a,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('23',0x706f736974696f6e,'1',0xe8818ce4bd8d,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('24',0x726576656e7565,'1',0xe5b9b4e694b6e585a5,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('25',0x616666656374697665737461747573,'1',0xe68385e6849fe78ab6e68081,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('26',0x6c6f6f6b696e67666f72,'1',0x20e4baa4e58f8be79baee79a84,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('27',0x626c6f6f6474797065,'1',0xe8a180e59e8b,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('28',0x686569676874,'1',0xe8baabe9ab98,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('29',0x776569676874,'1',0xe4bd93e9878d,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('30',0x616c69706179,'1',0xe694afe4bb98e5ae9de5b890e58fb7,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('31',0x6d736e,'1',0x4d534e,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('32',0x656d61696c,'1',0xe794b5e5ad90e982aee7aeb1,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('33',0x74616f62616f,'1',0xe998bfe9878ce697bae697ba,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('34',0x73697465,'1',0xe4b8bbe9a1b5,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('35',0x62696f,'1',0xe887aae68891e4bb8be7bb8d,'','0','0','0','0');");
E_D("replace into `ims_profile_fields` values('36',0x696e746572657374,'1',0xe585b4e8b6a3e788b1e5a5bd,'','0','0','0','0');");

require("../../inc/footer.php");
?>