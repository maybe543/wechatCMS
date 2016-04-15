<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */global $_W, $_GPC;
   $operation = in_array ( $_GPC ['op'], array ('default','useredit','jiaoliu','bdxs','bdls','unboundls','qingjia','agree','defeid','sagree','sdefeid','savemsg','xsqingjia','savesmsg') ) ? $_GPC ['op'] : 'default';

     if ($operation == 'default') {
	           die ( json_encode ( array (
			         'result' => false,
			         'msg' => '非法请求！'
	                ) ) );
              }			
     if ($operation == 'useredit') {
	     $data = explode ( '|', $_GPC ['json'] );
	      
            if (! $_GPC ['schoolid'] || ! $_W ['openid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
	       	       
		   $user = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_user ) . ' WHERE openid=:openid AND schoolid=:schoolid AND tid=:tid', array (
			        ':openid' => $_W ['openid'],
			        ':schoolid' =>  $_GPC ['schoolid'],
					':tid' =>  0
	          ) );
	       
		   $schoolid = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_index ) . ' WHERE id=' . $_GPC ['schoolid'] );
	      
           if (! $schoolid || ! $user) {
		        die ( json_encode ( array (
				  'result' => false,
				  'msg' => '非法请求！' 
		              ) ) );
	        } else {
		        		         
				if ($user ['status'] == 1) {
		     	     
					$data ['result'] = false; // 
					 
			        $data ['msg'] = '抱歉您的帐号被锁定，请联系校方！';
		         
				} else {
					
					$info = array ('name' => $_GPC ['name'],'mobile' => $_GPC ['mobile']);
			        
                    $temp['userinfo'] = iserializer($info);					
					
			        pdo_update ( $this->table_user, $temp, array ('id' => $user ['id']) );
				 							
			        $data ['result'] = true;
			
			        $data ['msg'] = '修改成功！';
		        }
		      die ( json_encode ( $data ) );
	        }
    }
	if ($operation == 'bdxs') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_W ['openid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
	    // $s_name = trim($_GPC['s_name']);
        // $mobile = trim($_GPC['mobile']);
		$subjectId = $_GPC['subjectId'];
		
		$sid = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And :weid = weid And :s_name = s_name And :mobile = mobile", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':s_name'=>$_GPC ['s_name'],
				 ':mobile'=>$_GPC ['mobile']
				  ), 'id');
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And weid = :weid AND id=:id ORDER BY id DESC", array(
		         ':weid' => $_GPC ['weid'],
                 ':schoolid' => $_GPC ['schoolid'],				 
		         ':id' => $sid['id']
	           	  ));
		  
		$user2 = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And :weid = weid And :own = own", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':own'=>$_GPC ['openid']
				  ), 'id');

		if (!empty($user2['id'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '抱歉,你已经绑定了其他学生信息！' 
		               ) ) );
		}

		$user3 = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And :weid = weid And :mom = mom", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':mom'=>$_GPC ['openid']
				  ), 'id');

		if (!empty($user3['id'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '抱歉,你已经绑定了其他学生信息！' 
		               ) ) );
		}
		
		$user4 = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And :weid = weid And :dad = dad", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':dad'=>$_GPC ['openid']
				  ), 'id');

		if (!empty($user4['id'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '抱歉,你已经绑定了其他学生信息！' 
		               ) ) );
		}		
		
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}
		
		if(empty($sid['id'])){
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '没有找到该生信息！' 
		          ) ) );
		}
		if($subjectId == 2 && $item['mom'] != '0'){
		   
				  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此学生母亲已经绑定了其他微信号！' 
		          ) ) );
		    
            }
		if($subjectId == 3 && $item['dad'] != '0'){
		   
		   		  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此学生父亲已经绑定了其他微信号！' 
		          ) ) );
		    
            }
		if($subjectId == 4 && $item['own'] != '0'){
		   
		   		  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此学生本人已经绑定了其他微信号！' 
		          ) ) );
		    
        }else{
			if($subjectId == 2 && $item['mom'] == '0'){
				$temp = array( 
				    'mom' => $_GPC ['openid'],
					'muid'=> $_GPC['uid']
				    );
			}
			if($subjectId == 3 && $item['dad'] == '0'){
				$temp = array(
				    'dad' => $_GPC ['openid'],
					'duid'=> $_GPC['uid']
				    );
			}
			if($subjectId == 4 && $item['own'] == '0'){
				$temp = array(
				    'own' => $_GPC ['openid'],
					'ouid'=> $_GPC['uid']
				    );
			}
 
			   
           pdo_update($this->table_students, $temp, array('id' => $sid['id']));
            		   
		   pdo_insert($this->table_user, array (
					'sid' => trim($sid['id']),
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_W ['openid'],
					'pard' => $subjectId,
					'uid' => $_GPC['uid']
			));
			
			$data ['result'] = true;
			
			$data ['msg'] = '绑定成功！';

		 die ( json_encode ( $data ) );
		}
    }
	
	if ($operation == 'bdls') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_W ['openid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
		
		$tid = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where :schoolid = schoolid And :weid = weid And :tname = tname And :code = code", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':tname'=>$_GPC ['tname'],
				 ':code'=>$_GPC ['code']
				  ), 'id');
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id ORDER BY id DESC", array(
		         ':weid' => $_GPC ['weid'], 
		         ':id' => $tid['id']
	           	  ));

		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where :schoolid = schoolid And :weid = weid And :openid = openid", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':openid'=>$_GPC ['openid']
				  ), 'id');

		if ($user['id']) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '抱歉,你已经绑定了其他教师信息！' 
		               ) ) );
		}				  
				  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}
		
		if(empty($tid['id'])){
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '姓名或绑定码输入有误！' 
		          ) ) );
		}
		if(!empty($item['openid'])){
		   
				  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此教师已经绑定了其他微信号！' 
		          ) ) );
		    
        }else{
  
		   $temp = array('openid' => $_GPC ['openid'], 'uid' => $_GPC['uid']);			
			   
           pdo_update($this->table_teachers, $temp, array('id' => $tid['id']));
            		   
		   pdo_insert($this->table_user, array (
					'tid' => trim($tid['id']),
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_W ['openid'],
					'uid' => $_GPC['uid']
			));
			
			$data ['result'] = true;
			
			$data ['msg'] = '绑定成功！';

		 die ( json_encode ( $data ) );
		}
    }	

	if ($operation == 'unboundls') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
		
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where :schoolid = schoolid And :weid = weid And :openid = openid", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':openid'=>$_GPC ['openid']
				  ), 'id');

		if (empty($user['id'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求，没找你的老师信息！' 
		               ) ) );
		}				  
				  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			
			$temp = array(
			        'openid' => '',
		           	'uid'    => 0
			       );
           pdo_update($this->table_teachers, $temp, array('id' => $_GPC['tid']));			   
           pdo_delete($this->table_user, array('id' => $_GPC['user']));	
			
			$data ['result'] = true;
			
			$data ['msg'] = '解绑成功！';

		 die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'qingjia') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
		
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where :schoolid = schoolid And :weid = weid And :openid = openid", array(
		         ':weid' => $_GPC ['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':openid'=>$_GPC ['openid']
				  ), 'id');
				  
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid'])); 
		
		$leave = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where :schoolid = schoolid And :weid = weid And :tid = tid ORDER BY id DESC LIMIT 1", array(
		         ':weid' => $_GPC['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':tid' => $_GPC ['tid']
				 )); 
				 
		if ((time() - $leave['createtime']) <  200) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '您请假太频繁了，请待会再试！' 
		               ) ) );
		}		 
		 
		if (empty($user['id'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求，没找你的老师信息！' 
		               ) ) );
		}				  
				  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$data = array(
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_GPC ['openid'],
					'tid' => $_GPC ['tid'],
					'type' => $_GPC ['type'],
					'startime' => $_GPC ['startTime'],
					'endtime' => $_GPC ['endTime'],
					'conet' => $_GPC ['content'],
					'uid' => $_GPC['uid'],
					'createtime' => time(),
			);
				
			pdo_insert($this->table_leave, $data);
   
			$leave_id = pdo_insertid();
			
			if ($setting['istplnotice'] == 1 && $setting['jsqingjia']) {
				
				$this->sendMobileJsqj($leave_id, $schoolid, $weid);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}
			
			$data ['result'] = true;
			
			$data ['msg'] = '申请成功，请勿重复申请！';

		 die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'agree') {
		$data = explode ( '|', $_GPC ['json'] );
			
            $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$leaveid = $_GPC['id'];
			
			$data = array(
			        'cltime' =>  time(),
					'status' =>  1,
			);
				
            pdo_update($this->table_leave, $data, array('id' => $leaveid));	

			if ($setting['istplnotice'] == 1 && $setting['jsqjsh']) {
				
				$this->sendMobileJsqjsh($leaveid, $schoolid, $weid);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}			
						
			$data ['result'] = true;
			
			$data ['msg'] = '审核成功！';
			
		 die ( json_encode ( $data ) );
		
    }

	if ($operation == 'defeid') {
		$data = explode ( '|', $_GPC ['json'] );
			
            $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$leaveid = $_GPC['id'];
			
			$data = array(
			        'cltime' =>  time(),
					'status' =>  2,
			);
				
            pdo_update($this->table_leave, $data, array('id' => $leaveid));	

			if ($setting['istplnotice'] == 1 && $setting['jsqjsh']) {
				
				$this->sendMobileJsqjsh($leaveid, $schoolid, $weid);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}			
						
			$data ['result'] = true;
			
			$data ['msg'] = '审核成功！';
			
		 die ( json_encode ( $data ) );
		
    }

	if ($operation == 'sagree') {
		$data = explode ( '|', $_GPC ['json'] );
			
            $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$leaveid = $_GPC['id'];
			
			$tname = $_GPC['tname'];
			
			$data = array(
			        'cltime' =>  time(),
					'status' =>  1,
			);
				
            pdo_update($this->table_leave, $data, array('id' => $leaveid));	

			if ($setting['istplnotice'] == 1 && $setting['xsqjsh']) {
				
				$this->sendMobileXsqjsh($leaveid, $schoolid, $weid, $tname);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}			
						
			$data ['result'] = true;
			
			$data ['msg'] = '审核成功！';
			
		 die ( json_encode ( $data ) );
		
    }

	if ($operation == 'sdefeid') {
		$data = explode ( '|', $_GPC ['json'] );
			
            $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$leaveid = $_GPC['id'];
			
			$tname = $_GPC['tname'];
			
			$data = array(
			        'cltime' =>  time(),
					'status' =>  2,
			);
				
            pdo_update($this->table_leave, $data, array('id' => $leaveid));	

			if ($setting['istplnotice'] == 1 && $setting['xsqjsh']) {
				
				$this->sendMobileXsqjsh($leaveid, $schoolid, $weid, $tname);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}			
						
			$data ['result'] = true;
			
			$data ['msg'] = '审核成功！';
			
		 die ( json_encode ( $data ) );
		
    }	

	if ($operation == 'savemsg') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
						  
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid'])); 
		
		$leave = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where :schoolid = schoolid And :weid = weid And :sid = sid  And :openid = openid And :isliuyan = isliuyan And :uid = uid And :bj_id = bj_id ORDER BY createtime ASC LIMIT 1", array(
		         ':weid' => $_GPC['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':openid' => $_GPC ['openid'],
				 ':bj_id' => $_GPC ['bj_id'],
				 ':uid' => $_GPC ['uid'],
				 ':isliuyan' => 1,
				 ':sid' => $_GPC ['sid']
				 )); 
				 
		$time = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where :schoolid = schoolid And :weid = weid And :sid = sid And :uid = uid And :bj_id = bj_id ORDER BY createtime DESC LIMIT 1", array(
		         ':weid' => $_GPC['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':bj_id' => $_GPC ['bj_id'],
				 ':uid' => $_GPC ['uid'],
				 ':sid' => $_GPC ['sid']
				 ));				 
		  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else if (!empty($leave['id'])) {
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$uid = $_GPC['uid'];
			
			$bj_id = $_GPC['bj_id'];
			
			$sid = $_GPC['sid'];
			
			$tid = $_GPC['tid'];
			
			$data = array(
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_GPC ['openid'],
					'sid' => $_GPC ['sid'],
					'conet' => $_GPC ['content'],
					'bj_id' => $_GPC['bj_id'],
					'uid' => $_GPC['uid'],
					'leaveid'=>$leave['id'],
					'isliuyan'=>1,
					'createtime' => time(),
			);
				
			pdo_insert($this->table_leave, $data);
   
			$leave_id = pdo_insertid();
			
			if ($setting['istplnotice'] == 1 && $setting['liuyan']) {
				
				$this->sendMobileJzly($leave_id, $schoolid, $weid, $uid, $bj_id, $sid, $tid);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}
			
			$data ['result'] = true;
			
			$data ['msg'] = '成功发送留言信息，请勿重复发送！';	
			
          die ( json_encode ( $data ) );
		  
		}else{
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$uid = $_GPC['uid'];
			
			$bj_id = $_GPC['bj_id'];
			
			$sid = $_GPC['sid'];
			
			$tid = $_GPC['tid'];
			
			$data = array(
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_GPC ['openid'],
					'sid' => $_GPC ['sid'],
					'conet' => $_GPC ['content'],
					'bj_id' => $_GPC['bj_id'],
					'uid' => $_GPC['uid'],
					'leaveid'=>$leave['id'],
					'isliuyan'=>1,
					'createtime' => time(),
			);
				
			pdo_insert($this->table_leave, $data);
   
			$leave_id = pdo_insertid();
			
			$data1 = array(
					'leaveid'=>$leave_id,
			);
			
			pdo_update($this->table_leave, $data1, array('id' => $leave_id));	
			
			if ($setting['istplnotice'] == 1 && $setting['liuyan']) {
				
				$this->sendMobileJzly($leave_id, $schoolid, $weid, $uid, $bj_id, $sid, $tid);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}
			
			$data ['result'] = true;
						
			$data ['msg'] = '成功发送留言信息，请勿重复发送！';

		 die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'xsqingjia') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
				  
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid'])); 
		
		$leave = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where :schoolid = schoolid And :weid = weid And :sid = sid And :tid = tid And :isliuyan = isliuyan ORDER BY id DESC LIMIT 1", array(
		         ':weid' => $_GPC['weid'],
				 ':schoolid' => $_GPC ['schoolid'],
				 ':tid' => 0,
				 ':isliuyan' => 0,
				 ':sid' => $_GPC ['sid']
				 )); 
				 
		if ((time() - $leave['createtime']) <  100) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '您请假太频繁了，请待会再试！' 
		               ) ) );
		}		 
		 			  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$tid = $_GPC['tid'];
			
			$data = array(
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_GPC ['openid'],
					'sid' => $_GPC ['sid'],
					'type' => $_GPC ['type'],
					'startime' => $_GPC ['startTime'],
					'endtime' => $_GPC ['endTime'],
					'conet' => $_GPC ['content'],
					'uid' => $_GPC['uid'],
					'bj_id' => $_GPC['bj_id'],
					'createtime' => time(),
			);
				
			pdo_insert($this->table_leave, $data);
   
			$leave_id = pdo_insertid();
			
			if ($setting['istplnotice'] == 1 && $setting['xsqingjia']) {
				
				$this->sendMobileXsqj($leave_id, $schoolid, $weid, $tid);
				
			}
			
			$data ['result'] = true;
			
			$data ['msg'] = '申请成功，请勿重复申请！';

		 die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'savesmsg') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
						  
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid'])); 
		 		 			  				  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$schoolid = $_GPC['schoolid'];
			
			$topenid = $_GPC['topenid'];
			
			$weid = $_GPC['weid'];
			
			$uid = $_GPC['uid'];
			
			$tuid = $_GPC['tuid'];
			
			$bj_id = $_GPC['bj_id'];
			
			$sid = $_GPC['sid'];
			
			$itemid = $_GPC['itemid'];
			
			$tname = $_GPC['tname'];
			
			$leaveid = $_GPC['leaveid'];
			
			$data = array(
					'weid' =>  $weid,
					'schoolid' => $schoolid,
					'openid' => $topenid,
					'sid' => $_GPC ['sid'],
					'conet' => $_GPC ['content'],
					'bj_id' => $bj_id,
					'uid' => $uid,
					'tuid' => $tuid,
					'leaveid'=>$leaveid,
					'isliuyan'=>1,
					'createtime' => time(),
					'status' =>  2,
			);
			
			$data1 = array(
			        'cltime' =>  time(),
					'status' =>  2,
			);			
				
			pdo_insert($this->table_leave, $data);
			
			$leave_id = pdo_insertid();
			
			pdo_update($this->table_leave, $data1, array('id' => $itemid));	
   
			if ($setting['istplnotice'] == 1 && $setting['liuyanhf']) {
				
				$this->sendMobileJzlyhf($leave_id, $schoolid, $weid, $topenid, $sid, $tname);
				
			}else{
				  die ( json_encode ( array (
                  'result' => false,
                  'msg' => '发送失败，请联系管理员开启模版消息！' 
		               ) ) );
			}
			
			$data ['result'] = true;
			
			$data ['msg'] = '成功发送留言信息，请勿重复发送！';	
			
          die ( json_encode ( $data ) );
		  
		}
    }
?>