<?php
defined('IN_IA') or exit('Access Denied');

class record {
	private $t_record;
	private $t_mc_members;
	private $t_member;
	
	private $tb_record;
	
	public function __construct() {
		$this->t_record = 'jiexi_aaa_record';
		$this->t_mc_members = 'mc_members';
		$this->t_member = 'jiexi_aaa_member';
	
		$this->tb_record = tablename($this->t_record);
		$this->tb_mc_members = tablename($this->t_mc_members);
		$this->tb_member = tablename($this->t_member);
	}

	function get_record_page($filter, $order='', $pindex = 0, $psize = 20, &$total = 0) {
		
		global $_W;
		$condition = 'r.uniacid=:uniacid';
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		
		if (!empty($filter['apply_member'])) {
			$condition .= ' and (a1.uid like :apply_member or a2.nickname like :apply_member or a1.wechat like :apply_member)';
			$pars[':apply_member'] = "%{$filter['apply_member']}%";
		}
		if (!empty($filter['approval_member'])) {
			$condition .= ' and (p1.uid like :approval_member or p2.nickname like :approval_member or p1.wechat like :approval_member)';
			$pars[':approval_member'] = "%{$filter['approval_member']}%";
		}

		$condition .= ' and r.apply_time between :apply_start_time and :apply_end_time';
		$pars[':apply_start_time'] = $filter['apply']['start_time'];
		$pars[':apply_end_time'] = $filter['apply']['end_time'];
		
		$order = "order by $order";
		
		$sql = 'select r.*,a2.nickname apply_name,p2.nickname approval_name,'.
			'm.nickname manager_name from '.$this->tb_record.
			' r left join '.$this->tb_member.' a1 on r.apply_uid=a1.uid '.
			'left join '.$this->tb_mc_members.' a2 on a1.uid=a2.uid '.
			'left join '.$this->tb_member.' p1 on r.approval_uid=p1.uid '.
			'left join '.$this->tb_mc_members.' p2 on p1.uid=p2.uid '.
			'left join '.$this->tb_mc_members.' m on r.manager_uid=m.uid '.
			"where $condition $order";
		
		if ($pindex > 0) {
			$cntsql = 'select count(*) from '.$this->tb_record.
			' r left join '.$this->tb_member.' a1 on r.apply_uid=a1.uid '.
			' left join '.$this->tb_mc_members.' a2 on a1.uid=a2.uid '.
			'left join '.$this->tb_member.' p1 on r.approval_uid=p1.uid '.
			'left join '.$this->tb_mc_members.' p2 on p1.uid=p2.uid '.
			"where $condition";
			$total = pdo_fetchcolumn($cntsql, $pars);
			$sql .= ' limit '. ($pindex - 1) * $psize . ','.$psize;
		}
		$list = pdo_fetchall($sql, $pars);
		
		return $list;
		
	}

	function add_record($entity) {
		$ret = pdo_insert($this->t_record, $entity);
		if (!empty($ret)) {
			return pdo_insertid();
		}
		return false;
	}

	function update_record($record_id, $entity) {
		pdo_update('jiexi_aaa_record', $entity, array(
		'record_id' => $record_id,
		));
	}

	function get_record($record_id) {
		
		global $_W;
		$condition = 'uniacid=:uniacid and record_id=:record_id';
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':record_id'] = $record_id;
		$sql = 'select * from '.$this->tb_record." where $condition";
		$exist = pdo_fetch($sql, $pars);
		if (!empty($exist)) {
			return $exist;
		}
		return false;
	}

	function get_record_by_apply_uid($apply_uid, $flag = 1) {
		
		global $_W;
		$condition = "uniacid=:uniacid and apply_uid=:apply_uid and (a_flag=:flag or m_flag=:flag)";
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':apply_uid'] = $apply_uid;
		$pars[':flag'] = $flag;
		
		$sql = "select * from " . $this->tb_record . " where $condition limit 1";
		$record = pdo_fetch($sql, $pars);
		if (!empty($record)) {
			return $record;
		}
		return false;
	}

	function get_record_by_approval_uid($approval_uid, $filter, $pindex = 0, $psize = 20, &$total = 0) {
		global $_W;
		$condition = "r.uniacid=:uniacid and ((r.approval_uid=:approval_uid and r.a_flag=:flag) or (r.manager_uid=:approval_uid and r.m_flag=:flag))";
		
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':approval_uid'] = $approval_uid;
		$pars[':flag'] = $filter['flag'];
		
		if (!empty($filter['apply_uid'])) {
			$condition .= " and r.apply_uid=:apply_uid";
			$pars[':apply_uid'] = $filter['apply_uid'];
		}
		
		if (!empty($filter['apply_member'])) {
			$condition .= " and (r.apply_uid like :apply_member or a2.nickname like :apply_member or a1.wechat like :apply_member)";
			$pars[':apply_member'] = "%{$filter['apply_member']}%";
		}
		
		$sql = "select r.*,a2.nickname apply_name,".
			'a2.avatar,a1.* from '.$this->tb_record.' r left join '.$this->tb_member.' a1 on r.apply_uid=a1.uid '.
			' left join '.$this->tb_mc_members." a2 on a1.uid=a2.uid where $condition";
		
		if ($pindex > 0) {
			$cntsql = 'select count(*) from '.$this->tb_record.
			' r left join '.$this->tb_member.' a1 on r.apply_uid=a1.uid '.
			' left join '.$this->tb_mc_members.' a2 on a1.uid=a2.uid '.
			"where $condition";
			$total = pdo_fetchcolumn($cntsql, $pars);
			$sql .= ' limit '. ($pindex - 1) * $psize . ','.$psize;
		}
		$list = pdo_fetchall($sql, $pars);
		if (!empty($list)) {
			return $list;
		}
		return false;
	}

	function get_packet($approval_uid) {
		global $_W;
		$condition = 'uniacid=:uniacid and (approval_uid=:approval_uid or manager_uid=:approval_uid) and a_flag=2 and m_flag=2';
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':approval_uid'] = $approval_uid;
	
		$sql = 'select sum(packet) packet from '.$this->tb_record." where $condition";
		$packet = pdo_fetchcolumn($sql, $pars);
		if (empty($packet)) {
			$packet = 0;
		}
		return $packet;
	}
}
