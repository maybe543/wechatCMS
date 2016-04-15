<?php
defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/wr_printer/functions.php';
class Wr_printerModuleProcessor extends WeModuleProcessor
{
    public $tablename = 'wr_printer';
    public function respond()
    {
        $content = $this->message['content'];
        global $_W;
        $rid       = $this->rule;
        $message   = $this->message;
        $content   = $message['content'];
        $from_user = $message['from'];
        load()->model('mc');
        $fans  = mc_fetch($from_user);
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        if (!$this->inContext) {
            $this->beginContext(300);
            if (empty($reply['cycle']) && $reply['cycle'] <> 1) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('wr_printer_pic') . " WHERE  rid = '" . $rid . "' and create_time > '" . strtotime(date('Y-m-d')) . "' AND  fid = '" . $fans['uid'] . "'");
            } else {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('wr_printer_pic') . " WHERE  rid = '" . $rid . "' AND  fid = '" . $fans['uid'] . "'");
            }
            if (empty($reply['status']) && $reply['status'] <> 1) {
                $this->endContext();
                session_destroy();
                return $this->respText('活动还没启动呢！');
            }
            if (!empty($reply['maxnum']) && $total >= $reply['maxnum']) {
                $fuser = pdo_fetch("SELECT * FROM " . tablename('wr_printer_count') . " WHERE rid = :rid AND fid = :fid order by id desc", array(
                    ':rid' => $rid,
                    ':fid' => $fans['uid']
                ));
                if (empty($fuser) || $fuser['count'] <= 0) {
                    if ($reply['is_consumecode'] == 1) {
                        $_SESSION['img']            = '0';
                        $_SESSION['is_consumecode'] = '1';
                        if ($reply['price'] > 0) {
                            return $this->respText($reply['msg'] . '免费打印次数已经用完，您可以<a href="' . $this->createMobileUrl('pay', array(
                                'rid' => $rid
                            ), true) . '">购买</a>消费码，并在此输入消费码：');
                        } else {
                            return $this->respText($reply['msg'] . '免费打印次数已经用完，必须通过消费码来参与了，请输入消费码：');
                        }
                    } else {
                        $this->endContext();
                        session_destroy();
                        return $this->respText('你本次活动的参与次数已用完！');
                    }
                } else {
                    $_SESSION['ucount'] = $fuser['count'];
                    $_SESSION['uid']    = $fuser['id'];
                }
            }
            if (!empty($reply['authcode']) && (($reply['is_authcode'] == 1) || ($reply['is_consumecode'] == 1))) {
                $_SESSION['img'] = '0';
                if ($reply['is_authcode'] == 1) {
                    return $this->respText($reply['msg'] . '请输入屏幕上的验证码：');
                } else {
                    if ($reply['price'] > 0) {
                        return $this->respText($reply['msg'] . '免费打印次数已经用完，您可以<a href="' . $this->createMobileUrl('pay', array(
                            'rid' => $rid
                        ), true) . '">购买</a>消费码，并在此输入消费码：');
                    } else {
                        return $this->respText($reply['msg'] . '免费打印次数已经用完，必须通过消费码来参与了，请输入你的消费码：');
                    }
                }
            } else {
                $_SESSION['img'] = '1';
                return $this->respText($reply['msg'] . '请选择一张照片上传(点对话框后面 + 号，选择图片)：');
            }
            $_SESSION['imgnum'] = 1;
        } else {
            if ($content == '退出') {
                $this->endContext();
                session_destroy();
                return $this->respText('您已回到普通模式！');
            }
            if (($reply['is_authcode'] == 1) && $_SESSION['img'] == 0 && ($_SESSION['is_consumecode'] != '1')) {
                if (($content == $reply['authcode']) && ($this->message['type'] == 'text')) {
                    $_SESSION['verifyed'] = '0';
                } else {
                    $_SESSION['verifyed'] = '1';
                }
            }
            if (($reply['is_consumecode'] == 1) && ($_SESSION['verifyed'] != '0') && empty($_SESSION['img'])) {
                $reply1 = pdo_fetch("SELECT  consumecode FROM " . tablename('wr_printer_consumecode') . " WHERE rid = :rid AND consumecode = :consumecode and status=0 LIMIT 1", array(
                    ':rid' => $rid,
                    ':consumecode' => $content
                ));
                if (($content == $reply1['consumecode']) && ($this->message['type'] == 'text')) {
                    $_SESSION['verifyed']      = '0';
                    $_SESSION['consumecodeps'] = $reply1['consumecode'];
                } else {
                    $_SESSION['verifyed'] = '1';
                }
            }
            if (($_SESSION['verifyed'] == '1') && empty($_SESSION['img'])) {
                if ($_SESSION['is_consumecode'] == '1') {
                    return $this->respText('你只有输入正确的消费码才能参与，请输入：');
                } else {
                    return $this->respText('输入的不对哦，请输入正确的验证码或消费码：');
                }
            } else {
                if ($_SESSION['img'] == '0') {
                    if (empty($_SESSION['consumecodeps'])) {
                        $filenamep = 'wr_printer/' . $rid . '/pwd.txt';
                        $pwd1      = random(3, true);
                        file_write($filenamep, 'lyqywp' . $pwd1);
                        pdo_update($this->tablename, array(
                            'authcode' => $pwd1
                        ), array(
                            'rid' => $rid
                        ));
                    }
                    $_SESSION['img'] = '1';
                    return $this->respText('请选择一张照片上传(点对话框后面 + 号，选择图片)：');
                }
                if ($_SESSION['img'] == '1') {
                    if (($this->message['type'] == 'image') && empty($_SESSION['piccontent'])) {
                        load()->func('communication');
                        $image    = ihttp_request($this->message['picurl']);
                        $time     = random(13);
                        $filename = 'wr_printer/' . $rid . '/' . $time . '.jpg';
                        file_write($filename, $image['content']);
                        $_SESSION['piccontent'] = $filename;
                        if ($reply['is_cut'] == 1) {
                            if ($reply['is_guestbook'] == 1) {
                                $_SESSION['img'] = '2';
                                return $this->respText('上传照片成功！如需裁剪 <a href="' . $_W['siteroot'] . '/lomo/cutimage.php?pic=/' . $GLOBALS['_W']['config']['upload']['attachdir'] . '/' . $filename . '">请点这里</a>。最后一步，请输入你想留在照片上的话（10个字以内），输入 # 则放弃留言：');
                            } else {
                                $_SESSION['img'] = '3';
                                return $this->respText('上传照片成功！如需裁剪 <a href="' . $_W['siteroot'] . '/lomo/cutimage.php?pic=/' . $GLOBALS['_W']['config']['upload']['attachdir'] . '/' . $filename . '">请点这里</a>。最后一步，直接回复 # 开始打印照片。');
                            }
                        } else {
                            if ($reply['is_guestbook'] == 1) {
                                $_SESSION['img'] = '2';
                                return $this->respText('上传照片成功！最后一步，请输入你想留在照片上的话（10个字以内），输入 # 则放弃留言：');
                            } else {
                                $_SESSION['img'] = '3';
                            }
                        }
                    } else {
                        return $this->respText('只能传照片哦！');
                    }
                }
                if ($_SESSION['img'] == '2') {
                    if (($this->message['type'] != 'text') || empty($content)) {
                        return $this->respText('只能输入文字：');
                    } elseif (mb_strlen($content) >= 33) {
                        return $this->respText('你输入的文字超长了吧，重新输入：');
                    } else {
                        if (($content == '#') || ($content == '＃')) {
                            $_SESSION['msg'] = '';
                        } else {
                            $_SESSION['msg'] = $content;
                        }
                        $_SESSION['img'] = '3';
                    }
                }
                if ($_SESSION['img'] == '3') {
                    $pic      = IA_ROOT . '/' . $GLOBALS['_W']['config']['upload']['attachdir'] . '/' . $_SESSION['piccontent'];
                    $pic_bg   = IA_ROOT . '/' . $GLOBALS['_W']['config']['upload']['attachdir'] . '/' . $reply['photo_ad'];
                    $file_ext = fileext($pic);
                    $pic_new  = str_replace('.' . $file_ext, '_new.' . $file_ext, $pic);
                    if ($reply['pic_size'] == 'A6') {
                        $pic_width  = 690;
                        $pic_height = 1000;
                        $pic_new    = $pic;
                    } else {
                        $pic_width  = 500;
                        $pic_height = 590;
                        img2thumb($pic, $pic_new, $pic_width, $pic_height, $cut = 0, $proportion = 0);
                    }
                    if (!empty($reply['photo_ad'])) {
                        $file_ext    = fileext($pic_new);
                        $file_ext_bg = fileext($pic_bg);
                        $filename_bg = str_replace('_new.' . $file_ext, '_ad.' . $file_ext_bg, $pic_new);
                        copy($pic_bg, $filename_bg);
                        imageWaterMark($filename_bg, 2, $pic_new);
                        @unlink($pic_new);
                        @rename($filename_bg, $pic_new);
                    }
                    $filenamec = '/wr_printer/' . $rid . '/count.txt';
                    $buffer    = '10000';
                    $file_name = IA_ROOT . '/' . $GLOBALS['_W']['config']['upload']['attachdir'] . $filenamec;
                    if (file_exists($file_name)) {
                        $fp = fopen($file_name, 'r');
                        while (!feof($fp)) {
                            $buffer = fgets($fp);
                        }
                        fclose($fp);
                    } else {
                        file_write($filenamec, $buffer);
                    }
                    $buffer++;
                    file_write($filenamec, $buffer);
                    $filenamem = 'wr_printer/' . $rid . '/msg.html';
                    $msghead   = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
                    $msgwrite  = '<wp><id>' . $buffer . '</id><purl>' . substr($_SESSION['piccontent'], -17) . '</purl><msg>' . $_SESSION['msg'] . '</msg></wp>';
                    $file_name = IA_ROOT . '/' . $GLOBALS['_W']['config']['upload']['attachdir'] . $filenamem;
                    if (file_exists($file_name)) {
                        file_put_contents($file_name, $msgwrite, FILE_APPEND);
                    } else {
                        $msgwrite = $msghead . $msgwrite;
                        file_write($filenamem, $msgwrite);
                    }
                    $insert = array(
                        'rid' => $rid,
                        'fid' => $fans['uid'],
                        'weid' => $_W['weid'],
                        'msg' => $_SESSION['msg'],
                        'pic' => $_SESSION['piccontent'],
                        'newpic' => str_replace(IA_ROOT . '/' . $GLOBALS['_W']['config']['upload']['attachdir'] . '/', '', $pic_new),
                        'bianhao' => $buffer,
                        'create_time' => time()
                    );
                    $reply2 = pdo_fetch("SELECT  fid,bianhao,create_time FROM " . tablename('wr_printer_pic') . " WHERE rid = :rid order by id desc LIMIT 1", array(
                        ':rid' => $rid
                    ));
                    if ((($fans['uid'] == $reply2['fid']) && ((time() - $reply2['create_time']) <= 5)) || ($reply2['bianhao'] == $buffer)) {
                        $cfps = '1';
                    } else {
                        $cfps = '0';
                    }
                    if ($cfps == '0') {
                        if ($id = pdo_insert('wr_printer_pic', $insert)) {
                            if ((!empty($_SESSION['uid'])) && (empty($_SESSION['consumecodeps']))) {
                                $data = array(
                                    'count' => $_SESSION['ucount'] - 1
                                );
                                pdo_update('wr_printer_count', $data, array(
                                    'id' => $_SESSION['uid']
                                ));
                            }
                            if (!empty($_SESSION['consumecodeps'])) {
                                $data = array(
                                    'status' => 1,
                                    'use_time' => time()
                                );
                                pdo_update('wr_printer_consumecode', $data, array(
                                    'rid' => $rid,
                                    'consumecode' => $_SESSION['consumecodeps']
                                ));
                            }
                            $_SESSION['imgnum']++;
                            if (($_SESSION['imgnum'] < $reply['dcmaxnum']) && (!empty($_SESSION['consumecodeps']))) {
                                $_SESSION['img']        = '1';
                                $_SESSION['piccontent'] = 0;
                                return $this->respText($reply['msg_succ'] . ' 你的照片编号为' . $buffer . "。还可继续传一张照片（退出请输入 退出）");
                            } else {
                                $this->endContext();
                                session_destroy();
                                return $this->respText($reply['msg_succ'] . ' 你的照片编号为' . $buffer . "。谢谢使用！");
                            }
                        } else {
                            $this->endContext();
                            session_destroy();
                            return $this->respText($reply['msg_fail']);
                        }
                    } else {
                        $this->endContext();
                        session_destroy();
                    }
                }
                $this->endContext();
                session_destroy();
            }
        }
    }
}