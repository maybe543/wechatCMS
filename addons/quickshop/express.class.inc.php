<?php
 class Express{
    private static $t_express = 'quickshop_express';
    public function create($data){
        $id = -1;
        $ret = pdo_insert(self :: $t_express, $data);
        if (false !== $ret){
            $id = pdo_insertid();
        }
        return $id;
    }
    public function update($weid, $id, $data){
        $ret = pdo_update(self :: $t_express, $data, array('weid' => $weid, 'id' => $id));
        return $ret;
    }
    public function get($id){
        $express = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_express) . ' WHERE id=:id', array(':id' => $id));
        return $express;
    }
    public function batchGet($weid, $conds = array(), $key = null){
        $condition = '';
        $expresses = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_express) . " WHERE weid = $weid  $condition ORDER BY displayorder DESC", array(), $key);
        return $expresses;
    }
    public function remove($weid, $id){
        return pdo_query("DELETE FROM " . tablename(self :: $t_express) . " WHERE id=:id AND weid=:weid", array(':weid' => $weid, ':id' => $id));
    }
    public function getExpressName($code){
        $codeMap = array("shunfeng" => "顺丰", "shentong" => "申通", "yunda" => "韵达快运", "tiantian" => "天天快递", "yuantong" => "圆通速递", "zhongtong" => "中通速递", "ems" => "ems快递", "huitongkuaidi" => "汇通快运", "quanfengkuaidi" => "全峰快递", "zhaijisong" => "宅急送", "aae" => "aae全球专递", "anjie" => "安捷快递", "anxindakuaixi" => "安信达快递", "biaojikuaidi" => "彪记快递", "bht" => "bht", "baifudongfang" => "百福东方国际物流", "coe" => "中国东方（COE）", "changyuwuliu" => "长宇物流", "datianwuliu" => "大田物流", "debangwuliu" => "德邦物流", "dhl" => "dhl", "dpex" => "dpex", "dsukuaidi" => "d速快递", "disifang" => "递四方", "fedex" => "fedex（国外）", "feikangda" => "飞康达物流", "fenghuangkuaidi" => "凤凰快递", "feikuaida" => "飞快达", "guotongkuaidi" => "国通快递", "ganzhongnengda" => "港中能达物流", "guangdongyouzhengwuliu" => "广东邮政物流", "gongsuda" => "共速达", "hengluwuliu" => "恒路物流", "huaxialongwuliu" => "华夏龙物流", "haihongwangsong" => "海红", "haiwaihuanqiu" => "海外环球", "jiayiwuliu" => "佳怡物流", "jinguangsudikuaijian" => "京广速递", "jixianda" => "急先达", "jjwl" => "佳吉物流", "jymwl" => "加运美物流", "jindawuliu" => "金大物流", "jialidatong" => "嘉里大通", "jykd" => "晋越快递", "kuaijiesudi" => "快捷速递", "lianb" => "联邦快递（国内）", "lianhaowuliu" => "联昊通物流", "longbanwuliu" => "龙邦物流", "lijisong" => "立即送",);
        return $codeMap[$code];
    }
}
