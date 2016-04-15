<?php
defined('IN_IA') or exit('Access Denied');

if(!pdo_fieldexists('hx_qr_reply', 'reply2')) {
	pdo_query("ALTER TABLE ".tablename('hx_qr_reply')." ADD `reply2` varchar(255) NOT NULL AFTER `reply1`;");
}
