<?php
$sql = "
CREATE TABLE IF NOT EXISTS `ims_weisrc_businesscenter_feedback` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `weid` int(11) NOT NULL COMMENT '公众号ID',
    `storeid` int(11) NOT NULL COMMENT '商家ID',
    `parentid` int(11) DEFAULT '0' COMMENT '父级ID',
    `from_user` varchar(100) DEFAULT NULL,
    `nickname` varchar(30) DEFAULT NULL,
    `content` varchar(600) DEFAULT NULL,
    `top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶',
    `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
    `status` tinyint(1) DEFAULT '0',
    `dateline` int(11) DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_businesscenter_news` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `weid` int(10) unsigned NOT NULL,
    `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家id',
    `title` varchar(200) NOT NULL DEFAULT '',
    `thumb` varchar(500) NOT NULL DEFAULT '',
    `summary` varchar(1000) NOT NULL DEFAULT '',
    `description` text NOT NULL DEFAULT '',
    `address` varchar(200) NOT NULL DEFAULT '',
    `start_time` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
    `end_time` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
    `url` varchar(200) NOT NULL DEFAULT '',
    `isfirst` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否在首页显示',
    `top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶',
    `mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '加入方式 0:后台 1:申请',
    `checked` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核',
    `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
    `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
    `dateline` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);

if (!pdo_fieldexists('weisrc_businesscenter_setting', 'appid')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `appid` varchar(300) NOT NULL DEFAULT '' COMMENT 'appid';");
}

if (!pdo_fieldexists('weisrc_businesscenter_setting', 'secret')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `secret` varchar(300) NOT NULL DEFAULT '' COMMENT 'secret';");
}

if (!pdo_fieldexists('weisrc_businesscenter_setting', 'scroll_announce_speed')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `scroll_announce_speed` tinyint(2) unsigned NOT NULL DEFAULT '6'  COMMENT '公告滚动速度';");
}

if (!pdo_fieldexists('weisrc_businesscenter_setting', 'copyright')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `copyright` varchar(500) NOT NULL DEFAULT '' COMMENT '底部版权';");
}
if (!pdo_fieldexists('weisrc_businesscenter_setting', 'copyright_link')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `copyright_link` varchar(500) NOT NULL DEFAULT '' COMMENT '底部版权链接';");
}

if (!pdo_fieldexists('weisrc_businesscenter_setting', 'feedback_show_enable')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `feedback_show_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示';");
}
if (!pdo_fieldexists('weisrc_businesscenter_setting', 'feedback_check_enable')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `feedback_check_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '留言是否需要审核';");
}

if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'top')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_feedback')." ADD `top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶';");
}

if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'displayorder')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_feedback')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}

if (!pdo_fieldexists('weisrc_businesscenter_stores', 'site_name')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `site_name` varchar(100) NOT NULL DEFAULT '' COMMENT '微站按钮名称';");
}
if (!pdo_fieldexists('weisrc_businesscenter_stores', 'site_url')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `site_url` varchar(200) NOT NULL DEFAULT '' COMMENT '微站网址';");
}
if (!pdo_fieldexists('weisrc_businesscenter_stores', 'shop_name')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `shop_name` varchar(100) NOT NULL DEFAULT '' COMMENT '折扣按钮名称';");
}

if (!pdo_fieldexists('weisrc_businesscenter_stores', 'discounts')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `discounts` varchar(200) NOT NULL COMMENT '会员折扣';");
}

//ims_weisrc_businesscenter_setting
if (!pdo_fieldexists('weisrc_businesscenter_setting', 'scroll_announce')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `scroll_announce` varchar(500) NOT NULL DEFAULT '' COMMENT '公告';");
}

if (!pdo_fieldexists('weisrc_businesscenter_setting', 'scroll_announce_link')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `scroll_announce_link` varchar(500) NOT NULL DEFAULT '' COMMENT '公告链接';");
}

