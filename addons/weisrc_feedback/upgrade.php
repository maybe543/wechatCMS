<?php

if(!pdo_fieldexists('weisrc_feedback_setting', 'templateid')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_feedback_setting')." ADD `templateid` varchar(100) DEFAULT '' COMMENT '模板id';");
}

if(!pdo_fieldexists('weisrc_feedback_setting', 'isnotice')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_feedback_setting')." ADD `isnotice` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否通知';");
}

if(!pdo_fieldexists('weisrc_feedback_setting', 'noticeuser')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_feedback_setting')." ADD `noticeuser` varchar(100) DEFAULT '' COMMENT '通知用户';");
}