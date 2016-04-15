<?php
/*
 */
defined('IN_IA') or exit('Access Denied');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$tempdo = empty($_GPC['tempdo']) ? "" : $_GPC['tempdo'];
$pageid = empty($_GPC['pageid']) ? "" : $_GPC['pageid'];
$apido = empty($_GPC['apido']) ? "" : $_GPC['apido'];
$reply = pdo_fetch("SELECT * FROM ".tablename($this->table_reply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	$rid = intval($_GPC['rid']);
if($operation == 'display') {
	$rid = intval($_GPC['rid']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	
	$templates = pdo_fetchall("SELECT * FROM ".tablename($this->table_templates)." WHERE uniacid = '{$_W['uniacid']}' or uniacid = 0 ORDER BY name ASC, createtime DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_templates) . " WHERE uniacid = '{$_W['uniacid']}' or uniacid = 0");
	$pager = pagination($total, $pindex, $psize);
	
	include $this->template('templates');
} elseif($operation == 'post') {
	load()->func('tpl');
	$id = intval($_GPC['id']);
	$rid = intval($_GPC['rid']);
	if (!empty($id)) {
		$item = pdo_fetch("SELECT * FROM ".tablename($this->table_templates)." WHERE id = :id" , array(':id' => $id));
	} 
	$files = array('1' =>'photosvote.html', '2'=>'tuser.html', '3'=>'paihang.html', '4' =>'reg.html', '5'=>'des.html');
	if (checksubmit('submit')) {
		
		define('REGULAR_STYLENAME', '/^(([a-z]+[0-9]+)|([a-z]+))[a-z0-9]*$/i');
		
		if(!preg_match(REGULAR_STYLENAME, $_GPC['stylename'])) {
			message('必须输入模板标识，格式为 字母（不区分大小写）+ 数字,不能出现中文、中文字符');
		}
		if (empty($_GPC['title'])) {
			message('标题不能为空，请输入标题！');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'version' => $_GPC['version'],
			'description' => $_GPC['description'],
			'author' => $_GPC['author'],
			'thumb' => $_GPC['thumb'],
			'url' => $_GPC['url'],
			'type' => 'all',
			'createtime' => TIMESTAMP
		);
		if (empty($id)) {
			if ($_GPC['stylename'] == $item['templates']) {
				message('该模板标识已存在，请更换');
			}
			$data['name'] = $_GPC['stylename'];
			pdo_insert($this->table_templates, $data);
			$aid = pdo_insertid();
		} else {
			$data['name'] = $item['name'];
			unset($data['createtime']);
			pdo_update($this->table_templates, $data, array('id' => $id));
		}
		message('模板更新成功！', $this->createWebUrl('templates', array('op' => 'display', 'rid' => $rid)), 'success');
	}
	include $this->template('templates');
} elseif($operation == 'designer') {

	$stylename = $_GPC['stylename'];
	$pagetype = $_GPC['pagetype'];
	$pages = pdo_fetchall("SELECT id,pagename,pagetype,setdefault FROM " . tablename($this->table_designer) . " WHERE uniacid= :uniacid  ", array(':uniacid' => $_W['uniacid']));
	$reply = pdo_fetchall("SELECT title,rid FROM " . tablename($this->table_reply) . " WHERE status=:status and uniacid= :uniacid  ", array(':uniacid' => $_W['uniacid'], ':status' => '1'));
	$allusers = pdo_fetchall("SELECT id,rid,from_user,nickname,realname,uid,avatar,photosnum,hits,xnphotosnum,xnhits,sharenum FROM " . tablename($this->table_users) . " WHERE uniacid= :uniacid AND status=:status ORDER BY uid ASC", array(':uniacid' => $_W['uniacid'], ':status' => '1'));
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	
	$templates = pdo_fetchall("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = '{$_W['uniacid']}' AND status = 1 ORDER BY uid ASC, createtime DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_users) . " WHERE uniacid = '{$_W['uniacid']}' AND status = 1");
	$pager = pagination($total, $pindex, $psize);
	
	if (!empty($pageid)) {
		$datas = pdo_fetch("SELECT * FROM " . tablename($this->table_designer) . " WHERE uniacid= :uniacid and id=:id", array(':uniacid' => $_W['uniacid'], ':id' => $pageid));
	}else{
		$datas = pdo_fetch("SELECT * FROM " . tablename($this->table_designer) . " WHERE uniacid= :uniacid AND stylename =:stylename AND pagetype=:pagetype ", array(':uniacid' => $_W['uniacid'], ':stylename' => $stylename, ':pagetype' => $pagetype));
	}
	$pageid = empty($_GPC['pageid']) ? $datas['id'] : $_GPC['pageid'];
	$pagetype = empty($_GPC['pagetype']) ? $datas['pagetype'] : $_GPC['pagetype'];
	if (!empty($datas)) {
		//ca('designer.page.edit');
		
		$data = htmlspecialchars_decode($datas['datas']);
		$d = json_decode($data, true);
		$usersids = array();
		foreach ($d as $k1 => &$dd) {
			if ($dd['temp'] == 'photosvote') {
				foreach ($dd['data'] as $k2 => $ddd) {
					$usersids[] = array('id' => $ddd['usersid'], 'k1' => $k1, 'k2' => $k2);
				} 
			} elseif ($dd['temp'] == 'richtext') {
				$dd['content'] = $this -> unescape($dd['content']);
			} 
		} 
		unset($dd);
		$arr = array();
		foreach($usersids as $a) {
			$arr[] = $a['id'];
		} 
		if (count($arr) > 0) {
			$usersinfo = pdo_fetchall("SELECT id,rid,from_user,nickname,realname,uid,avatar,photosnum,hits,xnphotosnum,xnhits,sharenum FROM " . tablename($this->table_users) . " WHERE id in ( " . implode(',', $arr) . ") AND uniacid= :uniacid AND status=:status AND rid =:rid ORDER BY uid ASC", array(':uniacid' => $_W['uniacid'], ':status' => '1', ':rid' => 34), 'id');
			
			$usersinfo = $this->set_medias($usersinfo, 'avatar');
			foreach ($d as $k1 => &$dd) {
				if ($dd['temp'] == 'photosvote') {
					foreach ($dd['data'] as $k2 => &$ddd) {
						$cdata = $usersinfo[$ddd['usersid']];
						$ddd['name'] = !empty($cdata['nickname']) ? $cdata['nickname'] : $cdata['realname'] ;
						$ddd['uid'] = $cdata['uid'];
						$ddd['from_user'] = $cdata['from_user'];
						$ddd['piaoshu'] = $cdata['photosnum'] + $cdata['xnphotosnum'];
						$ddd['img'] = $cdata['avatar'];
						$ddd['renqi'] = $cdata['hits'] + $cdata['xnhits'];
						$ddd['sharenum'] = $cdata['sharenum'];
					} 
					unset($ddd);
				} 
			} 
			unset($dd);
		}
		$data = json_encode($d);
		$data = rtrim($data, "]");
		$data = ltrim($data, "[");
		$pageinfo = htmlspecialchars_decode($datas['pageinfo']);
		$pageinfo = rtrim($pageinfo, "]");
		$pageinfo = ltrim($pageinfo, "[");
		$users = $this->getMember($from_user);
		$usersname = !empty($users['realname']) ? $users['realname'] : $users['nickname'] ;
		$system = array('tusertop' => array('name' => $usersname, 'logo' => tomedia($users['avatar'])));
		$system = json_encode($system);
	} else {
		//ca('designer.page.edit');
		$pageinfo = "{id:'M0000000000000',temp:'topbar',params:{title:'',desc:'',img:'',kw:'',footer:'1',floatico:'0',floatstyle:'right',floatwidth:'40px',floattop:'100px',floatimg:'',floatlink:''}}";
	}
	include $this->template('templates');
} elseif($operation == 'default') {
	$rid = intval($_GPC['rid']);
	
	if (!empty($rid) && !empty($_GPC['templatesname']) && $rid <> 0) {
		pdo_update($this->table_reply, array('templates' => $_GPC['templatesname']), array('rid' => $rid));
		$fmdata = array(
			"success" => 1,
			"msg" => '设置默认模板成功！'
		);
		echo json_encode($fmdata);
		exit();	
		//message('设置默认模板成功！', $this->createWebUrl('index', array('rid' => $rid)), 'success');
	}else{
		$fmdata = array(
			"success" => -1,
			"msg" => '设置默认模板错误！'
		);
		echo json_encode($fmdata);
		exit();	
		//message('设置默认模板错误！', $this->createWebUrl('index', array('rid' => $rid)), 'error');
	}
	
} elseif($operation == 'delete') {
	load()->func('file');
	$id = intval($_GPC['id']);
	$row = pdo_fetch("SELECT id,thumb,stylename FROM ".tablename($this->table_templates)." WHERE id = :id", array(':id' => $id));
	if (empty($row)) {
		message('抱歉，模板不存在或是已经被删除！');
	}
	if (!empty($row['thumb'])) {
		file_delete($row['thumb']);
	}
	pdo_delete($this->table_templates, array('id' => $id));
	pdo_delete($this->table_designer, array('stylename' => $row['stylename']));
	message('删除成功！', $this->createWebUrl('templates', array('op' => 'display', 'rid' => $rid)), 'success');
} elseif ($operation == 'api') {
	if ($_W['ispost']) {
		
		if ($apido == 'savepage') {
			$id = $_GPC['pageid'];
			$datas = $_GPC['datas'];
			$date = date("Y-m-d H:i:s");
			$pagename = $_GPC['pagename'];
			$pagetype = $_GPC['pagetype'];
			$stylename = $_GPC['stylename'];
			$pageinfo = $_GPC['pageinfo'];
			$stylename = $_GPC['stylename'];

			$p = htmlspecialchars_decode($pageinfo);
			$p = json_decode($p, true);
			$keyword = empty($p[0]['params']['kw']) ? "" : $p[0]['params']['kw'];
			$insert = array('pagename' => $pagename, 'pagetype' => $pagetype, 'stylename' => $stylename, 'pageinfo' => $pageinfo, 'savetime' => $date, 'datas' => $datas, 'uniacid' => $_W['uniacid'], 'keyword' => $keyword,);
			if (empty($id)) {
				//ca('designer.page.edit');
				$insert['createtime'] = $date;
				pdo_insert($this->table_designer, $insert);
				$id = pdo_insertid();
				load()->func('file');
				
				$file = gettemplates($pagetype);
		
				$targetfile = IA_ROOT . '/addons/fm_photosvote/template/mobile/templates/' . $stylename . '/' . $file;
				if(!file_exists($targetfile)) {
					mkdirs(dirname($targetfile));
					file_put_contents($targetfile, $content);
					@chmod($targetfile, $_W['config']['setting']['filemode']);
				}
			
				//plog('designer.page.edit', "店铺装修-添加修改页面 ID: {$id}");
			} else {
				load()->func('file');
				
				$file = gettemplates($pagetype);
				
				$targetfile = IA_ROOT . '/addons/fm_photosvote/template/mobile/templates/' . $stylename . '/' . $file;
				if(!file_exists($targetfile)) {
					mkdirs(dirname($targetfile));
					$content = getcontent();
					file_delete($targetfile);
					file_put_contents($targetfile, $content);
					@chmod($targetfile, $_W['config']['setting']['filemode']);
				}
			
				//ca('designer.page.edit');
				if ($pagetype == '4') {
					$insert['setdefault'] = '0';
				}
				pdo_update($this->table_designer, $insert, array('id' => $id));
				//plog('designer.page.edit', "店铺装修-修改修改页面 ID: {$id}");
			}
			/**$rule = pdo_fetch("select * from " . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name  limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'ewei_shop', ':name' => "ewei_shop:designer:" . $id));
			if (empty($rule)) {
				$rule_data = array('uniacid' => $_W['uniacid'], 'name' => 'ewei_shop:designer:' . $id, 'module' => 'ewei_shop', 'displayorder' => 0, 'status' => 1);
				pdo_insert('rule', $rule_data);
				$rid = pdo_insertid();
				$keyword_data = array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'module' => 'ewei_shop', 'content' => trim($keyword), 'type' => 1, 'displayorder' => 0, 'status' => 1);
				pdo_insert('rule_keyword', $keyword_data);
			} else {
				pdo_update('rule_keyword', array('content' => trim($keyword)), array('rid' => $rule['id']));
			}**/
			echo $id;
			exit;
		} elseif ($apido == 'delpage') {
			//ca('designer.page.delete');
			if (empty($pageid)) {
				message('删除失败！Url参数错误', $this->createWebUrl('templates'), 'error');
			} else {
				$page = pdo_fetch("SELECT * FROM " . tablename($this->table_designer) . " WHERE uniacid= :uniacid and id=:id", array(':uniacid' => $_W['uniacid'], ':id' => $pageid));
				if (empty($page)) {
					echo '删除失败！目标页面不存在！';
					exit();
				} else {
					$do = pdo_delete($this->table_designer, array('id' => $pageid));
					if ($do) {
						/**$rule = pdo_fetch("select * from " . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name  limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'ewei_shop', ':name' => "ewei_shop:designer:" . $pageid));
						if (!empty($rule)) {
							pdo_delete('rule_keyword', array('rid' => $rule['id']));
							pdo_delete('rule', array('id' => $rule['id']));
						}**/
						//plog('designer.page.edit', "店铺装修-修改修改页面 ID: {$pageid} 页面名称: {$page['pagename']}");
						echo 'success';
					} else {
						echo '删除失败！';
					}
				}
			}
		} elseif ($apido == 'selectgood') {
			$kw = $_GPC['kw'];
			$rid = $_GPC['rid'];
			$where ='';
			if (!empty($kw)) {
				$where .= ' AND (nickname LIKE %{$kw}% OR realname LIKE %{$kw}%) ';
			}
			$users = pdo_fetchall("SELECT id,rid,from_user,nickname,realname,uid,avatar,photosnum,hits,xnphotosnum,xnhits,sharenum FROM " . tablename($this->table_users) . " WHERE uniacid= :uniacid AND status=:status AND rid =:rid $where ORDER BY uid ASC", array(':uniacid' => $_W['uniacid'], ':status' => '1', ':rid' => $rid));
			foreach ($users as $key => $value) {
				$photos = pdo_fetch("SELECT * FROM " . tablename($this->table_users_picarr) . " WHERE from_user=:from_user AND isfm=:isfm AND uniacid=:uniacid AND rid =:rid ", array(':from_user' => $value['from_user'], ':isfm' => '1', ':uniacid' => $_W['uniacid'], ':rid' => $rid));
				if (empty($photos)) {
					$photos = pdo_fetch("SELECT * FROM " . tablename($this->table_users_picarr) . " WHERE from_user=:from_user AND uniacid=:uniacid AND rid =:rid ORDER BY id ASC LIMIT 1", array(':from_user' => $value['from_user'], ':uniacid' => $_W['uniacid'], ':rid' => $rid));
				}
				
				$users[$key]['mphotos'] = $photos['photos'];
			}

			$users = $this->set_medias($users, array('avatar','mphotos'));
			echo json_encode($users);
		} elseif ($apido == 'setdefault') {
			//ca('designer.page.setdefault');
			$do = $_GPC['d'];
			$id = $_GPC['id'];
			$type = $_GPC['type'];
			if ($do == 'on') {
				$pages = pdo_fetch("SELECT * FROM " . tablename($this->table_designer) . " WHERE pagetype=:pagetype AND setdefault=:setdefault AND uniacid=:uniacid ", array(':pagetype' => $type, ':setdefault' => '1', ':uniacid' => $_W['uniacid']));
				if (!empty($pages)) {
					$array = array('setdefault' => '0');
					pdo_update($this->table_designer, $array, array('id' => $pages['id']));
				}
				$array = array('setdefault' => '1');
				$action = pdo_update($this->table_designer, $array, array('id' => $id));
				if ($action) {
					$json = array('result' => 'on', 'id' => $id, 'closeid' => $pages['id']);
					//plog('designer.page.edit', "店铺装修-设置默认页面 ID: {$id} 页面名称: {$pages['pagename']}");
					echo json_encode($json);
				}
			} else {
				$pages = pdo_fetch("SELECT * FROM " . tablename($this->table_designer) . " WHERE  id=:id and uniacid=:uniacid ", array(':id' => $id, ':uniacid' => $_W['uniacid']));
				if ($pages['setdefault'] == 1) {
					$array = array('setdefault' => '0');
					$action = pdo_update($this->table_designer, $array, array('id' => $pages['id']));
					if ($action) {
						$json = array('result' => 'off', 'id' => $pages['id']);
						//plog('designer.page.edit', "店铺装修-关闭默认页面 ID: {$id} 页面名称: {$pages['pagename']}");
						echo json_encode($json);
					}
				}
			}
		} elseif ($apido == 'selectkeyword') {
			$kw = $_GPC['kw'];
			$rid = $_GPC['rid'];
			$pid = $_GPC['pid'];
			$rule = pdo_fetch("select * from " . tablename('rule_keyword') . ' where content=:content and uniacid=:uniacid and module=:module limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'ewei_shop', ':content' => $kw));
			if (empty($rule)) {
				echo 'ok';
			} else {
				$rule2 = pdo_fetch("select * from " . tablename('rule') . ' where id=:id and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $rule['rid']));
				if ($rule2['name'] == 'ewei_shop:designer:' . $pid) {
					echo 'ok';
				}
			}
		} elseif ($apido == 'selectlink') {
			$type = $_GPC['type'];
			$kw = $_GPC['kw'];
			$rid = $_GPC['rid'];
			if ($type == 'notice') {
				$notices = pdo_fetchall("select * from " . tablename('ewei_shop_notice') . ' where title LIKE :title and status=:status and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':status' => '1', ':title' => "%{$kw}%"));
				echo json_encode($notices);
			} elseif ($type == 'users') {
				$where ='';
				if (!empty($kw)) {
					$where .= ' AND (nickname LIKE %{$kw}% OR realname LIKE %{$kw}%) ';
				}
				$users = pdo_fetchall("SELECT id,rid,from_user,nickname,realname,uid,avatar,photosnum,hits,xnphotosnum,xnhits,sharenum FROM " . tablename($this->table_users) . " WHERE uniacid= :uniacid AND status=:status AND rid =:rid $where ORDER BY uid ASC", array(':uniacid' => $_W['uniacid'], ':status' => '1', ':rid' => $rid));
				echo json_encode($users);
			} else {
				exit();
			}
		}
	}
	exit();
}

function gettemplates($pagetype) {
	
	switch ($pagetype) {
	  case '1':
	    $name = 'photosvote.html';
	    break;
	  case '2':
	    $name = 'tuser.html';
	    break;
	  case '3':
	    $name = 'paihang.html';
	    break;
	  case '4':
	    $name = 'reg.html';
	    break;
	  case '5':
	    $name = 'des.html';
	    break;
	  
	  default:
	    $name = 'photosvote.html';
	    break;
	}
	return $name;
}
function getcontent() {
	$content ='<!doctype html>
<html ng-app="myApp">
<head>
<meta charset="utf-8">
<title>{$share[\'title\']}</title>
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport" name="viewport">
<script>var require = {urlArgs: \'v={php echo date(\'YmdHis\');}\'};</script>
<script language="javascript" src="{FMURL}js/dist/jquery-1.11.1.min.js"></script>
<script language="javascript" src="{FMURL}js/dist/jquery.gcjs.js"></script>
<link href="{FMURL}css/font-awesome.min.css" rel="stylesheet">
<link href="{FMURL}/designer/imgsrc/designer.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{FMURL}mobile/style/css/style.css">
<link rel="stylesheet" type="text/css" href="{FMURL}css/bootstrap.min.css">
<style>
body {margin:0px; background:#f9f9f9; }
.fe-mod:hover{border:2px dashed rgba(0,0,0,0); cursor:default;}
.fe-mod,.fe-mod:hover {border:0px;}
</style>
</head>
<body ng-controller="MainCtrl">
    <!-- 浮动按钮 -->
    <div class="fe-floatico" style="position: fixed;" ng-style="{\'width\':pages[0].params.floatwidth,\'top\':pages[0].params.floattop}" ng-class="{\'fe-floatico-right\':pages[0].params.floatstyle==\'right\'}" ng-show="pages[0].params.floatico==1">
        <a href="{{pages[0].params.floathref || \'javascript:;\'}}">
            <img src="{{pages[0].params.floatimg || \'{FMURL}/designer/imgsrc/init-data/init-image-7.png\'}}" style="width:100%;" />
        </a>
    </div>
    <!-- 关注按钮1 -->
    {if $followed!=1}
        <div style="height: 50px;" ng-show="pages[0].params.guide==1"></div>
        <a href="{$guide[\'followurl\']}">
            <div class="fe-guide" style="position: fixed;" ng-style="{\'display\':\'block\',\'background-color\':pages[0].params.guidebgcolor,\'opacity\':pages[0].params.guideopacity}" ng-show="pages[0].params.guide==1">
                <div class="fe-guide-faceimg" ng-style="{\'border-radius\':pages[0].params.guidefacestyle}">
                    <img src="{$guide[\'logo\']}" ng-style="{\'border-radius\':pages[0].params.guidefacestyle}" />
                </div>
                <div class="fe-guide-sub" ng-style="{\'color\':pages[0].params.guidenavcolor,\'background-color\':pages[0].params.guidenavbgcolor}">{{pages[0].params.guidesub ||\'立即关注\'}}</div>
                <div class="fe-guide-text"  ng-style="{\'font-size\':pages[0].params.guidesize,\'color\':pages[0].params.guidecolor}">
                    <p {if empty($guide[\'title2\'])} style="line-height:40px;"{/if}>{$guide[\'title1\']}</p>
                    <p {if empty($guide[\'title1\'])} style="line-height:40px;"{/if}>{$guide[\'title2\']}</p>
                </div>
            </div>
        </a>
    {/if}
    <div ng-repeat="Item in Items" class="fe-mod-repeat">
        <div ng-include="\'{FMURL}/designer/temp/show-\'+Item.temp+\'.html\'" class="fe-mod-parent" id="{{Item.id}}" mid="{{Item.id}}" on-finish-render-filters></div>
    </div>
    <div ng-show="Items==\'\'" style="line-height: 300px; text-align: center; font-size: 14px; color: #999;">
        <div id="core_loading" style="top:50%;left:50%;margin-left:-35px;margin-top:-50%;position:absolute;width:80px;height:60px;"><img src="{FMURL}images/loading.svg" width="80" /></div>
    </div>
<script type="text/javascript" src="{FMURL}designer/imgsrc/angular.min.js"></script>
<script type="text/javascript" src="{FMURL}designer/imgsrc/hhSwipe.js"></script>
<script type="text/javascript">
    function initswipe(jobj){
        var bullets = jobj.next().get(0).getElementsByTagName(\'a\');
        var banner = Swipe(jobj.get(0), {
            auto: 4000,
            continuous: true,
            disableScroll:false,
            callback: function(pos) {
                var i = bullets.length;
                while (i--) {
                    $(bullets[i]).css("opacity",0.4);
                }
                $(bullets[pos]).css("opacity",0.6);
            }
        })
    }
    var app = angular.module(\'myApp\', []);
    app.controller(\'MainCtrl\', [\'$scope\', function($scope){
            $scope.tusertop = {
                uniacid:\'{$_W["uniacid"]}\',
                rid:\'{$rid}\'
            };
            $scope.pages = [{$pageinfo}];
            $scope.system = [{$system}];
            $scope.Items = [{$data}];
            $scope.show = \'1\';
            $scope.$on(\'ngRepeatFinished\',function(ngRepeatFinishedEvent){
                $(\'.fe-mod-2 .swipe\').each(function(){
                        initswipe($(this));
                 });
                 $(\'.fe-mod-8-main-img img\').each(function(){
                     $(this).height($(this).width());    
                 });
                 $(\'.fe-mod-12 img\').each(function(){
                     $(this).height($(this).width());    
                 });
            });
    }]);
    app.directive(\'stringHtml\' , function(){
        return function(scope , el , attr){
            if(attr.stringHtml){
                scope.$watch(attr.stringHtml , function(html){
                    el.html(html || \'\');
                });
            }
        };
    });  
    app.directive("onFinishRenderFilters",function($timeout){
        return{
            restrict: \'A\',
            link: function(scope,element,attr){
                if(scope.$last === true){
                    $timeout(function(){
                        scope.$emit(\'ngRepeatFinished\');
                    });
                }
            }
        };
    });
</script>

{template \'footer\'}
';
	return $content;
}
function getnames($names) {
	switch ($names) {
	  case 'photosvote.html':
	    $name = '投票首页';
	    break;
	  case 'tuser.html':
	    $name = '投票详情页';
	    break;
	  case 'tuserphotos.html':
	    $name = '投票相册展示页';
	    break;
	  case 'reg.html':
	    $name = '注册报名页';
	    break;
	  case 'paihang.html':
	    $name = '排行榜页';
	    break;
	  case 'des.html':
	    $name = '活动详情页';
	    break;
	  
	  default:
	    $name = '女神来了';
	    break;
	}
	return $name;
}
