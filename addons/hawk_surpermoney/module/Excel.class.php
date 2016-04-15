<?php
class Excel{
    public function __construct() {
        //导入phpExcel核心类
        require_once(HK_ROOT .'./../../framework/library/phpexcel/PHPExcel.php');
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

}
?>