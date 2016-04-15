<?php

/**
 * 这个是基础的增删改查类，所有的service都应该继承此类，然后就不需要再写神嚒pdo_insert  pdo_update
 * User: leon
 * Date: 15/9/3
 * Time: 下午9:38
 */
abstract class CommonService
{
    // 表名
    protected $table_name;
    // 字段名字符串r
    protected $columns;

    /**
     * 根据id查询一条数据
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function selectById($id){
        $sql = null;
        $select_param = array(
            ':id' => $id
        );
        $sql = "SELECT ".$this->columns." FROM ".tablename($this->table_name)." WHERE id =:id";
        $result = pdo_fetch($sql,$select_param);
        //若平台无生成二维码的权限
        if(empty($result) || is_null($result)){
            throw new Exception("没有查询到该纪录",401);
        }
        return $result;
    }

    /**
     * 根据id数组来查询数据
     * @param $ids
     * @return array
     * @throws Exception
     */
    public function selectByIds($ids){
        if(!is_array($ids)){
            throw new Exception('查询参数异常',404);
        }
        if(sizeof($ids) <= 0 ){
            throw new Exception('参数为空',404);
        }

        //准备in参数
        $in = "";
        for($i = 0; $i < sizeof($ids); $i++){
            if($i == 0){
                $in = $in ."(".$ids[$i];
            }else{
                if($i == sizeof($ids) -1){
                    $in = $in.",".$ids[$i].")";
                }else{
                    $in = $in.",".$ids[$i];
                }
            }
        }
        $data_list = pdo_fetchall("SELECT ".$this->columns." FROM ".tablename($this->table_name)." WEHRE id in {$in}");
        if(empty($data_list)){
            throw new Exception("没有查到相关数据",404);
        }
        return $data_list;
    }

    /**
     * 查询所有数据
     * @param string $where 查询条件，必须以AND开头 可以为空这个where
     * @return array 返回查询结果
     * @throws Exception 不存在的时候抛出此异常
     */
    public function selectAll($where = ''){
        global $_W;
        $uniacid = $_W['uniacid'];
        $data_list = pdo_fetchall("SELECT ".$this->columns." FROM ".tablename($this->table_name)." WHERE 1=1 AND uniacid={$uniacid} {$where}");
        if(empty($data_list)){

        }
        return $data_list;
    }

    /**
     * 查询一条记录
     * @param string $where
     * @return bool
     * @throws SelectNullException
     */
    public function selectOne($where =''){
        $sql = "SELECT ".$this->columns." FROM ".tablename($this->table_name)." WHERE 1=1 {$where}";
        $result = pdo_fetch($sql);
        //若平台无生成二维码的权限
        if(empty($result) || is_null($result)){
            throw new Exception("没有查询到该纪录",401);
        }
        return $result;
    }
    /**
     * 条件查询 指定排序规则
     * @param string $where
     * @param string $order_to
     * @return array
     * @throws Exception
     */
    public function selectAllOrderBy($where = '', $order_by = ''){
        global $_W;
        $uniacid = $_W['uniacid'];
        $data_list = pdo_fetchall("SELECT ".$this->columns." FROM ".tablename($this->table_name)." WHERE 1=1 AND uniacid={$uniacid} {$where} {$orderby}");
        if(empty($data_list)){
            throw new Exception("没有查到相关数据",405);
        }
        return $data_list;
    }
    /**
     * 根据删除
     * @param $id
     * @throws Exception
     */
    public function deleteById($id){
        try{
            $item = $this->selectById($id);
            pdo_delete($this->table_name, array('id'=> $id));
        }catch (Exception $e){
            throw new Exception("无法删除，因为这条数据不存在",402);
        }
    }

    /**
     * 插入一条数据
     * @param $param
     */
    public function insertData($param){
        pdo_insert($this->table_name,$param);
        $param['id'] = pdo_insertid();
        return $param;
    }

    /**
     * 更新一条数据
     * @param $param
     * @return bool
     * @throws Exception
     */
    public function updateData($param){
        try{
            $id = $param['id'];
            $data = $this->selectById($id);
            pdo_update($this->table_name,$param,array('id'=>$id));
            return $this->selectById($id);
        }catch (Exception $e){
            // 当这个不存在的时候抛出异常
            throw new Exception("更新失败,数据不存在",403);
        }
    }

    /**
     * 更新某个字段
     * @param $column_name 字段名
     * @param $value 值
     * @param $id 要更新的数据id
     * @throws Exception 不存在或者系统异常的时候抛出此异常
     */
    public function updateColumn($column_name,$value,$id){
        //判断字段是否存在
        if(pdo_fieldexists($this->table_name,$column_name)){
            pdo_update($this->table_name,array($column_name=>$value),array('id'=>$id));
        }else{
            throw new Exception("不存在该属性",405);
        }
    }

    /**
     * 给某个int类型的字段 增长值
     * @param $columns_name 字段名
     * @param $add_count 要加的值
     * @param $id 字段id
     * @return bool
     * @throws Exception
     */
    public function columnAddCount($columns_name, $add_count, $id){
        //判断字段是否存在
        if(pdo_fieldexists($this->table_name,$columns_name)){
            try{
                $data = $this->selectById($id);
                $data[$columns_name] = $data[$columns_name] + $add_count;
                $new_data = $this->updateData($data);
                return $new_data;
            }catch (Exception $e){
                throw new Exception($e->getMessage(),$e->getCode());
            }
        }else{
            throw new Exception("不存在该属性",405);
        }
    }

    public function columnReduceCount($column_name, $reduce_count, $id){
        //判断字段是否存在
        if(pdo_fieldexists($this->table_name,$column_name)){
            $data = $this->selectById($id);
            if(empty($data)){
                throw new Exception("更新失败,数据不存在",403);
            }
            $data[$column_name] = $data[$column_name] - $reduce_count;
            $new_data = $this->updateData($data);
            return $new_data;
        }else{
            throw new Exception("表不存在[".$column_name."]属性",405);
        }
    }
    /**
     * 更新或者插入一条数据 当传入的参数中存在id的话则为更新 如果没有id则为插入
     * @param $param
     * @throws Exception
     */
    public function insertOrUpdate($param){
        if($param['id']){
            return $this->updateData($param);
        }else{
            return $this->insertData($param);
        }
    }

    /**
     * 日志方法
     * @param $content
     */
    public function log($content){
        load()->func('logging');
        logging_run($content,'normal','lonaking_taskcenter',true);
    }
}