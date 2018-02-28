<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * @author 王涛
 *
 */

class MyCommon extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getDbData(){
		$this->load->library('session');
		/*$gameservertime = $_SESSION['gameservertime'];
		
		if($gameservertime>time()){
			$newdata = $_SESSION['gameserver'] ;
			if($newdata){
				return unserialize($newdata);
			}	
		}*/
    	$pokegame1p800 = $this->load->database('p8ios1',true);
    	$pokegame2p800 = $this->load->database('p8ios2',true);
    	$pokegame3p800 = $this->load->database('p8ios3',true);
    	$p8android = $this->load->database('p8android',true);
    	$pokegame1 = $this->load->database('hun1',true);
    	$pokegame2 = $this->load->database('hun2',true);
    	$pokegame1mha = $this->load->database('yinghe',true);
    	$pokegame2mha = $this->load->database('yinghe2',true);
    	$yingyongbao = $this->load->database('yingyongbao',true);
    	$newdata = $showdata =array();
    	$sql = 'select DBName,idserver1 from g_dbconfig';
    	
    	//p8ios
    	$query = $pokegame1p800->query($sql);
    	$data = $query->result_array();

    	foreach ($data as $v){
    		if(empty($v['DBName'])){ //第一个库
    			$v['DBName'] = 'pokegame1p800';
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
    			
    			$newdata[] = array($$v['DBName'],$myserver[0],$myserver[1],5);
    			$showdata[] = array($myserver[0],$myserver[1],5);
    		}	
    	}

    	//p8安卓
    	$newdata[] = array($p8android,1,2,15);
    	
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
    	
    	//硬核
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
    	
    	//应用宝
    	$newdata[] = array($yingyongbao,1,90,3);
    	$_SESSION['gameserver'] = serialize($newdata);
    	$_SESSION['gameservertime'] = time()+60*60;
    	return $newdata;
    	/*return array(
    			array($p8ios1,1,50,5),
    			array($p8ios1,91,100,5),
    			array($p8ios2,51,90,5),
    			array($p8ios2,151,160,5),
    			array($p8ios2,171,180,5),
    			array($p8ios3,101,150,5),
    			array($p8ios3,161,170,5),
    			array($p8android,1,2,15),
    			array($hun1,1,19,8),
    			array($hun1,51,60,8),
    			array($hun1,91,110,8),
    			array($hun2,20,50,8),
    			array($hun2,61,90,8),
    			array($yinghe,1,79,6),
    			array($yinghe2,80,90,6),
    			array($yingyongbao,1,90,3)
    			//array($p8ios,1,1,5),
    	);*/
    }

}