if (!pdo_fieldexists('weisrc_businesscenter_setting', 'scroll_announce_enable')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `scroll_announce_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示顶部公告';");
}
if (!pdo_fieldexists('weisrc_businesscenter_setting', 'menuname1')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `menuname1` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单1名称';");
}
if (!pdo_fieldexists('weisrc_businesscenter_setting', 'menulink1')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `menulink1` varchar(500) NOT NULL DEFAULT '' COMMENT '菜单1链接';");
}
if (!pdo_fieldexists('weisrc_businesscenter_setting', 'menuname2')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `menuname2` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单2名称';");
}
if (!pdo_fieldexists('weisrc_businesscenter_setting', 'menulink2')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `menulink2` varchar(500) NOT NULL DEFAULT '' COMMENT '菜单2链接';");
}
if (!pdo_fieldexists('weisrc_businesscenter_setting', 'menuname3')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `menuname3` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单3名称';");
}
if (!pdo_fieldexists('weisrc_businesscenter_setting', 'menulink3')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `menulink3` varchar(500) NOT NULL DEFAULT '' COMMENT '菜单3链接';");
}

if(!pdo_fieldexists('weisrc_businesscenter_news', 'address')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_news')." ADD `address` varchar(200) NOT NULL DEFAULT '';");
}

if(!pdo_fieldexists('weisrc_businesscenter_setting', 'settled')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `settled` tinyint(1) unsigned NOT NULL DEFAULT '0'  COMMENT '是否开启入驻';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'address')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `address` varchar(200) NOT NULL DEFAULT '' COMMENT '地址';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'tel')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'place')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `place` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'lat')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'lng')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'location_p')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'location_c')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'location_a')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'location_a')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区';");
}

//**********************************************************

if(!pdo_fieldexists('weisrc_businesscenter_stores', 'username')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `username` varchar(20) NOT NULL DEFAULT '' COMMENT '联系人';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'businesslicense')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `businesslicense` varchar(200) NOT NULL DEFAULT '' COMMENT '营业执照';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'mode')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '加入方式 0:后台 1:申请入驻';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'checked')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `checked` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'shop_url')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `shop_url` varchar(400) NOT NULL DEFAULT '' COMMENT '商城链接';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'qrcode_url')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `qrcode_url` varchar(400) NOT NULL DEFAULT '' COMMENT '素材链接';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'qrcode_description')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `qrcode_description` varchar(200) NOT NULL DEFAULT '' COMMENT '二维码文字提示';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'from_user')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `from_user` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'starttime')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `starttime` varchar(10) NOT NULL DEFAULT '08:00' COMMENT '开始时间';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'endtime')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `endtime` varchar(10) NOT NULL DEFAULT '09:00' COMMENT '结束时间';");
}


if(!pdo_fieldexists('weisrc_businesscenter_stores', 'time_enable1')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `time_enable1` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用营业时间1';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'time_enable2')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `time_enable2` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用营业时间2';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'time_enable3')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `time_enable3` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用营业时间3';");
}

if(!pdo_fieldexists('weisrc_businesscenter_stores', 'starttime2')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `starttime2` varchar(10) NOT NULL DEFAULT '09:00' COMMENT '开始时间';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'endtime2')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `endtime2` varchar(10) NOT NULL DEFAULT '18:00' COMMENT '结束时间';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'starttime3')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `starttime3` varchar(10) NOT NULL DEFAULT '09:00' COMMENT '开始时间';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'endtime3')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `endtime3` varchar(10) NOT NULL DEFAULT '18:00' COMMENT '结束时间';");
}

if(!pdo_fieldexists('weisrc_businesscenter_stores', 'share_title')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `share_title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'share_desc')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `share_desc` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'share_cancel')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `share_cancel` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'share_url')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `share_url` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'share_num')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `share_num` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'follow_url')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD `follow_url` varchar(200) NOT NULL DEFAULT '';");
}

if(!pdo_fieldexists('weisrc_businesscenter_setting', 'share_title')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `share_title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'share_desc')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `share_desc` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'share_cancel')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `share_cancel` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'share_url')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `share_url` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'share_num')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `share_num` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'follow_url')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `follow_url` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'share_image')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD `share_image` varchar(500) NOT NULL DEFAULT '';");
}
if(pdo_fieldexists('weisrc_businesscenter_stores', 'description')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." change  `description` `description` text;");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'isvip')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD   `isvip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '时间限制';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'vip_start')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD    `vip_start` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_businesscenter_stores', 'vip_end')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_stores')." ADD     `vip_end` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_businesscenter_setting', 'statistics')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_businesscenter_setting')." ADD   `statistics` text NOT NULL;");
}