<?php
class Helper
{
    private $openid;
    private $wuserinfo;
    private $fans;
    private $follow;
    private $scope = "snsapi_userinfo";
    private $access_token;
    private $code;
    private $state;
    function __construct()
    {
        global $_W;
        if ($_W["container"] != "wechat") {
            die();
        }
        $this->openid       = $_SESSION["openid"];
        $this->access_token = $_SESSION["access_token"];
        $this->code         = $_SESSION["code"];
        $this->state        = $_SESSION["state"];
        $this->follow       = $_SESSION["follow"];
        $this->fans         = $_SESSION["fans"];
        $this->wuserinfo    = $_SESSION["wuserinfo"];
    }
    public function getFans()
    {
        return $this->fans;
    }
    public function getAuthCode()
    {
        return $this->code;
    }
    public function getState()
    {
        return $this->state;
    }
    public function getAccessToken()
    {
        return $this->access_token;
    }
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
    public function getFollow()
    {
        return $this->follow;
    }
    public function getOpenid()
    {
        return $this->openid;
    }
    public function getUid()
    {
        $userinfo = $this->userinfo;
        return $userinfo["uid"];
    }
    public function WechatOauth2()
    {
        global $_W;
        load()->model('mc');
        if ($_W["account"]["level"] == 4 && (empty($_SESSION["openid"]) || ($this->scope == "snsapi_base" && empty($_SESSION["access_token"])))) {
            $this->getOauth();
        }
    }
    private function getCode()
    {
        global $_W;
        $url        = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . ($_SERVER["QUERY_STRING"] ? "?" . $_SERVER["QUERY_STRING"] : "");
        $oauth2_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $_W["oauth_account"]["key"] . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=" . $this->scope . "&state=bf#wechat_redirect";
        header("Location: $oauth2_url");
        exit();
    }
    private function getOauth()
    {
        global $_W, $_GPC;
        load()->func('communication');
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $_W["oauth_account"]["key"] . "&secret=" . $_W["oauth_account"]["secret"] . "&code=" . $_GPC["code"] . "&grant_type=authorization_code";
        $content     = ihttp_get($oauth2_code);
        $token       = @json_decode($content["content"], true);
        if ($token["errcode"] == 41008 || empty($_GPC["code"])) {
            $this->getCode();
        }
        if (empty($token["access_token"]) || empty($token["openid"])) {
            message("网页授权access_token失败", referer(), "error");
        }
        if ($_W["account"]["level"] == 4) {
            $fans = pdo_fetch("SELECT * FROM " . tablename("mc_mapping_fans") . " WHERE uniacid=:uniacid AND acid=:acid AND openid=:openid", array(
                ":uniacid" => $_W["uniacid"],
                ":acid" => $_W["account"]["acid"],
                ":openid" => $token["openid"]
            ));
        } else {
            $fans = pdo_fetch("SELECT * FROM " . tablename("mc_mapping_fans") . " WHERE uniacid=:uniacid AND acid=:acid AND unionid=:unionid", array(
                ":uniacid" => $_W["uniacid"],
                ":acid" => $_W["account"]["acid"],
                "unionid" => $token["unionid"]
            ));
        }
        $_SESSION["openid"]       = $token["openid"];
        $_SESSION["access_token"] = $token["access_token"];
        $_SESSION["code"]         = trim($_GPC["code"]);
        $_SESSION["state"]        = trim($_GPC["state"]);
        $_SESSION["follow"]       = $fans["follow"];
        $_SESSION["fans"]         = $fans;
        $this->openid             = $token["openid"];
        $this->access_token       = $token["access_token"];
        $this->code               = trim($_GPC["code"]);
        $this->state              = trim($_GPC["state"]);
        $this->follow             = $fans["follow"];
        $this->fans               = $fans;
        if ($this->scope == "snsapi_userinfo") {
            $userinfo_url          = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $token["access_token"] . "&openid=" . $token["openid"] . "&lang=zh_CN";
            $content               = ihttp_get($userinfo_url);
            $wuserinfo             = @json_decode($content["content"], true);
            $_SESSION["wuserinfo"] = $wuserinfo;
            $this->wuserinfo       = $_SESSION["wuserinfo"];
        }
        return true;
    }
}