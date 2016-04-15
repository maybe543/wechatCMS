<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

        global $_GPC, $_W;
        $GLOBALS['frames'] = $this->getNaveMenu();
		
        $title = $this->actions_titles[$action];
		
		$action = 'banner';
		$schoolid = intval($_GPC['schoolid']);
		load()->func('tpl');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_banners) . " WHERE weid = '{$_W['uniacid']}' AND schoolid = '{$schoolid}' ORDER BY displayorder DESC");
		//	include $this->template('banner');
        } elseif ($operation == 'post') {

            $id = intval($_GPC['id']);
            if (checksubmit('submit')) {
                $data = array(
                    'weid' => intval($_W['uniacid']),
					'uniacid' => intval($_W['uniacid']),
                    'schoolid' => $schoolid,
                    'bannername' => $_GPC['bannername'],
                    'link' => $_GPC['link'],
                    'thumb' => $_GPC['thumb'],
                    'enabled' => intval($_GPC['enabled']),
                    'displayorder' => intval($_GPC['displayorder'])
                );
               
                if (!empty($id)) {
                    pdo_update($this->table_banners, $data, array('id' => $id));
					load()->func('file');
					file_delete($_GPC['thumb_old']);
                } else {
                    pdo_insert($this->table_banners, $data);
           //         $id = pdo_insertid();
                }
                message('更新幻灯片成功！', $this->createWebUrl('banner', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
            }
            $banner = pdo_fetch("select * from " . tablename($this->table_banners) . " where id=:id and weid=:weid and schoolid=:schoolid limit 1", array(":id" => $id, ":weid" => $_W['uniacid'], ':schoolid' => $schoolid));
			//include $this->template('banner_post');
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $banner = pdo_fetch("SELECT id  FROM " . tablename($this->table_banners) . " WHERE id = '$id' AND weid='{$_W['uniacid']}' AND schoolid=" . $schoolid . "");
            if (empty($banner)) {
                message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('banner', array('op' => 'display', 'schoolid' => $schoolid)), 'error');
            }
            pdo_delete($this->table_banners, array('id' => $id));
            message('幻灯片删除成功！', $this->createWebUrl('banner', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
        } else {
            message('请求方式不存在');
        }
		include $this->template('web/banner');
?>