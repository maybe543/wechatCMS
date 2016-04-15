<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mc_member_fields`;");
E_C("CREATE TABLE `ims_mc_member_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `fieldid` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL,
  `displayorder` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_fieldid` (`fieldid`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8");
E_D("replace into `ims_mc_member_fields` values('1','3','1',0xe79c9fe5ae9ee5a793e5908d,'1','0');");
E_D("replace into `ims_mc_member_fields` values('2','3','2',0xe698b5e7a7b0,'1','1');");
E_D("replace into `ims_mc_member_fields` values('3','3','3',0xe5a4b4e5838f,'1','1');");
E_D("replace into `ims_mc_member_fields` values('4','3','4',0x5151e58fb7,'1','0');");
E_D("replace into `ims_mc_member_fields` values('5','3','5',0xe6898be69cbae58fb7e7a081,'1','0');");
E_D("replace into `ims_mc_member_fields` values('6','3','6',0x564950e7baa7e588ab,'1','0');");
E_D("replace into `ims_mc_member_fields` values('7','3','7',0xe680a7e588ab,'1','0');");
E_D("replace into `ims_mc_member_fields` values('8','3','8',0xe587bae7949fe7949fe697a5,'1','0');");
E_D("replace into `ims_mc_member_fields` values('9','3','9',0xe6989fe5baa7,'1','0');");
E_D("replace into `ims_mc_member_fields` values('10','3','10',0xe7949fe88296,'1','0');");
E_D("replace into `ims_mc_member_fields` values('11','3','11',0xe59bbae5ae9ae794b5e8af9d,'1','0');");
E_D("replace into `ims_mc_member_fields` values('12','3','12',0xe8af81e4bbb6e58fb7e7a081,'1','0');");
E_D("replace into `ims_mc_member_fields` values('13','3','13',0xe5ada6e58fb7,'1','0');");
E_D("replace into `ims_mc_member_fields` values('14','3','14',0xe78fade7baa7,'1','0');");
E_D("replace into `ims_mc_member_fields` values('15','3','15',0xe982aee5af84e59cb0e59d80,'1','0');");
E_D("replace into `ims_mc_member_fields` values('16','3','16',0xe982aee7bc96,'1','0');");
E_D("replace into `ims_mc_member_fields` values('17','3','17',0xe59bbde7b18d,'1','0');");
E_D("replace into `ims_mc_member_fields` values('18','3','18',0xe5b185e4bd8fe59cb0e59d80,'1','0');");
E_D("replace into `ims_mc_member_fields` values('19','3','19',0xe6af95e4b89ae5ada6e6a0a1,'1','0');");
E_D("replace into `ims_mc_member_fields` values('20','3','20',0xe585ace58fb8,'1','0');");
E_D("replace into `ims_mc_member_fields` values('21','3','21',0xe5ada6e58e86,'1','0');");
E_D("replace into `ims_mc_member_fields` values('22','3','22',0xe8818ce4b89a,'1','0');");
E_D("replace into `ims_mc_member_fields` values('23','3','23',0xe8818ce4bd8d,'1','0');");
E_D("replace into `ims_mc_member_fields` values('24','3','24',0xe5b9b4e694b6e585a5,'1','0');");
E_D("replace into `ims_mc_member_fields` values('25','3','25',0xe68385e6849fe78ab6e68081,'1','0');");
E_D("replace into `ims_mc_member_fields` values('26','3','26',0x20e4baa4e58f8be79baee79a84,'1','0');");
E_D("replace into `ims_mc_member_fields` values('27','3','27',0xe8a180e59e8b,'1','0');");
E_D("replace into `ims_mc_member_fields` values('28','3','28',0xe8baabe9ab98,'1','0');");
E_D("replace into `ims_mc_member_fields` values('29','3','29',0xe4bd93e9878d,'1','0');");
E_D("replace into `ims_mc_member_fields` values('30','3','30',0xe694afe4bb98e5ae9de5b890e58fb7,'1','0');");
E_D("replace into `ims_mc_member_fields` values('31','3','31',0x4d534e,'1','0');");
E_D("replace into `ims_mc_member_fields` values('32','3','32',0xe794b5e5ad90e982aee7aeb1,'1','0');");
E_D("replace into `ims_mc_member_fields` values('33','3','33',0xe998bfe9878ce697bae697ba,'1','0');");
E_D("replace into `ims_mc_member_fields` values('34','3','34',0xe4b8bbe9a1b5,'1','0');");
E_D("replace into `ims_mc_member_fields` values('35','3','35',0xe887aae68891e4bb8be7bb8d,'1','0');");
E_D("replace into `ims_mc_member_fields` values('36','3','36',0xe585b4e8b6a3e788b1e5a5bd,'1','0');");

require("../../inc/footer.php");
?>