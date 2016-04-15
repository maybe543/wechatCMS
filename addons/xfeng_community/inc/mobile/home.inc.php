<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端首页
 */
	global $_GPC,$_W;	
	$userinfo = mc_oauth_userinfo();
	$member = $this->changemember();
	$region = $this->mreg();
	$list1 = pdo_fetchall("SELECT * FROM".tablename('xcommunity_slide')."WHERE weid=:uniacid",array(':uniacid' => $_W['uniacid']));
	if ($list1) {
		$slides = array();
		foreach ($list1 as $key => $value) {
			$regions = unserialize($value['regionid']);
			if (@in_array($member['regionid'], $regions)) {
				$slides[$key]['id'] = $value['id'];
				$slides[$key]['title'] = $value['title'];
				$slides[$key]['thumb'] = $value['thumb'];
				$slides[$key]['url'] = $value['url'];
			}
		}
	}
	$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
	if ($styleid == 1) {
		//模板1 
		$menu = pdo_fetch("SELECT * FROM".tablename('xcommunity_nav')."WHERE uniacid=:uniacid AND isshow = 1 AND status = 1 AND pcate = 0 limit 1",array(':uniacid' => $_W['uniacid']));
		// print_r($menu);exit();
		//二级菜单首页推荐
		$navs = pdo_fetchall("SELECT * FROM".tablename('xcommunity_nav')."WHERE  uniacid='{$_W['uniacid']}' AND isshow =1 AND status = 1 AND pcate != 0 order by displayorder asc ");
	}elseif ($styleid == 2) {
		//模板2
		$list1 = pdo_fetchall("SELECT * FROM".tablename('xcommunity_nav')."WHERE  uniacid='{$_W['uniacid']}' AND pcate = 0 AND status = 1 order by displayorder asc ");
		
			$list = array();
			foreach ($list1 as $key => $value) {
				$regions = unserialize($value['regionid']);
				if ($value['enable'] == 1) {
					$list[$key]['title'] = $value['title'];
				    $list[$key]['id'] = $value['id'];
				    

				}else{
					if (@in_array($member['regionid'], $regions)) {
						$list[$key]['title'] = $value['title'];
				    	$list[$key]['id'] = $value['id'];
					}
					
				}
					
			}
		$children = array();
		foreach ($list as $k => $value) {
			$sql  = "select *from".tablename("xcommunity_nav")."where uniacid='{$_W['uniacid']}' and  pcate='{$value['id']}' AND status = 1 AND isshow = 1 order by displayorder asc";
			$li1 = pdo_fetchall($sql);
				$li = array();
				foreach ($li1 as $key => $val) {
					$regions = unserialize($val['regionid']);
					if ($val['enable'] == 1) {
						$li[$key]['title'] = $val['title'];
						$li[$key]['id'] = $val['id'];
						$li[$key]['icon'] = $val['icon'];
						$li[$key]['bgcolor'] = $val['bgcolor'];
						$li[$key]['url'] = $val['url'];
						$li[$key]['thumb'] = $val['thumb'];
					}else{
						if (@in_array($member['regionid'], $regions)) {
							$li[$key]['title'] = $val['title'];
							$li[$key]['id'] = $val['id'];
							$li[$key]['icon'] = $val['icon'];
							$li[$key]['bgcolor'] = $val['bgcolor'];
							$li[$key]['url'] = $val['url'];
							$li[$key]['thumb'] = $val['thumb'];
						}
						
					}
					
				}

			$children[$value['id']] = $li;		
		}
	




	}
	
	
	
	if ($_W['isajax']) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 100;
		
			
			$list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_goods')."WHERE weid=:weid AND isrecommand =1 AND status = 1 AND type = 1 LIMIT ".($pindex - 1) * $psize.','.$psize,array(':weid' => $_W['uniacid']));
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_goods')."WHERE weid=:weid AND isrecommand =1 AND status = 1 AND type = 1 ",array(':weid' => $_W['uniacid']));


			$data = array();
			foreach ($list as $key => $value) {
				$url = $this->createMobileUrl('shopping',array('op' => 'detail','id' => $value['id']));
				$thumb = tomedia($value['thumb']);
				$datetime = date('Y-m-d H:i',$value['createtime']);
				
				$data[]['html'] .="
					<li>
				        <a href='".$url."' class='p-img'><img src='".$thumb."'>
				            <h3 class='p-name'>".$value['title']."<span>&nbsp;&nbsp;&nbsp;".$value['unit']."</span></h3></a>
				        <div class='channer_media' onclick='#'>
				            <div class='p-price'><span class='p-price-now'><b>¥ ".$value['marketprice']."</b></span>
				            <span id='market_price' class='p-price-cost'><b style='font-size:12px;'>".$value['productprice']."</b></span>
				            <i></i>
				            </div>
				        </div>
				        <div class='tag'></div>
				        <div class='tag-tltle'>热卖</div>
				    </li>

					";
			}
			$r = array(
		    		'allhtml' => $data,
		    		'page_count' => $total,
		    		
		    	);

		   print_r(json_encode($r));exit();
		}
		
	//获取购物车数量
	$carttotal = $this->getCartTotal();
	
	if ($styleid) {
		include $this->template('style/style'.$styleid.'/home/home');exit();
	}

















