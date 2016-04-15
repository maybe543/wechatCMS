<?php
/*
 * 分销自定义模块
 */
defined('IN_IA') or exit('Access Denied');
include 'model.php';
require_once IA_ROOT . '/addons/hc_hunxiao/responser.php';
class hc_hunxiaoModuleSite extends WeModuleSite {
	//用于异步执行二维码图片合成程序
	public function doMobileRunTask() {
		global $_W, $_GPC;
		ignore_user_abort(true);
		$uniacid = $_W['uniacid'];
		$openid = $_GPC['from_user'];
		$qr = new QRResponser($this->app, $this->sec);
		//$qr->respondText($_GPC['from_user']);
		$member = pdo_fetch("select * from ".tablename('hc_hunxiao_member')." where weid = ".$uniacid." and from_user = '".$openid."'");
		$id = $member['id'];
		$qr->aa($openid, $uniacid, $id);	
		exit(0);
	}
	
	public function __web($f_name){
		global $_W,$_GPC;
		checklogin();
		$weid = $_W['uniacid'];
		load()->func('tpl');
		$op= $operation = $_GPC['op']?$_GPC['op']:'display';
		
		include_once  'web/'.strtolower(substr($f_name,5)).'.php';
	}

	public function __mobile($f_name){
		global $_W,$_GPC;
		
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			 echo '请在手机微信端打开！';exit;
		}
		
		$weid = $_W['uniacid'];
		$uid = $_W['member']['uid'];
		$from_user = $_W['openid'];
		$op = $_GPC['op']?$_GPC['op']:'display';
		if(intval($_GPC['mid'])){
			$day_cookies = 15;
			$shareid = 'hc_hunxiao_shareid'.$_W['uniacid'];
			if(empty($_COOKIE[$shareid]) || (($_GPC['mid']!=$_COOKIE[$shareid]) && !empty($_GPC['mid']))){
				setcookie("$shareid", $_GPC['mid'], time()+3600*24*$day_cookies);
			}
		}
		include_once  'mobile/'.strtolower(substr($f_name,8)).'.php';
	}
	
	
	public function doWebCategory() {
       	$this->__web(__FUNCTION__);
    }
	
    public function doWebSetGoodsProperty() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		if (in_array($type, array('new', 'hot', 'recommand', 'discount'))) {
			$data = ($data==1?'0':'1');
			pdo_update("hc_hunxiao_goods", array("is" . $type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('status'))) {
			$data = ($data==1?'0':'1');
			pdo_update("hc_hunxiao_goods", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('type'))) {
			$data = ($data==1?'2':'1');
			pdo_update("hc_hunxiao_goods", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));
	}

    public function doWebGoods() {
        global $_GPC, $_W;
        load()->func('tpl');
        $category = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		if (!empty($category)) {
            $children = '';
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'post') {


            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_goods') . " WHERE id = :id", array(':id' => $id));
                if (empty($item)) {
                    message('抱歉，商品不存在或是已经删除！', '', 'error');
                }
                $allspecs = pdo_fetchall("select * from " . tablename('hc_hunxiao_spec')." where goodsid=:id order by displayorder asc",array(":id"=>$id));
                foreach ($allspecs as &$s) {
                    $s['items'] = pdo_fetchall("select * from " . tablename('hc_hunxiao_spec_item') . " where specid=:specid order by displayorder asc", array(":specid" => $s['id']));
                }
                unset($s);

                $params = pdo_fetchall("select * from " . tablename('hc_hunxiao_goods_param') . " where goodsid=:id order by displayorder asc", array(':id' => $id));
                $piclist = unserialize($item['thumb_url']);
                //处理规格项
                $html = "";
                $options = pdo_fetchall("select * from " . tablename('hc_hunxiao_goods_option') . " where goodsid=:id order by id asc", array(':id' => $id));

                //排序好的specs
                $specs = array();
                //找出数据库存储的排列顺序
                if (count($options) > 0) {
                    $specitemids = explode("_", $options[0]['specs'] );
                    foreach($specitemids as $itemid){
                        foreach($allspecs as $ss){
                             $items=  $ss['items'];
                             foreach($items as $it){
                                 if($it['id']==$itemid){
                                     $specs[] = $ss;
                                     break;
                                 }
                             }
                        }
                    }
                    
                    $html .= '<table class="table table-bordered table-condensed">';
					$html .= '<thead>';
					$html .= '<tr class="active">';

                    $len = count($specs);
                    $newlen = 1; //多少种组合
                    $h = array(); //显示表格二维数组
                    $rowspans = array(); //每个列的rowspan


                    for ($i = 0; $i < $len; $i++) {
                        //表头
                        $html .= "<th style='width:80px;'>" . $specs[$i]['title'] . "</th>";

                        //计算多种组合
                        $itemlen = count($specs[$i]['items']);
                        if ($itemlen <= 0) {
                            $itemlen = 1;
                        }
                        $newlen*=$itemlen;

                        //初始化 二维数组
                        $h = array();
                        for ($j = 0; $j < $newlen; $j++) {
                            $h[$i][$j] = array();
                        }
                        //计算rowspan
                        $l = count($specs[$i]['items']);
                        $rowspans[$i] = 1;
                        for ($j = $i + 1; $j < $len; $j++) {
                            $rowspans[$i]*= count($specs[$j]['items']);
                        }
                    }
                    //   print_r($rowspans);exit();

					$html .= '<th class="info" style="width:130px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">库存</div><div class="input-group"><input type="text" class="form-control option_stock_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_stock\');"></a></span></div></div></th>';
					$html .= '<th class="success" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">销售价格</div><div class="input-group"><input type="text" class="form-control option_marketprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_marketprice\');"></a></span></div></div></th>';
					$html .= '<th class="warning" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">市场价格</div><div class="input-group"><input type="text" class="form-control option_productprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_productprice\');"></a></span></div></div></th>';
					$html .= '<th class="danger" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">成本价格</div><div class="input-group"><input type="text" class="form-control option_costprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_costprice\');"></a></span></div></div></th>';
					$html .= '<th class="info" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">重量（克）</div><div class="input-group"><input type="text" class="form-control option_weight_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_weight\');"></a></span></div></div></th>';
					$html .= '</tr></thead>';
                    for($m=0;$m<$len;$m++){
                        $k = 0;$kid = 0;$n=0;
                             for($j=0;$j<$newlen;$j++){
                                   $rowspan = $rowspans[$m]; //9
                                   if( $j % $rowspan==0){
                                        $h[$m][$j]=array("html"=> "<td rowspan='".$rowspan."'>".$specs[$m]['items'][$kid]['title']."</td>","id"=>$specs[$m]['items'][$kid]['id']);
                                       // $k++; if($k>count($specs[$m]['items'])-1) { $k=0; }
                                   }
                                   else{
                                       $h[$m][$j]=array("html"=> "","id"=>$specs[$m]['items'][$kid]['id']);
                                   }
                                   $n++;
                                   if($n==$rowspan){
                                     $kid++; if($kid>count($specs[$m]['items'])-1) { $kid=0; }
                                      $n=0;
                                   }
                        }
                     }
         
                    $hh = "";
                    for ($i = 0; $i < $newlen; $i++) {
                        $hh.="<tr>";
                        $ids = array();
                        for ($j = 0; $j < $len; $j++) {
                            $hh.=$h[$j][$i]['html'];
                            $ids[] = $h[$j][$i]['id'];
                        }
                        $ids = implode("_", $ids);

                        $val = array("id" => "","title"=>"", "stock" => "", "costprice" => "", "productprice" => "", "marketprice" => "", "weight" => "");
                        foreach ($options as $o) {
                            if ($ids === $o['specs']) {
                                $val = array("id" => $o['id'],
                                    "title"=>$o['title'],
                                    "stock" => $o['stock'],
                                    "costprice" => $o['costprice'],
                                    "productprice" => $o['productprice'],
                                    "marketprice" => $o['marketprice'],
                                    "weight" => $o['weight']);
                                break;
                            }
                        }

                        $hh .= '<td class="info">';
						$hh .= '<input name="option_stock_' . $ids . '[]"  type="text" class="form-control option_stock option_stock_' . $ids . '" value="' . $val['stock'] . '"/></td>';
						$hh .= '<input name="option_id_' . $ids . '[]"  type="hidden" class="form-control option_id option_id_' . $ids . '" value="' . $val['id'] . '"/>';
						$hh .= '<input name="option_ids[]"  type="hidden" class="form-control option_ids option_ids_' . $ids . '" value="' . $ids . '"/>';
						$hh .= '<input name="option_title_' . $ids . '[]"  type="hidden" class="form-control option_title option_title_' . $ids . '" value="' . $val['title'] . '"/>';
						$hh .= '</td>';
						$hh .= '<td class="success"><input name="option_marketprice_' . $ids . '[]" type="text" class="form-control option_marketprice option_marketprice_' . $ids . '" value="' . $val['marketprice'] . '"/></td>';
						$hh .= '<td class="warning"><input name="option_productprice_' . $ids . '[]" type="text" class="form-control option_productprice option_productprice_' . $ids . '" " value="' . $val['productprice'] . '"/></td>';
						$hh .= '<td class="danger"><input name="option_costprice_' . $ids . '[]" type="text" class="form-control option_costprice option_costprice_' . $ids . '" " value="' . $val['costprice'] . '"/></td>';
						$hh .= '<td class="info"><input name="option_weight_' . $ids . '[]" type="text" class="form-control option_weight option_weight_' . $ids . '" " value="' . $val['weight'] . '"/></td>';
						$hh .= '</tr>';
                    }
                    $html.=$hh;
                    $html.="</table>";
                }
            }
            if (empty($category)) {
                message('抱歉，请您先添加商品分类！', $this->createWebUrl('category', array('op' => 'post')), 'error');
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['goodsname'])) {
                    message('请输入商品名称！');
                }
                if (empty($_GPC['pcate'])) {
                    message('请选择商品分类！');
                }
                $data = array(
                    'weid' => intval($_W['uniacid']),
                    'displayorder' => intval($_GPC['displayorder']),
                    'title' => $_GPC['goodsname'],
                    'pcate' => intval($_GPC['pcate']),
                    'ccate' => intval($_GPC['ccate']),
                    'type' => intval($_GPC['type']),
                    'isrecommand' => intval($_GPC['isrecommand']),
                    'ishot' => intval($_GPC['ishot']),
                    'isnew' => intval($_GPC['isnew']),
                    'isdiscount' => intval($_GPC['isdiscount']),
                    'istime' => intval($_GPC['istime']),
                    'timestart' => strtotime($_GPC['timestart']),
                    'timeend' => strtotime($_GPC['timeend']),
                    'description' => $_GPC['description'],
					'sharedescription' => $_GPC['sharedescription'],
                    'content' => htmlspecialchars_decode($_GPC['content']),
                    'goodssn' => $_GPC['goodssn'],
                    'unit' => $_GPC['unit'],
                    'thumb' => $_GPC['thumb'],
                    'createtime' => TIMESTAMP,
                    'total' => intval($_GPC['total']),
                    'totalcnf' => intval($_GPC['totalcnf']),
                    'marketprice' => $_GPC['marketprice'],
                    'fxprice' => $_GPC['fxprice'],
                    'weight' => $_GPC['weight'],
                    'costprice' => $_GPC['costprice'],
                    'productprice' => $_GPC['productprice'],
                    'productsn' => $_GPC['productsn'],
                    'credit' => intval($_GPC['credit']),
                    'jianjie' => $_GPC['jianjie'],
                    'maxbuy' => intval($_GPC['maxbuy']),
                    'commission' => intval($_GPC['commission']),
                    'hasoption' => intval($_GPC['hasoption']),
                    'sales' => intval($_GPC['sales']),
                    'issetfree' => intval($_GPC['issetfree']),
                    'status' => intval($_GPC['status']),
                    'tips' => trim($_GPC['tips']),
                );

                $cur_index = 0;
                if (!empty($_GPC['attachment-new'])) {
                    foreach ($_GPC['attachment-new'] as $index => $row) {
                        if (empty($row)) {
                            continue;
                        }
                        $hsdata[$index] = array(
                            'attachment' => $_GPC['attachment-new'][$index],
                        );
                    }
                    $cur_index = $index + 1;
                }
				if(is_array($_GPC['thumbs'])){
					$data['thumb_url'] = serialize($_GPC['thumbs']);
				}

                if (empty($id)) {
                    pdo_insert('hc_hunxiao_goods', $data);
                    $id = pdo_insertid();
                } else {
                    unset($data['createtime']);
                    pdo_update('hc_hunxiao_goods', $data, array('id' => $id));
                }


                $totalstocks = 0;

                //处理自定义参数    

                $param_ids = $_POST['param_id'];
                $param_titles = $_POST['param_title'];
                $param_values = $_POST['param_value'];
                $param_displayorders = $_POST['param_displayorder'];
                $len = count($param_ids);
                $paramids = array();
                for ($k = 0; $k < $len; $k++) {
                    $param_id = "";
                    $get_param_id = $param_ids[$k];
                    $a = array(
                        "title" => $param_titles[$k],
                        "value" => $param_values[$k],
                        "displayorder" => $k,
                        "goodsid" => $id,
                    );
                    if (!is_numeric($get_param_id)) {
                        pdo_insert("hc_hunxiao_goods_param", $a);
                        $param_id = pdo_insertid();
                    } else {
                        pdo_update("hc_hunxiao_goods_param", $a, array('id' => $get_param_id));
                        $param_id = $get_param_id;
                    }
                    $paramids[] = $param_id;
                }
                if (count($paramids) > 0) {
                    pdo_query("delete from " . tablename('hc_hunxiao_goods_param') . " where goodsid=$id and id not in ( " . implode(',', $paramids) . ")");
                }
                else{
                    pdo_query("delete from " . tablename('hc_hunxiao_goods_param') . " where goodsid=$id");
                }
