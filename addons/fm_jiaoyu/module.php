<?php
/**
 * 微教育模块定义
 *
 * @author 高贵血迹
 * @url http://bbs.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class Fm_jiaoyuModule extends WeModule {

    public $name = 'Fm_jiaoyubuilding';
    public $title = '微教育';
    public $ability = '';
    public $table_reply = 'wx_school_reply';
    public $table_index = 'wx_school_index';

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
            $sql = 'SELECT * FROM ' . tablename($this->table_index) . ' WHERE `weid`=:weid AND `id`=:id';
            $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':id' => $reply['schoolid']));
            $showpicurl = $this->getpicurl($activity['thumb']);
        }
//	    load ()->func ( 'tpl' );	
        include $this->template('web/form');
    }

    public function fieldsFormValidate($rid = 0) {
        global $_W, $_GPC;
        $schoolid = intval($_GPC['activity']);
        if (!empty($schoolid)) {
            $sql = 'SELECT * FROM ' . tablename($this->table_index) . " WHERE `id`=:schoolid";
            $params = array();
            $params[':schoolid'] = $schoolid;
            $activity = pdo_fetch($sql, $params);
            return;
            if (!empty($activity)) {
                return '';
            }
        }
        return '没有选择分校';
    }

    private function getpicurl($url) {
        global $_W;
        if ($url) {
            return tomedia($url);
        } else {
            return MODULE_URL . 'public/mobile/img/1.jpg';
        }
    }

    public function fieldsFormSubmit($rid) {
        global $_GPC;
        $schoolid = intval($_GPC['activity']);
        $record = array();
        $record['schoolid'] = $schoolid;
        $record['rid'] = $rid;
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
        if ($reply) {
            pdo_update($this->table_reply, $record, array('id' => $reply['id']));
        } else {
            pdo_insert($this->table_reply, $record);
        }
    }

    public function ruleDeleted($rid) {
        pdo_delete($this->table_reply, array('rid' => $rid));
    }

}