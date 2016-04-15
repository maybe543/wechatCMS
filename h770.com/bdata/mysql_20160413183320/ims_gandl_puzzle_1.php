<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_gandl_puzzle`;");
E_C("CREATE TABLE `ims_gandl_puzzle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `topic` varchar(255) NOT NULL COMMENT '主题',
  `cover` varchar(255) NOT NULL COMMENT '封面文件路径',
  `detail` text NOT NULL COMMENT '中奖人数',
  `award` int(11) NOT NULL,
  `award_remark` text NOT NULL COMMENT '宝藏获取说明',
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `type` tinyint(2) NOT NULL,
  `keys` text NOT NULL,
  `keys_least` int(11) NOT NULL COMMENT '至少找到N个线索才能回答',
  `truth` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '正确答案',
  `truth_options` text,
  `truth_remark` text NOT NULL COMMENT '答案解释说明',
  `share` text,
  `ad` text,
  `status` tinyint(1) NOT NULL COMMENT '1:正常，2停止',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>