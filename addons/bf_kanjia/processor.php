<?php
defined('IN_IA') or exit('Access Denied');
class Bf_kanjiaModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $where  = " uniacid=:uniacid AND rid=:rid";
        $params = array(
            ":uniacid" => $_W["uniacid"],
            ":rid" => $this->rule
        );
        $kanjia = DBUtil::getKanjia($where, $params);
        if (empty($kanjia)) {
            return $this->respText("该砍价活动不存在了~");
        } else {
            if (empty($kanjia["share_title"]) || empty($kanjia["share_desc"]) || empty($kanjia["cover"])) {
                return $this->respText('<a href="' . $_W["siteroot"] . "app/" . $this->createMobileUrl("detail", array(
                    "id" => $kanjia["id"]
                )) . '">点击参与砍价</a>');
            } else {
                return $this->respNews(array(
                    "title" => $kanjia["share_title"],
                    "description" => $kanjia["share_desc"],
                    "picurl" => tomedia($kanjia["cover"]),
                    "url" => $_W["siteroot"] . "app/" . $this->createMobileUrl("detail", array(
                        "id" => $kanjia["id"]
                    ))
                ));
            }
        }
    }
}