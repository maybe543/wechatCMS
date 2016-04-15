<?php

require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashUserService.php';
class FjPlayerService extends FlashCommonService
{
    private $userService;

    public function __construct()
    {
        $this->plugin_name = "lonaking_fishjoy";
        $this->table_name = 'lonaking_fishjoy_player';
        $this->columns = 'id,uniacid,token,openid,nickname,uid,play_times,total_score,create_time,update_time';
        $this->userService = new FlashUserService();
    }

    //初始化一个玩家信息
    public function initPlayerInfo(){
        global $_W;
        $fansInfo = $this->userService->fetchFansInfo($_W['openid']);
        $player = array(
            'uniacid' => $_W['uniacid'],
            'token' => time().rand(0,999),
            'openid' => $_W['openid'],
            'nickname' => $fansInfo['nickname'],
            'uid' => $fansInfo['uid'],
            'play_times' => 1,
            'total_score' => 0,
            'create_time' => time(),
            'update_time' => time()
        );
        $player = $this->insertData($player);
        $player['fans_info'] = $fansInfo;
        return $player;
    }

    //check the player if exist
    public function checkPlayerRegister(){
        global $_W;
        $player = $this->selectPlayerByOpenid($_W['openid']);
        if(empty($player)){
            return $this->initPlayerInfo();
        }else{
            $fansInfo = $this->userService->fetchFansInfo($player['openid']);
            $player['fans_info'] = $fansInfo;
            return $player;
        }
    }

    public function selectPlayerByOpenid($openid){
        global $_W;
        $player = $this->selectOne("AND uniacid={$_W['uniacid']} AND openid='{$openid}'");
        return $player;
    }

    public function getPlayerByToken($token){
        global $_W;
        $this->selectOne("AND uniacid={$_W['uniacid']} AND token='{$token}'");
    }

    public function AddScore($score){
        global $_W;
        $player = $this->selectPlayerByOpenid($_W['openid']);
        if(!empty($player)){
            $player['total_score'] = $player['total_score'] + $score;
            $this->updateData($player);
            $this->userService->addUserScore($score,$_W['openid'],"捕鱼达人游戏");
        }
    }
}