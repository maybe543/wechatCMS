<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_netsbd_set`;");
E_C("CREATE TABLE `ims_netsbd_set` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `today_income` decimal(10,2) DEFAULT NULL,
  `integral_eq_blance` decimal(8,2) DEFAULT NULL COMMENT '多少积分可兑换余额\r\n            积分满多少时自动兑换',
  `share_eq_integral` decimal(8,2) DEFAULT NULL,
  `max_share_today` decimal(8,2) DEFAULT NULL,
  `click_eq_integral` decimal(8,2) DEFAULT NULL,
  `max_click_today` decimal(8,2) DEFAULT NULL,
  `beclick_eq_integral` decimal(8,2) DEFAULT NULL,
  `max_beclick` decimal(8,2) DEFAULT NULL,
  `good_eq_integral` decimal(8,2) DEFAULT NULL COMMENT '0 未审核\r\n            1 提现中\r\n            2 已完成',
  `max_good_today` decimal(8,2) DEFAULT NULL,
  `comment_eq_integral` decimal(8,2) DEFAULT NULL,
  `max_comment_today` decimal(8,2) DEFAULT NULL,
  `begood_eq_integral` decimal(8,2) DEFAULT NULL,
  `max_begood` decimal(8,2) DEFAULT NULL,
  `becomment_eq_integral` decimal(8,2) DEFAULT NULL,
  `max_becomment` decimal(8,2) DEFAULT NULL,
  `login_eq_integral` decimal(8,2) DEFAULT NULL,
  `reregster_eq_integral` decimal(8,2) DEFAULT NULL,
  `today_maxregister` decimal(8,2) DEFAULT NULL,
  `clickad_eq_integral` decimal(8,2) DEFAULT NULL,
  `today_maxclickad` decimal(8,2) DEFAULT NULL,
  `cashshare_eq_integral` decimal(8,2) DEFAULT NULL,
  `today_maxshare` decimal(8,2) DEFAULT NULL,
  `changegood_eq_integral` decimal(8,2) DEFAULT NULL,
  `today_maxchange` decimal(8,2) DEFAULT NULL,
  `palygame_eq_integral` decimal(8,2) DEFAULT NULL,
  `today_maxpalygame` decimal(8,2) DEFAULT NULL,
  `list_ad_top` varchar(255) DEFAULT NULL,
  `list_ad_middle` varchar(255) DEFAULT NULL,
  `list_ad_bottom` varchar(1000) DEFAULT NULL,
  `detail_ad_top` varchar(255) DEFAULT NULL,
  `detail_ad_middle` varchar(255) DEFAULT NULL,
  `detail_ad_bottom` varchar(1000) DEFAULT NULL,
  `share_img` varchar(255) DEFAULT NULL,
  `share_title` varchar(255) DEFAULT NULL,
  `share_desc` varchar(255) DEFAULT NULL,
  `mchid` varchar(255) DEFAULT NULL,
  `follow_title` varchar(255) DEFAULT NULL,
  `follow_url` varchar(255) DEFAULT NULL,
  `follow_ico` varchar(255) DEFAULT NULL,
  `register_eq_money` decimal(8,2) DEFAULT NULL,
  `member_level1dis` int(11) DEFAULT NULL,
  `member_level2dis` int(11) DEFAULT NULL,
  `member_level3dis` int(11) DEFAULT NULL,
  `template_msg1` varchar(50) DEFAULT NULL,
  `template_msg2` varchar(50) DEFAULT NULL,
  `template_msg3` varchar(50) DEFAULT NULL,
  `template_msg4` varchar(50) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `min_cashmoney` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商户基础设置'");

require("../../inc/footer.php");
?>