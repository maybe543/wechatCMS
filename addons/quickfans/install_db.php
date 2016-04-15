<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_quickfans_user` (  `from_user` varchar(50) NOT NULL,  PRIMARY KEY (`from_user`)  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
if(!pdo_fieldexists('fans', 'center')){
    pdo_query("alter table " . tablename('fans') . " add column `center` bool default false");
}
if(!pdo_fieldexists('fans', 'register')){
    pdo_query("alter table " . tablename('fans') . " add column `register` bool default false");
}
if(!pdo_fieldexists('fans', 'passwd')){
    pdo_query("alter table " . tablename('fans') . "  add column `passwd` varchar(1024) default ''");
}
