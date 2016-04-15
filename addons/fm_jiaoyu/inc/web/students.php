<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_GPC, $_W;
        $GLOBALS['frames'] = $this->getNaveMenu();
        $weid = $this->_weid;
        $action = 'students';
		$schoolid = intval($_GPC['schoolid']);
		
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $sid));
		$xueqi = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'semester' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'semester', ':schoolid' => $schoolid));		
		$km = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'subject' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'subject', ':schoolid' => $schoolid));
		$bj = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'theclass' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'theclass', ':schoolid' => $schoolid));
		$xq = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'week' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'week', ':schoolid' => $schoolid));
		$sd = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'timeframe' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'timeframe', ':schoolid' => $schoolid));
		$qh = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'score' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'score', ':schoolid' => $schoolid));

        $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
        if (!empty($category)) {
            $children = '';
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }
		
        $member = pdo_fetchall("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid ORDER BY uid ASC", array(':uniacid' => $_W ['uniacid']), 'uid');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'post') {
            load()->func('tpl');
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE id = :id", array(':id' => $id));
                if (empty($item)) {   
                    message('抱歉，学生不存在或是已经删除！', '', 'error');
                } else {
                    if (!empty($item['thumb_url'])) {
                        $item['thumbArr'] = explode('|', $item['thumb_url']);
                    }
                }
            }
            if (checksubmit('submit')) {
                $data = array(
				    'weid' => $_W['uniacid'],
					'schoolid' => $schoolid,
                    's_name' => trim($_GPC['s_name']),
					'sex' => intval($_GPC['sex']),
					'bj_id' => trim($_GPC['bj']),
					'xq_id' => trim($_GPC['xueqi']),
					'birthdate' => strtotime($_GPC['birthdate']),
                    'homephone' => trim($_GPC['tel']),
                    'mobile' => trim($_GPC['mobile']),
					'area_addr' => trim($_GPC['addr']),
					'seffectivetime' => strtotime($_GPC['seffectivetime']),
					'stheendtime' => strtotime($_GPC['stheendtime']),
					'note' => trim($_GPC['note']),
                );

                if (empty($data['s_name'])) {
                    message('请输入学生姓名！');
                }
				if (empty($data['mobile'])) {
                    message('清输入学生家长手机');
                }				
                if (empty($id)) {
                    pdo_insert($this->table_students, $data);
                } else {
                    unset($data['dateline']);
                    pdo_update($this->table_students, $data, array('id' => $id));
                }
                message('添加学生信息成功！', $this->createWebUrl('students', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
            }
        } elseif ($operation == 'display') {

            $pindex = max(1, intval($_GPC['page']));
            $psize = 8;
            $condition = '';
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND s_name LIKE '%{$_GPC['keyword']}%'";
            }

		

            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_students) . " WHERE weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_students) . " WHERE weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} $condition");

            $pager = pagination($total, $pindex, $psize);
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id, s_name FROM " . tablename($this->table_students) . " WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，学生不存在或是已经被删除！');
            }
            pdo_delete($this->table_students, array('id' => $id));
            message('删除成功！', referer(), 'success');
        } elseif ($operation == 'own') {
            $id = intval($_GPC['id']);
			$openid = $_GPC['openid'];
            $row = pdo_fetch("SELECT id FROM " . tablename($this->table_students) . " WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，学生不存在或是已经被删除！');
            }

			$temp = array(
			        'own'  => 0,
		           	'ouid' => 0
			       );
			   
			pdo_update($this->table_students, $temp, array('id' => $id));
            pdo_delete($this->table_user, array('sid' => $id, 'openid' => $openid, 'tid' => 0, 'pard' => 4));
            message('解绑成功！', referer(), 'success');
        } elseif ($operation == 'mom') {
            $id = intval($_GPC['id']);
			$openid = $_GPC['openid'];
            $row = pdo_fetch("SELECT id FROM " . tablename($this->table_students) . " WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，学生不存在或是已经被删除！');
            }

			$temp = array(
			        'mom'   => 0,
		           	'muid'  => 0
			       );
			
			pdo_update($this->table_students, $temp, array('id' => $id));
            pdo_delete($this->table_user, array('sid' => $id, 'openid' => $openid, 'tid' => 0, 'pard' => 2));
            message('解绑成功！', referer(), 'success');
        } elseif ($operation == 'dad') {
            $id = intval($_GPC['id']);
			$openid = $_GPC['openid'];
            $row = pdo_fetch("SELECT id FROM " . tablename($this->table_students) . " WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，学生不存在或是已经被删除！');
            }

			$temp = array(
			        'dad'   => 0,
		           	'duid'  => 0
			       );
			
			pdo_update($this->table_students, $temp, array('id' => $id));
            pdo_delete($this->table_user, array('sid' => $id, 'openid' => $openid, 'tid' => 0, 'pard' => 3));
            message('解绑成功！', referer(), 'success');
        } elseif ($operation == 'deleteall') {
            $rowcount = 0;
            $notrowcount = 0;
            foreach ($_GPC['idArr'] as $k => $id) {
                $id = intval($id);
                if (!empty($id)) {
                    $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE id = :id", array(':id' => $id));
                    if (empty($goods)) {
                        $notrowcount++;
                        continue;
                    }
                    pdo_delete($this->table_students, array('id' => $id, 'weid' => $_W['uniacid']));
                    $rowcount++;
                }
            }
            $this->message("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
        } elseif ($operation == 'add') {
			load()->func('tpl');
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE id = :id", array(':id' => $id));				
                if (empty($item)) {
                    message('抱歉，学生不存在或是已经删除！', '', 'error');
                }
            }
			if (checksubmit('submit')) {
                $data = array(
				    'weid' => $_W['uniacid'],
					'schoolid' => $schoolid,
					'sid' => intval($_GPC['id']),
					'km_id' => trim($_GPC['km']),
					'bj_id' => trim($_GPC['bj']),
					'qh_id' => trim($_GPC['qh']),
					'xq_id' => trim($_GPC['xueqi']),
					'my_score' => trim($_GPC['score']),
                );
				
				pdo_insert($this->table_score, $data);
            	message('录入成功，请勿重复录入！', $this->createWebUrl('students', array('op' => 'display', 'schoolid' => $schoolid)), 'success');    
            }
		}	
        include $this->template ( 'web/students' );
?>