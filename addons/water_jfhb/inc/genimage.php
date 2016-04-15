<?php  



 function createbarcode_text($obj){
     $ret=array();
     $barcode = array(
          'expire_seconds' => '',
          'action_name' => '',
          'action_info' => array(
              'scene' => array('scene_id' => ''),
          ),
      );
      $uniacccount = WeAccount::create($obj['acid']); 

         
         $data=array("uniacid"=>$obj['uniacid'],"haibao_id"=>$obj['id'],
          "openid"=>$obj['from_user'], "nickname"=>$obj['nickname'],'createtime'=>time());
         $jfhb_qrcode= pdo_insert("jfhb_qrcode",$data);
         if (empty($jfhb_qrcode)){
           $ret=array("code"=>"-1","msg"=>"插入二维码表错误");
           return $ret;
         }  
          $id = pdo_insertid();

          
        $barcode['action_info']['scene']['scene_id'] = 10000+$id;
     
        $barcode['action_name'] = 'QR_LIMIT_SCENE';
        $result = $uniacccount->barCodeCreateFixed($barcode); 
        if (is_error($result)){
           $ret=array("code"=>"-2","msg"=>$result['message']);
           return $ret;
        }   
        $qrinfo=array('scene_id'=>$id,'from_user'=>$obj['from_user'],'ticket'=>$result['ticket']);
        newqr($qrinfo);

        $update['qr_img'] ="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$result['ticket'];
     
        $update['qr_img']=createImageUrl($obj,$update['qr_img']);
        $update['createtime'] = TIMESTAMP;
        pdo_update('jfhb_qrcode', $update, array('id' => $id));
        $ret=array("code"=>"1","qr_img"=>$update['qr_img'],"haibao_id"=>$id);
        return $ret;

   }

   

    function remote_file($src_file) {
      global $_W;
      if (!empty($_W['setting']['remote']['type'])) {   
     	load()->func('file');	
	    mkdirs("../attachment/water_jfhb/".date("Y/m/d"));
	    $target_file="../attachment/water_jfhb/".date("Y/m/d")."/".time().rand(1,1000).".jpg";
		if (!@copyfiles(tomedia($src_file),$target_file)){
		   message("远程文件失败");
		 }
		 return tomedia($target_file);
	   } else {
		  if (!strexists($src_file, 'http://') && !strexists($src_file, 'https://')) {
		    $src_file= ATTACHMENT_ROOT."/".$src_file;
		  } else {
			$src_file= tomedia($src_file); 
		  }
		  return 	$src_file;	
		}
	
   }
   
   function createImageUrl($param,$qr_file) {
		global $_W,$_GPC;
		$file="";
		load()->func('file');	
		load()->func('logging');	
		mkdirs("../attachment/water_jfhb/".date("Y/m/d"));
		$target_file="../attachment/water_jfhb/".date("Y/m/d")."/".time().rand(1,1000).".jpg";
	    $t1= microtime(true);	
	    $ret=mergeImage(remote_file($param['hb_img']), $qr_file, $target_file, $param);
	    if (!$ret){
	        WeUtility::logging('createImageUrl error', "mergeImage");	
            return "";
        }  
        $t2 = microtime(true);
	    if (!empty($param['avatar']) && !empty($param['avatarenable'])){
	      $ret=mergeAvatarImage($target_file, $param['avatar'], $target_file, $param); 
	      if (!$ret){
	       WeUtility::logging('createImageUrl error', "mergeAvatarImage");
            return "";
          }            
	    }
	    if (!empty($param['nickname']) && !empty($param['nameenable'])){ 
	      writeText($target_file, $target_file,$param['nickname'], $param);  
	    }
        return 	$target_file;  
            
	}


   function imagez($bg) {
        $bgImg = @imagecreatefromjpeg($bg);
        if (FALSE == $bgImg) {
            $bgImg = @imagecreatefrompng($bg);
        }
        if (FALSE == $bgImg) {
            $bgImg = @imagecreatefromgif($bg);
        }
        return $bgImg;
    }
   
   
     function mergeAvatarImage($bg, $qr, $out, $param) {  	
        list($bgWidth, $bgHeight) = getimagesize($bg);       
        list($qrWidth, $qrHeight) = getimagesize($qr);       
        $bgImg = imagez($bg);
        $qrImg = imagez($qr);
        $ret=imagecopyresized($bgImg, $qrImg,$param['avatarleft'], $param['avatartop'],
        0, 0, $param['avatarwidth'], $param['avatarheight'],$qrWidth, $qrHeight);  
        if (!$ret){
          
          return false;
        }     
        ob_start();
        imagejpeg($bgImg, NULL, 100);
        $contents = ob_get_contents();
        ob_end_clean();
        imagedestroy($bgImg);
        imagedestroy($qrImg);
        $fh = fopen($out, "w+");
        fwrite($fh, $contents);
        fclose($fh);
        return true;
    }
   	 
      function mergeImage($bg, $qr, $out, $param) {  	
        list($bgWidth, $bgHeight) = getimagesize($bg);       
        list($qrWidth, $qrHeight) = getimagesize($qr);       
        $bgImg = imagez($bg);
        $qrImg = imagez($qr);
        $ret=imagecopyresized($bgImg, $qrImg,$param['qrleft'], $param['qrtop'],
        0, 0, $param['qrwidth'], $param['qrheight'],$qrWidth, $qrHeight);  
        if (!$ret){
          return false;
        }     
        ob_start();
        imagejpeg($bgImg, NULL, 100);
        $contents = ob_get_contents();
        ob_end_clean();
        imagedestroy($bgImg);
        imagedestroy($qrImg);
        $fh = fopen($out, "w+");
        fwrite($fh, $contents);
        fclose($fh);
        return true;
   }



   function createbarcode($obj){
     $ret=array();
     $data=array();
     $barcode = array(
          'expire_seconds' => '',
          'action_name' => '',
          'action_info' => array(
              'scene' => array('scene_id' => ''),
          ),
      );
       
      $jfhb_qrcode= pdo_fetch("SELECT media_id,id,qr_img FROM ".tablename('jfhb_qrcode').
          " WHERE uniacid = '{$obj['uniacid']}'  and openid='{$obj['from_user']}' and haibao_id='{$obj['id']}'");
     
      if (!empty($jfhb_qrcode['media_id'])) {
      	 return array_merge(array("code"=>1),$jfhb_qrcode);
      }  
      
      load()->classs('weixin.account');
      $accObj= WeixinAccount::create($obj['acid']); 
          
      if (empty($jfhb_qrcode)){
      	  $data=array("uniacid"=>$obj['uniacid'],"haibao_id"=>$obj['id'],
          "openid"=>$obj['from_user'], "nickname"=>$obj['nickname'],'createtime'=>time());
      
         $scene_id = getNextAvaliableSceneID(); 
         $barcode['action_info']['scene']['scene_id'] = 10000+$scene_id;    
         $barcode['action_name'] = 'QR_LIMIT_SCENE';
         $result = $accObj->barCodeCreateFixed($barcode); 
         if (is_error($result)){
            $ret=array("code"=>"-2","msg"=>$result['message']);
            return $ret;
         } 
         $data['qr_img'] ="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$result['ticket'];     
         $data['qr_img']=createImageUrl($obj,$data['qr_img']); 
         if (empty($data['qr_img'])){
           $ret=array("code"=>"-3","msg"=>"qr_img error");
           return $ret;
         }
         
         WeUtility::logging('qr_img', $data['qr_img']);
         $qrinfo=array('scene_id'=>$scene_id,'from_user'=>$obj['from_user'],'ticket'=>$result['ticket']);   
         newqr($qrinfo); 
         $token=$accObj->fetch_token();       
         $data['media_id']=uploadImage($token,$data['qr_img']);
         $data['scene_id']=$scene_id;
         $data['media_time'] = TIMESTAMP;
         pdo_insert('jfhb_qrcode', $data);
         $ret=array("code"=>"1","qr_img"=>$data['qr_img'],"media_id"=>$data['media_id'],"media_time"=>$data['media_time']);
         return $ret;                 
      }  else {
      	 $id=$jfhb_qrcode['id'];
      	 if (empty($jfhb_qrcode['qr_img'])) { 
       	   $scene_id = getNextAvaliableSceneID(); 
           $barcode['action_info']['scene']['scene_id'] = 10000+$scene_id;    
           $barcode['action_name'] = 'QR_LIMIT_SCENE';
           $result = $accObj->barCodeCreateFixed($barcode); 
           if (is_error($result)){
             $ret=array("code"=>"-2","msg"=>$result['message']);
             return $ret;
           } 
           $data['scene_id']=$scene_id;
           $data['qr_img'] ="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$result['ticket'];     
           $data['qr_img']=createImageUrl($obj,$data['qr_img']); 
           if (empty($data['qr_img'])){
             $ret=array("code"=>"-3","msg"=>"qr_img error");
             return $ret;
           }
           $qrinfo=array('scene_id'=>$scene_id,'from_user'=>$obj['from_user'],'ticket'=>$result['ticket']);   
           newqr($qrinfo); 
         } else {
         	 $data['qr_img']=$jfhb_qrcode['qr_img'];
         }
          
         $token=$accObj->fetch_token();       
         $data['media_id']=uploadImage($token,$data['qr_img']);
         $data['media_time'] = TIMESTAMP;
         pdo_update('jfhb_qrcode', $data,array("id"=>$id));
         $ret=array("code"=>"1","qr_img"=>$data['qr_img'],"media_id"=>$data['media_id'],"media_time"=>$data['media_time']);
         return $ret;                 
     
      }
      
   }
   
   function copyfiles($file1,$file2){ 
    $contentx =@file_get_contents($file1); 
    $openedfile = fopen($file2, "w"); 
    fwrite($openedfile, $contentx); 
    fclose($openedfile); 
    if ($contentx === FALSE) { 
     $status=false; 
    } else 
     $status=true; 
     return $status; 
   } 
  
  function copyavatarfiles($file1,$file2){ 
    load()->func('communication');
    $resp = ihttp_request($file1);
    $contentx=$resp['content'];
    $openedfile = fopen($file2, "w"); 
    fwrite($openedfile, $contentx); 
    fclose($openedfile); 
    if ($contentx === FALSE) { 
     $status=false; 
   }
    else $status=true; 
   
   return $status; 
  }
  
   function uploadImage($access_token, $target_file) {
    $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type=image";
    WeUtility::logging('uploadurl', $url);

    $target_file=realpath($target_file);
 
    WeUtility::logging('target_file', $target_file); 
    $post = array(
      'media' => '@' . $target_file
    );
    
    WeUtility::logging('uploadImage', $post);
      load()->func('communication');
  /*   $headers = array('Content-Type' => 'multipart/form-data');
	 $ret =zhttp_request($url, $post, $headers);*/

	 $ret = ihttp_request($url, $post);

    $content = @json_decode($ret['content'], true);
  
    WeUtility::logging('content', $content);
    return $content['media_id'];
  } 
  
  
   function get_user($param = array()) {      
       $jfhb_user=pdo_fetch("select * from ".tablename('jfhb_user')." where openid='{$param['fromuser']}' and uniacid={$param['uniacid']}");
       if (empty($jfhb_user)){
   	     $uniacccount = WeAccount::create($param['acid']); 
   	     $userinfo=$uniacccount->fansQueryInfo($param['fromuser']);
         if (is_error($userinfo)){
   	        WeUtility::logging('userinfo1', $userinfo['message']);
   	        return false;
         } 
   	     $temp=pdo_insert("jfhb_user",
             array("uniacid"=>$param['uniacid'],
             	   "openid"=>$param['fromuser'],
             	   "money"=>0,
             	   "nickname"=>$userinfo['nickname'], 
             	   "headimgurl"=>$userinfo['headimgurl'], 
             	   "nickname"=>$userinfo['nickname'], 
             	   "province"=>$userinfo['province'], 
             	   "city"=>$userinfo['city'], 
             	   "sex"=>$userinfo['sex'], 
             	   "subscribe"=>$userinfo['subscribe'], 
             	   "tx_money"=>0, 
                   "wtx_money"=>0, 
                   "parent_openid"=>$param['fromuser'],
                   "createtime" =>TIMESTAMP,
             ));
    $jfhb_user=pdo_fetch("select * from ".tablename('jfhb_user')." where openid='{$param['fromuser']}' and uniacid={$param['uniacid']}");
   } else if (empty($jfhb_user['nickname']) || empty($jfhb_user['headimgurl'])) {
   	   $uniacccount = WeAccount::create($param['acid']); 
   	   $userinfo=$uniacccount->fansQueryInfo($param['fromuser']);
       if (is_error($userinfo)){
   	    WeUtility::logging('userinfo2', $userinfo['message']);
   	    return false;
       } 
      $temp=pdo_update("jfhb_user",
             array(
             	   "nickname"=>$userinfo['nickname'], 
             	   "headimgurl"=>$userinfo['headimgurl'], 
             	   "nickname"=>$userinfo['nickname'], 
             	   "province"=>$userinfo['province'], 
             	   "city"=>$userinfo['city'], 
             	   "sex"=>$userinfo['sex'], 
             	   "subscribe"=>$userinfo['subscribe'] 
             ),array("uniacid"=>$param['uniacid'],"openid"=>$param['fromuser']));
   	  $jfhb_user=pdo_fetch("select * from ".tablename('jfhb_user')." where openid='{$param['fromuser']}' and uniacid={$param['uniacid']}");
    }
    return $jfhb_user;
  }
  
  

  
  


   


