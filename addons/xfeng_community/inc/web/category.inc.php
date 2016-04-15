<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台分类管理
 */


		global $_GPC,$_W;
		$GLOBALS['frames'] = $this->NavMenu();
		$op       = !empty($_GPC['op']) ? $_GPC['op']:'list'; 
		$id       = intval($_GPC['id']);
		if ($op == 'add') {
			$parentid = intval($_GPC['parentid']);
			//编辑分类信息
			if (!empty($id)) {
				$category = pdo_fetch("SELECT * FROM".tablename('xcommunity_category')."WHERE id=:id",array(':id' => $id));
			}
			//添加分类主ID
			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT * FROM".tablename('xcommunity_category')."WHERE id=:parentid",array(':parentid' => $parentid));
			}
			//提交
			if (checksubmit('submit')) {
				$data = array(
					'name'         => $_GPC['catename'],
					'parentid'     => 0,
					'displayorder' => $_GPC['displayorder'],
					'description'  => $_GPC['description'],
					'enabled'      => 1,
					'weid'         => $_W['weid'],
					);
				if (empty($parentid)) {
					if (empty($id)) {
						//添加主类
						pdo_insert("xcommunity_category",$data);
					}else{
						//更新
						$data['displayorder'] = $_GPC['displayorder'];
						$data['name']         = $_GPC['catename'];
						$data['description']  = $_GPC['description'];
						pdo_update("xcommunity_category",$data,array('id'=>$id));
					}					
				}else{
					//添加子类
					if(empty($id)){
							$data['parentid'] = $parentid;
							pdo_insert("xcommunity_category",$data);
					}else{
						//更新子类
						$data['parentid'] = $parentid;
						$data['displayorder'] = $_GPC['displayorder'];
						$data['name']         = $_GPC['catename'];
						$data['description']  = $_GPC['description'];
						pdo_update("xcommunity_category",$data,array('id'=>$id));
					}
				
				}
				message('更新成功',referer(),'success');
			}
			include $this->template('web/category/add');
		}elseif($op == 'list'){
			//显示全部分类信息
			$sql      = "select * from".tablename("xcommunity_category")."where parentid= 0 ";
			$category = pdo_fetchall($sql);
			$children = array();
			foreach ($category as $key => $value) {
				$sql  = "select *from".tablename("xcommunity_category")."where weid='{$_W['weid']}' and  parentid=".$value['id'];
				$list = pdo_fetchall($sql);
				$children[$value['id']] = $list;
			}
			include $this->template('web/category/list');
		}elseif ($op == 'delete') {
			//删除分类信息
			pdo_delete("xcommunity_category",array('id'=>$id));
			message('删除成功',referer(),'success');
		}
		