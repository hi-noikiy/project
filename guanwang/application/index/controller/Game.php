<?php
namespace app\index\controller;

use think\Controller;

class Game extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function game()
    {
        $game = new \app\index\model\Game();
        $where['status'] = 1;
        $where['lang'] = '';
        $gameList = $game->where($where)->order('sorts asc')->select();
        $this->assign('gameList', $gameList);
        if($result = checkMobile()){
        	$html = 'game_mobile';
        }else{
        	$html = 'game';
        }
        return $this->fetch($html);
    }
    
}
