<?php
/**
 * 口令红包模块微站定义
 *
 * @author pzh
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
include_once("PZHSendMoney.php");
define('MB_ROOT', IA_ROOT . '/addons/pzh_klmoney');
class Pzh_klmoneyModuleSite extends WeModuleSite {

	//保存参数
	public function doWebSetting()
	{
		global $_GPC,$_W;
	            // message('保存成功','','error');
		load()->func('file');
		mkdirs(MB_ROOT . '/zhengshu');
		if(!empty($_GPC['secret']))
		{
			$this->module['config']['secret'] = $_GPC['secret'];
		}
		if(!empty($_GPC['appid']))
		{
			$this->module['config']['appid'] = $_GPC['appid'];
		}
		if(!empty($_GPC['mchid']))
		{
			$this->module['config']['mchid'] = $_GPC['mchid'];
		}
		if(!empty($_GPC['password']))
		{
			$this->module['config']['password'] = $_GPC['password'];
		}
		if(!empty($_GPC['ip']))
		{
			$this->module['config']['ip'] = $_GPC['ip'];
		}
		if(!empty($_GPC['gongzhonghao']))
		{
			$this->module['config']['gongzhonghao'] = $_GPC['gongzhonghao'];
		}
		$result=true;
		if(!empty($_GPC['ca'])) {

			$ret =  file_put_contents(MB_ROOT . '/zhengshu/rootca.pem.'. $_W['uniacid'], trim($_GPC['ca']));

			$result = $ret && $result;
		}
		if(!empty($_GPC['cert'])) {

			$ret =  file_put_contents(MB_ROOT . '/zhengshu/apiclient_cert.pem.'. $_W['uniacid'], trim($_GPC['cert']));
			$result = $ret && $result;

		} 
		if(!empty($_GPC['key'])) {

			$ret =  file_put_contents(MB_ROOT . '/zhengshu/apiclient_key.pem.'. $_W['uniacid'], trim($_GPC['key']));
			$result = $ret && $result;
		} 
		$this->saveSettings($this->module['config']);
		if(!$result)
		{
			message('证书保存失败，请确认'.MB_ROOT .'/zhengshu 文件夹有写入权限','','error');

		}
		message('数据保存成功！','','success');

	}
	 //口令红包设置入口
	public function doWebKouling() 
	{
		//口令红包入口
		load()->func('tpl');
		include $this->template('kouling');
	}
	//保存口令红包参数
	public function doWebKoulingsave()
	{
       //保存口令红包参数
		global $_W,$_GPC;

              //领取成功
		// $this->module['config']['kouling']['successUrl']=  $_GPC['successUrl'];
		$this->module['config']['kouling']['successMsg']=  $_GPC['successMsg'];
    //领取失败
		$this->module['config']['kouling']['errorMsg']=  $_GPC['errorMsg'];
		// $this->module['config']['kouling']['errorUrl']=  $_GPC['errorUrl'];
    //用户领取达到限制
		$this->module['config']['kouling']['limitMsg']=  $_GPC['limitMsg'];
		// $this->module['config']['kouling']['limitUrl']=  $_GPC['limitUrl'];
    //红包已经发完
		$this->module['config']['kouling']['sendAllMsg']=  $_GPC['sendAllMsg'];
		// $this->module['config']['kouling']['sendAllUrl']=  $_GPC['sendAllUrl'];
    //领取频繁文案
		$this->module['config']['kouling']['havegetMsg']=  $_GPC['havegetMsg'];
		// $this->module['config']['kouling']['havegetUrl']=  $_GPC['havegetUrl'];
   //口令失效活已被领取
		$this->module['config']['kouling']['misMsg']=  $_GPC['misMsg'];

     //活动时间
    $this->module['config']['kouling']['nobegin'] = $_GPC['nobegin'];
    $this->module['config']['kouling']['haveend'] = $_GPC['haveend'];
    $this->module['config']['kouling']['begin_time'] = $_GPC['begin_time'];
    $this->module['config']['kouling']['end_time'] = $_GPC['end_time'];
    //有效口令个数
    $this->module['config']['kouling']['canuse']= $_GPC['canuse'];
    //口令总个数
    $this->module['config']['kouling']['koulingcount']= $_GPC['koulingcount'];
     //分享奖励
    $this->module['config']['kouling']['sharemoney'] = $_GPC['sharemoney'];
    //冷却时间
    $this->module['config']['kouling']['cooling'] = $_GPC['cooling'];
    //尝试次数
    $this->module['config']['kouling']['cantry'] = $_GPC['cantry'];
    //是否需要关注
    $this->module['config']['kouling']['sub']= $_GPC['sub'];
        if(!empty($_GPC['useCount']))
        {
        	$this->module['config']['kouling']['useCount'] = $_GPC['useCount'];
        }
        else 
        {
        	$this->module['config']['kouling']['useCount'] = 1;
        }
       
		if(!empty($_GPC['key'])) 
		{
			$this->module['config']['kouling']['key'] = $_GPC['key'];
		}
		if(!empty($_GPC['money_list'])) 
		{

			$this->module['config']['kouling']['money_list'] =  $_GPC['money_list'];
		}
		if(!empty($_GPC['rand_list'])) 
		{


			$this->module['config']['kouling']['rand_list'] =  $_GPC['rand_list'];
		}

		
	       	//随机金额小数部分
			$this->module['config']['kouling']['small'] = $_GPC['small'];
		

		if(!empty($_GPC['nick_name'])) 
		{
			$this->module['config']['kouling']['nick_name'] = $_GPC['nick_name'];
		}
		if(!empty($_GPC['send_name'])) 
		{
			$this->module['config']['kouling']['send_name'] = $_GPC['send_name'];
		}
		if(!empty($_GPC['wishing'])) 
		{
			$this->module['config']['kouling']['wishing'] = $_GPC['wishing'];
		}
		if(!empty($_GPC['act_name'])) 
		{
			$this->module['config']['kouling']['act_name'] = $_GPC['act_name'];
	          // message($_GPC['act_name'],'','success');
		}

		if(!empty($_GPC['remark'])) 
		{
			$this->module['config']['kouling']['remark'] = $_GPC['remark'];
		}
		if(!empty($_GPC['maxCount'])) 
		{
			$this->module['config']['kouling']['maxCount'] = $_GPC['maxCount'];
		}
		if(!empty($_GPC['addressLimit'])) 
		{
			$this->module['config']['kouling']['addressLimit'] = $_GPC['addressLimit'];
		}
		else
		{
			$this->module['config']['kouling']['addressLimit'] = '';
		}
		if(!empty($_GPC['maxRedCount'])) 
		{
			$this->module['config']['kouling']['maxRedCount'] = $_GPC['maxRedCount'];
		}
		else 
		{
			$this->module['config']['kouling']['maxRedCount'] =0 ;
		}

		$this->saveSettings($this->module['config']);



		message('保存成功！','','success');
		return ;
	}
 	//发送口令红包
	public function doMobileSendkouling()
	{

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
            	echo $errorMsg;
            	return;
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
            	echo $errorMsg;
            	return;
            }
         
			//借权成功
            	$content = $_GPC['kouling'];

            	if(empty($content))
            	{
            		echo "没有接收到口令";
            		return;
            	}
            	
            	$re_openid   =  $_W['openid'];
            	if(empty($re_openid))
            	{
            		echo '获取不到openid';
            		return;
            	}

            	 $nick_name   =  $this->module['config']['kouling']['nick_name'];
	    $send_name   =  $this->module['config']['kouling']['send_name'];
	    $wishing     =  $this->module['config']['kouling']['wishing'];
	    $remark      =  $this->module['config']['kouling']['remark'];
	    $act_name    =  $this->module['config']['kouling']['act_name'];
	    $maxCount    =  $this->module['config']['kouling']['maxCount'];
	    $maxRedCount =  $this->module['config']['kouling']['maxRedCount'];
	    $rand_list   =  $this->module['config']['kouling']['rand_list'];
	    $money_list  =  $this->module['config']['kouling']['money_list'];
	    $small       =  $this->module['config']['kouling']['small'];
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
	    	 echo  $errorMsg;
	    	return;
	    }
            	
			 //判断用户操作是否过于频繁
               session_start(); 

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
            			echo $errorMsg;
            			return;
            		}
            		else
            		{
            			if($this->module['config']['kouling']['cantry']==1)
            				$_SESSION[$re_openid] = $_W['timestamp'];
            			else 
            				$_SESSION[$re_openid] =1;

            		}
            	}

              //判断是否关注过
            	if($this->module['config']['kouling']['sub']=='否')
            	{
            		$follow = true;
            	}
            	else
            	{
            		$follow = $this->judgeFollow();
            	}
            	if($follow==false)
            	{

            		$errorMsg='请先关注公众号:'.$this->module['config']['gongzhonghao'];
                   
            		echo  $errorMsg;
            		return;
            	}
            	//地区限制
            	
            	$addressLimit = $this->module['config']['kouling']['addressLimit'];
        	if(!empty($addressLimit))
        	{
		        $url = 'http://www.ip138.com/ips1388.asp?ip='.CLIENT_IP.'&action=2'; //这儿填页面地址
		        $info=file_get_contents($url);
		         // message(json_encode($url),'','error');
		        $content2=iconv("GBK", "UTF-8//IGNORE", $info);
		        preg_match('|<li>(.*?)<\/li>|i',$content2,$userAddress);
		        
		        $limit = explode('，',$addressLimit); 
		        $flag = 0;
		        for ($i=0; $i < count($limit); $i++) 
		        { 
		        	if(strpos($userAddress[1],$limit[$i]))
		        	{

		        		$flag=1;
		        		break;
		        	}
		        }
		        if($flag == 0)
		        {
		        	$errorMsg='您的位置不在本活动范围内哦~';
		        	echo $errorMsg;
		        	return;
		        }
		    }
		    $this ->init();
		 

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
	    	echo  $errorMsg;
	    	return;
	    }
         $beginer = $result['beginer'];
         $moneyCount = $result['moneyCount'];
	    //*************************************************************************************
	    
	    $nick_name   =  $this->module['config']['kouling']['nick_name'];
	    $send_name   =  $this->module['config']['kouling']['send_name'];
	    $wishing     =  $this->module['config']['kouling']['wishing'];
	    $remark      =  $this->module['config']['kouling']['remark'];
	    $act_name    =  $this->module['config']['kouling']['act_name'];
	    $maxCount    =  $this->module['config']['kouling']['maxCount'];
	    $maxRedCount =  $this->module['config']['kouling']['maxRedCount'];
	    $rand_list   =  $this->module['config']['kouling']['rand_list'];
	    $money_list  =  $this->module['config']['kouling']['money_list'];
	    $small       =  $this->module['config']['kouling']['small'];
	    $money_list = explode('，',$money_list); 
	    $rand_list = explode('，',$rand_list); 
        
	    
       
	    if(count($rand_list)==0||count($rand_list)!=count($money_list))
	    {
	       	//金额数和权值个数对不上
             echo  '金额数和权值数不一致或空';
	    	return;
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
	    	  echo '总权值是0';
	    	  return ;
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

	   
        if(empty($moneyCount) )
        {
        	 $total_amount =  $money_list[$i] + $smallNum;
        }
        else
        {
        	 $total_amount = $moneyCount*100;
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
				   echo  $errorMsg;
	    	        return;
			}
		}  

		$packet = new  PZHSend();
	     $weid = $_W['uniacid'];
		$result = $packet->pay($_COOKIE['pzh_openid'.$weid],$nick_name,$send_name,$total_amount,$wishing,$act_name,$remark,$this->module['config']['mchid'],$this->module['config']['appid'],$this->module['config']['password']);
        	
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
			  echo  $errorMsg;
	    	        return;
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
			pdo_query($sql);
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
             $result=  $packet->pay($beginer,$nick_name,$send_name,$this->module['config']['kouling']['sharemoney'],'您的好友成功猜对了口令~',$act_name,$remark,$this->module['config']['mchid'],$this->module['config']['appid'],$this->module['config']['password']);
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

			  echo  $successMsg;
	    	        return;
	    	
		}

}


    //获得红包口令

public function doMobileGetkouling()
{
	global $_GPC,$_W;
    	//如果有口令
     
	
	   $weid = $_W['uniacid'];
        if(empty($_COOKIE['pzh_openid'.$weid]))
            {

            	$url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_klmoney&do=xoauth";

            	$xoauthURL = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_klmoney&do=getkouling";
            	setcookie("xoauthURL",$xoauthURL, time()+3600*(24*5));

            	$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->module['config']['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
            	header("location:$oauth2_code");
            }
            else
            {
	if(empty($_W['openid']))
	{
		//如果获取不了openid则判断非法进入
		  $errorMsg='请先关注公众号:'.$this->module['config']['gongzhonghao'];
		 include $this->template('fail');
		 return;
	}
	if(empty( $_COOKIE['kouling'.$weid]))
	{
		$this->init();
        //如果没有缓存则查数据库
		 $sql = 'SELECT kouling FROM ' . tablename('pzh_sharekouling2') . ' WHERE `uniacid` = :uniacid and `acid` = :acid and `openid` = :openid';
	    $params = array(':uniacid' => $_W['uniacid'],':acid' => $_W['acid'] , ':openid' => $_W['openid']);
	    $account = pdo_fetch($sql, $params);
          if(!$account)
          {
          	//如果没有记录，则生成一个
          	$count = $this->module['config']['kouling']['koulingcount'];
            $arr= array();
            
            for ($i=0; $i < $count; $i++) 
            { 
            	array_push($arr, $i);
            }
            //生成有效的数的下标
            $result_arr = array();
            for ($i=0; $i <$this->module['config']['kouling']['canuse']; $i++) 
            { 

            	$offset = rand(0,100)%($count-$i);
            	array_push($result_arr, $arr[$offset]);
            	array_splice($arr, $offset, 1); 
            }
           
          	for($i=0;$i<$count;$i++)
          	{
               
          	  $kouling = $this->great_rand();
          	  if($i==0)
          	  {
          	  	$kouling_str = $kouling;
          	  }
          	  else
          	  { 
          	    $kouling_str=$kouling_str.','.$kouling;
          	  }

          	  if(in_array($i,$result_arr ,true))
          	  {
                
          	  	//如果是有效口令
          	     $time = date('Y-m-d H:i:s',time());
          	     $sql = 'INSERT INTO'.tablename('pzh_kouling2') .' (`uniacid`,`acid`,`kouling`,`createtime`,`state`,`count`,`beginer`) values ('.
				 strval($_W['uniacid']).','.strval($_W['acid']).',\''.strval($kouling).'\',\''.$time.'\',\'unuse\','.$this->module['config']['kouling']['useCount'].',\''.$_COOKIE['pzh_openid'.$weid].'\')'; 
                 
			     pdo_query($sql);
          	   }
          	}
		 
		   //插入口令记录
		
		$sql = 'INSERT INTO'.tablename('pzh_sharekouling2') .' (`uniacid`,`acid`,`createtime`,`kouling`,`openid`) values ('.
			strval($_W['uniacid']).','.strval($_W['acid']).',\''.$time.'\',\''.$kouling_str.'\',\''.$_W['openid'].'\')'; 
		$result = pdo_query($sql);
		setcookie("kouling",$kouling_str, time()+3600*1);
		 $kouling = $kouling_str;
		}
		else
		{
			//如果有记录，则用原来的
			 $kouling_arr = explode(',',$account['kouling']);
			
			  $kouling = $account['kouling'];
		}
	}
	else
	{
		//如果有缓存，则用原来的
			 $kouling_arr = explode(',',$_COOKIE['kouling']);
			 
			 $kouling = $_COOKIE['kouling'];
	}
	
	
   if(empty($this->module['config']['kouling']['fenxaingURL']))
   {
   	$url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_klmoney&do=sharekouling&kouling=".$kouling;
   }
   else
   {
   	 $kouling_arr = explode(',',$kouling);
   	 $num = count($kouling_arr);
   	 $tmp='';
   	 for ($i=1; $i <=$num ; $i++) 
   	 { 
   	 	$tmp=$tmp.'kouling'.$i.'='.$kouling_arr[$i-1].'&';
   	 }
   	$url = $this->module['config']['kouling']['fenxaingURL'].$tmp;
     }
	header("location:$url");
}
}
    //分享出去的口令入口
    public function doMobileSharekouling()
    {
        global $_W,$_GPC;
      
        //内容设置
        $kouling = $_GPC['kouling'];
        $kouling_arr = explode(',',$kouling);
        $kouling_count = count($kouling_arr);
        $tail = $this->module['config']['kouling']['tail'];
        $title = $this->module['config']['kouling']['title'];
        if(empty($this->module['config']['kouling']['top']))
        {
        	//如果没有设置百分比，则默认74
        	$top_percent = 74;
        }
        else
        {
        	//如果有设置百分比，用设置的
        	$top_percent = $this->module['config']['kouling']['top'];
        }
        //背景图片
        if(!empty($this->module['config']['kouling']['back_img']))
        {
        	$back_img=$_W['attachurl'].$this->module['config']['kouling']['back_img'];
        }
        else 
        {
        	$back_img = '../addons/pzh_klmoney/template/mobile/img/2.jpg';
        }
        //字体颜色设置
        $title_color = empty($this->module['config']['kouling']['title_color'])?'#FCEE38':$this->module['config']['kouling']['title_color'];
        $tail_color = empty($this->module['config']['kouling']['tail_color'])?'#FCEE38':$this->module['config']['kouling']['tail_color'];
        $kling_color = empty($this->module['config']['kouling']['kling_color'])?'#FCEE38':$this->module['config']['kouling']['kling_color'];
        $theme = $this->module['config']['kouling']['theme'];
        include $this->template('fenxiang');

		return;

    }
    public function doWebSetface()
    {
    	//封面设置入口
    	load()->func('tpl');
    	include $this->template('setfengmian');
    }
    public function doWebFenxiangsave()
    {
    	//保存封面设置
    	global $_W,$_GPC;
    	$this->module['config']['kouling']['tail_color'] =$_GPC["tail_color"];
    	$this->module['config']['kouling']['title_color'] =$_GPC["title_color"];
    	$this->module['config']['kouling']['kling_color'] =$_GPC["kling_color"];
    	$this->module['config']['kouling']['tail'] =$_GPC["tail"];
    	$this->module['config']['kouling']['title'] =$_GPC["title"];
    	$this->module['config']['kouling']['top'] =$_GPC["top"];
    	$this->module['config']['kouling']['fenxaingURL'] =$_GPC["fenxaingURL"];
    	$this->module['config']['kouling']['theme'] =$_GPC["theme"];
    	$this->module['config']['kouling']['back_img'] = $_GPC['back_img'];
    	$this->saveSettings($this->module['config']);
    	  message('保存成功','','success');


    }
	public function doWebReset()
	{
	  ///数据清零
		$this -> init();
		global $_GPC,$_W;
		$type = $_GPC['typeName'];
		$sql = 'update '.tablename('pzh_packet2') .'   set `redPackCount` = 0  WHERE `uniacid` = '.strval($_W['uniacid']).' and `type` = \''.$type.'\'  ';
		$result = pdo_query($sql);
		message('活动重新开启!用户领取红包次数清零！以前领取过红包的用户可以再次领取红包！','','success');
	}

	//清空现有口令
	public function doWebCleardata()
	{
		global $_W,$_GPC;
		$this->init();
		$sql = 'delete from '.tablename('pzh_sharekouling2') .' where `uniacid` = '.strval($_W['uniacid']).'';
		$result = pdo_query($sql);
		$sql = 'delete from '.tablename('pzh_kouling2') .' where `uniacid` = '.strval($_W['uniacid']).'';
		$result = pdo_query($sql);
		message('清除口令成功','','success');

	}
    //红包记录查询
    public function doWebRecord()
		{
            global $_GPC,$_W;
            $this -> init();
            $kind = $_GPC['kind'];
            $startTime = $_GPC['begainDate'];
            $endTime = $_GPC['endDate'];
         
		     $sql = 'SELECT openid ,moneyCount ,time, type ,state FROM ' . tablename('pzh_record') . ' WHERE `uniacid` = :uniacid ';
		     if(!empty($startTime))
		     {
		     	$sql=$sql.'and `time` >= \''.$startTime.'\' and `time` <= \''.$endTime.'\' ';
		     }
		     else 
		     {
		     	$startTime = date("Y-m-d",time());
		     	$endTime = date("Y-m-d",strtotime("+1 day"));
		     	$sql=$sql.'and `time` >= \''.$startTime.'\' and `time` <= \''.$endTime.'\' ';
		     }
		     if(!empty($kind)&&$kind!='全部')
		     {
		     	if($kind == '口令')
		     		$type = 'kouling';
                 $sql = $sql.'and `type` = \''.$type.'\'';
		     }
		   // message($sql,'','success');
	       $params = array(':uniacid' => $_W['uniacid'] );
	       $account = pdo_fetchall($sql, $params);
	       $result = array();
	       for ($i=0; $i <count($account) ; $i++) 
	       { 
	       	   
	       	array_push($result, array_merge(array('id'=>$i+1),$account[$i]));
	       }
				load()->func('tpl');
				include $this->template('search');
			
	}
	//增删改查口令
    public	function doWebRebuild()
    {
         load()->func('tpl');
         $createBgTime=$this->module['config']['kouling']['createBgTime'];
    	$createEdTime = $this->module['config']['kouling']['createEdTime'];
		include $this->template('adssetting');
    }
    //查询现有口令
    public function dowebSearch()
    {
    	global $_W,$_GPC;
    	$this->module['config']['kouling']['createBgTime'] =$_GPC['createBgTime'];
    	$this->module['config']['kouling']['createEdTime'] =$_GPC['createEdTime'];
    	$this->module['config']['kouling']['content'] =$_GPC["content"];
    	$createBgTime=$this->module['config']['kouling']['createBgTime'];
    	$createEdTime = $this->module['config']['kouling']['createEdTime'];
    	// and `time` >= \''.$startTime.'\' and `time` <= \''.$endTime.'\' 
    	$sql = 'SELECT kouling ,moneyCount ,count, createtime  FROM ' . tablename('pzh_kouling2') . ' WHERE `uniacid` = :uniacid  and `createtime` >= :createBgTime and `createtime` <= :createEdTime ';
        if(!empty($_GPC["content"]))
        {
        	$sql=$sql . ' and `kouling` like \'%'.$_GPC["content"].'%\'';
        }
    	$params = array(':uniacid' => $_W['uniacid'],':createBgTime'=> $createBgTime,':createEdTime' =>$createEdTime);
    	$this->saveSettings($this->module['config']);
    	$account = pdo_fetchall($sql, $params);
	    $result = array();
        for ($i=0; $i <count($account) ; $i++) 
	       { 
	       	array_push($result, array_merge(array('id'=>$i+1),$account[$i]));
	       }
	        load()->func('tpl');
		include $this->template('adssetting');

    }
    //增加口令
    public function dowebAddkouling()
    {
    	global $_GPC,$_W;
    	$kouling = $_GPC['content'];
    	if(empty($kouling))
    	{
    		echo '口令内容不能为空！';
    		return;
    	}

    	$money = $_GPC['money'];
    	if(empty($money))
    	{
    	echo '金额不能为空';
    	return ;
        }

    	$count = $_GPC['count'];
    	if(empty($count))
    	{
    	echo '使用次数不能为空';
    	return ;
        }

         $time = date('Y-m-d H:i:s',time());
    	$sql = 'INSERT INTO'.tablename('pzh_kouling2') .' (`uniacid`,`createtime`,`kouling`,`count`,`moneyCount`) values ('.
			strval($_W['uniacid']).',\''.$time.'\',\''.$kouling.'\','.$count.' ,'.strval($money/100).')'; 
		$result = pdo_query($sql);
		echo '添加成功!';
		return;

    }
     //修改口令
    public function dowebUpdatekouling()
    {
    	global $_GPC,$_W;
    	$kouling = $_GPC['content'];
    	if(empty($kouling))
    	{
    		echo '口令内容不能为空！';
    		return;
    	}

    	$money = $_GPC['money'];
    	if(empty($money))
    	{
    	echo '金额不能为空';
    	return ;
        }

    	$count = $_GPC['count'];
    	if(empty($count))
    	{
    	echo '使用次数不能为空';
    	return ;
        }

         // $time = date('Y-m-d H:i:s',time());
   //  	$sql = 'INSERT INTO'.tablename('pzh_kouling2') .' (`uniacid`,`createtime`,`kouling`,`count`,`moneyCount`) values ('.
			// strval($_W['uniacid']).',\''.$time.'\',\''.$kouling.'\','.$count.' ,'.strval($money/100).')'; 
        $sql = 'update '.tablename('pzh_kouling2') .' set `count` = '.$count.', `moneyCount`='.strval($money/100).' where `uniacid`='.
        strval($_W['uniacid']).' and `kouling` = \''.$kouling.'\'';
		$result = pdo_query($sql);
		echo '修改成功!';
		return;
    }
//删除口令
    public function dowebDeletekouling()
    {
    	global $_GPC,$_W;
    	$kouling = $_GPC['content'];
    	if(empty($kouling))
    	{
    		echo '口令内容不能为空！';
    		return;
    	}
     
        $sql = 'delete from '.tablename('pzh_kouling2') .' where `uniacid`='.
        strval($_W['uniacid']).' and `kouling` = \''.$kouling.'\'';
		$result = pdo_query($sql);
		echo '删除成功!';
		return;
    }
    //输入口令界面设置
    public function doWebInput()
    {
    	load()->func('tpl');
		include $this->template('input');
    }
    //输入封面参数保存
    public function doWebSaveinput()
    {
       global $_W,$_GPC;
       // message(json_encode($_GPC['input_theme']),'','');
       // return;
       $this->module['config']['kouling']['input_img']=$_GPC['input_img'];
       $this->module['config']['kouling']['input_top']=$_GPC['input_top'];
       $this->module['config']['kouling']['input_theme']=$_GPC['input_theme'];
       $this->module['config']['kouling']['input_color']=$_GPC['input_color'];
       $this->module['config']['kouling']['link'] = $_GPC['link'];
       $this->module['config']['kouling']['link_color'] = $_GPC['link_color'];
       $this->saveSettings($this->module['config']);

       message('保存成功!','','success');
    }
    //借权输入红包入口
public function doMobileEntrance()
{

    global $_W,$_GPC;
       $weid = $_W['uniacid'];
        //判断是否成功借权
            if(empty($_COOKIE['pzh_openid'.$weid]))
            {
    
            	$url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_klmoney&do=xoauth";

            	$xoauthURL = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m=pzh_klmoney&do=entrance";
            	setcookie("xoauthURL",$xoauthURL, time()+3600*(24*5));

            	$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->module['config']['appid']."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
            	header("location:$oauth2_code");
            }
            else
            {
            if(empty($this->module['config']['kouling']['input_img']))
            {
               
				$img='../addons/pzh_klmoney/template/mobile/img/back.jpg';            	
            }
            else
            {
            	$img=$_W['attachurl'].$this->module['config']['kouling']['input_img'];
            }
            if(empty($this->module['config']['kouling']['input_theme']))
            {
            	$title = '输入口令';
            }
            else
            {
            	$title = $this->module['config']['kouling']['input_theme'];
            }
            if(empty( $this->module['config']['kouling']['input_color']))
            {
            	$color='#ffffff';
            }
            else
            {
            	$color=$this->module['config']['kouling']['input_color'];
            }
            if(empty($this->module['config']['kouling']['input_top']))
            {
                $height = 10;
            }
            else 
            {
            	$height = $this->module['config']['kouling']['input_top'];
            }
            $link = $this->module['config']['kouling']['link'];
            if(empty( $this->module['config']['kouling']['link_color']))
            {
            	$link_color='#ffd43f';
            }
            else
            {
            	$link_color=$this->module['config']['kouling']['link_color'];
            }
			include $this->template('entrance');
			return ;
			}
}
	//初始化数据库
	function init()
	{

	      //查看关注数据库是否存在
		global $_W;
      
           $this->module['config']['kouling']['init']='yes';
           $this->saveSettings($this->module['config']);
     
		
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
//aouth消息路由
	public function doMobileXoauth() 
	{

		global $_W,$_GPC;

		if ($_GPC['code']=="authdeny" || empty($_GPC['code']))

		{

			exit("授权失败");

		}

		load()->func('communication');

		$appid=$this->module['config']['appid'];

		$secret=$this->module['config']['secret'];

		$state = $_GPC['state'];

		$code = $_GPC['code'];

		$oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";

		$content = ihttp_get($oauth2_code);

		$token = @json_decode($content['content'], true);

		if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) 

		{
			echo '<h1>获取微信公众号授权'.$code.'失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'].'<h1>';
			exit;
		}
		$from_user = $token['openid'];
		$weid = $_W['uniacid'];
		setcookie('pzh_openid'.$weid,$from_user, time()+3600*(24*5));

		$url=$_COOKIE["xoauthURL"];   

		header("location:$url");

		exit();

	}
	//生成随机口令
	  public function great_rand()
    {
        $str = '1234567890';
        $t1="";
        for($i=0;$i<8;$i++)
        {
            $j=rand(0,9);
            $t1 = $t1. $str[$j];
        }
        return $t1;
    }
    public function judgeFollow()
{
	global $_W,$_GPC;
    // message(json_encode($_W['fans']),'','');
	if($_W['fans']['follow']=='1')
	{
		return true;
	}
	else return false;

}


}