//                if ($totalstocks > 0) {
//                    pdo_update("hc_hunxiao_goods", array("total" => $totalstocks), array("id" => $id));
//                }
                //处理商品规格
                $files = $_FILES;
                $spec_ids = $_POST['spec_id'];
                $spec_titles = $_POST['spec_title'];

                $specids = array();
                $len = count($spec_ids);
                $specids = array();
                $spec_items = array();
                for ($k = 0; $k < $len; $k++) {
                    $spec_id = "";
                    $get_spec_id = $spec_ids[$k];
                    $a = array(
                        "weid" => $_W['uniacid'],
                        "goodsid" => $id,
                        "displayorder" => $k,
                        "title" => $spec_titles[$get_spec_id]
                    );
                    if (is_numeric($get_spec_id)) {

                        pdo_update("hc_hunxiao_spec", $a, array("id" => $get_spec_id));
                        $spec_id = $get_spec_id;
                    } else {
                        pdo_insert("hc_hunxiao_spec", $a);
                        $spec_id = pdo_insertid();
                    }
                    //子项
                    $spec_item_ids = $_POST["spec_item_id_".$get_spec_id];
                    $spec_item_titles = $_POST["spec_item_title_".$get_spec_id];
                    $spec_item_shows = $_POST["spec_item_show_".$get_spec_id];
                    
                    $spec_item_thumb = $_POST["spec_item_thumb_".$get_spec_id];
                    $itemlen = count($spec_item_ids);
                    $itemids = array();
                    
             
                    for ($n = 0; $n < $itemlen; $n++) {
                        $item_id = "";
                        $get_item_id = $spec_item_ids[$n];
                        $d = array(
                            "weid" => $_W['uniacid'],
                            "specid" => $spec_id,
                            "displayorder" => $n,
                            "title" => $spec_item_titles[$n],
                            "show" => $spec_item_shows[$n],
                            "thumb" => $spec_item_thumb[$n]
                        );
                        $f = "spec_item_thumb_" . $get_item_id;
                       

                        if (is_numeric($get_item_id)) {
                            pdo_update("hc_hunxiao_spec_item", $d, array("id" => $get_item_id));
                            $item_id = $get_item_id;
                        } else {
                            pdo_insert("hc_hunxiao_spec_item", $d);
                            $item_id = pdo_insertid();
                        }
                        $itemids[] = $item_id;

                        //临时记录，用于保存规格项
                        $d['get_id'] = $get_item_id;
                        $d['id']= $item_id;
                        $spec_items[] = $d;
                    }
                    //删除其他的
                    if(count($itemids)>0){
                         pdo_query("delete from " . tablename('hc_hunxiao_spec_item') . " where weid={$_W['uniacid']} and specid=$spec_id and id not in (" . implode(",", $itemids) . ")");    
                    }
                    else{
                         pdo_query("delete from " . tablename('hc_hunxiao_spec_item') . " where weid={$_W['uniacid']} and specid=$spec_id");    
                    }
                    
                    //更新规格项id
                    pdo_update("hc_hunxiao_spec", array("content" => serialize($itemids)), array("id" => $spec_id));

                    $specids[] = $spec_id;
                }

                //删除其他的
                if( count($specids)>0){
                	pdo_query("delete from " . tablename('hc_hunxiao_spec') . " where weid={$_W['uniacid']} and goodsid=$id and id not in (" . implode(",", $specids) . ")");
                }
                else{
                    pdo_query("delete from " . tablename('hc_hunxiao_spec') . " where weid={$_W['uniacid']} and goodsid=$id");
                }


                //保存规格
           
                $option_idss = $_POST['option_ids'];
                $option_productprices = $_POST['option_productprice'];
                $option_marketprices = $_POST['option_marketprice'];
                $option_costprices = $_POST['option_costprice'];
                $option_stocks = $_POST['option_stock'];
                $option_weights = $_POST['option_weight'];
                $len = count($option_idss);
                $optionids = array();
                for ($k = 0; $k < $len; $k++) {
                    $option_id = "";
                    $get_option_id = $_GPC['option_id_' . $ids][0];
             
                    $ids = $option_idss[$k]; $idsarr = explode("_",$ids);
                    $newids = array();
                    foreach($idsarr as $key=>$ida){
                        foreach($spec_items as $it){
                            if($it['get_id']==$ida){
                                $newids[] = $it['id'];
                                break;
                            }
                        }
                    }
                    $newids = implode("_",$newids);
                     
                    $a = array(
                        "title" => $_GPC['option_title_' . $ids][0],
                        "productprice" => $_GPC['option_productprice_' . $ids][0],
                        "costprice" => $_GPC['option_costprice_' . $ids][0],
                        "marketprice" => $_GPC['option_marketprice_' . $ids][0],
                        "stock" => $_GPC['option_stock_' . $ids][0],
                        "weight" => $_GPC['option_weight_' . $ids][0],
                        "goodsid" => $id,
                        "specs" => $newids
                    );
                   
                    $totalstocks+=$a['stock'];
					
                    if (empty($get_option_id)) {
                        pdo_insert("hc_hunxiao_goods_option", $a);
                        $option_id = pdo_insertid();
                    } else {
						if($k == 0){
							$hehe = $get_option_id;
						}
						if($k>0 && $get_option_id == $hehe){
							pdo_insert("hc_hunxiao_goods_option", $a);
							$option_id = pdo_insertid();
						} else {
							pdo_update("hc_hunxiao_goods_option", $a, array('id' => $get_option_id));
							$option_id = $get_option_id;
						}
                    }
                    $optionids[] = $option_id;
                }
                if (count($optionids) > 0) {
                    pdo_query("delete from " . tablename('hc_hunxiao_goods_option') . " where goodsid=$id and id not in ( " . implode(',', $optionids) . ")");
                }
                else{
                    pdo_query("delete from " . tablename('hc_hunxiao_goods_option') . " where goodsid=$id");
                }
                

                //总库存
                if ($totalstocks > 0) {
                    pdo_update("hc_hunxiao_goods", array("total" => $totalstocks), array("id" => $id));
                }
                //message('商品更新成功！', $this->createWebUrl('goods', array('op' => 'display')), 'success');
                message('商品更新成功！', $this->createWebUrl('goods', array('op' => 'post', 'id' => $id)), 'success');
            }
        } elseif ($operation == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $condition = '';
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
            }

            if (!empty($_GPC['cate_2'])) {
                $cid = intval($_GPC['cate_2']);
                $condition .= " AND ccate = '{$cid}'";
            } elseif (!empty($_GPC['cate_1'])) {
                $cid = intval($_GPC['cate_1']);
                $condition .= " AND pcate = '{$cid}'";
            }

            if (isset($_GPC['status'])) {
                $condition .= " AND status = '" . intval($_GPC['status']) . "'";
            }

            $list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_goods') . " WHERE weid = '{$_W['uniacid']}' and deleted=0 $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hc_hunxiao_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 $condition");
            $pager = pagination($total, $pindex, $psize);
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id, thumb FROM " . tablename('hc_hunxiao_goods') . " WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，商品不存在或是已经被删除！');
            }
