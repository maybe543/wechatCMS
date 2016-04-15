<?php

defined('IN_IA') or exit('Access Denied');
define("AMOUSE_FRIEND_IMPRESS", "amouse_friend_impress");
define("AMOUSE_FRIEND_IMPRESS_RES", "../addons/".AMOUSE_FRIEND_IMPRESS."/style");
require_once IA_ROOT."/addons/".AMOUSE_FRIEND_IMPRESS."/Util.class.php";

class Amouse_friend_impressModuleSite extends WeModuleSite{

    public static $USER_COOKIE_KEY="amouse_friend_impress_2015071403001001";

    /*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为微信端页面管理＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
    //微信端首页
    public function doMobileIndex(){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $userInfo=Util::getClientCookieUserInfo($this::$USER_COOKIE_KEY.$weid);
        $set = $this->module['config'];
        if(empty($userInfo)){
            $this->checkCookie();
        }
        $uid=$_GPC['uid'];
        if($uid){
            $joinUser=pdo_fetch("SELECT * FROM ".tablename('amouse_impress_user')." WHERE id= :id AND uniacid= :weid",array(":id"=>$uid,":weid"=>$weid));
            $list= pdo_fetchall("SELECT * FROM ".tablename('amouse_impress_record')." WHERE uid=:uid  order by createtime DESC ",array(":uid"=>$uid));
            $recCount=count($list);
        }else{
            $joinUser=pdo_fetch("SELECT * FROM ".tablename('amouse_impress_user')." WHERE oid= :oid AND uniacid= :weid",array(":oid"=>$userInfo['openid'],":weid"=>$weid));
            $list= pdo_fetchall("SELECT * FROM ".tablename('amouse_impress_record')." WHERE uid=:uid  order by createtime DESC ",array(":uid"=>$joinUser['id']));
            $recCount=count($list);
        }
        $shareurl=$_W['siteroot']."app/".substr($this->createMobileUrl('share',array('uid'=>$joinUser['id']),true), 2);
        include $this->template('impress_index');
    }

    //创建
    public function doMobileCreate(){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $openid =$_W['openid'];
        $uid=$_GPC['uid'];
        $firend=Util::getClientCookieUserInfo($this::$USER_COOKIE_KEY.$weid); 
		//$firend['openid']="oSWoes0T1YM4Uq83FZOdKv9q8ud0";
		//$firend['nickname']="AA-Zombie(w.mamani.cn)";
        if(empty($firend)){
            $this->checkCookie();
        }
        $joinUser=pdo_fetch("SELECT * FROM ".tablename('amouse_impress_user')." WHERE id= :uid AND uniacid= :weid",array(":uid"=>$uid,":weid"=>$weid));
        //$joinUser=pdo_fetch("SELECT * FROM ".tablename('amouse_impress_user')." WHERE oid= :uid AND uniacid= :weid",array(":uid"=>$firend['openid'],":weid"=>$weid));
        /*if($firend['openid'] != $joinUser['oid']){
            if ($_W['ispost']){
                $str="的是在和有大这主中北巷九命猫北城柳絮飞人上为们地个对不起我不吃猪肉用工时要动国产以我到他会作来分久不愈i会离开就别靠近生对于学下级就年阶义发成部民可出能方进同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批如应形想制心样干都向变关点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫康遵牧遭幅园腔订香肉弟屋敏恢忘衣孙龄岭骗休借丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩";
                $len = mb_strlen($str,'utf-8');
                $rand1=rand(0,$len-1);
                $rand2=rand(0,$len-1);
                $nickname = mb_substr($str, $rand1,2,'utf-8').mb_substr($str, $rand2,2,'utf-8');

                $userData=array('oid'=>$firend['openid'],
                    'uid'=>$uid,
                    'nickname'=>$nickname,
                    'vote'=>0,
                    'realname'=>$firend['nickname'],
                    'content'=>trim($_GPC['content']),
                    'createtime'=>TIMESTAMP);

                pdo_insert('amouse_impress_record', $userData);

                //message('匿名印象成功了', $this->createMobileUrl('index', array()), 'success');
                $shareurl=$_W['siteroot']."app/".substr($this->createMobileUrl('index',array('uid'=>$uid),true), 2);
                header("location:$shareurl");
                exit;
            }
        }else{
            message('自己别给自己留印象了，其实自己是最不了解自己的哦', $this->createMobileUrl('index', array('op' => 'list')), 'success');
        }*/
        $shareurl=$_W['siteroot']."app/".substr($this->createMobileUrl('share',array('uid'=>$uid),true), 2);
        include $this->template('impress_create');
    }


    public function  doMobileAjax(){ 
		global $_W, $_GPC;
		$weid=$_W['uniacid'];
        $uid=$_GPC['uid'];
        $joinUser=pdo_fetch("SELECT * FROM ".tablename('amouse_impress_user')." WHERE id= :uid AND uniacid= :weid",array(":uid"=>$uid,":weid"=>$weid));
        $firend=Util::getClientCookieUserInfo($this::$USER_COOKIE_KEY.$weid);
		//$firend['openid']="oSWoes0T1YM4Uq83FZOdKv9q8ud0";
		//$firend['nickname']="AA-Zombie(w.mamani.cn)";
        if(empty($firend)){
            $res['code']=504;
            $res['msg']="请授权登录后再给好友印象!";
            return json_encode($res);
        }
        if($firend['openid'] != $joinUser['oid']){
                $str="的是在和有大这主中北巷九命猫北城柳絮飞人上为们地个对不起我不吃猪肉用工时要动国产以我到他会作来分久不愈i会离开就别靠近生对于学下级就年阶义发成部民可出能方进同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批如应形想制心样干都向变关点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫康遵牧遭幅园腔订香肉弟屋敏恢忘衣孙龄岭骗休借丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩";
                $len = mb_strlen($str,'utf-8');
                $rand1=rand(0,$len-1);
                $rand2=rand(0,$len-1);
                $nickname = mb_substr($str, $rand1,2,'utf-8').mb_substr($str, $rand2,2,'utf-8');

                $userData=array('oid'=>$firend['openid'],
                    'uid'=>$uid,
                    'nickname'=>$nickname,
                    'vote'=>0,
                    'realname'=>$firend['nickname'],
                    'content'=>trim($_GPC['content']),
                    'createtime'=>TIMESTAMP);

                pdo_insert('amouse_impress_record', $userData);

            $res['code']=200;
            $res['msg']="";
            $res['data']=$uid;

            return json_encode($res);

        }else{
            $res['code']=202;
            $res['msg']="自己别给自己留印象了，其实自己是最不了解自己的哦";
            $res['data']=$uid;
            return json_encode($res);
        }
    }


    public  function doMobileShare(){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $uid=$_GPC['uid'];
        $userInfo=Util::getClientCookieUserInfo($this::$USER_COOKIE_KEY.$weid);
        if(empty($userInfo)){
            //$this->checkCookie('index');
        }
        $joinUser=pdo_fetch("SELECT * FROM ".tablename('amouse_impress_user')." WHERE id= :id AND uniacid= :weid",array(":id"=>$uid,":weid"=>$weid));
        $list= pdo_fetchall("SELECT * FROM ".tablename('amouse_impress_record')." WHERE  uid=:uid ",array(":uid"=>$uid));
        $recCount=count($list);
        $set = $this->module['config'];
        $shareurl=$_W['siteroot']."app/".substr($this->createMobileUrl('share',array('uid'=>$uid),true), 2);
        include $this->template('impress_share');
    }


    public function doMobileGuanzhu() {
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $oid=$_GPC['oid'];
        $settings = $this->module['config'];
        $userInfo=Util::getClientCookieUserInfo($this::$USER_COOKIE_KEY.$weid);
        if(empty($userInfo)){
            $this->checkCookie();
        }
        if($oid==$userInfo['openid']){
            $uid=$_GPC['uid'];
            $shareurl=$_W['siteroot']."app/".substr($this->createMobileUrl('index',array('uid'=>$uid),true), 2);
            header("location:$shareurl");
            exit;
        }else{
            load()->func('communication');
            $openid = $userInfo['openid'];
            //强制引导
            if($settings&&$settings['enable']==1){
                $shareurl=$_W['siteroot']."app/".substr($this->createMobileUrl('index',array('uid'=>$uid),true), 2);
                $gzDwz= $settings['gzDwz'] ;
                header("location:$gzDwz");
                exit;
            }
            if (!empty($openid)) {
                $uid=$_GPC['uid'];
                $shareurl=$_W['siteroot']."app/".substr($this->createMobileUrl('index',array('uid'=>$uid),true), 2);
                header("location:$shareurl");
                exit;
            }
        }
    }

    public  function doMobileGz(){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $userInfo=Util::getClientCookieUserInfo($this::$USER_COOKIE_KEY.$weid);
        $userInfo['openid']="12eddddd";
        $set = $this->module['config'];
        if(empty($userInfo)){
            // $this->checkCookie();
        }
        $settings = $this->module['config'];
        include $this->template('impress_guanzhu');
    }


    public  function doMobileVote(){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $record_id=$_GPC['record_id'];
        $userInfo=Util::getClientCookieUserInfo($this::$USER_COOKIE_KEY.$weid);
        if(empty($userInfo)){
            $res['ret']=504;
            return json_encode($res);
        }

        $record=pdo_fetch("SELECT * FROM ".tablename('amouse_impress_record')." WHERE id= $record_id ");
        if(empty($record)){
            $res['ret']=501;
            return json_encode($res);
        }

        if(pdo_update('amouse_impress_record',array('vote'=>$record['vote']+1), array('id'=>$record_id))){
            $res['ret']=0;
            return json_encode($res);
        }
    }


    private function checkCookie(){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $userInfo=Util::getClientCookieUserInfo($this::$USER_COOKIE_KEY.$weid);
        if(empty($userInfo)){
            load()->model('account');
            $_W['account']=account_fetch($_W['uniacid']);
            $appid=trim($_W['account']['key']);
            $secret=trim($_W['account']['secret']); 
            if($_W['account']['level'] != 4){
                //不是认证服务号
                $cfg=$this->module['config'];
                if(!empty($cfg['appid']) && !empty($cfg['secret'])){
                    $appid=trim($cfg['appid']);
                    $secret=trim($cfg['secret']);
                } else {
                    message('请使用认证服务号进行活动，或借用其他认证服务号权限!');
                }
            }

            if(empty($appid) || empty($secret)){
                message('请到管理后台设置完整的 AppID 和AppSecret !');
            }
            $url=$_W['siteroot']."app/".substr($this->createMobileUrl('userinfo', array('mau'=>$mau), true), 2);
            $oauth2_code="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
            header("location:$oauth2_code");
            exit;
        }
    }


    public function doMobileUserinfo(){
        global $_GPC, $_W;
        $weid=$_W['uniacid']; //当前公众号ID
        $mau=$_GPC['mau'];
        load()->func('communication');
        //用户不授权返回提示说明
        if($_GPC['code'] == "authdeny"){
            $url2=$_W['siteroot']."app/".substr($this->createMobileUrl('index', array(), true), 2);
            header("location:$url2");
            exit('authdeny');
        }
        //高级接口取未关注用户Openid
        if(isset($_GPC['code'])){
            //第二步：获得到了OpenID
            $cfg=$this->module['config'];
            load()->model('account');
            
            $_W['account']=account_fetch($_W['uniacid']); 
            $serverapp=$_W['account']['level']; 
            $appid=trim($_W['account']['key']);
            $secret=trim($_W['account']['secret']);
            if($serverapp != 4){
                //不是认证服务号
                if(!empty($cfg['appid']) && !empty($cfg['secret'])){
                    $appid=trim($cfg['appid']);
                    $secret=trim($cfg['secret']);
                } else {
                    //如果没有借用，判断是否认证服务号
                    message('请使用认证服务号进行活动，或借用其他认证服务号权限!');
                }
            }
            if(empty($appid) || empty($secret)){
                message('请到管理后台设置完整的 AppID 和AppSecret !');
            }
            $state=$_GPC['state'];
            //1为关注用户, 0为未关注用户
            $code=$_GPC['code'];
            $oauth2_code="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
            $content=ihttp_get($oauth2_code);
            $token=@json_decode($content['content'], true);
            if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])
            ){
                echo '<h1>获取微信公众号授权'.$code.'失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />'.$content['meta'].'<h1>';
                exit;
            }
            $from_user=$token['openid'];
            //未关注用户和关注用户取全局access_token值的方式不一样
            if($state == 1){
                $oauth2_url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
                $content=ihttp_get($oauth2_url);
                $token_all=@ json_decode($content['content'], true);
                if(empty($token_all) || !is_array($token_all) || empty($token_all['access_token'])){
                    echo '<h1>获取微信公众号授权失败[无法取得access_token],请稍后重试！公众平台返回原始数据为:<br />'.$content['meta'].'<h1>';
                    exit;
                }
                $access_token=$token_all['access_token'];
                $oauth2_url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$from_user."&lang=zh_CN";
            } else {
                $access_token=$token['access_token'];
                $oauth2_url="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$from_user."&lang=zh_CN";
            }

            //使用全局ACCESS_TOKEN获取OpenID的详细信息
            $content=ihttp_get($oauth2_url);
            $info=@json_decode($content['content'], true);
            if(empty($info) || !is_array($info) || empty($info['openid']) || empty($info['nickname'])){
                echo '<h1>获取微信公众号授权失败[无法取得info], 请稍后重试！<h1>';
                exit;
            }

            $row=array('nickname'=>$info["nickname"], 'gender'=>$info['sex']);
            if(!empty($info["country"])){
                $row['nationality']=$info["country"];
            }
            if(!empty($info["province"])){
                $row['resideprovince']=$info["province"];
            }
            if(!empty($info["city"])){
                $row['residecity']=$info["city"];
            }
            if(!empty($info["headimgurl"])){
                $row['avatar']=$info["headimgurl"];
            }
            fans_update($info['openid'], $row);
            $newfans=false;
            $member=pdo_fetch("select * from ".tablename('amouse_impress_user')." where oid=:oid limit 1", array(":oid"=>$info['openid']));
            if(!empty($member)){
                pdo_update("amouse_impress_user",
                    array("avatar"=>$info["headimgurl"], "nickname"=>$info["nickname"]),
                    array("oid"=>$info['openid']));
            } else {
                $fans=array("uniacid"=>$weid,
                    "oid"=>$info['openid'],
                    "avatar"=>$info["headimgurl"],
                    'nickname'=>$info["nickname"],'createtime'=>TIMESTAMP );
                pdo_insert("amouse_impress_user", $fans);
            }

            Util::setClientCookieUserInfo($info,$this::$USER_COOKIE_KEY.$_W['uniacid']);//保存到cookie
            $url=$_W['siteroot']."app/".substr($this->createMobileUrl('index', array('openid'=>$info['openid']), true), 2);
            header("location:$url");
            exit;
        } else {
            echo '<h1>网页授权域名设置出错!</h1>';
            exit;
        }
    }


    /*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为后台管理＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/

    //订单
    public function doWebManages(){
        global $_W, $_GPC;
        $weid=$_W['uniacid']; //当前公众号ID
        $op= $_GPC['op'] ? $_GPC['op'] : 'display';
        if($op == 'display') {  
            $pindex= max(1, intval($_GPC['page']));
            $psize= 20; //每页显示
            $condition= "WHERE `uniacid` = $weid";
            if(!empty($_GPC['keyword'])) {
                $condition .= " AND nickname LIKE '%".$_GPC['keyword']."%'";
            }
            $list= pdo_fetchall('SELECT * FROM '.tablename('amouse_impress_user')." $condition order by id desc LIMIT ".($pindex -1) * $psize.','.$psize);
            $total= pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('amouse_impress_user').$condition);
            $pager= pagination($total, $pindex, $psize);
        }elseif($op == 'list'){
            $pindex= max(1, intval($_GPC['page']));
            $psize= 20; //每页显示
            $uid= intval($_GPC['uid']);
            $condition= "WHERE `uid` = $uid";
            if(!empty($_GPC['keyword'])) {
                $condition .= " AND nickname LIKE '%".$_GPC['keyword']."%'";
            }
            $list= pdo_fetchall('SELECT * FROM '.tablename('amouse_impress_record')." $condition order by id desc LIMIT ".($pindex -1) * $psize.','.$psize);
            $total= pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('amouse_impress_record').$condition);
            $pager= pagination($total, $pindex, $psize);
        }elseif($op=='deleteop'){//删除
            $id = intval($_GPC['id']);
            pdo_delete('amouse_impress_record', array('uid'=>$id));
            //删除收藏
            $result = pdo_delete('amouse_impress_user', array('id'=>$id, 'uniacid'=>$weid));
            if(intval($result) == 1){
                message('删除成功.', $this->createWebUrl('Manages'), 'success');
            } else {
                message('删除失败.');
            }
        }elseif($op=='delete'){//删除
            $uid = intval($_GPC['uid']);
            $rid = intval($_GPC['rid']);
            $result =  pdo_delete('amouse_impress_record', array('id'=>$rid));
            if(intval($result) == 1){
                message('删除成功.', $this->createWebUrl('Manages',array('op'=>'list','uid'=>$uid)), 'success');
            } else {
                message('删除失败.');
            }
        }
        include $this->template('user');
    }

    /**
     * 用户信息导出
     */
    public function  doWebUDownload(){
        require_once 'udownload.php';
    }

    function  encode($value){
        return iconv("utf-8", "gb2312", $value);
    }
    public function __mobile($f_name){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $sharedata = $this->module['config'];
        $shae_url = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
        include_once 'mobile/'.strtolower(substr($f_name, 8)).'.php';
    }

}