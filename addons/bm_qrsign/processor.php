<?php
defined('IN_IA') or exit('Access Denied');
class bm_qrsignModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        global $_W;
        load()->func('compat.biz');
        $rid   = $this->rule;
        $sql   = "SELECT * FROM " . tablename('bm_qrsign_reply') . " WHERE `rid`=:rid LIMIT 1";
        $reply = pdo_fetch($sql, array(
            ':rid' => $rid
        ));
        if (empty($reply['id'])) {
            return $this->respText("系统升级中，请稍候！");
        }
        if (time() > strtotime($reply['endtime'])) {
            if (empty($reply['memo2'])) {
                $msg = '对不起，活动已经于' . $reply['endtime'] . '结束，感谢您的参与！！！';
            } else {
                $msg = $reply['memo2'];
            }
            return $this->respText($msg);
        }
        if (time() < strtotime($reply['starttime'])) {
            if (empty($reply['memo1'])) {
                $msg = '对不起，活动将于' . $reply['starttime'] . '开始，敬请期待！！！';
            } else {
                $msg = $reply['memo1'];
            }
            return $this->respText($msg);
        }
        $url                      = $_W['siteroot'] . 'app/' . $this->createMobileUrl('pay', array(
            'rid' => $rid,
            'from_user' => $this->message['from']
        ));
        $response['FromUserName'] = $this->message['to'];
        $response['ToUserName']   = $this->message['from'];
        $response['MsgType']      = 'news';
        $response['ArticleCount'] = 1;
        $response['Articles']     = array();
        $response['Articles'][]   = array(
            'Title' => $reply['title'],
            'Description' => $reply['desc'],
            'PicUrl' => !strexists($reply['picurl'], 'http://') ? $_W['attachurl'] . $reply['picurl'] : $reply['picurl'],
            'Url' => $url,
            'TagName' => 'item'
        );
        return $response;
    }
}
?>