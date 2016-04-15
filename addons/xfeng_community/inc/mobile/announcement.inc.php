<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端公告页面
 */
	global $_GPC,$_W;			
	$op = !empty($_GPC['op'])?$_GPC['op']:'list';
	$id = intval($_GPC['id']);
	$member = $this->changemember();
	// print_r($member);exit();
	$region = $this->mreg();
	if($op == 'list'){
		if ($_W['isajax']) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 6;
			$sql = "select * from ".tablename("xcommunity_announcement")." where weid='{$_W['weid']}' order by id desc LIMIT ".($pindex - 1) * $psize.','.$psize;
			$row  = pdo_fetchall($sql);
			$list = array();
			foreach ($row as $key => $value) {
				if ($value['regionid'] != 'N;') {
					$regions = unserialize($value['regionid']);
					if (@in_array($member['regionid'], $regions)) {
						$r = pdo_fetch("SELECT * FROM".tablename('xcommunity_reading_member')."WHERE aid=:aid AND openid=:openid",array(':aid' => $value['id'],':openid' => $_W['fans']['from_user']));
						if ($r) {
							$list[$key]['rstatus'] = 1;
						}
						$list[$key]['id'] = $value['id'];
						$list[$key]['createtime'] = $value['createtime'];
						$list[$key]['title'] = $value['title'];
						$list[$key]['datetime'] = $value['datetime'];
						$list[$key]['location'] = $value['location'];
						$list[$key]['reason'] = $value['reason'];
						$list[$key]['remark'] = $value['remark'];
					}
				}
				
			}
			// print_r($list);exit();
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_announcement')."WHERE weid='{$_W['weid']}'");
			
			$content = array();
			foreach ($list as $key => $value) {
				$r = pdo_fetch("SELECT * FROM".tablename('xcommunity_reading_member')."WHERE aid=:aid AND openid=:openid",array(':aid' => $value['id'],':openid' => $_W['fans']['from_user']));
				if ($r) {
					$rstatus = 1;
				}else{
					$rstatus = 0;
				}
					$dat = date('Y-m',$value['createtime']);

					$d = date('d',$value['createtime']);
					$url = $this->createMobileUrl('announcement',array('op' => 'detail','id' => $value['id']));
					$data = '';
					$data = "<li>
				                <div class='list_div'>
				                    <div class='list_l'>
				                        <div class='gg_time gg_noread'>
				                            <div class='day'>
				                                <span class='day_b'>".$d."</span><span class='day_s'>日</span>
				                            </div>
				                            <div class='year'>".$dat."</div>
				                        </div>
				                    </div>
				                    <div class='list_r'>
				                        <a href=".$url.">
				                            <h3>".$value['title']."</h3>
				                            <p>通知时间:".$value['datetime'].",通知范围:".$value['location'].",通知原因:".$value['reason'].",通知备注:".$value['remark']."</p>
				                            <dl>
				                            </dl>
				                        </a>
				                    </div>
				                </div>";

			                    if($rstatus == 1){
			                    	$data .="
			                    	<div class='list_div'>
			                   			 <div class='list_bl'>
			                   			 <span class='ggok_read'>已读</span>
			                   			 <a href='".$url."'><span  class='read_all'>阅读详情</span></a>
			                   			 </div>
			                   			 <div class='list_br'>
			                   			 <span class='ggok'></span>
			                   			 </div>
			                   			 </div>
			            				</li>";
			                    }else{
			                    	$data .="
			                    	<div class='list_div'>
			                   			 <div class='list_bl'>
			                   			 <span class='ggno_read'>未读</span>
			                   			 <a href='".$url."'><span  class='read_all'>阅读详情</span></a>
			                   			 </div>
			                   			 <div class='list_br'>
			                   			 <span class='ggno'></span>
			                   			 </div>
			                   			 </div>
			            				</li>";
			            		}
				$content[]['html'] = $data;
			}
			$r = array(
	        		'allhtml' => $content,
	        		'page_count' => $total,
	        		
	        	);
	       print_r(json_encode($r));exit();
		}
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/announcement/list');exit();
		}
	}elseif($op =='detail'){
		$item  = pdo_fetch("select * from ".tablename("xcommunity_announcement")."where weid='{$_W['weid']}' and id =:id",array(':id' => $id));	
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/announcement/detail');exit();
		}
	}elseif ($op == 'ajax') {

			$r = pdo_fetch("SELECT * FROM".tablename('xcommunity_reading_member')."WHERE aid=:aid AND openid=:openid",array(':aid' => $id,':openid' => $_W['fans']['from_user']));
			if (empty($r)) {
				$data = array(
						'uniacid' => $_W['uniacid'],
						'aid' => $id,
						'openid' => $_W['fans']['from_user'],
						'status' => 1,
					);
				$result = pdo_insert('xcommunity_reading_member',$data);
				if ($result) {
					echo json_encode(array('s' => 1));exit();
				}
			}

	}
	