<?php
/**
 * 微美发
 * Author:Shirne
 * 
 */
defined('IN_IA') or exit('Access Denied');
class bm_meifaModuleSite extends WeModuleSite {
    public $weid;
    public function __construct() { 
        global $_W;
        $this->weid = IMS_VERSION<0.6?$_W['weid']:$_W['uniacid'];
    }
    //技师列表
	public function doWebProject(){
		global $_GPC,$_W;
		load()->func('tpl');
		$op = !empty($_GPC['op'])?$_GPC['op']:'display';
		if ($op == 'post') {

			if (!empty($_GPC['id'])) {
				$item = pdo_fetch("SELECT * FROM".tablename('meifa_project')." WHERE id='{$_GPC['id']}'");
			}
			$data = array(
				'weid'            => $_W['weid'],
				'sort'            => $_GPC['sort'],
				'ser_name'        => $_GPC['ser_name'],
				'srvtime'        => htmlspecialchars_decode($_GPC['srvtime']),
				'position'     => $_GPC['position'],
				'kbox'            => $_GPC['kbox'],
				'price'           => $_GPC['price'],
				'picurl' => $_GPC['picurl'],
				'isshow'           => intval($_GPC['isshow']),
				'project_info'    => htmlspecialchars_decode($_GPC['project_info']),
			);
			if ($_W['ispost']) {
				if (empty($_GPC['id'])) {
					pdo_insert("meifa_project",$data);
				}else{
					pdo_update("meifa_project",$data,array('id' => $_GPC['id']));
				}
				message("更新成功",referer(),'success');
			}
		}elseif ($op == 'display') {
			$projects = pdo_fetchAll("SELECT * FROM".tablename('meifa_project')." WHERE weid='{$_W['weid']}'");
			//print_r($_W['weid']);exit;
			$list = array();
			foreach ($projects as $key => $value) {
				$list[$key]['id'] = $value['id'];
				$list[$key]['sort'] = $value['sort'];
				$list[$key]['ser_name'] = $value['ser_name'];
				$list[$key]['position'] = $value['position'];
				$list[$key]['kbox'] = $value['kbox'];

				$list[$key]['srvtime'] = $value['srvtime'];
			}
			
		}elseif ($op == 'delete'){
			pdo_delete("meifa_project",array('id' => $_GPC['id']));
			message(" 删除成功",referer(),'success');
		}

		include $this->template('project');
	}

	public function doWebOrders(){
		global $_GPC,$_W;
		load()->func('tpl');
		$orders = pdo_fetchAll("SELECT * FROM".tablename('meifa_reservation')." WHERE weid='{$_W['weid']}' order by id desc");
		$total = count($orders);
		if ($_GPC['op'] == 'delete') {
			pdo_delete("meifa_reservation",array('id' => $_GPC['id']));
			message('删除成功',referer(),'success');
		}
		include $this->template('orders');
	}
	public function doWebDetail(){
		global $_GPC,$_W;
		load()->func('tpl');
		$userinfo = pdo_fetch("SELECT * FROM".tablename('meifa_reservation')." WHERE id='{$_GPC['id']}'");
		if ($_W['ispost']) {
			$data = array(
				'remate' => intval($_GPC['remate']),
				'kfinfo' => $_GPC['kfinfo'],
			);
			pdo_update('meifa_reservation',$data,array('id' => $_GPC['id']));
			message('修改成功',referer(),'success');
		}
		include $this->template('detail');
	}

	public function doMobileIndex(){
		$this->doMobileList();
	}
	public function doMobileprofession(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);
		$sql="SELECT * FROM ".tablename('meifa_project')." WHERE isshow=1 AND id=:projid ";

		$info=pdo_fetch($sql,array(':projid'=>$id));

