<?php

defined('IN_IA') or exit('Access Denied');
define("MON_TIMELINE", "mon_timeline");
require_once IA_ROOT . "/addons/" . MON_TIMELINE . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_TIMELINE . "/monUtil.class.php";


class Mon_TimelineModuleProcessor extends WeModuleProcessor
{
	public function respond()
	{
		$rid = $this->rule;


		$timeline = pdo_fetch("select * from " . tablename(DBUtil::$TABLE_TIMELINE) . " where rid=:rid", array(":rid" => $rid));

		if (!empty($timeline)) {

			$news = array();
			$news [] = array('title' => $timeline['new_title'], 'description' => $timeline['new_content'], 'picurl' => MonUtil::getpicurl($timeline ['new_icon']), 'url' => $this->createMobileUrl('Index', array('tid' => $timeline['id'])));
			return $this->respNews($news);
		} else {
			return $this->respText("时间轴不存在");
		}

		return null;


	}


}
