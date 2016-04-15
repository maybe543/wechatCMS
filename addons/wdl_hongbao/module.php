<?php
defined('IN_IA') or exit('Access Denied');
class Wdl_hongbaoModule extends WeModule
{
    public function settingsDisplay_bf($settings)
    {
        global $_W, $_GPC;
        $data = $_GPC['data'];
        if (checksubmit()) {
            $flag = $this->saveSettings($data);
            if ($flag) {
                message("信息保存成功", "", "success");
            } else {
                message('信息保存失败', "", 'error');
            }
        }
        load()->func('tpl');
        include $this->template('setting');
        echo 'nihao';
    }
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        if (checksubmit()) {
            $dat           = array(
                'fhb_mchid' => $_GPC['fhb_mchid'],
                'fhb_appid' => $_GPC['fhb_appid'],
                'fhb_send_name' => $_GPC['fhb_send_name'],
                'fhb_nick_name' => $_GPC['fhb_nick_name'],
                'fhb_wishing' => $_GPC['fhb_wishing'],
                'fhb_remark' => $_GPC['fhb_remark'],
                'fhb_act_name' => $_GPC['fhb_act_name'],
                'fhb_send_type' => $_GPC['fhb_send_type'],
                'fhb_total_num' => 1,
                'fhb_send_key' => $_GPC['fhb_send_key']
            );
            $fhb_send_type = $_GPC['fhb_send_type'];
            if ($fhb_send_type == 'f') {
                $dat['fhb_send_money'] = round($_GPC['fhb_send_money'], 2) * 100;
                $dat['fhb_min_value']  = $dat['fhb_max_value'] = $dat['fhb_total_amount'] = $_GPC['fhb_send_money'];
            } else {
                $dat['fhb_send_money_from'] = $_GPC['fhb_send_money_from'];
                $dat['fhb_send_money_to']   = $_GPC['fhb_send_money_to'];
                $random_money               = rand(intval($dat['fhb_send_money_from']), intval($dat['fhb_send_money_to'])) * 100;
                $dat['fhb_min_value']       = $dat['fhb_max_value'] = $dat['fhb_total_amount'] = $random_money;
            }
            $this->saveSettings($dat);
            message('配置参数更新成功！', referer(), 'success');
        } else {
            $fhb_send_type = $this->module['config']['fhb_send_type'];
            $fhb_send_type = $fhb_send_type ? $fhb_send_type : 'f';
        }
        load()->func('tpl');
        include $this->template('setting');
    }
}