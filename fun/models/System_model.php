<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 15/12/21
 * Time: 22:04
 */
class System_model extends CI_Model
{
    const TABLE_GAME = 'auth_config';
    public function __construct()
    {
//        parent::__construct();
        $this->load->database();
        $this->load->library('pagination');
    }

    public function add_game($game_name)
    {
        $existing_group = $this->db->get_where(self::TABLE_GAME, array('name' => $game_name))->num_rows();
        if($existing_group !== 0)
        {
            $this->set_error('game_already_exists');
            return FALSE;
        }
        $data = array('name'=>$game_name,'created_at'=>date('Y-m-d H:i:s'));
        $this->db->insert(self::TABLE_GAME, $data);
        $this->load->model('access_token_model');
        $insert_id = $this->db->insert_id();
        $this->access_token_model->create_auth($insert_id);
        return $insert_id;
    }
    public function get_games()
    {
        return $this->db->get(self::TABLE_GAME)->result_array();
    }
    public function game($id = NULL)
    {
        $query = $this->db->get_where(self::TABLE_GAME, ['id'=>$id])->result_array();
        if ($query) {
            $this->load->model('access_token_model');
            $data = $this->access_token_model->get_info($id);
        }
        return array_merge(array_shift($query), array_shift($data));
    }

    public function get_sys_menu($appid)
    {
        $sql = <<<SQL
SELECT g.id,g.title,g.controller,s.id as sub_id,s.title as sub_title,s.controller as sub_controller
FROM sys_menus s LEFT JOIN sys_menus_grp g ON g.id=s.parent_id where appid=$appid
SQL;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function save_menu($appid, $menus)
    {
        //print_r($menus);
        foreach ($menus as $menu) {
            $menu['appid'] = $appid;
            $sub_menus = $menu['menus'];
            unset($menu['menus']);
            $this->db->insert('sys_menus_grp', $menu);
            $parent_id = $this->db->insert_id();
            if ($parent_id) {
                foreach ($sub_menus as $item) {
                    $item['parent_id'] = $parent_id;
                    $this->db->insert('sys_menus', $item);
                }
            }
        }
    }

    /**
     * 更新用户组权限
     *
     * @param $group_id
     * @param $menus
     * @param $appid
     * @param $channel
     * @return mixed
     */
    public function update_permissions($group_id,  $menus, $appid=0, $channel=0 , $serverids='')
    {
        $menu = is_array($menus) ? implode(',', $menus) : $menus;
        $res = $this->db->update('groups', ['permissions'=>$menu,'appid'=>$appid, 'channel'=>$channel,'serverids'=>$serverids], ['id'=>$group_id]);
        if ($res===false){
            echo "更新失败！";
            print_r($this->db->error());
            exit;
        }
        return $res;
    }

}