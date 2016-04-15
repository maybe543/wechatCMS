<?php
 defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/quickdynamics/define.php';
require_once MODULE_ROOT . '/quickcenter/loader.php';
class QuickDynamicsModule extends WeModule{
    public function fieldsFormDisplay($rid = 0){
    }
    public function fieldsFormValidate($rid = 0){
    }
    public function fieldsFormSubmit($rid){
        global $_GPC;
    }
    public function ruleDeleted($rid){
    }
    public function settingsDisplay($settings){
        global $_GPC, $_W;
        if (checksubmit()){
            $cfg = array();
            if ($this -> saveSettings($cfg)){
                message('保存成功', 'refresh');
            }
        }
        include $this -> template('setting');
    }
}
