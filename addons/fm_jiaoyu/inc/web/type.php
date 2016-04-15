<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_GPC, $_W;
        $weid = $this->_weid;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $GLOBALS['frames'] = $this->getNaveMenu();
        if ($operation == 'display') {
            if (!empty($_GPC['ssort'])) {
                foreach ($_GPC['ssort'] as $id => $ssort) {
                    pdo_update($this->table_type, array('ssort' => $ssort), array('id' => $id));
                }
                message('排序更新成功！', $this->createWebUrl('type', array('op' => 'display')), 'success');
            }

			$type = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " WHERE weid = '{$_W['uniacid']}'  ORDER BY parentid ASC, ssort DESC");

        } elseif ($operation == 'post') {
            $parentid = intval($_GPC['parentid']);
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $type = pdo_fetch("SELECT * FROM " . tablename($this->table_type) . " WHERE id = '$id'");
            } else {
                $type = array(
                    'ssort' => 0,
                );
            }

            if (checksubmit('submit')) {
                if (empty($_GPC['catename'])) {
                    message('请输入类型名称！');
                }

                $data = array(
                    'weid' => $_W['uniacid'],
                    'name' => $_GPC['catename'],
                    'ssort' => intval($_GPC['ssort']),
                    'parentid' => intval($parentid),
                );

                if (!empty($id)) {
                    unset($data['parentid']);
                    pdo_update($this->table_type, $data, array('id' => $id));
                } else {
                    pdo_insert($this->table_type, $data);
                }
                message('更新学校类型成功！', $this->createWebUrl('type', array('op' => 'display')), 'success');
            }
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $type = pdo_fetch("SELECT id, parentid FROM " . tablename($this->table_type) . " WHERE id = '$id'");
            if (empty($type)) {
                message('抱歉，数据不存在或是已经被删除！', $this->createWebUrl('type', array('op' => 'display')), 'error');
            }
            pdo_delete($this->table_type, array('id' => $id, 'parentid' => $id), 'OR');
            message('数据删除成功！', $this->createWebUrl('type', array('op' => 'display')), 'success');
        }
   include $this->template ( 'web/type' );
?>