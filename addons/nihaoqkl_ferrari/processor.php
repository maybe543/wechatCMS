<?php

defined('IN_IA') or exit('Access Denied');

class Nihaoqkl_ferrariModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];

//		if(preg_match("/法拉利/",$content)){

			$url = "http://" . $_SERVER['HTTP_HOST'] . '/app' . ltrim($this->createMobileUrl('index'),'.');
			return $this->respText($url);
//		}
	}
}