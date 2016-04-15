<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */global $_W, $_GPC;
   $operation = in_array ( $_GPC ['op'], array ('default','fabu','mfabu','zfabu','fangxue') ) ? $_GPC ['op'] : 'default';

    if ($operation == 'default') {
	           die ( json_encode ( array (
			         'result' => false,
			         'msg' => '你是傻逼吗'
	                ) ) );
              }			
	if ($operation == 'fabu') {
		
		 load()->func('communication');
		 load()->classs('weixin.account');
		 load()->func('file');
         $accObj= WeixinAccount::create($_W['account']['acid']);
         $access_token = $accObj->fetch_token();
	     $token2 =  $access_token;
		 $photoUrls = explode ( ',', $_GPC ['photoUrls'] );
		 $data = explode ( '|', $_GPC ['json'] );
		 
				if(!empty($photoUrls[0])) {		 
					$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[0];
					$pic_data = ihttp_request($url);
					$path = "images/fmjiaoyu/";
					$picurl0 = $path.random(30) .".jpg";
					file_write($picurl0,$pic_data['content']);
				}
		
				if(!empty($photoUrls[1])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[1];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl1 = $path.random(30) .".jpg";
				file_write($picurl1,$pic_data['content']);
				}

				if(!empty($photoUrls[2])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[2];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl2 = $path.random(30) .".jpg";
				file_write($picurl2,$pic_data['content']);
				}
		
				if(!empty($photoUrls[3])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[3];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl3 = $path.random(30) .".jpg";
				file_write($picurl3,$pic_data['content']);
				}

				if(!empty($photoUrls[4])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[4];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl4 = $path.random(30) .".jpg";
				file_write($picurl4,$pic_data['content']);				
				}

				if(!empty($photoUrls[5])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[5];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl5 = $path.random(30) .".jpg";
				file_write($picurl5,$pic_data['content']);
				}

				if(!empty($photoUrls[6])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[6];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl6 = $path.random(30) .".jpg";
				file_write($picurl6,$pic_data['content']);
				}

				if(!empty($photoUrls[7])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[7];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl7 = $path.random(30) .".jpg";
				file_write($picurl7,$pic_data['content']);
				}

				if(!empty($photoUrls[8])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[8];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl8 = $path.random(30) .".jpg";
				file_write($picurl8,$pic_data['content']);

				}
				
		 $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid'])); 
		
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			
			if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求,请刷新页面！' 
		               ) ) );
					   
		    }else{
					 				
				$schoolid = $_GPC['schoolid'];
			
				$title = $_GPC['title'];
			
				$weid = $_GPC['weid'];
			
				$content = $_GPC['content'];
			
				$tid = $_GPC['tid'];
			
				$bj_id = $_GPC['bj_id'];
							
				$tname = $_GPC['tname'];
				
				$temp = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'tid' => $tid,
					'tname' => $tname,
					'title' => $title,
					'content' => $content,
					'createtime' => time(),
					'bj_id' => $bj_id,
					'type'=>1,
				);
				
				$picstr = array(
						'p1' => $picurl0,
						'p2' => $picurl1,
						'p3' => $picurl2,
						'p4' => $picurl3,
						'p5' => $picurl4,
						'p6' => $picurl5,
						'p7' => $picurl6,
						'p8' => $picurl7,
                        'p9' => $picurl8,						
				         );
				
                $temp['picarr'] = iserializer($picstr);	
				
			    pdo_insert($this->table_notice, $temp);
			
			    $notice_id = pdo_insertid();
			
			    if ($setting['istplnotice'] == 1 && $setting['bjtz']) {
				
				   $this->sendMobileBjtz($notice_id, $schoolid, $weid, $tname, $bj_id);
				
			    }else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			    }
	
			
		        $data ['result'] = true;
			
			    $data ['msg'] = '发布成功，请勿重复发布！';		
				
			}
          die ( json_encode ( $data ) ); 
		}
    }

	if ($operation == 'mfabu') {
		
		 load()->func('communication');
		 load()->classs('weixin.account');
		 load()->func('file');
         $accObj= WeixinAccount::create($_W['account']['acid']);
         $access_token = $accObj->fetch_token();
	     $token2 =  $access_token;
		 $photoUrls = explode ( ',', $_GPC ['photoUrls'] );
		 $data = explode ( '|', $_GPC ['json'] );
		 
				if(!empty($photoUrls[0])) {		 
					$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[0];
					$pic_data = ihttp_request($url);
					$path = "images/fmjiaoyu/";
					$picurl0 = $path.random(30) .".jpg";
					file_write($picurl0,$pic_data['content']);
				}
		
				if(!empty($photoUrls[1])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[1];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl1 = $path.random(30) .".jpg";
				file_write($picurl1,$pic_data['content']);
				}

				if(!empty($photoUrls[2])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[2];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl2 = $path.random(30) .".jpg";
				file_write($picurl2,$pic_data['content']);
				}
		
				if(!empty($photoUrls[3])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[3];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl3 = $path.random(30) .".jpg";
				file_write($picurl3,$pic_data['content']);
				}

				if(!empty($photoUrls[4])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[4];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl4 = $path.random(30) .".jpg";
				file_write($picurl4,$pic_data['content']);				
				}

				if(!empty($photoUrls[5])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[5];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl5 = $path.random(30) .".jpg";
				file_write($picurl5,$pic_data['content']);
				}

				if(!empty($photoUrls[6])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[6];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl6 = $path.random(30) .".jpg";
				file_write($picurl6,$pic_data['content']);
				}

				if(!empty($photoUrls[7])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[7];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl7 = $path.random(30) .".jpg";
				file_write($picurl7,$pic_data['content']);
				}

				if(!empty($photoUrls[8])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[8];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl8 = $path.random(30) .".jpg";
				file_write($picurl8,$pic_data['content']);

				}
				
		 $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid'])); 
		
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			
			if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求,请刷新页面！' 
		               ) ) );
					   
		    }else{
					 				
				$schoolid = $_GPC['schoolid'];
			
				$title = $_GPC['title'];
			
				$weid = $_GPC['weid'];
			
				$content = $_GPC['content'];
			
				$tid = $_GPC['tid'];
			
				$groupid = $_GPC['bj_id']; //用户组
							
				$tname = $_GPC['tname'];
				
				$temp = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'tid' => $tid,
					'tname' => $tname,
					'title' => $title,
					'content' => $content,
					'createtime' => time(),
					'type'=>2,
					'groupid'=>$groupid,
				);
				
				$picstr = array(
						'p1' => $picurl0,
						'p2' => $picurl1,
						'p3' => $picurl2,
						'p4' => $picurl3,
						'p5' => $picurl4,
						'p6' => $picurl5,
						'p7' => $picurl6,
						'p8' => $picurl7,
                        'p9' => $picurl8,						
				         );
				
                $temp['picarr'] = iserializer($picstr);	
				
			    pdo_insert($this->table_notice, $temp);
			
			    $notice_id = pdo_insertid();
			
			    if ($setting['istplnotice'] == 1 && $setting['xxtongzhi']) {
				
				   $this->sendMobileXytz($notice_id, $schoolid, $weid, $tname, $groupid);
				
			    }else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			    }
	
			
		        $data ['result'] = true;
			
			    $data ['msg'] = '发布成功，请勿重复发布！';		
				
			}
          die ( json_encode ( $data ) ); 
		}
    }

	if ($operation == 'zfabu') {
		
		 load()->func('communication');
		 load()->classs('weixin.account');
		 load()->func('file');
         $accObj= WeixinAccount::create($_W['account']['acid']);
         $access_token = $accObj->fetch_token();
	     $token2 =  $access_token;
		 $photoUrls = explode ( ',', $_GPC ['photoUrls'] );
		 $data = explode ( '|', $_GPC ['json'] );
		 
				if(!empty($photoUrls[0])) {		 
					$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[0];
					$pic_data = ihttp_request($url);
					$path = "images/fmjiaoyu/";
					$picurl0 = $path.random(30) .".jpg";
					file_write($picurl0,$pic_data['content']);
				}
		
				if(!empty($photoUrls[1])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[1];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl1 = $path.random(30) .".jpg";
				file_write($picurl1,$pic_data['content']);
				}

				if(!empty($photoUrls[2])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[2];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl2 = $path.random(30) .".jpg";
				file_write($picurl2,$pic_data['content']);
				}
		
				if(!empty($photoUrls[3])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[3];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl3 = $path.random(30) .".jpg";
				file_write($picurl3,$pic_data['content']);
				}

				if(!empty($photoUrls[4])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[4];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl4 = $path.random(30) .".jpg";
				file_write($picurl4,$pic_data['content']);				
				}

				if(!empty($photoUrls[5])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[5];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl5 = $path.random(30) .".jpg";
				file_write($picurl5,$pic_data['content']);
				}

				if(!empty($photoUrls[6])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[6];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl6 = $path.random(30) .".jpg";
				file_write($picurl6,$pic_data['content']);
				}

				if(!empty($photoUrls[7])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[7];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl7 = $path.random(30) .".jpg";
				file_write($picurl7,$pic_data['content']);
				}

				if(!empty($photoUrls[8])) {		 
				$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$photoUrls[8];
				$pic_data = ihttp_request($url);
				$path = "images/fmjiaoyu/";
				$picurl8 = $path.random(30) .".jpg";
				file_write($picurl8,$pic_data['content']);

				}
				
		 $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid'])); 
		
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			
			if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求,请刷新页面！' 
		               ) ) );
					   
		    }else{
					 				
				$schoolid = $_GPC['schoolid'];
			
				$title = $_GPC['title'];
			
				$weid = $_GPC['weid'];
			
				$content = $_GPC['content'];
			
				$tid = $_GPC['tid'];
			
				$bj_id = $_GPC['bj_id']; //班级
				
				$km_id = $_GPC['km_id']; //班级
							
				$tname = $_GPC['tname'];
				
				$temp = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'tid' => $tid,
					'tname' => $tname,
					'title' => $title,
					'content' => $content,
					'createtime' => time(),
					'type'=>3,
					'km_id'=>$km_id,
					'bj_id'=>$bj_id,
				);
				
				$picstr = array(
						'p1' => $picurl0,
						'p2' => $picurl1,
						'p3' => $picurl2,
						'p4' => $picurl3,
						'p5' => $picurl4,
						'p6' => $picurl5,
						'p7' => $picurl6,
						'p8' => $picurl7,
                        'p9' => $picurl8,						
				         );
				
                $temp['picarr'] = iserializer($picstr);	
				
			    pdo_insert($this->table_notice, $temp);
			
			    $notice_id = pdo_insertid();
			
			    if ($setting['istplnotice'] == 1 && $setting['zuoye']) {
				
				   $this->sendMobileZuoye($notice_id, $schoolid, $weid, $tname, $bj_id);
				
			    }else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			    }
	
			
		        $data ['result'] = true;
			
			    $data ['msg'] = '发布成功，请勿重复发布！';		
				
			}
          die ( json_encode ( $data ) ); 
		}
    }

	if ($operation == 'fangxue') {

		 $data = explode ( '|', $_GPC ['json'] );
	
		 $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid'])); 
		
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			
			if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求,请刷新页面！' 
		               ) ) );
					   
		    }else{
					 				
				$schoolid = $_GPC['schoolid'];
						
				$weid = $_GPC['weid'];
									
				$bj_id = $_GPC['bj_id']; //班级
											
				$tname = $_GPC['tname'];

			    if ($setting['istplnotice'] == 1 && $setting['bjtz']) {
				
				   $this->sendMobileFxtz($schoolid, $weid, $tname, $bj_id);
				
			    }else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			    }
	
			
		        $data ['result'] = true;
			
			    $data ['msg'] = '群发成功，请勿重复发布！';		
				
			}
          die ( json_encode ( $data ) ); 
		}
    }	
?>