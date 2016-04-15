<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fighting_setting`;");
E_C("CREATE TABLE `ims_fighting_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(20) NOT NULL COMMENT '活动标题',
  `description` longtext NOT NULL COMMENT '活动介绍',
  `tiao` tinyint(1) unsigned NOT NULL COMMENT '1允许跳过0不允许',
  `status_fighting` tinyint(1) unsigned NOT NULL COMMENT '0正常1暂停2结束',
  `qnum` int(11) unsigned NOT NULL COMMENT '题目数量',
  `answertime` int(10) unsigned NOT NULL COMMENT '答题时间',
  `start` int(10) unsigned NOT NULL DEFAULT '1383235200' COMMENT '开始时间',
  `end` int(10) unsigned NOT NULL DEFAULT '1383235200' COMMENT '结束时间',
  `most_num_times` int(11) DEFAULT '0',
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `followurl` varchar(1000) DEFAULT '',
  `thumb` varchar(100) NOT NULL COMMENT '广告',
  `thumb_url` varchar(100) NOT NULL COMMENT '广告URL',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>