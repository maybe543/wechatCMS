<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_bbs_task`;");
E_C("CREATE TABLE `ims_meepo_bbs_task` (
  `taskid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `note` text NOT NULL,
  `num` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `maxnum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `image` varchar(150) NOT NULL DEFAULT '',
  `filename` varchar(50) NOT NULL DEFAULT '',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `nexttime` int(10) unsigned NOT NULL DEFAULT '0',
  `nexttype` varchar(20) NOT NULL DEFAULT '',
  `credit` smallint(6) NOT NULL DEFAULT '0',
  `displayorder` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`taskid`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8");
E_D("replace into `ims_meepo_bbs_task` values('41','3','1',0xe9a696e6aca1e782b9e8b59e,0xe9a696e6aca1e782b9e8b59eefbc8ce5a596e58ab1e7a7afe58886,'0','1000',0x687474703a2f2f7765697a616e2e61646d696e35752e636f6d2f2f6164646f6e732f6d6565706f5f6262732f69636f6e2e6a7067,0x66697273745f6c696b652e7461736b2e706870,'1457745478','1458955078','0','','10','0');");
E_D("replace into `ims_meepo_bbs_task` values('42','3','1',0xe98280e8afb7e5a5bde58f8b,0xe98280e8afb7e5a5bde58f8befbc8ce5a596e58ab1e7a7afe58886,'0','1000',0x687474703a2f2f7765697a616e2e61646d696e35752e636f6d2f2f6164646f6e732f6d6565706f5f6262732f69636f6e2e6a7067,0x667269656e642e7461736b2e706870,'1457745478','1458955078','0','','10','1');");
E_D("replace into `ims_meepo_bbs_task` values('43','3','1',0xe9a696e6aca1e8bdace58f91,0xe9a696e6aca1e79c8be5b896efbc8ce5a596e58ab1e7a7afe58886,'0','1000',0x687474703a2f2f7765697a616e2e61646d696e35752e636f6d2f2f6164646f6e732f6d6565706f5f6262732f69636f6e2e6a7067,0x66697273745f73686172652e7461736b2e706870,'1457745478','1458955078','0','','10','2');");
E_D("replace into `ims_meepo_bbs_task` values('44','3','1',0xe9a696e6aca1e79c8be5b896,0xe9a696e6aca1e8bdace58f91efbc8ce5a596e58ab1e7a7afe58886,'0','1000',0x687474703a2f2f7765697a616e2e61646d696e35752e636f6d2f2f6164646f6e732f6d6565706f5f6262732f69636f6e2e6a7067,0x66697273745f726561642e7461736b2e706870,'1457745478','1458955078','0','','10','3');");
E_D("replace into `ims_meepo_bbs_task` values('45','3','1',0xe5ae8ce59684e4b8aae4babae4bfa1e681af,0xe5ae8ce59684e4b8aae4babae4bfa1e681afefbc8ce5a596e58ab1e7a7afe58886,'0','1000',0x687474703a2f2f7765697a616e2e61646d696e35752e636f6d2f2f6164646f6e732f6d6565706f5f6262732f69636f6e2e6a7067,0x7570646174655f757365722e7461736b2e706870,'1457745478','1458955078','0','','10','4');");
E_D("replace into `ims_meepo_bbs_task` values('46','3','1',0xe9a696e6aca1e58f91e5b896,0xe9a696e6aca1e58f91e5b896efbc8ce5a596e58ab1e7a7afe58886efbc81,'0','1000',0x687474703a2f2f7765697a616e2e61646d696e35752e636f6d2f2f6164646f6e732f6d6565706f5f6262732f69636f6e2e6a7067,0x66697273745f706f73742e7461736b2e706870,'1457745478','1458955078','0','','10','5');");

require("../../inc/footer.php");
?>