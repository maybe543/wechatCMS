<?php

class poster
{
    private $t_poster;
    private $t_scene_id;

    private $tb_poster;
    private $tb_scene_id;

    public function __construct()
    {
        $this->t_poster = 'jiexi_aaa_poster';
        $this->t_scene_id = 'jiexi_aaa_scene_id';

        $this->tb_poster = tablename($this->t_poster);
        $this->tb_scene_id = tablename($this->t_scene_id);
    }

    public function add_poster($entity)
    {
        return pdo_insert($this->t_poster, $entity);
    }

    public function update_poster($poster_id, $entity)
    {
        return pdo_update($this->t_poster, $entity, array('poster_id' => $poster_id));
    }

    public function get_poster($poster_id)
    {
        global $_W;

        $sql = "select * from " . $this->tb_poster . " where uniacid=:uniacid and poster_id=:poster_id";

        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':poster_id'] = $poster_id;

        $exist = pdo_fetch($sql, $pars);

        if (!empty($exist)) {

            $exist = $this->decode_poster_param($exist, $exist['bgparam']);
        }

        return $exist;
    }

    public function get_poster_by_uniacid()
    {
        global $_W;

        $uniacid = $_W['uniacid'];
        $sql = "select * from " . $this->tb_poster . " where uniacid=:uniacid";

        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];

        $exist = pdo_fetch($sql, $pars);

        if (!empty($exist)) {
            $exist = $this->decode_poster_param($exist, $exist['bgparam']);
        }

        return $exist;
    }

    public function active_poster($poster_id)
    {
        global $_W;

        return pdo_update($this->t_poster, array(
            'active' => 1,
            'createtime' => TIMESTAMP
        ), array(
            'poster_id' => $poster_id,
            'uniacid' => $_W['uniacid']
        ));
    }

    public function update_field($poster_id, $field, $value)
    {
        global $_W;

        return pdo_update($this->t_poster, array(
            $field => $value
        ), array(
            'poster_id' => $poster_id,
            'uniacid' => $_W['uniacid']
        ));
    }

    public function get_next_avaliable_scene_id()
    {
        global $_W;

        $sql = 'SELECT scene_id FROM ' . $this->tb_scene_id . ' WHERE uniacid=:uniacid';

        $pars = array(':uniacid' => $_W['uniacid']);

        $scene_id = pdo_fetchcolumn($sql, $pars);

        if (empty($scene_id)) {
            $scene_id = 1;
            pdo_insert($this->t_scene_id, array('uniacid' => $_W['uniacid'], 'scene_id' => $scene_id));
        } else {
            $scene_id++;
            pdo_update($this->t_scene_id, array('scene_id' => $scene_id), array('uniacid' => $_W['uniacid']));
        }
        return $scene_id;
    }

    public function encode_poster_param($gpc)
    {
        $params = array(
            'qrleft' => intval($gpc['qrleft']),
            'qrtop' => intval($gpc['qrtop']),
            'qrwidth' => intval($gpc['qrwidth']),
            'qrheight' => intval($gpc['qrheight']),
            'avatarleft' => intval($gpc['avatarleft']),
            'avatartop' => intval($gpc['avatartop']),
            'avatarwidth' => intval($gpc['avatarwidth']),
            'avatarheight' => intval($gpc['avatarheight']),
            'avatarenable' => intval($gpc['avatarenable']),
            'nameleft' => intval($gpc['nameleft']),
            'nametop' => intval($gpc['nametop']),
            'namesize' => intval($gpc['namesize']),
            'namecolor' => intval($gpc['namecolor']),
            'nameenable' => intval($gpc['nameenable']),
            'genqr_info1' => $gpc['genqr_info1'],
            'genqr_info2' => $gpc['genqr_info2'],
            'genqr_info3' => $gpc['genqr_info3'],
        );
        return serialize($params);
    }

    public function decode_poster_param($item, $p)
    {
        $gpc = unserialize($p);
        $item['qrleft'] = intval($gpc['qrleft']) ? intval($gpc['qrleft']) : 145;
        $item['qrtop'] = intval($gpc['qrtop']) ? intval($gpc['qrtop']) : 475;
        $item['qrwidth'] = intval($gpc['qrwidth']) ? intval($gpc['qrwidth']) : 240;
        $item['qrheight'] = intval($gpc['qrheight']) ? intval($gpc['qrheight']) : 240;
        $item['avatarleft'] = intval($gpc['avatarleft']) ? intval($gpc['avatarleft']) : 40;
        $item['avatartop'] = intval($gpc['avatartop']) ? intval($gpc['avatartop']) : 40;
        $item['avatarwidth'] = intval($gpc['avatarwidth']) ? intval($gpc['avatarwidth']) : 96;
        $item['avatarheight'] = intval($gpc['avatarheight']) ? intval($gpc['avatarheight']) : 96;
        $item['avatarenable'] = intval($gpc['avatarenable']);
        $item['nameleft'] = intval($gpc['nameleft']) ? intval($gpc['nameleft']) : 150;
        $item['nametop'] = intval($gpc['nametop']) ? intval($gpc['nametop']) : 50;
        $item['namesize'] = intval($gpc['namesize']) ? intval($gpc['namesize']) : 30;
        $item['namecolor'] = $gpc['namecolor'];
        $item['nameenable'] = intval($gpc['nameenable']);
        $item['genqr_info1'] = empty($gpc['genqr_info1']) ? '正在为您生成二维码中，请稍候。大约需要等待6秒钟，稍等哦。。' : $gpc['genqr_info1'];
        $item['genqr_info2'] = empty($gpc['genqr_info2']) ? '头像生成中，马上就好哦。。' : $gpc['genqr_info2'];
        $item['genqr_info3'] = empty($gpc['genqr_info3']) ? '在为您生成二维码中，请稍候。。。' : $gpc['genqr_info3'];
        return $item;
    }
}

?>