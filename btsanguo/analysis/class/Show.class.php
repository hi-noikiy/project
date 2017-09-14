<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-10
 * Time: 下午6:06
 */

class Show extends ShowBase{

    public function PlayerPay()
    {
        $sql = <<<SQL
  SELECT sday, serverid, gameid, fenbaoid, SUM(paynopall) AS paynopall,
  SUM(paynopnew) AS paynopnew, SUM(paynopnew_money) AS paynopnew_money,
  SUM(paycnt) AS paycnt, SUM(income) AS income, SUM(arpu) AS arpu
  FROM sum_pay_daily {$this->where} {$this->group}
SQL;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
} 