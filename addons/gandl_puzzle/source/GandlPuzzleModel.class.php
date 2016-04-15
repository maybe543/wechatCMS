<?php

class GandlPuzzleModel extends Model{


	// 自动验证定义
	protected $_validate = array(
		
		// 主题：必须，1~20
		array('topic','require','解密主题不能为空！'), 
		array('topic','1,20','解密主题长度为1~20个字',0,'length'),

		// 主题图片：必须
		array('cover','require','请上传主题图片！'), 

		// 获奖人数：数字
		array('award','require','获奖人数不能为空！'), 
		array('award','is_numeric','获奖人数输入错误,只能是数字',0,'function'),

		// 详细说明：1~20000
		array('detail','require','详细说明不能为空！'), 
		array('detail','0,20000','详细说明长度不能超过20000个字',0,'length'),

		// 开始时间：日期时间
		array('start_time','require','开始时间不能为空！'), 

		// 结束时间：日期时间
		array('end_time','require','结束时间不能为空！'), 	
		
		// 线索
		array('keys','require','线索不能为空！'), 
		array('keys','0,5000','线索长度不能超过5000个字',0,'length'),

		// 提交限制
		array('keys_least','require','提交限制不能为空！'), 
		array('keys_least','is_numeric','提交限制输入错误,只能是数字',0,'function'),

		// 谜底：必须，1~20
		array('truth','require','谜底不能为空！'), 
		array('truth','1,20','谜底长度为1~20个字',0,'length'),
	
	);



	/**
	// 自定义自动完成
	// 时间段转换
	protected function auto_time($d){
		if(empty($d) || count($d)==0){
			return null;
		}else{
			foreach($d as $k=>$v){
				$this->__set($k.'_time',strtotime($v));
			}
		}
		return null;
	}
	**/
	
	// 创建解密
    public function add($data='',$options=array(),$replace=false) {
        if(empty($data)) {
            // 没有传递数据，获取当前数据对象的值
            if(!empty($this->data)) {
                $data           =   $this->data;
                // 重置数据
                $this->data     = array();
            }else{
                $this->error    = '没有数据';
                return false;
            }
        }
		
		// 业务补足
		$data['type']=1;
		$data['status']=1;
		$data['create_time']=time();
		$data['update_time']=time();

		pdo_insert($this->tableName, $data);

        return pdo_insertid();
    }

	// 保存解密
	public function save($data='',$options=array(),$replace=false) {
        if(empty($data)) {
            // 没有传递数据，获取当前数据对象的值
            if(!empty($this->data)) {
                $data           =   $this->data;
                // 重置数据
                $this->data     = array();
            }else{
                $this->error    = '没有数据';
                return false;
            }
        }
		
		// 业务处理
		unset($data['uniacid']); // 不允许修改所属
		unset($data['type']); // 不允许修改类型
		$data['update_time']=time();

		$ret = pdo_update($this->tableName, $data, array('id'=>$data['id']));
        if($ret === false) {
			$this->error    = '数据更新失败';
			return false;
        }

        return true;
    }
}