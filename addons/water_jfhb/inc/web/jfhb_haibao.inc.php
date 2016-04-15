<?php 
	global $_W, $_GPC;
  	load()->func('tpl');
  	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
  	$uniacid=$_W["uniacid"];
  	$id=$_GPC['id'];
  	if ($op=='display') {
  		
  		$pindex = max(1, intval($_GPC['page']));	
		$psize= 20;
		$list = pdo_fetchall("SELECT *  from ".tablename('jfhb_haibao')."  where uniacid=$uniacid  order by id desc LIMIT ". ($pindex -1) * $psize . ',' .$psize );
		$total = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename('jfhb_haibao')."  where uniacid=$uniacid ");
		$pager = pagination($total, $pindex, $psize);
  	}

  	if ($op=='post') {
  		if (!empty($id)) {
  		$item = pdo_fetch("SELECT *  from ".tablename('jfhb_haibao')."  where uniacid=$uniacid and id=$id");
  		if (empty($item)) {
				message('抱歉，项目不存在或是已经删除！', '', 'error');
			}
		}
		if (checksubmit('submit')) {
			$data = array(
			    'uniacid' => $_W['uniacid'],
			    'title'=> $_GPC['title'],
			    'hb_img'=> $_GPC['hb_img'],
				'qrleft' => $_GPC['qrleft'],
				'qrtop' => $_GPC['qrtop'],
				'qrwidth' => $_GPC['qrwidth'],
				'qrheight' => $_GPC['qrheight'],
				'avatarleft' => $_GPC['avatarleft'],
				'avatartop' => $_GPC['avatartop'],
				'avatarwidth' => $_GPC['avatarwidth'],
				'avatarheight' => $_GPC['avatarheight'],
				'avatarenable' => $_GPC['avatarenable'],				
				'nametop' => $_GPC['nametop'],
				'nameleft' => $_GPC['nameleft'],
				'namesize' => $_GPC['namesize'],
				'nameenable' => $_GPC['nameenable'],
			    'namecolor' => $_GPC['namecolor'],
				'createtime' => time()
			);
        
			if (!empty($id)) {
			 //  pdo_update('jfhb_qrcode', array('qr_img'=>""), array('haibao_id' => $id));
			   pdo_update('jfhb_haibao', $data, array('id' => $id));
			   message('更新成功！', $this->createWebUrl('jfhb_haibao'), 'success');
			} else {
			   $cz = pdo_fetch("SELECT *  from ".tablename('jfhb_haibao')."  where uniacid=$uniacid");
			   if (empty($cz)){
			   	$data['status']=1;
			  }

			  pdo_insert('jfhb_haibao', $data);
              message('增加成功！', $this->createWebUrl('jfhb_haibao'), 'success');
			}
		  }
		} 
		
	if ($op=='delete') {
		pdo_delete('jfhb_haibao', array('id' => $id));
        message('删除成功！', $this->createWebUrl('jfhb_haibao', array('op' => 'display')), 'success');
	}

	if ($op=='status') {
		pdo_update('jfhb_haibao',  array('status' =>0),array('uniacid' => $_W['uniacid']));
		pdo_update('jfhb_haibao',  array('status' =>1),array('id' => $id));
        message('设置成功！', $this->createWebUrl('jfhb_haibao', array('op' => 'display')), 'success');
	}

  	include $this->template('jfhb_haibao');