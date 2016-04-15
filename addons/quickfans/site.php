<?php
 defined('IN_IA') or exit('Access Denied');
require_once(IA_ROOT . '/addons/quickfans/define.php');
require_once(IA_ROOT . '/addons/quickcenter/loader.php');
class QuickFansModuleSite extends WeModuleSite{
    function __construct(){
    }
    public function doMobileCenter(){
    }
    public function doWebCenter(){
        global $_W, $_GPC;
        $this -> doWebAuth();
        yload() -> classs('quickcenter', 'fans');
        $_fans = new Fans();
        $op = empty($_GPC['op']) ? 'display' : ($_GPC['op']);
        if ($op == 'display'){
            $cond = array();
            if (isset($_GPC['searchtype'])){
                switch($_GPC['searchtype']){
                case 'nickname': $cond['nickname'] = $_GPC['search'];
                    break;
                case 'from_user': $cond['from_user'] = $_GPC['search'];
                    break;
                case 'mobile': $cond['mobile'] = $_GPC['search'];
                    break;
                case 'vip': $cond['vip'] = $_GPC['search'];
                    break;
                case 'credit1': $cond['credit1'] = $_GPC['search'];
                    $cond['orderby'] = 'credit';
                    break;
                case 'credit2': $cond['credit2'] = $_GPC['search'];
                    break;
                case 'follow': $cond['follow'] = $_GPC['search'];
                    break;
                default: $cond['nickname'] = $_GPC['search'];
                    $cond['from_user'] = $_GPC['search'];
                    $cond['mobile'] = $_GPC['search'];
                    break;
                }
            }else{
                $cond['follow'] = 1;
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 100;
            list($list, $total) = $_fans -> batchGet($_W['weid'], $cond, null, $pindex, $psize);
            foreach($list as & $_item){
                $this -> fansBeautify($_item);
            }
            $pager = pagination($total, $pindex, $psize);
            yload() -> classs('quickcenter', 'FormTpl');
            include $this -> template('list');
            exit(0);
        }else if ($op == 'post'){
            $from_user = $_GPC['from_user'];
            $item = $_fans -> get($_W['weid'], $from_user);
            $this -> fansBeautify($item);
            if (!empty($_GPC['id'])){
                yload() -> routing('quickfans', 'UpdateFans');
                message('执行成功', referer(), 'success');
            }
            yload() -> classs('quickcenter', 'creditlog');
            $_creditlog = new CreditLog();
            $creditlog = $_creditlog -> get($_W['weid'], $from_user);
            yload() -> classs('quickshop', 'order');
            $_order = new Order();
            $allgoods = $_order -> batchGetOrderGoodsByOpenIds($_W['weid'], array($from_user));
            yload() -> classs('quickcenter', 'fans');
            $_fans = new Fans();
            $leader = $_fans -> getUplevelFans($_W['weid'], $from_user);
            yload() -> classs('quickcenter', 'custommsg');
            $_cust = new CustomMsg();
            $msgHistory = $_cust -> getCustomMsg($_W['weid'], $from_user, 1000);
            yload() -> classs('quickcenter', 'FormTpl');
            include $this -> template('edit');
            exit(0);
        }
        message('未知操作', '', 'error');
    }
    public function doWebAuth(){
        global $_W, $_GPC;
        yload() -> classs('quickauth', 'auth');
        $_auth = new Auth();
        $op = trim($_GPC['op']);
        $modulename = MODULE_NAME;
        $version = '0.60';
        $_auth -> checkXAuth($op, $modulename, $version);
    }
    private function fansBeautify(& $fans){
        if (empty($fans['avatar'])){
            $fans['avatar'] = RES_IMG . 'default_head.png';
        }
        return;
    }
    public function doWebRefresh(){
        global $_W, $_GPC;
        if (empty($_GPC['from_user'])){
            message('非法OpenID', referer(), 'error');
        }
        yload() -> classs('quickcenter', 'fans');
        $_fans = new Fans();
        $fans = $_fans -> refresh($_W['weid'], $_GPC['from_user'], true);
        message($fans, referer(), 'ajax');
    }
    public function doMobileRefresh(){
        global $_W, $_GPC;
        yload() -> classs('quickcenter', 'fans');
        $_fans = new Fans();
        $fans = $_fans -> refresh($_W['weid'], $_W['fans']['from_user'], true);
        message('头像刷新成功', referer(), 'success');
    }
    public function doWebDisappear(){
        global $_W, $_GPC;
        yload() -> classs('quickcenter', 'fans');
        $_fans = new Fans();
        $_fans -> disappear($_W['weid'], $_GPC['from_user']);
        yload() -> classs('quicklink', 'follow');
        $_follow = new Follow();
        $_follow -> disappear($_W['weid'], $_GPC['from_user']);
        yload() -> classs('quickshop', 'order');
        $_order = new Order();
        $_order -> disappear($_W['weid'], $_GPC['from_user']);
        message($_GPC['from_user'] . '消失成功', referer(), 'success');
    }
}
