<?php
namespace app\index\controller;

use think\Controller;

class News extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function news($page=1,$num=5)
    {
        $news = new \app\index\model\News();
        $where['status'] = 1;
        $start=($page-1)*$num;
        $count = $news->where($where)->count();
        $newsList = $news->where($where)->order('create_time desc')->limit($start.', '.$num)->select();
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
        $this->assign('newsList', $newsList);
        if($result = checkMobile()){
        	$html = 'news_mobile';
        }else{
        	$html = 'news';
        }
        return $this->fetch($html);
    }
    
}
