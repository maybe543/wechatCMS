<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
global $_GPC, $_W;
yload() -> classs('quickcredit', 'CreditRequest');
$_request = new CreditRequest();
$pindex = 1;
$psize = 1024000;
$list = $_request -> batchGetExport($_W['weid'], array(), null, $pindex, $psize);
$sheet_title = $_W['account']['name'] . '-所有兑换和未兑换请求';
foreach ($list as & $row){
    $row['status'] = $_request -> getStatusName($row['status']);
}
unset($row);
$objPHPExcel = new PHPExcel();
$objPHPExcel -> getProperties() -> setCreator("XiaoChu") -> setLastModifiedBy("XiaoChu") -> setTitle("Office 2007 XLSX Document") -> setSubject("Office 2007 XLSX Document") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.") -> setKeywords("office 2007 openxml php") -> setCategory("Order File");
$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', 'ID') -> setCellValue('B1', '状态') -> setCellValue('C1', '姓名') -> setCellValue('D1', '手机') -> setCellValue('E1', 'OPENID') -> setCellValue('F1', '商品名') -> setCellValue('G1', '支付宝') -> setCellValue('H1', '款数') -> setCellValue('I1', '耗余额') -> setCellValue('J1', '备注') -> setCellValue('K1', '日期') -> setCellValue('L1', '地址') ;
$i = 2;
foreach ($list as $row){
    $objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A' . $i, $row['id']) -> setCellValue('B' . $i, $row['status']) -> setCellValue('C' . $i, $row['realname']) -> setCellValueExplicit('D' . $i, $row['mobile'], PHPExcel_Cell_DataType :: TYPE_STRING) -> setCellValue('E' . $i, $row['from_user']) -> setCellValue('F' . $i, $row['title']) -> setCellValue('G' . $i, $row['alipay']) -> setCellValue('H' . $i, $row['price']) -> setCellValue('I' . $i, $row['cost']) -> setCellValue('J' . $i, $row['note']) -> setCellValue('K' . $i, date('Y-m-d H:i', $row['createtime'])) -> setCellValue('L' . $i, $row['residedist']) ;
    $i++;
    unset($row);
}
$objPHPExcel -> getActiveSheet() -> getStyle('A1:K1') -> getFont() -> setBold(true);
$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(10);
$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(15);
$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(22);
$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(22);
$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(40);
$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(22);
$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(22);
$objPHPExcel -> getActiveSheet() -> getColumnDimension('I') -> setWidth(22);
$objPHPExcel -> getActiveSheet() -> getColumnDimension('J') -> setWidth(70);
$objPHPExcel -> getActiveSheet() -> getColumnDimension('K') -> setWidth(20);
$objPHPExcel -> getActiveSheet() -> getColumnDimension('L') -> setWidth(30);
$objPHPExcel -> getActiveSheet() -> setTitle($sheet_title);
$objPHPExcel -> setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $sheet_title . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory :: createWriter($objPHPExcel, 'Excel2007');
ob_clean();
$objWriter -> save('php://output');
exit;
