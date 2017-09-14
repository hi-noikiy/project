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
class AutoRunBak extends CI_Controller{

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunMonth run
     */
    public function run_test()
    {
    	$dbsdk = $this->load->database('rootsdk', true);
    	$Ym = 201707;
    	$t = date("t",strtotime($Ym)); //这月月末
    	$begintime = strtotime($Ym.'01');
    	$endtime = strtotime($Ym.$t)+86399;
    	for($i = 1;$i<=$t;$i++){
    		$date = $Ym.str_pad($i,2,0,STR_PAD_LEFT);
    		echo $date;
    		$sql = "insert into mydb.sum_emoney_day(type,emoney,logdate) SELECT type,sum(b.item_num),$date as logdate FROM u_behavior_$date a,item_trading_$date b,(select DISTINCT(accountid) from u_paylog where isbt=0 and created_at BETWEEN $begintime and $endtime)c
	where a.id=b.behavior_id and b.item_id=3 and
	a.accountid = c.accountid
	GROUP BY b.type";
    		$query = $dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    	}
    	
    	 
    
    	//$dbsdk->execute($sql);
    	unset($dbsdk);
    
    	parent::log('month running createTable');
    }

    /**
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunMonth run
     */
    public function run_join()
    {
    	$dbsdk = $this->load->database('rootsdk', true);
    	$sql = "insert into sum_join_201704(act_id,param,act_count,act_account,logdate,serverid,mysort)"
    			."select act_id,param,act_count,act_account,logdate,serverid,mysort from mydb.sum_join where left(logdate,6)=201704 and logdate<20170412"
    			." ON DUPLICATE KEY UPDATE act_count=values(act_count),act_account=values(act_account),mysort=values(mysort)";
    		
    		$query = $dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    	
    	 
    	//$dbsdk->execute($sql);
    	unset($dbsdk);
    
    	parent::log('month running createTable');
    }
    /**
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunMonth run
     */
    public function run()
    {
        $dbsdk = $this->load->database('sdk', true);
    	$Ym = date('Ym');
    	$t = date("t"); //下月末
    	for($i = 1;$i<=15;$i++){
    		//$sql .= "DROP TABLE IF EXISTS `u_behavior_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."`;";
    		$sql = "insert into game_user_$Ym(serverid,accountid,userid,name,gameid,status,dan,viplevel,level,communityid,power) select serverid,accountid,userid,name,gameid,status,dan,viplevel,level,communityid,power from game_user_".$Ym.str_pad($i,2,0,STR_PAD_LEFT);
    		$query = $dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    		$sql = "insert into game_user_eudemon_$Ym(eudemon,status,gameuserid,hp,skills1,skills2,skills3,skills4,pp1,pp2,pp3,pp4,abilities,fruit,equip,kidney) select eudemon,status,gameuserid,hp,skills1,skills2,skills3,skills4,pp1,pp2,pp3,pp4,abilities,fruit,equip,kidney from game_user_eudemon_".$Ym.str_pad($i,2,0,STR_PAD_LEFT);
    		$query = $dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    	}
    	
        //$dbsdk->execute($sql);
        unset($dbsdk);

        parent::log('month running createTable');
    }
    /**
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunMonth run
     */
    public function run_index()
    {
    	$sql = '';
    	$count = 0;
    	$handle = fopen("/data/log/site/api/index_20170420.log", "r");
    	$i = 0;
    	if ($handle) {
    		while (!feof($handle)) {
    			$buffer = fgets($handle, 4096);
    			$sarr = explode('#', $buffer);
    			$date = strtotime($sarr[0]);
    			/*if($date<1492620300){
    				continue;
    			}
    			if($date>1492628400){
    				break;;
    			}*/
    			$time = date('Ymd',$date);
    			parse_str($sarr[1], $arr);
    			$json = base64_decode($arr['data_raw']);
    			$data = json_decode($json,true);
    			$data['created_at'] = $date;
    			$data['appid'] = 10002;
    			$this->index($data);
    			$i++;
    			PHP_EOL;
    			//if($i>10)break;
    			echo $i;
    		}
    
    		//echo "TOTAL:$i\n";
    		fclose($handle);
    		//echo rtrim($sql,',');
    	}
    
    	//$dbsdk->execute($sql);
    	unset($dbsdk);
    }
    private function index($data){
    	if($data['typeid'] == 18){
    		$this->db = $this->load->database('sdk', true);
    		$udata = array();  //用户行为数据
    		$idata = array(); //道具产销数据
    		$ltype = 'get_';
    		$u_table_name = "u_behavior_".date('Ymd',$data['created_at']);
    		$i_table_name = "item_trading_".date('Ymd',$data['created_at']);
    		$udata = array(
    				'accountid' =>	$data['accountid'],
    				'userid' =>	$data['userid'],
    				'serverid' =>	$data['serverid'],
    				'channel' =>	$data['channel'],
    				'created_at' =>	$data['created_at'],
    				'client_time' =>time(),
    				'vip_level' =>	$data['vip_level'],
    				'act_id' =>	$data['counttype'],
    				'param' =>	$data['param'],
    		);
    		if($data['user_level']){
    			$udata['user_level'] = $data['user_level'];
    		}
    		$this->db->insert($u_table_name, $udata);
    		//道具产销记录
    		$idata['behavior_id'] = $this->db->insert_id();
    		$idata['table_type'] = $data['typeid'];
    		$idata['created_at'] = $data['created_at'];
    		$istatus = 0;
    		foreach ($data as $k =>$v){
    			if(strpos($k, 'get_') !== false || strpos($k, 'consume_') !== false){
    				if(strpos($k, 'get_') !== false){ //获取
    					$ltype = 'get_';
    					$idata['type'] = 0;
    				}else{ //消耗
    					$ltype = 'consume_';
    					$idata['type'] = 1;
    				}
    				if(strpos($k, 'emoney') !== false){ //钻石
    					$idata['item_id'] = 3;
    					$idata['item_num'] = $v;
    					$this->db->insert($i_table_name, $idata);
    					$istatus++;
    				}elseif(strpos($k, 'money') !== false){
    					$idata['item_id'] = 1;
    					$idata['item_num'] = $v;
    					$this->db->insert($i_table_name, $idata);
    					$istatus++;
    				}elseif(strpos($k, 'tired') !== false){
    					$idata['item_id'] = 2;
    					$idata['item_num'] = $v;
    					$this->db->insert($i_table_name, $idata);
    					$istatus++;
    				}elseif(strpos($k, 'currency_') !== false){
    					$idata['item_id'] = '1'.str_pad(explode('currency_', $k)[1],4,'0',STR_PAD_LEFT);
    					$idata['item_num'] = $v;
    					$this->db->insert($i_table_name, $idata);
    					$istatus++;
    				}elseif(strpos($k, 'item_') !== false){
    					$idata['item_id'] = $v;
    					$idata['item_num'] = $data[$ltype.'num_'.explode('item_', $k)[1]];
    					$this->db->insert($i_table_name, $idata);
    					$istatus++;
    				}
    			}
    			/*$result = $this->db->error();
    			 if($result['code'] !==0){
    			 parent::BetterLog('item_error',json_encode($result));
    			 }*/
    		}
    }
    }
    public function run_tower()
    {
    	$begin = strtotime('2017-05-03 00:00:00');
    	$diff =6 ;
    	for ($i=0; $i<$diff; $i ++) {
    		$now = strtotime("+$i days", $begin);
    		$date = date('Ymd', $now);
    		$sql = '';
    		$handle = fopen("/data/log/site/api/Gametower_{$date}.log", "r");
    		$i = 0;
    		if ($handle) {
    			while (!feof($handle)) {
    				$buffer = fgets($handle, 4096);
    				$sarr = explode('#', $buffer);
    				$date = strtotime($sarr[0]);
    				$time = date('Ymd',$date);
    				parse_str($sarr[1], $arr);
    				$json = base64_decode($arr['data_raw']);
    				$data = json_decode($json,true);
    				$data['created_at'] = $date;
    				$data['appid'] = 10002;
    				$this->Gametower($data);
    				$i++;
    				PHP_EOL;
    				//if($i>10)break;
    				//echo $i;
    			}
    			fclose($handle);
    		}
    		echo $date;
    	}
    	
    }
    /**
     * 精灵塔通关阵容
     *
     * @author 王涛 20170321
     */
    public function Gametower($data)
    {
    	unset($this->data['appid']);
    	$comdata = array(
    			'tower'=>$data['Group'],
    			'logdate'=>$data['EndTime'],
    			'integral'=>$data['Grade'],
    			'serverid'=>$data['ServerId'],
    			'playerid'=>$data['UserId'],
    			'created_at'=>$data['created_at'],
    	);
    	/*$ym = '20'.substr($data['endTime'], 0,4);
    	 $ymd = '20'.substr($data['endTime'], 0,6);*/
    	$m = '20'.substr($data['EndTime'], 0,4);
    	$this->db = $this->load->database('sdk', true);
    	for($i=1;$i<=12;$i++){
    		if(!isset($data['EudType'.$i])){
    			break;
    		}
    		$towerdata = $comdata;
    		$towerdata['eudemon'] = $data['EudType'.$i];
    		$towerdata['hp'] = $data['LeftLife'.$i];
    		for($j=1;$j<=4;$j++){
    			$towerdata['skills'.$j] = $data['MagicType'.$i.'_'.$j];
    			$towerdata['pp'.$j] = $data['CurUseTimes'.$i.'_'.$j];
    		}
    		$multdata[]=$towerdata;
    		//$this->db->insert('game_tower_'.$m, $towerdata);
    	}
    	$this->db->insert_batch('game_tower_'.$m, $multdata);
    	//$this->save('game_tower_'.$m);
    }
    /**
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunMonth run
     */
    public function run_login()
    {
    	$dbsdk = $this->load->database('sdk', true);
    	$sql = '';
    	$count = 0;
        $handle = fopen("/data/log/site/api/Login_20170906.log", "r");
        $i = 0;
        if ($handle) {
            while (!feof($handle)) {
            	
                $buffer = fgets($handle, 4096);
                $sarr = explode('#', $buffer);
                $date = strtotime($sarr[0]);
                /*if($date<1492620300){
                	continue;
                }
                if($date>1492628400){
                	break;;
                }*/
                $time = date('Ymd',$date);
                parse_str($sarr[1], $arr);
                $json = base64_decode($arr['data_raw']);
                $lastdata = json_decode($json,true);
                $sql = "INSERT INTO u_login_{$time}(`serverid`, `channel`, `appid`, `accountid`, `username`,userid,viplev,lev,client_type,ip,created_at,mac,trainer_lev,client_version)
                VALUES ({$lastdata['serverid']},{$lastdata['channel']},10002,{$lastdata['accountid']},'{$lastdata['username']}',{$lastdata['userid']},{$lastdata['viplev']},{$lastdata['lev']}
                ,'{$lastdata['client_type']}',".ip2long($arr['ip']).",{$date},'{$lastdata['mac']}','{$lastdata['trainer_lev']}','{$lastdata['client_version']}')
                ON DUPLICATE KEY UPDATE `channel`=VALUES(channel),`viplev`=VALUES(viplev),`lev`=VALUES(lev),`client_type`=VALUES(client_type),`ip`=VALUES(ip),
                `created_at`=VALUES(created_at),`mac`=VALUES(mac),`username`=VALUES(username),`trainer_lev`=VALUES(trainer_lev),`client_version`=VALUES(client_version),`userid`=VALUES(userid)
                ";
                $dbsdk->query($sql);
                //echo $sql;
                echo $sarr[0].json_encode($dbsdk->error());
            	$i++;
            	PHP_EOL;
               //if($i>10)break;
            	echo $i;
            }

            //echo "TOTAL:$i\n";
            fclose($handle);
            //echo rtrim($sql,',');
        }
    	 
    	//$dbsdk->execute($sql);
    	unset($dbsdk);
    }
    /**
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunMonth run
     */
    public function run_register()
    {
    	$dbsdk = $this->load->database('sdk', true);
    	$sql = '';
    	$count = 0;
    	$handle = fopen("/data/log/site/api/Register_20170904.log", "r"); 
    	$i = 0;
    	if ($handle) {
    		while (!feof($handle)) {
    			$buffer = fgets($handle, 4096);
    			$sarr = explode('#', $buffer);
    			$date = strtotime($sarr[0]);
    			/*if($date<1492620300){
    				continue;
    			}
    			if($date>1492628400){
    				break;;
    			}*/
    			$time = date('Ymd',$date);
    			parse_str($sarr[1], $arr);
    			$json = base64_decode($arr['data_raw']);
    			$lastdata = json_decode($json,true);
    			$sql = "INSERT INTO u_register(`serverid`, `channel`, `appid`, `accountid`, client_type,ip,created_at,mac,client_version,regway,reg_date)
    			VALUES ({$lastdata['serverid']},{$lastdata['channel']},10002,{$lastdata['accountid']}
    			,'{$lastdata['client_type']}',".ip2long($arr['ip']).",{$date},'{$lastdata['mac']}','{$lastdata['client_version']}','{$lastdata['regway']}',{$time})
    			ON DUPLICATE KEY UPDATE `channel`=VALUES(channel),`client_type`=VALUES(client_type),`ip`=VALUES(ip),
    			`created_at`=VALUES(created_at),`mac`=VALUES(mac),`reg_date`=VALUES(reg_date),`regway`=VALUES(regway),`client_version`=VALUES(client_version)
    			";
    			$dbsdk->query($sql);
    			echo $sql;
    			echo json_encode($dbsdk->error());
    			$i++;
    			PHP_EOL;
    			//if($i>10)break;
    			echo $i;
    		}
    
    			//echo "TOTAL:$i\n";
    			fclose($handle);
    			//echo rtrim($sql,',');
    		}
    
    		//$dbsdk->execute($sql);
    		unset($dbsdk);
    		}
    		public function run_avgonline()
    		{
    			$this->load->database();
    			$date = "20170828";
    			$dbsdk = $this->load->database('sdk', true);
    			$sql_login = <<<SQL
SELECT appid,serverid,channel,COUNT(distinct accountid) as total_online_num FROM u_login_$date
WHERE appid=10002
GROUP BY serverid,channel
SQL;
    			$query = $dbsdk->query($sql_login);
    			$data1 = array();
    			if($query) $data1 = $query->result_array();
    			foreach ($data1 as $lastdata) {
    				$sql = "INSERT INTO sum_online_avg_day(`serverid`, `channel`, `appid`, `date`, total_online_num)
    				VALUES ({$lastdata['serverid']},{$lastdata['channel']},10002,{$date},{$lastdata['total_online_num']})
    				ON DUPLICATE KEY UPDATE `total_online_num`=VALUES(total_online_num)
    				";
    				$this->db->query($sql);
    			}
    			       
    			//$dbsdk->execute($sql);
    			unset($dbsdk);
    			}
    			
