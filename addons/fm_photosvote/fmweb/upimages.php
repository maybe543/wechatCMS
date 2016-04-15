<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
		$from_user = $_GPC['from_user'];//
		$rbasic = pdo_fetch('SELECT * FROM '.tablename($this->table_reply).' WHERE uniacid= :uniacid AND rid =:rid ', array(':uniacid' => $uniacid, ':rid' => $rid) );
		$rvote = pdo_fetch("SELECT * FROM ".tablename($this->table_reply_vote)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$reply = array_merge($rbasic, $rvote); 
		$qiniu = iunserializer($reply['qiniu']);
		$now = time();
		load()->func('file');
		if(!empty($from_user)) {
			$mygift = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid));
		}
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
		if ($_GPC['upphotosone'] == 'start') {
			$base64=file_get_contents("php://input"); //获取输入流			
			$base64=json_decode($base64,1);
			$data = $base64['base64'];

			if($data){
				$harmtype = array('asp', 'php', 'jsp', 'js', 'css', 'php3', 'php4', 'php5', 'ashx', 'aspx', 'exe', 'cgi');
				
				preg_match("/data:image\/(.*?);base64/",$data,$res);
				$ext = $res[1];
				$setting = $_W['setting']['upload']['image'];
				if (!in_array(strtolower($ext), $setting['extentions']) || in_array(strtolower($ext), $harmtype)) {
					$fmdata = array(
						"success" => -1,
						"msg" => '系统不支持您上传的文件（扩展名为：'.$ext.'）,请上传正确的图片文件',
					);
					echo json_encode($fmdata);
					die;
				}
				
				$nfilename = 'FMFetchi'.date('YmdHis').random(16).'.'.$ext;
				$updir = '../attachment/images/'.$uniacid.'/'.date("Y").'/'.date("m").'/';
				mkdirs($updir);	
				$data = preg_replace("/data:image\/(.*);base64,/","",$data);
				if (file_put_contents($updir.$nfilename,base64_decode($data))===false) {
					$fmdata = array(
						"success" => -1,
						"msg" => '上传错误',
					);
					echo json_encode($fmdata);
				}else{
					$mid = $_GPC['mid'];
					$photosarrnum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->table_users_picarr)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $from_user,':rid' => $rid));
					$username = pdo_fetch("SELECT photoname FROM ".tablename($this->table_users_picarr)." WHERE uniacid = :uniacid AND rid = :rid AND id =:id LIMIT 1", array(':uniacid' => $uniacid,':rid' => $rid,':id' => $mid));

					if (!$qiniu['isqiniu']) {
						$picurl = $updir.$nfilename;
						if (!empty($username['photoname'])) {
							file_delete($updir.$username['photoname']);
							file_delete($updir.$nfilename);
							$insertdata = array(
								'photoname' => $nfilename,
								'createtime' => $now,
								"mid" => $mid,
								"photos" => $picurl,
							);
							pdo_update($this->table_users_picarr, $insertdata, array('rid' => $rid,'uniacid'=> $uniacid,'from_user' => $from_user, 'id'=>$mid));
							$lastmid = $mid;
						}else{
							if ($photosarrnum >= $reply['tpxz']) {
								$fmdata = array(
									"success" => -1,
									"msg" => '抱歉，你只能上传 '.$reply['tpxz'].' 张图片。',
								);
								echo json_encode($fmdata);
								exit;
							}
							$insertdata = array(
								'rid'       => $rid,
								'uniacid'      => $uniacid,
								'from_user' => $from_user,
								'status' => 1,
								'createtime' => $now,
							);

							$insertdata['isfm'] = 0;
							$insertdata['photos'] = $picurl;
							pdo_insert($this->table_users_picarr, $insertdata);
							$lastmid = pdo_insertid();	
							pdo_update($this->table_users_picarr, array('mid' => $lastmid), array('rid' => $rid,'uniacid'=> $uniacid,'from_user' => $from_user, 'id'=>$lastmid));
						}	

						$addlastmid = $lastmid + 1;
						$photosarrnum = $photosarrnum + 1;
						
						$fmdata = array(
							"success" => 1,
							"lastmid" => $lastmid,
							"addlastmid" => $addlastmid,
							"photosarrnum" => $photosarrnum,
							"msg" => '上传成功！',
							"imgurl" => $picurl,
						);
						echo json_encode($fmdata);
						exit();	
					}else {										
						$qiniu['upurl'] = $_W['siteroot'].'attachment/images/'.$uniacid.'/'.date("Y").'/'.date("m").'/'.$nfilename;	
						$username['type'] = '3';					
						$qiniuimages = $this->fmqnimages($nfilename, $qiniu, $mid, $username);
						if ($qiniuimages['success'] == '-1') {
							$fmdata = array(
								"success" => -1,
								"msg" => $qiniuimages['msg'],
							);
							echo json_encode($fmdata);
							exit();
						}else {
							if (!empty($username['photoname'])) {

								file_delete($updir.$username['photoname']);
								file_delete($updir.$nfilename);
								$insertdata = array(
									'photoname' => $nfilename,
									'createtime' => $now,
									"mid" => $mid,
									"photos" => $qiniuimages['picarr_'.$mid],
								);
								pdo_update($this->table_users_picarr, $insertdata, array('rid' => $rid,'uniacid' => $uniacid,'from_user' => $from_user, 'id' => $mid));
								$lastmid = $mid;
							}else{
								if ($photosarrnum >= $reply['tpxz']) {
									$fmdata = array(
										"success" => -1,
										"msg" => '抱歉，你只能上传 '.$reply['tpxz'].' 张图片。',
									);
									echo json_encode($fmdata);
									exit;
								}
								$insertdata = array(
									'rid'       => $rid,
									'uniacid'      => $uniacid,
									'from_user' => $from_user,
									'photoname' => $nfilename,
									'photos' => $qiniuimages['picarr_'.$mid],
									'status' => 1,
									'createtime' => $now,
								);
								pdo_insert($this->table_users_picarr, $insertdata);
								//更新mid
								$lastmid = pdo_insertid();	
								pdo_update($this->table_users_picarr, array('mid' => $lastmid), array('rid' => $rid,'uniacid'=> $uniacid,'from_user' => $from_user, 'id'=>$lastmid));

								file_delete($updir.$nfilename);
							}
							$addlastmid = $lastmid + 1;
							$photosarrnum = $photosarrnum + 1;

							$fmdata = array(
								"success" => 1,
								"lastmid" => $lastmid,
								"addlastmid" => $addlastmid,
								"photosarrnum" => $photosarrnum,
								"msg" => $qiniuimages['msg'],
								"imgurl" => $insertdata['photos'],
							);
							echo json_encode($fmdata);
							exit();	
						}
							
					}
				}
				
			}else{
				$fmdata = array(
					"success" => -1,
					"msg" =>'没有发现上传图片',
				);
				echo json_encode($fmdata);
				exit();	
			}
		}	
	