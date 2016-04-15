<?php
/**
 * 【超人】关键字抽奖模块微站定义
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
require IA_ROOT.'/addons/superman_lottery/common.func.php';
require IA_ROOT.'/addons/superman_lottery/model.func.php';
class Superman_lotteryModuleSite extends WeModuleSite {

	public function doWebWinner() {
        global $_W, $_GPC;
        load()->model('mc');
        $title = '中奖名单';
        $eid = intval($_GPC['eid']);
        $act = !empty($_GPC['act']) ? $_GPC['act'] : 'display';
        if ($act == 'display') {
            $rules = pdo_fetchall("SELECT * FROM ".tablename('rule')." WHERE uniacid='{$_W['uniacid']}' AND module = 'superman_lottery' ");
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 25;
            $start = ($pindex - 1) * $pagesize;
            $condition = ' WHERE a.uid=b.uid AND a.uniacid=:uniacid';
            $params = array(
                ':uniacid' => $_W['uniacid'],
            );
            $rid = $_GPC['rid'];
            if ($rid > 0) {
                $condition .= " AND a.rid=:rid";
                $params[':rid'] = $rid;
            }
            $status = $_GPC['status'];
            if ($status > -2) {
                $condition .= " AND a.status=:status";
                $params[':status'] = $status;
            }
            if (isset($_GPC['keyword']) && $_GPC['keyword'] != '') {
                if (is_numeric($_GPC['keyword'])) {
                    $condition .= " AND a.uid=".intval($_GPC['keyword']);
                } else {
                    $condition .= " AND b.nickname LIKE '%{$_GPC['keyword']}%'";
                }
            }
            $sql = 'SELECT COUNT(*) FROM '.tablename('superman_lottery_log').' AS a, '.tablename('mc_members').' AS b'.$condition;
            $total = pdo_fetchcolumn($sql, $params);
            if ($total > 0) {
                $sql = 'SELECT a.*,b.realname,b.nickname,b.mobile,b.avatar,b.address FROM '.tablename('superman_lottery_log').' AS a, '.tablename('mc_members').' AS b'.$condition." ORDER BY id DESC LIMIT $start,$pagesize";
                $list = pdo_fetchall($sql, $params);
                if ($list) {
                    $prize_ids = array();
                    foreach ($list as &$item) {
                        if (empty($item['prize'])) {
                            continue;
                        }
                        $prize_ids = array_merge($prize_ids, explode(',', $item['prize']));
                    }
					if ($prize_ids) {
						$prize_ids = array_unique($prize_ids);
						$prize_ids = implode(',', $prize_ids);
						$sql = "SELECT id,title,name FROM ".tablename('superman_lottery_prize')." WHERE id IN({$prize_ids})";
						$all_prize = pdo_fetchall($sql, array(), 'id');
						//print_r($all_prize);
						foreach ($list as &$item) {
							if (!empty($item['prize'])) {
								$arr = explode(',', $item['prize']);
								$prizes = $_prizes = array();
								foreach ($arr as $id) {
									$prizes[] = '<div style="margin:4px 0;"><button class="btn btn-info" type="button">'.$all_prize[$id]['title'].'<span class="badge">'.$all_prize[$id]['name'].'</span></button></div>';
                                    $_prizes[] = $all_prize[$id]['title'].' '.$all_prize[$id]['name'];
								}
								$item['prize'] = $prizes?implode('', $prizes):'';
								$item['_prize'] = $_prizes?implode("\n", $_prizes):'';
							}
							unset($item);
						}
					}
                    $pager = pagination($total, $pindex, $pagesize);
                }
            }
            $play_member_total = $play_total = 0;
            $sql = "SELECT COUNT(DISTINCT(uid)) FROM ".tablename('superman_lottery_log');
            $condition = ' WHERE uniacid=:uniacid';
            $params = array(
                ':uniacid' => $_W['uniacid'],
            );
            if ($rid > 0) {
                $condition .= " AND rid=:rid";
                $params[':rid'] = $rid;
            }
            $total = pdo_fetchcolumn($sql, $params);
            $play_member_total = $total?$total:0;
            $sql = "SELECT SUM(total) FROM ".tablename('superman_lottery_log').$condition;
            $total = pdo_fetchcolumn($sql, $params);
            $play_total = $total?$total:0;
        } else if ($act == 'setstatus') {
            if (!empty($_GPC['id'])) {
                $id = intval($_GPC['id']);
                $uid = intval($_GPC['uid']);
                $member = mc_fetch($uid, array('mobile', 'realname'));
                if ($member['mobile'] == '') {
                    message('未填写手机号，无法领奖！', referer(), 'error');
                }
                if ($member['realname'] == '') {
                    message('未填写姓名，无法领奖！', referer(), 'error');
                }
                pdo_update('superman_lottery_log', array('status' => intval($_GPC['status'])), array('id' => $id));
                message('操作成功！', $this->createWebUrl('winner', array('name' => 'superman_lottery', 'page' => $_GPC['page'])));
            }
        } else if ($act == 'delete') {
            if (!empty($_GPC['id'])) {
                $id = intval($_GPC['id']);
                pdo_delete('superman_lottery_log', array('id' => $id));
                message('操作成功！', $this->createWebUrl('winner', array('name' => 'superman_lottery', 'page' => $_GPC['page'])));
            }
        } else if ($act == 'export') {
            $condition = ' WHERE a.uid=b.uid AND a.uniacid=:uniacid';
            $params = array(
                ':uniacid' => $_W['uniacid'],
            );
            $rid = $_GPC['rid'];
            if ($rid > 0) {
                $condition .= " AND a.rid=:rid";
                $params[':rid'] = $rid;
            }
            $status = $_GPC['status'];
            if ($status > -2) {
                $condition .= " AND a.status=:status";
                $params[':status'] = $status;
            }
            if (isset($_GPC['keyword']) && $_GPC['keyword'] != '') {
                if (is_numeric($_GPC['keyword'])) {
                    $condition .= " AND a.uid=".intval($_GPC['keyword']);
                } else {
                    $condition .= " AND b.nickname LIKE '%{$_GPC['keyword']}%'";
                }
            }
            $sql = 'SELECT a.*,b.realname,b.nickname,b.mobile,b.avatar,b.address FROM '.tablename('superman_lottery_log').' AS a, '.tablename('mc_members').' AS b'.$condition." ORDER BY id DESC";
            $list = pdo_fetchall($sql, $params);
            if ($list) {
                $prize_ids = array();
                foreach ($list as &$item) {
                    if (empty($item['prize'])) {
                        continue;
                    }
                    $prize_ids = array_merge($prize_ids, explode(',', $item['prize']));
                }
                if ($prize_ids) {
                    $prize_ids = array_unique($prize_ids);
                    $prize_ids = implode(',', $prize_ids);
                    $sql = "SELECT id,title,name FROM ".tablename('superman_lottery_prize')." WHERE id IN({$prize_ids})";
                    $all_prize = pdo_fetchall($sql, array(), 'id');
                    //print_r($all_prize);
                    foreach ($list as &$item) {
                        if (!empty($item['prize'])) {
                            $arr = explode(',', $item['prize']);
                            $prizes = $_prizes = array();
                            foreach ($arr as $id) {
                                $prizes[] = '<div style="margin:4px 0;"><button class="btn btn-info" type="button">'.$all_prize[$id]['title'].'<span class="badge">'.$all_prize[$id]['name'].'</span></button></div>';
                                $_prizes[] = $all_prize[$id]['title'].' '.$all_prize[$id]['name'];
                            }
                            $item['prize'] = $prizes?implode('', $prizes):'';
                            $item['_prize'] = $_prizes?implode(',', $_prizes):'';
                        }
                        unset($item);
                    }
                }
                //print_r($list);

                $play_member_total = $play_total = 0;
                $sql = "SELECT COUNT(DISTINCT(uid)) FROM ".tablename('superman_lottery_log');
                $condition = ' WHERE uniacid=:uniacid';
                $params = array(
                    ':uniacid' => $_W['uniacid'],
                );
                if ($rid > 0) {
                    $condition .= " AND rid=:rid";
                    $params[':rid'] = $rid;
                }
                $total = pdo_fetchcolumn($sql, $params);
                $play_member_total = $total?$total:0;
                $sql = "SELECT SUM(total) FROM ".tablename('superman_lottery_log').$condition;
                $total = pdo_fetchcolumn($sql, $params);
                $play_total = $total?$total:0;

                require_once IA_ROOT.'/framework/library/phpexcel/PHPExcel.php';
                require_once IA_ROOT.'/framework/library/phpexcel/PHPExcel/IOFactory.php';
                require_once IA_ROOT.'/framework/library/phpexcel/PHPExcel/Writer/Excel5.php';
                $resultPHPExcel = new PHPExcel();
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                            'style' => PHPExcel_Style_Border::BORDER_THIN,//细边框
                            //'color' => array('argb' => 'FFFF0000'),
                        ),
                    ),
                );
                $style_fill = array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('argb' => '0xFFFF00')
                    ),
                );
                $resultPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray(($styleArray+$style_fill));
                $resultPHPExcel->getActiveSheet()->setCellValue('A1', 'UID');
                $resultPHPExcel->getActiveSheet()->setCellValue('B1', '会员');
                $resultPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
                $resultPHPExcel->getActiveSheet()->setCellValue('D1', '电话');
                $resultPHPExcel->getActiveSheet()->setCellValue('E1', '地址');
                $resultPHPExcel->getActiveSheet()->setCellValue('F1', '奖品');
                $resultPHPExcel->getActiveSheet()->setCellValue('G1', '抽奖总次数');
                $resultPHPExcel->getActiveSheet()->setCellValue('H1', '领奖状态');
                $resultPHPExcel->getActiveSheet()->setCellValue('I1', '抽奖时间');
                $resultPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $resultPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $resultPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $i = 2;
                foreach($list as $item){
                    $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, $item['uid']);
                    $resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item['nickname']);
                    $resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item['realname']);
                    $resultPHPExcel->getActiveSheet()->setCellValue('D' . $i, $item['mobile']);
                    $resultPHPExcel->getActiveSheet()->setCellValue('E' . $i, $item['address']);
                    $resultPHPExcel->getActiveSheet()->setCellValue('F' . $i, $item['_prize']);
                    $resultPHPExcel->getActiveSheet()->setCellValue('G' . $i, $item['total']);
                    $status_title = '';
                    if ($item['status'] == 1) {
                        $status_title = '已领奖';
                    } else if ($item['status'] == 0) {
                        $status_title = '已中奖';
                    } else {
                        $status_title = '未中奖';
                    }
                    $resultPHPExcel->getActiveSheet()->setCellValue('H' . $i, $status_title);
                    $resultPHPExcel->getActiveSheet()->setCellValue('I' . $i, date('Y-m-d H:i:s', $item['dateline']));
                    $resultPHPExcel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->applyFromArray($styleArray);
                    $i++;
                }
                $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, '总人数：'.count($list).'人');
                $resultPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray(array('font' => array('bold' => true)));

                $outputFileName = 'data'.date('Ymd').'.xls';
                $xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header('Content-Disposition:inline;filename="'.$outputFileName.'"');
                header("Content-Transfer-Encoding: binary");
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Pragma: no-cache");
                $xlsWriter->save( "php://output" );
                exit;
            }
            message('导出数据为空', referer(), 'warning');
        }

        include $this->template('winner');
	}

    public function doWebPrize() {
        global $_W, $_GPC;
        $act = $_GPC['act']?$_GPC['act']:'';
        if ($act == 'create') {
            $data = array(
                'rid' => intval($_GPC['rid']),
                'join_play' => 1,
            );
            pdo_insert('superman_lottery_prize', $data);
            $new_id = pdo_insertid();
            include $this->template('prize');
            exit;
        } else if ($act == 'delete') {
            $id = intval($_GPC['id']);
            if ($id > 0) {
                pdo_delete('superman_lottery_prize', array('id' => $id));
            }
            echo 'success';
        } else {
            message('非法请求！', referer(), 'error');
        }
    }

    public function doWebRule() {
        global $_W, $_GPC;
        if(isset($_GPC['set_join_play']) && in_array($_GPC['value'], array(0, 1))
            && isset($_GPC['id'])){
            pdo_update('superman_lottery_prize', array('join_play' => intval($_GPC['value'])), array('id' => intval($_GPC['id'])));
            echo 'success';
            exit;
        }
    }

    public function doMobileLottery() {
        global $_W, $_GPC, $config;
        load()->model('mc');
        $uid = intval($_GPC['uid']);
        $rid = intval($_GPC['rid']);
        $key = trim($_GPC['key']);
        $_t = trim($_GPC['_t']);
        $param = array(
            'uid' => $uid,
            'rid' => $rid,
            '_t' => $_t,
        );
        $new_key = superman_sign_key($param, $config['setting']['authkey']);
        if ($key != $new_key) {
            message('非法请求', referer(), 'error');
        }
        $member = mc_fetch($uid, array('realname', 'mobile', 'address'));
        if (checksubmit('submit')) {
            $realname = trim($_GPC['realname']);
            $mobile = trim($_GPC['mobile']);
            $address = trim($_GPC['address']);
            if (!preg_match("/^1[34578]\d{9}$/", $mobile)) {
                message('手机号格式不正确，请重新填写', referer(), 'error');
            }
            mc_update($uid, array(
                'realname' => $realname,
                'mobile' => $mobile,
                'address' => $address,
            ));
            message('提交成功，请准时领奖！', '', 'success');
        }

        include $this->template('lottery');
    }

    public function doMobileWinner() {
        global $_W, $_GPC;
        $title = '中奖名单';
        $rid = intval($_GPC['rid']);
        if ($rid <= 0) {
            message('非法参数！', referer(), 'error');
        }
        $pindex = max(1, intval($_GPC['page']));
        $pagesize = 10;
        $start = ($pindex - 1) * $pagesize;
        $params = array(
            ':rid' => $rid,
        );
        $sql = "SELECT COUNT(*) FROM ".tablename('superman_lottery_log')." AS a,".tablename('mc_members')." AS b WHERE a.rid=:rid AND a.status>=0 AND a.prize!='' AND a.uid=b.uid";
        $total = pdo_fetchcolumn($sql, $params);
        if ($total) {
            $sql = "SELECT a.*,b.nickname,b.avatar FROM ".tablename('superman_lottery_log')." AS a,".tablename('mc_members')." AS b WHERE a.rid=:rid AND a.status>=0 AND a.prize!='' AND a.uid=b.uid LIMIT $start,$pagesize";
            $list = pdo_fetchall($sql, $params);
            if ($list) {
                $prize_ids = array();
                foreach ($list as &$item) {
                    if (empty($item['prize'])) {
                        continue;
                    }
                    $prize_ids = array_merge($prize_ids, explode(',', $item['prize']));
                }
                if ($prize_ids) {
                    $prize_ids = array_unique($prize_ids);
                    $prize_ids = implode(',', $prize_ids);
                    $sql = "SELECT id,title,name FROM ".tablename('superman_lottery_prize')." WHERE id IN({$prize_ids})";
                    $all_prize = pdo_fetchall($sql, array(), 'id');
                }
                foreach ($list as &$item) {
                    $item['nickname'] = cutstr($item['nickname'], 1).'**';

                    //查询奖品
                    $arr = explode(',', $item['prize']);
                    $prizes = array();
                    foreach ($arr as $id) {
                        $style = $prizes?'margin-top:2px':'';
                        $prizes[] = '<div style="'.$style.'"><button class="btn btn-info btn-sm" type="button">'.$all_prize[$id]['title'].' <span class="badge">'.$all_prize[$id]['name'].'</span></button></div>';
                    }
                    $item['prize'] = $prizes?implode('', $prizes):'';
                    unset($item);
                }
                $pager = pagination($total, $pindex, $pagesize, '', array('before' => -1, 'after' => -1));
            }
        }
        include $this->template('winner');
    }
}
