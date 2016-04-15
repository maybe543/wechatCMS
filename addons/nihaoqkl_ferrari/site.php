<?php
defined('IN_IA') or exit('Access Denied');
class Nihaoqkl_ferrariModuleSite extends WeModuleSite
{
    public function doMobileIndex()
    {
        global $_GPC, $_W;
        include $this->template('index');
    }
    public function doMobilePic()
    {
        global $_GPC, $_W;
        $name    = $_GPC['name'];
        $ferrari = pdo_fetchall("select * from " . tablename('addons_ferrari') . ' where uniacid = ' . $_W['uniacid']);
        if ($ferrari) {
            for ($i = 0; $i < count($ferrari); $i++) {
                $ferrari[$i]['pic'] = $_W['attachurl'] . $ferrari[$i]['pic'];
            }
        }
        if (!$ferrari) {
            $ferrari = array(
                array(
                    'pic' => MODULE_URL . '/template/mobile/01.jpg'
                ),
                array(
                    'pic' => MODULE_URL . '/template/mobile/02.jpg'
                ),
                array(
                    'pic' => MODULE_URL . '/template/mobile/03.jpg'
                )
            );
        }
        include $this->template('pic');
    }
    public function doMobileTupian()
    {
        global $_GPC;
        $name = $_GPC['name'];
        $this->pic($name);
    }
    public function pic($name)
    {
        $background_pic_path = MODULE_ROOT . '/template/mobile/tupian.png';
        $im                  = imagecreatefrompng($background_pic_path);
        $font                = MODULE_ROOT . '/template/fonts/qianming.ttf';
        $timefont            = MODULE_ROOT . '/template/fonts/shijian.ttf';
        $color               = imagecolorallocate($im, 65, 65, 65);
        imagefttext($im, 24, 0, 176, 910, $color, $font, '本人已确认以上配置');
        if (mb_strlen($name, 'utf-8') == 2) {
            imagefttext($im, 24, 0, 538, 910, $color, $font, $name);
            imagefttext($im, 20, 0, 538, 948, $color, $timefont, date('Y.m.d'));
        } else if (mb_strlen($name, 'utf-8') == 3) {
            imagefttext($im, 24, 0, 510, 910, $color, $font, $name);
            imagefttext($im, 20, 0, 510, 948, $color, $timefont, date('Y.m.d'));
        } else if (mb_strlen($name, 'utf-8') == 4) {
            imagefttext($im, 24, 0, 490, 910, $color, $font, $name);
            imagefttext($im, 20, 0, 490, 948, $color, $timefont, date('Y.m.d'));
        } else if (mb_strlen($name, 'utf-8') > 4) {
            $name = '名字过长';
            imagefttext($im, 24, 0, 490, 910, $color, $font, $name);
            imagefttext($im, 20, 0, 490, 948, $color, $timefont, date('Y.m.d'));
        }
        header('Content-type: image/png');
        $result = imagepng($im);
        imagedestroy($im);
    }
    public function doWebCheck()
    {
        $gd    = function_exists('imagefttext');
        $font1 = file_exists(MODULE_ROOT . '/template/fonts/qianming.ttf');
        $font2 = file_exists(MODULE_ROOT . '/template/fonts/shijian.ttf');
        if ($gd && $font1 && $font2) {
            message('你的环境支持运行[法拉利装逼神器]');
        } else {
            if (!$gd)
                error('你的环境不支持运行，未安装gd库，自行百度 PHP中开启GD库支持 或 联系作者!');
            if (!$font1 || !$font2)
                error('你的环境不支持运行，字体不全, 打开 http://pan.baidu.com/s/1i4fR8TR 下载字体后解压覆盖到 addons/nihaoqkl_ferrari/template/fonts 或 联系作者!');
        }
    }
    public function doWebSet()
    {
        global $_GPC, $_W;
        $op = $_GPC['op'];
        if (!$op || $op == 'list') {
            $pagesize  = 10;
            $pageindex = max(intval($_GPC['page']), 1);
            $where     = ' WHERE uniacid=:uniacid';
            $params    = array(
                ':uniacid' => $_W['uniacid']
            );
            $sql       = 'SELECT COUNT(*) FROM ' . tablename('addons_ferrari') . " {$where}";
            $total     = pdo_fetchcolumn($sql, $params);
            $pager     = pagination($total, $pageindex, $pagesize);
            $sql       = 'SELECT * FROM ' . tablename('addons_ferrari') . " {$where} ORDER BY sort desc LIMIT " . (($pageindex - 1) * $pagesize) . ',' . $pagesize;
            $data      = pdo_fetchall($sql, $params);
        }
        if ($op == 'add') {
            if (checksubmit('submit')) {
                $count = pdo_fetchcolumn("select count(*) from " . tablename('addons_ferrari') . ' where `uniacid`=:uniacid', array(
                    ':uniacid' => $_W['uniacid']
                ));
                if ($count <= 5) {
                    $data = array(
                        'pic' => $_GPC['pic'],
                        'sort' => $_GPC['sort'],
                        'create_time' => TIMESTAMP,
                        'update_time' => TIMESTAMP
                    );
                    unset($data['id']);
                    $data   = array_merge($data, array(
                        'uniacid' => $_W['uniacid']
                    ));
                    $result = pdo_insert('addons_ferrari', $data);
                    !$result ? error('修改出错/或者未修改数据') : message('添加成功', '', 'success');
                } else {
                    error('展示图最多添加5张');
                }
            }
        }
        if ($op == 'modify') {
            $info = pdo_fetch('select * from ' . tablename('addons_ferrari') . ' where `id`=:id', array(
                ':id' => $_GPC['id']
            ));
            if (checksubmit('submit')) {
                if ($info) {
                    $data   = array(
                        'pic' => $_GPC['pic'],
                        'sort' => $_GPC['sort'],
                        'update_time' => TIMESTAMP
                    );
                    $where  = array(
                        'id' => $_GPC['id']
                    );
                    $result = pdo_update('addons_ferrari', $data, $where);
                    !$result ? error('修改出错/或者未修改数据') : message('修改成功', '', 'success');
                } else {
                    error('你修改的展示图不存在哦');
                }
            }
        }
        if ($op == 'del') {
            $info = pdo_fetch('select id from ' . tablename('addons_ferrari') . ' where `id`=:id', array(
                ':id' => $_GPC['id']
            ));
            if ($info) {
                $where  = array(
                    'id' => $_GPC['id']
                );
                $result = pdo_delete('addons_ferrari', $where);
                !$result ? error('删除出错') : message('删除成功', '', 'success');
            } else {
                error('你删除的展示图不存在哦');
            }
        }
        include $this->template('set');
    }
    public function doWebIndex()
    {
        $url = "http://" . $_SERVER['HTTP_HOST'] . '/app' . ltrim($this->createMobileUrl('index'), '.');
        include $this->template('index');
    }
}