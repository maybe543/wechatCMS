<?php
defined('IN_IA') or exit('Access Denied');
include '../addons/bm_qrsign/phpqrcode.php';
class bm_qrsignModule extends WeModule
{
    public $weid;
    public function __construct()
    {
        global $_W;
        $this->weid = IMS_VERSION < 0.6 ? $_W['weid'] : $_W['uniacid'];
    }
    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            if (empty($reply['qrcode'])) {
                if ($reply['qrtype'] == 0) {
                    $value                = $_W['siteroot'] . 'app/' . $this->createmobileurl('sign', array(
                        'rid' => $rid
                    ));
                    $errorCorrectionLevel = 'H';
                    $matrixPointSize      = '16';
                    $rand_file            = rand() . '.png';
                    $att_target_file      = 'qr-' . $rand_file;
                    $target_file          = '../addons/bm_qrsign/tmppic/' . $att_target_file;
                    QRcode::png($value, $target_file, $errorCorrectionLevel, $matrixPointSize);
                    $reply['qrcode'] = $target_file;
                } else {
                    $value                = $_W['siteroot'] . 'app/' . $this->createmobileurl('pay', array(
                        'rid' => $rid
                    ));
                    $errorCorrectionLevel = 'H';
                    $matrixPointSize      = '16';
                    $rand_file            = rand() . '.png';
                    $att_target_file      = 'qr-' . $rand_file;
                    $target_file          = '../addons/bm_qrsign/tmppic/' . $att_target_file;
                    QRcode::png($value, $target_file, $errorCorrectionLevel, $matrixPointSize);
                    $reply['qrcode'] = $target_file;
                }
            }
        }
        load()->func('tpl');
        include $this->template('form');
    }
    public function fieldsFormValidate($rid = 0)
    {
        return '';
    }
    public function fieldsFormSubmit($rid)
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $data = array(
            'rid' => $rid,
            'weid' => $weid,
            'n' => intval($_GPC['n']),
            'desc' => $_GPC['desc'],
            'pictype' => $_GPC['pictype'],
            'picurl' => $_GPC['picurl'],
            'urlx' => $_GPC['urlx'],
            'title' => $_GPC['title'],
            'starttime' => $_GPC['starttime'],
            'endtime' => $_GPC['endtime'],
            'qrcode' => $_GPC['qrcode'],
            'urly' => $_GPC['urly'],
            'url1' => $_GPC['url1'],
            'url2' => $_GPC['url2'],
            'memo1' => $_GPC['memo1'],
            'memo2' => $_GPC['memo2'],
            'play_times' => $_GPC['play_times'],
            'play_nums' => $_GPC['play_nums'],
            'play_type' => $_GPC['play_type'],
            'qrtype' => $_GPC['qrtype'],
            'qrmoney' => $_GPC['qrmoney'],
            'qrerrormemo' => $_GPC['qrerrormemo'],
            'qrerrorurl' => $_GPC['qrerrorurl'],
            'memo' => $_GPC['memo'],
            'qrinput' => $_GPC['qrinput'],
            'logo' => $_GPC['logo'],
            'templateid' => $_GPC['templateid'],
            'awaremethod' => $_GPC['awaremethod'],
            'awaretime' => $_GPC['awaretime'],
            'openid' => $_GPC['openid'],
            'templateid1' => $_GPC['templateid1'],
            'button' => $_GPC['button']
        );
        if ($_W['ispost']) {
            if (empty($_GPC['reply_id'])) {
                pdo_insert('bm_qrsign_reply', $data);
            } else {
                pdo_update('bm_qrsign_reply', $data, array(
                    'id' => $_GPC['reply_id']
                ));
            }
            message('更新成功', referer(), 'success');
        }
    }
    public function ruleDeleted($rid)
    {
        global $_W;
        $replies  = pdo_fetchall("SELECT *  FROM " . tablename('bm_qrsign_reply') . " WHERE rid = '$rid'");
        $deleteid = array();
        if (!empty($replies)) {
            foreach ($replies as $index => $row) {
                $deleteid[] = $row['id'];
            }
        }
        pdo_delete('bm_qrsign_reply', "id IN ('" . implode("','", $deleteid) . "')");
        return true;
    }
	public function settingsDisplay($settings) {
        global $_GPC, $_W;
        load()->func('tpl');
        if(checksubmit()) {
            $settings = array();
            $settings['title'] = $_GPC['title'];
            $settings['picurl'] = $_GPC['picurl'];
            $settings['info'] = $_GPC['info'];
            $settings['account'] = $_GPC['account'];
            $settings['pwd'] = $_GPC['pwd'];
            if($this->saveSettings($settings)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('setting');
    }
}
?>