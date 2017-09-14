<?php
namespace Vote\Controller;
use Think\Controller;
class IndexController extends VoteExtends {
    public function index($id){

    	if (IS_AJAX) {
    		if ($id == 0) {
    			S('stopVoteFlag', true);
    			$data['status'] = 1;
	            $data['content'] = '停止投票';
	            $data['data'] = $nowVote;
	            die(json_encode($data));
    		}else{
    			$nowVote = (int)S($this->cachePre . 'vote' . $id);
    			$data['status'] = 0;
	            $data['content'] = '获取成功';
	            $data['data'] = $nowVote;
	            die(json_encode($data));
    		}
    	}else{
    		S('stopVoteFlag', false);
	    	$this->assign('nowVote',$nowVote);
	    	$this->assign('id',$id);
	    	$this->assign('programConfig',$this->programConfig[$id]);
	    	$this->display();	
    	}

    }

    public function all()
    {
    	foreach ($this->programConfig as $key => $value) {
    		$this->programConfig[$key]['vote'] = (int)S($this->cachePre . 'vote' . $key);
    		$this->programConfig[$key]['id'] = $key;
    		$arrayCount[] = (int)S($this->cachePre . 'vote' . $key);
    	}
    	array_multisort($arrayCount, SORT_DESC, $this->programConfig);
    	// print_r($this->programConfig);

    	if (IS_AJAX) {
            $data['status'] = 0;
            $data['content'] = '获取成功';
            $data['data'] = $this->programConfig;
            die(json_encode($data));
    	}else{
    		S('stopVoteFlag', false);
	    	$this->assign('nowVote',$nowVote);
	    	$this->assign('id',$id);
	    	$this->assign('programConfig',$this->programConfig);
	    	$this->display();   	
    	}
    }

    public function c($pwd)
    {
    	if ($pwd == '123') {
    		$cachePre = date('YmdHis') . rand(1000,9999);
            S('cachePre', $cachePre);
            echo '清除数据成功！';
    	}
    }

}