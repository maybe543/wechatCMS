<?php

/**
 *助力活动
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_zl') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`rid` int(10) NOT NULL,
 `weid` int(11) NOT NULL  ,
 `title` varchar(200) NOT NULL,
 `starttime` int(10),
 `endtime` int(10),
 `awardstime` int(10),
 `awardetime` int(10),
 `awardaddress` varchar(2000),
 `rule` text,
 `award` text,
 `content` text,
  `title_bg` varchar(300),
  `share_bg` varchar(300),
 `copyright` varchar(50),
 `randking_count` int(10),
 `follow_url` varchar(200),
`new_title` varchar(200),
`new_icon` varchar(200),
`new_content` varchar(200),
 `share_title` varchar(200),
`share_icon` varchar(200),
`share_content` varchar(200),
`createtime` int(10),
`updatetime` int(10),
`top_banner` varchar(500),
`top_banner_title` varchar(100),
`top_banner_show` int(1) default 0,
 `top_banner_url` varchar(500),
 `zl_follow_enable` int(1),
 `join_follow_enable` int(1),
`follow_dlg_tip` varchar(500),
`follow_btn_name` varchar(20),
`udetail_eable` int(1),
`telname` varchar(30) default '手机',
`contact_tel` varchar(20),
`contact_name` varchar(20) default '联系小编',
`startp` int(10),
`maxp` int(10),
`zl_rule` varchar(2000),
`join_btn_name` varchar(100) default '我要报名',
`uzl_btn_name` varchar(100) default '发送给好友助力',
`fzl_btn_name` varchar(100) default '发送给好友帮他助力',
`top_tag` int(3),
`view_count` int(3),
`share_count` int(3),
`f_zl_limit` int(3) ,
`zlunit` varchar(10),
`syncredit` int(1),
`f_zl_limit_tip` varchar(2000),
`f_day_limit` int(10),
`f_day_limit_tip` varchar(2000),
`f_diff_limt` int(10),
`f_diff_tip` varchar(2000),
`ip_limit` int(10),
`ip_limit_tip` varchar(2000),
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

/**
 * 助力用户
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_zl_user') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`zid` int(10) ,
`moid` varchar(500) NOT NULL,
`openid` varchar(200) NOT NULL,
`nickname` varchar(100) NOT NULL,
`headimgurl` varchar(300) NOT NULL,
`uname` varchar(200),
`tel` varchar(50),
`point` int(10),
`ptime` int(10),
`ip` varchar(50),
`createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

/**
 * 助力用户
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_zl_friend') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`zid` int(10) ,
`uid` int(10),
`openid` varchar(200) NOT NULL,
`nickname` varchar(300),
`headimgurl` varchar(300),
`ip` varchar(50),
`point` int(10),
`createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

/**
 * 助力设置
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_zl_setting') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`appid` varchar(300) ,
`apps` varchar(300),
`createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);




