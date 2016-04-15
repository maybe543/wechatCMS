<?php
 defined('IN_IA') or exit('Access Denied');
include 'define.php';
require_once(IA_ROOT . '/addons/quickcenter/loader.php');
class QuickShopModule extends WeModule{
    public function settingsDisplay($settings){
        global $_GPC, $_W;
        if (checksubmit()){
            $cfg = array('vip_buy_guide_link' => $_GPC['vip_buy_guide_link'], 'sellername' => $_GPC['sellername'], 'key' => $_GPC['key'], 'secret' => $_GPC['secret'], 'getnick' => $_GPC['getnick'], 'enable_order_remark' => intval($_GPC['enable_order_remark']), 'enable_top_user' => $_GPC['enable_top_user'], 'followurl' => $_GPC['followurl'], 'noticeemail' => $_GPC['noticeemail'], 'shopname' => $_GPC['shopname'], 'address' => $_GPC['address'], 'phone' => $_GPC['phone'], 'officialweb' => $_GPC['officialweb'], 'enable_inshop_mode' => intval($_GPC['enable_inshop_mode']), 'inshop_banner' => $_GPC['inshop_banner'], 'inshop_banner_href' => $_GPC['inshop_banner_href'], 'inshop_logo' => $_GPC['inshop_logo'], 'inshop_logo_href' => $_GPC['inshop_logo_href'], 'inshop_color' => $_GPC['inshop_color'], 'inshop_share_text' => $_GPC['inshop_share_text'], 'logo' => $_GPC['logo'], 'description' => htmlspecialchars_decode($_GPC['description']), 'enable_single_goods_id' => intval($_GPC['enable_single_goods_id']), 'enable_user_remove_order' => intval($_GPC['enable_user_remove_order']), 'require_follow_first' => intval($_GPC['require_follow_first']), 'payed_template_id' => $_GPC['payed_template_id'], 'default_province' => empty($_GPC['default_province']) ? '北京市' : trim($_GPC['default_province']), 'default_city' => empty($_GPC['default_city']) ? '北京辖区' : trim($_GPC['default_city']), 'default_area' => empty($_GPC['default_area']) ? '' : trim($_GPC['default_area']),);
            if (isset($_GPC['template'])){
                $cfg['template'] = trim($_GPC['template']);
            }else{
                $cfg['template'] = trim($settings['template']);
            }
            if ($this -> saveSettings($cfg)){
                message('保存成功', 'refresh');
            }
        }
        yload() -> classs('quickcenter', 'FormTpl');
        yload() -> classs('quickcenter', 'dirscanner');
        $_scanner = new DirScanner();
        $dirs = $_scanner -> scan('quicktemplate/quickshop');
        $template_items = array();
        foreach($dirs as $dir){
            $template_items[$dir] = $dir;
        }
        yload() -> classs('quickshop', 'goods');
        $_goods = new Goods();
        list($list, $total) = $_goods -> batchGet($_W['weid']);
        $shop_items = array('0' => '不启用');
        foreach($list as $item){
            $shop_items[$item['id']] = $item['title'];
        }
        $remove_order_option = array('0' => '【不允许】用户删除未支付订单', '1' => '【允许】用户删除未支付订单');
        $order_remark_option = array('0' => '【不允许】用户下单时添加备注', '1' => '【允许】用户下单时添加备注');
        $inshop_mode_option = array('0' => '【不开启】', '1' => '【开启】');
        if (empty($settings['sellername'])){
            $settings['sellername'] = '东家';
        }
        include $this -> template('setting');
    }
}