//            if (!empty($row['thumb'])) {
//                file_delete($row['thumb']);
//            }
//            pdo_delete('hc_hunxiao_goods', array('id' => $id));
            //修改成不直接删除，而设置deleted=1
            pdo_update("hc_hunxiao_goods", array("deleted" => 1), array('id' => $id));

            message('删除成功！', referer(), 'success');
        } elseif ($operation == 'productdelete') {
            $id = intval($_GPC['id']);
            pdo_delete('hc_hunxiao_product', array('id' => $id));
            message('删除成功！', '', 'success');
        }
		//配送方式
        $dispatch = pdo_fetchall("select id, dispatchname from " . tablename("hc_hunxiao_dispatch") . " WHERE enabled = 1 and weid = {$_W['uniacid']} order by displayorder desc");
        
        include $this->template('goods');
    }

    public function doWebOrder() {
        global $_W, $_GPC;
		load()->func('tpl');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($_GPC['opp']=='output'){
			$conditions = array(
				'keyword'=>$_GPC['keyword'],
				'transid'=>$_GPC['transid'],
				'member'=>$_GPC['member'],
				'paytype'=>$_GPC['paytype'],
				'status'=>$_GPC['status'],
				'starttime'=>strtotime($_GPC['time']['start']),
				'endtime'=>strtotime($_GPC['time']['end']) + 86399
			);
			$url = $this->createWebUrl('outputorder', array('conditions'=>$conditions));
			header("location:$url");
		}
        if ($operation == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $status = $_GPC['status'];
            $sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
            $condition = '';
			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = time();
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND createtime >= ".$starttime." AND createtime <= ".$endtime;
				$paras[':starttime'] = $starttime;
				$paras[':endtime'] = $endtime;
			}
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND ordersn LIKE '%{$_GPC['keyword']}%'";
            }
			 if (!empty($_GPC['transid'])) {
                $condition .= " AND transid = '{$_GPC['transid']}'";
            }
			if (!empty($_GPC['member'])) {
				$addressids = pdo_fetchall("select id from ".tablename('hc_hunxiao_address')." where weid = ".$_W['uniacid']." and realname LIKE '%{$_GPC['member']}%' or mobile LIKE '%{$_GPC['member']}%'");
				$addressid = 0;
				if(!empty($addressids)){
					foreach($addressids as $a){
						$addressid = $addressid.','.$a['id'];
					}
					$addressid = trim($addressid, ',');
				}
				$condition .= " AND addressid in (".$addressid.")";
			}
			if($_GPC['paytype'] !=-1){
				if (!empty($_GPC['paytype'])) {
					$condition .= " AND paytype = '{$_GPC['paytype']}'";
				} elseif ($_GPC['paytype'] === '0') {
					$condition .= " AND paytype = '{$_GPC['paytype']}'";
				}
			}
            if (!empty($_GPC['cate_2'])) {
                $cid = intval($_GPC['cate_2']);
                $condition .= " AND ccate = '{$cid}'";
            } elseif (!empty($_GPC['cate_1'])) {
                $cid = intval($_GPC['cate_1']);
                $condition .= " AND pcate = '{$cid}'";
            }

            if ($status != -3 && $status!='') {
                $condition .= " AND status = '" . intval($status) . "'";
            }
			$paytype = array (
					'0' => array('css' => 'default', 'name' => '未支付'),
					'1' => array('css' => 'danger','name' => '余额支付'),
					'2' => array('css' => 'info', 'name' => '在线支付'),
					'3' => array('css' => 'warning', 'name' => '货到付款')
			);
			if(!empty($_GPC['shareid'])){
				$shareid = $_GPC['shareid'];
				$orderid = pdo_fetchall("select orderid from ".tablename('hc_hunxiao_memberrelative')." where shareid = ".$shareid." and weid = ".$_W['uniacid']);
				$orderids = '';
				if(empty($orderid)){
					$orderids = "(0)";
				} else {
					foreach($orderid as $o){
						$orderids = $orderids.$o['orderid'].',';
					}
					$orderids = '('.trim($orderids,',').')';
				}
				$condition .= " AND id in ". $orderids;
			}
			
            if (!empty($sendtype) && empty($_GPC['shareid'])) {
                $condition .= " AND sendtype = '" . intval($sendtype)."'";
            }

            $list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_order') . " WHERE weid = '{$_W['uniacid']}' $condition ORDER BY status ASC, createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hc_hunxiao_order') . " WHERE weid = '{$_W['uniacid']}' $condition");
            $pager = pagination($total, $pindex, $psize);
			if(!empty($shareid)){
				if (!empty($list)) {
					foreach ($list as $key=>$l){
						$commission = pdo_fetch("select total, commission from ".tablename('hc_hunxiao_memberrelative')." where shareid = ".$shareid." and orderid = ".$l['id']." and weid = ".$_W['uniacid']);
						$list[$key]['commission'] = $commission['commission'] * $commission['total'];
					}
				}
			}
            if (!empty($list)) {
                foreach ($list as &$row) {
                    !empty($row['addressid']) && $addressids[$row['addressid']] = $row['addressid'];
                    $row['dispatch'] = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_dispatch') . " WHERE id = :id", array(':id' => $row['dispatch']));
                }
                unset($row);
            }
            if (!empty($addressids)) {
                $address = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_address') . " WHERE id IN ('" . implode("','", $addressids) . "')", array(), 'id');
            }
        } elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$memberrelative = pdo_fetchall("select commission, shareid from ".tablename('hc_hunxiao_memberrelative')." where weid = ".$_W['uniacid']." and orderid = ".$id);
			$members = pdo_fetchall("select id, realname, from_user from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and status = 1");
			$member = array();
			foreach($members as $m){
				$member[$m['id']] = $m['realname'];
				$from_user[$m['id']] = $m['from_user'];
			}

            $item = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_order') . " WHERE id = :id", array(':id' => $id));
            if (empty($item)) {
                message("抱歉，订单不存在!", referer(), "error");
            }
            if (checksubmit('confirmsend')) {
                if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
                    message('请输入快递单号！');
                }
               // $item = pdo_fetch("SELECT transid FROM " . tablename('hc_hunxiao_order') . " WHERE id = :id", array(':id' => $id));
                if (!empty($item['transid'])) {
                    $this->changeWechatSend($id, 1);
                }
				pdo_update('hc_hunxiao_memberrelative', array('flag'=>2), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
                pdo_update('hc_hunxiao_order', array(
                    'status' => 2,
                    'remark' => $_GPC['remark'],
                    'express' => $_GPC['express'],
                    'expresscom' => $_GPC['expresscom'],
                    'expresssn' => $_GPC['expresssn'],
                ), array('id' => $id));
				$url = $_W['siteroot'].'app/'.$this->createMobileUrl('myorder', array('op'=>'detail', 'orderid'=>$id));
				sendGoodsSend($item['from_user'], $item['ordersn'], $_GPC['expresscom'], $_GPC['expresssn'], $url);
                message('发货操作成功！', referer(), 'success');
            }
            if (checksubmit('cancelsend')) {
                $item = pdo_fetch("SELECT transid FROM " . tablename('hc_hunxiao_order') . " WHERE id = :id", array(':id' => $id));
                if (!empty($item['transid'])) {
                    $this->changeWechatSend($id, 0, $_GPC['cancelreson']);
                }
                pdo_update('hc_hunxiao_order', array(
                    'status' => 1,
                    'remark' => $_GPC['remark'],
                        ), array('id' => $id));
                message('取消发货操作成功！', referer(), 'success');
            }
            if (checksubmit('finish')) {
                pdo_update('hc_hunxiao_order', array('status' => 3, 'remark' => $_GPC['remark']), array('id' => $id));
                pdo_update('hc_hunxiao_memberrelative', array('flag'=>3), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
				pdo_update('hc_hunxiao_credit', array('status'=>0), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
				foreach($memberrelative as $m){
					if(!empty($m['commission'])){
						$url = $_W['siteroot'].'app/'.$this->createMobileUrl('fansindex');
						sendCommWarm($from_user[$m['shareid']], $m['commission'], date('Y-m-d H:i:s', time()), $url);
					}
				}
				//$this->setOrderCredit($id, true);
				//增加积分
                $this->setOrderCredit($id);
				message('订单操作成功！', referer(), 'success');
            }
//            if (checksubmit('cancel')) {
//                pdo_update('hc_hunxiao_order', array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id));
//                message('取消完成订单操作成功！', referer(), 'success');
//            }
            if (checksubmit('cancelpay')) {
                pdo_update('hc_hunxiao_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				pdo_update('hc_hunxiao_memberrelative', array('flag'=>0), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
                //设置库存
                $this->setOrderStock($id, false);
                //减少积分
                $this->setOrderCredit($id, false);

                message('取消订单付款操作成功！', referer(), 'success');
            }
            if (checksubmit('confrimpay')) {
                pdo_update('hc_hunxiao_order', array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id));
				pdo_update('hc_hunxiao_memberrelative', array('flag'=>1), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
                //设置库存
                $this->setOrderStock($id);

                message('确认订单付款操作成功！', referer(), 'success');
            }
            if (checksubmit('close')) {
                $item = pdo_fetch("SELECT transid FROM " . tablename('hc_hunxiao_order') . " WHERE id = :id", array(':id' => $id));
                if (!empty($item['transid'])) {
                    $this->changeWechatSend($id, 0, $_GPC['reson']);
                }
                pdo_update('hc_hunxiao_order', array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
				pdo_update('hc_hunxiao_memberrelative', array('flag'=>-1), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
                message('订单关闭操作成功！', referer(), 'success');
            }
            if (checksubmit('open')) {
                pdo_update('hc_hunxiao_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				pdo_update('hc_hunxiao_memberrelative', array('flag'=>0), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
                message('开启订单操作成功！', referer(), 'success');
            }

            $dispatch = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_dispatch') . " WHERE id = :id", array(':id' => $item['dispatch']));
            if (!empty($dispatch) && !empty($dispatch['express'])) {
                $express = pdo_fetch("select * from " . tablename('hc_hunxiao_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
            }
            $item['user'] = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_address') . " WHERE id = {$item['addressid']}");
            $goods = pdo_fetchall("SELECT g.id, g.title, g.status, g.goodssn, g.productsn, g.thumb, g.unit, g.marketprice,o.total,g.type,o.optionname,o.optionid,o.price as orderprice FROM " . tablename('hc_hunxiao_order_goods') . " o left join " . tablename('hc_hunxiao_goods') . " g on o.goodsid=g.id "
                    . " WHERE o.orderid='{$id}'");
            $item['goods'] = $goods;

        }elseif ($operation == 'delete') {
			/*订单删除*/
			$orderid = intval($_GPC['id']);
			pdo_delete("hc_hunxiao_memberrelative", array('orderid'=>$orderid, 'weid'=>$_W['uniacid']));
			if (pdo_delete('hc_hunxiao_order', array('id' => $orderid))) {
				message('订单删除成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
			} else {
				message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
			}
		}
        include $this->template('order');
    }

    //设置订单商品的库存 minus  true 减少  false 增加
    private function setOrderStock($id = '', $minus = true) {

        $goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,g.total as goodstotal,o.total,o.optionid,g.sales FROM " . tablename('hc_hunxiao_order_goods') . " o left join " . tablename('hc_hunxiao_goods') . " g on o.goodsid=g.id "
                . " WHERE o.orderid='{$id}'");
        foreach ($goods as $item) {
            if ($minus) {
                //属性
                if (!empty($item['optionid'])) {
                    pdo_query("update " . tablename('hc_hunxiao_goods_option') . " set stock=stock-:stock where id=:id", array(":stock" => $item['total'], ":id" => $item['optionid']));
                }
                $data = array();
                if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
                    $data['total'] = $item['goodstotal'] - $item['total'];
                }
                $data['sales'] = $item['sales'] + $item['total'];
                pdo_update('hc_hunxiao_goods', $data, array('id' => $item['id']));
            } else {
                //属性
                if (!empty($item['optionid'])) {
                    pdo_query("update " . tablename('hc_hunxiao_goods_option') . " set stock=stock+:stock where id=:id", array(":stock" => $item['total'], ":id" => $item['optionid']));
                }
                $data = array();
                if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
                    $data['total'] = $item['goodstotal'] + $item['total'];
                }
                $data['sales'] = $item['sales'] - $item['total'];
                pdo_update('hc_hunxiao_goods', $data, array('id' => $item['id']));
            }
        }
    }

    public function doWebNotice() {
        global $_GPC, $_W;
		load()->func('tpl');
        $operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
        $operation = in_array($operation, array('display')) ? $operation : 'display';

        $pindex = max(1, intval($_GPC['page']));
        $psize = 50;

        $starttime = empty($_GPC['starttime']) ? strtotime('-1 month') : strtotime($_GPC['starttime']);
        $endtime = empty($_GPC['endtime']) ? TIMESTAMP : strtotime($_GPC['endtime']) + 86399;

        $where .= " WHERE `weid` = :weid AND `createtime` >= :starttime AND `createtime` < :endtime";
        $paras = array(
            ':weid' => $_W['uniacid'],
            ':starttime' => $starttime,
            ':endtime' => $endtime
        );
        $keyword = $_GPC['keyword'];
        if (!empty($keyword)) {
            $where .= " AND `feedbackid`=:feedbackid";
            $paras[':feedbackid'] = $keyword;
        }

        $type = empty($_GPC['type']) ? 0 : $_GPC['type'];
        $type = intval($type);
        if ($type != 0) {
            $where .= " AND `type`=:type";
            $paras[':type'] = $type;
        }
        $status = empty($_GPC['status']) ? 0 : intval($_GPC['status']);
        $status = intval($status);
        if ($status != -1) {
            $where .= " AND `status` = :status";
            $paras[':status'] = $status;
        }

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('hc_hunxiao_feedback') . $where, $paras);
        $list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_feedback') . $where . " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);
        $pager = pagination($total, $pindex, $psize);

        $transids = array();
        foreach ($list as $row) {
            $transids[] = $row['transid'];
        }
        if (!empty($transids)) {
            $sql = "SELECT * FROM " . tablename('hc_hunxiao_order') . " WHERE weid='{$_W['uniacid']}' AND transid IN ( '" . implode("','", $transids) . "' )";
            $orders = pdo_fetchall($sql, array(), 'transid');
        }
        $addressids = array();
        if(!empty($orders)){
			foreach ($orders as $transid => $order) {
				$addressids[] = $order['addressid'];
			}
		}
        $addresses = array();
        if (!empty($addressids)) {
            $sql = "SELECT * FROM " . tablename('hc_hunxiao_address') . " WHERE weid='{$_W['uniacid']}' AND id IN ( '" . implode("','", $addressids) . "' )";
            $addresses = pdo_fetchall($sql, array(), 'id');
        }

        foreach ($list as &$feedback) {
            $transid = $feedback['transid'];
            $order = $orders[$transid];
            $feedback['order'] = $order;
            $addressid = $order['addressid'];
            $feedback['address'] = $addresses[$addressid];
        }

        include $this->template('notice');
    }

    public function getCartTotal() {
        global $_W;
        $cartotal = pdo_fetchcolumn("select sum(total) from " . tablename('hc_hunxiao_cart') . " where weid = '{$_W['uniacid']}' and from_user='{$_W['openid']}'");
        return empty($cartotal) ? 0 : $cartotal;
    }

    private function getFeedbackType($type) {
        $types = array(1 => '维权', 2 => '告警');
        return $types[intval($type)];
    }

    private function getFeedbackStatus($status) {
        $statuses = array('未解决', '用户同意', '用户拒绝');
        return $statuses[intval($status)];
    }
	
	// 排行榜入口
	public function doMobilePhb(){
		$this->__mobile(__FUNCTION__);
	}
	
	// 经销商入口
	public function doMobileFansIndex(){
		$this->__mobile(__FUNCTION__);
	}
	
	// 经销商注册
	public function doMobileRegister(){
		$this->__mobile(__FUNCTION__);
	}
	
	// 我的佣金
	public function doMobileCommission(){
		$this->__mobile(__FUNCTION__);
	}
	
	// 我的银行卡
	public function doMobileBankcard(){
		$this->__mobile(__FUNCTION__);
	}
	
	// 经销商订单
	public function doMobileFansorder(){
		$this->__mobile(__FUNCTION__);
	}
	
	// 活动细则
	public function doMobileRule(){
		$this->__mobile(__FUNCTION__);
	}
	
	// 我的代理商
	public function doMobileMyfans(){
		$this->__mobile(__FUNCTION__);
	}
	
	// 积分兑换
	public function doMobileCreditApply(){
		$this->__mobile(__FUNCTION__);
	}
	// 我的二维码入口
	public function doMobileMyqrcode(){
		$this->__mobile(__FUNCTION__);
	}
	
	
//-----------------------------------web端
	
	// 导出订单
	public function doWebOutPutOrder(){
		$this->__web(__FUNCTION__);
	}
	
	// 退款
	public function doWebRefund(){
		$this->__web(__FUNCTION__);
	}
	
	// 经销商管理
	public function doWebfansmanager(){
		$this->__web(__FUNCTION__);
	}
	
	// 佣金管理
	public function doWebCommission(){
		$this->__web(__FUNCTION__);
	}
	
	// 充值记录导出
	public function doWebOutCommission(){
		$this->__web(__FUNCTION__);
	}
	
	// 细则与条款
	public function doWebRules(){
		$this->__web(__FUNCTION__);
	}
	
	// 模版消息设置
	public function doWebTemplateNews(){
		$this->__web(__FUNCTION__);
	}
	
	// 积分管理
	public function doWebCredit(){
		$this->__web(__FUNCTION__);
	}
	
	// 积分申请导出
	public function doWebOutCrediting(){
		$this->__web(__FUNCTION__);
	}
	
	// 海报编辑器
	public function doWebPoster(){
		$this->__web(__FUNCTION__);
	}

	public function doMobileAjax(){
		global $_GPC, $_W;
		$from_user = $_GPC['from_user'];
		$ret = array(
			'aaa'=>$from_user,
			'from_user'=>$from_user,
		);
		echo json_encode($ret);
	}
	
    public function doMobileindex() {
        global $_GPC, $_W;
		//首页推荐
        $condition = ' and isrecommand=1';
		$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
		$psize = 4;
		$total = 0;
		$uniacid    = $_W['uniacid'];
		$rule = pdo_fetch('SELECT * FROM '.tablename('hc_hunxiao_rules').' WHERE `weid` = :weid ',array(':weid' => $_W['uniacid']));
		$rule['title'] = '商城首页-欢迎浏览';
		$rule['description'] = '欢迎浏览，商城首页-本商城有大量精美商品，价格实惠！';
		$member = pdo_fetch("SELECT id, realname, shareid, headimg, flag FROM ".tablename('hc_hunxiao_member')." WHERE from_user = '".$_W['openid']."' AND weid = ".$_W['uniacid']);
        if($op=='display'){
			$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
			if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
				echo '请在手机微信端打开！';exit;
			}
			//$rule = pdo_fetch("select * from ".tablename('hc_hunxiao_rules')." where weid = ".$_W['uniacid']);
			$day_cookies = 15;
			$shareid = 'hc_hunxiao_shareid'.$_W['uniacid'];
			if(empty($_COOKIE[$shareid]) || (($_GPC['mid']!=$_COOKIE[$shareid]) && !empty($_GPC['mid']))){
				setcookie("$shareid", $_GPC['mid'], time()+3600*24*$day_cookies);
			}
			$mid = intval($_GPC['mid']) ? intval($_GPC['mid']) : $_COOKIE[$shareid];
			if(!empty($member['shareid'])){
				$highmember = pdo_fetch("SELECT realname, headimg, realname FROM ".tablename('hc_hunxiao_member')." WHERE id = ".$member['shareid']);
			} else {
				if(intval($mid)){
					$highmember = pdo_fetch("SELECT realname, headimg FROM ".tablename('hc_hunxiao_member')." WHERE id = ".$mid);
				}
			}
			$follow = pdo_fetchcolumn("select follow from ".tablename('mc_mapping_fans')." where uniacid = ".$_W['uniacid']." and openid = '".$_W['openid']."'");
			$id = intval($member['id']) ? intval($member['id']) : 0;

			//幻灯片
			$advs = pdo_fetchall("select * from " . tablename('hc_hunxiao_adv') . " where enabled=1 and weid= '{$_W['uniacid']}'  order by displayorder asc");
			foreach ($advs as &$adv) {
				if (substr($adv['link'], 0, 5) != 'http:') {
					$adv['link'] = "http://" . $adv['link'];
				}
			}
			unset($adv);

			$rlist = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder DESC, sales DESC limit ".$psize);
			$total = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('hc_hunxiao_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition");
		}
		
		if($op=='loadmore'){
			$psize1 = intval($_GPC['psize']);
			$psize1 = $psize + $psize1;
			$rlist = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_goods') . " WHERE weid = '{$_W['uniacid']}' and deleted=0 AND status = '1' $condition ORDER BY displayorder DESC, sales DESC limit ".$psize1.",".$psize);
			if(!empty($rlist)){
				$json['psize'] = $psize1;
				foreach($rlist as $key=>$l){
					$rlist[$key]['url'] = $this->createMobileurl('detail',array('id'=>$l['id']));
				}
				$json['goods'] = $rlist;
				echo json_encode($json);
			} else {
				$json['goods'] = 0;
				echo json_encode($json);
			}
			exit;
		}
        include $this->template('list');
    }
	

    public function doMobilelistmore_rec() {
        global $_GPC, $_W;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			 echo '请在手机微信端打开！';exit;
		}
        $pindex = max(1, intval($_GPC['page']));
        $psize = 6;
        $condition = ' and isrecommand=1 ';
        $list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        include $this->template('list_more');
    }

    public function doMobilelistmore() {
        global $_GPC, $_W;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			 echo '请在手机微信端打开！';exit;
		}
        $pindex = max(1, intval($_GPC['page']));
        $psize = 6;
        $condition = '';
        if (!empty($_GPC['ccate'])) {
            $cid = intval($_GPC['ccate']);
            $condition .= " AND ccate = '{$cid}'";
            $_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('hc_hunxiao_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
        } elseif (!empty($_GPC['pcate'])) {
            $cid = intval($_GPC['pcate']);
            $condition .= " AND pcate = '{$cid}'";
        }
        $list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_goods') . " WHERE weid = '{$_W['uniacid']}' AND status = '1' $condition ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        include $this->template('list_more');
    }

   public function doMobilelist2() {
        global $_GPC, $_W;
		$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
		$condition = empty($_GPC['condition']) ? '' : $_GPC['condition'];
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		$sorturl = $this->createMobileUrl('list2', array("keyword" => $_GPC['keyword'], "pcate" => $_GPC['pcate'], "ccate" => $_GPC['ccate']));
		if (!empty($_GPC['ccate'])) {
			$condition .= " AND ccate = ".$_GPC['ccate'];
			$sorturl.="&ccate=".$_GPC['ccate'];
		}
		if (!empty($_GPC['isnew'])) {
			$condition .= " AND isnew = 1";
			$sorturl.="&isnew=1";
		}
		if (!empty($_GPC['ishot'])) {
			$condition .= " AND ishot = 1";
			$sorturl.="&ishot=1";
		}
		if (!empty($_GPC['isdiscount'])) {
			$condition .= " AND isdiscount = 1";
			$sorturl.="&isdiscount=1";
		}
		if (!empty($_GPC['istime'])) {
			$condition .= " AND istime = 1 and " . time() . ">=timestart and " . time() . "<=timeend";
			$sorturl.="&istime=1";
		}
		
		$psize = 6;
		$total = 0;
		$member = pdo_fetch("SELECT id, flag FROM ".tablename('hc_hunxiao_member')." WHERE from_user = '".$_W['openid']."' AND weid = ".$_W['uniacid']);
        if($op=='display'){
			$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
			if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
				 echo '请在手机微信端打开！';exit;
			}
		
			$rule = pdo_fetch("select * from ".tablename('hc_hunxiao_rules')." where weid = ".$_W['uniacid']);
			$urlcookie = "hc_hunxiao_url".$_W['uniacid'];
			if (empty($member)) {
				$url = $_SERVER['REQUEST_URI'];
				setcookie($urlcookie, $url, time()+3600*240);
				$this->CheckCookie();
			} else {
				if(!empty($_COOKIE[$urlcookie])){
					$url = $_COOKIE[$urlcookie];
					setcookie($urlcookie, '', time()+3600*240);
					header("location:$url");
				}
			}
			$id = intval($member['id']) ? intval($member['id']) : 0;
			$list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder desc limit ".$psize);
			$total = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('hc_hunxiao_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition");
        }
		if($op=='loadmore'){
			$psize1 = intval($_GPC['psize']);
			$psize1 = $psize + $psize1;
			$list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder desc limit ".$psize1.",".$psize);
			if(!empty($list)){
				$json['psize'] = $psize1;
				foreach($list as $key=>$l){
					$list[$key]['url'] = $this->createMobileurl('detail',array('id'=>$l['id']));
				}
				$json['goods'] = $list;
				echo json_encode($json);
			} else {
				$json['goods'] = 0;
				echo json_encode($json);
			}
			exit;
		}
		include $this->template('list2');
    }
	//商品分类页面
	public function doMobilelistCategory() {
        global $_GPC, $_W;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			 echo '请在手机微信端打开！';exit;
		}
		$category = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		//$children = array();
		foreach ($category as $index => $row) {
            if (!empty($row['parentid'])) {
                $children[$row['id']] = $row;
                unset($category[$index]);
            }
        }

		//$carttotal = $this->getCartTotal();

        include $this->template('list_category');
    }
	
    function time_tran($the_time) {

        $timediff = $the_time - time();
        $days = intval($timediff / 86400);
        if (strlen($days) <= 1) {
            $days = "0" . $days;
        }
        $remain = $timediff % 86400;
        $hours = intval($remain / 3600);
        ;
        if (strlen($hours) <= 1) {
            $hours = "0" . $hours;
        }
        $remain = $remain % 3600;
        $mins = intval($remain / 60);
        if (strlen($mins) <= 1) {
            $mins = "0" . $mins;
        }
        $secs = $remain % 60;
        if (strlen($secs) <= 1) {
            $secs = "0" . $secs;
        }
        $ret = "";
        if ($days > 0) {
            $ret.=$days . " 天 ";
        }
        if ($hours > 0) {
            $ret.=$hours . ":";
        }
        if ($mins > 0) {
            $ret.=$mins . ":";
        }

        $ret.=$secs;

        return array("倒计时 " . $ret, $timediff);
    }

    public function doMobileMyCart() {
        global $_W, $_GPC;
		
        $op = $_GPC['op'];
		$title = '购物车';
        if ($op == 'add') {
			$follow = pdo_fetch("select uid, follow from ".tablename('mc_mapping_fans')." where uniacid = ".$_W['uniacid']." and openid = '".$_W['openid']."'");
			if(empty($follow) || $follow['follow']==0){
				$res['follow']= -1;
				die(json_encode($res));
				exit;
			}
			$profile = pdo_fetch('SELECT * FROM '.tablename('hc_hunxiao_member')." WHERE  weid = :weid  AND from_user = :from_user" , array(':weid' => $_W['uniacid'],':from_user' => $_W['openid']));
            if(empty($profile)){
				$res['follow']= -2;
				die(json_encode($res));
				exit;
			}
			$goodsid = intval($_GPC['id']);
            $total = intval($_GPC['total']);
            $total = empty($total) ? 1 : $total;
            $optionid = intval($_GPC['optionid']);
            $goods = pdo_fetch("SELECT id, type, total, fxprice, marketprice, maxbuy FROM " . tablename('hc_hunxiao_goods') . " WHERE id = :id", array(':id' => $goodsid));
            if (empty($goods)) {
                $result['message'] = '抱歉，该商品不存在或是已经被删除！';
                message($result, '', 'ajax');
            }
            $marketprice = $goods['marketprice'];
            if (!empty($optionid)) {
                $option = pdo_fetch("select marketprice from " . tablename('hc_hunxiao_goods_option') . " where id=:id limit 1", array(":id" => $optionid));
                if (!empty($option)) {
                    $marketprice = $option['marketprice'];
                }
            }

            $row = pdo_fetch("SELECT id, total FROM " . tablename('hc_hunxiao_cart') . " WHERE from_user = :from_user AND weid = '{$_W['uniacid']}' AND goodsid = :goodsid  and optionid=:optionid", array(':from_user' => $_W['openid'], ':goodsid' => $goodsid,':optionid'=>$optionid));
            if ($row == false) {
                //不存在
                $data = array(
                    'weid' => $_W['uniacid'],
                    'goodsid' => $goodsid,
                    'goodstype' => $goods['type'],
                    'marketprice' => $marketprice,
                    'from_user' => $_W['openid'],
                    'total' => $total,
                    'optionid' => $optionid
                );
                pdo_insert('hc_hunxiao_cart', $data);
            } else {
                //累加最多限制购买数量
                $t = $total + $row['total'];
                if (!empty($goods['maxbuy'])) {
                    if ($t > $goods['maxbuy']) {
                        $t = $goods['maxbuy'];
                    }
                }
                //存在
                $data = array(
                    'marketprice' => $marketprice,
                    'total' => $t,
                    'optionid' => $optionid
                );
                pdo_update('hc_hunxiao_cart', $data, array('id' => $row['id']));
            }

            //返回数据
            $carttotal = $this->getCartTotal();

            $result = array(
                'result' => 1,
                'total' => $carttotal
            );
            die(json_encode($result));
        }
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			 echo '请在手机微信端打开！';exit;
		}
		$this->checkAuth();
		if ($op == 'clear') {
            pdo_delete('hc_hunxiao_cart', array('from_user' => $_W['openid'], 'weid' => $_W['uniacid']));
            die(json_encode(array("result" => 1)));
        } else if ($op == 'remove') {
            $id = intval($_GPC['id']);
            pdo_delete('hc_hunxiao_cart', array('from_user' => $_W['openid'], 'weid' => $_W['uniacid'], 'id' => $id));
            die(json_encode(array("result" => 1, "cartid" => $id)));
        } else if ($op == 'update') {
            $id = intval($_GPC['id']);
            $num = intval($_GPC['num']);
            $sql = "update " . tablename('hc_hunxiao_cart') . " set total=$num where id=:id";
            pdo_query($sql, array(":id" => $id));
            die(json_encode(array("result" => 1)));
        } else {
            $list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_cart') . " WHERE  weid = '{$_W['uniacid']}' AND from_user = '{$_W['openid']}'");
            $profile = pdo_fetch('SELECT flag FROM '.tablename('hc_hunxiao_member')." WHERE  weid = :weid  AND from_user = :from_user" , array(':weid' => $_W['uniacid'],':from_user' => $_W['openid']));
			$totalprice = 0;
            if (!empty($list)) {
                foreach ($list as &$item) {
                    $goods = pdo_fetch("SELECT  title, thumb, fxprice, marketprice, unit, total,maxbuy FROM " . tablename('hc_hunxiao_goods') . " WHERE id=:id limit 1", array(":id" => $item['goodsid']));
                    //属性
                    $option = pdo_fetch("select title,marketprice,stock from " . tablename("hc_hunxiao_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
                    if ($option) {
                        $goods['title'] = $goods['title'];
                        $goods['optionname'] = $option['title'];
                        $goods['marketprice'] = $option['marketprice'];
                        $goods['total'] = $option['stock'];
                    }
                    $item['goods'] = $goods;
                    $item['totalprice'] = (floatval($goods['marketprice']) * intval($item['total']));
                    $totalprice += $item['totalprice'];
                }
                unset($item);
            }
            include $this->template('cart');
        }
    }

    public function doMobileConfirm() {
       $this->__mobile(__FUNCTION__);
    }

    //设置订单积分
    public function setOrderCredit($orderid, $add = true) {
        global $_W;
		$order = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_order') . " WHERE id = :id limit 1", array(':id' => $orderid));
        if (empty($order)) {
            return;
        }
        $ordergoods = pdo_fetchall("SELECT goodsid, total FROM " . tablename('hc_hunxiao_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
        $ordergood = array();
		foreach($ordergoods as $o){
			$ordergood[$o['goodsid']] = $o['total'];
		}
		if (!empty($ordergoods)) {
            $goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total,credit FROM " . tablename('hc_hunxiao_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
        }
        //增加积分
        if (!empty($goods)) {

            $credits = 0;
            foreach ($goods as $g) {
                $credits+=$g['credit']*$ordergood[$g['id']];
            }
			$uid = pdo_fetchcolumn("select uid from ".tablename('mc_mapping_fans')." where openid = '".$order['from_user']."' and uniacid = ".$_W['uniacid']);
			if(intval($uid)){
				$fans = pdo_fetch("select credit1 from ".tablename('mc_members')." where uid = ".$uid);
			}
            if (!empty($fans)) {
                if ($add) {
                    $new_credit = $credits + $fans['credit1'];
                } else {
                    $new_credit = $fans['credit1'] - $credits;
                    if ($new_credit <= 0) {
                        $new_credit = 0;
                    }
                }
				pdo_update('mc_members', array("credit1" => $new_credit), array('uid'=>$uid));
            }
        }
    }

	
	//首页查询功能
	    public function doMobileSearch() {
        global $_GPC, $_W;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			 echo '请在手机微信端打开！';exit;
		}
		$keyword = $_GPC['keyword'];
		$url = $_W['siteroot'].$this->createMobileUrl('list2', array('name' =>'hc_hunxiao','weid'=>$_W['uniacid'], 'keyword'=>$keyword, 'sort'=>1));
		header("location:$url");
        include $this->template('list2');
    }
	
    public function doMobilePay() {
        global $_W, $_GPC;
        $this->checkAuth();
        $orderid = intval($_GPC['orderid']);
        $order = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_order') . " WHERE id = :id", array(':id' => $orderid));
        if ($order['status'] != '0') {
            message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('myorder'), 'error');
        }
        if (checksubmit('codsubmit')) {

            $ordergoods = pdo_fetchall("SELECT goodsid, total,optionid FROM " . tablename('hc_hunxiao_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
            if (!empty($ordergoods)) {
                $goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total,credit FROM " . tablename('hc_hunxiao_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
            }
			//邮件提醒
			if (!empty($this->module['config']['noticeemail'])) {
				$address = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_address') . " WHERE id = :id", array(':id' => $order['addressid']));
				$body = "<h3>购买商品清单</h3> <br />";
				if (!empty($goods)) {
					foreach ($goods as $row) {
						//属性
						$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("hc_hunxiao_goods_option") . " where id=:id limit 1", array(":id" => $ordergoods[$row['id']]['optionid']));
						if ($option) {
							$row['title'] = "[" . $option['title'] . "]" . $row['title'];
						}
						$body .= "名称：{$row['title']} ，数量：{$ordergoods[$row['id']]['total']} <br />";
					}
				}
				$paytype = $order['paytype']=='3'?'货到付款':'已付款';
				$body .= "<br />总金额：{$order['price']}元 （{$paytype}）<br />";
				$body .= "<h3>购买用户详情</h3> <br />";
				$body .= "真实姓名：$address[realname] <br />";
				$body .= "地区：$address[province] - $address[city] - $address[area]<br />";
				$body .= "详细地址：$address[address] <br />";
				$body .= "手机：$address[mobile] <br />";
				load()->func('communication');
				ihttp_email($this->module['config']['noticeemail'], '分销商城订单提醒', $body);
			}
            $goodsname = ''; 
			if (!empty($goods)) {
				foreach ($goods as $row) {
					//属性
					$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("hc_hunxiao_goods_option") . " where id=:id limit 1", array(":id" => $ordergoods[$row['id']]['optionid']));
					if ($option) {
						$row['title'] = "[" . $option['title'] . "]" . $row['title'];
					}
					$goodsname = $row['title'].','.$goodsname;
				}
			}
            pdo_update('hc_hunxiao_order', array('status' => '1', 'paytype' => '3'), array('id' => $orderid));
            //增加积分
            $this->setOrderCredit($orderid);
            message('订单提交成功，请您收到货时付款！', $this->createMobileUrl('myorder'), 'success');
        }

        if (checksubmit()) {
            if ($order['paytype'] == 1 && $_W['fans']['credit2'] < $order['price']) {
                message('抱歉，您帐户的余额不够支付该订单，请充值！', create_url('mobile/module/charge', array('name' => 'member', 'weid' => $_W['uniacid'])), 'error');
            }
            if ($order['price'] == '0') {
                $this->payResult(array('tid' => $orderid, 'from' => 'return', 'type' => 'credit2'));
                exit;
            }
        }
        $params['tid'] = $orderid;
        $params['user'] = $_W['openid'];
        $params['fee'] = $order['price'];
        $params['title'] = $_W['account']['name'];
        $params['ordersn'] = $order['ordersn'];
        $params['virtual'] = $order['goodstype'] == 2 ? true : false;
        include $this->template('pay');
    }

    public function doMobileContactUs() {
        global $_W;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			 echo '请在手机微信端打开！';exit;
		}
        $cfg = $this->module['config'];
        include $this->template('contactus');
    }

    public function doMobileMyOrder() {
        global $_W, $_GPC;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			 echo '请在手机微信端打开！';exit;
		}
        $this->checkAuth();
        $op = $_GPC['op'];
        if ($op == 'confirm') {
            $orderid = intval($_GPC['orderid']);
            $order = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_order') . " WHERE id = :id AND from_user = :from_user", array(':id' => $orderid, ':from_user' => $_W['openid']));
            if (empty($order)) {
                message('抱歉，您的订单不存在或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
            }
            pdo_update('hc_hunxiao_order', array('status' => 3), array('id' => $orderid, 'from_user' => $_W['openid']));
			pdo_update('hc_hunxiao_memberrelative', array('flag'=>3), array('orderid'=>$orderid, 'weid'=>$_W['uniacid']));
			pdo_update('hc_hunxiao_credit', array('status'=>0), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
			$memberrelative = pdo_fetchall("select commission, shareid from ".tablename('hc_hunxiao_memberrelative')." where weid = ".$_W['uniacid']." and orderid = ".$orderid);
			$members = pdo_fetchall("select id, realname, from_user from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and status = 1");
			$member = array();
			foreach($members as $m){
				$from_user[$m['id']] = $m['from_user'];
			}
			foreach($memberrelative as $m){
				if(!empty($m['commission'])){
					$url = $_W['siteroot'].'app/'.$this->createMobileUrl('fansindex');
					sendCommWarm($from_user[$m['shareid']], $m['commission'], date('Y-m-d H:i:s', time()), $url);
				}
			}
            message('确认收货完成！', $this->createMobileUrl('myorder'), 'success');
        } else if ($op=="cancel"){
			$orderid = intval($_GPC['orderid']);
			pdo_update('hc_hunxiao_order', array('status' => -1), array('id' => $orderid, 'from_user' => $_W['openid']));
			message('订单取消成功！', $this->createMobileUrl('myorder'), 'success');
		} else if ($op=="recover"){
			$orderid = intval($_GPC['orderid']);
			pdo_update('hc_hunxiao_order', array('status' => 0), array('id' => $orderid, 'from_user' => $_W['openid']));
			message('订单恢复成功！', $this->createMobileUrl('myorder'), 'success');
		} else if ($op=="refund"){
			$orderid = intval($_GPC['orderid']);
			if($_GPC['opp']=='refund'){
				pdo_update('hc_hunxiao_order', array('status' => -2, 'refundreason'=>trim($_GPC['refundreason']), 'refundtime'=>time()), array('id' => $orderid, 'from_user' => $_W['openid']));
				$order = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_order')." WHERE id = ".$orderid);
				$ordergoods = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_order_goods')." WHERE weid = ".$_W['uniacid']." and orderid = ".$orderid);
				$goods = pdo_fetchall("SELECT id, title FROM " . tablename('hc_hunxiao_goods')." WHERE weid = ".$_W['uniacid']);
				$good = array();
				foreach($goods as $g){
					$good[$g['id']] = $g['title'];
				}
				$title = '';
				foreach($ordergoods as $o){
					$title = $good[$o['goodsid']].','.$title;
				}
				$title = trim($title, ',');
				sendApplyMoneyBack($order['from_user'], $order['price'], $title, $order['ordersn']);
				message('退款申请成功！', $this->createMobileUrl('myorder'), 'success');
			} else {
				include $this->template('refundreason');
				exit;
			}
		} else if ($op == 'detail') {

            $orderid = intval($_GPC['orderid']);
            $item = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['openid']}' and id='{$orderid}' limit 1");
            if (empty($item)) {
                message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
            }
            $goodsid = pdo_fetchall("SELECT goodsid,total FROM " . tablename('hc_hunxiao_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');

            $goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,o.total,o.optionid FROM " . tablename('hc_hunxiao_order_goods') . " o left join " . tablename('hc_hunxiao_goods') . " g on o.goodsid=g.id "
                    . " WHERE o.orderid='{$orderid}'");
            foreach ($goods as &$g) {
                //属性
                $option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("hc_hunxiao_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
                if ($option) {
                    $g['title'] = "[" . $option['title'] . "]" . $g['title'];
                    $g['marketprice'] = $option['marketprice'];
                }
            }
            unset($g);

            $dispatch = pdo_fetch("select id,dispatchname from " . tablename('hc_hunxiao_dispatch') . " where id=:id limit 1", array(":id" => $item['dispatch']));
            include $this->template('order_details');
        } else {
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;

            $status = intval($_GPC['status']);
            $where = " weid = '{$_W['uniacid']}' AND from_user = '{$_W['openid']}'";
            ;
            if ($status == 2) {
                $where.=" and ( status=1 or status=2 )";
            } else {
                $where.=" and status=$status";
            }

            $list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_order') . " WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id');
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hc_hunxiao_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['openid']}'");
            $pager = pagination($total, $pindex, $psize);

            if (!empty($list)) {
                foreach ($list as &$row) {
                    $goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,o.total,o.optionid FROM " . tablename('hc_hunxiao_order_goods') . " o left join " . tablename('hc_hunxiao_goods') . " g on o.goodsid=g.id "
                            . " WHERE o.orderid='{$row['id']}'");
                    foreach ($goods as &$item) {
                        //属性
                        $option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("hc_hunxiao_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
                        if ($option) {
                            $item['title'] = "[" . $option['title'] . "]" . $item['title'];
                            $item['marketprice'] = $option['marketprice'];
                        }
                    }
                    unset($item);
                    $row['goods'] = $goods;
                    $row['total'] = $goodsid;
                    $row['dispatch'] = pdo_fetch("select id,dispatchname from " . tablename('hc_hunxiao_dispatch') . " where id=:id limit 1", array(":id" => $row['dispatch']));
                }
            }
			$carttotal = $this->getCartTotal();
            include $this->template('order');
        }
    }

    public function doMobileDetail() {
        global $_W, $_GPC;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			 echo '请在手机微信端打开！';exit;
		}
		$mobile = $this->module['config']['phone'];
		$day_cookies = 15;
		$rule = pdo_fetch("select * from ".tablename('hc_hunxiao_rules')." where weid = ".$_W['uniacid']);
		$shareid = 'hc_hunxiao_shareid'.$_W['uniacid'];
		if(empty($_COOKIE[$shareid]) || (($_GPC['mid']!=$_COOKIE[$shareid]) && !empty($_GPC['mid']))){
			setcookie("$shareid", $_GPC['mid'], time()+3600*24*$day_cookies);
		}
		$member = pdo_fetch( " SELECT * FROM ".tablename('hc_hunxiao_member')." WHERE from_user='".$_W['openid']."' AND weid=".$_W['uniacid']." " );
        	$goodsid = intval($_GPC['id']);
       		$goods = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_goods') . " WHERE id = :id", array(':id' => $goodsid));
		if(empty($goods['commission'])){
			$goods['commission'] = $rule['globalCommission'];
		}
		$ccate = intval($goods['ccate']);
		
		$id = intval($member['id']) ? intval($member['id']) : 0;
        if (empty($goods)) {
            message('抱歉，商品不存在或是已经被删除！');
        }
		$starttime = 0;
        if ($goods['istime'] == 1) {
            if (time() < $goods['timestart']) {
				$starttime = 1;
                //message('抱歉，还未到购买时间, 暂时无法购物哦~', referer(), "error");
            }
            if (time() > $goods['timeend']) {
				$starttime = 2;
                //message('抱歉，商品限购时间已到，不能购买了哦~', referer(), "error");
            }
        }
        //浏览量
        pdo_query("update " . tablename('hc_hunxiao_goods') . " set viewcount=viewcount+1 where id=:id and weid='{$_W['uniacid']}' ", array(":id" => $goodsid));
        $piclist1 = array(array("attachment" => $goods['thumb']));
		$piclist = array();
		if (is_array($piclist1)) {
			foreach($piclist1 as $p){
				$piclist[] = is_array($p)?$p['attachment']:$p;
			}
		}
		if ($goods['thumb_url'] != 'N;') {
			$urls = unserialize($goods['thumb_url']);
			if (is_array($urls)) {
				foreach($urls as $p){
					$piclist[] = is_array($p)?$p['attachment']:$p;
				}
			}
		}
        $marketprice = $goods['marketprice'];
        $productprice= $goods['productprice'];
        $stock = $goods['total'];

      
        //规格及规格项
           $allspecs = pdo_fetchall("select * from " . tablename('hc_hunxiao_spec') . " where goodsid=:id order by displayorder asc", array(':id' => $goodsid));
           foreach ($allspecs as &$s) {
                 $s['items'] = pdo_fetchall("select * from " . tablename('hc_hunxiao_spec_item') . " where  `show`=1 and specid=:specid order by displayorder asc", array(":specid" => $s['id']));
           }
           unset($s);
			
           //处理规格项
           $options = pdo_fetchall("select id,title,thumb,marketprice,productprice,costprice, stock,weight,specs from " . tablename('hc_hunxiao_goods_option') . " where goodsid=:id order by id asc", array(':id' => $goodsid));

           //排序好的specs
          $specs = array();
          //找出数据库存储的排列顺序
          if (count($options) > 0) {
			$specitemids = explode("_", $options[0]['specs'] );
			foreach($specitemids as $itemid){
				foreach($allspecs as $ss){
					 $items=  $ss['items'];
					 foreach($items as $it){
						 if($it['id']==$itemid){
							 $specs[] = $ss;
							 break;
						 }
					 }
				}
			}
        }
		
        if (!empty($goods['hasoption'])) {
            $options = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_goods_option') . " WHERE goodsid=:goodsid order by thumb asc,displayorder asc", array(":goodsid" => $goods['id']));
            foreach ($options as $o) {
                if ($marketprice >= $o['marketprice']) {
                    $marketprice = $o['marketprice'];
                }
                if ($productprice >= $o['productprice']) {
                    $productprice = $o['productprice'];
                }
                if ($stock <= $o['stock']) {
                    $stock = $o['stock'];
                }
            }
        }
        $params = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_goods_param') . " WHERE goodsid=:goodsid order by displayorder asc", array(":goodsid" => $goods['id']));
		$carttotal = $this->getCartTotal();
        include $this->template('detail');
    }
	


    public function doMobileAddress() {
        global $_W, $_GPC;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			 echo '请在手机微信端打开！';exit;
		}
        $from = $_GPC['from'];
        $returnurl = urldecode($_GPC['returnurl']);
        $this->checkAuth();
        // $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'post';
        $operation = $_GPC['op'];

        if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $data = array(
                'weid' => $_W['uniacid'],
                'openid' => $_W['openid'],
                'realname' => $_GPC['realname'],
                'mobile' => $_GPC['mobile'],
                'province' => $_GPC['province'],
                'city' => $_GPC['city'],
                'area' => $_GPC['area'],
                'address' => $_GPC['address'],
            );
            if (empty($_GPC['realname']) || empty($_GPC['mobile']) || empty($_GPC['address'])) {
                message('请输完善您的资料！');
            }
            if (!empty($id)) {
                unset($data['weid']);
                unset($data['openid']);
                pdo_update('hc_hunxiao_address', $data, array('id' => $id));
                message($id, '', 'ajax');
            } else {
                pdo_update('hc_hunxiao_address', array('isdefault' => 0), array('weid' => $_W['uniacid'], 'openid' => $_W['openid']));
                $data['isdefault'] = 1;
                pdo_insert('hc_hunxiao_address', $data);
                $id = pdo_insertid();
                if (!empty($id)) {
                    message($id, '', 'ajax');
                } else {
                    message(0, '', 'ajax');
                }
            }
        } elseif ($operation == 'default') {
            $id = intval($_GPC['id']);
            pdo_update('hc_hunxiao_address', array('isdefault' => 0), array('weid' => $_W['uniacid'], 'openid' => $_W['openid']));
            pdo_update('hc_hunxiao_address', array('isdefault' => 1), array('id' => $id));
            message(1, '', 'ajax');
        } elseif ($operation == 'detail') {
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id, realname, mobile, province, city, area, address FROM " . tablename('hc_hunxiao_address') . " WHERE id = :id", array(':id' => $id));
            message($row, '', 'ajax');
        } elseif ($operation == 'remove') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $address = pdo_fetch("select isdefault from " . tablename('hc_hunxiao_address') . " where id='{$id}' and weid='{$_W['uniacid']}' and openid='{$_W['openid']}' limit 1 ");

                if (!empty($address)) {
                    //pdo_delete("hc_hunxiao_address",  array('id'=>$id, 'weid' => $_W['uniacid'], 'openid' => $_W['openid']));
                    //修改成不直接删除，而设置deleted=1
                    pdo_update("hc_hunxiao_address", array("deleted" => 1, "isdefault" => 0), array('id' => $id, 'weid' => $_W['uniacid'], 'openid' => $_W['openid']));

                    if ($address['isdefault'] == 1) {
                        //如果删除的是默认地址，则设置是新的为默认地址
                        $maxid = pdo_fetchcolumn("select max(id) as maxid from " . tablename('hc_hunxiao_address') . " where weid='{$_W['uniacid']}' and openid='{$_W['openid']}' limit 1 ");
                        if (!empty($maxid)) {
                            pdo_update('hc_hunxiao_address', array('isdefault' => 1), array('id' => $maxid, 'weid' => $_W['uniacid'], 'openid' => $_W['openid']));
                            die(json_encode(array("result" => 1, "maxid" => $maxid)));
                        }
                    }
                }
            }
            die(json_encode(array("result" => 1, "maxid" => 0)));
        } else {
            //$profile = pdo_fetch("select resideprovince, residecity, residedist, address, realname, mobile from ".tablename('mc_members')." where uid = ".$_W['member']['uid']);
            $address = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_address') . " WHERE deleted=0 and openid = :openid", array(':openid' => $_W['openid']));
            $carttotal = $this->getCartTotal();
            include $this->template('address');
        }
    }

    private function checkAuth() {
        global $_W;
        $profile = pdo_fetch('SELECT * FROM '.tablename('hc_hunxiao_member')." WHERE  weid = :weid  AND from_user = :from_user" , array(':weid' => $_W['uniacid'],':from_user' => $_W['openid']));
		$gzurl = pdo_fetchcolumn("select gzurl from ".tablename('hc_hunxiao_rules')." where weid = ".$_W['uniacid']);
		$follow = pdo_fetch("select uid, follow from ".tablename('mc_mapping_fans')." where uniacid = ".$_W['uniacid']." and openid = '".$_W['openid']."'");
		if(empty($follow) || $follow['follow']==0){
			message('请先关注该公众号哦！', $gzurl, 'error');
			exit;
		}
		if(empty($profile)){
			$url = $this->createMobileUrl('register');
			header("location:$url");
		}
    }

    private function changeWechatSend($id, $status, $msg = '') {
		global $_W;
		$paylog = pdo_fetch("SELECT plid, openid, tag FROM " . tablename('core_paylog') . " WHERE tid = '{$id}' AND status = 1 AND type = 'wechat'");
		if (!empty($paylog['openid'])) {
			$paylog['tag'] = iunserializer($paylog['tag']);
			$acid = $paylog['tag']['acid'];
			$account = account_fetch($acid);
			$payment = uni_setting($account['uniacid'], 'payment');
			if ($payment['payment']['wechat']['version'] == '2') {
				return true;
			}
			$send = array(
					'appid' => $account['key'],
					'openid' => $paylog['openid'],
					'transid' => $paylog['tag']['transaction_id'],
					'out_trade_no' => $paylog['plid'],
					'deliver_timestamp' => TIMESTAMP,
					'deliver_status' => $status,
					'deliver_msg' => $msg,
			);
			$sign = $send;
			$sign['appkey'] = $payment['payment']['wechat']['signkey'];
			ksort($sign);
			$string = '';
			foreach ($sign as $key => $v) {
				$key = strtolower($key);
				$string .= "{$key}={$v}&";
			}
			$send['app_signature'] = sha1(rtrim($string, '&'));
			$send['sign_method'] = 'sha1';
			$account = WeAccount::create($acid);
			$response = $account->changeOrderStatus($send);
			if (is_error($response)) {
				message($response['message']);
			}
		}
	}

    public function payResult($params) {
		global $_W;
        $fee = intval($params['fee']);
        $data = array('status' => $params['result'] == 'success' ? 1 : 0);
		$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');
		$data['paytype'] = $paytype[$params['type']];
        if ($params['type'] == 'wechat') {
            $data['transid'] = $params['tag']['transaction_id'];
        }
        pdo_update('hc_hunxiao_order', $data, array('id' => $params['tid']));
        if ($params['from'] == 'return') {
			$order = pdo_fetch("SELECT price, from_user, createtime, paytype, addressid FROM " . tablename('hc_hunxiao_order') . " WHERE id = '{$params['tid']}'");
			$ordergoods = pdo_fetchall("SELECT goodsid, total FROM " . tablename('hc_hunxiao_order_goods') . " WHERE orderid = '{$params['tid']}'", array(), 'goodsid');
			$goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total FROM " . tablename('hc_hunxiao_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
			if (!empty($this->module['config']['noticeemail'])) {
				$address = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_address') . " WHERE id = :id", array(':id' => $order['addressid']));
				$body = "<h3>购买商品清单</h3> <br />";
				if (!empty($goods)) {
					foreach ($goods as $row) {
						$body .= "名称：{$row['title']} ，数量：{$ordergoods[$row['id']]['total']} <br />";
					}
				}
				$paytype = $order['paytype'] == '3' ? '货到付款' : '已付款';
				$body .= "<br />总金额：{$order['price']}元 （{$paytype}）<br />";
				$body .= "<h3>购买用户详情</h3> <br />";
				$body .= "真实姓名：{$address['realname']} <br />";
				$body .= "地区：{$address['province']} - {$address['city']} - {$address['area']}<br />";
				$body .= "详细地址：{$address['address']} <br />";
				$body .= "手机：{$address['mobile']} <br />";
				load()->func('communication');
				ihttp_email($this->module['config']['noticeemail'], '分销商城订单提醒', $body);
			}
			$goodsname = '';
			if (!empty($goods)) {
				foreach ($goods as $row) {
					$goodsname = $row['title'].','.$goodsname;
				}
			}
			$goodsname = trim($goodsname, ',');
			$url = $_W['siteroot'].'app/'.$this->createMobileUrl('myorder');
			sendOrder($order['from_user'], $goodsname, $order['price'], date('Y-m-d H:i:s', $order['createtime']), $url);
            if ($params['type'] == 'credit2') {
                message('支付成功！', $this->createMobileUrl('myorder'), 'success');
            } else {
                message('支付成功！', $this->createMobileUrl('myorder'), 'success');
            }
        }
    }

    public function doWebOption() {
        $tag = random(32);
        global $_GPC;
        include $this->template('option');
    }

    public function doWebSpec() {

        global $_GPC;
        $spec = array(
            "id" => random(32),
            "title" => $_GPC['title']
        );
        include $this->template('spec');
    }

    public function doWebSpecItem() {
        global $_GPC;
        load()->func('tpl');
		$spec = array(
            "id" => $_GPC['specid']
        );
        $specitem = array(
            "id" => random(32),
            "title" => $_GPC['title'],
            "show" => 1
        );
        include $this->template('spec_item');
    }

    public function doWebParam() {
        $tag = random(32);
        global $_GPC;
        include $this->template('param');
    }

    public function doWebExpress() {
        global $_W, $_GPC;
        // pdo_query('DROP TABLE ims_hc_hunxiao_express');
        //pdo_query("CREATE TABLE IF NOT EXISTS `ims_hc_hunxiao_express` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',  `express_name` varchar(50) NOT NULL COMMENT '分类名称',  `express_price` varchar(10) NOT NULL DEFAULT '0',  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',  `express_area` varchar(50) NOT NULL COMMENT '配送区域',  `enabled` tinyint(1) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");
        //pdo_query("ALTER TABLE  `ims_hc_hunxiao_order` ADD  `expressprice` VARCHAR( 10 ) NOT NULL AFTER  `totalnum` ;");
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            $list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_express') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
        } elseif ($operation == 'post') {
            $id = intval($_GPC['id']);
            if (checksubmit('submit')) {
                if (empty($_GPC['express_name'])) {
                    message('抱歉，请输入物流名称！');
                }
                $data = array(
                    'weid' => $_W['uniacid'],
                    'displayorder' => intval($_GPC['express_name']),
                    'express_name' => $_GPC['express_name'],
                    'express_url' => $_GPC['express_url'],
                    'express_area' => $_GPC['express_area'],
                );
                if (!empty($id)) {
                    unset($data['parentid']);
                    pdo_update('hc_hunxiao_express', $data, array('id' => $id));
                } else {
                    pdo_insert('hc_hunxiao_express', $data);
                    $id = pdo_insertid();
                }
                message('更新物流成功！', $this->createWebUrl('express', array('op' => 'display')), 'success');
            }
            //修改
            $express = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_express') . " WHERE id = '$id' and weid = '{$_W['uniacid']}'");
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $express = pdo_fetch("SELECT id  FROM " . tablename('hc_hunxiao_express') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
            if (empty($express)) {
                message('抱歉，物流方式不存在或是已经被删除！', $this->createWebUrl('express', array('op' => 'display')), 'error');
            }
            pdo_delete('hc_hunxiao_express', array('id' => $id));
            message('物流方式删除成功！', $this->createWebUrl('express', array('op' => 'display')), 'success');
        } else {
            message('请求方式不存在');
        }
        include $this->template('express', TEMPLATE_INCLUDEPATH, true);
    }

    public function doWebDispatch() {
        global $_W, $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {

            $list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_dispatch') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
        } elseif ($operation == 'post') {

            $id = intval($_GPC['id']);
            if (checksubmit('submit')) {
                $data = array(
                    'weid' => $_W['uniacid'],
                    'displayorder' => intval($_GPC['displayorder']),
                    'dispatchtype' => intval($_GPC['dispatchtype']),
                    'dispatchname' => $_GPC['dispatchname'],
                    'express' => $_GPC['express'],
                    'firstprice' => $_GPC['firstprice'],
                    'firstweight' => $_GPC['firstweight'],
                    'secondprice' => $_GPC['secondprice'],
                    'secondweight' => $_GPC['secondweight'],
                    'description' => $_GPC['description'],
                    'enabled' => $_GPC['enabled']
                );
                if (!empty($id)) {
                    pdo_update('hc_hunxiao_dispatch', $data, array('id' => $id));
                } else {
                    pdo_insert('hc_hunxiao_dispatch', $data);
                    $id = pdo_insertid();
                }
                message('更新配送方式成功！', $this->createWebUrl('dispatch', array('op' => 'display')), 'success');
            }
            //修改
            $dispatch = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_dispatch') . " WHERE id = '$id' and weid = '{$_W['uniacid']}'");
            $express = pdo_fetchall("select * from " . tablename('hc_hunxiao_express') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $dispatch = pdo_fetch("SELECT id  FROM " . tablename('hc_hunxiao_dispatch') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
            if (empty($dispatch)) {
                message('抱歉，配送方式不存在或是已经被删除！', $this->createWebUrl('dispatch', array('op' => 'display')), 'error');
            }
            pdo_delete('hc_hunxiao_dispatch', array('id' => $id));
            message('配送方式删除成功！', $this->createWebUrl('dispatch', array('op' => 'display')), 'success');
        } else {
            message('请求方式不存在');
        }
        include $this->template('dispatch', TEMPLATE_INCLUDEPATH, true);
    }

    public function doWebAdv() {
        global $_W, $_GPC;
		load()->func('tpl');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            $list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_adv') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
        } elseif ($operation == 'post') {

            $id = intval($_GPC['id']);
            if (checksubmit('submit')) {
                $data = array(
                    'weid' => $_W['uniacid'],
                    'advname' => $_GPC['advname'],
                    'link' => $_GPC['link'],
                    'enabled' => intval($_GPC['enabled']),
                    'displayorder' => intval($_GPC['displayorder'])
                );
                if (!empty($_GPC['thumb'])) {
                    $data['thumb'] = $_GPC['thumb'];
                    //file_delete($_GPC['thumb-old']);
                }

                if (!empty($id)) {
                    pdo_update('hc_hunxiao_adv', $data, array('id' => $id));
                } else {
                    pdo_insert('hc_hunxiao_adv', $data);
                    $id = pdo_insertid();
                }
                message('更新幻灯片成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
            }
            $adv = pdo_fetch("select * from " . tablename('hc_hunxiao_adv') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $adv = pdo_fetch("SELECT id  FROM " . tablename('hc_hunxiao_adv') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
            if (empty($adv)) {
                message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('adv', array('op' => 'display')), 'error');
            }
            pdo_delete('hc_hunxiao_adv', array('id' => $id));
            message('幻灯片删除成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
        } else {
            message('请求方式不存在');
        }
        include $this->template('adv', TEMPLATE_INCLUDEPATH, true);
    }

    public function doMobileAjaxdelete() {
        global $_GPC;
        $delurl = $_GPC['pic'];
        // if (file_delete($delurl)) {
            // echo 1;
        // } else {
            // echo 0;
        // }
    }
	
	public function doMobileUserinfo() {
		global $_GPC,$_W;
		$weid = $_W['uniacid'];//当前公众号ID
		load()->func('communication');
		//用户不授权返回提示说明
		if ($_GPC['code']=="authdeny"){
		    $url = $_W['siteroot'].'app/'.$this->createMobileUrl('index', array(), true);
			header("location:$url");
			exit('authdeny');
		}
		//高级接口取未关注用户Openid
		if (isset($_GPC['code'])){
		    //第二步：获得到了OpenID
		    $appid = $_W['account']['key'];
		    $secret = $_W['account']['secret'];
			$serverapp = $_W['account']['level'];	
			if ($serverapp!=4) {
				$cfg = $this->module['config'];
			    $appid = $cfg['appid'];
			    $secret = $cfg['secret'];
				if(empty($appid) || empty($secret)){
					return ;
				}
			}
			$state = $_GPC['state'];
			//1为关注用户, 0为未关注用户
			
		    $rid = $_GPC['rid'];
			//查询活动时间
			$code = $_GPC['code'];
		    $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
		    $content = ihttp_get($oauth2_code);
		    $token = @json_decode($content['content'], true);
			if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
				echo '<h1>获取微信公众号授权'.$code.'失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'].'<h1>';
				exit;
			}
		    $from_user = $token['openid'];
			//再次查询是否为关注用户
			$profile = pdo_fetch("select * from ".tablename('mc_mapping_fans')." where uniacid = ".$_W['uniacid']." and openid = '".$from_user."'");
			//关注用户直接获取信息	
			if ($profile['follow']==1){
			    $state = 1;
			}else{
				//未关注用户跳转到授权页
				$url = $_W['siteroot'].'app/'.$this->createMobileUrl('userinfo', array(), true);
				$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";				
				header("location:$oauth2_code");
			}
			//未关注用户和关注用户取全局access_token值的方式不一样
			
			$access_token = $token['access_token'];
			$oauth2_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$from_user."&lang=zh_CN";
			
			//使用全局ACCESS_TOKEN获取OpenID的详细信息			
			$content = ihttp_get($oauth2_url);
			$info = @json_decode($content['content'], true);
			if(empty($info) || !is_array($info) || empty($info['openid'])  || empty($info['nickname']) ) {
				echo '<h1>获取微信公众号授权失败[无法取得info], 请稍后重试！<h1>';
				exit;
			}
			$shareid = 'hc_hunxiao_shareid'.$_W['uniacid'];
			$headimg = $info['headimgurl'];
			if(empty($headimg)){
				$headimg = '';
			}
			$data = array(
				'weid'=>$_W['uniacid'],
				'from_user'=>$_W['openid'],
				'headimg'=>$headimg,
				'shareid'=>empty($_GPC['id']) ? $_COOKIE[$shareid] : $_GPC['id'],
				'realname'=>$info['nickname'],
				'mobile'=>$_GPC['mobile'],
				'pwd'=>'123456',
				'commission'=>0,
				'createtime'=>TIMESTAMP,
				'status'=>1,
				'flag'=>0
			);
			$member = pdo_fetch("select id from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and from_user = '".$_W['openid']."'");
			if(empty($member)){
				pdo_insert('hc_hunxiao_member',$data);
			} else {
				if(!empty($headimg)){
					pdo_update('hc_hunxiao_member', array('headimg'=>$headimg), array('id'=>$member['id']));
				}
			}
			setCookie('hc_hunxiao_headimgurl', $info['headimgurl'], time()+3600*240);
			$url = $this->createMobileUrl('fansindex');
			//die('<script>location.href = "'.$url.'";</script>');
			header("location:$url");
			exit;
		}else{
			echo '<h1>网页授权域名设置出错!</h1>';
			exit;		
		}
	}
	
	private function CheckCookie() {
		global $_W;
		//return;
		$appid = $_W['account']['key'];
		$secret = $_W['account']['secret'];
		//是否为高级号
		$serverapp = $_W['account']['level'];	
		if ($serverapp!=4) {
			$cfg = $this->module['config'];
			$appid = $cfg['appid'];
			$secret = $cfg['secret'];
			if(empty($appid) || empty($secret)){
				return ;
			}
		}
		//借用的
		$url = $_W['siteroot'].'app/'.$this->createMobileUrl('userinfo', array(), true);
		$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";				
		//exit($oauth2_code);
		header("location:$oauth2_code");
		exit;
	}

}

/*
$url=$this->createMobileUrl('index');
			die('<script>location.href = "'.$url.'";</script>');
			header("location:$url");
			exit;
		*/
/**
 * 生成分页数据
 * @param int $currentPage 当前页码
 * @param int $totalCount 总记录数
 * @param string $url 要生成的 url 格式，页码占位符请使用 *，如果未写占位符，系统将自动生成
 * @param int $pageSize 分页大小
 * @return string 分页HTML
 */
function pagination1($tcount, $pindex, $psize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => '')) {
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

	$html = '<div class="pagination pagination-centered"><ul>';
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
			//$html .= ($i == $pdata['cindex'] ? '<li class="active"><a href="javascript:;">' . $i . '</a></li>' : "<li><a {$aa}>" . $i . '</a></li>');
		}
	}

	if($pdata['cindex'] < $pdata['tpage']) {
		$html .= "<li><a {$pdata['naa']} class=\"pager-nav\">下一页&raquo;</a></li>";
		$html .= "<li><a {$pdata['laa']} class=\"pager-nav\">尾页</a></li>";
	}
	$html .= '</ul></div>';
	return $html;
}

