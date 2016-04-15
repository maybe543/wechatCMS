<?php

/**
 * Class Feiyin 飞印打印类
 */
class Feiyin extends WeModuleSite {
    var $MEMBER_CODE = "77687b3a6b1911e4bce500163e02163b";
    var $FEYIN_KEY = "1b3d7c58";
    var $DEVICE_NO = null;
    //
    var $FEYIN_HOST = 'my.feyin.net';
    var $FEYIN_PORT = '80';

    function __construct()
    {
        require_once ('HttpClient.class.php');
    }

    function code($str)
    {
        if ($str) $this->MEMBER_CODE = $str;
    }
    function key($str)
    {
        if ($str) $this->FEYIN_KEY = $str;
    }
    function no($str)
    {
        $this->DEVICE_NO = $str;
    }


    /**
     * 自由格式的打印内容
     * @param $Detail
     * @param $msgNo
     * @return mixed
     */
    function apiSendFreeMessage($Detail, $msgNo)
    {
        $freeMessage = array(
            'memberCode'=>$this->MEMBER_CODE,
            'msgDetail'=>$Detail,
            'deviceNo'=>$this->DEVICE_NO,
            'msgNo'=>$msgNo,
        );
        return $this->sendFreeMessage($freeMessage);
    }

    /**
     * 格式化的打印内容
     * @param $msgInfo
     * @return string
     */
    function apiSendFormatedMessage($msgInfo)
    {
        if (is_array($msgInfo)) {
            $msgInfo['memberCode'] = $this->MEMBER_CODE;
            $msgInfo['deviceNo'] = $this->DEVICE_NO;
            return $this->sendFormatedMessage($msgInfo);
        }else{
            return '参数出错';
        }
    }


    /**
     * 查询打印状态
     */
    function apiQueryState($msgNo){
        $result = $this->queryState($msgNo);
        return $result;
    }

    /**
     * 获取设备列表
     */
    function apiListDevice(){
        return $this->listDevice();
    }

    /**
     * 返回的状态码含义
     * @param $num
     * @return string
     */
    function statustxt($num) {
        switch ($num)
        {
            case 0:
                $text = "正常"; break;
            case -1:
                $text = "IP地址不允许"; break;
            case -2:
                $text = "关键参数为空或请求方式不对"; break;
            case -3:
                $text = "客户编码不对"; break;
            case -4:
                $text = "安全校验码不正确"; break;
            case -5:
                $text = "请求时间失效"; break;
            case -6:
                $text = "订单内容格式不对"; break;
            case -7:
                $text = "重复的消息"; break;
            case -8:
                $text = "消息模式不对"; break;
            case -9:
                $text = "服务器错误"; break;
            case -10:
                $text = "服务器内部错误"; break;
            case -111:
                $text = "打印终端不属于该账户"; break;
            default:
                $text = "未知"; break;
        }
        return $text;
    }

    /**
     * @param string $l
     * @param string $r
     * @return string
     */
    function formatstr($l = '', $r = '')
    {
        $nbsp = '                              ';
        $llen = $this->print_strlen($l);
        $rlen = $this->print_strlen($r);
        if ($l && $r) {
            $lr = $llen+$rlen;
            $nl = $this->print_strlen($nbsp);
            if ($lr >= $nl) {
                $strtxt = $l."\r\n".$this->formatstr(null,$r);
            }else{
                $strtxt = $l.substr($nbsp, $lr).$r;
            }
        }elseif ($r) {
            $strtxt = substr($nbsp, $rlen).$r;
        }else {
            $strtxt = $l;
        }
        return $strtxt;
    }


    /********************************************************************************************/
    /********************************************************************************************/
    /********************************************************************************************/


