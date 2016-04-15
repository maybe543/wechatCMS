<?php
defined('IN_IA') or exit('Access Denied');
class Yg_astyModule extends WeModule
{
    public $table_reply = 'yg_asty_reply';
    public $table_oauth = 'yg_asty_oauth';
    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
        if ($rid == 0) {
            $reply = array(
                'title' => '中秋抢月饼!',
                'description' => '中秋抢月饼，送大奖！',
                'starttime' => time(),
                'endtime' => time() + 10 * 84400,
                'status' => 1,
                'indexbg' => MODULE_URL . 'template/mobile/images/0begin.png'
            );
        } else {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
        }
        include $this->template('form');
    }
    public function fieldsFormValidate($rid = 0)
    {
        return '';
    }
    public function fieldsFormSubmit($rid)
    {
        global $_W, $_GPC;
        $id     = intval($_GPC['reply_id']);
        $i      = 1;
        $insert = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'title' => $_GPC['title'],
            'thumb' => $_GPC['thumb'],
            'description' => $_GPC['description'],
            'starttime' => strtotime($_GPC['time'][start]),
            'endtime' => strtotime($_GPC['time'][end]),
            'status' => intval($_GPC['status']),
            'indexbg' => $_GPC['indexbg'],
            'single_0' => $_GPC['single_0'],
            'single_1' => $_GPC['single_1'],
            'single_2' => $_GPC['single_2'],
            'single_3' => $_GPC['single_3'],
            'lovers_0' => $_GPC['lovers_0'],
            'lovers_1' => $_GPC['lovers_1'],
            'lovers_2' => $_GPC['lovers_2'],
            'lovers_3' => $_GPC['lovers_3'],
            'follelink' => $_GPC['follelink'],
            'blood' => $_GPC['blood'],
            'pnum' => $_GPC['pnum'],
            'addnum' => $_GPC['addnum'],
            'gametime' => $_GPC['gametime'],
            'leftpic' => $_GPC['leftpic'],
            'rightpic' => $_GPC['rightpic'],
            'turng' => $_GPC['turng'],
            'sharepic' => $_GPC['sharepic'],
            'sharedesc' => $_GPC['sharedesc'],
            'sharetitle' => $_GPC['sharetitle'],
            'forward' => $_GPC['forward'],
            'createtime' => TIMESTAMP
        );
        if (empty($id)) {
            pdo_insert($this->table_reply, $insert);
        } else {
            unset($insert['createtime']);
            pdo_update($this->table_reply, $insert, array(
                'id' => $id
            ));
        }
    }
    public function ruleDeleted($rid)
    {
        $replies  = pdo_fetchall("SELECT id  FROM " . tablename($this->table_reply) . " WHERE rid = '$rid'");
        $deleteid = array();
        if (!empty($replies)) {
            foreach ($replies as $index => $row) {
                $deleteid[] = $row['id'];
            }
        }
        pdo_delete($this->table_reply, "id IN ('" . implode("','", $deleteid) . "')");
    }
    public function settingsDisplay($settings)
    {
        global $_GPC, $_W;
        if (checksubmit()) {
            $cfg           = array();
            $cfg['appid']  = $_GPC['appid'];
            $cfg['secret'] = $_GPC['secret'];
            if ($this->saveSettings($cfg)) {
                $insert = array(
                    'weid' => $_W['weid'],
                    'appid' => $cfg['appid'],
                    'secret' => $cfg['secret']
                );
                $result = pdo_fetch("select * from " . tablename($this->table_oauth) . " where 1=1 and weid={$_W['weid']}");
                if (empty($result)) {
                    pdo_insert($this->table_oauth, $insert);
                } else {
                    pdo_update($this->table_oauth, $insert, array(
                        'id' => $result['id']
                    ));
                }
                message('保存成功', 'refresh');
            }
        }
        $config = '已授权';
        include $this->template('setting');
    }
}