function haha($hehe){
	$phone = $hehe;
	$mphone = substr($phone,3,6);
	$lphone = str_replace($mphone,"****",$phone);
	return $lphone;
}


function hehe($string=null, $length=0) {
	// 将字符串分解为单元
	$name = $string;
	if(intval($length) && !empty($name)){
		preg_match_all("/./us", $string, $match);
		if(count($match[0])>$length){
			$mname = '';
			for($i=0; $i<$length; $i++){
				$mname = $mname.$match[0][$i];
			}
			$name = $mname.'..';
		}
	}
	return $name;
}

function heihei($num=0){
	$hehe = array(
		'0'=>'',
		'1'=>'一',
		'2'=>'二',
		'3'=>'三',
		'4'=>'四',
		'5'=>'五',
		'6'=>'六',
		'7'=>'七',
		'8'=>'八',
		'9'=>'九',
		'10'=>'十'
	);
	return $hehe[$num];
}

function showStatus($status=0){
	$hehe = array(
		'-1'=>'订单取消',
		'0'=>'未受理',
		'1'=>'已付款',
		'2'=>'已发货',
		'3'=>'已完成'
	);
	return $hehe[$status];
}
//购买成功通知
function sendOrder($openid, $goodsname, $goodsprice, $createtime, $url) {
	global $_W;
	$template_id = pdo_fetchcolumn("select template_id from ".tablename('hc_hunxiao_templatenews')." where weid = ".$_W['uniacid']);
	$template_id = trim($template_id);
	//$template_id = 'L1_D8BHe--SqpaKvat2pFRjh7PhkYaQjT6DGIlv5Fw4';//消息模板id 微信的模板id
	if (!empty($template_id)) {
		$datas = array(
		   'first'=>array('value'=>'购买成功通知！','color'=>'#173177'),
		   'product'=>array('value'=>$goodsname,'color'=>'#173177'),
		   'price'=> array('value'=>$goodsprice.'元','color'=>'#173177'),
		   'time'=> array('value'=>$createtime,'color'=>'#173177'),
		   'remark'=> array('value'=>'祝您生活愉快！','color'=>'#173177'),
		);

		$data = json_encode($datas); //发送的消息模板数据
	}

	if (!empty($template_id)){
		$accountid = pdo_fetch("select * from ".tablename('account_wechats')." where uniacid = ".$_W['uniacid']);
		$appid = $accountid['key'];
		$appSecret = $accountid['secret'];
		if(empty($url)){
			$url = '';
		}
		$sendopenid = $openid;
		$topcolor = "#FF0000";
		tempmsg($template_id, $url, $data, $topcolor, $sendopenid, $appid, $appSecret);
	}
}

