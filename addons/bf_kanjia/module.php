<?php
defined('IN_IA') or exit('Access Denied');
class Bf_kanjiaModule extends WeModule
{
    public function __construct()
    {
        global $_W;
        include_once dirname(__FILE__) . '/libs.php';
        if ($_W["role"] == "operator") {
            message($i18n["competence_error"], "", "error");
            exit();
        }
    }
    public function fieldsFormDisplay($rid = 0)
    {
    }
    public function fieldsFormValidate($rid = 0)
    {
    }
    public function fieldsFormSubmit($rid)
    {
    }
    public function ruleDeleted($rid)
    {
    }
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        if (checksubmit()) {
            $this->saveSettings($data);
        }
        include $this->template('setting');
    }
}