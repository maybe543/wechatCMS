<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_GPC, $_W;
        $GLOBALS['frames'] = $this->getNaveMenu();
        $weid = $this->_weid;
        $action = 'kecheng';
	    $schoolid = intval($_GPC['schoolid']);
		

		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $sid));
		$xueqi = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'semester' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'semester', ':schoolid' => $schoolid));		
		$km = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'subject' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'subject', ':schoolid' => $schoolid));
		$bj = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'theclass' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'theclass', ':schoolid' => $schoolid));
		$xq = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'week' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'week', ':schoolid' => $schoolid));
		$sd = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'timeframe' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'timeframe', ':schoolid' => $schoolid));
		$qh = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'score' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'score', ':schoolid' => $schoolid));

        $teachers = pdo_fetchall("SELECT * FROM " . tablename($this->table_teachers) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY id ASC, id DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'id');
        if (!empty($teachers)) {
            $child = '';
            foreach ($teachers as $pid => $pcate) {
                if (!empty($pcate['parentid'])) {
                    $child[$pcate['parentid']][$pcate['id']] = array($pcate['id'], $pcate['name']);
                }
            }
        }
        $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
        if (!empty($category)) {
            $children = '';
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'post') {
            load()->func('tpl');
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $id));
                if (empty($item)) {   
                    message('抱歉，本条信息不存在在或是已经删除！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                $data = array(
				    'weid' => $_W['uniacid'],
					'schoolid' => $schoolid,
					'tid' => intval($_GPC['tid']),
					'km_id' => trim($_GPC['km']),
					'bj_id' => trim($_GPC['bj']),
					'name' => trim($_GPC['name']),
					'minge' => trim($_GPC['minge']),
					'dagang' => trim($_GPC['dagang']),
					'adrr' => trim($_GPC['adrr']),
					'is_hot' => intval($_GPC['is_hot']),
					'start' => strtotime($_GPC['start']),
					'end' => strtotime($_GPC['end']),
                );
			
                if (empty($id)) {
                    message('抱歉，本条信息不存在在或是已经删除！', '', 'error');
                } else {
                    pdo_update($this->table_tcourse, $data, array('id' => $id));
                }
                message('修改成功！', $this->createWebUrl('kecheng', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
            }
        } elseif ($operation == 'display') {

            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $condition = '';
			
		    if (!empty($_GPC['name'])) {
                $condition .= " AND id LIKE '%{$_GPC['name']}%' ";
            }
						
            if (!empty($_GPC['bj_id'])) {
                $cid = intval($_GPC['bj_id']);
                $condition .= " AND bj_id = '{$cid}'";
            }	
						
            if (!empty($_GPC['km_id'])) {
                $cid = intval($_GPC['km_id']);
                $condition .= " AND km_id = '{$cid}'";
            }		

            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_tcourse) . " WHERE weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} $condition");

            $pager = pagination($total, $pindex, $psize);
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                message('抱歉，本条信息不存在在或是已经被删除！');
            }
            pdo_delete($this->table_tcourse, array('id' => $id));
            message('删除成功！', referer(), 'success');
        } elseif ($operation == 'deleteall') {
            $rowcount = 0;
            $notrowcount = 0;
            foreach ($_GPC['idArr'] as $k => $id) {
                $id = intval($id);
                if (!empty($id)) {
                    $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id", array(':id' => $id));
                    if (empty($goods)) {
                        $notrowcount++;
                        continue;
                    }
                    pdo_delete($this->table_tcourse, array('id' => $id, 'weid' => $_W['uniacid']));
                    $rowcount++;
                }
            }
            $this->message("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
        } elseif ($operation == 'add') {
			load()->func('tpl');
            $id = intval($_GPC['id']);
           // $row = pdo_fetch("SELECT id, thumb FROM " . tablename($this->table_tcourse) . " WHERE id = :id", array(':id' => $id));
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id", array(':id' => $id));				
                if (empty($item)) {
                    message('抱歉，教师不存在或是已经删除！', '', 'error');
                }
            }
			if (checksubmit('submit')) {
                $data = array(
				    'weid' => $_W['uniacid'],
					'schoolid' => $schoolid,
					'tid' => intval($_GPC['tid']),
					'kcid' => trim($_GPC['kcid']),
					'bj_id' => trim($_GPC['bj_id']),
					'km_id' => trim($_GPC['km_id']),					
					'sd_id' => trim($_GPC['sd']),
					'xq_id' => trim($_GPC['xq']),					
					'nub' => trim($_GPC['nub']),
					'date' => strtotime($_GPC['date']),
                );

                if (istrlen($data['nub']) == 0) {
                    message('没有输入编号.', '', 'error');
                }	
										
				pdo_insert($this->table_kcbiao, $data);
            	message('操作成功', $this->createWebUrl('kecheng', array('op' => 'display', 'schoolid' => $schoolid)), 'success');    
            }
		}	
        include $this->template ( 'web/kecheng' );
?>