<?php
defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/quickdynamics/define.php';
require_once MODULE_ROOT . '/quickcenter/loader.php';
class QuickDynamicsModuleSite extends WeModuleSite{
    private static $t_queue = 'quickdynamics_queue';
    public function doWebQueryRunningState(){
        global $_W;
        yload() -> classs('quickdynamics', 'runningstate');
        yload() -> classs('quickdynamics', 'messagequeue');
        $_state = new RunningState();
        $_queue = new MessageQueue();
        $state = $_state -> leaseHold();
        $recent = $_queue -> getRecentMsg($_W['weid']);
        $result = array('state' => $state, 'recent' => $recent);
        message($result, '', 'ajax');
    }
    public function doWebStat(){
        global $_W, $_GPC;
        $this -> doWebAuth();
        include $this -> template('stat');
    }
    public function doWebAuth(){
        global $_W, $_GPC;
        yload() -> classs('quickauth', 'auth');
        $_auth = new Auth();
        $op = trim($_GPC['op']);
        $modulename = MODULE_NAME;
        $version = '0.60';
    }
    public function doWebManual(){
        global $_GPC;
        $this -> doWebAuth();
        yload() -> classs('quickdynamics', 'messagequeue');
        $_queue = new MessageQueue();
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
        if ('start' == $op){
            $_queue -> activate();
        }else if ('stop' == $op){
            $_queue -> stop();
        }
        $leaseHold = $_queue -> leaseHold();
        include $this -> template('manual');
    }
    public function doWebDynamics(){
        global $_GPC;
        $this -> doWebAuth();
        yload() -> classs('quickdynamics', 'messagequeue');
        $_queue = new MessageQueue();
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
        if ('start' == $op){
            $_queue -> activate();
        }else if ('stop' == $op){
            $_queue -> stop();
        }
        $queueSize = $_queue -> getSize();
        $leaseHold = $_queue -> leaseHold();
        include $this -> template('dynamics');
    }
    public function doMobileActivate(){
        yload() -> routing('quickdynamics', 'taskrunner');
    }
    public function doMobileAdd(){
        global $_GPC;
        yload() -> classs('quickdynamics', 'messagequeue');
        $_queue = new MessageQueue();
        $param = array('text' => $_GPC['text'], 'from_user' => $_GPC['from_user']);
        $_queue -> push('quickdynamics', 'sendmsg', 'SendMsg', 'notifyBuyer', $param);
    }
}
