<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

        global $_GPC, $_W;
        $GLOBALS['frames'] = $this->getNaveMenu();
		$weid = $this->_weid;
		load()->func('tpl');

            
            $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE weid = :weid", array(':weid' => $_W['uniacid']));
            
            if (checksubmit('submit')) {
                $data = array(
				    'weid' => $_W['uniacid'],
                    'istplnotice' => intval($_GPC['istplnotice']),
					'xsqingjia' => trim($_GPC['xsqingjia']),
					'xsqjsh' => trim($_GPC['xsqjsh']),
					'jsqingjia' => trim($_GPC['jsqingjia']),
					'jsqjsh' => trim($_GPC['jsqjsh']),
					'xxtongzhi' => trim($_GPC['xxtongzhi']),
					'liuyan' => trim($_GPC['liuyan']),
					'liuyanhf' => trim($_GPC['liuyanhf']),
					'zuoye' => trim($_GPC['zuoye']),
					'bjtz' => trim($_GPC['bjtz']),
                );

                if (empty($setting)) {
                    pdo_insert($this->table_set, $data);
                } else {
                    pdo_update($this->table_set, $data, array('weid' => $_W['uniacid']));
                }
				message('操作成功', $this->createWebUrl('basic'), 'success');
            }
        
		
   include $this->template ( 'web/basic' );
?>