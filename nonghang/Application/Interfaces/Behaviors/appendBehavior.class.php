<?php
namespace Api\Behaviors;
class appendBehavior extends \Think\Behavior{
    //行为执行入口
    public function run(&$param){
    	$endTime = microtime(true);

    	if (($endTime - $param['startTime']) >= 0.1) {
    		wlog('执行接口【' . $param['actionName'] . '】共耗时' . ($endTime - $param['startTime']) . '毫秒', 'actionTime/LongTime');
    	}

    	wlog('执行接口【' . $param['actionName'] . '】共耗时' . ($endTime - $param['startTime']) . '毫秒', 'actionTime/' . $param['actionName']);
    }
}