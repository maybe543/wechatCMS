<?php
 $sql = "
CREATE TABLE IF NOT EXISTS `ims_quickshop_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `area` varchar(30) NOT NULL,
  `address` varchar(300) NOT NULL,
  `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE if not exists  `ims_quickshop_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) default 0,
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` int(11) DEFAULT '0',
  `description` text,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE if not exists  `ims_quickshop_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE if not exists `ims_quickshop_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE if not exists `ims_quickshop_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),KEY `indx_goodsid` (`goodsid`),KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE if not exists `ims_quickshop_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) default 0,
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),KEY `indx_weid` (`weid`),KEY `indx_enabled` (`enabled`),KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_quickshop_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) default 0,
  `displayorder` int(11) default 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE if not exists `ims_quickshop_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) default 0,
  `specid` int(11) default 0,
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) default 0,
  `displayorder` int(11) default 0,
  PRIMARY KEY (`id`),KEY `indx_weid` (`weid`),KEY `indx_specid` (`specid`),KEY `indx_show` (`show`),KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

";
pdo_run($sql);
if(pdo_fieldexists('quickshop_goods', 'marketprice')){
    pdo_query("ALTER TABLE  " . tablename('quickshop_goods') . " CHANGE `marketprice` `marketprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(pdo_fieldexists('quickshop_goods', 'productprice')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " CHANGE `productprice` `productprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'sendtype')){
    pdo_query("ALTER TABLE  " . tablename('quickshop_goods') . " ADD `sendtype`  tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1为快递，2为自提';");
}
if(!pdo_fieldexists('quickshop_goods', 'costprice')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `costprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'weight')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `weight` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'totalcnf')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `totalcnf` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'credit')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `credit` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'credit2')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `credit2` decimal(10,2)  NOT NULL DEFAULT '0' COMMENT '购物返现金';");
}
if(!pdo_fieldexists('quickshop_goods', 'hasoption')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `hasoption` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'maxbuy')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `maxbuy` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods_option', 'productprice')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods_option') . " ADD `productprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'thumb_url')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `thumb_url` text;");
}
if(!pdo_fieldexists('quickshop_goods', 'dispatch')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `dispatch` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'isrecommend')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `isrecommend` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'isnew')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `isnew` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'ishot')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `ishot` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'istime')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `istime` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'timestart')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `timestart` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'timeend')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `timeend` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_category', 'thumb')){
    pdo_query("ALTER TABLE " . tablename('quickshop_category') . " ADD `thumb` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('quickshop_category', 'isrecommend')){
    pdo_query("ALTER TABLE " . tablename('quickshop_category') . " ADD `isrecommend` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_category', 'enabled')){
    pdo_query("ALTER TABLE " . tablename('quickshop_category') . " ADD `enabled` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_cart', 'optionid')){
    pdo_query("ALTER TABLE " . tablename('quickshop_cart') . " ADD `optionid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_cart', 'marketprice')){
    pdo_query("ALTER TABLE " . tablename('quickshop_cart') . " ADD `marketprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_order', 'dispatchprice')){
    pdo_query("ALTER TABLE " . tablename('quickshop_order') . " ADD `dispatchprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_order', 'goodsprice')){
    pdo_query("ALTER TABLE " . tablename('quickshop_order') . " ADD `goodsprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_order', 'dispatch')){
    pdo_query("ALTER TABLE " . tablename('quickshop_order') . " ADD `dispatch` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_order', 'express')){
    pdo_query("ALTER TABLE " . tablename('quickshop_order') . " ADD `express` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('quickshop_order_goods', 'optionid')){
    pdo_query("ALTER TABLE " . tablename('quickshop_order_goods') . " ADD `optionid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'isdiscount')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `isdiscount` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'viewcount')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `viewcount` int(11) NOT NULL DEFAULT '0';");
}
if(pdo_fieldexists('quickshop_adv', 'link')){
    pdo_query("ALTER TABLE " . tablename('quickshop_adv') . " CHANGE `link` `link` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('quickshop_dispatch', 'dispatchtype')){
    pdo_query("ALTER TABLE " . tablename('quickshop_dispatch') . " ADD `dispatchtype` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_category', 'description')){
    pdo_query("ALTER TABLE " . tablename('quickshop_category') . " ADD `description` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('quickshop_goods', 'deleted')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `deleted` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_address', 'deleted')){
    pdo_query("ALTER TABLE " . tablename('quickshop_address') . " ADD `deleted` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_order_goods', 'price')){
    pdo_query("ALTER TABLE " . tablename('quickshop_order_goods') . " ADD `price` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_spec', 'goodsid')){
    pdo_query("ALTER TABLE " . tablename('quickshop_spec') . " ADD `goodsid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_spec', 'displayorder')){
    pdo_query("ALTER TABLE " . tablename('quickshop_spec') . " ADD `displayorder` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_order_goods', 'optionname')){
    pdo_query("ALTER TABLE " . tablename('quickshop_order_goods') . " ADD `optionname` text;");
}
if(!pdo_fieldexists('quickshop_goods_option', 'specs')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods_option') . " ADD `specs` text;");
}
global $_W;
$goods = pdo_fetchall("select id from " . tablename('quickshop_goods') . " where weid=:weid", array(":weid" => $_W['weid']));
$optionids = array();
foreach($goods as $o){
    $goods_options = pdo_fetchall("select * from " . tablename('quickshop_goods_option') . " where goodsid=:goodsid and specs='' order by id asc", array(":goodsid" => $o['id']));
    if(count($goods_options) <= 0){
        continue;
    }
    $spec = array("weid" => $_W['weid'], "title" => "规格", "goodsid" => $o['id'], "description" => "", "displaytype" => 0, "content" => serialize(array()), "displayorder" => 0);
    pdo_insert("quickshop_spec", $spec);
    $specid = pdo_insertid();
    $n = 0;
    $spec_item_ids = array();
    foreach ($goods_options as $go){
        $spec_item = array("weid" => $_W['weid'], "specid" => $specid, "title" => $go['title'], "show" => 1, "displayorder" => $n, "thumb" => $go['thumb']);
        pdo_insert("quickshop_spec_item", $spec_item);
        $spec_item_id = pdo_insertid();
        pdo_update("quickshop_goods_option", array("specs" => $spec_item_id), array("id" => $go['id']));
    }
}
$goods = pdo_fetchall("select id,costprice,productprice from " . tablename('quickshop_goods') . " where weid=:weid", array(":weid" => $_W['weid']));
foreach($goods as $o){
    $costprice = $o['costprice'];
    $productprice = $o['productprice'];
    pdo_update("quickshop_goods_option", array("costprice" => $productprice, "productprice" => $costprice), array("id" => $o['id']));
}
$options = pdo_fetchall("select id, specs from " . tablename('quickshop_goods_option') . " where specs<>''");
foreach($options as $o){
    $specs = explode("_", $o['specs']);
    $titles = array();
    foreach($specs as $sp){
        $item = pdo_fetch("select title from " . tablename('quickshop_spec_item') . " where id=:id limit 1", array(":id" => $sp));
        if($item){
            $titles[] = $item['title'];
        }
    }
    $titles = implode("+", $titles);
    pdo_update("quickshop_goods_option", array("title" => $titles), array("id" => $o['id']));
    pdo_update("quickshop_order_goods", array("optionname" => $titles), array("optionid" => $o['id']));
}
if(pdo_fieldexists('quickshop_order', 'price')){
    pdo_query("ALTER TABLE  " . tablename('quickshop_order') . " CHANGE `price` `price` Decimal(10,2) NOT NULL DEFAULT 0;");
}
if(pdo_fieldexists('quickshop_goods', 'thumb')){
    pdo_query("ALTER TABLE  " . tablename('quickshop_goods') . " CHANGE `thumb` `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('quickshop_goods', 'killenable')){
    pdo_query("ALTER TABLE  " . tablename('quickshop_goods') . " ADD `killenable` tinyint(2) default 1 AFTER `timelinethumb`;");
}
if(!pdo_fieldexists('quickshop_order', 'updatetime')){
    pdo_query("ALTER TABLE " . tablename('quickshop_order') . " ADD `updatetime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'credittype')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `credittype` tinyint(2) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('quickshop_goods', 'isminimode')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `isminimode` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickshop_goods', 'max_coupon_credit')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `max_coupon_credit` Decimal(10,2) NOT NULL DEFAULT 0;");
}
if(!pdo_fieldexists('quickshop_goods', 'support_delivery')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `support_delivery` tinyint(1) NOT NULL DEFAULT 0;");
}
if(!pdo_fieldexists('quickshop_goods', 'pgoodsid')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `pgoodsid` int(10) NOT NULL DEFAULT 0;");
}
if(!pdo_fieldexists('quickshop_goods', 'cover_content')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `cover_content` text");
}
if(!pdo_fieldexists('quickshop_order', 'usecredit')){
    pdo_query("ALTER TABLE " . tablename('quickshop_order') . " ADD `usecredit` tinyint(1) unsigned NOT NULL DEFAULT 0;");
}
if(!pdo_fieldexists('quickshop_order', 'creditused')){
    pdo_query("ALTER TABLE " . tablename('quickshop_order') . " ADD `creditused` int(10) unsigned NOT NULL DEFAULT 0;");
}
if(!pdo_fieldexists('quickshop_goods', 'min_buy_level')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `min_buy_level` int(10) default 0 COMMENT '最低购买级别，低于这个级别的用户无法购买商品';");
}
if(!pdo_fieldexists('quickshop_goods', 'min_visible_level')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `min_visible_level` int(10) default 0 COMMENT '最低显示级别，低于这个级别的用户看不到该商品';");
}
if(!pdo_fieldexists('quickshop_goods', 'dealeropenid')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `dealeropenid` varchar(50) default '' COMMENT '店主OpenID接受通知';");
}
if(!pdo_fieldexists('quickshop_dispatch', 'province')){
    pdo_query("ALTER TABLE " . tablename('quickshop_dispatch') . " ADD `province` varchar(100) default '' COMMENT '选择省份, 可为空';");
}
if(!pdo_fieldexists('quickshop_dispatch', 'city')){
    pdo_query("ALTER TABLE " . tablename('quickshop_dispatch') . " ADD `city` varchar(100) default '' COMMENT '选择市, 可为空';");
}
if(!pdo_fieldexists('quickshop_dispatch', 'area')){
    pdo_query("ALTER TABLE " . tablename('quickshop_dispatch') . " ADD `area` varchar(100) default '' COMMENT '选择区, 可为空';");
}
if(pdo_fieldexists('quickshop_order_goods', 'price') and !pdo_fieldexists('quickshop_order_goods', 'ordergoodsprice')){
    pdo_query("ALTER TABLE  " . tablename('quickshop_order_goods') . " CHANGE `price` `ordergoodsprice` decimal(10,2) NOT NULL DEFAULT '0' COMMENT '订单商品id';");
    pdo_query("ALTER TABLE  " . tablename('quickshop_order_goods') . " ADD INDEX (  `weid` ,  `goodsid` );");
}
if(!pdo_fieldexists('quickshop_goods', 'secret_content')){
    pdo_query("ALTER TABLE " . tablename('quickshop_goods') . " ADD `secret_content` text COMMENT '仅仅购买过的用户才能看到的内容';");
}
