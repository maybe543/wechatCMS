<?php
defined('IN_IA') or exit('Access Denied');
class Fwei_nearshopModuleSite extends WeModuleSite
{
    public function doMobileList()
    {
        global $_GPC, $_W;
        $weid   = $_W['uniacid'];
        $sql    = 'SELECT * FROM ' . tablename('fwei_nearshop') . " WHERE weid='{$weid}' ORDER BY id ASC";
        $list   = pdo_fetchall($sql);
        $config = $this->module['config'];
        include $this->template('list');
    }
    public function doMobileDetail()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $id   = intval($_GPC['id']);
        $item = pdo_fetch('SELECT * FROM ' . tablename('fwei_nearshop') . " WHERE weid = '{$weid}' AND id='{$id}' LIMIT 1");
        if (empty($item)) {
            message('参数错误');
        }
        $config = $this->module['config'];
        include $this->template('detail');
    }
    public function doWebList()
    {
        load()->func('tpl');
        global $_GPC, $_W;
        $weid   = $_W["uniacid"];
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
        $where  = " AND title like '%{$_GPC['keys']}%'";
        $list   = pdo_fetchall('SELECT * FROM ' . tablename('fwei_nearshop') . ' WHERE weid = ' . $weid . $where . ' ORDER BY id DESC  LIMIT ' . ($pindex - 1) * $psize . ',' . $psize);
        $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fwei_nearshop') . ' WHERE weid = ' . $weid . $where);
        $pager  = pagination($total, $pindex, $psize);
        include $this->template('list');
    }
    public function doWebCreate()
    {
        global $_GPC, $_W;
        $weid = $_W["uniacid"];
        $id   = intval($_GPC['id']);
        if (checksubmit('submit')) {
            $insert_data = array(
                'weid' => $weid,
                'title' => $_GPC['title'],
                'thumb' => $_GPC['thumb'],
                'content' => $_GPC['content'],
                'phone' => $_GPC['phone'],
                'qq' => $_GPC['qq'],
                'province' => $_GPC['dis']['province'],
                'city' => $_GPC['dis']['city'],
                'dist' => $_GPC['dis']['district'],
                'address' => $_GPC['address'],
                'lng' => $_GPC['baidumap']['lng'],
                'lat' => $_GPC['baidumap']['lat'],
                'industry1' => $_GPC['industry']['parent'],
                'industry2' => $_GPC['industry']['child'],
                'createtime' => time(),
                'outlink' => $_GPC['outlink']
            );
            $config      = $this->module['config'];
            $mapkey      = $config['mapkey'];
            if ($mapkey && $insert_data['lng'] && $insert_data['lat']) {
                load()->func('communication');
                $res            = ihttp_get("http://apis.map.qq.com/ws/coord/v1/translate/?type=3&locations={$insert_data['lat']},{$insert_data['lng']}&output=json&key={$mapkey}");
                $res['content'] = json_decode($res['content'], true);
                if ($res['code'] == 200 && $res['content']['status'] == 0) {
                    $insert_data['soso_lng'] = $res['content']['locations'][0]['lng'];
                    $insert_data['soso_lat'] = $res['content']['locations'][0]['lat'];
                }
            }
            if ($id) {
                pdo_update('fwei_nearshop', $insert_data, array(
                    'id' => $id,
                    'weid' => $weid
                ));
            } else {
                pdo_insert('fwei_nearshop', $insert_data);
            }
            message('商家设置成功！', $this->createWebUrl('list'), 'success');
        }
        $sql    = 'SELECT * FROM ' . tablename('fwei_nearshop') . " WHERE `id` = :id AND `weid`= :weid";
        $item   = pdo_fetch($sql, array(
            ':id' => $id,
            ':weid' => $weid
        ));
        $reside = array();
        if ($item) {
            $reside['province'] = $item['province'];
            $reside['city']     = $item['city'];
            $reside['district'] = $item['dist'];
        }
        load()->func('tpl');
        include $this->template('create');
    }
    public function doWebDelete()
    {
        global $_GPC, $_W;
        $weid = $_W["uniacid"];
        $id   = intval($_GPC['id']);
        pdo_delete('fwei_nearshop', array(
            'id' => $id,
            'weid' => $weid
        ));
        message('操作成功！', $this->createWebUrl('list'), 'success');
    }
}