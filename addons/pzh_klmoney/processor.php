<?php
/**
 * 口令红包模块处理程序
 *
 * @author pzh
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
include_once("PZHSendMoney.php");
define('MB_ROOT', IA_ROOT . '/addons/pzh_klmoney');
class Pzh_klmoneyModuleProcessor extends WeModuleProcessor 
{
	public function respond() 
	{

		$content = $this->message['content'];
		global $_GPC,$_W;
        
		 //判断是否在时间内
            $time = date('Y-m-d H:i:s',time());
            if($time< $this->module['config']['kouling']['begin_time'] )
            {
            	if(empty($this->module['config']['kouling']['nobegin']))
            	{
            		$errorMsg='活动未开始';
            	}
            	else 
            	{
            		$errorMsg = $this->module['config']['kouling']['nobegin'];
            	}
            	 return $this->respText($errorMsg);
			    
            }

             if($time> $this->module['config']['kouling']['end_time'] )
            {
            	if(empty($this->module['config']['kouling']['haveend']))
            	{
            		$errorMsg='活动已结束';
            	}
            	else 
            	{
            		$errorMsg = $this->module['config']['kouling']['haveend'];
            	}
            	 return $this->respText($errorMsg);
            }
         session_start(); 
		 $re_openid   =  $this->message['from'];
		 //判断用户操作是否过于频繁
        
		 if($_SESSION[$re_openid]<$this->module['config']['kouling']['cantry'])
		 {
		 	if(isset($_SESSION[$re_openid]))
		 	$_SESSION[$re_openid]=$_SESSION[$re_openid]+1;
		    else if($this->module['config']['kouling']['cantry']==1)
		    	$_SESSION[$re_openid] = $_W['timestamp'];
		    else $_SESSION[$re_openid] = 1;

		 
		 }
		 else  if($_SESSION[$re_openid]==$this->module['config']['kouling']['cantry'])
		 {
		 	$_SESSION[$re_openid] = $_W['timestamp'];
		 }
		 else
		 {
		 	if($_W['timestamp']-$_SESSION[$re_openid]<$this->module['config']['kouling']['cooling'])
		 	{
		 		if(!empty($this->module['config']['kouling']['havegetMsg']))
		 		{
		 			$errorMsg = $this->module['config']['kouling']['havegetMsg'];
		 		}
		 		else 
		 		{
		 			$errorMsg='您的操作过于频繁';
		 		}
		 		$_SESSION[$re_openid] = $_W['timestamp'];
		 		return $this->respText($errorMsg);
		 	}
		 	else
		 	{
		 		if($this->module['config']['kouling']['cantry']==1)
                 $_SESSION[$re_openid] = $_W['timestamp'];
		 		else 
		 		$_SESSION[$re_openid] =1;

		 	}
		 }
    
		$weid = $_W['uniacid'];
	    
		$this ->init();
		 $maxRedCount =  $this->module['config']['kouling']['maxRedCount'];
	 if($maxRedCount <= 0)
	    {
	    	//红包已领完
	    	if(!empty($this->module['config']['kouling']['sendAllMsg']))
	    	{
	    		$errorMsg = $this->module['config']['kouling']['sendAllMsg'];
	    	}
	    	else 
	    	{
	    		$errorMsg='红包已领完';
	    	}
	    	 return $this->respText($errorMsg);
	    }
		//查询口令是否存在
	    $sql = 'SELECT beginer,moneyCount,count FROM ' . tablename('pzh_kouling2') . ' WHERE `uniacid` = :uniacid  and `kouling` = :kouling';
	    $params = array(':uniacid' => $_W['uniacid'] , ':kouling' => $content);
	   // return $this->respText($_W['acid']);
	    $result = pdo_fetch($sql, $params);
        $kouling_count=$result['count'];

	    if(!$result||$result['count']<=0)
	    {
	    	
	    	if(!empty($this->module['config']['kouling']['misMsg']))
	    	{
	    	$errorMsg = $this->module['config']['kouling']['misMsg'];
	        }
	        else
	        {
	        	$errorMsg='没有查到该口令';
	        }
	    	return $this->respText($errorMsg);
	    }
         $beginer = $result['beginer'];
         if(!empty($result['moneyCount']))
         {
         $total_amount = $result['moneyCount'];
    	 }
	    //*************************************************************************************
	    $re_openid   =  $this->message['from'];
	    $nick_name   =  $this->module['config']['kouling']['nick_name'];
	    $send_name   =  $this->module['config']['kouling']['send_name'];
	    $wishing     =  $this->module['config']['kouling']['wishing'];
	    $remark      =  $this->module['config']['kouling']['remark'];
	    $act_name    =  $this->module['config']['kouling']['act_name'];
	    $maxCount    =  $this->module['config']['kouling']['maxCount'];
	   
	    $rand_list   =  $this->module['config']['kouling']['rand_list'];
	    $money_list  =  $this->module['config']['kouling']['money_list'];
	    $small       =  $this->module['config']['kouling']['small'];
	    $money_list = explode('，',$money_list); 
	    $rand_list = explode('，',$rand_list); 
        
	   

	    if(empty( $total_amount))
	    {

	    	//如果没有设置金额
	    	if(count($rand_list)==0||count($rand_list)!=count($money_list))
	    	{
	       	//金额数和权值个数对不上
	    		return $this->respText('金额数和权值数不一致或空');
	    	}
			//获得金额小数部分
	    	$smallNum = rand(0,$small);
	    	$all_quan = 0;
	       //计算随机金额
	    	for ($i=0; $i <count($rand_list) ; $i++) 
	    	{ 
	    		$all_quan = $all_quan + $rand_list[$i];
	    	}
	    	if($all_quan==0)
	    	{
	    		return $this->respText('总权值是0');
	    	}
	    	$seed = rand(1,$all_quan);
	    	for ($i=0; $i <count($rand_list) ; $i++) 
	    	{ 
	    		if($seed > $rand_list[$i] )
	    		{
	    			$seed = $seed - $rand_list[$i];
	    		}
	    		else
	    		{
	    			break;
	    		}
	    	}


	    	$total_amount =  $money_list[$i] + $smallNum;
	    }
	    else
	    {
	    	//如果设置了金额，则使用该金额
	    	$total_amount=$total_amount*100;
	    }
        

	    $sql = 'SELECT redPackCount,lastTime FROM ' . tablename('pzh_packet2') . ' WHERE `uniacid` = :uniacid and `type` = :type and `openid` = :openid';
	    $params = array(':uniacid' => $_W['uniacid'],':type' => 'kouling' , ':openid' => $re_openid);
	    $account = pdo_fetch($sql, $params);

	    if(!$account)
	    {
	        //如果查询不到该用户
	    	$sql = 'INSERT INTO'.tablename('pzh_packet2') .' (`uniacid`,`openid`,`redPackCount`,`lastTime`,`type`) values ('.
	    		strval($_W['uniacid']).',\''.$re_openid.'\',0,'.strval($_W['timestamp']).',\'kouling\')'; 
			$result = pdo_query($sql);
	         // return $this->respText($sql);
		}
		else
		{
			 if($account['redPackCount']>=$maxCount)
			{
	          //红包个数超过设定值
	          // return $this->respText('您的红包已领完~');
		        //用户红包个数超过设定值
				if(!empty($this->module['config']['kouling']['limitMsg']))
				{
					$errorMsg = $this->module['config']['kouling']['limitMsg'];
				}
				else 
				{
					$errorMsg='您的红包领取达到限制';
				}
				 return $this->respText($errorMsg);
			}
		}  
       
		$packet = new  PZHSend();
		$result = $packet->pay($re_openid,$nick_name,$send_name,$total_amount,$wishing,$act_name,$remark,$this->module['config']['mchid'],$this->module['config']['appid'],$this->module['config']['password']);
        	// $result='success';
		if($result->return_code == 'FAIL' || $result ==  'fail')
		{

             //领取失败
			if(!empty($this->module['config']['kouling']['errorMsg']))
			{
				$errorMsg = $this->module['config']['kouling']['errorMsg'];
			}
			else 
			{
				$errorMsg=$result->return_msg;
			}
			 return $this->respText($errorMsg);
		}
		else
		{
             
			//发送成功  
			$this ->module['config']['kouling']['maxRedCount']  =$maxRedCount - 1 ;
			$this->saveSettings($this->module['config']);
			$sql = 'update '.tablename('pzh_packet2') .'   set `redPackCount` = ' .strval($account['redPackCount']+1) . 
			' ,`lastTime`= ' . strval($_W['timestamp']). ' WHERE `uniacid` = '.strval($_W['uniacid']).' and `type` = \'kouling\' and `openid` = \''.$re_openid.'\'  ';

			$result = pdo_query($sql);
            //随机红包记录数据
			$time = date('Y-m-d H:i:s',time());
			$sql = 'INSERT INTO'.tablename('pzh_record') .' (`uniacid`,`openid`,`moneyCount`,`time`,`type`,`state`) values ('.
				strval($_W['uniacid']).',\''.$re_openid.'\','.strval($total_amount/100.0).',\''.$time.'\',\'kouling\',\'success\')'; 
			pdo_query($sql);
           
           
           //减去口令个数
			
		    $sql = 'update '.tablename('pzh_kouling2') .' set `count` = '.strval($kouling_count-1).'  WHERE `uniacid` = '.$_W['uniacid'].' and `kouling` = \''.$content.'\''; 
			
			 $result =pdo_query($sql);
			
			if(!empty($this->module['config']['kouling']['successMsg']))
			{
				$successMsg = $this->module['config']['kouling']['successMsg'];

			}
			else 
			{
				$successMsg = '恭喜你获得一个红包~';
				
			}

			if(!empty($this->module['config']['kouling']['sharemoney'])&&$this->module['config']['kouling']['sharemoney']>=100&&$re_openid!=$beginer&&!empty($beginer))
			{
				//奖励分享者
             $result=  $packet->pay($beginer,$nick_name,$send_name,$this->module['config']['kouling']['sharemoney'],'您的好友成功获得了红包~',$act_name,$remark,$this->module['config']['mchid'],$this->module['config']['appid'],$this->module['config']['password']);
				if($result->return_code == 'FAIL' || $result ==  'fail')
				{
                   
				}
				else
				{
					$sql = 'INSERT INTO'.tablename('pzh_record') .' (`uniacid`,`openid`,`moneyCount`,`time`,`type`,`state`) values ('.
				  strval($_W['uniacid']).',\''.$beginer.'\','.strval($total_amount/100.0).',\''.$time.'\',\'kouling\',\'share\')'; 
			      pdo_query($sql);
				}
			}
           
			 return $this->respText($successMsg);
		}
	      // return $this->respText('红包还没准备好哦');
		$errorMsg='红包还没准备好哦~';
		include $this->template('fail');
		return;

	//************************************************************************************************************************************
	}


	//初始化数据库
	function init()
	{
	      //查看关注数据库是否存在
		global $_W;
		 if(empty($this->module['config']['kouling']['init']))
        {
           $this->module['config']['kouling']['init']='yes';
           $this->saveSettings($this->module['config']);
        }
        else
        {
        	return ;
        }
		
		$tableName = $_W['config']['db']['tablepre'].'pzh_packet2';
		$exists= pdo_tableexists('pzh_packet2');
		if(!$exists)
		{
			$sql = 'CREATE TABLE '.$tableName.' (
				`uniacid` int(10)  NOT NULL,
				`openid` varchar(35) NOT NULL,
				`redPackCount` int(10) NOT NULL,
				`lastTime` int(50) ,
				`type`  varchar(50),
				`remark`   varchar(50)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;';

		pdo_run($sql);
		}
		$tableName = $_W['config']['db']['tablepre'].'pzh_record';
		$exists= pdo_tableexists('pzh_record');
		if(!$exists)
		{
			$sql = 'CREATE TABLE '.$tableName.' (
				`uniacid` int(10)  NOT NULL,
				`openid` varchar(35) NOT NULL,
				`moneyCount` float(10) NOT NULL,
				`time` varchar(50) ,
				`type`  varchar(50),
				`state`  varchar(50),
				`remark`   varchar(50)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;';

		pdo_run($sql);
		}
			$tableName = $_W['config']['db']['tablepre'].'pzh_kouling2';
		$exists= pdo_tableexists('pzh_kouling2');
		if(!$exists)
		{
			$sql = 'CREATE TABLE '.$tableName.' (
				`uniacid` int(10)  NOT NULL,
				`acid` int(10) NOT NULL,
				`moneyCount` float(10),
				`kouling` varchar(50) ,
				`createtime` varchar(50) ,
				`state`  varchar(50),
				`usetime` varchar(50),
				`count`   int(10),
				`beginer` varchar(50),
				`remark`   varchar(50)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;';

		pdo_run($sql);
		}
		$tableName = $_W['config']['db']['tablepre'].'pzh_sharekouling2';
		$exists= pdo_tableexists('pzh_sharekouling2');
		if(!$exists)
		{
			$sql = 'CREATE TABLE '.$tableName.' (
				`uniacid` int(10)  NOT NULL,
				`acid` int(10) NOT NULL,
				`kouling` varchar(250),
				`openid` varchar(50),
				`createtime` varchar(50) ,
				`remark`   varchar(50)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
		pdo_run($sql);
		}
	}
}