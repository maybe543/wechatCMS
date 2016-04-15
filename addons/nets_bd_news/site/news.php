<?php
global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if(empty($_GPC['op'])){
			$_GPC['op']=$operation;
		}
		$domain=str_replace("www.", "", $_W['siteroot']);
		$domain=str_replace("http:", "", $domain);
		$domain=str_replace("/", "", $domain);
		$uid=$_W["uid"];
		$uniacid=$_W['uniaccount']['uniacid'];
		if ($operation == 'add') {
			$category=pdo_fetchall("SELECT * FROM ".tablename('netsbd_news_category')." WHERE  uniacid=".$uniacid);
			load()->func('tpl');
			if(!empty($_GPC['id'])){
				$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_news')." WHERE id=".$_GPC['id']);
			}
			include $this->template('news');
		}elseif($operation=='display'){
			
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$start = ($pindex - 1) * $psize;
			//云端采集
			if(!empty($_GPC["cloud"])){
				load()->func('communication');
				//新闻入库
				if(!empty($_GPC["importids"])){
					$importResult=ihttp_get('http://182.254.152.121:9009/api.php/Index/getcloud_news_all?id='.$_GPC["importids"].'&domain='.$domain."&uid=".$uniacid);
					
					//print("<br/>psize:".$psize);
					//print("<br/>pindex:".$pindex);
					$importRecord=$importResult['content'];
					if(empty($importRecord)){
						message('系统繁忙请稍后在试！', $this->createWebUrl('Hxsnews', array('op' => 'display')), 'error');
					}
					$importRecord=json_decode($importRecord,true);
					foreach ($importRecord as $value) {
							$importRecord=$value;
							break;
					}
					if(!empty($importRecord)){
						foreach($importRecord as $r){
							//print("<br/>ID::".$r["id"]);
							$temp["uid"]=$uid;
							$temp["uniacid"]=$uniacid;
							$temp["cid"]=0;
							$temp["title"]=$r['title'];
							$temp["brief"]=$r['brief'];
							$temp["picture"]=$r['picture'];
							$temp["content"]=$r['content'];
							$temp["source"]=$r['source'];
							$temp["source_url"]=$r['source_url'];
							$temp["author"]=$r['author'];
							$temp["tag"]=$r['tag'];
							$temp["click_num"]=$r['click_num'];
							$temp["like_num"]=rand(100,9999);
							$temp["comment_num"]=rand(100,9999);
							$temp["share_num"]=rand(100,9999);
							$temp["createtime"]=$r['createtime'];
							$i=pdo_insert("netsbd_news",$temp);
						}
					}
				}
				//显示数据
				$tagResult=ihttp_get('http://182.254.152.121:9009/api.php/Index/gettags');
				$tagRecord=$tagResult['content'];
				//var_dump($tagRecord);
				if(empty($tagRecord)){
					message('系统繁忙请稍后在试！', $this->createWebUrl('Hxsnews', array('op' => 'display')), 'error');
				}
				$tagRecord=json_decode($tagRecord,true);
				foreach ($tagRecord as $value) {
						$tagRecord=$value;
						break;
				}
				$keyword="";
				$tag="";
				//取post传值
				if(!empty($_GPC['post'])){
					if(!empty($_GPC['tag'])){
						$tag=$_GPC['tag'];
						isetcookie ('tag_c', $_GPC['tag'], 3600); 
					}else{
						isetcookie ('tag_c', "", -3600); 
					}
					if(!empty($_GPC['keyword'])){
						$keyword=$_GPC['keyword'];
						isetcookie ('keyword_c', $_GPC['keyword'], 3600); 
					}else{
						isetcookie ('keyword_c', "", -3600); 
					}
				}else{//取cookie
					if(!empty($_GPC['tag_c'])){
						$tag=$_GPC['tag_c'];
					}
					if(!empty($_GPC['keyword_c'])){
						$keyword=$_GPC['keyword_c'];
					}
				}
				if($tag=="-1"){$tag="";}
				$url='http://182.254.152.121:9009/api.php/Index/getcloud_news?keywords='.$keyword.'&tag='.$tag.'&pageindex='.$pindex.'&size='.$psize.'&domain='.$domain."&uid=".$uniacid;
				$result = ihttp_get($url);
				//print("<br/>url:".$url);
				//exit;
				//print("<br/>pindex:".$pindex);
				$record=$result['content'];
				if(empty($record)){
					message('系统繁忙请稍后在试！', $this->createWebUrl('Hxsnews', array('op' => 'display')), 'error');
				}
				//var_dump($record);
				$record=json_decode($record,true);
				$total=$record['TotalCount'];
				$code=$record['Code'];
				if($code=="-1"){
					message('你已经安装了盗版并读新闻，请联系官方QQ群：424163499购买完成支付，否则保障不了您的数据安全，请三思！', $this->createWebUrl('Hxsnews', array('op' => 'display')), 'error');
				}
				$pager = pagination($total, $pindex, $psize);
				foreach ($record as $value) {
						$record=$value;
						break;
				}
			}//本地新闻库
			else{
				//新闻归类
				if(!empty($_GPC["importids"]) && !empty($_GPC["category"])){
					$ids=$_GPC["importids"];
					$cid=$_GPC["category"];
					$update_sql="UPDATE ims_netsbd_news SET cid=".$cid." WHERE id IN(".$ids.")";
					pdo_query($update_sql);
				}
				//新闻推荐
				if(!empty($_GPC["importids"]) && !empty($_GPC["ishome"])){
					$ids=$_GPC["importids"];
					$update_sql="UPDATE ims_netsbd_news SET ishome=1 WHERE id IN(".$ids.")";
					pdo_query($update_sql);
				}
				//取出新闻总数
				$tag="";
				$tag1="";
				$where="";
				
				//取post传值
				if(!empty($_GPC['post'])){
					if(!empty($_GPC['tag'])){
						if($_GPC['tag']=="-1"){
							$where=$where." AND N.cid=0";
						}else{
							$where=$where." AND N.cid=".$_GPC['tag'];
						}
						isetcookie ('tag_c', $_GPC['tag'], 3600); 
					}else{
						isetcookie ('tag_c', "", -3600); 
					}
					if(!empty($_GPC['tag1'])){
						$where=$where." AND N.tag like '%".$_GPC['tag1']."%'";
						isetcookie ('tag1_c', $_GPC['tag1'], 3600); 
					}else{
						isetcookie ('tag1_c', "", -3600); 
					}
					if(!empty($_GPC['keyword'])){
						$where=$where." AND N.title like '%".$_GPC['keyword']."%'";
						isetcookie ('keyword_c', $_GPC['keyword'], 3600); 
					}else{
						isetcookie ('keyword_c', "", -3600); 
					}
				}else{//取cookie
					if(!empty($_GPC['tag_c'])){
						if($_GPC['tag_c']=="-1"){
							$where=$where." AND N.cid=0";
						}else{
							$where=$where." AND N.cid=".max(0, intval($_GPC['tag_c']));
						}	
					}
					if(!empty($_GPC['keyword_c'])){
						$where=$where." AND N.title like '%".$_GPC['keyword_c']."%'";
					}
				}
				// 未归类的新闻标签分组
				$group_tag_sql="SELECT tag FROM ims_netsbd_news WHERE tag IS NOT NULL GROUP BY tag";
				$group_tag=pdo_fetchall($group_tag_sql);
				$tagRecord=pdo_fetchall("SELECT id,`name` AS tag FROM ims_netsbd_news_category where uniacid=".$uniacid);
				$total_sql="select count(*) from ".tablename('netsbd_news')." AS N where cid>=0 AND uniacid=".$uniacid.$where." ORDER BY sort DESC, ishome DESC,ID DESC";
				$total = pdo_fetchcolumn($total_sql);
				$record=pdo_fetchall("SELECT C.Name,N.* FROM ".tablename('netsbd_news')." AS N LEFT JOIN ".tablename('netsbd_news_category')." AS C ON N.cid=C.id WHERE N.cid>=0 AND N.uniacid=".$uniacid.$where."  ORDER BY sort DESC, ishome DESC,ID DESC LIMIT {$start}, {$psize} ");
				
				$pager = pagination($total, $pindex, $psize);
			}
			include $this->template('news');
		}elseif($operation=='showcommon'){
			$id=$_GPC["showcommon"];
			$t=$_GPC["t"];
			$type=1;
			if($t=="like"){
				$typename="赞列表";
				$type=1;
			}
			if($t=="comment"){
				$typename="评论列表";
				$type=2;
			}
			if($t=="click"){
				$typename="点击列表";
			}
			if($t=="share"){
				$typename="分享列表";
				$type=3;
			}
			if($_GPC["del"]!=""){
				$cid=$_GPC["del"];
				pdo_delete("netsbd_news_comment",array("id"=>$cid));
			}
			$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_news')." WHERE uniacid=".$uniacid."  AND id=:id",array(":id"=>$id));
			$comment=pdo_fetchall("SELECT c.*,m.nickname,m.realname,m.avatar FROM ims_netsbd_news_comment AS c LEFT JOIN ims_mc_members AS m ON m.uid=c.uid  WHERE c.newsid=:newsid AND type=:type",array(":newsid"=>$id,":type"=>$type));
			
			include $this->template('news');
		}elseif($operation=='del'){
			$i=pdo_delete("netsbd_news",array("id" => $_GPC['id']));
			if($i>0){
				message('删除成功！', $this->createWebUrl('Hxsnews', array('op' => 'display')), 'success');
			}else{
				message('删除失败，请联系管理员！', $this->createWebUrl('Hxsnews', array('op' => 'display')), 'success');
			}
		}elseif ($operation == 'post') {
				$r["cid"]=$_GPC['cid'];
				$r["title"]=$_GPC['title'];
				$r["brief"]=$_GPC['brief'];
				$r["picture"]=$_GPC['picture'];
				if($_GPC['isshowdetail']){
					$r["isshowdetail"]=1;
				}else{
					$r["isshowdetail"]=0;
				}
				
				$r["content"]=$_GPC['content'];
				$r["source"]=$_GPC['source'];
				$r["source_url"]=$_GPC['source_url'];
				$r["author"]=$_GPC['author'];
				$r["tag"]=$_GPC['tag'];
				$r["like_num"]=$_GPC['like_num'];
				$r["click_num"]=$_GPC['click_num'];
				$r["comment_num"]=$_GPC['comment_num'];
				$r["share_num"]=$_GPC['share_num'];
				$r["ishide"]=$_GPC['ishide'];
				$r["sort"]=$_GPC['sort'];
				if(!empty($_GPC['picture'])){
					$r["picture"]=$_GPC['picture'];
				}else{
					if (!empty($_GPC['autolitpic'])) {
					$match = array();
					$pattern="/<img.*?src=[\'|\"](.*?(?:[\.gif|\.jpg\.png]))[\'|\"].*?[\/]?>/";
					preg_match($pattern, $_GPC['content'], $match);
					if (!empty($match[1])) {
						$r["picture"] = $match[1].$match[2];
					} else {
						preg_match('/(http|https):\/\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $_GPC['content'], $match);
						$r["picture"] = $match[0];
					}
				}
				}
				$r["ishome"]=$_GPC['ishome'];
				$r["createtime"]=TIMESTAMP;
			if(empty($_GPC['id'])){
				$r["uid"]=$uid;
				$r["uniacid"]=$uniacid;
				pdo_insert("netsbd_news",$r);
			}else{
				$r["createtime"]=TIMESTAMP;
				pdo_update("netsbd_news",$r,array('id' => $_GPC['id']));
			}
			message('保存成功！', $this->createWebUrl('Hxsnews', array('op' => 'display')), 'success');
		}
?>