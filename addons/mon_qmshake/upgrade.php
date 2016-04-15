<?php

/**
 * 摇一摇share
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_qmshake_share') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`sid` int(10) NOT NULL,
`uid` int(10) ,
`openid` varchar(300),
`award_count` int(10),
`createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

if (!pdo_fieldexists('mon_qmshake', 'rule')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `rule` varchar(2000) ;");
}


if (!pdo_fieldexists('mon_qmshake_prize', 'p_summary')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake_prize') . " ADD `p_summary` varchar(500) ;");
}



if (!pdo_fieldexists('mon_qmshake', 'prize_limit')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `prize_limit` int(3) default 0 ;");
}




if (!pdo_fieldexists('mon_qmshake_record', 'pname')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake_record') . " ADD `pname` varchar(200) ;");
}




if (!pdo_fieldexists('mon_qmshake_record', 'djtime')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake_record') . " ADD `djtime` int(10) ;");
}


if (!pdo_fieldexists('mon_qmshake', 'dpassword')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `dpassword` varchar(20) ;");
}



if (!pdo_fieldexists('mon_qmshake', 'top_banner_title')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `top_banner_title` varchar(100) ;");
}

if (!pdo_fieldexists('mon_qmshake', 'top_banner_show')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `top_banner_show` int(1) default 0 ;");
}

if (!pdo_fieldexists('mon_qmshake', 'title_bg')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `title_bg` varchar(300);");
}


if (!pdo_fieldexists('mon_qmshake', 'copyright')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `copyright` varchar(50);");
}

if (!pdo_fieldexists('mon_qmshake', 'randking_count')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `randking_count` int(3) ;");
}


if (!pdo_fieldexists('mon_qmshake', 'shake_bg')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `shake_bg` varchar(300) ;");
}

if (!pdo_fieldexists('mon_qmshake', 'index_bg')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `index_bg` varchar(300) ;");
}


/**1.0.6*/
if (!pdo_fieldexists('mon_qmshake', 'share_enable')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `share_enable` int(1) default 0 ;");
}

if (!pdo_fieldexists('mon_qmshake', 'share_times')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `share_times` int(3) default 0 ;");
}

if (!pdo_fieldexists('mon_qmshake', 'share_award_count')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `share_award_count` int(3) default 0 ;");
}

if (!pdo_fieldexists('mon_qmshake', 'share_bg')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `share_bg` varchar(300);");
}


if (!pdo_fieldexists('mon_qmshake', 'share_url')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `share_url` varchar(1000);");
}

if (!pdo_fieldexists('mon_qmshake_prize', 'tgs')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake_prize') . " ADD `tgs` varchar(250);");
}
if (!pdo_fieldexists('mon_qmshake_prize', 'tgs_url')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake_prize') . " ADD `tgs_url` varchar(1000);");
}

if (!pdo_fieldexists('mon_qmshake', 'unstarttip')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD   `unstarttip` text;");
}
if (!pdo_fieldexists('mon_qmshake_prize', 'virtual_count')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake_prize') . " ADD     `virtual_count` int(10) DEFAULT NULL;");
}

if (!pdo_fieldexists('mon_qmshake', 'tmpId')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD     `tmpId` varchar(2000) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_qmshake', 'tmpenable')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD    `tmpenable` int(1) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_qmshake', 'udefine')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD    `udefine` varchar(200) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_qmshake', 'lj_tip')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD    `lj_tip` varchar(2000) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_qmshake_user', 'udefine')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake_user') . " ADD    `udefine` varchar(200) DEFAULT NULL;");
}