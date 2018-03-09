<?php
namespace app\pokemon\controller;

use think\Controller;

class Index extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function index()
    {
        // 横幅
        $banner = new \app\pokemon\model\VsBanner();
        $gwhere['status'] = 1;
        $gwhere['lang'] = _LATER;
        $bannerList = $banner->where($gwhere)->order('sorts asc')->select();
        $this->assign('bannerList', $bannerList);

        // 新闻类型
        $newsType = new \app\pokemon\model\VsNewsType();
        $twhere['status'] = 1;
        $twhere['lang'] = _LATER;
        $newsTypeList = $newsType->where($twhere)->order('sorts asc')->select();
        $this->assign('newsTypeList', $newsTypeList);

        // 新闻
        $news = new \app\pokemon\model\VsNews();
        $nwhere['status'] = 1;
        $nwhere['top'] = 0;
        $nwhere['lang'] = _LATER;
        $newsList = $news->where($nwhere)->order('create_time desc')->field('id,type_id,title,create_time')->select();
        foreach ($newsList as $k=>$v){
        	$newsList[$k]['ctime'] = substr($v['create_time'], 0,10);
        }
        $this->assign('newsList', $newsList);

        // 置顶新闻
        $vsNews = new \app\pokemon\model\VsNews();
        $pwhere['status'] = 1;
        $nwhere['lang'] = _LATER;
        $pwhere['top'] = 1;
        $topNews = $vsNews->where($pwhere)->find();
        foreach ($topNews as $k=>$v){
            $topNews[$k]['ctime'] = substr($v['create_time'], 0,10);
        }
        $this->assign('topNews', $topNews);


        // 外链
        $link = new \app\pokemon\model\VsLink();
        $lwhere['status'] = 1;
        $lwhere['lang'] = _LATER;
        $linkList = $link->where($lwhere)->order('sorts asc')->select();
        $this->assign('linkList', $linkList);
		
        if(checkMobile()){
        	$html = 'mobile';
        }else{
        	$html = 'index';
        }
        $html .= _LATERS;
		return $this->fetch($html); 
    }
    
}
