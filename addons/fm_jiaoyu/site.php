<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
define('OSSURL', '../addons/fm_jiaoyu/');
class Fm_jiaoyuModuleSite extends WeModuleSite {
	
	// ===============================================
	public $m = 'wx_school';
	public $table_cat = 'wx_school_cat';
	public $table_classify = 'wx_school_classify';
	public $table_score = 'wx_school_score';
	public $table_index = 'wx_school_index';
	public $table_students = 'wx_school_students';
	public $table_tcourse = 'wx_school_tcourse';
	public $table_teachers = 'wx_school_teachers';
	public $table_area = 'wx_school_area';
    public $table_type = 'wx_school_type';	
    public $table_kcbiao = 'wx_school_kcbiao';	
	public $table_cook = 'wx_school_cookbook';	
	public $table_reply = 'wx_school_reply';	
	public $table_banners = 'wx_school_banners';
	public $table_bbsreply = 'wx_school_bbsreply';	
	public $table_user = 'wx_school_user';
	public $table_set = 'wx_school_set';
	public $table_leave = 'wx_school_leave';
	public $table_notice = 'wx_school_notice';
	
	// ===============================================
		
	// public function doWebUpgrade(){
		// global $_W, $_GPC;
		// include_once 'sys/upgrade.php';
		// echo 'upgraded';
	// }
	
	// 载入逻辑方法
	private function getLogic($_name, $type = "web", $auth = false) {
		global $_W, $_GPC;
		if ($type == 'web') {
			checkLogin ();
			include_once 'inc/web/' . strtolower ( substr ( $_name, 5 ) ) . '.php';
		} else if ($type == 'mobile') {
			 if ($auth) {
				  include_once 'inc/func/isauth.php';
			  }
			include_once 'inc/mobile/' . strtolower ( substr ( $_name, 8 ) ) . '.php';
		} else if ($type == 'func') {
			//checkAuth ();
			include_once 'inc/func/' . strtolower ( substr ( $_name, 8 ) ) . '.php';
		}
	}

	// ====================== Web =====================
	
