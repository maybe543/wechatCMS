<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $this->weid;
        $from_user = $this->_fromuser;
		$schoolid = intval($_GPC['schoolid']);
        load()->func('tpl');
        $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_cook) . " WHERE id = :id ", array(':id' => $id));
		        $monarr = iunserializer($item['monday']);
				$tusarr = iunserializer($item['tuesday']);
				$wedarr = iunserializer($item['wednesday']);
				$thuarr = iunserializer($item['thursday']);
				$friarr = iunserializer($item['friday']);
				$satarr = iunserializer($item['saturday']);
				$sunarr = iunserializer($item['sunday']);
                if (empty($item)) {   
                    message('抱歉，本条信息不存在在或是已经删除！', '', 'error');
                }
            }
		
        include $this->template('cook');
?>