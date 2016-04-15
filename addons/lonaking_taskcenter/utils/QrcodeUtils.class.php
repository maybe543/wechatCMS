<?php

class QrcodeUtils
{
    public static function createQrcodeDisposable($barcode){
        global $_W;
        $account = WeiXinAccount::create($_W['uniacid']);
    }
}
