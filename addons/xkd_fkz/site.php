<?php
defined('IN_IA') or exit('Access Denied');
define('JX_ROOT', str_replace('\\', '/', dirname(preg_replace('@\(.*\(.*$@', '', __FILE__))));
require IA_ROOT . '/addons/xkd_fkz/define.php';
require APP_PHP . 'wechatutil.php';
require APP_PHP . 'wechatapi.php';
require APP_PHP . 'usermanager.php';
require APP_PHP . 'wechatservice.php';
require_once APP_PHP . 'responser.php';
class Xkd_fkzModuleSite extends WeModuleSite
{
    public $m_setting;
    public function __construct()
    {
        include_once 'core.php';
        global $_W;
        if ($_W['os'] == 'mobile') {
            $this->checkOauth();
            $this->m_setting = $this->getSetting($_W['uniacid']);
        }
    }
    public function loadMod($class)
    {
        require_once JX_ROOT . '/mod/' . $class . '.mod.php';
    }
    public function doWebSetting()
    {
        global $_W, $_GPC;
        checklogin();
        $setting = $this->getSetting($_W['uniacid']);
        if ($_W['ispost']) {
            $post_setting = $_GPC['setting'];
            if (empty($setting)) {
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'fkz_name' => $post_setting['fkz_name'],
                    'adv_headimg' => $post_setting['adv_headimg'],
                    'adv_open' => $post_setting['adv_open']
                );
                pdo_insert('jiexi_aaa_setting', $data);
            } else {
                $data = array(
                    'fkz_name' => $post_setting['fkz_name'],
                    'adv_headimg' => $post_setting['adv_headimg'],
                    'adv_open' => $post_setting['adv_open']
                );
                pdo_update('jiexi_aaa_setting', $data, array(
                    'id' => $setting['id']
                ));
            }
            message('保存成功！', $this->createWebUrl('setting'));
        }
        load()->func('tpl');
        include $this->template('m_setting');
    }
    private function getSetting($uniacid)
    {
        $sql = "SELECT * FROM " . tablename('jiexi_aaa_setting') . " WHERE uniacid=$uniacid";
        return pdo_fetch($sql);
    }
    public function checkOauth()
    {
        global $_W;
        $fans = $_W['fans']['tag'];
        if (empty($fans)) {
            mc_oauth_userinfo();
        }
    }
    public function doMobileIndexA()
    {
        $this->checkOauth();
        global $_W, $_GPC;
        $setting = $this->getSetting($_W['uniacid']);
        include $this->template('index_a');
    }
    public function doMobileAdv()
    {
        $this->checkOauth();
        global $_W, $_GPC;
        $setting     = $this->getSetting($_W['uniacid']);
        $wechat_user = $_W['fans']['tag'];
        $adv_list    = $this->getAdvList($_W['uniacid']);
        include $this->template('adv');
    }
    public function doMobileEditAdv()
    {
        $this->checkOauth();
        global $_W, $_GPC;
        $setting     = $this->getSetting($_W['uniacid']);
        $wechat_user = $_W['fans']['tag'];
        $member      = $this->getMember($wechat_user['openid'], $_W['uniacid']);
        if (empty($member) || $member['level'] == 0)
            message('会员才能在互粉朋友圈发布内容哦，快去找找你的上级帮你升级吧！', $this->createMobileUrl('adv'));
        $adv = $this->getAdv($wechat_user['openid'], $_W['uniacid']);
        if ($_W['ispost']) {
            $post_adv = $_GPC['adv'];
            if (empty($adv)) {
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'openid' => $wechat_user['openid'],
                    'nickname' => $wechat_user['nickname'],
                    'headimgurl' => $wechat_user['avatar'],
                    'uploadimg' => $post_adv['uploadimg'],
                    'content' => $post_adv['content'],
                    'link' => $post_adv['link'],
                    'add_time' => time()
                );
                pdo_insert('jiexi_aaa_adv', $data);
            } else {
                $data = array(
                    'uploadimg' => $post_adv['uploadimg'],
                    'content' => $post_adv['content'],
                    'link' => $post_adv['link']
                );
                pdo_update('jiexi_aaa_adv', $data, array(
                    'id' => $adv['id']
                ));
            }
            message('保存成功！', $this->createMobileUrl('adv'));
        }
        load()->func('tpl');
        $wechat_user = $_W['fans']['tag'];
        include $this->template('adv_edit');
    }
    private function getAdv($openid, $uniacid)
    {
        $sql = "SELECT * FROM " . tablename('jiexi_aaa_adv') . " WHERE uniacid=$uniacid AND openid='$openid'";
        return pdo_fetch($sql);
    }
    private function getAdvList($uniacid)
    {
        $table_adv    = tablename('jiexi_aaa_adv');
        $table_member = tablename('jiexi_aaa_member');
        $sql          = "SELECT * FROM $table_adv AS adv LEFT JOIN $table_member AS member ON adv.openid=member.openid WHERE adv.uniacid=$uniacid ORDER BY `level` DESC,adv.add_time ASC LIMIT 20";
        return pdo_fetchall($sql);
    }
    private function getMember($openid, $uniacid)
    {
        $sql = "SELECT * FROM " . tablename('jiexi_aaa_member') . " WHERE uniacid=$uniacid AND openid='$openid'";
        return pdo_fetch($sql);
    }
}