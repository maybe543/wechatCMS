<?php
/**
 * 犀牛溜冰场模块定义
 *
 * @author Edward Gao <edward@weini.me>
 * @copyright 2014-2015 WeiNi Tech
 * @license MIT
 * @todo 清理代码，完善功能
 */
defined('IN_IA') or exit('Access Denied');

class Weini_rhinoModule extends WeModule {
    public $table_reply = 'weini_rhino_reply';
    public $table_rank = 'weini_rhino_rank';
    public $table_fans = 'weini_rhino_fans';

	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
        global $_W;
        load()->func('tpl');
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        }
        $time = date('Y-m-d H:i', TIMESTAMP + 3600*24);
        if (!$reply) {
            $now = TIMESTAMP;
            $reply = array(
                "title" => "犀牛溜冰场！",
                "description" => "欢迎来到犀牛溜冰场！",
                "picture" => "../addons/weini_rhino/template/mobile/image/start_url.jpg",
                "rule" => "1、自己玩。<br />
                                2、自己学规则。<br />
                                3、结果按分数排名。<br />
                                <br />
                                这个游戏很有意思，但是你得好好玩。
                                <p>1、 手机号码为兑奖重要凭证，填写应当真实有效，如若有误，作废处理。</p>
                                <p>2、 优惠券使用规则参照商家实际制定。</p>
                                <p>3、 本活动最终解释权归维尼科技所有。</p>",
                "bg" => "../addons/weini_rhino/template/mobile/image/background_1.png",
                "end_title" => "活动结束了",
                "end_description" => "活动已经结束了",
                "end_picurl" => "../addons/weini_rhino/template/mobile/image/end_url.jpg",
                "starttime" => $now,
                "endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
                "total_times" => 10,
                "totaldayplay_times" => 3,
                "totaldayshare_times" => 1,
                "sharelottery_times" => 1,
                "gametime" => 30,
                "showusernum" => 15,
                "share_title" => "其实犀牛溜冰场大战不仅可以爽，还快赢奖品的哦！",
                "share_desc" => "犀牛溜冰场大战已经全面开启，敢不敢来挑战~",
                "share_image" => "../addons/weini_rhino/template/mobile/image/share.jpg",
                "follow_url" => "",
                "gameovertext" => "犀牛溜冰场游戏的机会已用完啦，快分享给好友，接着战吧！",
                "tips1text" => "碰",
                "tips2text" => "厉害",
                "tips3text" => "碰犀牛",
                "signtext" => "个",
                "isneedfollow" => 1,
                "awardtip" => "注:活动时间截止{$time},活动结束后依次按排行榜名次发奖",
                "copyright" => "维尼科技",
                "copyrighturl" => "http://www.weini.me/"
            );
        }
        include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

    public function fieldsFormSubmit($rid) {
        //规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
        global $_W,$_GPC;
        load()->func('file');
        $id = intval($_GPC['reply_id']);

        $insert = array(
            "rid" => $rid,
            "weid" => $_W['uniacid'],
            "title" => $_GPC['title'],
            //"picture" => $_GPC['picture'],
            "description" => $_GPC['description'],
            "rule" => trim($_GPC['rule']),
            //本游戏不支持自定义背景。
            //"bg" => "../addons/weini_rhino/template/mobile/image/background_1.png",
            "end_title" => $_GPC['end_title'],
            "end_description" => $_GPC['end_description'],
            //"end_picurl" => $_GPC['end_picurl'],
            "starttime" => strtotime($_GPC['datelimit']['start']),
            "endtime" => strtotime($_GPC['datelimit']['end']),
            "total_times" => $_GPC['total_times'],
            "totaldayplay_times" => $_GPC['totaldayplay_times'],
            "totaldayshare_times" => $_GPC['totaldayshare_times'],
            "sharelottery_times" => $_GPC['sharelottery_times'],
            "gametime" => $_GPC['gametime'],
            "showusernum" => $_GPC['showusernum'],
            "share_title" => $_GPC['share_title'],
            "share_desc" => $_GPC['share_desc'],
            "share_url" => $_GPC['share_url'],
            //"share_image" => $_GPC['share_image'],
            "follow_url" => $_GPC['follow_url'],
            "gameovertext" => $_GPC['gameovertext'],
            "tips1text" => $_GPC['tips1text'],
            "tips2text" => $_GPC['tips2text'],
            "tips3text" => $_GPC['tips3text'],
            "signtext" => $_GPC['signtext'],
            "isneedfollow" => $_GPC['isneedfollow'],
            "awardtip" => $_GPC['awardtip'],
            "copyright" => $_GPC['copyright'],
        );
        if (!empty($_GPC['start_picurl'])) {
            $insert['picture'] = $_GPC['start_picurl'];
        }

        if (!empty($_GPC['end_picurl'])) {
            $insert['end_picurl'] = $_GPC['end_picurl'];
        }
        if (!empty($_GPC['share_image'])) {
            $insert['share_image'] = $_GPC['share_image'];
        }
        if (empty($id)) {
            if ($insert['starttime'] <= time()) {
                $insert['status'] = 1;
            } else {
                $insert['status'] = 0;
            }
            pdo_insert($this->table_reply, $insert);
        } else {
            pdo_update($this->table_reply, $insert, array('id' => $id));
        }
    }

    public function ruleDeleted($rid) {
        //删除规则时调用，这里 $rid 为对应的规则编号
        pdo_delete($this->table_reply, array('rid' => $rid));
        pdo_delete($this->table_fans, array('rid' => $rid));
        pdo_delete($this->table_rank, array('rid' => $rid));
        return true;
    }

    public function settingsDisplay($settings) {
        global $_W, $_GPC;
        //点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
        //在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
        if(checksubmit()) {
            //字段验证, 并获得正确的数据$dat
            $this->saveSettings($dat);
        }
        //这里来展示设置项表单
        include $this->template('setting');
    }
}