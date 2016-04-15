<?php
defined('IN_IA') or exit('Access Denied');
class Bf_kanjiaModuleSite extends WeModuleSite
{
    public function __construct()
    {
    }
    public function doMobileTopAjax()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $data     = file_get_contents("php://input");
        $data     = json_decode($data, true);
        $page     = max(1, intval($data["page"]));
        $pagesize = 10;
        $where    = " uniacid=:uniacid AND kid=:kid";
        $params   = array(
            ":uniacid" => $_W["uniacid"],
            ":kid" => $data["id"]
        );
        $where .= " ORDER BY `price` ASC, `createtime` ASC";
        $recordlist = DBUtil::getKanjiaRecordSelectWhere("id,nickname,headimgurl,price,createtime", $where, $params, $page, $pagesize);
        foreach ($recordlist as $key => &$value) {
            $value["number"] = ($page - 1) * $pagesize + $key + 1;
            $value["href"]   = $this->createMobileUrl("my", array(
                "id" => $value["id"]
            ));
        }
        unset($value);
        echo json_encode($recordlist);
        exit();
    }
    public function doMobileTop()
    {
        global $_W, $_GPC;
        $this->checkAgent();
        include_once dirname(__FILE__) . '/libs.php';
        $kanjia              = DBUtil::getKanjia(" uniacid=:uniacid AND id=:id", array(
            ":uniacid" => $_W["uniacid"],
            ":id" => intval($_GPC["id"])
        ));
        $_W["page"]["title"] = $i18n["kanjia_title_top"];
        $share               = array(
            "title" => $kanjia["share_title"],
            "desc" => $kanjia["share_desc"],
            "link" => $_W["siteroot"] . "app/" . $this->createMobileUrl("detail", array(
                "id" => $kanjia["id"]
            )),
            "imgUrl" => $_W["attachurl"] . $kanjia["share_imgUrl"]
        );
        include $this->template("top");
    }
    public function doMobileHelpList()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $data       = file_get_contents("php://input");
        $data       = json_decode($data, true);
        $where      = " `uniacid`=:uniacid AND `rid`=:rid ORDER BY `createtime` DESC";
        $params     = array(
            ":uniacid" => $_W["uniacid"],
            ":rid" => intval($data["rid"])
        );
        $recordlist = DBUtil::getKanjiaHelpSelectWhere(" nickname, headimgurl, price", $where, $params);
        shuffle($recordlist);
        echo json_encode($recordlist);
        exit;
    }
    public function doMobileHelpAjax()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $data            = file_get_contents("php://input");
        $data            = json_decode($data, true);
        $where           = " `uniacid`=:uniacid AND `id`=:id";
        $params          = array(
            ":uniacid" => $_W["uniacid"],
            ":id" => intval($data["id"])
        );
        $data            = array();
        $record          = DBUtil::getKanjiaRecord($where, $params);
        $kanjia          = DBUtil::getKanjia(" `uniacid`=:uniacid AND `id`=:id", array(
            ":uniacid" => $_W["uniacid"],
            ":id" => $record["kid"]
        ));
        $kanjia["rules"] = unserialize($kanjia["rules"]);
        $help            = DBUtil::getKanjiaHelp(" `uniacid`=:uniacid AND `rid`=:rid AND `openid`=:openid", array(
            ":uniacid" => $_W["uniacid"],
            ":rid" => $record["id"],
            ":openid" => $_SESSION["openid"]
        ));
        $helpcount       = DBUtil::getKanjiaHelpWithRecordCount(" a.`uniacid`=:uniacid AND a.`openid`=:openid AND b.`kid`=:kid", array(
            ":uniacid" => $_W["uniacid"],
            ":openid" => $_SESSION["openid"],
            ":kid" => $record["kid"]
        ));
        if (empty($record)) {
            $data["errcode"] = "0";
            $data["errmsg"]  = "record_empty";
        } elseif (empty($_SESSION["openid"])) {
            $data["errcode"] = "10000";
            $data["errmsg"]  = "record_fans_empty";
            $data["href"]    = $kanjia["follow_url"];
        } elseif ($kanjia["follow_must"] && empty($_W["fans"]["follow"])) {
            $data["errcode"] = "10000";
            $data["errmsg"]  = "record_fans_empty";
            $data["href"]    = $kanjia["follow_url"];
        } elseif (!empty($help)) {
            $data["errcode"] = "10001";
            $data["errmsg"]  = "kanjia_helped";
        } elseif ($record["price"] == $kanjia["product_pricelow"]) {
            $data["errcode"] = "10002";
            $data["errmsg"]  = "kanjia_is_pricelow";
        } elseif ($helpcount >= $kanjia["max_help"] && !empty($kanjia["max_help"])) {
            $data["errcode"] = "10003";
            $data["errmsg"]  = "kanjia_helped_max";
        } else {
            $kanjia_price = $this->kanjia($record["price"], $kanjia["product_pricelow"], $kanjia["rules"]);
            $post         = array(
                "uniacid" => $_W["uniacid"],
                "rid" => $record["id"],
                "openid" => empty($_W["fans"]["openid"]) ? $_SESSION["wuserinfo"]["openid"] : $_W["fans"]["openid"],
                "nickname" => empty($_W["fans"]["nickname"]) ? $_SESSION["wuserinfo"]["nickname"] : $_W["fans"]["nickname"],
                "headimgurl" => empty($_W["fans"]["tag"]["avatar"]) ? $_SESSION["wuserinfo"]["headimgurl"] : $_W["fans"]["tag"]["avatar"],
                "price" => $kanjia_price,
                "createtime" => TIMESTAMP
            );
            if (DBUtil::saveKanjiaHelp($post)) {
                DBUtil::updateKanjiaRecord(array(
                    "price" => $record["price"] - $kanjia_price,
                    "number_help" => $record["number_help"] + 1,
                    "createtime" => TIMESTAMP
                ), array(
                    "uniacid" => $_W["uniacid"],
                    "id" => $record["id"]
                ));
                DBUtil::updateKanjia(array(
                    'number_help' => $kanjia["number_help"] + 1
                ), array(
                    "uniacid" => $_W["uniacid"],
                    "id" => $kanjia["id"]
                ));
                $data["errcode"] = "1";
                $data["errmsg"]  = "kanjia_success";
                $data["price"]   = $kanjia_price;
            }
        }
        echo json_encode($data);
        exit();
    }
    public function doMobileOrderSave()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $data   = file_get_contents("php://input");
        $data   = json_decode($data, true);
        $record = DBUtil::getKanjiaRecord(" `uniacid`=:uniacid AND `id`=:id AND `openid`=:openid", array(
            ":uniacid" => $_W["uniacid"],
            ":id" => intval($data["id"]),
            ":openid" => $_SESSION["openid"]
        ));
        if (empty($record)) {
            $err["errcode"] = "0";
            $err["errmsg"]  = "record_empty";
            echo json_encode($err);
            exit();
        }
        $order = DBUtil::getKanjiaOrder(" `uniacid`=:uniacid AND `rid`=:rid ", array(
            ":uniacid" => $_W["uniacid"],
            ":rid" => $record["id"]
        ));
        if (!empty($order)) {
            $err["errcode"] = "10001";
            $err["errmsg"]  = "order_had";
            echo json_encode($err);
            exit();
        }
        $data = array(
            "uniacid" => $_W["uniacid"],
            "acid" => $_W["account"]["acid"],
            "kid" => $record["kid"],
            "rid" => intval($record["id"]),
            "uid" => $_W['member']['uid'],
            "openid" => $_SESSION["openid"],
            "name" => trim($data["address"]["name"]),
            "address" => trim($data["address"]["address"]),
            "tel" => trim($data["address"]["telnumber"]),
            "uniontid" => trim($data["data"]["uniontid"]),
            "price" => $record["price"],
            "remark" => trim($data["remark"]),
            "status" => 1,
            "createtime" => TIMESTAMP
        );
        if (DBUtil::saveKanjiaOrder($data)) {
            $kanjia = DBUtil::getKanjia(" `uniacid`=:uniacid AND `id`=:id", array(
                ":uniacid" => $_W["uniacid"],
                ":id" => $record["kid"]
            ));
            DBUtil::updateKanjia(array(
                'product_sold' => $kanjia["product_sold"] + 1
            ), array(
                "uniacid" => $_W["uniacid"],
                "id" => $kanjia["id"]
            ));
            $account = WeAccount::create($_W["account"]["acid"]);
            $account->sendCustomNotice(array(
                "touser" => $_SESSION["openid"],
                "msgtype" => "text",
                "text" => array(
                    "content" => urlencode("恭喜您，支付成功！\n商品名称：" . $kanjia["product_name"] . "\n下单时间：" . date("Y-m-d H:i:s", time()) . "\n付款金额：" . $record["price"] . "元\n付款状态：付款成功\n\n感谢您的参与,祝您生活愉快。")
                )
            ));
            $err["errcode"] = "1";
            $err["errmsg"]  = "order_success";
        } else {
            $err["errcode"] = "10002";
            $err["errmsg"]  = "order_error";
        }
        echo json_encode($err);
        exit();
    }
    public function doMobileUnifiedOrder()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $data   = file_get_contents("php://input");
        $data   = json_decode($data, true);
        $record = DBUtil::getKanjiaRecord(" `uniacid`=:uniacid AND `id`=:id AND `openid`=:openid", array(
            ":uniacid" => $_W["uniacid"],
            ":id" => intval($data["id"]),
            ":openid" => $_SESSION["openid"]
        ));
        if (empty($record)) {
            $err["errcode"] = "0";
            $err["errmsg"]  = "record_empty";
            echo json_encode($err);
            exit();
        }
        $order = DBUtil::getKanjiaOrder(" `uniacid`=:uniacid AND `rid`=:rid ", array(
            ":uniacid" => $_W["uniacid"],
            ":rid" => $record["id"]
        ));
        if (!empty($order)) {
            $err["errcode"] = "10001";
            $err["errmsg"]  = "order_had";
            echo json_encode($err);
            exit();
        }
        $kanjia = DBUtil::getKanjia(" `uniacid`=:uniacid AND `id`=:id", array(
            ":uniacid" => $_W["uniacid"],
            ":id" => $record["kid"]
        ));
        if ($kanjia["product_inventory"] - $kanjia["product_sold"] <= 0) {
            $err["errcode"] = "10003";
            $err["errmsg"]  = "kanjia_inventory_shortage";
            echo json_encode($err);
            exit();
        }
        if ($record["price"] == 0) {
            $err["errcode"] = "1";
            $err["errmsg"]  = "kanjia_record_price_zero";
            echo json_encode($err);
            exit();
        }
        load()->model('payment');
        $setting = uni_setting($_W["uniacid"], array(
            "payment"
        ));
        if (!is_array($setting["payment"])) {
            $err["errcode"] = "10002";
            $err["errmsg"]  = "payment_error";
            echo json_encode($err);
            exit();
        }
        $wechat                  = $setting["payment"]["wechat"];
        $data                    = DBUtil::getWechatSelect(" `key`, `secret`", " `acid`=:acid", array(
            ":acid" => $wechat["account"]
        ));
        $wechat["appid"]         = $data["key"];
        $wechat["secret"]        = $data["secret"];
        $data                    = DBUtil::getModuleSelect(" `mid`", " `name`=:name", array(
            ":name" => "bf_kanjia"
        ));
        $moduleid                = empty($data["mid"]) ? '000000' : sprintf("%06d", $data["mid"]);
        $uniontid                = date('YmdHis') . $moduleid . random(8, 1);
        $pay_config              = wechat_build(array(
            "title" => $kanjia["product_name"],
            "uniontid" => $uniontid,
            "fee" => $record["price"]
        ), $wechat);
        $pay_config["timeStamp"] = (string) $pay_config["timeStamp"];
        $pay_config["uniontid"]  = $uniontid;
        echo json_encode($pay_config);
        exit;
    }
    public function doMobileBuyAjax()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $data   = file_get_contents("php://input");
        $data   = json_decode($data, true);
        $where  = " `uniacid`=:uniacid AND `id`=:id";
        $params = array(
            ":uniacid" => $_W["uniacid"],
            ":id" => intval($data["id"])
        );
        $data   = array();
        $record = DBUtil::getKanjiaRecord($where, $params);
        $kanjia = DBUtil::getKanjia(" `uniacid`=:uniacid AND `id`=:id", array(
            ":uniacid" => $_W["uniacid"],
            ":id" => $record["kid"]
        ));
        $order  = DBUtil::getKanjiaOrder(" `uniacid`=:uniacid AND `rid`=:rid", array(
            ":uniacid" => $_W["uniacid"],
            ":rid" => $record["id"]
        ));
        if (empty($record)) {
            $data["errcode"] = "0";
            $data["errmsg"]  = "record_empty";
        } elseif (empty($_SESSION["openid"])) {
            $data["errcode"] = "10000";
            $data["errmsg"]  = "record_fans_empty";
            $data["href"]    = $kanjia["follow_url"];
        } elseif ($kanjia["follow_must"] && empty($_W["fans"]["follow"])) {
            $data["errcode"] = "10000";
            $data["errmsg"]  = "record_fans_empty";
            $data["href"]    = $kanjia["follow_url"];
        } elseif ($record["openid"] != $_SESSION["openid"]) {
            $data["errcode"] = "10001";
            $data["errmsg"]  = "record_not_mine";
        } elseif (!empty($order)) {
            $data["errcode"] = "10002";
            $data["errmsg"]  = "record_bought";
        } elseif (TIMESTAMP < $kanjia["starttime"] || TIMESTAMP > $kanjia["endtime"]) {
            $data["errcode"] = "10003";
            $data["errmsg"]  = "kanjia_finish";
        } elseif ($kanjia["product_inventory"] - $kanjia["product_sold"] <= 0) {
            $data["errcode"] = "10004";
            $data["errmsg"]  = "kanjia_inventory_shortage";
        } else {
            if ($kanjia["buy_type"] == 1) {
                if ($record["price"] == $kanjia["product_pricelow"]) {
                    $data["errcode"] = "1";
                    $data["errmsg"]  = "can_buy";
                    $data["href"]    = $this->createMobileUrl("buy", array(
                        "id" => $record["id"],
                        "showwxpaytitle" => 1
                    ));
                } else {
                    $data["errcode"] = "10005";
                    $data["errmsg"]  = "record_not_pricelow";
                }
            } elseif ($kanjia["buy_type"] == 2) {
                if ($record["price"] == $kanjia["product_pricelow"]) {
                    $data["errcode"] = "2";
                    $data["errmsg"]  = "offline_buy";
                } else {
                    $data["errcode"] = "10005";
                    $data["errmsg"]  = "record_not_pricelow";
                }
            } else {
                $data["errcode"] = "1";
                $data["errmsg"]  = "can_buy";
                $data["href"]    = $this->createMobileUrl("buy", array(
                    "id" => $record["id"],
                    "showwxpaytitle" => 1
                ));
            }
        }
        echo json_encode($data);
        exit();
    }
    public function doMobileBuy()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $record = DBUtil::getKanjiaRecord(" `uniacid`=:uniacid AND `id`=:id AND `openid`=:openid", array(
            ":uniacid" => $_W["uniacid"],
            ":id" => intval($_GPC["id"]),
            ":openid" => $_SESSION["openid"]
        ));
        if (empty($record)) {
            message($i18n["kanjia_record"], "", "error");
        }
        $kanjia = DBUtil::getKanjia(" uniacid=:uniacid AND id=:id", array(
            ":uniacid" => $_W["uniacid"],
            ":id" => $record["kid"]
        ));
        include_once dirname(__FILE__) . '/Helper.php';
        $helper = new Helper();
        $helper->setScope("snsapi_base");
        $helper->WechatOauth2();
        $addrSign            = SHA1("accesstoken=" . $helper->getAccessToken() . "&appid=" . $_W["account"]["jssdkconfig"]["appId"] . "&noncestr=" . $_W["account"]["jssdkconfig"]["nonceStr"] . "&timestamp=" . $_W["account"]["jssdkconfig"]["timestamp"] . "&url=http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        $address_token       = array(
            "appId" => $_W["account"]["jssdkconfig"]["appId"],
            "scope" => "jsapi_address",
            "signType" => "SHA1",
            "addrSign" => $addrSign,
            "timeStamp" => $_W["account"]["jssdkconfig"]["timestamp"],
            "nonceStr" => $_W["account"]["jssdkconfig"]["nonceStr"]
        );
        $_W["page"]["title"] = $i18n["kanjia_title_buy"];
        $share               = array(
            "title" => $kanjia["share_title"],
            "desc" => $kanjia["share_desc"],
            "link" => $_W["siteroot"] . "app/" . $this->createMobileUrl("detail", array(
                "id" => $kanjia["id"]
            )),
            "imgUrl" => $_W["attachurl"] . $kanjia["share_imgUrl"]
        );
        include $this->template("buy");
    }
    public function doMobileMyAjax()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $where           = " uniacid=:uniacid AND id=:id";
        $params          = array(
            ":uniacid" => $_W["uniacid"],
            ":id" => intval($_GPC["id"])
        );
        $record          = DBUtil::getKanjiaRecordSelect("id,kid,openid,nickname,headimgurl,price,number_help", $where, $params);
        $record["price"] = floatval($record["price"]);
        if ($record["openid"] == $_SESSION["openid"]) {
            $record["record_type"] = "mine";
        } else {
            $record["record_type"] = "friend";
        }
        unset($record["openid"]);
        echo json_encode($record);
        exit();
    }
    public function doMobileMy()
    {
        global $_W, $_GPC;
        $this->checkAgent();
        include_once dirname(__FILE__) . '/libs.php';
        include_once dirname(__FILE__) . '/Helper.php';
        $helper = new Helper();
        $helper->WechatOauth2();
        $record              = DBUtil::getKanjiaRecord(" `uniacid`=:uniacid AND `id`=:id", array(
            ":uniacid" => $_W["uniacid"],
            ":id" => intval($_GPC["id"])
        ));
        $kanjia              = DBUtil::getKanjia(" uniacid=:uniacid AND id=:id", array(
            ":uniacid" => $_W["uniacid"],
            ":id" => intval($record["kid"])
        ));
        $_W["page"]["title"] = $kanjia["title"];
        $share               = array(
            "title" => sprintf($i18n["share_title_my"], $kanjia["title"]),
            "desc" => $kanjia["share_desc"],
            "link" => $_W["siteroot"] . "app/" . $this->createMobileUrl("my", array(
                "id" => $record["id"]
            )),
            "imgUrl" => $_W["attachurl"] . $kanjia["share_imgUrl"]
        );
        include $this->template("my");
    }
    public function doMobileJoin()
    {
        global $_W, $_GPC;
        $this->checkAgent();
        include_once dirname(__FILE__) . '/libs.php';
        include_once dirname(__FILE__) . '/Helper.php';
        $helper = new Helper();
        $helper->WechatOauth2();
        $where           = " uniacid=:uniacid AND id=:id";
        $params          = array(
            ":uniacid" => $_W["uniacid"],
            ":id" => $_GPC["id"]
        );
        $kanjia          = DBUtil::getKanjia($where, $params);
        $kanjia["rules"] = unserialize($kanjia["rules"]);
        if (empty($kanjia["id"])) {
            message($i18n["kanjia_empty"], "", "error");
        }
        if ($kanjia["starttime"] > TIMESTAMP) {
            message($i18n["kanjia_starttime"], "", "error");
        }
        if ($kanjia["endtime"] < TIMESTAMP) {
            message($i18n["kanjia_endtime"], "", "error");
        }
        if (empty($_SESSION["openid"])) {
            message($i18n["fans_empty"], $kanjia["follow_url"], "error");
        } elseif ($kanjia["follow_must"] && empty($_W["fans"]["follow"])) {
            message($i18n["fans_empty"], $kanjia["follow_url"], "error");
        }
        $record = DBUtil::getKanjiaRecord(" `uniacid`=:uniacid AND `kid`=:kid AND `openid`=:openid", array(
            ":uniacid" => $_W["uniacid"],
            ":kid" => $kanjia["id"],
            ":openid" => $_SESSION["openid"]
        ));
        if (empty($record)) {
            $kanjia_price = $this->kanjia($kanjia["product_price"], $kanjia["product_pricelow"], $kanjia["rules"]);
            $data         = array(
                "uniacid" => $_W["uniacid"],
                "acid" => $_W["account"]["acid"],
                "kid" => $kanjia["id"],
                "uid" => $_W["member"]["uid"],
                "openid" => empty($_W["fans"]["openid"]) ? $_SESSION["wuserinfo"]["openid"] : $_W["fans"]["openid"],
                "nickname" => empty($_W["fans"]["nickname"]) ? $_SESSION["wuserinfo"]["nickname"] : $_W["fans"]["nickname"],
                "headimgurl" => empty($_W["fans"]["tag"]["avatar"]) ? $_SESSION["wuserinfo"]["headimgurl"] : $_W["fans"]["tag"]["avatar"],
                "price" => ($kanjia["product_price"] - $kanjia_price),
                "number_help" => 1,
                "createtime" => TIMESTAMP
            );
            if (DBUtil::saveKanjiaRecord($data)) {
                $id = pdo_insertid();
                DBUtil::saveKanjiaHelp(array(
                    'uniacid' => $_W["uniacid"],
                    "rid" => $id,
                    "openid" => empty($_W["fans"]["openid"]) ? $_SESSION["wuserinfo"]["openid"] : $_W["fans"]["openid"],
                    "nickname" => empty($_W["fans"]["nickname"]) ? $_SESSION["wuserinfo"]["nickname"] : $_W["fans"]["nickname"],
                    "headimgurl" => empty($_W["fans"]["tag"]["avatar"]) ? $_SESSION["wuserinfo"]["headimgurl"] : $_W["fans"]["tag"]["avatar"],
                    "price" => $kanjia_price,
                    "createtime" => TIMESTAMP
                ));
                DBUtil::updateKanjia(array(
                    'number_join' => $kanjia["number_join"] + 1
                ), array(
                    "uniacid" => $_W["uniacid"],
                    "id" => $kanjia["id"]
                ));
                header('Location:' . $this->createMobileUrl("my", array(
                    "id" => $id
                )));
                exit;
            }
        } else {
            message($i18n["href_go"], $this->createMobileUrl("my", array(
                "id" => $record["id"]
            )), "success");
        }
        message($i18n["unknown_error"], "", "error");
    }
    public function doMobileDetailAjax()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $where                      = " uniacid=:uniacid AND id=:id";
        $params                     = array(
            ":uniacid" => $_W["uniacid"],
            ":id" => intval($_GPC["id"])
        );
        $kanjia                     = DBUtil::getKanjiaSelect("id,title,cover,starttime,endtime,tel,follow_url,notice,product_name,product_image,product_price,product_pricelow,product_inventory,product_sold,product_detail,product_url,number_join", $where, $params);
        $kanjia["cover"]            = tomedia($kanjia["cover"]);
        $kanjia["notice"]           = htmlspecialchars_decode($kanjia["notice"]);
        $kanjia["product_image"]    = tomedia($kanjia["product_image"]);
        $kanjia["product_pricelow"] = floatval($kanjia["product_pricelow"]);
        $kanjia["product_price"]    = floatval($kanjia["product_price"]);
        $kanjia["product_detail"]   = htmlspecialchars_decode($kanjia["product_detail"]);
        if ($kanjia["starttime"] > TIMESTAMP) {
            $kanjia["kanjia_status"] = "kanjia_starttime";
            $kanjia["kanjia_href"]   = "javascript:;";
        } elseif ($kanjia["endtime"] < TIMESTAMP) {
            $kanjia["kanjia_status"] = "kanjia_endtime";
            $kanjia["kanjia_href"]   = "javascript:;";
        } else {
            $record = DBUtil::getKanjiaRecord(" `uniacid`=:uniacid AND `kid`=:kid AND `openid`=:openid", array(
                ":uniacid" => $_W["uniacid"],
                ":kid" => $kanjia["id"],
                ":openid" => $_SESSION["openid"]
            ));
            if (empty($record)) {
                $kanjia["kanjia_status"] = "record_empty";
                $kanjia["kanjia_href"]   = $this->createMobileUrl("join", array(
                    "id" => $kanjia["id"]
                ));
            } else {
                $kanjia["kanjia_status"] = "record_have";
                $kanjia["kanjia_href"]   = $this->createMobileUrl("my", array(
                    "id" => $record["id"]
                ));
            }
        }
        echo json_encode($kanjia);
        exit();
    }
    public function doMobileDetail()
    {
        global $_W, $_GPC;
        $this->checkAgent();
        include_once dirname(__FILE__) . '/libs.php';
        $kanjia = DBUtil::getKanjia(" uniacid=:uniacid AND id=:id", array(
            ":uniacid" => $_W["uniacid"],
            ":id" => intval($_GPC["id"])
        ));
        if (empty($kanjia["status"])) {
            message($i18n["kanjia_status_0"], "", "error");
        }
        $_W["page"]["title"] = $kanjia["title"];
        $share               = array(
            "title" => $kanjia["share_title"],
            "desc" => $kanjia["share_desc"],
            "link" => $_W["siteroot"] . "app/" . $this->createMobileUrl("detail", array(
                "id" => $kanjia["id"]
            )),
            "imgUrl" => $_W["attachurl"] . $kanjia["share_imgUrl"]
        );
        include $this->template("detail");
    }
    public function doMobileListAjax()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $data     = file_get_contents("php://input");
        $data     = json_decode($data, true);
        $ops      = array(
            "normal",
            "begin",
            "past"
        );
        $op       = in_array($data["op"], $ops) ? $data["op"] : $ops[0];
        $page     = max(1, intval($data["page"]));
        $pagesize = 10;
        $where    = " uniacid=:uniacid AND status=:status";
        $params   = array(
            ":uniacid" => $_W["uniacid"],
            ":status" => 1
        );
        if ($op == "begin") {
            $where .= " AND `starttime` > :starttime";
            $params[":starttime"] = TIMESTAMP;
        } elseif ($op == "past") {
            $where .= " AND `endtime` < :endtime";
            $params[":endtime"] = TIMESTAMP;
        } else {
            $where .= " AND `starttime` < :starttime AND `endtime` > :endtime";
            $params[":starttime"] = TIMESTAMP;
            $params[":endtime"]   = TIMESTAMP;
        }
        $where .= " ORDER BY `createtime` DESC";
        $total      = DBUtil::getKanjiaCountWhere($where, $params);
        $pager      = pagination($total, $page, $pagesize);
        $kanjialist = DBUtil::getKanjiaSelectWhere("id,title,cover,starttime,endtime,product_name,product_price,product_pricelow", $where, $params, $page, $pagesize);
        foreach ($kanjialist as $key => &$value) {
            $value["product_pricelow"] = floatval($value["product_pricelow"]);
            $value["product_price"]    = floatval($value["product_price"]);
            $value["cover"]            = tomedia($value["cover"]);
            $value["href"]             = $_W["siteroot"] . "app/" . $this->createMobileUrl("detail", array(
                "id" => $value["id"]
            ));
        }
        unset($value);
        echo json_encode($kanjialist);
        exit();
    }
    public function doMobileList()
    {
        global $_W, $_GPC;
        $this->checkAgent();
        include_once dirname(__FILE__) . '/libs.php';
        $_W["page"]["title"] = $i18n["kanjia_title"];
        $share               = array(
            "title" => $i18n["share_title"],
            "desc" => $i18n["share_desc"],
            "link" => $_W["siteroot"] . "app/" . $this->createMobileUrl("list"),
            "imgUrl" => MODULE_URL . "icon.jpg"
        );
        include $this->template("list");
    }
    public function doWebOrder()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $ops         = array(
            "list",
            "detail"
        );
        $op          = in_array($_GPC["op"], $ops) ? $_GPC["op"] : $ops[0];
        $ORDER_STTUS = array(
            $i18n["order_status_0"],
            $i18n["order_status_1"],
            $i18n["order_status_2"],
            $i18n["order_status_3"],
            $i18n["order_status_4"],
            $i18n["order_status_5"]
        );
        if ($op == "list") {
            $kid = intval($_GPC["id"]);
            if ($_W["role"] == "operator") {
                checkCompetence("order", "select");
                $kanjia = DBUtil::getKanjia(" uniacid=:uniacid AND id=:id AND uid=:uid", array(
                    ":uniacid" => $_W["uniacid"],
                    ":id" => $kid,
                    ":uid" => $_W["uid"]
                ));
                if (empty($kanjia)) {
                    message($i18n["kanjia_empty"], "", "error");
                }
            }
            $page     = max(1, intval($_GPC["page"]));
            $pagesize = 20;
            $where    = " uniacid=:uniacid AND kid=:kid";
            $params   = array(
                ":uniacid" => $_W["uniacid"],
                ":kid" => $kid
            );
            if (!empty($_GPC["uniontid"])) {
                $where .= " AND uniontid like :uniontid";
                $params[":uniontid"] = "%" . trim($_GPC["uniontid"]) . "%";
            }
            $where . -" ORDER BY `createtime` DESC";
            $total = DBUtil::getKanjiaOrderCountWhere($where, $params);
            $pager = pagination($total, $page, $pagesize);
            if (checksubmit('outexcel')) {
                $orderlist = DBUtil::getKanjiaOrderWhere($where, $params);
                require_once '../framework/library/phpexcel/PHPExcel.php';
                $phpexcel       = new PHPExcel();
                $phpexcel_props = $phpexcel->getProperties();
                $phpexcel_props->setCreator($_W["account"]["name"]);
                $phpexcel_props->setLastModifiedBy($_W["account"]["name"]);
                $phpexcel_props->setTitle("订单列表");
                $phpexcel_props->setSubject("订单列表");
                $phpexcel_props->setDescription("订单列表");
                $phpexcel_props->setKeywords("订单列表");
                $phpexcel_props->setCategory("订单列表");
                $phpexcel->setActiveSheetIndex(0);
                $phpexcel_sheet = $phpexcel->getActiveSheet();
                $excelField     = array(
                    "H",
                    "I",
                    "J",
                    "K",
                    "L",
                    "M",
                    "N",
                    "O",
                    "P",
                    "Q",
                    "R",
                    "S",
                    "T",
                    "U",
                    "V",
                    "W",
                    "X",
                    "Y",
                    "Z"
                );
                $phpexcel_sheet->setTitle("订单列表");
                $n = 1;
                $phpexcel_sheet->setCellValue("A" . $n, "序号");
                $phpexcel_sheet->setCellValue("B" . $n, "订单号");
                $phpexcel_sheet->setCellValue("C" . $n, "订单金额/元");
                $phpexcel_sheet->setCellValue("D" . $n, "收货人");
                $phpexcel_sheet->setCellValue("E" . $n, "联系方式");
                $phpexcel_sheet->setCellValue("F" . $n, "收货地址");
                $phpexcel_sheet->setCellValue("G" . $n, "物流公司");
                $phpexcel_sheet->setCellValue("H" . $n, "物流单号");
                $phpexcel_sheet->setCellValue("I" . $n, "订单状态");
                $phpexcel_sheet->setCellValue("J" . $n, "下单时间");
                $phpexcel_sheet->setCellValue("K" . $n, "备注");
                $n += 1;
                foreach ($orderlist as $key => $value) {
                    $phpexcel_sheet->setCellValue("A" . $n, $n - 1);
                    $phpexcel_sheet->setCellValueExplicit("B" . $n, $value["uniontid"], PHPExcel_Cell_DataType::TYPE_STRING);
                    $phpexcel_sheet->setCellValue("C" . $n, $value["price"]);
                    $phpexcel_sheet->setCellValue("D" . $n, $value["name"]);
                    $phpexcel_sheet->setCellValue("E" . $n, $value["tel"]);
                    $phpexcel_sheet->setCellValue("F" . $n, $value["address"]);
                    $phpexcel_sheet->setCellValue("G" . $n, $value["expressname"]);
                    $phpexcel_sheet->setCellValue("H" . $n, $value["expresscode"]);
                    $phpexcel_sheet->setCellValue("I" . $n, $ORDER_STTUS[$value["status"]]);
                    $phpexcel_sheet->setCellValue("J" . $n, date("Y-m-d H:i:s", $value["createtime"]));
                    $phpexcel_sheet->setCellValue("K" . $n, str_replace(array(
                        "\r\n",
                        "\n",
                        "\r"
                    ), "", $value["remark"]));
                    $n += 1;
                }
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename=订单列表.csv');
                header('Cache-Control: max-age=0');
                $phpexcel_write = new PHPExcel_Writer_Excel5($phpexcel);
                $phpexcel_write->save("php://output");
                exit;
            } else {
                $orderlist = DBUtil::getKanjiaOrderWhere($where, $params, $page, $pagesize);
            }
            foreach ($orderlist as $key => &$value) {
                $value["fans"] = DBUtil::getFans(" uniacid=:uniacid AND openid=:openid", array(
                    ":uniacid" => $_W["uniacid"],
                    ":openid" => $value["openid"]
                ));
            }
            unset($value);
        } elseif ($op == "detail") {
            if ($_W["role"] == "operator") {
                checkCompetence("order", "select");
            }
            $id    = intval($_GPC["id"]);
            $order = DBUtil::getKanjiaOrder(" uniacid=:uniacid AND id=:id", array(
                ":uniacid" => $_W["uniacid"],
                ":id" => $id
            ));
            if (empty($order)) {
                message($i18n["order_empty"], "", "error");
            }
            $kid = $order["kid"];
            if ($_W["role"] == "operator") {
                $kanjia = DBUtil::getKanjia(" uniacid=:uniacid AND id=:id AND uid=:uid", array(
                    ":uniacid" => $_W["uniacid"],
                    ":id" => $kid,
                    ":uid" => $_W["uid"]
                ));
                if (empty($kanjia)) {
                    message($i18n["kanjia_empty"], "", "error");
                }
            }
            if (checksubmit()) {
                if ($_W["role"] == "operator") {
                    checkCompetence("order", "update");
                }
                $expressname = trim($_GPC["expressname"]);
                $expresscode = trim($_GPC["expresscode"]);
                if (empty($expresscode)) {
                    message($i18n["expressname_empty"], "", "error");
                }
                if (empty($expresscode)) {
                    message($i18n["expresscode_empty"], "", "error");
                }
                if (DBUtil::updateKanjiaOrder(array(
                    'expressname' => $expressname,
                    "expresscode" => $expresscode,
                    "status" => 2
                ), array(
                    "uniacid" => $_W["uniacid"],
                    "id" => $order["id"]
                ))) {
                    message($i18n["pdo_success"], referer(), "success");
                } else {
                    message($i18n["pdo_error"], referer(), "success");
                }
            }
        }
        load()->func('tpl');
        include $this->template("order");
    }
    public function doWebHelp()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        if ($_W["role"] == "operator") {
            checkCompetence("help", "select");
        }
        $rid      = intval($_GPC["id"]);
        $page     = max(1, intval($_GPC["page"]));
        $pagesize = 20;
        $where    = " uniacid=:uniacid AND rid=:rid";
        $params   = array(
            ":uniacid" => $_W["uniacid"],
            ":rid" => $rid
        );
        if (!empty($_GPC["nickname"])) {
            $where .= " AND nickname LIKE :nickname";
            $params[":nickname"] = "%" . trim($_GPC["nickname"]) . "%";
        }
        if (!empty($_GPC["pricesort"])) {
            $where .= " ORDER BY `price` ASC";
        } else {
            $where .= " ORDER BY `createtime` DESC";
        }
        $total  = DBUtil::getKanjiaHelpCountWhere($where, $params);
        $pager  = pagination($total, $page, $pagesize);
        $record = DBUtil::getKanjiaRecord(" uniacid=:uniacid AND id=:id", array(
            ":uniacid" => $_W["uniacid"],
            ":id" => $rid
        ));
        if ($_W["role"] == "operator") {
            $kanjia = DBUtil::getKanjia(" uniacid=:uniacid AND id=:id AND uid=:uid", array(
                ":uniacid" => $_W["uniacid"],
                ":id" => $record["kid"],
                ":uid" => $_W["uid"]
            ));
        } else {
            $kanjia = DBUtil::getKanjia(" uniacid=:uniacid AND id=:id", array(
                ":uniacid" => $_W["uniacid"],
                ":id" => $record["kid"]
            ));
        }
        if (empty($kanjia)) {
            message($i18n["kanjia_empty"], "", "error");
        }
        if (checksubmit('fans')) {
            if ($_W["role"] == "operator") {
                checkCompetence("help", "update");
            }
            $helplist = DBUtil::getKanjiaHelpWhere($where, $params);
            foreach ($helplist as $key => $value) {
                if (empty($value["nickname"]) || empty($value["headimgurl"])) {
                    $account = WeAccount::create($record["acid"]);
                    $data    = $account->fansQueryInfo($value["openid"]);
                    if (!is_error($fan)) {
                        DBUtil::updateKanjiaHelp(array(
                            "nickname" => $data["nickname"],
                            "headimgurl" => $data["headimgurl"]
                        ), array(
                            "uniacid" => $_W["uniacid"],
                            "id" => $value["id"]
                        ));
                    }
                }
            }
            message($i18n["pdo_success"], referer(), "success");
            exit;
        } else {
            $helplist = DBUtil::getKanjiaHelpWhere($where, $params, $page, $pagesize);
        }
        load()->func('tpl');
        include $this->template("help");
    }
    public function doWebRecord()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $id = intval($_GPC["id"]);
        if ($_W["role"] == "operator") {
            checkCompetence("record", "select");
            $kanjia = DBUtil::getKanjia(" uniacid=:uniacid AND id=:id AND uid=:uid", array(
                ":uniacid" => $_W["uniacid"],
                ":id" => $id,
                ":uid" => $_W["uid"]
            ));
        } else {
            $kanjia = DBUtil::getKanjia(" uniacid=:uniacid AND id=:id", array(
                ":uniacid" => $_W["uniacid"],
                ":id" => $id
            ));
        }
        if (empty($kanjia)) {
            message($i18n["kanjia_empty"], "", "error");
        }
        $page     = max(1, intval($_GPC["page"]));
        $pagesize = 20;
        $where    = " uniacid=:uniacid AND kid=:kid";
        $params   = array(
            ":uniacid" => $_W["uniacid"],
            ":kid" => $kanjia["id"]
        );
        if (!empty($_GPC["nickname"])) {
            $where .= " AND nickname LIKE :nickname";
            $params[":nickname"] = "%" . trim($_GPC["nickname"]) . "%";
        }
        if (!empty($_GPC["pricesort"])) {
            $where .= " ORDER BY `price` ASC";
        } else {
            $where .= " ORDER BY `createtime` DESC";
        }
        $total = DBUtil::getKanjiaRecordCountWhere($where, $params);
        $pager = pagination($total, $page, $pagesize);
        if (checksubmit('fans')) {
            if ($_W["role"] == "operator") {
                checkCompetence("record", "update");
            }
            $recordlist = DBUtil::getKanjiaRecordWhere($where, $params);
            foreach ($recordlist as $key => $value) {
                if (empty($value["nickname"]) || empty($value["headimgurl"])) {
                    $account = WeAccount::create($value["acid"]);
                    $data    = $account->fansQueryInfo($value["openid"]);
                    if (!is_error($fan)) {
                        DBUtil::updateKanjiaRecord(array(
                            "nickname" => $data["nickname"],
                            "headimgurl" => $data["headimgurl"]
                        ), array(
                            "uniacid" => $_W["uniacid"],
                            "id" => $value["id"]
                        ));
                    }
                }
            }
            message($i18n["pdo_success"], referer(), "success");
            exit;
        } else {
            $recordlist = DBUtil::getKanjiaRecordWhere($where, $params, $page, $pagesize);
        }
        load()->func('tpl');
        include $this->template("record");
    }
    public function doWebManager()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        $ops = array(
            "list",
            "post",
            "delete",
            "weidian"
        );
        $op  = in_array($_GPC["op"], $ops) ? $_GPC["op"] : $ops[0];
        if ($op == "list") {
            if ($_W["role"] == "operator") {
                checkCompetence("kanjia", "select");
            }
            $page     = max(1, intval($_GPC["page"]));
            $pagesize = 20;
            $where    = " uniacid=:uniacid";
            $params   = array(
                ":uniacid" => $_W["uniacid"]
            );
            if ($_W["role"] == "operator") {
                $where .= " AND `uid`=:uid";
                $params[":uid"] = $_W["uid"];
            }
            $total      = DBUtil::getKanjiaCountWhere($where, $params);
            $pager      = pagination($total, $page, $pagesize);
            $kanjialist = DBUtil::getKanjiaWhere($where, $params, $page, $pagesize);
            foreach ($kanjialist as $key => &$value) {
                $value["shop"] = DBUtil::getUser(" uid=:uid", array(
                    ":uid" => $value["uid"]
                ));
            }
            unset($value);
        } elseif ($op == "post") {
            $id = intval($_GPC["id"]);
            if ($_W["role"] == "operator") {
                checkCompetence("kanjia", "select");
                $kanjia = DBUtil::getKanjia(" uniacid=:uniacid AND id=:id AND uid=:uid", array(
                    ":uniacid" => $_W["uniacid"],
                    ":id" => $id,
                    ":uid" => $_W["uid"]
                ));
            } else {
                $kanjia = DBUtil::getKanjia(" uniacid=:uniacid AND id=:id", array(
                    ":uniacid" => $_W["uniacid"],
                    ":id" => $id
                ));
            }
            $kanjia["rules"] = unserialize($kanjia["rules"]);
            if (empty($kanjia["starttime"])) {
                $kanjia["starttime"] = TIMESTAMP;
            }
            if (empty($kanjia["endtime"])) {
                $kanjia["endtime"] = TIMESTAMP;
            }
            if (checksubmit()) {
                if (empty($_GPC["title"])) {
                    message($i18n["name_empty"], "", "error");
                } elseif (empty($_GPC["cover"])) {
                    message($i18n["cover_empty"], "", "error");
                } elseif (empty($_GPC["product_name"])) {
                    message($i18n["product_name_empty"], "", "error");
                } elseif (empty($_GPC["product_image"])) {
                    message($i18n["product_image_empty"], "", "error");
                } elseif ($_GPC["product_pricelow"] < 0) {
                    message($i18n["product_pricelow_min"], "", "error");
                }
                foreach ($_GPC["price"] as $key => $value) {
                    if (floatval($_GPC["price"][$key]) >= floatval($_GPC["product_price"])) {
                        message($i18n["rule_price_max"], "", "error");
                    }
                    if (floatval($_GPC["price"][$key]) < floatval($_GPC["product_pricelow"])) {
                        message($i18n["rule_price_min"], "", "error");
                    }
                }
                $data          = array(
                    "uniacid" => $_W["uniacid"],
                    "title" => trim($_GPC["title"]),
                    "cover" => trim($_GPC["cover"]),
                    "starttime" => strtotime($_GPC["time"]["start"]),
                    "endtime" => strtotime($_GPC["time"]["end"]),
                    "tel" => trim($_GPC["tel"]),
                    "buy_type" => intval($_GPC["buy_type"]),
                    "max_help" => intval($_GPC["max_help"]),
                    "notice" => trim($_GPC["notice"]),
                    "product_name" => trim($_GPC["product_name"]),
                    "product_image" => trim($_GPC["product_image"]),
                    "product_price" => floatval($_GPC["product_price"]),
                    "product_pricelow" => floatval($_GPC["product_pricelow"]),
                    "product_inventory" => intval($_GPC["product_inventory"]),
                    "product_detail" => trim($_GPC["product_detail"]),
                    "product_url" => trim($_GPC["product_url"]),
                    "share_title" => trim($_GPC["share_title"]),
                    "share_link" => trim($_GPC["share_link"]),
                    "share_imgUrl" => trim($_GPC["share_imgUrl"]),
                    "share_desc" => trim($_GPC["share_desc"]),
                    "footer" => trim($_GPC["footer"])
                );
                $data["rules"] = array();
                foreach ($_GPC["price"] as $key => $value) {
                    if (!empty($_GPC["price"])) {
                        $data["rules"][$key] = array(
                            "price" => floatval($_GPC["price"][$key]),
                            "min" => floatval($_GPC["min"][$key]),
                            "max" => floatval($_GPC["max"][$key])
                        );
                    }
                }
                $data["rules"] = serialize($data["rules"]);
                if (empty($_GPC["id"])) {
                    if ($_W["role"] == "operator") {
                        checkCompetence("kanjia", "add");
                        $data["status"] = 0;
                    } else {
                        $data["status"] = 1;
                    }
                    $data["createtime"] = TIMESTAMP;
                    $data["uid"]        = $_W["uid"];
                    if (DBUtil::saveKanjia($data)) {
                        message($i18n["pdo_save_success"], $this->createWebUrl("manager"), "success");
                    }
                } else {
                    if ($_W["role"] == "operator") {
                        checkCompetence("kanjia", "update");
                        $result = DBUtil::updateKanjia($data, array(
                            "uniacid" => $_W["uniacid"],
                            "id" => $id,
                            "uid" => $_W["uid"]
                        ));
                    } else {
                        $data["follow_url"]  = trim($_GPC["follow_url"]);
                        $data["follow_must"] = intval($_GPC["follow_must"]);
                        $data["status"]      = intval($_GPC["status"]);
                        $result              = DBUtil::updateKanjia($data, array(
                            "uniacid" => $_W["uniacid"],
                            "id" => $id
                        ));
                    }
                    if ($result) {
                        message($i18n["pdo_update_success"], referer(), "success");
                    } else {
                        message($i18n["pdo_error"], referer(), "error");
                    }
                }
            }
        } elseif ($op == "delete") {
            $id = intval($_GPC["id"]);
            if ($_W["role"] == "operator") {
                checkCompetence("kanjia", "del");
                $result = DBUtil::delKanjia(array(
                    "uniacid" => $_W["uniacid"],
                    "id" => $id,
                    "uid" => $_W["uid"]
                ));
            } else {
                $result = DBUtil::delKanjia(array(
                    "uniacid" => $_W["uniacid"],
                    "id" => $id
                ));
            }
            if ($result) {
                message($i18n["pdo_delete_success"], referer(), "success");
            } else {
                message($i18n["pdo_error"], referer(), "error");
            }
        } elseif ($op == "weidian") {
            if ($_W["role"] == "operator") {
                checkCompetence("kanjia", "add");
            }
            $step = intval($_GPC["step"]);
            if (!empty($step)) {
                load()->func("communication");
                $this->weidian_token_url = str_replace(":weidian_appkey", $this->weidian_appkey, $this->weidian_token_url);
                $this->weidian_token_url = str_replace(":weidian_secret", $this->weidian_secret, $this->weidian_token_url);
                $result                  = ihttp_get($this->weidian_token_url);
                $data                    = json_decode($result["content"], true);
                if (empty($_GPC["url"])) {
                    message($i18n["weidian_url_empty"], "", "error");
                }
                if (empty($data["result"]["access_token"])) {
                    message($i18n["weidian_token_error"], "", "error");
                }
                $params                     = $this->parseUrlParam($_GPC["url"]);
                $this->weidian_cps_item_url = str_replace(":access_token", $data["result"]["access_token"], $this->weidian_cps_item_url);
                $this->weidian_cps_item_url = str_replace(":itemid", $params["itemID"], $this->weidian_cps_item_url);
                $data                       = ihttp_get($this->weidian_cps_item_url);
                $data                       = json_decode($data["content"], true);
                $kanjia                     = array();
                if ($data["status"]["status_reason"] == "success") {
                    $_product_price = explode("-", $data["result"]["price"]);
                    $kanjia         = array(
                        "title" => trim($data["result"]["item_name"]),
                        "starttime" => TIMESTAMP,
                        "endtime" => TIMESTAMP,
                        "tel" => "",
                        "buy_type" => 0,
                        "max_help" => "",
                        "notice" => "",
                        "product_name" => trim($data["result"]["item_name"]),
                        "product_price" => floatval($_product_price[0]),
                        "product_pricelow" => floatval($_product_price[0]),
                        "product_inventory" => intval($data["result"]["stock"]),
                        "product_detail" => "",
                        "product_url" => trim($data["result"]["item_url"])
                    );
                    foreach ($data["result"]["imgs"] as $key => $value) {
                        if ($key == 0) {
                            $kanjia["cover"]         = $value;
                            $kanjia["product_image"] = $value;
                            $kanjia["share_imgUrl"]  = $value;
                        }
                        $kanjia["product_detail"] .= "<div><img src='" . $value . "' /></div>";
                    }
                }
            }
            load()->func('tpl');
            include $this->template("weidian");
            exit;
        }
        load()->func('tpl');
        include $this->template("manager");
    }
    private $weidian_appkey = "633098";
    private $weidian_secret = "758024fad7813899eb8049912b9536d5";
    private $weidian_token_url = "https://api.vdian.com/token?grant_type=client_credential&appkey=:weidian_appkey&secret=:weidian_secret";
    private $weidian_cps_item_url = 'https://api.vdian.com/api?public={"method":"vdian.cps.item.get","access_token":":access_token","version":"1.0","format":"json"}&param={"itemid":":itemid"}';
    private function parseUrlParam($url)
    {
        $url    = parse_url($url, PHP_URL_QUERY);
        $array  = explode('&', $url);
        $params = array();
        if ($array[0] !== '') {
            foreach ($array as $param) {
                list($name, $value) = explode('=', $param);
                $params[urldecode($name)] = urldecode($value);
            }
        }
        return $params;
    }
    public function doWebShop()
    {
        global $_W, $_GPC;
        include_once dirname(__FILE__) . '/libs.php';
        if (!$this->checkCompetenceBasic()) {
            message($i18n["competence_error"], "", "error");
        }
        $ops = array(
            "index",
            "save",
            "update",
            "del"
        );
        $op  = empty($_GPC["op"]) ? $ops[0] : trim($_GPC["op"]);
        if ($op == "index") {
            $page          = max(1, $_GPC["page"]);
            $pagesize      = 20;
            $account_users = DBUtil::getAccountUsersSelect("`uid`", " uniacid=:uniacid AND role=:role", array(
                ":uniacid" => $_W["uniacid"],
                ":role" => "operator"
            ));
            $account_array = array();
            foreach ($account_users as $key => $value) {
                $account_array[] = $value["uid"];
            }
            if (count($account_users) > 0) {
                $total    = DBUtil::getUsersCountWhere(" `uid` in (" . implode(",", $account_array) . ")", array());
                $pager    = pagination($total, $page, $pagesize);
                $userlist = DBUtil::getUsersWhere(" `uid` in (" . implode(",", $account_array) . ")", array(), $page, $pagesize);
            } else {
                $userlist = array();
                $pager    = "";
            }
        } elseif ($op == "save") {
            if (checksubmit()) {
                load()->model("user");
                $data["groupid"]  = 1;
                $data["username"] = trim($_GPC["username"]);
                $data["password"] = trim($_GPC["password"]);
                $data["remark"]   = trim($_GPC["remark"]);
                if (empty($data["username"])) {
                    message($i18n["shop_username_empty"], "", "error");
                }
                if (!preg_match("/^[a-z\d_]{3,15}$/iu", $data["username"])) {
                    message($i18n["shop_username_error"], "", "error");
                }
                if (empty($data["password"])) {
                    message($i18n["shop_password_empty"], "", "error");
                }
                if (strlen($data["password"]) < 6) {
                    message($i18n["shop_password_length_error"], "", "error");
                }
                if ($data["password"] != trim($_GPC["password_confirm"])) {
                    message($i18n["shop_password_confirm_error"], "", "error");
                }
                if (user_check(array(
                    'username' => $data["username"]
                ))) {
                    message($i18n["shop_username_had"], "", "error");
                }
                $uid = user_register($data);
                if (!empty($uid)) {
                    $account_users = DBUtil::getAccountUsers(" uniacid=:uniacid AND uid=:uid", array(
                        ":uniacid" => $_W["uniacid"],
                        ":uid" => $uid
                    ));
                    if (empty($account_users)) {
                        DBUtil::saveAccountUsers(array(
                            "uniacid" => $_W["uniacid"],
                            "uid" => $uid,
                            "role" => "operator"
                        ));
                    }
                    $urls            = parse_url(url("home/welcome/ext", array(
                        "m" => $MODILE_NAME
                    )));
                    $url             = rtrim($urls["query"], "&");
                    $usersPermission = DBUtil::getUsersPermission(" uniacid=:uniacid AND uid=:uid AND url=:url", array(
                        ":uniacid" => $_W["uniacid"],
                        ":uid" => $uid,
                        ":url" => $url
                    ));
                    if (!$usersPermission) {
                        DBUtil::saveUsersPermission(array(
                            "uniacid" => $_W["uniacid"],
                            "uid" => $uid,
                            "url" => $url
                        ));
                    }
                    foreach ($RULES as $key => $rule) {
                        foreach ($rule as $key2 => $item) {
                            $post["rule"][$key][$item] = intval($_GPC[$key][$item]);
                        }
                    }
                    $post["uniacid"]    = $_W["uniacid"];
                    $post["uid"]        = $uid;
                    $post["rule"]       = serialize($post["rule"]);
                    $post["createtime"] = TIMESTAMP;
                    DBUtil::saveKanjiaShop($post);
                    message($i18n["shop_save_success"], $this->craeteWebUrl("shop"), "success");
                }
                message($i18n["shop_save_error"], referer(), "error");
            }
        } elseif ($op == "update") {
            $uid = intval($_GPC["uid"]);
            load()->model('user');
            $user = user_single($uid);
            if (empty($user)) {
                message($i18n["shop_user_empty"], "", "error");
            }
            $shop = DBUtil::getKanjiaShop(" `uniacid`=:uniacid AND `uid`=:uid", array(
                ":uniacid" => $_W["uniacid"],
                ":uid" => $uid
            ));
            if (!empty($shop)) {
                $shop["rule"] = unserialize($shop["rule"]);
            } else {
                $shop["rule"] = array();
            }
            if (checksubmit()) {
                $post = array(
                    "uid" => $user["uid"],
                    "salt" => $user["salt"]
                );
                if (!empty($_GPC["password"])) {
                    $post["password"] = trim($_GPC["password"]);
                }
                if ($user["remark"] != trim($_GPC["remark"])) {
                    $post["remark"] = trim($_GPC["remark"]);
                }
                if (intval($_GPC["status"])) {
                    $post["status"] = 1;
                } else {
                    $post["status"] = 2;
                }
                $result          = user_update($post);
                $urls            = parse_url(url("home/welcome/ext", array(
                    "m" => $MODILE_NAME
                )));
                $url             = rtrim($urls["query"], "&");
                $usersPermission = DBUtil::getUsersPermission(" uniacid=:uniacid AND uid=:uid AND url=:url", array(
                    ":uniacid" => $_W["uniacid"],
                    ":uid" => $uid,
                    ":url" => $url
                ));
                if (!$usersPermission) {
                    DBUtil::saveUsersPermission(array(
                        "uniacid" => $_W["uniacid"],
                        "uid" => $uid,
                        "url" => $url
                    ));
                }
                foreach ($RULES as $key => $rule) {
                    foreach ($rule as $key2 => $item) {
                        $post["rule"][$key][$item] = intval($_GPC[$key][$item]);
                    }
                }
                if (empty($shop["id"])) {
                    $data["uniacid"]    = $_W["uniacid"];
                    $data["uid"]        = $uid;
                    $data["rule"]       = serialize($post["rule"]);
                    $data["createtime"] = TIMESTAMP;
                    DBUtil::saveKanjiaShop($data);
                } else {
                    DBUtil::updateKanjiaShop(array(
                        'rule' => serialize($post["rule"])
                    ), array(
                        "uniacid" => $_W["uniacid"],
                        "uid" => $uid
                    ));
                }
                message($i18n["pdo_update_success"], referer(), "success");
            }
        } elseif ($op == "del") {
            exit("error");
        }
        load()->func('tpl');
        include $this->template("shop");
    }
    private function checkCompetenceBasic()
    {
        global $_W;
        if ($_W["role"] == "manager" || $_W["role"] == "founder") {
            return true;
        } else {
            return false;
        }
    }
    private function checkAgent()
    {
        global $_W;
        if ($_W["container"] != "wechat") {
            die();
        }
    }
    private function kanjia($price, $pricelow, $rules)
    {
        if ($price > $pricelow) {
            if (empty($rules)) {
                $kanjia_price = $pricelow + mt_rand() / mt_getrandmax() * ($price - $pricelow);
            } else {
                foreach ($rules as $key => $rule) {
                    if ($price > $rule["price"]) {
                        $kanjia_price = $rule["min"] + mt_rand() / mt_getrandmax() * ($rule["max"] - $rule["min"]);
                        break;
                    }
                }
            }
            $kanjia_price = sprintf("%.1f", $kanjia_price);
            if ($price - $kanjia_price < $pricelow) {
                $kanjia_price = $price - $pricelow;
            }
            return $kanjia_price;
        } else {
            return 0;
        }
    }
}