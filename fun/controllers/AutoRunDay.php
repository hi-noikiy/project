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
class AutoRunDay extends CI_Controller{

    private $appids;
    public function __construct()
    {
        parent::__construct();
        $this->appids = $this->getAppid();

    }

    private function getAppid()
    {
        $this->load->database();
        $query = $this->db->query('SELECT appid FROM auth_config');
        if ($query) return $query->result_array();
        return [];
    }

    /**
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunDay run
     */
    public function run()
    {
        $dbsdk = $this->load->database('sdk', true);
        $dbsdk->query("update u_dayonline set online_date = FROM_UNIXTIME(created_at, '%Y%m%d')  where online_date=0");
        $dbsdk->query("update u_login set logindate = FROM_UNIXTIME(created_at, '%Y%m%d')  where logindate=0 OR isnull(logindate)");
        $dbsdk->query("update u_login_new set logindate = FROM_UNIXTIME(created_at, '%Y%m%d')  where logindate=0 OR isnull(logindate)");
        unset($dbsdk);

        parent::log('day running');
        //$this->UserRemain('10001');
        //$this->NewPlayerCal('10001');

        foreach ($this->appids as $_d) {
            $this->OnlineCal($_d['appid']);
            $this->DeviceCal($_d['appid']);
            sleep(1);
        }
    }

	public function summary($date1=''){
	      
	    	$date1 = $date1 ? $date1 : date('Ymd', strtotime('-1 days'));
	    	$date2 = $date1;
	    	
	    	$this->load->model('Summary_model');
	    	$res = $this->Summary_model->getData($this->appids[0]['appid'], $date1, $date2, $serveid, $channel);
	    	$data = [];
	    	foreach ($res['device'] as $item) {
	    		$data[$item['date']]['device'] = $item['cnt'];
	    	}
	    	foreach ($res['register'] as $item) {
	    		$data[$item['date']]['macregister'] = $item['cnt'];
	    	}
	    	foreach ($res['au'] as $item) {
	    		$data[$item['sday']]['role'] = $item['new_role'];
	    		$data[$item['sday']]['dau'] = $item['dau'];
	    		$data[$item['sday']]['wau'] = $item['wau'];
	    		$data[$item['sday']]['mau'] = $item['mau'];
	    	}
	    	//注册
	    	foreach ($res['reg'] as $item) {
	    		$data[$item['date']]['reg'] = $item['cnt'];
	    	}
	    	//最大在线
	    	foreach ($res['max_online'] as $item) {
	    		$data[$item['date']]['max_online'] += $item['cnt'];
	    		$data[$item['date']]['avg_online_cnt'] += ceil($item['cnt'] / 24);
	    	}
	    	//平均在线
	    	foreach ($res['avg_online'] as $item) {
	    		$data[$item['date']]['avg_online'] = $item['total_online_num']>0 ? ceil($item['total_online_time'] / $item['total_online_num']) : 0;
	    	}
	    	$output = [];
	    	foreach($data as $date=>$item) {
	    		$output[] = array(
	    				'date'  => $date,
	    				'device'=>isset($item['device']) ? $item['device'] : 0,
	    				'macregister'=>isset($item['macregister']) ? $item['macregister'] : 0,
	    				'rare'=>(isset($item['device'])? number_format($item['macregister'] / $item['device'], 2) * 100 : 0).'%',
	    				'reg'   => isset($item['reg']) ? $item['reg'] : 0,
	    				'role'   => isset($item['role']) ? $item['role'] : 0,
	    				'trans_rate'   => isset($item['role']) && isset($item['reg'])? number_format($item['role'] / $item['reg'], 2) * 100 : 0,
	    				'dau'   => isset($item['dau']) ? $item['dau'] : 0,
	    				'wau'   => isset($item['wau']) ? $item['wau'] : 0,
	    				'mau'   => isset($item['mau']) ? $item['mau'] : 0,
	    				'max_online'   => isset($item['max_online']) ? $item['max_online'] : 0,
	    				'avg_online_cnt'   => isset($item['avg_online_cnt']) ? $item['avg_online_cnt'] : 0,
	    				'avg_online'   => isset($item['avg_online']) ? number_format($item['avg_online'] / 60,2): 0,
	    		);
	    	}
	    	//print_r($output);
	    	$this->insert_batch('sum_summary',$output,$this->db);
	    
	    	print_r($this->db->error());
	    }
    public function summary_by_channel($date1=''){
    	$date1 = $date1 ? $date1 : date('Ymd', strtotime('-1 days'));
    	$date2 = $date1;
    	
    	$this->load->model('Summary_model');
    	$res = $this->Summary_model->getDataByChannel($this->appids[0]['appid'], $date1, $date2, $serveid, $channel);
    	$data = [];
    	foreach ($res['device'] as $item) {
    		$data[$item['channel']]['device'] = $item['cnt'];
    	}
    	foreach ($res['register'] as $item) {
    		$data[$item['channel']]['macregister'] = $item['cnt'];
    	}
    	foreach ($res['au'] as $item) {
    		$data[$item['channel']]['role'] = $item['new_role'];
    		$data[$item['channel']]['dau'] = $item['dau'];
    		$data[$item['channel']]['wau'] = $item['wau'];
    		$data[$item['channel']]['mau'] = $item['mau'];
    	}
    	//注册
    	foreach ($res['reg'] as $item) {
    		$data[$item['channel']]['reg'] = $item['cnt'];
    	}
    	ksort($data);
    	$output = [];
    	foreach($data as $channel=>$item) {
    		$output[] = array(
    				'date'=>$date1,
    				'channel'  => $this->data['channel_list'][$channel]?$this->data['channel_list'][$channel]:$channel,
    				'device'=>isset($item['device']) ? $item['device'] : 0,
    				'macregister'=>isset($item['macregister']) ? $item['macregister'] : 0,
    				'rare'=>(isset($item['device'])? number_format($item['macregister'] / $item['device'], 2) * 100 : 0).'%',
    				'reg'   => isset($item['reg']) ? $item['reg'] : 0,
    				'role'   => isset($item['role']) ? $item['role'] : 0,
    				'trans_rate'   => isset($item['role']) && isset($item['reg'])? number_format($item['role'] / $item['reg'], 2) * 100 : 0,
    				'dau'   => isset($item['dau']) ? $item['dau'] : 0,
    				'wau'   => isset($item['wau']) ? $item['wau'] : 0,
    				'mau'   => isset($item['mau']) ? $item['mau'] : 0,
    		);
    	}
    	$this->insert_batch('sum_summary_by_channel',$output,$this->db);
    }
    /**
     * php /var/www/ci/index.php AutoRunDay CliNewPlayer
     */
    public function CliNewPlayer()
    {
        foreach ($this->appids as $_d) {
            $this->NewPlayerCal($_d['appid']);
        }
    }