    			public function run_role()
    			{
    				$dbsdk = $this->load->database('sdk', true);
    			$sql = '';
    			$count = 0;
    			$handle = fopen("/data/log/site/api/CreateRole_20170802.log", "r");
    			$i = 0;
    			if ($handle) {
    				while (!feof($handle)) {
    					$buffer = fgets($handle, 4096);
    					$sarr = explode('#', $buffer);
    					$date = strtotime($sarr[0]);
    					/*if($date<1492620300){
    						continue;
    					}
    					if($date>1492628400){
    						break;;
    					}*/
    					$time = date('Ymd',$date);
    					parse_str($sarr[1], $arr);
    					$json = base64_decode($arr['data_raw']);
    					$data = json_decode($json,true);
    					$data['created_at'] = $date;
    					$data['appid'] = 10002;
    					$this->CreateRole($data);
    					$i++;
    					PHP_EOL;
    					//if($i>10)break;
    					echo $i;
    				}
    		
    				//echo "TOTAL:$i\n";
    				fclose($handle);
    				//echo rtrim($sql,',');
    			}
    		
    			//$dbsdk->execute($sql);
    			unset($dbsdk);
    			}
    			/**
    			 * 角色创建-优先入库
    			 */
    			public function CreateRole($data)
    			{
    				$this->data = $data;
    				$this->db = $this->load->database('sdk', true);
    				//1、收到客户端发送的创建角色消息（createrole），检查u_players是否已经存在accountid和channel 一样的数据，如果还没有，则往u_players写入数据
    				$sql = "SELECT id FROM u_players WHERE accountid={$this->data['accountid']} AND channel={$this->data['channel']} LIMIT 1";
    				$query = $this->db->query($sql);
    				if ( $query && $query->row()->id ) {
    					$this->db->insert('u_players', $this->data);
    				}
    				$sql = "SELECT id FROM u_roles WHERE accountid={$this->data['accountid']} AND userid={$this->data['userid']} LIMIT 1";
    				$query = $this->db->query($sql);
    				if ( $query && $query->row()->id ) {
    					return false;
    				}
    				$this->db->insert('u_roles',$this->data);
    				echo json_encode($this->db->error());
    			}
    		