    /**
     * PHP获取字符串中英文混合长度
     * @param $str 		字符串
     * @param string $charset	编码
     * @return int 返回长度，1中文=2位(utf-8为3位)，1英文=1位
     */
    private function print_strlen($str,$charset = ''){
        global $_W;
        if(empty($charset)) {
            $charset = $_W['charset'];
        }
        if(strtolower($charset) == 'gbk') {
            $charset = 'gbk';
            $ci = 2;
        } else {
            $charset = 'utf-8';
            $ci = 3;
        }
        if(strtolower($charset)=='utf-8') $str = iconv('utf-8','GBK//IGNORE',$str);
        $num = strlen($str);
        $cnNum = 0;
        for($i=0;$i<$num;$i++){
            if(ord(substr($str,$i+1,1))>127){
                $cnNum++;
                $i++;
            }
        }
        $enNum = $num-($cnNum*$ci);
        $number = $enNum+$cnNum*$ci;
        return ceil($number);
    }


    private function sendFreeMessage($msg) {
        $msg['reqTime'] = number_format(1000*time(), 0, '', '');
        $content = $msg['memberCode'].$msg['msgDetail'].$msg['deviceNo'].$msg['msgNo'].$msg['reqTime'].$this->FEYIN_KEY;
        $msg['securityCode'] = md5($content);
        $msg['mode']=2;

        return $this->sendMessage($msg);
    }

    private function sendFormatedMessage($msgInfo) {
        $msgInfo['reqTime'] = number_format(1000*time(), 0, '', '');
        $content = $msgInfo['memberCode'].$msgInfo['customerName'].$msgInfo['customerPhone'].$msgInfo['customerAddress'].$msgInfo['customerMemo'].$msgInfo['msgDetail'].$msgInfo['deviceNo'].$msgInfo['msgNo'].$msgInfo['reqTime'].$this->FEYIN_KEY;

        $msgInfo['securityCode'] = md5($content);
        $msgInfo['mode']=1;

        return $this->sendMessage($msgInfo);
    }


    private function sendMessage($msgInfo) {
        $client = new HttpClient($this->FEYIN_HOST,$this->FEYIN_PORT);
        if(!$client->post('/api/sendMsg',$msgInfo)){ //提交失败
            return 'faild';
        }
        else{
            return $client->getContent();
        }
    }

    private function queryState($msgNo){
        $now = number_format(1000*time(), 0, '', '');
        $client = new HttpClient($this->FEYIN_HOST,$this->FEYIN_PORT);
        if(!$client->get('/api/queryState?memberCode='.$this->MEMBER_CODE.'&reqTime='.$now.'&securityCode='.md5($this->MEMBER_CODE.$now.$this->FEYIN_KEY.$msgNo).'&msgNo='.$msgNo)){ //请求失败
            return 'faild';
        }
        else{
            return $client->getContent();
        }
    }

    private function listDevice(){
        $now = number_format(1000*time(), 0, '', '');
        $client = new HttpClient($this->FEYIN_HOST,$this->FEYIN_PORT);
        if(!$client->get('/api/listDevice?memberCode='.$this->MEMBER_CODE.'&reqTime='.$now.'&securityCode='.md5($this->MEMBER_CODE.$now.$this->FEYIN_KEY))){ //请求失败
            return 'faild';
        }
        else{
            /***************************************************
            解释返回的设备状态
            格式：
            <device id="4600006007272080">
            <address>广东**</address>
            <since>2010-09-29</since>
            <simCode>135600*****</simCode>
            <lastConnected>2011-03-09  19:39:03</lastConnected>
            <deviceStatus>离线 </deviceStatus>
            <paperStatus></paperStatus>
            </device>
             **************************************************/

            $xml = $client->getContent();
            $sxe = new SimpleXMLElement($xml);
            $arr = array();
            foreach($sxe->device as $device) {
                $id = $device['id'];
                $deviceStatus = $device->deviceStatus;
                $arr[$id.""] = trim($deviceStatus."");
            }
            return $arr;
        }
    }


    private function listException(){
        $now = number_format(1000*time(), 0, '', '');
        $client = new HttpClient($this->FEYIN_HOST,$this->FEYIN_PORT);
        if(!$client->get('/api/listException?memberCode='.$this->MEMBER_CODE.'&reqTime='.$now.'&securityCode='.md5($this->MEMBER_CODE.$now.$this->FEYIN_KEY))){ //请求失败
            return 'faild';
        }
        else{
            return $client->getContent();
        }
    }

}
?>