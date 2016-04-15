<?php
//查询父类关系
function father_exist($openid){
    global $_W;
    $father = new Father();
    $res = $father->getOne($openid);
    return $res;
}

function father_handle($from){
    global $_W;
    if(empty($from) || empty($_W['fans']['from_user']) ){
        return false;
    }
    $father = new Father();
    //增加父类记录
    $fatherres = $father->getOne($from);
    if(!$fatherres){
        $father->create($from,$_W['account']['name']);
    }
    if($from == $_W['fans']['from_user']){
        return  false;
    }
    $ex =father_exist($_W['fans']['from_user']);
    if($ex){
        return false;
    }else{
        $finsert=$father->create($_W['fans']['from_user'],$from);
        if(!$finsert){
            return false;
        }else{
            return $finsert;
        }
    }
}
//访问记录处理
function record_handle($articleid){
    global $_W;
    if(empty($_W['fans']['from_user'])){
        return false;
    }
    $record = new Record();
    $is_exits = $record->getOne($_W['fans']['from_user'],$articleid);
    if($is_exits){
        return false;
    }else{
        $entity = array();
        $entity['openid'] = $_W['fans']['from_user'];
        $entity['articleid'] = $articleid;
        $insert = $record->create($entity);
        if($insert){
            $money = new Money();
            $article = $money->getOne($articleid);
            $update = array();
            $update['viewnums'] = $article['viewnums'] + 1;
            $money->modify($articleid,$update);
            return $insert;
        }else{
            return false;
        }
    }
}

//收益统计
function money($key='all'){
    global $_W;
    $father = new Father();
    $fatherres = $father->getAll($_W['fans']['from_user']);
    //一级会员
    if($fatherres){
        foreach($fatherres as $k=>$v){
            $openids[] = $v['openid'];
        }
    }
    $res='';
    if(is_array($openids)){
        foreach($openids as $k=>$v){
            $record = new Record();
            $filters= array();
            if($key=='all'){
                $filters=array();
            }elseif($key=='today'){
                $filters['time']='today';
            }elseif($key=='yestoday'){
                $filters['time']='yestoday';
            }
            $redata=$record->getOne($v,'',$filters);
            if($redata) {
                $money = new Money();
                $moneydata = $money->getOne($redata['articleid']);
                if($moneydata){
                    $res += $moneydata['money'];
                }
            }

        }
    }
    return $res;


}
//会员统计
function getdata($openid,$level='2'){
    $father = new Father();
    $self = $father->getOne($openid);
    if($self){
        $first = $father->getAll($openid);
        if(is_array($first)){
            foreach($first as $k=>$v){
                $firstids[] = $v['openid'];
            }
            if(is_array($firstids)){
                foreach($firstids as $k=>$v){
                    $second = $father->getAll($v);
                    if(is_array($second)){
                        foreach($second as $k=>$v){
                            $secondids[] = $v['openid'];
                        }
                    }
                }
            }
        }
    }
    $res = array();
    $res['firstids']= $firstids;
    $res['secondids'] = $secondids;
    $res['firstcount'] = count($firstids);
    $res['secondcount'] = count($secondids);
    $res['totalcount'] = $res['firstcount'] + $res['secondcount'];
    //统计总收益
    //$totalids = array_merge($firstids,$secondids);
    $totalmoney = '';
    $totalmoney += getmoney_byopenids($firstids);
    $totalmoney += getmoney_byopenids($secondids,'2');
    $res['totalmoney'] = $totalmoney;
    //统计今天昨天收益
    $todaymoney = '';
    $todaymoney += getmoney_byopenids($firstids,'','today');
    $todaymoney += getmoney_byopenids($secondids,'2','today');
    $res['todaymoney'] = $todaymoney;
    $yestodaymoney = '';
    $yestodaymoney += getmoney_byopenids($firstids,'','yestoday');
    $yestodaymoney += getmoney_byopenids($secondids,'2','yestoday');
    $res['yestodaymoney'] = $yestodaymoney;
    //统计一级会员 二级会员
    if(is_array($firstids)){
        foreach($firstids as $k=>$v){
            $tempmem = getmeminfo($v);
            if($tempmem){
                $res['first'][]=$tempmem;
            }
        }
    }
    if(is_array($secondids)){
        foreach($secondids as $k=>$v){
            $tempmem = getmeminfo($v);
            if($tempmem){
                $res['second'][]=$tempmem;
            }
        }
    }
    return $res;

}

function getmeminfo($openid){
    $fan = new Fan();
    $res = $fan->getOne($openid);
    if($res){
        return $res;
    }else{
        return false;
    }
}

function getmoney_byopenids($openids,$level='1',$time=''){
    $totalmoney='';
    if(is_array($openids)){
        foreach($openids as $k=>$v){
            $record = new Record();
            $money = new Money();
            if(empty($time)){
                $filters = array();
            }else{
                $filters['time']=$time;
            }
            $temp = $record->getOne($v,'',$filters);
            if($temp){
                $moneytemp = $money->getOne($temp['articleid']);
                if($level=='2'){
                    $totalmoney += $moneytemp['second'];
                }else {
                    $totalmoney += $moneytemp['first'];
                }
            }
        }
    }
    return $totalmoney;
}

function preparehandle($id){
    $money = new Money();
    $article = $money->getOne($id);
    //状态判断
    if($article['status']!=0 ){
        return error(-1,'此文章已暂停访问！');
    }
    //金额判断
    $record = new Record();
    $recorddata = $record->getAll($id);
    if($recorddata){
        $nums = count($recorddata);
        $usedmoney = $nums * $article['money'];
    }
    if($usedmoney >= $article['totalmoney']){
        return error(-2,"资金不足,已停止访问！");
    }
    if($nums >= $article['limit']){
        return error(-3,"你来晚了,已经被领完了！");
    }
}