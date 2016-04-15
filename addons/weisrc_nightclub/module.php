<?php
/**
 * 微夜店
 *
 * 作者:折翼天使资源社区
 *
 * qq : 87243435
 */
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/weisrc_nightclub/template/');
//{RES}images/nopic.jpeg
//{RES}images/default-headimg.jpg
class weisrc_nightclubModule extends WeModule {
	public $name = 'weisrc_nightclubModule';
	public $title = '微夜店';
	public $ability = '';
	public $tablename = 'weisrc_nightclub_reply';
    public $action = 'detail';//方法
    public $modulename = 'weisrc_nightclub';//模块标识

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
    }

    public function fieldsFormSubmit($rid = 0) {
        global $_GPC, $_W;
    }
}