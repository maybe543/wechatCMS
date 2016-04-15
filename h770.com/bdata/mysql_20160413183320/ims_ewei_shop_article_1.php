<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_shop_article`;");
E_C("CREATE TABLE `ims_ewei_shop_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_title` varchar(255) NOT NULL DEFAULT '' COMMENT '文章标题',
  `resp_desc` text NOT NULL COMMENT '回复介绍',
  `resp_img` text NOT NULL COMMENT '回复图片',
  `article_content` longtext,
  `article_category` int(11) NOT NULL DEFAULT '0' COMMENT '文章分类',
  `article_date_v` varchar(20) NOT NULL DEFAULT '' COMMENT '虚拟发布时间',
  `article_date` varchar(20) NOT NULL DEFAULT '' COMMENT '文章发布时间',
  `article_mp` varchar(50) NOT NULL DEFAULT '' COMMENT '公众号',
  `article_author` varchar(20) NOT NULL DEFAULT '' COMMENT '发布作者',
  `article_readnum_v` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟阅读量',
  `article_readnum` int(11) NOT NULL DEFAULT '0' COMMENT '真实阅读量',
  `article_likenum_v` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟点赞数',
  `article_likenum` int(11) NOT NULL DEFAULT '0' COMMENT '真实点赞数',
  `article_linkurl` varchar(300) NOT NULL DEFAULT '' COMMENT '阅读原文链接',
  `article_rule_daynum` int(11) NOT NULL DEFAULT '0' COMMENT '每人每天参与次数',
  `article_rule_allnum` int(11) NOT NULL DEFAULT '0' COMMENT '所有参与次数',
  `article_rule_credit` int(11) NOT NULL DEFAULT '0' COMMENT '增加y积分',
  `article_rule_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '增加z余额',
  `page_set_option_nocopy` int(1) NOT NULL DEFAULT '0' COMMENT '页面禁止复制url',
  `page_set_option_noshare_tl` int(1) NOT NULL DEFAULT '0' COMMENT '页面禁止分享至朋友圈',
  `page_set_option_noshare_msg` int(1) NOT NULL DEFAULT '0' COMMENT '页面禁止发送给好友',
  `article_keyword` varchar(255) NOT NULL DEFAULT '' COMMENT '页面关键字',
  `article_report` int(1) NOT NULL DEFAULT '0' COMMENT '举报按钮',
  `product_advs_type` int(1) NOT NULL DEFAULT '0' COMMENT '营销显示产品',
  `product_advs_title` varchar(255) NOT NULL DEFAULT '' COMMENT '营销产品标题',
  `product_advs_more` varchar(255) NOT NULL DEFAULT '' COMMENT '推广产品底部标题',
  `product_advs_link` varchar(255) NOT NULL DEFAULT '' COMMENT '推广产品底部链接',
  `product_advs` text NOT NULL COMMENT '营销商品',
  `article_state` int(1) NOT NULL DEFAULT '0',
  `network_attachment` varchar(255) DEFAULT '',
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `article_rule_credittotal` int(11) DEFAULT '0',
  `article_rule_moneytotal` decimal(10,2) DEFAULT '0.00',
  `article_rule_credit2` int(11) DEFAULT '0',
  `article_rule_money2` decimal(10,2) DEFAULT '0.00',
  `article_rule_creditm` int(11) DEFAULT '0',
  `article_rule_moneym` decimal(10,2) DEFAULT '0.00',
  `article_rule_creditm2` int(11) DEFAULT '0',
  `article_rule_moneym2` decimal(10,2) DEFAULT '0.00',
  `article_readtime` int(11) DEFAULT '0',
  `article_areas` varchar(255) DEFAULT '',
  `article_endtime` int(11) DEFAULT '0',
  `article_hasendtime` tinyint(3) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_article_title` (`article_title`),
  KEY `idx_article_content` (`article_content`(10)),
  KEY `idx_article_keyword` (`article_keyword`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>