<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/3/16
 * Time: 21:03
 */
class system_analysis_model extends CI_Model
{
    private $db_sdk;
    private $appid;
    private $bt;
    private $et;
    private $sday;
    private $serverid;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    /**
     * init set
     *
     * @param string $appid appid
     * @param int $bt begin time
     * @param int $et end time
     * @param int $serverid
     * @param int $channel
     * @param bool|false $init_sdk
     */
    public function init($appid, $bt=0, $et=0, $sday, $serverid=0, $channel=0, $db_sdk=false )
    {
        $this->appid = $appid;
        $this->bt    = $bt;
        $this->et    = $et;
        $this->sday  = $sday;

        $this->serverid  = $serverid;

        $this->db_sdk = $db_sdk;

    }

    public function emoney_analysis($item_type='')
    {
        $sql = <<<SQL
SELECT item_type,sday,SUM(emoney_get) as emoney_get, SUM(emoney_use) AS emoney_use,
SUM(emoney_left) AS emoney_left FROM sum_emoney_analysis
WHERE appid=? AND sday BETWEEN ? AND ?
SQL;
        if ($this->serverid>0)  $sql .= " AND serverid={$this->serverid}";
        if (!empty($item_type)) $sql .= " AND item_type='$item_type'";
        $sql .= " GROUP BY sday, item_type";
        $query = $this->db->query($sql, [$this->appid, $this->bt, $this->et]);
        return $query->result_array();
    }

    /**
     * 道具分析
     *
     * @param string $prop_id
     * @return mixed
     */
    public function props_analysis($prop_id='', $prop_id_end='')
    {
        $sql = <<<SQL
SELECT prop_id,prop_name,sday,SUM(props_get) as props_get, SUM(props_use) AS props_use
FROM sum_props_analysis
WHERE appid=? AND sday BETWEEN ? AND ?
SQL;
        if ($this->serverid>0)  $sql .= " AND serverid={$this->serverid}";
        if (!empty($prop_id) && !$prop_id_end)  $sql .= " AND prop_id=$prop_id";
        elseif (!empty($prop_id) && !empty($prop_id_end))  $sql .= " AND prop_id>=$prop_id AND prop_id<=$prop_id_end";
        $sql .= " GROUP BY sday, prop_id";
        $query = $this->db->query($sql, [$this->appid, $this->bt, $this->et]);
        return $query->result_array();
    }

    /**
     * @param string $prop_id
     * @return mixed
     */
    public function copy_analysis($copy_id_begin=0, $copy_id_end=0)
    {
        $where = ' appid=? AND sday BETWEEN ? AND ? ';
        if ($this->serverid>0)  $where .= " AND serverid={$this->serverid}";
        if ($copy_id_end>0 || $copy_id_begin>0){
            if ($copy_id_begin>0 && $copy_id_end==0)  {
                $where .= " AND copy_id=$copy_id_begin";
            }
            elseif ($copy_id_begin>0 && $copy_id_end>0)  $where .= " AND copy_id>=$copy_id_begin";
            if (!empty($copy_id_end))  $where .= " AND copy_id<=$copy_id_end";
            $grp    = " GROUP BY sday,copy_id";
            $select = "copy_type,copy_id,copy_title";
        }
        else {
            $grp    = " GROUP BY sday,copy_type";
            $select = 'copy_type';
        }
        $sql = <<<SQL
SELECT sday,{$select},sum(success_times) as success_times,sum(fail_times) as fail_times,sum(total_times) as total_times
 FROM sum_copy_analysis
WHERE $where $grp
SQL;
        //echo $sql;
        $query = $this->db->query($sql, [$this->appid, $this->bt, $this->et]);
        return $query->result_array();
    }

    /**
     * 关卡进度
     *
     * @param string $appid
     * @param int $lev
     * @param int $serverid
     * @return mixed
     */
    public function level_analysis($appid, $lev=0, $lev2=10, $serverid=0, $account_id=0)
    {
        $db_sdk = $this->load->database('sdk', TRUE);

        $sql = "SELECT lev,level_type,level_id,highest_level FROM u_level_process WHERE appid=? ";
        if ($account_id>0)  $sql .= " AND accountid={$account_id}";
        if ($serverid>0)    $sql .= " AND serverid={$serverid}";
        if ($lev>0)         $sql .= " AND lev>=$lev";
        if ($lev2>0)        $sql .= " AND lev<=$lev2";
        $sql .= " GROUP BY lev,level_id";
        $query = $db_sdk->query($sql, [$appid]);
        return $query->result_array();
    }

