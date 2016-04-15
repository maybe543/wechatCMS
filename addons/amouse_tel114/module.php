<?php
/**
 * 一战到底模块定义
 */
defined('IN_IA') or exit('Access Denied');

class amouse_tel114Module extends WeModule {

	public $tablename = 'amouse_fighting_setting';
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
        load()->func('tpl');
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));		
 			if(!empty($reply['picture'])) {
				$reply['picture'] = tomedia($reply['picture']);
			}
 		}else{
 			$reply['start'] = time();
			$reply['end'] = time() + 6 * 86400;
			$reply['status_fighting'] = '0';
            $reply['most_num_times'] = '1';
 		}
 			/*$a= 2;
		$b = &$a;
		echo (++$a)+($a++);*/
		include $this->template('form');
	}

  	public function fieldsFormValidate($rid = 0) {
        return true;
	}
  
  	public function fieldsFormSubmit($rid = 0) {
		global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
			'title' => $_GPC['title'],
			'description' => htmlspecialchars_decode($_GPC['description']),
			'qnum' => $_GPC['qnum'],
			'picture' => $_GPC['picture'],
			'thumb' => $_GPC['thumb'],
			'thumb_url' => $_GPC['thumb_url'],
			'weid' => $_W['uniacid'],
            'most_num_times'=> $_GPC['most_num_times'],
			'status_fighting' =>$_GPC['status_fighting'],
			'answertime' =>$_GPC['answertime'],
			'start' =>strtotime($_GPC['datelimit']['start']),
			'followurl' => $_GPC['followurl'],
            'share_title' => $_GPC['share_title'],
            'share_picture' => $_GPC['share_picture'],
			'end' =>strtotime($_GPC['datelimit']['end']),
		);
		if (empty($id)) {
			pdo_insert($this->tablename, $insert);
		} else {
			pdo_update($this->tablename, $insert, array('id' => $id));
		}
      	return true;
	}

   	public function ruleDeleted($rid = 0) {
		global $_W;
		$replies = pdo_fetchall("SELECT id,rid FROM ".tablename($this->tablename)." WHERE rid = '$rid'");
		$deleteid = array();
		if (!empty($replies)) {
          	foreach ($replies as $index => $row) {
				$deleteid[] = $row['id'];
			}     
		}
		pdo_delete($this->tablename, "id IN ('".implode("','", $deleteid)."')");
		return true;
	}

	public function settingsDisplay($settings) {

	}
	
}