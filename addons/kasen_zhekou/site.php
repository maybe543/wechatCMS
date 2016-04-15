<?php
/**
 * 微折扣模块微站定义
 *
 * @author Kasen
 * @url http://www.ka-sen.com
 */
defined('IN_IA') or exit('Access Denied');

class Kasen_zhekouModuleSite extends WeModuleSite {
	
	public function doWebAddType() {
		//这个操作被定义用来呈现 管理中心导航菜单
			global $_W,$_GPC;
			load()->func('tpl');
			if(checksubmit()){
				//$content['id'] = null;
				$content['name'] = $_GPC['title'];
				$content['desc'] = $_GPC['desc'];
				$content['code'] = $_GPC['code'];
				$content['images'] = $_GPC['images'];
				$content['link'] = $_GPC['link'];				
				$res = pdo_insert('ks_yhq',$content); 
				message('添加成功！',$this->createWebUrl('AddType',array()),'success');
			}
			
			include $this->template('addtype');
			 
	}
	public function doWebType() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		load()->func('tpl');
		$sql = 'SELECT * FROM ' . tablename('ks_yhq');
		$accounts = pdo_fetchall($sql);
		if(checksubmit()){
			$pid = intval($_GPC['pid']);
			$datarss = pdo_delete('ks_yhq', array('pid' => $pid));
			if($datarss){
				//ok
				message('删除成功！',$this->createWebUrl('Type',array()),'success');
			}else{				 
				//message('删除失败！','','error');
			}			 
		}else{
			//echo $delete.'输入有误';
		}
		include $this->template('type');
	}
	public function doWebAdmin() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		load()->func('tpl');
		$sql = "SELECT * FROM ".tablename('ks_yhq_code')." ORDER BY `id` DESC";		
		$accounts = pdo_fetchall($sql);
		if(checksubmit()){
			if($_GPC['steta']=='0'){
				$id = $_GPC['checks']; 
				foreach($id as $v){
				pdo_run("UPDATE  ".tablename('ks_yhq_code')." SET  `use` =  '1' WHERE  `id` =".$v.";");
				}
				message('设置成功！',$this->createWebUrl('Admin',array()),'success'); 
			}
			else if($_GPC['steta']=='1'){
				$id = $_GPC['checks']; 
				foreach($id as $v){
				pdo_run("UPDATE  ".tablename('ks_yhq_code')." SET  `void` =  '1' WHERE  `id` =".$v.";");
				}
				message('设置成功！',$this->createWebUrl('Admin',array()),'success'); 
			}
			else if($_GPC['steta']=='2'){
				$id = $_GPC['checks']; 
				foreach($id as $v){
				$datarss = pdo_delete('ks_yhq_code', array('id' => $v));
				} 
				message('设置成功！',$this->createWebUrl('Admin',array()),'success'); 
			}
			else if($_GPC['steta']=='3'){
				$id = $_GPC['checks']; 
				foreach($id as $v){
				pdo_run("UPDATE  ".tablename('ks_yhq_code')." SET  `send` =  '1' WHERE  `id` =".$v.";");
				}
				message('设置成功！',$this->createWebUrl('Admin',array()),'success'); 
			}
			else if($_GPC['steta']=='4'){
				$id = $_GPC['checks']; 
				foreach($id as $v){
				pdo_run("UPDATE  ".tablename('ks_yhq_code')." SET  `use` =  '0' WHERE  `id` =".$v.";");
				}
				message('设置成功！',$this->createWebUrl('Admin',array()),'success'); 
			}
			else if($_GPC['steta']=='5'){
				$id = $_GPC['checks']; 
				foreach($id as $v){
				pdo_run("UPDATE  ".tablename('ks_yhq_code')." SET  `void` =  '0' WHERE  `id` =".$v.";");
				}
				message('设置成功！',$this->createWebUrl('Admin',array()),'success'); 
			}
			else if($_GPC['steta']=='6'){
				$id = $_GPC['checks']; 
				foreach($id as $v){
				pdo_run("UPDATE  ".tablename('ks_yhq_code')." SET  `send` =  '0' WHERE  `id` =".$v.";");
				}
				message('设置成功！',$this->createWebUrl('Admin',array()),'success'); 
			}
			else{
				message('无操作！',$this->createWebUrl('Admin',array()),'error'); 
			}
		}
		include $this->template('admin');
	}
	public function doWebAdd() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		load()->func('tpl');
		$sql = "SELECT * FROM" .tablename('ks_yhq');
		$accounts = pdo_fetchall($sql);
		if(checksubmit()){
			//添加
			$pid = $_GPC['pid']; 
			$content = $_GPC['contentt']; 
			$line= explode("\r\n",$content);			 
			foreach ($line as $v) {
				pdo_run("INSERT INTO ".tablename('ks_yhq_code')." (`id`, `code`, `pid`,`use`,`void`,`send`) VALUES (NULL, '$v',$pid,0,0,0)");				 
			 }
			message('添加成功！',$this->createWebUrl('Add',array()),'success'); 
			}else{
				//message('添加失败！','','error');
		}
		 include $this->template('add');
	}
	public function doWebUse() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		load()->func('tpl');
		if(checksubmit()){
			if($_GPC['state']==0){
				$code = $_GPC['code'];
				$sql = "SELECT * FROM ".tablename('ks_yhq_code')." where `code` = '".$code."'";		
				$accounts = pdo_fetch($sql);
			}
			else if($_GPC['state']==1){
				$code = $_GPC['code'];
				pdo_run("UPDATE  ".tablename('ks_yhq_code')."  SET  `use` =  '1' WHERE  `code` ='".$code."'");
				message('使用成功！',$this->createWebUrl('Use',array()),'success'); 
			}
		}
		include $this->template('use');
	}
	public function doWebProduce() {
		//这个操作被定义用来呈现 管理中心导航菜单
			global $_W,$_GPC;
			function getRandStr($length) {
			$str = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
			$randString = ''; 
			$len = strlen($str)-1; 
			for($i = 0;$i < $length;$i ++){ $num = mt_rand(0, $len); $randString .= $str[$num]; 
			} 
			return $randString ; }
			if(checksubmit()){
				$id = $_GPC['num'];
				$line =  $_GPC['line'];
				for ($x=0; $x<=$id; $x++) {
				  $skey[$x] = getRandStr($line)."<br>";
				} 
			}
			else{}
			include $this->template('produce');
			 
	}
	

}




























