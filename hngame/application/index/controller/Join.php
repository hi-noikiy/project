<?php
namespace app\index\controller;

use think\Controller;

class Join extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function join($page=1,$num=5)
    {
        $Job = new \app\index\model\Job();
        $where['status'] = 1;
        $start=($page-1)*$num;
        $count = $Job->where($where)->count();
        $jobList = $Job->where($where)->order('sorts asc,create_time desc')->limit($start.', '.$num)->select();
        $this->assign('count', $count);
        $this->assign('pageNum', ceil($count/$num));
        $this->assign('nowPage', $page);
        $this->assign('prevPage', $page-1);
        $this->assign('nextPage', $page+1);
        $hasPrevPage=0;
        if($start>=$num){
            $hasPrevPage=1;
        }
        $hasNextPage=0;
        if($start<$count-$num){
            $hasNextPage=1;
        }
        $this->assign('hasPrevPage', $hasPrevPage);
        $this->assign('hasNextPage', $hasNextPage);
        $this->assign('jobList', $jobList);
        return $this->fetch();
    }
    
    public function jobInfo($id=1){
        $Job = new \app\index\model\Job();
        $where['id'] = $id;
        $jobInfo = $Job->where($where)->find();
        return json($jobInfo);
    }
}
