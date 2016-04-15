<?php
global $_W,$_GPC;
        $weid = $_W['uniacid'];
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
		$from = $_W['openid'];  
        $res = $this->getalllikeme($from);
        if (!empty($res)) {
            foreach ($res as $row) {
                $result[] = $this->getusers($weid,$row['openid']);
            }
        }
        include $this->template('likeme');