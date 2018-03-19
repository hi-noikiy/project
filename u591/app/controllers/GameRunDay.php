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
include APPPATH . 'libraries/mycommon.php';
class GameRunDay extends CI_Controller{

	private $common;
    public function __construct()
    {
    	$this->common = new MyCommon();
        parent::__construct();
    }

    public function run(){
    	$this->synscience();
    }
    
    public function run_test(){
    	$temid = '101533';
    	$date = '20180225';
    	$this->getelves($temid,$date);
    }
    /**
     * 获取精灵数据
     */
    public function getelves($temid,$date)
    {
    	ignore_user_abort(TRUE);
    	$temid = $this->input->get ( 'temid' )?$this->input->get ( 'temid' ):$temid; // 精灵组
    	$date = $this->input->get ( 'date' )?$this->input->get ( 'date' ):$date; // 日期
    	if(!$temid || !$date){
    		exit(0);
    	}
    	$dbsdk = $this->load->database('sdk',true);
    	$delsql = "delete from game_elves where template_id in ($temid)";
    	$dbsdk->query($delsql);
    	$date = date('Ymd',strtotime($date));
    	$data = $this->common->getDbData();
    	foreach ($data as $v){
    		$this->getTemplate($v[0],$v[1],$v[2],$v[3],$temid,$date);
    	}
    }
    private  function getTemplate($database,$start,$num,$pre,$temid,$date){
    	$data = [];
    	$database->reconnect();
    	
    	$now = date('YmdHis');
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "SELECT $date as logindate,$now as logdate,s.player_id,s.template_id,t.`level` as lev,t.vip_level,intimacy_level,t.server_id,(hp_ex2+atk_ex2+def_ex2+spatk_ex2+spdef_ex2+speed_ex2) as ex2 FROM u_eudemon$preser s,
    		(SELECT b.id,a.vip_level,server_id,`level` FROM u_gift_recharge$preser a,(SELECT id,account_id,serverid,`level` FROM u_player$preser WHERE from_unixtime(`login_time`, '%Y%m%d')>=$date) b
    		WHERE a.account_id=b.account_id) t WHERE s.template_id in($temid) and s.player_id=t.id";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){
    				$this->insert_batch("game_elves", $data);
    				unset($data);
    			}
    		}
    	}
    }
    
    
    
    public function getelvesNew()
    {  
    	ignore_user_abort(TRUE);
    	$temid = $this->input->get ( 'temid' )?$this->input->get ( 'temid' ):$temid; // 精灵组
    	$date = $this->input->get ( 'date' )?$this->input->get ( 'date' ):$date; // 日期
   
    	$servertype = $this->input->get('servertype')?$this->input->get ('servertype'):'';
    	if(!$temid || !$date || !$servertype){
    		exit(0);
    	}
    	$date = date('Ymd',strtotime($date));
    	
    	$dbsdk = $this->load->database('sdk',true);
    	$delsql = "delete from game_elves where template_id in ($temid) and (logindate>$date and logindate<$date)";
    	$dbsdk->query($delsql);
    
    //	$data = $this->common->getDbData();
    	
    	
    	$sql = 'select DBName,idserver1 from g_dbconfig';
    	if ($servertype==8){//hun
    		$pokegame1 = $this->load->database('hun1',true);
    		$pokegame2 = $this->load->database('hun2',true);
    		
    		//混服
    		$query = $pokegame1->query($sql);
    		$data = $query->result_array();
    		foreach ($data as $v){
    			if(empty($v['DBName'])){ //第一个库
    				$v['DBName'] = 'pokegame1';
    			}
    			$myservers = explode(',', $v['idserver1']);
    			foreach ($myservers as $value){
    				if(substr($value, 1,1) == 9){ //pk服
    					continue;
    				}
    				$myserver = explode('-', $value);
    				$myserver[0] = intval(substr($myserver[0] , 1));
    				if(!isset($myserver[1])){
    					$myserver[1] = $myserver[0];
    				}else{
    					$myserver[1] = intval(substr($myserver[1] , 1));
    				}
    				$newdata[] = array($$v['DBName'],$myserver[0],$myserver[1],8);
    				$showdata[] = array($myserver[0],$myserver[1],8);
    			}
    		}
    		
    		if(empty($newdata)){
    			$newdata= array($pokegame1,1,10,8);
    		}
    		
    		foreach ($newdata as $v){
    			$this->getTemplateNew($v[0],$v[1],$v[2],$v[3],$temid,$date);
    		}
    		 
    	}elseif ($servertype==6){//yinhe
    		$pokegame1mha = $this->load->database('yinghe',true);
    		$pokegame2mha = $this->load->database('yinghe2',true);
    		
    		
    		$query = $pokegame1mha->query($sql);
    		$data = $query->result_array();
    		foreach ($data as $v){
    			if(empty($v['DBName'])){ //第一个库
    				$v['DBName'] = 'pokegame1mha';
    			}
    			$myservers = explode(',', $v['idserver1']);
    			foreach ($myservers as $value){
    				if(substr($value, 1,1) == 9){ //pk服
    					continue;
    				}
    				$myserver = explode('-', $value);
    				$myserver[0] = intval(substr($myserver[0] , 1));
    				if(!isset($myserver[1])){
    					$myserver[1] = $myserver[0];
    				}else{
    					$myserver[1] = intval(substr($myserver[1] , 1));
    				}
    				$newdata[] = array($$v['DBName'],$myserver[0],$myserver[1],6);
    				$showdata[] = array($myserver[0],$myserver[1],6);
    			}
    		}
    		if(empty($newdata)){
    			$newdata=array($pokegame1mha,1,10,6);
    		}
    		
    		foreach ($newdata as $v){
    			$this->getTemplateNew($v[0],$v[1],$v[2],$v[3],$temid,$date);
    		}
    		 
    	}elseif ($servertype==3){//yinyongbao
    		$yingyongbao = $this->load->database('yingyongbao',true);
    		$newdata[] = array($yingyongbao,1,90,3);
    		foreach ($newdata as $v){
    			$this->getTemplateNew($v[0],$v[1],$v[2],$v[3],$temid,$date);
    		}
    	}elseif ($servertype==15){//p8 andro
    		
    		
    		$p8android = $this->load->database('p8android',true);
    		$newdata[] = array($p8android,1,2,15);
    		foreach ($newdata as $v){
    			$this->getTemplateNew($v[0],$v[1],$v[2],$v[3],$temid,$date);
    		}
    		
    
    		
    		 
    	}elseif ($servertype==5){//p8ios
    		$pokegame1p800 = $this->load->database('p8ios1',true);
    		$pokegame2p800 = $this->load->database('p8ios2',true);
    		$pokegame3p800 = $this->load->database('p8ios3',true);
    		
    		$newdata[]=array($pokegame1p800,1,370,5);
    		foreach ($newdata as $v){
    			$this->getTemplateNew($v[0],$v[1],$v[2],$v[3],$temid,$date);
    		}
    		 
    		$newdata2[]=array($pokegame2p800,1,370,5);
    		foreach ($newdata2 as $v){
    			$this->getTemplateNew($v[0],$v[1],$v[2],$v[3],$temid,$date);
    		}
    		
    		$newdata3[]=array($pokegame3p800,1,370,5);
    		foreach ($newdata3 as $v){
    			$this->getTemplateNew($v[0],$v[1],$v[2],$v[3],$temid,$date);
    		}
    		
    		
    	}elseif ($servertype==14){// 虎牙  14
    	   
    	}
    	
    
   
    }
    public  function getTemplateNew($database,$start,$num,$pre,$temid,$date){
    	$data = [];
    	$database->reconnect();
    	 
    	$now = date('YmdHis');
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "SELECT $date as logindate,$now as logdate,s.player_id,s.template_id,t.`level` as lev,t.vip_level,intimacy_level,t.server_id,(hp_ex2+atk_ex2+def_ex2+spatk_ex2+spdef_ex2+speed_ex2) as ex2 FROM u_eudemon$preser s,
    		(SELECT b.id,a.vip_level,server_id,`level` FROM u_gift_recharge$preser a,(SELECT id,account_id,serverid,`level` FROM u_player$preser WHERE from_unixtime(`login_time`, '%Y%m%d')>=$date) b
    		WHERE a.account_id=b.account_id) t WHERE s.template_id in($temid) and s.player_id=t.id";
    		$query = $database->query($sql);
    	
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){
    				$this->insert_batch("game_elves", $data);
    				unset($data);
    			}
    		}
    	}
    }
    
    
    /**
     * 获得坐骑数据
     * cli模式运行
     *
     * @author 王涛 20170921
     */
    public function horse()
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->gethorse($v[0],$v[1],$v[2],$v[3]);
    	}
    }
    private  function gethorse($database,$start,$num,$pre){
    	$data = [];
    	$database->reconnect();
    	$now = date('Ymd',strtotime('-1 days'));
    	$begintime = strtotime($now);
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "SELECT t.vip_level,horse_id,COUNT(*) horse_num,$now as logdate,$serverid as serverid FROM `u_player_rider$preser` a,
(SELECT b.id,vip_level FROM u_player$preser b,u_gift_recharge$preser c WHERE b.account_id=c.account_id AND from_unixtime(b.login_time, '%Y%m%d')>$now) t
WHERE a.player_id=t.id GROUP BY t.vip_level,a.horse_id";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){
    			$this->insert_batch("game_horse", $data);
    				unset($data);
    			}
    		}
		}
	}
    /**
     * 获得月卡数据
     * cli模式运行
     *
     * @author 王涛 20170825
     */
    public function monthcard()
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->getmonthcard($v[0],$v[1],$v[2],$v[3]);
    	}
    }
    private  function getmonthcard($database,$start,$num,$pre){
    	$data = [];
    	$database->reconnect();
    	$now = date('Ymd',strtotime('-1 days'));
    	$begintime = strtotime($now);
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "SELECT vip_level,COUNT(*) as usual,b.serverid,$now as logdate from u_playerdata$preser a,(SELECT id,account_id,serverid FROM u_player$preser where login_time>=$begintime) b,u_gift_recharge$preser c 
WHERE a.id=b.id and mouthcard_endtime>=$begintime and b.account_id=c.account_id
GROUP BY c.vip_level";//普通月卡
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){
    				$this->insert_batch("game_monthcard", $data);
    				unset($data);
    			}
    		}
    		$sql = "SELECT c.vip_level,COUNT(*) as hunting,b.serverid,$now as logdate from u_statusex$preser a,(SELECT id,account_id,serverid FROM u_player$preser where login_time>=$begintime) b,u_gift_recharge$preser c
    		WHERE a.owner_id=b.id and a.end_time>=$begintime and b.account_id=c.account_id
    		GROUP BY c.vip_level";//狩猎月卡
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){
    			$this->insert_batch("game_monthcard", $data);
    			unset($data);
    			}
    		}
    		$sql = "SELECT vip_level,COUNT(*) as lifetime,b.serverid,$now as logdate from u_playerdata$preser a,(SELECT id,account_id,serverid FROM u_player$preser where login_time>=$begintime) b,u_gift_recharge$preser c
    		WHERE a.id=b.id and lifetime_card=2 and b.account_id=c.account_id
    		GROUP BY c.vip_level";//终身月卡
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){
    				$this->insert_batch("game_monthcard", $data);
    				unset($data);
    			}
    		}
    	}
    }
    /**
     * 获得社团争霸玩家信息
     * cli模式运行
     *
     * @author 王涛 20170908
     */
    public function syn()
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->getsyn($v[0],$v[1],$v[2],$v[3]);
    	}
    }
    private  function getsyn($database,$start,$num,$pre){
    	$data = [];
    	$database->reconnect();
    	$now = date('Ymd',strtotime('-1 days'));
    	$begintime = strtotime($now);
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "(SELECT atk_player_id as player_id,atk_player_name as player_name,atk_game_server as game_server,atk_syn_id as syn_id,atk_syn_name as syn_name  
    				FROM `u_synpkgame_history$preser` GROUP BY atk_player_id) union 
    				(SELECT def_player_id as player_id,def_player_name as player_name,def_game_server as game_server,def_syn_id as syn_id,def_syn_name as syn_name  
    				FROM `u_synpkgame_history$preser` GROUP BY def_player_id)";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){
    				$this->insert_batch("game_syn", $data);
    				unset($data);
    			}
    		}
    	}
    }
    /**
     * 获得时装数据
     * cli模式运行
     *
     * @author 王涛 20170620
     */
    public function fashion()
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->getfashion($v[0],$v[1],$v[2],$v[3]);
    	}
    }
    private  function getfashion($database,$start,$num,$pre){
    	$data = [];
    	$database->reconnect();
    	$now = date('Ymd',strtotime('-1 days'));
    	$begintime = strtotime($now);
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "SELECT a.player_id,a.fashion_type,a.end_time,b.server_id,b.vip_level FROM `u_player_fashion$preser` a,u_gift_recharge$preser b,u_player$preser c WHERE a.player_id=c.id AND c.account_id=b.account_id;";
    		$query = $database->query($sql);
    		if($query){
    		$data = $query->result_array();
    		if(!empty($data)){
    		$this->insert_batch("game_player_fashion", $data);
    		unset($data);
    		}
    		}
    		}
    		}
    /**
     * 获得精灵数据
     * cli模式运行
     *
     * @author 王涛 20170606
     */
    public function eudemon()
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->geteudemon($v[0],$v[1],$v[2],$v[3]);
    	}
    }
    private  function geteudemon($database,$start,$num,$pre){
    	$data = [];
    	$database->reconnect();
    	$now = date('Ymd',strtotime('-1 days'));
    	$begintime = strtotime($now);
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "SELECT b.account_id,b.serverid,c.vip_level,a.template_id FROM `u_eudemon$preser` a,u_player$preser b,u_gift_recharge$preser c
    				 WHERE a.template_id=100974 and a.player_id=b.id AND b.account_id=c.account_id";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){
    				$this->insert_batch("game_eudemon", $data);
    				unset($data);
    			}
    		}
    	}
    }
    /**
     * 活跃玩家数据
     * cli模式运行
     *
     * @author 王涛 20170525
     */
    public function synscience()
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->getsynscience($v[0],$v[1],$v[2],$v[3]);
    	}
    }
    private  function getsynscience($database,$start,$num,$pre){
    	$data = [];
    	$database->reconnect();
    	$now = date('Ymd',strtotime('-1 days'));
    	$begintime = strtotime($now);
    	$Ym = date('Ym',strtotime('-1 days'));
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "SELECT a.player_id,a.group_id,a.level,b.account_id,b.serverid,c.vip_level,$now as logdate FROM `u_player_synscience$preser` a,u_player$preser b,u_gift_recharge$preser c 
    		WHERE a.player_id=b.id AND b.login_time>=$begintime AND b.account_id=c.account_id";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){
    				$this->insert_batch("game_synscience_$Ym", $data);
    				unset($data);
    			}
    		}
    		//
    		$field = '';
    		for($j=1;$j<=40;$j++){
    			$field .= "a.currency$j,";
    		}
    		$sql = "SELECT $field b.account_id,b.serverid,c.vip_level,$now as logdate FROM `u_player_currency$preser` a,u_player$preser b,u_gift_recharge$preser c 
    		WHERE a.id=b.id AND b.login_time>=$begintime  AND b.account_id=c.account_id";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){
    				$this->insert_batch("game_currency_$Ym", $data);
    				unset($data);
    			}
    		}
    		
    		//
    		$sql = "SELECT a.adventurelev,a.active_tasknum,a.rank1_num,a.rank2_num,a.rank3_num,a.rank4_num,a.rank5_num,a.rank6_num,a.player_id,b.account_id,b.serverid,c.vip_level ,$now as logdate FROM `u_playeradventure_level$preser` a,u_player$preser b,u_gift_recharge$preser c 
    				WHERE a.player_id=b.id AND b.login_time>=$begintime  AND b.account_id=c.account_id";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){
    				$this->insert_batch("game_adventure_leve_$Ym", $data);
    				unset($data);
    			}
    		}
    	}
    }
    /**
     * 创世徽章统计
     * cli模式运行
     *
     * @author 王涛 20170525
     */
    public function stone()
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->getstone($v[0],$v[1],$v[2],$v[3]);
    	}
    }
    private  function getstone($database,$start,$num,$pre){
    	$data = [];
    	$database->reconnect();
    	$now = date('Ymd',strtotime('-1 days'));
    	$begintime = strtotime($now);
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "select a.player_id,a.stonetype,a.stonestep,a.attr1lv hp,a.attr2lv attack_p,a.attr3lv defense_p,a.attr4lv attack_s,a.attr5lv defense_s,a.attr6lv speed,c.vip_level,b.account_id,b.serverid,$now as logdate
    		from u_player_stone$preser a,u_player$preser b,u_gift_recharge$preser c WHERE a.player_id=b.id
    		AND b.login_time>=$begintime  AND b.account_id=c.account_id";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){
    				$this->insert_batch("game_stone", $data);
    				unset($data);
    			}
    		}
    	}
    }
    /**
     * 全球对战段位分布
     * cli模式运行
     *
     * @author 王涛 20170418
     */
    public function dan()
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->getdan($v[0],$v[1],$v[2],$v[3]);
    	}
    }
    private  function getdan($database,$start,$num,$pre){
    	$data = $edata = [];
    	$database->reconnect();
    	//$dbsdk = $this->load->database('sdk',true);
    	$now = date('Ymd',strtotime('-1 days'));
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "select a.id playerid,a.account_id,a.serverid,a.name,a.level,
    		b.vip_level,
    		c.season,c.com_totaltimes,c.com_wintimes,c.com_ranklev,c.elite_totaltimes,c.elite_wintimes,c.elite_ranklev 
    		from u_player$preser a LEFT JOIN u_gift_recharge$preser b on a.account_id=b.account_id
    		inner join u_player_pkgame$preser c on a.id=c.player_id inner JOIN (select max(season) maxseason from u_player_pkgame$preser) h on c.season=h.maxseason";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(empty($data)){
    				continue;
    			}
    			$sql = "select b.player_id playerid,$serverid as serverid,a.template_id eud,(hp_ex1+atk_ex1+def_ex1+spatk_ex1+spdef_ex1+speed_ex1) ex1,(hp_ex2+atk_ex2+def_ex2+spatk_ex2+spdef_ex2+speed_ex2) ex2,
    				a.intimacy_level intilv,c.level booklv
    				from u_eudemon$preser a 
