<?php
class FlashUserService
{
    public function addUserScore($score, $openid, $log = '')
    {
        $this->updateUserScore($score, $openid, $log);
    }
    public function reduceUserScore($score, $openid, $log = '')
    {
        if ($score < 0) {
            $this->updateUserScore($score, $openid, $log);
        } else {
            $this->updateUserScore($score * -1, $openid, $log);
        }
    }
    public function updateUserScore($score, $openid, $log = '')
    {
        load()->model('mc');
        $uid        = mc_openid2uid($openid);
        $log_arr    = array();
        $log_arr[0] = $uid;
        $log_arr[1] = ($log == '' ? '未记录' : $log);
        mc_credit_update($uid, 'credit1', $score, $log_arr);
    }
    public function fetchUserScore($openid)
    {
        load()->model('mc');
        $uid     = mc_openid2uid($openid);
        $credits = mc_credit_fetch($uid, array(
            'credit1'
        ));
        return $credits['credit1'];
    }
    public function fetchUserMoney($openid)
    {
        load()->model('mc');
        $uid     = mc_openid2uid($openid);
        $credits = mc_credit_fetch($uid, array(
            'credit2'
        ));
        return $credits['credit2'];
    }
    public function fetchUserCredit($openid)
    {
        load()->model('mc');
        $uid     = mc_openid2uid($openid);
        $credits = mc_credit_fetch($uid);
        return $credits;
    }
    public function fetchFansInfo($openid)
    {
        global $_W;
        load()->model('mc');
        $uid  = mc_openid2uid($openid);
        $user = mc_fansinfo($_W['member']['uid'], $_W['acid'], $_W['uniacid']);
        if (empty($user)) {
            return null;
        }
        $user['credit'] = $this->fetchUserCredit($openid);
        $user['score']  = intval($user['credit']['credit1']);
        $user['money']  = $user['credit']['credit2'];
        return $user;
    }
    public function authFansInfo()
    {
        global $_W;
        load()->model('mc');
        $user = $this->fetchFansInfo($_W['openid']);
        if (empty($user)) {
            $user = mc_oauth_userinfo();
        }
        $user['credit'] = $this->fetchUserCredit($_W['openid']);
        $user['score']  = intval($user['credit']['credit1']);
        $user['money']  = $user['credit']['credit2'];
        return $user;
    }
    public function fetchUid($openid)
    {
        load()->model('mc');
        $uid = mc_openid2uid($openid);
        return $uid;
    }
}