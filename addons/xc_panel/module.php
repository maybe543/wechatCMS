<?php
defined('IN_IA') or exit('Access Denied');

class XC_PanelModule extends WeModule {

  public function settingsDisplay($settings) {
    global $_GPC, $_W;
    if(checksubmit()) {
      $m = str_replace(' ', '', $_GPC['modules']);
      $m = explode(',', $m);
      $m = array_unique($m);
      $cfg = array(
        'modules' => serialize($m),
      );
      if($this->saveSettings($cfg)) {
        message('保存成功', wurl('site/entry/panel', array('m'=>'xc_panel')), 'success');
      }
    }
    $settings['modules'] = implode(',', unserialize($settings['modules']));
    include $this->template('setting');
  }

}
