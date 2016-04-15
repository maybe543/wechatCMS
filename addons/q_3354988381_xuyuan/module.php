<?php
<?php
defined('IN_IA') or exit('Access Denied');
class q_3354988381_xuyuanModule extends WeModule
{
    public $tablename = 'dream_reply';
    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
        load()->func('tpl');
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
        }
        if (!$reply) {
            $now   = time();
            $reply = array(
                "title" => "为梦想举杯!",
                "picurl" => "",
                "starttime" => $now,
                "endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
                "share_title" => "为梦想举杯",
                "share_content" => "为梦想迈出的每一步,都值得庆祝。而中国农历新年,是我们放飞梦想,为梦想举杯的最佳时刻。"
            );
        }
        include $this->template('form');
    }
    public function fieldsFormValidate($rid = 0)
    {
        return '';
    }
    public function fieldsFormSubmit($rid)
    {
        global $_GPC, $_W;
        $id     = intval($_GPC['reply_id']);
        $insert = array(
            'rid' => $rid,
            'weid' => $_W['weid'],
            'title' => $_GPC['title'],
            'picurl' => $_GPC['picurl'],
            'starttime' => strtotime($_GPC['datelimit']['start']),
            'endtime' => strtotime($_GPC['datelimit']['end']),
            'share_title' => $_GPC['share_title'],
            'share_content' => $_GPC['share_content'],
            'logo' => $_GPC['logo'],
            'gzurl' => $_GPC['gzurl'],
            'slogans' => $_GPC['slogans']
        );
        if (empty($id)) {
            if ($insert['starttime'] <= time()) {
                $insert['isshow'] = 1;
            } else {
                $insert['isshow'] = 0;
            }
            $id = pdo_insert($this->tablename, $insert);
        } else {
            pdo_update($this->tablename, $insert, array(
                'id' => $id
            ));
        }
        return true;
    }
    public function ruleDeleted($rid)
    {
        if (pdo_tableexists('dream_wish')) {
            pdo_delete('dream_wish', array(
                'rid' => $rid
            ));
        }
        if (pdo_tableexists('dream_reply')) {
            pdo_delete('dream_reply', array(
                'rid' => $rid
            ));
        }
    }
}