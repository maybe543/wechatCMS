<?php
/**
 * 【超人】关键字抽奖模块定义
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
require IA_ROOT.'/addons/superman_lottery/common.func.php';
require IA_ROOT.'/addons/superman_lottery/model.func.php';
class Superman_lotteryModule extends WeModule {

    public function fieldsFormDisplay($rid = 0) {
        global $_GPC, $_W;
        $eid = intval($_GPC['eid']);
        load()->func('tpl');

        $credits = superman_get_credits();
        $base = array(
            'repeat_win' => 0,
            'play_total' => 10,
            'play_today_total' => 2,
            'activity_time' => array(
                'start' => date('Y-m-d H:i'),
                'end' => date('Y-m-d H:i', strtotime('+7 day')),
            ),
            'interval_time' => 10,
            'rule_url' => '',
            'winning_msg' => "恭喜您，抽中{奖品等级}{奖品名称}！\n\n{领奖链接}\n\n{规则链接}",
            'nowin_msg' => '未中奖，本次抽奖时间：{时间}',
        );
        $credit = array(
            'type' => 'credit1',
            'value' => '0',
        );
        $prizes = array();
        if ($rid) {
            $sql = "SELECT extend FROM ".tablename('superman_lottery')." WHERE rid=:rid";
            $params = array(
                ':rid' => $rid,
            );
            $extend = pdo_fetchcolumn($sql, $params);
            if ($extend) {
                $extend = unserialize($extend);
                $base = $extend['base'];
                $credit = isset($extend['credit'])?$extend['credit']:array(
                    'type' => 'credit1',
                    'value' => '0',
                );
            }

            $sql = "SELECT * FROM ".tablename('superman_lottery_prize')." WHERE rid=:rid ORDER BY displayorder ASC";
            $prizes = pdo_fetchall($sql, $params);
        }

        include $this->template('rule');
    }

    public function fieldsFormValidate($rid = 0) {
        global $_GPC, $_W;

        if (!is_numeric($_GPC['base']['play_total']) || $_GPC['base']['play_total'] < 0) {
            return '抽奖总次数参数非法，请重新填写';
        }
        /*$starttime = strtotime($_GPC['base']['activity_time']['start']);
        $endtime = strtotime($_GPC['base']['activity_time']['end']);
        if ($endtime < TIMESTAMP) {
            return '活动时间参数非法，请重新选择';
        }*/
        if (!is_numeric($_GPC['base']['interval_time']) || $_GPC['base']['interval_time'] < 0) {
            return '关键字发送间隔参数非法，请重新填写';
        }
        if (!is_numeric($_GPC['credit']['value']) || $_GPC['credit']['value'] < 0) {
            return '积分数参数非法，请重新填写';
        }
        return '';
    }

    public function fieldsFormSubmit($rid) {
        global $_GPC, $_W;

        $new_extend = array(
            'base' => $_GPC['base'],
            'credit' => $_GPC['credit'],
        );
        if ($rid) {
            $sql = "SELECT extend FROM ".tablename('superman_lottery')." WHERE rid=:rid";
            $params = array(
                ':rid' => $rid,
            );
            $extend = pdo_fetchcolumn($sql, $params);
            $data = array(
                'extend' => serialize($new_extend),
            );
            if (!$extend) {
                $data['rid'] = $rid;
                pdo_insert('superman_lottery', $data);
                $id = pdo_insertid();
                if (!$id) {
                    message('保存失败，请重试！', referer(), 'error');
                }
            } else {
                $condition = array(
                    'rid' => $rid,
                );
                $ret = pdo_update('superman_lottery', $data, $condition);
                if ($ret === false) {
                    message('保存失败，请重试！', referer(), 'error');
                }
            }

            if ($_GPC['prizes']['title']) {
                foreach ($_GPC['prizes']['title'] as $k=>$v) {
                    if ($_GPC['prizes']['title'][$k] == ''
                        || $_GPC['prizes']['probability'][$k] == '') {
                        continue;
                    }
                    $id = intval($_GPC['prizes']['id'][$k]);
                    $data = array(
                        'rid' => $rid,
                        'title' => $_GPC['prizes']['title'][$k],
                        'name' => $_GPC['prizes']['name'][$k],
                        'total' => intval($_GPC['prizes']['total'][$k]),
                        'remain_total' => intval($_GPC['prizes']['remain_total'][$k]),
                        'probability' => $_GPC['prizes']['probability'][$k],
                        'displayorder' => $k,
                    );
                    if ($id > 0) {
                        $condition = array(
                            'id' => $id,
                        );
                        pdo_update('superman_lottery_prize', $data, $condition);
                    } else {
                        pdo_insert('superman_lottery_prize', $data);
                    }
                }
            }
        } else {
            message('规则不存在或已删除', referer(), 'error');
        }
        message('操作成功！', referer(), 'success');
    }

    public function ruleDeleted($rid) {
        global $_GPC, $_W;
        $condition = array(
            'rid' => $rid,
        );
        pdo_delete('superman_lottery', $condition);
        pdo_delete('superman_lottery_prize', $condition);
        pdo_delete('superman_lottery_log', $condition);
        return true;
    }
}