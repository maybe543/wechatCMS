<?php  
include 'phpqrcode.php';
$value ='http://www.baidu.com';
$errorCorrectionLevel ='H';
$matrixPointSize ='10';
$rand_file =rand(). '.png';$att_target_file ='qr-' . $rand_file;$target_file ='tmppic/' . $att_target_file;
QRcode::png($value, $target_file, $errorCorrectionLevel, $matrixPointSize);
echo $target_file;
?>