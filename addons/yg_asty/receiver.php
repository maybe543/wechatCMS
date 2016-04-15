<?php
defined('IN_IA') or exit('Access Denied');
class Yg_astyModuleReceiver extends WeModuleReceiver
{
    public function receive()
    {
        $type = $this->message['type'];
    }
}