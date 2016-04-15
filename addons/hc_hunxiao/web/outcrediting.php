<?php

	$outputdate = $_GPC['outputdate'];
	$starttime = strtotime($outputdate['start']);
	$endtime = strtotime($outputdate['end']);
	if($op=='output'){
		$selected = explode(",", $_GPC['selected']);
		if(empty($selected)){
			message('已没有需要导出的数据了！');
			exit;
		} else {
			$ids = 0;
			foreach($selected as $s){
				$ids = $ids.','.$s;
			}
			$ids = '('.trim($s, ',').')';
		}
		$outcrediting = pdo_fetchall("SELECT c.*,m.realname,m.mobile,m.bankcard,m.alipay,m.wxhao FROM `ims_hc_hunxiao_commission` AS c LEFT JOIN `ims_hc_hunxiao_member` AS m ON c.mid=m.id WHERE c.id in ".$ids." and c.isout = 0 AND c.flag = -1 AND c.weid=".$_W['uniacid']);
	} else {
		$outcrediting = pdo_fetchall("SELECT c.*,m.realname,m.mobile,m.bankcard,m.alipay,m.wxhao FROM `ims_hc_hunxiao_commission` AS c LEFT JOIN `ims_hc_hunxiao_member` AS m ON c.mid=m.id WHERE c.createtime>=".$starttime." AND c.createtime<=".$endtime." AND c.isout = 0 AND c.flag = -1 AND c.weid=".$_W['uniacid']);
	}
	if(empty($outcrediting)){
		message('已没有需要导出的数据了！');
		exit;
	}

	$list = array();
	foreach($outcrediting as $k=>$v){
		pdo_update('hc_hunxiao_commission', array('isout'=>1, 'flag'=>2), array('id'=>$v['id']));
		$list[$k]['realname'] = $v['realname'];
		$list[$k]['mobile'] = $v['mobile'];
		$list[$k]['commission'] = $v['commission'];
		$list[$k]['bankcard'] = $v['bankcard'];
		$list[$k]['alipay'] = $v['alipay'];
		$list[$k]['wxhao'] = $v['wxhao'];
		$list[$k]['createtime'] = date('Y-m-d H:m:s' ,$v['createtime']);
		$list[$k]['content'] = $v['content'];
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
				->setCellValue('C1', '申请佣金')
				->setCellValue('D1', '银行卡号')
				->setCellValue('E1', '支付宝号')
				->setCellValue('F1', '微信号码')
				->setCellValue('G1', '申请时间')
				->setCellValue('H1', '备注');

	foreach($list as $i=>$v){
		$i = $i+2;
		$objPHPExcel->setActiveSheetIndex(0)			
					->setCellValue('A'.$i, $v['realname'])
					->setCellValue('B'.$i, $v['mobile'])
					->setCellValue('C'.$i, $v['commission'])
					->setCellValue('D'.$i,' '.$v['bankcard'].' ')
					->setCellValue('E'.$i,' '.$v['alipay'].' ')
					->setCellValue('F'.$i,' '.$v['wxhao'].' ')
					->setCellValue('G'.$i, $v['createtime'])
					->setCellValue('H'.$i, $v['content']);
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
	$objPHPExcel->getActiveSheet()->setTitle('积分兑换佣金充值'.$time);


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	// Redirect output to a client’s web browser (Excel2007)]

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="积分兑换佣金充值'.$time.'.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');

	exit;
?>he-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');

	exit;
?>