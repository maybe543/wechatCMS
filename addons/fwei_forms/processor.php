<?php
/**
 * 通用表单模块处理程序
 *
 * @author fwei.net
 * @url http://www.fwei.net/
 */
defined('IN_IA') or exit('Access Denied');

class Fwei_formsModuleProcessor extends WeModuleProcessor {

	public function respond() {
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		global $_W;
		$rid = $this->rule;
		$openid = $_W['openid'];
		$row = pdo_fetch("SELECT * FROM " . tablename('fwei_forms') . " WHERE `rid`=:rid LIMIT 1", array(':rid' => $rid));
		if( empty($row) ){
			return $this->respText( '表单已被删除！' );
		}
		if( TIMESTAMP < $row['stime'] ){
			return $this->respText( $row['title']. '，开始时间为：'.date('Y-m-d H:i:s', $row['stime']) );
		}
		if( TIMESTAMP > $row['etime'] ){
			return $this->respText( $row['title']. '，结束时间为：'.date('Y-m-d H:i:s', $row['etime']) );
		}
		$news = array();
		$news[] = array(
			'title'	=>	$row['title'],
			'description'	=>	$row['description'],
			'picurl'	=>	tomedia($row['thumb']),
			'url'	=>	$this->createMobileUrl('forms', array('id'=>$rid, '_openid'=>$openid)),
		);
		return $this->respNews( $news );
	}

}