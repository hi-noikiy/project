<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/2/21
 * Time: 10:06
 *
 * 用户留存模型
 */
include_once __DIR__ . '/base_model.php';
class GameDataAnalysis_model extends Base_model
{
    public function __construct()
    {
        parent::__construct();
        $this->db_game = $this->load->database('game', TRUE);
    }

    /**
     * 每日留存
     *
     * @param sring $date
     * @return array|bool
     */
    public function Remain($date)
    {
        $time_start = strtotime($date . ' 00:00:00');
        $time_end   = strtotime($date . ' 23:59:59');
        //新增玩家数： create_time字段（时间戳） （创建角色时间）
        //次日流失: 最后登出时间≤创建当天   logout_time字段（最后登出时间）
        //3日流失: 最后登出时间≤创建当天+1
        //留存率计算公式：（时间区间内新增玩家数-时间区间内流失人数）/时间区间内新增玩家数
        $sql = "SELECT COUNT(*) AS cnt FROM u_player_all WHERE create_time BETWEEN $time_start and $time_end";
        //echo $sql,"\n";
        $query = $this->db_game->query($sql);
        $row = $query->row();
        $today = date('Ymd');
        if ($row) {
            $new_user = $row->cnt;
            if ($new_user==0) return false;
            $data = ['total'=>$new_user, 'data'=>[]];
            $dayList = array(0=>'次日',1=>'三日', 2=>'四日', 3=>'五日', 4=>'六日', 5=>'七日', 13=>'十五日',28=>'三十日');
            foreach ( $dayList as $day_idx=>$day) {
                $tm              = strtotime("+ $day_idx days", $time_start);
                $cur_date         = date('Ymd', $tm);
                //echo $cur_date, '--' ,$today,"\n";
                $createTimeBegin = strtotime(date('Y-m-d 00:00:00', $tm));
                $createTimeEnd   = strtotime(date('Y-m-d 23:59:59', $tm));
                //echo 'today:',$date,'--',$day,'--登出时间：',date('Y-m-d H:i:s', $createTimeBegin) ,'--', date('Y-m-d H:i:s', $createTimeEnd),"\n";
                if ($cur_date>$today) {
                    $data['data'][$day_idx]['login'] = 0;
                    $data['data'][$day_idx]['lost']  = 0;
                    $data['data'][$day_idx]['lost_rate']    = '-';
                    $data['data'][$day_idx]['remain_rate']  = '-';
                }
                else {
                    $sql_lost = "SELECT COUNT(*) AS cnt FROM u_player_all WHERE create_time BETWEEN $time_start and $time_end AND logout_time BETWEEN $time_start and $createTimeEnd";
                    //echo $sql_lost,"\n";
                    $query  = $this->db_game->query($sql_lost);
                    $row    = $query->row();
                    if ( $row ) {
                        $data['data'][$day_idx]['login'] = $row->cnt;
                        $data['data'][$day_idx]['lost']  = $new_user - $row->cnt;
                        $rate = ($new_user - $row->cnt) / $new_user;
                        //$data['data'][$day_idx]['lost_rate']    = round($rate, 4) * 100;
                        //$data['data'][$day_idx]['remain_rate']  = round(100 - $data['data'][$day_idx]['lost_rate'], 2);

                        $data['data'][$day_idx]['remain_rate']    = round($rate, 4) * 100;
                        $data['data'][$day_idx]['lost_rate']  = 100 - $data['data'][$day_idx]['remain_rate'];
                    }
                }

            }
            return $data;
        }
        return false;
    }

    public function getLostOneDay($time_start, $time_end)
    {
        $sql_lost = "SELECT accountid FROM u_player_all WHERE create_time BETWEEN $time_start and $time_end AND logout_time BETWEEN $time_start and $time_end group by level";
        $query  = $this->db_game->query($sql_lost);
        $result    = $query->result();
        //foreach ($result as $)
    }

