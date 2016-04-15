<?php
class DealerNotifier{
    public function notify($weid, $dealer, $from_user, $msg_template){
        global $_W;
        if (empty($dealer) or empty($from_user) or empty($msg_template)){
            return;
        }
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quickcenter', 'textparser');
        yload() -> classs('quickcenter', 'fans');
        $_fans = new Fans();
        $_api = new WechatAPI();
        $_parser = new TextParser();
        $buyer = $_fans -> get($weid, $from_user);
        $map = array('/\[buyer\]/' => $buyer['nickname'], '/\[time\]/' => date('m-d H:i:s', TIMESTAMP));
        $msg = $_parser -> batchParse($map, $msg_template);
        $_api -> sendText($dealer, $msg);
    }
}
