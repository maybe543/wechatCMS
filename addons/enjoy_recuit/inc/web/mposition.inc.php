<?php
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	//职位信息浏览
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$list=pdo_fetchall("select * from ".tablename('enjoy_recuit_position')." as a left join ".tablename('enjoy_recuit_position_range')." as b on a.id=b.pid WHERE a.uniacid = '{$_W['uniacid']}' order by hot desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('enjoy_recuit_position') . " WHERE uniacid = '{$_W['uniacid']}'");
	$pager = pagination($total, $pindex, $psize);


} elseif ($operation == 'post') {
	load()->func('tpl');
	//添加职位
	$id=intval($_GPC['id']);
	$item = pdo_fetch("select * from ".tablename('enjoy_recuit_position')." as a left join ".tablename('enjoy_recuit_position_range')." as b on a.id=b.pid WHERE a.id = ".$id);
	$mposition = pdo_fetch("select * from ".tablename('enjoy_recuit_position')." as a left join ".tablename('enjoy_recuit_position_range')." as b on a.id=b.pid WHERE a.id = ".$id);
		
	if(checksubmit('submit')){
		$data=array(
				'uniacid'=>$_W['uniacid'],
				'pname'=> $_GPC['pname'],
				'hot'=> intval($_GPC['hot']),
				'sex'=> $_GPC['sex'],
				'ed'=> $_GPC['ed'],
				'type'=> $_GPC['type'],
				'key'=> $_GPC['key'],
				'num'=> intval($_GPC['num']),
				'place'=> $_GPC['place'],
				'way'=> $_GPC['way'],
				'descript'=> $_GPC['descript'],
				'competence'=> $_GPC['competence'],
				'stime'=> TIMESTAMP
		);

		if (!empty($id)) {
			//更新数据
			pdo_update('enjoy_recuit_position', $data, array('id' => $id));
			$range_data=array(
					'uniacid'=>$_W['uniacid'],
					'pid'=>$id,
					'maxage'=>intval($_GPC['maxage']),
					'minage'=>intval($_GPC['minage']),
					'maxsalary'=>intval($_GPC['maxsalary']),
					'minsalary'=>intval($_GPC['minsalary']),
					'maxexper'=>intval($_GPC['maxexper']),
					'minexper'=>intval($_GPC['minexper'])
			);
			pdo_update('enjoy_recuit_position_range', $range_data, array('id' => $id));
			$message="更新职位成功！";
		} else {
			//插入数据
			pdo_insert('enjoy_recuit_position', $data);
			$id = pdo_insertid();
			$range_data=array(
					'uniacid'=>$_W['uniacid'],
					'pid'=>$id,
					'maxage'=>intval($_GPC['maxage']),
					'minage'=>intval($_GPC['minage']),
					'maxsalary'=>intval($_GPC['maxsalary']),
					'minsalary'=>intval($_GPC['minsalary']),
					'maxexper'=>intval($_GPC['maxexper']),
					'minexper'=>intval($_GPC['minexper'])
			);
			pdo_insert('enjoy_recuit_position_range', $range_data);
			$message="创建职位成功！";
		}
		message($message, $this->createWebUrl('Mposition', array('op' => 'display')), 'success');



	}
		

} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	//删除职位
	$mposition=pdo_fetch("select id from ".tablename('enjoy_recuit_position')." where id=".$id." and uniacid=".$_W['uniacid']."");
	if(empty($mposition)){
		message('抱歉,职位不存在或是已经被删除！', $this->createWebUrl('Mposition', array('op' => 'display')), 'error');
	}else {
		pdo_delete('enjoy_recuit_position', array('id' => $id));
		pdo_delete('enjoy_recuit_position_range', array('pid' => $id));
		message('职位删除成功！', $this->createWebUrl('Mposition', array('op' => 'display')), 'success');
	}
} else {
	//message('请求方式不存在');
}
include $this->template('mposition', TEMPLATE_INCLUDEPATH, true);