    		/**
    		 * cli模式运行
    		 *
    		 * php /var/www/ci/index.php AutoRunMonth run
    		 */
    		public function run_device()
    		{
    			$dbsdk = $this->load->database('sdk', true);
    			$sql = '';
    			$count = 0;
    			$handle = fopen("/data/log/site/api/DeviceActive_20170911.log", "r");    		                                
    			$i = 0;
    			if ($handle) {
    				while (!feof($handle)) {
    					$buffer = fgets($handle, 4096);
    					$sarr = explode('#', $buffer);
    					$date = strtotime($sarr[0]);
    					$time = date('Ymd',$date);
    					parse_str($sarr[1], $arr);
    					$json = base64_decode($arr['data_raw']);
    					$data = json_decode($json,true);
    					$data['created_at'] = $date;
    					$data['appid'] = 10002;
    					$this->DeviceActive($data);
    					$i++;
    					PHP_EOL;
    					//if($i>10)break;
    					echo $i;
    				//	sleep(1);
    				}
    		
    				//echo "TOTAL:$i\n";
    				fclose($handle);
    				//echo rtrim($sql,',');
    			}
    		
    			//$dbsdk->execute($sql);
    			unset($dbsdk);
    			}
    			public function DeviceActive($data)
    			{
    				$this->data = $data;
    				$this->db = $this->load->database('sdk', true);
    				//$this->save($this->config->item(__FUNCTION__));
    				$res = $this->db->insert('u_device_active', $this->data);
    				//echo json_encode($this->db->error());
    				//写入数据到唯一的设备激活表
    				$chk = "select id from u_device_unique WHERE appid={$this->data['appid']} AND mac='{$this->data['mac']}' LIMIT 1";
    				$qr  = $this->db->query($chk);
    				if (!$qr || !$qr->result()) {
    					$this->db->insert( 'u_device_unique', $this->data);    			
    				}
    			}
    			
    			
    			/**
    			 * cli模式运行
    			 *
    			 * php /var/www/ci/index.php AutoRunMonth run
    			 */
    			public function run_online()
    			{
    				$dbsdk = $this->load->database('sdk', true);
    				$sql = '';
    				$count = 0;
    				$handle = fopen("/data/log/site/api/Online_20170828.log", "r");    				
    				$i = 0;
    				if ($handle) {
    					while (!feof($handle)) {
    						$buffer = fgets($handle, 4096);
    						$sarr = explode('#', $buffer);
    						$date = strtotime($sarr[0]);    						
    						$sarr[1]= str_replace("ip:","ip=",$sarr[1]);    					
    						$sarr[1]= str_replace(";request_method:Online;;data:","&request_method=Online&data=",$sarr[1]);   						
    						$time = date('Ymd',$date);
    						parse_str($sarr[1], $arr);    						
    						$lastdata=json_decode($arr['data'],true);    						
    						$lastdata['ip']=$arr['ip'];    						
    						$sql = "INSERT INTO online(`servername`, `online`, `MaxOnline`, `WorldOnline`, WorldMaxOnline,daytime,gameid,serverid,appid,remote_id,created_at)
    						VALUES ('{$lastdata['servername']}','{$lastdata['online']}','{$lastdata['MaxOnline']}','{$lastdata['WorldOnline']}','{$lastdata['WorldMaxOnline']}'
    						,'{$lastdata['daytime']}','{$lastdata['gameid']}','{$lastdata['serverid']}',10002,".ip2long($arr['ip']).",'{$date}')
    						ON DUPLICATE KEY UPDATE `servername`=VALUES(servername),`online`=VALUES(online),`MaxOnline`=VALUES(MaxOnline),
    						`WorldOnline`=VALUES(WorldOnline),`WorldMaxOnline`=VALUES(WorldMaxOnline),`gameid`=VALUES(gameid),
    						`serverid`=VALUES(serverid),`remote_id`=VALUES(remote_id),`created_at`=VALUES(created_at)
    						";
    						$dbsdk->query($sql);
    					//	echo $sql;
    						echo json_encode($dbsdk->error());
    						$i++;
    						PHP_EOL;    						
    						echo $i;
    					}    			
    					fclose($handle);    				
    				}
    			
    				unset($dbsdk);
    			}
   

}
