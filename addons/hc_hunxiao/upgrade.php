<?php

if(!pdo_fieldexists('hc_hunxiao_rules', 'template_id')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_rules')." ADD `template_id` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('hc_hunxiao_rules', 'qrpicture')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_rules')." ADD `qrpicture` varchar(255) DEFAULT NULL;");
}
//0810
if(!pdo_fieldexists('hc_hunxiao_goods', 'tips')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_goods')." ADD   `tips` text;");
}
if(!pdo_fieldexists('hc_hunxiao_order', 'refundreason')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_order')." ADD  `refundreason` text;");
}
if(!pdo_fieldexists('hc_hunxiao_rules', 'sendGoodsSend')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_rules')." ADD   `sendGoodsSend` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_hunxiao_rules', 'sendCommWarm')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_rules')." ADD   `sendCommWarm` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_hunxiao_rules', 'sendCheckChange')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_rules')." ADD  `sendCheckChange` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_hunxiao_rules', 'sendApplyMoneyBack')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_rules')." ADD  `sendApplyMoneyBack` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_hunxiao_rules', 'sendMoneyBack')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_rules')." ADD  `sendMoneyBack` varchar(100) DEFAULT NULL;");
}

$sql = "
	CREATE TABLE IF NOT EXISTS `ims_hc_hunxiao_templatenews` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `weid` int(10) NOT NULL,
	  `template_id` varchar(100) DEFAULT NULL,
	  `sendGoodsSend` varchar(100) DEFAULT NULL,
	  `sendCommWarm` varchar(100) DEFAULT NULL,
	  `sendCheckChange` varchar(100) DEFAULT NULL,
	  `sendApplyMoneyBack` varchar(100) DEFAULT NULL,
	  `sendMoneyBack` varchar(100) DEFAULT NULL,
	  `createtime` int(10) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `ims_hc_hunxiao_poster` (
	  `id` int(10) NOT NULL AUTO_INCREMENT,
	  `uniacid` int(11) DEFAULT '0',
	  `title` varchar(255) DEFAULT '',
	  `bg` varchar(255) DEFAULT '',
	  `data` text,
	  `keyword` varchar(255) DEFAULT '',
	  `waittext` varchar(50) default '',
	  `createtime` int(10) unsigned NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_query($sql);

if(!pdo_fieldexists('hc_hunxiao_order', 'refundreason')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_order')." ADD `refundreason` text;");
}

if(!pdo_fieldexists('hc_hunxiao_goods', 'tips')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_goods')." ADD `tips` text;");
}
if(!pdo_fieldexists('hc_hunxiao_goods', 'fxprice')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_goods')." ADD   `fxprice` decimal(10,2) NOT NULL DEFAULT '0.00';");
}

if(!pdo_fieldexists('hc_hunxiao_goods', 'issetfree')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_goods')." ADD     `issetfree` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hc_hunxiao_member', 'ischange')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_member')." ADD `ischange` tinyint(1) DEFAULT '0' COMMENT '是否改变海报样式，0否，1是';");
}
if(!pdo_fieldexists('hc_hunxiao_member', 'mediatime')) {
	pdo_query("ALTER TABLE ".tablename('hc_hunxiao_member')." ADD   `mediatime` int(10) NOT NULL;");
}

?>