<?php
 defined('IN_IA') or exit('Access Denied');
include 'define.php';
require_once(IA_ROOT . '/addons/quickcenter/loader.php');
class QuickCreditModuleSite extends WeModuleSite{
    public $table_request = "quickcredit_request";
    public $table_goods = "quickcredit_goods";
    private $creditname = '积分';
    function __construct(){
        $creditnames = uni_setting($_W['uniacid'], array('creditnames'));
        if (!empty($creditnames['creditnames']['credit1']['title'])){
            $this -> creditname = $creditnames['creditnames']['credit1']['title'];
        }
    }
    public function doWebGoods(){
        global $_W;
        global $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
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
                    message('请输入兑换商品需要消耗的积分数量！');
                }
                if (empty($_GPC['price'])){
                    message('请输入商品实际价值！');
                }
                $cost = intval($_GPC['cost']);
                $price = intval($_GPC['price']);
                $vip_require = intval($_GPC['vip_require']);
                $amount = intval($_GPC['amount']);
                $type = intval($_GPC['type']);
                $per_user_limit = intval($_GPC['per_user_limit']);
                $data = array('weid' => $_W['weid'], 'title' => $_GPC['title'], 'logo' => $_GPC['logo'], 'timestart' => strtotime($_GPC['timestart']), 'timeend' => strtotime($_GPC['timeend']), 'deadline' => $_GPC['deadline'], 'amount' => $amount, 'min_idle_time' => intval($_GPC['min_idle_time']), 'per_user_limit' => intval($per_user_limit), 'vip_require' => $vip_require, 'cost' => $cost, 'price' => $price, 'type' => $type, 'content' => $_GPC['content'], 'createtime' => TIMESTAMP,);
                if (!empty($goods_id)){
                    pdo_update($this -> table_goods, $data, array('goods_id' => $goods_id));
                }else{
                    pdo_insert($this -> table_goods, $data);
                }
                message('商品更新成功！', $this -> createWebUrl('goods', array('op' => 'display')), 'success');
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
            $list = pdo_fetchall("SELECT * FROM " . tablename($this -> table_goods) . " WHERE weid = '{$_W['weid']}' $condition ORDER BY displayorder DESC, createtime DESC");
        }
        include $this -> template('goods');
    }
    public function doWebRequest(){
        global $_W, $_GPC;
        load() -> func('tpl');
        yload() -> classs('quickcenter', 'FormTpl');
        yload() -> classs('quickcredit', 'CreditRequest');
        $_request = new CreditRequest();
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
            if (checksubmit('batchsend')){
                foreach ($_GPC['id'] as $id){
                    $data = array('status' => 'done');
                    $row = pdo_fetch("SELECT id FROM " . tablename($this -> table_request) . " WHERE id = :id", array(':id' => $id));
                    if (empty($row)){
                        continue;
                    }
                    pdo_update($this -> table_request, $data, array('id' => $id));
                }
                message('批量兑换成功. 兑换成功的请求已移入‘已兑换请求’栏！', referer(), 'success');
            }
            $condition = '';
            if (!empty($_GPC['search'])){
                $kw = $_GPC['keyword'];
                $condition .= "  AND (t1.from_user like '%" . $kw . "%' OR  t1.mobile like '%" . $kw . "%' OR t1.realname like '%" . $kw . "%' OR t1.residedist like '%" . $kw . "%') ";
            }
            $sql = "SELECT t1.*,t2.title FROM " . tablename($this -> table_request) . "as t1 LEFT JOIN " . tablename($this -> table_goods) . " as t2 " . " ON  t2.goods_id=t1.goods_id AND t2.weid=t1.weid AND t2.weid='{$_W['weid']}' WHERE t1.weid = '{$_W['weid']}' AND t1.status != 'done' " . $condition . " ORDER BY t1.createtime DESC";
            $list = pdo_fetchall($sql);
            $ar = pdo_fetchall($sql, array());
            $fanskey = array();
            foreach ($ar as $v){
                $fanskey[$v['from_user']] = 1;
            }
            $fans = fans_search(array_keys($fanskey), array('realname', 'mobile', 'residedist', 'alipay'));
            load() -> model('mc');
        }else if ($operation == 'display_done'){
            $condition = '';
            if (!empty($_GPC['search'])){
                $kw = $_GPC['keyword'];
                $condition .= "  AND (t1.from_user like '%" . $kw . "%' OR  t1.mobile like '%" . $kw . "%' OR t1.realname like '%" . $kw . "%' OR t1.residedist like '%" . $kw . "%') ";
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 100;
            $sql = "SELECT t1.*, t2.title FROM " . tablename($this -> table_request) . "as t1 LEFT JOIN " . tablename($this -> table_goods) . " as t2 " . " ON t2.goods_id=t1.goods_id AND t2.weid = t1.weid AND t2.weid = {$_W['weid']} WHERE t1.weid='{$_W['weid']}' AND t1.status = 'done' " . $condition . " ORDER BY t1.createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
            $total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this -> table_request) . "as t1 LEFT JOIN " . tablename($this -> table_goods) . " as t2 " . " ON t2.goods_id=t1.goods_id AND t2.weid = t1.weid AND t2.weid = {$_W['weid']} WHERE t1.weid='{$_W['weid']}' AND t1.status = 'done' " . $condition);
            $list = pdo_fetchall($sql);
            $ar = pdo_fetchall($sql, array());
            $fanskey = array();
            foreach ($ar as $v){
                $fanskey[$v['from_user']] = 1;
            }
            $fans = fans_search(array_keys($fanskey), array('realname', 'mobile', 'residedist', 'alipay'));
            $pager = pagination($total, $pindex, $psize);
        }else{
            $sql = "SELECT t1.*, t2.title FROM " . tablename($this -> table_request) . "as t1 LEFT  JOIN " . tablename($this -> table_goods) . " as t2 " . " ON t2.goods_id=t1.goods_id AND t1.weid=t2.weid AND t2.weid = '{$_W['weid']} WHERE t1.weid='{$_W['weid']}' AND t1.status != 'done' ORDER BY t1.createtime DESC";
            $list = pdo_fetchall($sql);
            $ar = pdo_fetchall($sql, array());
            $fanskey = array();
            foreach ($ar as $v){
                $fanskey[$v['from_user']] = 1;
            }
            $fans = fans_search(array_keys($fanskey), array('realname', 'mobile', 'residedist', 'alipay'));
        }
        include $this -> template('request');
    }
    public function doMobileGoods(){
        global $_W, $_GPC;
        checkauth();
        $goods_list = pdo_fetchall("SELECT * FROM " . tablename($this -> table_goods) . " WHERE weid = '{$_W['weid']}' and NOW() < deadline and amount >= 0 order by displayorder DESC, createtime");
        $fans = fans_search($_W['fans']['from_user'], array('realname', 'mobile', 'residedist', 'alipay', 'credit1', 'credit2', 'vip'));
        $my_goods_list = pdo_fetch("SELECT * FROM " . tablename($this -> table_request) . " WHERE  from_user='{$_W['fans']['from_user']}' AND weid = '{$_W['weid']}'");
        include $this -> template('goods');
    }
    public function doMobileFillInfo(){
        global $_W, $_GPC;
        checkauth();
        $goods_id = intval($_GPC['goods_id']);
        $profile = fans_search($_W['fans']['from_user']);
        $goods_info = pdo_fetch("SELECT * FROM " . tablename($this -> table_goods) . " WHERE goods_id = $goods_id AND weid = '{$_W['weid']}'");
        if ($goods_info['amount'] <= 0){
            message('手太慢, 已经兑换一空', referer(), 'error');
        }
        include $this -> template('fillinfo');
    }
    public function doMobileExchange(){
        global $_W, $_GPC;
        checkauth();
        $goods_id = intval($_GPC['goods_id']);
        if (!empty($_GPC['goods_id'])){
            $fans = fans_search($_W['fans']['from_user'], array('realname', 'mobile', 'residedist', 'alipay', 'credit1', 'credit2', 'vip'));
            $goods_info = pdo_fetch("SELECT * FROM " . tablename($this -> table_goods) . " WHERE goods_id = $goods_id AND weid = '{$_W['weid']}'");
            if ($goods_info['amount'] <= 0){
                message('商品已经兑空，请重新选择商品！', $this -> createMobileUrl('goods', array('weid' => $_W['weid'])), 'error');
            }
            if (intval($goods_info['vip_require']) > $fans['vip']){
                message('您的VIP级别不够，无法参与本项兑换，试试其它的吧。', referer(), 'error');
            }
            $min_idle_time = empty($goods_info['min_idle_time']) ? 0 : $goods_info['min_idle_time'] * 60;
            $replicated = pdo_fetch("SELECT * FROM " . tablename($this -> table_request) . "  WHERE goods_id = $goods_id AND weid = '{$_W['weid']}' AND from_user = '{$_W['fans']['from_user']}' AND " . TIMESTAMP . " - createtime < {$min_idle_time}");
            if (!empty($replicated)){
                $last_time = date('H:i:s', $replicated['createtime']);
                $idle_hour = message("您在{$last_time}已经成功兑换【{$goods_info['title']}】。{$goods_info['min_idle_time']}分钟内不能重复兑换相同物品", $this -> createMobileUrl("MyRequest"), "error");
            }
            if ($goods_info['per_user_limit'] > 0){
                $goods_limit = pdo_fetch("SELECT count(*) as per_user_limit FROM " . tablename($this -> table_request) . "  WHERE goods_id = $goods_id AND weid = '{$_W['weid']}' AND from_user = '{$_W['fans']['from_user']}'");
                if ($goods_limit['per_user_limit'] >= $goods_info['per_user_limit']){
                    message("本商品每个用户最多可兑换" . $goods_info['per_user_limit'] . "件，您已经达到最大限制，请重新选择商品！", $this -> createMobileUrl('goods', array('weid' => $_W['weid'])), 'error');
                }
            }
            if ($fans['credit1'] < $goods_info['cost']){
                message('积分不足, 请重新选择商品！<br>当前商品所需积分:' . $goods_info['cost'] . '<br>您的积分:' . $fans['credit1'] . '<br><br>小提示：<br>参与我们的活动，可以赚取积分哦', $this -> createMobileUrl('goods', array('weid' => $_W['weid'])), 'error');
            }
            if (true){
                $data = array('amount' => $goods_info['amount'] - 1);
                pdo_update($this -> table_goods, $data, array('weid' => $_W['weid'], 'goods_id' => $goods_id));
                $data = array('realname' => ("" == $fans['realname'])?$_GPC['realname']:$fans['realname'], 'mobile' => ("" == $fans['mobile'])?$_GPC['mobile']:$fans['mobile'], 'residedist' => ("" == $fans['residedist'])?$_GPC['residedist']:$fans['residedist'], 'alipay' => ("" == $fans['alipay'])?$_GPC['alipay']:$fans['alipay'],);
                fans_update($_W['fans']['from_user'], $data);
                $data = array('weid' => $_W['weid'], 'from_user' => $_W['fans']['from_user'], 'from_user_realname' => $fans['realname'], 'realname' => $_GPC['realname'], 'mobile' => $_GPC['mobile'], 'residedist' => $_GPC['residedist'], 'alipay' => $_GPC['alipay'], 'note' => $_GPC['note'], 'goods_id' => $goods_id, 'price' => $goods_info['price'], 'cost' => $goods_info['cost'], 'createtime' => TIMESTAMP);
                if ($goods_info['cost'] > $fans['credit1']){
                    message("系统出现未知错误，请重试或与管理员联系", "", "error");
                }
                pdo_insert($this -> table_request, $data);
                yload() -> classs('quickcenter', 'fans');
                $_fans = new Fans();
                $_fans -> addCredit($_W['weid'], $_W['fans']['from_user'], 0 - $goods_info['cost'], 1, '商品【' . $goods_info['title'] . '】积分兑换扣减');
                message("积分兑换成功！从系统积分中扣除{$goods_info['cost']}分。", $this -> createMobileUrl('MyRequest', array('weid' => $_W['weid'], 'op' => 'display')), 'success');
            }
        }else{
            message('请选择要兑换的商品！', $this -> createMobileUrl('goods', array('weid' => $_W['weid'])), 'error');
        }
    }
    public function doMobileMyRequest(){
        global $_W, $_GPC;
        checkauth();
        $goods_list = pdo_fetchall("SELECT * FROM " . tablename($this -> table_goods) . " as t1," . tablename($this -> table_request) . "as t2 WHERE t1.goods_id=t2.goods_id AND from_user='{$_W['fans']['from_user']}' AND t1.weid = '{$_W['weid']}' ORDER BY t2.createtime DESC");
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
        return "<span  class='label label-success'>{$fans['credit2']}元</span>";
    }
    public function getExchangedCredit(){
        global $_W;
        $totalCredit = 0;
        if (!empty($_W['fans']['from_user'])){
            yload() -> classs('quickcredit', 'CreditRequest');
            $_request = new CreditRequest();
            $totalCredit = $_request -> getTotalExchanaged($_W['weid'], array('status' => 'done', 'from_user' => $_W['fans']['from_user']));
        }
        return "<span  class='label label-success'>{$totalCredit}分</span>";
    }
    public function doWebDownload(){
        yload() -> routing('quickcredit', 'download');
    }
}
