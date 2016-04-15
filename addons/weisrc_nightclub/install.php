<?php
global $_W;

$sql = "
DROP TABLE IF EXISTS `ims_weisrc_nightclub_activity`;
CREATE TABLE `ims_weisrc_nightclub_activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类别id',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类别id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT 'logo',
  `content` text COMMENT '内容',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '地址',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '网址',
  `start_time` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `isfirst` tinyint(1) NOT NULL DEFAULT '0' COMMENT '首页推荐',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_weisrc_nightclub_activity_feedback`;
CREATE TABLE `ims_weisrc_nightclub_activity_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL COMMENT '公众号ID',
  `activityid` int(11) NOT NULL,
  `parentid` int(11) DEFAULT '0',
  `from_user` varchar(100) DEFAULT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `headimgurl` varchar(500) DEFAULT '',
  `content` varchar(600) DEFAULT NULL,
  `top` tinyint(1) NOT NULL DEFAULT '0',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `dateline` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_weisrc_nightclub_activity_user`;
CREATE TABLE `ims_weisrc_nightclub_activity_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `activityid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT '',
  `nickname` varchar(100) DEFAULT '',
  `headimgurl` varchar(500) DEFAULT '',
  `title` varchar(200) DEFAULT NULL COMMENT '存酒名称',
  `username` varchar(100) DEFAULT NULL COMMENT '用户姓名',
  `tel` varchar(30) DEFAULT NULL COMMENT '联系电话',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_weisrc_nightclub_category`;
CREATE TABLE `ims_weisrc_nightclub_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_weisrc_nightclub_goods`;
CREATE TABLE `ims_weisrc_nightclub_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `marketprice` varchar(10) NOT NULL DEFAULT '',
  `productprice` varchar(10) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_weisrc_nightclub_introduce`;
CREATE TABLE `ims_weisrc_nightclub_introduce` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '顶部图片',
  `info` varchar(1000) NOT NULL DEFAULT '' COMMENT '简短描述',
  `content` text NOT NULL COMMENT '简介',
  `savewinerule` text NOT NULL COMMENT '存酒规则',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省',
  `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市',
  `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区',
  `hours` varchar(200) NOT NULL DEFAULT '' COMMENT '营业时间',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '地址',
  `contact` varchar(100) NOT NULL DEFAULT '' COMMENT '联系人',
  `consume` varchar(100) NOT NULL DEFAULT '' COMMENT '人均消费',
  `wifi` varchar(200) NOT NULL DEFAULT '' COMMENT '人均消费',
  `place` varchar(200) NOT NULL DEFAULT '',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在手机端显示',
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_weisrc_nightclub_neighbor_feedback`;
CREATE TABLE `ims_weisrc_nightclub_neighbor_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL COMMENT '公众号ID',
  `from_user` varchar(100) DEFAULT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `headimgurl` varchar(500) DEFAULT '',
  `content` varchar(600) DEFAULT NULL,
  `top` tinyint(1) NOT NULL DEFAULT '0',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `dateline` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_weisrc_nightclub_neighbor_user`;
CREATE TABLE `ims_weisrc_nightclub_neighbor_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT '',
  `nickname` varchar(100) DEFAULT '',
  `headimgurl` varchar(500) DEFAULT '',
  `username` varchar(100) DEFAULT NULL COMMENT '用户姓名',
  `weixin` varchar(50) DEFAULT NULL COMMENT '微信',
  `tel` varchar(30) DEFAULT NULL COMMENT '联系电话',
  `qq` varchar(30) DEFAULT NULL COMMENT '联系电话',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_weisrc_nightclub_photo`;
