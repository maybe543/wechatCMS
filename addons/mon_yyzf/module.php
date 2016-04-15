<?php
/**
 * 
 *
 * @author  codeMonkey
 * qq:631872807
 * @url
 */
defined('IN_IA') or exit('Access Denied');


define("YYZF_MODULENAME", "mon_yyzf");

require_once IA_ROOT . "/addons/" . YYZF_MODULENAME . "/CRUD.class.php";



class Mon_yyZfModule extends WeModule {

	public $weid;
	public function __construct() {
		global $_W;
		$this->weid = IMS_VERSION<0.6?$_W['weid']:$_W['uniacid'];

	}
	

	public function fieldsFormDisplay($rid = 0) {
		global $_W;

		if(!empty($rid)){


			$reply=CRUD::findUnique(CRUD::$table_mon_yyzf,array(":rid"=>$rid));

		}
		load()->func('tpl');
		include $this->template('form');


	}
	public function fieldsFormValidate($rid = 0) {

		return '';
	}
	public function fieldsFormSubmit($rid) {
		global $_GPC, $_W;


		$yid=$_GPC['yid'];

		$data=array(
			'title'=>$_GPC['title'],
			'rid'=>$rid,
			'start_title'=>$_GPC['start_title'],
			'play_title'=>$_GPC['play_title'],
			'record_tip'=>htmlspecialchars_decode($_GPC['record_tip']),
			'follow_url'=>$_GPC['follow_url'],
			'weid'=>$this->weid,
			'wish'=>htmlspecialchars_decode($_GPC['wish']),
			'new_icon'=>$_GPC['new_icon'],
			'new_title'=>$_GPC['new_title'],
			'new_content'=>$_GPC['new_content'],
			'share_icon'=>$_GPC['share_icon'],
			'share_title'=>$_GPC['share_title'],
			'share_content'=>$_GPC['share_content'],
			'createtime'=>TIMESTAMP
		);

		if(empty($yid)){

			CRUD::create(CRUD::$table_mon_yyzf,$data);


		}else{

			CRUD::updateById(CRUD::$table_mon_yyzf,$data,$yid);
		}

		return true;
	}
	public function ruleDeleted($rid) {

		$yy=CRUD::findUnique(CRUD::$table_mon_yyzf,array(":rid"=>$rid));

		pdo_delete(CRUD::$table_mon_yyzf_record,array("yid"=>$yy['id']));

	}
    
    
    
   

}