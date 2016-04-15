<?php
defined("IN_IA") or print("Access Denied");
class qw_zfModule extends WeModule
{
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        $fu = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&c=entry&do=fu&m=qw_zf";
        $fu = urlencode($fu);
        if (checksubmit()) {
            $cfg = array(
                'jfbl' => $_GPC['jfbl'] ? $_GPC['jfbl'] : 1,
                'addjf_templateid' => $_GPC['addjf_templateid'],
                'gl_templateid' => $_GPC['gl_templateid'],
                'gl_openid' => $_GPC['gl_openid'],
                'info' => $_GPC['info'],
                'logo' => $_GPC['logo'],
                'title' => $_GPC['title'],
                'sitegame' => $_GPC['sitegame'],
                'succ_templateid' => $_GPC['succ_templateid']
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('setting');
    }
}