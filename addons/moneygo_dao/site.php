<?php
/**
 * 夺宝岛模块微站定义
 *
 * 
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Moneygo_daoModuleSite extends WeModuleSite {
//会员信息提取
	public function __construct(){
		global $_W;
		load()->model('mc');
		$profile = pdo_fetch("SELECT * FROM " . tablename('moneygo_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$_W['openid']}'");
		if (empty($profile)) {
			$userinfo = mc_oauth_userinfo();
			if (!empty($userinfo['avatar'])) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'from_user' => $userinfo['openid'],
					'nickname' => $userinfo['nickname'],
					'avatar' => $userinfo['avatar']
				);
				$member = pdo_fetch("SELECT * FROM " . tablename('moneygo_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$userinfo['openid']}'");
				if (empty($member['id'])) {
					pdo_insert('moneygo_member', $data);
				}else{
					pdo_update('moneygo_member', $data, array('id' =>$member['id']));
				}
			}
		}
	}
/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为微信端页面管理＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
	public function doMobileIndex() {
		$this->__mobile(__FUNCTION__);
	}
	
	

//商品详情
	public function doMobiledetails() {
		$this->__mobile(__FUNCTION__);
	}
//分类列表
	public function doMobilelist() {
		$this->__mobile(__FUNCTION__);
	}
//购买
	public function doMobileexchange() {
		$this->__mobile(__FUNCTION__);
	}
//提交订单
	public function doMobilepostorder() {
		$this->__mobile(__FUNCTION__);
	}
//兑换记录
	public function doMobilemyorder() {
		$this->__mobile(__FUNCTION__);
	}
//我的兑换码
	public function doMobilemycodes() {
		$this->__mobile(__FUNCTION__);
	}
//兑换码加载	
	public function doMobileshowrecord() {
		$this->__mobile(__FUNCTION__);
	}
//个人中心
	public function doMobileprofile() {
		$this->__mobile(__FUNCTION__);
	}
//个人资料
	public function doMobileprodata() {
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileAjaxypsubmit() {
		$this->__mobile(__FUNCTION__);
	}
//往期开奖
	public function doMobileperiod() {
		$this->__mobile(__FUNCTION__);
	}
//获得的商品
	public function doMobileprize(){
		$this->__mobile(__FUNCTION__);
	}
//晒单
	public function doMobileshow() {
		$this->__mobile(__FUNCTION__);
	}
//玩儿法介绍
	public function doMobileintroduction() {
		global $_W, $_GPC;
		checkauth();
		$share_data = $this->module['config'];
		include $this->template('introduction');
	}
//获取玩法模式
   public function getshu($id){
   	global $_W;
	$xinxi = pdo_fetch("SELECT * FROM".tablename('moneygo_record')."WHERE uniacid='{$_W['uniacid']}' AND id=:id",array(':id'=>$id));
	$goods = pdo_fetch("SELECT * FROM".tablename('moneygo_goodslist')."WHERE uniacid='{$_W['uniacid']}' AND id=:id",array(':id'=>$xinxi['sid']));
	
	return $goods['danjia'];
   }
//付款
	public function doMobilePay() {
		$this->__mobile(__FUNCTION__);
	}
//关注引导
	public function doMobileattention() {
		$share_data = $this->module['config'];
		include $this->template('attention');
	}
//付款结果返回
	public function payResult($params){
		
		
		global $_W, $_GPC;
		$uniacid=$_W['uniacid'];
		$jishu = $this->getshu($params['tid']);
		$fee = intval($params['fee']);
		$fee = round($fee/$jishu);
		
		
		//计算取出来几个，根据钱来判断几个码
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		$paytype = array('credit' => '1', 'wechat' => '3', 'alipay' => '2');
		$data['paytype'] = $paytype[$params['type']];
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		$order = pdo_fetch("SELECT * FROM " . tablename('moneygo_record') . " WHERE id ='{$params['tid']}'");//获取商品ID
		$codes = pdo_fetch("SELECT * FROM " . tablename('moneygo_goodscodes') . " WHERE s_id ='{$order['sid']}'");//获取商品code
		$sidm = pdo_fetch("SELECT * FROM " . tablename('moneygo_goodslist') . " WHERE id ='{$order['sid']}'");//获取商品详情
		if ($params['result'] == 'success' && $params['from'] == 'notify') {
			if ($order['status'] != 1) {
				if ($params['result'] == 'success') {
					$data['status'] = 1;
					$s_codes=unserialize($codes['s_codes']);//转换商品code
					$c_number=intval($codes['s_len']);
					if ($c_number>0) {
						
						if ($fee<$c_number) {
							//计算购买的夺宝码
							
							//$fee为基数，比如5个，从数组0-4取出5个，再组合数组
							$data['s_codes']=array_slice($s_codes,0,$fee);
							$data['s_codes']=serialize($data['s_codes']);
							$r_codes['s_len']=$c_number-$fee;
							$r_codes['s_codes']=array_slice($s_codes,$fee,$r_codes['s_len']);
							$r_codes['s_codes']=serialize($r_codes['s_codes']);
							$sid_mess['canyurenshu']=$sidm['canyurenshu']+$fee;
							$sid_mess['shengyurenshu']=$sidm['shengyurenshu']-$fee;
							$sid_mess['scale']=round(($sid_mess['canyurenshu'] / $sidm['zongrenshu'])*100);
                         
						  
							//执行数据库更新
							pdo_update('moneygo_record', $data, array('id' => $params['tid']));
							pdo_update('moneygo_goodscodes', $r_codes, array('id' => $codes['id']));
							pdo_update('moneygo_goodslist', $sid_mess, array('id' => $sidm['id']));
							$result_mess = '支付成功！';
						}elseif ($fee>=$c_number) {
							
						   $data['s_codes']=$codes['s_codes'];
							pdo_update('moneygo_record', $data, array('id' => $params['tid']));
							/*$data['s_codes']=serialize($data['s_codes']);*/
							$r_codes['s_len']=0;
							$r_codes['s_codes']=NULL;

							//计算中奖结果
							$jisuanzjr = $this->jisuanzjr($sidm,$order['sid']);

							$sid_mess['q_user']=$jisuanzjr['openid'];
							$sid_mess['canyurenshu']=$sidm['zongrenshu'];
							$sid_mess['shengyurenshu']=0;
							$sid_mess['q_user_code']=$jisuanzjr['wincode'];
							$pro_m = pdo_fetch("SELECT * FROM " . tablename('moneygo_member') . " WHERE uniacid = '{$_W['uniacid']}' and from_user ='{$sid_mess['q_user']}'");//用户信息
							$sid_mess['q_uid']=$pro_m['nickname'];
							$sid_mess['status']=1;
							$sid_mess['q_end_time']=TIMESTAMP;
							$sid_mess['scale']=100;
							//模板消息推送
							$this->sendsuccess($sid_mess['q_user'],$jishu);
							//生成新一期
							$this->insetnewgoods($sidm);

							if ($fee==$c_number) {
								//执行数据库操作
								pdo_update('moneygo_goodscodes', $r_codes, array('id' => $codes['id']));
								pdo_update('moneygo_goodslist', $sid_mess, array('id' => $sidm['id']));
								$result_mess = '支付成功！';
							}else{
								$data['count'] = $c_number;
								$reprice = $fee - $c_number;
								load()->model('mc');
								$result_c = mc_credit_update($_W['member']['uid'], 'credit2', $reprice);
								//执行数据库操作
								pdo_update('moneygo_goodscodes', $r_codes, array('id' => $codes['id']));
								pdo_update('moneygo_goodslist', $sid_mess, array('id' => $sidm['id']));
								$result_mess = '支付成功！';
							}
						}
					}else{
						$reprice = $fee;
						$data['status'] = 0;
						load()->model('mc');
						$result_c = mc_credit_update($_W['member']['uid'], 'credit2', $reprice);
						$result_mess = '支付失败，已退款！';
						pdo_update('moneygo_record', $data, array('id' => $params['tid']));
					}
				}
			}
		}
		
		if ($params['from'] == 'return') {
			$this->sendpaymess($sidm,$fee,$jishu);
			if ($order['status'] != 1) {
				if ($params['result'] == 'success') {
					$data['status'] = 1;
					$s_codes=unserialize($codes['s_codes']);//转换商品code
					$c_number=intval($codes['s_len']);;
					if ($c_number>0) {
						if ($fee<$c_number) {
							//计算购买的夺宝码
							$data['s_codes']=array_slice($s_codes,0,$fee);
							$data['s_codes']=serialize($data['s_codes']);
							$r_codes['s_len']=$c_number-$fee;
							$r_codes['s_codes']=array_slice($s_codes,$fee,$r_codes['s_len']);
							$r_codes['s_codes']=serialize($r_codes['s_codes']);
							$sid_mess['canyurenshu']=$sidm['canyurenshu']+$fee;
							$sid_mess['shengyurenshu']=$sidm['shengyurenshu']-$fee;
							$sid_mess['scale']=round(($sid_mess['canyurenshu'] / $sidm['zongrenshu'])*100);

							//执行数据库更新
							pdo_update('moneygo_record', $data, array('id' => $params['tid']));
							pdo_update('moneygo_goodscodes', $r_codes, array('id' => $codes['id']));
							pdo_update('moneygo_goodslist', $sid_mess, array('id' => $sidm['id']));
							$result_mess = '支付成功！';
						}elseif ($fee>=$c_number) {
							$data['s_codes']=$codes['s_codes'];
							pdo_update('moneygo_record', $data, array('id' => $params['tid']));
							/*$data['s_codes']=serialize($data['s_codes']);*/
							$r_codes['s_len']=0;
							$r_codes['s_codes']=NULL;
							//计算中奖结果
							$jisuanzjr = $this->jisuanzjr($sidm,$order['sid']);

							$sid_mess['q_user']=$jisuanzjr['openid'];
							$sid_mess['canyurenshu']=$sidm['zongrenshu'];
							$sid_mess['shengyurenshu']=0;
							$sid_mess['q_user_code']=$jisuanzjr['wincode'];
							$pro_m = pdo_fetch("SELECT * FROM " . tablename('moneygo_member') . " WHERE uniacid = '{$_W['uniacid']}' and from_user ='{$sid_mess['q_user']}'");//用户信息
							$sid_mess['q_uid']=$pro_m['nickname'];
							$sid_mess['status']=1;
							$sid_mess['q_end_time']=TIMESTAMP;
							$sid_mess['scale']=100;

							//模板消息推送
							$this->sendsuccess($sid_mess['q_user'],$jishu);
							//生成新一期
							$this->insetnewgoods($sidm);
							
							if ($fee==$c_number) {
								//执行数据库操作
								pdo_update('moneygo_goodscodes', $r_codes, array('id' => $codes['id']));
								pdo_update('moneygo_goodslist', $sid_mess, array('id' => $sidm['id']));
								$result_mess = '支付成功！';
							}else{
								$data['count'] = $c_number;
								$reprice = $fee - $c_number;
								load()->model('mc');
								$result_c = mc_credit_update($_W['member']['uid'], 'credit2', $reprice);
								//执行数据库操作
								pdo_update('moneygo_goodscodes', $r_codes, array('id' => $codes['id']));
								pdo_update('moneygo_goodslist', $sid_mess, array('id' => $sidm['id']));
								$result_mess = '支付成功！';
							}
						}
					}else{
						$reprice = $fee;
						$data['status'] = 0;
						load()->model('mc');
						$result_c = mc_credit_update($_W['member']['uid'], 'credit2', $reprice);
						$result_mess = '支付失败，已退款！';
						pdo_update('moneygo_record', $data, array('id' => $params['tid']));
					}
				}
			}
			$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $setting['creditbehaviors']['currency'];
			if ($params['type'] == $credit) {
				message('支付成功！', $this->createMobileUrl('myorder'), 'success');
			} else {
				message('支付成功！', '../../app/' . $this->createMobileUrl('myorder'), 'success');
			}
		}
	}

