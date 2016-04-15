<?php
 if(!pdo_fieldexists('quickcredit_goods', 'min_idle_time')){
    pdo_query("ALTER TABLE " . tablename('quickcredit_goods') . " ADD `min_idle_time` int(11) unsigned NOT NULL DEFAULT 10 COMMENT  '最小兑换时间间隔,秒'");
}
if(!pdo_fieldexists('quickcredit_goods', 'timestart')){
    pdo_query("ALTER TABLE " . tablename('quickcredit_goods') . " ADD `timestart` int(11) NOT NULL DEFAULT 0 COMMENT  '排序'");
    pdo_query("UPDATE " . tablename('quickcredit_goods') . " SET timestart=0");
}
if(!pdo_fieldexists('quickcredit_goods', 'timeend')){
    pdo_query("ALTER TABLE " . tablename('quickcredit_goods') . " ADD `timeend` int(11) NOT NULL DEFAULT 0 COMMENT  '排序'");
    pdo_query("UPDATE " . tablename('quickcredit_goods') . " SET timeend=UNIX_TIMESTAMP(deadline)");
}
if(!pdo_fieldexists('quickcredit_goods', 'displayorder')){
    pdo_query("ALTER TABLE " . tablename('quickcredit_goods') . " ADD `displayorder` int(11) NOT NULL DEFAULT 0 COMMENT  '排序'");
}
if(!pdo_fieldexists('quickcredit_goods', 'type')){
    pdo_query("ALTER TABLE " . tablename('quickcredit_goods') . " ADD `type` int(10) NOT NULL DEFAULT '1' AFTER `content`;");
}
if(!pdo_fieldexists('quickcredit_goods', 'vip_require')){
    pdo_query("ALTER TABLE " . tablename('quickcredit_goods') . " ADD `vip_require` int(10) NOT NULL DEFAULT '0' COMMENT '兑换最低VIP级别';");
}
if(!pdo_fieldexists('quickcredit_request', 'cost')){
    pdo_query("ALTER TABLE " . tablename('quickcredit_request') . " ADD `cost` Decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickcredit_request', 'price')){
    pdo_query("ALTER TABLE " . tablename('quickcredit_request') . " ADD `price` Decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickcredit_request', 'alipay')){
    pdo_query("ALTER TABLE " . tablename('quickcredit_request') . " ADD `alipay` varchar(50) NOT NULL DEFAULT '';");
}
$sql = "CREATE TABLE IF NOT EXISTS `ims_quickcredit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `credit` decimal(10,2) DEFAULT NULL,
  `tag` varchar(100) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `delta` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY idx_from_user (`weid`, `from_user`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;";
pdo_run($sql);
