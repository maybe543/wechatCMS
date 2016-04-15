<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
global $_GPC, $_W;
if(!isset($_GPC['status'])){
    message('抱歉，传递的参数错误！', '', 'error');
}
$status = intval($_GPC['status']);
yload() -> classs('quickcenter', 'fans');
yload() -> classs('quickshop', 'order');
yload() -> classs('quickshop', 'dispatch');
yload() -> classs('quickshop', 'address');
yload() -> classs('quickshop', 'express');
$_order = new Order();
$_fans = new Fans();
$_dispatch = new Dispatch();
$_address = new Address();
$_express = new Express();
$pindex = 1;
$psize = 1024000;
list($list, $total) = $_order -> batchGet($_W['weid'], array('status' => $status, 'allstatus' => !isset($status)), null, $pindex, $psize);
if (!empty($list)){
    foreach ($list as & $row){
        $address = $_address -> get($row['addressid']);
        $fans = $_fans -> get($row['weid'], $row['from_user']);
        $row['nickname'] = $fans['nickname'];
        $row['realname'] = $address['realname'];
        $row['mobile'] = $address['mobile'];
        $row['address'] = $address['province'] . $address['city'] . $address['area'] . $address['address'];
        $goods = $_order -> getDetailedGoods($row['id']);
        $body = '';
        foreach($goods as $g){
            $body .= "数量:{$g['total']} x {$g['title']} - id({$g['id']}) - 单价({$g['ordergoodsprice']}) " . "\n";
        }
        $row['goods'] = $body;
        unset($row);
        unset($body);
    }
}
if (empty($status)){
    $sheet_title = $_W['account']['name'] . '-全部订单数据';
}else{
    $sheet_title = $_W['account']['name'] . '-' . $_order -> getOrderStatusName($status) . '订单数据';
}
foreach ($list as & $row){
    $row['status'] = $_order -> getOrderStatusName($row['status']);
    $row['paytype'] = $_order -> getPayTypeName($row['paytype']);
    $row['sendtype'] = $_dispatch -> getSendTypeName($row['sendtype']);
}
unset($row);
$objPHPExcel = new PHPExcel();
$objPHPExcel -> getProperties() -> setCreator("XiaoChu") -> setLastModifiedBy("XiaoChu") -> setTitle("Office 2007 XLSX Document") -> setSubject("Office 2007 XLSX Document") -> setDescription("Test document for Office 2007 XLSX, generated using PHP classes.") -> setKeywords("office 2007 openxml php") -> setCategory("Order File");
$cmap = array('id' => 'A', 'ordersn' => 'B', 'status' => 'C', 'nickname' => 'D', 'realname' => 'E', 'mobile' => 'F', 'openid' => 'G', 'address' => 'H', 'price' => 'I', 'createtime' => 'J', 'detail' => 'K', 'remark' => 'L', 'paytype' => 'M', 'sendtype' => 'N');
$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue($cmap['id'] . '1', 'ID') -> setCellValue($cmap['ordersn'] . '1', '订单号') -> setCellValue($cmap['status'] . '1', '状态') -> setCellValue($cmap['nickname'] . '1', '昵称') -> setCellValue($cmap['realname'] . '1', '姓名') -> setCellValue($cmap['mobile'] . '1', '手机') -> setCellValue($cmap['openid'] . '1', 'OPENID') -> setCellValue($cmap['address'] . '1', '快递地址') -> setCellValue($cmap['price'] . '1', '总价') -> setCellValue($cmap['createtime'] . '1', '下单时间') -> setCellValue($cmap['detail'] . '1', '订单详情') -> setCellValue($cmap['remark'] . '1', '备注') -> setCellValue($cmap['paytype'] . '1', '付款方式') -> setCellValue($cmap['sendtype'] . '1', '快递类型') ;
$i = 2;
foreach ($list as $listrow){
    $objPHPExcel -> setActiveSheetIndex(0) -> setCellValue($cmap['id'] . $i, $listrow['id']) -> setCellValueExplicit($cmap['ordersn'] . $i, $listrow['ordersn'], PHPExcel_Cell_DataType :: TYPE_STRING) -> setCellValue($cmap['status'] . $i, $listrow['status']) -> setCellValue($cmap['nickname'] . $i, $listrow['nickname']) -> setCellValue($cmap['realname'] . $i, $listrow['realname']) -> setCellValueExplicit($cmap['mobile'] . $i, $listrow['mobile'], PHPExcel_Cell_DataType :: TYPE_STRING) -> setCellValue($cmap['openid'] . $i, $listrow['from_user']) -> setCellValue($cmap['address'] . $i, $listrow['address']) -> setCellValue($cmap['price'] . $i, $listrow['price']) -> setCellValue($cmap['createtime'] . $i, date('Y-m-d H:i', $listrow['createtime'])) -> setCellValue($cmap['detail'] . $i, $listrow['goods']) -> setCellValue($cmap['remark'] . $i, $listrow['remark']) -> setCellValue($cmap['paytype'] . $i, $listrow['paytype']) -> setCellValue($cmap['sendtype'] . $i, $listrow['sendtype']) ;
    $i++;
    unset($listrow);
}
$objPHPExcel -> getActiveSheet() -> getStyle('A1:J1') -> getFont() -> setBold(true);
$objPHPExcel -> getActiveSheet() -> getColumnDimension($cmap['id']) -> setWidth(10);
$objPHPExcel -> getActiveSheet() -> getColumnDimension($cmap['ordersn']) -> setWidth(10);
$objPHPExcel -> getActiveSheet() -> getColumnDimension($cmap['status']) -> setWidth(15);
$objPHPExcel -> getActiveSheet() -> getColumnDimension($cmap['realname']) -> setWidth(22);
$objPHPExcel -> getActiveSheet() -> getColumnDimension($cmap['mobile']) -> setWidth(22);
$objPHPExcel -> getActiveSheet() -> getColumnDimension($cmap['openid']) -> setWidth(40);
$objPHPExcel -> getActiveSheet() -> getColumnDimension($cmap['address']) -> setWidth(40);
$objPHPExcel -> getActiveSheet() -> getColumnDimension($cmap['price']) -> setWidth(7);
$objPHPExcel -> getActiveSheet() -> getColumnDimension($cmap['createtime']) -> setWidth(22);
$objPHPExcel -> getActiveSheet() -> getColumnDimension($cmap['detail']) -> setWidth(70);
$objPHPExcel -> getActiveSheet() -> setTitle($sheet_title);
$objPHPExcel -> setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $sheet_title . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory :: createWriter($objPHPExcel, 'Excel2007');
ob_clean();
$objWriter -> save('php://output');
exit;
