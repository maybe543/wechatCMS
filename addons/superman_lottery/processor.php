<?php
/**
 * 【超人】关键字抽奖模块处理程序
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
require IA_ROOT.'/addons/superman_lottery/common.func.php';
require IA_ROOT.'/addons/superman_lottery/model.func.php';
class Superman_lotteryModuleProcessor extends WeModuleProcessor {
    private $uid;
    private $debug = false;
    private $starttime, $endtime;
	public function respond() {
        global $_W, $config;
        if ($this->debug) {
            $this->starttime = microtime(true);
        }
        load()->model('mc');
        $rid = $this->rule;
        $openid = $this->message['from'];
        $member = mc_fansinfo($openid);
        if (!$member || !$member['follow']) {
            return $this->show_message('请先关注公众号！');
        }
        if (!$member['uid']) {
            WeUtility::logging('fatal', 'not found uid, openid='.$openid);
            return $this->show_message('公众号配置错误，请开启自动注册！');
        }
        $this->uid = $member['uid'];
        $url_param = array(
            'uid' => $this->uid,
            'rid' => $rid,
            '_t' => TIMESTAMP,
        );
        $url_param['key'] = superman_sign_key($url_param, $config['setting']['authkey']);
        $message_variable = array(
            '{抽奖次数}' => '',
            '{今天抽奖次数}' => '',
            '{奖品等级}' => '',
            '{奖品名称}' => '',
            '{时间}' => date('Y-m-d H:i:s', TIMESTAMP),
            '{领奖链接}' => $this->createMobileUrl('lottery', $url_param),
            '{规则链接}' => '',
            '{消耗积分}' => '0',
            '{总积分}' => '',
            '{中奖名单链接}' => $this->createMobileUrl('winner', array('rid' => $rid)),
            'credit_title' => '',
        );

        //setting
        $sql = "SELECT extend FROM ".tablename('superman_lottery')." WHERE rid=:rid";
        $params = array(
            ':rid' => $rid,
        );
        $extend = pdo_fetchcolumn($sql, $params);
        if (!$extend) {
            WeUtility::logging('fatal', 'extend参数为空, rid='.$rid);
            return $this->show_message('活动参数为空，请重新设置！', $message_variable);
        }
        $extend = unserialize($extend);
        if (!$extend['base']) {
            WeUtility::logging('fatal', 'base参数为空, rid='.$rid);
            return $this->show_message('活动参数为空，请重新设置！', $message_variable);
        }
        $base_setting = $extend['base'];
        $credit_setting = $extend['credit'];
        $message_variable['{规则链接}'] = $base_setting['rule_url']?$base_setting['rule_url']:'';
        $member_credits = mc_credit_fetch($this->uid);
        $message_variable['{消耗积分}'] = $credit_setting['value']>0?$credit_setting['value']:'';
        $message_variable['{总积分}'] = $member_credits[$credit_setting['type']];
        $credit_title = $this->get_credit_title($credit_setting['type']);
        $message_variable['credit_title'] = $credit_title;

        //检查活动时间
        $starttime = strtotime($base_setting['activity_time']['start']);
        $endtime = strtotime($base_setting['activity_time']['end']);
        if ($starttime > TIMESTAMP) {
            return $this->show_message('活动未开始，活动时间：'.$base_setting['activity_time']['start'].' 至 '.$base_setting['activity_time']['end']);
        }
        if ($endtime < TIMESTAMP) {
            return $this->show_message('活动已结束，感谢您的参与！');
        }

        //抽奖记录
        $sql = "SELECT * FROM ".tablename('superman_lottery_log')." WHERE rid=:rid AND uid=:uid";
        $params = array(
            ':rid' => $rid,
            ':uid' => $this->uid,
        );
        $lottery_log = pdo_fetch($sql, $params);

        //检查总抽奖次数限制
        if ($lottery_log && $base_setting['play_total'] > 0) {
            if ($lottery_log['total'] > 0 && $lottery_log['total'] >= $base_setting['play_total']) {
                return $this->show_message('已达到最大抽奖次数限制，本活动每人可以抽奖'.$base_setting['play_total'].'次。', $message_variable);
            }
        }

        //检查每天抽奖次数限制
        $today = array(
            'start' => strtotime(date('Y-m-d 0:0:0')),
            'end' => strtotime(date('Y-m-d 23:59:59')),
        );
        if ($lottery_log && $base_setting['play_today_total'] > 0) {
            if ($lottery_log['today_total'] > 0 && $lottery_log['today_total'] >= $base_setting['play_today_total']
                && $lottery_log['dateline'] >= $today['start'] && $lottery_log['dateline'] <= $today['end']) {
                return $this->show_message('已达到今天抽奖次数限制，本活动每人每天最多可以抽奖'.$base_setting['play_today_total'].'次。', $message_variable);
            }
        }
        $today_total = 0;
        if ($lottery_log) {
            $today_total = $lottery_log['today_total'];
            if ($lottery_log['dateline'] >= $today['start'] && $lottery_log['dateline'] <= $today['end']) {
                $today_total += 1;
            } else {
                $today_total = 1;   //初始化
            }
        } else {
            $today_total = 1;
        }

        //检查关键字发送频率
        if ($lottery_log && $base_setting['interval_time'] > 0) {
            $interval = TIMESTAMP - $lottery_log['dateline'];
            if ($interval < $base_setting['interval_time']) {
                $remain_second = $base_setting['interval_time'] - $interval;
                return $this->show_message('发送太快了，请休息一下，距离下次发送还有'.$remain_second.'秒', $message_variable);
            }
        }

        //检查积分配置
        if ($credit_setting['value'] > 0) {
            if ($member_credits[$credit_setting['type']] < $credit_setting['value']) {
                return $this->show_message('本次抽奖需消耗'.$credit_setting['value'].$credit_title.'，您还有'.$member_credits[$credit_setting['type']].$credit_title.'，积分不足！', $message_variable);
            }

            //扣积分
            $sql = "SELECT `name` FROM ".tablename('rule')." WHERE id={$rid}";
            $rule_name = pdo_fetchcolumn($sql);
            $credit_log = array($this->uid, "【{$rule_name}】抽奖消耗", '【超人】关键字抽奖');
            $ret = mc_credit_update($this->uid, $credit_setting['type'], -$credit_setting['value'], $credit_log);
            if ($ret !== true) {
                WeUtility::logging('fatal', 'mc_credit_update failed, uid='.$this->uid.', credit_type='.$credit_setting['type'].', credit_value=-'.$credit_setting['value'].', error='.var_export($ret, true));
                return $this->show_message($ret[1], $message_variable);
            }
        }

        $message_variable['{抽奖次数}'] = $lottery_log?$lottery_log['total'] + 1:1;
        $message_variable['{今天抽奖次数}'] = $today_total;
        $message_variable['{总积分}'] -= $credit_setting['value'];

        //获取有库存奖品
        $sql = "SELECT * FROM ".tablename('superman_lottery_prize')." WHERE rid=:rid AND remain_total>0 AND join_play=1";
        $params = array(
            ':rid' => $rid,
        );
        $prizes = pdo_fetchall($sql, $params);
        if (!$prizes) {
            WeUtility::logging('trace', 'not found prize, rid='.$rid);
            $this->update_member_lottery_number($lottery_log);
            return $this->show_message($base_setting['nowin_msg'], $message_variable);
        }

        //已中奖 && 不能重复中奖
		//WeUtility::logging('trace', 'repeat_win='.$base_setting['repeat_win'].', prize='.$lottery_log['prize']);
        if (!$base_setting['repeat_win'] && $lottery_log['prize'] != '') {
            WeUtility::logging('trace', 'not repeat winning');
            $this->update_member_lottery_number($lottery_log);
            return $this->show_message($base_setting['nowin_msg'], $message_variable);
        }

        $prize_list = array();
        foreach ($prizes as $v) {
            $rate = 1;
            if (empty($v['probability'])) {
                continue;
            }
            //计算奖品概率，包含小数概率时，小数点位数转换为整数
            $probability = $v['probability'];
            if ($probability < 1) {
                $arr = explode('.', $probability);
                $num = pow(10, strlen($arr[1]));
                $rate = $num > 1 ? $num : 1;
                $probability = $probability * $rate;
            }
            $rand = mt_rand(1, $rate * 100);
            //WeUtility::logging('trace', 'lottery rand='.$rand.', probability='.$probability);
            if ($rand > $probability) {
                continue;
            }
            $prize_list[] = array(
                'id' => $v['id'],
                'title' => $v['title'],
                'name' => $v['name'],
                'total' => $v['total'],
                'probability' => $probability,
            );
        }
        //未中奖
        if (!$prize_list) {
            WeUtility::logging('trace', 'not found prize list');
            $this->update_member_lottery_number($lottery_log);
            return $this->show_message($base_setting['nowin_msg'], $message_variable);
        }

        //生成中奖奖品
        $win_prize = $prize_list[mt_rand(0, count($prize_list) - 1)];
        $message_variable['{奖品等级}'] = $win_prize['title'];
        $message_variable['{奖品名称}'] = $win_prize['name'];

        //更新奖品数量
        $sql = "UPDATE ".tablename('superman_lottery_prize')." SET remain_total=remain_total-1 WHERE remain_total>0 AND id=".$win_prize['id']." AND join_play=1";
        $ret = pdo_query($sql);
        if ($ret === false) {
            WeUtility::logging('trace', 'update prize total failed');
            $this->update_member_lottery_number($lottery_log);
            return $this->show_message($base_setting['nowin_msg'], $message_variable);
        }

        //记录中奖记录
        if (!$lottery_log) {
            $prize_ids[] = $win_prize['id'];
            $data = array(
                'uniacid' => $_W['uniacid'],
                'rid' => $rid,
                'uid' => $member['uid'],
                'total' => 1,
                'today_total' => 1,
                'prize' => implode(',', $prize_ids),
                'status' => 0,  //中奖
                'dateline' => TIMESTAMP,
            );
            pdo_insert('superman_lottery_log', $data);
            $new_id = pdo_insertid();
            if (!$new_id) {
                WeUtility::logging('fatal', 'insert superman_lottery_log failed, data='.var_export($data, true));
                return $this->show_message($base_setting['nowin_msg'], $message_variable);
            }
        } else {
            if ($lottery_log['prize']) {
                $prize_ids = explode(',', $lottery_log['prize']);
            }
            $prize_ids[] = $win_prize['id'];
            $data = array(
                'total' => $lottery_log['total'] + 1,
                'today_total' => $today_total,
                'prize' => implode(',', $prize_ids),
                'status' => 0,  //中奖
                'dateline' => TIMESTAMP,
            );
            $condition = array(
                'id' => $lottery_log['id'],
            );
            $ret = pdo_update('superman_lottery_log', $data, $condition);
            if ($ret === false) {
                WeUtility::logging('fatal', 'update superman_lottery_log failed, id='.$lottery_log['id'].', data='.var_export($data, true));
                return $this->show_message($base_setting['nowin_msg'], $message_variable);
            }
        }

        //成功
        return $this->show_message($base_setting['winning_msg'], $message_variable);
	}

    private function show_message($msg, $var = array()) {
        //WeUtility::logging('trace', $msg);
        //WeUtility::logging('trace', var_export($var, true));
        $msg = str_replace('{抽奖次数}', $var['{抽奖次数}'], $msg);
        $msg = str_replace('{今天抽奖次数}', $var['{今天抽奖次数}'], $msg);
        $msg = str_replace('{奖品等级}', $var['{奖品等级}'], $msg);
        $msg = str_replace('{奖品名称}', $var['{奖品名称}'], $msg);
        $msg = str_replace('{时间}', $var['{时间}'], $msg);
        if ($var['{奖品名称}'] != '') {
            $url = '<a href="'.$var['{领奖链接}'].'">点击提交领奖信息</a>';
            $msg = str_replace('{领奖链接}', $url, $msg);
        } else {
            $msg = str_replace('{领奖链接}', '', $msg);
        }
        if ($var['{规则链接}'] != '') {
            $url = '<a href="'.$var['{规则链接}'].'">点击查看领奖规则</a>';
            $msg = str_replace('{规则链接}', $url, $msg);
        } else {
            $msg = str_replace('{规则链接}', '', $msg);
        }
        if ($var['{消耗积分}'] > 0) {
            $msg = str_replace('{消耗积分}', $var['{消耗积分}'].$var['credit_title'], $msg);
        }
        $msg = str_replace('{总积分}', $var['{总积分}'].$var['credit_title'], $msg);
        if ($var['{中奖名单链接}'] != '') {
            $url = '<a href="'.$var['{中奖名单链接}'].'">点击查看中奖名单</a>';
            $msg = str_replace('{中奖名单链接}', $url, $msg);
        } else {
            $msg = str_replace('{中奖名单链接}', '', $msg);
        }

        if ($this->debug) {
            $this->_endtime = microtime(true);
            WeUtility::logging('trace', 'superman_lottery runtime='.round($this->starttime - $this->endtime, 3));
        }
        return $this->respText($msg);
    }

    private function update_member_lottery_number($row) {
        global $_W;
        if ($row) {
            $total = $row['total'] + 1;
            $today_total = $row['today_total'];
            $today = array(
                'start' => strtotime(date('Y-m-d 0:0:0')),
                'end' => strtotime(date('Y-m-d 23:59:59')),
            );
            if ($row['dateline'] >= $today['start'] && $row['dateline'] <= $today['end']) {
                $today_total += 1;
            } else {
                $today_total = 1;   //初始化
            }
            $data = array(
                'total' => $total,
                'today_total' => $today_total,
                'dateline' => TIMESTAMP,
            );
            $condition = array(
                'id' => $row['id'],
            );
            $ret = pdo_update('superman_lottery_log', $data, $condition);
            if ($ret === false) {
                WeUtility::logging('fatal', 'update lottery total failed, id='.$row['id'].', total='.$total.', today_total='.$today_total);
            }
        } else {
            $data = array(
                'uniacid' => $_W['uniacid'],
                'rid' => $this->rule,
                'uid' => $this->uid,
                'total' => 1,
                'today_total' => 1,
                'status' => -1,
                'dateline' => TIMESTAMP,
            );
            pdo_insert('superman_lottery_log', $data);
            $new_id = pdo_insertid();
            if (!$new_id) {
                WeUtility::logging('fatal', 'insert lottery total failed, data='.var_export($data, true));
            }
        }
    }

    private function get_credit_title($type) {
        $credits = superman_get_credits();
        if ($credits && isset($credits[$type])) {
            return $credits[$type]['title'];
        }
        return '';
    }
}