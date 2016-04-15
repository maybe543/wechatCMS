<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_GPC, $_W;
        $GLOBALS['frames'] = $this->getNaveMenu();
        $weid = $this->_weid;
		$action = 'assess';
		$schoolid = intval($_GPC['schoolid']);

        $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_teachers) . " WHERE weid = :weid And schoolid=:schoolid ORDER BY id ASC, sort DESC", array(':weid' => $weid, ':schoolid' => $schoolid), 'id');
		

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $sid));
        $xueqi = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'semester' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'semester', ':schoolid' => $schoolid));
	    $km = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'subject' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'subject', ':schoolid' => $schoolid));
		$bj = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'theclass' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'theclass', ':schoolid' => $schoolid));
		$xq = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'week' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'week', ':schoolid' => $schoolid));
		$sd = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} And type = 'timeframe' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'timeframe', ':schoolid' => $schoolid));

        $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
       		   
	   if (!empty($category)) {
            $children = array();
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }
		
		
		
        $kcbiao = pdo_fetchall("SELECT * FROM " . tablename($this->table_kcbiao) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'id');
        if (!empty($kcbiao)) {
            $children = array();
            foreach ($kcbiao as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }	
		
		$member = pdo_fetchall("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid ORDER BY uid ASC", array(':uniacid' => $_W ['uniacid']), 'uid');		
		
		if (empty($schoolid)) {
            message('没有选中任何学校!');
        }

        if ($operation == 'post') {
            load()->func('tpl');
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $id));
			
                if (empty($item)) {
                    message('抱歉，教师不存在或是已经删除！', '', 'error');
                } else {
                    if (!empty($item['thumb_url'])) {
                        $item['thumbArr'] = explode('|', $item['thumb_url']);
                    }
                }
            }
			if ($item['code'] == 0){
			     $randStr = str_shuffle('1234567890');
                 $rand = substr($randStr,0,6);
				}else{
		  	     $rand = $item['code'];	
			}
			if(!empty($_GPC['code'])){
				 $rand = $_GPC['code'];	   
			}
            if (checksubmit('submit')) {
                $data = array(
				    'weid' => $_W['uniacid'],
					'schoolid' => $schoolid,
                    'tname' => trim($_GPC['tname']),
					'birthdate' => strtotime($_GPC['birthdate']),
                    'tel' => trim($_GPC['tel']),
                    'mobile' => trim($_GPC['mobile']),
                    'thumb' => trim($_GPC['thumb']),
                    'email' => trim($_GPC['email']),
					'jiontime' => strtotime($_GPC['jiontime']),
                    'sex' => intval($_GPC['sex']),
					'status' => intval($_GPC['status']),
                    'sort' => intval($_GPC['sort']),
					'xq_id1' => trim($_GPC['xq_id1']),
					'xq_id2' => trim($_GPC['xq_id2']),
					'xq_id3' => trim($_GPC['xq_id3']),
					'bj_id1' => trim($_GPC['bj_id1']),
					'bj_id2' => trim($_GPC['bj_id2']),
					'bj_id3' => trim($_GPC['bj_id3']),
					'km_id1' => trim($_GPC['km_id1']),
					'km_id2' => trim($_GPC['km_id2']),
					'headinfo' => trim($_GPC['headinfo']),
					'jinyan' => trim($_GPC['jinyan']),
					'info' => htmlspecialchars_decode($_GPC['info']),
					'code' => $rand,
                );

                if (empty($data['tname'])) {
                    message('请输入教师姓名！');
                }
				if (empty($data['info'])) {
                    message('请输入教师简介！');
                }
				if (empty($data['headinfo'])) {
                    message('请输入教师描述！');
                }
                if (!empty($_FILES['thumb']['tmp_name'])) {
                    load()->func('file');
                    file_delete($_GPC['thumb_old']);
                    $upload = file_upload($_FILES['thumb']);
                    if (is_error($upload)) {
                        message($upload['message'], '', 'error');
                    }
                    $data['thumb'] = $upload['path'];
                }
                if (empty($id)) {
                    pdo_insert($this->table_teachers, $data);
                } else {
                    unset($data['dateline']);
                    pdo_update($this->table_teachers, $data, array('id' => $id));
                }
                message('操作成功', $this->createWebUrl('assess', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
            }
        } elseif ($operation == 'display') {

            $pindex = max(1, intval($_GPC['page']));
            $psize = 8;
            $condition = '';
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND tname LIKE '%{$_GPC['keyword']}%'";
            }

            if (isset($_GPC['status'])) {
                $condition .= " AND status = '" . intval($_GPC['status']) . "'";
            }

            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_teachers) . " WHERE weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} $condition ORDER BY status DESC, sort DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_teachers) . " WHERE weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} $condition");

            $pager = pagination($total, $pindex, $psize);
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id, thumb FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，教师不存在或是已经被删除！');
            }
            if (!empty($row['thumb'])) {
                load()->func('file');
                file_delete($row['thumb']);
            }
            pdo_delete($this->table_teachers, array('id' => $id));
            message('删除成功！', referer(), 'success');
        } elseif ($operation == 'jiebang') {
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id, thumb FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，教师不存在或是已经被删除！');
            }
            if (!empty($row['thumb'])) {
                load()->func('file');
            //    file_delete($row['thumb']);
            }
			$temp = array(
			        'openid' => '',
		           	'uid'    => 0
			       );
			
			pdo_update($this->table_teachers, $temp, array('id' => $id));
            pdo_delete($this->table_user, array('tid' => $id));
            message('解绑成功！', referer(), 'success');
        } elseif ($operation == 'deleteall') {
            $rowcount = 0;
            $notrowcount = 0;
            foreach ($_GPC['idArr'] as $k => $id) {
                $id = intval($id);
                if (!empty($id)) {
                    $assess = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $id));
                    if (empty($assess)) {
                        $notrowcount++;
                        continue;
                    }
                    pdo_delete($this->table_teachers, array('id' => $id, 'weid' => $_W['uniacid']));
                    $rowcount++;
                }
            }
            $this->message("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
        } elseif ($operation == 'add') {
			load()->func('tpl');
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id, thumb FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $id));
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $id));				
                if (empty($item)) {
                    message('抱歉，教师不存在或是已经删除！', '', 'error');
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
					'tid' => intval($_GPC['id']),
					'km_id' => trim($_GPC['km']),
					'bj_id' => trim($_GPC['bj']),
					'name' => trim($_GPC['name']),
					'is_hot' => intval($_GPC['is_hot']),
					'minge' => trim($_GPC['minge']),
					'dagang' => trim($_GPC['dagang']),
					'adrr' => trim($_GPC['adrr']),
					'start' => strtotime($_GPC['start']),
					'end' => strtotime($_GPC['end']),
                );
				
				pdo_insert($this->table_tcourse, $data);
            	message('操作成功', $this->createWebUrl('assess', array('op' => 'display', 'schoolid' => $schoolid)), 'success');    
            }
		}	
        include $this->template ( 'web/assess' );
?>