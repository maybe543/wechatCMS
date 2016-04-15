<?php

defined('IN_IA') or exit('Access Denied');

class member {
	private $t_mc_members;
	private $t_member;
	private $t_record;

	private $tb_mc_members;
	private $tb_member;
	private $tb_record;

	public function __construct() {
		$this->t_mc_members = 'mc_members';
		$this->t_member = 'jiexi_aaa_member';
		$this->t_record = 'jiexi_aaa_record';
		$this->tb_mc_members = tablename($this->t_mc_members);
		$this->tb_member = tablename($this->t_member);
		$this->tb_record = tablename($this->t_record);
	}

	function get_member_page($filter, $order='', $pindex = 0, $psize = 20, &$total = 0) {
		
		global $_W;
		$condition = 'a.uniacid=:uniacid and a.deleted=0';
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		
		if (!empty($filter['child_level'])) {
			$condition .= " and a.parent" . $filter['child_level'] . "=:parentid";
			$pars[':parentid'] = $filter['parentid'];
		}
		if (!empty($filter['uid'])) {
			$condition .= " and a.uid like :uid";
			$pars[':uid'] = "%{$filter['uid']}%";
		}
		if (!empty($filter['nickname'])) {
			$condition .= " and m.nickname like :nickname";
			$pars[':nickname'] = "%{$filter['nickname']}%";
		}
		if (!empty($filter['wqm_member'])) {
			$condition .= " and (a.wechat like :wqm_member or a.qq like :wqm_member or a.mobile like :wqm_member)";
			$pars[':wqm_member'] = "%{$filter['wqm_member']}%";
		}
		
		if (!empty($order)) {
			$order = "order by $order";
		} else {
			$order = '';
		}
		$sql = "select a.*,m.nickname,m.avatar,sum(r.packet) packet from ".$this->tb_member.'a left join '.$this->tb_mc_members." m on a.uid=m.uid left join " . $this->tb_record . " r on (a.uid=r.approval_uid or a.uid=r.manager_uid) and a_flag=2 and m_flag=2 where $condition group by a.uid $order";
		
		if ($pindex > 0) {
			$cntsql = "select count(*) from ".$this->tb_member.'a left join '.$this->tb_mc_members." m on a.uid=m.uid where $condition";
			$total = pdo_fetchcolumn($cntsql, $pars);
			$sql .= ' limit '. ($pindex - 1) * $psize . ",".$psize;
		}
		
		$list = pdo_fetchall($sql, $pars);
		
		return $list;
	}

	function get_children_count($uid, $level = 0, $valid = 0) {
		global $_W;
		
		$condition = "uniacid=:uniacid and deleted=0";
		
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		
		if (empty($level)) {
			$condition .= " and (parent1=:uid or parent2=:uid or parent3=:uid ".
				"or parent4=:uid or parent5=:uid or parent6=:uid or parent7=:uid ".
				"or parent8=:uid or parent9=:uid or parent10=:uid or parent11=:uid ".
				"or parent12=:uid)";
		} else {
			$condition .= " and parent$level=:uid";
		}
		$pars[':uid'] = $uid;
		
		if ($valid == -1) {
			$condition .= " and level=0";
		} elseif ($valid == 1) {
			$condition .= " and level>0";
		}
		
		$sql = "select count(*) from " . $this->tb_member . " where $condition";
		return pdo_fetchcolumn($sql, $pars);
	}
	
	function get_children_list($uid, $level, $valid = 0) {
		global $_W;
		
		$condition = "uniacid=:uniacid and parent$level=:uid and deleted=0";
		
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':uid'] = $uid;
		
		if ($valid == -1) {
			$condition .= " and level=0";
		} elseif ($valid == 1) {
			$condition .= " and level>0";
		}
		
