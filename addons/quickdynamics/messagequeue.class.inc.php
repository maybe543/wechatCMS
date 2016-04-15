<?php
 class MessageQueue{
    private static $t_option = 'quickdynamics_option';
    private static $t_queue = 'quickdynamics_queue';
    public static $lease = 3;
    public function activate(){
        global $_W;
        WeUtility :: logging('try start a msg loop thread');
        if ($this -> leaseHold()){
            WeUtility :: logging('another thread is already running. exit');
            return;
        }
        $this -> start();
        $root = str_replace('payment/wechat/', '', $_W['siteroot']);
        $url = $root . '/app/' . murl('entry/module/activate', array('weid' => $_W['weid'], 'm' => 'quickdynamics'));
        $parsed = parse_url($url);
        $host = $parsed['host'];
        $path = $parsed['path'] . '?' . $parsed['query'] ;
        $cookie = '';
        $fp = fsockopen($host, 80, $errno, $errstr, 30);
        WeUtility :: logging('fsockopen', array($url, $errno, $errstr, $fp));
        if (!$fp){
            return -1;
        }
        $out = "GET " . $path . " HTTP/1.1\r\n";
        $out .= "Host: " . $host . "\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cookie: " . $cookie . "\r\n\r\n";
        fwrite($fp, $out);
        fclose($fp);
        WeUtility :: logging('Msg loop thread start success', $fp);
        return 0;
    }
    public function start(){
        $ret = pdo_query('UPDATE ' . tablename(self :: $t_option) . ' SET running=1');
        return $ret;
    }
    public function stop(){
        $ret = pdo_query('UPDATE ' . tablename(self :: $t_option) . ' SET running=0');
        WeUtility :: logging('Receive a singal', array('stop' => $stop, 'ret' => $ret));
        return $ret;
    }
    public function getSize(){
        $size = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(self :: $t_queue));
        return $size;
    }
    public function push($module, $file, $class, $method, $param){
        $sparam = iserializer($param);
        $af = pdo_insert(self :: $t_queue, array('module' => $module, 'file' => $file, 'class' => $class, 'method' => $method, 'param' => $sparam, 'createtime' => time()));
        $this -> activate();
        return $af;
    }
    public function leaseHold(){
        $lasttime = pdo_fetchcolumn('SELECT lasttime FROM ' . tablename(self :: $t_option) . ' LIMIT 1');
        $running = (time() <= ($lasttime + self :: $lease));
        if ($running){
            WeUtility :: logging('expire in ' . ($lasttime + self :: $lease - time()) . ' seconds');
        }else{
            WeUtility :: logging('expired already');
        }
        return intval($running);
    }
    public function isStopped(){
        $running = pdo_fetchcolumn('SELECT running FROM ' . tablename(self :: $t_option) . ' LIMIT 1');
        return (0 == $running);
    }
    public function isLeaseFree(){
        $lasttime = pdo_fetchcolumn('SELECT lasttime FROM ' . tablename(self :: $t_option) . ' LIMIT 1');
        $free = (time() > ($lasttime + self :: $lease));
        unset($lasttime);
        return intval($free);
    }
    public function renewLease(){
        pdo_query('UPDATE ' . tablename(self :: $t_option) . ' SET lasttime=:t', array(':t' => time()));
    }
    public function releaseLease(){
        pdo_query('UPDATE ' . tablename(self :: $t_option) . ' SET lasttime=0');
    }
}
