<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 14-7-6
 * Time: 下午10:18
 */

class PlayerLevel extends Base{

    /**
     * 等级分布——从游戏数据库查询
     *
     * @param $serverid
     */
    public function run()
    {
        $data = $this->getData();
        if (is_array($data)) {
            $rowCount = $this->Insert($data, 'sum_player_level');
            if(is_numeric($rowCount) && $rowCount>0) {
                writeLog('OK|Insert Into sum_player_level|rowCount='.$rowCount, LOG_PATH.'/sum_player_level.log');
            }
            else {
                writeLog('FAIL|Insert Into sum_player_level|MSG='.$rowCount,
                    LOG_PATH.'/sum_player_level.log');
            }
        }
    }
    public function getData($server_id='')
    {
        //echo LOG_PATH;exit;
        $this->_souce_db->exec("SET SESSION wait_timeout=65535");
        $where = '1=1';
        if (is_array($server_id) && count($server_id)>0) {
            $where .= " AND serverid IN(".implode(',',$server_id).")";
        }
        elseif (is_numeric($server_id) && $server_id>0) {
            $where .= " AND serverid=$server_id";
        }
        $where .= "  AND n.createtime>={$this->bt} AND n.createtime<={$this->et}";
        //统计总数
        $sql = "SELECT accountid FROM newmac n WHERE " .$where;
//        echo $sql;exit;
        $stmt = $this->_souce_db->prepare($sql);
        $stmt->execute();
        //$stmt->bindColumn('cnt', $totalPlayer);
        $accountsList = $stmt->fetchAll(PDO::FETCH_COLUMN);
//        echo $totalPlayer;
        if (count($accountsList)) {
            $accountsStr  = implode(',', $accountsList);
            $sqlLevel = <<<SQL
  SELECT COUNT(*) AS nop,lev,serverid,fenbao as fenbaoid,{$this->sday} as sday,
  {$this->gameid} as gameid from player
  WHERE accountid IN($accountsStr) GROUP BY lev,serverid,fenbao ORDER BY null
SQL;
//            echo $sqlLevel;
            $stmt = $this->_souce_db->prepare($sqlLevel);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }
        return false;
    }
} 