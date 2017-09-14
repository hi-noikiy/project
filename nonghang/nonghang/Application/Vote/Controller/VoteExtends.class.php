<?php
namespace Vote\Controller;
use Think\Controller;

class VoteExtends extends Controller {
    
    protected $cachePre = 'test_';
    protected $programConfig = '';
    protected $cacheTime = 0;
	public function _initialize(){

        
        if(strtolower(CONTROLLER_NAME) == 'index'){
            $cachePre = S('cachePre');
            if (empty($cachePre)) {
                $cachePre = date('YmdHis') . rand(1000,9999);
                S('cachePre', $cachePre);
            }
            $this->cachePre = S('cachePre'); 
        }else{
           $this->cachePre = S('cachePre'); 
        }
        
        $this->cacheTime = 3600*24*7;
        $this->programConfig[1] = array('type'=>'舞蹈', 'store' => '红星店', 'name' =>'《boom clap》');
        $this->programConfig[2] = array('type'=>'快板书', 'store' => '南华店', 'name' =>'《中瑞》');
        $this->programConfig[3] = array('type'=>'创意舞蹈', 'store' => '市场部', 'name' =>'《光阴的故事》');
        $this->programConfig[4] = array('type'=>'搞笑朗诵', 'store' => '办公室', 'name' =>'《生活》');
        $this->programConfig[5] = array('type'=>'小品', 'store' => '市场部', 'name' =>'《富翁的烦恼》');
        $this->programConfig[6] = array('type'=>'小品', 'store' => '最美', 'name' =>'《最美的一天》');
        $this->programConfig[7] = array('type'=>'舞蹈', 'store' => '省体店', 'name' =>'《大王叫我来巡山》');
        $this->programConfig[8] = array('type'=>'大合唱', 'store' => '南华店', 'name' =>'《黄河大合唱》');
        $this->programConfig[9] = array('type'=>'手语舞', 'store' => '仓山店', 'name' =>'《步步高》');
        $this->programConfig[10] = array('type' => '舞台剧','store' => '福清店', 'name' =>'《不知道》');
        $this->programConfig[11] = array('type' => '爵士舞','store' => '天影', 'name' =>'《舞娘》');
        $this->programConfig[12] = array('type' => '歌曲','store' => '最美', 'name' =>'《经典影视歌曲串烧》');
        $this->programConfig[13] = array('type' => '魔术','store' => '仓山店', 'name' =>'《火盆出鸽》');
        $this->programConfig[14] = array('type' => '视频','store' => '红星店', 'name' =>'《巡店记》');
        $this->programConfig[15] = array('type' => '唱歌','store' => '天影', 'name' =>'《江南》');
        $this->programConfig[16] = array('type' => '小品','store' => '财务部', 'name' =>'《纳新》');
        $this->programConfig[17] = array('type' => '小品','store' => '省体店', 'name' =>'《中瑞好声音第一季》');
        $this->programConfig[18] = array('type' => '大合唱','store' => '仓山店', 'name' =>'《团结就是力量》');
    }
   
}