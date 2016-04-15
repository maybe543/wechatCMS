<?php
 if(!pdo_fieldexists('quickmoney_goods', 'timestart')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_goods') . " ADD `timestart` int(11) NOT NULL DEFAULT 0 COMMENT  '排序'");
    pdo_query("UPDATE " . tablename('quickmoney_goods') . " SET timestart=0");
}
if(!pdo_fieldexists('quickmoney_goods', 'timeend')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_goods') . " ADD `timeend` int(11) NOT NULL DEFAULT 0 COMMENT  '排序'");
    pdo_query("UPDATE " . tablename('quickmoney_goods') . " SET timeend=UNIX_TIMESTAMP(deadline)");
}
if(!pdo_fieldexists('quickmoney_goods', 'displayorder')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_goods') . " ADD `displayorder` int(11) NOT NULL DEFAULT 0 COMMENT  '排序'");
}
if(!pdo_fieldexists('quickmoney_goods', 'type')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_goods') . " ADD `type` int(10) NOT NULL DEFAULT '1' AFTER `content`;");
}
if(!pdo_fieldexists('quickmoney_request', 'exchangetype')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_request') . " ADD `exchangetype` int(10) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('quickmoney_request', 'cost')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_request') . " ADD `cost` Decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickmoney_goods', 'vip_require')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_goods') . " ADD `vip_require` int(10) NOT NULL DEFAULT '0' COMMENT '兑换最低VIP级别';");
}
if(!pdo_fieldexists('quickmoney_goods', 'userchangecost')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_goods') . " ADD `userchangecost` int(10) NOT NULL DEFAULT 0 COMMENT '用户是否可以修改兑换值'");
}
