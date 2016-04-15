<?php

/*
if (!pdo_fieldexists('mon_baton_user', 'baton_num')) {
    pdo_query("ALTER TABLE " . tablename('mon_baton_user') . " ADD  `baton_num` int(10) default 0 ;");

}*/


/**
 * 摇一摇奖品
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_shake_prize') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`sid` int(10) NOT NULL,
`pname` varchar(50) NOT NULL,
`pimg` varchar(250) NOT NULL,
`p_url` varchar(250) ,
`pb` int(10) defualt 0,
`display_order` int(3) ,
`createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);


