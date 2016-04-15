<?php

/**
 * 微预约
 *
 * @author dayu
 * @url QQ800083075
 */
defined('IN_IA') or exit('Access Denied');

class dayu_yuyuepayModule extends WeModule {

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay_reply') . " WHERE rid = :rid", array(':rid' => $rid));
            $sql = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . ' WHERE `weid`=:weid AND `reid`=:reid';
            $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':reid' => $reply['reid']));
        }
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        global $_GPC;
        $reid = intval($_GPC['activity']);
        if ($reid) {
            $sql = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . " WHERE `reid`=:reid";
            $params = array();
            $params[':reid'] = $reid;
            $activity = pdo_fetch($sql, $params);
            if (!empty($activity)) {
                return '';
            }
        }
        return '没有选择合适的预约';
    }

    public function fieldsFormSubmit($rid) {
        global $_GPC;
        $reid = intval($_GPC['activity']);
        $record = array();
        $record['reid'] = $reid;
        $record['rid'] = $rid;
        $reply = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay_reply') . " WHERE rid = :rid", array(':rid' => $rid));
        if ($reply) {
            pdo_update('dayu_yuyuepay_reply', $record, array('id' => $reply['id']));
        } else {
            pdo_insert('dayu_yuyuepay_reply', $record);
        }
    }

    public function ruleDeleted($rid) {
        pdo_delete('dayu_yuyuepay_reply', array('rid' => $rid));
    }

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if (checksubmit()) {
            $cfg = array(
                'noticeemail' => $_GPC['noticeemail'],
                'k_templateid' => $_GPC['k_templateid'],
                'kfid' => $_GPC['kfid'],
                'm_templateid' => $_GPC['m_templateid'],
                'mobile' => $_GPC['mobile'],
                'accountsid' => $_GPC['accountsid'],
                'tokenid' => $_GPC['tokenid'],
                'appId' => $_GPC['appId'],
                'templateId' => $_GPC['templateId'],
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('setting');
    }

}