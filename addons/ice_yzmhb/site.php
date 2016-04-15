<?php
/**
 * 验证码红包模块微站定义
 *
 * @author 宙斯
 * @url #
 */
defined('IN_IA') or exit('Access Denied');

class Ice_yzmhbModuleSite extends WeModuleSite {
	
	
	public  function  doMobileIndex(){
		
		require 'inc/mobile/index.inc.php';
	}

	public function doWebSendlist() {
		require 'inc/web/sendlist.inc.php';
	}
	public function doWebCodeset() {
		//这个操作被定义用来呈现 规则列表
		require 'inc/web/codeset.inc.php';
	}
	
	
	public function doWebSend() {
		//这个操作被定义用来呈现 规则列表
		require 'inc/web/send.inc.php';
	}
	
	public function doWebInfoset() {
		//这个操作被定义用来呈现 规则列表
		require 'inc/web/infoset.inc.php';
	}
	public function doWebRule() {
		//这个操作被定义用来呈现 规则列表
		require 'inc/web/rule.inc.php';
	}
	public function doWebManage() {
		//这个操作被定义用来呈现 管理中心导航菜单
		require 'inc/web/setting.inc.php';
	}

	private function getcode($num,$no) {
		//生成兑换码  $num 数量   $no 批次
		$time =time('Ymd');
	}
	
	
	public function doWebExport(){
		global $_W,$_GPC;
		$acid = intval($_W['account']['uniacid']);
	
		// 		$acc = WeAccount::create($acid);
		//$fan = $acc->fansQueryInfo($openid, true);
		//$fan['nickname']
		require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
	
	
	
		$result = array();
		$piciid = $_GPC['piciid'];
		$condition  = "";
		$condition .= " and c.piciid = ".$piciid;
		$condition .= " and c.yzmhbid = 0 ";
	
	
		// 		if (!empty($_GPC['status'])) {
		// 			$cid = intval($_GPC['status']);
		// 			$cid = $cid == '1' ? '1' : '0';
		// 			$condition .= " AND status = '{$cid}'";
		// 		}
		$type = pdo_fetchcolumn("select type from ".tablename("ice_yzmhb_codenum")." where id = :id",array(":id"=>$piciid));
		
		if($type == 1 || $type == 2){
			$list  = pdo_fetchall("select code,status from ".tablename("ice_yzmhb_code")." where uniacid = :uniacid and piciid = :piciid",array(":uniacid"=>$_W['uniacid'],':piciid'=>$piciid));
			foreach ($list as $k => $v) {
				$status = $v['status'];
				if($status == '1'){
					$list[$k]['status1'] = "未使用";
				}else if($status == '2'){
					$list[$k]['status1'] = "已使用";
				}
			}
		
		}elseif($type == 3){
			$list = pdo_fetchall("SELECT c.code as code,b.status as status  FROM " . tablename('ice_yzmhb_code') . " c left join ".tablename("ice_guesshb")." b on c.id = b.codeid  WHERE c.uniacid = '{$_W['uniacid']}'  $condition ORDER BY c.time desc  ");
			//$total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('ice_act_enroll') .  " WHERE uniacid = '{$_W['uniacid']}'  $condition");
			
			foreach ($list as $k => $v) {
				$status = $v['status'];
				if(empty($status)){
					$list[$k]['status1'] = "未使用";
				}else if($status == '1'){
					$list[$k]['status1'] = "正在猜测中";
				}else if($status == '2'){
					$list[$k]['status1'] = "已使用";
				}
			}
			
		}elseif($type == 4){
		
		
		$list = pdo_fetchall("SELECT c.code as code,b.status as status  FROM " . tablename('ice_yzmhb_code') . " c left join ".tablename("ice_robhb")." b on c.id = b.codeid  WHERE c.uniacid = '{$_W['uniacid']}'  $condition ORDER BY c.time desc  ");
		//$total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('ice_act_enroll') .  " WHERE uniacid = '{$_W['uniacid']}'  $condition");
	
		foreach ($list as $k => $v) {
			$status = $v['status'];
			if(empty($status)){
				$list[$k]['status1'] = "未使用";
			}else if($status == '1'){
				$list[$k]['status1'] = "正在抢夺中";
			}else if($status == '2'){
				$list[$k]['status1'] = "已使用";
			}
		}
	
		}
		// 		$result['params']= $params;
		// 		$result['pager'] = $pager;
		// 		$result['list'] = $list;
		// 		$result['sAid'] = $sAid;
	
	
	
		$objPHPExcel=new PHPExcel();
		$objPHPExcel->getProperties()->setCreator('http://www.icetime.cn')
		->setLastModifiedBy('http://www.icetime.cn')
		->setTitle('Office 2007 XLSX Document')
		->setSubject('Office 2007 XLSX Document')
		->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Result file');
		$objPHPExcel->setActiveSheetIndex(0);
		//设置sheet的name
		$objPHPExcel->getActiveSheet()->setTitle('验证码');
		$objPHPExcel->getActiveSheet()->setCellValue('A1','批次');
		$objPHPExcel->getActiveSheet()->setCellValue('B1','验证码');
		$objPHPExcel->getActiveSheet()->setCellValue('C1','状况');
	
		// 		->setCellValue('D1','红包积分')
		// 		->setCellValue('E1','扫码时间')
		// 		->setCellValue('F1','是否发放');
		$i=2;
		foreach($list as $k=>$v){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$piciid);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i,$v['code']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i,$v['status1']);
	
