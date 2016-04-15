<?php

defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . "/addons/water_jfhb/inc/common.php"; 
class Water_jfhbModuleReceiver extends WeModuleReceiver {

	public function receive() {
		global $_W;	
		
		$settings=$this->module['config'];
		if (empty($settings)){
			return;
		}

		if (!empty($settings['debug'])){
			load()->func('logging');
		    logging_run("Water_jfhbModuleReceiver");   
			logging_run($this->message); 
		}
		
		$settings["gz_min_amount"]=empty($settings["gz_min_amount"])?0:$settings["gz_min_amount"];
        $settings["min_money"]=empty($settings["min_money"])?0:$settings["min_money"];
        $uniacid=$_W['uniacid'];
        

		if ($this->message['event'] == 'unsubscribe' && empty($settings["qx_guanzhu"])){
			load()->func('logging');
		    logging_run("start");   
			logging_run($this->message['from']); 
            $openid= $this->message['from'];           
            $sql="select parent_openid from ".
            tablename("jfhb_user")." where uniacid=".$_W['uniacid']." and openid='{$this->message['from']}'";
            
            $user=pdo_fetch($sql);
            
            $this->del_money($_W['uniacid'],$user['parent_openid'],$settings);
     
            
           if (!empty($settings['credit_enable'])){
           	 if (empty($settings['qx_credit'])){
           	    return;
           	 }
             $fans=get_fans(array("openid"=>$user['parent_openid']));
              $this->post_send_text($user['parent_openid'],"你邀请的一个朋友取消了关注，积分减少到了".$fans["credit1"]);
              return;
           }  
            
                   
            $sql="select wtx_money,parent_openid from ".
            tablename("jfhb_user")." where uniacid=".$_W['uniacid']." and openid='{$user['parent_openid']}'";
            $temp=pdo_fetch($sql);
            
            $this->post_send_text($user['parent_openid'],"你邀请的一个朋友取消了关注，余额减少到了".$temp["wtx_money"].
              "元,满".$settings["tx_money"].'就可以提现');
             
            if (!empty($settings['show_money'])){
   	          $param=array("uniacid"=>$_W['uniacid'],
              "openid"=>$user['parent_openid'],"child_openid"=>$this->message['from'],"money"=>$settings['min_money'],type=>"2");
   	          $this->insert_userlog($param);
            }
            return;
         }	
       
        //直接关注，没有通过二维码扫码 
		if($this->message['event'] == 'subscribe' && empty($this->message['ticket']) && !empty($settings["gz_min_amount"])) {
          $zz=array();
          $zz['money']= $settings["gz_min_amount"];
          $zz['uniacid']=$uniacid;
          $zz['openid']=$this->message['from'];
          $zz['parent_openid']=$this->message['from'];       
         
		   //第一次
		  $result=$this->oneuserdata($zz,$settings);

		  if ($result['code']!="1"){
		  	 $this->post_send_text($this->message['from'],$result['msg']);	
		  	 return;
		  }
		  
		  //如果关注得到的红包大于提现的红包				 
		  if ($settings["gz_min_amount"]>=$settings["tx_money"]){
		  	$ret=$this->send_xjhb($settings,$this->message['from'],$settings["gz_min_amount"],"恭喜你获得红包");
		  	if ($ret['code']==0){
              $this->txmoneydata($zz,$settings,$settings["gz_min_amount"]);
              return $this->post_send_text($this->message['from'],"你已经获得了".$settings["gz_min_amount"]."元红包，请注意查收");
            } else {
              logging_run("send_xjhb:error1");
              logging_run($ret);
              if (!empty($settings['debug'])){ 
                 $this->post_send_text($this->message['from'],"发放红包的过程出错错误信息:".$ret['msg']);	
              } else {
              	 $this->post_send_text($this->message['from'],"发放红包的接口出错，程序猿哥哥在紧急修复中");
              }             
              return;
            }

		  }
		  
		   $gz_note=empty($settings["gz_note"])?"你已经获得了".$settings["gz_min_amount"].
           "元红包,满".$settings["tx_money"].'元就可以提现了，点击我的海报，邀请好友扫一扫就可以增加余额了':$settings["gz_note"];
           $gz_note=str_replace("&#039;","'",$gz_note);
		   $gz_note=str_replace("\"","'",$gz_note);
		   $gz_note=str_replace("#gz_min_amount#",$settings["gz_min_amount"],$gz_note);
		   $gz_note=str_replace("#tx_money#",$settings["tx_money"],$gz_note);
		  
		   $this->post_send_text($this->message['from'],$gz_note);	
		   return;
		  
		}
         
         
       

	}
	
  public function insert_userlog($param){	
 	$temp=pdo_insert("jfhb_user_log",
             array("uniacid"=>$param['uniacid'],
             	   "openid"=>$param['openid'],
             	   "child_openid"=>$param['child_openid'],
                   "money"=>$param['money'], 
                   "type"=>$param['type'],                 
                   "createtime" =>TIMESTAMP,
     ));
  }
  
