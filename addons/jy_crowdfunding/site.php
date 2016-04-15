<?php
/**
 * 众筹项目模块微站定义
 *
 * @author Michael Hu
 */
defined('IN_IA') or exit('Access Denied');
define('MB_ROOT', IA_ROOT . '/addons/crowdfunding');

class Jy_crowdfundingModuleSite extends WeModuleSite {


	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		$this->checkAuth();

		$from_user=$_W['openid'];

		$weid=$_W['uniacid'];
		$uid = $_W['member']['uid'];

		$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
		if(empty($member_temp['nickname']) || $member_temp['uid']==0)
		{
			unset($uid);
		}
		else
		{
			$uid=$member_temp['uid'];
			$_W['member']['uid']=$uid;
			unset($member_temp);
		}

		if(empty($uid))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'index','member_id'=>$member_id))."';					
			</script>";
		}

		$item=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);

		$huodong=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_member')." WHERE weid=".$weid." AND uid=".$uid." ORDER BY createtime desc LIMIT 1");
		if((time()-$huodong['createtime'])<$item['hour']*3600)
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('geren')."';					
			</script>";
		}

		$user=pdo_fetch('SELECT avatar FROM '.tablename('mc_members')." WHERE uid=".$uid);

		include $this->template('index');
	}

	public function doMobileHuodong() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		$this->checkAuth();

		$from_user=$_W['openid'];

		$weid=$_W['uniacid'];
		$uid = $_W['member']['uid'];

		$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
		

		if(empty($member_temp['nickname']) || $member_temp['uid']==0)
		{
			unset($uid);
		}
		else
		{
			$api2 = pdo_fetch("SELECT url FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);

			$uid=$member_temp['uid'];
			$_W['member']['uid']=$uid;	
				
			
			if(!empty($api2['url']))
			{
				if($member_temp['follow']==0)
				{
					unset($uid);
				}
			}
			unset($member_temp);

		}

		if(empty($uid))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'huodong','member_id'=>$member_id))."';					
			</script>";
		}

		$from_user=$_W['openid'];
		

		$item=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);
		$huodong=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_member')." WHERE weid=".$weid." AND uid=".$uid." ORDER BY createtime desc LIMIT 1");
		

		$user=pdo_fetch('SELECT avatar FROM '.tablename('mc_members')." WHERE uid=".$uid);

		$op = $_GPC['op']?$_GPC['op']:'display';
		if($op=='add')
		{
			if((time()-$huodong['createtime'])<$item['hour']*3600)
			{
				echo 2;
				exit;
			}
			else
			{
				$data=array(
					'weid'=>$_W['uniacid'],
					'from_user'=>$from_user,
					'destination'=>$_GPC['destination'],
					'budget'=>$_GPC['budget'],
					'uid'=>$uid,
					'createtime'=>TIMESTAMP	
				);
				pdo_insert('jy_crowdfunding_member',$data);
				echo 1;
				exit;
			}
		}

		if((time()-$huodong['createtime'])<$item['hour']*3600)
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('geren')."';					
			</script>";
		}

		if($op=='display'){
			include $this->template('huodong');
			exit;
		}
	}

	public function doMobileRule() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;

		$weid=$_W['uniacid'];
		
		$item=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);
		include $this->template('rule');
	}

	public function doMobileSharelist() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		$this->checkAuth();

		$from_user=$_W['openid'];

		$weid=$_W['uniacid'];
		$member_id=$_GPC['member_id'];
		
		$item=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);

		//$huodong=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_member')." WHERE id=".$member_id);
		$log=pdo_fetchall("SELECT a.createtime,a.log,a.completed,a.budget,b.avatar,b.nickname FROM ".tablename('jy_crowdfunding_log')." as a left join ".tablename('mc_members')." as b on a.uid=b.uid  WHERE a.weid=".$weid." AND a.status=1 AND a.member_id=".$member_id." ORDER BY a.createtime desc");
		$num=count($log);
		include $this->template('sharelist');
	}

	public function doMobileGeren() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		$this->checkAuth();

		$weid=$_W['uniacid'];
		$uid = $_W['member']['uid'];

		$from_user=$_W['openid'];
		$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
		if(empty($member_temp['nickname']) || $member_temp['uid']==0)
		{
			unset($uid);
		}
		else
		{
			$uid=$member_temp['uid'];
			$_W['member']['uid']=$uid;
			unset($member_temp);
		}
		

		if(empty($uid))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'geren','member_id'=>$member_id))."';					
			</script>";
		}

		$user=pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid=".$uid);
		$item=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);
		$huodong=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_member')." WHERE weid=".$weid." AND uid=".$uid." ORDER BY createtime desc LIMIT 1");

		if(empty($huodong))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('index')."';					
			</script>";
		}

		$log=pdo_fetchall("SELECT count(id) as num,sum(budget) as sum FROM ".tablename("jy_crowdfunding_log")." WHERE member_id=".$huodong['id']." AND status!=0");
		if(empty($log[0]['sum']))
		{
			$num=0;
			$sum=0;
			$left=$huodong['budget'];
		}
		else
		{
			$num=$log[0]['num'];
			$sum=$log[0]['sum'];
		    $sum=sprintf("%.2f",$sum);

			$left=$huodong['budget']-$sum;
			if($left<=0)
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('success',array('member_id'=>$huodong['id']))."';					
				</script>";
			}
		}
		if((time()-$huodong['createtime'])<$item['hour']*3600)
		{
			$title="打赏一个盒饭钱，赐我去【".$huodong['destination']."】吧！";
			$desc="我的亲朋好友们，打赏一个盒饭钱，即可赐我去【".$huodong['destination']."】！";


		}
		else
		{
			if($left>0)
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('fail',array('member_id'=>$huodong['id']))."';					
				</script>";
			}
			else
			{

				echo "<script>
					window.location.href = '".$this->createMobileUrl('success',array('member_id'=>$huodong['id']))."';					
				</script>";
			}
			
		}
		
		include $this->template('geren');
	}

	public function doMobileFriend() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		
		$this->checkAuth();

		$from_user=$_W['openid'];

		$weid=$_W['uniacid'];

		$uid = $_W['member']['uid'];
		$member_id=$_GPC['member_id'];

		$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
		if(empty($member_temp['nickname']) || $member_temp['uid']==0)
		{
			unset($uid);
		}
		else
		{
			$uid=$member_temp['uid'];
			$_W['member']['uid']=$uid;
			unset($member_temp);
		}

	

		if(empty($uid))
		{
    		echo "<script>
				window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'friend','member_id'=>$member_id))."';					
			</script>";
		}
		
		$item=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);
		$huodong=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_member')." WHERE weid=".$weid." AND id=".$member_id);
		if($huodong['uid']==$uid)
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('geren')."';					
			</script>";
		}

		$done=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_log')." WHERE member_id=".$member_id." AND uid=".$uid." AND weid=".$weid." AND status!=0");
		if(!empty($done))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('pay_done',array('member_id'=>$member_id))."';					
			</script>";
		}

		$user=pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid=".$huodong['uid']);
		if((time()-$huodong['createtime'])<$item['hour']*3600)
		{
			$title="打赏一个盒饭钱，赐我的好友".$user['nickname']."去【".$huodong['destination']."】吧！";
			$desc="我的亲朋好友们，打赏一个盒饭钱，即可赐我的好友".$user['nickname']."去【".$huodong['destination']."】！";
		}
		else
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('f_end',array('member_id'=>$member_id))."';					
			</script>";
		}

		$log=pdo_fetchall("SELECT count(id) as num,sum(budget) as sum FROM ".tablename("jy_crowdfunding_log")." WHERE member_id=".$huodong['id']." AND status!=0");
		if(empty($log[0]['sum']))
		{
			$num=0;
			$sum=0;
			$left=$huodong['budget'];
		}
		else
		{
			$num=$log[0]['num'];
			$sum=$log[0]['sum'];
		    $sum=sprintf("%.2f",$sum);

			$left=$huodong['budget']-$sum;
			if($left<=0)
			{
				//$left=0;
				echo "<script>
					window.location.href = '".$this->createMobileUrl('f_end',array('member_id'=>$huodong['id']))."';					
				</script>";
			}
		}
		include $this->template('friend');
	}

	public function doMobilePay() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		$this->checkAuth();

		$from_user=$_W['openid'];

		$weid=$_W['uniacid'];
		$uid = $_W['member']['uid'];

		$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
		if(empty($member_temp['nickname']) || $member_temp['uid']==0)
		{
			unset($uid);
		}
		else
		{
			$uid=$member_temp['uid'];
			$_W['member']['uid']=$uid;
			unset($member_temp);
		}

		if(empty($uid))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'pay','member_id'=>$member_id))."';					
			</script>";
		}

		$member_id=$_GPC['member_id'];

		$item=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);		
		$huodong=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_member')." WHERE weid=".$weid." AND id=".$member_id);
		if($huodong['uid']==$uid)
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('geren')."';					
			</script>";
		}

		$user=pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid=".$huodong['uid']);

		if((time()-$huodong['createtime'])<$item['hour']*3600)
		{
			$title="打赏一个盒饭钱，赐我的好友".$user['nickname']."去【".$huodong['destination']."】吧！";
			$desc="我的亲朋好友们，打赏一个盒饭钱，即可赐我的好友".$user['nickname']."去【".$huodong['destination']."】！";
		}
		else
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('f_end',array('member_id'=>$huodong['id']))."';				
			</script>";
		}

		$log=pdo_fetchall("SELECT count(id) as num,sum(budget) as sum FROM ".tablename("jy_crowdfunding_log")." WHERE member_id=".$huodong['id']." AND status!=0");
		if(empty($log[0]['sum']))
		{
			$num=0;
			$sum=0;
			$left=$huodong['budget'];
		}
		else
		{
			$num=$log[0]['num'];
			$sum=$log[0]['sum'];
		    $sum=sprintf("%.2f",$sum);

			$left=$huodong['budget']-$sum;
			if($left<=0)
			{
				//$left=0;
				echo "<script>
					window.location.href = '".$this->createMobileUrl('f_end',array('member_id'=>$huodong['id']))."';					
				</script>";
			}
		}

		include $this->template('pay');
	}


	public function doMobileMoney() {
		global $_W, $_GPC;
		//message("ds");
		$this->checkAuth();

		$from_user=$_W['openid'];

		$weid=$_W['uniacid'];
		$uid = $_W['member']['uid'];
		

		$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
		if(empty($member_temp['nickname']) || $member_temp['uid']==0)
		{
			unset($uid);
		}
		else
		{
			$uid=$member_temp['uid'];
			$_W['member']['uid']=$uid;
			unset($member_temp);
		}

		if(empty($uid))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'money','member_id'=>$member_id))."';					
			</script>";
		}
		//message($uid);

		$member_id = intval($_GPC['member_id']);
		$from_user=$_W['openid'];

		$data=array(
				'weid'=>$weid,
				'from_user'=>$from_user,
				'member_id'=>$member_id,
				'uid'=>$uid,
				'budget'=>$_GPC['money'],
				'createtime'=>TIMESTAMP,
			);
		if(!empty($from_user))
		{
			pdo_insert('jy_crowdfunding_log',$data);
			$tid=pdo_insertid();

			$params['tid'] = $tid;
			$params['user'] = $from_user;
			$params['fee'] = $_GPC['money'];
			$params['title'] = "打赏给你的小费";
			$params['ordersn'] = random(8);
			$params['virtual'] =  true;

			if(!$this->inMobile) {
				message('支付功能只能在手机上使用');
			}
			if (empty($_W['member']['uid'])) {
				checkauth();
			}

			$params['module'] = $this->module['name'];
			$pars = array();
			$pars[':uniacid'] = $_W['uniacid'];
			$pars[':module'] = $params['module'];
			$pars[':tid'] = $params['tid'];

			$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
			$log = pdo_fetch($sql, $pars);
			if(!empty($log) && $log['status'] == '1') {
				message('这个订单已经支付成功, 不需要重复支付.');
			}
			$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
			if(!is_array($setting['payment'])) {
				message('没有有效的支付方式, 请联系网站管理员.');
			}

			$params=base64_encode(json_encode($params));
			echo "<script>
					window.location.href = '".url('mc/cash/wechat')."&params=".$params."';					
				</script>";
		}		

	}

	public function doMobilePay_done() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		
		$this->checkAuth();

		$from_user=$_W['openid'];

		$weid=$_W['uniacid'];
		$uid = $_W['member']['uid'];

		$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
		if(empty($member_temp['nickname']) || $member_temp['uid']==0)
		{
			unset($uid);
		}
		else
		{
			$uid=$member_temp['uid'];
			$_W['member']['uid']=$uid;
			unset($member_temp);
		}

		if(empty($uid))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'pay_done','member_id'=>$member_id))."';					
			</script>";
		}

		$member_id=$_GPC['member_id'];
		
		$item=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);
		$huodong=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_member')." WHERE weid=".$weid." AND id=".$member_id);

		if($huodong['uid']==$uid)
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('geren')."';					
			</script>";
		}

		$user=pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid=".$huodong['uid']);
		if((time()-$huodong['createtime'])<$item['hour']*3600)
		{
			$title="打赏一个盒饭钱，赐我的好友".$user['nickname']."去【".$huodong['destination']."】吧！";
			$desc="我的亲朋好友们，打赏一个盒饭钱，即可赐我的好友".$user['nickname']."去【".$huodong['destination']."】！";
		}
		else
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('f_end',array('member_id'=>$huodong['id']))."';				
			</script>";
		}

		$done_arr=pdo_fetchall("SELECT sum(budget) as sum FROM ".tablename("jy_crowdfunding_log")." WHERE member_id=".$huodong['id']." AND status!=0 AND uid=$uid");
		$done=$done_arr[0]['sum'];
		$done=sprintf("%.2f",$done);
		unset($done_arr);

		$log=pdo_fetchall("SELECT count(id) as num,sum(budget) as sum FROM ".tablename("jy_crowdfunding_log")." WHERE member_id=".$huodong['id']." AND status!=0");
		if(empty($log[0]['sum']))
		{
			$num=0;
			$sum=0;
			$left=$huodong['budget'];
		}
		else
		{
			$num=$log[0]['num'];
			$sum=$log[0]['sum'];
		    $sum=sprintf("%.2f",$sum);

			$left=$huodong['budget']-$sum;
			if($left<=0)
			{
				$left=0;
				echo "<script>
					window.location.href = '".$this->createMobileUrl('f_end',array('member_id'=>$huodong['id']))."';					
				</script>";
			}
		}

		include $this->template('pay_done');
	}

	public function doMobileF_end() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		
		$this->checkAuth();

		$from_user=$_W['openid'];

		$weid=$_W['uniacid'];
		$uid = $_W['member']['uid'];

		$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
		if(empty($member_temp['nickname']) || $member_temp['uid']==0)
		{
			unset($uid);
		}
		else
		{
			$uid=$member_temp['uid'];
			$_W['member']['uid']=$uid;
			unset($member_temp);
		}

		if(empty($uid))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'f_end','member_id'=>$member_id))."';					
			</script>";
		}

		$member_id=$_GPC['member_id'];
		
		$item=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);
		$huodong=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_member')." WHERE weid=".$weid." AND id=".$member_id);
		if($huodong['uid']==$uid)
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('geren')."';					
			</script>";
		}

		$user=pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid=".$huodong['uid']);

		$log=pdo_fetchall("SELECT count(id) as num,sum(budget) as sum FROM ".tablename("jy_crowdfunding_log")." WHERE member_id=".$huodong['id']." AND status!=0");
		if(empty($log[0]['sum']))
		{
			$num=0;
			$sum=0;
			$left=$huodong['budget'];
		}
		else
		{
			$num=$log[0]['num'];
			$sum=$log[0]['sum'];
		    $sum=sprintf("%.2f",$sum);

			$left=$huodong['budget']-$sum;
			if($left<0)
			{
				$left=0;
			}
		}

		include $this->template('f_end');
	}

	public function doMobileFail() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		
		$this->checkAuth();

		$from_user=$_W['openid'];

		$weid=$_W['uniacid'];
		$uid = $_W['member']['uid'];

		$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
		if(empty($member_temp['nickname']) || $member_temp['uid']==0)
		{
			unset($uid);
		}
		else
		{
			$uid=$member_temp['uid'];
			$_W['member']['uid']=$uid;
			unset($member_temp);
		}

		if(empty($uid))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'fail','member_id'=>$member_id))."';					
			</script>";
		}

		$member_id=$_GPC['member_id'];
		
		$item=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);
		$huodong=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_member')." WHERE weid=".$weid." AND id=".$member_id);
		if(empty($huodong))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('index')."';					
			</script>";
		}
		if($huodong['uid']!=$uid)
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('huodong')."';					
			</script>";
		}
		if((time()-$huodong['createtime'])<$item['hour']*3600)
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('geren')."';					
			</script>";
		}

		$user=pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid=".$uid);

		$log=pdo_fetchall("SELECT count(id) as num,sum(budget) as sum FROM ".tablename("jy_crowdfunding_log")." WHERE member_id=".$huodong['id']." AND status!=0");
		if(empty($log[0]['sum']))
		{
			$num=0;
			$sum=0;
			$left=$huodong['budget'];
		}
		else
		{
			$num=$log[0]['num'];
			$sum=$log[0]['sum'];
		    $sum=sprintf("%.2f",$sum);

			$left=$huodong['budget']-$sum;
			if($left<0)
			{
				$left=0;
			}
		}

		if($left<=0)
		{
			echo "<script>
					window.location.href = '".$this->createMobileUrl('success',array('member_id'=>$huodong['id']))."';					
				</script>";
		}

		include $this->template('fail');
	}

	public function doMobileSuccess() {
		//这个操作被定义用来呈现 功能封面
		global $_W,$_GPC;

		$this->checkAuth();

		$from_user=$_W['openid'];

		$weid=$_W['uniacid'];
		$uid = $_W['member']['uid'];

		$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
		if(empty($member_temp['nickname']) || $member_temp['uid']==0)
		{
			unset($uid);
		}
		else
		{
			$uid=$member_temp['uid'];
			$_W['member']['uid']=$uid;
			unset($member_temp);
		}

		if(empty($uid))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'success','member_id'=>$member_id))."';					
			</script>";
		}

		$member_id=$_GPC['member_id'];
		
		$item=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);
		$huodong=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_member')." WHERE weid=".$weid." AND id=".$member_id);
		if(empty($huodong))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('index')."';					
			</script>";
		}
		if($huodong['uid']!=$uid)
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('huodong')."';					
			</script>";
		}
		

		$user=pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid=".$uid);

		$log=pdo_fetchall("SELECT count(id) as num,sum(budget) as sum FROM ".tablename("jy_crowdfunding_log")." WHERE member_id=".$huodong['id']." AND status!=0");
		if(empty($log[0]['sum']))
		{
			$num=0;
			$sum=0;
			$left=$huodong['budget'];
		}
		else
		{
			$num=$log[0]['num'];
			$sum=$log[0]['sum'];
		    $sum=sprintf("%.2f",$sum);

			$left=$huodong['budget']-$sum;
			if($left<=0)
			{
				$left=0;
			}
			else
			{
				if((time()-$huodong['createtime'])<$item['hour']*3600)
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('geren')."';					
					</script>";
				}
				else
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('fail')."';					
					</script>";
				}
			}
		}

		include $this->template('success');
	}

	public function payResult($params) {
		global $_W,$_GPC;

		$api = $this->module['config']['api'];
		$uniacid=$_W['uniacid'];

		$member=pdo_fetch("SELECT member_id FROM".tablename('jy_crowdfunding_log')." WHERE id=".$params['tid']);
		$member_id=$member['member_id'];

		pdo_update('jy_crowdfunding_log', array('status'=>1), array('id' => $params['tid']));
  

        

		if ($params['from'] == 'return') {

			// echo "<script>
			// 		window.location.href = '".$this->createMobileUrl('pay_done',array('member_id'=>$member_id))."';					
			// 	</script>";

			// echo "<script>
			// 		window.location.href = '".$this->createMobileUrl('hongbao',array('member_id'=>$member_id,'tid'=>$params['tid']))."';					
			// 	</script>";

			//message('支付成功！', $this->createMobileUrl('hongbao',array('member_id'=>$member_id,'tid'=>$params['tid'])), 'success');
			message('支付成功！', $this->createMobileUrl('pay_done',array('member_id'=>$member_id)), 'success');
		}
	}

	public function doWebSend() {

		global $_W, $_GPC;
		$weid=$_W['uniacid'];
		$uniacid=$_W['uniacid'];
		$api = $this->module['config']['api'];
		$member_id=$_GPC['member_id'];
		$params['tid']=rand(0,999999999);
		$total=0;

		$temp_log=pdo_fetchall("SELECT * FROM ".tablename('jy_crowdfunding_log')." WHERE member_id=".$member_id." AND status=1 AND completetime=0 AND weid=".$weid);
		foreach ($temp_log as  $value) {
			# code...
			$total+=$value['budget'];
			if($total>200)
			{
				$total-=$value['budget'];
				break;
			}
			else
			{
				$log[]=$value['id'];
			}

		}

		$user_id=pdo_fetch("SELECT uid,from_user FROM ".tablename('jy_crowdfunding_member')." WHERE id=".$member_id);
		$user=pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid=".$user_id['uid']);

		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$pars = array();
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] = $api['mchid'] . date('Ymd') . sprintf('%010d', $params['tid']);
        $pars['mch_id'] = $api['mchid'];
        $pars['wxappid'] = $api['appid'];
        $pars['nick_name'] = $user['nickname'];
        $pars['send_name'] = "打赏给你的小费";
        $pars['re_openid'] = $user_id['from_user'];  
       	$pars['total_amount'] = $total*100;
        $pars['min_value'] = $pars['total_amount'];
        $pars['max_value'] = $pars['total_amount'];
        $pars['total_num'] = 1;
        $pars['wishing'] = "梦想，从这里开始";
        $pars['client_ip'] = $api['ip'];
        $pars['act_name'] = "打赏活动";
        $pars['remark'] = "赶紧去叫上朋友一起帮打赏你吧！";
        $pars['logo_imgurl'] = tomedia('https://wx.gtimg.com/mch/img/ico-logo.png');
        $pars['share_content'] = "打赏一个盒饭钱，赐给Ta吧！";
        $pars['share_imgurl'] = tomedia('https://wx.gtimg.com/mch/img/ico-logo.png');
        $pars['share_url'] = $_W['siteroot'] . 'app/' . substr($this->createMobileUrl('friend', array('member_id'=>$member_id)), 2);

        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$api['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO'] = MB_ROOT . '/cert/rootca.pem.' . $uniacid;
        $extras['CURLOPT_SSLCERT'] = MB_ROOT . '/cert/apiclient_cert.pem.' . $uniacid;
        $extras['CURLOPT_SSLKEY'] = MB_ROOT . '/cert/apiclient_key.pem.' . $uniacid;


        load()->func('communication');
        $procResult = null;
        $resp = ihttp_request($url, $xml, $extras);

        

        if(is_error($resp)) {
            $procResult = $resp;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new \DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new \DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult = true;
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $err_code = $xpath->evaluate('string(//xml/err_code)');
                    $procResult = error(-2, $error);
                }
            } else {
                $procResult = error(-1, 'error response');
            }
        }

        if(is_error($procResult)) {
        	foreach ($log as $value) {
        		$filters = array();
	            $filters['weid'] = $uniacid;
	            $filters['id'] = $value;
	            $rec = array();
	            $rec['log'] = $procResult['message'].$err_code;
	            $rec['completed'] = 'error';
	            pdo_update('jy_crowdfunding_log', $rec, $filters);
	            
        	}
        	message("发送失败!原因是:".$procResult['message'],$this->CreateWebUrl('huodong'),'error');
            
        } 
        else {
        	foreach ($log as $value) {
	        	$filters = array();
		        $filters['weid'] = $uniacid;
		        $filters['id'] = $value;
		        
		        $rec = array();
		        $rec['completed'] = 'complete';
		        $rec['completetime'] = TIMESTAMP;
		        $rec['log']='';
		        pdo_update('jy_crowdfunding_log', $rec, $filters);
		        
		    }
		    message("发送成功!".$user['nickname']."已经收到红包！",$this->CreateWebUrl('huodong'),'success');
        }  
	}

	

	public function doWebMember() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W, $_GPC;
		$weid=$_W['uniacid'];

		$member=pdo_fetchall("SELECT a.*,b.nickname,b.avatar FROM ".tablename('jy_crowdfunding_member')." as a left join ".tablename('mc_members')." as b on a.uid=b.uid WHERE a.weid=".$weid." ORDER BY a.createtime desc ");

		$i=0;
		$total_money=0;
		foreach ($member as $key => $value) {
			# code...
			$sum_arr=pdo_fetchall("SELECT SUM(budget) as sum,count(id) as num FROM ".tablename('jy_crowdfunding_log')." WHERE member_id=".$value['id']." AND status=1");
			$sum=$sum_arr[0]['sum'];
			if(empty($sum))
			{
				$member[$i]['sum']=0;
			}
			else
			{
				$member[$i]['sum']=sprintf("%.2f",$sum);
			}
			$member[$i]['num']=$sum_arr[0]['num'];
			$total_money+=$member[$i]['sum'];
			$i++;
		}

		$total=count($member);

		include $this->template('member');
	}

	public function doWebMingxi() {
		global $_W, $_GPC;
		$weid=$_W['uniacid'];
		$member_id=$_GPC['member_id'];
		$user_member=pdo_fetch("SELECT b.nickname FROM ".tablename('jy_crowdfunding_member')." as a left join ".tablename('mc_members')." as b on a.uid=b.uid WHERE a.id=".$member_id);
		
		$member=pdo_fetchall("SELECT a.createtime,a.budget,b.nickname,b.avatar FROM ".tablename('jy_crowdfunding_log')." as a left join ".tablename('mc_members')." as b on a.uid=b.uid WHERE a.status=1 AND member_id=".$member_id);
		$total=count($member);
		$sum=0;
		foreach ($member as $key => $value) {
			# code...
			$sum+=$value['budget'];
		}
		include $this->template('mingxi');
	}

	public function doWebHuodong() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W, $_GPC;
		$weid=$_W['uniacid'];

		//$member=pdo_fetchall("SELECT a.budget,a.id,a.createtime,b.nickname as pay_nickname,b.avatar as pay_avatar,c.from_user,d.nickname,d.avatar FROM ".tablename('jy_crowdfunding_log')." as a left join ".tablename('mc_members')." as b on a.uid=b.uid left join ".tablename('jy_crowdfunding_member')." as c on a.member_id=c.id left join ".tablename('mc_members')." as d on c.uid=d.uid WHERE a.weid=$weid AND a.status=1 and a.completetime=0 ORDER BY a.member_id,a.uid");
		$member=array();
		$member_id=pdo_fetchall("SELECT member_id FROM ".tablename('jy_crowdfunding_log')." WHERE  weid=$weid AND status=1 AND completetime=0 GROUP BY member_id");
		foreach ($member_id as $key => $value) {
			$temp_member=pdo_fetchall("SELECT sum(a.budget) as budget,a.member_id,a.id,a.createtime,b.nickname as pay_nickname,b.avatar as pay_avatar,c.from_user,d.nickname,d.avatar FROM ".tablename('jy_crowdfunding_log')." as a left join ".tablename('mc_members')." as b on a.uid=b.uid left join ".tablename('jy_crowdfunding_member')." as c on a.member_id=c.id left join ".tablename('mc_members')." as d on c.uid=d.uid WHERE a.weid=$weid AND a.status=1 and a.completetime=0 AND a.member_id=".$value['member_id']." GROUP BY a.member_id");
			foreach ($temp_member as $item) {
				$member[]=$item;
			}
			
			
		}
		$total=count($member);

		include $this->template('hongbao');
	}

	public function doWebSetting() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W, $_GPC;
		$weid=$_W['uniacid'];
		load()->func('tpl');

		$item=pdo_fetch("SELECT * FROM ".tablename('jy_crowdfunding_setting')." WHERE weid=".$weid);
 
		if(checksubmit()) {
			$data=array(
				'aname'=>$_GPC['aname'],
				'url'=>$_GPC['url'],
				'hour'=>$_GPC['hour'],
				'index'=>$_GPC['index'],
				'index_text'=>$_GPC['index_text'],
				'index_button'=>$_GPC['index_button'],
				'rule'=>$_GPC['rule'],
				'rule_bg'=>$_GPC['rule_bg'],
				'huodong'=>$_GPC['huodong'],
				'huodong_button_top'=>$_GPC['huodong_button_top'],
				'huodong_button_bottom'=>$_GPC['huodong_button_bottom'],
				'share_bg'=>$_GPC['share_bg'],
				'geren'=>$_GPC['geren'],
				'success'=>$_GPC['success'],
				'fail'=>$_GPC['fail'],
				'friend'=>$_GPC['friend'],
				'friend_text'=>$_GPC['friend_text'],
				'friend_ad_text'=>$_GPC['friend_ad_text'],
				'friend_ad_url'=>$_GPC['friend_ad_url'],					
				'pay_done'=>$_GPC['pay_done'],
				'pay'=>$_GPC['pay'],
				'f_end'=>$_GPC['f_end'],
				'sharelist'=>$_GPC['sharelist'],
				'sharelist_color'=>$_GPC['sharelist_color'],
				'updatetime'=>TIMESTAMP,
			);
			if(empty($item))
			{
				$data['weid']=$weid;
				pdo_insert('jy_crowdfunding_setting',$data);
			}
			else
			{
				pdo_update('jy_crowdfunding_setting',$data,array('weid'=>$weid));
			}
			message("设置成功!",$this->CreateWebUrl('setting'),'success');
		}

		include $this->template('setting');
	}

	public function doWebApi() {
		//这个操作被定义用来呈现 接口参数菜单
		global $_W, $_GPC;
        if(checksubmit()) {
            load()->func('file');
            mkdirs(MB_ROOT . '/cert');
            $r = true;
            if(!empty($_GPC['cert'])) {
                $ret = file_put_contents(MB_ROOT . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(MB_ROOT . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['ca'])) {
                $ret = file_put_contents(MB_ROOT . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));
                $r = $r && $ret;
            }
            if(!$r) {
                message('证书保存失败, 请保证 /addons/red_envelope/cert/ 目录可写');
            }
            $input = array_elements(array('appid', 'secret', 'mchid', 'password', 'ip'), $_GPC);
            $input['appid'] = trim($input['appid']);
            $input['secret'] = trim($input['secret']);
            $input['mchid'] = trim($input['mchid']);
            $input['password'] = trim($input['password']);
            $input['ip'] = trim($input['ip']);
            $setting = $this->module['config'];
            $setting['api'] = $input;
            if($this->saveSettings($setting)) {
                message('保存参数成功', 'refresh');
            }
        }
        $config = $this->module['config']['api'];
        if(empty($config['ip'])) {
            $config['ip'] = $_SERVER['SERVER_ADDR'];
        }
        include $this->template('api');
	}

	public function __mobile($f_name){
        global $_W, $_GPC;
        //每个页面都要用的公共信息，今后可以考虑是否要运用到缓存
        include_once 'cert/' . strtolower(substr($f_name, 8)) . '.php';
    }

	
	public function doMobileUserinfo() {
		$this->__mobile(__FUNCTION__);					
	}


}