    /**
     * @param $date
     * @return array | bool
     */
    public function Lost($date)
    {
        $time_start = strtotime($date . ' 00:00:00');
        $time_end   = strtotime($date . ' 23:59:59');
        //新增玩家数： create_time字段（时间戳） （创建角色时间）
        //次日流失: 最后登出时间≤创建当天   logout_time字段（最后登出时间）
        //3日流失:  最后登出时间≤创建当天+1
        //留存率计算公式：（时间区间内新增玩家数-时间区间内流失人数）/时间区间内新增玩家数
        //根据等级，统计【次日流失】的玩家人数  level字段（角色等级）

        $sql_lost = "SELECT COUNT(*) AS cnt,level FROM u_player_all WHERE create_time BETWEEN $time_start and $time_end AND logout_time BETWEEN $time_start and $time_end group by level";
        //echo $sql_lost,"\n";
        $query  = $this->db_game->query($sql_lost);
        $result    = $query->result();
        $data      = [];
        if ( $result ) {
            foreach($result as $row){
                $data[] = ['level'=>$row->level, 'cnt'=>$row->cnt];
            }
            return $data;

        }
        return false;
    }

    public function LostTimeLong($date)
    {
        //统计【次日流失】的玩家，从创建角色到最后登出时间的间隔。
        //使用的表： u_player001~u_player003
        //流失时长：最后登出时间 - 创建角色时间 logout_time字段（最后登出时间）  create_time字段（时间戳）
        $time_start = strtotime($date . ' 00:00:00');
        $time_end   = strtotime($date . ' 23:59:59');
        $sql_lost = "SELECT account_id,(logout_time-create_time) as life FROM u_player_all WHERE create_time BETWEEN $time_start and $time_end AND logout_time BETWEEN $time_start and $time_end ORDER BY life ASC";
        //echo $sql_lost,"\n";
        $query  = $this->db_game->query($sql_lost);
        $result    = $query->result();
        if ( $result ) {
            $data = [];
            foreach($result as $row) {
                $time = ceil($row->life / 60);
                if ($time <= 30) {
                    $data[$time] += 1;
                }
                elseif ($time <= 60) {
                    //30分钟-60分钟 5分钟一条数据
                    for ($i=30;$i<=60; $i += 5) {
                        if ( $time >= $i && $time <= ($i+5) )
                            $data[$i] += 1;
                    }
                }
                elseif ($time <= 200) {
                    //60分钟-200分钟 10分钟一条数据
                    for ($i=60; $i<=200; $i += 10) {
                        if ($time >= $i && $time <= ($i+10))
                            $data[$i] += 1;
                    }
                }
                else {
                    $data['200+'] += 1;
                }
            }
            $ret = [];
            foreach ($data as $life=>$cnt) {
                $ret[] = [
                    'life'      => $life,
                    'cnt'       => $cnt
                ];
            }
            return $ret;
        }
        return false;
    }

    /**
     * 统计【次日流失】的玩家，最后停留的关卡数据。
     * u_playerdata001~u_playerdata003表中 wanted_normal字段记录冒险关卡id
     * @param $date
     * @return array
     */
    public function RiskLost($date)
    {
        $time_start = strtotime($date . ' 00:00:00');
        $time_end   = strtotime($date . ' 23:59:59');
        $sql_lost = "SELECT COUNT(*) AS cnt,d.wanted_normal FROM u_player_all a "
            ."left join u_playerdata d on d.id=a.id WHERE a.create_time "
            ."BETWEEN $time_start and $time_end AND logout_time BETWEEN $time_start and $time_end and wanted_normal>0 GROUP BY wanted_normal";
        //echo $sql_lost;
        $query  = $this->db_game->query($sql_lost);
        $data = $query->result_array();
        if (!$data) return false;
        $output = [];
        foreach ($data as $item) {
            $output[$item['wanted_normal']] = $item['cnt'];
        }
        //print_r($output);
        return $output;
    }
}