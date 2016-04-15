<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_fighting_logs`;");
E_C("CREATE TABLE `ims_stonefish_fighting_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `day_credit` int(10) NOT NULL COMMENT '得分',
  `questionid` varchar(2000) NOT NULL COMMENT '答题ID',
  `questionids` varchar(500) NOT NULL COMMENT '答题答案1为正确0为错',
  `todayannum` int(10) NOT NULL COMMENT '今日问题数',
  `rightannum` int(10) NOT NULL COMMENT '今日正确数',
  `wrongannum` int(10) NOT NULL COMMENT '今日错误数',
  `jumpannum` int(10) NOT NULL COMMENT '今日跳过数',
  `createtime` int(10) NOT NULL COMMENT '参与时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>