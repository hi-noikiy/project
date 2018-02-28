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
                	if($data['status'] == '1'){
                		$this->setNews();
                	}
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
            	if($data['status'] == '1'){
            		$this->setNews();
            	}
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
    private function setNews()
    {
    	$mdString = 'fu;djf,jk7g.fk*o3l';
    	$time = time();
    	$sign = md5($time.$mdString);
    	$url = "http://fhweb.u776.com:86/interface/guanwang/flushnews.php?time=$time&sign=$sign";
    	$this->https_post($url);
    }
    private function https_post($url, $data=array(), $i = 0) {
    	$i++;
    	$str = '';
    	if ($data) {
    		foreach ( $data as $key => $value ) {
    			$str .= $key . "=" . $value . "&";
    		}
    	}
    	$curl = curl_init ( $url ); // 启动一个CURL会话
    	curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
    	curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 2 ); // 从证书中检查SSL加密算法是否存在
    	// curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    	curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
    	curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
    	if ($str) {
    		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $str ); // Post提交的数据包
    	}
    	curl_setopt ( $curl, CURLOPT_TIMEOUT, 5 ); // 设置超时限制防止死循环
    	// curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    	curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
    	// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    	// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    	// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	$tmpInfo = curl_exec ($curl);
    	$Err = curl_error($curl);

    	if (false === $tmpInfo || !empty($Err)) {
    		if($i == 1)
    			return https_post ($url, $data, $i);
    		curl_close ($curl);
    		return $Err;
    	}
    	curl_close ($curl);
    	return $tmpInfo;
    }
    
}