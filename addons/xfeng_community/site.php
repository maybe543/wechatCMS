<?php
/**
 * 
 *
 * 
 */
defined('IN_IA') or exit('Access Denied');
load()->classs('wesession');
define(EARTH_RADIUS, 6371); //地球半径，平均半径为6371km
// require_once IA_ROOT ."/addons/xfeng_community/model.php";
define('ALIPAY_GATEWAY', 'https://mapi.alipay.com/gateway.do');
class Xfeng_communityModuleSite extends WeModuleSite {

	function __construct(){
		global $_W, $_GPC;
		

		//导入微信端导航数据
			$navs = pdo_fetchall("SELECT * FROM".tablename('xcommunity_nav')."WHERE uniacid= '{$_W['uniacid']}'");
			if(empty($navs)){
				$data1 =array('displayorder' => 0,'pcate' => 0 ,'title' => '物业服务','url' => '','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1);
				$data2 =array('displayorder' => 0,'pcate' => 0 ,'title' => '小区互动','url' => '','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1);
				$data3 =array('displayorder' => 0,'pcate' => 0 ,'title' => '生活服务','url' => '','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1);
				if ($data1) {
					pdo_insert('xcommunity_nav',$data1);
					$nid1 = pdo_insertid();
					$menu1 = array(
							array('displayorder' => 0,'pcate' => $nid1,'title' => '社区公告','do' => 'announcement','icon' => 'glyphicon glyphicon-bullhorn','bgcolor' => '#95bd38','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=announcement&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/notice.png"),
							array('displayorder' => 0,'pcate' => $nid1,'title' => '小区报修','do' => 'repair','icon' => 'glyphicon glyphicon-wrench','bgcolor' => '#3c87c8','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=repair&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/repair.png"),
							array('displayorder' => 0,'pcate' => $nid1,'title' => '意见建议','do' => 'report','icon' => 'fa fa-legal','bgcolor' => '#dd4b2b','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=report&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/report.png"),
							array('displayorder' => 0,'pcate' => $nid1,'title' => '缴物业费','do' => 'cost','icon' => 'glyphicon glyphicon-send','bgcolor' => '#3c87c8','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=cost&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/cost.png"),

							array('displayorder' => 0,'pcate' => $nid1,'title' => '便民号码','do' => 'phone','icon' => 'glyphicon glyphicon-earphone','bgcolor' => '#ab5e90','url' =>$_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=phone&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/phone.png"),
							array('displayorder' => 0,'pcate' => $nid1,'title' => '常用查询','do' => 'search','icon' => 'glyphicon glyphicon-search','bgcolor' => '#ec9510','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=search&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/chaxun.png"),

					);
					foreach ($menu1 as $key => $value1) {
						pdo_insert('xcommunity_nav',$value1);
					}
				}
				if ($data2) {
					pdo_insert('xcommunity_nav',$data2);
					$nid2 = pdo_insertid();
					$menu2 = array(
							array('displayorder' => 0,'pcate' => $nid2,'title' => '活动','do' => 'activity','icon' => 'glyphicon glyphicon-tasks','bgcolor' => '#65944e','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=activity&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/huodong.png"),
							array('displayorder' => 0,'pcate' => $nid2,'title' => '二手','do' => 'fled','icon' => 'fa fa-exchange','bgcolor' => '#666699','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=fled&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/ershou.png"),
							array('displayorder' => 0,'pcate' => $nid2,'title' => '家政','do' => 'homemaking','icon' => 'glyphicon glyphicon-leaf','bgcolor' => '#95bd38','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=homemaking&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/jiazheng.png"),
							array('displayorder' => 0,'pcate' => $nid2,'title' => '租赁','do' => 'houselease','icon' => 'fa fa-info','bgcolor' => '#38bfc8','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=houselease&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/zuning.png"),
							array('displayorder' => 0,'pcate' => $nid2,'title' => '拼车','do' => 'car','icon' => 'fa fa-truck','bgcolor' => '#7f6000','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=car&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/pingche.png"),
					);
					foreach ($menu2 as $key => $value2) {
						pdo_insert('xcommunity_nav',$value2);
					}
				}		
				if ($data3) {
					pdo_insert('xcommunity_nav',$data3);
					$nid3 = pdo_insertid();
					$menu3 = array(
							array('displayorder' => 0,'pcate' => $nid3,'title' => '周边商家','do' => 'business','icon' => 'glyphicon glyphicon-shopping-cart','bgcolor' => '#65944e','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=business&op=list&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/zhoubian.png"),
							array('displayorder' => 0,'pcate' => $nid3,'title' => '生活超市','do' => 'shopping','icon' => 'glyphicon glyphicon-shopping-cart','bgcolor' => '#65944e','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=shopping&op=list&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid'],'enable' => 1,'thumb' => $_W['siteroot']."addons/xfeng_community/template/mobile/style/style1/static/image/icon/chaoshi.png"),
					);
					foreach ($menu3 as $key => $value3) {
						pdo_insert('xcommunity_nav',$value3);
					}
				}	
			}
		//导入后台菜单数据
		$menus = pdo_fetchall("SELECT * FROM".tablename('xcommunity_menu')."WHERE uniacid= '{$_W['uniacid']}'");
		if (empty($menus)) {
			$data1 =array('pcate' => 0 ,'title' => '管理中心','url' => '','uniacid' => $_W['uniacid'],'do' => 'manage');
			$data2 =array('pcate' => 0 ,'title' => '功能管理','url' => '','uniacid' => $_W['uniacid'],'do' => 'fun');
			$data3 =array('pcate' => 0 ,'title' => '小区超市','url' => '','uniacid' => $_W['uniacid'],'do' => 'shop');
			$data4 =array('pcate' => 0 ,'title' => '小区商家','url' => '','uniacid' => $_W['uniacid'],'do' => 'business');
			$data5 =array('pcate' => 0 ,'title' => '分权系统','url' => '','uniacid' => $_W['uniacid'],'do' => 'perm');
			$data6 =array('pcate' => 0 ,'title' => '系统设置','url' => '','uniacid' => $_W['uniacid'],'do' => 'sysset');
			// $data7 =array('pcate' => 0 ,'title' => '系统超级管理','url' => '','uniacid' => $_W['uniacid'],'do' => 'other');
			if ($data1) {
					pdo_insert('xcommunity_menu',$data1);
					$nid1 = pdo_insertid();
					$m1 = array(
							array('pcate' => $nid1,'title' => '小区管理','url' => './index.php?c=site&a=entry&op=list&do=region&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'manage'),
							array('pcate' => $nid1,'title' => '物业管理','url' => './index.php?c=site&a=entry&op=list&do=property&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'manage'),
							array('pcate' => $nid1,'title' => '业主管理','url' => './index.php?c=site&a=entry&op=list&do=member&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'manage'),
							array('pcate' => $nid1,'title' => '菜单设置','url' => './index.php?c=site&a=entry&op=list&do=nav&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'manage'),
							array('pcate' => $nid1,'title' => '模板设置','url' => './index.php?c=site&a=entry&op=list&do=style&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'manage'),
							array('pcate' => $nid1,'title' => '幻灯管理','url' => './index.php?c=site&a=entry&op=list&do=slide&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'manage'),

					);
					foreach ($m1 as $key => $value1) {
						pdo_insert('xcommunity_menu',$value1);
					}
			}
			if ($data2) {
					pdo_insert('xcommunity_menu',$data2);
					$nid2 = pdo_insertid();
					$m2 = array(
							array('pcate' => $nid2,'title' => '小区公告','url' => './index.php?c=site&a=entry&op=list&do=announcement&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'fun'),
							array('pcate' => $nid2,'title' => '小区报修','url' => './index.php?c=site&a=entry&op=list&do=repair&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'fun'),
							array('pcate' => $nid2,'title' => '意见建议','url' => './index.php?c=site&a=entry&op=list&do=report&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'fun'),
							array('pcate' => $nid2,'title' => '家政服务','url' => './index.php?c=site&a=entry&op=list&do=homemaking&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'fun'),
							array('pcate' => $nid2,'title' => '租赁服务','url' => './index.php?c=site&a=entry&op=list&do=houselease&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'fun'),
							array('pcate' => $nid2,'title' => '缴物业费','url' => './index.php?c=site&a=entry&op=list&do=cost&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'fun'),
							array('pcate' => $nid2,'title' => '小区活动','url' => './index.php?c=site&a=entry&op=list&do=activity&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'fun'),
							array('pcate' => $nid2,'title' => '便民查询','url' => './index.php?c=site&a=entry&op=list&do=search&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'fun'),
							array('pcate' => $nid2,'title' => '便民号码','url' => './index.php?c=site&a=entry&op=list&do=phone&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'fun'),
							array('pcate' => $nid2,'title' => '二手市场','url' => './index.php?c=site&a=entry&op=list&do=fled&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'fun'),
							array('pcate' => $nid2,'title' => '小区拼车','url' => './index.php?c=site&a=entry&op=list&do=car&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'fun'),
							array('pcate' => $nid2,'title' => '黑名单管理','url' => './index.php?c=site&a=entry&op=list&do=black&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'fun'),
					);
					foreach ($m2 as $key => $value1) {
						pdo_insert('xcommunity_menu',$value1);
					}
			}
			if ($data3) {
					pdo_insert('xcommunity_menu',$data3);
					$nid3 = pdo_insertid();
					$m3 = array(
							array('pcate' => $nid3,'title' => '订单管理','url' => './index.php?c=site&a=entry&op=order&do=shopping&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'shop'),
							array('pcate' => $nid3,'title' => '商品管理','url' => './index.php?c=site&a=entry&op=goods&do=shopping&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'shop'),
							array('pcate' => $nid3,'title' => '商品分类','url' => './index.php?c=site&a=entry&op=category&do=shopping&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'shop'),
					);
					foreach ($m3 as $key => $value1) {
						pdo_insert('xcommunity_menu',$value1);
					}
			}
			if ($data4) {
					pdo_insert('xcommunity_menu',$data4);
					$nid4 = pdo_insertid();
					$m4 = array(
							// array('pcate' => $nid4,'title' => '用户管理','url' => './index.php?c=site&a=entry&op=users&do=business&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'business'),
							// array('pcate' => $nid4,'title' => '店铺分类','url' => './index.php?c=site&a=entry&op=category&do=business&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'business'),
							array('pcate' => $nid4,'title' => '店铺管理','url' => './index.php?c=site&a=entry&op=dp&do=business&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'business'),
							array('pcate' => $nid4,'title' => '卡券核销','url' => './index.php?c=site&a=entry&op=coupon&do=business&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'business'),
							array('pcate' => $nid4,'title' => '余额提现','url' => './index.php?c=site&a=entry&op=cash&do=business&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'business'),

							array('pcate' => $nid4,'title' => '订单管理','url' => './index.php?c=site&a=entry&op=order&do=business&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'business'),

					);
					foreach ($m4 as $key => $value1) {
						pdo_insert('xcommunity_menu',$value1);
					}
			}
			if ($data5) {
					pdo_insert('xcommunity_menu',$data5);
					$nid5 = pdo_insertid();
					$m5 = array(
							array('pcate' => $nid5,'title' => '用户管理','url' => './index.php?c=site&a=entry&op=list&do=users&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'perm'),
					);
					foreach ($m5 as $key => $value1) {
						pdo_insert('xcommunity_menu',$value1);
					}
			}
			if ($data6) {
					pdo_insert('xcommunity_menu',$data6);
					$nid6 = pdo_insertid();
					$m6 = array(
							array('pcate' => $nid6,'title' => '小区设置','url' => './index.php?c=site&a=entry&do=set&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'sysset'),
							array('pcate' => $nid6,'title' => '通知设置','url' => './index.php?c=site&a=entry&op=list&do=notice&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'sysset'),
							array('pcate' => $nid6,'title' => '短信设置','url' => './index.php?c=site&a=entry&do=sms&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'sysset'),
							array('pcate' => $nid6,'title' => '打印机设置','url' => './index.php?c=site&a=entry&op=list&do=print&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'sysset'),
							array('pcate' => $nid6,'title' => '模板消息设置','url' => './index.php?c=site&a=entry&do=tpl&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'sysset'),
							array('pcate' => $nid6,'title' => '支付方式设置','url' => './index.php?c=site&a=entry&do=pay&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'sysset'),
					);
					foreach ($m6 as $key => $value1) {
						pdo_insert('xcommunity_menu',$value1);
					}
			}
			// if ($data7) {
			// 		pdo_insert('xcommunity_menu',$data7);
			// 		$nid7 = pdo_insertid();
			// 		$m7 = array(
			// 				array('pcate' => $nid7,'title' => '小区控制','url' => './index.php?c=site&a=entry&op=list&do=control&m=xfeng_community','uniacid' => $_W['uniacid'],'do' => 'other'),
			// 		);
			// 		foreach ($m7 as $key => $value1) {
			// 			pdo_insert('xcommunity_menu',$value1);
			// 		}
			// }
		}
		//首次导入模板一
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if (!$styleid) {
			$data = array(
					'uniacid' => $_W['uniacid'],
					'styleid' => 1,
				);
			pdo_insert('xcommunity_template',$data);
		}
	}
	//后台菜单
	public function NavMenu(){
		global $_W;
		$user = pdo_fetch("SELECT * FROM".tablename("xcommunity_users")."WHERE uniacid=:uniacid AND uid=:uid",array(":uniacid" => $_W['uniacid'],":uid" => $_W['uid']));
		$condition = '';
		if ($user) {
			if (empty($user['menus'])) {
				message('没有操作权限,请联系管理员。',referer(),'error');exit();
			}
			$condition .="AND id in({$user['menus']})";
		}
		
		$menus = pdo_fetchall("SELECT * FROM".tablename('xcommunity_menu')."WHERE uniacid=:uniacid AND pcate = 0 $condition",array(':uniacid' => $_W['uniacid']));
		
		$navmenus = array();
		foreach ($menus as $key => $menu) {
			$m = pdo_fetchall("SELECT title,url,id,do FROM".tablename('xcommunity_menu')."WHERE pcate = :pcate AND uniacid =:uniacid $condition",array(':pcate' => $menu['id'],':uniacid' => $_W['uniacid']));
			$navmenus[] = array(
					'title' => $menu['title'],
					'items' => $m,
					'id' => $menu['id'],
					'do' => $menu['do'],
				);
		}
 		return $navmenus;

	}
	//判断是否是操作员管理
	public function user(){
		global $_W;
		$user = pdo_fetch("SELECT * FROM".tablename("xcommunity_users")."WHERE uniacid=:uniacid AND uid=:uid",array(":uniacid" => $_W['uniacid'],":uid" => $_W['uid']));
		return $user;
	}

	//获取当前公众号所有小区信息
	public function regions(){
		global $_W;
		$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
		return $regions;
	}
	//获取当前小区名称
	public function region($regionid){
		global $_W;
		$region = pdo_fetch("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}' AND id=:regionid",array(':regionid' => $regionid));
		return $region;
	}
	//判断当前小区
	public function mreg(){
		global $_W;
		$member = $this->changemember();
		$region = pdo_fetch("SELECT title FROM".tablename('xcommunity_region')."WHERE id='{$member['regionid']}'");
		return $region;
	}
	//判断是否注册成为小区用户
    public function changemember(){
    	global $_GPC,$_W;
    	$memberid = $_W['member']['uid'];
		$m1  = pdo_fetch("SELECT * FROM".tablename('xcommunity_member')."WHERE memberid=:memberid AND weid=:uniacid AND status = 1",array(':memberid' => $memberid,':uniacid' => $_W['uniacid']));
		$m2  = pdo_fetch("SELECT * FROM".tablename('xcommunity_member')."WHERE openid='{$_W['fans']['from_user']}' AND weid=:uniacid AND status = 1",array(':uniacid' => $_W['uniacid']));
		if ($m1) {
			$member = $m1;
		}elseif ($m2) {
			$member = $m2;
		}
		if (empty($member)) {
			header("Location:".$this->createMobileUrl('register'));
			exit;
		}else{
			return $member;
		}
    }
    //获取会员信息
    public function member($openid){
    	global $_W;
    	$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_member')."WHERE openid=:openid AND weid=:uniacid",array(':uniacid' => $_W['uniacid'],':openid' => $openid));
    	return $member;
    }
	//报修投诉短信提醒
	public function Resms($content,$tpl_id,$appkey,$mmobile,$mobile){
		global $_W,$_GPC;
			$tpl_value = urlencode("#content#=$content&#mobile#=$mobile");
			$params    = "mobile=".$mmobile."&tpl_id=".$tpl_id."&tpl_value=".$tpl_value."&key=".$appkey;
			load()->func('communication');
			$url       = 'http://v.juhe.cn/sms/send';
			$content   = ihttp_post($url,$params);	
	}
	/**
	* 读取excel $filename 路径文件名 $indata 返回数据的编码 默认为utf8
	*以下基本都不要修改
	*/
	public function read($filename,$encode='utf-8'){
		require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load($filename);
		$indata = $objPHPExcel->getSheet(0)->toArray();
		return $indata;
			
	 } 
	 protected function column_str($key)
    {
        $array = array(
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            'AA',
            'AB',
            'AC',
            'AD',
            'AE',
            'AF',
            'AG',
            'AH',
            'AI',
            'AJ',
            'AK',
            'AL',
            'AM',
            'AN',
            'AO',
            'AP',
            'AQ',
            'AR',
            'AS',
            'AT',
            'AU',
            'AV',
            'AW',
            'AX',
            'AY',
            'AZ',
            'BA',
            'BB',
            'BC',
            'BD',
            'BE',
            'BF',
            'BG',
            'BH',
            'BI',
            'BJ',
            'BK',
            'BL',
            'BM',
            'BN',
            'BO',
            'BP',
            'BQ',
            'BR',
            'BS',
            'BT',
            'BU',
            'BV',
            'BW',
            'BX',
            'BY',
            'BZ',
            'CA',
            'CB',
            'CC',
            'CD',
            'CE',
            'CF',
            'CG',
            'CH',
            'CI',
            'CJ',
            'CK',
            'CL',
            'CM',
            'CN',
            'CO',
            'CP',
            'CQ',
            'CR',
            'CS',
            'CT',
            'CU',
            'CV',
            'CW',
            'CX',
            'CY',
            'CZ'
        );
        return $array[$key];
    }
    protected function column($key, $columnnum = 1)
    {
        return $this->column_str($key) . $columnnum;
    }
	 //导出数据
	public function export($list, $params = array())
    {
        if (PHP_SAPI == 'cli') {
            die('This example should only be run from a Web Browser');
        }
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
        $excel = new PHPExcel();
        $excel->getProperties()->setCreator("微小区")->setLastModifiedBy("微小区")->setTitle("Office 2007 XLSX Test Document")->setSubject("Office 2007 XLSX Test Document")->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")->setKeywords("office 2007 openxml php")->setCategory("report file");
        $sheet  = $excel->setActiveSheetIndex(0);
        $rownum = 1;
        foreach ($params['columns'] as $key => $column) {
            $sheet->setCellValue($this->column($key, $rownum), $column['title']);
            if (!empty($column['width'])) {
                $sheet->getColumnDimension($this->column_str($key))->setWidth($column['width']);
            }
        }
        $rownum++;
        foreach ($list as $row) {
            $len = count($row);
            for ($i = 0; $i < $len; $i++) {
                $value = $row[$params['columns'][$i]['field']];
                $sheet->setCellValue($this->column($i, $rownum), $value);
            }
            $rownum++;
        }
        $excel->getActiveSheet()->setTitle($params['title']);
        $filename = urlencode($params['title'] . '-' . date('Y-m-d H:i', time()));
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');
        exit;
    }
	 //处理图片上传;
	 public function doMobileimgupload(){
			global $_W,$_GPC;
			if(!empty($_GPC['pic'])){
				preg_match("/data\:image\/([a-z]{1,5})\;base64\,(.*)/",$_GPC['pic'],$r);
				$imgname = 'bl'.time().rand(10000,99999).'.'.$r[1];
				$path = IA_ROOT.'/'.$_W['config']['upload']['attachdir'].'/images/';
				$f =fopen($path.$imgname,'w+');
				fwrite($f,base64_decode($r[2]));
				fclose($f);
				$imgurl = $_W['attachurl'].'images/'.$imgname;
				$is = pdo_insert('xcommunity_images',array('src'=>$imgurl));
				$id = pdo_insertid();
				if(empty($is)){
				 exit(json_encode(array(
					  'errCode'=>1,
					  'message'=>'上传出现错误',
					  'data'=>array('id'=>$_GPC['t'],'picId'=>$id)
				  )));
				}else{
				  exit(json_encode(array(
					  'errCode'=>0,
					  'message'=>'上传成功',
					  'data'=>array('id'=>$_GPC['id'],'picId'=>$id)
				  )));
				}
			}
			
		} 
		
	//获取购物车商品数量
	public function getCartTotal() {
		global $_W;
		$cartotal = pdo_fetchcolumn("select sum(total) from " . tablename('xcommunity_cart') . " where weid = '{$_W['uniacid']}' and from_user='{$_W['fans']['from_user']}'");
		return empty($cartotal) ? 0 : $cartotal;
	}
		
	 	
	//设置订单积分
	public function setOrderCredit($orderid, $add = true) {
		global $_W;
		$order = pdo_fetch("SELECT * FROM " . tablename('xcommunity_order') . " WHERE id = :id limit 1", array(':id' => $orderid));
		if (empty($order)) {
			return false;
		}
		$sql = 'SELECT `goodsid`, `total` FROM ' . tablename('xcommunity_order_goods') . ' WHERE `orderid` = :orderid';
		$orderGoods = pdo_fetch($sql, array(':orderid' => $orderid));
		if (!empty($orderGoods)) {
			$sql = 'SELECT `credit` FROM ' . tablename('xcommunity_goods') . ' WHERE `id` = :id';
			$credit = pdo_fetchcolumn($sql, array(':id' => $orderGoods['goodsid']));
		}
		//增加积分
		if (!empty($credit)) {
			load()->model('mc');
			load()->func('compat.biz');
			$uid = mc_openid2uid($order['from_user']);
			$fans = fans_search($uid, array("credit1"));
			if (!empty($fans)) {
				if (!empty($add)) {
					mc_credit_update($_W['member']['uid'], 'credit1', $credit * $orderGoods['total'], array('0' => $_W['member']['uid'], '购买商品赠送'));
				} else {
					mc_credit_update($_W['member']['uid'], 'credit1', 0 - $credit * $orderGoods['total'], array('0' => $_W['member']['uid'], '微商城操作'));
				}
			}
		}
	}
	//设置订单商品的库存 minus  true 减少  false 增加
	public function setOrderStock($id = '', $minus = true) {
		$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,g.total as goodstotal,o.total FROM " . tablename('xcommunity_order_goods') . " o left join " . tablename('xcommunity_goods') . " g on o.goodsid=g.id "
				. " WHERE o.orderid='{$id}'");
		foreach ($goods as $item) {
			if ($minus) {

				$data = array();
				if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
					$data['total'] = $item['goodstotal'] - $item['total'];
				}
				
				pdo_update('xcommunity_goods', $data, array('id' => $item['id']));
			} else {
				
				$data = array();
				if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
					$data['total'] = $item['goodstotal'] + $item['total'];
				}
				
				pdo_update('xcommunity_goods', $data, array('id' => $item['id']));
			}
		}
	}

	public function payResult($params) {
		global $_W;
		$fee = intval($params['fee']);
		$ordersn = $params['tid'];
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');
		$data['paytype'] = $paytype[$params['type']];
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		if ($params['type'] == 'delivery') {
			$data['status'] = 1;
			$data['paytype'] = 3;
			$order = pdo_fetch("SELECT * FROM".tablename('xcommunity_order')."WHERE ordersn=:ordersn",array(':ordersn' => $ordersn));
			if ($order['type'] == 'shopping') {
				$sql = 'SELECT * FROM ' . tablename('xcommunity_order_goods') . ' WHERE `orderid` = :orderid';
					$goods = pdo_fetch($sql, array(':orderid' => $order['id']));
					$sql = 'SELECT * FROM ' . tablename('xcommunity_goods') . ' WHERE `id` = :id';
					$goodsInfo = pdo_fetch($sql, array(':id' => $goods['goodsid']));
					// 更改库存
					if ($goodsInfo['totalcnf'] == '1' && !empty($goodsInfo['total'])) {
						pdo_update('xcommunity_goods', array('total' => $goodsInfo['total'] - 1), array('id' => $goodsId));
					}
					//微信提醒
					$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_member')."WHERE openid=:openid",array(':openid' => $order['from_user']));
					$notice = pdo_fetchall("SELECT * FROM".tablename('xcommunity_wechat_notice')."WHERE regionid='{$order['regionid']}'");
					foreach ($notice as $key => $value) {
						if ($value['shopping_status'] == 2) {
							$openid = $value['fansopenid'];
							$url = '';
							$tpl = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_tplid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
							$template_id = $tpl['good_tplid'];
							$createtime = date('Y-m-d H:i:s', $_W['timestamp']);
							$content = array(
									'first' => array(
											'value' => '超市新订单通知',
										),
									'keyword1' => array(
											'value' => $goodsInfo['title'].',数量:'.$goods['total'],
										),
									'keyword2' => array(
											'value' => $order['goodsprice'],
										),
									'keyword3'	=> array(
											'value' => $member['realname'],
										),
									'keyword4'    => array(
											'value' => $member['mobile'],
										),
									'keyword5'    => array(
											'value' => $ordersn,
										),
									'remark'    => array(
										'value' => $order['remark'],
									),	
								);
							$this->sendtpl($openid,$url,$template_id,$content);
						}
						
					}
			//普通打印机
					// $print = pdo_fetch("SELECT * FROM".tablename('xcommunity_shopping_print')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
					// $deviceNo = $print['deviceNo'];
					// $key = $print['api_key'];
					// $createtime = date('Y-m-d H:i:s', $_W['timestamp']);
					// $content = "^N1^F1\n";
					// $content .= "^B2 超市新订单通知\n";
					// $content .="内容：".$goodsInfo['title'].',数量:'.$goods['total']."\n";
					// $content .="地址：".$member['address']."\n";
					// $content .="业主：".$member['realname']."\n";
					// $content .="电话：".$member['mobile']."\n";
					// $content .="时间：".$createtime;

					// $result = $this->sendSelfFormatOrderInfo($deviceNo, $key, 1,$content);
					
			pdo_update('xcommunity_order', $data, array('ordersn' => $params['tid']));
			// message('支付成功！', $this->createMobileUrl('home'), 'success');
			header("location: " . $this->createMobileUrl('home'));
			}
		}
		// if ($params['result'] == 'success' && $params['from'] == 'notify') {
		if ($params['from'] == 'return') {
			if ($params['result'] == 'success') {
				$order = pdo_fetch("SELECT * FROM".tablename('xcommunity_order')."WHERE ordersn=:ordersn",array(':ordersn' => $ordersn));
				if ($order['type'] == 'business') {
					//$member = pdo_fetch('SELECT commission,balance FROM'.tablename('xcommunity_business')."WHERE id=:id",array(':id' => $order['uid']));
					//$good = pdo_fetch("SELECT total FROM".tablename('xcommunity_sjdp_goods')."WHERE id=:id",array(':id' => $order['gid']));
					// $data = array(
					// 	'uniacid' => $_W['uniacid'],
					// 	'gid'	=> $order['gid'],
					// 	'openid' => $order['from_user'],
					// 	'couponsn' => date('md') . random(5, 1),
					// 	'type'	=> 1,
					// 	'createtime' => TIMESTAMP,
					// 	'orderid' => $order['id'],
					// 	'uid' => $order['uid'],
					// );
					$goods = pdo_fetch("SELECT sold FROM".tablename('xcommunity_goods')."WHERE id=:gid",array(':gid' => $order['gid']));
					pdo_update('xcommunity_goods',array('sold' => $goods['sold'] + $order['num']),array('id' => $order['gid']));
					if ($order['uid']) {
						//判断是否开启设置提成
						$set = pdo_fetch("SELECT * FROM".tablename('xcommunity_set')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
						if ($set['s_status']) {
							$users = pdo_fetch("SELECT * FROM".tablename('xcommunity_users')."WHERE uniacid:uniacid AND uid=:uid",array(':uniacid' => $_W['uniacid'],':uid' => $order['uid']));
							$balance = number_format(floatval($fee*(1-$users['commission'])));
						}else{
							$balance = $fee;
						}
						pdo_update('xcommunity_users',array('balance' => $users['balance'] + $balance,2),array('uid' => $order['uid']));
					}
					
					// pdo_insert('xcommunity_sjdp_member', $data);
					// pdo_update('xcommunity_sjdp_goods',array('total' => $good['total'] -1),array('id' => $order['gid']));
					pdo_update('xcommunity_order', array('status' => 1,'couponsn' => date('md') . random(5, 1)), array('ordersn' => $params['tid']));
				}elseif ($order['type'] == 'pfree') {
					//更新用户物业费状态
					pdo_update('xcommunity_cost_list', array('status' => '是'), array('id' => $order['pid']));
				}elseif ($order['type'] == 'shopping') {
					$sql = 'SELECT * FROM ' . tablename('xcommunity_order_goods') . ' WHERE `orderid` = :orderid';
					$goods = pdo_fetch($sql, array(':orderid' => $order['id']));
					$sql = 'SELECT * FROM ' . tablename('xcommunity_goods') . ' WHERE `id` = :id';
					$goodsInfo = pdo_fetch($sql, array(':id' => $goods['goodsid']));
					// 更改库存
					if ($goodsInfo['totalcnf'] == '1' && !empty($goodsInfo['total'])) {
						pdo_update('xcommunity_goods', array('total' => $goodsInfo['total'] - 1), array('id' => $goodsId));
					}
					//微信提醒
					$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_member')."WHERE openid=:openid",array(':openid' => $order['from_user']));
					$notice = pdo_fetchall("SELECT * FROM".tablename('xcommunity_wechat_notice')."WHERE uniacid=:uniacid",array('uniacid' => $_W['uniacid']));
					foreach ($notice as $key => $value) {
						$regions = unserialize($value['regionid']);
						if (@in_array($member['regionid'], $regions)) {
							if ($value['shopping_status'] == 2) {
								$openid = $value['fansopenid'];
								$url = '';
								$tpl = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_tplid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
								$template_id = $tpl['good_tplid'];
								$createtime = date('Y-m-d H:i:s', $_W['timestamp']);
								$content = array(
										'first' => array(
												'value' => '超市新订单通知',
											),
										'keyword1' => array(
												'value' => $goodsInfo['title'].',数量:'.$goods['total'],
											),
										'keyword2' => array(
												'value' => $order['goodsprice'].'元',
											),
										'keyword3'	=> array(
												'value' => $member['realname'],
											),
										'keyword4'    => array(
												'value' => $member['mobile'],
											),
										'keyword5'    => array(
												'value' => $ordersn,
											),
										'remark'    => array(
											'value' => $order['remark'],
										),	
									);
								$this->sendtpl($openid,$url,$template_id,$content);
							}

						}
						

					}
					//判断打印机
					$prints = pdo_fetchall("SELECT * FROM".tablename('xcommunity_print')."WHERE uniacid = :uniacid",array(':uniacid' => $_W['uniacid']));
					$row = array();
					foreach ($prints as $key => $value) {
						$regions = unserialize($value['regionid']);
						if (@in_array($member['regionid'], $regions)) {
							$row = $prints;
						}
					}
					foreach ($row as $key => $value) {
							if ($value['print_status']) {
								if (empty($value['print_type']) || $value['print_type'] == '1') {
									$key = $value['api_key'];
									$createtime = date('Y-m-d H:i:s', $_W['timestamp']);
									$msgNo = time()+1;
									$deviceNo = $value['deviceNo'];
									if ($value['member_code']) {
										$freeMessage = array(
										'memberCode'=>$value['member_code'], 
										'msgDetail'=>
												'
												    超市新订单通知

												内容：'.$goodsInfo['title'].',数量:'.$goods['total'].'
												-------------------------

												地址：'.$member['address'].'
												业主：'.$member['realname'].'
												电话：'.$member['mobile'].'
												时间：'.$createtime.'
												',
										'deviceNo'=>$deviceNo, 
										'msgNo'=>$msgNo,
									);
									echo $this->sendFreeMessage($freeMessage,$key);



									}else{
										//普通打印机
										$content = "^N1^F1\n";
										$content .= "^B2 超市新订单通知\n";
										$content .="内容：".$goodsInfo['title'].',数量:'.$goods['total']."\n";
										$content .="地址：".$member['address']."\n";
										$content .="业主：".$member['realname']."\n";
										$content .="电话：".$member['mobile']."\n";
										$content .="时间：".$createtime;
										$result = $this->sendSelfFormatOrderInfo($deviceNo, $key, 1,$content);




									}


								}


							}


					}
					

					
				}elseif ($order['type'] == 'activity') {
					$r = pdo_fetch("SELECT aid,num FROM".tablename('xcommunity_res')."WHERE id=:id",array(':id' => $order['aid']));
					pdo_query("UPDATE ".tablename('xcommunity_res')."SET status = 1 WHERE id=:id",array(':id' => $order['aid']));
					pdo_query("UPDATE ".tablename('xcommunity_activity')." SET resnumber=resnumber+'{$r['num']}' WHERE id=:id",array(':id' => $r['aid']));
					// message('报名成功',$this->createMobileUrl('activity',array('op' => 'list')),'success');
					header("location: " . $this->createMobileUrl('activity',array('op' => 'list')));
				}
				//更新订单状态
				pdo_update('xcommunity_order', $data, array('ordersn' => $params['tid']));
				
				message('支付成功！', $this->createMobileUrl('home'), 'success');
				//header("location: " . $this->createMobileUrl('home'));
			}
		}

	}
	private function changeWechatSend($id, $status, $msg = '') {
		global $_W;
		$paylog = pdo_fetch("SELECT plid, openid, tag FROM " . tablename('core_paylog') . " WHERE tid = '{$id}' AND status = 1 AND type = 'wechat'");
		if (!empty($paylog['openid'])) {
			$paylog['tag'] = iunserializer($paylog['tag']);
			$acid = $paylog['tag']['acid'];
			$account = account_fetch($acid);
			$payment = uni_setting($account['uniacid'], 'payment');
			if ($payment['payment']['wechat']['version'] == '2') {
				return true;
			}
			$send = array(
					'appid' => $account['key'],
					'openid' => $paylog['openid'],
					'transid' => $paylog['tag']['transaction_id'],
					'out_trade_no' => $paylog['plid'],
					'deliver_timestamp' => TIMESTAMP,
					'deliver_status' => $status,
					'deliver_msg' => $msg,
			);
			$sign = $send;
			$sign['appkey'] = $payment['payment']['wechat']['signkey'];
			ksort($sign);
			$string = '';
			foreach ($sign as $key => $v) {
				$key = strtolower($key);
				$string .= "{$key}={$v}&";
			}
			$send['app_signature'] = sha1(rtrim($string, '&'));
			$send['sign_method'] = 'sha1';
			$account = WeAccount::create($acid);
			$response = $account->changeOrderStatus($send);
			if (is_error($response)) {
				message($response['message']);
			}
		}
	}
	//模板消息通知提醒
	public function sendtpl($openid,$url,$template_id,$content){
		global $_GPC,$_W;
		load()->classs('weixin.account');
		load()->func('communication');
		$obj = new WeiXinAccount();
		$access_token = $obj->fetch_available_token();
		$data = array(
				'touser' => $openid,
				'template_id' => $template_id,
				'url' => $url,
				'topcolor' => "#FF0000",
				'data' => $content,
			);
		$json = json_encode($data);
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
		$ret = ihttp_post($url,$json);
		return $ret;
    }

    /**
     * 计算某个经纬度的周围某段距离的正方形的四个点
     *
     * @param lng float 经度
     * @param lat float 纬度
     * @param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
     * @return array 正方形的四个点的经纬度坐标
     */
    public function squarePoint($lng, $lat, $distance = 0.5) {

        $dlng = 2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);

        $dlat = $distance / EARTH_RADIUS; //EARTH_RADIUS地球半径
        $dlat = rad2deg($dlat);

        return array(
            'left-top' => array('lat' => $lat + $dlat, 'lng' => $lng - $dlng),
            'right-top' => array('lat' => $lat + $dlat, 'lng' => $lng + $dlng),
            'left-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng - $dlng),
            'right-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng + $dlng)
        );
    }
    //测算距离
    function getDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2) {
        $radLat1 = $lat1 * M_PI / 180;
        $radLat2 = $lat2 * M_PI / 180;
        $a = $lat1 * M_PI / 180 - $lat2 * M_PI / 180;
        $b = $lng1 * M_PI / 180 - $lng2 * M_PI / 180;

        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * EARTH_RADIUS;
        $s = round($s * 1000);
        if ($len_type > 1) {
            $s /= 1000;
        }
        return round($s, $decimal);
    }
    //飞印打印机
	function sendFreeMessage($msg,$key) {
		$API_KEY      = $key;
		$msg['reqTime'] = number_format(1000*time(), 0, '', '');
		$content = $msg['memberCode'].$msg['msgDetail'].$msg['deviceNo'].$msg['msgNo'].$msg['reqTime'].$API_KEY;
		$msg['securityCode'] = md5($content);
		$msg['mode']=2;

		return $this->sendMessage($msg);
	}
	public function sendMessage($msgInfo){
		load()->func('communication');
		$content = ihttp_post('http://my.feyin.net/api/sendMsg',$msgInfo);
	} 
	//普通打印机
	function sendSelfFormatOrderInfo($device_no,$key,$times,$orderInfo){ // $times打印次数
		$selfMessage = array(
			'deviceNo'=>$device_no,  
			'printContent'=>$orderInfo,
			'key'=>$key,
			'times'=>$times
		);				
		$url = "http://open.printcenter.cn:8080/addOrder";
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencoded ",
				'method'  => 'POST',
				'content' => http_build_query($selfMessage),
			),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $result;
	}
	//支付调用
	public function syspay($type){
		global $_W;
		$setdata = pdo_fetch("select * from " . tablename('xcommunity_pay') . ' where uniacid=:uniacid AND type=:type limit 1', array(
        	':uniacid' => $_W['uniacid'],':type' => $type
    	));
    	return $setdata;
	}
	//支付宝支付

	function alipay_build($params, $alipay = array()) {
		global $_W;
		$tid = $params['uniontid'];
		$set = array();
		$set['service'] = 'alipay.wap.create.direct.pay.by.user';
		$set['partner'] = $alipay['partner'];
		$set['_input_charset'] = 'utf-8';
		$set['sign_type'] = 'MD5';
		$set['notify_url'] = $_W['siteroot'] . 'addons/xfeng_community/payment/alipay/notify.php';
		$set['return_url'] = $_W['siteroot'] . 'addons/xfeng_community/payment/alipay/return.php?pid='.$params['pid'].'&cid='.$params['cid'].'&ordersn='.$params['tid'];
		$set['out_trade_no'] = $tid;
		$set['subject'] = $params['title'];
		$set['total_fee'] = $params['fee'];
		$set['seller_id'] = $alipay['account'];
		$set['payment_type'] = 1;
		$set['body'] = $_W['uniacid'];
		$prepares = array();
		foreach($set as $key => $value) {
			if($key != 'sign' && $key != 'sign_type') {
				$prepares[] = "{$key}={$value}";
			}
		}
		// print_r($set);exit();
		sort($prepares);
		$string = implode('&', $prepares);
		$string .= $alipay['secret'];
		$set['sign'] = md5($string);
		load()->func('communication');
		$response = ihttp_request(ALIPAY_GATEWAY . '?' . http_build_query($set, '', '&'), array(), array('CURLOPT_FOLLOWLOCATION' => 0));
			
		return array('url' => $response['headers']['Location']);

	}
}





