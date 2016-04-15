<?php
/**
 * 人人赚管理面板
 */
defined('IN_IA') or exit('Access Denied');


include_once IA_ROOT . '/addons/xc_panel/define.php';

class XC_PanelModuleSite extends WeModuleSite {
  private function famap($key) {
    $fa = array(
      '商品管理'=>'fa fa-gift',
      '商品分类'=>'fa fa-cubes',
      '订单管理'=>'fa fa-cc-visa',
      '物流模板'=>'fa fa-truck',
      '业绩报表'=>'fa fa-file-text',
      '幻灯片管理'=>'fa fa-picture-o',

      '传单管理'=>'fa fa-qrcode',
      '黑名单'=>'fa fa-lock',
      '排行榜'=>'fa fa-trophy',

      '分销管理' => 'fa fa-joomla',

      '订单管理'=>'fa fa-shopping-cart',
      '返利记录'=>'fa fa-calculator',
      '消息通知'=>'fa fa-volume-up',
      '用户中心'=>'fa fa-user',
      '取现请求管理'=>'fa fa-pencil-square-o',
      '取现模板管理'=>'fa fa-file-text',
      '兑换请求管理'=>'fa fa-pencil-square-o',
      '兑换模板管理'=>'fa fa-file-text',
      '文章管理'=>'fa fa-pencil',
      '文章分类'=>'fa fa-list',
      '分享点击记录'=>'fa fa-weixin',
      '帮助'=>'fa fa-question',
      '会员积分管理'=>'fa fa-users',
      '粉丝管理'=>'fa fa-user-plus',
      '消息群发'=>'fa fa-paper-plane',
      '粉丝自动同步'=>'fa fa-jsfiddle',
    );
    if (isset($fa[$key])) {
      return $fa[$key];
    }
    return 'fa fa-puzzle-piece';
  }

  public function doWebPanel() {
    $modules = array('quickshop', 'quicklink', 'quickdist', 'quickmoney', 'quickcredit', 'quickfans', 'xc_article');
    $u_modules = unserialize($this->module['config']['modules']);
    if (is_array($u_modules)) {
      $modules = array_unique(array_merge($modules, $u_modules));
    }

    load()->model('module');
	  $installedmodulelist = uni_modules(false);
    foreach ($modules as $m) {
		  $entries[$m] = module_entries($m, array('menu')); //, 'home', 'profile', 'shortcut', 'cover'));
    }
    foreach ($modules as $m) {
      $titles[$m] = $installedmodulelist[$m]['title'];
    }
    // sys entry
    $sys_entry['title'] = '常用工具';
    $sys_entry['menu'] = array(
      array('title'=>'会员积分管理', 'url'=>wurl('mc/creditmanage', array('type'=>3))),
      array('title'=>'粉丝管理', 'url'=>wurl('mc/fans' , array('acid'=>$_W['acid'], 'nickname'=>''))),
      array('title'=>'消息群发', 'url'=>wurl('mc/mass')),
      array('title'=>'粉丝自动同步', 'url'=>wurl('mc/passport/sync')),
    );
    //default entry when renrenzhuan not installed
    $default_entry['title'] = '分销';
    $default_entry['menu'] = array(
      array('title'=>'人人赚分销', 'url'=>'http://www.30pu.net/')
    );
    include $this->template('panel');
  }
}