    /**
     * php /var/www/ci/index.php AutoRunDay CliLogin
     */
    public function CliLogin()
    {
        foreach ($this->appids as $_d) {
            $this->LoginCal($_d['appid']);
        }
    }
    private function cal($model, $appid, $date='', $func=null)
    {
        $date   = empty($date) ? date('Ymd', strtotime('-1 days')) : $date;
        $model->init($appid,'','');
        $data = $model->day_counts($date);
        if (!is_null($func)) {
            $model->$func();
        }
        return $data;
    }
    /**
     * 统计实时在线——每小时
     */
    private function OnlineCal($appid, $date='')
    {
        parent::log('OnlineCal running');
        $this->load->model('dayonline_model');
        $this->cal($this->dayonline_model, $appid, $date);
        return true;
    }

    /**
     * 统计实时在线——每小时
     */
    private function DeviceCal($appid, $date='')
    {
        parent::log('DeviceCal running');
        $this->load->model('device_model');
        $this->cal($this->device_model, $appid, $date);
        return true;
    }

    /**
     * 统计实时新注册用户——每小时
     */
    private function NewPlayerCal($appid, $date='')
    {
        parent::log('NewPlayerCal running');
        $this->load->model('register_model');
        $this->cal($this->register_model, $appid, $date);
        return true;
    }

    /**
     * 统计实时登录用户——每小时
     */
    private function LoginCal($appid, $date='')
    {
        echo "LoginCal running - $appid - $date\n";
        parent::log('LoginCal running');
        $this->load->model('login_model');
        $this->cal($this->login_model, $appid, $date);
        return true;
    }

    /**
     * 活跃度统计
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunDay au
     */
    public function au($tm=0)
    {
        parent::log('au running');
        $this->load->model('player_analysis_model');
        $appids = $this->getAppid();
        //$appids = [['appid'=>'10001']];
        foreach ($appids as $appid) {
             $this->player_analysis_model->saveVipPlayer($appid['appid'], $tm);
             $this->player_analysis_model->saveRolesCount($appid['appid'], $tm);
             $this->player_analysis_model->saveDau($appid['appid'], $tm);
             $this->player_analysis_model->saveWau($appid['appid'], $tm);
             $this->player_analysis_model->saveMau($appid['appid'], $tm);
        }
    }

