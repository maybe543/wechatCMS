<?php
<?php
defined('IN_IA') or exit('Access Denied');
class q_3354988381_xuyuanModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename('dream_reply') . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(
            ':rid' => $rid
        ));
        if ($row == false) {
            return $this->respText("活动已取消...");
        }
        if ($row['isshow'] == 0) {
            return $this->respText("活动未开始，请等待...");
        }
        if ($row['endtime'] > time()) {
            return $this->respNews(array(
                'Title' => $row['title'],
                'PicUrl' => tomedia($row['picurl']),
                'Url' => $this->createMobileUrl('index', array(
                    'id' => $rid
                ))
            ));
        } else {
            return $this->respText("活动已结束，下次再来吧！");
        }
    }
}