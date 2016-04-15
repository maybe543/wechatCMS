<?php
global $_GPC;
$raw = @base64_decode($_GPC['raw']);
if(!empty($raw)) {
	include IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
	QRcode::png($raw, false, QR_ECLEVEL_Q, 4);
}