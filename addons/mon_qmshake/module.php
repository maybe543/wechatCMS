<?php
/**
 *
 *
 * @author  codeMonkey
 * qq:631872807
 * @url
 */
defined('IN_IA') or exit('Access Denied');

define("MON_QMSHAKE", "mon_qmshake");
define("MON_QMSHAKE_RES", "../addons/" . MON_QMSHAKE . "/");
require_once IA_ROOT . "/addons/" . MON_QMSHAKE . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_QMSHAKE . "/monUtil.class.php";

class Mon_QMShakeModule extends WeModule
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
			$reply = DBUtil::findUnique(DBUtil::$TABLE_QMSHAKE, array(":rid" => $rid));
			$hd_time =  array(
				'start' => date("Y-m-d H:i", $reply['starttime']),
				'end'   => date("Y-m-d H:i", $reply['endtime']),
			);
			$reply['starttime'] = date("Y-m-d  H:i", $reply['starttime']);
			$reply['endtime'] = date("Y-m-d  H:i", $reply['endtime']);



		}


		$prizes=pdo_fetchall("select p.*,  (select count(*) from ".tablename(DBUtil::$TABLE_QMSHAKE_RECORD)." r where r.pid = p.id ) as ycount from ".tablename(DBUtil::$TABLE_QMSHAKE_PRIZE)." p
		where sid=:sid order by display_order asc,createtime asc ",array(":sid"=>$reply['id']));

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
		$sid = $_GPC['sid'];

		$hd_time = $_GPC['hd_time'];// 结构为: array('start'=>?, 'end'=>?)

		$starttime = empty($hd_time['start']) ? strtotime('-1 month') : strtotime($hd_time['start']);
		$endtime   = empty($hd_time['end'])   ? TIMESTAMP : strtotime($hd_time['end']);

		//'starttime' => strtotime($_GPC['starttime']),
		//	'endtime' => strtotime($_GPC['endtime']),
		$data = array(
			'rid' => $rid,
			'weid' => $this->weid,
			'title' => $_GPC['title'],
			'starttime' => $starttime,
			'endtime' => $endtime,
			'follow_url' => $_GPC['follow_url'],
			'follow_btn_name' =>$_GPC['follow_btn_name'],
			'title_bg' => $_GPC['title_bg'],
			'shake_bg' => $_GPC['shake_bg'],
			'index_bg' => $_GPC['index_bg'],
			'share_bg' => $_GPC['share_bg'],
			'copyright' => $_GPC['copyright'],
			'randking_count' =>$_GPC['randking_count'],
			'follow_dlg_tip' =>$_GPC['follow_dlg_tip'],
			'new_title' => $_GPC['new_title'],
			'new_icon' => $_GPC['new_icon'],
			'new_content' => $_GPC['new_content'],
			'share_title' => $_GPC['share_title'],
			'share_icon' => $_GPC['share_icon'],
			'share_content' => $_GPC['share_content'],
			'rule' => htmlspecialchars_decode($_GPC['rule']),
			'top_banner' => $_GPC['top_banner'],
			'top_banner_url' => $_GPC['top_banner_url'],
			'top_banner_title' => $_GPC['top_banner_title'],
			'top_banner_show' =>$_GPC['top_banner_show'],
			'shake_day_limit' => $_GPC['shake_day_limit'],
			'total_limit' => $_GPC['total_limit'],
			'prize_limit' => $_GPC['prize_limit'],
			'dpassword' =>$_GPC['dpassword'],
			'share_enable' =>$_GPC['share_enable'],
			'share_times' => $_GPC['share_times'],
			'share_url' =>$_GPC['share_url'],
			'share_award_count' =>$_GPC['share_award_count'],
			'updatetime' => TIMESTAMP,
			'unstarttip' => $_GPC['unstarttip'],
			'tmpId' => $_GPC['tmpId'],
			'tmpenable' => $_GPC['tmpenable'],
			'udefine' => $_GPC['udefine'],
			'lj_tip' => $_GPC['lj_tip'],
			'jfye_auto_dh' => $_GPC['jfye_auto_dh']
		);

		if (empty($sid)) {
			$data['createtime'] = TIMESTAMP;
			DBUtil::create(DBUtil::$TABLE_QMSHAKE, $data);
			$sid = pdo_insertid();
		} else {
			DBUtil::updateById(DBUtil::$TABLE_QMSHAKE, $data, $sid);
		}

		$prizids = array();
		$pids = $_GPC['pids'];
		$display_orders = $_GPC['display_orders'];
		$pnames = $_GPC['pnames'];
		$p_summarys = $_GPC['p_summarys'];
		$p_tggs = $_GPC['tgss'];
		$p_types = $_GPC['ptypes'];
		$pjfyes = $_GPC['jfyes'];
		$p_tgs_urls = $_GPC['tgs_urls'];
		$pimgs = $_GPC['pimgs'];
		$prices = $_GPC['prices'];
		$pvirtual_counts = $_GPC['virtual_counts'];
		$pcounts = $_GPC['pcounts'];
		$pbs = $_GPC['pbs'];
		$pimgs = $_GPC['pimgs'];
		if (is_array($pids)) {
			foreach ($pids as $key => $value) {
				$value = intval($value);
				$d = array(
					"sid" => $sid,
					"pname" => $pnames[$key],
					'display_order' => $display_orders[$key],
					'p_summary' => $p_summarys[$key],
					'pimg' => $pimgs[$key],
					'price' => $prices[$key],
					'pcount' => $pcounts[$key],
					'virtual_count' => $pvirtual_counts[$key],
					'tgs' => $p_tggs[$key],
					'tgs_url'=>$p_tgs_urls[$key],
					'pb' => $pbs[$key],
					'ptype'=>$p_types[$key],
					'jfye' => $pjfyes[$key],
					"createtime" => TIMESTAMP
				);

				if (empty($value)) {
					$d['left_count'] =  $d['pcount'];
					DBUtil::create(DBUtil::$TABLE_QMSHAKE_PRIZE, $d);
					$prizids[] = pdo_insertid();
				} else {
					DBUtil::updateById(DBUtil::$TABLE_QMSHAKE_PRIZE, $d, $value);
					$prizids[] = $value;
				}

			}

			if (count($prizids) > 0) {
				pdo_query("delete from " . tablename(DBUtil::$TABLE_QMSHAKE_PRIZE) . " where sid='{$sid}' and id not in (" . implode(",", $prizids) . ")");
			} else {
				pdo_query("delete from " . tablename(DBUtil::$TABLE_QMSHAKE_PRIZE) . " where sid='{$sid}'");
			}
		}
		return true;
	}

	public function ruleDeleted($rid)
	{
		$shake = DBUtil::findUnique(DBUtil::$TABLE_QMSHAKE, array(":rid" => $rid));
		pdo_delete(DBUtil::$TABLE_QMSHAKE_USER, array("sid" => $shake['id']));
		pdo_delete(DBUtil::$TABLE_QMSHAKE_PRIZE, array('sid' => $shake['id']));
		pdo_delete(DBUtil::$TABLE_QMSHAKE_RECORD, array('sid' => $shake['id']));
		pdo_delete(DBUtil::$TABLE_QMSHAKE, array('id' => $shake['id']));
	}


}