<?php
/**
 * 微折扣模块处理程序
 *
 * @author Kasen
 * @url http://www.ka-sen.com
 */
defined('IN_IA') or exit('Access Denied');

class Kasen_zhekouModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		function findNum($str=''){
				$str=trim($str);
				if(empty($str)){return '';}
				$temp=array('1','2','3','4','5','6','7','8','9','0','a','b','c','d','e','f','g','h','i','g','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
				$result='';
				for($i=0;$i<strlen($str);$i++){
					if(in_array($str[$i],$temp)){
						$result.=$str[$i];
					}
				}
				return $result;
			}
			$sql = 'SELECT * FROM ' . tablename('ks_yhq');
			$accounts = pdo_fetch($sql);
			if(findNum($content)==null){$nums=$accounts[pid];}else{$nums=findNum($content);}
			//echo $nums;
			$sql2 = 'SELECT * FROM '. tablename('ks_yhq') .' where code = "'.$nums.'"';
			//echo $sql2;
			$account2 = pdo_fetch($sql2);
			if(findNum($content)==null){$nums=$accounts[pid];}else{$nums=$account2[pid];}
			//echo $nums;
			$sql = 'SELECT * FROM '. tablename('ks_yhq_code') .' where `use` = 0 and `void` = 0 and `send` = 0 and `pid` = "'.$nums.'"';
			//echo $sql;
			//echo $sql;
			$account = pdo_fetch($sql);			
			if($account2[name]==null){$names=$accounts[name];}else{$names=$account2[name];}
			if(count($account)<2){return $this->respText('本时间段优惠券已经发放完毕，稍后再试试！');exit;}
			else{$huifucon = '【'.$names.'】您的优惠券是：'.$account[code].'，请尽快使用！';}
			$usesql = 'update '.tablename('ks_yhq_code').' set `send`=1 WHERE ID = '.$account[id];
			pdo_run($usesql);
			return $this->respText($huifucon);
	}
}