<?php
 defined('IN_IA') or exit('Access Denied');
$sql = "
CREATE TABLE IF NOT EXISTS `ims_quickdist_commission` (
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `order_leader` varchar(50) NOT NULL,
  `order_openid` varchar(50) NOT NULL,
  `order_createtime` int(10) unsigned NOT NULL,
  `level` int(10) unsigned NOT NULL,
  `commission_value` decimal(10,2) default 0 NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`weid`, `orderid`, `goodsid`, `order_leader`),
  KEY `indx_order_leader` (`weid`, `order_leader`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_run($sql);
if(pdo_fieldexists('quickdist_commission', 'price')){
    pdo_query("ALTER TABLE " . tablename('quickdist_commission') . " CHANGE `price` `ordergoodsprice` decimal(10,2) NOT NULL DEFAULT '0' ;");
}
if(!pdo_fieldexists('quickdist_commission', 'rate')){
    pdo_query("ALTER TABLE " . tablename('quickdist_commission') . " ADD `rate` decimal(10,2) NOT NULL DEFAULT '0' ;");
}
if(!pdo_fieldexists('quickdist_commission', 'level')){
    pdo_query("ALTER TABLE " . tablename('quickdist_commission') . " ADD `level` int(10) NOT NULL;");
}
if(!pdo_fieldexists('quickdist_commission', 'total')){
    pdo_query("ALTER TABLE " . tablename('quickdist_commission') . " ADD `total` int(10) NOT NULL;");
}
$sql = "
CREATE TABLE IF NOT EXISTS `ims_quickdist_notify` (
  `weid` int(10)  NOT NULL,
  `level` int(10)  NOT NULL DEFAULT 3,
  `param` varchar(10240)  NOT NULL,
  `createtime`  int(10) unsigned NOT NULL DEFAULT 0
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_run($sql);
