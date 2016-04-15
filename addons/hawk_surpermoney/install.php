<?php
$sql = "
CREATE TABLE IF NOT EXISTS `ims_hmoney_article` (
  `id` int(11) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `status` tinyint(3) unsigned DEFAULT '0' COMMENT ' 文章状态',
  `reurl` varchar(200) NOT NULL COMMENT '跳转地址',
  `title` varchar(50) NOT NULL COMMENT '文章标题',
  `description` varchar(100) NOT NULL COMMENT '描述',
  `shareimg` varchar(100) NOT NULL COMMENT '分享图片',
  `content` text NOT NULL,
  `money` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '每次点击发放金额',
  `first` int(3) unsigned DEFAULT '0' COMMENT '一级分润',
  `second` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '二级分润',
  `totalmoney` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '共发放金额',
  `createtime` int(11) unsigned NOT NULL,
  `starttime` int(11) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(11) unsigned NOT NULL COMMENT '结束时间',
  `author` varchar(30) NOT NULL,
  `viewnums` int(11) unsigned NOT NULL,
  `limit` int(11) unsigned NOT NULL,
  `allow` tinyint(2) unsigned NOT NULL,
  INDEX(`uniacid`),
  INDEX(`type`),
  INDEX(`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_hmoney_cashrecords` (
`id` int(11) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `money` int(11) unsigned NOT NULL,
  `createtime` int(11) unsigned NOT NULL,
  INDEX(`uniacid`),
  INDEX(`openid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_hmoney_fans` (
`id` int(11) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(500) NOT NULL,
  `credit` int(11) unsigned NOT NULL COMMENT '总积分',
  `used` int(11) unsigned NOT NULL COMMENT '已提现',
  `memnums` int(11) unsigned NOT NULL COMMENT '家族成员',
  `createtime` int(11) unsigned NOT NULL,
  INDEX(`uniacid`),
  INDEX(`openid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_hmoney_father` (
`id` int(11) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `father` varchar(50) NOT NULL COMMENT '父openid',
  `createtime` int(11) unsigned NOT NULL COMMENT '建立时间',
  INDEX(`uniacid`),
  INDEX(`openid`),
  INDEX(`father`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_hmoney_records` (
`id` int(11) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `articleid` int(11) unsigned NOT NULL,
  `createtime` int(11) unsigned NOT NULL,
  INDEX(`uniacid`),
  INDEX(`openid`),
  INDEX(`articleid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

pdo_run($sql);