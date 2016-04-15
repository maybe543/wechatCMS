<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
global $_GPC, $_W;
if(!isset($_GPC['status'])){
    message('抱歉，传递的参数错误！', '', 'error');
}
$status = $_GPC['status'];
yload() -> classs('quickmoney', 'MoneyRequest');
yload() -> classs('quickmoney', 'MoneyGoods');
$_request = new MoneyRequest();
$_money = new MoneyGoods();
$pindex = 1;
$psize = 1024000;
$list = $_request -> batchGet($_W['weid'], array('status' => $status), null, $pindex, $psize);
if ($status == 'done'){
    $sheet_title = $_W['account']['name'] . '-已兑换请求';
}else{
    $sheet_title = $_W['account']['name'] . '-未兑换请求';
}
foreach ($list as & $row){
    $row['status'] = $_request -> getStatusName($row['status']);
}
unset($row);
$objPHPExcel = new PHPExcel();
$objPHPExcel -> getProperties() -> setCreator("XiaoChu") -> setLastModifiedBy("XiaoChu") -> setTitle("Office 2007 XLSX Document") -> setSubject("Office 2007 XLSX Document") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.") -> setKeywords("office 2007 openxml php") -> setCategory("Order File");
$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A1', 'ID') -> setCellValue('B1', '状态') -> setCellValue('C1', '姓名') -> setCellValue('D1', '手机') -> setCellValue('E1', '反利方式') -> setCellValue('F1', 'OPENID') -> setCellValue('G1', '支付宝') -> setCellValue('H1', '银行卡号') -> setCellValue('I1', '开户行名称') -> setCellValue('J1', '款数') -> setCellValue('K1', '耗余额') -> setCellValue('L1', '备注') -> setCellValue('M1', '日期') ;
$i = 2;
foreach ($list as $row){
    $objPHPExcel -> setActiveSheetIndex(0) -> setCellValue('A' . $i, $row['id']) -> setCellValue('B' . $i, $row['status']) -> setCellValue('C' . $i, $row['realname']) -> setCellValueExplicit('D' . $i, $row['mobile'], PHPExcel_Cell_DataType :: TYPE_STRING) -> setCellValue('E' . $i, $_money -> getExchangeTypeStr($row['exchangetype'])) -> setCellValue('F' . $i, $row['from_user']) -> setCellValue('G' . $i, $row['alipay']) -> setCellValueExplicit('H' . $i, $row['bankcard'], PHPExcel_Cell_DataType :: TYPE_STRING) -> setCellValue('I' . $i, $row['bankname']) -> setCellValue('J' . $i, $row['cost']) -> setCellValue('K' . $i, $row['cost']) -> setCellValue('L' . $i, $row['note']) -> setCellValue('M' . $i, date('Y-m-d H:i', $row['createtime'])) ;
    $i++;
    unset($row);
}
$objPHPExcel -> getActiveSheet() -> getStyle('A1:L1') -> getFont() -> setBold(true);
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
$objPHPExcel -> getActiveSheet() -> getColumnDimension('L') -> setWidth(20);
$objPHPExcel -> getActiveSheet() -> getColumnDimension('M') -> setWidth(20);
$objPHPExcel -> getActiveSheet() -> setTitle($sheet_title);
$objPHPExcel -> setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $sheet_title . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory :: createWriter($objPHPExcel, 'Excel2007');
ob_clean();
$objWriter -> save('php://output');
exit;
