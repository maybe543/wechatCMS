<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/1
 * Time: 上午10:00
 */
class QrcodeUtils
{
    private function checkQrcode($user){
        global $_GPC, $_W;
        //check the qrcode's source people is overtime
        if(!empty($user['qrcode']) ){ // 公众号无权限生成二维码
            if(time() - $user['qrcode_updatetime'] > 600000){//过期
                //do qrcode
                $barcode = array(
                    'expire_seconds' => 604800, // 二维码的有效时间, 单位 秒.
                    'action_name' => 'QR_SCENE',
                    'action_info' => array(
                        'scene' => array(
                            'scene_id' => $user['scene_id']
                        )
                    )
                );
                load()->classs('weixin.account');
                $account = WeiXinAccount::create($_W['account']['acid']);
                $qrcode = $account->barCodeCreateDisposable($barcode);
                if($qrcode['errno'] != -1){//有权限生成二维码
                    $qrcode_url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($qrcode['ticket']);
                    $user['qrcode'] = $qrcode_url;
                    $user['qrcode_updatetime'] = time();
                    $user['updatetime'] = time();
                    pdo_update(TableResource::$table['user']['name'],$user,array('id'=>$user['id']));//更新二维码
                }else{
                    $user['qrcode'] = '';
                    $user['qrcode_updatetime'] = '';
                }
            }
        }
        return $user;
    }
}