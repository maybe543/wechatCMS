
<?php
global $_W, $_GPC;
if (!empty($_W['openid']) && intval($_W['account']['level']) >= 3) {
    $accObj   = WeiXinAccount::create($_W['account']);
    $userinfo = $accObj->fansQueryInfo($_W['openid']);
}
//$url      = "http://9yetech.com/huxu/app/index.php?i=11&c=entry&code=A77683186&module=A86604982&url=" . $_W['siteroot'] . "&do=auth&m=jy_auth";
//$response = ihttp_get($url);
//$oauth    = @json_decode($response['content'], true);
//if ($oauth != '1') {
//    exit;
//}
$state                = 'we7sid-' . $_W['session_id'];
$_SESSION['dest_url'] = base64_encode($_SERVER['QUERY_STRING']);
$op                   = $_GPC['op'];
$member_id            = $_GPC['member_id'];
$code                 = $_GET['code'];
$from_user            = $_W['openid'];
if (empty($code)) {
    if ($userinfo['subscribe'] == 0) {
        if ($op == 'huodong') {
            $weid = $_W['uniacid'];
            $api  = pdo_fetch("SELECT url FROM " . tablename('jy_crowdfunding_setting') . " WHERE weid=" . $weid);
            if (!empty($api['url'])) {
                echo "<script>
						window.location.href = '" . $api['url'] . "';					
					</script>";
            }
        }
        $url      = $_W['siteroot'] . 'app/' . $this->createMobileUrl('userinfo', array(
            'op' => $op,
            'member_id' => $member_id
        ));
        $callback = urlencode($url);
        $forward  = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $_W['oauth_account']['key'] . '&redirect_uri=' . $callback . '&response_type=code&scope=snsapi_userinfo&state=' . $state . '#wechat_redirect';
        header("Location: " . $forward);
    } else {
        $weid     = $_W['uniacid'];
        $fan_temp = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
        if (!empty($userinfo) && !empty($userinfo['headimgurl']) && !empty($userinfo['nickname'])) {
            $userinfo['avatar'] = $userinfo['headimgurl'];
            unset($userinfo['headimgurl']);
            $default_groupid  = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(
                ':uniacid' => $_W['uniacid']
            ));
            $data             = array(
                'uniacid' => $_W['uniacid'],
                'email' => md5($_W['openid']) . '@9yetech.com' . $op,
                'salt' => random(8),
                'groupid' => $default_groupid,
                'createtime' => TIMESTAMP,
                'nickname' => stripslashes($userinfo['nickname']),
                'avatar' => $userinfo['avatar'],
                'gender' => $userinfo['sex'],
                'nationality' => $userinfo['country'],
                'resideprovince' => $userinfo['province'] . '省',
                'residecity' => $userinfo['city'] . '市'
            );
            $data['password'] = md5($_W['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);
            if (empty($fan_temp)) {
                pdo_insert('mc_members', $data);
                $uid = pdo_insertid();
            } else {
                pdo_update('mc_members', $data, array(
                    'uid' => $fan_temp['uid']
                ));
                $uid = $fan_temp['uid'];
            }
            $record        = array(
                'openid' => $_W['openid'],
                'uid' => $uid,
                'acid' => $_W['acid'],
                'uniacid' => $_W['uniacid'],
                'salt' => random(8),
                'updatetime' => TIMESTAMP,
                'nickname' => stripslashes($userinfo['nickname']),
                'follow' => $userinfo['subscribe'],
                'followtime' => $userinfo['subscribe_time'],
                'unfollowtime' => 0,
                'tag' => base64_encode(iserializer($userinfo))
            );
            $record['uid'] = $uid;
            if (empty($fan_temp)) {
                pdo_insert('mc_mapping_fans', $record);
            } else {
                pdo_update('mc_mapping_fans', $record, array(
                    'fanid' => $fan_temp['fanid']
                ));
            }
        }
    }
} else {
    $url      = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $_W['oauth_account']['key'] . "&secret=" . $_W['oauth_account']['secret'] . "&code=" . $code . "&grant_type=authorization_code";
    $response = ihttp_get($url);
    $oauth    = @json_decode($response['content'], true);
    $url      = "https://api.weixin.qq.com/sns/userinfo?access_token={$oauth['access_token']}&openid={$oauth['openid']}&lang=zh_CN";
    $response = ihttp_get($url);
    if (!is_error($response)) {
        $userinfo             = array();
        $userinfo             = @json_decode($response['content'], true);
        $userinfo['nickname'] = stripcslashes($userinfo['nickname']);
        $userinfo['avatar']   = $userinfo['headimgurl'];
        unset($userinfo['headimgurl']);
        $_SESSION['userinfo'] = base64_encode(iserializer($userinfo));
        if (!empty($userinfo) && !empty($userinfo['avatar']) && !empty($userinfo['nickname'])) {
            $weid             = $_W['uniacid'];
            $fan_temp         = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
            $default_groupid  = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(
                ':uniacid' => $_W['uniacid']
            ));
            $data             = array(
                'uniacid' => $_W['uniacid'],
                'email' => md5($_W['openid']) . '@9yetech.com' . $op,
                'salt' => random(8),
                'groupid' => $default_groupid,
                'createtime' => TIMESTAMP,
                'nickname' => stripslashes($userinfo['nickname']),
                'avatar' => rtrim($userinfo['avatar'], '0') . 132,
                'gender' => $userinfo['sex'],
                'nationality' => $userinfo['country'],
                'resideprovince' => $userinfo['province'] . '省',
                'residecity' => $userinfo['city'] . '市'
            );
            $data['password'] = md5($_W['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);
            if (empty($fan_temp)) {
                pdo_insert('mc_members', $data);
                $uid = pdo_insertid();
            } else {
                pdo_update('mc_members', $data, array(
                    'uid' => $fan_temp['uid']
                ));
                $uid = $fan_temp['uid'];
            }
            $record        = array(
                'openid' => $_W['openid'],
                'uid' => $uid,
                'acid' => $_W['acid'],
                'uniacid' => $_W['uniacid'],
                'salt' => random(8),
                'updatetime' => TIMESTAMP,
                'nickname' => stripslashes($userinfo['nickname']),
                'follow' => $userinfo['subscribe'],
                'followtime' => $userinfo['subscribe_time'],
                'unfollowtime' => 0,
                'tag' => base64_encode(iserializer($userinfo))
            );
            $record['uid'] = $uid;
            if (empty($fan_temp)) {
                pdo_insert('mc_mapping_fans', $record);
            } else {
                pdo_update('mc_mapping_fans', $record, array(
                    'fanid' => $fan_temp['fanid']
                ));
            }
        }
    } else {
        message('微信授权获取用户信息失败,请重新尝试: ' . $response['message']);
    }
}
echo "<script>
			window.location.href = '" . $this->createMobileUrl($op, array(
    'member_id' => $member_id
)) . "';					
		</script>";
?>