//订单发货通知
function sendGoodsSend($openid, $ordernum, $expresscom, $expresssn, $url) {
	global $_W;
	$template_id = pdo_fetchcolumn("select sendGoodsSend from ".tablename('hc_hunxiao_templatenews')." where weid = ".$_W['uniacid']);
	$template_id = trim($template_id);
	//$template_id = 'On-C52lNNz5ehW_REcySGprKE-xn7ZgMzAqdZgsIyZk';//消息模板id 微信的模板id
	if (!empty($template_id)) {
		$datas = array(
		   'first'=> array('value'=>'您好，您的订单已发货','color'=>'#173177'),
		   'keyword1'=> array('value'=>$ordernum,'color'=>'#173177'),
		   'keyword2'=> array('value'=>$expresscom,'color'=>'#173177'),
		   'keyword3'=> array('value'=>$expresssn,'color'=>'#173177'),
		   'remark'=> array('value'=>'点击查看订单详情。','color'=>'#173177'),
		);

		$data = json_encode($datas); //发送的消息模板数据
	}

	if (!empty($template_id)){
		$accountid = pdo_fetch("select * from ".tablename('account_wechats')." where uniacid = ".$_W['uniacid']);
		$appid = $accountid['key'];
		$appSecret = $accountid['secret'];
		$url = $url;
		$sendopenid = $openid;
		$topcolor = "#FF0000";
		tempmsg($template_id, $url, $data, $topcolor, $sendopenid, $appid, $appSecret);
	}
}

