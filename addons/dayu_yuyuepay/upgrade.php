<?php
if(!pdo_fieldexists('dayu_yuyuepay', 'code')) {
	pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')."  ADD `code` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'is_time')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD  `is_time` tinyint(1) DEFAULT '0';");
}