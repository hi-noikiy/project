<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 14-7-9
 * Time: 下午17:46
 * 统计每日活跃玩家金币、元宝数——级别、区服、渠道
 */

class PlayerMoney extends Base{


    /**
     * 等级分布——从游戏数据库查询
     *
     */
    public function run()
    {
        //统计总数
        $sqlLevel = <<<SQL
  SELECT COUNT(*) AS nop,SUM(money) as money, SUM(emoney) as emoney,serverid,fenbaoid,lev,{$this->sday} as sday,
  {$this->gameid} as gameid from palyerday
  WHERE gameid=? AND `day`=?
  GROUP BY fenbaoid,serverid,lev ORDER BY null
SQL;
        $stmt = $this->_souce_db->prepare($sqlLevel);
        $stmt->execute(array($this->gameid, $this->sday));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($data)) {
            $rowCount = $this->Insert($data, 'sum_player_money');
            if(is_numeric($rowCount) && $rowCount>0) {
                writeLog('OK|Insert Into sum_player_money|rowCount='.$rowCount, LOG_PATH.'/sum_player_money.log');
            }
            else {
                writeLog('FAIL|Insert Into sum_player_money|MSG='.$rowCount,
                    LOG_PATH.'/sum_player_money.log');
            }
        }

    }
} 