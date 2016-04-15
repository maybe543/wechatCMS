<?php
defined('IN_IA') or exit('Access Denied');
class Wdl_hongbaoModuleReceiver extends WeModuleReceiver
{
    public function receive()
    {
        $type = $this->message['type'];
    }
}