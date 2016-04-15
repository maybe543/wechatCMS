<?php

defined('IN_IA') or exit('Access Denied');
load()->func('communication');
   load()->func('tpl');
class Yg_hcityModuleSite extends WeModuleSite {
public $table_reply = 'yg_hcity_reply';
public $table_oauth = 'yg_hcity_oauth';
public $table_info = 'yg_hcity_info';
    function __construct()
    {
        global $_W, $_GPC;

        $string = $_SERVER['REQUEST_URI'];
        
        if (strpos($string, 'app') == true) {
            if (strpos($string, 'indexfuzhu') == true) {

            }else{
                $this->jboauth();
            }
            
        }
    }

    public function doMobileImport() {
         global $_W,$_GPC;
         $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " LIMIT 0,1");
           $sucurl=$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('indexfuzhu',array('id' => $id)));
            die('<script>location.href =" '.$sucurl.'";</script>');
    }
    public function  doMobileIndex(){
            global $_W,$_GPC;   
            
            $id = $_GPC['id'];
             $sucurl=$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('indexfuzhu',array('id' => $id)));
            die('<script>location.href =" '.$sucurl.'";</script>');
   }
    public function doMobileindexfuzhu(){
        //这个操作被定义用来呈现 功能封面
            global $_W,$_GPC;
            //判断活动是否开启
         
            $this->checkact();
            $id = $_GPC['id'];
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(':id' => $id ));
            $one = $reply['pwd']['0'];
            $two = $reply['pwd']['1'];
            $there = $reply['pwd']['2'];
            $four = $reply['pwd']['3'];
            //$signPackage = $this->getSignPackage();
            $info = pdo_fetch("select * from " . tablename($this->table_info) . " where logoopenid = :logoopenid and uniacid =:uniacid", array(':logoopenid' => $_W['openid'], ':uniacid' => $_W['uniacid']));
            $nickname = $info['nickname'];
            $openid = $info['openid'];
           $headimgurl = $info['headimgurl'];
            include $this->template('index');
    }

  public   function  checkact(){
    global $_W,$_GPC;
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(':id' => $_GPC['id']));
            if($reply) {
                if ($reply['starttime'] > time()) {//检测时间是否开始
                     echo "本次活动尚未开始,敬请期待！";exit;
     
                }elseif ($reply['endtime'] < time() || $reply['status'] == 0) {//检测时间是否结束或者状态是否为结束
                    echo "本次活动已经结束，请关注我们后续的活动！";exit;
                    
                }elseif ($reply['status'] == 2) {//检测状态是否暂停
                    
                    echo "本次活动暂停中";exit;
                }
                
            }
    
   }
   
    public function getOauthCode($data, $key)
    {
        global $_GPC, $_W;

        $forward = urlencode($data);

        //snsapi_userinfo//snsapi_base
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $key . '&redirect_uri=' . $forward . '&response_type=code&scope=snsapi_userinfo&wxref=mp.weixin.qq.com#wechat_redirect';
        header('location:' . $url);
    }

    public function jboauth()
    {
        global $_GPC, $_W;
    
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            die("本页面仅支持微信访问!非微信浏览器禁止浏览!");

        }
        $serverapp = $_W['account']['level'];    //是否为高级号

        //借用还是本身为认证号
        if ($serverapp==4) {
            //借用还是本身为认证号
            $appid = $_W['account']['key'];
            $secret = $_W['account']['secret'];
        }else{
            $cfg = pdo_fetch("select * from ".tablename($this->table_oauth)." where 1=1 and weid={$_W['weid']}");
            $appid = $cfg['appid'];
            $secret = $cfg['secret'];
        }
       

        /* if (!empty($_COOKIE['hc_openid'])) {
        
            $user['nickname'] = $_COOKIE['hc_nickname'];
            $user['openid'] = $_COOKIE['hc_openid'];
            $user['headimgurl'] = $_COOKIE['hc_headimgurl'];
        } else */
    
          $info = pdo_fetch("select * from " . tablename($this->table_info) . " where logoopenid = :logoopenid and uniacid =:uniacid", array(':logoopenid' => $_W['openid'], ':uniacid' => $_W['uniacid']));
        if (!empty($info)) {
            $user['nickname'] = $info['nickname'];
            $user['openid'] = $info['openid'];
            $user['headimgurl'] = $info['headimgurl'];
        } else{
        
            $code = $_GPC['code'];
            if (empty($code)) {
                $url = $_W['siteroot'] . $_SERVER['REQUEST_URI'];
                // $url=str_replace("///","/",$url);
               $this->getOauthCode($url, $appid);
            } else {
                if (empty($code)) {
                    $url = $_W['siteroot'] . $_SERVER['REQUEST_URI'];
                    $this->getOauthCode($url);
                } else {
                    $key = $appid;
                    $secret = $secret;
                    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $key . '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';
                    $data = ihttp_get($url);
                
                    if ($data['code'] != 200) {
                        message('诶呦,网络异常..请稍后再试..');
                    }
                    $temp = @json_decode($data['content'], true);
                    $access_token = $temp['access_token'];
                    $openid = $temp['openid'];
                    
                   $user_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid;
                // $user_url ="https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . '&openid=' . $openid;
                    $user_temp = ihttp_get($user_url);
                        
                    if ($user_temp['code'] != 200) {
                        message('诶呦,网络异常..请稍后再试..');
                    }
                    $user = @json_decode($user_temp['content'], true);
                //print_r($user);exit;
                    if (!empty($user['errocde']) || $user['errocde'] != 0) {
                        message(account_weixin_code($user['errcode']), '', 'error');//调试用查看报错提示
                    }
                    if (empty($fromuser)) {
                        $from_user = $openid;
                    }
                }
               /* setcookie("hc_nickname", $user['nickname'], time() + 3600 * 24 * 150);
                setcookie("hc_openid", $user['openid'], time() + 3600 * 24 * 150);
                setcookie("hc_headimgurl", $user['headimgurl'], time() + 3600 * 24 * 150);*/
                     $datainfo = array('uniacid' => $_W['uniacid'], 'logoopenid' => $_W['openid'], 'openid' => $user['openid'], 'nickname' => $user['nickname'], 'headimgurl' => $user['headimgurl'],);
                if (empty($info)) {
                    pdo_insert($this->table_info, $datainfo);
                } else {
                    $wheredata = array('id' => $info['id']);
                    pdo_update($this->table_info, $datainfo, $wheredata);
                }

            }
        }

        return $user;
    }
    
 //以下都是分享的
    public function getSignPackage()
    {
        global $_GPC, $_W;

      $serverapp = $_W['account']['level'];    //是否为高级号

        //借用还是本身为认证号
        if ($serverapp==4) {
            //借用还是本身为认证号
            $appid = $_W['account']['key'];
            $secret = $_W['account']['secret'];
        }else{
            $cfg = pdo_fetch("select * from ".tablename($this->table_oauth)." where 1=1 and weid={$_W['weid']}");
            $appid = $cfg['appid'];
            $secret = $cfg['secret'];
        } 
        $jsapiTicket = $this->getJsApiTicket();
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId" => $appid,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket()
    {

        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $urlb = __FILE__;
        $urlb = str_replace('site.php','',$urlb);
        
        $data = json_decode(file_get_contents($urlb."jsapi/jsapi_ticket.json"));
        
        if ($data->expire_time < time()) {
          
            $accessToken = $this->getAccessToken();
            $url = "http://api.weixin.qq.com/cgi-bin/ticket/getticket?type=1&access_token=$accessToken";
            $res = json_decode(file_get_contents($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
    
                $fp = fopen($urlb."jsapi/jsapi_ticket.json", "w");
        //fwrite($fp,"Hello World. Testing!");

               fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
        //exit;
            $ticket = $data->jsapi_ticket;
        }

        return $ticket;
    }

    private function getAccessToken()
    {
        global $_GPC, $_W;
       
   $serverapp = $_W['account']['level'];    //是否为高级号

        //借用还是本身为认证号
        if ($serverapp==4) {
            //借用还是本身为认证号
            $appid = $_W['account']['key'];
            $secret = $_W['account']['secret'];
        }else{
            $cfg = pdo_fetch("select * from ".tablename($this->table_oauth)." where 1=1 and weid={$_W['weid']}");
            $appid = $cfg['appid'];
            $secret = $cfg['secret'];
        }

        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $urla = __FILE__;
        $urla = str_replace('site.php','',$urla);
        $data = json_decode(file_get_contents($urla."jsapi/access_token.json"));

        if ($data->expire_time < time()) {

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";

             $res= json_decode(file_get_contents($url));

           $access_token  = $res->access_token;

            if ($access_token) {

                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
            
                $fp = fopen($urla."jsapi/access_token.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
    }

    private function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
 
        curl_close($curl);

        return $res;
    }
}