/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为后台管理＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
//商品管理
	private function getGoodsStatus($status){
		$status = intval($status);
		if ($status == 1) {
			return '下架';
		} elseif ($status == 2) {
			return '上架';
		} else {
			return '未知';
		}
	}
//商品管理
	public function doWebGoods() {
		$this->__web(__FUNCTION__);
	}
	
	
		//商品分类
	public function doWebcate() {
		$this->__web(__FUNCTION__);
	}
	//模式管理
	public function doWebDuoyuan() {
		$this->__web(__FUNCTION__);
	}
//往期商品
	public function doWebshowperiod() {
		$this->__web(__FUNCTION__);
	}
//交易记录
	public function doWebRecord() {
		$this->__web(__FUNCTION__);
	}
//中奖订单
	public function doWebOrder() {
		$this->__web(__FUNCTION__);
	}
//中奖订单发货
	public function doWebsendprize() {
		$this->__web(__FUNCTION__);
	}
//会员管理
	public function doWebMember() {
		$this->__web(__FUNCTION__);
	}
//兑换码加载	
	public function doWebshowrecords() {
		$this->__web(__FUNCTION__);
	}
//商品交易记录	
	public function doWebsrecords() {
		$this->__web(__FUNCTION__);
	}

	public function __web($f_name){
		global $_W,$_GPC;
		checklogin();
		$uniacid=$_W['uniacid'];
		load()->func('tpl');
		include_once  'web/'.strtolower(substr($f_name,5)).'.php';
	}
	
	public function __mobile($f_name){
		global $_W,$_GPC;
//		checkauth();
		$uniacid=$_W['uniacid'];
		$share_data = $this->module['config'];
		$to_url = $_W['siteroot'].'app/'.$this->createMobileUrl('attention', array());
		include_once  'mobile/'.strtolower(substr($f_name,8)).'.php';
	}

	public function insetnewgoods($sidm) {
		global $_W;
		//生成新一期商品
		if ($sidm['periods']<$sidm['maxperiods']) {
			$new_sid=array(
				'uniacid'=>$_W['uniacid'],
				'sid'=>$sidm['sid'],
				'title'=>$sidm['title'],
				'price'=>$sidm['price'],
				'zongrenshu'=>$sidm['zongrenshu'],
				'canyurenshu'=>0,
				'shengyurenshu'=>$sidm['zongrenshu'],
				'periods'=>$sidm['periods']+1,
				'maxperiods'=>$sidm['maxperiods'],
				'picarr'=>$sidm['picarr'],
				'content'=>$sidm['content'],
				'createtime'=>TIMESTAMP,
				'pos'=>$sidm['pos'],
				'status'=>$sidm['status'],
				'danjia'=>$sidm['danjia'],
				'zongji'=>$sidm['danjia']*$sidm['price'],
				'cid'=>$sidm['cid'],
			);
			pdo_insert('moneygo_goodslist',$new_sid);
			$id = pdo_insertid();

			$CountNum=intval($sidm['price']);
			$new_codes=array();
			for($i=1;$i<=$CountNum;$i++){
				$new_codes[$i]=1000000+$i;
			}shuffle($new_codes);$new_codes=serialize($new_codes);

			$data1['uniacid'] = $_W['uniacid'];
			$data1['s_id'] = $id;
			$data1['s_len'] = $CountNum;
			$data1['s_codes'] = $new_codes;
			$data1['s_codes_tmp'] = $new_codes;

			$ret = pdo_insert('moneygo_goodscodes', $data1);
			unset($new_codes);
		}
	}

	public function sendsuccess($openid,$jishu) {
		global $_W;
		//模板消息推送
		load()->model('account');
		
		$access_token = WeAccount::token();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token."";
		$json_data = array(
		  'touser'=> $openid,
		  'template_id'=>$this->module['config']['win_mess'],
		  'url'=>$_W['siteroot'].'app/'.$this->createMobileUrl('prize'),
		  'topcolor'=>'#FF0000',
		  "data"=>array("title"=>array('value' =>'尊敬的客户' ,'color' =>'#173177' ),
		  				"headinfo"=>array('value' =>'恭喜您，中奖啦！' ,'color' =>'#FF0000' ),
		  				"program"=>array('value' =>$jishu.'元夺宝','color'=>'#FF0000' ),
		  				"result"=>array('value' =>'获得了我们的大奖' ,'color'=>'#FF0000' ),
		  				"remark"=>array('value' =>'点击进入查看中奖详情，祝你生活愉快！' ,'color' =>'#173177' )
		  				)
			);
		$msg_json=json_encode($json_data);
		include_once 'message.php';
		$sendmessage = new WX_message();
		$res=$sendmessage->WX_request($url,$msg_json);
	}

	public function sendpaymess($sidm,$fee,$jishu) {
		global $_W;
		load()->model('account');
		
		$access_token = WeAccount::token();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token."";
		$url2=$_W['siteroot'].'app/'.$this->createMobileUrl('myorder');//点击模板详情跳转的地址url2
		$time = date("Y-m-d H:i:s",time());
		$openid = trim($_W['openid']);
		$msg_json= '{
           	"touser":"'.$openid.'",
           	"template_id":"'.$this->module['config']['succ_mess'].'",
           	"url":"'.$url2.'",
           	"topcolor":"#FF0000",
           	"data":{
               	"first":{
                   "value":"恭喜您，成功参与'.$jishu.'元夺宝！",
                   "color":"#0099FF"
               	},
               	"orderMoneySum":{
					"value":"'.$fee*$jishu.'元",
               	    "color":"#0099FF"
				},
				"orderProductName":{
					"value":"'.$sidm['title'].'",
               		"color":"#0099FF"
				},
               	"Remark":{
                   "value":"点击查看订单详情",
                   "color":"#0099FF"
               	}
           	}
   		}';
   		include_once 'message.php';
   		$sendmessage = new WX_message();
   		$res=$sendmessage->WX_request($url,$msg_json);
	}

	public function jisuanzjr($sidm,$sid) {
		global $_W;
		//计算获奖的code和获奖人
		$s_record = pdo_fetchall("SELECT * FROM " . tablename('moneygo_record') . " WHERE uniacid = '{$_W['uniacid']}' and sid ='{$sid}'");//获取商品所有交易记录
		if (empty($sidm['q_user_code'])) {
			$wincode=mt_rand(1,$sidm['zongrenshu']);
			$wincode=$wincode+1000000;
		}else{
			$wincode=$sidm['q_user_code'];
		}
		//计算获奖人
		foreach ($s_record as $value) {
			$ss_codes=unserialize($value['s_codes']);//转换商品code
			for ($i=0; $i < count($ss_codes) ; $i++) { 
				if ($ss_codes[$i]==$wincode) {
					$sid_mess['q_user']=$value['from_user'];
					break;
				}
			}
		}
		if(empty($sid_mess['q_user'])){
			$ss_codes=unserialize($data['s_codes']);//转换商品code
			for ($i=0; $i < count($ss_codes) ; $i++) { 
				if ($ss_codes[$i]==$wincode) {
					$sid_mess['q_user']=$_W['fans']['from_user'];
					break;
				}
			}
		}

		$result = array('wincode' => $wincode, 'openid' => $sid_mess['q_user']);

		return $result;
	}
}