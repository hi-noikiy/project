<?php
/**
 * Created by PhpStorm.
 * User: fusha
 * Date: 16-2-29
 * Time: 下午10:07
 *
 * 每小时自动统计程序
 */
set_time_limit(3000);
ini_set('memory_limit', '1024M');
ini_set('display_errors', 'On');
class AutoRunHour extends CI_Controller{
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
	 * 用户留存新
	 * cli模式运行
	 * php /var/www/ci/index.php AutoRunHour UserRemainNew
	 * 
	 * @author 王涛 20170217
	 */
	public function UserRemainNew($tm=0)
	{
		parent::log('UserRemain running...');
		$this->load->model('userremain_model');
		$this->userremain_model->load();
		//$appids = $this->getAppid();
		$appids =$this->appids;
		$tm = $tm > 0 ? $tm : strtotime(date('Ymd'));
		foreach($appids as $appid) {
			$this->userremain_model->init($appid['appid'], $tm,'');
			$this->userremain_model->remainDailyNew();
		}
	}
	
	/**
	 * 活跃账号统计
	 * clid模式
	 * php /var/www/ci/index.php ActiveAccountCount au
	 *
	 * @author 王涛 20170217
	 */
	public function ActiveAccountCount($tm=0)
	{
		$tm = $tm > 0 ? $tm : strtotime(date('Ymd'));
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
	
	/**
	 * 统计实时新注册用户——每小时
	 * 
	 * @author 王涛 20170217
	 */
	public function NewPlayerCal($tm='')
	{
		parent::log('NewPlayerCal running');
		$this->load->model('register_model');
		$appids =$this->appids;
		$tm = $tm > 0 ? $tm : date('Ymd');
		foreach($appids as $appid) {
			$this->cal($this->register_model, $appid['appid'], $tm);
		}
		return true;
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
	public function run_summary(){
		$this->summary();
		$this->summary_by_channel();
	}
    /**
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunHour run
     */
    public function run()
    {
    	$this->NewPlayerCal();
    	$this->ActiveAccountCount();
    	$this->UserRemainNew();
        parent::log('hour_running');
        $this->load->database();
        //$this->db     = $this->load->database('default', TRUE);
        $db_sdk = $this->load->database('sdk', TRUE);
        $data = $this->db->query('SELECT appid FROM auth_config')->result_array();
        $t1     = strtotime('-1 hours');
        $t2     = time();
        $hour   = date('H', $t1);
        $date   = date('Ymd', $t1);
        //第二天零点的时候统计的数据是前一天的
        //if ($t1 == 23) {
        //    $date   = date('Ymd', $t1);
        //}
        //$data = [['appid'=>10002]];
        $this->load->model('real_time_model');
        //$r = new real_time_model();
        foreach ($data as $_d) {
            echo $_d['appid'],PHP_EOL;
            $this->real_time_model->init($_d['appid'], $t1, $t2, $hour, $date, $db_sdk, $this->db);
            //$this->real_time_model->init('10001', $t1, $t2, $hour, $date, $db_sdk, $this->db);
            $this->real_time_model->hour_count(real_time_model::TBL_ONLINE);
            parent::log( $_d['appid'] . ':hour_running TBL_ONLINE');
            $this->real_time_model->hour_count(real_time_model::TBL_DEVICE);
            parent::log($_d['appid'] . ':hour_running TBL_DEVICE');
            $this->real_time_model->hour_count(real_time_model::TBL_LOGIN);
            parent::log($_d['appid'] . ':hour_running TBL_LOGIN');
            //$this->real_time_model->hour_count(real_time_model::TBL_REGISTER);
            //parent::log($_d['appid'] . ':hour_running TBL_REGISTER');
            //$this->real_time_model->hour_count(real_time_model::TBL_NEW_ROLES);
            //parent::log($_d['appid'] . ':hour_running TBL_NEW_ROLES');
            $this->real_time_model->hour_count(real_time_model::TBL_INCOME);
            parent::log($_d['appid'] . ':hour_running TBL_INCOME');
            $this->real_time_model->hour_count(real_time_model::TBL_DAY_ONLINE);
            parent::log($_d['appid'] . ':hour_running TBL_DAY_ONLINE');
            sleep(5);
        }
    }

    /*public function reg1()
    {
        $this->Register(10001);
    }*/
    public function reg2()
    {
        $this->Register($this->appids[0]['appid']);
    }
    private function Register($appid)
    {
        parent::log($appid . ':hour_running TBL_REGISTER');
        $tm     = time();
        $ttm    = date('Y-m-d H:00:00', $tm);
        $t1     = strtotime('-1 hours', strtotime($ttm));
        $t2     = strtotime(date('Y-m-d H:00:00', $tm));
        $hour   = date('H', $t1);
        $date   = date('Ymd', $t1);
        $this->load->model('real_time_model');
        $this->load->database();
        $db_sdk = $this->load->database('sdk', TRUE);
        $this->real_time_model->init($appid, $t1, $t2, $hour, $date, $db_sdk, $this->db);
        $this->real_time_model->hour_count(real_time_model::TBL_REGISTER);
        $this->real_time_model->hour_count(real_time_model::TBL_NEW_ROLES);
        $this->real_time_model->hour_count(real_time_model::TBL_NEW_PLAYERS);
    }
    public function summary($date1=''){
    	$date1 = $date1 ? $date1 : date('Ymd');
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
    	$this->insert_batch('sum_summary',$output,$this->db);
    }
    public function summary_by_channel($date1=''){
    	$date1 = $date1 ? $date1 : date('Ymd');
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
    function run_action()
    {
    	$this->NewPlayerCal();
    		$this->ActiveAccountCount();
    		$this->UserRemainNew();
    		//usleep(500);
    }
    public function run_demo()
    {
        log_message('error', date('Y-m-d H:i:s').'|hour running...');
        echo 'running...'.PHP_EOL;
        $this->load->database();
        //$this->db     = $this->load->database('default', TRUE);
        $db_sdk = $this->load->database('sdk', TRUE);
        //$data = $this->db->query('SELECT appid FROM auth_config')->result_array();
        //$t1     = strtotime('-1 hours');
        //$t2     = time();
        //echo $t1, '---', $t2;
        //$hour   = date('H', $t2);
        //$date   = date('Ymd', $t2);

        $this->load->model('real_time_model');
        //$r = new real_time_model();
        $now = strtotime('2016-11-29 00:00:00');
        for ($i=0; $i<1; $i ++) {
            $bt = strtotime("+$i days", $now);
            for ($j=0; $j<22; $j++) {
                $t1 = strtotime("+$j hours", $bt);
                $jj = $j + 1;
                $t2 = strtotime("+$jj hours", $bt);
                echo date('Y-m-d H:i', $t1), "\n";
                echo date('Y-m-d H:i', $t2), "\n";

                //continue;
                //$t1     = strtotime('2016-08-23 11:00:00');
                //$t2     = strtotime('2016-08-23 12:00:00');
                $hour   = date('H', $t1);
                $date   = date('Ymd', $t1);
                //echo "hour", $hour;
                //exit;
                //echo $hour, '---', $date,"\n";continue;
                //echo 'DATE :',date('Y-m-d H:i:s', $t1),'---', date('Y-m-d H:i:s', $t2),'<br/>';
                //foreach ($data as $_d) {
                    //echo $_d['appid'],PHP_EOL;
                    //$t1 = date('ymdHi',$t1);
                    //$t2 = date('ymdHi',$t2);
                    //echo $t1,'----',$t2,'<br/>';
                    $this->real_time_model->init($this->appids[0]['appid'], $t1, $t2, $hour, $date, $db_sdk, $this->db);
                    $this->real_time_model->hour_count(real_time_model::TBL_ONLINE);
                    $this->real_time_model->hour_count(real_time_model::TBL_REGISTER);
                    $this->real_time_model->hour_count(real_time_model::TBL_NEW_ROLES);
                    $this->real_time_model->hour_count(real_time_model::TBL_NEW_PLAYERS);

                // //parent::log('hour_running TBL_NEW_ROLES');
                    $this->real_time_model->hour_count(real_time_model::TBL_DEVICE);
                    $this->real_time_model->hour_count(real_time_model::TBL_LOGIN);
                   $this->real_time_model->hour_count(real_time_model::TBL_DAY_ONLINE);
                    $this->real_time_model->hour_count(real_time_model::TBL_INCOME);

                    //usleep(1000);
                //}
            }
        }
        exit;

    }
} 