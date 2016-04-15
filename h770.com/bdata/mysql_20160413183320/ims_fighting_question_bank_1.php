<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fighting_question_bank`;");
E_C("CREATE TABLE `ims_fighting_question_bank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `figure` int(30) NOT NULL,
  `question` varchar(500) NOT NULL,
  `option_num` int(10) unsigned NOT NULL,
  `optionA` varchar(100) NOT NULL,
  `optionB` varchar(100) NOT NULL,
  `optionC` varchar(100) NOT NULL,
  `optionD` varchar(100) NOT NULL,
  `optionE` varchar(100) NOT NULL,
  `optionF` varchar(100) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `sid` int(10) unsigned NOT NULL COMMENT '广告URL',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>