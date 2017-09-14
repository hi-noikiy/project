<?php
namespace Vote\Controller;
use Think\Controller;
class PhoneController extends VoteExtends {
    public function index($id){
        $nowVote = (int)S($this->cachePre . 'vote' . $id);
        if (IS_AJAX) {

            $nowCookie = cookie($this->cachePre . 'cache' . $id);
            $stopVoteFlag = S('stopVoteFlag');
            if(!$nowCookie && !$stopVoteFlag){ 
                cookie($this->cachePre . 'cache' . $id, true, $this->cacheTime);
                S($this->cachePre . 'vote' . $id, $nowVote+1);
                $data['status'] = 0;
                $data['content'] = '投票成功';
                $data['data'] = $nowVote+1;
            }else{
                $data['status'] = 1;
                $data['content'] = $stopVoteFlag ? '投票失败，当前投票已终止！':'投票失败，您已投过票！';
                $data['data'] = $nowVote+1;
            }

            die(json_encode($data));
        }else{
            
            $this->assign('diabled',cookie($this->cachePre . 'cache' . $id) ? 'zanBg2' : 'zanBg1');
            
            $this->assign('nowVote',$nowVote);
            $this->assign('id',$id);
            $this->assign('programConfig',$this->programConfig[$id]);
        	$this->display(); 
        }  
    }

    public function allVote()
    {   $stopVoteFlag = S('stopVoteFlag');
        foreach ($this->programConfig as $key => $value) {
            $this->programConfig[$key]['vote'] = (int)S($this->cachePre . 'vote' . $key);
            $this->programConfig[$key]['id'] = $key;
            $this->programConfig[$key]['diabled'] = cookie($this->cachePre . 'cache' . $key) ? 'diabled' : '';
            if ($stopVoteFlag) {
                $this->programConfig[$key]['diabled'] = 'stop';
            }
        }
        if (IS_AJAX) {
            $data['status'] = 0;
            $data['content'] = '获取成功';
            $data['data'] = $this->programConfig;
            die(json_encode($data));
        }else{
            $this->assign('programConfig',$this->programConfig);
            $this->display();  
        }

    }
}