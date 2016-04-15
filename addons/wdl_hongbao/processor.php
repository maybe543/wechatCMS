<?php
defined('IN_IA') or exit('Access Denied');
class Wdl_hongbaoModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        $content = $this->message['content'];
    }
}