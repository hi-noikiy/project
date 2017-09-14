<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-17
 * Time: 下午4:43
 * 任务统计
 */

class Quest {
    private $_db;
    public $gameid;
    public function __construct(PDO $db, $gameid=5)
    {
//        $this->_db->exec("SET NAMES 'utf8';");
        $this->_db    = $db;
        $this->gameid = $gameid;

    }

    /**
     * 生成任务列表
     *
     * @param PDO $db
     * @param int $type
     * @return array
     */
    public static function QuestList(PDO $db, $type=110)
    {
        $sql = "select system_id as id,`name`,min_level,max_level from s_quest where type=? ORDER BY system_id ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($type));
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $taskList = array();
        foreach ($tasks as $task) {
            $taskList[$task['id']] = $task;
        }
//        print_r($taskList);exit;
        $s = "<?php\n\$taskList = " . var_export($taskList, true) . ";\n";
        file_put_contents(A_ROOT.'inc/quests.inc.php', $s);
        return $taskList;
    }

    public function QuestComplete($bt, Array $serverid=array(), Array $fenbaoid=array())
    {
        $where = '';
        $tm = strtotime($bt);
        $bt = date('ymd0000', $tm);
        $et = date('ymd2359', $tm);
        $lenServer = count($serverid);

        if ($lenServer==1) {
            $where .= " AND serverid=" . array_shift($serverid);
        }
        elseif ($lenServer>1) {
            $where .= " AND serverid IN(" . implode(',', $serverid).')';
        }
        $lenFenbao = count($fenbaoid);


        if ($lenFenbao==1) {
            $where .= " AND fenbaoid=" . array_shift($fenbaoid);
        }
        elseif ($lenFenbao>1) {
            $where .= " AND fenbaoid IN(" . implode(',', $fenbaoid).')';
        }
        $sql_sum = "SELECT COUNT(*) AS cnt FROM over_quest WHERE `time`>=? AND `time`<=? {$where}";
//        echo $sql_sum;
        $stmt = $this->_db->prepare($sql_sum);
        $stmt->execute(array($bt, $et));
        $totalPlayer = $stmt->fetchColumn();

        $sql = <<<SQL
      SELECT COUNT(*) AS cnt,systemid,time FROM over_quest
      WHERE `time`>=? AND `time`<=? $where
      GROUP BY systemid
      ORDER BY null
SQL;
//        print_r(array($bt, $et));
//        exit;
//        echo $sql;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($bt, $et));
        $quests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array('quests'=>$quests, 'totalPlayer'=>$totalPlayer);
    }
}