		$sql = "select * from " . $this->tb_member . " where $condition order by add_time";
		return pdo_fetchall($sql, $pars);
	}

	function get_member_list() {
		
		global $_W;
		$condition = "a.uniacid=:uniacid";
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		
		$sql = "select a.*,m.nickname,m.avatar from ".$this->tb_member.' a left join '.$this->tb_mc_members." m on a.uid=m.uid where $condition";
		$list = pdo_fetchall($sql, $pars);
		if (!empty($list)) {
			return $list;
		}
		return false;
	}

	function get_member($uid) {
		
		global $_W;
		$condition = "a.uniacid=:uniacid and a.uid=:uid and a.deleted=0";
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':uid'] = $uid;
		$sql = "select a.*,m.nickname,m.avatar from ".$this->tb_member.' a left join '.$this->tb_mc_members." m on a.uid=m.uid where $condition";
		$exist = pdo_fetch($sql, $pars);
		if (!empty($exist)) {
			$level_list = $this->get_level_list();
			$exist['level_text'] = $level_list[$exist['level']]['name'];
			return $exist;
		}
		return false;
	}

	function add_member($entity) {
		
		$ret = pdo_insert('jiexi_aaa_member', $entity);
		if (!empty($ret)) {
			return pdo_insertid();
		}
		return false;
	}

	function drop_member($uid) {
		
		global $_W;
		$condition = "uniacid=:uniacid and uid=:uid";
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':uid'] = $uid;
		
		$sql = "delete from ".$this->tb_member." where $condition";
		
		return pdo_query($sql, $pars);
	}

	function update_member($uid, $entity) {
		pdo_update('jiexi_aaa_member', $entity, array(
			'uid' => $uid,
		));
	}

	function update_member_field($uid, $field, $value) {
		pdo_update('jiexi_aaa_member', array($field => $value),
			array('uid' => $uid));
	}

	function get_member_by_openid($openid) {
		
		$condition = "a.openid=:openid";
		$pars = array();
		$pars[':openid'] = $openid;
		$sql = "select a.*,m.nickname,m.avatar from ".$this->tb_member.' a left join '.$this->tb_mc_members." m on a.uid=m.uid where $condition";
		$exist = pdo_fetch($sql, $pars);
		if (!empty($exist)) {
			return $exist;
		}
		return false;
	}

	function get_superior_list() {
	
		$list = array(
			0 => array('key' => 0, 'name' => '没有上级'),
			1 => array('key' => 1, 'name' => '一层上级'),
			2 => array('key' => 2, 'name' => '二层上级'),
			3 => array('key' => 3, 'name' => '三层上级'),
			4 => array('key' => 4, 'name' => '四层上级'),
			5 => array('key' => 5, 'name' => '五层上级'),
			6 => array('key' => 6, 'name' => '六层上级'),
			7 => array('key' => 7, 'name' => '七层上级'),
			8 => array('key' => 8, 'name' => '八层上级'),
			9 => array('key' => 9, 'name' => '九层上级'),
			10 => array('key' => 10, 'name' => '十层上级'),
			11 => array('key' => 11, 'name' => '十一层上级'),
			12 => array('key' => 12, 'name' => '十二层上级')
		);
		return $list;
	}

	function get_junior_list() {
	
		$list = array(
			0 => array('key' => 0, 'name' => '没有下级'),
			1 => array('key' => 1, 'name' => '一层下级'),
			2 => array('key' => 2, 'name' => '二层下级'),
			3 => array('key' => 3, 'name' => '三层下级'),
			4 => array('key' => 4, 'name' => '四层下级'),
			5 => array('key' => 5, 'name' => '五层下级'),
			6 => array('key' => 6, 'name' => '六层下级'),
			7 => array('key' => 7, 'name' => '七层下级'),
			8 => array('key' => 8, 'name' => '八层下级'),
			9 => array('key' => 9, 'name' => '九层下级'),
			10 => array('key' => 10, 'name' => '十层下级'),
			11 => array('key' => 11, 'name' => '十一层下级'),
			12 => array('key' => 12, 'name' => '十二层下级')
		);
		return $list;
	}
	
	function get_level_list() {
		
		$list = array(
			0 => array('level' => 0, 'name' => '普通会员'),
			1 => array('level' => 1, 'name' => '一级会员'),
			2 => array('level' => 2, 'name' => '二级会员'),
			3 => array('level' => 3, 'name' => '三级会员'),
			4 => array('level' => 4, 'name' => '四级会员'),
			5 => array('level' => 5, 'name' => '五级会员'),
			6 => array('level' => 6, 'name' => '六级会员'),
			7 => array('level' => 7, 'name' => '七级会员'),
			8 => array('level' => 8, 'name' => '八级会员'),
			9 => array('level' => 9, 'name' => '九级会员'),
			10 => array('level' => 10, 'name' => '十级会员'),
			11 => array('level' => 11, 'name' => '十一级会员'),
			12 => array('level' => 12, 'name' => '十二级会员'),
		);
		return $list;
	}
}