<?php
$sql = "
  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_category` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
    `name` varchar(50) NOT NULL COMMENT '分类名称',
    `thumb` varchar(255) NOT NULL COMMENT '分类图片',
    `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
    `isrecommand` int(10) DEFAULT '0',
    `description` varchar(500) NOT NULL COMMENT '分类介绍',
    `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
    `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
    `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 COMMENT='超市商品分类';
  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_express` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `weid` int(11) DEFAULT '0',
    `express_name` varchar(50) DEFAULT '',
    `displayorder` int(11) DEFAULT '0',
    `express_price` varchar(10) DEFAULT '',
    `express_area` varchar(100) DEFAULT '',
    `express_url` varchar(255) DEFAULT '',
    `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
    PRIMARY KEY (`id`),
    KEY `indx_weid` (`weid`),
    KEY `indx_displayorder` (`displayorder`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_dispatch` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `weid` int(11) DEFAULT '0',
    `dispatchname` varchar(50) DEFAULT '',
    `dispatchtype` int(11) DEFAULT '0',
    `displayorder` int(11) DEFAULT '0',
    `firstprice` decimal(10,2) DEFAULT '0.00',
    `secondprice` decimal(10,2) DEFAULT '0.00',
    `firstweight` int(11) DEFAULT '0',
    `secondweight` int(11) DEFAULT '0',
    `express` int(11) DEFAULT '0',
    `description` text,
    `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
    PRIMARY KEY (`id`),
    KEY `indx_weid` (`weid`),
    KEY `indx_displayorder` (`displayorder`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_slide` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `weid` int(11) DEFAULT '0',
    `advname` varchar(50) DEFAULT '',
    `link` varchar(255) NOT NULL DEFAULT '',
    `thumb` varchar(255) DEFAULT '',
    `displayorder` int(11) DEFAULT '0',
    `enabled` int(11) DEFAULT '0',
    `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
    PRIMARY KEY (`id`),
    KEY `indx_weid` (`weid`),
    KEY `indx_enabled` (`enabled`),
    KEY `indx_displayorder` (`displayorder`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_goods` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `weid` int(10) unsigned NOT NULL,
    `pcate` int(10) unsigned NOT NULL DEFAULT '0',
    `ccate` int(10) unsigned NOT NULL DEFAULT '0',
    `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟',
    `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
    `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
    `title` varchar(100) NOT NULL DEFAULT '',
    `thumb` varchar(255) DEFAULT '',
    `unit` varchar(5) NOT NULL DEFAULT '',
    `description` varchar(1000) NOT NULL DEFAULT '',
    `content` text NOT NULL,
    `goodssn` varchar(50) NOT NULL DEFAULT '',
    `productsn` varchar(50) NOT NULL DEFAULT '',
    `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00',
    `productprice` decimal(10,2) NOT NULL DEFAULT '0.00',
    `costprice` decimal(10,2) NOT NULL DEFAULT '0.00',
    `originalprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
    `total` int(10) NOT NULL DEFAULT '0',
    `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
    `sales` int(10) unsigned NOT NULL DEFAULT '0',
    `spec` varchar(5000) NOT NULL,
    `createtime` int(10) unsigned NOT NULL,
    `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
    `credit` int(11) DEFAULT '0',
    `maxbuy` int(11) DEFAULT '0',
    `hasoption` int(11) DEFAULT '0',
    `dispatch` int(11) DEFAULT '0',
    `thumb_url` text,
    `isnew` int(11) DEFAULT '0',
    `ishot` int(11) DEFAULT '0',
    `isdiscount` int(11) DEFAULT '0',
    `isrecommand` int(11) DEFAULT '0',
    `istime` int(11) DEFAULT '0',
    `timestart` int(11) DEFAULT '0',
    `timeend` int(11) DEFAULT '0',
    `viewcount` int(11) DEFAULT '0',
    `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
    `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_goods_option` (
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
    `specs` text,
    PRIMARY KEY (`id`),
    KEY `indx_goodsid` (`goodsid`),
    KEY `indx_displayorder` (`displayorder`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_goods_param` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `goodsid` int(10) DEFAULT '0',
    `title` varchar(50) DEFAULT '',
    `value` text,
    `displayorder` int(11) DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `indx_goodsid` (`goodsid`),
    KEY `indx_displayorder` (`displayorder`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_product` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `goodsid` int(11) NOT NULL,
    `productsn` varchar(50) NOT NULL,
    `title` varchar(1000) NOT NULL,
    `marketprice` decimal(10,0) unsigned NOT NULL,
    `productprice` decimal(10,0) unsigned NOT NULL,
    `total` int(11) NOT NULL,
    `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
    `spec` varchar(5000) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_goodsid` (`goodsid`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_spec` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `weid` int(10) unsigned NOT NULL,
    `title` varchar(50) NOT NULL,
    `description` varchar(1000) NOT NULL,
    `displaytype` tinyint(3) unsigned NOT NULL,
    `content` text NOT NULL,
    `goodsid` int(11) DEFAULT '0',
    `displayorder` int(11) DEFAULT '0',
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_spec_item` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `weid` int(11) DEFAULT '0',
    `specid` int(11) DEFAULT '0',
    `title` varchar(255) DEFAULT '',
    `thumb` varchar(255) DEFAULT '',
    `show` int(11) DEFAULT '0',
    `displayorder` int(11) DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `indx_weid` (`weid`),
    KEY `indx_specid` (`specid`),
    KEY `indx_show` (`show`),
    KEY `indx_displayorder` (`displayorder`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_address` (
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
    `regionid` int(11) unsigned NOT NULL COMMENT '当前小区ID',
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_cart` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `weid` int(10) unsigned NOT NULL,
    `goodsid` int(11) NOT NULL,
    `goodstype` tinyint(1) NOT NULL DEFAULT '1',
    `from_user` varchar(50) NOT NULL,
    `total` int(10) unsigned NOT NULL,
    `optionid` int(10) DEFAULT '0',
    `marketprice` decimal(10,2) DEFAULT '0.00',
    PRIMARY KEY (`id`),
    KEY `idx_openid` (`from_user`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
  CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_order` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `weid` int(10) unsigned NOT NULL,
    `from_user` varchar(50) NOT NULL,
    `ordersn` varchar(20) NOT NULL,
    `price` varchar(10) NOT NULL,
    `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
    `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
    `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
    `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
    `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',
    `remark` varchar(1000) NOT NULL DEFAULT '',
    `addressid` int(10) unsigned NOT NULL,
    `expresscom` varchar(30) NOT NULL DEFAULT '',
    `expresssn` varchar(50) NOT NULL DEFAULT '',
    `express` varchar(200) NOT NULL DEFAULT '',
    `goodsprice` decimal(10,2) DEFAULT '0.00',
    `dispatchprice` decimal(10,2) DEFAULT '0.00',
    `dispatch` int(10) DEFAULT '0',
    `createtime` int(10) unsigned NOT NULL,
    `regionid` int(11) unsigned NOT NULL COMMENT '当前小区ID',
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;
    CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_order_goods` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `weid` int(10) unsigned NOT NULL,
      `orderid` int(10) unsigned NOT NULL,
      `goodsid` int(10) unsigned NOT NULL,
      `price` decimal(10,2) DEFAULT '0.00',
      `total` int(10) unsigned NOT NULL DEFAULT '1',
      `optionid` int(10) DEFAULT '0',
      `createtime` int(10) unsigned NOT NULL,
      `optionname` text,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;
    CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝',
  `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号',
  `transid` varchar(30) NOT NULL COMMENT '订单号',
  `reason` varchar(1000) NOT NULL COMMENT '理由',
  `solution` varchar(1000) NOT NULL COMMENT '期待解决方案',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_feedbackid` (`feedbackid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_transid` (`transid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_notice_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `template_id_1` varchar(100) NOT NULL,
  `template_id_2` varchar(100) NOT NULL,
  `template_id_3` varchar(100) NOT NULL,
  `template_id_4` varchar(100) NOT NULL,
  `template_id_5` varchar(100) NOT NULL,
  `template_id_6` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='公告模板消息ID设置' AUTO_INCREMENT=3 ;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='模板设置' AUTO_INCREMENT=3 ;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_wechat_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `fansopenid` varchar(30) NOT NULL,
  `repair_status` int(1) NOT NULL,
  `report_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='微信设置' AUTO_INCREMENT=3 ;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `displayorder` int(10) NOT NULL,
  `pcate` int(10) NOT NULL,
  `title` varchar(30) NOT NULL COMMENT '菜单标题',
  `url` varchar(1000) NOT NULL COMMENT '菜单链接',
  `styleid` int(10) NOT NULL COMMENT '风格id',
  `status` int(1) NOT NULL COMMENT '状态',
  `icon` varchar(50) NOT NULL COMMENT '系统图标',
  `bgcolor` varchar(20) NOT NULL COMMENT '背景颜色',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
pdo_query($sql);

if(!pdo_fieldexists('xcommunity_carpool', 'status')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `status` int( 1 ) NOT NULL ;");
}

if(!pdo_fieldexists('xcommunity_announcement', 'enable')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD   `enable` tinyint(1) NOT NULL COMMENT '模板类型' ;");
}
if(!pdo_fieldexists('xcommunity_announcement', 'datetime')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD     `datetime` varchar(100)  NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_announcement', 'location')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD     `location` varchar(100) NOT NULL COMMENT '通知范围';");
}
if(!pdo_fieldexists('xcommunity_announcement', 'location')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD     `reason` varchar(100) NOT NULL COMMENT '通知范围';");
}
if(!pdo_fieldexists('xcommunity_announcement', 'location')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD     `remark` varchar(100) NOT NULL COMMENT '通知备注';");
}

if(pdo_fieldexists('xcommunity_sjdp', 'regionid')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." change   `regionid`  `regionid`   varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp', 'businessurl')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD   `businessurl` varchar(100)  NOT NULL;");
}

if(pdo_fieldexists('xcommunity_propertyfree', 'homenumber')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')."  change   `homenumber`  `homenumber` varchar(15)  NOT NULL;");
}
if(pdo_fieldexists('xcommunity_propertyfree', 'profree')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')."  change   `profree` `profree` varchar(10)  NOT NULL;");
}
if(pdo_fieldexists('xcommunity_propertyfree', 'tcf')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')."  change    `tcf`  `tcf` varchar(10)  NOT NULL;");
}
if(pdo_fieldexists('xcommunity_propertyfree', 'gtsf')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')."  change   `gtsf`  `gtsf` varchar(10)  NOT NULL;");
}
if(pdo_fieldexists('xcommunity_propertyfree', 'gtdf')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')."  change    `gtdf`  `gtdf` varchar(10)  NOT NULL;");
}

if(!pdo_fieldexists('xcommunity_propertyfree', 'status')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')."  add     `status` int(1) unsigned NOT NULL COMMENT '1代表缴费，0代表未缴费';");
}
if(!pdo_fieldexists('xcommunity_propertyfree', 'paytype')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')."  add     `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付';");
}
if(!pdo_fieldexists('xcommunity_propertyfree', 'transid')) {
  pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')."  add     `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号';");
}
