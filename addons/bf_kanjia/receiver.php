<?php
defined('IN_IA') or exit('Access Denied');
class Bf_kanjiaModuleReceiver extends WeModuleReceiver
{
    public function receive()
    {
        $type = $this->message['type'];
    }
}