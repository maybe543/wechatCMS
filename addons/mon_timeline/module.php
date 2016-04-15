<?php
/**
 *
 *
 * @author  codeMonkey
 * qq:2463619823
 * @url
 */
defined('IN_IA') or exit('Access Denied');

define("MON_TIMELINE", "mon_timeline");
define("MON_TIMELINE_RES", "../addons/" . MON_TIMELINE . "/");
require_once IA_ROOT . "/addons/" . MON_TIMELINE . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_TIMELINE . "/monUtil.class.php";

class Mon_TimelineModule extends WeModule
{

	public $weid;

	public function __construct()
	{
		global $_W;
		$this->weid = IMS_VERSION < 0.6 ? $_W['weid'] : $_W['uniacid'];
	}

	public function fieldsFormDisplay($rid = 0)
	{
		global $_W;

		if (!empty($rid)) {
			$reply = DBUtil::findUnique(DBUtil::$TABLE_TIMELINE, array(":rid" => $rid));

		}
		load()->func('tpl');
		include $this->template('form');


	}

	public function fieldsFormValidate($rid = 0)
	{
		global $_GPC, $_W;


		return '';
	}

	public function fieldsFormSubmit($rid)
	{
		global $_GPC;
		$tid = $_GPC['tid'];
		$data = array(
			'rid' => $rid,
			'weid' => $this->weid,
			'title' => $_GPC['title'],
			'list_bg' => $_GPC['list_bg'],
			'copyright' => $_GPC['copyright'],
			'new_title' => $_GPC['new_title'],
			'new_icon' => $_GPC['new_icon'],
			'new_content' => $_GPC['new_content'],
			'share_title' => $_GPC['share_title'],
			'share_icon' => $_GPC['share_icon'],
			'share_content' => $_GPC['share_content'],
			'updatetime' => TIMESTAMP
		);

		if (empty($tid)) {
			$data['createtime'] = TIMESTAMP;
			DBUtil::create(DBUtil::$TABLE_TIMELINE, $data);
		} else {
			DBUtil::updateById(DBUtil::$TABLE_TIMELINE, $data, $tid);
		}

		return true;
	}

	public function ruleDeleted($rid)
	{
		$timeline = DBUtil::findUnique(DBUtil::$TABLE_TIMELINE, array(":rid" => $rid));
		pdo_delete(DBUtil::$TABLE_TIMELINE_ITEM, array("tid" => $timeline['id']));
		pdo_delete(DBUtil::$TABLE_TIMELINE, array('id' => $timeline['id']));
	}



}