<?php
defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/quicklink/define.php';
require_once IA_ROOT . '/addons/quickcenter/loader.php';
require IA_ROOT . '/addons/quickcenter/data_template.php';
class QuickLinkModuleSite extends WeModuleSite{
    private static $t_black = 'quickspread_blacklist';
    private static $t_qr = 'quickspread_qr';
    private static $t_follow = 'quickspread_follow';
    private static $t_credit = 'quickspread_credit';
    private static $t_channel = 'quickspread_channel';
    private static $t_sys_fans = 'mc_mapping_fans';
    private static $t_sys_member = 'mc_members';
    public function refreshUserInfo($weid, $from_user){
        global $_W;
        yload() -> classs('quickcenter', 'fans');
        $_fans = new Fans();
        $userInfo = $_fans -> refresh($weid, $from_user);
        WeUtility :: logging('refresh', $userInfo);
        return $userInfo;
    }
    public function doMobileFollow(){
        global $_W;
        $fans = $this -> refreshUserInfo($_W['weid'], $_W['fans']['from_user']);
        $mylist = pdo_fetchall("SELECT a.createtime createtime, c.nickname, c.avatar, b.openid from_user FROM " . tablename(self :: $t_follow) . " a LEFT JOIN " . tablename(self :: $t_sys_fans) . " b ON a.follower = b.openid AND a.weid=b.uniacid LEFT JOIN " . tablename(self :: $t_sys_member) . " c ON b.uid=c.uid " . " WHERE leader=:leader AND a.weid=:weid ORDER BY createtime DESC LIMIT 500", array(':leader' => $_W['fans']['from_user'], ':weid' => $_W['weid']));
        $is_follow = true;
        include $this -> template('follow');
    }
    public function doMobileCredit(){
        global $_W;
        $fans = $this -> refreshUserInfo($_W['weid'], $_W['fans']['from_user']);
        $mylist = pdo_fetchall("SELECT createtime, type, credit FROM " . tablename(self :: $t_credit) . " WHERE from_user=:from_user AND weid=:weid ORDER BY createtime DESC", array(':from_user' => $_W['fans']['from_user'], ':weid' => $_W['weid']));
        include $this -> template('credit');
    }
    public function doMobileTop(){
        $this -> doMobileTopFollow();
    }
    public function doMobileTopFollow(){
        global $_W;
        yload() -> classs('quicklink', 'topfollow');
        $_topfollow = new TopFollow();
        $psize = empty($this -> module['config']['top_cnt']) ? 10 : $this -> module['config']['top_cnt'];
        $mylist = $_topfollow -> get($_W['weid'], $psize);
        $fans = $this -> refreshUserInfo($_W['weid'], $_W['fans']['from_user']);
        include $this -> template('topfollow');
    }
    public function doMobileTopCredit(){
        global $_W;
        yload() -> classs('quicklink', 'topcredit');
        $_topcredit = new TopCredit();
        $psize = empty($this -> module['config']['top_cnt']) ? 10 : $this -> module['config']['top_cnt'];
        $mylist = $_topcredit -> get($_W['weid'], $psize);
        $fans = $this -> refreshUserInfo($_W['weid'], $_W['fans']['from_user']);
        include $this -> template('topcredit');
    }
    public function doWebTopList(){
        global $_W, $_GPC;
        $this -> doWebAuth();
        yload() -> classs('quicklink', 'topfollow');
        $_topfollow = new TopFollow();
        if (checksubmit()){
            $_GPC['op'] = 'refresh';
        }else if (checksubmit('manual')){
            $_GPC['op'] = 'manualrefresh';
        }
        $op = empty($_GPC['op']) ? 'setting' : $_GPC['op'];
        if ($op == 'refresh'){
            $psize = 20;
            $pindex = 1;
            $orderby = ' follower_credit';
            $having = ' HAVING follower_count > 0 ';
            $my_follows_sql = "SELECT a.createtime createtime, nickname, avatar, from_user, COUNT(b.follower) follower_count, SUM(b.credit) follower_credit, credit1 FROM  " . tablename('fans') . " a LEFT JOIN " . tablename(self :: $t_follow) . " b ON a.from_user = b.leader AND a.weid=b.weid " . " WHERE a.weid=:weid GROUP BY a.from_user " . $having . " ORDER BY " . $orderby . " DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
            $mylist = pdo_fetchall($my_follows_sql, array(':weid' => $_W['weid']));
            $ret = $_topfollow -> updateCache($_W['weid'], $mylist);
            if (empty($ret)){
                message('更新缓存失败', '', 'error');
            }
            message('更新缓存成功', referer(), 'sucess');
        }elseif ($op == 'manualrefresh'){
            message('手工更新缓存成功', referer(), 'sucess');
        }else{
            $mylist = $_topfollow -> get($_W['weid']);
        }
        include $this -> template('top');
    }
    public function doWebSpread(){
        global $_W, $_GPC;
        $this -> doWebAuth();
        load() -> func('tpl');
        yload() -> classs('quickcenter', 'FormTpl');
        yload() -> classs('quicklink', 'channel');
        $_channel = new Channel();
        $op = empty($_GPC['op']) ? 'leaflet' : $_GPC['op'];
        if ($op == 'delete'){
            $ret = $_channel -> remove($_W['weid'], intval($_GPC['channel']));
            message("删除成功", referer(), "success");
        }else if ($op == 'leaflet'){
            yload() -> classs('quicklink', 'follow');
            yload() -> classs('quicklink', 'channelreply');
            $_follow = new Follow();
            $_channelreply = new ChannelReply();
            $mylist = $_channel -> batchGet($_W['weid']);
            $keywords = array();
            foreach ($mylist as $item){
                $words = $_channelreply -> getKeyword($_W['weid'], $item['channel']);
                $keywords[$item['channel']] = $words;
                unset($item);
            }
            $followCount = $_follow -> getAllChannelFollowCount($_W['weid']);
        }else if ($op == 'active'){
            $channel_id = intval($_GPC['channel']);
            $item = $_channel -> getActive($_W['weid']);
            $_channel -> setActive($_W['weid'], $channel_id);
            message('成功！当用户通过分享链接关注本微信号后，会根据本传单设定的积分数给上线送积分', referer(), 'success');
        }else if ($op == 'refresh'){
            $_channel -> refresh($_W['weid'], intval($_GPC['channel']));
            message('清除二维码传单缓存成功', referer(), 'success');
        }else if ($op == 'post'){
            if (checksubmit('submit')){
                $this -> assertIsJPG($_GPC['attachment']);
                if (!empty($_GPC['channel'])){
                    $_channel -> update($_W['weid'], intval($_GPC['channel']), $_GPC);
                    message('更新传单成功', referer(), 'success');
                }else{
                    $list_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(self :: $t_channel) . " WHERE weid=:weid AND deleted = 0", array(":weid" => $_W['weid']));
                    $active = ($list_count == 0);
                    $_channel -> create($_W['weid'], $active, $_GPC);
                    message('新建传单成功', $this -> createWebUrl('spread', array('op' => 'leaflet')), 'success');
                }
            }
            $item = array();
            if (!empty($_GPC['channel'])){
                $item = $_channel -> get($_W['weid'], $_GPC['channel']);
            }else{
                $item = $_channel -> decode_channel_param($item, null);
            }
        }else if ($op == 'log' or $op == 'qualitylog'){
            $having = ' ';
            $orderby = ' b.createtime ';
            if ($op == 'qualitylog'){
                $having = ' HAVING follower_count > 0 ';
                $orderby = ' follower_count ';
            }
            $where = '';
            if (isset($_GPC['search']) && ($_GPC['search'] == $op)){
                $where = " AND (from_user='" . $_GPC['keyword'] . "') ";
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $poffset = ($pindex - 1) * $psize;
            $my_follows_sql = "SELECT t_member.createtime createtime, t_member.nickname, avatar, t_follow.from_user, t_member.credit1, " . "                      t_follow.follower_count, t_follow.follower_credit " . " FROM (SELECT COUNT(b.follower) follower_count, SUM(b.credit) follower_credit, b.leader from_user, weid, leader FROM " . tablename(self :: $t_follow) . " b " . " WHERE weid={$_W['weid']} {$where} GROUP BY b.leader {$having} ORDER BY {$orderby} DESC LIMIT {$poffset}, {$psize}) t_follow " . " LEFT JOIN " . tablename(self :: $t_sys_fans) . " t_fans ON t_follow.weid = t_fans.uniacid AND t_follow.leader = t_fans.openid " . " LEFT JOIN " . tablename(self :: $t_sys_member) . " t_member ON t_fans.uniacid = t_member.uniacid AND t_fans.uid = t_member.uid";
            $my_follows_size_sql = "SELECT COUNT(follower) follower_count FROM " . tablename(self :: $t_follow) . " b WHERE weid={$_W['weid']} {$where} GROUP BY b.leader {$having}";
            $mylist = pdo_fetchall($my_follows_sql);
            $total = pdo_fetchcolumn($my_follows_size_sql);
            $pager = pagination($total, $pindex, $psize);
        }else if ($op == 'user'){
            $from_user = $_GPC['from_user'];
            $uplevel = pdo_fetch("SELECT * FROM " . tablename(self :: $t_follow) . " WHERE weid=:weid AND follower=:follower", array(":weid" => $_W['weid'], ":follower" => $from_user));
            $fans = fans_search($from_user, array('avatar', 'nickname', 'createtime', 'credit1'));
            $mylist = pdo_fetchall("SELECT a.createtime createtime, c.nickname, avatar, openid from_user, credit FROM " . tablename(self :: $t_follow) . " a LEFT JOIN " . tablename(self :: $t_sys_fans) . " b ON a.follower = b.openid AND a.weid=b.uniacid LEFT JOIN " . tablename(self :: $t_sys_member) . " c ON b.uniacid = c.uniacid AND b.uid = c.uid  " . " WHERE leader=:leader AND a.weid=:weid", array(':leader' => $from_user, ':weid' => $_W['weid']));
            $from_user_list = "'xxxooo'";
            foreach($mylist as $l){
                $from_user_list .= ",'" . $l['from_user'] . "'";
            }
            if (count($mylist) > 0){
                $mylist2 = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_follow) . " a LEFT JOIN " . tablename(self :: $t_sys_fans) . " b ON a.follower = b.openid AND a.weid=b.uniacid LEFT JOIN " . tablename(self :: $t_sys_member) . " c ON b.uniacid = c.uniacid AND b.uid = c.uid  " . " WHERE  a.leader in (" . $from_user_list . ") AND a.weid=:weid", array(':weid' => $_W['weid']));
            }
        }else if ('black_remove' == $op){
            $from_user = $_GPC['from_user'];
            pdo_delete(self :: $t_black, array('from_user' => $from_user, 'weid' => $_W['weid']));
            message('执行成功', $this -> createWebUrl('Spread', array('op' => 'black')), 'success');
        }else if ('black' == $op){
            if (!empty($_GPC['from_user'])){
                $from_user = $_GPC['from_user'];
                $b = pdo_fetch("SELECT * FROM " . tablename(self :: $t_black) . " WHERE from_user=:f AND weid=:w LIMIT 1", array(':f' => $from_user, ':w' => $_W['weid']));
                if (empty($b)){
                    pdo_insert(self :: $t_black, array('from_user' => $from_user, 'weid' => $_W['weid'], 'access_time' => time()));
                }
                message('添加黑名单成功', referer(), 'success');
            }
            $list = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_black) . " WHERE weid=:w", array(':w' => $_W['weid']));
        }else{
            message('error!', '', 'error');
        }
        include $this -> template('spread');
    }
    public function doWebRanking(){
        global $_W, $_GPC;
        $this -> doWebAuth();
        $quality_threshold = 3;
        load() -> func('tpl');
        yload() -> classs('quickcenter', 'FormTpl');
        $op = empty($_GPC['op']) ? 'qualitylog' : $_GPC['op'];
        if ($op == 'log' or $op == 'qualitylog'){
            $having = ' ';
            $orderby = ' b.createtime ';
            if ($op == 'qualitylog'){
                $having = ' HAVING follower_count >  ' . $quality_threshold;
                $orderby = ' follower_count ';
            }
            $where = '';
            if (isset($_GPC['search']) && ($_GPC['search'] == $op)){
                $where = " AND (leader ='" . $_GPC['keyword'] . "') ";
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $poffset = ($pindex - 1) * $psize;
            $my_follows_sql = "SELECT t_member.createtime createtime, t_member.nickname, avatar, t_follow.from_user, t_member.credit1, " . "                      t_follow.follower_count, t_follow.follower_credit " . " FROM (SELECT COUNT(b.follower) follower_count, SUM(b.credit) follower_credit, b.leader from_user, weid, leader FROM " . tablename(self :: $t_follow) . " b " . " WHERE weid={$_W['weid']} {$where} GROUP BY b.leader {$having} ORDER BY {$orderby} DESC LIMIT {$poffset}, {$psize}) t_follow " . " LEFT JOIN " . tablename(self :: $t_sys_fans) . " t_fans ON t_follow.weid = t_fans.uniacid AND t_follow.leader = t_fans.openid " . " LEFT JOIN " . tablename(self :: $t_sys_member) . " t_member ON t_fans.uniacid = t_member.uniacid AND t_fans.uid = t_member.uid";
            $my_follows_size_sql = "SELECT COUNT(follower) follower_count FROM " . tablename(self :: $t_follow) . " b WHERE weid={$_W['weid']} {$where} GROUP BY b.leader {$having}";
            $my_follows_size_sql = "SELECT COUNT(*) FROM (" . $my_follows_size_sql . ") t";
            $mylist = pdo_fetchall($my_follows_sql);
            $total = pdo_fetchcolumn($my_follows_size_sql);
            $pager = pagination($total, $pindex, $psize);
        }else if ($op == 'user'){
            yload() -> classs('quickcenter', 'fans');
            $_fans = new Fans();
            $from_user = $_GPC['from_user'];
            $fans = $_fans -> get($_W['weid'], $from_user);
            $uplevel = pdo_fetch("SELECT * FROM " . tablename(self :: $t_follow) . " WHERE weid=:weid AND follower=:follower", array(":weid" => $_W['weid'], ":follower" => $from_user));
            $mylist = pdo_fetchall("SELECT a.createtime createtime, c.nickname, avatar, a.follower from_user, credit FROM " . tablename(self :: $t_follow) . " a LEFT JOIN " . tablename(self :: $t_sys_fans) . " b ON a.follower = b.openid AND a.weid=b.uniacid LEFT JOIN " . tablename(self :: $t_sys_member) . " c ON b.uniacid = c.uniacid AND b.uid = c.uid  " . " WHERE leader=:leader AND a.weid=:weid ORDER by a.createtime DESC", array(':leader' => $from_user, ':weid' => $_W['weid']));
            $from_user_list = "'xxxooo'";
            foreach($mylist as $l){
                $from_user_list .= ",'" . $l['from_user'] . "'";
            }
            if (count($mylist) > 0){
                $mylist2 = pdo_fetchall("SELECT a.createtime createtime, c.nickname, c.avatar, a.leader, a.follower openid FROM " . tablename(self :: $t_follow) . " a LEFT JOIN " . tablename(self :: $t_sys_fans) . " b ON a.follower = b.openid AND a.weid=b.uniacid LEFT JOIN " . tablename(self :: $t_sys_member) . " c ON b.uniacid = c.uniacid AND b.uid = c.uid  " . " WHERE  a.leader in (" . $from_user_list . ") AND a.weid=:weid ORDER BY a.createtime DESC", array(':weid' => $_W['weid']));
            }
        }else{
            message('error!', '', 'error');
        }
        include $this -> template('ranking');
    }
    public function doWebBlackList(){
        global $_W, $_GPC;
        $this -> doWebAuth();
        load() -> func('tpl');
        yload() -> classs('quickcenter', 'FormTpl');
        $op = empty($_GPC['op']) ? 'black' : $_GPC['op'];
        if ('black_remove' == $op){
            $from_user = $_GPC['from_user'];
            pdo_delete(self :: $t_black, array('from_user' => $from_user, 'weid' => $_W['weid']));
            message('执行成功', $this -> createWebUrl('Spread', array('op' => 'black')), 'success');
        }else if ('black' == $op){
            if (!empty($_GPC['from_user'])){
                $from_user = $_GPC['from_user'];
                $b = pdo_fetch("SELECT * FROM " . tablename(self :: $t_black) . " WHERE from_user=:f AND weid=:w LIMIT 1", array(':f' => $from_user, ':w' => $_W['weid']));
                if (empty($b)){
                    pdo_insert(self :: $t_black, array('from_user' => $from_user, 'weid' => $_W['weid'], 'access_time' => time()));
                }
                message('添加黑名单成功', referer(), 'success');
            }
            $list = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_black) . " WHERE weid=:w", array(':w' => $_W['weid']));
        }else{
            message('error!', '', 'error');
        }
        include $this -> template('blacklist');
    }
    public function doMobileAjaxDeleteImage(){
        global $_GPC;
        $delurl = $_GPC['pic'];
        ob_clean();
        if (file_delete($delurl)){
            echo 1;
        }else{
            echo 0;
        }
    }
    private function clearQRCache($ch){
        global $_W;
    }
    public function doWebUnlink(){
        global $_W, $_GPC;
        yload() -> classs('quicklink', 'follow');
        $_follow = new Follow();
        $_follow -> unFollow($_W['weid'], $_GPC['leader'], $_GPC['follower']);
        message('unlink success');
    }
    public function doWebUserDetail(){
        global $_W, $_GPC;
        $this -> doWebAuth();
        include $this -> template('user_detail');
    }
    public function doMobileRunTask(){
        global $_W, $_GPC;
        ignore_user_abort(true);
        yload() -> classs('quicklink', 'responser');
        $_responser = new Responser();
        $_responser -> respondText($_W['weid'], $_GPC['from_user'], intval($_GPC['channel_id']), $_GPC['rule']);
        exit(0);
    }
    private function userlink($u){
        return "<a style='color:black' href='" . $this -> CreateWebUrl('Ranking', array('from_user' => $u, 'op' => 'user')) . "'>" . $u . "</a>";
    }
    private function link($u){
        return $this -> CreateWebUrl('Ranking', array('from_user' => $u, 'op' => 'user'));
    }
    public function doMobileClearQR(){
        global $_W;
        $ret = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_qr) . " WHERE weid={$_W['weid']} LIMIT 100");
        print_r($ret);
        $ret = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_channel) . " WHERE weid={$_W['weid']}");
        print_r($ret);
        echo "开始清理QR数据库过期数据<br>";
        $ret = pdo_query("DELETE FROM " . tablename(self :: $t_qr));
        print_r($ret);
        echo "<br>";
        $ret = pdo_query("DELETE FROM " . tablename(self :: $t_follow));
        print_r($ret);
        echo "<br>";
        $ret = pdo_query("DELETE FROM " . tablename(self :: $t_credit));
        print_r($ret);
        echo "<br>";
        $ret = pdo_query("DELETE FROM " . tablename(self :: $t_channel));
        print_r($ret);
        echo "<br>";
        echo "<br>";
        $ret = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_qr) . " WHERE weid={$_W['weid']}");
        print_r($ret);
        $ret = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_channel) . " WHERE weid={$_W['weid']}");
        print_r($ret);
    }
    private function assertIsJPG($bgs){
        if (!empty($bgs)){
            foreach ($bgs as $rand_bg){
                $path_parts = explode('.', $rand_bg);
                $suffix = end($path_parts);
                if (strcasecmp('jpg', $suffix) != 0){
                    message('传单背景图必须是jpg格式。不支持png等其他格式。', referer(), 'error');
                }
            }
            $image_count_limit = 6;
            if (count($bgs) > $image_count_limit){
                message('最多只能上传' . $image_count_limit . '张背景图。', referer(), 'error');
            }
        }
    }
    public function doWebAuth(){
        global $_W, $_GPC;
        yload() -> classs('quickauth', 'auth');
        $_auth = new Auth();
        $op = trim($_GPC['op']);
        $modulename = MODULE_NAME;
        $version = '0.60';
        $_auth -> checkXAuth($op, $modulename, $version);
        yload() -> classs('quickcenter', 'dependencychecker');
        $_checker = new DependencyChecker();
        $_checker -> requireModules($_W['account']['modules'], array('fans', 'qrcode'));
    }
    public function doMobileBlackList(){
        global $_W, $_GPC;
        if (!empty($_GPC['from_user']) && !empty($_GPC['passwd'])){
            if ($_GPC['passwd'] == $this -> module['config']['antispam_passwd']){
                $from_user = $_GPC['from_user'];
                $b = pdo_fetch("SELECT * FROM " . tablename(self :: $t_black) . " WHERE from_user=:f AND weid=:w LIMIT 1", array(':f' => $from_user, ':w' => $_W['weid']));
                if (empty($b)){
                    pdo_insert(self :: $t_black, array('from_user' => $from_user, 'weid' => $_W['weid'], 'access_time' => time()));
                }
                message('添加黑名单成功', '', 'success');
            }else{
                message('密码错误', '', 'error');
            }
        }
        include $this -> template('black');
    }
}
