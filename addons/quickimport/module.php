<?php
/**
 */
defined('IN_IA') or exit('Access Denied');

class QuickImportModule extends WeModule {
     public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if (checksubmit('submit')) {
            $cfg = array(
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('setting');
    }

}