inner JOIN u_player_pkgame$preser b on a.player_id=b.player_id
inner JOIN (select max(season) maxseason from u_player_pkgame$preser) h on b.season=h.maxseason 
inner JOIN u_player_fightgroup$preser f on a.id=f.idEudemonType1 and f.group_index=1
    				inner join u_player_handbook$preser c on b.player_id=c.player_id 
and floor(a.template_id/10)=c.ngroup ";
    			for($ii=2;$ii<=6;$ii++){
    				$sql .= " union select b.player_id playerid,$serverid as serverid,a.template_id eud,(hp_ex1+atk_ex1+def_ex1+spatk_ex1+spdef_ex1+speed_ex1) ex1,(hp_ex2+atk_ex2+def_ex2+spatk_ex2+spdef_ex2+speed_ex2) ex2,
    				a.intimacy_level intilv,c.level booklv
    				from u_eudemon$preser a
    				inner JOIN u_player_pkgame$preser b on a.player_id=b.player_id
    				inner JOIN (select max(season) maxseason from u_player_pkgame$preser) h on b.season=h.maxseason
    				inner JOIN u_player_fightgroup$preser f on a.id=f.idEudemonType$ii  and f.group_index=1
    				inner join u_player_handbook$preser c on b.player_id=c.player_id
    				and floor(a.template_id/10)=c.ngroup ";
    			}
    			//echo $sql;
    			$query = $database->query($sql);
    			if($query){
    				$edata = $query->result_array();
    			}
    			$this->insert_batch("game_world_user_$now", $data);    		
    			$this->insert_batch("game_world_eudemon_$now", $edata);    		
    			
    		}else{
    			continue;
    		}
    	}
    	unset($data);
    }
    /**
     * 关卡进度统计
     * cli模式运行
     *
     * @author 王涛 20170410
     */
    public function process()
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->getprocess($v[0],$v[1],$v[2],$v[3]);
    	}
    }
    private  function getprocess($database,$start,$num,$pre){
    	$data = [];
    	$database->reconnect();
    	//$dbsdk = $this->load->database('sdk',true);
    	$now = date('Ymd',strtotime('-1 days'));
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "select a.id player_id,a.account_id,a.serverid,a.name,a.level,a.login_time,a.logout_time,a.create_time,b.vip_level
    				from u_player$preser a LEFT JOIN u_gift_recharge$preser b on a.account_id=b.account_id 
    				  GROUP BY a.account_id";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(empty($data)){
    				continue;
    			}
    			$this->insert_batch("game_process_$now", $data); 
    			$sql = "select a.player_id,$serverid as serverid,if(progress_num_1>0,right(nGroup,3)+0,0) maxGroup ,LENGTH(progress_num_1) progress_num,if(left(progress_num_1,1)=9,1,0) process_status
