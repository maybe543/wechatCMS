<?php
/**
 * bf 库
 *
 */
defined("IN_IA") or exit("Access Denied");

include_once dirname(__FILE__) . "/db.class.php";

//语言
$i18n = parse_ini_file(dirname(__FILE__) . "/lang_zh_CN.ini");

$BUY_TYPE = array(
	$i18n["buy_type_0"],
	$i18n["buy_type_1"],
	$i18n["buy_type_2"],
);

//砍价权限
$RULES = array(
	"kanjia" => array("add", "del", "update", "select"),
	"record" => array("add", "del", "update", "select"),
	"help" => array("add", "del", "update", "select"),
	"order" => array("add", "del", "update", "select"),
);

$MODILE_NAME = "bf_kanjia";

//当前登录用户的权限
/**
 * 判断操作权限的方法
 * @param name 功能模块
 * @param curd 功能类型
 */
function checkCompetence($name, $curd) {
	global $_W, $_GPC;

	$shop = DBUtil::getKanjiaShop(" `uniacid`=:uniacid AND `uid`=:uid", array(":uniacid" => $_W["uniacid"], ":uid" => $_W["uid"]));
	if (!empty($shop)) {
		$shop["rule"] = unserialize($shop["rule"]);
	} else {
		$shop["rule"] = array();
	}
	if (empty($shop["rule"][$name][$curd])) {
		//无权操作
		message("亲，您没有对应的管理权限哦！", "", "error");
	}
}
?>