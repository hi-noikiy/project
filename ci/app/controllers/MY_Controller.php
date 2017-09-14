<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 15/12/19
 * Time: 11:40
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller
{
    //set the class variable.
    public $template  = array();
    public $data      = array();
    public $userData  = array();
    public $group_info = array();
    public $appid  = '';
    /**
     * @var $System_model System_model
     */
    public $System_model;
    /*Loading the default libraries, helper, language */
    public function __construct(){
        parent::__construct();

        //$this->load->database();
        //$this->db1    = $this->load->database('default', TRUE);
        //$this->db_sdk = $this->load->database('sdk', TRUE);
        $this->load->helper(array('form','language','url'));
        $this->load->library(array('ion_auth','form_validation','session'));
        $this->load->model('System_model');

        //
        $request_method = $this->router->fetch_method();
        //
        if ($request_method!='login' && !$this->ion_auth->logged_in())  {
            redirect('Auth/login', 'refresh');
        }
        if ($this->input->get('appid')) {
            $this->session->set_userdata(['appid'=>$this->input->get('appid')]);
            $this->appid = $this->input->get('appid');
        }
        else {
            //设置游戏的appid
            $this->appid = $this->session->userdata('appid');
            if(!$this->appid){
            	$this->appid = $this->getAppid()[0]['appid'];
            }
        }
        //echo $this->appid;exit;
        $this->userData = $this->ion_auth->user()->row();
        $this->data['bt'] = isset($this->bt) ? $this->bt : date('Y-m-d', strtotime('-7 days'));
        $this->data['et'] = isset($this->et) ? $this->et : date('Y-m-d', strtotime('-1 days'));
        $this->data['btserver'] = isset($this->btserver) ? $this->btserver : 20170132;
        $this->data['etserver'] = isset($this->etserver) ? $this->etserver : 20170132;        
        
        $this->data['_controller'] = $this->router->uri->segments[1];
        $this->data['_request_method'] = $this->router->uri->uri_string;

        $global_channel = include  APPPATH .'/config/channel_list.php'; //渠道字典
        $global_server  = include  APPPATH .'/config/server_list.php'; //服务器字典
        $this->data['server_list']  = $global_server;
        $this->data['big_server_list']  = include  APPPATH .'/config/big_server_list.php'; //服务器字典
        if (!$this->ion_auth->is_admin()) {
            //获取用户所在分组
            $groupData = $this->ion_auth->get_users_groups($this->userData->id)->row();
            $this->group_info = $this->ion_auth->group($groupData->id)->row();
            $this->data['menus']        = $this->my_menus( $this->group_info);
            $this->data['channel_list'] = [$this->group_info->channel=>$global_channel[$this->group_info->channel]];
            if($this->group_info->serverids){
            	$this->data['big_server_list'] = '';
            	$serverids = explode(',', $this->group_info->serverids);
            	$sarray = array();
            	foreach ($serverids as $v){
            		$sarray[$v] = $global_server[$v]?$global_server[$v]:$v;
            	}
            	$this->data['server_list'] = $sarray;
            	$this->data['all_server_list'] = $sarray;
            }
        }
        else {
            $menus_config = include  APPPATH .'/config/menu.php';
            $this->data['menus']        = $menus_config[$this->appid];
            $this->data['channel_list'] = $global_channel;
        }
        $res = self::SearchRequestTitle($this->data['menus'], $this->data['_controller'], $this->data['_request_method']);
        $this->data['page_title']   = "{$res['first_title']}>{$res['second_title']}";
        
    }
    private static function SearchRequestTitle($menus_config, $controller, $method)
    {
        foreach ($menus_config as $menu)  {
            if ($menu['controller']==$controller) {
                foreach($menu['menus'] as $item) {
                    if ($item['controller'] == $method) {
                        return ['first_title'=>$menu['title'], 'second_title'=>$item['title']];
                    }
                }
            }
        }
        return '';
    }
    private function getAppid()
    {
    	$this->load->database();
    	$query = $this->db->query('SELECT appid FROM auth_config');
    	if ($query) return $query->result_array();
    	return [];
    }
    /**
     * 获取菜单信息
     *
     * @param Object $group 分组对象
     * @return array
     */
    private function my_menus($group)
    {
        $data = $this->System_model->get_sys_menu($this->appid);
        $menu = [];
        $my_menus = explode(',', $group->permissions);
        //print_r($my_menus);
        foreach ($data as $item) {
            if (!in_array($item['sub_id'], $my_menus)) continue;
            $menu[$item['id']]['title']         = $item['title'];
            $menu[$item['id']]['id']            = $item['id'];
            $menu[$item['id']]['controller']    = $item['controller'];
            $menu[$item['id']]['menus'][]   = [ 'id'=>$item['sub_id'], 'title'=>$item['sub_title'], 'controller'=>$item['sub_controller'], ];
        }
        ksort($menu);
        return $menu;
    }
    protected function setLayOutParam($key, $val)
    {
        $this->data[$key] = $val;
        return $this->data[$key];
    }
    /*Front Page Layout*/
    public function layout() {
        //active
        //$request_method = $this->router->fetch_method();
        //var_dump($this->router->uri);
        //print_r($request_method);
        //print_r($this->router->uri->segments);
        //print_r($this->router->uri->uri_string);
        //exit;

        //print_r($this->data);
        //exit;
        $this->template['left']   = $this->load->view('layout/left', $this->data, true);
        $this->data['search_form']   = $this->load->view('layout/search_form', $this->data, true);
        $this->data['search_form_server']   = $this->load->view('layout/search_form_server', $this->data, true);
        $this->data['search_form_web']   = $this->load->view('layout/search_form_web', $this->data, true);
        if (!empty($this->body)) {
            $this->template['body']   = $this->load->view($this->body, $this->data, true);
        };
        $this->load->view('layout/front', $this->template);
    }

    public static function isAjax()
    {
        $r = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';
       return $r === 'xmlhttprequest';
    }
}
