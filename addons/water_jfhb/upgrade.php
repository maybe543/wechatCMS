<?php

if (!pdo_indexexists('jfhb_user', 'idxjfhb_user_ground1')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user')." ADD INDEX `idxjfhb_user_ground1` (`uniacid`, `openid`);");
}


if (!pdo_indexexists('jfhb_user', 'idxjfhb_user_index1')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user')." ADD INDEX `idxjfhb_user_index1` (`uniacid`);");
}

if (!pdo_fieldexists('jfhb_user', 'nickname')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user')." ADD  `nickname` varchar(20);");
}

if (!pdo_fieldexists('jfhb_user', 'sex')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user')." ADD  `sex` int(1);");
}

if (!pdo_fieldexists('jfhb_user', 'city')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user')." ADD  `city` varchar(20);");
}

if (!pdo_fieldexists('jfhb_user', 'province')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user')." ADD  `province` varchar(20);");
}


if (!pdo_fieldexists('jfhb_user', 'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user')." ADD  `headimgurl` varchar(220);");
}

if (!pdo_fieldexists('jfhb_user', 'subscribe')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user')." ADD  `subscribe` int(1);");
}


$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('jfhb_user_log') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `uniacid` int(3) NOT NULL,
 `openid` varchar(100)  NOT NULL COMMENT '微信id',
 `child_openid` varchar(100)  NOT NULL COMMENT '取消关注以后的下线',
 `nickname` varchar(200)  NOT NULL COMMENT '昵称',
 `headimgurl` varchar(100)  NOT NULL COMMENT '头像',
 `money` decimal(10,2) NOT NULL  DEFAULT 0 COMMENT '获得的总金额',
 `type` int(1) NOT NULL default 0 COMMENT  '获得积分类型 0 关注，1,邀请关注，2取消关注，3,提现',
 `createtime` int(10),
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);


$sql = "
CREATE TABLE IF NOT EXISTS ". tablename('jfhb_scene_id') ."(
  `uniacid`  int(10) unsigned NOT NULL,
  `scene_id` int(10)  NOT NULL,
  PRIMARY KEY(`uniacid`)
) ENGINE = MYISAM DEFAULT CHARSET = utf8;";

pdo_query($sql);


if (!pdo_fieldexists('jfhb_user_log', 'child_openid')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user_log')." ADD  `child_openid` varchar(200);");
}



if (!pdo_fieldexists('jfhb_haibao', 'avatarleft')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_haibao')." ADD  `avatarleft` int(3);");
}


if (!pdo_fieldexists('jfhb_haibao', 'avatartop')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_haibao')." ADD  `avatartop` int(3);");
}



if (!pdo_fieldexists('jfhb_haibao', 'avatarwidth')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_haibao')." ADD  `avatarwidth` int(3);");
}


if (!pdo_fieldexists('jfhb_haibao', 'avatarheight')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_haibao')." ADD  `avatarheight` int(3);");
}

if (!pdo_fieldexists('jfhb_haibao', 'avatarenable')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_haibao')." ADD  `avatarenable` int(1);");
}

if (!pdo_fieldexists('jfhb_haibao', 'nameleft')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_haibao')." ADD  `nameleft` int(3);");
}


if (!pdo_fieldexists('jfhb_haibao', 'nametop')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_haibao')." ADD  `nametop` int(3);");
}



if (!pdo_fieldexists('jfhb_haibao', 'namesize')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_haibao')." ADD  `namesize` int(3);");
}

if (!pdo_fieldexists('jfhb_haibao', 'namecolor')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_haibao')." ADD  `namecolor` varchar(20);");
}



if (!pdo_fieldexists('jfhb_haibao', 'nameenable')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_haibao')." ADD  `nameenable` int(1);");
}


if (!pdo_fieldexists('jfhb_qrcode', 'media_id')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_qrcode')." ADD  `media_id` varchar(220);");
}

if (!pdo_fieldexists('jfhb_qrcode', 'media_time')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_qrcode')." ADD  `media_time` int(11);");
}


if (!pdo_fieldexists('jfhb_user', 'jyopenid')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user')." ADD  `jyopenid` varchar(200);");
}


if (!pdo_fieldexists('jfhb_user', 'tx_time')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user')." ADD  `tx_time` int(2) NOT NULL  DEFAULT 0 COMMENT '提现次数';");
}


if (!pdo_fieldexists('jfhb_user', 'user_jl')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user')." ADD  `user_jl` int(1) NOT NULL  DEFAULT 0 COMMENT '老用户奖励标记';");
}


if (!pdo_indexexists('jfhb_qrcode', 'jfhb_qrcode_index1')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_qrcode')." ADD INDEX `jfhb_qrcode_index1` (`uniacid`);");
}


if (!pdo_indexexists('jfhb_qrcode', 'jfhb_qrcode_ground1')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_qrcode')." ADD INDEX `jfhb_qrcode_ground1` (`uniacid`, `openid`);");
}



if (!pdo_indexexists('jfhb_user', 'idxjfhb_user_money1')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_user')." ADD INDEX `idxjfhb_user_money1` (`uniacid`, `tx_money`);");
}


if (!pdo_fieldexists('jfhb_qrcode', 'scene_id')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_qrcode')." ADD  `scene_id` int(10) NOT NULL  DEFAULT 0 COMMENT '二维码场景id';");
}


if (!pdo_indexexists('jfhb_qrcode', 'idxjfhb_scene_id')) {
	pdo_query("ALTER TABLE ".tablename('jfhb_qrcode')."  ADD INDEX `idxjfhb_scene_id` (`uniacid`, `scene_id`);");
}







