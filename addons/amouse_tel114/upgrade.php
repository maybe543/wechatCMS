<?php
if(!pdo_fieldexists('amouse_tel114_sysset', 'appid_share')) {
    pdo_query("ALTER TABLE ".tablename('amouse_tel114_sysset')." ADD `appid_share` varchar(200)  DEFAULT '';");
}
if(!pdo_fieldexists('amouse_tel114_sysset', 'appsecret_share')) {
    pdo_query("ALTER TABLE ".tablename('amouse_tel114_sysset')." ADD `appsecret_share` varchar(200) NOT NULL DEFAULT '';");
}

if(!pdo_fieldexists('amouse_tel114', 'outlink')) {
    pdo_query("ALTER TABLE  ".tablename('amouse_tel114')." ADD `outlink` varchar(200) NOT NULL DEFAULT '' ;");
}

if(!pdo_fieldexists('amouse_tel114', 'displayorder')) {
    pdo_query("ALTER TABLE  ".tablename('amouse_tel114')." ADD `displayorder`  int(10) unsigned NOT NULL ;");
}
if(!pdo_fieldexists('amouse_tel114', 'status')) {
    pdo_query("ALTER TABLE  ".tablename('amouse_tel114')." ADD `status` int(1) unsigned NOT NULL DEFAULT '0' ;");
}
if(!pdo_fieldexists('amouse_tel114_sysset', 'logo')) {
    pdo_query("ALTER TABLE  ".tablename('amouse_tel114_sysset')." ADD `logo`  varchar(500) NOT NULL ;");
}
$sql =<<<EOF
CREATE TABLE IF NOT EXISTS `ims_amouse_tel114_nav`(
  `id` int(11)  AUTO_INCREMENT,
  `weid` int(11) DEFAULT 0 ,
  `displayorder` int(10) unsigned NOT NULL,
  `followurl` varchar(1000) DEFAULT '' comment '连接',
  `title` varchar(1000) DEFAULT '' comment '导航名称',
  PRIMARY KEY (`id`),KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOF;
pdo_run($sql);