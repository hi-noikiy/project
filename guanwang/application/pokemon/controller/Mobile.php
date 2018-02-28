<?php
namespace app\pokemon\controller;

use think\Controller;

class Mobile extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function index()
    {
        // 横幅
        $banner = new \app\pokemon\model\VsBanner();
        $gwhere['status'] = 1;
        $bannerList = $banner->where($gwhere)->order('sorts asc')->select();
        $this->assign('bannerList', $bannerList);

        // 新闻类型
        $newsType = new \app\pokemon\model\VsNewsType();
        $twhere['status'] = 1;
        $newsTypeList = $newsType->where($twhere)->order('sorts asc')->select();
        $this->assign('newsTypeList', $newsTypeList);

        // 新闻
        $news = new \app\pokemon\model\VsNews();
        $nwhere['status'] = 1;
        $newsList = $news->where($nwhere)->order('create_time desc')->select();
        $this->assign('newsList', $newsList);

        // 外链
        $link = new \app\pokemon\model\VsLink();
        $lwhere['status'] = 1;
        $linkList = $link->where($lwhere)->order('sorts asc')->select();
        $this->assign('linkList', $linkList);

        // 游戏
        $game = new \app\index\model\Game();
        $where['name_en'] = 'Pokemon';
        $pokemon = $game->where($where)->find();
        $this->assign('pokemon', $pokemon);

		return $this->fetch(); 
    }
    
}
