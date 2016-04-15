<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

        global $_GPC, $_W;
        $GLOBALS['frames'] = $this->getNaveMenu();
		
        $title = $this->actions_titles[$action];
		
		$action = 'week';
		$schoolid = intval($_GPC['schoolid']);
		
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            if (!empty($_GPC['ssort'])) {
                foreach ($_GPC['ssort'] as $sid => $ssort) {
                    pdo_update($this->table_classify, array('ssort' => $ssort), array('sid' => $sid));
                }
                message('批量更新排序成功', $this->createWebUrl('week', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
            }
            $children = array();
            $week = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = '{$_W['uniacid']}' And type = 'week' And schoolid = {$schoolid} ORDER BY sid ASC, ssort DESC");
            foreach ($week as $index => $row) {
                if (!empty($row['parentid'])) {
                    $children[$row['parentid']][] = $row;
                    unset($week[$index]);
                }
            }
        } elseif ($operation == 'post') {
            $parentid = intval($_GPC['parentid']);
            $sid = intval($_GPC['sid']);
            if (!empty($sid)) {
                $week = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = '$sid'");
            } else {
                $week = array(
                    'ssort' => 0,
                );
            }

            if (checksubmit('submit')) {
                if (empty($_GPC['catename'])) {
                    message('抱歉，请输入名称！');
                }

                $data = array(
                    'weid' => $_W['uniacid'],
					'schoolid' => $_GPC['schoolid'],
                    'sname' => $_GPC['catename'],
                    'ssort' => intval($_GPC['ssort']),
                    'type' => 'week',
					'parentid' => intval($parentid),
                );
				

                if (!empty($sid)) {
                    unset($data['parentid']);
                    pdo_update($this->table_classify, $data, array('sid' => $sid));
                } else {
                    pdo_insert($this->table_classify, $data);
                    $sid = pdo_insertid();
                }
                message('更新星期成功！', $this->createWebUrl('week', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
            }
        } elseif ($operation == 'delete') {
            $sid = intval($_GPC['sid']);
            $week = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sid = '$sid'");
            if (empty($week)) {
                message('抱歉，星期不存在或是已经被删除！', $this->createWebUrl('week', array('op' => 'display', 'schoolid' => $schoolid)), 'error');
            }
            pdo_delete($this->table_classify, array('sid' => $sid), 'OR');
            message('星期删除成功！', $this->createWebUrl('week', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
        }
   include $this->template ( 'web/week' );
?>