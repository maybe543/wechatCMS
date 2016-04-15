<?php
global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$uid=$_W["uid"];
		$uniacid=$_W['uniaccount']['uniacid'];
		
		if ($operation == 'add') {
			$category=pdo_fetchall("SELECT * FROM ".tablename('netsbd_news_category')." WHERE uniacid=".$uniacid);
			load()->func('tpl');
			if(!empty($_GPC['id'])){
				$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_news')." WHERE id=".$_GPC['id']);
			}
			include $this->template('help');
		}elseif($operation=='display'){
			$record=pdo_fetchall("SELECT * FROM ".tablename('netsbd_news')." WHERE cid<0 AND  uniacid=".$uniacid);
			if(empty($record)){
				initHelp();
				$record=pdo_fetchall("SELECT * FROM ".tablename('netsbd_news')." WHERE cid<0 AND  uniacid=".$uniacid);
			}
			include $this->template('help');
		}elseif($operation=='del'){
			$i=pdo_delete("netsbd_news",array("id" => $_GPC['id']));
			if($i>0){
				message('删除成功！', $this->createWebUrl('Hxshelp', array('op' => 'display')), 'success');
			}else{
				message('删除失败，请联系管理员！', $this->createWebUrl('Hxshelp', array('op' => 'display')), 'success');
			}
		}elseif ($operation == 'post') {
				$r["cid"]=$_GPC['cid'];
				$r["title"]=$_GPC['title'];
				$r["brief"]=$_GPC['brief'];
				$r["picture"]=$_GPC['picture'];
				$r["content"]=$_GPC['content'];
				$r["source"]=$_GPC['source'];
				$r["source_url"]=$_GPC['source_url'];
				$r["author"]=$_GPC['author'];
				$r["tag"]=$_GPC['tag'];
				$r["like_num"]=$_GPC['like_num'];
				$r["click_num"]=$_GPC['click_num'];
				$r["comment_num"]=$_GPC['comment_num'];
				$r["share_num"]=$_GPC['share_num'];
				$r["ishide"]=$_GPC['ishide'];
				$r["createtime"]=TIMESTAMP;
				$r["sort"]=$_GPC['sort'];
			if(empty($_GPC['id'])){
				$r["uid"]=$uid;
				$r["uniacid"]=$uniacid;
				pdo_insert("netsbd_news",$r);
			}else{
				$r["createtime"]=TIMESTAMP;
				pdo_update("netsbd_news",$r,array('id' => $_GPC['id']));
			}
			message('保存成功！', $this->createWebUrl('Hxshelp', array('op' => 'display')), 'success');
		}
		
		function initHelp(){
			global $_GPC, $_W;
			$initSql="INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ('-1', '{$_W['uniaccount']['uniacid']}', ' 相关违规说明','&lt;p&gt;为规范提现和言论环境，以下是针对违规评论和作弊行为的相

关规则：&lt;/p&gt;&lt;p&gt;1.禁止发布任何形式广告内容，违者视情节轻重给予禁言或封号

处理;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;2.严禁利用任何违法违规手段刷

分，一经发现直接封号处理;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;3.建议不

要将多个帐号余额提现到同一收款帐号，避免被系统自动加入监控名单;&lt;/p&gt;&lt;p&gt;

华新社的健壮成长，我们一起努力！&lt;/p&gt;&lt;p&gt;最终解释权归所有

&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '1445321159');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ('-2', '{$_W['uniaccount']['uniacid']}', '礼品商城如何兑换商品

？', '&lt;p&gt;入口：【抢钱】- 【礼品商城】&lt;/p&gt;&lt;p&gt;消费方

式：支持账户余额消费(不支持积分)，可直接购买物美价廉的商品、参与抽奖活动、话费充值

、优惠劵兑换等等。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '1444826598');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ( '-2', '{$_W['uniaccount']['uniacid']}', '总收入余额如何提现？

','&lt;p&gt;当前总收入金额大于或等于30元，可进行提现。

&lt;/p&gt;&lt;p&gt;提现渠道：微信支付。&lt;/p&gt;&lt;p&gt;提现费用：免手续费。

&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','1444826627');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ('-2', '{$_W['uniaccount']['uniacid']}', '积分兑换现金的比例是

多少？', '&lt;p&gt;兑换比例依据：根据当日广告收益额度，用户总数，用户

活跃度等因素来决定次日可用于投放的分成金额。&lt;/p&gt;&lt;p&gt;积分兑换成现金的比例

与广告收益、用户活跃度等参数成正比。参与度越高，则每1个积分所兑换的现金就越多。

&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','1444826653');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ( '-2', '{$_W['uniaccount']['uniacid']}', '积分如何转化为现金？

',  '&lt;p&gt;每日凌晨系统自动将用户当日积分按照一定“兑换率”转化为现

金，存入用户的“总收入金额”，并将当日积分清零！

&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','1444826671');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ( '-2', '{$_W['uniaccount']['uniacid']}', '如何获取积分？',  '&lt;section&gt;&lt;p&gt;&lt;table class=&quot;table fs-14&quot; 

cellspacing=&quot;0&quot; cellpadding=&quot;0&quot;&gt;&lt;tbody&gt;&lt;tr 

class=&quot;firstRow&quot;&gt;&lt;th&gt;每日常规操作&lt;/th&gt;&lt;th&gt;积分/次

&lt;/th&gt;&lt;th&gt;每日积分上限&lt;/th&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;每日登录

&lt;/td&gt;&lt;td&gt;3积分

&lt;/td&gt;&lt;td&gt;3&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;分享资讯

&lt;/td&gt;&lt;td style=&quot;word-break: break-all;&quot;&gt;20积分/篇

&lt;/td&gt;&lt;td style=&quot;word-break: break-

all;&quot;&gt;500&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;推荐注册

&lt;/td&gt;&lt;td&gt;100积分/人

&lt;/td&gt;&lt;td&gt;500&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;分享被阅读

&lt;/td&gt;&lt;td style=&quot;word-break: break-all;&quot;&gt;1积分/次

&lt;/td&gt;&lt;td style=&quot;word-break: break-

all;&quot;&gt;500&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;评论点赞

&lt;/td&gt;&lt;td&gt;1积分/次

&lt;/td&gt;&lt;td&gt;100&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;评论被赞

&lt;/td&gt;&lt;td&gt;2积分/次

&lt;/td&gt;&lt;td&gt;100&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;评论资讯

&lt;/td&gt;&lt;td&gt;2积分/次

&lt;/td&gt;&lt;td&gt;100&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;阅读资讯

&lt;/td&gt;&lt;td&gt;5积分/篇

&lt;/td&gt;&lt;td&gt;120&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;点击广告

&lt;/td&gt;&lt;td style=&quot;word-break: break-all;&quot;&gt;20积分/次

&lt;/td&gt;&lt;td style=&quot;word-break: break-

all;&quot;&gt;500&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;&lt;br/&gt;&lt;/

p&gt;&lt;table class=&quot;table fs-14&quot; cellspacing=&quot;1&quot; 

cellpadding=&quot;0&quot;&gt;&lt;tbody&gt;&lt;tr 

class=&quot;firstRow&quot;&gt;&lt;th&gt;特别加分项&lt;/th&gt;&lt;th&gt;积分/次

&lt;/th&gt;&lt;th&gt;说明&lt;/th&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;提现成功分享

&lt;/td&gt;&lt;td&gt;30积分/次&lt;/td&gt;&lt;td&gt;当日仅限1次

&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;兑换成功分享&lt;/td&gt;&lt;td&gt;10积分/

次&lt;/td&gt;&lt;td&gt;当日仅限5次&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;参与游

戏&lt;/td&gt;&lt;td&gt;5积分/次&lt;/td&gt;&lt;td&gt;当日仅限5次

&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;&lt;p&gt;温馨提示：&lt;br/&gt;1、

外部合作媒体文章分享到其他平台后被阅读，不能获得【分享被阅读】积分。

&lt;/p&gt;&lt;/section&gt;',

'1444827013');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ( '-2', '{$_W['uniaccount']['uniacid']}', '颠覆新华社，让读者赚

钱', '&lt;p&gt;华新社资讯将平台所得广告收入，转化为华新社粉丝用户的阅

读分成。人人参与，人人获利，旨在以一种互惠互利的方式共成长!&lt;/p&gt;', '1444826738');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ( '-1', '{$_W['uniaccount']['uniacid']}', '华新社多长时间清理一

次帐户？', '&lt;p&gt;帐户连续100天没有登录，帐户将被注销。

&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '1444827099');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ('-1', '{$_W['uniaccount']['uniacid']}', '华新社是怎样计算奖金

和结算奖金的？',  '&lt;p&gt;1、根据当日广告收益额度，用户总数，用户活

跃度等因素来决定次日可用于投放的分成金额。积分兑换成现金的比例与广告收益、用户活跃

度等参数成正比。参与度越高，则每1个积分所兑换的现金就越多。每日凌晨系统自动将用户当

日积分按照一定“兑换率”转化为现金，存入用户的“总收入金额”，并将当日积分清零！

&lt;/p&gt;&lt;p&gt;2、当账户余额大于30元,可以申请提现,3个工作日支付到你的微信钱包！

提现没有手续费。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','1444827179');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ( '-1', '{$_W['uniaccount']['uniacid']}', '如何加入华新社？', 

'&lt;p&gt;注册就可以加入我们，注册后别忘记收藏网址哦。&lt;/p&gt;', 

 '1444827206');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ( '-1', '{$_W['uniaccount']['uniacid']}', '华新社的收益有保障吗

？',  '&lt;p&gt;华新社有大量的广告主，收入稳定，可靠性高，请各位转客放

心参与。&lt;/p&gt;',

'1444827229');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ( '-1', '{$_W['uniaccount']['uniacid']}', '100个好友如何才能月

入过万？','&lt;p&gt;很多转客说：我只有100个好友，反正赚不到多少钱，

我就不参与了。其实不是这样的！推广在于日积月累，在于您是否用心。即使只有100个好友，

每天业绩超过500元也不是没有可能。所以，您要认真看一下下述的方法：

&lt;/p&gt;&lt;p&gt;1、用心选择分享的内容。每个人都有不同的圈子，每个人的圈子都有不

同的爱好内容，所以您一定要认真从华新社上查到适合您的圈子的内容去分享。只有您分享的

内容是您的朋友喜欢的，他们才有可能转发。转发？对，转是个特值得高兴的事，因为倍增的

速度太快了，从100到1 万，也许就是几分钟的事，所以，您分享一个链接前，一定要想一下这

个链接能不能让朋友们转发！&lt;/p&gt;&lt;p&gt;2、通过摇一摇和附近的人每天多加些好友

，并且保持每天分享5-12篇文章，这样收益一天比一天增多。&lt;/p&gt;&lt;p&gt;3、点击底

部的【推荐好友】学习最快并且是赚得最多的方法。

&nbsp;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;按照这样的方法尝试一下吧，您

将会发现：刚开始您每天的业绩只有您好友的三分之一，一周后业绩增长到和好友数目一样多

，再过一周即可达到好友数量的两倍，再过一段时间，您会突然发现您的业绩已经成了好友数

量的N倍。原因是什么呢？就是因为您坚持每天在做，总有一些链接被人一次又一次的转载，而

不管转载多少次，我们的系统都会计入您自己的业绩。这是个几何倍增的效果，每天做到500元

绝对不是梦，这需要您用心去做！&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '1444827313');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES ( '-1', '{$_W['uniaccount']['uniacid']}', '如何推荐朋友加入华新

社？', '&lt;p&gt;在“抢钱”页面点击推荐好友，发送当前页面给你的朋友或

微信群，你朋友点击注册后就会成为你的粉丝。&lt;/p&gt;&lt;p&gt;您所推荐的好友成功注册

后，您将获得额外高额的积分奖励。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '1444827372');
INSERT INTO `ims_netsbd_news`(cid,uniacid,title,content,createtime) VALUES (  '-1', '{$_W['uniaccount']['uniacid']}', '如何进入华新社？', 

 '&lt;p&gt;1、点击顶部关注我们，以后可以直接从微信里面进入。

&lt;/p&gt;&lt;p&gt;2、找回以前分享过的文章，点击文章底部的链接进入。

&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','1445321397');
";
pdo_query($initSql);
		}
?>