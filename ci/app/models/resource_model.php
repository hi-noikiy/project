<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/1/19
 * Time: 20:08
 *
 * 资源模型
 */
class resource_model extends CI_Model
{
    public function __construct($appid,$bt, $et)
    {
        parent::__construct();
        $this->db1    = $this->load->database('default', TRUE);
        $this->db_sdk = $this->load->database('sdk', TRUE);
//        $this->bt = $bt;
//        $this->et = $et;
        $this->appid = $appid;
    }

    /**
     * 添加物品
     *
     * @param $item_name string 商品名称
     * @param $item_id int 商品类型
     *
     * @retuen bool
     */
    public function add_items($item_name, $item_id)
    {
        $data = [
            'item_name' => $item_name,
            'item_id'   => $item_id,
            'created_at'=> $_SERVER['REQUEST_TIME'],
        ];
        return $this->db1->insert($data, 's_items');
    }

    /**
     * 添加商品类型
     *
     * @param $item_name string 类型名称
     * @return $insert_id int|bool
     */
    public function add_item_types( $item_name )
    {
        $data = [
            'type_name'     => $item_name,
            'appid'         => $this->appid,
            'created_at'    => $_SERVER['REQUEST_TIME'],
        ];
        $insert_id = $this->db1->insert($data, 's_item_types');
        return $insert_id;
    }









}