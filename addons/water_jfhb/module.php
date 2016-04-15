<?php
defined('IN_IA') or exit('Access Denied');
define('MB_ROOT', IA_ROOT . '/addons/cgc_gzredbag');
class Water_jfhbModule extends WeModule
{
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        load()->func('tpl');
        if (checksubmit()) {
            load()->func('file');
            $_W['uploadsetting']                            = array();
            $_W['uploadsetting']['image']['folder']         = 'images/' . $_W['uniacid'];
            $_W['setting']['upload']['image']['extentions'] = array_merge($_W['setting']['upload']['image']['extentions'], array(
                "pem"
            ));
            $_W['uploadsetting']['image']['limit']          = $_W['config']['upload']['image']['limit'];
            if (!empty($_FILES['apiclient_cert_file']['name'])) {
                $file = file_upload($_FILES['apiclient_cert_file']);
                if (is_error($file)) {
                    message('apiclient_cert证书保存失败, 请保证目录可写' . $file['message']);
                } else {
                    $_GPC['apiclient_cert'] = empty($file['path']) ? trim($_GPC['apiclient_cert']) : ATTACHMENT_ROOT . '/' . $file['path'];
                }
            }
            if (!empty($_FILES['apiclient_key_file']['name'])) {
                $file = file_upload($_FILES['apiclient_key_file']);
                if (is_error($file)) {
                    message('apiclient_key证书保存失败, 请保证目录可写' . $file['message']);
                } else {
                    $_GPC['apiclient_key'] = empty($file['path']) ? trim($_GPC['apiclient_key']) : ATTACHMENT_ROOT . '/' . $file['path'];
                }
            }
            if (!empty($_FILES['rootca_file']['name'])) {
                $file = file_upload($_FILES['rootca_file']);
                if (is_error($file)) {
                    message('rootca证书保存失败, 请保证目录可写' . $file['message']);
                } else {
                    $_GPC['rootca'] = empty($file['path']) ? trim($_GPC['rootca']) : ATTACHMENT_ROOT . '/' . $file['path'];
                }
            }
            $input                     = array();
            $input['apiclient_cert']   = trim($_GPC['apiclient_cert']);
            $input['apiclient_key']    = trim($_GPC['apiclient_key']);
            $input['rootca']           = trim($_GPC['rootca']);
            $input['total_money']      = trim($_GPC['total_money']);
            $input['starttime']        = trim($_GPC['starttime']);
            $input['endtime']          = trim($_GPC['endtime']);
            $input['appid']            = trim($_GPC['appid']);
            $input['secret']           = trim($_GPC['secret']);
            $input['mchid']            = trim($_GPC['mchid']);
            $input['password']         = trim($_GPC['password']);
            $input['ip']               = trim($_GPC['ip']);
            $input['min_money']        = trim($_GPC['min_money']);
            $input['min_money1']       = trim($_GPC['min_money1']);
            $input['max_money']        = trim($_GPC['max_money']);
            $input['sendtype']         = trim($_GPC['sendtype']);
            $input['act_name']         = trim($_GPC['act_name']);
            $input['send_name']        = trim($_GPC['send_name']);
            $input['remark']           = trim($_GPC['remark']);
            $input['gz_min_amount']    = trim($_GPC['gz_min_amount']);
            $input['tx_money']         = trim($_GPC['tx_money']);
            $input['addr']             = trim($_GPC['addr']);
            $input['tx_type']          = trim($_GPC['tx_type']);
            $input['zdyurl']           = trim($_GPC['zdyurl']);
            $input['iplimit']          = trim($_GPC['iplimit']);
            $input['locationtype']     = trim($_GPC['locationtype']);
            $input['debug']            = trim($_GPC['debug']);
            $input['gz_note']          = trim($_GPC['gz_note']);
            $input['yaoqing_note']     = trim($_GPC['yaoqing_note']);
            $input['person_max_money'] = trim($_GPC['person_max_money']);
            $input['max_money']        = trim($_GPC['max_money']);
            $input['start_hour']       = trim($_GPC['start_hour']);
            $input['end_hour']         = trim($_GPC['end_hour']);
            $input['jfsc_url']         = trim($_GPC['jfsc_url']);
            $input['show_money']       = trim($_GPC['show_money']);
            $input['qx_guanzhu']       = trim($_GPC['qx_guanzhu']);
            $input['sex']              = trim($_GPC['sex']);
            if ($this->saveSettings($input)) {
                message('保存参数成功', 'refresh');
            }
        }
        if (empty($settings['ip'])) {
            $settings['ip'] = $_SERVER['SERVER_ADDR'];
        }
        include $this->template('setting');
    }
}