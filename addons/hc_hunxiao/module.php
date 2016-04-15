<?php
/*
 * 分销自定义模块
 */

defined('IN_IA') or exit('Access Denied');
class hc_hunxiaoModule extends WeModule {

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
		$setting = $_W['account']['modules'][$this->_saveing_params['mid']]['config'];
		//include $this->template('rule');
    }

    public function fieldsFormSubmit($rid = 0) {
        global $_GPC, $_W;
        if (!empty($_GPC['title'])) {
            $data = array(
                'title' => $_GPC['title'],
                'description' => $_GPC['description'],
                'picurl' => $_GPC['thumb-old'],
                'url' => create_url('mobile/module/list', array('name' => 'hc_hunxiao', 'weid' => $_W['weid'])),
            );
            if (!empty($_GPC['thumb'])) {
                $data['picurl'] = $_GPC['thumb'];
               // file_delete($_GPC['thumb-old']);
            }
            $this->saveSettings($data);
        }
        return true;
    }

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
		load()->func('tpl');
        if (checksubmit()) {
            $cfg = array(
				'noticeemail' => $_GPC['noticeemail'],
                'guanzhuurl' => $_GPC['guanzhuurl'],
                'shopname' => $_GPC['shopname'],
                'yindaotitle' => $_GPC['yindaotitle'],
                'address' => $_GPC['address'],
                'phone' => $_GPC['phone'],
                'logo' => $_GPC['logo'],
                'shouye' => $_GPC['shouye'],
                'officialweb' => $_GPC['officialweb'],
                'description'=> htmlspecialchars_decode($_GPC['description'])
            );
            
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('setting');
    }

}
