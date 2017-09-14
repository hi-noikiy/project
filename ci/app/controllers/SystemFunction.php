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
			$group = 'floor(param/5)';
			$btype = $this->input->get ( 'btype' );
			$this->load->model ( 'SystemFunction_model' );
			$newdata = array ();
			if ($btype == 0) { // 各等级段参与精灵塔的人数
				$names = array (
						'66' => '各等级段参与精灵塔的人数' 
				);
				$where ['typeids'] = [ 66 ];
				//$field = 'act_id,floor(user_level/10) as level,count(distinct accountid) cid';
				$field = 'act_id,floor(user_level/10) as level,count(distinct accountid) cid';
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );
				if(!$data){
					echo json_encode(array('code'=>'fail','info'=>'未查到数据'));die;
				}
			
				foreach ( $data as $v ) {
					if (! isset ( $newdata [$v ['act_id']] )) {
						for($i = 0; $i < 10; $i ++) {
							$newdata [$v ['act_id']] ['level_' . $i] = 0;
							$newdata [$v ['act_id']] ['act_name'] = $names [$v ['act_id']];
						}
					}
					$newdata [$v ['act_id']] ['level_' . $v ['level']] = $v ['cid'];
				}	
				
			} elseif ($btype == 1) { // 通关精灵塔各层的人数
			   
				$where ['typeids'] = [ 67 ];
				$field = 'act_id,floor(param/5) as param,floor(user_level/10) as level,count(distinct accountid) cid';
				$group = 'accountid';
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );
				
	
				
				foreach ( $data as $v ) {				
				            $newdata [ $v ['param']] ['act_name'] = '通关精灵塔'.$v ['param'].'层的人数';          
				   
				            if($v['level']==0){
				                $newdata [$v ['param']] ['level_0'] ++;
				            }elseif ($v['level']==1){
				                $newdata [$v ['param']] ['level_1'] ++;
				            }elseif ($v['level']==2){
				                $newdata [$v ['param']] ['level_2'] ++;
				            }elseif ($v['level']==3){
				                $newdata [$v ['param']] ['level_3'] ++;
				            }elseif ($v['level']==4){
				                $newdata [$v ['param']] ['level_4'] ++;
				            }elseif ($v['level']==5){
				                $newdata [$v ['param']] ['level_5'] ++;
				            }elseif ($v['level']==6){
				                $newdata [$v ['param']] ['level_6'] ++;
				            }elseif ($v['level']==7){
				                $newdata [$v ['param']] ['level_7'] ++;
				            }elseif ($v['level']==8){
				                $newdata [$v ['param']] ['level_8'] ++;
				            }elseif ($v['level']==9){
				                $newdata [$v ['param']] ['level_9'] ++;
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
						'692' => '使用购买精灵塔宝箱2次的人数'
				);
				$where ['typeids'] = [69];
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
				$group = 'level,accountid';
				$where['cid'] = "1,2";
				$field = 'act_id,floor(user_level/10) as level,count(id) cid';
				$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );
				foreach ($data as $v){
					if (! isset ( $newdata [$v ['act_id'].$v['cid']] )) {
						for($i = 0; $i < 10; $i ++) {
							$newdata [$v ['act_id'].$v['cid']] ['level_' . $i] = 0;
							$newdata [$v ['act_id'].$v['cid']] ['act_name'] = $names [$v ['act_id'].$v['cid']];
						}
					}
					$newdata[$v['act_id'].$v['cid']] ['level_' . $v ['level']]+=1;
				}
			}elseif ($btype == 6) { // 每天获得精灵塔1层首通青铜宝箱的人数 等	 			    
			    $names = array (			     
                '10101'=>'每天获得精灵塔1层首通青铜宝箱的人数',
                '10102'=>'每天获得精灵塔1层首通白银宝箱的人数',
                '10103'=>'每天获得精灵塔1层首通黄金宝箱的人数',
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
		    //	$where ['typeids'] = [ 67 ];
				$field = 'act_id,param,floor(user_level/10) as level,count(distinct accountid) cid';
				$group = 'accountid';	
		    	$field = 'act_id,param,floor(user_level/10) as level,count(distinct accountid) cid';
				$data = $this->SystemFunction_model->ActionByParam ( $where, $field, $group );
		
				

				foreach ( $data as $v ) {
				    $newdata [ $v ['param']] ['act_name'] = $names[$v['param']];
				    	
				    if($v['level']==0){
				        $newdata [$v ['param']] ['level_0'] ++;
				    }elseif ($v['level']==1){
				        $newdata [$v ['param']] ['level_1'] ++;
				    }elseif ($v['level']==2){
				        $newdata [$v ['param']] ['level_2'] ++;
				    }elseif ($v['level']==3){
				        $newdata [$v ['param']] ['level_3'] ++;
				    }elseif ($v['level']==4){
				        $newdata [$v ['param']] ['level_4'] ++;
				    }elseif ($v['level']==5){
				        $newdata [$v ['param']] ['level_5'] ++;
				    }elseif ($v['level']==6){
				        $newdata [$v ['param']] ['level_6'] ++;
				    }elseif ($v['level']==7){
				        $newdata [$v ['param']] ['level_7'] ++;
				    }elseif ($v['level']==8){
				        $newdata [$v ['param']] ['level_8'] ++;
				    }elseif ($v['level']==9){
				        $newdata [$v ['param']] ['level_9'] ++;
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
				$field = 'act_id,param,floor(user_level/10) as level,count(distinct accountid) cid';
				$group = 'accountid';	
		    	$field = 'act_id,param,floor(user_level/10) as level,count(distinct accountid) cid';
				$data = $this->SystemFunction_model->ActionByParam ( $where, $field, $group );
		
				if(!empty($data)){
				    foreach ( $data as $v ) {
				        $newdata [ $v ['param']] ['act_name'] = $names[$v['param']];
				         
				        if($v['level']==0){
				            $newdata [$v ['param']] ['level_0'] ++;
				        }elseif ($v['level']==1){
				            $newdata [$v ['param']] ['level_1'] ++;
				        }elseif ($v['level']==2){
				            $newdata [$v ['param']] ['level_2'] ++;
				        }elseif ($v['level']==3){
				            $newdata [$v ['param']] ['level_3'] ++;
				        }elseif ($v['level']==4){
				            $newdata [$v ['param']] ['level_4'] ++;
				        }elseif ($v['level']==5){
				            $newdata [$v ['param']] ['level_5'] ++;
				        }elseif ($v['level']==6){
				            $newdata [$v ['param']] ['level_6'] ++;
				        }elseif ($v['level']==7){
				            $newdata [$v ['param']] ['level_7'] ++;
				        }elseif ($v['level']==8){
				            $newdata [$v ['param']] ['level_8'] ++;
				        }elseif ($v['level']==9){
				            $newdata [$v ['param']] ['level_9'] ++;
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
		    	$where ['typeids'] = [ 108 ];
				$field = 'act_id,param,floor(user_level/10) as level,count(distinct accountid) cid';
				$group = 'accountid';	
		    	$field = 'act_id,param,floor(user_level/10) as level,count(distinct accountid) cid';
				$data = $this->SystemFunction_model->ActionByParam ( $where, $field, $group );
		
				if(!empty($data)){
				foreach ( $data as $v ) {
				    $newdata [ $v ['param']] ['act_name'] = $names[$v['param']];
				     
				    if($v['level']==0){
				        $newdata [$v ['param']] ['level_0'] ++;
				    }elseif ($v['level']==1){
				        $newdata [$v ['param']] ['level_1'] ++;
				    }elseif ($v['level']==2){
				        $newdata [$v ['param']] ['level_2'] ++;
				    }elseif ($v['level']==3){
				        $newdata [$v ['param']] ['level_3'] ++;
				    }elseif ($v['level']==4){
				        $newdata [$v ['param']] ['level_4'] ++;
				    }elseif ($v['level']==5){
				        $newdata [$v ['param']] ['level_5'] ++;
				    }elseif ($v['level']==6){
				        $newdata [$v ['param']] ['level_6'] ++;
				    }elseif ($v['level']==7){
				        $newdata [$v ['param']] ['level_7'] ++;
				    }elseif ($v['level']==8){
				        $newdata [$v ['param']] ['level_8'] ++;
				    }elseif ($v['level']==9){
				        $newdata [$v ['param']] ['level_9'] ++;
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
			$field = 'eudemon,count(*) cid,sum(if(gu.status=0,1,0)) sum1,sum(if(gu.status=1,1,0)) sum0';
			$group = 'eudemon';
			$order = 'cid desc';
			$data = $this->GameServerData->eudemonData ( $where, $field, $group, $order );
			foreach ( $data as &$v ) {
				$v ['eudemon'] = $v ['eudemon'] . $items [$v ['eudemon']];
				$v ['rare'] = round ( $v ['cid'] / $cgid * 100 ) . '%';
				$v ['win_rate'] = round ( $v ['sum0'] /  $v ['cid'] * 100 ) . '%';
				
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
	
}
