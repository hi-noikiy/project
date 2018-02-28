<?php
namespace app\index\controller;

use think\Controller;

class Index extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function index()
    {
        $game = new \app\index\model\Game();
        $gwhere['status'] = 1;
        $gwhere['lang'] = '';
        $gwhere['recommend'] = ['in', '1,2'];
        $gameList = $game->where($gwhere)->order('sorts asc')->select();
        $this->assign('gameList', $gameList);
        return $this->fetch();
    }
    
}