		$title = $info['ser_name'].' - 技师介绍';
		include $this->template('profession');
	}	
	public function doMobileList(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);
		$projects = pdo_fetchAll("SELECT * FROM".tablename('meifa_project')." WHERE isshow=1 order by sort desc");
		
		$hslists=unserialize($item['hs_pic']);		
		include $this->template('list');
	}
	public function doMobileReservation(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);
		$datetime=$_GPC['datetime'];
		$sql="SELECT * FROM ".tablename('meifa_project')." WHERE isshow=1 AND weid='{$_W['weid']}' AND id='{$_GPC['id']}'";		
		$project = pdo_fetch($sql);	

		if(empty($datetime)){
			$timelist=json_decode($project['srvtime'],TRUE);

			$havs=pdo_fetchall("SELECT restime,count(id) as rescount from ".tablename('meifa_reservation')." WHERE weid='{$_W['weid']}' and remate<>'2' and project_id=:projid GROUP BY restime",array(':projid'=>$project['id']),'restime');

			$dates=array();
			$now=new DateTime();
			while(count($dates)<7){
				$now->modify('+1 day');
				if(in_array($now->format('w'),$timelist['weekset'])){
					$dates[]=$now->format('Y-m-d');
				}
			}
			include $this->template('timelist');
		}else{
			$srvtime=base64_decode($datetime);
			$member=mc_fetch($_W['member']['uid'],array('realname','gender','mobile'));
			include $this->template('reservation');
		}
	}
	public function doMobileyysave(){
		global $_GPC,$_W;

		if ($_W['ispost']) {
			$data = array(
				'truename'   => $_GPC['truename'],
				'mobile'     => $_GPC['mobile'],
				'ser_name'   => $_GPC['ser_name'],
				'createtime' => TIMESTAMP,
				'remate'     => '0',
				'info'       => $_GPC['info'],
				'openid'     => $_W['fans']['from_user'],
				'weid'       => $_W['weid'],
				'reid'       => $_GPC['reid'],
				'project_id' => intval($_GPC['project_id']),
				'position' => intval($_GPC['position']),
				'sex'        => $_GPC['sex'],
				'restime'    => $_GPC['restime'],				
			);
			$project = pdo_fetch("SELECT * FROM".tablename('meifa_project')."WHERE id=:reid",array(':reid'=>$_GPC['reid']));
			$recount = pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('meifa_reservation')." WHERE weid='{$_W['weid']}' and remate<>'2' and project_id=:projid AND restime=:restime",array(':projid'=>$project['id'],':restime'=>$data['restime']));

			$total=0;
			$timelis='';
			$timelist=json_decode($project['srvtime'],TRUE);
			$timelit=explode(' ',$_GPC['restime']);
			foreach($timelist['times'] as $time){
				$timelis=$time['start'].'-'.$time['end'];
				if($timelit[1]==$timelis){
					$total=(int)$time['number'];
				}
			}

			if ($recount>=$total) {
				$url = $this->createMobileUrl('list');
				echo json_encode(array('errno'=>3,'msg'=>"非常抱歉,该技师 ".$timelis." 时段预约已满,请您预约其它时段.",'url'=>$url));
				exit;
			}
			pdo_insert('meifa_reservation',$data);
			$id = pdo_insertid();
			if ($id) {
				 $url = $this->createMobileUrl('mylist');
				 $arr=array('errno'=>1,'url'=>$url);
				  echo json_encode($arr);exit;
			}else{
				 $arr=array('errno'=>2);
           		 echo json_encode($arr);exit;
			}
		}
	}
	public function doMobileMylist(){
		global $_GPC,$_W;

		$rebs = pdo_fetchAll("SELECT * FROM".tablename('meifa_reservation')." WHERE  isdel=0 AND openid='{$_W['fans']['from_user']}'");
		if ($_GPC['op'] == 'delete') {
			pdo_update('meifa_reservation',array('isdel'=>1),array('id' => $_GPC['id']));
			//pdo_delete("meifa_reservation",array('id' => $_GPC['id']));
			message('删除成功',referer(),'success');
		}

		include $this->template('mylist');
	}

	
	public  function  doMobileAjaxdelete()
	{
		global $_GPC;
		$delurl = $_GPC['pic'];
		if(file_delete($delurl))
		{echo 1;}
		else 
		{echo 0;}
	}	
}