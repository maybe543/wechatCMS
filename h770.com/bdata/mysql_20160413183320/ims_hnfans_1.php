<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hnfans`;");
E_C("CREATE TABLE `ims_hnfans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL COMMENT '用户的唯一身份ID',
  `createtime` int(10) unsigned NOT NULL COMMENT '加入时间',
  `realname` varchar(10) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '头像',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别(0:保密 1:男 2:女)',
  `constellation` varchar(10) NOT NULL DEFAULT '' COMMENT '星座',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '邮寄地址',
  `nationality` varchar(30) NOT NULL DEFAULT '' COMMENT '国籍',
  `resideprovincecity` varchar(30) NOT NULL DEFAULT '' COMMENT '居住省市',
  `residedist` varchar(30) NOT NULL DEFAULT '' COMMENT '居住行政区/县',
  `education` varchar(10) NOT NULL DEFAULT '' COMMENT '学历',
  `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业',
  `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位',
  `revenue` varchar(10) NOT NULL DEFAULT '' COMMENT '年收入',
  `affectivestatus` varchar(30) NOT NULL DEFAULT '' COMMENT '情感状态',
  `lookingfor` varchar(255) NOT NULL DEFAULT '' COMMENT ' 交友目的',
  `height` varchar(5) NOT NULL DEFAULT '' COMMENT '身高',
  `weight` varchar(5) NOT NULL DEFAULT '' COMMENT '体重',
  `interest` text NOT NULL COMMENT '兴趣爱好',
  `lxxingzuo` varchar(200) NOT NULL DEFAULT '' COMMENT '理想星座',
  `housestatus` varchar(20) NOT NULL DEFAULT '' COMMENT '是否有房',
  `carstatus` varchar(20) NOT NULL DEFAULT '' COMMENT '是否有车',
  `lat` varchar(20) NOT NULL DEFAULT '' COMMENT '经度',
  `lng` varchar(20) NOT NULL DEFAULT '' COMMENT '纬度',
  `ueducation` varchar(30) NOT NULL DEFAULT '' COMMENT 'TA的学历',
  `urevenue` varchar(30) NOT NULL DEFAULT '' COMMENT '他的月薪',
  `love` int(10) NOT NULL DEFAULT '0' COMMENT '被喜欢次数',
  `mails` int(10) NOT NULL DEFAULT '0' COMMENT '收信次数',
  `uheightL` int(10) NOT NULL DEFAULT '0' COMMENT 'Ta的最小身高',
  `uheightH` int(10) NOT NULL DEFAULT '0' COMMENT 'Ta的最大身高',
  `uweight` int(10) NOT NULL DEFAULT '0' COMMENT 'Ta的体重',
  `uage` int(10) NOT NULL DEFAULT '0' COMMENT 'Ta的年龄',
  `Descrip` varchar(200) NOT NULL DEFAULT '' COMMENT '一句话描述',
  `uitsCharacter` varchar(300) NOT NULL DEFAULT '' COMMENT 'Ta的性格',
  `uitsOthers` varchar(300) NOT NULL DEFAULT '' COMMENT 'Ta的其他要求',
  `age` int(10) NOT NULL DEFAULT '0' COMMENT '自己的年龄',
  `isshow` int(2) NOT NULL DEFAULT '0' COMMENT '注册审核机制',
  `time` int(12) NOT NULL DEFAULT '0' COMMENT '进入平台获取资料时间',
  `yingcang` int(2) NOT NULL DEFAULT '1' COMMENT '隐藏显示',
  `qq` varchar(20) NOT NULL COMMENT '会员QQ',
  `wechat` varchar(25) NOT NULL COMMENT '会员微信',
  `telephoneconfirm` int(2) NOT NULL DEFAULT '0' COMMENT '是否手机验证',
  `tuijian` int(2) NOT NULL DEFAULT '1' COMMENT '推荐',
  `tjtype` int(2) NOT NULL DEFAULT '0' COMMENT '推荐类型',
  PRIMARY KEY (`id`),
  KEY `weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>