    /**
     * 活跃账号统计
     * clid模式
     *
     * php /var/www/ci/index.php ActiveAccountCount au
     *
     * @param $tm
     */
    public function ActiveAccountCount($tm=0)
    {
        parent::log('ActiveAccountCount running');
        echo 'ActiveAccountCount running',PHP_EOL;
        $this->load->model('player_analysis_model');
        foreach ($this->appids as $appid) {
            $this->player_analysis_model->saveRolesCount($appid['appid'], $tm, 1);
            $this->player_analysis_model->saveDau($appid['appid'], $tm, 4);
            $this->player_analysis_model->saveWau($appid['appid'], $tm, 5);
            $this->player_analysis_model->saveMau($appid['appid'], $tm, 6);
            $this->player_analysis_model->saveCleanDau($appid['appid'], $tm, 7);
        }
    }
	public function run_summary($date=''){
     //	$date = '20170801';
    	$this->summary($date);
    	$this->summary_by_channel($date);
    }
    /**
     * 用户留存
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunDay UserRemain
     */
    public function UserRemain($tm=0)
    {
        parent::log('UserRemain running...');
        $this->load->model('userremain_model');
        $this->userremain_model->load();
        //$appids = $this->getAppid();
        $appids =$this->appids;
        $tm = $tm > 0 ? $tm : strtotime('-1 days');
        foreach($appids as $appid) {
            $this->userremain_model->init($appid['appid'], $tm,'');
            $this->userremain_model->remainDaily();
        }
    }
    
    /**
     * 每日统计前7日行为表数据
     * cli模式运行
     *
     * @author 王涛 20170203
     */
    public function ActionCountByAct($tm=0)
    {
    	$this->load->model('SystemFunction_model');
    	$this->SystemFunction_model->ActionCount('',$tm);
    	$this->SystemFunction_model->ActionCount('serverid',$tm);
    	$this->SystemFunction_model->ActionCount('channel',$tm);
    }
    /**
     * 每日统计前7日行为表数据
     * cli模式运行
     *
     * @author 王涛 20170203
     */
    public function ActionCountByServer($tm=0)
    {
    	$this->load->model('SystemFunction_model');
    	$this->SystemFunction_model->ActionCount('serverid',$tm);
    }
    
    /**
     * 每日统计前7日道具产销表数据
     * cli模式运行
     *
     * @author 王涛 20170204
     */
    public function ItemCount($tm=0)
    {
    	$this->load->model('SystemFunction_model');
    	$this->SystemFunction_model->ItemCount($tm);
    }
    /**
     * 每日统计前7日行为表数据
     * cli模式运行
     *
     * @author 王涛 20170203
     */
    public function ActionCountByChannel($tm=0)
    {
    	$this->load->model('SystemFunction_model');
    	$this->SystemFunction_model->ActionCount('channel',$tm);
    }
    /**
     * 用户留存新
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunDay UserRemain
     * @author 王涛 20170113
     */
    public function UserRemainNew($tm=0)
    {
    	parent::log('UserRemain running...');
    	$this->load->model('userremain_model');
    	$this->userremain_model->load();
    	//$appids = $this->getAppid();
    	$appids =$this->appids;
    	$tm = $tm > 0 ? $tm : strtotime('-1 days');
    	foreach($appids as $appid) {
    		$this->userremain_model->init($appid['appid'], $tm,'');
    		$this->userremain_model->remainDailyNew();
    	}
    }
    /**
     * 用户流失
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunDay UserLost
     */
    public function UserLost($date='')
    {
        //$appid
        $this->load->model('player_lost_model');
        $date = $date=='' ? strtotime(date('Y-m-d 00:00:00', strtotime("-1 days"))) : strtotime($date);
        $appids = $this->getAppid();
        echo $date;
        //print_r($appids);
        //exit;
        //$appids = [['appid'=>'10002']];
        foreach($appids as $appid) {
            parent::log("UserLost running;appid={$appid['appid']};data={$date};");
            $this->player_lost_model->lost($appid['appid'], $date);
            sleep(3);
        }
    }

    public function UserLostReRun($appid)
    {
        //$appid
        $this->load->model('player_lost_model');
        $date = strtotime(date('Y-m-d 00:00:00', strtotime("-1 days")));
        echo $date;
        $this->player_lost_model->lost($appid, $date);
    }
    public function user_lost_0()
    {
        $appids = $this->getAppid();
        $this->UserLostReRun($appids[0]['appid']);
    }
    public function user_lost_1()
    {
        $appids = $this->getAppid();
        $this->UserLostReRun($appids[1]['appid']);
    }

