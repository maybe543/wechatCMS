<?php
/**
 * 捷讯活动平台模块微站定义
 *
 * @author 捷讯设计
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
include('../addons/qw_cjhdpt/myfunction.php');
class qw_cjhdptModuleSite extends WeModuleSite {
	
	public function doMobileAjax() {
		//客户主动签到功能
		global $_GPC, $_W;
		$Str=empty($_GPC['str'])?"":$_GPC['str'];
		$content=encrypt($Str, 'D', "www.yfjs-design.com");
		if(empty($content))die(json_encode(array('success'=>false,'msg'=>"编码错误:0".$Str)));
		
		$strAry=explode("_",$content);
		if(count($strAry)!=2)die(json_encode(array('success'=>false,'msg'=>'编码错误:1')));
		if(!is_numeric($strAry[0]) || !is_numeric($strAry[1]))die(json_encode(array('success'=>false,'msg'=>'编码错误:2')));
		$rid=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE id = '".$strAry[1]."' ");
		if(empty($rid))die(json_encode(array('success'=>false,'msg'=>'活动已删除或者不存在！')));
		$item=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE aid = '".$rid['id']."' and from_user='".$_W['openid']."'");
		if(empty($item['id']))die(json_encode(array('success'=>false,'msg'=>'亲，您没有参加本次活动哦~！')));
		if($item['attend']==1)die(json_encode(array('success'=>false,'msg'=>'不需要重复签到哦~！')));
		pdo_update("qw_cjhdpt_winner",array('attend'=>1,'endtime'=>TIMESTAMP),array('id'=>$item['id']));
		$msg="签到成功!".$item['reloadmsg'];
		if($rid['credit_append']){
			load()->model('mc');
			mc_credit_update($_W['member']['uid'],'credit1',$rid['credit_append'],array($_W['user']['uid'],'签到添加积分'));
			$msg.=",签到奖励积分".$rid['credit_append']."分";
		}
		$cfg = $this->module['config'];
		if($cfg['tempmsg_on'] && $cfg['tempmsg_join_sign']){
			$url=$_W['siteroot']."app/index.php?i={$_W['uniacid']}&c=entry&do=view&m=qw_cjhdpt&id=".$rid['id']."&wxref=mp.weixin.qq.com#wechat_redirect";
			$result=j_tempeleSendMessage($cfg['tempmsg_join_sign'],$rid['id'],$_W['openid'],'','',$url);
			//print_r($result);
		}else{
			$tempmsg="恭喜您，|#姓名#| |#性别#|签到成功！|#签到回调#|";
			if($cfg['msg_attend'])$tempmsg=$cfg['msg_attend'];
			$ruselt=jetsum_sendMessage($_W['openid'],$tempmsg,$rid['rid'],'客户签到');
		}
		die(json_encode(array('success'=>true,'msg'=>$msg,'id'=>$strAry[1])));
	}
	public function doMobileAjaxuser() {
		//管理者签到功能
		global $_GPC, $_W;
		if(!$_W['isajax'] || !$_W['openid'])die();
		$idqrcode=empty($_GPC['idqrcode'])?"":$_GPC['idqrcode'];
		$id=intval($_GPC['id']);
		$openid=encrypt($idqrcode, 'D', "www.yfjs-design.com");
		if(empty($idqrcode) || empty($id))die(json_encode(array('success'=>false,'msg'=>"编码错误:0".$openid)));
		
		$rid=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE id = '".$id."' ");
		$item=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE aid = '".$id."' and from_user='".$openid."'");
		
		if(empty($item['id']))die(json_encode(array('success'=>false,'msg'=>'该客户没有报名本活动！')));
		if($item['attend']==1)die(json_encode(array('success'=>false,'msg'=>'该客户已签到，签到时间为：'.date('Y-m-d G:i:s',$item['endtime']).'！')));
		
		pdo_update("qw_cjhdpt_winner",array('attend'=>1,'endtime'=>TIMESTAMP),array('id'=>$item['id']));
		$msg="您好".$item['realname']."，您已签到成功!\r\n".$item['reloadmsg'];
		if($rid['credit_append']){
			load()->model('mc');
			mc_credit_update($_W['member']['uid'],'credit1',$rid['credit_append'],array($_W['user']['uid'],'签到添加积分'));
			$msg.=",签到奖励积分".$rid['credit_append']."分";
		}
		$cfg = $this->module['config'];
		if($cfg['tempmsg_on'] && $cfg['tempmsg_join_sign']){
			$url=$_W['siteroot']."app/index.php?i={$_W['uniacid']}&c=entry&do=view&m=qw_cjhdpt&id=".$rid['id']."&wxref=mp.weixin.qq.com#wechat_redirect";
			$result=j_tempeleSendMessage($cfg['tempmsg_join_sign'],$rid['id'],$openid,'','',$url);
		}
		$tempmsg="恭喜您，|#姓名#| |#性别#|签到成功！|#签到回调#|";
		if($cfg['msg_attend'])$tempmsg=$cfg['msg_attend'];
		$ruselt=jetsum_sendMessage($openid,$tempmsg,$rid['rid'],'组织者签到');
		
		die(json_encode(array('success'=>true,'msg'=>$msg,'id'=>$item['id'])));
	}
	
	public function doMobileAjaxgetinfo() {
		//手机端获取用户资料
		global $_GPC, $_W;
		if(!$_W['isajax'])die(json_encode(array('success'=>false,'msg'=>"错误！")));
		$item=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE id=:id",array(":id"=>intval($_GPC['id'])));
		if(empty($item))die(json_encode(array('success'=>false,'msg'=>"报名人员不存在！")));
		$reply=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE id=:id",array(":id"=>intval($item['aid'])));
		if(empty($reply))die(json_encode(array('success'=>false,'msg'=>"活动已被删除！")));
		$parama=json_decode($reply['parama'],true);
		$p=json_decode($item['parama'],true);
		$para=array();
		foreach($parama as $index=>$val){
			if($val==2){
				$para[$index]="<img src='".$p[$index]."' class='viewimg' width='80' height='80' />";
			}else{
				$para[$index]=$p[$index];
			}
		}
		if($item['createtime'])$item['createtime']=date("y-m-d H:i:s",$item['createtime']);
		if($item['endtime'])$item['endtime']=date("y-m-d H:i:s",$item['endtime']);
		die(json_encode(array('success'=>true,'info'=>$item,'parama'=>$para)));
	}
	public function doMobileAjaxget(){
		global $_GPC, $_W;
		if(!$_W['isajax'])die(json_encode(array('success'=>false,'msg'=>"错误！")));
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($operation=='getlist'){
			$order=isset($_GPC['order'])? intval($_GPC['order']):1;
			$pindex=isset($_GPC['page'])? intval($_GPC['page'])-1:0;
			$world=isset($_GPC['world'])? intval($_GPC['world']):28;
			$psize = isset($_GPC['psize'])? intval($_GPC['psize']):5;
			$orderstr=" status asc ";
			
			if($order==1){
				$orderstr.=" ,joinstarttime asc ";
			}elseif($order==2){
				$orderstr.=" ,joinstarttime desc ";
			}elseif($order==3){
				$orderstr.=" ,starttime asc ";
			}elseif($order==4){
				$orderstr.=" ,starttime desc ";
			}elseif($order==5){
				$orderstr.=" ,(charge*100) asc ";
			}elseif($order==6){
				$orderstr.=" ,(charge*100) desc ";
			}
			
			$list = pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE weid = '{$_W['uniacid']}' order by ".$orderstr." LIMIT ".$pindex*$psize.",".$psize);
			$total = pdo_fetchcolumn("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE weid = '{$_W['uniacid']}' ");
			$tempary=array();
			foreach($list as $row){
				$temp=array();
				$temp['link']=$this->createMobileUrl('view',array('id'=>$row['id']));
				$temp['title']=$row['title'];
				$temp['info']=cutstr($row['description'],$world,true);
				$temp['img']=$_W['attachurl'].$row['picture'];
				$temp['groupname']=$row['usertype']>-1 ? pdo_fetchcolumn("SELECT title FROM ".tablename('mc_groups')." WHERE groupid ='".$row['usertype']."'"):'';
				$temp['time']=date('Y.m.d',$row['joinstarttime'])." - ".date('m.d',$row['joinendtime']);
				$temp['time2']=date('Y.m.d',$row['starttime'])." - ".date('m.d',$row['endtime']);
				$temp['organizer']=$row['organizer'];
				$temp['charge']=$row['charge'];
				$temp['quota']=$row['quota'];
				$temp['joinnum']=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('qw_cjhdpt_winner')." WHERE aid ='".$row['id']."'");
				$temp['ccate']=$row['ccate'];
				$temp['address']=$row['address'];
				$temp['label']=$row['label'];
				$temp['status']=TIMESTAMP>$row['endtime'] ? 1:0;
				if($row['ccate']>0){
					$p=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_category')." WHERE id = '".$row['ccate']."' ");
					if($p){
						$temp['label']=$p['name'];
						$temp['bg']=$p['background'];
						$temp['color']=$p['color'];
					}
				}
				array_push($tempary,$temp);			
			}
			$allpage=$total % $psize>0 ?$total / $psize +1:$total / $psize;
			die(json_encode(array('success'=>true,'total'=>$allpage,'item'=>$tempary)));
		}
		if($operation=='submitreg'){
			//提交资料
			if ($_W['ispost']){
				if($_GPC['from_user']){
					$isjoin=pdo_fetch("SELECT id FROM ".tablename('qw_cjhdpt_winner')." WHERE from_user='".$_GPC['from_user']."' and aid='".$_GPC['id']."'");
					//if($isjoin)die(json_encode(array('success'=>false,'msg'=>"不能重复报名哦")));
				}
				$item=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE id = :id",array(':id'=>$_GPC['id'],));
				$insert=array(
					'mobile'=>trim($_GPC['mobile']),
					'nickname'=>$_GPC['nickname'],
					'realname'=>$_GPC['realname'],
					'gender'=>isset($_GPC['gender']) ? $_GPC['gender']:1,
					'avatar'=>$_GPC['avatar'],
					'from_user'=>$_GPC['from_user'],
					'openid'=>$_GPC['openid'],
					'aid'=>$_GPC['id'],
					'weid'=>$_W['uniacid'],
					'createtime'=>strtotime(date('Y-m-d H:i:s')),
					'status'=>1,
				);
				
				$paramkey=explode("|#jetsum#|",$_GPC['paramkey']);
				$paramval=explode("|#jetsum#|",$_GPC['paramval']);
				$parama=array();
				if(count($paramkey)){
					for($i=0;$i<count($paramkey);$i++) {
						$parama[urlencode($paramkey[$i])]=urlencode($paramval[$i]);
					}
				}
				$insert['parama']=urldecode(json_encode($parama));
				if(empty($isjoin)){
					pdo_insert("qw_cjhdpt_winner", $insert);
				}else{
					unset($insert['nickname']);
					unset($insert['avatar']);
					unset($insert['gender']);
					unset($insert['aid']);
					unset($insert['weid']);
					unset($insert['createtime']);
					pdo_update("qw_cjhdpt_winner", $insert,array("id"=>$isjoin['id']));
				}
				//---报名成功后---//
				//if($item['credit_join'])mc_credit_update($_W['member']['uid'],'credit1',$item['credit_join'],array($_W['user']['uid'],'活动报名成功添加积分'));
				$cfg = $this->module['config'];
				if($cfg['tempmsg_on'] && $cfg['tempmsg_join_call']){
					$url=$_W['siteroot']."app/index.php?i={$_W['uniacid']}&c=entry&do=view&m=qw_cjhdpt&id=".$_GPC['id']."&wxref=mp.weixin.qq.com#wechat_redirect";
					$openid=$_GPC['from_user'];
					if($cfg['user_oauth'])$openid=$_GPC['openid'];
					$result=j_tempeleSendMessage($cfg['tempmsg_join_call'],$_GPC['id'],$openid,'','',$url,$cfg['appid'],$cfg['appsecret']);
				}else{
					$tempmsg="恭喜您成功报名《|#活动标题#|》，我们将会尽快审核您的报名信息。审核结果我们将通过微信公众平台通知您。";
					if($cfg['msg_join'])$tempmsg=$cfg['msg_join'];
					$ruselt=jetsum_sendMessage($_GPC['from_user'],$tempmsg,$item['rid'],'报名成功');
				}
				$msg=$item['charge']>0 && $cfg['is_pay'] ? "本次活动需要支付￥".$item['charge']."元，方能通过报名哦.":"资料提交成功<br>我们将尽快审核，谢谢您的参与！";
				$str=$item['redirectmsg']? $item['redirectmsg']:$msg;
				$url=$item['redirecturl']? $item['redirecturl']:$this->createMobileUrl('view',array('id'=>$_GPC['id']));
				die(json_encode(array('success'=>true,'msg'=>$str,'url'=>$url,'feild'=>$_GPC['avatar'])));
			}
		}
	}
	//********************//
	public function doMobileHistory() {
		global $_GPC, $_W;
		load()->func('tpl');
		load()->model('mc');
		
		$act_all=pdo_fetchall("select * from ".tablename('qw_cjhdpt_reply')." where id in(select aid FROM ".tablename('qw_cjhdpt_winner')." WHERE weid = '{$_W['uniacid']}' and from_user='".$_W['openid']."') ");
		$act_ok=pdo_fetchall("select * from ".tablename('qw_cjhdpt_reply')." where id in(SELECT aid FROM ".tablename('qw_cjhdpt_winner')." WHERE weid = '{$_W['uniacid']}' and from_user='".$_W['openid']."' and status>1 order by id desc)");
		$profile = j_member_fetch();
		include $this->template('history');
	}
	
	public function doMobileAppend() {
		//签到处页面
		global $_GPC, $_W;
		include('../addons/qw_cjhdpt/phpqrcode.php');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$id=intval($_GPC['id']);
		if(empty($id))message('异常访问！');
		$_showMenu=1;
		$item=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE id = :id",array(':id'=>$id));
		if($operation=='ok'){
			$list=pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE aid = :aid order by attend desc,status desc,id desc",array(':aid'=>$item['id']));
			$parama=json_decode($item['parama'],true);
			$codename=$_W['uniacid']."_.png";
			$value = $_W['uniacid']."_".$id;
			//$cfg = $this->module['config'];
			$str=urlencode(encrypt($value, 'E', "www.yfjs-design.com"));
			//echo $str;
			QRcode::png($str, $codename, "L", 10);
		}
		include $this->template('append');
	}
	public function doMobileInfo() {
		//活动列表
		global $_GPC, $_W;
		load()->model('mc');
		$keyword=$_GPC['keyword'];
		$today=strtotime(date("Y-m-d"));
		$condition=" ";
		if($keyword)$condition.=" and (title like '%".$keyword."%' or description like '%".$keyword."%' or info like '%".$keyword."%' or rule like '%".$keyword."%' )";
		$list = pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE weid = '{$_W['uniacid']}' $condition order by id desc ");
		$children = array();
		$category = pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_category')." WHERE weid = '{$_W['uniacid']}' order by parentid asc, displayorder asc");
		foreach ($category as $index => $row) {
			$children[$row['id']]=$row;
		}
		$title="活动中心";
		$cfg = $this->module['config'];
		if($cfg['self_list']){
			include $this->template($cfg['self_list']);
		}else{
			if($cfg['lisstyle']){
				include $this->template('list_1');
			}else{
				include $this->template('list');
			}
		}
		
	}
	public function doMobileList() {
		//这个操作被定义用来呈现 微站首页导航图标
		$this->doMobileInfo();
	}

	public function doMobileIdqrcode() {
		//用户二维码身份
		global $_GPC, $_W;
		include('../addons/qw_cjhdpt/phpqrcode.php');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if(empty($_W['openid']))die('异常访问！');
		$codename=$_W['openid'].".png";
		$value = $_W['openid'];
		$str=urlencode(encrypt($value, 'E', "www.yfjs-design.com"));
		QRcode::png($str, $codename, "L", 10);
		include $this->template('idcard');
	}
	public function doMobileView(){
		//这个操作被定义用来呈现 微站首页导航图标
		global $_GPC, $_W;
		$id=intval($_GPC['id']);
		$openid_oath=$_GPC['openid_oath'] ? $_GPC['openid_oath'] : $_cookie['openid_oath'];
		$from_user=$_W['openid'];
		
		//----加载个人资料----//
		$isjoin=array();
		$user_status=1;
		$accountlevel=pdo_fetchcolumn("SELECT level FROM ".tablename("account_wechats")." WHERE uniacid = '".$_W['uniacid']."'");
		if($accountlevel==4 && !$openid_oath){
			$openid_oath=$_W['openid'];
		}
		//判断是否是本号登陆
		if(!$openid_oath){
			$user_status=1;
			if($cfg['user_oauth']){
				die(header("location:".$this->createMobileUrl('autooath',array('id'=>$id))));
			}
		}
		$p=j_memberAcatar_fetch_web($_W['openid']);
		$nickname=$p['nickname'];
		$avatar=$p['avatar'];
		
		///-----------------------//
		$item=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE id = :id",array(':id'=>$id));
		$cfg = $this->module['config'];
		$today=TIMESTAMP;
		if($today>$item['endtime']){
			if($item['status']!=2)pdo_update('qw_cjhdpt_reply',array('status'=>2),array('id'=>$id));
		}else{
			if($item['status']==0){
				if($today>=$item['joinstarttime'] && $today<=$item['joinendtime']){
					pdo_update('qw_cjhdpt_reply',array('status'=>1),array('id'=>$id));
					$item=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE id = :id",array(':id'=>$id));
				}
			}
		}
		$parama=json_decode($item["parama"],true);
		if($from_user){
			$member=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE aid = '".$item['id']."' and from_user='".$from_user."' ");
		}
		//---访问记录---//
		pdo_update('qw_cjhdpt_reply',array('visitied'=>$item['visitied']+1),array('id'=>$id));
		//---访问记录---//
		if($_W['openid'] || $_W['clientip']){
			$visiter=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_record')." WHERE aid='".$id."' and (from_user='".$_W['openid']."' or ip='".$_W['clientip']."') order by id desc limit 1");
			if(empty($visiter) || TIMESTAMP-$visiter['createtime']>3600){
				$visidata=array(
					'weid'=>$_W['uniacid'],
					'from_user'=>$_W['openid'],
					'ip'=>$_W['clientip'],
					'aid'=>$id,
					'createtime'=>TIMESTAMP,
				);
				pdo_insert('qw_cjhdpt_record',$visidata);
			}
		}
		//=====显示报名情况，最新报名人员=====//
		$newjoinerList=pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE aid = '".$item['id']."' order by id desc ");
		$joincount=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('qw_cjhdpt_winner')." WHERE aid = '".$item['id']."' ");
		//========初始化参数=========//
		$parmaTemp="";
		$parma=json_decode($item["parama"],true);
		foreach($parma as $index=>$row) {
			$parmaTemp.='<div class="jrow"><div class="jcell-3 lh30 text-right">'.$index."&nbsp;&nbsp;</div>";
			switch($row){
				case 1:
					$parmaTemp.='<div class="jcell-7 lh30"><input type="text" name="parama-key['.$index.']" key="'.$index.'" class="jreg_box_input" /></div>';
				break;
				case 2:
					$parmaTemp.='<div class="jcell-7 lh30"><input type="hidden" name="parama-key['.$index.']" key="'.$index.'" /><button type="button" onclick="uploadWimg(\''.$index.'\')" key="'.$index.'" class="btn btn-info btn-block"/>选择图片</button></div>';
				break;
			}
			$parmaTemp.="</div>";
		}
		//-------------支付---------------//
		if($item['charge']>0 && $member && $cfg['is_pay'] && $member['paystatus']==0 && $member['paytime']==0  && !$member['transid']){
			$tid=$member['ordersn'];
			if(!$tid){
				$tid=$member['id'].$member["createtime"];
				pdo_update("qw_cjhdpt_winner",array('ordersn'=>$tid),array('id'=>$member['id']));
			}
			$params['tid'] = $tid;
			$params['user'] = $_W['openid'];
			$params['fee'] = $item['charge'];
			$params['title'] = $_W['account']['name'];
			$params['ordersn'] = $tid;
			$params['virtual'] = true;
			$params['module'] = $this->module['name'];
			
			$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
			$pars = array();
			$pars[':uniacid'] = $_W['uniacid'];
			$pars[':module'] = $params['module'];
			$pars[':tid'] = $params['tid'];
			$log = pdo_fetch($sql, $pars);
			if(!empty($log) && $log['status'] == '1'){
				pdo_update("qw_cjhdpt_winner",array('paystatus'=>1,'status'=>2,'paytime'=>TIMESTAMP,'transid'=>$log['plid']),array('ordersn'=>$tid));
				die("<script>location.reload();</script>");
			}
			$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
			if(!is_array($setting['payment']))message('没有有效的支付方式, 请联系网站管理员.');
			$pay = $setting['payment'];
			$pay['credit']['switch'] = false;
			$pay['delivery']['switch'] = false;
		}
		//----------------------------//
		$title=$item['title'];
		$keyword = pdo_fetchcolumn("SELECT content FROM ".tablename('rule_keyword')." WHERE rid = :rid order by id asc LIMIT 1", array(':rid' => $item['rid']));
		
		$cfg = $this->module['config'];
		//include $this->template('view_1');
		if($cfg['self_view']){
			include $this->template($cfg['self_view']);
		}else{
			include $this->template('view_1');
		}
	}

	public function payResult($params) {
		//支付
		global $_W;
        $fee = intval($params['fee']);
		$id = intval($params['aid']);
        $data = array('paystatus' => $params['result'] == 'success' ? 1 : 0);
        if ($params['type'] == 'wechat') {
            $data['transid'] = $params['tag']['transaction_id'];
			$data['status'] = 2;
			$data['paytime'] = TIMESTAMP;
        }
        pdo_update('qw_cjhdpt_winner', $data, array('ordersn' => $params['tid']));
		$user=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE ordersn = :ordersn",array(":ordersn"=>$params['tid']));
		$openid=$user['openid'];
		$item=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE id = :id",array(":id"=>$user['aid']));
		$cfg = $this->module['config'];
		$tempmsg="恭喜您，|#姓名#| |#性别#|，您报名的《|#活动标题#|》已成功通过审核。活动将于|#活动时间#|，在|#活动地点#|举行。请准时出席哦";
		if($cfg['msg_ok'])$tempmsg=$cfg['msg_ok'];
		$tempstr=str_replace("|#活动标题#|",$item['title'],$tempmsg);
		$tempstr=str_replace("|#姓名#|",$user['realname'],$tempstr);
		$tempstr=str_replace("|#昵称#|",$user['nickname'],$tempstr);
		$tempstr=str_replace("|#性别#|",($user['gender']==1? "先生":"女士"),$tempstr);
		$tempstr=str_replace("|#电话#|",$user['mobile'],$tempstr);
		$tempstr=str_replace("|#活动时间#|",date('Y-m-d',$item['starttime'])."至".date('Y-m-d',$item['endtime']),$tempstr);
		$tempstr=str_replace("|#活动地点#|",$item['address'],$tempstr);
		$tempstr=str_replace("|#签到时间#|",date('Y-m-d H:i',$user['endtime']),$tempstr);
		$tempstr=str_replace("|#签到回调#|",$user['reloadmsg'],$tempstr);
		$send=array(
			"touser"=>$openid,
			"msgtype"=>"text",
			'text' => array('content' => urlencode($tempstr)),
		);
		load()->func('communication');
		$account=pdo_fetch("SELECT * FROM ".tablename('account_wechats')." WHERE uniacid = :uniacid",array(':uniacid'=>$_W['uniacid']));
		$acccount_acc=iunserializer($account['access_token']);
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$acccount_acc['token']}";
		$response = ihttp_request($url, urldecode(json_encode($send)));
			
        $url=$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&id=".$item['id']."&do=view&m=qw_cjhdpt";
		if($item['redirecturl'])$url=$item['redirecturl'];
		header("Location:".$url);
    }
	//--------借用并且同步数据----------//
	public function doMobileAutooath() {
		//借用并且同步数据
		global $_GPC, $_W;
		load()->func('communication');
		$appid="";
		$appsecret="";
		$account=pdo_fetch("SELECT * FROM ".tablename("account_wechats")." WHERE uniacid = '".$_W['uniacid']."'");
		if($account['level']==4){
			$appid=$account['key'];
			$appsecret=$account['secret'];
		}else{
			$cfg = $this->module['config'];
			if($cfg['appid'] && $cfg['appsecret']){
				$appid=$cfg['appid'];
				$appsecret=$cfg['appsecret'];
			}
		}
		if(!isset($_GPC['code'])){
			$url=$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&id=".$_GPC['id']."&from_user=".$_GPC['from_user']."&do=autooath&m=qw_cjhdpt";
			header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
		}else{
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$_GPC['code']."&grant_type=authorization_code";
			$content = ihttp_get($url);
			$token = @json_decode($content['content'], true);
			$url="https://api.weixin.qq.com/sns/userinfo?access_token=".$token['access_token']."&openid=".$token['openid']."&lang=zh_CN";
			$content = ihttp_get($url);
			$profile = @json_decode($content['content'], true);
			
			setcookie('openid_oath',$profile['openid'],time() + 86400);
			setcookie('openid_gender',$profile['sex'],time() + 86400);
			setcookie('openid_avatar',$profile['avatar'],time() + 86400);
			setcookie('openid_nickname',$profile['nickname'],time() + 86400);
			
			die(header("location:".$this->createMobileUrl('view',array('id'=>$_GPC['id'],'openid_oath'=>$profile['openid']))));
		}
	}
	public function doMobileError() {
		//错误页面
		global $_GPC, $_W;
		$id=$_GPC['id'];
		$rid = pdo_fetchcolumn("SELECT rid FROM ".tablename('qw_cjhdpt_reply')." WHERE id = :id LIMIT 1", array(':id' => $id));
		$keyword = pdo_fetchcolumn("SELECT content FROM ".tablename('rule_keyword')." WHERE rid = :rid order by id asc LIMIT 1", array(':rid' => $rid));
		include $this->template('error');
	}
	public function doMobileAjaxupload() {
		global $_GPC, $_W;
		$media_id=$_GPC['media_id'];
		load()->func('communication');
		$ACCESS_TOKEN=$_W['account']['access_token']['token'];
		$url="http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$ACCESS_TOKEN."&media_id=".$media_id."";
		echo saveMedia($url);
	}
	public function doWebJoiner() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$rid=intval($_GPC['id']);
		$uid=intval($_GPC['uid']);
		load()->model('mc');
		$item = pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE rid = :rid",array(':rid'=>$rid));
		$where="";
		$order="  id asc ";
		$siteurl=$_W["siteurl"];
		if($_GPC['order']==1){
			$order=" id asc ";
			$siteurl=str_replace("&order=1","",$siteurl);
		}elseif($_GPC['order']==2){
			$order=" id desc";
			$siteurl=str_replace("&order=2","",$siteurl);
		}elseif($_GPC['order']==3){
			$order=" paytime asc";
			$siteurl=str_replace("&order=3","",$siteurl);
		}elseif($_GPC['order']==4){
			$order=" paytime desc";
			$siteurl=str_replace("&order=4","",$siteurl);
		}elseif($_GPC['order']==5){
			$order=" endtime asc";
			$siteurl=str_replace("&order=5","",$siteurl);
		}elseif($_GPC['order']==6){
			$order=" endtime desc";
			$siteurl=str_replace("&order=6","",$siteurl);
		}
		if($_GPC['keyword'])$where.=" and (nickname like '%".$_GPC['keyword']."%' or mobile like '%".$_GPC['keyword']."%' or realname like '%".$_GPC['keyword']."%' or parama like '%".$_GPC['keyword']."%' or reloadmsg like '%".$_GPC['keyword']."%' or remark like '%".$_GPC['keyword']."%' )";
		if($_GPC['gender'])$where.=" and gender ='".$_GPC['gender']."' ";
		if($_GPC['attend'])$where.=" and attend ='".($_GPC['attend']==1 ? 1 : 0)."' ";
		if($_GPC['status'])$where.=" and status ='".$_GPC['status']."' ";
		$list = pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE aid = '".$item['id']."' $where order by $order");
		$count0=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('qw_cjhdpt_winner')." WHERE aid = '".$item['id']."' and status=-1");
		$count1=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('qw_cjhdpt_winner')." WHERE aid = '".$item['id']."' and status=1");
		$count2=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('qw_cjhdpt_winner')." WHERE aid = '".$item['id']."' and status=2");
		$grouplist= pdo_fetchall("SELECT * FROM ".tablename("mc_groups")." WHERE uniacid = '".$_W['uniacid']."' ORDER BY `orderlist` asc");
		$groupAry=array();
		foreach($grouplist as $row){
			$groupAry[$row['groupid']]=$row['name'];
		}
		$parama=json_decode($item['parama'],true);
		$field=array();
		foreach($parama as $index=>$row){
			array_push($field,$index);
		}
		$cfg = $this->module['config'];
		if($operation=='in'){
			if(!empty($uid)){
				pdo_update('qw_cjhdpt_winner',array('status'=>'2'),array('id'=>$uid));
				$openid = pdo_fetchcolumn("SELECT from_user FROM ".tablename('qw_cjhdpt_winner')." WHERE id = '".$uid."' ");
				if($item['credit_in']){
					$uuid = pdo_fetchcolumn("SELECT uid FROM ".tablename('mc_mapping_fans')." WHERE openid = '".$openid."' ");
					if($uuid)mc_credit_update($uuid,'credit1',$item['credit_in'],array($_W['user']['uid'],'入选添加积分'));
				}
				if($cfg['tempmsg_on'] && $cfg['tempmsg_join_ok'] && $item['status']<2){
					$url=$_W['siteroot']."app/index.php?i={$_W['uniacid']}&c=entry&do=view&m=qw_cjhdpt&id=".$item['id']."&wxref=mp.weixin.qq.com#wechat_redirect";
					if($cfg['user_oauth'])$openid=pdo_fetchcolumn("SELECT openid FROM ".tablename('qw_cjhdpt_winner')." WHERE id = '".$uid."' ");
					$result=j_tempeleSendMessage($cfg['tempmsg_join_ok'],$item['id'],$openid,'','',$url,$cfg['appid'],$cfg['appsecret']);
					//print_r($result);
				}
				$openid = pdo_fetchcolumn("SELECT from_user FROM ".tablename('qw_cjhdpt_winner')." WHERE id = '".$uid."' ");
				$tempmsg="恭喜您，|#姓名#| |#性别#|，您报名的《|#活动标题#|》已成功通过审核。活动将于|#活动时间#|，在|#活动地点#|举行。请准时出席哦";
				if($cfg['msg_ok'])$tempmsg=$cfg['msg_ok'];
				$ruselt=jetsum_sendMessage($openid,$tempmsg,$rid,'后台通过审核');
				message('操作成功！',$this->createWebUrl('joiner',array('id'=>$rid)), 'success');
			}
		}elseif($operation=='out'){
			if(!empty($uid)){
				$openid = pdo_fetchcolumn("SELECT from_user FROM ".tablename('qw_cjhdpt_winner')." WHERE id = '".$uid."' ");
				if($cfg['tempmsg_on'] && $cfg['tempmsg_join_out'] && $item['status']<2){
					$url=$_W['siteroot']."app/index.php?i={$_W['uniacid']}&c=entry&do=view&m=qw_cjhdpt&id=".$item['id']."&wxref=mp.weixin.qq.com#wechat_redirect";
					if($cfg['user_oauth'])$openid=pdo_fetchcolumn("SELECT openid FROM ".tablename('qw_cjhdpt_winner')." WHERE id = '".$uid."' ");
					$result=j_tempeleSendMessage($cfg['tempmsg_join_out'],$item['id'],$openid,'','',$url,$cfg['appid'],$cfg['appsecret']);
				}
				$tempmsg="十分抱歉，您报名的《|#活动标题#|》审核不通过。再次感谢您的关注";
				if($cfg['msg_false'])$tempmsg=$cfg['msg_false'];
				$openid = pdo_fetchcolumn("SELECT from_user FROM ".tablename('qw_cjhdpt_winner')." WHERE id = '".$uid."' ");
				$ruselt=jetsum_sendMessage($openid,$tempmsg,$rid,'后台不通过审核');
				
				pdo_update('qw_cjhdpt_winner',array('status'=>'-1'),array('id'=>$uid));
				message('操作成功！',$this->createWebUrl('joiner',array('id'=>$rid)), 'success');
			}
		}elseif($operation=='delete'){
			if(!empty($uid)){
				$openid = pdo_fetchcolumn("SELECT from_user FROM ".tablename('qw_cjhdpt_winner')." WHERE id = '".$uid."' ");
				if($cfg['tempmsg_on'] && $cfg['tempmsg_join_out'] && $item['status']<2){
					
					$url=$_W['siteroot']."app/index.php?i={$_W['uniacid']}&c=entry&do=view&m=qw_cjhdpt&id=".$item['id']."&wxref=mp.weixin.qq.com#wechat_redirect";
					if($cfg['user_oauth'])$openid=pdo_fetchcolumn("SELECT openid FROM ".tablename('qw_cjhdpt_winner')." WHERE id = '".$uid."' ");
					$result=j_tempeleSendMessage($cfg['tempmsg_join_out'],$item['id'],$openid,'','',$url,$cfg['appid'],$cfg['appsecret']);
				}
				$tempmsg="十分抱歉，您报名的《|#活动标题#|》审核不通过。再次感谢您的关注";
				if($cfg['msg_false'])$tempmsg=$cfg['msg_false'];
				$openid = pdo_fetchcolumn("SELECT from_user FROM ".tablename('qw_cjhdpt_winner')." WHERE id = '".$uid."' ");
				$ruselt=jetsum_sendMessage($openid,$tempmsg,$item['rid'],'后台不通过审核');
				echo $ruselt;
				pdo_delete('qw_cjhdpt_winner',array('id'=>$uid));
				message('操作成功！',$this->createWebUrl('joiner',array('id'=>$rid)), 'success');
			}
		}elseif($operation=='msg'){
			$uid=intval($_GPC['uid']);
			$profile=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE id = '".$uid."' ");
			$list=pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_msgrecord')." WHERE aid = '".$item['id']."' and (from_user='".$profile['from_user']."' or from_user='".$profile['openid']."') order by id desc");
		}
		include $this->template('joiner');
	}
	public function doWebManage() {
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		load()->func('tpl');
		$id = intval($_GPC['id']);
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE weid = '{$_W['uniacid']}' order by id desc");
		} elseif ($operation == 'post') {
			
			if(!empty($id)) {
				$reply = pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE id = '$id'");
			}
			$grouplist= pdo_fetchall("SELECT * FROM ".tablename("mc_groups")." WHERE uniacid = '".$_W['uniacid']."' ORDER BY `orderlist` asc");
			if (checksubmit('submit')) {
				$insert = array(
					'picture' => $_GPC['picture'],
					'qrcode' => $_GPC['qrcode'],
					'pcate' => intval($_GPC['pcate']),
					'ccate' => intval($_GPC['ccate']),
					'clientpic' => $_GPC['clientpic'],
					'title' => $_GPC['title'],
					'description' => str_replace("\r\n","",($_GPC['description'])),
					'info' => htmlspecialchars_decode($_GPC['info']),
					'rule' => htmlspecialchars_decode($_GPC['rule']),
					'content' => htmlspecialchars_decode($_GPC['content']),
					'appendcode' => $_GPC['appendcode'],
					'quota' => intval($_GPC['quota']),
					'joinstarttime' => strtotime($_GPC['jointime']['start']),
					'joinendtime' => strtotime($_GPC['jointime']['end']),
					'starttime' => strtotime($_GPC['acttime']['start']),
					'endtime' => strtotime($_GPC['acttime']['end']),
					'applicants'=>intval($_GPC['applicants']),
					'status'=>intval($_GPC['status']),
					'usertype'=>intval($_GPC['usertype']),
					'credit_join'=>intval($_GPC['credit_join']),
					'credit_in'=>intval($_GPC['credit_in']),
					'credit_append'=>intval($_GPC['credit_append']),
					'longitude' => $_GPC['longitude'],
					'latitude' => $_GPC['latitude'],
					'address' => $_GPC['address'],
					'redirecturl' => $_GPC['redirecturl'],
					'organizer' => $_GPC['organizer'],
					'charge' => floatval($_GPC['charge']),
					'redirectmsg' => $_GPC['redirectmsg'],
					'label' => isset($_GPC['selectlabel']) ? $_GPC['selectlabel']=="自定义" ? $_GPC['selectlabeltext']:$_GPC['selectlabel'] :"",
					
				);
				//echo $_GPC['longitude'];
				$parama=array();
				if(isset($_GPC['parama-key'])){
					foreach ($_GPC['parama-key'] as $index => $row) {
						if(empty($row))continue;
						
						$parama[urlencode($row)]=urlencode($_GPC['parama-val'][$index]);
					}
				}
				if(isset($_GPC['parama-key-new'])){
					foreach ($_GPC['parama-key-new'] as $index => $row) {
						if(empty($row))continue;
						echo $_GPC['parama-val'][$index];
						$parama[urlencode($row)]=urlencode($_GPC['parama-val-new'][$index]);
					}
				}
				$insert['parama']=urldecode(json_encode($parama));
				if (!empty($id)) {
					pdo_update('qw_cjhdpt_reply', $insert, array('id' => $id));
				}
				message('更新成功！', $this->createWebUrl('manage', array('op' => 'display')), 'success');
			}
		}
		include $this->template('manage');
	}

	public function doWebExport() {
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		require_once '../addons/qw_cjhdpt/PHPZip.php';
		require_once '../framework/library/phpexcel/PHPExcel.php';
		$rid=intval($_GPC['rid']);
		load()->func('file');
		$item = pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE rid =:id ",array(":id"=>$rid));
		$list = pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE aid =:id order by id desc",array(":id"=>$item['id']));
		if($operation=="excel"){
			$excelFiel=array("K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
			$objExcel = new PHPExcel();
			$objWriter = new PHPExcel_Writer_Excel5($objExcel);
			$objProps = $objExcel->getProperties();
			$objProps->setCreator("捷讯活动平台");
			$objProps->setLastModifiedBy("捷讯活动平台");
			$objProps->setTitle("活动报名数据");
			$objProps->setSubject("活动报名数据");
			$objProps->setDescription("活动报名数据");
			$objProps->setKeywords("活动报名数据");
			$objProps->setCategory("活动报名数据");
			$objExcel->setActiveSheetIndex(0);
			$objActSheet = $objExcel->getActiveSheet();
			$objActSheet->setTitle($item['title']);
			$objActSheet->setCellValue('A1', '编号');
			$objActSheet->setCellValue('B1', '昵称');
			$objActSheet->setCellValue('C1', '姓名');
			$objActSheet->setCellValue('D1', '手机');
			$objActSheet->setCellValue('E1', '性别');
			$objActSheet->setCellValue('F1', '报名时间');
			$objActSheet->setCellValue('G1', '是否入选');
			$objActSheet->setCellValue('H1', '签到时间');
			$objActSheet->setCellValue('I1', '签到信息');
			$objActSheet->setCellValue('J1', '备注');
			$parama=json_decode($item['parama'],true);
			$i=0;
			foreach($parama as $index=>$row){
				$objActSheet->setCellValue($excelFiel[$i].'1', $index);
				$i++;
			}
			$i=2;
			echo count($list);
			foreach($list as $row){
				$objActSheet->setCellValue('A'.$i, $i-1);
				$objActSheet->setCellValue('B'.$i, $row['nickname']);
				$objActSheet->setCellValue('C'.$i, $row['realname']);
				$objActSheet->setCellValue('D'.$i, $row['mobile']);
				$objActSheet->setCellValue('E'.$i, $row['gender']==1? '先生':'女士');
				$objActSheet->setCellValue('F'.$i, date("Y-m-d G:i:s",$row['createtime']));
				$status="待审核";
				switch($row['status']){
					case -1:
						$status="落选";
					break;
					case 1:
					case 0:
						$status="待审核";
					break;
					default:
						$status="入选";
				}
				$objActSheet->setCellValue('G'.$i, $status);
				$objActSheet->setCellValue('H'.$i, $row['attend']? '已签到':'');
				$objActSheet->setCellValue('I'.$i, $row['reloadmsg']);
				$objActSheet->setCellValue('J'.$i, $row['remark']);
				$para=json_decode($row['parama'],true);
				
				$j=0;
				foreach($parama as $index=>$p){
					$objActSheet->setCellValue($excelFiel[$j].$i,$para[$index]);
					$j++;
				}
				$i++;
			}
			
			if(!is_dir("../addons/qw_cjhdpt/temp/"))mkdirs("../addons/qw_cjhdpt/temp/");
			$outputFlorder="../addons/qw_cjhdpt/temp/".$_W['uniacid']."/";
			if(!is_dir($outputFlorder))mkdirs($outputFlorder);
			$outputFileName = $outputFlorder."/temp.xls";
			if(file_exists($outputFileName))file_delete($outputFileName);
			$objWriter->save($outputFileName);
			header("Location:$outputFileName");
		}
		if($operation=="allinfo"){
			if(is_dir("../addons/qw_cjhdpt/temp/".$_W['uniacid']."/".$rid))rmdirs("../addons/qw_cjhdpt/temp/".$_W['uniacid']."/".$rid);
			if(!is_dir("../addons/qw_cjhdpt/temp/"))@mkdirs("../addons/qw_cjhdpt/temp/");
			$outputFlorder="../addons/qw_cjhdpt/temp/".$_W['uniacid']."/";
			if(!is_dir($outputFlorder))@mkdirs($outputFlorder);
			$outputFileName = $outputFlorder."/".$rid.".zip";
			if(file_exists($outputFileName))file_delete($outputFileName);
			$outputFlorder="../addons/qw_cjhdpt/temp/".$_W['uniacid']."/".$rid;
			if(!is_dir($outputFlorder))@mkdirs($outputFlorder);
			header("Location:".$this->createWebUrl('export',array('op'=>'allinfo2','rid'=>$rid,)));
		}
		if($operation=="allinfo2"){
			$outputFlorder="../addons/qw_cjhdpt/temp/".$_W['uniacid']."/".$rid;
			$parama=json_decode($item['parama'],true);
			$i=0;
			foreach($parama as $index=>$row){
				if($row==2){
					$i++;
				}
			}
			if(!$i)die('没有图片资料，请直接导出excel');
			
			foreach($list as $row){
				$tempflorder=$row['mobile'] ? iconv('utf-8', 'gb2312',$row['mobile'].$row['realname']) :$row['id'];
				if(!is_dir($outputFlorder."/".$tempflorder))@mkdirs($outputFlorder."/".$tempflorder);
				$temparama=json_decode($row['parama'],true);
				$i=1;
				foreach($parama as $index=>$p){
					if($p==2){
						$tempAry=explode(".",$temparama[$index]);
						$n=count($tempAry);
						$ext=$tempAry[$n-1];
						$picurl=$temparama[$index];
						$target=$outputFlorder."/".$tempflorder."/".$row['mobile']."_".$i.".".$ext;
						if(file_exists($picurl)){
							copy($picurl,$target);
							$i++;
						}
					}
				}
			}
			$archive  = new PHPZip();
			$archive -> ZipAndDownload($outputFlorder);
		}
	}
	//---------测试功能-----------
	public function doWebTest() {
		global $_GPC, $_W;
		
		include $this->template('test');
	}
	//--------------------
	public function doWebScreen() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$reply = pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE rid = '$id'");
		$time=pdo_fetchcolumn("SELECT max(times) FROM ".tablename('qw_cjhdpt_lucky')." WHERE aid = '".$reply['id']."' limit 1");
		$times=intval($time)+1;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($operation=="restart"){
			if($id){
				pdo_delete("qw_cjhdpt_lucky",array("aid"=>$reply['id']));
			}
			message('系统初始化完成！', $this->createWebUrl('screen', array('op' => 'display','id' => $id)), 'success');
		}
		$gamenumber = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('qw_cjhdpt_lucky')." WHERE aid ='".$reply['id']."'");
		include $this->template('screen');
	}
	public function doWebLucky() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$reply = pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE rid = '$id'");
		if($_GPC['wid']){
			$status=intval($_GPC['status']);
			$wid=intval($_GPC['wid']);
			if($wid){
				pdo_update("qw_cjhdpt_lucky",array("status"=>$status),array('id'=>$wid));
			}
			message('标识成功！', $this->createWebUrl('lucky', array('id' => $id)), 'success');
		}
		if (checksubmit('delete')) {
			pdo_delete('qw_cjhdpt_lucky', " from_user  IN  ('".implode("','", $_GPC['select'])."')");
			message('删除成功！', $this->createWebUrl('lucky', array('id' => $id)), 'success');
		}
		if (checksubmit('geprize')) {
			pdo_query("update ".tablename('qw_cjhdpt_lucky')." set status=2 where from_user  IN  ('".implode("','", $_GPC['select'])."')");
			message('标记成功！', $this->createWebUrl('lucky', array('id' => $id)), 'success');
		}
		if (checksubmit('dealprize')) {
			pdo_query("update ".tablename('qw_cjhdpt_lucky')." set status=0 where from_user  IN  ('".implode("','", $_GPC['select'])."')");
			message('标记成功！', $this->createWebUrl('lucky', array('id' => $id)), 'success');
		}
		$list = pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_lucky')." WHERE aid ='".$reply['id']."' order by id desc");
		include $this->template('lucky');
	}
	
	public function doWebAttend() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		if(empty($id))message('异常访问！');
		
		$item=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE id =:id ",array(":id"=>$id));
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$title=$item['title'];
		include('../addons/qw_cjhdpt/phpqrcode.php');
		$list=pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE aid = :aid order by attend desc,status desc,id desc",array(':aid'=>$item['id']));
		$parama=json_decode($item['parama'],true);
		$codename=$_W['uniacid']."_.png";
		$value = $_W['uniacid']."_".$id;
		$cfg = $this->module['config'];
		$str=urlencode(encrypt($value, 'E', "www.yfjs-design.com"));
		QRcode::png($str, $codename, "L", 10);
		
		include $this->template('screen2');
	}
	public function doWebAjax() {
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($operation=="editmark"){
			$id = intval($_GPC['id']);
			$item = pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE id =:id ",array(":id"=>$id));
			die(json_encode(array("remark"=>$item["remark"],"reloadmsg"=>$item["reloadmsg"],)));
		}
		if($operation=="savemark"){
			$id = intval($_GPC['id']);
			$reloadmsg = $_GPC['reloadmsg'];
			$remark = $_GPC['remark'];
			pdo_update('qw_cjhdpt_winner',array('reloadmsg'=>$reloadmsg,'remark'=>$remark),array('id'=>$id));
			die(json_encode(array("success"=>true)));
		}
		if($operation=="ajaxgetinfo"){
			$id = intval($_GPC['id']);
			$item=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE id=:id",array(":id"=>intval($_GPC['id'])));
			if(empty($item))die(json_encode(array('success'=>false,'msg'=>"报名人员不存在！")));
			$reply=pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE id=:id",array(":id"=>intval($item['aid'])));
			if(empty($reply))die(json_encode(array('success'=>false,'msg'=>"活动已被删除！")));
			$parama=json_decode($reply['parama'],true);
			$p=json_decode($item['parama'],true);
			$para=array();
			foreach($parama as $index=>$val){
				if($val==2){
					$para[$index]="<img src='".$p[$index]."' class='viewimg' width='80' height='80' />";
				}else{
					$para[$index]=$p[$index];
				}
			}
			if($item['createtime'])$item['createtime']=date("y-m-d H:i:s",$item['createtime']);
			if($item['endtime'])$item['endtime']=date("y-m-d H:i:s",$item['endtime']);
			die(json_encode(array('success'=>true,'info'=>$item,'parama'=>$para)));
		}
		if($operation=="gettempmsg"){
			$id = intval($_GPC['id']);
			$item = pdo_fetch("SELECT * FROM ".tablename('j_tempmsg_temp')." where id='".$id."' ");
			$temp=str_replace(array("\r\n", "\r", "\n"), '',$item['content']);
			
			$parama=json_decode($item['parama'],true);
			foreach($parama as $key=>$val){
				$temp=str_replace('{{'.$key.'.DATA}}', '',$temp);
			}
			$tempAry=explode("：",$temp);
			$tempStr="";
			$i=0;
			foreach($parama as $key=>$val){
				if($key=="first"){
					$tempStr.='<div class="form-group"><div class="col-sm-3 col-xs-3">标题</div><div class="col-sm-9 col-xs-9">
					<input type="text" class="form-control tempinput" name="first" placeholder="请填写标题" ></div></div>';
				}elseif($key=="remark"){
					$tempStr.='<div class="form-group"><div class="col-sm-3 col-xs-3">备注</div><div class="col-sm-9 col-xs-9">
					<input type="text" class="form-control tempinput" name="remark" ></div></div>';
				}else{
					$tempStr.='<div class="form-group"><div class="col-sm-3 col-xs-3">'.$tempAry[($i-1)].'</div><div class="col-sm-9 col-xs-9">
					<input type="text" class="form-control tempinput" name="'.$key.'" placeholder="'.$tempAry[($i-1)].'" ></div></div>';
				}
				$i++;
			}			
			die(json_encode(array("success"=>true,'str'=>$tempStr,'s'=>$temp)));
		}
		if($operation=="sendtempmsg"){
			$id = intval($_GPC['id']);
			$tempid = intval($_GPC['tempid']);
			$openid = $_GPC['openid'];
			$key = $_GPC['key'];
			$val = $_GPC['val'];
			$data=array();
			$temp1=explode("||",$key);
			$temp2=explode("||",$val);
			for($i=0;$i<count($temp1);$i++){
				$data[$temp1[$i]]=$temp2[$i];
			}
			//=====
			$cfg = $this->module['config'];
			if($cfg['user_oauth'])$openid=pdo_fetchcolumn("SELECT openid FROM ".tablename('qw_cjhdpt_winner')." WHERE from_user = '".$openid."' and aid='".$id."'");
			$url=$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=view&m=qw_cjhdpt&id=".$id."&wxref=mp.weixin.qq.com#wechat_redirect";
			$result=j_tempeleSendMessage($tempid,$id,$openid,$data,'',$url,$cfg['appid'],$cfg['appsecret']);
			die(json_encode(array("success"=>true,'str'=>$result)));
		}
		if($operation=="getlucky"){
			//点指兵兵
			$id = intval($_GPC['id']);
			$isappend = intval($_GPC['isappend']);
			$isstatus = intval($_GPC['isstatus']);
			$sex = intval($_GPC['sex']);
			$num = intval($_GPC['num']);
			$times = intval($_GPC['times']);
			$where="";
			if(!empty($isappend))$where.=" and attend=1";
			if(!empty($isstatus))$where.=" and status=2";
			if(!empty($sex))$where.=" and gender=".$sex;
			$reply = pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE rid = '$id'");
			$item = pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE aid =:rid $where and from_user not in(SELECT from_user FROM ".tablename('qw_cjhdpt_lucky')." WHERE aid ='".$reply['id']."') order by id desc",array(":rid"=>$reply['id']));
			$ary=array();
			foreach($item as $row){
				$sex="先生";
				if($row['gender']==2)$sex="女士";
				$ary[]=array(
					"from_user"=>$row['from_user'],
					"realname"=>mb_substr($row['realname'],0,1,'utf-8').$sex,
					"mobile"=>"*******".substr($row['mobile'],-4),
					"avatar"=>$row['avatar'],
				);
			}
			shuffle($ary);
			$result_ary=array();
			$i=0;
			foreach($ary as $row){
				if($i<$num){
					$result_ary[]=$row;
					$data=array(
						"weid"=>$_W['uniacid'],
						"from_user"=>$row['from_user'],
						"aid"=>$reply['id'],
						"times"=>$times,
						"status"=>0,
					);
					pdo_insert("qw_cjhdpt_lucky",$data);
					/*$cfg = $this->module['config'];
					if($cfg['tempmsg_on'] && $cfg['tempmsg_lucky']){
						$url="#";
						$result=j_tempeleSendMessage($cfg['tempmsg_lucky'],$reply['id'],$row['from_user'],'','',$url);
					}*/
				}
				$i++;
			}
			$gamenumber = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('qw_cjhdpt_lucky')." WHERE aid ='".$reply['id']."'");
			die(json_encode(array("success"=>true,'item'=>$result_ary,"gamenumber"=>$gamenumber,)));
		}
		if($operation=="getluckylist"){
			//点指兵兵
			$id = intval($_GPC['id']);
			$reply = pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE rid = '$id'");
			$item = pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE aid =:rid $where and from_user in(SELECT from_user FROM ".tablename('qw_cjhdpt_lucky')." WHERE aid ='".$reply['id']."') order by id desc",array(":rid"=>$reply['id']));
			$ary=array();
			foreach($item as $row){
				$sex="先生";
				if($row['gender']==2)$sex="女士";
				$ary[]=array(
					"from_user"=>$row['from_user'],
					"realname"=>mb_substr($row['realname'],0,1,'utf-8').$sex,
					"mobile"=>"*******".substr($row['mobile'],-4),
					"avatar"=>$row['avatar'],
				);
			}
			
			die(json_encode(array("success"=>true,'item'=>$ary,)));
		}
		if($operation=="sendluckymessage"){
			$id = intval($_GPC['id']);
			$type = intval($_GPC['type']);
			$reply = pdo_fetch("SELECT * FROM ".tablename('qw_cjhdpt_reply')." WHERE rid = '$id'");
			$item = pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_winner')." WHERE aid =:rid and from_user in(SELECT from_user FROM ".tablename('qw_cjhdpt_lucky')." WHERE aid ='".$reply['id']."') order by id desc",array(":rid"=>$reply['id']));
			$send_ok=0;
			$send_false=0;
			$cfg = $this->module['config'];
			$msg_contet="恭喜您，在|#活动标题#|中获得奖励。请携带信息（转发无效）到现场。详情请联系客服。";
			if(!$cfg['msg_lucky'])$msg_contet=$cfg['msg_lucky'];
			foreach($item as $row){
				$result=0;
				if($type==1){
					if($cfg['tempmsg_on'] && $cfg['tempmsg_lucky']){
						$url="#";
						$result=j_tempeleSendMessage($cfg['tempmsg_lucky'],$reply['id'],$row['from_user'],'','',$url);
					}
				}else{
					$ruselt=jetsum_sendMessage($row['from_user'],$msg_contet,$_GPC['id'],'点指兵兵');
				}
				if($ruselt){
					$send_ok++;
				}else{
					$send_false++;
				}
			}
			die(json_encode(array("success"=>true,'allnum'=>count($item),'send_ok'=>$send_ok,'send_false'=>$send_false)));
		}
		
		if($operation=="sendmsg"){
			$aid = intval($_GPC['aid']);
			$content =htmlspecialchars_decode( $_GPC['content']);
			$openid = $_GPC['openid'];
			$remark = $_GPC['remark'];
			//die(print_r($_GPC));
			$ruselt=jetsum_sendMessage($openid,$content,$aid,$remark);
			die(json_encode(array("success"=>$ruselt)));
		}
		
		if($operation=="getmsgrecord"){
			$rid = intval($_GPC['rid']);
			$openid = $_GPC['openid'];
			$list=pdo_fetchall("SELECT * FROM ".tablename('qw_cjhdpt_msgrecord')." WHERE aid =:rid and from_user='".$openid."' order by id asc ",array(":rid"=>$rid));
			$temp=array();
			foreach($list as $row){
				$temp[]=array(
					"content"=>$row["content"],
					"remark"=>$row["remark"],
					"createtime"=>date("Y-m-d G:i:s",$row["createtime"]),
					"status"=>$row["status"],
				);
			}
			die(json_encode(array("success"=>true,"items"=>$temp)));
		}
		
	}
}