  //取消关注减去金额
  public function del_money($weid,$openid,$settings){
    if (!empty($settings['credit_enable']) && !empty($settings['qx_credit'])){
        add_fans_score(array("openid"=>$openid,"credit1"=>"-".$settings['qx_credit']));
        return;
    } 
 
   $money=$settings['min_money'];
   $sql="update ".tablename("jfhb_user").
           " set money=money-".$money.",wtx_money=wtx_money-".$money.",createtime=".TIMESTAMP.
       " where uniacid=".$weid." and openid='$openid'";
   $temp=pdo_query($sql);
   if ($temp==false) {          
        logging_run("del_money:".$sql); 
        return;
    } 
    
   if (!empty($settings['system_money'])){
        add_fans_money(array("openid"=>$openid,"money"=>"-".$money));
    }
    
    
 

    
   }
  
  //推荐人总金额改变
  public function txmoneydata($obj){
    $weid=$obj['uniacid'];
    $openid=$obj['openid'];
    $temp=pdo_query("update ".tablename("jfhb_user").
           " set tx_money=wtx_money+tx_money ,wtx_money=0,createtime=".TIMESTAMP.
       " where uniacid=".$weid." and openid='$openid'");
    
    if ($temp==false) {          
        logging_run("txmoneydata:error1"); 
     } 
     return   $temp;   	
  }




  //推荐人总金额改变
  public function updateuserdata($obj){
    $weid=$obj['uniacid'];
    $openid=$obj['parent_openid'];

    $sql="select openid from ".tablename("jfhb_user")."  where uniacid=".$weid." and openid='$openid'";
  	$userdata=pdo_fetchcolumn($sql);
   if (empty($userdata)){
   	     $temp=pdo_insert("jfhb_user",
             array("uniacid"=>$weid,
             	   "openid"=>$openid,
             	   "money"=>$obj['tg_money'], 
                   "tx_money"=>0, 
                   "wtx_money"=>$obj['tg_money'], 
                   "parent_openid"=>$openid,
                   "createtime" =>TIMESTAMP,
                  ));
      if ($temp==false) {          
        logging_run("updateuserdata1:"); 
      }  
      return;
   }

    $temp=pdo_query("update ".tablename("jfhb_user").
           " set money=money+".$obj['tg_money'].",wtx_money=wtx_money+"
           .$obj['tg_money'].",createtime=".time()." where uniacid=".$weid." and openid='$openid'");
     if ($temp==false) {          
        logging_run("updateuserdata2:"); 
      }  
     
 	
  }
  

  
    //第一次关注生成用户信息
  public function oneuserdata($obj,$settings){
  	$weid=$obj['uniacid'];
    $openid=$obj['openid'];
    $zz="select money from ".tablename("jfhb_user")."  where uniacid=".$weid." and openid='$openid'";
  	$userdata=pdo_fetch($zz);

    if (empty($userdata)){  	
      $temp=pdo_insert("jfhb_user",
             array("uniacid"=>$weid,
             	   "openid"=>$openid,
             	   "money"=>$obj['money'], 
                   "tx_money"=>0, 
                   "wtx_money"=>$obj['money'], 
                   "parent_openid"=>$obj['parent_openid'],
                   "createtime" =>TIMESTAMP,
                  ));
      if ($temp==false) {          
        logging_run("oneuserdata:");
        return array("code"=>-1,"msg"=>"系统崩溃中，请联系"); 
      }   
   } else if(!empty($userdata['money'])){
 
   	 return array("code"=>-2,"msg"=>"你已经领取过福利了"); 
   }

   if (!empty($settings['show_money'])){
   	  $param=array("uniacid"=>$obj['uniacid'],
      "openid"=>$obj['openid'],"money"=>$obj['money']);
   	  $this->insert_userlog($param);
    }

    if (!empty($settings['credit_enable'])){
       add_fans_score(array("openid"=>$openid,"credit1"=>$settings['guanzhu_credit']));
    }  

    
    return array("code"=>1);
  }
  