//佣金提醒
function sendCommWarm($openid, $commission, $createtime, $url) {
	global $_W;
	$template_id = pdo_fetchcolumn("select sendCommWarm from ".tablename('hc_hunxiao_templatenews')." where weid = ".$_W['uniacid']);
	$template_id = trim($template_id);
	//$template_id = 'dMOXdJZto1kcokj1vdNVAqzk5AwieSXvd4jnvrLXKjA';//消息模板id 微信的模板id
	if (!empty($template_id)) {
		$datas = array(
		   'first'=> array('value'=>'您获得了一笔新的佣金','color'=>'#173177'),
		   'keyword1'=> array('value'=>$commission,'color'=>'#173177'),
		   'keyword2'=> array('value'=>$createtime,'color'=>'#173177'),
		   'remark'=> array('value'=>'请进入店铺查看详情。','color'=>'#173177'),
		);

		$data = json_encode($datas); //发送的消息模板数据
	}

	if (!empty($template_id)){
		$accountid = pdo_fetch("select * from ".tablename('account_wechats')." where uniacid = ".$_W['uniacid']);
		$appid = $accountid['key'];
		$appSecret = $accountid['secret'];
		if(empty($url)){
			$url = '';
		} else {
			$url = $url;
		}
		$sendopenid = $openid;
		$topcolor = "#FF0000";
		tempmsg($template_id, $url, $data, $topcolor, $sendopenid, $appid, $appSecret);
	}
}

