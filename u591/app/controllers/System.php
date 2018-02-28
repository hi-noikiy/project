<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 15/12/21
 * Time: 22:39
 */
include 'MY_Controller.php';
//ini_set('display_errors', 'On');
class System extends MY_Controller
{
    /**
     * @var $System_model System_model
     */
    public $System_model;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
    }

    public function create_game()
    {
        $this->form_validation->set_rules('name','游戏名称', 'required');

        if ($this->form_validation->run()===FALSE)
        {
            $this->data['message'] = '';
            $this->data['game_name'] = array(
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->body = 'system/create_game';
            $this->page_name = '添加新游戏';
            $this->layout();
        }
        else {
            $this->System_model->add_game($this->input->post('name'));
            redirect('system/games');
        }
    }

    public function game($game_id)
    {
        $this->page_name = '游戏详情';

        $this->data['game'] = $this->System_model->game($game_id);
        $this->body = 'system/game';
        $this->layout();
    }

    public function games()
    {
        $this->data['games'] = $this->System_model->get_games();
        $this->body = 'system/games';
//        print_r($this->data);
        //exit;
        $this->layout();
    }

    public function get_menus($group_id = 0)
    {
        if (!$this->appid) {
            $this->appid = 10002;
        }
        $data = $this->System_model->get_sys_menu($this->appid);
        $menu = [];
        foreach ($data as $item) {
            $menu[$item['id']]['title']         = $item['title'];
            $menu[$item['id']]['id']            = $item['id'];
            $menu[$item['id']]['controller']    = $item['controller'];
            $menu[$item['id']]['sub_menus'][]   = [ 'id'=>$item['sub_id'], 'title'=>$item['sub_title'], 'controller'=>$item['sub_controller'], ];
        }
        $this->data['my_menus'] = $menu;

        if ($group_id>0) {
            $group = $this->ion_auth->group($group_id)->row();
            $this->data['submit_btn'] = true;
            $this->data['saved_channel'] =  explode(',', $group->channel);
            $this->data['saved_serverids'] = explode(',', $group->serverids);
            $this->data['saved_appid']   = $group->appid;
            $this->data['saved_menus']   = $group->permissions!='' ? explode(',', $group->permissions) : [];
        }
        else {
            $this->data['submit_btn'] = false;
            $this->data['saved_menus'] = [];
        }
        $this->body = 'system/menu';
        $this->layout();

    }
    public function save_menus()
    {
        $menus_config = include  APPPATH .'/config/menu.php';
        $menus        = $menus_config[$this->appid];
        $this->System_model->save_menu($this->appid, $menus);
    }

    /**
     * 权限配置
     */
    public function edit_permission($group_id)
    {
        $this->data['game_list'] = $this->System_model->get_games();
        if (isset($_POST) && !empty($_POST)) {
            sort($_POST['sub_menus']);
            $menus = $_POST['sub_menus'];
            $appid = $_POST['appid'] + 0;
            $channel = implode(',', $_POST['channel']);
            
         
            $serverids = implode(',', $_POST['serverid']);
            $res = $this->System_model->update_permissions($group_id, $menus, $appid, $channel,$serverids);
        }
        $this->get_menus($group_id);
    }

}