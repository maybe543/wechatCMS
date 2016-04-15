<?php
	$conditions = $_GPC['conditions'];
	$condition = '';
	if (!empty($conditions['keyword'])) {
		$condition .= " AND ordersn LIKE '%{$conditions['keyword']}%'";
	}
	if (!empty($conditions['transid'])) {
		$condition .= " AND transid = '{$conditions['transid']}'";
	}
	if (!empty($conditions['member'])) {
		$addressids = pdo_fetchall("select id from ".tablename('hc_hunxiao_address')." where weid = ".$_W['uniacid']." and realname LIKE '%{$conditions['member']}%' or mobile LIKE '%{$conditions['member']}%'");
		$addressid = 0;
		if(!empty($addressids)){
			foreach($addressids as $a){
				$addressid = $addressid.','.$a['id'];
			}
			$addressid = trim($addressid, ',');
		}
		$condition .= " AND addressid in (".$addressid.")";
	}
	if($conditions['paytype'] !=-1){
		if (!empty($conditions['paytype'])) {
			$condition .= " AND paytype = '{$conditions['paytype']}'";
		} elseif ($conditions['paytype'] === '0') {
			$condition .= " AND paytype = '{$conditions['paytype']}'";
		}
	}

	$starttime = $conditions['starttime'];
	$endtime = $conditions['endtime'];
	$condition .= " AND createtime >= ".$starttime." AND createtime <= ".$endtime;
	
	if ($conditions['status'] != -3) {
		$condition .= " AND status = '" . intval($conditions['status']) . "'";
	}

	$orders = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_order') . " WHERE weid = '{$_W['uniacid']}' $condition ");
	if(empty($orders)){
		message('没有需要导出的订单！');
	} else {
		$orderid = '';
		$order = array();
		$orderdispatch = array();
		foreach($orders as $l){
			$orderid = $l['id'].','.$orderid;
			$order[$l['id']] = $l['addressid'];
			$orderdispatch[$l['id']] = $l['dispatch'];
		}
		$orderid = '('.trim($orderid, ',').')';
	}
	$dispatchs = pdo_fetchall("SELECT id, dispatchname FROM " . tablename('hc_hunxiao_dispatch') . " WHERE weid = ".$weid);
	$dispatch = array();
	foreach($dispatchs as $d){
		$dispatch[$d['id']] = $d['dispatchname'];
	}
	$goods = pdo_fetchall("SELECT id, title FROM " . tablename('hc_hunxiao_goods')." WHERE weid = ".$_W['uniacid']);
	$good = array();
	foreach($goods as $g){
		$good[$g['id']] = $g['title'];
	}
	$address = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_address')." WHERE weid = ".$weid);
	$addres = array();
	foreach($address as $d){
		$addres['realname'][$d['id']] = $d['realname'];
		$addres['mobile'][$d['id']] = $d['mobile'];
		$addres['address'][$d['id']] = $d['address'];
	}
	$goods = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_order_goods')." WHERE weid = ".$_W['uniacid']." and orderid in ".$orderid);
	$list = array();
	foreach($goods as $k=>$v){
		$list[$k]['realname'] = $addres['realname'][$order[$v['orderid']]];
		$list[$k]['mobile'] = $addres['mobile'][$order[$v['orderid']]];
		$list[$k]['address'] = $addres['address'][$order[$v['orderid']]];
		$list[$k]['title'] = $good[$v['goodsid']];
		$list[$k]['optionname'] = $v['optionname'];
		$list[$k]['dispatch'] = empty($orderdispatch[$v['orderid']]) ? 自提 : $dispatch[$orderdispatch[$v['orderid']]];
		$list[$k]['createtime'] = date('Y-m-d H:m:s' ,$v['createtime']);
	}

	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');
		
	require_once '../framework/library/phpexcel/PHPExcel.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator($_W['account']['name'])
								 ->setLastModifiedBy($_W['account']['name'])
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Test result file");
	// Add some data
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', '真实姓名')
				->setCellValue('B1', '手机号码')
				->setCellValue('C1', '地址')
				->setCellValue('D1', '商品标题')
				->setCellValue('E1', '商品规格')
				->setCellValue('F1', '配送方式')
				->setCellValue('G1', '下单时间');

	foreach($list as $i=>$v){
		$i = $i+2;
		$objPHPExcel->setActiveSheetIndex(0)			
					->setCellValue('A'.$i, $v['realname'])
					->setCellValue('B'.$i, $v['mobile'])
					->setCellValue('C'.$i, $v['address'])
					->setCellValue('D'.$i, $v['title'])
					->setCellValue('E'.$i, $v['optionname'])
					->setCellValue('F'.$i, $v['dispatch'])
					->setCellValue('G'.$i, $v['createtime']);
	}					
	$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30); 

	// Rename worksheet
	$time=time();
	$objPHPExcel->getActiveSheet()->setTitle('订单'.$time);


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	// Redirect output to a client’s web browser (Excel2007)]

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="订单_'.$time.'.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
?>