<?php
/**
 * 女神来了模块定义
 *
 */
defined('IN_IA') or exit('Access Denied');

		$item_per_page = 10;  
		$page_number = $_GPC['pagesnum'];    
		if(!is_numeric($page_number)){  
   		 header('HTTP/1.1 500 Invalid page number!');  
    		exit();  
		}
      	
		$position = ($page_number * $item_per_page);  
		$where = '';
		if (!empty($_GPC['keyword'])) {
				$keyword = $_GPC['keyword'];
				if (is_numeric($keyword)) 
					$where .= " AND uid = '".$keyword."'";
				else 				
					$where .= " AND (nickname LIKE '%{$keyword}%' OR realname LIKE '%{$keyword}%' )";
			
		}
		
		$where .= " AND status = '1'";


		if (!empty($_GPC['tagid'])) {
			$where .= " AND tagid = '".$_GPC['tagid']."'";
		}
		if ($reply['indexorder'] == '1') {
			$where .= " ORDER BY `istuijian` DESC, `createtime` DESC";
		}elseif ($reply['indexorder'] == '11') {
			$where .= " ORDER BY `istuijian` DESC, `createtime` ASC";
		}elseif ($reply['indexorder'] == '2') {
			$where .= " ORDER BY `istuijian` DESC, `uid` DESC, `id` DESC";
		}elseif ($reply['indexorder'] == '22') {
			$where .= " ORDER BY `istuijian` DESC, `uid` ASC, `id` ASC";
		}elseif ($reply['indexorder'] == '3') {
			$where .= " ORDER BY `istuijian` DESC, `photosnum` + `xnphotosnum` DESC";
		}elseif ($reply['indexorder'] == '33') {
			$where .= " ORDER BY `istuijian` DESC, `photosnum` + `xnphotosnum` ASC";
		}elseif ($reply['indexorder'] == '4') {
			$where .= " ORDER BY `istuijian` DESC, `hits` + `xnhits` DESC";
		}elseif ($reply['indexorder'] == '44') {
			$where .= " ORDER BY `istuijian` DESC, `hits` + `xnhits` ASC";
		}elseif ($reply['indexorder'] == '5') {
			$where .= " ORDER BY `istuijian` DESC, `vedio` DESC, `music` DESC, `id` DESC";
		}else {
			$where .= " ORDER BY `istuijian` DESC, `id` DESC";
		}
		$userlist = pdo_fetchall('SELECT * FROM '.tablename($this->table_users).' WHERE uniacid= :uniacid and rid = :rid AND istuijian <> 1 '.$where.'  LIMIT ' . $position . ',' . $item_per_page, array(':uniacid' => $uniacid,':rid' => $rid) );
		
		$tjlist = pdo_fetchall('SELECT * FROM '.tablename($this->table_users).' WHERE uniacid= :uniacid and rid = :rid AND istuijian = 1 '.$where.'  LIMIT ' . $position . ',' . $item_per_page, array(':uniacid' => $uniacid,':rid' => $rid) );
		
		$str=json_encode($userlist);
    echo $str;
		//print_r($userlist);
		//echo json_encode($userlist);
		//die(json_encode($userlist));
		exit;