//提现审核结果通知
function sendCheckChange($openid, $commission, $applytime, $checktime) {
	global $_W;
	$template_id = pdo_fetchcolumn("select sendCheckChange from ".tablename('hc_hunxiao_templatenews')." where weid = ".$_W['uniacid']);
	$template_id = trim($template_id);
	//$template_id = 'JAZjjZyLKIOzGsD39cdS5noa-HDCfGWxzbQSmTT75oQ';//消息模板id 微信的模板id
	if (!empty($template_id)) {
		$datas = array(
		   'first'=> array('value'=>'您好,您的提现申请已处理','color'=>'#173177'),
		   'keyword1'=> array('value'=>$commission,'color'=>'#173177'),
		   'keyword2'=> array('value'=>'普通提现','color'=>'#173177'),
		   'keyword3'=> array('value'=>$applytime,'color'=>'#173177'),
		   'keyword4'=> array('value'=>'审核通过','color'=>'#173177'),
		   'keyword5'=> array('value'=>$checktime,'color'=>'#173177'),
		   'remark'=> array('value'=>'有任何疑问，请致电客服。','color'=>'#173177'),
		);

		$data = json_encode($datas); //发送的消息模板数据
	}

	if (!empty($template_id)){
		$accountid = pdo_fetch("select * from ".tablename('account_wechats')." where uniacid = ".$_W['uniacid']);
		$appid = $accountid['key'];
		$appSecret = $accountid['secret'];
		if(empty($url)){
			$url = '';
		} else {
			$url = $url;
		}
		$sendopenid = $openid;
		$topcolor = "#FF0000";
		tempmsg($template_id, $url, $data, $topcolor, $sendopenid, $appid, $appSecret);
	}
}

