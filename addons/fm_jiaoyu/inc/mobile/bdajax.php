<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $this->weid;
        $from_user = $this->_fromuser;
		$schoolid = intval($_GPC['schoolid']);
		$openid = $_W['openid'];
        
	    $s_name = trim($_GPC['s_name']);
        $mobile = trim($_GPC['mobile']);
		$subjectId = $_GPC['subjectId'];
		
				
		$sid = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And :weid = weid And :s_name = s_name And :mobile = mobile", array(':weid' => $weid, ':schoolid' => $schoolid, ':mobile'=>$mobile, ':s_name'=>$s_name), 'id');
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $sid['id']));
	  		   
           

			
	  if(!empty($sid['id'])){        	

			if($subjectId == 2 && $item['mom'] == 0){
				$temp = array(
				    'schoolid' => $schoolid, 
				    'mom' => $openid
				    );
			}
			if($subjectId == 3 && $item['dad'] == 0){
				$temp = array(
				    'schoolid' => $schoolid,
				    'dad' => $openid
				    );
			}
			if($subjectId == 4 && $item['own'] == 0){
				$temp = array(
				    'schoolid' => $schoolid,
				    'own' => $openid
				    );
			}
			if($subjectId == 2 && $item['mom'] != '0'){
		   
		    message('绑定失败，此学生信息已经绑定了其他微信号！');
		    
            }
			if($subjectId == 3 && $item['dad'] != '0'){
		   
		    message('绑定失败，此学生信息已经绑定了其他微信号！');
		    
            }
			if($subjectId == 4 && $item['own'] != '0'){
		   
		    message('绑定失败，此学生信息已经绑定了其他微信号！');
		    
            }   
			   
           pdo_update($this->table_students, $temp, array('id' => $sid['id']));
            		   
		   pdo_insert($this->table_user, array (
					'sid' => trim($sid['id']),
					'weid' => $weid,
					'schoolid' => $schoolid,
					'openid' => $openid,
					'status' => 1,
					'pard' => $subjectId,
					'uid' => $fan ['uid']
			));
			
        message('绑定成功！', $this->createMobileUrl('user', array('schoolid' => $schoolid), true));
		
		}else{
         include $this->template('404');
        }        
?>