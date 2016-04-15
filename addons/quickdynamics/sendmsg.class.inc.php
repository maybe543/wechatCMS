<?php
 class SendMsg{
    public function notifyBuyer($param){
        $text = $param['text'];
        $from_user = $param['from_user'];
        yload() -> classs('quickcenter', 'wechatapi');
        $_api = new WechatAPI();
        $_api -> sendText($from_user, $text);
        unset($_api);
        $ret = 1;
        return $ret;
    }
}
