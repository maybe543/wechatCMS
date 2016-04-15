<?php



defined('IN_IA') or exit('Access Denied');
define("YYZF_MODULENAME", "mon_yyzf");

require_once IA_ROOT . "/addons/" . YYZF_MODULENAME . "/CRUD.class.php";
class Mon_YYZFModuleProcessor extends WeModuleProcessor {

    private $sae=false;

    public function respond() {
        $rid = $this->rule;
		
        $yy=pdo_fetch("select * from ".tablename(CRUD::$table_mon_yyzf)." where rid=:rid",array(":rid"=>$rid));


        if(!empty($yy)){

                $from=$this->message['from'];
                $news = array ();
                $news [] = array ('title' => $yy['new_title'], 'description' =>$yy['new_content'], 'picurl' => $this->getpicurl ( $yy ['new_icon'] ), 'url' => $this->createMobileUrl ( 'Zf',array('openid'=>$from,'yid'=>$yy['id']))  );
                return $this->respNews ( $news );

        }else{
          return   $this->respText("语音祝福删除或不存在");

        }

        return null;
    }




    private function getpicurl($url) {
        global $_W;


        return $_W ['attachurl'] . $url;

    }

}
