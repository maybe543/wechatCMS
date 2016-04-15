<?php
/**
 * 通用表单模块微站定义
 *
 * @author fwei.net
 * @url http://www.fwei.net/
 */
defined('IN_IA') or exit('Access Denied');

class Fwei_formsModuleSite extends WeModuleSite {

	public function getItemTiles() {
        global $_W;
        $urls = array();
        $forms = pdo_fetchall("SELECT rid, title FROM " . tablename('fwei_forms') . " WHERE uniacid = '{$_W['uniacid']}'");
        if (!empty($forms)) {
            foreach ($forms as $row) {
                $urls[] = array('title' => $row['title'], 'url' => $this->createMobileUrl('forms', array('id' => $row['rid'])));
            }
            return $urls;
        }
    }

	public function doWebCreate(){
		header('Location:'.url('platform/reply/post',array('m'=>'fwei_forms')));
	}

	public function doWebPreview(){
		global $_GPC;
		header('Location:'.'../app/'.$this->createMobileUrl('forms', array('m'=>'fwei_forms', 'id'=>$_GPC['id'],'_openid'=>'fromUser')));
	}

}