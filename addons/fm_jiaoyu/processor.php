<?php
/**
 * 微教育模块处理程序
 *
 * @author 高贵血迹
 * @url http://bbs.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class Fm_jiaoyuModuleProcessor extends WeModuleProcessor {
    public $name = 'Fm_jiaoyuModuleProcessor';
    public $table_reply = 'wx_school_reply';
    public $table_index = 'wx_school_index';

    public function respond() {
        global $_W;
        $rid = $this->rule;
        $fromuser = $this->message['from'];

        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
            if ($reply) {
                $sql = 'SELECT * FROM ' . tablename($this->table_index) . ' WHERE `weid`=:weid AND `id`=:schoolid';
                $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':schoolid' => $reply['schoolid']));
                $news = array();
                $news[] = array(
                    'title' => $activity['title'],
                    'description' => trim(strip_tags($activity['info'])),
                    'picurl' => $this->getpicurl($activity['thumb']),
                    'url' => $this->createMobileUrl('detail', array('schoolid' => $activity['id'])),
                );
                return $this->respNews($news);
            }
        }
        return null;
    }

    private function getpicurl($url) {
        global $_W;
        if ($url) {
            return $_W['attachurl'] . $url;
        } else {
            return MODULE_URL . 'public/mobile/img/1.jpg';
        }
    }
}