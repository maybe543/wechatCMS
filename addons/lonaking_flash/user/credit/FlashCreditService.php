<?php
require_once dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)) . '/../../FlashCommonService.php';
require_once dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)) . '/../../FlashUserService.php';
class FlashCreditService extends FlashCommonService
{
    private $flashUserService;
    public function __construct()
    {
        $this->table_name       = "mc_credits_record";
        $this->columns          = "*";
        $this->plugin_name      = "lonaking_flash";
        $this->flashUserService = new FlashUserService();
    }
    public function fetchUserCreditRecordPage($openid, $pageIndex = "", $pageSize = "", $creditType = "credit1")
    {
        $pageIndex = max(1, $pageIndex);
        $pageSize  = $pageSize >= 10 ? $pageSize : 10;
        $uid       = $this->flashUserService->fetchUid($openid);
        $page      = $this->selectPageOrderBy("AND uid={$uid} AND credittype='{$creditType}'", "createtime DESC,", $pageIndex, $pageSize);
        return $page;
    }
}