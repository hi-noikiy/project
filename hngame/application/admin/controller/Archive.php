<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Archive as Archives;
use app\admin\model\Arctype;
use think\Db;

class Archive extends Common
{
    private $cModel;   //当前控制器关联模型
    
    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new Archives;   //别名：避免与控制名冲突
    }
    
    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where['title|keywords|description'] = ['like', '%'.input('get.search').'%'];
        }
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'id desc';
        }
        $dataList = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        foreach ($dataList as $k => $v){
            if(!empty($v['flag'])){ $dataList[$k]['flag'] = explode(',', $v['flag']); }
            $v->Arctype;   //关联栏目数据
            $v->User;
            if(in_array('j', $dataList[$k]['flag']) && !empty($v['jumplink'])){
                $dataList[$k]['arcurl'] = $v['jumplink'];
            }else{
                if(isset($v->Arctype->jumplink)){
                    $dataList[$k]['arcurl'] = $v->Arctype->jumplink.'?id='.$v['id'];
                }else{
                    $dataList[$k]['arcurl'] = '';
                }
            }
        }
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    
    public function create($typeid)
    {
        if (request()->isPost()){
            try{
                $data = input('post.');
                $data['create_time'] = strtotime($data['create_time']);
                if (isset($data['flag']) || isset($data['litpic'])){
                    $data['flag'] = $this->_flag($data['flag'], $data['litpic']);
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);
                // 提交事务
                if ($result){
                    return ajaxReturn(lang('action_success'), url('index'));
                }else{
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) {
                // 回滚事务
                return ajaxReturn($e->getMessage());
            }
        }else{
            $atModel = new Arctype();
            $arctypeList = $atModel->treeList();
            $this->assign('arctypeList', $arctypeList);
            
            $arcData = $atModel->where(['id' => $typeid])->find();   //栏目数据
            $data['typeid'] = $arcData['id'];
            $data['create_time'] = date('Y-m-d H:i:s', time());
            $this->assign('data', $data);
            return $this->fetch('edit');
        }
    }
    
    public function edit($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            if (isset($data['create_time'])){
                $data['create_time'] = strtotime($data['create_time']);
            }
            if (isset($data['flag']) || isset($data['litpic'])){
                $data['flag'] = $this->_flag($data['flag'], $data['litpic']);
            }
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
            $atModel = new Arctype();
            $arctypeList = $atModel->treeList();
            $this->assign('arctypeList', $arctypeList);
            
            $data = $this->cModel->get($id);
            if (!empty($data['flag'])){
                $data['flag'] = explode(',', $data['flag']);
            }
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
                if (!empty($id_arr)){
                    foreach ($id_arr as $val){
                        $this->cModel->where('id='.$val)->delete();
                    }
                    return ajaxReturn(lang('action_success'), url('index'));
                }else{
                    return ajaxReturn(lang('action_fail'));
                }
            }
        }
    }
    
    private function _flag($flag, $litpic)
    {
        if(empty($flag)){ $flag=array(); }
        if($litpic != ''){
            array_push($flag, "p");
        }else{
            $flag = unset_array("p", $flag);
        }
        $flag_arr = array_unique($flag);
        $result = implode(',', $flag_arr );
        return $result;
    }
}