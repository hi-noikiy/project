<?php
// +----------------------------------------------------------------------
// | 系统基础控制器
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
// +----------------------------------------------------------------------

namespace Interfaces\Controller;
use Think\Controller;
class InterfacesExtends extends Controller {

	protected $param = '';
	protected $startTime = '';
    protected $cacheName = '';
    protected $userInfo = '';
    protected $payConfig = '';
    protected $cinemaGroupInfo = '';
    protected $appInfo = '';
    protected $pageNum = 10;
    protected $orderlog = 'other/order';
    /**
     * 系统基础控制器初始化
     */
    protected function _initialize(){
    	$this->startTime = microtime(true);
        $this->param = I('request.');

        $endParam['param'] = $this->param;
        $endParam['startTime'] = $this->startTime;
        $endParam['actionName'] = strtolower(MODULE_NAME . '_' . CONTROLLER_NAME . '_' . ACTION_NAME);
        \Think\Hook::listen('app_end', $endParam); 
        
        
        if(!in_array(strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME) , array('interfaces/service/gettoken'))){
            if(!checkToken($this->param)){
                $this->error('', '10001');//令牌失效！
            }
        }
        session('tokenId', $this->param['tokenId']);
        $this->appInfo = S('APPINFOUserInfotokenId_' . $this->param['tokenId']);

        $this->cacheName = strtolower(MODULE_NAME.'_'.CONTROLLER_NAME.'_'.ACTION_NAME);
        $paramValue = getCacheName($this->param);

        $this->cacheName .= str_replace($this->param['tokenId'], '', $paramValue);


        $cacheValue = S($this->cacheName);
        // if($cacheValue){
        //     $apiAllCacheName = S(C('CACHE_NAME_LIST'));
        //     $cacheInfo = $apiAllCacheName[$this->cacheName];
        //     $cacheValue['timeOut'] =$cacheInfo['expiration'] - (time() - strtotime($cacheInfo['createTime']));
        //     die(str_replace(':null', ':""', json_encode($cacheValue)));
        // }

        
    }

    // 10001:令牌失效
    // 11001:参数错误
    /**
    * 成功输出信息
    * @param  
    * @return null
    * @author 
    */
    public function success($text='', $successInfo='', $timeOut = 0, $cacheName = '')
    {
        $result = array(
            'status' => 0,
            'data' => $successInfo, 
            'text' => $text,
            'timeOut' => $timeOut,
        );
        $cacheName = $cacheName ? $cacheName : $this->cacheName;
        if($timeOut != 0){
            S('APPINFO' . $cacheName, $result, $timeOut);
        }
        // print_r($this->param);
        if (!empty($this->param['jsoncallback'])) {
            $strBing = $this->param['jsoncallback'] . '(';
            $strEnd = ')';
        }

        die($strBing . str_replace(':null', ':""', json_encode($result)) . $strEnd);
    }   

    /**
    * 成功输出信息
    * @param  
    * @return null
    * @author 
    */
    public function error($errorInfo='', $status = 1, $data = '')
    {
        $result = array(
            'status' => $status,
            'data' => $data, 
            'text' => $errorInfo,
            'timeOut' => 0,
        );


        if (!empty($this->param['jsoncallback'])) {
            $strBing = $this->param['jsoncallback'] . '(';
            $strEnd = ')';
        }
        die($strBing . str_replace(':null', ':""', json_encode($result)) . $strEnd);
    }
    
}