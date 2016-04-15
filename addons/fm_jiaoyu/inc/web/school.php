<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $this->_weid;

        $GLOBALS['frames'] = $this->getNaveMenu();

        $action = 'school';
        $title = '学校管理';
        $url = $this->createWebUrl($action, array('op' => 'display'));
        $area = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " where weid = '{$_W['uniacid']}' ORDER BY ssort DESC", array(':weid' => $weid));
        $schooltype = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " where weid = '{$_W['uniacid']}' ORDER BY ssort DESC", array(':weid' => $weid));

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$quyu = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " WHERE weid = '{$_W['uniacid']}' ORDER BY id ASC, ssort DESC", array(':weid' => $_W['uniacid']), 'id');
        if (!empty($quyu)) {
            $children = '';
            foreach ($quyu as $qid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }
		$leixing = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " WHERE weid = '{$_W['uniacid']}' ORDER BY id ASC, ssort DESC", array(':weid' => $_W['uniacid']), 'id');
        if (!empty($leixing)) {
            $children1 = '';
            foreach ($leixing as $lid => $pcate) {
                if (!empty($pcate['parentid'])) {
                    $children1[$pcate['parentid']][$pcate['id']] = array($pcate['id'], $pcate['name']);
                }
            }
        }		
        if ($operation == 'display') {
            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['ssort'])) {
                    foreach ($_GPC['ssort'] as $id => $val) {
                        $data = array('ssort' => intval($_GPC['ssort'][$id]));
                        pdo_update($this->table_index, $data, array('id' => $id));
                    }
                }
                message('操作成功!', $url);
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $where = "WHERE weid = '{$_W['uniacid']}'";
			$where1 = "WHERE weid = '{$_W['uniacid']}' And schoolid = '{$id}'";
            $schoollist = pdo_fetchall("SELECT * FROM " . tablename($this->table_index) . " {$where} order by ssort desc,id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            if (!empty($schoollist)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_index) . " $where");
				$shumu = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_students) . " $where1");
                $pager = pagination($total, $pindex, $psize);  				
            }	
			
        } elseif ($operation == 'post') {
            load()->func('tpl');
            $id = intval($_GPC['id']); 
            $reply = pdo_fetch("select * from " . tablename($this->table_index) . " where id=:id and weid =:weid", array(':id' => $id, ':weid' => $_W['uniacid']));

            $piclist = unserialize($reply['thumb_url']);

            if (checksubmit('submit')) {
                $data = array(
                    'weid' => intval($_W['uniacid']),
                    'areaid' => intval($_GPC['area']),
                    'typeid' => intval($_GPC['type']),
                    'title' => trim($_GPC['title']),
                    'info' => trim($_GPC['info']),
                    'content' => trim($_GPC['content']),
                    'tel' => trim($_GPC['tel']),
                    'gonggao' => trim($_GPC['gonggao']),
                    'logo' => trim($_GPC['logo']),
					'thumb' => trim($_GPC['thumb']),
                    'address' => trim($_GPC['address']),
                    'location_p' => trim($_GPC['location_p']),
                    'location_c' => trim($_GPC['location_c']),
                    'location_a' => trim($_GPC['location_a']),
                    'lng' => trim($_GPC['baidumap']['lng']),
                    'lat' => trim($_GPC['baidumap']['lat']),
                    'password' => trim($_GPC['password']),
                    'recharging_password' => trim($_GPC['recharging_password']),
                    'is_show' => intval($_GPC['is_show']),
					'is_rest' => intval($_GPC['is_rest']),
                    'is_sms' => intval($_GPC['is_sms']),
                    'is_hot' => intval($_GPC['is_hot']),
					'style1' => intval($_GPC['style1']),
					'ssort' => intval($_GPC['ssort']),
                    'dateline' => TIMESTAMP,
                );

                if (istrlen($data['title']) == 0) {
                    message('没有输入标题.', '', 'error');
                }
                if (istrlen($data['title']) > 30) {
                    message('标题不能多于30个字。', '', 'error');
                }
                if (istrlen($data['tel']) == 0) {
//                    message('没有输入联系电话.', '', 'error');
                }
                if (istrlen($data['address']) == 0) {
                    //message('请输入地址。', '', 'error');
                }

                if (is_array($_GPC['thumbs'])) {
                    $data['thumb_url'] = serialize($_GPC['thumbs']);
                }

                if (!empty($id)) {
                    unset($data['dateline']);
                    pdo_update($this->table_index, $data, array('id' => $id, 'weid' => $_W['uniacid']));
                } else {
                    pdo_insert($this->table_index, $data);
                }
                message('操作成功!', $url);
            }
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $store = pdo_fetch("SELECT id FROM " . tablename($this->table_index) . " WHERE id = '$id'");
            if (empty($store)) {
                message('抱歉，不存在或是已经被删除！', $this->createWebUrl('school', array('op' => 'display')), 'error');
            }
            pdo_delete($this->table_index, array('id' => $id, 'weid' => $_W['uniacid']));
            message('删除成功！', $this->createWebUrl('school', array('op' => 'display')), 'success');
        }
        include $this->template ( 'web/school' );
?>