CREATE TABLE `ims_weisrc_nightclub_photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `attachment` varchar(100) NOT NULL DEFAULT '',
  `from_user` varchar(100) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `likecount` int(10) unsigned NOT NULL DEFAULT '0',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `isfirst` tinyint(1) NOT NULL DEFAULT '1' COMMENT '显示位置',
  `mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '加入方式 0:后台 1:前台上传',
  `checked` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_weisrc_nightclub_product`;
CREATE TABLE `ims_weisrc_nightclub_product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '小类id',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '大类id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT 'logo',
  `content` text NOT NULL COMMENT '描述',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '微站网址',
  `isfirst` tinyint(1) NOT NULL DEFAULT '0' COMMENT '首页推荐',
  `top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐商家，相当于置顶',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在手机端显示',
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_weisrc_nightclub_savewine_log`;
CREATE TABLE `ims_weisrc_nightclub_savewine_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT '',
  `nickname` varchar(100) DEFAULT '',
  `headimgurl` varchar(500) DEFAULT '',
  `savenumber` varchar(100) NOT NULL DEFAULT '' COMMENT '存酒卡号',
  `title` varchar(200) DEFAULT NULL COMMENT '存酒名称',
  `username` varchar(100) DEFAULT NULL COMMENT '用户姓名',
  `tel` varchar(30) DEFAULT NULL COMMENT '联系电话',
  `remark` text NOT NULL COMMENT '备注',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `takeouttime` int(10) unsigned NOT NULL DEFAULT '0',
  `savetime` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_weisrc_nightclub_setting`;
CREATE TABLE `ims_weisrc_nightclub_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '所属帐号',
  `title` varchar(100) NOT NULL DEFAULT '',
  `bg` varchar(500) NOT NULL DEFAULT '',
  `pagesize` int(10) unsigned NOT NULL DEFAULT '5' COMMENT '每页显示数据量',
  `topcolor` varchar(20) NOT NULL DEFAULT '' COMMENT '顶部字体颜色',
  `topbgcolor` varchar(20) NOT NULL DEFAULT '' COMMENT '顶部字体颜色',
  `announcebordercolor` varchar(20) NOT NULL DEFAULT '' COMMENT '公告边框颜色',
  `announcebgcolor` varchar(20) NOT NULL DEFAULT '' COMMENT '公告背景颜色',
  `announcecolor` varchar(20) NOT NULL DEFAULT '' COMMENT '公告字体颜色',
  `storestitlecolor` varchar(20) NOT NULL DEFAULT '' COMMENT '商家名称颜色',
  `storesstatuscolor` varchar(20) NOT NULL DEFAULT '' COMMENT '商家状态颜色',
  `showcity` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示城市选择',
  `settled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启入驻',
  `feedback_show_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `feedback_check_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '留言是否需要审核',
  `photo_check_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '相片是否需要审核',
  `scroll_announce` varchar(500) NOT NULL DEFAULT '' COMMENT '公告',
  `scroll_announce_speed` tinyint(2) unsigned NOT NULL DEFAULT '6' COMMENT '公告滚动速度',
  `scroll_announce_link` varchar(500) NOT NULL DEFAULT '' COMMENT '公告链接',
  `scroll_announce_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示顶部公告',
  `copyright` varchar(500) NOT NULL DEFAULT '' COMMENT '底部版权',
  `copyright_link` varchar(500) NOT NULL DEFAULT '' COMMENT '底部版权链接',
  `appid` varchar(300) NOT NULL DEFAULT '' COMMENT 'appid',
  `secret` varchar(300) NOT NULL DEFAULT '' COMMENT 'secret',
  `share_title` varchar(100) NOT NULL DEFAULT '',
  `share_image` varchar(500) NOT NULL DEFAULT '',
  `share_desc` varchar(200) NOT NULL DEFAULT '',
  `share_cancel` varchar(200) NOT NULL DEFAULT '',
  `share_url` varchar(200) NOT NULL DEFAULT '',
  `share_num` int(10) NOT NULL DEFAULT '0',
  `follow_url` varchar(200) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL,
  `tplinfowine` varchar(500) NOT NULL DEFAULT '' COMMENT '模版消息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
";
pdo_query($sql);