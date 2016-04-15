<?php

class GandlPuzzleUserModel extends Model{
	/**
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
	**/
}