<?php


require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
require_once dirname(__FILE__)."/../question/SqQuestionService.php";
class SQClassService extends FlashCommonService
{

    public function __construct()
    {
        $this->table_name = "lonaking_super_question_class";
        $this->columns = "id,uniacid,name,code,count,create_time,update_time";
        $this->plugin_name = "lonaking_super_question";
    }


    /**
     * 创建一个分类
     * @param $name
     * @param null $uniacid
     * @return array
     */
    public function createClass($name,$uniacid = null){
        global $_W;
        if(is_null($uniacid)){
            $uniacid = $_W['uniacid'];
        }
        $class = array(
            'uniacid'=> $uniacid,
            'name' => $name,
            'code' => "CLASS_".time().rand(0,9),
            'count' => 0,
            'create_time' => time(),
            'update_time' => time()
        );
        $class = $this->insertData($class);
        return $class;
    }

    /**
     * 给类别增加次数
     * @param $classId
     * @param int $count
     * @throws Exception
     */
    public function addCount($classId, $count=1){
        $class = $this->selectById($classId);
        $this->columnAddCount("count",$count,$classId);
    }

    /**
     * 给类别减少次数
     * @param $classId
     * @param int $count
     * @throws Exception
     */
    public function reduceCount($classId, $count = 1){
        $class = $this->selectById($classId);
        $this->columnReduceCount("count",$count,$classId);
    }

    /**
     * 删除一个类别
     * @param $classId
     * @throws Exception
     */
    public function deleteClassById($classId){
        $this->deleteById($classId);
        $questionService = new SqQuestionService();
        $questionService->updateColumnByWhere("class_id","class_id={$classId}");
    }
}