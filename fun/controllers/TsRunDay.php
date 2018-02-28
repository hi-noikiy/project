<?php
/**
 * Created by PhpStorm.
 * User: fusha
 * Date: 16-2-29
 * Time: 下午10:07
 *
 * 每日凌晨自动统计程序
 */
set_time_limit(3000);
ini_set('memory_limit', '1024M');
ini_set('display_errors', 'On');
class TsRunDay extends CI_Controller{

    public function __construct()
    {
        parent::__construct();

    }
   
    /**
     * cli模式运行
     *
     * 最近三天都有登录的玩家
     */
    public function send3login()
    {
        $dbsdk = $this->load->database('sdk', true);
        $date = date('Ymd');
        $sql = "SELECT DISTINCT a.mac,$date as logdate FROM `u_login_".date('Ymd',strtotime('-1 day'))."` a INNER JOIN u_login_".date('Ymd',strtotime('-2 day'))." b on a.mac=b.mac
        		 INNER JOIN u_login_".date('Ymd',strtotime('-3 day'))." c on b.mac=c.mac";
        $query = $dbsdk->query($sql);
        if($query){
        	$data = $query->result_array();
        	if($data){
        		$this->insert_batch('send3_login',$data);
        	}
        }
        unset($dbsdk);
    }
    
    /**
     * cli模式运行
     *
     * 新玩家隔天推送功能
     */
    public function send2login()
    {
    	$dbsdk = $this->load->database('sdk', true);
    	$date = date('Ymd');
    	$btime = strtotime(date('Ymd',strtotime('-7 day')));
    	$etime = strtotime(date('Ymd',strtotime('-1 day')))-1;
    	$edate = date('Ymd',strtotime('-1 day'));
    	$sql = "SELECT last_login_mac as mac,$date as logdate FROM `push_regid` where role_create_time between $btime and $etime and logdate<$edate";
    	$query = $dbsdk->query($sql);
    	if($query){
    		$data = $query->result_array();
    		if($data){
    			$this->insert_batch('send2_login',$data);
    		}
    	}
    	unset($dbsdk);
    }
    
    /**
     * cli模式运行
     *
     * 超过三天没有登录的玩家
     */
    public function send3nologin()
    {
    	$dbsdk = $this->load->database('sdk', true);
    	$date = date('Ymd');
    	$lastday = date('Ymd',strtotime('-3 day'));
    	$sql = "SELECT last_login_mac as mac,$date as logdate FROM `push_regid` where logdate<$lastday";
    	$query = $dbsdk->query($sql);
    	if($query){
    		$data = $query->result_array();
    		if($data){
    			$this->insert_batch('send3_nologin',$data);
    		}
    	}
    	unset($dbsdk);
    }
    
    

}
