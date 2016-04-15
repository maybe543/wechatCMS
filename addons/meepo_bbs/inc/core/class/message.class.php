<?php
defined('IN_IA') or exit('Access Denied');
load()->classs('weixin.account');
class message extends WeiXinAccount{
	public $account = null;
	public function __construct($acid){
		$acc = self::create($acid);
		$this->account = $acc->account;
	}
	
	public function send($send = array()){
		global $_W;
	}
}