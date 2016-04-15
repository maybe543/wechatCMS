<?php
defined('IN_IA') or exit('Access Denied');
class Wr_printerModule extends WeModule
{
    public $tablename = 'wr_printer';
    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
        }
        load()->func('tpl');
        include $this->template('form');
    }
    public function fieldsFormValidate($rid = 0)
    {
        return true;
    }
    public function fieldsFormSubmit($rid)
    {
        global $_GPC, $_W;
        load()->func('file');
        $id = intval($_GPC['reply_id']);
        empty($_GPC['msg']) ? $_GPC['msg'] = '欢迎进入微拍活动！' : '';
        empty($_GPC['msg_succ']) ? $_GPC['msg_succ'] = '参与活动成功。' : '';
        empty($_GPC['msg_fail']) ? $_GPC['msg_fail'] = '提交失败，请重试。' : '';
        if (empty($_GPC['timer'])) {
            $_GPC['timer'] = '15';
        }
        if (($_GPC['adtimed'] > 1000) || ($_GPC['adtimed'] < 10)) {
            $adtimed = 10;
        } else {
            $adtimed = $_GPC['adtimed'];
        }
        if (($_GPC['adtime1'] > 1000) || ($_GPC['adtime1'] < 10)) {
            $adtime1 = 10;
        } else {
            $adtime1 = $_GPC['adtime1'];
        }
        $insert = array(
            'rid' => $rid,
            'weid' => $_W['weid'],
            'maxnum' => $_GPC['maxnum'],
            'dcmaxnum' => $_GPC['dcmaxnum'],
            'authcode' => '888',
            'photo_ad' => $_GPC['photo_ad'],
            'msg' => $_GPC['msg'],
            'msg_succ' => $_GPC['msg_succ'],
            'msg_fail' => $_GPC['msg_fail'],
            'status' => intval($_GPC['wpstatus']),
            'is_guestbook' => intval($_GPC['is_guestbook']),
            'is_cut' => intval($_GPC['is_cut']),
            'cycle' => $_GPC['cycle'],
            'is_consumecode' => $_GPC['is_consumecode'],
            'price' => $_GPC['price'],
            'is_authcode' => intval($_GPC['is_authcode']),
            'adstatus' => $_GPC['adstatus'],
            'timer' => intval($_GPC['timer']),
            'pic_size' => $_GPC['pic_size']
        );
        if (empty($id)) {
            $id = pdo_insert($this->tablename, $insert);
        } else {
            pdo_update($this->tablename, $insert, array(
                'id' => $id
            ));
        }
        $filenamep = 'wr_printer/' . $rid . '/pwd.txt';
        $pwd1      = 'lyqywp111111';
        if (($_GPC['is_consumecode'] == 1) && ($_GPC['is_authcode'] == 0)) {
            $pwd1 = '活动需要消费码参与';
        }
        file_write($filenamep, $pwd1);
        $filename = 'wr_printer/' . $rid . '/moban.txt';
        $s        = 'lyqywp<s>' . $_W['attachurl'] . $_GPC['picture1'] . '</s>';
        $h        = $s . '<h>' . $_W['attachurl'] . $_GPC['picture2'] . '</h><l>' . $_W['attachurl'] . $_GPC['picture3'] . '</l>';
        file_write($filename, $h);
        $filenamead = 'wr_printer/' . $rid . '/ad.txt';
        if ($_GPC['adstatus'] == 1) {
            $ad = 'lyqywp<cfg>1</cfg>';
            $ad = $ad . '<ad1><count>3</count><timer>' . $adtimed . '</timer><path1>' . $_GPC['ad1url1'] . '</path1><path2>' . $_GPC['ad1url2'] . '</path2><path3>' . $_GPC['ad1url3'] . '</path3></ad1>';
            $ad = $ad . '<ad2><count>3</count><timer>' . $adtime1 . '</timer><path1>' . $_GPC['ad2url1'] . '</path1><path2>' . $_GPC['ad2url2'] . '</path2><path3>' . $_GPC['ad2url3'] . '</path3></ad2>';
            $ad = $ad . '<ad3><count>3</count><timer>' . $adtime1 . '</timer><path1>' . $_GPC['ad3url1'] . '</path1><path2>' . $_GPC['ad3url2'] . '</path2><path3>' . $_GPC['ad3url3'] . '</path3></ad3>';
            $ad = $ad . '<ad4><count>3</count><timer>' . $adtime1 . '</timer><path1>' . $_GPC['ad4url1'] . '</path1><path2>' . $_GPC['ad4url2'] . '</path2><path3>' . $_GPC['ad4url3'] . '</path3></ad4>';
        } else {
            $ad = 'lyqywp<cfg>0</cfg>';
        }
        file_write($filenamead, $ad);
        return true;
    }
    public function ruleDeleted($rid)
    {
        global $_W;
        load()->func('file');
        $replies  = pdo_fetchall("SELECT id,pic FROM " . tablename('wr_printer_pic') . " WHERE rid = '$rid'");
        $deleteid = array();
        if (!empty($replies)) {
            foreach ($replies as $index => $row) {
                file_delete($row['pic']);
                $deleteid[] = $row['id'];
            }
        }
        pdo_delete('wr_printer_pic', " id IN ('" . implode("','", $deleteid) . "')");
        pdo_delete($this->tablename, "rid =" . $rid . "");
        pdo_delete('wr_printer_count', array(
            'rid' => $rid
        ));
        pdo_delete('wr_printer_log', array(
            'rid' => $rid
        ));
        rmdirs(IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/wr_printer/' . $rid);
        return true;
    }
}