    public function online_time_2()
    {
        $this->OnlineTime($this->appids[0]['appid']);
    }

    /**
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunDay OnlineTime
     */
    public function OnlineTime($tms = 0, $tme=0)
    {
        //$this->load->database();
        $appids = $this->getAppid();
        $this->load->model('online_analysis_model');
        $t1     = $tms>0 ? $tms : strtotime(date('Y-m-d 00:00:00', strtotime('-1 days')));
        $t2     = $tme>0 ? $tme :strtotime(date('Y-m-d 23:59:59', strtotime('-1 days')));
        $date   = date('Ymd', $t2);
        $db_sdk = $this->load->database('sdk', TRUE);
        foreach ($appids as $appid)
        {
            parent::log($appid['appid'] . ':OnlineTime running');
            $this->online_analysis_model->init($appid['appid'], $t1, $t2, $date, $db_sdk, $this->db);
            $this->online_analysis_model->CalOnlineTimeAvg($date);
            //$this->online_analysis_model->online_time(online_analysis_model::ONLINE_ACTIVE);
            //$this->online_analysis_model->online_time(online_analysis_model::ONLINE_VIP);
            //$this->online_analysis_model->online_time(online_analysis_model::ONLINE_NEW);
            $this->online_analysis_model->player_online_level($date);
        }
    }

    /**
     *  系统分析
     *  php /var/www/ci/index.php AutoRunDay SystemAnalysis
     */
    public function SystemAnalysis($t1=0, $t2=0)
    {
        parent::log('SystemAnalysis running');
        $appids = $this->getAppid();
        $this->load->model('system_analysis_model');
        $tm     = strtotime('-1 days');
        $t1     = $t1 ? $t1 : strtotime(date('Y-m-d 00:00:00', $tm));
        $t2     = $t2 ? $t2 : strtotime(date('Y-m-d 23:59:59', $tm));
        $date   = date('Ymd', $t2);
        $db_sdk = $this->load->database('sdk', TRUE);
        foreach ($appids as $appid)
        {
            $this->system_analysis_model->init($appid['appid'], $t1, $t2,$date, 0, 0, $db_sdk);
            $this->system_analysis_model->emoney_use();
            //sleep(1);
            $this->system_analysis_model->emoney_get();
            //sleep(1);
            $this->system_analysis_model->emoney_left();
            sleep(1);
            //道具获取
            $this->system_analysis_model->props(0);
            //sleep(1);
            //道具消耗
            $this->system_analysis_model->props(1);
            //sleep(1);
            //副本分析
            $this->system_analysis_model->copy_progress();
        }
    }

    //废弃--王涛 20170215
    /*public function RunSystemAnalysis()
    {
        $appids = $this->getAppid();
        $this->load->model('system_analysis_model');
        for($i = 0; $i<10; $i ++) {
            //$date = strtotime(date('Y-m-d 00:00:00', ));

            $t1     = strtotime(date('Y-m-d 00:00:00', strtotime("-{$i} days")));
            $t2     = strtotime(date('Y-m-d 23:59:59', strtotime("-{$i} days")));
            $date   = date('Ymd', $t2);
            $db_sdk = $this->load->database('sdk', TRUE);
            $this->system_analysis_model->init('10001', $t1, $t2,$date, 0, 0, $db_sdk);
            $this->system_analysis_model->emoney_use();
            //sleep(1);
            $this->system_analysis_model->emoney_get();
            //sleep(1);
            $this->system_analysis_model->emoney_left();
            sleep(1);
            //道具获取
            $this->system_analysis_model->props(0);
            //sleep(1);
            //道具消耗
            $this->system_analysis_model->props(1);
            $this->system_analysis_model->copy_progress();
        }
    }*/

    /**
     * 参与度统计
     * 
     * @author 王涛 --20170330
     */
    public function joinCount($tm='')
    {
    	$this->load->model('SystemFunction_model');
    	$this->SystemFunction_model->joinCount($tm);
    }
    /**
     * 渠道用户留存新
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunDay UserRemain
     * @author 王涛 20170912
     */
    public function UserRemainAd($tm=0)
    {
    	parent::log('UserRemain running...');
    	$this->load->model('userremain_model');
    	$this->userremain_model->load();
    	//$appids = $this->getAppid();
    	$appids =$this->appids;
    	$tm = $tm > 0 ? $tm : strtotime('-1 days');
    	foreach($appids as $appid) {
    		$this->userremain_model->init($appid['appid'], $tm,'');
    		$this->userremain_model->remainDailyAd();
    	}
    }
	/**
	 * 渠道统计注册，dau，留存跟充值 
	 */
    function run_ad()
    {
    	$begin = strtotime('2017-10-02 00:00:00');
    	$end   = strtotime('2017-10-05 23:59:59');
    	$diff =1;//floor( ($end - $begin ) / 86400) ;// 1;
    
    	for ($i=0; $i<$diff; $i ++) {
    		$now = strtotime("+$i days", $begin);
    		$date = date('Ymd', $now);
    		$this->UserRemainAd($now);
    		usleep(1000);
    	}
    
    }
    /**
     * 各商店销售统计
     */
    public function ShopSaleCount($tm='')
    {
    	if(!$tm){
    		$tm = date('Ymd',strtotime("-1 days"));
    	}
    	$this->load->model('GameEmoney_model');
    	$this->GameEmoney_model->sumshop($tm);
    	$this->GameEmoney_model->rankshop($tm);
    }

