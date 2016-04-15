<?php
/**
 * 报料台模块微站定义
 *
 */
defined('IN_IA') or exit('Access Denied');

class Ali_BaoliaoModuleSite extends WeModuleSite {
	private $tb_baoliao = 'baoliao';
	
	public function doMobilemybaoliao(){
		global $_W, $_GPC;
		$openid = $_W['openid'];
		$sql = "SELECT title,content,uptime,reply,replytime,fromuser FROM".tablename('baoliao')." WHERE `fromuser` = '".$openid."' order by id desc";
		$mybl = pdo_fetchall($sql);
		include $this->template('mybaoliao');
	}
	
	public function doWebblreply(){
		global $_W, $_GPC;
		
		
		$msgtype = trim($_GPC['msgtype']);
		$acid = intval($_GPC['acid']);

		$send['touser'] = trim($_GPC['openid']);
		$send['msgtype'] = $msgtype;
		$fans = pdo_fetch('SELECT salt,acid,openid FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND openid = :openid', array(':acid' => $acid, ':openid' => $send['touser']));
		if($msgtype == 'text') {
			$send['text'] = array('content' => urlencode($_GPC['content']));
		} elseif($msgtype == 'image') {
			$send['image'] = array('media_id' => $_GPC['media_id']);
		} elseif($msgtype == 'voice') {
			$send['voice'] = array('media_id' => $_GPC['media_id']);
		} elseif($msgtype == 'video') {
			$send['video'] = array(
				'media_id' => $_GPC['media_id'],
				'thumb_media_id' => $_GPC['thumb_media_id'],
				'title' => urlencode($_GPC['title']),
				'description' => urlencode($_GPC['description'])
			);
		} elseif($msgtype == 'music') {
			$send['music'] = array(
				'musicurl' => tomedia($_GPC['musicurl']),
				'hqmusicurl' => tomedia($_GPC['hqmusicurl']),
				'title' => urlencode($_GPC['title']),
				'description' => urlencode($_GPC['description']),
				'thumb_media_id' => $_GPC['thumb_media_id'],
			);
		} elseif($msgtype == 'news') {
			$rid = intval($_GPC['ruleid']);
			$rule = pdo_fetch('SELECT module,name FROM ' . tablename('rule') . ' WHERE id = :rid', array(':rid' => $rid));
			if(empty($rule)) {
				exit(json_encode(array('status' => 'error', 'message' => '没有找到指定关键字的回复内容，请检查关键字的对应规则')));
			}
			$idata = array('rid' => $rid, 'name' => $rule['name'], 'module' => $rule['module']);
			$module = $rule['module'];
			$reply = pdo_fetchall('SELECT * FROM ' . tablename($module . '_reply') . ' WHERE rid = :rid', array(':rid' => $rid));
			if($module == 'cover') {
				$idata['do'] = $reply[0]['do'];
				$idata['cmodule'] = $reply[0]['module'];
			}
			if(!empty($reply)) {
				foreach($reply as $c) {
					$row = array();
					$row['title'] = urlencode($c['title']);
					$row['description'] = urlencode($c['description']);
					!empty($c['thumb']) && ($row['picurl'] = tomedia($c['thumb']));

					if(strexists($c['url'], 'http://') || strexists($c['url'], 'https://')) {
						$row['url'] = $c['url'];
					} else {
						$pass['time'] = TIMESTAMP;
						$pass['acid'] = $fans['acid'];
						$pass['openid'] = $fans['openid'];
						$pass['hash'] = md5("{$fans['openid']}{$pass['time']}{$fans['salt']}{$_W['config']['setting']['authkey']}");
						$auth = base64_encode(json_encode($pass));
						$vars = array();
						$vars['__auth'] = $auth;
						$vars['forward'] = base64_encode($c['url']);
						$row['url'] =  $_W['siteroot'] . 'app/' . murl('auth/forward', $vars);
					}
					$news[] = $row;
				}
				$send['news']['articles'] = $news;
			} else {
				$idata = array();
				$send['news'] = '';
			}
		}

		if($acid) {
			$acc = WeAccount::create($acid);
			$data = $acc->sendCustomNotice($send);
			if(is_error($data)) {
				exit(json_encode(array('status' => 'error', 'message' => $data['message'])));
			} else {
							$account = account_fetch($acid);
				$message['from'] = $_W['openid'] = $send['touser'];
				$message['to'] = $account['original'];
				if(!empty($message['to'])) {
					$sessionid = md5($message['from'] . $message['to'] . $_W['uniacid']);
					load()->classs('wesession');
					load()->classs('account');
					session_id($sessionid);
					WeSession::start($_W['uniacid'], $_W['openid'], 300);
					$processor = WeUtility::createModuleProcessor('chats');
					$processor->begin(300);
				}

				if($send['msgtype'] == 'news') {
					$send['news'] = $idata;
				}
							pdo_insert('mc_chats_record',array(
					'uniacid' => $_W['uniacid'],
					'acid' => $acid,
					'flag' => 1,
					'openid' => $send['touser'],
					'msgtype' => $send['msgtype'],
					'content' => iserializer($send[$send['msgtype']]),
					'createtime' => TIMESTAMP,
				));
				//exit(json_encode(array('status' => 'success', 'message' => '消息发送成功')));
			}
			//exit();
		}
		
		$r = pdo_update('baoliao', array('reply' => $_GPC['content'],'replytime'=>time()), array('id' => $_GPC['id']));
		if($r != 0){
			message('回复成功',$this->createWeburl('bllist'),'success');
		}
	}
	
	
	public function doWebcontentinfo(){
		global $_W, $_GPC;
		//print_r($_GPC);
		$sql = 'SELECT * FROM ' .tablename('baoliao') . ' WHERE `id` = '.$_GET['id'];
		$arr = pdo_fetch($sql);
		include $this->template("content");
	} 
	
	
	public function doWebsearch(){
		global $_W,$_GPC;
		if( $_GPC['keywordtel'] != '' or $_GPC['keywordname'] != ''){
			$sql = "SELECT * FROM " .tablename('baoliao') . " WHERE `tel` = '".$_GPC['keywordtel']."' or `name` = '".$_GPC['keywordname']."'";
			$arr = pdo_fetch($sql);
			include $this->template("content");
		}else{
			message('姓名或电话不能为空！',$this->createWeburl('bllist'));
		}
	}
	
