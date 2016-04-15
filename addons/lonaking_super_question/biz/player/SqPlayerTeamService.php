<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/10/25
 * Time: 上午1:35
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
class SqPlayerTeamService extends FlashCommonService
{
    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";
        $this->table_name = 'lonaking_super_question_player_team';//join_status :0加入 1已退出
        $this->columns = 'id,uniacid,player_id,team_id,join_time,join_status,out_time,create_time,update_time';
    }

    public function getPlayerIdsByTeamId($teamId){
        $playerTeamArray = $this->selectAll(" AND team_id={$teamId}");
        $arr = array();
        foreach($playerTeamArray as $playerTeam){
            $arr[] = $playerTeam['id'];
        }
        return $arr;
    }

    /**
     * delete team player
     */
    public function removePlayerById($playerId,$teamId){
        global $_W;
        $uniacid = $_W['uniacid'];
        $teamPlayer = $this->selectOne("AND uniacid={$uniacid} AND player_id={$playerId} AND team_id={$teamId}");
        if(!empty($teamPlayer)){
            $this->deleteById($teamPlayer['id']);
        }
    }

}