<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
header('Content-type: application/json');
		$qiniu = iunserializer($reply['qiniu']);
		$fmmid = random(16);
		$now = time();
		$mygift = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid));
		$uid = pdo_fetch("SELECT uid FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid AND rid = :rid ORDER BY uid DESC, id DESC LIMIT 1", array(':uniacid' => $uniacid,':rid' => $rid));
		if (empty($mygift)) {
			$insertdata = array(
				'rid'       => $rid,
				'uid'       => $uid['uid'] + 1,
				'uniacid'      => $uniacid,
				'from_user' => $from_user,
				'avatar'    => $avatar,
				'nickname'  => $nickname,			    
				'sex'  => $sex,			    
				'photo'  => '',			    
				'description'  => '',
				'photoname'  => '',
				'realname'  => '',
				'mobile'  => '',
				'weixin'  => '',
				'qqhao'  => '',
				'email'  => '',
				'job'  => '',
				'xingqu'  => '',
				'address'  => '',
				'photosnum'  => '0',
				'xnphotosnum'  => '0',
				'hits'  => '1',
				'xnhits'  => '1',
				'yaoqingnum'  => '0',
				'createip' => getip(),
				'lastip' => getip(),
				'status'  => '2',
				'sharetime' => $now,
				'createtime'  => $now,
			);
			$insertdata['iparr'] = getiparr($insertdata['lastip']);

			pdo_insert($this->table_users, $insertdata);
			

		   if($reply['isfans']){
				if($myavatar){
					fans_update($from_user, array(
						'avatar' => $myavatar,					
					));
				} 
				if($mynickname){
					fans_update($from_user, array(
						'nickname' => $mynickname,					
					));
				}
				
				if($reply['isrealname']){
					fans_update($from_user, array(
						'realname' => $realname,					
					));
				}
				if($reply['ismobile']){
					fans_update($from_user, array(
						'mobile' => $mobile,					
					));
				}				
				if($reply['isqqhao']){
					fans_update($from_user, array(
						'qq' => $qqhao,					
					));
				}
				if($reply['isemail']){
					fans_update($from_user, array(
						'email' => $email,					
					));
				}
				if($reply['isaddress']){
					fans_update($from_user, array(
						'address' => $address,					
					));
				}				
			}
				
				
		}
		
		$udata = array(
			'uniacid' => $uniacid,
			'rid' => $rid,
			'from_user' => $from_user,
			'fmmid' => $fmmid,
			'mediaid'  =>$_POST['serverId'],	
			'timelength' => $_GPC['timelength'],	
			'ip' => getip(),
			'createtime' => $now,
		);
		if ($udata['mediaid']) {
			$voice = $this->downloadVoice($udata['mediaid'], $fmmid);	
			
			if ($qiniu['isqiniu']) {
				$nfilename = 'FMVOICE'.date('YmdHis').random(16).'.amr';
				$upurl = tomedia($voice);
				$username = pdo_fetch("SELECT * FROM ".tablename($this->table_users_name)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid));
				$audiotype = 'voice';
					$qiniuaudios = $this->fmqnaudios($nfilename, $qiniu, $upurl, $audiotype, $username);
					$nfilenamefop = $qiniuaudios['nfilenamefop'];
					if ($qiniuaudios['success'] == '-1') {
					//	var_dump($err);
						$fmdata = array(
							"success" => -1,
							"msg" => $qiniuaudios['msg'],
						);
						echo json_encode($fmdata);
						exit();	
					} else {
						$insertdata = array();		
						
						if ($qiniuaudios['success'] == '-2') {
							//var_dump($err);
							$fmdata = array(
									"success" => -1,
									"msg" => $qiniuaudios['msg'],
								);
								echo json_encode($fmdata);
								exit();	
						} else {
							
							$voice = $qiniuaudios[$audiotype];
							$udata[$audiotype] = $qiniuaudios[$audiotype];
							
							pdo_insert($this->table_users_voice, $udata);
							pdo_update($this->table_users, array('fmmid' => $fmmid,'mediaid'  =>$_POST['serverId'],'lastip' => getip(),'lasttime' => $now,'voice' => $voice,'timelength' => $_GPC['timelength']), array('uniacid' => $uniacid, 'rid' => $rid, 'from_user' => $from_user));
							
							if ($username) {
								$insertdataname = array();
								$insertdataname[$audiotype.'name'] = $nfilename;
								$insertdataname[$audiotype.'namefop'] = $nfilenamefop;
								pdo_update($this->table_users_name, $insertdataname, array('from_user'=>$from_user, 'rid' => $rid, 'uniacid' => $uniacid));
							}else {
								$insertdataname = array(
									'rid'       => $rid,
									'uniacid'      => $uniacid,
									'from_user' => $from_user,
								);
								$insertdataname[$audiotype.'name'] = $nfilename;
								$insertdataname[$audiotype.'namefop'] = $nfilenamefop;
								pdo_insert($this->table_users_name, $insertdataname);
							}
						}						
					}
			}else {
				$udata['voice'] = $voice;
				pdo_insert($this->table_users_voice, $udata);
				pdo_update($this->table_users, array('fmmid' => $fmmid, 'mediaid'=>$_POST['serverId'], 'lastip' => getip(), 'lasttime' => $now, 'voice' => $voice, 'timelength' => $_GPC['timelength']), array('uniacid' => $uniacid, 'rid' => $rid, 'from_user' => $from_user));
			}
			
		}
		
		
		
		$data=json_encode(array('ret'=>0,'serverId'=>$_POST['serverId'],'msg'=>'上传语音成功，点击试听播放。'));
		die($data);