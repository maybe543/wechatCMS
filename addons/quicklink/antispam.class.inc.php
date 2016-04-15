<?php
 class AntiSpam{
    private static $t_follow = 'quickspread_follow';
    private static $t_wechat = 'wechats_modules';
    public function filter($weid, $leader, $follower){
        global $_W;
        yload() -> classs('quickcenter', 'wechatsetting');
        $_settings = new WechatSetting();
        $setting = $_settings -> get($weid, 'quicklink');
        if (empty($setting)){
            $time_threshold = 120;
            $user_threshold = 10;
        }else{
            WeUtility :: logging("setting2", $setting);
            $time_threshold = empty($setting['antispam_time_threshold']) ? 120 : $setting['antispam_time_threshold'];
            $user_threshold = empty($setting['antispam_user_threshold']) ? 10 : $setting['antispam_user_threshold'];
            $antispam_admin = $setting['antispam_admin'];
        }
        $since = TIMESTAMP - $time_threshold;
        $usercount = $user_threshold + 100;
        if ($setting['antispam_enable'] == 1){
            $result = pdo_fetch("SELECT count(*) as count, SUM(credit) as credit FROM " . tablename(self :: $t_follow) . " WHERE leader = :leader AND weid = :weid AND createtime > :since", array(':leader' => $leader, ':weid' => $weid, ':since' => $since));
            $count = $result['count'];
            $credit = $result['credit'];
            if ($count < $user_threshold){
                $count = 0;
            }
            if ($count > 0 and !empty($antispam_admin)){
                yload() -> classs('quickcenter', 'wechatapi');
                yload() -> classs('quickcenter', 'fans');
                $_weapi = new WechatAPI();
                $_fans = new Fans();
                $fans = $_fans -> get($weid, $leader);
                $black_url = $_W['siteroot'] . murl('entry/module/blacklist', array('weid' => $weid, 'm' => 'quicklink', 'from_user' => $leader));
                $warning = "[报警] 检测到刷分攻击, {$fans['nickname']}(OPENID:{$fans['from_user']})在{$time_threshold}秒内至少增加{$count}个下线，至少获得{$credit}分.<a href='{$black_url}'>立即移入黑名单</a>";
                $_weapi -> sendText($antispam_admin, $warning);
                WeUtility :: logging('WARNING:' . $antispam_admin, $warning);
            }
        }
        return $count;
    }
}
?>
