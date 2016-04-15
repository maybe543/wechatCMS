<?php
/**
 * 犀牛溜冰场模块处理
 *
 * @author Edward Gao <edward@weini.me>
 * @copyright 2014-2015 WeiNi Tech
 * @license MIT
 * @todo 清理代码，完善功能
 */
defined('IN_IA') or exit('Access Denied');

class Weini_rhinoModuleProcessor extends WeModuleProcessor {
    public $table_reply = 'weini_rhino_reply';
    public $table_rank = 'weini_rhino_rank';
    public $table_fans = 'weini_rhino_fans';

    public function respond() {
        //	$content = $this->message['content'];
        global $_W;

        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename($this->table_reply) . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));

        if ($row == false) {
            return $this->respText("活动已取消...");
        }

        if ($row['status'] == 0) {
            return $this->respText("活动暂停，请稍后...");
        }

        if ($row['starttime'] > TIMESTAMP) {
            return $this->respText("活动未开始，请等待...");
        }

        if ($row['endtime'] < TIMESTAMP) {
            return $this->respNews(array(
                'Title' => $row['end_title'],
                'Description' => $row['end_description'],
                'PicUrl' => $_W['siteroot'] . "attachment/" . $row['end_picurl'],
                'Url' => $this->createMobileUrl('rank', array('id' => $rid)),
            ));
        } else {
            return $this->respNews(array(
                'Title' => $row['title'],
                'Description' => $row['description'],
                'PicUrl' => $_W['siteroot'] . "attachment/" . $row['picture'],
                'Url' => $this->createMobileUrl('index', array('id' => $rid)),
            ));
        }
    }
}