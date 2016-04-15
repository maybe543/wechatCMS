<?php
defined('IN_IA') or exit('Access Denied');
class Wdl_hongbaoModuleSite extends WeModuleSite
{
    public $_weid = '';
    public $_from_user = '';
    public $_upload_prefix = '';
    public function __construct()
    {
        global $_W, $_GPC;
        if (0) {
            $this->_from_user = 'test_from_user';
            $this->_weid      = 2;
        } else {
            $this->_from_user = $_W['openid'];
            $this->_weid      = $_W['uniacid'];
        }
        if (1 || !$_GPC['do']) {
            $hongbao_status_arr = array(
                'SENDING' => '发放中',
                'SENT' => '已发放待领取',
                'FAILED' => '发放失败',
                'RECEIVED' => '已领取',
                'REFUND' => '已退款'
            );
            $condition          = " and weid= " . $this->_weid;
            $sql                = "SELECT id, bill_no, remark FROM " . tablename('fhb_sendrec') . " WHERE 1 $condition and send_finish='Y' and receive_flag !='Y' ORDER BY id DESC ";
            $list               = pdo_fetchall($sql);
            foreach ($list as $row) {
                $get_status_arr = $this->get_hongbao_status($row['bill_no']);
                if ($get_status_arr['return_code'] == 'SUCCESS' && $get_status_arr['result_code'] == 'SUCCESS') {
                    $receive_flag = ($get_status_arr['status'] == 'RECEIVED' ? 'Y' : 'N');
                    pdo_update('fhb_sendrec', array(
                        'receive_flag' => $receive_flag,
                        'remark' => $row['remark'] . ' 最新状态：' . $hongbao_status_arr[$get_status_arr['status']]
                    ), array(
                        'id' => $row['id']
                    ));
                }
            }
        }
    }
    public function doMobileIndex()
    {
        global $_W, $_GPC;
        if (1 && empty($this->_from_user)) {
            message('请先关注公众号！');
            exit;
        }
        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 15;
        $condition = " and weid= " . $this->_weid . " and openid='" . $this->_from_user . "'";
        $sql       = "SELECT * FROM " . tablename('fhb_sendrec') . " WHERE 1 $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $list      = pdo_fetchall($sql);
        $total     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fhb_sendrec') . " WHERE 1 $condition");
        $pager     = pagination($total, $pindex, $psize);
        $pageend   = ceil($total / $psize);
        if ($total / $psize != 0 && $total >= $psize) {
            $pageend++;
        }
        include $this->template('rec_list');
    }
    public function doWebSendrec()
    {
        global $_W, $_GPC;
        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 15;
        $condition = " rec.weid=" . $this->_weid;
        if ($_GPC['openid']) {
            $condition .= " and rec.openid like '%" . $_GPC['openid'] . "%'";
        }
        $sql           = "SELECT rec.*, mem.nickname " . "FROM " . tablename('fhb_sendrec') . " rec " . "LEFT JOIN " . tablename('mc_mapping_fans') . " fans ON rec.openid=fans.openid " . "LEFT JOIN " . tablename('mc_members') . " mem ON fans.uid=mem.uid " . "WHERE $condition ORDER BY rec.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $list          = pdo_fetchall($sql);
        $total         = pdo_fetchcolumn('SELECT COUNT(*) ' . "FROM " . tablename('fhb_sendrec') . " rec " . "LEFT JOIN " . tablename('mc_mapping_fans') . " fans ON rec.openid=fans.openid " . "LEFT JOIN " . tablename('mc_members') . " mem ON fans.uid=mem.uid " . "WHERE $condition");
        $pager         = pagination($total, $pindex, $psize);
        $category_list = $this->get_article_category();
        include $this->template('sendrec');
    }
    public function read($filename, $encode = 'utf-8')
    {
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load($filename);
        $indata      = $objPHPExcel->getSheet(0)->toArray();
        return $indata;
    }
    public function doWebSendrec_add()
    {
        global $_W, $_GPC;
        if ($_GPC['op'] == 'delete') {
            $id  = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM " . tablename('fhb_sendrec') . " WHERE id = :id", array(
                ':id' => $id
            ));
            if (empty($row)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_delete('fhb_sendrec', array(
                'id' => $id
            ));
            message('删除成功！', referer(), 'success');
        }
        if (!empty($_FILES['file_cumtomer']['name'])) {
            $tmp_file   = $_FILES['file_cumtomer']['tmp_name'];
            $file_types = explode(".", $_FILES['file_cumtomer']['name']);
            $file_type  = $file_types[count($file_types) - 1];
            $savePath   = IA_ROOT . '/addons/wdl_hongbao/template/upFile/';
            $str        = date('Ymdhis');
            $file_name  = $str . "." . $file_type;
            if (!copy($tmp_file, $savePath . $file_name)) {
                message('上传失败');
            }
            $res                    = $this->read($savePath . $file_name);
            $insert['receive_flag'] = 'N';
            $insert['weid']         = $this->_weid;
            $insert['createtime']   = date('Y-m-d H:i:s');
            foreach ($res as $k => $v) {
                if ($k != 0) {
                    $insert['openid'] = $v[0];
                    $insert['money']  = round($v[1], 2);
                    $insert['remark'] = $v[2];
                    pdo_insert('fhb_sendrec', $insert);
                    $in_id   = pdo_insertid();
                    $bill_no = $this->module['config']['fhb_mchid'] . date('YmdHis') . str_pad($in_id, 4, "0", STR_PAD_LEFT);
                    pdo_update('fhb_sendrec', array(
                        'bill_no' => $bill_no
                    ), array(
                        'id' => $in_id
                    ));
                    $id          = $in_id;
                    $data        = pdo_fetch("SELECT * FROM " . tablename('fhb_sendrec') . " WHERE id = :id", array(
                        ':id' => $id
                    ));
                    $send_res    = $this->send_hongbao($data['bill_no'], $data['openid'], $data['money']);
                    $res         = $send_res['return_code'] . '->' . $send_res['return_msg'];
                    $update_data = array(
                        'send_res' => $res,
                        'send_finish' => strstr($send_res['return_code'], 'SUCCESS') ? 'Y' : 'N',
                        'send_time' => date('Y-m-d H:i:s')
                    );
                    pdo_update('fhb_sendrec', $update_data, array(
                        'id' => $id
                    ));
                }
            }
            $curr_index_url = $this->createWebUrl('sendrec');
            message('导入成功！', $curr_index_url, 'success');
        } else {
            if (checksubmit()) {
                $id           = intval($_GPC['id']);
                $data         = array(
                    'weid' => $this->_weid,
                    'openid' => $_GPC['openid'],
                    'money' => $_GPC['money'] * 100,
                    'remark' => $_GPC['remark']
                );
                $no_empty_arr = array(
                    'openid' => 'openid',
                    'money' => '红包金额'
                );
                foreach ($no_empty_arr as $field => $item_name) {
                    if (empty($data[$field])) {
                        message('请填写' . $item_name . '！', '', 'error');
                    }
                }
                if (!empty($id)) {
                    pdo_update('fhb_sendrec', $data, array(
                        'id' => $id
                    ));
                } else {
                    $data['receive_flag'] = 'N';
                    $data['createtime']   = date('Y-m-d H:i:s');
                    pdo_insert('fhb_sendrec', $data);
                    $in_id   = pdo_insertid();
                    $bill_no = $this->module['config']['fhb_mchid'] . date('YmdHis') . str_pad($in_id, 4, "0", STR_PAD_LEFT);
                    pdo_update('fhb_sendrec', array(
                        'bill_no' => $bill_no
                    ), array(
                        'id' => $in_id
                    ));
                    $id = $in_id;
                }
                $curr_index_url = $this->createWebUrl('sendrec');
                $data           = pdo_fetch("SELECT * FROM " . tablename('fhb_sendrec') . " WHERE id = :id", array(
                    ':id' => $id
                ));
                $send_res       = $this->send_hongbao($data['bill_no'], $data['openid'], $data['money']);
                $res            = $send_res['return_code'] . '->' . $send_res['return_msg'];
                $update_data    = array(
                    'send_res' => $res,
                    'send_finish' => strstr($send_res['return_code'], 'SUCCESS') ? 'Y' : 'N',
                    'send_time' => date('Y-m-d H:i:s')
                );
                pdo_update('fhb_sendrec', $update_data, array(
                    'id' => $id
                ));
                message('更新成功！' . $res, $curr_index_url, 'success');
            }
        }
        $data          = array();
        $fhb_send_type = $this->module['config']['fhb_send_type'];
        if ($fhb_send_type == 'f') {
            $data['money'] = round($this->module['config']['fhb_send_money'] / 100, 2);
        } else {
            $data['fhb_send_money_from'] = $this->module['config']['fhb_send_money_from'];
            $data['fhb_send_money_to']   = $this->module['config']['fhb_send_money_to'];
            $random_money                = rand(intval($data['fhb_send_money_from']), intval($data['fhb_send_money_to']));
            $data['money']               = $random_money;
        }
        include $this->template('sendrec_add');
    }
    public function doWebSendrec_edit()
    {
        global $_W, $_GPC;
        $id            = intval($_GPC['id']);
        $sql           = "SELECT rec.*, mem.nickname " . "FROM " . tablename('fhb_sendrec') . " rec " . "LEFT JOIN " . tablename('mc_mapping_fans') . " fans ON rec.openid=fans.openid " . "LEFT JOIN " . tablename('mc_members') . " mem ON fans.uid=mem.uid " . "WHERE id = :id  ";
        $data          = pdo_fetch($sql, array(
            ':id' => $id
        ));
        $fhb_send_type = $this->module['config']['fhb_send_type'];
        if ($fhb_send_type == 'f') {
            $data['fhb_send_money'] = round($this->module['config']['fhb_send_money'] / 100, 2);
        } else {
            $data['fhb_send_money_from'] = $this->module['config']['fhb_send_money_from'];
            $data['fhb_send_money_to']   = $this->module['config']['fhb_send_money_to'];
            $random_money                = rand(intval($data['fhb_send_money_from']), intval($data['fhb_send_money_to']));
            $data['fhb_send_money']      = $random_money;
        }
        $data['money'] = $data['money'] / 100;
        include $this->template('sendrec_add');
    }
    public function doMobileHttpsendhongbaotest()
    {
        global $_W, $_GPC;
        echo '--begin---<br>';
        $obj_url   = 'http://192.168.1.12/weiqing7/app/index.php?i=1&c=entry&do=httpsendhongbao&m=wdl_hongbao&';
        $param_arr = array(
            'openid' => 'oI4oAuBCtPsR1YQRu8fDcNbA29U0',
            'memberid' => 1,
            'user_info_id' => 2,
            'money' => 100,
            'remark' => '红包测试'
        );
        ksort($param_arr);
        $para_str = $this->formatQueryParaMap($param_arr);
        $para_str .= '&key=' . $this->module['config']['fhb_send_key'];
        $para_str .= '&sign=' . strtoupper(md5($para_str));
        $obj_url .= $para_str;
        $res = file_get_contents($obj_url);
        echo '--end---<br>';
        print_r($res);
        exit;
    }
    public function doMobileHttpsendhongbao()
    {
        global $_W, $_GPC;
        $no_empty_arr = array(
            'openid' => 'openid',
            'money' => '红包金额',
            'sign' => '签名'
        );
        foreach ($no_empty_arr as $field => $item_name) {
            if (empty($_GPC[$field])) {
                $res_arr = array(
                    'error' => '1',
                    'msg' => '请填写' . $item_name . '！'
                );
                echo json_encode($res_arr);
                exit;
            }
        }
        $data      = array(
            'weid' => $this->_weid,
            'receive_flag' => 'N',
            'send_finish' => 'N',
            'createtime' => date('Y-m-d H:i:s')
        );
        $field_arr = array(
            'openid',
            'memberid',
            'user_info_id',
            'money',
            'remark'
        );
        sort($field_arr);
        $get_str = '';
        foreach ($field_arr as $field) {
            if (!empty($_GPC[$field])) {
                $data[$field] = $_GPC[$field];
                $get_str .= $field . '=' . $_GPC[$field] . '&';
            }
        }
        $get_str .= 'key=' . $this->module['config']['fhb_send_key'];
        $get_str_sign = strtoupper(md5($get_str));
        if ($get_str_sign != $_GPC['sign']) {
            $res_arr = array(
                'error' => '1',
                'msg' => '签名错误！'
            );
            echo json_encode($res_arr);
            exit;
        }
        pdo_insert('fhb_sendrec', $data);
        $in_id   = pdo_insertid();
        $bill_no = $this->module['config']['fhb_mchid'] . date('YmdHis') . str_pad($in_id, 4, "0", STR_PAD_LEFT);
        pdo_update('fhb_sendrec', array(
            'bill_no' => $bill_no
        ), array(
            'id' => $in_id
        ));
        $id          = $in_id;
        $data        = pdo_fetch("SELECT * FROM " . tablename('fhb_sendrec') . " WHERE id = :id", array(
            ':id' => $id
        ));
        $send_res    = $this->send_hongbao($data['bill_no'], $data['openid'], $data['money']);
        $res         = $send_res['return_code'] . '->' . $send_res['return_msg'];
        $update_data = array(
            'send_res' => $res,
            'send_finish' => strstr($send_res['return_code'], 'SUCCESS') ? 'Y' : 'N',
            'send_time' => date('Y-m-d H:i:s')
        );
        pdo_update('fhb_sendrec', $update_data, array(
            'id' => $id
        ));
        $res_arr = array(
            'error' => '0',
            'msg' => '保存成功！发放红包结果：' . $res
        );
        echo json_encode($res_arr);
        exit;
    }
    private function get_hongbao_status($bill_no)
    {
        global $_W, $_GPC;
        $para_data    = pdo_fetch("SELECT * FROM " . tablename('uni_account_modules') . " WHERE module = :module AND uniacid = :uniacid", array(
            ':module' => 'wdl_hongbao',
            ':uniacid' => $_W['uniacid']
        ));
        $para_data    = unserialize($para_data['settings']);
        $company_info = array(
            'mchid' => $para_data['fhb_mchid'],
            'appid' => $para_data['fhb_appid'],
            'key' => $para_data['fhb_send_key']
        );
        $para_str     = '';
        $hongbao_url  = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo';
        $param_arr    = array(
            'nonce_str' => md5(date('YmdHis') . rand(0, 1000)),
            'mch_billno' => $bill_no,
            'mch_id' => $company_info['mchid'],
            'appid' => $company_info['appid'],
            'bill_type' => 'MCHT'
        );
        ksort($param_arr);
        $para_str = $this->formatQueryParaMap($param_arr);
        $para_str .= '&key=' . $company_info['key'];
        $param_arr['sign'] = strtoupper(md5($para_str));
        $xml               = $this->arr2xml($param_arr);
        $result            = $this->vpost($hongbao_url, $xml);
        $array_data        = json_decode(json_encode(simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }
    private function send_hongbao($bill_no, $to_user_openid, $money)
    {
        $company_info = array(
            'mchid' => $this->module['config']['fhb_mchid'],
            'bill_no' => $bill_no,
            'appid' => $this->module['config']['fhb_appid'],
            'send_name' => $this->module['config']['fhb_send_name'],
            'nick_name' => $this->module['config']['fhb_nick_name'],
            'to_user_openid' => $to_user_openid,
            'min_value' => $money,
            'max_value' => $money,
            'total_amount' => $money,
            'total_num' => 1,
            'wishing' => $this->module['config']['fhb_wishing'],
            'remark' => $this->module['config']['fhb_remark'],
            'act_name' => $this->module['config']['fhb_act_name'],
            'key' => $this->module['config']['fhb_send_key']
        );
        $para_str     = '';
        $hongbao_url  = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $param_arr    = array(
            'nonce_str' => md5(date('YmdHis') . rand(0, 1000)),
            'mch_billno' => $company_info['bill_no'],
            'mch_id' => $company_info['mchid'],
            'wxappid' => $company_info['appid'],
            'send_name' => $company_info['send_name'],
            'nick_name' => $company_info['nick_name'],
            're_openid' => $company_info['to_user_openid'],
            'min_value' => $company_info['min_value'],
            'max_value' => $company_info['max_value'],
            'total_amount' => $company_info['total_amount'],
            'total_num' => $company_info['total_num'],
            'wishing' => $company_info['wishing'],
            'client_ip' => $_SERVER['SERVER_ADDR'] ? $_SERVER['SERVER_ADDR'] : '192.168.1.1',
            'remark' => $company_info['remark'],
            'act_name' => $company_info['act_name']
        );
        ksort($param_arr);
        $para_str = $this->formatQueryParaMap($param_arr);
        $para_str .= '&key=' . $company_info['key'];
        $param_arr['sign'] = strtoupper(md5($para_str));
        $xml               = $this->arr2xml($param_arr);
        $result            = $this->vpost($hongbao_url, $xml);
        $array_data        = json_decode(json_encode(simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }
    private function arr2xml($data, $root = true)
    {
        $str = "";
        if ($root) {
            $str .= "<xml>";
        }
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $child = arr2xml($val, false);
                $str .= "<$key>$child</$key>";
            } else {
                $str .= "<$key>$val</$key>";
            }
        }
        if ($root) {
            $str .= "</xml>";
        }
        return $str;
    }
    private function formatQueryParaMap($paraMap, $urlencode = 0)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v && "sign" != $k) {
                if ($urlencode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
    private function vpost($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        $key_url = str_replace('app', 'web', getcwd());
        curl_setopt($curl, CURLOPT_SSLCERT, $key_url . '/apiclient_cert.pem');
        curl_setopt($curl, CURLOPT_SSLKEY, $key_url . '/apiclient_key.pem');
        curl_setopt($curl, CURLOPT_CAINFO, $key_url . '/rootca.pem');
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl);
        }
        curl_close($curl);
        return $tmpInfo;
    }
}