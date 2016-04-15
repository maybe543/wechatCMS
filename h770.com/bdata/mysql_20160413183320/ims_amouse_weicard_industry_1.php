<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_weicard_industry`;");
E_C("CREATE TABLE `ims_amouse_weicard_industry` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `displayorder` int(10) DEFAULT NULL,
  `weid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8");
E_D("replace into `ims_amouse_weicard_industry` values('1',0x4954c2b7e4ba92e88194e7bd91c2b7e6b8b8e6888f,'1','0');");
E_D("replace into `ims_amouse_weicard_industry` values('2',0xe98791e89e8de4b89aefbc88e68a95e8b584c2b7e4bf9de999a9c2b7e8af81e588b8c2b7e59fbae98791efbc89,'2','0');");
E_D("replace into `ims_amouse_weicard_industry` values('3',0xe58cbbe79697c2b7e4bf9de581a5c2b7e7be8ee5aeb9,'4','0');");
E_D("replace into `ims_amouse_weicard_industry` values('4',0xe69599e882b2c2b7e59fb9e8aeadc2b7e7a791e7a094c2b7e999a2e6a0a1,'5','0');");
E_D("replace into `ims_amouse_weicard_industry` values('5',0xe9809ae4bfa1e8a18ce4b89a,'6','0');");
E_D("replace into `ims_amouse_weicard_industry` values('6',0xe688bfe59cb0e4baa7e5bc80e58f91c2b7e5bbbae7ad91e4b88ee5b7a5e7a88b,'7','0');");
E_D("replace into `ims_amouse_weicard_industry` values('7',0xe5b9bfe5918ac2b7e4bc9ae5b195c2b7e585ace585b3,'8','0');");
E_D("replace into `ims_amouse_weicard_industry` values('8',0xe794b5e5ad90c2b7e5beaee794b5e5ad90,'9','0');");
E_D("replace into `ims_amouse_weicard_industry` values('9',0xe789a9e4b89ae7aea1e79086c2b7e59586e4b89ae4b8ade5bf83,'10','0');");
E_D("replace into `ims_amouse_weicard_industry` values('10',0xe5aeb6e5b185c2b7e5aea4e58685e8aebee8aea1c2b7e8a385e6bda2,'11','0');");
E_D("replace into `ims_amouse_weicard_industry` values('11',0xe4b8ade4bb8be69c8de58aa1,'12','0');");
E_D("replace into `ims_amouse_weicard_industry` values('12',0xe4b893e4b89ae69c8de58aa1efbc88e592a8e8afa2c2b7e8b4a2e4bc9ac2b7e6b395e5be8be7ad89efbc89,'13','0');");
E_D("replace into `ims_amouse_weicard_industry` values('13',0xe6a380e9aa8cc2b7e6a380e6b58bc2b7e8aea4e8af81,'14','0');");
E_D("replace into `ims_amouse_weicard_industry` values('14',0xe8b4b8e69893c2b7e8bf9be587bae58fa3,'15','0');");
E_D("replace into `ims_amouse_weicard_industry` values('15',0xe5aa92e4bd93c2b7e587bae78988c2b7e69687e58c96e4bca0e692ad,'16','0');");
E_D("replace into `ims_amouse_weicard_industry` values('16',0xe58db0e588b7c2b7e58c85e8a385c2b7e980a0e7bab8,'17','0');");
E_D("replace into `ims_amouse_weicard_industry` values('17',0xe5bfabe9809fe6b688e8b4b9e59381,'18','0');");
E_D("replace into `ims_amouse_weicard_industry` values('18',0xe88090e794a8e6b688e8b4b9e59381,'19','0');");
E_D("replace into `ims_amouse_weicard_industry` values('19',0xe78ea9e585b7c2b7e5b7a5e889bae59381c2b7e694b6e8978fe59381c2b7e5a5a2e4be88e59381,'20','0');");
E_D("replace into `ims_amouse_weicard_industry` values('20',0xe5aeb6e794b5e4b89a,'21','0');");
E_D("replace into `ims_amouse_weicard_industry` values('21',0xe58a9ee585ace8aebee5a487c2b7e794a8e59381,'22','0');");
E_D("replace into `ims_amouse_weicard_industry` values('22',0xe689b9e58f91c2b7e99bb6e594ae,'23','0');");
E_D("replace into `ims_amouse_weicard_industry` values('23',0xe4baa4e9809ac2b7e8bf90e8be93c2b7e789a9e6b581,'24','0');");
E_D("replace into `ims_amouse_weicard_industry` values('24',0xe5a8b1e4b990c2b7e8bf90e58aa8c2b7e4bc91e997b2,'25','0');");
E_D("replace into `ims_amouse_weicard_industry` values('25',0xe588b6e88dafc2b7e7949fe789a9e5b7a5e7a88b,'26','0');");
E_D("replace into `ims_amouse_weicard_industry` values('26',0xe58cbbe79697e8aebee5a487c2b7e599a8e6a2b0,'27','0');");
E_D("replace into `ims_amouse_weicard_industry` values('27',0xe78eafe4bf9de8a18ce4b89a,'28','0');");
E_D("replace into `ims_amouse_weicard_industry` values('28',0xe79fb3e6b2b9c2b7e58c96e5b7a5c2b7e79fbfe4baa7c2b7e98787e68e98c2b7e586b6e782bcc2b7e58e9fe69d90e69699,'29','0');");
E_D("replace into `ims_amouse_weicard_industry` values('29',0xe883bde6ba90c2b7e6b0b4e588a9,'30','0');");
E_D("replace into `ims_amouse_weicard_industry` values('30',0xe4bbaae599a8c2b7e4bbaae8a1a8c2b7e5b7a5e4b89ae887aae58aa8e58c96c2b7e794b5e6b094,'31','0');");
E_D("replace into `ims_amouse_weicard_industry` values('31',0xe6b1bde8bda6c2b7e691a9e68998e8bda6,'32','0');");
E_D("replace into `ims_amouse_weicard_industry` values('32',0xe69cbae6a2b0e588b6e980a0c2b7e69cbae794b5c2b7e9878de5b7a5,'33','0');");
E_D("replace into `ims_amouse_weicard_industry` values('33',0xe58e9fe69d90e69699e58f8ae58aa0e5b7a5,'34','0');");
E_D("replace into `ims_amouse_weicard_industry` values('34',0xe5869cc2b7e69e97c2b7e789a7c2b7e6b894,'35','0');");
E_D("replace into `ims_amouse_weicard_industry` values('35',0xe888aae7a9bac2b7e888aae5a4a9e7a094e7a9b6e4b88ee588b6e980a0,'36','0');");
E_D("replace into `ims_amouse_weicard_industry` values('36',0xe888b9e888b6e588b6e980a0,'37','0');");
E_D("replace into `ims_amouse_weicard_industry` values('37',0xe694bfe5ba9cc2b7e99d9ee890a5e588a9e69cbae69e84,'38','0');");
E_D("replace into `ims_amouse_weicard_industry` values('38',0xe98592e5ba972fe69785e6b8b8,'39','0');");
E_D("replace into `ims_amouse_weicard_industry` values('39',0xe9a490e9a5ae2fe5a8b1e4b990,'40','0');");
E_D("replace into `ims_amouse_weicard_industry` values('40',0xe79bb4e99480e8a18ce4b89a,'41','0');");
E_D("replace into `ims_amouse_weicard_industry` values('41',0xe7be8ee5aeb9e7be8ee58f91,'42','0');");
E_D("replace into `ims_amouse_weicard_industry` values('42',0xe69599e882b2c2b7e59fb9e8aeadc2b7e7a791e7a094c2b7e999a2e6a0a1c2b7e5a4a7e5ada6,'43','0');");
E_D("replace into `ims_amouse_weicard_industry` values('45',0x444e46,'44','0');");

require("../../inc/footer.php");
?>