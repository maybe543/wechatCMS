<?php

require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
require_once dirname(__FILE__) . '/../player/FjPlayerService.php';
class FjRecordService extends FlashCommonService
{
    private $playerService;
    public function __construct()
    {
        $this->plugin_name = "lonaking_fishjoy";
        $this->table_name = 'lonaking_fishjoy_record';
        $this->columns = 'id,uniacid,player_id,uid,openid,nickname,score,play_time,shared,create_time,update_time';
        $this->playerService = new FjPlayerService();
    }

    public function getTodayRecordList(){
        global $_W;
        $openid = $_W['openid'];
        $todayStart = strtotime(date('Y-m-d', time()));
        $todayEnd = strtotime(date('Y-m-d',strtotime('+1 day')));
        $recordList = $this->selectAll("AND openid='{$_W['openid']}' AND  play_time>{$todayStart} AND play_time<{$todayEnd}");
        return $recordList;
    }

    public function addPlayerPlayRecord($score){
        global $_W;
        $player = $this->playerService->checkPlayerRegister();
        if(empty($player)){
            $player = $this->playerService->initPlayerInfo();
        }
        $record = array(
            'uniacid' => $player['uniacid'],
            'player_id' => $player['id'],
            'uid' => $player['uid'],
            'openid' => $player['openid'],
            'nickname' => $player['nickname'],
            'score' => $score,
            'play_time' => time(),
            'shared' => 0,
            'create_time' => time(),
            'update_time' => time()
        );
        return $this->insertData($record);
    }

}