    function run_action()
    {
    	$begin = strtotime('2017-03-10 00:00:00');
        $end   = strtotime('2017-01-27 23:59:59');
        $diff = 1;//floor( ($end - $begin ) / 86400) ;// 1;

        for ($i=0; $i<$diff; $i ++) {
            $now = strtotime("+$i days", $begin);
            $date = date('Ymd', $now);
            $this->ItemCount($date);
//             $this->ActionCountByAct($date);
        }
		
    }
    function run_demo()
    {
        $this->load->database();
        //$begin = strtotime('2016-11-29 00:00:00');
        $begin = strtotime('2017-03-15 00:00:00');
        $end   = strtotime('2017-01-09 23:59:59');
        //$begin = strtotime('2016-07-13 00:00:00');
        $_d['appid'] = $this->appids[0]['appid'];
        //$end  = time();
        $diff =3;//floor( ($end - $begin ) / 86400) ;// 1;


        for ($i=0; $i<$diff; $i ++) {

            $now = strtotime("+$i days", $begin);
            $date = date('Ymd', $now);
            echo $date, PHP_EOL;
            //continue;
            $t1=strtotime(date('Y-m-d 00:00:00', $now));
            $t2=strtotime(date('Y-m-d 23:59:00', $now));
//             $this->OnlineTime($t1, $t2);
//             $this->SystemAnalysis($t1, $t2);
            $this->NewPlayerCal($_d['appid'], $date);
            $this->ActiveAccountCount( $now);
            $this->UserRemainNew($now);
            $this->UserLost( $date);

//            $this->au( $now);
            

            //$date = date('Ymd', $bt);
            //echo $date, '<br/>';
//             $this->OnlineCal($_d['appid'],$date);
            //$this->DeviceCal($_d['appid'], $date);
            //$this->NewPlayerCal($_d['appid'], $date);
            //$this->LoginCal($_d['appid'],$date);
            usleep(500);

        }
    }
/*
     *  删除 u_behavior    zzl
     */
    public function delBehavior()
    {
    	date_default_timezone_set("PRC");
    	$suffix= date('Ymd', time());
    	$dbsdk = $this->load->database('sdk', true);    	
    	$dbsdk->query("DELETE u_behavior_{$suffix}.*,item_trading_{$suffix}.* FROM u_behavior_{$suffix} inner JOIN item_trading_{$suffix}  on u_behavior_{$suffix}.id=item_trading_{$suffix}.behavior_id WHERE u_behavior_{$suffix}.accountid<1000 and u_behavior_{$suffix}.accountid>0");
      	unset($dbsdk);  
    }
    
    /*
     * 新设备使用旧账号登录数量   安装新包使用旧账号登录数量  zzl 20171018
     */
    public  function accountDevice(){
     //   $assign_date="20171018";
        $assign_date='';
        $this->load->model('Mydb_sum_model');
        $res = $this->Mydb_sum_model->accountDevice($assign_date);
         
    }
    
/*/
 * 支付dau zzl  20180122
 */
    public function payTotal()
    {
    	echo	$where['date_table']= date("Ymd", time()-86400);
     	$sql = "select COUNT(DISTINCT l.accountid) pay_total from  u_paylog p INNER JOIN u_login_{$where['date_table']} l on l.accountid=p.accountid ";
    	
    	$db = $this->load->database('sdk',true);
    	$result = $db->query($sql);
    	if ($result) {
    		$result_data = $result->result_array();
    	}
    	
    	$db_mydb = $this->load->database('default',true);
    	$sql_insert="UPDATE  sum_summary  set pay_dau={$result_data[0]['pay_total']}  WHERE date={$where['date_table']}";
    	$result = $db_mydb->query($sql_insert);
    
   
    }

}
