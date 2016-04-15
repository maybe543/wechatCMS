<?php
	$outputdate = $_GPC['time'];
	$starttime = strtotime($outputdate['start']);
	$endtime = strtotime($outputdate['end']);
	if($_GPC['op']=='select'){
		$isoutput = $_GPC['isoutput'];
		if(!empty($isoutput)){
			$ids = implode(",", $isoutput);
			$lists = pdo_fetchall("SELECT o.id, o.from_user, o.ordersn, o.refundtime, o.price, o.refundreason, m.realname, m.mobile, m.bankcard, m.alipay, m.wxhao FROM ".tablename('hc_hunxiao_order')." as o left join ".tablename('hc_hunxiao_member')." as m on o.from_user = m.from_user and o.weid = m.weid WHERE o.weid = ".$_W['uniacid']." and o.status = -2 and o.id in (".$ids.")");
			foreach($isoutput as $i){
				pdo_update('hc_hunxiao_order', array('isoutput'=>1), array('id'=>$i));
			}
		} else {
			message('没有要导出的订单！');
			exit;
		}
	} else {
		$lists = pdo_fetchall("SELECT o.id, o.from_user, o.ordersn, o.refundtime, o.price, o.refundreason, m.realname, m.mobile, m.bankcard, m.alipay, m.wxhao FROM ".tablename('hc_hunxiao_order')." as o left join ".tablename('hc_hunxiao_member')." as m on o.from_user = m.from_user and o.weid = m.weid WHERE o.weid = ".$_W['uniacid']." and o.status = -2 and o.refundtime >=".$starttime." and o.refundtime <".$endtime);
	}
	$goods = pdo_fetchall("SELECT id, title FROM " . tablename('hc_hunxiao_goods')." WHERE weid = ".$_W['uniacid']);
	$good = array();
	foreach($goods as $g){
		$good[$g['id']] = $g['title'];
	}
	
	$list = array();
	foreach($lists as $k=>$v){
		$ordergoods = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_order_goods')." WHERE weid = ".$_W['uniacid']." and orderid = ".$v['id']);
		$title = '';
		foreach($ordergoods as $o){
			$title = $good[$o['goodsid']].','.$title;
		}
		$title = trim($title, ',');
		sendMoneyBack($v['from_user'], $v['price'], '返回至银行卡或微信钱包或支付宝', '7个工作日内', $title, $v['ordersn'], $v['refundreason']);
		$list[$k]['realname'] = $v['realname'];
		$list[$k]['mobile'] = $v['mobile'];
		$list[$k]['bankcard'] = $v['bankcard'];
		$list[$k]['alipay'] = $v['alipay'];
		$list[$k]['wxhao'] = $v['wxhao'];
		$list[$k]['refundtime'] = date('Y-m-d H:m:s' ,$v['refundtime']);
		$list[$k]['price'] = $v['price'];
		$list[$k]['refundreason'] = $v['refundreason'];
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
				->setCellValue('C1', '退款时间')
				->setCellValue('D1', '退款金额')
				->setCellValue('E1', '银行卡号')
				->setCellValue('F1', '支付宝号')
				->setCellValue('G1', '微信号码')
				->setCellValue('H1', '退款理由');

	foreach($list as $i=>$v){
		$i = $i+2;
		$objPHPExcel->setActiveSheetIndex(0)			
					->setCellValue('A'.$i, $v['realname'])
					->setCellValue('B'.$i, $v['mobile'])
					->setCellValue('C'.$i, $v['refundtime'])
					->setCellValue('D'.$i, $v['price'])
					->setCellValue('E'.$i,' '.$v['bankcard'].' ')
					->setCellValue('F'.$i,' '.$v['alipay'].' ')
					->setCellValue('G'.$i,' '.$v['wxhao'].' ')
					->setCellValue('H'.$i, $v['refundreason']);
	}					
	$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30); 
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30); 

	// Rename worksheet
	$time=time();
	$objPHPExcel->getActiveSheet()->setTitle('退款订单'.$time);


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	// Redirect output to a client’s web browser (Excel2007)]

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="退款订单_'.$time.'.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
?>