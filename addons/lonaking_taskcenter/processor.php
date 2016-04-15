<?php
/**
 * 猪八戒推广中心模块处理程序
 *
 * @author lonaking
 * @url http://bbs.we7.cc/thread-8992-1-1.html
 */
defined('IN_IA') or exit('Access Denied');

class Lonaking_taskcenterModuleProcessor extends WeModuleProcessor
{
    public $reply_setting_table = "lonaking_supertask";
    public function respond()
    {
        global $_W, $_GPC;
        $content = $this->message['content'];
        $uniacid = $_W['uniacid'];
        $we_config = pdo_fetch("SELECT id,uniacid,setting FROM ".tablename($this->reply_setting_table) ." WHERE uniacid = :uniacid" , array(':uniacid'=>$uniacid));
        $settings = unserialize($we_config['setting']);
        //设置默认值
        $settings['re_time'] = isset($settings['re_time']) ? $settings['re_time'] : 10;
        $settings['out_cmd'] = isset($settings['out_cmd']) ? $settings['out_cmd'] : "退出";
        $settings['out_msg'] = isset($settings['out_msg']) ? $settings['out_msg'] : "您已经成功退出";
        $settings['timeout'] = isset($settings['timeout']) ? $settings['timeout'] : 60;
        $welcome_message = '您好，点击这里进入<a href="'. $this->buildSiteUrl($this->createMobileUrl('center')) .'">猪八戒微信朋友圈推广中心</a>';
        if(isset($settings['welcome_message'])){
            $href = $this->buildSiteUrl($this->createMobileUrl('center'));
            if(strpos($settings['welcome_message'], '$url')){
                $welcome_message = str_replace('$url', $href, $settings['welcome_message']);
            }else{
                $settings['welcome_message'] = $settings['welcome_message'];
            }
        }
        $settings['welcome_message'] = $welcome_message;
        
        if ($content == $settings['out_cmd']) {
            $this->endContext();
            return $this->respText($settings['out_msg']);
        }
        // 开启会话
        if (!$this->inContext) {
            $this->beginContext($settings['timeout']);
            
            $this->endContext();//TODO 临时这么干 后期如果需要改，这里改掉
            return $this->respText($welcome_message);
        } else {
            
        }
    }
}