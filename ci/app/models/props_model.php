<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/1/19
 * Time: 20:08
 *
 * 资源模型
 */
class props_model extends CI_Model
{

    public function __construct($appid,$bt, $et)
    {
        parent::__construct();

        $this->db1    = $this->load->database('default', TRUE);
        $this->db_sdk = $this->load->database('sdk', TRUE);

        $this->appid = $appid;
    }

    /**
     * 道具管理
     *
     * @param $item_name string 道具名称
     * @param $item_id int 道具类型
     * @param $gain_way int 道具获取途径
     *
     * @retuen bool
     */
    public function add_props($item_name, $item_id, $gain_way)
    {
        $data = [
            'name'          => $item_name,
            'type'          => $item_id,
            'gain_way'      => $gain_way,
            'created_at'    => $_SERVER['REQUEST_TIME'],
        ];
        return $this->db1->insert($data, 's_props');
    }

    /**
     * 道具类型配置
     *
     * @param $item_name string 类型名称
     * @return $insert_id int|bool
     */
    public function add_prop_types( $item_name )
    {
        $data = [
            'name'          => $item_name,
            'appid'         => $this->appid,
            'created_at'    => $_SERVER['REQUEST_TIME'],
        ];
        $insert_id = $this->db1->insert($data, 's_prop_types');
        return $insert_id;
    }

    /**
     *
     * 道具获取路径配置
     *
     * @param $item_name
     * @return mixed
     */
    public function add_prop_way( $item_name )
    {
        $data = [
            'name'          => $item_name,
            'appid'         => $this->appid,
            'created_at'    => $_SERVER['REQUEST_TIME'],
        ];
        $insert_id = $this->db1->insert($data, 's_prop_way');
        return $insert_id;
    }

    /**
     * 每日获取的道具统计
     *
     * @return mixed
     */
    public function gain_every_day()
    {
        $sql = <<<SQL
      SELECT SUM(amounts) AS amounts,serverid,channel,props_id FROM u_props
      WHERE appid=? AND created_at BETWEEN ? AND ?
      GROUP BY props_id,serverid,channel
      ORDER BY null
SQL;
        $query = $this->db->query($sql);
        $ret = $query->result_array();
        $this->save($ret, 'props_get');
        return $ret;
    }

    /**
     * 每日消耗
     *
     * @return mixed
     */
    public function use_every_day()
    {
        $sql = <<<SQL
      SELECT SUM(amounts) AS amounts,serverid,channel,props_id FROM u_props_used
      WHERE appid=? AND created_at BETWEEN ? AND ?
      GROUP BY props_id,serverid,channel
      ORDER BY null
SQL;
        $query = $this->db->query($sql);
        $ret = $query->result_array();
        $this->save($ret, 'props_use');
        return $ret;
    }

    /**
     * 保存数据
     *
     * @param $data
     * @param $col
     * @return mixed
     */
    private function save($data, $col)
    {
        $sql = <<<SQL
INSERT INTO sum_props_analysis(serverid,channel,appid,`$col`,sday) VALUES(%REPLACE%)
ON DUPLICATE KEY UPDATE `$col`=VALUES($col)
SQL;
        $str  = '';
        foreach ($data as $row) {
            $str .= "({$row['serverid']}, {$row['channel']}, $this->appid, {$row['emoney']}, {$this->sday}),";
        }
        $str = rtrim($str, ',');
        $sql = str_replace('%REPLACE%', $str, $sql);
        return $this->db->query($sql);
    }

    /**
     * 每日获得的数量-每日消耗的数量
     *
     * @return bool
     */
    public function count_left_props()
    {

        return false;
    }
}