<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_rule_keyword`;");
E_C("CREATE TABLE `ims_rule_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `module` varchar(50) NOT NULL,
  `content` varchar(255) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_content` (`content`),
  KEY `idx_rid` (`rid`),
  KEY `idx_uniacid_type_content` (`uniacid`,`type`,`content`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8");
E_D("replace into `ims_rule_keyword` values('1','1','0',0x75736572617069,0x5e2e2be5a4a9e6b09424,'3','255','1');");
E_D("replace into `ims_rule_keyword` values('2','2','0',0x75736572617069,0x5ee799bee7a7912e2b24,'3','255','1');");
E_D("replace into `ims_rule_keyword` values('3','2','0',0x75736572617069,0x5ee5ae9ae4b9892e2b24,'3','255','1');");
E_D("replace into `ims_rule_keyword` values('4','3','0',0x75736572617069,0x5e402e2b24,'3','255','1');");
E_D("replace into `ims_rule_keyword` values('5','4','0',0x75736572617069,0xe697a5e58e86,'1','255','1');");
E_D("replace into `ims_rule_keyword` values('6','4','0',0x75736572617069,0xe4b887e5b9b4e58e86,'1','255','1');");
E_D("replace into `ims_rule_keyword` values('7','4','0',0x75736572617069,0xe9bb84e58e86,'1','255','1');");
E_D("replace into `ims_rule_keyword` values('8','4','0',0x75736572617069,0xe587a0e58fb7,'1','255','1');");
E_D("replace into `ims_rule_keyword` values('9','5','0',0x75736572617069,0xe696b0e997bb,'1','255','1');");
E_D("replace into `ims_rule_keyword` values('10','6','0',0x75736572617069,0x5e28e794b3e9809a7ce59c86e9809a7ce4b8ade9809a7ce6b187e9809a7ce99fb5e8bebe7ce9a1bae4b8b07c454d5329202a5b612d7a302d395d7b312c7d24,'3','255','1');");
E_D("replace into `ims_rule_keyword` values('11','7','1',0x636f766572,0xe4b8aae4babae4b8ade5bf83,'1','0','1');");
E_D("replace into `ims_rule_keyword` values('12','8','1',0x636f766572,0xe9a696e9a1b5,'1','0','1');");

require("../../inc/footer.php");
?>