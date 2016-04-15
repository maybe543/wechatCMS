<?php
/**
 * fwei_forms 通用表单
 * ============================================================================
 * * 版权所有 2005-2012 fwei.net，并保留所有权利。
 *   网站地址: http://www.fwei.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: fwei.net  / 1331305@qq.com
 *
 **/

$attr_types = array(
	'radio'	=>	'单选',
	'checkbox'	=>	'多选',
	'select'	=>	'下拉选框',
	'text'	=>	'单行文本',
	'textarea'	=>	'多行文本',
	'date'	=>	'日期',
	'datetime'	=>	'日期时间',
	'images'	=>	'图片'
);


function toExcel( $out_data, $filename = 'exprot.xls' ){
	//开始导入
	set_time_limit(0);
	include_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
	/** PHPExcel_IOFactory */
	include_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/IOFactory.php';
	
	include_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Writer/Excel5.php';
	
	//创建新的PHPExcel对象
	$objPHPExcel = new PHPExcel();
	$objActSheet = $objPHPExcel->getActiveSheet();
	$column = 1;
	foreach ($out_data as $row){
		$span = ord("A");
		foreach ($row as $val){
			$per = intval( ($span - 90) / 26 );
			$num = ($span - 65) % 26;
			$chr = $span>90 ? chr( 65 + $per ) : '';
			$chr = $chr . chr( 65 + $num );
			$objActSheet->setCellValue($chr.$column, $val);
			$span++;
		}
		$column++;
	}
	
	$fileName = iconv("utf-8", "gb2312", $filename);
	//将输出重定向到一个客户端web浏览器(Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header("Content-Disposition: attachment; filename=\"$fileName\"");
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}