from u_player_progress$preser a 
inner join (SELECT player_id,type,MAX(nGroup) as maxGroup FROM `u_player_progress$preser` where type=1 and (progress_num_1>0 or nGroup=3001001) GROUP BY player_id )b on a.player_id=b.player_id and a.type=b.type and a.nGroup=b.maxGroup";
    			$query = $database->query($sql);
    			if($query){
    				$data = $query->result_array();
    				$this->insert_batch("game_process_$now", $data);
    			}
    			$sql = "select a.player_id,$serverid as serverid,if(progress_num_1>0,right(nGroup,3)+0,0) maxGroup2 ,LENGTH(progress_num_1) progress_num2,if(left(progress_num_1,1)=9,1,0) process_status2
from u_player_progress$preser a
inner join (SELECT player_id,type,MAX(nGroup) as maxGroup FROM `u_player_progress$preser` where type=2 and (progress_num_1>0 or nGroup=3001001) GROUP BY player_id )b on a.player_id=b.player_id and a.type=b.type and a.nGroup=b.maxGroup";
    			$query = $database->query($sql);
    			if($query){
    				$data = $query->result_array();
    				$this->insert_batch("game_process_$now", $data);
    			}
    		}else{
    			continue;
    		}
    	}
    	unset($data);
    }
    /**
     * 统计精灵
     * cli模式运行
     *
     * @author 王涛 20170303
     */
    public function EudemonCount($tm=0)
    {
    	$data = $this->common->getDbData();;
    	$lists = include APPPATH .'/config/count_list.php'; //道具字典
    	foreach ($data as $v){
    		$this->geteudemondata($lists['eudemons'],$v[0],$v[1],$v[2],$v[3]);
    		
    	}
    }
    private  function geteudemondata($eudemon,$database,$start,$num,$pre){
    	$data = [];
    	$database->reconnect();
    	$dbsdk = $this->load->database('sdk',true);
    	for($i=$start;$i<=$num;$i++){
    		$serverid = $pre.str_pad($i,3,0,STR_PAD_LEFT);
    		$sql = "SELECT template_id,count(distinct player_id) as cid from u_eudemon".str_pad($i,3,0,STR_PAD_LEFT) ." where template_id in (".implode(',', $eudemon).") group by template_id";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(empty($data)){
    				continue;
    			}
    			$now = date('Ymd',strtotime('-1 days'));
    			$insertsql = "insert into u_eudemon(serverid,eudemon,num,logdate) values";
    			foreach ($data as $v){
    				$insertsql .= "($serverid,{$v['template_id']},{$v['cid']},$now),";
    			}
    			$insertsql = rtrim($insertsql,',') . " ON DUPLICATE KEY UPDATE num=values(num)";
    			$dbsdk->query($insertsql);
    		}else{
    			continue;
    		}
    	}
    	unset($dbsdk,$data);
    }
    
    /**
     * 统计服务器剩余钻石前50名
     * cli模式运行
     *
     * @author 王涛 20170306
     */
    public function EmoneyServer50($tm=0)
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->getdata50($v[0],$v[1],$v[2],'u_playershare','emoney',$v[3]);
    	}
    }
    /**
     * 统计服务器剩余金币前50名
     * cli模式运行
     *
     * @author 王涛 20170306
     */
    public function MoneyServer50($tm=0)
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->getdata50($v[0],$v[1],$v[2],'u_player','money',$v[3]);
    	}
    }
  
    /**
     * 统计金币，钻石前50名
     * @param unknown $database
     * @param unknown $start
     * @param unknown $num
     * @param unknown $table
     * @param unknown $field
     */
    private  function getdata50($database,$start,$num,$table,$field,$pre){
    	$data = [];
    	$database->reconnect();
    	$dbsdk = $this->load->database('sdk',true);
    	$now = date('Ymd',strtotime('-1 days'));
    	$no = strtotime($now);
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "SELECT b.account_id,b.$field FROM `u_player$preser` a,u_playershare$preser b WHERE a.account_id=b.account_id and a.login_time>$no order by b.$field desc limit 50";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(empty($data)){
    				continue;
    			}
    			$insertsql = "insert into game_rank_$field(accountid,serverid,$field,logdate) values";
    			foreach ($data as $v){
    				$insertsql .="({$v['account_id']},{$serverid},{$v[$field]},$now),";
    			}
    			$insertsql = rtrim($insertsql,',')." ON DUPLICATE KEY UPDATE $field=values($field)";
    			//echo $insertsql;die;
    			$dbsdk->query($insertsql);
    			//echo json_encode($dbsdk->error());
    		}else{
    			continue;
    		}
    	}
    	unset($dbsdk,$data);
    }
    /**
     * 统计服务器剩余钻石
     * cli模式运行
     *
     * @author 王涛 20170306
     */
    public function EmoneyCount($tm=0)
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->getdata($v[0],$v[1],$v[2],'u_playershare','emoney');
    		$this->getactivedata($v[0],$v[1],$v[2],'u_playershare','emoney',$v[3]);
    		$this->getvipdata($v[0],$v[1],$v[2],'u_playershare','emoney',$v[3]);
    	}
    }
    /**
     * 统计服务器剩余金币
     * cli模式运行
     *
     * @author 王涛 20170306
     */
    public function MoneyCount($tm=0)
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		$this->getdata($v[0],$v[1],$v[2],'u_player','money');
    		//$this->getactivedata($v[0],$v[1],$v[2],'u_player','money',$v[3]);
    	}
    }
    /**
     * 统计剩余金币，钻石
     * @param unknown $database
     * @param unknown $start
     * @param unknown $num
     * @param unknown $table
     * @param unknown $field
     */
    private  function getdata($database,$start,$num,$table,$field){
    	$data = [];
    	$database->reconnect();
    	$dbsdk = $this->load->database('sdk',true);
    	$field1 = 'serverid';
    	if($table == 'u_playershare'){
    		$field1 = 'server_id';
    	}
    	for($i=$start;$i<=$num;$i++){
    
    		$sql = "SELECT $field1,sum($field) as $field from $table".str_pad($i,3,0,STR_PAD_LEFT);
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(empty($data)){
    				continue;
    			}
    			$now = date('Ymd',strtotime('-1 days'));
    			$insertsql = "insert into u_server_emoney(serverid,$field,logdate) values({$data[0][$field1]},{$data[0][$field]},$now)
    			ON DUPLICATE KEY UPDATE $field=values($field)";
    			$dbsdk->query($insertsql);
    		}else{
    			continue;
    		}
    	}
    	unset($dbsdk,$data);
    }
    /**
     * 统计活跃玩家剩余金币，钻石
     * @param unknown $database
     * @param unknown $start
     * @param unknown $num
     * @param unknown $table
     * @param unknown $field
     */
    private  function getactivedata($database,$start,$num,$table,$field,$pre){
    	$data = [];
    	$database->reconnect();
    	$dbsdk = $this->load->database('sdk',true);
    	$now = date('Ymd',strtotime('-1 days'));
    	$no = strtotime($now);
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "SELECT SUM(b.$field)as $field FROM `u_player$preser` a,u_playershare$preser b WHERE a.account_id=b.account_id and a.login_time> $no";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(empty($data)){
    				continue;
    			}
    			$insertsql = "insert into u_server_emoney_active(serverid,$field,logdate) values({$serverid},{$data[0][$field]},$now)
    			ON DUPLICATE KEY UPDATE $field=values($field)";
    			$dbsdk->query($insertsql);
    		}else{
    			continue;
    		}
    	}
    	unset($dbsdk,$data);
    }
    /**
     * 统计活跃玩家剩余金币，钻石
     * @param unknown $database
     * @param unknown $start
     * @param unknown $num
     * @param unknown $table
     * @param unknown $field
     */
    private  function getvipdata($database,$start,$num,$table,$field,$pre){
    	$data = [];
    	$database->reconnect();
    	$dbsdk = $this->load->database('sdk',true);
    	$now = date('Ymd',strtotime('-1 days'));
    	//$now = 20170522;
    	$no = strtotime($now);
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "SELECT $serverid as serverid,SUM(b.$field)as $field,count(*) caccount,c.vip_level,$now as logdate FROM `u_player$preser` a,
    		u_playershare$preser b,u_gift_recharge$preser c WHERE a.account_id=b.account_id and a.login_time> $no AND a.account_id=c.account_id group by c.vip_level";
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(empty($data)){
    				continue;
    			}
				$this->insert_batch('u_server_emoney_vip', $data);
    		}else{
    			continue;
    		}
    	}
    	unset($dbsdk,$data);
    }
  
    private function getDbTable($serverid,$pre='u_playershare'){
    	$gameserver = include APPPATH .'/config/game_server_list.php'; //道具字典
    	foreach ($gameserver as $v){
    		if($serverid>=$v[0]&&$serverid<=$v[1]){
    			$data['db'] = $this->load->database($v[2], TRUE);
    			$p = $serverid%100;
    			$data['table'] = $pre.str_pad($p,3,0,STR_PAD_LEFT);
    			continue;
    		}
    	}
    	return $data;
    }
    /**
     * 精灵塔通关阵容
     * @param unknown $database
     * @param unknown $start
     * @param unknown $num
     * @param unknown $table
     * @param unknown $field
     */
    private  function getsquad($database,$start,$num){
    	$data = [];
    	$database->reconnect();
    	$dbsdk = $this->load->database('sdk',true);
    	$table = "u_pve_fightgroup";
    	$field = "template_id,totalpower,server_id,username,eud_id1,eud_id2,eud_id3,eud_id4,eud_id5,eud_id6";
    	for($i=$start;$i<=$num;$i++){
    		$sql = "SELECT $field from $table".str_pad($i,3,0,STR_PAD_LEFT);
    		$query = $database->query($sql);
    		if($query){
    			$data = $query->result_array();
    			if(empty($data)){
    				continue;
    			}
    			$insertsql = "insert into game_squad_eudemon($field) values";
    			foreach ($data as $v){
    				$insertsql .= "({$v['template_id']},{$v['totalpower']},{$v['server_id']},'{$v['username']}',{$v['eud_id1']},{$v['eud_id2']},{$v['eud_id3']},{$v['eud_id4']},{$v['eud_id5']},{$v['eud_id6']}),";
    			}
    			$dbsdk->query(rtrim($insertsql,','));
    			echo json_encode($dbsdk->error());
    		}else{
    			continue;
    		}
    	}
    	unset($dbsdk,$data);
    }
    public function SquadCount($tm=0)
    {
    	$data = $this->common->getDbData();;
    	foreach ($data as $v){
    		//$this->getsquad($v[0],$v[1],$v[2]);
    	}
    }
    
    /*
     * 活跃玩家充值积分统计  zzl 20170907
     */
    public function playerdata()
    {
        $data = $this->common->getDbData();;
        foreach ($data as $v){
            $this->getplayerdata($v[0],$v[1],$v[2],$v[3]);
        }
    }
    /*
     * 活跃玩家充值积分统计  zzl 20170907
     */
    public   function getplayerdata($database,$start,$num,$pre){
    	$data = [];
    	$database->reconnect();
    	$now = date('Ymd',strtotime('-1 days'));
    	$begintime = strtotime($now);
    	for($i=$start;$i<=$num;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = $pre.$preser;
    		echo $serverid.PHP_EOL;
    		$sql = "SELECT vip_level,COUNT(*) as usual,b.serverid,recharge_mark,$now as logdate from u_playerdata$preser a,(SELECT id,account_id,serverid FROM u_player$preser where login_time>=$begintime) b,u_gift_recharge$preser c 
WHERE a.id=b.id and mouthcard_endtime>=$begintime and b.account_id=c.account_id
GROUP BY c.vip_level";
    		$query = $database->query($sql);
    		
    		if($query){
    			$data = $query->result_array();
    			if(!empty($data)){    				
    				$db = $this->load->database('sdk',true);
    				$db->insert_batch('u_playerdata', $data);    			
    				unset($data);
    			}
    		}

    	}
    }
    
    
    /**
     * 获得社团争霸完整信息
     * cli模式运行
     *
     * @author zzl 20170927
     */
    public function synHistory()
    {   
        
        $db = $this->load->database('sdk',true);      
        $data = $this->common->getDbData(); 
        if($data){
        	$sql="DELETE from synpkgame_history";  //u_synpkgame_history 没时间，所以每次重新获取
        	$db->query($sql);
       
        foreach ($data as $v){
            $this->getsynHistory($v[0],$v[1],$v[2],$v[3]);
        }
         }
    }
    private  function getsynHistory($database,$start,$num,$pre){    
    $start=1;
        $num=1000; 
        $data = [];
        $database->reconnect();
        $now = date('Ymd',strtotime('-1 days'));
        $begintime = strtotime($now);
        for($i=$start;$i<=$num;$i++){
            $preser = str_pad($i,3,0,STR_PAD_LEFT);
            $serverid = $pre.$preser;
            echo $serverid.PHP_EOL;
            $time1= strtotime(date('Ymd',strtotime('-1 days')));     
            $time2= strtotime(date('Ymd',strtotime('-0 days')));

            echo    $sql="(SELECT s.pk_th,s.atk_player_id as player_id,s.atk_player_name as player_name,s.atk_game_server as game_server,s.atk_syn_id as syn_id,s.atk_syn_name as syn_name,g.vip_level from u_synpkgame_history$preser as  s left JOIN u_player$preser as p on p.id=s.atk_player_id left JOIN u_gift_recharge$preser as g on g.account_id=p.account_id GROUP BY atk_player_id)  union (SELECT s.pk_th,s.def_player_id as player_id,s.def_player_name as player_name,s.def_game_server as game_server,s.def_syn_id as syn_id,s.def_syn_name as syn_name,g.vip_level from u_synpkgame_history$preser as  s left JOIN u_player$preser as p on p.id=s.atk_player_id left JOIN  u_gift_recharge$preser as g on g.account_id=p.account_id  GROUP BY def_player_id)";
             
            
            $query = $database->query($sql);
            if($query){
                $data = $query->result_array();
                if(!empty($data)){
                    $this->insert_batch("synpkgame_history", $data);
                    unset($data);
                }
            }
        }
    }
    
    
    /*
     * 社团争霸赛  二组
     */
    public function synpvpgameUser()
    {
        $db = $this->load->database('sdk',true);
     
    
        $data = $this->common->getDbData();;
        if( $data){
        	  $sql="DELETE from u_synpvpgame_userpart";
        	$db->query($sql);
        	foreach ($data as $v){
        		$this->getSynpvpgameUser($v[0],$v[1],$v[2],$v[3]);
        	}
        }
       
    }
    private  function getSynpvpgameUser($database,$start,$num,$pre){
        $data = [];
        $database->reconnect();
        $now = date('Ymd',strtotime('-1 days'));
        $begintime = strtotime($now);
        $start=0;
        $num=1000;        
   
      
        for($i=$start;$i<=$num;$i++){
            $preser = str_pad($i,3,0,STR_PAD_LEFT);
            $serverid = $pre.$preser;
            echo $serverid.PHP_EOL;

            echo    $sql="SELECT s.updata_time,s.pk_th,s.player_id as player_id,s.player_name as player_name,s.game_server as game_server,s.syn_id as syn_id,s.syn_name as syn_name,g.vip_level,p.account_id as player_account_id from u_synpvpgame_userpart$preser as  s left JOIN u_player$preser as p on p.id=s.player_id left JOIN u_gift_recharge$preser as g on g.account_id=p.account_id  GROUP BY player_id";
             
            $query = $database->query($sql);
            if($query){
                $data = $query->result_array();
                if(!empty($data)){
                    $this->insert_batch("u_synpvpgame_userpart", $data);
                    unset($data);
                }
            }
         
            
            
/*              echo    $sql="select id,id as player_id,account_id,logout_time,login_time,create_time from u_player$preser";
             
            $query = $database->query($sql);
            if($query){
                $data = $query->result_array();
                if(!empty($data)){
                    $this->insert_batch("game_player", $data);
                    unset($data);
                }
            } */
           
            
/*                echo    $sql="SELECT account_id,vip_level from u_gift_recharge$preser";
            
            $query = $database->query($sql);
            if($query){
                $data = $query->result_array();
                if(!empty($data)){
                    $this->insert_batch("game_gift_recharge", $data);
                    unset($data);
                }
            }  */
         
            
        }
    }   
    
   
    
    
    

}
