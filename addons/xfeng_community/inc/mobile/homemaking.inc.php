<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端家政服务
 */

		global $_GPC,$_W;
		$op = !empty($_GPC['op']) ? $_GPC['op']:'add' ;
		$id = $_GPC['id'];
		//查对应的小区编号
		$member = $this->changemember();
		$region = $this->mreg();
		
		if($op == 'add'){
			load()->model('mc');
			$m = mc_fetch($_W['member']['uid'],array('realname','mobile','address'));
			//查家政子类 家政主类ID=1
			$categories = pdo_fetchall("SELECT * FROM".tablename('xcommunity_category')."WHERE weid='{$_W['weid']}' AND type=1");
			if(!empty($id)){
    			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_homemaking')."WHERE id=:id",array(':id' => $id));
    		}
    		if($_W['isajax']){
    			$data = array(
					'weid'                 => $_W['weid'],
					'openid'               => $_W['fans']['from_user'],
					'regionid'             => $member['regionid'],
					'category' => $_GPC['category'],
					'servicetime'          => $_GPC['servicetime'],
					'realname'          => $_GPC['realname'],
					'mobile'               => $_GPC['mobile'],
					'address'	=> $_GPC['address'],
					'createtime'           => TIMESTAMP,
					'status'               => 0,
					'content' => $_GPC['content'],
				);
				$cate = pdo_fetch("SELECT * FROM".tablename('xcommunity_category')."WHERE id=:id AND weid=:weid",array(':id' => $data['category'],':weid' => $_W['weid']));
    			if (empty($id)) {
    				$r = pdo_insert('xcommunity_homemaking',$data);
    			}else{
    				$r = pdo_update('xcommunity_homemaking',$data,array('id' => $id));
    			}
    			//微信通知
			$notice = pdo_fetchall("SELECT * FROM".tablename('xcommunity_wechat_notice')."WHERE uniacid=:uniacid",array('uniacid' => $_W['uniacid']));
			// $list = array();
			foreach ($notice as $key => $value) {
				if ($value['type'] == 1 || $value['type'] == 3) {
					$regions = unserialize($value['regionid']);
					if (@in_array($member['regionid'], $regions)) {
						// $list = $notice;
						if ($value['homemaking_status'] == 2) {

							//模板消息通知
							$openid = $value['fansopenid'];
							$url ='';
							$tpl = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_tplid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
							$template_id = $tpl['homemaking_tplid'];
							$createtime = date('Y-m-d H:i:s', $data['servicetime']);
							$content = array(
									'first' => array(
											'value' => '您好，有一条新的家政服务预约。',
										),
									'keyword1' => array(
											'value' => $_GPC['realname']
										),
									'keyword2' => array(
											'value' => $_GPC['mobile']
										),
									'keyword3' => array(
											'value' => $createtime
										),
									'keyword4' => array(
											'value' => $cate['name'],
										),
									'remark'    => array(
										'value' => '请尽快联系客户。',
									),	
								);
							$this->sendtpl($openid,$url,$template_id,$content);
						}
					}
				}
				
			}
    			if ($r) {
					$result = array(
								'status' => 1,
							);
						echo json_encode($result);exit();
				}	
    		}
    		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($styleid) {
					include $this->template('style/style'.$styleid.'/homemaking/add');exit();
			}
		}elseif ($op == 'my') {
			if ($_W['isajax']) {
				$pindex = max(1, intval($_GPC['page']));
				$psize = 2;

				$list = pdo_fetchall('SELECT f.*,s.name as name FROM'.tablename('xcommunity_homemaking')."as f left join".tablename('xcommunity_category')."as s on f.category = s.id WHERE f.weid='{$_W['weid']}' AND f.regionid='{$member['regionid']}' AND f.openid='{$_W['fans']['from_user']}' order by f.id desc LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
				$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_homemaking')."as f left join".tablename('xcommunity_category')."as s on f.category = s.id WHERE f.weid='{$_W['weid']}' AND f.regionid='{$member['regionid']}' AND f.openid='{$_W['fans']['from_user']}' order by f.id desc",$params);

				$data = array();
				foreach ($list as $key => $value) {
					$data[]['html'] = "<a class='weui_cell' href='#'>
							                <div class='weui_cell_bd weui_cell_primary'>
							                    <p>
							                        
							                        <p style='background-color:#48b54e;color:white;width:50px;height:20px; border-radius: 5px;font-size:12px;text-align:center;line-height:20px;float:left'>【".$value['name']."】</p>
							                       

							                       <p style='font-size:12px;line-height:20px;'>&nbsp;&nbsp;".$value['content']."</p>
							                    </p>
							                    
							                   
							                </div>
							            
							                <div class='weui_cell_ft'>
							                </div>
							            </a>
							            <div class='weui_cell'>
							                <div class='weui_cell_bd weui_cell_primary'>
							                	<p style='font-size:11px;color: #a9a9a9;clear:both;margin-top:5px;''>服务时间：".$value['servicetime']." </p> 
							                </div>";
							               $data[]['html'].="<div class=\"weui_cell_ft del\"  onclick ='delectFun(".$value['id'].")'>删除</div>&nbsp;&nbsp;";
							             if (empty($value['status'])) {
							             	$data[]['html'].="<div class=\"weui_cell_ft rank\" onclick ='confirmFun(".$value['id'].")'>确认完成</div>";
								            
							             }
							$data[]['html'] .="</div>
								            <a style='height:20px;width:100%;background-color: #efeef4;display:block'></a>";
				}
				$r = array(
			    		'allhtml' => $data,
			    		'page_count' => $total,
			    		
			    	);

			   print_r(json_encode($r));exit();
			}
			$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($styleid) {
					include $this->template('style/style'.$styleid.'/homemaking/my');exit();
			}
		}elseif ($op == 'delete') {
			$id = intval($_GPC['id']);
			if (pdo_delete('xcommunity_homemaking',array('id'=>$id))) {
				$result['state'] = 0;
				message($result, '', 'ajax');
			}
		}elseif ($op == 'finish') {
			$id = intval($_GPC['id']);
			if ($id) {
				$r = pdo_update('xcommunity_homemaking',array('status' => 1),array('id' => $id));
				if ($r) {
					echo json_encode(array('result' => 1));
				}
			}
		}elseif ($op == 'ajax') {
			$cid = intval($_GPC['cid']);
			if ($cid) {
				$cate = pdo_fetch("SELECT * FROM".tablename('xcommunity_category')."WHERE id=:id",array(':id' => $cid));
				print_r(json_encode($cate));exit();
			}
		}