 //现金红包接口
   function send_xjhb($settings,$fromUser,$amount,$desc) {
   	   return $this->send_qyfk($settings,$fromUser,$amount,$desc);
   	   $ret=array();
       $ret['code']=0;
       $ret['message']="success";     
       //return $ret;
      	$amount=$amount*100;
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars = array();
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] =random(10). date('Ymd') . random(3);
        $pars['mch_id'] = $settings['mchid'];
        $pars['wxappid'] = $settings['appid'];
        $pars['nick_name'] =   $settings['send_name'];
        $pars['send_name'] = $settings['send_name'];
        $pars['re_openid'] = $fromUser;
        $pars['total_amount'] = $amount;
        $pars['min_value'] = $amount;
        $pars['max_value'] = $amount;
        $pars['total_num'] = 1;
        $pars['wishing'] = $desc;
        $pars['client_ip'] = $settings['ip'];
        $pars['act_name'] =  $settings['act_name'];
        $pars['remark'] = $settings['remark'];

        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$settings['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
       
        $extras['CURLOPT_CAINFO']= $settings['rootca'];
        $extras['CURLOPT_SSLCERT'] =$settings['apiclient_cert'];
        $extras['CURLOPT_SSLKEY'] =$settings['apiclient_key'];


        load()->func('communication');
        $procResult = null; 
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $procResult = $resp["message"];
            $ret['code']=-1;
            $ret['message']=$procResult;
            return $ret;     
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
             if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $result = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']=0;
                    $ret['message']="success";
               
                    return $ret;
                  
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $ret['code']=-2;
                    $ret['message']=$error;
                    return $ret;
                 }
            } else {
                $ret['code']=-3;
                $ret['message']="3error3";
                return $ret;
            }
            
        }

     
    }

  
  //企业付款接口
   function send_qyfk($settings,$fromUser,$amount,$desc){
    $ret=array();
    $amount=$amount*100;
    $ret['amount']=$amount;
    $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    $pars = array();
    $pars['mch_appid'] =$settings['appid'];
    $pars['mchid'] = $settings['mchid'];
    $pars['nonce_str'] = random(32);
    $pars['partner_trade_no'] = random(10). date('Ymd') . random(3);
    $pars['openid'] =$fromUser;
    $pars['check_name'] = "NO_CHECK";
    $pars['amount'] =$amount;
    $pars['desc'] = $desc;
    $pars['spbill_create_ip'] =$settings['ip']; 
    ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$settings['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO']= $settings['rootca'];
        $extras['CURLOPT_SSLCERT'] =$settings['apiclient_cert'];
        $extras['CURLOPT_SSLKEY'] =$settings['apiclient_key'];
 
     
        load()->func('communication');
        $procResult = null; 
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $procResult = $resp['message'];
            $ret['code']=-1;
            $ret['message']="-1:".$procResult;
            return $ret;            
         } else {        	
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $result = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']=0;
                    $ret['message']="success";
                    return $ret;
                  
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $ret['code']=-2;
                    $ret['message']="-2:".$error;
                    return $ret;
                 }
            } else {
                $ret['code']=-3;
                $ret['message']="error response";
                return $ret;
            }
        }
    
   }






//主动文本回复消息，48小时之内
public function post_send_text($openid,$content,$obj=array()) {
	    global $_W;
	    $weid = $_W['acid'];  
        $accObj= WeAccount::create($weid);
        $token=$accObj->fetch_token();  
        load()->func('communication');
		$data['touser'] =$openid;
		$data['msgtype'] = 'text';
		$data['text']['content'] = urlencode($content);
		$dat = json_encode($data);
		$dat = urldecode($dat);
		 //客服消息
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
	    $ret=ihttp_post($url,$dat);
		$dat = $ret['content'];
		$result = @json_decode($dat, true);
		if ($result['errcode'] == '0') {
		  //message('发送消息成功！', referer(), 'success');
		} else {
		  logging_run("post_send_text:");
		  logging_run($dat);
		  logging_run($result);
		  //$this->sendTemplateMsg($templateid,$openid,$obj);
		}
	    return true;
}

 //
   public function sendTemplateMsg($templateid,$openid,$obj){
    //    $openid="o4xFyuF9JzrcCskKy189IY4RD3SE";
     //http请求方式: POST
//https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=ACCESS_TOKEN
         $template_mess= <<<EOF
        {
							           "touser":"$openid",
							           "template_id":"$templateid",
							           "url":"{$obj['url']}",
							           "topcolor":"#FF0000",
							           "data":{
							                   "first": {
							                       "value":"{$obj['first']}",
							                       "color":"#173177"
							                   },
							                   "keynote1":{
							                       "value":"{$obj['keynote1']}",
							                       "color":"#FF0000"
							                   },
							                   "keynote2": {
							                       "value":"{$obj['keynote2']}",
							                       "color":"#FF0000"
							                   },
		
							                   "remark": {
							                       "value":"{$obj['remark']}",
							                       "color":"#FF0000"
							                   }
							             
							            }
						       		}
EOF;
    
	  return $this->send_template_message($template_mess);
  	 
   }
   
   
	public function send_template_message($data){
		global $_W, $_GPC;
		$weid = $_W['uniacid'];  
        load()->classs('weixin.account');
        $accObj= WeixinAccount::create($weid);
        $access_token=$accObj->fetch_token();
		$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		$res=$this->http_request($url, $data);
		return $res;
	 }
	 
	 public function http_request($url, $data=NULL){
		$curl=curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output=curl_exec($curl);
		curl_close($curl);
		return $output;
	}
  
}
       