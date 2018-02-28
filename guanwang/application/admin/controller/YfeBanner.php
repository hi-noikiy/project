<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Yfe as Yfe;

class YfeBanner extends Common
{
    private $cModel;   //当前控制器关联模型
    
    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new Yfe;   //别名：避免与控制名冲突
    }
    
	/**
     * 衣范儿首页轮播和头图
     */
    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where['name'] = ['like', '%'.input('get.search').'%'];
        }
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'create_time desc,id desc';
        }
		
		$where['type'] = array('in','1,2');
        $dataList = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    
	/**
     * 衣范儿首页轮播和头图-新增
     */
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
    
	/**
     * 衣范儿首页轮播和头图-编辑
     */
    public function edit($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            if($data['subType']!='form'){
                $result = $this->cModel->allowField(true)->save($data, $data['id']);
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
	
	/**
     * 衣范儿首页轮播和头图-删除
     */
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
     * 衣范儿首页轮播和头图-保存
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
     * 衣范儿视频底部文字
     */
	public function title()
    {
        $where = ['type' => '3'];
        $data = $this->cModel->where($where)->find();
        $this->assign('data', $data);
        return $this->fetch();
    }
	
	/**
     * 衣范儿视频底部文字-保存
     */
	public function savetitle()
    {
        if (request()->isPost()){
            $data = input('post.');
			$type = $data['type'];
            $title = $data['title'];
			$isFind = $this->cModel->where(['type' => '3'])->find();
            if(!empty($type)){
				if ($isFind['id']) {
					$resture = $this->cModel->where(['id' => $isFind['id']])->update(['title' => $title]);
				}else {
					$save = [
						'name' => '视频底部文字',
						'title' => $title,
						'type' => $type,
						'create_time' => time(),	
						'status' => '1',
						'sorts' => '1',
					];
					$resture = $this->cModel->allowField(true)->save($save);
				}
				if (isset($resture)) {
					return ajaxReturn(lang('action_success'), url('YfeBanner/title'));
				}else {
					return ajaxReturn($this->cModel->getError());
				}	
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }
    }
	
	/**
     * 衣范儿资讯内容配置页
     */
	public function information()
	{
		$where = [];
        if (input('get.search')){
            $where['name'] = ['like', '%'.input('get.search').'%'];
        }
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'create_time desc,id desc';
        }
		
		$where['type'] = array('in','4');
        $dataList = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->assign('dataList', $dataList);
        return $this->fetch();
		
	}
	
	/**
     * 衣范儿资讯内容配置页-新增
     */
	public function createinfo()
    {
        if (request()->isPost()){
            $data = input('post.');
            $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);
            if ($result){
                return ajaxReturn(lang('action_success'), url('information'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            return $this->fetch('editinfo');
        }
    }
	
	/**
     * 衣范儿资讯内容配置页-编辑
     */
	public function editinfo($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            if($data['subType']!='form'){
                $result = $this->cModel->allowField(true)->save($data, $data['id']);
            }else{
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if ($result){
                return ajaxReturn(lang('action_success'), url('information'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            $data = $this->cModel->get($id);
            $this->assign('data', $data);
            return $this->fetch();
        }
    }
	
	/**
     * 衣范儿资讯内容配置页-删除
     */
	public function deleteinfo()
    {
        if (request()->isPost()){
            $id = input('id');
            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);
                $where = [ 'id' => ['in', $id_arr] ];
                $result = $this->cModel->where($where)->delete();
                if ($result){
                    return ajaxReturn(lang('action_success'), url('information'));
                }else{
                    return ajaxReturn($this->cModel->getError());
                }
            }
        }
    }
	
	/**
     * 衣范儿资讯文字
     */
	public function infotitle()
    {
        $where = ['type' => '5'];
        $data = $this->cModel->where($where)->find();
        $this->assign('data', $data);
        return $this->fetch();
    }
	
	/**
     * 衣范儿资讯文字-保存
     */
	public function saveinfotitle()
    {
        if (request()->isPost()){
            $data = input('post.');
			$type = $data['type'];
            $title = $data['title'];
			$isFind = $this->cModel->where(['type' => '5'])->find();
            if(!empty($type)){
				if ($isFind['id']) {
					$resture = $this->cModel->where(['id' => $isFind['id']])->update(['title' => $title]);
				}else {
					$save = [
						'name' => '资讯文字',
						'title' => $title,
						'type' => $type,
						'create_time' => time(),	
						'status' => '1',
						'sorts' => '1',
					];
					$resture = $this->cModel->allowField(true)->save($save);
				}
				if (isset($resture)) {
					return ajaxReturn(lang('action_success'), url('YfeBanner/infotitle'));
				}else {
					return ajaxReturn($this->cModel->getError());
				}	
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }
    }
    
    /**
     * 衣范儿下载地址
     */
    public function download()
    {
    	$where = ['type' => '6'];
    	$data = $this->cModel->where($where)->paginate('', false, page_param());
    	$this->assign('data', $data);
    	return $this->fetch();
    }
    
    /**
     * 衣范儿下载地址-保存
     */
	public function downloadsavedit($id)
    {
    	$data = input('post.');
    	if($data['subType']!='form'){
    		$result = $this->cModel->allowField(true)->save($data, $data['id']);
    	}else{
    		$result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
    	}
    	if ($result){
    		return ajaxReturn(lang('action_success'), url('download'));
    	}else{
    		return ajaxReturn($this->cModel->getError());
    	}
    }
}