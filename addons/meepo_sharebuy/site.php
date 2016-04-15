<?php

defined('IN_IA') or exit('Access Denied');
define('MEEPO','../addons/meepo_sharebuy/template/mobile/style/');
class Meepo_sharebuyModuleSite extends WeModuleSite {
	public function doMobileIndex() {
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$sql = "SELECT * FROM  ".tablename('share_datas')." WHERE weid=:weid order by rand() LIMIT 1";
		$settings  = pdo_fetch($sql,array(':weid'=>$weid));
        if(empty($settings)){
		   message('请先添加分享数据！');
		}
		include $this->template('index');
	}
	public function doMobileshare(){
	    global $_W,$_GPC;
		$weid = $_W['uniacid'];
		if($_W['isajax']){
			$id = intval($_GPC['id']);
		    pdo_query("UPDATE ".tablename('share_datas')." SET num = num + 1 WHERE id = '{$id}' AND weid='{$weid}' ");
		}
	}
	public function doWebSharemanage() {//添加分享内容自定义
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		load()->func('tpl');
		if($operation == 'display'){
					$pindex = max(1, intval($_GPC['page']));
					$psize = 20;
					$condition = '';
					$condition = " weid = :weid";
					if (!empty($_GPC['keyword'])) {//微信昵称
							$condition .= " AND share_title LIKE '%{$_GPC['keyword']}%'";
					}
					if (!empty($_GPC['keyword2'])) {//微信昵称
							$condition .= " AND share_content LIKE '%{$_GPC['keyword2']}%'";
					}
					$paras = array(':weid' => $_W['uniacid']);
					$sql = "SELECT * FROM ".tablename('share_datas')." WHERE $condition ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
					$lists = pdo_fetchall($sql,$paras);
					$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('share_datas')." WHERE weid=:weid",array(':weid'=>$weid));
					$nums = pdo_fetchcolumn("SELECT count(num) FROM ".tablename('share_datas')." WHERE weid=:weid",array(':weid'=>$weid));
				    $pager = pagination($total, $pindex, $psize);
		}elseif($operation == 'post'){
			   if(!empty($_GPC['id'])){
				   $id = intval($_GPC['id']);
				   $list = pdo_fetch("SELECT * FROM ".tablename('share_datas')." WHERE id=:id AND weid=:weid",array(':id'=>$id,':weid'=>$weid));
			   } 
				 if (checksubmit('submit')) {
					if(!empty($_GPC['id'])){
						$id = intval($_GPC['id']);
						$data = array(
						  'share_title'=>$_GPC['share_title'],
						  'share_content'=>$_GPC['share_content'],
							'share_logo'=>$_GPC['share_logo'],
							'share_link'=>$_GPC['share_link'],
                            'share_ztlink'=>$_GPC['share_ztlink'],
					    );
					    pdo_update('share_datas',$data,array('id'=>$id,'weid'=>$weid));
					    message('更新分享数据成功', $this->createWebUrl('sharemanage', array('page' => $_GPC['page'])),'success');
					}else{
						$data = array(
							'weid'=>$weid,
						    'share_title'=>$_GPC['share_title'],
						    'share_content'=>$_GPC['share_content'],
							'share_logo'=>$_GPC['share_logo'],
							'share_link'=>$_GPC['share_link'],
							'share_ztlink'=>$_GPC['share_ztlink'],
						    'createtime'=>time(),
					    );
					    pdo_insert('share_datas',$data);
					    message('新增分享数据成功', $this->createWebUrl('sharemanage', array('page' => $_GPC['page'])),'success');
					}
				 }
		}elseif($operation == 'delete'){
		       if(empty($_GPC['id'])){
				    message('此项不存在或是已经被删除');
				 }else{
					pdo_delete('share_datas',array('id'=>intval($_GPC['id']),'weid'=>$weid));
					message('删除成功', $this->createWebUrl('sharemanage', array('page' => $_GPC['page'])),'success');
				 }
		}else{
		   message('非法操作！');
		}
        include $this->template('sharemanage');
	}

}