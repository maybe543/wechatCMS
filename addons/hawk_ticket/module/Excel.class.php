<?php
class Excel{
 public function __construct() {
      //导入phpExcel核心类 
     require_once(HK_ROOT .'./../../framework/library/phpexcel/PHPExcel.php');
 }
/**
* 读取excel $filename 路径文件名 $encode 返回数据的编码 默认为utf8
*以下基本都不要修改
*/
 public function read($filename,$encode='utf-8'){
	$inputFileType = 'Excel2007';
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objPHPExcel = $objReader->load($filename);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	return $sheetData;
 }
 /*
  * excel文件导出
  * param array $file array('title'=>'记录表','编号','OPENID','昵称') 表格式设置
  * param array $data array(0=>array('id'=>1,'name'=>'kk')) 必须和上面列保持一至sql数组
  * param array $elem 过滤要导出的字符串 array('id','openid','nickname')默认为空不过滤
 */
 public function down($file,$data,$elem=array()){
 	$flag = true;
 	if(!is_array($file) || !is_array($data)){
		$flag = false;
	}
	
	if(!$flag){
		return false;
	}
	$sfile = $file;
 	if($file['title']){
		unset($file['title']);
		$nfile = $file;
	}else{
		$nfile = $file;
		$sfile['title'] = 'system';
	}
 	//创建一个excel
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	//设置sheet的name
	$objPHPExcel->getActiveSheet()->setTitle($sfile['title']);
	//设置单元格的值
	$j=ord('A');
	foreach($nfile as $k=>$v){
		$objPHPExcel->getActiveSheet()->setCellValue(chr($j).'1', $v);
		$j++;
	}
	$i=2;
	foreach($data as &$v){
		$j=ord('A');		
		foreach($v as $dk=>&$dv){
			if(!in_array($dk,$elem) && !empty($elem)){
				continue;
			}
			 $tag = strval(chr($j).$i);
			 $objPHPExcel->getActiveSheet()->setCellValue($tag, $dv);
			 $j++;
		}
		$i++;
	}
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$sfile['title'].'.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;	
 }

	public  function checkdata($exceldata){
		$exceldata[1]='';
		$exceldata = array_filter($exceldata);
		foreach($exceldata as $k=>$v){
			$identify[] = $v['D'];
		}
		if(count($identify)< 1) {
			return error(-1,'导入数据为空');
		}
		foreach($identify as $k=>$v){
			if(empty($v)){
				return error('-1','identify不能为空');
			}
		}
		if(count($identify) != count(array_unique($identify))){
			return error(-2,'导入identify数据不唯一');
		}
		return $exceldata;
	}
 
}
?>