	public function doMobileBaoliaocover() {
		global $_W, $_GPC;
		$openid = $_W['openid'];
		$sql = "SELECT count(*) FROM".tablename('baoliao')." WHERE `fromuser` = '".$openid."'";
		$blnum = pdo_fetchall($sql);
		$url = $this->createMobileUrl('blpost',array('rid'=>$_GET['rid'],'fromuser'=>$_GET['fromuser']));
		$_SESSION['ask'] = substr(md5("alibaoliao".time()),0,10);
		include $this->template('index');
	}
	public function doWebBllist() {
		global $_W, $_GPC;
		//print_r($_GPC);die;
		if($_GPC['delete'] == "删除"){
			foreach($_GPC['select'] as $k=>$v){
				$r = pdo_delete('baoliao',array('id'=>$v));
			}
			message('删除成功！',$_W['siteurl'],'success');
		}
		
		$pageindex = max(1, intval($_GPC['page']));
		$pagesize = 20;
		$sql = 'SELECT * FROM ' .tablename('baoliao') . ' WHERE `weid` = '.$_W['uniacid'].' ORDER BY id DESC LIMIT '.(($pageindex-1)*$pagesize)." , ".$pagesize;
		$bllist = pdo_fetchall($sql);
		$total_sql = 'SELECT * FROM ' .tablename('baoliao') . ' WHERE `weid` = '.$_W['uniacid'];
		$total = count(pdo_fetchall($total_sql));
		$pager = pagination($total, $pageindex, $pagesize,'', array('before' => 0, 'after' => 0));
		//print_r($bllist);
		include $this->template('bllist');
	}
	 
	public function doMobileBlpost(){
		global $_W, $_GPC;
		
		$dir = "../attachment/baoliao_imgs";
		if($_SESSION['ask'] == $_GPC['ask']){
			$_SESSION['ask'] = "alibaoliao";
			$infos =$_GPC['info'];
			if(!is_dir($dir)){
				mkdir("../attachment/baoliao_imgs");
			}
			if(is_dir($dir)){
				foreach($_GPC['pics'] as $k=>$v){
					$pname[$k] = substr(md5(time() . mt_rand(1,1000000)),0,16);
					$data = base64_decode($v);
					file_put_contents($dir."/".$pname[$k].".jpg",$data);
				}
			}
			$info['pics'] = implode("-",$pname);
			$info['bltype'] = $infos['type'];
			$info['name'] = $infos['name'];
			$info['tel'] = $infos['tel'];
			$info['title'] = $infos['title'];
			$info['content'] = $infos['content'];
			$info['weid'] = $_W['uniacid'];
			$info['rid'] = $_GPC['rid'];
			$info['uptime'] = time();
			$info['fromuser'] = $_W['openid'];
			//print_r($_GPC);die;
			pdo_insert($this->tb_baoliao, $info);
			message('谢谢您，报料成功！',$this->createMobileUrl('Baoliaocover', array('rid'=>$info['rid'],'fromuser'=>$info['fromuser'])),'success');
		}
	}

}