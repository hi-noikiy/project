<?php
namespace app\pokemon\controller;

use think\Controller;

class Activity extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function activity($channel='pc')
    {
        // 新闻类型
        $activity = new \app\pokemon\model\VsActivity();
        $twhere['status'] = 1;
        $twhere['lang'] = _LATER;
        $activityList = $activity->where($twhere)->order('create_time desc')->select();
        foreach ($activityList as $k => $v){
            if($v['end_time']<time()){
                $activityList[$k]['type']=2;
            }else{
                $activityList[$k]['type']=1;
            }
            $activityList[$k]['begin_time']=date('Y-m-d', $v['begin_time']);
            $activityList[$k]['end_time']=date('Y-m-d', $v['end_time']);
        }
        $this->assign('activityList', $activityList);
		
        if(checkMobile()){
       		$html = 'activity_mobile';
        }else{
        	$html = 'activity';
        }
        $html .= _LATERS;
        return $this->fetch($html);
    }
}
