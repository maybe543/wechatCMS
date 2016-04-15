<?php
 defined('IN_IA') or exit('Access Denied');
include 'define.php';
require_once(IA_ROOT . '/addons/quickcenter/loader.php');
class QuickMoneyModuleSite extends WeModuleSite{
    public $table_request = "quickmoney_request";
    public $table_goods = "quickmoney_goods";
    private $creditname = '余额';
    function __construct(){
        $creditnames = uni_setting($_W['uniacid'], array('creditnames'));
        if (!empty($creditnames['creditnames']['credit2']['title'])){
            $this -> creditname = $creditnames['creditnames']['credit2']['title'];
        }
    }
    public function doWebGoods(){
        global $_W;
        global $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        yload() -> classs('quickmoney', 'MoneyGoods');
        $_money = new MoneyGoods();
        if ($operation == 'post'){
            yload() -> classs('quickcenter', 'FormTpl');
            $goods_id = intval($_GPC['goods_id']);
            if (!empty($goods_id)){
                $item = pdo_fetch("SELECT * FROM " . tablename($this -> table_goods) . " WHERE goods_id = :goods_id" , array(':goods_id' => $goods_id));
                if (empty($item)){
                    message('抱歉，兑换商品不存在或是已经删除！', '', 'error');
                }
            }
            if (checksubmit('submit')){
                if (empty($_GPC['title'])){
                    message('请输入兑换商品名称！');
                }
                if (empty($_GPC['cost'])){
                    message('请输入取现需要消耗的余额！');
                }
                $cost = intval($_GPC['cost']);
                $vip_require = intval($_GPC['vip_require']);
                $amount = intval($_GPC['amount']);
                $per_user_limit = intval($_GPC['per_user_limit']);
                $data = array('weid' => $_W['weid'], 'title' => $_GPC['title'], 'logo' => $_GPC['logo'], 'timestart' => strtotime($_GPC['timestart']), 'timeend' => strtotime($_GPC['timeend']), 'deadline' => $_GPC['deadline'], 'exchangetype' => $_GPC['exchangetype'], 'userchangecost' => intval($_GPC['userchangecost']), 'amount' => $amount, 'per_user_limit' => $per_user_limit, 'vip_require' => $vip_require, 'cost' => $cost, 'content' => $_GPC['content'], 'createtime' => TIMESTAMP,);
                if (!empty($goods_id)){
                    pdo_update($this -> table_goods, $data, array('goods_id' => $goods_id));
                }else{
                    pdo_insert($this -> table_goods, $data);
                }
                message('商品更新成功！', $this -> createWebUrl('goods', array('op' => 'display')), 'success');
            }else{
                if (empty($goods_id)){
                    $item = array();
                    $item['title'] = "100元积分兑换";
                    $item['amount'] = 1000;
                    $item['per_user_limit'] = 5;
                    $item['cost'] = 100;
                    $item['userchangecost'] = 0;
                    $item['logo'] = "http://img3.redocn.com/20120218/Redocn_2012021818143904.jpg";
                    $item['content'] = "余额满100, 立即取现100元!";
                    $item['exchangetype'] = 1;
                    $item['deadline'] = date('Y-m-d H:i:s', TIMESTAMP + 60 * 60 * 24 * 30);
                }
            }
        }else if ($operation == 'delete'){
            $goods_id = intval($_GPC['goods_id']);
            $row = pdo_fetch("SELECT goods_id FROM " . tablename($this -> table_goods) . " WHERE goods_id = :goods_id", array(':goods_id' => $goods_id));
            if (empty($row)){
                message('抱歉，商品' . $goods_id . '不存在或是已经被删除！');
            }
            pdo_delete($this -> table_goods, array('goods_id' => $goods_id));
            message('删除成功！', referer(), 'success');
        }else if ($operation == 'display'){
            if (checksubmit()){
                if (!empty($_GPC['displayorder'])){
                    foreach ($_GPC['displayorder'] as $id => $displayorder){
                        pdo_update($this -> table_goods, array('displayorder' => $displayorder), array('goods_id' => $id));
                    }
                    message('排序更新成功！', referer(), 'success');
                }
            }
            $condition = '';
            $list = pdo_fetchall("SELECT * FROM " . tablename($this -> table_goods) . " WHERE weid = '{$_W['weid']}' $condition ORDER BY displayorder DESC, goods_id DESC");
        }
        include $this -> template('goods');
    }
    public function doWebRequest(){
        global $_W, $_GPC;
        yload() -> classs('quickmoney', 'MoneyRequest');
        yload() -> classs('quickcenter', 'FormTpl');
        $_request = new MoneyRequest();
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display_new';
        if ($operation == 'delete'){
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT * FROM " . tablename($this -> table_request) . " WHERE id = :id", array(':id' => $id));
            if (empty($row)){
                message('抱歉，编号为' . $id . '的兑换请求不存在或是已经被删除！');
            }else if ($row['status'] != 'done'){
                message('未兑换商品无法删除。请兑换后删除！', referer(), 'error');
            }
            pdo_delete($this -> table_request, array('id' => $id));
            message('删除成功！', referer(), 'success');
        }else if ($operation == 'do_goods'){
            $data = array('status' => 'done');
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM " . tablename($this -> table_request) . " WHERE id = :id", array(':id' => $id));
            if (empty($row)){
                message('抱歉，编号为' . $id . '的兑换请求不存在或是已经被删除！');
            }
            pdo_update($this -> table_request, $data, array('id' => $id));
            message('已经移入“已兑换请求”栏！', referer(), 'success');
        }else if ($operation == 'display_new'){
            $status = 'new';
            $condition = '';
            if (!empty($_GPC['search'])){
                $kw = $_GPC['keyword'];
                $condition .= "  AND (t2.from_user like '%" . $kw . "%' OR  t2.mobile like '%" . $kw . "%' OR t2.realname like '%" . $kw . "%' OR t2.alipay like '%" . $kw . "%'  OR t2.bankname like '%" . $kw . "%') ";
            }
            $sql = "SELECT t1.title, t1.logo, t2.exchangetype, t2.* FROM " . tablename($this -> table_goods) . " as t1," . tablename($this -> table_request) . "as t2 WHERE  t2.status != 'done' AND  t1.goods_id=t2.goods_id AND t1.weid = '{$_W['weid']}' " . $condition . " ORDER BY t2.createtime DESC";
            $list = pdo_fetchall($sql);
            $ar = pdo_fetchall($sql, array(), 'from_user');
            $fans = fans_search(array_keys($ar), array('realname', 'mobile', 'alipay'));
        }else if ($operation == 'display_done'){
            $status = 'done';
            $condition = '';
            if (!empty($_GPC['search'])){
                $kw = $_GPC['keyword'];
                $condition .= "  AND (t2.from_user like '%" . $kw . "%' OR  t2.mobile like '%" . $kw . "%' OR t2.realname like '%" . $kw . "%' OR t2.alipay like '%" . $kw . "%' OR t2.bankname like '%" . $kw . "%') ";
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 100;
            $sql = "SELECT t1.title, t1.logo, t2.exchangetype, t2.* FROM " . tablename($this -> table_goods) . " as t1," . tablename($this -> table_request) . "as t2 WHERE t1.goods_id=t2.goods_id AND t1.weid = {$_W['weid']} and t2.status = 'done' " . $condition . " ORDER BY t2.createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
            $total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this -> table_goods) . " as t1," . tablename($this -> table_request) . "as t2 WHERE t1.goods_id=t2.goods_id AND t1.weid = {$_W['weid']} and t2.status = 'done' " . $condition);
            $list = pdo_fetchall($sql);
            $ar = pdo_fetchall($sql, array(), 'from_user');
            $fans = fans_search(array_keys($ar), array('realname', 'mobile', 'alipay'));
            $pager = pagination($total, $pindex, $psize);
        }else{
            $status = 'new';
            $sql = "SELECT t1.title, t1.logo, t2.exchangetype, t2.* FROM " . tablename($this -> table_goods) . " as t1," . tablename($this -> table_request) . "as t2 WHERE t1.goods_id=t2.goods_id AND t1.weid = '{$_W['weid']} and t2.status != 'done' ORDER BY t2.createtime DESC";
            $list = pdo_fetchall($sql);
            $ar = pdo_fetchall($sql, array(), 'from_user');
            $fans = fans_search(array_keys($ar), array('realname', 'mobile', 'alipay'));
        }
        include $this -> template('request');
    }
    public function doMobileGoods(){
        global $_W, $_GPC;
        checkauth();
        $goods_list = pdo_fetchall("SELECT * FROM " . tablename($this -> table_goods) . " WHERE weid = '{$_W['weid']}' and NOW() < deadline and amount > 0 order by displayorder DESC, createtime");
        $fans = fans_search($_W['fans']['from_user']);
        $my_goods_list = pdo_fetch("SELECT * FROM " . tablename($this -> table_request) . " WHERE  from_user='{$_W['fans']['from_user']}' AND weid = '{$_W['weid']}'");
        include $this -> template('goods');
    }
    public function doMobileFillInfo(){
        global $_W, $_GPC;
        checkauth();
        $goods_id = intval($_GPC['goods_id']);
        $profile = fans_search($_W['fans']['from_user']);
        $goods_info = pdo_fetch("SELECT * FROM " . tablename($this -> table_goods) . " WHERE goods_id = $goods_id AND weid = '{$_W['weid']}'");
        include $this -> template('fillinfo');
    }
    public function doMobileExchange(){
        global $_W, $_GPC;
        checkauth();
        $goods_id = intval($_GPC['goods_id']);
        if (!empty($_GPC['goods_id'])){
            $fans = fans_search($_W['fans']['from_user']);
            $goods_info = pdo_fetch("SELECT * FROM " . tablename($this -> table_goods) . " WHERE goods_id = $goods_id AND weid = '{$_W['weid']}'");
            if ($goods_info['userchangecost'] == 0 and $goods_info['cost'] != floatval($_GPC['cost'])){
                message('金额不可修改', referer(), 'error');
            }
            $cost_limit = $goods_info['cost'];
            if ($goods_info['userchangecost'] == 1){
                $goods_info['cost'] = max(0, floatval($_GPC['cost']));
                if ($cost_limit > $_GPC['cost']){
                    message("对不起，每次兑换金额不得低于{$cost_limit}", referer(), 'error');
                }
            }
            if (intval($goods_info['vip_require']) > $fans['vip']){
                message('您的VIP级别不够，无法参与本项取现，试试其它的吧。', referer(), 'error');
            }
            $replicated = pdo_fetch("SELECT * FROM " . tablename($this -> table_request) . "  WHERE goods_id = $goods_id AND weid = '{$_W['weid']}' AND from_user = '{$_W['fans']['from_user']}' AND " . time() . " - createtime < 60 * 0");
            if (!empty($replicated)){
                $last_time = date('H:i:s', $replicated['createtime']);
                message("您在{$last_time}已经成功兑换【{$goods_info['title']}】。5分钟内不能重复兑换相同物品", $this -> createMobileUrl("MyRequest"), "success");
            }
            if ($goods_info['amount'] <= 0){
                message('商品已经兑空，请重新选择商品！', $this -> createMobileUrl('goods', array('weid' => $_W['weid'])), 'error');
            }
            if ($goods_info['per_user_limit'] > 0){
                $goods_limit = pdo_fetch("SELECT count(*) as per_user_limit FROM " . tablename($this -> table_request) . "  WHERE goods_id = $goods_id AND weid = '{$_W['weid']}' AND from_user = '{$_W['fans']['from_user']}'");
                if ($goods_limit['per_user_limit'] >= $goods_info['per_user_limit']){
                    message("本商品每个用户最多可兑换" . $goods_info['per_user_limit'] . "件，您已经达到最大限制，请重新选择商品！", $this -> createMobileUrl('goods', array('weid' => $_W['weid'])), 'error');
                }
            }
            if ($fans['credit2'] < $goods_info['cost']){
                message('积分不足, 请重新选择！<br>当前兑换所需积分:' . $goods_info['cost'] . '<br>您的余额:' . $fans['credit2'], $this -> createMobileUrl('goods', array('weid' => $_W['weid'])), 'error');
            }
            if (true){
                $data = array('amount' => $goods_info['amount'] - 1);
                pdo_update($this -> table_goods, $data, array('weid' => $_W['weid'], 'goods_id' => $goods_id));
                $data = array('realname' => ("" == $fans['realname'])?$_GPC['realname']:$fans['realname'], 'mobile' => ("" == $fans['mobile'])?$_GPC['mobile']:$fans['mobile'], 'alipay' => ("" == $fans['alipay'])?$_GPC['alipay']:$fans['alipay'],);
                fans_update($_W['fans']['from_user'], $data);
                $data = array('weid' => $_W['weid'], 'from_user' => $_W['fans']['from_user'], 'from_user_realname' => $fans['realname'], 'realname' => $_GPC['realname'], 'mobile' => $_GPC['mobile'], 'alipay' => $_GPC['alipay'], 'bankname' => $_GPC['bankname'], 'bankcard' => $_GPC['bankcard'], 'note' => $_GPC['note'], 'cost' => $goods_info['cost'], 'status' => 'new', 'exchangetype' => $goods_info['exchangetype'], 'goods_id' => $goods_id, 'createtime' => TIMESTAMP);
                pdo_insert($this -> table_request, $data);
                if ($goods_info['cost'] > $fans['credit2']){
                    message("系统出现未知错误，请重试或与管理员联系", "", "error");
                }
                yload() -> classs('quickcenter', 'fans');
                $_fans = new Fans();
                $_fans -> addCredit($_W['weid'], $_W['fans']['from_user'], 0 - $goods_info['cost'], 2, '移动端取现');
                message("取现申请提交成功！<br>消耗{$goods_info['cost']}分。", $this -> createMobileUrl('MyRequest', array('weid' => $_W['weid'], 'op' => 'display')), 'success');
            }
        }else{
            message('请选择要兑换的商品！', $this -> createMobileUrl('goods', array('weid' => $_W['weid'])), 'error');
        }
    }
    public function doMobileMyRequest(){
        global $_W, $_GPC;
        checkauth();
        $goods_list = pdo_fetchall("SELECT t1.title, t1.logo, t2.exchangetype, t2.* FROM " . tablename($this -> table_goods) . " as t1," . tablename($this -> table_request) . "as t2 WHERE t1.goods_id=t2.goods_id AND from_user='{$_W['fans']['from_user']}' AND t1.weid = '{$_W['weid']}' ORDER BY t2.createtime DESC");
        $fans = fans_search($_W['fans']['from_user']);
        include $this -> template('request');
    }
    public function doMobileDoneExchange(){
        global $_W, $_GPC;
        $data = array('status' => 'done');
        $id = intval($_GPC['id']);
        $row = pdo_fetch("SELECT id FROM " . tablename($this -> table_request) . " WHERE id = :id", array(':id' => $id));
        if (empty($row)){
            message('抱歉，编号为' . $id . '的兑换请求不存在或是已经被删除！');
        }
        pdo_update($this -> table_request, $data, array('id' => $id));
        message('兑换成功！！', referer(), 'success');
    }
    public function getCredit(){
        global $_W;
        $fans = fans_search($_W['fans']['from_user'], array('credit1'));
        return "<span  class='label label-success'>{$fans['credit1']}分</span>";
    }
    public function getCredit2(){
        global $_W;
        $fans = fans_search($_W['fans']['from_user'], array('credit2'));
        return "<span  class='label label-success'>" . number_format($fans['credit2'], 2) . "元</span>";
    }
    public function getExchangedMoney(){
        global $_W;
        $totalMoney = 0;
        if (!empty($_W['fans']['from_user'])){
            yload() -> classs('quickmoney', 'MoneyRequest');
            $_request = new MoneyRequest();
            $totalMoney = $_request -> getTotalExchanaged($_W['weid'], array('status' => 'done', 'from_user' => $_W['fans']['from_user']));
        }
        return "<span  class='label label-success'>" . number_format($totalMoney, 2) . "元</span>";
    }
    public function doWebDownload(){
        yload() -> routing('quickmoney', 'download');
    }
}
