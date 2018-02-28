<?php
ini_set ( 'display_errors', 'On' );
/**
 * Created by PhpStorm.
 * User: guangpeng
 * Date: 8/15-015
 * Time: 21:02
 * 系统功能统计
 */
include 'MY_Controller.php';
ini_set ( 'memory_limit', '1024M' );
class SystemFunction extends MY_Controller {
	/**
	 *
	 * @var $SystemFunction_model SystemFunction_Model
	 */
	public $SystemFunction_model;
	public function __construct() {
		parent::__construct ();
		// $this->load->model('player_analysis_model');
		$this->load->model ( 'SystemFunction_model' );
		$this->SystemFunction_model->setAppid ( $this->appid );
	}
	/**
	 * 社团每日捐献统计
	 */
	public function donate() {
	
	
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			$where ['Ym']=date ( 'Ym', strtotime ( $date ) );
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['enddate'] = date ( 'Ymd', strtotime ( $date2 ) );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['type_id'] = $this->input->get ( 'type_id' );
			$field='count(DISTINCT a.accountid) as participation,a.vip_level,count(if(b.item_id=1 and  b.type=1,true,null)) as total_1,count(if(b.item_id=3 and b.item_num=20 and b.type=1,true,null)) as total_20,count(if(b.item_id=3 and b.item_num=100 and b.type=1,true,null)) as total_100, sum(if(a.act_id=21 and b.type=0 ,item_num,null)) as total_donate,sum(if(b.item_id=3 and b.type=1,item_num,null)) as total_diamond';
			$this->load->model ( 'Data_analysis_model' );
			$logininfo = $this->Data_analysis_model->viplogin ( $where );
	
			$table = '';
			$this->load->model ( 'Data_analysis_model' );
			$data = $this->Data_analysis_model->donate( $where,$field );			
			foreach ( $logininfo ['day0'] as $k => &$v ) {
				$v ['gold_avg']=$v ['diamond_avg'] =$v ['bounty_avg']=$v ['donate_avg']=$v ['avg_expenditure'] =0;
				foreach ( $data as $v2 ) {
					if ($v ['viplev'] == $v2 ['vip_level']) {
						$v ['active'] = $v ['c'] ? $v ['c'] : 0;
						$v ['participation'] =  $v2 ['participation'];
						$v ['gold_avg'] = round($v2['total_1']/$v['active'],2);
						$v ['diamond_avg'] =round($v2['total_20']/$v['active'],2);
						$v ['bounty_avg'] =round($v2['total_100']/$v['active'],2);
						$v ['donate_avg'] =round($v2['total_donate']/$v['active'],2);
						$v ['avg_expenditure'] =round($v2['total_diamond']/$v['active'],2);
					}
				}
	
			}
	
			if (! empty ( $logininfo ['day0'] ))
				echo json_encode ( [
						'status' => 'ok',
						'data' => $logininfo ['day0']
				] );
				else
					echo json_encode ( [
							'status' => 'fail',
							'info' => '未查到数据'
					] );
		} else {
			$this->data ['type_list'] = $types;
			$this->data ['hide_end_time'] = true;
			$this->body = 'DataAnalysis/donate';
			$this->layout ();
		}
	
	
	
	}
	/**
	 * 椰蛋树活动
	 *
	 * @author 王涛 --20170515
	 */
	public function Egg() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$where ['begindate'] = date('Ymd',strtotime ( $date ));
			$where ['enddate'] = date('Ymd',strtotime ( $date ));
			$where['begintime'] = strtotime($date);
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$btype = $this->input->get ( 'btype' );
			$newdata = array();
			if($btype == 0){
				$this->load->model('SystemFunction_model');
				$field = "COUNT(DISTINCT accountid) c";
				$where['typeids'] = [75];
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior($where,$field,$group);
				!isset($newdata[0]) && $newdata[0]['name'] = "每天参与过玩法的玩家人数";
				$newdata[0]['count'] = $data?$data[0]['c']:0;
		
				$where['typeids'] = [76];
				$where['cid'] = 5;
				$field = "COUNT(*) c";
				$data = $this->SystemFunction_model->ActionByInvasion($where,$field);
				!isset($newdata[1]) && $newdata[1]['name'] = "每天在活动中获得战斗胜利奖励满5次的人数";
				$newdata[1]['count'] = $data?$data[0]['c']:0;
			}elseif($btype == 1){
				$this->load->model('SystemFunction_model');
				$where['typeids'] = [76];
				$field = "param,COUNT(DISTINCT accountid) c";
				$group = "param";
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior($where,$field,$group);
				$types = array(
				 		1=>		"洗澡（简单）",
						2=>"喂食（普通）",
						3=>"抓痒（困难）"
				);
				foreach ($data as $v){
					!isset($newdata[$v['param']]) && $newdata[$v['param']]['name'] = "选择了{$types[$v['param']]}选项并成功获得战斗奖励的人数";
					$newdata[$v['param']]['count'] = $v['c'];
				}
			}elseif($btype == 2){
				$this->load->model ( 'GameServerData' );
				$where ['type'] = 3;
				$where ['status'] = 1; //通关
				$field = 'count(distinct communityid,serverid) c';
				$data = $this->GameServerData->community ( $where,$field,$group );//通关社团副本的社团的个数
				!isset($newdata[0]) && $newdata[0]['name'] = "开启椰蛋树玩法的社团数";
				$newdata[0]['count'] = $data?$data[0]['c']:0;
				
				$where['beginh'] = 1930;
				$where['endh'] = 2059;
				$data = $this->GameServerData->community ( $where,$field,$group );//通关社团副本的社团的个数
				!isset($newdata[1]) && $newdata[1]['name'] = "19:30~20:59期间开启玩法的社团数";
				$newdata[1]['count'] = $data?$data[0]['c']:0;
				
				$where['beginh'] = 2100;
				$where['endh'] = 2300;
				$data = $this->GameServerData->community ( $where,$field,$group );//通关社团副本的社团的个数
				!isset($newdata[2]) && $newdata[2]['name'] = "21:00~23:00期间开启玩法的社团数";
				$newdata[2]['count'] = $data?$data[0]['c']:0;
					
			}elseif($btype == 3){
				$this->load->model ( 'GameServerData' );
				$field = 'type,count(distinct communityid,serverid) c';
				$group = "type";
				$data = $this->GameServerData->egg($where,$field,$group);
				$types = array(
						2=>		"达成幸福值累积发放了椰蛋树礼物的社团数",
						1=>"幸福值累计达到40以上的社团数",
				);
				foreach ($data as $v){
					$newdata[$v['type']]['name'] = $types[$v['type']];
					$newdata[$v['type']]['count'] = $v['c'];
				}

			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $newdata
			] ) );
		} else {
			//$this->data ['hide_server_list'] = true;
			$this->data ['hide_end_time'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/egg';
			$this->layout ();
		}
	}
	/**
	 * 社团入侵
	 *
	 * @author 王涛 --20170512
	 */
	public function Invasion() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$where ['begindate'] = date('Ymd',strtotime ( $date ));
			$where ['enddate'] = date('Ymd',strtotime ( $date ));
			$where['begintime'] = strtotime($date);
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$btype = $this->input->get ( 'btype' );
			$newdata = array();
			if($btype == 0){
				$this->load->model('SystemFunction_model');
				$where['typeids'] = [78];
				$field = "floor(user_level/10) as level,COUNT(DISTINCT accountid) c";
				$group = "level";
				$where['beginh'] = 1200;
				$where['endh'] = 1230;
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior($where,$field,$group);
				!isset($newdata[0]) && $newdata[0]['name'] = "每天12:00~12:30时段的参与人数";
				foreach ($data as $v){
					$newdata[0][$v['level']] = $v['c'];
				}
		
				$a = array_fill(0,10,0);
				foreach ($newdata as &$v){
					$v += $a;
				}
			}elseif($btype == 1){
				$this->load->model('SystemFunction_model');
				$where['typeids'] = [78];
				$field = "floor(user_level/10) as level,COUNT(DISTINCT accountid) c";
				$group = "level";
				$where['beginh'] = 1500;
				$where['endh'] = 1530;
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior($where,$field,$group);
				!isset($newdata[2]) && $newdata[2]['name'] = "每天15:00~15:30时段的参与人数";
				foreach ($data as $v){
					$newdata[2][$v['level']] = $v['c'];
				}
				$a = array_fill(0,10,0);
				foreach ($newdata as &$v){
					$v += $a;
				}
			}elseif($btype == 2){
				$this->load->model('SystemFunction_model');
				$where['typeids'] = [78];
				$field = "floor(user_level/10) as level,COUNT(DISTINCT accountid) c";
				$group = "level";
				$where['beginh'] = 1600;
				$where['endh'] = 1630;
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior($where,$field,$group);
				!isset($newdata[3]) && $newdata[3]['name'] = "每天16:00~16:30时段的参与人数";
				foreach ($data as $v){
					$newdata[3][$v['level']] = $v['c'];
				}
				$a = array_fill(0,10,0);
				foreach ($newdata as &$v){
					$v += $a;
				}
			}elseif($btype == 3){
				$this->load->model('SystemFunction_model');
				$where['typeids'] = [78];
				$field = "floor(user_level/10) as level,COUNT(DISTINCT accountid) c";
				$group = "level";
				$where['beginh'] = 1700;
				$where['endh'] = 1730;
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior($where,$field,$group);
				!isset($newdata[4]) && $newdata[4]['name'] = "每天17:00~17:30时段的参与人数";
				foreach ($data as $v){
					$newdata[4][$v['level']] = $v['c'];
				}
				$a = array_fill(0,10,0);
				foreach ($newdata as &$v){
					$v += $a;
				}
			}elseif($btype == 4){
				$this->load->model('SystemFunction_model');
				$where['typeids'] = [79];
				$field = "level,count(*) c";
				$where['cid'] = 1;
				$data = $this->SystemFunction_model->ActionByInvasion($where,$field,'level');
				!isset($newdata[5]) && $newdata[5]['name'] = "每天在活动中获得战斗胜利奖励1次以上的人数";
				foreach ($data as $v){
					$newdata[5][$v['level']] = $v['c'];
				}
				$where['cid'] = 5;
				$data = $this->SystemFunction_model->ActionByInvasion($where,$field);
				!isset($newdata[6]) && $newdata[6]['name'] = "每天在活动中获得战斗胜利奖励5次以上的人数";
				foreach ($data as $v){
					$newdata[6][$v['level']] = $v['c'];
				}
				$a = array_fill(0,10,0);
				foreach ($newdata as &$v){
					$v += $a;
				}
			}elseif($btype == 5){
				$this->load->model ( 'GameServerData' );
				$where ['type'] = 2;
				$where ['status'] = 3; //通关
				$field = 'communityid,serverid,count(distinct onh) c';
				$group = 'communityid,serverid';
				$data = $this->GameServerData->community ( $where,$field,$group );//通关社团副本的社团的个数
				if(!$data){
					echo json_encode(array('status'=>'fail','info'=>'暂无数据'));die;
				}
					
				foreach ($data as $v){
					!isset($newdata['3'.$v['c']]) && $newdata['3'.$v['c']]['name'] = "每天有{$v['c']}个时段活动被判定胜利的社团数";
					$newdata['3'.$v['c']][0] += 1;
				}
				$where ['status'] = 4; //失败
				$data = $this->GameServerData->community ( $where,$field,$group );//通关社团副本的社团的个数
				foreach ($data as $v){
					!isset($newdata['4'.$v['c']]) && $newdata['4'.$v['c']]['name'] = "每天有{$v['c']}个时段活动被判定失败的社团数";
					$newdata['4'.$v['c']][0] += 1;
				}
				$a = array_fill(0,10,0);
				foreach ($newdata as &$v){
					$v += $a;
				}
			}
				
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $newdata
			] ) );
		} else {
			$this->data ['hide_end_time'] = true;
			//$this->data ['hide_server_list'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/invasion';
			$this->layout ();
		}
	}
	/**
	 * 社团副本
	 *
	 * @author 王涛 --20170512
	 */
	public function The() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$where ['begindate'] = date('Ymd',strtotime ( $date ));
			$where ['enddate'] = date('Ymd',strtotime ( $date2 ));
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$this->load->model ( 'GameServerData' );
			$where ['type'] = 1;
			$where ['status'] = 3; //通关
			$field = 'count(distinct communityid,serverid) c';
			$data = $this->GameServerData->community ( $where,$field,$group );//通关社团副本的社团的个数
			if(!$data){
				echo json_encode(array('status'=>'fail','info'=>'暂无数据'));die;
			}
			$newdata['0']['count'] = $data[0]['c'];
			$newdata['0']['name'] = '通关社团副本的社团个数';
			$where ['status'] = '1,2'; //开启或重置
			$where ['c'] = '1,2,3'; //次数
			$field = 'communityid,serverid,count(*) c';
			$group = 'communityid,serverid';
			$data = $this->GameServerData->community ( $where,$field,$group );//重置或开启n次社团副本的社团的个数
			unset($where ['c']);
			foreach ($data as $v){
				!isset($newdata['1_'.$v['c']]) && $newdata['1_'.$v['c']]['name'] = "开启或重置社团副本{$v['c']}次的社团个数";
				$newdata['1_'.$v['c']]['count'] += 1;
			}
			$where ['status'] = 3; //通关
			$field = 'process,count(distinct communityid,serverid) c';
			$group = 'process';
			$data = $this->GameServerData->community ( $where,$field,$group );//通关第n个社团副本的社团的个数
			foreach ($data as $v){
				!isset($newdata['2_'.$v['process']]) && $newdata['2_'.$v['process']]['name'] = "通关第{$v['process']}个社团副本的社团个数";
				$newdata['2_'.$v['process']]['count'] = $v['c'];
			}
			$field = 'process,count(*) c';
			$group = 'process';
			$data = $this->GameServerData->community ( $where,$field,$group );//通关第n个社团副本的次数
			foreach ($data as $v){
				!isset($newdata['3_'.$v['process']]) && $newdata['3_'.$v['process']]['name'] = "通关第{$v['process']}个社团副本的次数";
				$newdata['3_'.$v['process']]['count'] = $v['c'];
			}
			
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $newdata
			] ) );
		} else {
			//$this->data ['hide_server_list'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/the';
			$this->layout ();
		}
	}
	
	
	/**
	 * 社团副本宝箱
	 *
	 * @author zzl --20170726
	 */
	public function theTreasure() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );			
			$where ['begindate'] = date('Ymd',strtotime ( $date ));			
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$this->load->model ( 'GameServerData' );
			
			$field = 'count(distinct communityid,serverid) c';
			$data = $this->GameServerData->community ( $where,$field,$group );//通关社团副本的社团的个数
			if(!$data){
				echo json_encode(array('status'=>'fail','info'=>'暂无数据'));die;
			}
			$group = 'accountid';				
			$field = 'count(*) as total';
			$group = 'accountid';
			$data = $this->GameServerData->communityMore ( $where,$field,$group);	
			

			$newdata[4]['name']='每天在社团副本使用过购买宝箱功能的玩家';
			$newdata[4]['count']=$data['total'][0]['total'];
			
			
			$newdata[5]['name']='每天在第1章社团副本使用过购买宝箱的玩家人数';
			$newdata[5]['count']=$data['total'][0]['total_1010'];
			
			$newdata[6]['name']='每天在第2章社团副本使用过购买宝箱的玩家人数';
			$newdata[6]['count']=$data['total'][0]['total_1011'];
			

			$a=0;$b=0;$c=0;$d=0;$e=0;
			foreach ($data['group'] as $v){
				if($v['total']>=3){
					$a++;
				}
				if($v['total']>=6){
					$b++;
				}
				if($v['total']>=12){
					$c++;
				}
				if($v['total']>=24){
					$d++;
				}
				if($v['total']>=48){
					$e++;
				}
			}
			$newdata[7]['name']='每天购买社团副本宝箱达到3次的玩家';
			$newdata[7]['count']=$a;
			
			$newdata[8]['name']='每天购买社团副本宝箱达到6次的玩家';
			$newdata[8]['count']=$b;
			
			$newdata[9]['name']='每天购买社团副本宝箱达到12次的玩家';
			$newdata[9]['count']=$c;
			
			$newdata[10]['name']='每天购买社团副本宝箱达到24次的玩家';
			$newdata[10]['count']=$d;
			
			$newdata[11]['name']='每天购买社团副本宝箱达到48次的玩家';
			$newdata[11]['count']=$e;
			

			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $newdata
			] ) );
		} else {
			
			$this->data ['hide_end_time'] = true;
			//$this->data ['hide_server_list'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/theTreasure';
			$this->layout ();
		}
	}
	
	
	
	
	
	/**
	 * 匹配时间
	 *
	 * @author 王涛 --20170511
	 */
	public function Match() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$where ['begindate'] = date('Ymd',strtotime ( $date ));
			$where ['enddate'] = date('Ymd',strtotime ( $date2 ));
			$where['gametype'] = $this->input->get ( 'gametype' );//对战类型
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$where ['dan'] = $this->input->get ( 'dan' ); //段位
			$where ['danend'] = $this->input->get ( 'danend' ); //结束段位
			$this->load->model ( 'GameServerData' );
			$field = 'matchtime,count(*) c';
			$group  = 'matchtime';
			$data = $this->GameServerData->match ( $where,$field,$group );
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $data
			] ) );
		} else {
			
			$this->body = 'SystemFunction/match';
			$this->layout ();
		}
	}
	/**
	 * 全球精灵养成
	 *
	 * @author 王涛 --20170419
	 */
	public function EdumonDevelop() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['date'] = date('Ymd',strtotime ( $date ));
			$type = $this->input->get ( 'processtype' ); //副本
			$developtype = $this->input->get ( 'developtype' ); //养成类型
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$where ['season'] = $this->input->get ( 'season' ); // 赛季
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$this->load->model ( 'GameServerData' );
			if($type == 1){//普通
				$field = 'com_ranklev ranklev,count(*) caccount';
			}else{
				$field = 'elite_ranklev ranklev,count(*) caccount';
			}
			if($developtype == 1){//图鉴养成
				$field .= ',booklv develop,sum(booklv) sumdev';
			}elseif($developtype == 2){//亲密度养成
				$field .= ',ceil(intilv/5)*5 develop,sum(intilv) sumdev';
			}elseif($developtype == 3){//努力值养成
				$field .= ',ceil(ex2/15)*15 develop,sum(ex2) sumdev';
			}else{//个体值养成
				$field .= ',ceil(ex1/5)*5 develop,sum(ex1) sumdev';
			}
			$group = 'ranklev,develop';
			$data = $this->GameServerData->daneudemon ( $where,$field,$group );
			
			$field='com_ranklev ranklev, convert(sum(intilv)/count(*),decimal(10,2)) as avg';
			$group="ranklev";			
			$avg_intilv = $this->GameServerData->getIntilv ( $where,$field,$group );
			
		
			
			$newdata = $three = $titledata = array();
			$maxdevelop = 0;
			foreach ( $data as $v ) {
				if(!isset($three[$v['ranklev']])){
					$three[$v['ranklev']]['ranklev'] = $v['ranklev'];
				}
				if(!isset($titledata[$v['develop']])){
					$titledata[$v['develop']] = $v['develop'];
				}
				if($maxdevelop<$v['develop']){
					$maxdevelop = $v['develop'];
				}
				$three[$v['ranklev']]['sum'] += $v['caccount'];
				$three[$v['ranklev']]['num'] += $v['sumdev'];
				$newdata[$v['ranklev']][$v['develop']] = $v['caccount'];
			}
			ksort($three);
			//$processtitle = array();
			foreach ($three as $k=>$v){
				if(!isset($processtitle[$k])){
					$processtitle[$k] = $v['ranklev'];
				}
				$three[$k]['ave'] = round($v['num']/$v['sum'],2);
			}
			foreach ($newdata as &$v){
				$diff = array_diff(array_keys($titledata),array_keys($v));
				foreach($diff as $_diff_day) {
					$v[$_diff_day] = 0;
				}
			}
			$json_data[] = [
					'name' => '段位平均值',
					'type' => 'bar',
					'data'  => array_column($three, 'ave'),
			];
			if($processtitle){
				$processtitle = array_values($processtitle);
			}
			//print_r($processtitle);die;
			
			foreach ($three as &$v){
			    foreach ($avg_intilv as $v2){
			        if($v['ranklev']==$v2['ranklev']){
			            $v['avg_intilv']=$v2['avg'];
			             
			        }
			    }			    
			}
			

			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => array(
						'data'=>$newdata,
						'title'=>$titledata,
						'three'=>	$three,
					),
					'series'     =>$json_data,
					'legend'     =>$legend,
					'category'=>$processtitle,
			] ) );
		} else {
			$this->data ['hide_end_time'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/edumondevelop';
			$this->layout ();
		}
	}
	/**
	 * 全球段位分布
	 *
	 * @author 王涛 --20170419
	 */
	public function Dan() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['date'] = date('Ymd',strtotime ( $date ));
			$type = $this->input->get ( 'processtype' ); //副本
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$where ['season'] = $this->input->get ( 'season' ); // 赛季
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$this->load->model ( 'GameServerData' );
			$cdata = $this->GameServerData->dan ( $where,'count(*) caccount',$group );
			if($cdata && $cdata[0]['caccount']>0){
				$allcount = $cdata[0]['caccount'];
			}else{
				echo json_encode(array('status'=>'fail','info'=>'暂无数据'));die;
			}
			if($type == 1){//普通
				$field = 'com_ranklev ranklev,count(*) caccount';
			}else{
				$field = 'elite_ranklev ranklev,count(*) caccount';
			}
			$group = 'ranklev';
			$maxdan = 1;
			$data = $this->GameServerData->dan ( $where,$field,$group );
			$newdata = array();
			foreach ( $data as $v ) {
				if($v['ranklev']>$maxdan){
					$maxdan = $v['ranklev'];
				}
				$newdata[$v['ranklev']]['rare'] = round($v['caccount']/$allcount*100,2);
				$newdata[$v['ranklev']]['caccount'] = $v['caccount'];
				$newdata[$v['ranklev']]['ranklev'] = $v['ranklev'];
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $newdata,
					'allaccount'=>$allcount,
					'maxdan'=>$maxdan
			] ) );
		} else {
			$this->data ['hide_end_time'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/dan';
			$this->layout ();
		}
	}
	/**
	 * 神兽来袭
	 *
	 * @author 王涛 --20170317
	 */
	public function Beast() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where['channels'] = $this->input->get('channel_id');
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$group = 'level';
			$btype = $this->input->get ( 'btype' );
			$this->load->model ( 'SystemFunction_model' );
			$newdata = array ();
			if ($btype == 0) { // 各等级段参与精灵塔的人数
				$names = array (
						'35' => '各等级段参与活动的人数'
				);
				$where ['typeids'] = [ 35 ];
				$field = 'act_id,floor(user_level/10) as level,count(distinct accountid) cid';
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );
				foreach ( $data as $v ) {
					if (! isset ( $newdata [$v ['act_id']] )) {
						for($i = 0; $i < 10; $i ++) {
							$newdata [$v ['act_id']] ['level_' . $i] = 0;
							$newdata [$v ['act_id']] ['act_name'] = $names [$v ['act_id']];
						}
					}
					$newdata [$v ['act_id']] ['level_' . $v ['level']] = $v ['cid'];
				}
			} elseif ($btype == 1) { // 活动时间段使用钻石消除战斗冷却的人数
				$names = array (
						'4' => '使用钻石消除战斗人却的人数',
				);
				$where ['typeids'] = [ 35 ];
				$where['params'] = [ 4 ];
				$field = 'param,floor(user_level/10) as level,count(distinct accountid) cid';
				$group = 'level,param';
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );
				foreach ( $data as $v ) {
					if (! isset ( $newdata [ $v ['param']] )) {
						for($i = 0; $i < 10; $i ++) {
							$newdata [ $v ['param']] ['level_' . $i] = 0;
							$newdata [ $v ['param']] ['act_name'] = $names [ $v ['param']];
						}
					}
					$newdata [ $v ['param']] ['level_' . $v ['level']] = $v ['cid'];
				}
			} elseif ($btype == 2) { // 每天战斗胜利的人数
				$names = array (
						'1' => '每天战斗胜利1次以上',
						'2' => '每天战斗胜利5次以上',
						'3' => '每天战斗胜利10次以上',
				);
				$where ['typeids'] = [ 35 ];
				$where['params'] = [ 3 ];
				$field = 'floor(user_level/10) as level,sum(if(cid>=1,1,0)) s1,sum(if(cid>=5,1,0)) s2,sum(if(cid>=10,1,0)) s3';
				$data = $this->SystemFunction_model->ActionByTree ( $where, $field );
				foreach ($data as $v){
					if (! isset ( $newdata [1] )) {
						for($i = 0; $i < 10; $i ++) {
							$newdata [1] ['level_' . $i] = 0;
							$newdata [2] ['level_' . $i] = 0;
							$newdata [3] ['level_' . $i] = 0;
							$newdata [1] ['act_name'] = $names [1];
							$newdata [2] ['act_name'] = $names [2];
							$newdata [3] ['act_name'] = $names [3];
						}
					}
					$newdata [1] ['level_' . $v ['level']] = $v ['s1'];
					$newdata [2] ['level_' . $v ['level']] = $v ['s2'];
					$newdata [3] ['level_' . $v ['level']] = $v ['s3'];
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $newdata
			] ) );
		} else {
			$this->data ['hide_end_time'] = true;
			$this->data ['viplev_filter'] = true;
			$this->body = 'SystemFunction/beast';
			$this->layout ();
		}
	}
	/**
	 * 植树节活动
	 *
	 * @author 王涛 --20170316
	 */
	public function Tree() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where['channels'] = $this->input->get('channel_id');
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$group = 'level';
			$btype = $this->input->get ( 'btype' );
			$this->load->model ( 'SystemFunction_model' );
			$newdata = array ();
			if ($btype == 0) { // 各等级段参与精灵塔的人数
				$names = array (
						'64' => '各等级段参与活动的人数' 
				);
				$where ['typeids'] = [ 64 ];
				$field = 'act_id,floor(user_level/10) as level,count(distinct accountid) cid';
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );
				foreach ( $data as $v ) {
					if (! isset ( $newdata [$v ['act_id']] )) {
						for($i = 0; $i < 10; $i ++) {
							$newdata [$v ['act_id']] ['level_' . $i] = 0;
							$newdata [$v ['act_id']] ['act_name'] = $names [$v ['act_id']];
						}
					}
					$newdata [$v ['act_id']] ['level_' . $v ['level']] = $v ['cid'];
				}
			} elseif ($btype == 1) { // 活动时间段参与人数
				$names = array (
						'641' => '时段1参与人数',
						'642' => '时段2参与人数',
						'643' => '时段3参与人数',
				);
				$where ['typeids'] = [ 64 ];
				$field = 'act_id,param,floor(user_level/10) as level,count(distinct accountid) cid';
				$group = 'level,param';
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );
				foreach ( $data as $v ) {
					if (! isset ( $newdata [$v ['act_id'] . $v ['param']] )) {
						for($i = 0; $i < 10; $i ++) {
							$newdata [$v ['act_id'] . $v ['param']] ['level_' . $i] = 0;
							$newdata [$v ['act_id'] . $v ['param']] ['act_name'] = $names [$v ['act_id'] . $v ['param']];
						}
					}
					$newdata [$v ['act_id'] . $v ['param']] ['level_' . $v ['level']] = $v ['cid'];
				}
			} elseif ($btype == 2) { // 3个时段都参与的人数
				
			}elseif ($btype == 3) { // 每天战斗胜利的人数
				$names = array (
						'1' => '每天战斗胜利10次以下',
						'2' => '每天战斗胜利10-20以下',
						'3' => '每天战斗胜利20次以上',
				);
				$where ['typeids'] = [64];
				$field = 'floor(user_level/10) as level,sum(if(cid<10,1,0)) s1,sum(if(cid>=10&&cid<=20,1,0)) s2,sum(if(cid>20,1,0)) s3';
				$data = $this->SystemFunction_model->ActionByTree ( $where, $field );
				foreach ($data as $v){
					if (! isset ( $newdata [1] )) {
						for($i = 0; $i < 10; $i ++) {
							$newdata [1] ['level_' . $i] = 0;
							$newdata [2] ['level_' . $i] = 0;
							$newdata [3] ['level_' . $i] = 0;
							$newdata [1] ['act_name'] = $names [1];
							$newdata [2] ['act_name'] = $names [2];
							$newdata [3] ['act_name'] = $names [3];
						}
					}
					$newdata [1] ['level_' . $v ['level']] = $v ['s1'];
					$newdata [2] ['level_' . $v ['level']] = $v ['s2'];
					$newdata [3] ['level_' . $v ['level']] = $v ['s3'];
				}
			} elseif ($btype == 4) { // 获得椰树蛋奖励的人数
				$names = array (
						'1' => '获得椰树蛋奖励1次的人数',
						'2' => '获得椰树蛋奖励2次的人数',
						'3' => '获得椰树蛋奖励3次以上的人数'
				);
				$where ['typeids'] = [65];
				$field = 'floor(user_level/10) as level,sum(if(cid=1,1,0)) s1,sum(if(cid=2,1,0)) s2,sum(if(cid>3,1,0)) s3';
				$data = $this->SystemFunction_model->ActionByTree ( $where, $field );
				foreach ($data as $v){
					if (! isset ( $newdata [1] )) {
						for($i = 0; $i < 10; $i ++) {
							$newdata [1] ['level_' . $i] = 0;
							$newdata [2] ['level_' . $i] = 0;
							$newdata [3] ['level_' . $i] = 0;
							$newdata [1] ['act_name'] = $names [1];
							$newdata [2] ['act_name'] = $names [2];
							$newdata [3] ['act_name'] = $names [3];
						}
					}
					$newdata [1] ['level_' . $v ['level']] = $v ['s1'];
					$newdata [2] ['level_' . $v ['level']] = $v ['s2'];
					$newdata [3] ['level_' . $v ['level']] = $v ['s3'];
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $newdata 
			] ) );
		} else {
			$this->data ['hide_end_time'] = true;
			$this->data ['viplev_filter'] = true;
			$this->body = 'SystemFunction/tree';
			$this->layout ();
		}
	}
	/**
	 * 精灵塔推荐阵容
	 *
	 * @author 王涛 --20170315
	 */
	public function Squad() {
		if (parent::isAjax ()) {
			$where ['template_id'] = $this->input->get ( 'template_id' ); // 层数
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 道具编号
			$this->load->model ( 'GameServerData' );
			$data = $this->GameServerData->squad ( $where );
			$items = include APPPATH .'/config/item_types.php'; //道具字典
			foreach ( $data as &$v ) {
				$v['eud1'] = $v['eud_id1'].$items[$v['eud_id1']];
				$v['eud2'] = $v['eud_id2'].$items[$v['eud_id2']];
				$v['eud3'] = $v['eud_id3'].$items[$v['eud_id3']];
				$v['eud4'] = $v['eud_id4'].$items[$v['eud_id4']];
				$v['eud5'] = $v['eud_id5'].$items[$v['eud_id5']];
				$v['eud6'] = $v['eud_id6'].$items[$v['eud_id6']];
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $data
			] ) );
		} else {
			$this->data ['hide_start_time'] = true;
			$this->data ['hide_end_time'] = true;
			$this->data ['tem_filter'] = true;
			
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/squad';
			$this->layout ();
		}
	}
	/**
	 * 精灵塔
	 *
	 * @author 王涛 --20170315
	 */
	public function Elftower() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			
			$btype = $this->input->get ( 'btype' );
			$this->load->model ( 'SystemFunction_model' );
			$newdata = array ();
			if ($btype == 0) { // 各等级段参与精灵塔的人数
				$names = array (
						'66' => '各等级段参与精灵塔的人数' 
				);
				$group = 'accountid';
				$where ['typeids'] = [ 66 ];
			
				$field = 'id,act_id,user_level as level,count(distinct accountid) cid';

		
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );
				if(!$data){
					echo json_encode(array('code'=>'fail','info'=>'未查到数据'));die;
				}
				
				foreach ( $data as $v ) {				
    				for($i = 0; $i < 10; $i ++) {
    				    $newdata [$v ['act_id']] ['level_' . $i] = 0;
    				    $newdata [$v ['act_id']] ['act_name'] = $names [$v ['act_id']];
    				}
				}
				
				foreach ( $data as $v ) {
				    
				    $newdata [$v ['act_id']] ['act_name'] = $names [$v ['act_id']];
				if($v['level']<10 ){
				    $newdata [$v ['act_id']] ['level_0'] += $v['cid'];
				}elseif ($v['level']>=10 && $v['level']<20 ){
				    $newdata [$v ['act_id']] ['level_1'] += $v['cid'];
				}elseif ($v['level']>=20 && $v['level']<30){
				    $newdata [$v ['act_id']] ['level_2'] += $v['cid'];
				}elseif ($v['level']>=30 && $v['level']<40){
				    $newdata [$v ['act_id']] ['level_3'] += $v['cid'];
				}elseif ($v['level']>=40 && $v['level']<50){
				    $newdata [$v ['act_id']] ['level_4'] += $v['cid'];
				}elseif ($v['level']>=50 && $v['level']<60){
				    $newdata [$v ['act_id']] ['level_5']  += $v['cid'];
				}elseif ($v['level']>=60 && $v['level']<70){
				    $newdata [$v ['act_id']] ['level_6']  += $v['cid'];
				}elseif ($v['level']>=70 && $v['level']<80){
				    $newdata [$v ['act_id']] ['level_7']  += $v['cid'];
				}elseif ($v['level']>=80 && $v['level']<90){
				    $newdata [$v ['act_id']] ['level_8'] += $v['cid'];
				}elseif ($v['level']>=90){
				    $newdata [$v ['act_id']] ['level_9']  += $v['cid'];
				}
				
				}
				

				
				
				
			} elseif ($btype == 1) { // 通关精灵塔各层的人数
			   
				$where ['typeids'] = [ 67 ];
		
				$field = 'accountid,act_id,CEILING(param/5) as param,user_level as level,count(distinct accountid) cid';
				$group = 'accountid';			
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );
				
	
 				if(!empty($data)){
				    foreach ( $data as $v ) {
				        $layer=$v ['param'];
				      $newdata [ $v ['param']] ['act_name'] = '通关精灵塔'.$layer.'层的人数';   
				      if($v['level']<10 ){
				          $newdata [$v ['param']] ['level_0'] += $v['cid'];
				      }elseif ($v['level']>=10 && $v['level']<20 ){
				         $newdata [$v ['param']] ['level_1'] += $v['cid'];
				      }elseif ($v['level']>=20 && $v['level']<30){
				         $newdata [$v ['param']] ['level_2'] += $v['cid'];
				      }elseif ($v['level']>=30 && $v['level']<40){
				         $newdata [$v ['param']] ['level_3'] += $v['cid'];
				      }elseif ($v['level']>=40 && $v['level']<50){
				         $newdata [$v ['param']] ['level_4'] += $v['cid'];
				      }elseif ($v['level']>=50 && $v['level']<60){
				         $newdata [$v ['param']] ['level_5']  += $v['cid'];
				      }elseif ($v['level']>=60 && $v['level']<70){
				         $newdata [$v ['param']] ['level_6']  += $v['cid'];
				      }elseif ($v['level']>=70 && $v['level']<80){
				         $newdata [$v ['param']] ['level_7']  += $v['cid'];
				      }elseif ($v['level']>=80 && $v['level']<90){
				         $newdata [$v ['param']] ['level_8'] += $v['cid'];
				      }elseif ($v['level']>=90){
				         $newdata [$v ['param']] ['level_9']  += $v['cid'];
				      }
				   
				    }
				} 
				
	

			} elseif ($btype == 2) { // 获得精灵各层首通各种宝箱的人数
			  
				$names = array (
						'43210000' => '获得精灵塔1层首通青铜宝箱的人数',
						'43212000' => '获得精灵塔1层首通白银宝箱的人数',
						'43214000' => '获得精灵塔1层首通黄金宝箱的人数',
						'43216000' => '获得精灵塔2层首通青铜宝箱的人数',
						'43218000' => '获得精灵塔2层首通白银宝箱的人数',
						'43220000' => '获得精灵塔2层首通黄金宝箱的人数',
						'43222000' => '获得精灵塔3层首通青铜宝箱的人数',
						'43224000' => '获得精灵塔3层首通白银宝箱的人数',
						'43226000' => '获得精灵塔3层首通黄金宝箱的人数',
						'43228000' => '获得精灵塔4层首通青铜宝箱的人数',
						'43230000' => '获得精灵塔4层首通白银宝箱的人数',
						'43232000' => '获得精灵塔4层首通黄金宝箱的人数',
						'43234000' => '获得精灵塔5层首通青铜宝箱的人数',
						'43236000' => '获得精灵塔5层首通白银宝箱的人数',
						'43238000' => '获得精灵塔5层首通黄金宝箱的人数',
						'43240000' => '获得精灵塔6层首通青铜宝箱的人数',
						'43242000' => '获得精灵塔6层首通白银宝箱的人数',
						'43244000' => '获得精灵塔6层首通黄金宝箱的人数',
						'43246000' => '获得精灵塔7层首通青铜宝箱的人数',
						'43248000' => '获得精灵塔7层首通白银宝箱的人数',
						'43250000' => '获得精灵塔7层首通黄金宝箱的人数',
						'43252000' => '获得精灵塔8层首通青铜宝箱的人数',
						'43254000' => '获得精灵塔8层首通白银宝箱的人数',
						'43256000' => '获得精灵塔8层首通黄金宝箱的人数',
						'43258000' => '获得精灵塔9层首通青铜宝箱的人数',
						'43260000' => '获得精灵塔9层首通白银宝箱的人数',
						'43262000' => '获得精灵塔9层首通黄金宝箱的人数' 
				);
				
				$where ['itemid'] = implode ( ',', array_keys ( $names ) );
				$where ['typeids'] = [68];
				$group = 'level,item_id';
				$field = 'act_id,item_id,floor(user_level/10) as level,count(accountid) cid';
				$data = $this->SystemFunction_model->BehaviorProduceSaleNew ( $where, $field, $group );
				foreach ($data as $v){
					if (! isset ( $newdata [$v ['item_id']] )) {
						for($i = 0; $i < 10; $i ++) {
							$newdata [$v ['item_id']] ['level_' . $i] = 0;
							$mytower = floor(($v ['item_id'] - 43210000)/2000/3)+1;
							switch(($v ['item_id'] - 43210000)/2000%3){
								case 0:
									$chestname = '青铜';
									break;
								case 1:
									$chestname = '白银';
									break;
								case 2:
									$chestname = '黄金';
									break;
							}
							$newdata [$v ['item_id']] ['act_name'] = '获得精灵塔'.$mytower.'层首通'.$chestname.'宝箱的人数';
						}
					}
					$newdata [$v ['item_id']] ['level_' . $v ['level']] = $v ['cid'];
				}
			} elseif ($btype == 3) { // 使用购买精灵塔宝箱功能的人数
				$names = array (
						'69' => '使用购买精灵塔宝箱功能的人数',
						'691' => '购买精灵塔宝箱1次的人数',
						'692' => '使用购买精灵塔宝箱2次的人数',
				        '693' => '使用购买精灵塔宝箱3次的人数'
				);
				$group="accountid";
				$where ['typeids'] = [69];
		
				$field = 'act_id,user_level as level,count(distinct accountid) cid';
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );
				
				
				foreach ( $data as $v ) {
				
				    $newdata [$v ['act_id']] ['act_name'] = $names [$v ['act_id']];
				    if($v['level']<10 ){
				        $newdata [$v ['act_id']] ['level_0'] += $v['cid'];
				    }elseif ($v['level']>=10 && $v['level']<20 ){
				        $newdata [$v ['act_id']] ['level_1'] += $v['cid'];
				    }elseif ($v['level']>=20 && $v['level']<30){
				        $newdata [$v ['act_id']] ['level_2'] += $v['cid'];
				    }elseif ($v['level']>=30 && $v['level']<40){
				        $newdata [$v ['act_id']] ['level_3'] += $v['cid'];
				    }elseif ($v['level']>=40 && $v['level']<50){
				        $newdata [$v ['act_id']] ['level_4'] += $v['cid'];
				    }elseif ($v['level']>=50 && $v['level']<60){
				        $newdata [$v ['act_id']] ['level_5']  += $v['cid'];
				    }elseif ($v['level']>=60 && $v['level']<70){
				        $newdata [$v ['act_id']] ['level_6']  += $v['cid'];
				    }elseif ($v['level']>=70 && $v['level']<80){
				        $newdata [$v ['act_id']] ['level_7']  += $v['cid'];
				    }elseif ($v['level']>=80 && $v['level']<90){
				        $newdata [$v ['act_id']] ['level_8'] += $v['cid'];
				    }elseif ($v['level']>=90){
				        $newdata [$v ['act_id']] ['level_9']  += $v['cid'];
				    }
				
				}
				
			
	
				$group = 'accountid';
				$where['cid'] = "1,2,3";
				$field = 'act_id,user_level as level,count(id) cid';
			
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );
				
				foreach ( $data as $v ) {
				    $newdata ['act_id']= $newdata [$v ['act_id'].$v['cid']] ;
				    $newdata [$v ['act_id'].$v['cid']] ['act_name'] = $names [$v ['act_id'].$v['cid']];
				    if($v['level']<10 ){
				        $newdata [$v ['act_id'].$v['cid']] ['level_0'] += 1;
				    }elseif ($v['level']>=10 && $v['level']<20 ){
				        $newdata [$v ['act_id'].$v['cid']] ['level_1'] += 1;
				    }elseif ($v['level']>=20 && $v['level']<30){
				        $newdata [$v ['act_id'].$v['cid']] ['level_2'] += 1;
				    }elseif ($v['level']>=30 && $v['level']<40){
				        $newdata [$v ['act_id'].$v['cid']] ['level_3'] += 1;
				    }elseif ($v['level']>=40 && $v['level']<50){
				        $newdata [$v ['act_id'].$v['cid']] ['level_4'] += 1;
				    }elseif ($v['level']>=50 && $v['level']<60){
				        $newdata [$v ['act_id'].$v['cid']] ['level_5']  += 1;
				    }elseif ($v['level']>=60 && $v['level']<70){
				        $newdata [$v ['act_id'].$v['cid']] ['level_6']  +=1;
				    }elseif ($v['level']>=70 && $v['level']<80){
				        $newdata [$v ['act_id'].$v['cid']] ['level_7']  += 1;
				    }elseif ($v['level']>=80 && $v['level']<90){
				        $newdata [$v ['act_id'].$v['cid']] ['level_8'] += 1;
				    }elseif ($v['level']>=90){
				        $newdata [$v ['act_id'].$v['cid']] ['level_9']  += 1;
				    }
				
				}
				
	
			}elseif ($btype == 6) { // 每天获得精灵塔1层首通青铜宝箱的人数 等	 			    
			    $names = array (			     
                '100101'=>'每天获得精灵塔1层首通青铜宝箱的人数',
                '100102'=>'每天获得精灵塔1层首通白银宝箱的人数',
                '100103'=>'每天获得精灵塔1层首通黄金宝箱的人数',
                '100201'=>'每天获得精灵塔2层首通青铜宝箱的人数',
                '100202'=>'每天获得精灵塔2层首通白银宝箱的人数',
                '100203'=>'每天获得精灵塔2层首通黄金宝箱的人数',
                '100301'=>'每天获得精灵塔3层首通青铜宝箱的人数',
                '100302'=>'每天获得精灵塔3层首通白银宝箱的人数',
                '100303'=>'每天获得精灵塔3层首通黄金宝箱的人数',
                '100401'=>'每天获得精灵塔4层首通青铜宝箱的人数',
                '100402'=>'每天获得精灵塔4层首通白银宝箱的人数',
                '100403'=>'每天获得精灵塔4层首通黄金宝箱的人数',
                '100501'=>'每天获得精灵塔5层首通青铜宝箱的人数',
                '100502'=>'每天获得精灵塔5层首通白银宝箱的人数',
                '100503'=>'每天获得精灵塔5层首通黄金宝箱的人数'
			        
			    );
			    
			    $where ['param_list'] = implode ( ',', array_keys ( $names ) );
		    	$where ['typeids'] = [ 68 ];
				
				$group = 'id';	
			
		    	$field = 'id,act_id,param1,user_level as level,count(distinct accountid) cid';
				$data = $this->SystemFunction_model->ActionByParamNew ( $where, $field, $group );	
			
				
				foreach ( $data as $v ) {
				
				   $newdata [$v ['param1']] ['act_name'] = $names [$v ['param1']];
				    if($v['level']<10 ){
				        $newdata [$v ['param1']] ['level_0'] += $v['cid'];
				    }elseif ($v['level']>=10 && $v['level']<20 ){
				        $newdata [$v ['param1']] ['level_1'] += $v['cid'];
				    }elseif ($v['level']>=20 && $v['level']<30){
				        $newdata [$v ['param1']] ['level_2'] += $v['cid'];
				    }elseif ($v['level']>=30 && $v['level']<40){
				        $newdata [$v ['param1']] ['level_3'] += $v['cid'];
				    }elseif ($v['level']>=40 && $v['level']<50){
				        $newdata [$v ['param1']] ['level_4'] += $v['cid'];
				    }elseif ($v['level']>=50 && $v['level']<60){
				        $newdata [$v ['param1']] ['level_5']  += $v['cid'];
				    }elseif ($v['level']>=60 && $v['level']<70){
				        $newdata [$v ['param1']] ['level_6']  += $v['cid'];
				    }elseif ($v['level']>=70 && $v['level']<80){
				        $newdata [$v ['param1']] ['level_7']  += $v['cid'];
				    }elseif ($v['level']>=80 && $v['level']<90){
				        $newdata [$v ['param1']] ['level_8'] += $v['cid'];
				    }elseif ($v['level']>=90){
				        $newdata [$v ['param1']] ['level_9']  += $v['cid'];
				    }
				
				}
	
	

			}elseif ($btype == 7) { //精灵塔1-9层达到扫荡要求的玩家人数 			    
			    
			    $names = array (			     
                '1'=>'精灵塔1层达到扫荡要求的玩家人数',
                '2'=>'精灵塔2层达到扫荡要求的玩家人数',
                '3'=>'精灵塔3层达到扫荡要求的玩家人数',
                '4'=>'精灵塔4层达到扫荡要求的玩家人数',
                '5'=>'精灵塔5层达到扫荡要求的玩家人数',
                '6'=>'精灵塔6层达到扫荡要求的玩家人数',
                '7'=>'精灵塔7层达到扫荡要求的玩家人数',
                '8'=>'精灵塔8层达到扫荡要求的玩家人数',
                '9'=>'精灵塔9层达到扫荡要求的玩家人数',       
			        
			    );
			    
			    $where ['param_list'] = implode ( ',', array_keys ( $names ) );
		    	$where ['typeids'] = [ 107 ];
				$field = 'id,act_id,param,user_level as level,count(distinct accountid) cid';
				$group = 'id';	
		    	$field = 'act_id,param,user_level as level,count(distinct accountid) cid';
				$data = $this->SystemFunction_model->ActionByParam ( $where, $field, $group );
		
				if(!empty($data)){
				    foreach ( $data as $v ) {
				        $newdata [ $v ['param']] ['act_name'] = $names[$v['param']];
				         
				        if($v['level']<10 ){
				            $newdata [$v ['param']] ['level_0'] += $v['cid'];
				        }elseif ($v['level']>=10 && $v['level']<20 ){
				            $newdata [$v ['param']] ['level_1'] += $v['cid'];
				        }elseif ($v['level']>=20 && $v['level']<30){
				            $newdata [$v ['param']] ['level_2'] += $v['cid'];
				        }elseif ($v['level']>=30 && $v['level']<40){
				            $newdata [$v ['param']] ['level_3'] += $v['cid'];
				        }elseif ($v['level']>=40 && $v['level']<50){
				            $newdata [$v ['param']] ['level_4'] += $v['cid'];
				        }elseif ($v['level']>=50 && $v['level']<60){
				            $newdata [$v ['param']] ['level_5']  += $v['cid'];
				        }elseif ($v['level']>=60 && $v['level']<70){
				            $newdata [$v ['param']] ['level_6']  += $v['cid'];
				        }elseif ($v['level']>=70 && $v['level']<80){
				            $newdata [$v ['param']] ['level_7']  += $v['cid'];
				        }elseif ($v['level']>=80 && $v['level']<90){
				            $newdata [$v ['param']] ['level_8'] += $v['cid'];
				        }elseif ($v['level']>=90){
				            $newdata [$v ['param']] ['level_9']  += $v['cid'];
				        }
		
				         
				    }
				}
	
				
	

			}elseif ($btype == 8) { //每天使用过1-9层精灵塔扫荡的玩家人数
			    
			    
			    
			    $names = array (			     
                '1'=>'每天使用过1层精灵塔扫荡的玩家人数',
                '2'=>'每天使用过2层精灵塔扫荡的玩家人数',
                '3'=>'每天使用过3层精灵塔扫荡的玩家人数',
                '4'=>'每天使用过4层精灵塔扫荡的玩家人数',
                '5'=>'每天使用过5层精灵塔扫荡的玩家人数',
                '6'=>'每天使用过6层精灵塔扫荡的玩家人数',
                '7'=>'每天使用过7层精灵塔扫荡的玩家人数',
                '8'=>'每天使用过8层精灵塔扫荡的玩家人数',
                '9'=>'每天使用过9层精灵塔扫荡的玩家人数',
			        
			        
			    );
			    
			    $where ['param_list'] = implode ( ',', array_keys ( $names ) );
		    	$where ['typeids'] = [ 112 ];	
		
				$field = 'act_id,param,user_level as level,count(distinct accountid) cid';
				$group = 'id';	
		    	$field = 'id,accountid,act_id,param,user_level as level,count(distinct accountid) cid';
				$data = $this->SystemFunction_model->ActionByParam ( $where, $field, $group );
		
				if(!empty($data)){
			
				    
				    foreach ( $data as $v ) {
				    
				        $newdata [$v ['param']] ['act_name'] = $names [$v ['param']];
				        if($v['level']<10 ){
				            $newdata [$v ['param']] ['level_0'] += $v['cid'];
				        }elseif ($v['level']>=10 && $v['level']<20 ){
				            $newdata [$v ['param']] ['level_1'] += $v['cid'];
				        }elseif ($v['level']>=20 && $v['level']<30){
				            $newdata [$v ['param']] ['level_2'] += $v['cid'];
				        }elseif ($v['level']>=30 && $v['level']<40){
				            $newdata [$v ['param']] ['level_3'] += $v['cid'];
				        }elseif ($v['level']>=40 && $v['level']<50){
				            $newdata [$v ['param']] ['level_4'] += $v['cid'];
				        }elseif ($v['level']>=50 && $v['level']<60){
				            $newdata [$v ['param']] ['level_5']  += $v['cid'];
				        }elseif ($v['level']>=60 && $v['level']<70){
				            $newdata [$v ['param']] ['level_6']  += $v['cid'];
				        }elseif ($v['level']>=70 && $v['level']<80){
				            $newdata [$v ['param']] ['level_7']  += $v['cid'];
				        }elseif ($v['level']>=80 && $v['level']<90){
				            $newdata [$v ['param']] ['level_8'] += $v['cid'];
				        }elseif ($v['level']>=90){
				            $newdata [$v ['param']] ['level_9']  += $v['cid'];
				        }
				    
				    }


				}
	
			}
			
			
			
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $newdata 
			] ) );
		} else {
			$this->data ['hide_end_time'] = true;
			$this->data ['viplev_filter'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/elftower';
			$this->layout ();
		}
	}
	/**
	 * 道具查询
	 *
	 * @author 王涛 --20170308
	 */
	public function itemData() {
		if (parent::isAjax ()) {
			$where ['itemid'] = $this->input->get ( 'itemid' ); // 道具编号
			if (! $where ['itemid']) {
				echo '{"status":"fail","info":"请输入道具编号"}';
				exit ();
			}
			$serverids = $this->input->get ( 'server_id' );
			if (! $serverids) {
				echo '{"status":"fail","info":"请选择区服"}';
				exit ();
			}
			$this->load->model ( 'GameEmoney_model' );
			$field = 'amount,count(id) as cid';
			$group = 'amount';
			foreach ( $serverids as $v ) {
				$where ['serverid'] = $v;
				$datas [] = $this->GameEmoney_model->serverItem ( $where, $field, $group );
			}
			$newdata = array ();
			foreach ( $datas as $data ) {
				foreach ( $data as $v ) {
					if (! isset ( $newdata [$v ['amount']] )) {
						$newdata [$v ['amount']] ['amount'] = $v ['amount'];
					}
					$newdata [$v ['amount']] ['cid'] += $v ['cid'];
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $newdata 
			] ) );
		} else {
			$this->data ['hide_start_time'] = true;
			$this->data ['hide_end_time'] = true;
			$this->data ['item_id_filter'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/itemdata';
			$this->layout ();
		}
	}
	/**
	 * 全球对战--精灵使用率
	 *
	 * @author 王涛 --20170307
	 */
	public function eudemonData() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$where ['type'] = $this->input->get ( 'gametype' ); // 对战类型
			$where ['btype'] = $this->input->get ( 'btype' ); // 对战类型
			if($where ['btype'] == 4){
				unset($where ['type']);
			}
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$where ['estatus'] = $this->input->get ( 'estatus' );
			$where ['dan'] = $this->input->get ( 'dan' );
			$items = include APPPATH . '/config/item_types.php'; // 道具字典
			if ($date) {
				$where ['begintime'] = date ( 'ymdHi', strtotime ( $date . ' 00:00:00' ) );
			}
			if ($date2) {
				$where ['endtime'] = date ( 'ymdHi', strtotime ( $date2 . ' 00:00:00' ) + 86400 );
			}
			$this->load->model ( 'GameServerData' );
			$field = 'count(distinct gameid) cgid';
			$cgid = 0;
			$data = $this->GameServerData->eudemonData ( $where, $field, $group, $order ); // 获取总场次
			if ($data) {
				$cgid = $data [0] ['cgid'] * 2;
			}
			$field = 'gue.eudemon as eudemonid,count(DISTINCT gu.accountid) user_total,eudemon,count(*) cid,sum(if(gu.status=0,1,0)) sum1,sum(if(gu.status=1,1,0)) sum0';		
			$group = 'eudemon';
			$order = 'cid desc';
			$data = $this->GameServerData->eudemonData ( $where, $field, $group, $order );
			foreach ( $data as &$v ) {
				$v ['eudemon'] = $v ['eudemon'] . $items [$v ['eudemon']];
				$v ['rare'] = round ( $v ['cid'] / $cgid * 100 ) . '%';
				$v ['win_rate'] = round ( $v ['sum0'] /  $v ['cid'] * 100 ) . '%';
				$v['text']="<a href='javascript:detail($v[eudemonid], 1)'>技能配置</a>";
				
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $data,
					'cgid' => $cgid 
			] ) );
		} else {
			//$this->data ['bt'] = $this->data ['et'] = '';
			$this->data ['viplev_filter'] = true;
			$this->data ['game_filter'] = true;
			$this->data ['hide_server_list'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->data ['estatus_filter'] = true;
			$this->data ['dan_filter'] = true;
			$this->data ['btype_filter'] = true;
			$this->body = 'SystemFunction/eudemondata';
			$this->layout ();
		}
	}
	/**
	 * 全球对战--详情
	 *
	 * @author 王涛 --20170306
	 */
	public function worldData() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['accountid'] = $this->input->get ( 'accountid' );
			$where ['type'] = $this->input->get ( 'gametype' ); // 对战类型
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['dan'] = $this->input->get ( 'dan' );
			$where ['eudemons'] = $this->input->get ( 'eudemon' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$where ['btype'] = $this->input->get ( 'btype' );
			if($where ['btype'] == 4){
				unset($where ['type']);
			}
			$limit = $this->input->get ( 'limit' )?$this->input->get ( 'limit' ):100;
			
			if (empty ( $date ) && empty ( $date2 ) && empty ( $where ['serverids'] ) && empty ( $where ['accountid'] ) && empty ( $where ['dan'] ) && empty ( $where ['status'] ) && empty ( $where ['viplev_min'] ) && empty ( $where ['viplev_max'] ) && empty ( $where ['eudemons'] ) && $where ['type'] == - 1) {
				echo '{"status":"fail","info":"请选择查询条件"}';
				exit ();
			}
			$items = include APPPATH . '/config/item_types.php'; // 道具字典
			if ($date) {
				$where ['begintime'] = date ( 'ymdHi', strtotime ( $date . ' 00:00:00' ) );
			}
			if ($date2) {
				$where ['endtime'] = date ( 'ymdHi', strtotime ( $date2 . ' 00:00:00' ) + 86400 );
			}
			$newdata = array ();
			$this->load->model ( 'GameServerData' );
			$field = '*';
			$group = '';
			$data = $this->GameServerData->worldData ( $where, $field, $group, $order ,$limit);
			$types = array (
					0 => '普通',
					1 => '练习',
					2 => '天梯普通',
					3 => '天梯神兽',
					4 => '排位' 
			);
			$utypes = array (
					1 => '胜利',
					0 => '失败' 
			);
			$etypes = array (
					0 => '死亡',
					1 => '存活',
					2 => '未上场' 
			);
			$items = include APPPATH . '/config/item_types.php'; // 道具字典
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $data,
					'types' => $types,
					'utypes' => $utypes,
					'etypes' => $etypes,
					'itemtypes' => $items 
			] ) );
		} else {
			$this->data ['bt'] = $this->data ['et'] = '';
			$this->data ['viplev_filter'] = true;
			$this->data ['limit_filter'] = true;
			$this->data ['game_filter'] = true;
			$this->data ['dan_filter'] = true;
			$this->data ['account_id_filter'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->data ['eudemon_filter'] = true;
			$this->data ['btype_filter'] = true;
			
			$this->body = 'SystemFunction/worlddata';
			$this->layout ();
		}
	}
	/**
	 * 精灵觉醒
	 *
	 * @author 王涛 --20170120
	 */
	public function elvesAwake() {
		if (parent::isAjax ()) {
			$field = 'accountid,if(sum(item_num)>11,1,0) getAll,if(sum(act_id=1)>0,1,0) getBuyAll,if(sum(act_id=1)>0&&sum(act_id=1)<=5,1,0) get1All,';
			$field .= 'if(sum(act_id=1)>5&&sum(act_id=1)<=20,1,0) get2All,if(sum(act_id=1)>20&&sum(act_id=1)<=51,1,0) get3All,if(sum(act_id=1)>51,1,0) get4All';
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['itemid'] = '3500302704';
			$where ['accountid'] = '0';
			$group = 'accountid';
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$where ['endtime'] = strtotime ( $date . ' 23:59:59' ); // 只统计当日的数据
			$newdata = array ();
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->BehaviorProduceSaleNew ( $where, $field, $group );
			if ($data [0] ['accountid']) {
				$newdata ['date'] = $date;
				$newdata ['getNum'] = count ( $data );
				$newdata ['getAll'] = array_sum ( array_column ( $data, 'getAll' ) );
				$newdata ['getBuyAll'] = array_sum ( array_column ( $data, 'getBuyAll' ) );
				;
				$newdata ['get1All'] = array_sum ( array_column ( $data, 'get1All' ) );
				;
				$newdata ['get2All'] = array_sum ( array_column ( $data, 'get2All' ) );
				;
				$newdata ['get3All'] = array_sum ( array_column ( $data, 'get3All' ) );
				;
				$newdata ['get4All'] = array_sum ( array_column ( $data, 'get4All' ) );
				;
			}
			$this->load->model ( 'GameServerData' );
			$where ['eudemons'] = array (
					100521,
					100522,
					100523,
					100524 
			);
			$field = 'eudemon,sum(num) snum';
			$group = 'eudemon';
			$where ['logdate'] = date ( 'Ymd', $where ['begintime'] );
			$data = $this->GameServerData->eudemonCount ( $where, $field, $group );
			foreach ( $data as $v ) {
				$newdata [$v ['eudemon']] = $v ['snum'];
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $newdata 
			] ) );
		} else {
			$this->data ['hide_end_time'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/elvesawake';
			$this->layout ();
		}
	}
	/**
	 * 固定交换统计
	 *
	 * @author 王涛 --20170119
	 */
	public function Fixchange() {
		if (parent::isAjax ()) {
			$field = 'u.act_id,ceil(user_level/10) as level,sum(item_num) snum,count(i.id) cid';
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			
			$where ['typeids'] = [ 
					20,
					42,
					43,
					44 
			];
			
			$group = 'act_id,level';
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$where ['endtime'] = strtotime ( $date . ' 23:59:59' ); // 只统计当日的数据
			$names = array (
					'20_count' => '每天固定交换次数',
					'20_sum' => '每天固定交换消耗钻石',
					'42_count' => '每天购买固定交换次数',
					'44_count' => '每天刷新固定交换次数',
					'42_sum' => '每天购买固定交换消耗钻石',
					'43_sum' => '每天还原固定交换消耗钻石',
					'44_sum' => '每天刷新固定交换消耗钻石' 
			);
			$newdata = array ();
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->BehaviorProduceSaleNew ( $where, $field, $group );
			if ($data [0] ['act_id']) {
				foreach ( $data as $v ) {
					if (in_array ( $v ['act_id'], array (
							20,
							42,
							44 
					) )) {
						if (! $newdata [$v ['act_id'] . '_count']) {
							$newdata [$v ['act_id'] . '_count'] ['act_name'] = $names [$v ['act_id'] . '_count'];
							for($i = 1; $i <= 10; $i ++) {
								$newdata [$v ['act_id'] . '_count'] ['level_' . $i] = 0;
							}
							$newdata [$v ['act_id'] . '_count'] ['text'] = '';
							if (in_array ( $v ['act_id'], array (
									20,
									44 
							) )) {
								$newdata [$v ['act_id'] . '_count'] ['text'] = "<a href='javascript:showdetail({$v['act_id']})'>详细</a>";
							}
						}
						if ($v ['level'] >= 10) {
							$v ['level'] = 10;
							$newdata [$v ['act_id'] . '_count'] ['level_' . $v ['level']] += $v ['cid'];
						} else {
							$newdata [$v ['act_id'] . '_count'] ['level_' . $v ['level']] = $v ['cid'];
						}
					}
					if (in_array ( $v ['act_id'], array (
							20,
							42,
							43,
							44 
					) )) {
						if (! $newdata [$v ['act_id'] . '_sum']) {
							$newdata [$v ['act_id'] . '_sum'] ['act_name'] = $names [$v ['act_id'] . '_sum'];
							for($i = 1; $i <= 10; $i ++) {
								$newdata [$v ['act_id'] . '_sum'] ['level_' . $i] = 0;
							}
							$newdata [$v ['act_id'] . '_sum'] ['text'] = '';
						}
						if ($v ['level'] >= 10) {
							$v ['level'] = 10;
							$newdata [$v ['act_id'] . '_sum'] ['level_' . $v ['level']] += $v ['snum'];
						} else {
							$newdata [$v ['act_id'] . '_sum'] ['level_' . $v ['level']] = $v ['snum'];
						}
					}
				}
			}
			foreach ( $names as $k => $v ) {
				if (! isset ( $newdata [$k] )) {
					$newdata [$k] ['act_name'] = $v;
					for($i = 1; $i <= 10; $i ++) {
						$newdata [$k] ['level_' . $i] = 0;
					}
					$newdata [$k] ['text'] = '';
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $newdata 
			] ) );
		} else {
			$this->data ['hide_end_time'] = true;
			$this->data ['viplev_filter'] = true;
			$this->body = 'SystemFunction/fixchange';
			$this->layout ();
		}
	}
	
	/**
	 * 通用货币获取消耗新
	 *
	 * @author 王涛 20170104
	 */
	public function CommonCurrencyNew() {
		if (parent::isAjax ()) {
			$this->loadModel ( 'GameServerData' );
			
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d', strtotime ( '-7 days' ) );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			$data = $this->GameServerData->CommonCurrencyNew ( $this->appid, $date1, $server_id, $channel_id, $viplev_min, $viplev_max );
			$output = [ ];
			$names = [ 
					1 => '获得',
					2 => '消耗' 
			];
			$item_types_list = include APPPATH . 'config/item_types.php';
			foreach ( $data as $item ) {
				$output [$item->item_type . $item->daction] ['title'] = $item_types_list [$item->item_type] . $names [$item->daction];
				$output [$item->item_type . $item->daction] [$item->newlev] = ceil ( $item->total_amount / $item->user_count );
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $output 
			] ) );
		} else {
			$this->data ['bt'] = date ( 'Y-m-d' );
			$this->body = 'GameServerData/CommonCurrency';
			$this->layout ();
		}
	}
	/**
	 * 玩家养成情况（从登陆消息中查询
	 */
	public function PlayerDevelop() {
		if (parent::isAjax ()) {
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			$data = $this->SystemFunction_model->PlayerDevelop ( $server_id, $channel_id, $viplev_min, $viplev_max );
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => $data === false ? 'fail' : 'ok',
					'data' => $data 
			] ) );
		} else {
			$this->data ['viplev_filter'] = true;
			$this->body = 'SystemFunction/PlayerDevelop';
			$this->layout ();
		}
	}
	
	/**
	 * 3.捕捉统计数据（TYPE=2,3,4）
	 */
	public function Capture() {
		if (parent::isAjax ()) {
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			$data = $this->SystemFunction_model->PlayerDevelop ( $server_id, $channel_id, $viplev_min, $viplev_max );
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => $data === false ? 'fail' : 'ok',
					'data' => $data 
			] ) );
		} else {
			$this->data ['viplev_filter'] = true;
			$this->body = 'SystemFunction/Capture';
			$this->layout ();
		}
	}
	/**
	 * 3.捕捉统计数据（TYPE=2,3,4）
	 */
	public function money_use() {
		if (parent::isAjax ()) {
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d', strtotime ( '-7 days' ) );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$date1 = strtotime ( $date1 );
			$date2 = strtotime ( $date2 );
			$data = $this->SystemFunction_model->money_use ( $date1, $date2, $server_id, $channel_id );
			if (! $data) {
				echo '{"status":"fail"}';
				exit ();
			}
			// $diff_time = ceil(($date2 - $date1) / 86400);
			$output = [ 
					0 => [ 
							1 => 0,
							2 => 0,
							3 => 0,
							4 => 0,
							5 => 0,
							6 => 0,
							7 => 0,
							8 => 0,
							9 => 0 
					],
					1 => [ 
							1 => 0,
							2 => 0,
							3 => 0,
							4 => 0,
							5 => 0,
							6 => 0,
							7 => 0,
							8 => 0,
							9 => 0 
					] 
			];
			// for ($i=0; $i<$diff_time; $i ++) {
			// $_d = date('Y-m-d', strtotime("+ $i days", $date1));
			// $output[$_d] = [
			// 0 => [1=>0, 2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0, 9=>0],
			// 1 => [1=>0, 2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0, 9=>0],
			// ];
			// }
			
			foreach ( $data as $item ) {
				// $output[$item['date']] = $initdata;
				if (isset ( $output [$item ['get_or_use']] [$item ['currency_type']] )) {
					$output [$item ['get_or_use']] [$item ['currency_type']] += $item ['money'];
				} else {
					$output [$item ['get_or_use']] [$item ['currency_type']] = $item ['money'];
				}
			}
			$html = <<<HTML
                    <tbody>
                        <tr><th>每日获取钻石数量</th><td>{$output[1][2]}</td></tr>
                        <tr><th>每日消耗钻石数量</th><td>{$output[0][2]}</td></tr>
                        <tr><th>每日获取金币数量</th><td>{$output[1][1]}</td></tr>
                        <tr><th>每日消耗金币数量</th><td>{$output[0][1]}</td></tr>
                        <tr><th>每日获取体力数量</th><td>{$output[1][9]}</td></tr>
                        <tr><th>每日消耗体力数量</th><td>{$output[0][9]}</td></tr>
                        <tr><th>每日获取精力数量</th><td>{$output[1][8]}</td></tr>
                        <tr><th>每日消耗精力数量</th><td>{$output[0][8]}</td></tr>
                        <tr><th>每日获取努力点数量</th><td>{$output[1][7]}</td></tr>
                        <tr><th>每日消耗努力点数量</th><td>{$output[0][7]}</td></tr>
                        <tr><th>每日获取联盟币数量</th><td>{$output[1][3]}</td></tr>
                        <tr><th>每日消耗联盟币数量</th><td>{$output[0][3]}</td></tr>
                        <tr><th>每日获取神秘积分数量</th><td>{$output[1][6]}</td></tr>
                        <tr><th>每日消耗神秘积分数量</th><td>{$output[0][6]}</td></tr>
                        <tr><th>每日获取冠军币数量</th><td>{$output[1][4]}</td></tr>
                        <tr><th>每日消耗冠军币数量</th><td>{$output[0][4]}</td></tr>
                        <tr><th>每日获取全球对战币数量</th><td>{$output[1][5]}</td></tr>
                        <tr><th>每日消耗全球对战币数量</th><td>{$output[0][5]}</td></tr>
                    </tbody>
HTML;
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $html 
			] ) );
		} else {
			$this->body = 'SystemFunction/money_use';
			$this->layout ();
		}
	}
	
	/**
	 * 7.道具商店统计数据（typeid=7,10）
	 */
	public function props_shop() {
		if (parent::isAjax ()) {
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d', strtotime ( '-7 days' ) );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$date1 = strtotime ( $date1 );
			$date2 = strtotime ( $date2 );
			$data = $this->SystemFunction_model->props_shop ( $date1, $date2, $server_id, $channel_id );
			if (! $data) {
				echo '{"status":"fail"}';
				exit ();
			}
			// print_r($data);
			$output = $name_list = [ ];
			foreach ( $data as $item ) {
				$name_list [$item ['buy_item_id']] = $item ['buy_item_name'];
				$output [$item ['buy_item_id']] ['num'] += $item ['num'];
				$output [$item ['buy_item_id']] ['cnt'] += $item ['cnt'];
			}
			// print_r($output);exit;
			$html = '';
			foreach ( $output as $id => $item ) {
				$html .= "<tbody>";
				$html .= <<<HTML
                    <tbody>
                        <tr><th colspan="2">道具名称:{$name_list[$id]}({$id})</th></tr>
                        <tr><th>购买数量</th><td>{$item['num']}</td></tr>
                        <tr><th>购买人数</th><td>{$item['cnt']}</td></tr>
                        <tr class="split"><td colspan="2"></td></tr>
HTML;
				// foreach ($items as $id=>$item) {
				//
				// }
				$html .= "</tbody>";
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $html 
			] ) );
		} else {
			$this->body = 'SystemFunction/props_shop';
			$this->layout ();
		}
	}
	public function BehaviorProduceSale() {
		if (parent::isAjax ()) {
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d', strtotime ( '-7 days' ) );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$date1 = strtotime ( $date1 );
			$date2 = strtotime ( $date2 . ' 23:59:59' );
			$accountid = $this->input->get ( 'accountid' );
			$userid = $this->input->get ( 'userid' );
			if (! $accountid && ! $userid) {
				echo '{"status":"fail"}';
				exit ();
			}
			$data = $this->SystemFunction_model->BehaviorProduceSale ( $date1, $date2, $server_id, $channel_id, $accountid, $userid );
			// print_r($data);
			if (! $data) {
				echo '{"status":"fail"}';
				exit ();
			}
			$conf_data = $this->SystemFunction_model->BehaviorProductSaleConf ();
			// print_r($output);exit;
			$html = '';
			foreach ( $data as $item ) {
				$time = date ( 'Y-m-d H:i:s', $item ['created_at'] );
				$params = $conf_data [$item ['counttype']] ['params'];
				$param_txt = is_array ( $params ) ? $params [$item ['param']] : $params;
				$html .= <<<HTML
<tr>
    <td>{$time}</td>
    <td>{$conf_data[$item['counttype']]['title']}</td>
    <td>{$param_txt}:{$item['param']}</td>
    <td>{$item['consume_money']}</td>
    <td>{$item['consume_emoney']}</td>
    <td>{$item['consume_tired']}</td>
    <td>{$item['consume_currency_1']}</td>
    <td>{$item['consume_currency_2']}</td>
    <td>{$item['consume_currency_3']}</td>
    <td>{$item['consume_currency_4']}</td>
    <td>{$item['consume_currency_5']}</td>
    <td>{$item['consume_currency_6']}</td>
    <td>{$item['consume_currency_7']}</td>
    <td>{$item['consume_currency_8']}</td>
    <td>{$item['consume_currency_9']}</td>
    <td>{$item['consume_currency_10']}</td>
    <td>{$item['consume_item_1']}</td>
    <td>{$item['consume_num_1']}</td>
    <td>{$item['consume_item_2']}</td>
    <td>{$item['consume_num_2']}</td>
    <td>{$item['consume_item_3']}</td>
    <td>{$item['consume_num_3']}</td>
    <td>{$item['consume_item_4']}</td>
    <td>{$item['consume_num_4']}</td>
    <td>{$item['consume_item_5']}</td>
    <td>{$item['consume_num_5']}</td>
    <td>{$item['consume_item_6']}</td>
    <td>{$item['consume_num_6']}</td>
    <td>{$item['consume_item_7']}</td>
    <td>{$item['consume_num_7']}</td>
    <td>{$item['consume_item_8']}</td>
    <td>{$item['consume_num_8']}</td>
    <td>{$item['consume_item_9']}</td>
    <td>{$item['consume_num_9']}</td>
    <td>{$item['consume_item_10']}</td>
    <td>{$item['consume_num_10']}</td>
    <td>{$item['get_money']}</td>
    <td>{$item['get_emoney']}</td>
    <td>{$item['get_tired']}</td>
    <td>{$item['get_currency_1']}</td>
    <td>{$item['get_currency_2']}</td>
    <td>{$item['get_currency_3']}</td>
    <td>{$item['get_currency_4']}</td>
    <td>{$item['get_currency_5']}</td>
    <td>{$item['get_currency_6']}</td>
    <td>{$item['get_currency_7']}</td>
    <td>{$item['get_currency_8']}</td>
    <td>{$item['get_currency_9']}</td>
    <td>{$item['get_currency_10']}</td>
    <td>{$item['get_item_1']}</td>
    <td>{$item['get_num_1']}</td>
    <td>{$item['get_item_2']}</td>
    <td>{$item['get_num_2']}</td>
    <td>{$item['get_item_3']}</td>
    <td>{$item['get_num_3']}</td>
    <td>{$item['get_item_4']}</td>
    <td>{$item['get_num_4']}</td>
    <td>{$item['get_item_5']}</td>
    <td>{$item['get_num_5']}</td>
    <td>{$item['get_item_6']}</td>
    <td>{$item['get_num_6']}</td>
    <td>{$item['get_item_7']}</td>
    <td>{$item['get_num_7']}</td>
    <td>{$item['get_item_8']}</td>
    <td>{$item['get_num_8']}</td>
    <td>{$item['get_item_9']}</td>
    <td>{$item['get_num_9']}</td>
    <td>{$item['get_item_10']}</td>
    <td>{$item['get_num_10']}</td>
</tr>
HTML;
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $html 
			] ) );
		} else {
			$this->data ['user_id_filter'] = true;
			$this->data ['account_id_filter'] = true;
			$this->body = 'SystemFunction/BehaviorProduceSale';
			$this->layout ();
		}
	}
	
	/**
	 *
	 * @var $Consume_Output_model Consume_Output_model
	 */
	public $Consume_Output_model = null;
	/**
	 *
	 * @var $GameServerData GameServerData
	 */
	public $GameServerData = null;
	private function loadModel($modelName = 'Consume_Output_model') {
		$this->load->model ( $modelName );
	}
	
	/**
	 * 钻石获取&钻石消耗
	 *
	 * @param int $action
	 *        	0获取,1消耗
	 */
	public function Diamond($action = 0) {
		if (parent::isAjax ()) {
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d', strtotime ( '-7 days' ) );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$date1 = strtotime ( $date1 );
			$date2 = strtotime ( $date2 . ' 23:59:59' );
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			$this->loadModel ( 'Consume_Output_model' );
			$data = $this->Consume_Output_model->Diamond ( $action, $this->appid, $date1, $date2, $server_id, $channel_id, $viplev_min, $viplev_max );
			$output = [ ];
			
			$day_diff = ceil ( ($date2 - $date1) / 86400 );
			
			$count_types = [ ];
			foreach ( $data as $item ) {
				$count_types [] = $item ['counttype'];
				$output [$item ['_date']] [$item ['counttype']] = $item ['emoney'];
			}
			$title_list = [ ];
			$count_types = array_unique ( $count_types );
			sort ( $count_types );
			$consume_types = include APPPATH . 'config/comsume_types.php';
			foreach ( $count_types as $count_type_idx ) {
				$title_list [$count_type_idx] = $consume_types [$count_type_idx];
				for($i = 0; $i < $day_diff; $i ++) {
					$_date = date ( 'Y/m/d', strtotime ( "+$i days", $date1 ) );
					if (isset ( $output [$_date] [$count_type_idx] ))
						continue;
					$output [$_date] [$count_type_idx] = 0;
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $output,
					'titles' => $title_list 
			] ) );
		} else {
			$this->data ['bt'] = $this->data ['et'] = date ( 'Y-m-d' );
			$this->body = 'Consume_Output/Diamond';
			$this->layout ();
		}
	}
	public function Diamond_use() {
		$this->Diamond ( 1 );
	}
	
	/**
	 * 活跃度统计
	 */
	public function PlayerActive() {
		if (parent::isAjax ()) {
			$this->loadModel ( 'GameServerData' );
			
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d', strtotime ( '-7 days' ) );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			$lev_min = $this->input->get ( 'lev_min' );
			$lev_max = $this->input->get ( 'lev_max' );
			
			$data = $this->GameServerData->PlayerActive ( $this->appid, $date1, $date2, $server_id, $channel_id, $viplev_min, $viplev_max, $lev_min, $lev_max );
			$output = [ ];
			foreach ( $data as $item ) {
				$output [$item->log_date] ['total_user'] += $item->user_count;
				$output [$item->log_date] [$item->active_level] = $item->user_count;
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $output 
			] ) );
		} else {
			$this->data ['bt'] = $this->data ['et'] = date ( 'Y-m-d' );
			$this->body = 'GameServerData/PlayerActive';
			$this->layout ();
		}
	}
	
	/**
	 * 玩法次数统计
	 */
	public function PlayingMethod() {
		if (parent::isAjax ()) {
			$this->loadModel ( 'GameServerData' );
			$methods_list = [ 
					1 => '普通副本',
					2 => '挑战副本',
					3 => '捕捉玩法',
					4 => '狩猎场',
					5 => '全球对战',
					6 => '联盟大赛',
					7 => '精灵塔',
					8 => '超梦来袭-',
					9 => '金币玩法',
					10 => '试练副本-A',
					11 => '试练副本-B',
					12 => '试练副本-C',
					13 => '派遣任务',
					14 => '答题活动' 
			];
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ); // ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			$data = $this->GameServerData->PlayingMethod ( $this->appid, $date1, $server_id, $channel_id, $viplev_min, $viplev_max );
			$output = [ ];
			if (! $data)
				$status = 'fail';
			else {
				$status = 'ok';
				foreach ( $data as $item ) {
					$output ['summary'] [$item->lev] += $item->user_count;
					$output ['times'] [$item->method] [$item->lev] += $item->playing_times;
					$output ['time'] [$item->method] [$item->lev] += $item->playing_time;
				}
				$output ['methods_list'] = $methods_list;
			}
			
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => $status,
					'data' => $output 
			] ) );
		} else {
			$this->data ['bt'] = date ( 'Y-m-d' );
			$this->body = 'GameServerData/PlayingMethod';
			$this->layout ();
		}
	}
	
	/**
	 * 通用货币获取消耗
	 */
	public function CommonCurrency($daction = 1) {
		if (parent::isAjax ()) {
			$this->loadModel ( 'GameServerData' );
			
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d', strtotime ( '-7 days' ) );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			$data = $this->GameServerData->CommonCurrency ( $this->appid, $date1, $server_id, $channel_id, $viplev_min, $viplev_max, $daction );
			$output = [ ];
			$total = [ 
					1 => 0,
					2 => 0 
			];
			$item_types_list = include APPPATH . 'config/item_types.php';
			foreach ( $data as $item ) {
				$total [$item->daction] += $item->user_count;
				if ($item->lev < 10)
					$lev = 1;
				elseif ($item->lev > 10 && $item->lev <= 20)
					$lev = 2;
				elseif ($item->lev > 20 && $item->lev <= 30)
					$lev = 3;
				elseif ($item->lev > 30 && $item->lev <= 40)
					$lev = 4;
				elseif ($item->lev > 40 && $item->lev <= 50)
					$lev = 5;
				elseif ($item->lev > 50 && $item->lev <= 60)
					$lev = 6;
				elseif ($item->lev > 60 && $item->lev <= 70)
					$lev = 7;
				elseif ($item->lev > 70 && $item->lev <= 80)
					$lev = 8;
				elseif ($item->lev > 80 && $item->lev <= 90)
					$lev = 9;
				elseif ($item->lev > 90 && $item->lev <= 100)
					$lev = 10;
				else
					$lev = 11;
				$output [$item->item_type] ['title'] = $item_types_list [$item->item_type];
				$output [$item->item_type] [$lev] = ceil ( $item->total_amount / $item->user_count );
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $output 
			] ) );
		} else {
			$this->data ['bt'] = date ( 'Y-m-d' );
			$this->body = 'GameServerData/CommonCurrency';
			$this->layout ();
		}
	}
	public function CommonCurrency_use() {
		$this->CommonCurrency ( 2 );
	}
	/**
	 * 精灵星级&关卡统计
	 */
	public function ElfStarLev() {
		if (parent::isAjax ()) {
			$this->loadModel ( 'GameServerData' );
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ); // ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
			$date2 = $this->input->get ( 'date2' ); // ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			$data = $this->GameServerData->ElfStarLev ( $this->appid, $date1, $date2, $server_id, $channel_id );
			$output = [ ];
			if (! $data)
				$status = 'fail';
			else {
				$status = 'ok';
				foreach ( $data as $item ) {
					$output [$item->viplev] [9999] = ceil ( $item->lev / $item->user_count );
					$output [$item->viplev] [1] = ceil ( $item->elf_1 / $item->user_count );
					$output [$item->viplev] [2] = ceil ( $item->elf_2 / $item->user_count );
					$output [$item->viplev] [3] = ceil ( $item->elf_3 / $item->user_count );
					$output [$item->viplev] [4] = ceil ( $item->elf_4 / $item->user_count );
					$output [$item->viplev] [5] = ceil ( $item->elf_5 / $item->user_count );
					$output [$item->viplev] [6] = ceil ( $item->elf_6 / $item->user_count );
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => $status,
					'data' => $output 
			] ) );
		} else {
			$this->data ['bt'] = date ( 'Y-m-d' );
			$this->data ['et'] = date ( 'Y-m-d' );
			$this->body = 'GameServerData/ElfStarLev';
			$this->layout ();
		}
	}
	
	/**
	 * 图鉴等级
	 */
	public function PhotoLevel() {
		if (parent::isAjax ()) {
			$this->loadModel ( 'GameServerData' );
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ); // ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
			$date2 = $this->input->get ( 'date2' ); // ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			$data = $this->GameServerData->PhotoLevel ( $this->appid, $date1, $date2, $server_id, $channel_id );
			$output = [ ];
			if (! $data)
				$status = 'fail';
			else {
				$status = 'ok';
				foreach ( $data as $item ) {
					$output [$item->viplev] [9999] = ceil ( $item->lev / $item->user_count );
					$output [$item->viplev] [1] = ceil ( $item->pht_1 / $item->user_count );
					$output [$item->viplev] [2] = ceil ( $item->pht_2 / $item->user_count );
					$output [$item->viplev] [3] = ceil ( $item->pht_3 / $item->user_count );
					$output [$item->viplev] [4] = ceil ( $item->pht_4 / $item->user_count );
					$output [$item->viplev] [5] = ceil ( $item->pht_5 / $item->user_count );
					$output [$item->viplev] [6] = ceil ( $item->pht_6 / $item->user_count );
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => $status,
					'data' => $output 
			] ) );
		} else {
			$this->data ['bt'] = date ( 'Y-m-d' );
			$this->data ['et'] = date ( 'Y-m-d' );
			$this->body = 'GameServerData/PhotoLevel';
			$this->layout ();
		}
	}
	/**
	 * 关卡进度统计-vip关卡
	 * 
	 * @author 王涛 20170412
	 */
	public function LevelProgress() {
		if (parent::isAjax ()) {
			$this->loadModel ( 'GameServerData' );
			$type = $this->input->get ( "processtype");
			$date1 = $this->input->get ( 'date1' ); // ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
			$where['date'] = date('Ymd',strtotime($date1));
			$where['chapter_min'] = $this->input->get ( 'chapter_min' );
			$where['chapter_max'] = $this->input->get ( 'chapter_max' );
			if($type != 2){
				$titleflied = "maxGroup mg,progress_num pn,CONCAT(maxGroup,'-',progress_num)title";
			}else{
				$titleflied = "maxGroup2 mg,progress_num2 pn,CONCAT(maxGroup2,'-',progress_num2)title";
			}
			$titlegroup = "mg,pn";
			$titledata = $this->GameServerData->process ( $where,$titleflied,$titlegroup); //获取标题
			$where['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where['viplev_max'] = $this->input->get ( 'viplev_max' );
			$where['serverids'] = $this->input->get ( 'server_id' );
			$where['outtime'] = $this->input->get ( 'lostday' )?$this->input->get ( 'lostday' ):7;
			if($type != 2){
				$field = "vip_level,maxGroup mg,progress_num pn,process_status ps,CONCAT(maxGroup,'-',progress_num)title,count(*)t";
			}else{
				$field = "vip_level,maxGroup2 mg,progress_num2 pn,process_status2 ps,CONCAT(maxGroup2,'-',progress_num2)title,count(*)t";
			}
			$group = "vip_level,ps,mg,pn";
			
			$data = $this->GameServerData->process ( $where,$field,$group); //获取数据
			$newdata = array();
			$stas = array(
				1=>'失败',		
					0=>'自然',
					2=>'全',
			);
			$newdatas = $processdata=array();
			$newdatas['all']['titlename'] = 'sum-全';
			foreach ($data as $v){
				if(!isset($newdata[$v['vip_level']])){
					$newdata[$v['vip_level'].'-2']['titlename'] = 'vip'.$v['vip_level'].'-全';
				}
				if(!isset($newdata['all-'.$v['ps']])){
					$newdatas['all-'.$v['ps']]['titlename'] = 'sum-'.$stas[$v['ps']];
				}
				$newdata[$v['vip_level'].'-2'][$v['title']] += $v['t'];
				$newdata[$v['vip_level'].'-2']['all'] += $v['t'];
				$newdata[$v['vip_level'].'-'.$v['ps']]['titlename'] = 'vip'.$v['vip_level'].'-'.$stas[$v['ps']];
				$newdata[$v['vip_level'].'-'.$v['ps']][$v['title']] = $v['t'];
				$newdata[$v['vip_level'].'-'.$v['ps']]['all'] += $v['t'];
				$newdatas['all-'.$v['ps']][$v['title']] += $v['t'];
				$newdatas['all-'.$v['ps']]['all'] += $v['t'];
				$newdatas['all'][$v['title']]+=$v['t'];
				$newdatas['all']['all']+=$v['t'];
			}
			$newdata+=$newdatas;
			$i=0;
			$where['viplev_min'] = $this->input->get ( 'viplev_min' );
			$beginvip=$where['viplev_min']?$where['viplev_min']:0;
			$endvip=$where['viplev_max']?$where['viplev_max']:12;
			$where['viplev_max'] = $this->input->get ( 'viplev_max' );
			foreach ($titledata as $v){
				$processtitle[]=$v['title'];
				for($t=$beginvip;$t<=$endvip;$t++){ //vip等级
					if(!isset($newdata[$t.'-2'][$v['title']])){//全
						$processdata[$t.'-2'][$v['title']]=0;
					}else{
						$processdata[$t.'-2'][$v['title']]=$newdata[$t.'-2'][$v['title']];
					}
					if(!isset($newdata[$t.'-0'][$v['title']])){//自然
						$processdata[$t.'-0'][$v['title']]=0;
					}else{
						$processdata[$t.'-0'][$v['title']]=$newdata[$t.'-0'][$v['title']];
					}
					if(!isset($newdata[$t.'-1'][$v['title']])){//失败
						$processdata[$t.'-1'][$v['title']]=0;
					}else{
						$processdata[$t.'-1'][$v['title']]=$newdata[$t.'-1'][$v['title']];
					}
					
				}
				if($i++>20)break;
			}
			$i=0;
			foreach ($processdata as $k=>$v){
				$d=explode('-', $k);
				$legend['data'][] = 'vip'.$d[0].$stas[$d[1]];
				if($i++>2){
					$legend['selected']['vip'.$d[0].$stas[$d[1]]] = false;
				}
				
				$json_data[] = [
					'name' => 'vip'.$d[0].$stas[$d[1]],
					'type' => 'bar',
					'data'  => array_values($v),
				];
			}
			
			//print_r($legend);die;
			unset($data);
			//print_r($newdata);die;
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => array(
						'data'=>$newdata,
						'title'=>$titledata,
					),
					'series'     =>$json_data,
					'legend'     =>$legend,
					'category'=>$processtitle,
			] ) );
		} else {
			$this->data ['bt'] = date ( 'Y-m-d' ,strtotime("-1 days"));
			$this->body = 'GameServerData/LevelProgress';
			$this->layout ();
		}
	}
	
	/**
	 * 关卡进度统计--废弃
	 */
	/*public function LevelProgress() {
		if (parent::isAjax ()) {
			$this->loadModel ( 'GameServerData' );
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ); // ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
			$date2 = $this->input->get ( 'date2' ); // ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			$data = $this->GameServerData->LevelProgress ( $this->appid, $date1, $date2, $server_id, $channel_id, $viplev_min, $viplev_max );
			$output = [ ];
			if (! $data)
				$status = 'fail';
			else {
				$status = 'ok';
				foreach ( $data as $item ) {
					$output [$item->lev] [1] = ( int ) $item->user_count;
					$output [$item->lev] [2] = ceil ( $item->fighting / $item->user_count );
					$output [$item->lev] [3] = ceil ( $item->nomal_copy / $item->user_count );
					$output [$item->lev] [4] = ceil ( $item->nomal_elite / $item->user_count );
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => $status,
					'data' => $output 
			] ) );
		} else {
			$this->data ['bt'] = date ( 'Y-m-d' );
			$this->data ['et'] = date ( 'Y-m-d' );
			$this->body = 'GameServerData/LevelProgress';
			$this->layout ();
		}
	}*/
	/**
	 * 关卡难易程度统计
	 */
	public function LevelDifficulty() {
		if (parent::isAjax ()) {
			$this->loadModel ( 'GameServerData' );
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ); // ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
			$date2 = $this->input->get ( 'date2' ); // ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			$copy_type = $this->input->get ( 'copy_type' ); // 1 || 2
			$data = $this->GameServerData->LevelDifficulty ( $this->appid, $date1, $date2, $server_id, $channel_id, $viplev_min, $viplev_max, $copy_type );
			$output = [ ];
			if (! $data)
				$status = 'fail';
			else {
				$status = 'ok';
				foreach ( $data ['total_user'] as $item ) {
					$output [$item->level_id] ['total_user'] = $item->user_count;
					$output [$item->level_id] ['3star_pass'] = 0;
					$output [$item->level_id] ['3star_first_pass'] = 0;
					$output [$item->level_id] ['2star_first_pass'] = 0;
					$output [$item->level_id] ['1star_first_pass'] = 0;
					$output [$item->level_id] ['star_first_fail'] = 0;
					$output [$item->level_id] ['avg_fail_times'] = 0;
					$output [$item->level_id] ['3star_pass_times'] = 0;
					$output [$item->level_id] ['level'] = 0;
					$output [$item->level_id] ['fighting'] = 0;
				}
				if ($data ['3star_pass'] != false) {
					foreach ( $data ['3star_pass'] as $item ) {
						$output [$item->level_id] ['3star_pass'] = $item->user_count;
						$output [$item->level_id] ['3star_pass_times'] = $item->max_star_times;
					}
				}
				if ($data ['star_first_pass'] != false) {
					foreach ( $data ['star_first_pass'] as $item ) {
						if ($item->star == 1)
							$output [$item->level_id] ['1star_first_pass'] = $item->user_count;
						if ($item->star == 2)
							$output [$item->level_id] ['2star_first_pass'] = $item->user_count;
						if ($item->star == 3)
							$output [$item->level_id] ['3star_first_pass'] = $item->user_count;
					}
				}
				if ($data ['star_first_fail'] != false) {
					foreach ( $data ['star_first_fail'] as $item ) {
						$output [$item->level_id] ['star_first_fail'] = $item->user_count;
					}
				}
				if ($data ['avg_fail_times'] != false) {
					foreach ( $data ['avg_fail_times'] as $item ) {
						$output [$item->level_id] ['avg_fail_times'] = ceil ( $item->failure_times / $output [$item->level_id] ['total_user'] );
					}
				}
				if ($data ['fight_level'] != false) {
					foreach ( $data ['fight_level'] as $item ) {
						$output [$item->level_id] ['level'] = ceil ( $item->avg_level / $output [$item->level_id] ['total_user'] );
						$output [$item->level_id] ['fighting'] = ceil ( $item->avg_fighting / $output [$item->level_id] ['total_user'] );
					}
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => $status,
					'data' => $output 
			] ) );
		} else {
			$this->data ['bt'] = date ( 'Y-m-d' );
			$this->data ['et'] = date ( 'Y-m-d' );
			$this->body = 'GameServerData/LevelDifficulty';
			$this->layout ();
		}
	}
	
	/**
	 * 商店销售统计
	 */
	public function ShopSaleCount() {
		if (parent::isAjax ()) {
			$this->loadModel ( 'Consume_Output_model' );
			$server_id = $this->input->get ( 'server_id' );
			$channel_id = $this->input->get ( 'channel_id' );
			$date1 = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d', strtotime ( '-7 days' ) );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$date1 = strtotime ( $date1 );
			$date2 = strtotime ( $date2 . ' 23:59:59' );
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			$data = $this->Consume_Output_model->ShopSaleCount ( $this->appid, $date1, $date2, $server_id, $channel_id, $viplev_min, $viplev_max );
			$status = 'fail';
			if (! $data) {
				$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
						'status' => $status,
						'data' => [ ] 
				] ) );
				return false;
			}
			$status = 'ok';
			$format_array = [ ];
			$total = [ ];
			$date_list = [ ];
			foreach ( $data as $item ) {
				$date_list [] = $item->_date;
				$format_array [$item->param] [$item->_date] [$item->item] ['times'] = $item->cnt;
				$format_array [$item->param] [$item->_date] [$item->item] ['num'] = $item->num;
				$total [$item->param] [$item->_date] += $item->num;
			}
			$output = [ ];
			$items_types = include APPPATH . 'config/item_types.php';
			$consume_types = include APPPATH . 'config/comsume_types.php';
			foreach ( $format_array as $param => $params ) {
				foreach ( $params as $date => $items ) {
					$idx = 0;
					foreach ( $items as $key => $item ) {
						if ($idx >= 3)
							continue;
						$output [$param] [$date] [$idx] = [ 
								'item_type' => $key,
								'item_type_name' => $items_types [$key],
								'times' => $item ['times'],
								'num' => $item ['num'] 
						];
						$idx += 1;
					}
				}
			}
			$date_list = array_unique ( $date_list );
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => $status,
					'data' => $output,
					'header' => $date_list,
					'consume_types' => $consume_types 
			] ) );
		} else {
			$this->data ['bt'] = $this->data ['et'] = date ( 'Y-m-d' );
			$this->body = 'GameServerData/ShopCount';
			$this->layout ();
		}
	}
	
	/*
	 * 	全球对战-战斗回合数统计   zzl 20170814 
	 */
	public function combatBout(){		
		
		$server_list = include APPPATH . '/config/server_list.php'; // 统计类型字典
	
	
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$where ['type'] = $this->input->get ( 'gametype' ); // 对战类型
			$where ['btype'] =1; // 对战类型
			if($where ['btype'] == 4){
				unset($where ['type']);
			}
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$where ['estatus'] = $this->input->get ( 'estatus' );
			$where ['dan'] = $this->input->get ( 'dan' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['typeids'] = $this->input->get ( 'type_id' );
			$where ['dan_s'] = $this->input->get ( 'dan_s' );
			$where ['dan_e'] = $this->input->get ( 'dan_e' );
			
			$items = include APPPATH . '/config/item_types.php'; // 道具字典
			if ($date) {
				$where ['begintime'] = date ( 'ymdHi', strtotime ( $date . ' 00:00:00' ) );
			}
			if ($date2) {
				$where ['endtime'] = date ( 'ymdHi', strtotime ( $date2 . ' 00:00:00' ) + 86400 );
			}
			

		
			
			$this->load->model ( 'GameServerData' );	
		    $field = 'continuous,count(DISTINCT gameid) cid';
			$group = 'continuous';
			$order = 'continuous';
			$data = $this->GameServerData->combatBout ( $where, $field, $group, $order );
			foreach ( $data as &$v ) {
				$v ['text']="<a href='javascript:dan({$v['continuous']},0)'>段位分布</a> ";
		
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $data,
					'cgid' => $cgid
			] ) );
		} else {		
			
			$this->data ['show_dan_list'] = true;
		    $this->data ['game_filter'] = true;	
			$this->data ['hide_channel_list'] = true;			
			$this->body = 'SystemFunction/combatBout';
			$this->layout ();
		}
		
		
		
	}
	
	
	/*
	 *  精灵推荐配招
	 */
	public function recommend(){
	    

	    if (parent::isAjax ()) {
	        $date = $this->input->get ( 'date1' );
	        $date2 = $this->input->get ( 'date2' );

	        
	        $combattype = $this->input->get ( 'combattype' );
	        if($combattype==0){	            
	            $where ['btype']=1;
	            $where ['type']=1;
	        }elseif ($combattype==1){
	            $where ['btype']=1;
	            $where ['type']=2;
	        }elseif ($combattype==2){
	            $where ['btype']=1;
	            $where ['type']=3;
	        }elseif ($combattype==3){
	            $where ['btype']=2;	           
	        }	        
	        
	        $where ['date'] = $date;
	        $where ['viplev_min'] = $this->input->get ( 'viplev_min' );
	        $where ['eudemon'] = $this->input->get ( 'eudemon' );
	        $where ['viplev_max'] = $this->input->get ( 'viplev_max' );
	        $where ['estatus'] = $this->input->get ( 'estatus' );
	        $where ['dan'] = $this->input->get ( 'dan' );
	        $where ['serverids'] = $this->input->get ( 'server_id' );
	        $where ['typeids'] = $this->input->get ( 'type_id' );
	        $where ['dan_s'] = $this->input->get ( 'dan_s' );
	        $where ['dan_e'] = $this->input->get ( 'dan_e' );	
	        if ($date) {
	            $where ['begintime'] = date ( 'Ymd', strtotime ( $date ) );
	        }
	        if ($date2) {
	            $where ['endtime'] = date ( 'Ymd', strtotime ( $date2 ));
	        }
	        	
	    
	    
	        	
	        $this->load->model ( 'SystemFunction_model' );
	        $field = "left((concat('20',gd.endTime)),8) as endTime,gu.userid,gu.name,gu.serverid,gu.dan,gu.viplevel,gu.level,gue.eudemon,gue.skills1,gue.skills2,gue.skills3,gue.skills4,gue.abilities,gue.fruit,gue.equip,gue.kidney";
	        $group = 'gu.accountid';
	        $order = 'gu.accountid,gu.dan';
	        $data = $this->SystemFunction_model->recommend ($where, $field  ,$group,$order,$limit );
	        
	
	       
	        $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
	            'status' => 'ok',
	            'data' => $data,
	            'cgid' => $cgid
	        ] ) );
	    } else {	        	
	        
	        $this->data ['show_combat_type'] = true;
	        $this->data ['register_time'] = false;
	        $this->data ['hide_start_time'] = true;
	        $this->data ['hide_end_time'] = true;
	        $this->data ['show_start_time_month'] = true;
	        $this->data ['show_dan_list'] = true;
	        $this->data ['show_eudemonr'] = true;

	        $this->data ['hide_channel_list'] = true;
	        $this->body = 'SystemFunction/recommend';
	        $this->layout ();
	    }

	}
	/*
	 *  技能专精  zzl 20170926
	 */
	public function mastery(){
	    

	    if (parent::isAjax ()) {
	        $date = $this->input->get ( 'date1' );
	        $date2 = $this->input->get ( 'date2' );
	           
	        $where ['date'] = $date;
	        $where ['viplev_min'] = $this->input->get ( 'viplev_min' );
	        $where ['eudemon'] = $this->input->get ( 'eudemon' );
	        $where ['viplev_max'] = $this->input->get ( 'viplev_max' );
	        $where ['estatus'] = $this->input->get ( 'estatus' );
	        $where ['dan'] = $this->input->get ( 'dan' );
	        $where ['serverids'] = $this->input->get ( 'server_id' );
	        $where ['typeids'] = $this->input->get ( 'type_id' );
	        $where ['dan_s'] = $this->input->get ( 'dan_s' );
	        $where ['dan_e'] = $this->input->get ( 'dan_e' );
	        if ($date) {
	            $where ['begintime'] = substr(date ( 'Ymd', strtotime ( $date ) ), 2,7) ;
	        }
	        if ($date2) {
	            $where ['endtime'] = date ( 'Ymd', strtotime ( $date2 ));
	        }
	          $this->load->model ( 'SystemFunction_model' );
	
	        $field = "count(DISTINCT gsy.account_id) as total,dan,count(DISTINCT gsy.account_id) as user_total,count(if(group_id=1,true,null)) as num_1,count(if(group_id=2,true,null)) as num_2,count(if(group_id=3,true,null)) as num_3,count(if(group_id=4,true,null)) as num_4,count(if(group_id=5,true,null)) as num_5,count(if(group_id=6,true,null)) as num_6,count(if(group_id=7,true,null)) as num_7,count(if(group_id=8,true,null)) as num_8,count(if(group_id=9,true,null)) as num_9,count(if(group_id=10,true,null)) as num_10,count(if(group_id=11,true,null)) as num_11,count(if(group_id=12,true,null)) as num_12,count(if(group_id=13,true,null)) as num_13,count(if(group_id=14,true,null)) as num_14,count(if(group_id=15,true,null)) as num_15,count(if(group_id=16,true,null)) as num_16,count(if(group_id=17,true,null)) as num_17,count(if(group_id=18,true,null)) as num_18,sum(if(group_id=1,gsy.level,null)) as sum_1,sum(if(group_id=2,gsy.level,null)) as sum_2,sum(if(group_id=3,gsy.level,null)) as sum_3,sum(if(group_id=4,gsy.level,null)) as sum_4,sum(if(group_id=5,gsy.level,null)) as sum_5,sum(if(group_id=6,gsy.level,null)) as sum_6,sum(if(group_id=7,gsy.level,null)) as sum_7,sum(if(group_id=8,gsy.level,null)) as sum_8,sum(if(group_id=9,gsy.level,null)) as sum_9,sum(if(group_id=10,gsy.level,null)) as sum_10,sum(if(group_id=11,gsy.level,null)) as sum_11,sum(if(group_id=12,gsy.level,null)) as sum_12,sum(if(group_id=13,gsy.level,null)) as sum_13,sum(if(group_id=14,gsy.level,null)) as sum_14,sum(if(group_id=15,gsy.level,null)) as sum_15,sum(if(group_id=16,gsy.level,null)) as sum_16,sum(if(group_id=17,gsy.level,null)) as sum_17,sum(if(group_id=18,gsy.level,null)) as sum_18";
	        $group = 'dan';
	        
	        
	        
	        $order = '';
	        $data = $this->SystemFunction_model->mastery ($where, $field  ,$group,$order,$limit );
	         
	        foreach ($data as &$v){
	          $total_num=$v['num_1']+$v['num_2']+$v['num_3']+$v['num_4']+$v['num_5']+$v['num_6']+$v['num_7']+$v['num_8']+$v['num_9']+$v['num_10']+$v['num_11']+$v['num_12']+$v['num_13']+$v['num_14']+$v['num_15']+$v['num_16']+$v['num_17']+$v['num_18'];
	          $total_sum=$v['sum_1']+$v['sum_2']+$v['sum_3']+$v['sum_4']+$v['sum_5']+$v['sum_6']+$v['sum_7']+$v['sum_8']+$v['sum_9']+$v['sum_10']+$v['sum_11']+$v['sum_12']+$v['sum_13']+$v['sum_14']+$v['sum_15']+$v['sum_16']+$v['sum_17']+$v['sum_18'];   
	          $v['avg_mastery']=round($total_sum/$total_num,2);
	    
	          
	            $v['num_1']=round($v['sum_1']/$v['num_1'],2);
	            $v['num_2']=round($v['sum_2']/$v['num_2'],2);
	            $v['num_3']=round($v['sum_3']/$v['num_3'],2);
	            $v['num_4']=round($v['sum_4']/$v['num_4'],2);
	            $v['num_5']=round($v['sum_5']/$v['num_5'],2);
	            $v['num_6']=round($v['sum_6']/$v['num_6'],2);
	            $v['num_7']=round($v['sum_7']/$v['num_7'],2);
	            $v['num_8']=round($v['sum_8']/$v['num_8'],2);
	            $v['num_9']=round($v['sum_9']/$v['num_9'],2);
	            $v['num_10']=round($v['sum_10']/$v['num_10'],2);
	            $v['num_11']=round($v['sum_11']/$v['num_11'],2);
	            $v['num_12']=round($v['sum_12']/$v['num_12'],2);
	            $v['num_13']=round($v['sum_13']/$v['num_13'],2);
	            $v['num_14']=round($v['sum_14']/$v['num_14'],2);
	            $v['num_15']=round($v['sum_15']/$v['num_15'],2);
	            $v['num_16']=round($v['sum_16']/$v['num_16'],2);
	            $v['num_17']=round($v['sum_17']/$v['num_17'],2);
	            $v['num_18']=round($v['sum_18']/$v['num_18'],2);
	            
	            
	        }
	    
	    
	        $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
	            'status' => 'ok',
	            'data' => $data,
	            'cgid' => $cgid
	        ] ) );
	    } else {
	         

	        $this->data ['hide_server_list'] = true;
	        
	        $this->data ['hide_end_time'] = true;
	        $this->data ['hide_channel_list'] = true;
	        $this->body = 'SystemFunction/mastery';
	        $this->layout ();
	    }
	    
	    
	}
	
	
	/*
	 *  徽章  zzl 20170926
	 */
	public function badge(){



	    if (parent::isAjax ()) {
	        $date = $this->input->get ( 'date1' );
	        $date2 = $this->input->get ( 'date2' );
	    
	        $where ['date'] = $date;
	        $where ['viplev_min'] = $this->input->get ( 'viplev_min' );
	        $where ['eudemon'] = $this->input->get ( 'eudemon' );
	        $where ['viplev_max'] = $this->input->get ( 'viplev_max' );
	        $where ['estatus'] = $this->input->get ( 'estatus' );
	        $where ['dan'] = $this->input->get ( 'dan' );
	        $where ['serverids'] = $this->input->get ( 'server_id' );
	        $where ['typeids'] = $this->input->get ( 'type_id' );
	        $where ['dan_s'] = $this->input->get ( 'dan_s' );
	        $where ['dan_e'] = $this->input->get ( 'dan_e' );
	        if ($date) {
	            $where ['begintime'] = substr(date ( 'Ymd', strtotime ( $date ) ), 2,7) ;
	        }
	        if ($date2) {
	            $where ['endtime'] = date ( 'Ymd', strtotime ( $date2 ));
	        }
	        $this->load->model ( 'SystemFunction_model' );
	    
	        $field = "count(DISTINCT gsy.account_id) as total,dan,count(DISTINCT gsy.account_id) as user_total,count(if(group_id=1,true,null)) as num_1,count(if(group_id=2,true,null)) as num_2,count(if(group_id=3,true,null)) as num_3,count(if(group_id=4,true,null)) as num_4,count(if(group_id=5,true,null)) as num_5,count(if(group_id=6,true,null)) as num_6,count(if(group_id=7,true,null)) as num_7,count(if(group_id=8,true,null)) as num_8,count(if(group_id=9,true,null)) as num_9,count(if(group_id=10,true,null)) as num_10,count(if(group_id=11,true,null)) as num_11,count(if(group_id=12,true,null)) as num_12,count(if(group_id=13,true,null)) as num_13,count(if(group_id=14,true,null)) as num_14,count(if(group_id=15,true,null)) as num_15,count(if(group_id=16,true,null)) as num_16,count(if(group_id=17,true,null)) as num_17,count(if(group_id=18,true,null)) as num_18,sum(if(group_id=1,gsy.level,null)) as sum_1,sum(if(group_id=2,gsy.level,null)) as sum_2,sum(if(group_id=3,gsy.level,null)) as sum_3,sum(if(group_id=4,gsy.level,null)) as sum_4,sum(if(group_id=5,gsy.level,null)) as sum_5,sum(if(group_id=6,gsy.level,null)) as sum_6,sum(if(group_id=7,gsy.level,null)) as sum_7,sum(if(group_id=8,gsy.level,null)) as sum_8,sum(if(group_id=9,gsy.level,null)) as sum_9,sum(if(group_id=10,gsy.level,null)) as sum_10,sum(if(group_id=11,gsy.level,null)) as sum_11,sum(if(group_id=12,gsy.level,null)) as sum_12,sum(if(group_id=13,gsy.level,null)) as sum_13,sum(if(group_id=14,gsy.level,null)) as sum_14,sum(if(group_id=15,gsy.level,null)) as sum_15,sum(if(group_id=16,gsy.level,null)) as sum_16,sum(if(group_id=17,gsy.level,null)) as sum_17,sum(if(group_id=18,gsy.level,null)) as sum_18";
	        $group = 'dan';
	         
	         
	         
	        $order = '';
	        $data = $this->SystemFunction_model->mastery ($where, $field  ,$group,$order,$limit );
	    
	        foreach ($data as &$v){
	            $total_num=$v['num_1']+$v['num_2']+$v['num_3']+$v['num_4']+$v['num_5']+$v['num_6']+$v['num_7']+$v['num_8']+$v['num_9']+$v['num_10']+$v['num_11']+$v['num_12']+$v['num_13']+$v['num_14']+$v['num_15']+$v['num_16']+$v['num_17']+$v['num_18'];
	            $total_sum=$v['sum_1']+$v['sum_2']+$v['sum_3']+$v['sum_4']+$v['sum_5']+$v['sum_6']+$v['sum_7']+$v['sum_8']+$v['sum_9']+$v['sum_10']+$v['sum_11']+$v['sum_12']+$v['sum_13']+$v['sum_14']+$v['sum_15']+$v['sum_16']+$v['sum_17']+$v['sum_18'];
	            $v['avg_mastery']=round($total_sum/$total_num,2);
	             
	             
	            $v['num_1']=round($v['sum_1']/$v['num_1'],2);
	            $v['num_2']=round($v['sum_2']/$v['num_2'],2);
	            $v['num_3']=round($v['sum_3']/$v['num_3'],2);
	            $v['num_4']=round($v['sum_4']/$v['num_4'],2);
	            $v['num_5']=round($v['sum_5']/$v['num_5'],2);
	            $v['num_6']=round($v['sum_6']/$v['num_6'],2);
	            $v['num_7']=round($v['sum_7']/$v['num_7'],2);
	            $v['num_8']=round($v['sum_8']/$v['num_8'],2);
	            $v['num_9']=round($v['sum_9']/$v['num_9'],2);
	            $v['num_10']=round($v['sum_10']/$v['num_10'],2);
	            $v['num_11']=round($v['sum_11']/$v['num_11'],2);
	            $v['num_12']=round($v['sum_12']/$v['num_12'],2);
	            $v['num_13']=round($v['sum_13']/$v['num_13'],2);
	            $v['num_14']=round($v['sum_14']/$v['num_14'],2);
	            $v['num_15']=round($v['sum_15']/$v['num_15'],2);
	            $v['num_16']=round($v['sum_16']/$v['num_16'],2);
	            $v['num_17']=round($v['sum_17']/$v['num_17'],2);
	            $v['num_18']=round($v['sum_18']/$v['num_18'],2);
	             
	             
	        }
	         
	         
	        $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
	            'status' => 'ok',
	            'data' => $data,
	            'cgid' => $cgid
	        ] ) );
	    } else {
	    
	    
	        $this->data ['hide_server_list'] = true;
	         
	        $this->data ['hide_end_time'] = true;
	        $this->data ['hide_channel_list'] = true;
	        $this->body = 'SystemFunction/badge';
	        $this->layout ();
	    }
	     
	     
	    
	     
	}
	
	/*
	 * 社团争霸赛数据提取并处理  zzl  20170927
	 */
	public function hegemony(){
	     
	
	    if (parent::isAjax ()) {
	        $date = $this->input->get ( 'date1' );
	        $date2 = $this->input->get ( 'date2' );	
	        $where ['date'] = $date;	       
	        $where ['serverids'] = $this->input->get ( 'server_id' );
	        $where ['typeids'] = $this->input->get ( 'type_id' );
	        $where ['pk_th'] = $this->input->get ( 'pk_th' );
	        $where ['group'] = $this->input->get ( 'group' );
	     
	        if ($date) {
	            $where ['begintime'] = substr(date ( 'Ymd', strtotime ( $date ) ), 2,7) ;
	        }
	        if ($date2) {
	            $where ['endtime'] = date ( 'Ymd', strtotime ( $date2 ));
	        }
	        $this->load->model ( 'SystemFunction_model' );
	
  
	
	     
	            
	            
	            $field = "game_server as serverid,count(*) as total,count(if(vip_level=0,true,null)) vip0,count(if(vip_level=1,true,null)) vip1,count(if(vip_level=2,true,null)) vip2,count(if(vip_level=3,true,null)) vip3,count(if(vip_level=4,true,null)) vip4,count(if(vip_level=5,true,null)) vip5,count(if(vip_level=6,true,null)) vip6,count(if(vip_level=7,true,null)) vip7,count(if(vip_level=8,true,null)) vip8,count(if(vip_level=9,true,null)) vip9,count(if(vip_level=10,true,null)) vip10,count(if(vip_level=11,true,null)) vip11,count(if(vip_level=12,true,null)) vip12";
	            
	            $order = '';
	            $group="game_server";
	            $data = $this->SystemFunction_model->hegemony ($where, $field  ,$group,$order,$limit );
	            
	            foreach ($data as &$v){
	                
	                $v['vip0']=$v['total']-$v['vip1']-$v['vip2']-$v['vip3']-$v['vip4']-$v['vip5']-$v['vip6']-$v['vip7']-$v['vip8']-$v['vip9']-$v['vip10']-$v['vip11']-$v['vip12'];
	                
	            }
	             
	             
	             
	            $field = "syn_id,count(*) as total,game_server as serverid";
	            $order = '';
	            $group="syn_id";
	            $data2 = $this->SystemFunction_model->hegemonyGroup ($where, $field  ,$group,$order,$limit );
	            
	            


	         
	         
	        $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
	            'status' => 'ok',
	            'data' => $data,
	            'data2' => $data2,
	            'cgid' => $cgid
	        ] ) );
	    } else {
	        
	        $this->data ['show_group'] = true;
	        $this->data ['hide_server_list'] = true;
	        $this->data ['show_syn_id'] = true;	        
	        $this->data ['hide_start_time'] = true;
	        $this->data ['hide_end_time'] = true;
	        $this->data ['hide_channel_list'] = true;
	        $this->body = 'SystemFunction/hegemony';
	        $this->layout ();
	    }
	     
	     
	}
	
	/*
	 *  远古宝藏统计
	 */
	public function ancient(){
	    
    if (parent::isAjax ()) {
	        $date = $this->input->get ( 'date1' );
	        $date2 = $this->input->get ( 'date2' );
	      

	        $where ['serverids'] = $this->input->get ( 'server_id' );
	        $where ['typeids'] = $this->input->get ( 'type_id' );
	    
	        	
	      
	        if ($date) {
	          $where ['begintime'] = date ( 'Ymd', strtotime ( $date . ' 00:00:00' ) );
	        }
	    
	        if ($date) {
	            $where ['endtime'] = date ( 'Ymd', strtotime ( $date2 . ' 00:00:00' ) );
	        }
	 
	        
	        
	        $names = array (
	            '10' => '获得物质0',
	            '11' =>'获得物质1-100',
	            '12' => '获得物质101-500',
	            '13' => '获得物质501-1000',
	            '14' =>'获得物质1001-2000',
	            '15' => '获得物质2001-3000',
	            '16' => '获得物质3001-5000',
	             '17' => '获得物质5001-10000',
	             '18' => '获得物质10000',
	        );
	    
	    
	        $this->load->model ( 'SystemFunction_model' );
	   
	        $field = 'id,communityid,serverid,type,operate_time,logdate, sum(if(param=0,true,null)) as sum0, sum(if(param>0 and param<=100,true,null)) as sum1,  sum(if(param>100 and param<=500,true,null)) as sum2,    sum(if(param>500 and param<=1000,true,null)) as sum3, sum(if(param>1000 and param<=2000,true,null)) as sum4, sum(if(param>2000 and param<=3000,true,null)) as sum5,sum(if(param>3001 and param<=5000,true,null)) as sum6,sum(if(param>5000 and param<=10000,true,null)) as sum7,sum(if(param>10000,true,null)) as sum8';
	        $group = 'logdate';
	        $order = '';
	   
	        $data = $this->SystemFunction_model->ancient ( $where, $field, $group, $order,$limit);
	        

	   
	        
	        
	        foreach ($data as $k=>$v){
	        
	            if(!isset($v['sum0'])){$v['sum0']=0;	            
	            }
	            if(!isset($v['sum1'])){$v['sum1']=0;}
	            if(!isset($v['sum2'])){$v['sum2']=0;}
	            if(!isset($v['sum3'])){$v['sum3']=0;}
	            if(!isset($v['sum4'])){$v['sum4']=0;}
	            if(!isset($v['sum5'])){$v['sum5']=0;}
	            if(!isset($v['sum6'])){$v['sum6']=0;}
	            if(!isset($v['sum7'])){$v['sum7']=0;}
	            if(!isset($v['sum8'])){$v['sum8']=0;}
	            $v['text']= "<a href='javascript:showdetail($v[logdate],1)'>社团详细</a>";
	            $data_new[$k]=$v;
	        
	        }
	
	   

	        foreach ($data as $k=>$v){
	            $v['sum0']=0;
	          $v['sum1']=0;
	         $v['sum2']=0;
	          $v['sum3']=0;
	          $v['sum4']=0;
	         $v['sum5']=0;
	         $v['sum6']=0;
	         $v['sum7']=0;
	         $v['sum8']=0;
	         $v['text']= "<a href='javascript:showdetail($v[logdate],$k)'>社团详细</a>";
	            $challenge_list[$k]=$v;
	          //  $challenge_list[$k]['logdate']=$v['logdate'];
	            
	        }
	        
	        
	      // print_r($challenge_list);
	        $field ="count(*) as cnt,communityid,serverid,communityname,logdate";
	        $group="communityid,serverid";
	        
	        

	       
	     $data_challenge = $this->SystemFunction_model->challenge ( $where, $field, $group, $order,$limit);
	     
	        
	        
	     $participation= $this->SystemFunction_model->participation ( $where, $field, $group, $order,$limit);

 	       foreach ($challenge_list as $k=>&$v){
 	         
	              foreach ($data_challenge as $k2=>$v2){	                     
	                  
	                  if($v['logdate']==$v2['logdate']){      
	         
	                      if($v2['cnt']==0){$v['sum0']=$v['sum0']+intval($v2['cnt']);}                       
	                      if($v2['cnt']>0 && $v2['cnt']<6){$v['sum1']+=intval($v2['cnt']);      
	                      }
	                    
	                    
	                      if($v2['cnt']>=6 && $v2['cnt']<=10){$v['sum2']+=intval($v2['cnt']);}
	                      if($v2['cnt']>=11 && $v2['cnt']<=20){$v['sum3']+=intval($v2['cnt']);}
	                      if($v2['cnt']>=21 && $v2['cnt']<=30){$v['sum4']=$v['sum4']+intval($v2['cnt']);}
	                      if($v2['cnt']>=31 && $v2['cnt']<=40){$v['sum5']+=$v2['cnt'];}
	                      if($v2['cnt']>=41 && $v2['cnt']<=50){$v['sum6']+=$v2['cnt'];}
	                      if($v2['cnt']>=51 && $v2['cnt']<=100){$v['sum7']+=$v2['cnt'];}
	                      if($v2['cnt']>100){$v['sum8']+=$v2['cnt'];}
	                      $v['text']= "<a href='javascript:showdetail($v[logdate],2)'>社团详细</a>";
	                  
	                  } else {
	               
	                  } 
	    
		       }
	    }

	       
	        $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
	            'status' => 'ok',
	            'data' => $data_new,	           
	            'challenge' => $challenge_list,
	            'cgid' => $cgid
	        ] ) );
	    } else {     	
	 
	
	        $this->data ['hide_channel_list'] = true;	       
	        $this->body = 'SystemFunction/ancient';
	        $this->layout ();
	    }
	    
	    
	    
	    
	}
	
	
	/*
	 * 	菁英挑战统计  20171023  zzl
	 */
	
	public function elite()
    {
        if (parent::isAjax()) {
            $date = $this->input->get('date1');
            $date2 = $this->input->get('date2');
            
            $where['serverids'] = $this->input->get('server_id');
            $where['typeids'] = $this->input->get('type_id');
            $where['user_level'] = $this->input->get('user_level');
            $where ['channels'] = $this->input->get ( 'channel_id' );
            
            if ($date) {
                $where['date'] = date('Ymd', strtotime($date));
            }
            
            $this->load->model('SystemFunction_model');
            
            $field = "COUNT(*) as cnt,accountid,param,serverid,act_id,vip_level,user_level";
            $order = '';
            
            $group = "accountid";
            
            $data = $this->SystemFunction_model->elite($table, $where, $field, $group, $order, $limit);
            
            
            $group = "vip_level";
            $field = "count(IF(act_id=141,true,null)) as purchase_treasure,count(IF(param=1 && act_id=142,true,null)) as team_1,count(IF(param=2 && act_id=142,true,null)) as team_2,count(IF(param=3  && act_id=142,true,null)) as team_3,accountid,param,serverid,act_id,vip_level,user_level";
            $data2 = $this->SystemFunction_model->elite_treasure($table, $where, $field, $group, $order, $limit);
            
            
            $data_new = array
            (
                0=>  array("vip_level"=>0,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),
                1=>  array("vip_level"=>1,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),
                2=>  array("vip_level"=>2,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),
                3=>  array("vip_level"=>3,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),
                4=>  array("vip_level"=>4,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),
                5=>  array("vip_level"=>5,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),
                6=>  array("vip_level"=>6,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),
                7=>  array("vip_level"=>7,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),
                8=>  array("vip_level"=>8,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),
                9=>  array("vip_level"=>9,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),                
                10=>  array("vip_level"=>10,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),
                11=>  array("vip_level"=>11,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),
                12=>  array("vip_level"=>12,"part_1"=>0,"part_2"=>0,"part_3"=>0,"part_4"=>0,"part_5"=>0,"team_1"=>0,"team_2"=>0,"team_3"=>0,"purchase_treasure"=>0,"click"=>0),               
             
            );
              
            $this->load->model('Data_analysis_model');
            $logininfo = $this->Data_analysis_model->viplogin($where);
            
            foreach ($data as $v) {
                foreach ($data_new as $k2 => &$v2) {
                    
        
                    
                    if ($v['vip_level'] == $v2['vip_level']) {
                        
                        if ($v['cnt'] == 1) {
                            $v2['part_1'] ++;
                        } elseif ($v['cnt'] == 2) {
                            $v2['part_2'] ++;
                        } elseif ($v['cnt'] == 3) {
                            $v2['part_3'] ++;
                        } elseif ($v['cnt'] == 4) {
                            $v2['part_4'] ++;
                        } elseif ($v['cnt'] == 5) {
                            $v2['part_5'] ++;
                        }
                        
                       
                    }
                    
                    
                    
                    foreach ($data2 as $v6){
                     
                        if ($v2['vip_level'] == $v6['vip_level']) {                       
                      
                            $v2['team_1']=$v6['team_1'];
                            $v2['team_2']=round($v6['team_2']/2,0);
                            $v2['team_3'] = round($v6['team_3']/3,0);
            
                        $v2['purchase_treasure']=$v6['purchase_treasure'];
                        }
                   }
                 }
             }
            foreach ($data_new as $k2 => &$v2) {
                
                foreach ($logininfo['day0'] as $v3) {
                    
                    if ($v2['vip_level'] == $v3['viplev']) {
                        $v2['c'] = $v3['c'];
                    }
        
                }
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'status' => 'ok',
                'data' => $data_new,
                'data2' => $data2,
                'cgid' => $cgid
            ]));
        } else {
            
            $this->data['show_user_level'] = true;
            $this->data['hide_end_time'] = true;
            $this->body = 'SystemFunction/elite';
            $this->layout();
        }
    }
	
	/*
	 * 周任务链后台统计   zzl 20171027 
	 */
    public function mission()
    {
        if (parent::isAjax()) {
            $date = $this->input->get('date1');
            $date2 = $this->input->get('date2');
            
            $where['serverids'] = $this->input->get('server_id');
            $where['typeids'] = $this->input->get('type_id');
            $where ['channels'] = $this->input->get ( 'channel_id' );
            
            if ($date) {
                $where['date'] = date('Ymd', strtotime($date));
            }
            
            $this->load->model('SystemFunction_model');
            
            $field = "sum(if(type=1 and item_id=3,item_num,null)) as consume,count(*) as achieve,vip_level";
            $order = '';
            
            $group = "vip_level";
            
            $data = $this->SystemFunction_model->mission($table, $where, $field, $group, $order, $limit);

            $this->load->model('Data_analysis_model');
            $logininfo = $this->Data_analysis_model->viplogin($where);
            
            foreach ($data as &$v) {
                
                  foreach ($data['param'] as $v2) {
                    
                    if ($v['vip_level'] == $v2['vip_level']) {
                        
                        $v['p1'] = $v2['p1'];
                        $v['p2'] = $v2['p2'];
                        $v['p3'] = $v2['p3'];
                        $v['p4'] = $v2['p4'];
                    }
                    }
				 foreach ($data['span'] as $v3) {
                    
                    if ($v['vip_level'] == $v3['vip_level']) {
                        
                        $v['v'] = $v3['v'];
                    }
                }
				
				foreach ($data['vel'] as $v4) {
                    
                    if ($v['vip_level'] == $v4['v']) {
                        
                        $v['v1'] = $v4['p1'];
                        $v['v2'] = $v4['p2'];
                        $v['v3'] = $v4['p3'];
                        $v['v4'] = $v4['p4'];
                        $v['v5'] = $v4['p5'];
                        $v['v6'] = $v4['p6'];
                        $v['v7'] = $v4['p7'];
                        $v['v8'] = $v4['p8'];
                        $v['v9'] = $v4['p9'];
                    }
                }
					
             
                foreach ($logininfo['day0'] as $v3) {
                    if ($v['vip_level'] == $v3['viplev']) {
                        $v['c'] = $v3['c'];
                    }
                }
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'status' => 'ok',
                'data' => $data
            ]
            ));
        } else {
            
            $this->data['hide_end_time'] = true;
            $this->body = 'SystemFunction/mission';
            $this->layout();
        }
    }

	/*
	 * 一键狩猎统计   banjin 2017-12-8
	 */
	public function hunting()
	{
	    

	    if (parent::isAjax()) {
	        $date = $this->input->get('date1');
	    
	        $where['serverids'] = $this->input->get('server_id');
	        $where['typeids'] = $this->input->get('type_id');
	        $where['channels'] = $this->input->get ( 'channel_id' );
	    
	        if ($date) {
	            $where['date'] = date('Ymd', strtotime($date));
	        }
	    
	        $this->load->model('SystemFunction_model');
	    
	        $field = "sum(if(type=1 and item_id=3,item_num,null)) as consume,count(*) as achieve,vip_level";
	    
	        $group = "vip_level";
	    
	        $data = $this->SystemFunction_model->hunting($where, $field, $group);
	        $this->load->model('Data_analysis_model');
	        $logininfo = $this->Data_analysis_model->viplogin($where);
	        $day = $logininfo['day0'];
	        foreach ($day as &$v) {
	            $v['p1'] = 0;
	            $v['consume'] = 0;
	            $v['achieve'] = 0;
	            foreach ($data['param'] as $v2) {
	    
	                if ($v['viplev'] == $v2['vip_level']) {
	    
	                    $v['v'] = $v2['v'];
	                }
	            }
	    
	            foreach ($data['span'] as $v3) {
	    
	                if ($v['viplev'] == $v3['vip_level']) {
	    
	                    $v['p1'] = $v3['s'];
	                }
	            }
	    
	            foreach ($data as $v3) {
	                if ($v['viplev'] == $v3['vip_level']) {
	                    $v['consume'] = $v3['consume'];
	                    $v['achieve'] = $v3['achieve'];
	                }
	            }
	        }
	        $this->output->set_content_type('application/json')->set_output(json_encode([
	            'status' => 'ok',
	            'data' => $day
	        ]
	            ));
	    } else {
	    
	        $this->data['hide_end_time'] = true;
	        $this->body = 'SystemFunction/hunting';
	        $this->layout();
	    }
	    
	}
    /*
     * 精灵塔简单模式 zzl 20171027
     */
    public function  fairyTower(){

        if (parent::isAjax ()) {
            $date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Ymd' );
            $where ['serverids'] = $this->input->get ( 'server_id' );
            $where ['viplev_min'] = $this->input->get ( 'viplev_min' )?$this->input->get ( 'viplev_min' ):0;
            $where ['viplev_max'] = $this->input->get ( 'viplev_max' )?$this->input->get ( 'viplev_max' ):12;
            $where ['begintime'] = strtotime ( $date . ' 00:00:00' );
            	
            $btype = $this->input->get ( 'btype' );
            $this->load->model ( 'SystemFunction_model' );
            $newdata = array ();
           
            
            if ($date) {
                $where['date'] = date('Ymd', strtotime($date));
            }

                $data_new = array
                (
                    0=>  array("name"=>"参与过精灵塔简单模式的人数（参数）"),
                    1=>  array("name"=>"  通关精灵塔简单模式1层的人数（累计）"),
                    2=>  array("name"=>" 通关精灵塔简单模式2层的人数（累计）"),
                    
                    3=>  array("name"=>"  通关精灵塔简单模式3层的人数（累计）"),
                  /*   4=>  array("name"=>" 通关精灵塔简单模式1层获得青铜宝箱的人数（累计）"),
                    5=>  array("name"=>" 通关精灵塔简单模式1层获得白银宝箱的人数（累计）"),
                    
                    6=>  array("name"=>" 通关精灵塔简单模式1层获得黄金宝箱的人数（累计）"),
                    7=>  array("name"=>" 通关精灵塔简单模式2层获得青铜宝箱的人数（累计）"),
                    8=>  array("name"=>" 通关精灵塔简单模式2层获得白银宝箱的人数（累计）"),
                    
                    9=>  array("name"=>" 通关精灵塔简单模式2层获得黄金宝箱的人数（累计）"),
                    10=>  array("name"=>" 通关精灵塔简单模式3层获得青铜宝箱的人数（累计）"),
                    11=>  array("name"=>" 通关精灵塔简单模式3层获得白银宝箱的人数（累计）"),
                    12=>  array("name"=>"通关精灵塔简单模式3层获得黄金宝箱的人数（累计）"), */
                     
                );
              
            $field = "user_level";
            $order = '';
            
            $group = "vip_level";            
            $data = $this->SystemFunction_model->fairyTower($table, $where, $field, $group, $order, $limit);
            
            $data143=array('c0'=>0,'c1'=>0,'c2'=>0,'c3'=>0,'c4'=>0,'c5'=>0,'c6'=>0,'c7'=>0,'c8'=>0,'c9'=>0,'c10'=>0,);
            foreach ($data['143'] as &$v){
                if($v['level']==0){
                    $data143['c0']=$v['cnt'];
                } 
                
                elseif ($v['level']==1){
                    $data143['c1']++;
                }
                elseif($v['level']==2){
                    $data143['c2']++;
                }
                elseif($v['level']==3){
                    $data143['c3']++;
                }
                elseif($v['level']==4){
                    $data143['c4']++;
                }
                elseif($v['level']==5){
                    $data143['c5']++;
                }
                elseif($v['level']==6){
                    $data143['c6']++;
                }
                elseif($v['level']==7){
                    $data143['c7']++;
                }
                elseif($v['level']==8){
                    $data143['c8']++;
                }
                elseif($v['level']==9){
                    $data143['c9']++;
                }
                
                elseif($v['level']==10){
                    $data143['c10']++;
                }
                elseif($v['level']==11){
                    $data143['c11']++;
                }
                
                elseif($v['level']==12){
                    $data143['c12']++;
                }                
                
            }
      $data_144[1]=array('name'=>' 通关精灵塔简单模式1层的人数（累计）','c0'=>0,'c1'=>0,'c2'=>0,'c3'=>0,'c4'=>0,'c5'=>0,'c6'=>0,'c7'=>0,'c8'=>0,'c9'=>0,'c10'=>0,);
      $data_144[2]=array('name'=>' 通关精灵塔简单模式2层的人数（累计）','c0'=>0,'c1'=>0,'c2'=>0,'c3'=>0,'c4'=>0,'c5'=>0,'c6'=>0,'c7'=>0,'c8'=>0,'c9'=>0,'c10'=>0,);
      $data_144[3]=array('name'=>' 通关精灵塔简单模式2层的人数（累计）','c0'=>0,'c1'=>0,'c2'=>0,'c3'=>0,'c4'=>0,'c5'=>0,'c6'=>0,'c7'=>0,'c8'=>0,'c9'=>0,'c10'=>0,);


      
      foreach ($data['144'] as $k=>&$v){
      
              $data_144[$v[param]]['name']="通关精灵塔简单模式".$v['param']."层的人数（累计）";
          foreach ($data['144_2'] as  $k2=>$v2){      
              if(($v['param']==$v2['param'])){                   
                 
      
                  if($v2['user_level']==0){
                        $data_144[$v[param]]['c0']=$v2['cnt'];
                       
                  }elseif($v2['user_level']==1){
                        $data_144[$v[param]]['c1']=$v2['cnt'];
                  }elseif($v2['user_level']==2){
                        $data_144[$v[param]]['c2']=$v2['cnt'];
                  }elseif($v2['user_level']==3){
                        $data_144[$v[param]]['c3']=$v2['cnt'];
                  }elseif($v2['user_level']==4){
                        $data_144[$v[param]]['c4']=$v2['cnt'];
                  }elseif($v2['user_level']==5){
                        $data_144[$v[param]]['c5']=$v2['cnt'];
                  }elseif($v2['user_level']==6){
                        $data_144[$v[param]]['c6']=$v2['cnt'];
                  }elseif($v2['user_level']==7){
                        $data_144[$v[param]]['c7']=$v2['cnt'];
                  }elseif($v2['user_level']==8){
                        $data_144[$v[param]]['c8']=$v2['cnt'];
                  }elseif($v2['user_level']==9){
                        $data_144[$v[param]]['c9']=$v2['cnt'];
                  }elseif($v2['user_level']==10){
                        $data_144[$v[param]]['c10']=$v2['cnt'];
                  }
              }
      
          }
      }
           $data_new[0]= array_merge( $data_new[0], $data143);
           $data_new[1]= array_merge( $data_new[1], $data_144[1]);
           $data_new[2]= array_merge( $data_new[2], $data_144[2]);
           $data_new[3]= array_merge( $data_new[3],$data_144[3]);
           
           
          

           $name145=array(1=>"青铜",2=>"白银",3=>"黄金");
    
           
           //通关精灵塔简单模式1层获得黄金宝箱的人数（累计）  ，1青铜，2白银，3黄金
         if($btype==1) {
           foreach ($data['145'] as $k=>&$v){
           
               $v['name']="通关精灵塔简单模式".$v['param']."层获得".$name145[$v[subpa]];
           
               foreach ($data['145_2'] as  $k2=>$v2){
           
                   if(($v['param']==$v2['param']) &&  ($v['subpa']==$v2['subpa']) ){
                        
                      
           
           
                       if($v2['user_level']==0){
                           $v['c0']=$v2['cnt'];
                            
                       }
                       elseif($v2['user_level']==1){
                           $v['c1']=$v2['cnt'];
                       }
                       
                       elseif($v2['user_level']==2){
                           $v['c2']=$v2['cnt'];
                       }
                       elseif($v2['user_level']==3){
                           $v['c3']=$v2['cnt'];
                       }
                       elseif($v2['user_level']==4){
                           $v['c4']=$v2['cnt'];
                       }
                       elseif($v2['user_level']==5){
                           $v['c5']=$v2['cnt'];
                       }
                       elseif($v2['user_level']==6){
                           $v['c6']=$v2['cnt'];
                       }
                       elseif($v2['user_level']==7){
                           $v['c7']=$v2['cnt'];
                       }
                       elseif($v2['user_level']==8){
                           $v['c8']=$v2['cnt'];
                       }
                       elseif($v2['user_level']==9){
                           $v['c9']=$v2['cnt'];
                       }
                       elseif($v2['user_level']==10){
                           $v['c10']=$v2['cnt'];
                       }
                     
                   }
           
               }
           }
           
           $data_new=    $data['145'];
           
         }
       


            $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
                'status' => 'ok',
                'data' => $data_new
            ] ) );
        } else {
            $this->data ['hide_end_time'] = true;
            $this->data ['viplev_filter'] = true;
           
            $this->body = 'SystemFunction/fairyTower';
            $this->layout ();
        }
        
        
    }
    
    /*
     * 洛奇亚的爆诞活动  zzl 20171031
     */
    public function Lugia(){
        


        if (parent::isAjax ()) {
            $date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Ymd' );
            $where ['serverids'] = $this->input->get ( 'server_id' );
            $where ['viplev_min'] = $this->input->get ( 'viplev_min' )?$this->input->get ( 'viplev_min' ):0;
            $where ['viplev_max'] = $this->input->get ( 'viplev_max' )?$this->input->get ( 'viplev_max' ):12;
            $where ['begintime'] = strtotime ( $date . ' 00:00:00' );
             
            $btype = $this->input->get ( 'btype' );
            $this->load->model ( 'SystemFunction_model' );
            $newdata = array ();
          
            if ($date) {
                $where['date'] = date('Ymd', strtotime($date));
            }
        
            $data_new = array
            (        
                1=>  array("name"=>"达到参与条件等级40级的活跃玩家人数"),
                2=>  array("name"=>"每天参加过活动玩法的人数"),
        
                3=>  array("name"=>"  每天购买过行动点的人数"),
                4=>  array("name"=>"    每天行动点被购买的总次数"),
                5=>  array("name"=>"  每天购买行动点花费的钻石"),
        
                6=>  array("name"=>"  每天使用活动无限副本扫荡花费的钻石总数"),
                7=>  array("name"=>"    每天使用活动转盘消耗的钻石总数"),
  
                10=>  array("name"=>"  商品兑换次数"),
     
                 
            );
        
            $field = "";
            $order = '';
        
            $group = "";
            $data = $this->SystemFunction_model->Lugia($table, $where, $field, $group, $order, $limit);
 
            
            $data_2=array('v0'=>0,'v1'=>0,'v2'=>0,'v3'=>0,'v4'=>0,'v5'=>0,'v6'=>0,'v7'=>0,'v8'=>0,'v9'=>0,'v10'=>0,'v11'=>0,'v12'=>0,'v13'=>0,'v14'=>0,'v15'=>0);
            foreach ($data['2'] as $v){
                $data_2['total']++;
                if($v['viplev']==0){
                    $data_2['v0']++;
                }elseif($v['viplev']==1){
                    $data_2['v1']++;
                }elseif($v['viplev']==2){
                    $data_2['v2']++;
                }elseif($v['viplev']==3){
                    $data_2['v3']++;
                }elseif($v['viplev']==4){
                    $data_2['v4']++;
                }elseif($v['viplev']==5){
                    $data_2['v5']++;
                }elseif($v['viplev']==6){
                    $data_2['v6']++;
                }elseif($v['viplev']==7){
                    $data_2['v7']++;
                }elseif($v['viplev']==8){
                    $data_2['v8']++;
                }elseif($v['viplev']==9){
                    $data_2['v9']++;
                }elseif($v['viplev']==10){
                    $data_2['v10']++;
                }elseif($v['viplev']==11){
                    $data_2['v11']++;
                }elseif($v['viplev']==12){
                    $data_2['v12']++;
                }
               elseif($v['viplev']==13){
                	$data_2['v13']++;
                }
                elseif($v['viplev']==14){
                	$data_2['v14']++;
                }
                elseif($v['viplev']==15){
                	$data_2['v15']++;
                }
            }
            
            $data_3=array('v0'=>0,'v1'=>0,'v2'=>0,'v3'=>0,'v4'=>0,'v5'=>0,'v6'=>0,'v7'=>0,'v8'=>0,'v9'=>0,'v10'=>0,'v11'=>0,'v12'=>0,'v13'=>0,'v14'=>0,'v15'=>0);
            foreach ($data['135_1'] as $v){
                $data_3['total']++;
                if($v['vip_level']==0){
                    $data_3['v0']++;
                }elseif($v['vip_level']==1){
                    $data_3['v1']++;
                }elseif($v['vip_level']==2){
                    $data_3['v2']++;
                }elseif($v['vip_level']==3){
                    $data_3['v3']++;
                }elseif($v['vip_level']==4){
                    $data_3['v4']++;
                }elseif($v['vip_level']==5){
                    $data_3['v5']++;
                }elseif($v['vip_level']==6){
                    $data_3['v6']++;
                }elseif($v['vip_level']==7){
                    $data_3['v7']++;
                }elseif($v['vip_level']==8){
                    $data_3['v8']++;
                }elseif($v['vip_level']==9){
                    $data_3['v9']++;
                }elseif($v['vip_level']==10){
                    $data_3['v10']++;
                }elseif($v['vip_level']==11){
                    $data_3['v11']++;
                }elseif($v['vip_level']==12){
                    $data_3['v12']++;
                }
                elseif($v['vip_level']==13){
                	$data_3['v13']++;
                }
                elseif($v['vip_level']==14){
                	$data_3['v14']++;
                }
                elseif($v['vip_level']==15){
                	$data_3['v15']++;
                }
            }
            

            $data_138_1=array('v0'=>0,'v1'=>0,'v2'=>0,'v3'=>0,'v4'=>0,'v5'=>0,'v6'=>0,'v7'=>0,'v8'=>0,'v9'=>0,'v10'=>0,'v11'=>0,'v12'=>0,'v13'=>0,'v14'=>0,'v15'=>0);
            foreach ($data['138_1'] as $v){
                $data_138_1['total']++;
                if($v['vip_level']==0){
                    $data_138_1['v0']++;
                }elseif($v['vip_level']==1){
                    $data_138_1['v1']++;
                }elseif($v['vip_level']==2){
                    $data_138_1['v2']++;
                }elseif($v['vip_level']==3){
                    $data_138_1['v3']++;
                }elseif($v['vip_level']==4){
                    $data_138_1['v4']++;
                }elseif($v['vip_level']==5){
                    $data_138_1['v5']++;
                }elseif($v['vip_level']==6){
                    $data_138_1['v6']++;
                }elseif($v['vip_level']==7){
                    $data_138_1['v7']++;
                }elseif($v['vip_level']==8){
                    $data_138_1['v8']++;
                }elseif($v['vip_level']==9){
                    $data_138_1['v9']++;
                }elseif($v['vip_level']==10){
                    $data_138_1['v10']++;
                }elseif($v['vip_level']==11){
                    $data_138_1['v11']++;
                }elseif($v['vip_level']==12){
                    $data_138_1['v12']++;
                }
                elseif($v['vip_level']==13){
                	$data_138_1['v13']++;
                }
                elseif($v['vip_level']==14){
                	$data_138_1['v14']++;
                }
                elseif($v['vip_level']==15){
                	$data_138_1['v15']++;
                }
            }
        
            
            $data_138_2=array('v0'=>0,'v1'=>0,'v2'=>0,'v3'=>0,'v4'=>0,'v5'=>0,'v6'=>0,'v7'=>0,'v8'=>0,'v9'=>0,'v10'=>0,'v11'=>0,'v12'=>0,'v13'=>0,'v14'=>0,'v15'=>0);
            foreach ($data['138_2'] as $v){
                $data_138_2['total']++;
                if($v['vip_level']==0){
                    $data_138_2['v0']++;
                }elseif($v['vip_level']==1){
                    $data_138_2['v1']++;
                }elseif($v['vip_level']==2){
                    $data_138_2['v2']++;
                }elseif($v['vip_level']==3){
                    $data_138_2['v3']++;
                }elseif($v['vip_level']==4){
                    $data_138_2['v4']++;
                }elseif($v['vip_level']==5){
                    $data_138_2['v5']++;
                }elseif($v['vip_level']==6){
                    $data_138_2['v6']++;
                }elseif($v['vip_level']==7){
                    $data_138_2['v7']++;
                }elseif($v['vip_level']==8){
                    $data_138_2['v8']++;
                }elseif($v['vip_level']==9){
                    $data_138_2['v9']++;
                }elseif($v['vip_level']==10){
                    $data_138_2['v10']++;
                }elseif($v['vip_level']==11){
                    $data_138_2['v11']++;
                }elseif($v['vip_level']==12){
                    $data_138_2['v12']++;
                }
                elseif($v['vip_level']==13){
                	$data_138_2['v13']++;
                }
                elseif($v['vip_level']==14){
                	$data_138_2['v14']++;
                }
                elseif($v['vip_level']==15){
                	$data_138_2['v15']++;
                }
            }
            
       
            
            $data_138_3=array('v0'=>0,'v1'=>0,'v2'=>0,'v3'=>0,'v4'=>0,'v5'=>0,'v6'=>0,'v7'=>0,'v8'=>0,'v9'=>0,'v10'=>0,'v11'=>0,'v12'=>0,'v13'=>0,'v14'=>0,'v15'=>0);
            foreach ($data['138_3'] as $v){
                $data_138_3['total']+=$v['sum_num'];
                if($v['vip_level']==0){
                    $data_138_3['v0']+=$v['sum_num'];
                }elseif($v['vip_level']==1){
                    $data_138_3['v1']+=$v['sum_num'];
                }elseif($v['vip_level']==2){
                    $data_138_3['v2']+=$v['sum_num'];
                }elseif($v['vip_level']==3){
                    $data_138_3['v3']+=$v['sum_num'];
                }elseif($v['vip_level']==4){
                    $data_138_3['v4']+=$v['sum_num'];
                }elseif($v['vip_level']==5){
                    $data_138_3['v5']+=$v['sum_num'];
                }elseif($v['vip_level']==6){
                    $data_138_3['v6']+=$v['sum_num'];
                }elseif($v['vip_level']==7){
                    $data_138_3['v7']+=$v['sum_num'];
                }elseif($v['vip_level']==8){
                    $data_138_3['v8']+=$v['sum_num'];
                }elseif($v['vip_level']==9){
                    $data_138_3['v9']+=$v['sum_num'];
                }elseif($v['vip_level']==10){
                    $data_138_3['v10']+=$v['sum_num'];
                }elseif($v['vip_level']==11){
                    $data_138_3['v11']+=$v['sum_num'];
                }elseif($v['vip_level']==12){
                    $data_138_3['v12']+=$v['sum_num'];
                }
                elseif($v['vip_level']==13){
                	$data_138_3['v13']+=$v['sum_num'];
                }
                elseif($v['vip_level']==14){
                	$data_138_3['v14']+=$v['sum_num'];
                }
                elseif($v['vip_level']==15){
                	$data_138_3['v15']+=$v['sum_num'];
                }
            }
          
            
            $data_139=array('v0'=>0,'v1'=>0,'v2'=>0,'v3'=>0,'v4'=>0,'v5'=>0,'v6'=>0,'v7'=>0,'v8'=>0,'v9'=>0,'v10'=>0,'v11'=>0,'v12'=>0,'v12'=>0,'v13'=>0,'v14'=>0,'v15'=>0);
            foreach ($data['139'] as $v){
                $data_139['total']+=$v['sum_num'];
                if($v['vip_level']==0){
                    $data_139['v0']+=$v['sum_num'];
                }elseif($v['vip_level']==1){
                    $data_139['v1']+=$v['sum_num'];
                }elseif($v['vip_level']==2){
                    $data_139['v2']+=$v['sum_num'];
                }elseif($v['vip_level']==3){
                    $data_139['v3']+=$v['sum_num'];
                }elseif($v['vip_level']==4){
                    $data_139['v4']+=$v['sum_num'];
                }elseif($v['vip_level']==5){
                    $data_139['v5']+=$v['sum_num'];
                }elseif($v['vip_level']==6){
                    $data_139['v6']+=$v['sum_num'];
                }elseif($v['vip_level']==7){
                    $data_139['v7']+=$v['sum_num'];
                }elseif($v['vip_level']==8){
                    $data_139['v8']+=$v['sum_num'];
                }elseif($v['vip_level']==9){
                    $data_139['v9']+=$v['sum_num'];
                }elseif($v['vip_level']==10){
                    $data_139['v10']+=$v['sum_num'];
                }elseif($v['vip_level']==11){
                    $data_139['v11']+=$v['sum_num'];
                }elseif($v['vip_level']==12){
                    $data_139['v12']+=$v['sum_num'];
                }
                elseif($v['vip_level']==13){
                	$data_139['v13']+=$v['sum_num'];
                }
                elseif($v['vip_level']==14){
                	$data_139['v14']+=$v['sum_num'];
                }
                elseif($v['vip_level']==15){
                	$data_139['v15']+=$v['sum_num'];
                }
            }
           
            

            $data_137=array('v0'=>0,'v1'=>0,'v2'=>0,'v3'=>0,'v4'=>0,'v5'=>0,'v6'=>0,'v7'=>0,'v8'=>0,'v9'=>0,'v10'=>0,'v11'=>0,'v12'=>0,'v12'=>0,'v13'=>0,'v14'=>0,'v15'=>0);
            foreach ($data['137'] as $v){
                $data_137['total']+=$v['sum_num'];
                if($v['vip_level']==0){
                    $data_137['v0']+=$v['sum_num'];
                }elseif($v['vip_level']==1){
                    $data_137['v1']+=$v['sum_num'];
                }elseif($v['vip_level']==2){
                    $data_137['v2']+=$v['sum_num'];
                }elseif($v['vip_level']==3){
                    $data_137['v3']+=$v['sum_num'];
                }elseif($v['vip_level']==4){
                    $data_137['v4']+=$v['sum_num'];
                }elseif($v['vip_level']==5){
                    $data_137['v5']+=$v['sum_num'];
                }elseif($v['vip_level']==6){
                    $data_137['v6']+=$v['sum_num'];
                }elseif($v['vip_level']==7){
                    $data_137['v7']+=$v['sum_num'];
                }elseif($v['vip_level']==8){
                    $data_137['v8']+=$v['sum_num'];
                }elseif($v['vip_level']==9){
                    $data_137['v9']+=$v['sum_num'];
                }elseif($v['vip_level']==10){
                    $data_137['v10']+=$v['sum_num'];
                }elseif($v['vip_level']==11){
                    $data_137['v11']+=$v['sum_num'];
                }elseif($v['vip_level']==12){
                    $data_137['v12']+=$v['sum_num'];
                }elseif($v['vip_level']==13){
                    $data_137['v13']+=$v['sum_num'];
                }elseif($v['vip_level']==14){
                    $data_137['v14']+=$v['sum_num'];
                }elseif($v['vip_level']==15){
                    $data_137['v15']+=$v['sum_num'];
                }
            }
             

            
            $data_136=array('v0'=>0,'v1'=>0,'v2'=>0,'v3'=>0,'v4'=>0,'v5'=>0,'v6'=>0,'v7'=>0,'v8'=>0,'v9'=>0,'v10'=>0,'v11'=>0,'v12'=>0,);
            foreach ($data['136'] as $v){
                $data_136['total']++;
                if($v['vip_level']==0){
                    $data_136['v0']++;
                }elseif($v['vip_level']==1){
                    $data_136['v1']++;
                }elseif($v['vip_level']==2){
                    $data_136['v2']++;
                }elseif($v['vip_level']==3){
                    $data_136['v3']++;
                }elseif($v['vip_level']==4){
                    $data_136['v4']++;
                }elseif($v['vip_level']==5){
                    $data_136['v5']++;
                }elseif($v['vip_level']==6){
                    $data_136['v6']++;
                }elseif($v['vip_level']==7){
                    $data_136['v7']++;
                }elseif($v['vip_level']==8){
                    $data_136['v8']++;
                }elseif($v['vip_level']==9){
                    $data_136['v9']++;
                }elseif($v['vip_level']==10){
                    $data_136['v10']++;
                }elseif($v['vip_level']==11){
                    $data_136['v11']++;
                }elseif($v['vip_level']==12){
                    $data_136['v12']++;
                }
                elseif($v['vip_level']==13){
                	$data_136['v13']++;
                }
                elseif($v['vip_level']==14){
                	$data_136['v14']++;
                }
                elseif($v['vip_level']==15){
                	$data_136['v15']++;
                }
                $data_136['text']="<a href='javascript:showdetail({$v['act_id']})'>详细</a>";
            }
           
         
            $data_new[1]= array_merge( $data_new[1], $data_2);
             $data_new[2]= array_merge( $data_new[2], $data_3);
            
            $data_new[3]= array_merge( $data_new[3], $data_138_1);
           
            $data_new[4]= array_merge( $data_new[4], $data_138_2);
            $data_new[5]= array_merge( $data_new[5], $data_138_3);
            
            $data_new[6]= array_merge( $data_new[6], $data_139);
          
            $data_new[7]= array_merge( $data_new[7], $data_137);
           $data_new[10]= array_merge( $data_new[10], $data_136);
          if($btype==1){
                foreach ($data['135_139_one'] as $k=>&$v){
              
                  $v['name']="通关各个关卡的人数".$v['param'];
              
                  foreach ($data['135_139_two'] as  $k2=>$v2){
              
                      if(($v['param']==$v2['param']) ){
                           
                          $v['total']+=$v2['cnt'];              
              
                          if($v2['vip_level']==0){
                              $v['v0']=$v2['cnt'];
                               
                          }elseif($v2['vip_level']==1){
                              $v['v1']=$v2['cnt'];
                          }elseif($v2['vip_level']==2){
                              $v['v2']=$v2['cnt'];
                          }elseif($v2['vip_level']==3){
                              $v['v3']=$v2['cnt'];
                          }elseif($v2['vip_level']==4){
                              $v['v4']=$v2['cnt'];
                          }elseif($v2['vip_level']==5){
                              $v['v5']=$v2['cnt'];
                          }elseif($v2['vip_level']==6){
                              $v['v6']=$v2['cnt'];
                          }elseif($v2['vip_level']==7){
                              $v['v7']=$v2['cnt'];
                          }elseif($v2['vip_level']==8){
                              $v['v8']=$v2['cnt'];
                          }elseif($v2['vip_level']==9){
                              $v['v9']=$v2['cnt'];
                          }elseif($v2['vip_level']==10){
                              $v['v10']=$v2['cnt'];
                          }elseif($v2['vip_level']==11){
                              $v['v11']=$v2['cnt'];
                          }elseif($v2['vip_level']==12){
                              $v['v12']=$v2['cnt'];
                          }
                          elseif($v2['vip_level']==13){
                          	$v['v13']=$v2['cnt'];
                          }
                          elseif($v2['vip_level']==14){
                          	$v['v14']=$v2['cnt'];
                          }
                          elseif($v2['vip_level']==15){
                          	$v['v15']=$v2['cnt'];
                          }
                      }
              
                  }
              }
        
              
              $data_new=    $data['135_139_one'];
              
          }
          
          if($btype==2){


              
              foreach ($data['135_139_2'] as $k=>&$v){
                
                   $v['name']="各个关卡各种通关".$v['param1']."星级的玩家数".$v['param'];
                  
                  foreach ($data['135_139_2_2'] as  $k2=>$v2){
                      
                 if(($v['param']==$v2['param']) &&  ($v['param1']==$v2['param1']) ){
                     
                     $v['total']+=$v2['cnt'];
                  
                  
                  if($v2['vip_level']==0){                       
                      $v['v0']=$v2['cnt'];
                       
                  }elseif($v2['vip_level']==1){
                      $v['v1']=$v2['cnt'];
                  }elseif($v2['vip_level']==2){
                      $v['v2']=$v2['cnt'];
                  }elseif($v2['vip_level']==3){
                      $v['v3']=$v2['cnt'];
                  }elseif($v2['vip_level']==4){
                      $v['v4']=$v2['cnt'];
                  }elseif($v2['vip_level']==5){
                      $v['v5']=$v2['cnt'];
                  }elseif($v2['vip_level']==6){
                      $v['v6']=$v2['cnt'];
                  }elseif($v2['vip_level']==7){
                      $v['v7']=$v2['cnt'];
                  }elseif($v2['vip_level']==8){
                      $v['v8']=$v2['cnt'];
                  }elseif($v2['vip_level']==9){
                      $v['v9']=$v2['cnt'];
                  }elseif($v2['vip_level']==10){
                      $v['v10']=$v2['cnt'];
                  }elseif($v2['vip_level']==11){
                      $v['v11']=$v2['cnt'];
                  }elseif($v2['vip_level']==12){
                      $v['v12']=$v2['cnt'];
                  }
                  elseif($v2['vip_level']==13){
                  	$v['v13']=$v2['cnt'];
                  }
                  elseif($v2['vip_level']==14){
                  	$v['v14']=$v2['cnt'];
                  }elseif($v2['vip_level']==15){
                      $v['v15']=$v2['cnt'];
                  }
                                                                  }
              
                    }
               }
              
          $data_new=    $data['135_139_2'];
          
              
          }
          

          
          
            $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
                'status' => 'ok',
                'data' => $data_new
            ] ) );
        } else {
            $this->data ['hide_end_time'] = true;
            $this->data ['viplev_filter'] = true;
             
            $this->body = 'SystemFunction/Lugia';
            $this->layout ();
        }
        
        
        
        
        
    }
    
    
    public  function champion(){




        if (parent::isAjax ()) {
            $date = $this->input->get ( 'date1' );
            $date2 = $this->input->get ( 'date2' );
             
            $where ['date'] = date ( 'Ymd', strtotime ( $date ));

            $where ['serverids'] = $this->input->get ( 'server_id' );
            $where ['typeids'] = $this->input->get ( 'type_id' );
     
      
            $this->load->model ( 'SystemFunction_model' );
             
            $field = "count(DISTINCT accountid) as total,serverid,param1,param,count(if(param=16,true,null)) as total_16,count(if(param=8,true,null)) as total_8,count(if(param=4,true,null)) as total_4,count(if(param=1,true,null)) as total_1";
            $group = 'serverid';
            $order = '';
            $data = $this->SystemFunction_model->champion ($table,$where, $field  ,$group,$order,$limit );
            
     
         
            foreach ($data as $k=>$v){
                
                foreach ($data['more'] as $v2){              
                
                if($v['serverid']==$v2['serverid']){                    
               /*      $v['total_16']+= $v2['total_16'];
                    $v['total_8']+= $v2['total_8'];
                    $v['total_4']+= $v2['total_4'];
                    $v['total_1']+= $v2['total_1']; */       
                    if($v2['param1']==16){
                        $v['total_16']= $v2['total'];
                    }
                    if($v2['param1']==8){
                        $v['total_8']= $v2['total'];
                    }
                    if($v2['param1']==4){
                        $v['total_4']= $v2['total'];
                    }
                    if($v2['param1']==1){
                        $v['total_1']= $v2['total'];
                    }
                }
                $v['text']="<a href='javascript:detail({$v['serverid']},0)'>十六强分布</a> ";
                
                }
                
                
                $data_new[$k]=$v;
                }
             
   
        
        
            $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
                'status' => 'ok',
                'data' => $data_new,
                'cgid' => $cgid
            ] ) );
        } else {
             
             
          //  $this->data ['hide_server_list'] = true;
        
            $this->data ['hide_end_time'] = true;
       //     $this->data ['hide_channel_list'] = true;
            $this->body = 'SystemFunction/champion';
            $this->layout ();
        }
        
        
         
        
        
        
        
        
    }

}