			//->setCellValueExplicit('D'.$i,$v['phone'],PHPExcel_Cell_DataType::TYPE_STRING)
			// 			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i,date('Y-m-d H:i:s',$v['usetime']));
			// 			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i,$v['status'] == '1' ? '已发放' : '未发放');
			$i++;
		}
	
		// 		$objPHPExcel->getActiveSheet()->setTitle('二维码使用记录表');
		// 		$objPHPExcel->setActiveSheetIndex(0);
		//$filename=urlencode('学生信息统计表').'_'.date('Y-m-dHis');
	
		$filename='验证码数据'.'_'.date('Y-m-d');
	
		//*生成xlsx文件
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
	
	
	
		//*生成xls文件
		// header('Content-Type: application/vnd.ms-excel');
		// header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		// header('Cache-Control: max-age=0');
		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	
	
		$objWriter->save('php://output');
		exit;
	}
	
	
	public function doWebImport() {
		//这个操作被定义用来呈现 管理中心导航菜单 导入试题
		global $_W, $_GPC;
		load() ->func('logging');
		$piciid = $_GPC['piciid'];
		$type = pdo_fetchcolumn("select type from ".tablename("ice_yzmhb_codenum")." where id = :id",array(":id"=>$piciid));
		if(!empty($_GPC['foo']))
		{
			try
			{
				include_once("reader.php");
				$tmp = $_FILES['file']['tmp_name'];
				if (empty ($tmp)) {
					echo '请选择要导入的Excel文件！';
					exit;
				}
				// 				$save_path = "xls/";
				// 				$file_name = "code.xls";
				$file_name = IA_ROOT."/addons/ice_yzmhb/xls/code.xls";
				$uniacid = $_W['uniacid'];
				$type = pdo_fetchcolumn("select type from ".tablename("ice_yzmhb_codenum")." where id = :id",array(":id"=>$piciid));
				if (copy($tmp, $file_name)) {
					$xls = new Spreadsheet_Excel_Reader();
					$xls->setOutputEncoding('utf-8');
					$xls->read($file_name);
					$data_values = "";
					$count = $xls->sheets[0]['numRows'];
					for ($i=1; $i<=$count; $i++) {
						$code = $xls->sheets[0]['cells'][$i][1];
						$time = time();
						$data_values .= "('$uniacid','$code',0,'$piciid','$type','$time','1'),";
					}
					$data_values = substr($data_values,0,-1); //去掉最后一个逗号
					$query = pdo_query("insert into `ims_ice_yzmhb_code`(uniacid,code,yzmhbid,piciid,type,time,status) values $data_values",array());//批量插入数据表中
					if($query){
						pdo_query("update ".tablename("ice_yzmhb_codenum")." set count = count + $count where id = :id and uniacid =:uniacid",array(":id"=>$piciid,":uniacid"=>$uniacid));
						$url = $this->createWebUrl('codeset');
						echo "<script>alert('导入成功！')</script>";
						echo "<script>window.location.href= '$url'</script>";
					}else{
					$url = $this->createWebUrl('Import', array());
					echo "<script>alert('导入失败！')</script>";
					echo "<script>window.location.href= '$url'</script>";
					}
			}else{
			echo '复制失败！';
				exit;
			}
			}
			catch(Exception $e)
			{
			logging_run($e,'','upload_tiku');
			}
			}
			else
			{
			include $this->template('import');
			}
			}
			
			
			public function doWebShowcode(){
			global $_W,$_GPC;
	
			$result = array();
			$piciid = $_GPC['piciid'];
			$condition  = " ";
			$condition .= " and c.piciid = ".$piciid;
			$condition .= " and c.yzmhbid = 0 ";
	
			$type = pdo_fetchcolumn("select type from ".tablename("ice_yzmhb_codenum")." where id = :id",array(":id"=>$piciid));
		
			
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			
		if($type == 1 || $type == 2){
			$list  = pdo_fetchall("select code,status from ".tablename("ice_yzmhb_code")." where uniacid = :uniacid and piciid = :piciid LIMIT " . ($pindex - 1) * $psize . ',' . $psize ,array(":uniacid"=>$_W['uniacid'],':piciid'=>$piciid));
			
			$total = pdo_fetchcolumn("select count(*) from ".tablename("ice_yzmhb_code")." where uniacid = :uniacid and piciid = :piciid " ,array(":uniacid"=>$_W['uniacid'],':piciid'=>$piciid));
			
			foreach ($list as $k => $v) {
				$status = $v['status'];
				if($status == '1'){
					$list[$k]['status1'] = "未使用";
				}else if($status == '2'){
					$list[$k]['status1'] = "已使用";
				}
			}
		
		}elseif($type == 3){
			$list = pdo_fetchall("SELECT c.code as code,b.status as status  FROM " . tablename('ice_yzmhb_code') . " c left join ".tablename("ice_guesshb")." b on c.id = b.codeid  WHERE c.uniacid = '{$_W['uniacid']}'  $condition ORDER BY c.time desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize );
			$total = pdo_fetchcolumn("SELECT count(*)  FROM " . tablename('ice_yzmhb_code') . " c left join ".tablename("ice_guesshb")." b on c.id = b.codeid  WHERE c.uniacid = '{$_W['uniacid']}'  $condition " );
			
			foreach ($list as $k => $v) {
				$status = $v['status'];
				if(empty($status)){
					$list[$k]['status1'] = "未使用";
				}else if($status == '1'){
					$list[$k]['status1'] = "正在猜测中";
				}else if($status == '2'){
					$list[$k]['status1'] = "已使用";
				}
			}
			
		}elseif($type == 4){
		$list = pdo_fetchall("SELECT c.code as code,b.status as status  FROM " . tablename('ice_yzmhb_code') . " c left join ".tablename("ice_robhb")." b on c.id = b.codeid  WHERE c.uniacid = '{$_W['uniacid']}'  $condition ORDER BY c.time desc  LIMIT " . ($pindex - 1) * $psize . ',' . $psize );
		$total = pdo_fetchcolumn("SELECT count(*)  FROM " . tablename('ice_yzmhb_code') . " c left join ".tablename("ice_robhb")." b on c.id = b.codeid  WHERE c.uniacid = '{$_W['uniacid']}'  $condition " );
		foreach ($list as $k => $v) {
			$status = $v['status'];
			if(empty($status)){
				$list[$k]['status1'] = "未使用";
			}else if($status == '1'){
				$list[$k]['status1'] = "正在抢夺中";
			}else if($status == '2'){
				$list[$k]['status1'] = "已使用";
			}
		}
	
		}
		
		
		$pager = pagination($total, $pindex, $psize);
		
		include $this->template("showcode");
		
		
	}
	

}