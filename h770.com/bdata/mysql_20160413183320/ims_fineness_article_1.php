<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fineness_article`;");
E_C("CREATE TABLE `ims_fineness_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `musicurl` varchar(100) NOT NULL DEFAULT '' COMMENT '上传音乐',
  `content` mediumtext,
  `credit` varchar(255) DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板目录',
  `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板名称',
  `author` varchar(300) NOT NULL DEFAULT '' COMMENT '作者',
  `bg_music_switch` varchar(1) NOT NULL DEFAULT '1',
  `clickNum` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '缩略图',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '简介',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `outLink` varchar(500) DEFAULT '' COMMENT '外链',
  `type` varchar(10) NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `tel` varchar(15) NOT NULL,
  `zanNum` int(10) unsigned NOT NULL DEFAULT '0',
  `iscomment` tinyint(1) DEFAULT '0',
  `isadmire` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否赞赏',
  `admiretxt` varchar(30) NOT NULL DEFAULT '' COMMENT '内容模板目录',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>