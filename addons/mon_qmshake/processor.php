<?php

defined('IN_IA') or exit('Access Denied');
define("MON_QMSHAKE", "mon_qmshake");
require_once IA_ROOT . "/addons/" . MON_QMSHAKE . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_QMSHAKE . "/monUtil.class.php";
require_once IA_ROOT . "/addons/" . MON_QMSHAKE . "/value.class.php";

class Mon_QmShakeModuleProcessor extends WeModuleProcessor
{
	public function respond()
	{
		$rid = $this->rule;


		$shake = pdo_fetch("select * from " . tablename(DBUtil::$TABLE_QMSHAKE) . " where rid=:rid", array(":rid" => $rid));

		if (!empty($shake)) {
			if (TIMESTAMP < $shake['starttime']) {
				return $this->respText($shake['unstarttip']);
			}
			$news = array();
			$news [] = array('title' => $shake['new_title'], 'description' => $shake['new_content'], 'picurl' => MonUtil::getpicurl($shake ['new_icon']), 'url' => $this->createMobileUrl('Index', array('sid' => $shake['id'])));
			return $this->respNews($news);
		} else {
			return $this->respText("摇一摇活动不存在");
		}

		return null;


	}


}
