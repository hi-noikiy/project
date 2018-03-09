<?php
namespace app\pokemon\controller;

use think\Controller;

class News extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function news($channel='pc',$typeId=0,$page=1,$num=5)
    {
        // 新闻类型
        $newsType = new \app\pokemon\model\VsNewsType();
        $twhere['lang'] = _LATER;
        $twhere['status'] = 1;
        $newsTypeList = $newsType->where($twhere)->order('sorts asc')->select();
        $this->assign('newsTypeList', $newsTypeList);

        // 新闻
        $news = new \app\pokemon\model\VsNews();
        $where['status'] = 1;
        $where['lang'] = _LATER;
        if(checkMobile()){
            if($typeId!=0){
                $where['type_id'] = $typeId;
            }
            $start=($page-1)*$num;
            $count = $news->where($where)->count();
            $newsList = $news->where($where)->order('create_time desc')->field('id,type_id,title,description,image_url,create_time')->limit($start.', '.$num)->select();
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
        }
        $this->assign('typeId', $typeId);
		
        if(checkMobile()){
        	$html = 'news_mobile';
        }else{
        	$html = 'news';
        }
        $html .= _LATERS;
        return $this->fetch($html);
    }

    public function newsAjax($typeId=0,$page=1,$num=5)
    {
        // 新闻
        $news = new \app\pokemon\model\VsNews();
        $where['status'] = 1;
        $where['lang'] = _LATER;
        if($typeId!=0){
            $where['type_id'] = $typeId;
        }
        $start=($page-1)*$num;
        $count = $news->where($where)->count();
        $newsList = $news->where($where)->order('create_time desc')->field('id,type_id,title,description,image_url,create_time')->limit($start.', '.$num)->select();
        $hasPrevPage=0;
        if($start>=$num){
            $hasPrevPage=1;
        }
        $hasNextPage=0;
        if($start<$count-$num){
            $hasNextPage=1;
        }
        $arr = array(
        'hasPrevPage' => $hasPrevPage,
        'hasNextPage' => $hasNextPage,
        'newsList' => $newsList);    
        return json($arr);
    }
    
    public function detail($channel='pc',$id){
        $news = new \app\pokemon\model\VsNews();
        $data = $news->get($id);
        $data['click']=$data['click']+1;
        $news->where('id', $id)->update(['click' => $data['click']]);
        $this->assign('data', $data);
		
		if(checkMobile()){
       		$html = 'detail_mobile';
        }else{
        	$html = 'detail';
        }
        $html .= _LATERS;
        return $this->fetch($html);
    }

    public function thumbUp($id,$thumbUp=0){
        $news = new \app\pokemon\model\VsNews();
        if($thumbUp==0){
            $updateA=['thumb_up' => ['exp','thumb_up-1']];
        }else{
            $updateA=['thumb_up' => ['exp','thumb_up+1']];
        }
        $data = $news->where('id', $id)->update($updateA);
        return json($data);
    }
}
