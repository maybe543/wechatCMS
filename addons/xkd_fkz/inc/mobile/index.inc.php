<?php
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
    exit('请在微信中浏览');
}

defined('IN_IA') or exit('Access');
global $_W, $_GPC;

$this->loadMod('member');
$mod_member = new member();

$this->loadMod('record');
$mod_record = new record();

$op = $_GPC['op'];
$ops = array('uplevel', 'upseturl', 'goup', 'complete', 'edit');
$op = in_array($op, $ops) ? $op : 'goup';

$uid = $_W['member']['uid'];
$cfg = $this->module['config'];

$member = $mod_member->get_member($uid);

if (empty($member['parent1']) && $uid != $cfg['uid']) {
    include $this->template('nolevel');
    return;
}
if ($op == 'goup') {
    if (empty($member['wechat'])) {
        include $this->template('complete');
    } else {
        $level = $member['level'] + 1;

        $exist_apply = $mod_record->get_record_by_apply_uid($uid);

        if (!empty($exist_apply)) {
            if (!empty($exist_apply['approval_uid'])) {
                $parent = $mod_member->get_member($exist_apply['approval_uid']);
            }

            if (!empty($exist_apply['manager_uid'])) {
                $manager = $mod_member->get_member($exist_apply['manager_uid']);
            }
        }

        include $this->template('index');
    }
} elseif ($op == 'complete') {
    if ($_W['ispost']) {
        $wechat = $_GPC['wechat'];
        if (empty($wechat)) {
            message('微信号不能为空!', $this->createMobileUrl('index'), 'warning');
        } else {
            $mod_member->update_member_field($uid, 'wechat', $wechat);
            header('Location:' . $this->createMobileUrl('index'));
        }
    }
} elseif ($op == 'uplevel') {
    $level = $member['level'] + 1;

    $response = array();

    if ($member['level'] == $cfg['level']) {
        $response['s'] = 'no';
        $response['msg'] = '您已经是最高级了!';
    } else {
        $exist_apply = $mod_record->get_record_by_apply_uid($uid);
        if (!empty($exist_apply)) {
            $response['s'] = 'no';
            $response['msg'] = '您的升级申请已提交，请耐心等待!';
        } else {
            if ($member['level'] == 0 && $cfg['enableleader'] == 1) {
                for ($i = $cfg['leaderlevel']; $i < 13; $i++) {
                    $managerid = $member["parent$i"];
                    if (empty($managerid)) break;

                    $children = $mod_member->get_children_count($managerid);
                    if ($children >= $cfg['leadercondition']) {
                        $manager = $mod_member->get_member($managerid);
                        break;
                    }
                }
            }

            $parentid = $member["parent$level"];


            if ($parentid == 0) {
                $parent = $mod_member->get_member($cfg['uid']);
            } else {
                $parent = $mod_member->get_member($parentid);

                if ($member['level'] == 0 && $parentid != $cfg['uid']) {
                    $children_count = $mod_member->get_children_count($parentid, 1, 1);
                    if ($children_count >= $cfg['num']) {
                        $flag = 0;
                        for ($i = 1; $i < 13; $i++) {
                            $children_list = $mod_member->get_children_list($parentid, $i, 1);

                            foreach ($children_list as $c) {
                                $grandchildren_count = $mod_member->get_children_count($c['uid'], 1, 1);
                                if ($grandchildren_count < $cfg['num']) {
                                    $flag = 1;
                                    $parent = $c;
                                    break;
                                }
                            }

                            if ($flag) break;
                        }
                    }

                    if ($parent['uid'] != $parentid) {
                        $data = array();

                        $data['parent1'] = $parent['uid'];
                        $data['parent2'] = $parent['parent1'];
                        $data['parent3'] = $parent['parent2'];
                        $data['parent4'] = $parent['parent3'];
                        $data['parent5'] = $parent['parent4'];
                        $data['parent6'] = $parent['parent5'];
                        $data['parent7'] = $parent['parent6'];
                        $data['parent8'] = $parent['parent7'];
                        $data['parent9'] = $parent['parent8'];
                        $data['parent10'] = $parent['parent9'];
                        $data['parent11'] = $parent['parent10'];
                        $data['parent12'] = $parent['parent11'];

                        $mod_member->update_member($uid, $data);
                    }
                } elseif ($member['level'] >= $parent['level']) {
                    $parent = $mod_member->get_member($cfg['uid']);
                }
            }

            //exit(json_encode(['s'=>'no','msg'=>$parent['uid']]));

            $level = $member['level'] + 1;
            $apply = array(
                'uniacid' => $_W['uniacid'],
                'apply_uid' => $uid,
                'upgrade' => $level,
                'approval_uid' => $parent['uid'],
                'manager_uid' => 0,
                'a_flag' => 1,
                'm_flag' => 2,
                'packet' => $cfg["level_money$level"],
                'apply_time' => TIMESTAMP,
            );
            if ($member['level'] == 0 && $cfg['enableleader'] == 1 && !empty($manager)) {
                $apply['manager_uid'] = $manager['uid'];
                $apply['m_flag'] = 1;
            }
            $record_id = $mod_record->add_record($apply);
            $response['s'] = 'ok';
            $response['msg'] = '您的升级申请已提交，请耐心等待!';
        }
    }

    echo json_encode($response);
} elseif ($op == 'upseturl') {
    $apply_uid = $_GPC['apply_uid'];
    $applicant = $mod_member->get_member($apply_uid);

    $record = $mod_record->get_record_by_apply_uid($apply_uid, 1);
    $response = array('s' => 'no', 'msg' => '');

    if (empty($record)) {
        $response['msg'] = '该升级申请信息不存在!';
    } else {
        $flag = 0;

        if ($record['approval_uid'] == $uid && $record['a_flag'] == 1) {
            $data = array(
                'a_flag' => 2,
                'approval_time' => TIMESTAMP,
            );

            $flag = 1;
        } elseif ($record['manager_uid'] == $uid && $record['m_flag'] == 1) {
            $data = array(
                'm_flag' => 2,
                'approval_time' => TIMESTAMP,
            );

            $flag = 1;
        }

        if ($flag == 1) {
            $mod_record->update_record($record['record_id'], $data);

            $record = $mod_record->get_record($record['record_id']);

            if ($record['a_flag'] == 2 && $record['m_flag'] == 2) {
                $acid = $_W['account']['acid'];
                if (empty($acid)) {
                    $acid = $_W['uniacid'];
                }
                $acc = WeAccount::create($acid);
                $send = array(
                    'touser' => $applicant['openid'],
                    'msgtype' => 'text',
                    'text' => array(
                        'content' => urlencode('恭喜，您的升级申请已通过')
                    )
                );
                $acc->sendCustomNotice($send);

                $mod_member->update_member_field($apply_uid, 'level', $record['upgrade']);
            }

            $response['s'] = 'ok';
            $response['msg'] = '升级申请批准成功!';
        } else {
            $response['msg'] = '该升级申请您已批准';
        }
    }
    echo json_encode($response);
} elseif ($op == 'edit') {

    if ($_W['ispost']) {
        $wechat = $_GPC['wechat'];
        $qq = $_GPC['qq'];
        $mobile = $_GPC['mobile'];
        if (empty($wechat)) {
            $response = array('s' => 'no', 'msg' => '微信号不能为空!');
        } else {
            $entity = array(
                'wechat' => $wechat,
                'qq' => $qq,
                'mobile' => $mobile,
            );
            $mod_member->update_member($uid, $entity);
            $response = array('s' => 'ok', 'msg' => '个人资料修改成功!');
        }
        echo json_encode($response);
    } else {
        include $this->template('edit');
    }
}

?>