//退款申请通知
function sendApplyMoneyBack($openid, $money, $goodsname, $order) {
	global $_W;
	$template_id = pdo_fetchcolumn("select sendApplyMoneyBack from ".tablename('hc_hunxiao_templatenews')." where weid = ".$_W['uniacid']);
	$template_id = trim($template_id);
	//$template_id = 'G6INboXqA4BvDRhG4cHtv3SF0gFrhDyQXy7ykraNpQQ';//消息模板id 微信的模板id
	if (!empty($template_id)) {
		$datas = array(
		   'first'=> array('value'=>'您已申请退款，等待商家确认退款信息。','color'=>'#173177'),
		   'orderProductPrice'=> array('value'=>$money.'元','color'=>'#173177'),
		   'orderProductName'=> array('value'=>$goodsname,'color'=>'#173177'),
		   'orderName'=> array('value'=>$order,'color'=>'#173177'),
		   'remark'=> array('value'=>'有任何疑问，请致电客服。','color'=>'#173177'),
		);

		$data = json_encode($datas); //发送的消息模板数据
	}

	if (!empty($template_id)){
		$accountid = pdo_fetch("select * from ".tablename('account_wechats')." where uniacid = ".$_W['uniacid']);
		$appid = $accountid['key'];
		$appSecret = $accountid['secret'];
		if(empty($url)){
			$url = '';
		} else {
			$url = $url;
		}
		$sendopenid = $openid;
		$topcolor = "#FF0000";
		tempmsg($template_id, $url, $data, $topcolor, $sendopenid, $appid, $appSecret);
	}
}

//退款通知
function sendMoneyBack($openid, $money, $backtype, $returntime, $goodsname, $ordersn, $refundreason) {
	global $_W;
	$template_id = pdo_fetchcolumn("select sendMoneyBack from ".tablename('hc_hunxiao_templatenews')." where weid = ".$_W['uniacid']);
	$template_id = trim($template_id);
	//$template_id = '5OIMC81bafY4pRn7NLwmld8EAljsr04X5W-kklo4Uog';//消息模板id 微信的模板id
	if (!empty($template_id)) {
		$datas = array(
		   'first'=> array('value'=>'退款申请已核实，请等待商家退款信息。','color'=>'#173177'),
		   'keynote1'=> array('value'=>$money.'元','color'=>'#173177'),
		   'keynote2'=> array('value'=>$backtype,'color'=>'#173177'),
		   'keynote3'=> array('value'=>$returntime,'color'=>'#173177'),
		   'keynote4'=> array('value'=>$goodsname,'color'=>'#173177'),
		   'keynote5'=> array('value'=>$ordersn,'color'=>'#173177'),
		   'keynote6'=> array('value'=>$refundreason,'color'=>'#173177'),
		   'remark'=> array('value'=>'有任何疑问，请致电客服。','color'=>'#173177'),
		);

		$data = json_encode($datas); //发送的消息模板数据
	}

	if (!empty($template_id)){
		$accountid = pdo_fetch("select * from ".tablename('account_wechats')." where uniacid = ".$_W['uniacid']);
		$appid = $accountid['key'];
		$appSecret = $accountid['secret'];
		if(empty($url)){
			$url = '';
		} else {
			$url = $url;
		}
		$sendopenid = $openid;
		$topcolor = "#FF0000";
		tempmsg($template_id, $url, $data, $topcolor, $sendopenid, $appid, $appSecret);
	}
}

function tempmsg($template_id, $url, $data, $topcolor, $sendopenid, $appid, $appSecret){
	load()->func('communication');
	if ($data->expire_time < time()) {
		$url1 = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appSecret."";
		$res = json_decode(httpGet($url1));
		$tokens = $res->access_token;
		if(empty($tokens)){
			return;
		}
		$postarr = '{"touser":"'.$sendopenid.'","template_id":"'.$template_id.'","url":"'.$url.'","topcolor":"'.$topcolor.'","data":'.$data.'}';
		$res = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$tokens,$postarr);
	}
}

function httpGet($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_URL, $url);
	$res = curl_exec($curl);
	curl_close($curl);

	return $res;
}