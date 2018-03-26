<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Config as Configs;

class Config extends Common
{
    private $cModel;   //当前控制器关联模型
    
    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new Configs;   //别名：避免与控制名冲突
    }
    
    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where['key|value|desc|type'] = ['like', '%'.input('get.search').'%'];
        }
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'type asc,status desc,sorts asc,id asc';
        }
        $dataList = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    
    public function create()
    {
        if (request()->isPost()){
            $data = input('post.');
            $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);
            if ($result){
                return ajaxReturn(lang('action_success'), url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            return $this->fetch('edit');
        }
    }
    
    public function edit($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            if (count($data) == 2){
                foreach ($data as $k =>$v){
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            }else{
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if ($result){
                return ajaxReturn(lang('action_success'), url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            $data = $this->cModel->get($id);
            $this->assign('data', $data);
            return $this->fetch();
        }
    }
    
    public function delete()
    {
        if (request()->isPost()){
            $id = input('id');
            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);
                $where = [ 'id' => ['in', $id_arr] ];
                $result = $this->cModel->where($where)->delete();
                if ($result){
                    return ajaxReturn(lang('action_success'), url('index'));
                }else{
                    return ajaxReturn($this->cModel->getError());
                }
            }
        }
    }
    
    /**
     * 循环保存数据
     */
    public function save()
    {
        if (request()->isPost()){
            $data = input('post.');
            $type = $data['type'];   //取出类型
            unset($data['type']);
            if(!empty($type)){
                if(is_array($data) && !empty($data)){
                    foreach ($data as $k=>$val) {
                        $where = array('system' => $type, 'key'=>$k);
                        $this->cModel->where($where)->update(['value' => $val]);
                    }
                    return ajaxReturn(lang('action_success'), url('Config/'.$type));
                }else{
                    return ajaxReturn($this->cModel->getError());
                }
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }
    }
    
    /**
     * 站点配置
     */
    public function web()
    {
        $system = ACTION_NAME;
        $where = ['system' => $system];
        $data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
        $this->assign('data', $data);
        $this->assign('type', $system);
        return $this->fetch();
    }

    /**
     * 站点配置
     */
    public function pokemon()
    {
        $system = ACTION_NAME;
        $where = ['system' => $system];
        $data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
        $this->assign('data', $data);
        $this->assign('type', $system);
        return $this->fetch($type);
    }
    
    /**
     * 英文站点配置
     */
    public function pokemon_us()
    {
    	$system = ACTION_NAME;
    	$where = ['system' => $system];
    	$data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
    	$this->assign('data', $data);
    	$this->assign('type', $system);
    	return $this->fetch($type);
    }
    
    /**
     * 越南站点配置
     */
    public function pokemon_vn()
    {
    	$system = ACTION_NAME;
    	$where = ['system' => $system];
    	$data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
    	$this->assign('data', $data);
    	$this->assign('type', $system);
    	return $this->fetch($type);
    }
    
    /**
     * 俄罗斯站点配置
     */
    public function pokemon_ru()
    {
    	$system = ACTION_NAME;
    	$where = ['system' => $system];
    	$data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
    	$this->assign('data', $data);
    	$this->assign('type', $system);
    	return $this->fetch($type);
    }
	
	/**
     * 阿拉伯站点配置
     */
    public function pokemon_ar()
    {
    	$system = ACTION_NAME;
    	$where = ['system' => $system];
    	$data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
    	$this->assign('data', $data);
    	$this->assign('type', $system);
    	return $this->fetch($type);
    }
	
	/**
     * 魔兽官网站点配置
     */
    public function war()
    {
    	$system = ACTION_NAME;
    	$where = ['system' => $system];
    	$data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
    	$this->assign('data', $data);
    	$this->assign('type', $system);
    	return $this->fetch($type);
    }

    /**
     * 系统配置
     */
    public function system()
    {
        $system = ACTION_NAME;
        $where = ['system' => 'all'];
        $data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
        $this->assign('data', $data);
        $this->assign('type', $system);
        return $this->fetch('web');
    }
    
    /**
     * 上传配置
     */
    public function up()
    {
        $type = ACTION_NAME;
        $where = ['type' => $type];
        $data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
        $this->assign('data', $data);
        $this->assign('type', $type);
        return $this->fetch('web');
    }
    
    /**
     * 短信配置
     */
    public function sms()
    {
        $type = ACTION_NAME;
        $where = ['type' => $type];
        $data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
        $this->assign('data', $data);
        $this->assign('type', $type);
        return $this->fetch('web');
    }
}