    /**
     * 成就进度
     *
     * @param $appid
     * @param int $lev
     * @param int $lev2
     * @param int $serverid
     * @return mixed
     */
    public function success_analysis($appid, $lev=0, $lev2=10, $serverid=0, $account_id=0)
    {
        $db_sdk = $this->load->database('sdk', TRUE);

        $sql = "SELECT lev,success_type,success_id,highest_success FROM u_success_process WHERE appid=?";
        if ($account_id>0)  $sql .= " AND accountid={$account_id}";
        if ($serverid>0)    $sql .= " AND serverid={$serverid}";
        if ($lev>0)         $sql .= " AND lev>=$lev";
        if ($lev2>0)        $sql .= " AND lev<=$lev2";
        $sql .= " GROUP BY lev,success_id";
        $query = $db_sdk->query($sql, [$appid]);
        return $query->result_array();
    }

    /**
     * 等级分析
     *
     * @param $appid
     * @param int $lev
     * @param int $lev2
     * @param int $serverid
     * @return mixed
     */
    public function upgrade_analysis($appid, $lev=0, $lev2=10, $serverid=0, $account_id=0)
    {
        $db_sdk = $this->load->database('sdk', TRUE);
        $sql = "SELECT lev,accountid,upgrade_time,created_at,serverid FROM u_upgrade_process WHERE appid='$appid'";
        if ($account_id>0)  $sql .= " AND accountid={$account_id}";
        if ($serverid>0)    $sql .= " AND serverid={$serverid}";
        if ($lev>0)         $sql .= " AND lev>=$lev";
        if ($lev2>0)        $sql .= " AND lev<=$lev2";
        $sql .= " GROUP by accountid,lev";
        $sql .= " ORDER BY accountid ASC,lev ASC";
        $query = $db_sdk->query($sql, [$appid]);
        //var_dump( $this->convert(memory_get_usage()) );
        $data  = $query->result_array();
        //var_dump( $this->convert(memory_get_usage()) );
        return $data;
    }
    function convert($size){
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
    /**
     * 每日消耗
     *
     * @return mixed
     */
    public function emoney_use()
    {
        $sql = <<<SQL
      SELECT SUM(emoney) AS emoney,serverid,channel,itemtype as item_type FROM u_rmb
      WHERE appid=? AND created_at BETWEEN ? AND ?
      GROUP BY item_type,serverid,channel
      ORDER BY null
SQL;
        $query = $this->db_sdk->query($sql, [$this->appid, $this->bt, $this->et]);
        $ret = $query->result_array();
        $this->emoney_save($ret, 'emoney_use');
        return $ret;
    }

    /**
     * 每日获取的元宝统计
     *
     * @return mixed
     */
    public function emoney_get()
    {
        $sql = <<<SQL
      SELECT SUM(emoney) AS emoney,serverid,channel,item_type FROM u_give_emoney
      WHERE appid=? AND created_at BETWEEN ? AND ?
      GROUP BY item_type,serverid,channel
      ORDER BY null
SQL;
        $query = $this->db_sdk->query($sql, [$this->appid, $this->bt, $this->et]);
        $ret = $query->result_array();
        $this->emoney_save($ret, 'emoney_get');
        return $ret;
    }

    /**
     * 每日获得的数量-每日消耗的数量
     *
     * @return bool
     */
    public function emoney_left()
    {
        $sql = <<<SQL
SELECT id,emoney_get,emoney_use FROM sum_emoney_analysis
WHERE appid='$this->appid' AND emoney_left=-1
SQL;
        //echo $sql;exit;
        $query = $this->db->query($sql, [$this->appid]);
        $update = "UPDATE sum_emoney_analysis SET emoney_left= CASE id ";
        $id_list = [];
        $rows = $query->result_array();
        foreach ( $rows as $_row ) {
            $left = $_row['emoney_get'] - $_row['emoney_use'];
            $update .= " WHEN {$_row['id']} THEN {$left}";
            $id_list[] = $_row['id'];
        }
        $update .= " END WHERE id IN(".implode(',', $id_list).")";
        if (count($id_list)) {
            echo $update;
            return $this->db->query($update);
        }
        return false;
    }
    /**
     * 保存数据
     *
     * @param $data
     * @param $col
     * @return mixed
     */
    private function emoney_save($data, $col)
    {
        if (empty($data)) {
            log_message('error', "system_analysis_model.emoney_save run exit,cause empty data");
            return false;
        }
        $sql = <<<SQL
INSERT INTO sum_emoney_analysis(serverid,channel,appid,item_type,`$col`,sday) VALUES %REPLACE%
ON DUPLICATE KEY UPDATE `$col`=VALUES($col)
SQL;
        //print_r($data);return;
        $str  = '';
        foreach ($data as $row) {
            $str .= "({$row['serverid']}, {$row['channel']}, $this->appid,'{$row['item_type']}', {$row['emoney']}, {$this->sday}),";
            //echo $str;
        }
        $str = rtrim($str, ',');
        $sql = str_replace('%REPLACE%', $str, $sql);
        return $this->db->query($sql);
    }

    /**
     * 道具每日消耗
     * @param int $action 0获取 1 消耗
     * @return mixed
     */
    public function props($action=0)
    {
        if ($action==0) {
            $sdk_table = 'u_props';
            $col       = 'props_get';
        } else if ($action==1) {
            $sdk_table = 'u_props_used';
            $col       = 'props_use';
        }
        $sql = <<<SQL
      SELECT SUM(amounts) AS amounts,serverid,channel,prop_id as prop_id,
      prop_name FROM $sdk_table WHERE appid='$this->appid'
      AND created_at BETWEEN $this->bt AND $this->et
      GROUP BY prop_id,serverid,channel
      ORDER BY null
SQL;
        echo $sql;
        //exit; , [$this->appid, $this->bt, $this->et]
        $query = $this->db_sdk->query($sql);
        $ret = $query->result_array();
        $this->props_save($ret, $col);
        return $ret;
    }
    private function props_save($data, $col)
    {
        if (empty($data)) {
            log_message('error', "system_analysis_model.props_save run exit,cause empty data");
            return false;
        }
        $sql = <<<SQL
INSERT INTO sum_props_analysis(serverid,channel,appid,prop_id,prop_name,`$col`,sday) VALUES %REPLACE%
ON DUPLICATE KEY UPDATE `$col`=VALUES($col)
SQL;
        //print_r($data);return;
        $str  = '';
        foreach ($data as $row) {
            $str .= "({$row['serverid']}, {$row['channel']}, $this->appid,'{$row['prop_id']}','{$row['prop_name']}', {$row['amounts']}, {$this->sday}),";
            //echo $str;
        }
        $str = rtrim($str, ',');
        $sql = str_replace('%REPLACE%', $str, $sql);
        return $this->db->query($sql);
    }

    /**
     * 副本分析
     */
    public function copy_progress()
    {
        //挑战次数, 成功失败次数
        $sql = <<<SQL
SELECT count(*)  AS cnt, is_success,`type`,copy_id,`title`,serverid FROM u_copy_progress
WHERE appid='{$this->appid}' AND created_at BETWEEN $this->bt AND $this->et
GROUP BY `copy_id`,is_success,serverid
SQL;
        $query = $this->db_sdk->query($sql);
        $ret = $query->result_array();
        if (!$ret) return false;
        $data = [];
        $copy_data = $success_times = $fail_times = [];
        foreach ($ret as $_row)
        {
            $_key = $_row['copy_id'].'_'.$_row['serverid'];
            $copy_data[$_key]['title'] = $_row['title'];
            $copy_data[$_key]['type'] = $_row['type'];
            $copy_data[$_key]['copy_id'] = $_row['copy_id'];
            $copy_data[$_key]['serverid'] = $_row['serverid'];
            if (!isset($success_times[$_key])) $success_times[$_key] = 0;
            if (!isset($fail_times[$_key])) $fail_times[$_key] = 0;
            if ($_row['is_success'] == 1 ) {
                $success_times[$_key] += $_row['cnt'];
            }
            else {
                $fail_times[$_key]    += $_row['cnt'];
            }
        }
        //print_r($copy_data);exit;
        foreach($copy_data as $_key=>$_row) {
            $success = isset($success_times[$_key]) ? $success_times[$_key] : 0;
            $fail    = isset($fail_times[$_key]) ? $fail_times[$_key] : 0;
            $data[] = [
                'appid'         => $this->appid,
                'copy_type'     => $_row['type'],
                'copy_id'       => $_row['copy_id'],
                'copy_title'    => $_row['title'],
                'success_times' => $success,
                'fail_times'    => $fail,
                'total_times'   => $success + $fail,
                'serverid'      => $_row['serverid'],
                'sday'          => $this->sday,
            ];
        }
        //print_r($data);exit;
        $this->db->insert_batch('sum_copy_analysis', $data);
    }

    /**
     * 玩家副本通关/失败时的等级
     *
     * @param $appid
     * @param $copy_type
     * @param int $is_success
     * @return mixed
     */
    public function copy_player_lev($appid, $copy_id, $is_success=0)
    {
        $db_sdk = $this->load->database('sdk', TRUE);
//        $sql    = <<<SQL
//SELECT accountid, MAX(lev) AS lev,created_at FROM u_copy_progress
//WHERE appid='$appid' AND `type`=$copy_type AND is_success=$is_success
//GROUP BY accountid
//SQL;
        $sql    = <<<SQL
SELECT accountid, MAX(lev) AS lev,created_at FROM u_copy_progress
WHERE appid=? AND `type`=? AND is_success=?
GROUP BY accountid
SQL;
        //echo $sql;
        $query = $db_sdk->query($sql, [$appid, $copy_id, $is_success]);
        return $query->result_array();
    }

    public function RegFlow($appid, $startTime, $endTime, $channelId=0)
    {
        $db_sdk = $this->load->database('sdk', true);
        $query_str = "select count(*) as cnt,channel";
        $query_ext = ",FROM_UNIXTIME(created_at, '%H') as hour,FROM_UNIXTIME(created_at, '%i') as minute";
        $map = " where appid=? and created_at between ? and ?";
        $group = " group by channel,serverid";
        $group_detail = ",hour,minute";
        $bind= [$appid, $startTime, $endTime];
        if ($channelId>0) {
            $map .= " and channelid=?";
            $bind[] = $channelId;
        }
        //设备激活数量
        $sql_device = $query_str . $query_ext . ' from u_device_active';
        $device = $db_sdk->query($sql_device . $map . $group.$group_detail, $bind);

        //注册数
        $sql_reg = $query_str . $query_ext . ' from u_register ';
        $register= $db_sdk->query($sql_reg . $map . $group.$group_detail, $bind);

        //角色数量
        $sql_role = $query_str . $query_ext . ' from u_roles ';
        $roles    = $db_sdk->query($sql_role . $map . $group.$group_detail, $bind);

        //总注册数
        $sql_reg_his = $query_str . ' from u_register ';
        $register_his= $db_sdk->query($sql_reg_his . $group);

        //活跃
//        $sql_ac = <<<SQL
//SELECT COUNT(DISTINCT accountid) as cnt,channel FROM u_dayonline WHERE appid=? AND created_at BETWEEN ? AND ? GROUP BY serverid,channel,accountid HAVING count(accountid)>2
//SQL;
    $sql_ac = <<<SQL
SELECT COUNT(DISTINCT accountid) as cnt,channel,FROM_UNIXTIME(created_at, '%H') as hour,FROM_UNIXTIME(created_at, '%i') as minute FROM u_dayonline WHERE appid=? AND created_at BETWEEN ? AND ? GROUP BY serverid,channel,hour,minute HAVING count(accountid)>2
SQL;
        //在线15分钟才算是活跃玩家，数据库中一条记录是指在线300秒（5分钟）
        $active = $db_sdk->query($sql_ac, $bind);
        //print_r($active->result_array());


        return [
            'device'    =>$device->result_array(),
            'register'  =>$register->result_array(),
            'role'      =>$roles->result_array(),
            'register_his' =>$register_his->result_array(),
            'active'    => $active->result_array()
        ];
    }
}