	// 学校管理
	public function doWebSchool() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}

	public function doWebIndexajax() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}	
	
	// 分类管理
	public function doWebSemester() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}
	
	// 教师管理
	public function doWebAssess() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}
	
	// 学生管理
	public function doWebStudents() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}

	// 成绩查询
	public function doWebChengji() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}

    // 课程安排
	public function doWebKecheng() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}	

	// 课表安排
	public function doWebKcbiao() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}	
	
	// 课程预约
	public function doWebSubscribe() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}

	// 食谱安排
	public function doWebCookBook() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}
	
	// 首页导航
	public function doWebNave() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}

	//班级管理
	public function doWebTheclass() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}
	//成绩管理
	public function doWebScore() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}
	
	//科目管理
	public function doWebSubject() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}
	
    //时段管理
	public function doWebTimeframe() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}	
	
	//星期管理
	public function doWebWeek() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}	

	//区域管理
	public function doWebArea() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}

	//学校类型管理
	public function doWebType() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}
	
	//分校幻灯片
	public function doWebBanner() {
		$this->getLogic ( __FUNCTION__, 'web' );
	}	

    public function doWebQuery() {
        $this->getLogic ( __FUNCTION__, 'web' ); 
    }
	
    public function doWebBasic() {
        $this->getLogic ( __FUNCTION__, 'web' ); 
    }

    public function doWebCook() {
        $this->getLogic ( __FUNCTION__, 'web' ); 
    }	
		
	// ====================== Mobile =====================
	

	public function doMobileAuth() {
		$this->getLogic ( __FUNCTION__, 'func' );
	}
 	// 异步加载
	public function doMobileIndexajax() {
		$this->getLogic ( __FUNCTION__, 'mobile' );
	}	
	
    public function doMobileDongtaiajax() {
		$this->getLogic ( __FUNCTION__, 'mobile' );
	}
	
	public function doMobileWapindex() {
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}
	
    public function doMobileDetail() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileJianjie() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	
	
    public function doMobileKc() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	

    public function doMobileKcinfo() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileKcdg() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	

    public function doMobileTeachers() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileTcinfo() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	
	
    public function doMobileChaxun() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileChengji() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileCooklist() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileCook() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	
	
    public function doMobileUser() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileBangding() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	
	
    public function doMobileBdajax() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileMyinfo() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileJiaoliu() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileMytecher() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	
	
    public function doMobileMyclass() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	

    public function doMobileMyschool() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileQingjia() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	

    public function doMobileSqingjia() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	
	//for master
    public function doMobileTmssage() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileTmcomet() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileMnotice() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileMnoticelist() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileMfabu() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	
	
    //for teacher
    public function doMobileSmssage() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}
	
    public function doMobileSmcomet() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	
	
    public function doMobileXsqj() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileTjiaoliulist() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileTjiaoliu() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileNoticelist() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileNotice() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileFabu() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileSnoticelist() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}

    public function doMobileSnotice() {	
		$this->getLogic ( __FUNCTION__, 'mobile', true );
	}	
	
	// ====================== teacher =====================	
	



	
	// ====================== FUNC =====================		
    public function getNaveMenu()
    {
        global $_W, $_GPC;
        $do = $_GPC['do'];
        $navemenu = array();
        $navemenu[0] = array(
            'title' => '微教育',
            'items' => array(
                0 => array('title' => '学校管理', 'url' => $do != 'school' ? $this->createWebUrl('school', array('op' => 'display')) : ''),
                1 => array('title' => '校区设置', 'url' => $do != 'area' ? $this->createWebUrl('area', array('op' => 'display')) : ''),
                2 => array('title' => '分类设置', 'url' => $do != 'type' ? $this->createWebUrl('type', array('op' => 'display')) : ''),
                3 => array('title' => '基本设置', 'url' => $do != 'basic' ? $this->createWebUrl('basic', array('op' => 'display')) : ''),
            )
        );


        return $navemenu;
    }	

    public function set_tabbar1($action, $schoolid)
    {
        $actions_titles1 = $this->actions_titles1;
        $html = '<ul class="nav nav-tabs">';
        foreach ($actions_titles1 as $key => $value) {
            $url = $this->createWebUrl($key, array('op' => 'display', 'schoolid' => $schoolid));
            $html .= '<li class="' . ($key == $action ? 'active' : '') . '"><a href="' . $url . '">' . $value . '</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public $actions_titles1 = array(
	    'semester' => '分类管理',
        'assess' => '教师管理',
        'students' => '学生管理',
        'chengji' => '成绩查询',
        'kecheng' => '课程安排',
		'kcbiao' => '课表设置',
		'cook' => '食谱管理',
		'banner' => '幻灯片管理',		
    );	
	
    public function set_tabbar($action, $schoolid)
    {
        $actions_titles = $this->actions_titles;
        $html = '<ul class="nav nav-tabs">';
        foreach ($actions_titles as $key => $value) {
            $url = $this->createWebUrl($key, array('op' => 'display', 'schoolid' => $schoolid));
            $html .= '<li class="' . ($key == $action ? 'active' : '') . '"><a href="' . $url . '">' . $value . '</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }
	
    public $actions_titles = array(
	    'semester' => '年级管理',
        'theclass' => '班级管理',
        'score' => '成绩管理',
        'subject' => '科目管理',
        'timeframe' => '时段管理',
        'week' => '星期管理',

    );	
	
    public function showMessageAjax($msg, $code = 0)
    {
        $result['code'] = $code;
        $result['msg'] = $msg;
        message($result, '', 'ajax');
    }

	public function sendtempmsg($template_id, $url, $data, $topcolor, $tousers = '') {
		load()->func('communication');		
		load()->classs('weixin.account');
		$access_token = WeAccount::token();
		if(empty($access_token)) {
			return;
		}
		$postarr = '{"touser":"'.$tousers.'","template_id":"'.$template_id.'","url":"'.$url.'","topcolor":"'.$topcolor.'","data":'.$data.'}';
		$res = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token,$postarr);
		return true;
	}

    public function doWebsendMobileQfMsg() {
		global $_GPC,$_W;
		$groupid = $_GPC['gid'];
		$id = $_GPC['id'];
		$rid = $_GPC['rid'];
		$url = urldecode($_GPC['url']);
		$uniacid = $_W['uniacid'];
		if (!empty($groupid) || $groupid <> 0) {
			$w = " AND id = '{$groupid}'";
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$a = $item = pdo_fetch("SELECT * FROM ".tablename('site_article')." WHERE id = :id" , array(':id' => $id));
		
		if ($groupid == -1) {
			
			$userinfo = pdo_fetchall("SELECT openid FROM ".tablename('mc_mapping_fans')." WHERE uniacid = '{$_W['uniacid']}' ORDER BY updatetime DESC, fanid DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . " WHERE uniacid = '{$_W['uniacid']}'");
		}elseif ($groupid == -2) {
			
			$userinfo = pdo_fetchall("SELECT from_user FROM ".tablename('fm_photosvote_provevote')." WHERE uniacid = '{$_W['uniacid']}' AND rid = '{$rid}' ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_photosvote_provevote') . " WHERE uniacid = '{$_W['uniacid']}' AND rid = '{$rid}' ");
		}elseif ($groupid == -3) {
			
			$userinfo = pdo_fetchall("SELECT distinct(from_user) FROM ".tablename('fm_photosvote_votelog')." WHERE uniacid = '{$_W['uniacid']}' AND rid = '{$rid}'  ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_photosvote_votelog') . " WHERE uniacid = '{$_W['uniacid']}' AND rid = '{$rid}' ");
		}else {
			$userinfo = pdo_fetchall("SELECT openid FROM ".tablename('mc_mapping_fans')." WHERE uniacid = '{$_W['uniacid']}' ORDER BY updatetime DESC, fanid DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . " WHERE uniacid = '{$_W['uniacid']}'");
		}
		
		
		$pager = pagination($total, $pindex, $psize);

		//$userinfo = pdo_fetchall("SELECT * FROM ".tablename('fm_autogroup_members')." WHERE uniacid = '{$_W['uniacid']}' $uw ORDER BY id DESC");
		$fmqftemplate = pdo_fetch("SELECT fmqftemplate FROM ".tablename($this->table_reply_huihua)." WHERE rid = :rid LIMIT 1", array(':rid' => $rid));
		//message($fmqftemplate['fmqftemplate']);
		foreach ($userinfo as $mid => $u) {
			if (empty($u['from_user'])) {
				$from_user = $u['openid'];
			}else {
				$from_user = $u['from_user'];
			}
			include 'mtemplate/fmqf.php';

			if (!empty($template_id)) {
				$this->sendtempmsg($template_id, $url, $data, '#FF0000', $from_user);
			}
			if (($psize-1) == $mid) {
				$mq =  round((($pindex - 1) * $psize/$total)*100);
				$msg = '正在发送，目前：<strong style="color:#5cb85c">'.$mq.' %</strong>';
				
				$page = $pindex + 1;
				$to = $this->createWebUrl('sendMobileQfMsg', array('gid' => $groupid,'rid' => $rid,'id' => $id,'url' => $url, 'page' => $page));
				message($msg, $to);
			}
		}
		
		message('发送成功！', $this->createWebUrl('fmqf', array('rid' => $rid)));
	}

	private function sendMobileXytz($notice_id, $schoolid, $weid, $tname, $groupid) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));	
		$notice = pdo_fetch("SELECT * FROM ".tablename($this->table_notice)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $notice_id, ':schoolid' => $schoolid));
		$school = pdo_fetch("SELECT * FROM ".tablename($this->table_index)." WHERE :weid = weid AND :id = id", array(':weid' => $weid, ':id' => $schoolid));
        $template_id = $msgtemplate['xxtongzhi'];//消息模板id 微信的模板id
		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE :weid = weid AND :schoolid =schoolid", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');
		
		if ($groupid == 1) {
			
		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_user)." where weid = :weid And schoolid = :schoolid",array(':weid'=>$weid, ':schoolid'=>$schoolid));	
			
		}elseif ($groupid == 2) {
			
		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_teachers)." where weid = :weid And schoolid = :schoolid",array(':weid'=>$weid, ':schoolid'=>$schoolid));	
		
		}elseif ($groupid == 3) {
			
		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid",array(':weid'=>$weid, ':schoolid'=>$schoolid));
		
        }	
		
		foreach ($userinfo as $key => $value) {
			
			$openid = "";
			
				if ($groupid == 1) {
					
				$openid = pdo_fetchcolumn("select openid from ".tablename($this->table_user)." where id = '{$value['id']}' ");	
                
                $url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('mnotice', array('schoolid' => $schoolid,'id' => $notice_id));				
			
		        }elseif ($groupid == 2) {
					
				$openid = pdo_fetchcolumn("select openid from ".tablename($this->table_user)." where tid = '{$value['id']}' ");	
				
				$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('mnotice', array('schoolid' => $schoolid,'id' => $notice_id)); 
					
				}elseif ($groupid == 3) {
					
				$openid = pdo_fetchcolumn("select openid from ".tablename($this->table_user)." where sid = '{$value['id']}' ");  
				
				$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('snotice', array('schoolid' => $schoolid,'id' => $notice_id));
				
				}
			$schoolname ="{$school['title']}";
			$name  = "{$tname}老师";
			$bjname  = "{$category[$notice['bj_id']]['sname']}";
			$ttime = date('Y-m-d H:i:s', $notice['createtime']);
			$body  = "点击本条消息查看详情 ";
			$datas=array(
				'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
				'first'=>array('value'=>'您收到一条学校通知','color'=>'#1587CD'),
				'keyword1'=>array('value'=>$schoolname,'color'=>'#1587CD'),
				'keyword2'=>array('value'=>$name,'color'=>'#2D6A90'),
				'keyword3'=>array('value'=>$ttime,'color'=>'#1587CD'),
				'keyword4'=>array('value'=>$notice['title'],'color'=>'#1587CD'),
				'remark'=> array('value'=>$body,'color'=>'#FF9E05')
						);
			$data = json_encode($datas); //发送的消息模板数据
			
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $openid);
		}
	}	

	private function sendMobileBjtz($notice_id, $schoolid, $weid, $tname, $bj_id) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));	
		$notice = pdo_fetch("SELECT * FROM ".tablename($this->table_notice)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $notice_id, ':schoolid' => $schoolid));
        $template_id = $msgtemplate['bjtz'];//消息模板id 微信的模板id
		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE :weid = weid AND :schoolid =schoolid", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');
		
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		
		$userinfo=pdo_fetchall("SELECT * FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid And bj_id = :bj_id",array(':weid'=>$weid, ':schoolid'=>$schoolid, ':bj_id'=>$bj_id));	
		
		foreach ($userinfo as $key => $value) {
			
			$openid=pdo_fetchcolumn("select openid from ".tablename($this->table_user)." where sid = '{$value['id']}' ");
			
			$name  = "{$tname}老师";
			$bjname  = "{$category[$notice['bj_id']]['sname']}";
			$ttime = date('Y-m-d H:i:s', $notice['createtime']);
			$body  = "点击本条消息查看详情 ";
			$datas=array(
				'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
				'first'=>array('value'=>'您收到一条班级通知','color'=>'#1587CD'),
				'keyword1'=>array('value'=>$bjname,'color'=>'#1587CD'),
				'keyword2'=>array('value'=>$name,'color'=>'#2D6A90'),
				'keyword3'=>array('value'=>$ttime,'color'=>'#1587CD'),
				'keyword4'=>array('value'=>$notice['title'],'color'=>'#1587CD'),
				'remark'=> array('value'=>$body,'color'=>'#FF9E05')
						);
			$data = json_encode($datas); //发送的消息模板数据
			$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('snotice', array('schoolid' => $schoolid,'id' => $notice_id));
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $openid);
		}
	}
		
	private function sendMobileXsqj($leave_id, $schoolid, $weid, $tid) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));	
		$leave = pdo_fetch("SELECT * FROM ".tablename($this->table_leave)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $leave_id, ':schoolid' => $schoolid));
        $template_id = $msgtemplate['xsqingjia'];//消息模板id 微信的模板id
        $student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $leave['sid']));
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $tid));
		
		$guanxi = "本人";
		
		if($student['muid'] == $leave['uid']){
			$guanxi = "妈妈";
		}else if($student['duid'] == $leave['uid']) {
			$guanxi = "爸爸";
		}  
  
        if (!empty($template_id)) {
		
		$shenfen = "{$student['s_name']}{$guanxi}";
	    $stime = $leave['startime'];
	    $etime = $leave['endtime'];
		$ttime = date('Y-m-d H:i:s', $leave['createtime']);
		$time  = "{$stime}至{$etime}";
		$body .= "消息时间：{$ttime} \n";
		$body .= "点击本条消息快速处理 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'您收到了一条'.$shenfen.'的请假申请','color'=>'#1587CD'),
		'childName'=>array('value'=>$student['s_name'],'color'=>'#1587CD'),
		'time'=>array('value'=>$time,'color'=>'#2D6A90'),
		'score'=>array('value'=>$leave['conet'],'color'=>'#1587CD'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据
        }
			$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('smcomet', array('schoolid' => $schoolid,'id' => $leave_id));
		//}

		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $teacher['openid']);
		}
	}
	
	private function sendMobileXsqjsh($leaveid, $schoolid, $weid, $tname) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));	
		$leave = pdo_fetch("SELECT * FROM ".tablename($this->table_leave)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $leaveid, ':schoolid' => $schoolid));
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $leave['sid']));
        $template_id = $msgtemplate['xsqjsh'];//消息模板id 微信的模板id
        $jieguo = "";
		if($leave['status'] ==1){
			$jieguo = "同意";
		}else{
			$jieguo = "不同意";
		}
  
        if (!empty($template_id)) {
		$stime = $leave['startime'];
	    $etime = $leave['endtime'];
		$time = "{$stime}至{$etime}";
		$ctime = date('Y-m-d H:i:s', $leave['cltime']);
		$body .= "处理时间：{$ctime} \n";
		$body .= "";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'您好，'.$tname.'老师已经回复了您的请假申请','color'=>'#1587CD'),
		'keyword1'=>array('value'=>$student['s_name'],'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$time,'color'=>'#2D6A90'),
		'keyword3'=>array('value'=>$jieguo,'color'=>'#1587CD'),
		'keyword4'=>array('value'=>$tname,'color'=>'#1587CD'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据
        }
			//$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('smcomet', array('schoolid' => $schoolid,'id' => $leaveid));
		//}
		
		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $leave['openid']);
		}
	}
	
	private function sendMobileJzly($leave_id, $schoolid, $weid, $uid, $bj_id, $sid, $tid) {
		global $_GPC,$_W;

		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
        $leave = pdo_fetch("SELECT * FROM ".tablename($this->table_leave)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $leave_id, ':schoolid' => $schoolid));	
		$students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $sid));
		$msgs = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND schoolid=:schoolid AND status=:status", array(':weid' => $weid, ':schoolid' => $schoolid, ':status' => 2));
		$template_id = $msgtemplate['liuyan'];//消息模板id 微信的模板id
		$teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $tid));//查询master
		
		$guanxi = "本人";
		
		if($students['muid'] == $uid){
			$guanxi = "妈妈";
		}else if($students['duid'] == $uid) {
			$guanxi = "爸爸";
		}
		
        if (!empty($template_id)) {
		$time = date('Y-m-d H:i:s', $leave['createtime']);
		$data1 = "{$students['s_name']}{$guanxi}";
		$body .= "留言摘要：{$leave['conet']} \n";
		$body .= "点击本条消息快速回复 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'您收到了一条留言信息！','color'=>'#1587CD'),
		'keyword1'=>array('value'=>$data1,'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$time,'color'=>'#2D6A90'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据
        }	
		
		$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('tjiaoliu', array('schoolid' => $schoolid,'id' => $leave_id,'leaveid' => $leave['leaveid']));

		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $teacher['openid']);
		}
	}
	
	private function sendMobileJzlyhf($leave_id, $schoolid, $weid, $topenid, $sid, $tname) {
		global $_GPC,$_W;

		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
        $leave = pdo_fetch("SELECT * FROM ".tablename($this->table_leave)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $leave_id, ':schoolid' => $schoolid));	
		$students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $sid));
		$msgs = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND schoolid=:schoolid AND status=:status", array(':weid' => $weid, ':schoolid' => $schoolid, ':status' => 2));
		$template_id = $msgtemplate['liuyanhf'];//消息模板id 微信的模板id
		$teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $tid));//查询master
		
		$guanxi = "";
		
		if($students['muid'] == $uid){
			$guanxi = "妈妈";
		}else if($students['duid'] == $uid) {
			$guanxi = "爸爸";
		}
		
        if (!empty($template_id)) {
		$time = date('Y-m-d H:i:s', $leave['createtime']);
		$data1 = "{$students['s_name']}{$guanxi},您收到了一条老师的留言回复信息！";
		$body = "点击本条消息快速回复 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>$data1,'color'=>'#1587CD'),
		'keyword1'=>array('value'=>$tname,'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$time,'color'=>'#2D6A90'),
		'keyword3'=>array('value'=>$leave['conet'],'color'=>'#2D6A90'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据
        }	

		$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('jiaoliu', array('schoolid' => $schoolid));

		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $topenid);
		}
	}	
	
	private function sendMobileJsqj($leave_id, $schoolid, $weid) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));	
		$leave = pdo_fetch("SELECT * FROM ".tablename($this->table_leave)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $leave_id, ':schoolid' => $schoolid));
		$msgs = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND schoolid=:schoolid AND status=:status", array(':weid' => $weid, ':schoolid' => $schoolid, ':status' => 2));
        $template_id = $msgtemplate['jsqingjia'];//消息模板id 微信的模板id
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $leave['tid']));
  
  
        if (!empty($template_id)) {
		
	    $stime = $leave['startime'];
	    $etime = $leave['endtime'];
		$time = "{$stime}至{$etime}";
		$body = "点击本条消息快速处理 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'您收到了一条教师请假申请','color'=>'#1587CD'),
		'keyword1'=>array('value'=>$teacher['tname'],'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$leave['type'],'color'=>'#2D6A90'),
		'keyword3'=>array('value'=>$time,'color'=>'#1587CD'),
		'keyword4'=>array('value'=>$leave['conet'],'color'=>'#173177'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据
        }
			$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('tmcomet', array('schoolid' => $schoolid,'id' => $leave_id));
		//}
		

		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $msgs['openid']);
		}
	}

	private function sendMobileJsqjsh($leaveid, $schoolid, $weid) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));	
		$leave = pdo_fetch("SELECT * FROM ".tablename($this->table_leave)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $leaveid, ':schoolid' => $schoolid));
		$msgs = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND schoolid=:schoolid AND status=:status", array(':weid' => $weid, ':schoolid' => $schoolid, ':status' => 2));
        $template_id = $msgtemplate['jsqjsh'];//消息模板id 微信的模板id
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $leave['tid']));
        $jieguo = "";
		if($leave['status'] ==1){
			$jieguo = "同意";
		}else{
			$jieguo = "不同意";
		}
  
        if (!empty($template_id)) {
		
		$time = date('Y-m-d H:i:s', $leave['cltime']);
		$body = "点击本条消息查看详情 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'请假审批结果通知','color'=>'#1587CD'),
		'keyword1'=>array('value'=>$jieguo,'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$msgs['tname'],'color'=>'#2D6A90'),
		'keyword3'=>array('value'=>$time,'color'=>'#1587CD'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据
        }
			$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('tmcomet', array('schoolid' => $schoolid,'id' => $leaveid));
		//}
		
		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $leave['openid']);
		}
	}	

    protected function exportexcel($data = array(), $title = array(), $filename = 'report')
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GB2312", $v);
            }
            $title = implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key] = implode("\t", $data[$key]);

            }
            echo implode("\n", $data);
        }
    }

    function uploadFile($file, $filetempname, $array)
    {
        //自己设置的上传文件存放路径
        $filePath = '../addons/fm_jiaoyu/public/upload/';

        include 'inc/func/phpexcelreader/reader.php';

        $data = new Spreadsheet_Excel_Reader();
        $data->setOutputEncoding('utf-8');

        //设置时区
        $time = date("y-m-d-H-i-s"); //去当前上传的时间
        $extend = strrchr($file, '.');
        //上传后的文件名
        $name = $time . $extend;
        $uploadfile = $filePath . $name; //上传后的文件名地址

        if (copy($filetempname, $uploadfile)) {
            if (!file_exists($filePath)) {
                echo '文件路径不存在.';
                return;
            }
            if (!is_readable($uploadfile)) {
                echo ("文件为只读,请修改文件相关权限.");
                return;
            }
            $data->read($uploadfile);
            error_reporting(E_ALL ^ E_NOTICE);
            $count = 0;
            for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { //$=2 第二行开始
                //以下注释的for循环打印excel表数据
                for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
                    //echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
                }

                $row = $data->sheets[0]['cells'][$i];
                //message($data->sheets[0]['cells'][$i][1]);

                if ($array['ac'] == "assess") {
                    $count = $count + $this->upload_assess($row, TIMESTAMP, $array);
                } else if ($array['ac'] == "students") {
                    $count = $count + $this->upload_students($row, TIMESTAMP, $array);
                } else if ($array['ac'] == "chengji") {
                    $count = $count + $this->upload_chengji($row, TIMESTAMP, $array);
                }
            }
        }
        if ($count == 0) {
            $msg = "名字有重复哦！";
        } else {
            $msg = 1;
        }

        return $msg;
    }

    function upload_assess($strs, $time, $array)
    {
        global $_W;
        $insert = array();
		//时间处理
		$t = $strs[2]; //读取到的值
		$j = $strs[6];
        $birthdate = intval(($t - 25569) * 24*60*60); //转换成1970年以来的秒数	
		$jiontime = intval(($j - 25569) * 24*60*60); 
		//绑定码
		$randStr = str_shuffle('1234567890');
        $rand = substr($randStr,0,6);
		//年级处理
		$xueqi1 = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[10], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));
		$xueqi2 = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[11], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));
		$xueqi3 = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[12], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));
		//班级处理
		$banji1 = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[13], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));		
		$banji2 = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[14], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));		
		$banji3 = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[15], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));		
		//科目处理
		$kemu1 = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[16], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));   
        $kemu2 = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[17], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid'])); 		
		$insert['weid'] = $_W['uniacid'];
        $insert['tname'] = $strs[1];  
        $insert['birthdate'] = $birthdate;
        $insert['tel'] = $strs[3];
        $insert['mobile'] = $strs[4];
        $insert['email'] = $strs[5];
        $insert['jiontime'] = $jiontime;
        $insert['headinfo'] = $strs[7];
        $insert['info'] = $strs[8];
        $insert['sex'] = $strs[9];
        $insert['xq_id1'] = empty($xueqi1) ? 0 : intval($xueqi1['sid']);
        $insert['xq_id2'] = empty($xueqi2) ? 0 : intval($xueqi2['sid']);
        $insert['xq_id3'] = empty($xueqi3) ? 0 : intval($xueqi3['sid']);		
        $insert['bj_id1'] = empty($banji1) ? 0 : intval($banji1['sid']);	
        $insert['bj_id2'] = empty($banji2) ? 0 : intval($banji2['sid']);
        $insert['bj_id3'] = empty($banji3) ? 0 : intval($banji3['sid']);
        $insert['km_id1'] = empty($kemu1) ? 0 : intval($kemu1['sid']);
        $insert['km_id2'] = empty($kemu2) ? 0 : intval($kemu2['sid']);		
		$insert['schoolid'] = $array['schoolid'];
        $insert['status'] = 1;
        $insert['sort'] = '';
		$insert['code'] = $rand;
		$insert['openid'] = '';
		$insert['uid'] = 0;
		$insert['thumb'] = 'images/global/avatars/avatar_3.jpg';

        $assess = pdo_fetch("SELECT * FROM " . tablename('wx_school_teachers') . " WHERE tname=:tname AND weid=:weid And schoolid=:schoolid LIMIT 1", array(':tname' => $strs[1], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));

        if (empty($assess)) {
            return pdo_insert('wx_school_teachers', $insert);
        } else {
            return 0;
        }
    }
	
    function upload_students($strs, $time, $array)
    {
        global $_W;
        $insert = array();
        //时间处理
		$b = $strs[3]; //读取到的值
		$s = $strs[6];
		$e = $strs[7];
        $birthdate = intval(($b - 25569) * 24*60*60); //转换成1970年以来的时间戳
		$start = intval(($s - 25569) * 24*60*60); 
		$end = intval(($e - 25569) * 24*60*60); 
		//年级处理
		$xueqi = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[9], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));
		//班级处理
		$banji = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[10], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));
        $insert['weid'] = $_W['uniacid'];
        $insert['s_name'] = $strs[1];
        $insert['sex'] = $strs[2];
        $insert['birthdate'] = $birthdate;
        $insert['mobile'] = $strs[4];
        $insert['homephone'] = $strs[5];
        $insert['seffectivetime'] = $start;
        $insert['stheendtime'] = $end;
        $insert['area_addr'] = $strs[8];
        $insert['xq_id'] = empty($xueqi) ? 0 : intval($xueqi['sid']);
        $insert['bj_id'] = empty($banji) ? 0 : intval($banji['sid']);
		$insert['schoolid'] = $array['schoolid'];
		$insert['createdate'] = '';		
		$insert['jf_statu'] = '';
		$insert['localdate_id'] = '';
		$insert['note'] = '';
		$insert['amount'] = '';
		$insert['area'] = '';
		$insert['own'] = '';

        $students = pdo_fetch("SELECT * FROM " . tablename('wx_school_students') . " WHERE s_name=:s_name AND weid=:weid And schoolid=:schoolid LIMIT 1", array(':s_name' => $strs[1], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));

        if (empty($students)) {
            return pdo_insert('wx_school_students', $insert);
        } else {
            return 0;
        }
    }	

    function upload_chengji($strs, $time, $array)
    {
        global $_W;	
        $insert = array();
		//名字处理
		$sid = pdo_fetch("SELECT id FROM " . tablename('wx_school_students') . " WHERE s_name=:s_name AND weid=:weid And schoolid=:schoolid ", array(':s_name' => $strs[1], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));		
		//年级处理
		$xueqi = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[2], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));
		//期号处理
		$qihao = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[3], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));
		//班级处理
		$banji = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[4], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));		
		//科目处理
		$kemu = pdo_fetch("SELECT sid FROM " . tablename('wx_school_classify') . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid ", array(':sname' => $strs[5], ':weid' => $_W['uniacid'], ':schoolid'=> $array['schoolid']));		
        $insert['sid'] = empty($sid) ? 0 : intval($sid['id']);
        $insert['xq_id'] = empty($xueqi) ? 0 : intval($xueqi['sid']);
		$insert['qh_id'] = empty($qihao) ? 0 : intval($qihao['sid']);
        $insert['bj_id'] = empty($banji) ? 0 : intval($banji['sid']);
        $insert['km_id'] = empty($kemu) ? 0 : intval($kemu['sid']);		
        $insert['my_score'] = $strs[6];
		$insert['schoolid'] = $array['schoolid'];
        $insert['weid'] = $_W['uniacid'];

        return pdo_insert('wx_school_score', $insert);
    }	

    private function checkUploadFileMIME($file)
    {
        // 1.through the file extension judgement 03 or 07
        $flag = 0;
        $file_array = explode(".", $file ["name"]);
        $file_extension = strtolower(array_pop($file_array));

        // 2.through the binary content to detect the file
        switch ($file_extension) {
            case "xls" :
                // 2003 excel
                $fh = fopen($file ["tmp_name"], "rb");
                $bin = fread($fh, 8);
                fclose($fh);
                $strinfo = @unpack("C8chars", $bin);
                $typecode = "";
                foreach ($strinfo as $num) {
                    $typecode .= dechex($num);
                }
                if ($typecode == "d0cf11e0a1b11ae1") {
                    $flag = 1;
                }
                break;
            case "xlsx" :
                // 2007 excel
                $fh = fopen($file ["tmp_name"], "rb");
                $bin = fread($fh, 4);
                fclose($fh);
                $strinfo = @unpack("C4chars", $bin);
                $typecode = "";
                foreach ($strinfo as $num) {
                    $typecode .= dechex($num);
                }
                echo $typecode . 'test';
                if ($typecode == "504b34") {
                    $flag = 1;
                }
                break;
        }

        // 3.return the flag
        return $flag;
    }

    public function doWebUploadExcel()
    {
        global $_GPC, $_W;

        if ($_GPC['leadExcel'] == "true") {
            $filename = $_FILES['inputExcel']['name'];
            $tmp_name = $_FILES['inputExcel']['tmp_name'];

            $flag = $this->checkUploadFileMIME($_FILES['inputExcel']);
            if ($flag == 0) {
                message('文件格式不对.');
            }

            if (empty($tmp_name)) {
                message('请选择要导入的Excel文件！');
            }

            $msg = $this->uploadFile($filename, $tmp_name, $_GPC);

            if ($msg == 1) {
                message('导入成功！', referer(), 'success');
            } else {
                message($msg, '', 'error');
            }
        }
    }	
}
if(!function_exists('paginationm')) {
	/**
	 * 生成分页数据
	 * @param int $currentPage 当前页码
	 * @param int $totalCount 总记录数
	 * @param string $url 要生成的 url 格式，页码占位符请使用 *，如果未写占位符，系统将自动生成
	 * @param int $pageSize 分页大小
	 * @return string 分页HTML
	 */
	function paginationm($tcount, $pindex, $psize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => '')) {
		global $_W;
		$pdata = array(
			'tcount' => 0,
			'tpage' => 0,
			'cindex' => 0,
			'findex' => 0,
			'pindex' => 0,
			'nindex' => 0,
			'lindex' => 0,
			'options' => ''
		);
		if($context['ajaxcallback']) {
			$context['isajax'] = true;
		}

		$pdata['tcount'] = $tcount;
		$pdata['tpage'] = ceil($tcount / $psize);
		if($pdata['tpage'] <= 1) {
			return '';
		}
		$cindex = $pindex;
		$cindex = min($cindex, $pdata['tpage']);
		$cindex = max($cindex, 1);
		$pdata['cindex'] = $cindex;
		$pdata['findex'] = 1;
		$pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
		$pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
		$pdata['lindex'] = $pdata['tpage'];

		if($context['isajax']) {
			if(!$url) {
				$url = $_W['script_name'] . '?' . http_build_query($_GET);
			}
			$pdata['faa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['paa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['naa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['laa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', ' . $context['ajaxcallback'] . ')"';
		} else {
			if($url) {
				$pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
				$pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
				$pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
				$pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
			} else {
				$_GET['page'] = $pdata['findex'];
				$pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
				$_GET['page'] = $pdata['pindex'];
				$pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
				$_GET['page'] = $pdata['nindex'];
				$pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
				$_GET['page'] = $pdata['lindex'];
				$pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			}
		}

		$html = '<div class="pagination pagination-centered"><ul class="pagination pagination-centered">';
		if($pdata['cindex'] > 1) {
			$html .= "<li><a {$pdata['faa']} class=\"pager-nav\">首页</a></li>";
			$html .= "<li><a {$pdata['paa']} class=\"pager-nav\">&laquo;上一页</a></li>";
		}
		//页码算法：前5后4，不足10位补齐
		if(!$context['before'] && $context['before'] != 0) {
			$context['before'] = 5;
		}
		if(!$context['after'] && $context['after'] != 0) {
			$context['after'] = 4;
		}

		if($context['after'] != 0 && $context['before'] != 0) {
			$range = array();
			$range['start'] = max(1, $pdata['cindex'] - $context['before']);
			$range['end'] = min($pdata['tpage'], $pdata['cindex'] + $context['after']);
			if ($range['end'] - $range['start'] < $context['before'] + $context['after']) {
				$range['end'] = min($pdata['tpage'], $range['start'] + $context['before'] + $context['after']);
				$range['start'] = max(1, $range['end'] - $context['before'] - $context['after']);
			}
			for ($i = $range['start']; $i <= $range['end']; $i++) {
				if($context['isajax']) {
					$aa = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $i . '\', ' . $context['ajaxcallback'] . ')"';
				} else {
					if($url) {
						$aa = 'href="?' . str_replace('*', $i, $url) . '"';
					} else {
						$_GET['page'] = $i;
						$aa = 'href="?' . http_build_query($_GET) . '"';
					}
				}
				$html .= ($i == $pdata['cindex'] ? '<li class="active"><a href="javascript:;">' . $i . '</a></li>' : "<li><a {$aa}>" . $i . '</a></li>');
			}
		}

		if($pdata['cindex'] < $pdata['tpage']) {
			$html .= "<li><a {$pdata['naa']} class=\"pager-nav\">下一页&raquo;</a></li>";
			$html .= "<li><a {$pdata['laa']} class=\"pager-nav\">尾页</a></li>";
		}
		$html .= '</ul></div>';
		return $html;
	}
}