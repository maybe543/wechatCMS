<?php
$t_option = 'quickdynamics_option';
$t_queue = 'quickdynamics_queue';
ignore_user_abort(true);
$max_execution_time = intval(ini_get('max_execution_time'));
WeUtility :: logging('max_execution_time', $max_execution_time);
if (empty($max_execution_time) or $max_execution_time < 20){
    $max_execution_time = 10;
}else{
    $max_execution_time = $max_execution_time - 10;
}
if (!empty($_POST) || defined('SENDING_MSG')){
    WeUtility :: logging('die running');
    die();
}
define('SENDING_MSG', true);
yload() -> classs('quickdynamics', 'messagequeue');
$_queue = new MessageQueue();
if ($_queue -> isLeaseFree()){
    $begin_time = time();
    WeUtility :: logging('Task Runner start a new thread and renewLease the lease');
    $seconds = $max_execution_time;
    while ($seconds > 0){
        $_queue -> renewLease();
        $m = pdo_fetch('SELECT * FROM ' . tablename($t_queue) . ' ORDER BY id LIMIT 1');
        if (!empty($m)){
            WeUtility :: logging('running task', $m);
            $af = pdo_delete($t_queue, array('id' => $m['id']));
            if ($af === 0){
                WeUtility :: logging('Another is running. exit', posix_getpid());
                exit(0);
            }
            yload() -> classs($m['module'], $m['file']);
            $c = new $m['class']();
            if (method_exists($c, $m['method'])){
                $param = iunserializer($m['param']);
                $c -> $m['method']($param);
                WeUtility :: logging('task executed', $m);
            }
            unset($m);
            $seconds = $max_execution_time;
        }else{
            $seconds--;
            sleep(1);
        }
        $stop = $_queue -> isStopped();
        if ($stop){
            WeUtility :: logging('queue stopped, exit msg loop');
            break;
        }else{
            WeUtility :: logging('tick' . $seconds, $stop);
            $end_time = time();
            $remain = $max_execution_time - ($end_time - $begin_time);
            if ($remain <= 0){
                break;
            }else{
                WeUtility :: logging('terminate in ' . $remain . ' seconds');
            }
        }
    }
}else{
    WeUtility :: logging('Task Runner fail get a new lease. terminate');
}
unset($_queue);
