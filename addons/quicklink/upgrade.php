<?php
 if (true){
    $create_table_sql = "CREATE TABLE IF NOT EXISTS " . tablename('quickspread_blacklist') . " (
    `from_user` varchar(50) not null default '',
    `weid`  int(10) unsigned NOT NULL ,
    `access_time`  int(10) unsigned NOT NULL ,
    `hit` int(10) NOT NULL DEFAULT 0,
    PRIMARY KEY(from_user, weid)
  ) ENGINE = MYISAM DEFAULT CHARSET = utf8;";
    pdo_query($create_table_sql);
}
if (true){
    $create_table_sql = "
    CREATE TABLE IF NOT EXISTS  " . tablename('quickspread_reply') . "(
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `rid` int(10) unsigned NOT NULL,
      `channel` int(10) unsigned NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8   AUTO_INCREMENT=1;";
    pdo_query($create_table_sql);
}
if (true){
    $create_table_sql = "
    CREATE TABLE IF NOT EXISTS  " . tablename('quickspread_cache') . "(
      `weid`  int(10) unsigned NOT NULL ,
      `content`  varchar(10240) NOT NULL ,
      PRIMARY KEY (`weid`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
    pdo_query($create_table_sql);
}
if(!pdo_fieldexists('quickspread_channel', 'createtime')){
    pdo_query("ALTER TABLE " . tablename('quickspread_channel') . " ADD `createtime` int(10) unsigned NOT NULL DEFAULT 0;");
}
if(!pdo_fieldexists('quickspread_channel', 'bgparam')){
    pdo_query("ALTER TABLE " . tablename('quickspread_channel') . " ADD `bgparam` varchar(10240) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('quickspread_channel', 'deleted')){
    pdo_query("ALTER TABLE " . tablename('quickspread_channel') . " ADD `deleted` tinyint(3) NOT NULL DEFAULT '0';");
}
if (true){
    $create_table_sql = "CREATE TABLE IF NOT EXISTS " . tablename('quickspread_top_cache') . " (
    `weid`  int(10) unsigned NOT NULL ,
    `createtime`  int(10) unsigned NOT NULL ,
    `cache` text NOT NULL DEFAULT '',
    PRIMARY KEY(weid)
  ) ENGINE = MYISAM DEFAULT CHARSET = utf8;";
    pdo_query($create_table_sql);
}
