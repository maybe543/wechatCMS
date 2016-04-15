<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_newflats`;");
E_C("CREATE TABLE `ims_amouse_newflats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL COMMENT '姓名',
  `thumb` varchar(255) NOT NULL COMMENT '图片',
  `price` varchar(100) NOT NULL COMMENT '价格',
  `type` varchar(200) NOT NULL COMMENT '建筑类型',
  `years` varchar(100) NOT NULL COMMENT '产权年限',
  `wytype` varchar(100) NOT NULL COMMENT '物业类别',
  `cqtype` varchar(100) NOT NULL COMMENT '产权类型',
  `jzarea` varchar(100) NOT NULL COMMENT '建筑面积',
  `ratio` varchar(100) NOT NULL COMMENT '容积率',
  `floor_area` varchar(100) NOT NULL COMMENT '房屋面积',
  `afforestation` varchar(100) NOT NULL COMMENT '绿化率',
  `total` varchar(100) NOT NULL COMMENT '总户型',
  `door_area` varchar(100) NOT NULL COMMENT '户型面积',
  `road_transport` varchar(100) NOT NULL COMMENT '道路交通',
  `investors` varchar(100) NOT NULL COMMENT '投资商',
  `developers` varchar(100) NOT NULL COMMENT '开发商',
  `property_compay` varchar(100) NOT NULL COMMENT '物业公司',
  `propertypay` varchar(100) NOT NULL COMMENT '物业费',
  `features` varchar(100) NOT NULL COMMENT '楼盘特色',
  `sales_addres` varchar(100) NOT NULL COMMENT '售楼地址',
  `checkin_time` varchar(100) NOT NULL COMMENT '入住时间',
  `sales_status` varchar(100) NOT NULL COMMENT '销售状况',
  `average_price` varchar(100) NOT NULL COMMENT '均价',
  `discounted_costs` varchar(100) NOT NULL COMMENT '折扣价格',
  `payment` varchar(100) NOT NULL COMMENT '付款方式',
  `business` varchar(100) NOT NULL COMMENT '商业配套',
  `banks` varchar(100) NOT NULL COMMENT '银行',
  `trading_area` varchar(100) NOT NULL COMMENT '商圈',
  `park` varchar(100) NOT NULL COMMENT '公园',
  `hotel` varchar(100) NOT NULL COMMENT '酒店',
  `supermarket` varchar(100) NOT NULL COMMENT '超市',
  `humanities` varchar(100) NOT NULL COMMENT '人文自然景观',
  `supporting` varchar(100) NOT NULL COMMENT '社区内配套',
  `internal` varchar(100) NOT NULL COMMENT '内部配套',
  `parking_number` varchar(100) NOT NULL COMMENT '车位数',
  `base` varchar(100) NOT NULL COMMENT '基本参数',
  `equally` varchar(100) NOT NULL COMMENT '公摊系数',
  `surrounding` varchar(100) NOT NULL COMMENT '周边商业',
  `landscape` varchar(100) NOT NULL COMMENT '周边景观',
  `hospitals` varchar(100) NOT NULL COMMENT '周边医院',
  `school` varchar(100) NOT NULL COMMENT '周边学校',
  `traffic` varchar(100) NOT NULL COMMENT '交通',
  `construction` varchar(100) NOT NULL COMMENT '建筑施工单位',
  `design` varchar(100) NOT NULL COMMENT '规划设计单位',
  `salecom` varchar(100) NOT NULL COMMENT '销售公司',
  `address` varchar(255) NOT NULL COMMENT '销售公司所在位置图片',
  `introduction` text NOT NULL COMMENT '详细描述',
  `readcount` int(11) DEFAULT '0' COMMENT '阅读量',
  `openid` varchar(25) NOT NULL COMMENT '微信OPENID',
  `like` int(11) DEFAULT '0' COMMENT '点赞',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>