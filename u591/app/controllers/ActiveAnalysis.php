<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-12-13
 * Time: 下午8:56
 * 实时数据统计
 */
include_once 'MY_Controller.php';
ini_set ( 'memory_limit', '1024M' );
include APPPATH . 'libraries/mycommon.php';
class ActiveAnalysis extends MY_Controller {
	/**
	 * 坐骑数据统计
	 * 
	 * @author 王涛 20170920
	 */
	public function  horse(){
	
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['enddate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			$where ['beginserver'] = $date3?date ( 'Ymd', strtotime ( $date3 ) ):"";
			$where ['endserver'] = $date4?date ( 'Ymd', strtotime ( $date4 ) ):"";
			$this->load->model ( 'GameServerData' );
			$table = 'game_horse';
	
			$field = 'vip_level, horse_id, sum(horse_num) horse_num';
			$group = 'vip_level,horse_id';
			$order = 'vip_level';
			$data = $this->GameServerData->DataAnalysis ( $table, $where, $field, $group, $order );
			$newdata = array();
			$horsearr = array();
			foreach ($data as $v){
				$newdata[$v['vip_level']][$v['horse_id']] = $v['horse_num'];
				!isset($horsearr[$v['horse_id']])&&$horsearr[$v['horse_id']] = $v['horse_id'];
			}
			$newdatas['data'] = $newdata;
			$newdatas['horsedata'] = $horsearr;
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $newdatas,
			] ) );
		} else {
			$this->data['hide_channel_list'] = true;
			$this->data['hide_end_time'] = true;
			$this->body = 'ActiveAnalysis/horse';
			$this->layout ();
		}
	}
	/**
	 * 月卡统计数据
	 */
	public function  monthCard(){
	
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['enddate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			$where ['beginserver'] = $date3?date ( 'Ymd', strtotime ( $date3 ) ):"";
			$where ['endserver'] = $date4?date ( 'Ymd', strtotime ( $date4 ) ):"";
			$this->load->model ( 'GameServerData' );
			$table = 'game_monthcard';
	
			$field = 'vip_level, sum(usual) susual, sum(hunting) shunting, sum(lifetime) slifetime';
			$group = 'vip_level';
			$order = 'vip_level';
			$data = $this->GameServerData->DataAnalysis ( $table, $where, $field, $group, $order );
			$newdata = array();
			foreach ($data as $v){
				$newdata[$v['vip_level']] = $v;
			}
			// 查询活跃人数
			$field = 'viplev,COUNT(*) as c';
			$group = 'viplev';
			$order = 'viplev';
			$table = 'u_login_' . date ( 'Ymd', strtotime ( $date ) );
			unset($where ['begindate'],$where ['enddate']);
			$data = $this->GameServerData->DataAnalysis ( $table, $where, $field, $group, $order );
			foreach ( $data as $v ) {
				if(!isset($newdata[$v['viplev']])){
					$newdata [$v ['viplev']]['vip_level'] = $v['viplev'];
					$newdata [$v ['viplev']]['susual'] = 0;
					$newdata [$v ['viplev']]['shunting'] = 0;
					$newdata [$v ['viplev']]['slifetime'] = 0;
				}
				$newdata [$v ['viplev']] ['caccount'] = $v ['c'];
				$newdata [$v ['viplev']] ['susualrate'] = $v ['c']>0?round($newdata [$v ['viplev']]['susual']/$v ['c']*100,2):0;
				$newdata [$v ['viplev']] ['shuntingrate'] = $v ['c']>0?round($newdata [$v ['viplev']]['shunting']/$v ['c']*100,2):0;
				$newdata [$v ['viplev']] ['slifetimerate'] = $v ['c']>0?round($newdata [$v ['viplev']]['slifetime']/$v ['c']*100,2):0;
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $newdata
			] ) );
		} else {
			$this->data['hide_channel_list'] = true;
			$this->data['hide_end_time'] = true;
			$this->body = 'ActiveAnalysis/monthcard';
			$this->layout ();
		}
	}
    public function cakeData()
    {
    	if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			$where ['beginserver'] = $date3?date ( 'Ymd', strtotime ( $date3 ) ):"";
			$where ['endserver'] = $date4?date ( 'Ymd', strtotime ( $date4 ) ):"";
			$this->load->model ( 'GameServerData' );
			$table = 'u_behavior_' . date ( 'Ymd', strtotime ( $date ) );
			
			// 查询精炼和制作人数及次数
			$where ['typeids'] = [126,127];
			$field = 'vip_level,act_id,count(distinct accountid) caccount,count(*) ccount';
			$group = 'vip_level,act_id';
			$data = $this->GameServerData->DataAnalysis ( $table, $where, $field, $group );
			$newdata = array ();
			foreach ( $data as $v ) {
				$newdata [$v ['vip_level']] [$v['act_id']] = $v ['caccount'];
				$newdata [$v ['vip_level']] [$v['act_id'].'a'] = round($v ['ccount']/$v ['caccount'],2); //平均次数
			}

			// 查询活跃人数
			$field = 'viplev,COUNT(*) as c';
			$group = 'viplev';
			$table = 'u_login_' . date ( 'Ymd', strtotime ( $date ) );
			unset ( $where ['typeids'] );
			$data = $this->GameServerData->DataAnalysis ( $table, $where, $field, $group, 'viplev' );
			foreach ( $data as $v ) {
				$newdata [$v ['viplev']] ['caccount'] = $v ['c'];
			}
			
			$this->load->model ( 'SystemFunction_model' );
			$where ['begintime'] = strtotime($date);
			$where ['typeids'] = [127];
			$where ['type'] = 1;
			$where ['itemid'] = 3;
			$field = "vip_level,count(*) ccount,sum(item_num) snum";
			$group = "vip_level";
			$data = $this->SystemFunction_model->BehaviorProduceSaleNew($where,$field ,$group);
			foreach ( $data as $v ) {
				$newdata [$v ['vip_level']] ['avgcount'] = isset($newdata [$v ['vip_level']]['127'])?round($v ['ccount']/$newdata [$v ['vip_level']]['127'],2):0; //平均付费次数
				$newdata [$v ['vip_level']] ['avgnum'] = isset($newdata [$v ['vip_level']]['127'])?round($v ['snum']/$newdata [$v ['vip_level']]['127'],2):0; //平均付费钻石
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $newdata 
			] ) );
		} else {
			$this->data['hide_end_time'] = true;
			$this->body = 'ActiveAnalysis/cakeData';
			$this->layout ();
		}
    }
    /**
     * 获取精灵数据
     */
    public function getelves()
    {
    	$this->data['hide_end_time'] = true;
    	$this->data['hide_channel_list'] = true;
    	$this->data['hide_server_list'] = true;
    	$this->data['hide_server_list'] = true;
    	$this->body = 'ActiveAnalysis/getelves';
    	$this->layout ();
    }
    /**
     * 精灵数据查询
     */
    public function elves()
    {
    	if (parent::isAjax ()) {
    		$serverids = $this->input->get ( 'server_id' ); // 区服
    		$type = $this->input->get ( 'type' );
    		$levmin = $this->input->get ( 'levmin' );
    		$levmax = $this->input->get ( 'levmax' );
    		$vipmin = $this->input->get ( 'vipmin' );
    		$vipmax = $this->input->get ( 'vipmax' );
    		$template_id = $this->input->get ( 'template_id' );
    		$ex2min = $this->input->get ( 'ex2min' );
    		$ex2max = $this->input->get ( 'ex2max' );
    		$intimacy_levelmin = $this->input->get ( 'intimacy_levelmin' );
    		$intimacy_levelmax = $this->input->get ( 'intimacy_levelmax' );
    		$sql = "select template_id,sum(ctem) stem,count(*) cuser";
    		if($type == 'user_lev'){
    			$sql .= ',floor(lev/10) as user_lev';
    		}else{
    			$sql .= ',vip_level';
    		}
    		$sql .= " from (SELECT *,count(*) ctem FROM `game_elves` where 1=1";
    		$serverids && $sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    		$levmin && $sql .= " AND lev>=$levmin";
    		$levmax && $sql .= " AND lev<=$levmax";
    		$vipmin && $sql .= " AND vip_level>=$vipmin";
    		$vipmax && $sql .= " AND vip_level<=$vipmax";
    		$template_id && $sql .= " AND template_id in ($template_id)";
    		$ex2min && $sql .= " AND ex2 >= $ex2min";
    		$ex2max && $sql .= " AND ex2 <= $ex2max";
    		$intimacy_levelmin && $sql .= " AND intimacy_level >= $intimacy_levelmin";
    		$intimacy_levelmax && $sql .= " AND intimacy_level <= $intimacy_levelmax";
    		$sql .= " GROUP BY template_id,player_id,server_id)a GROUP BY template_id,$type;";
    		$sdk = $this->load->database('sdk',true);
    		$query = $sdk->query($sql);
    		$newdata = $data = $title = array ();
    		if($query){
    			$data = $query->result_array();
    		}
    		foreach ( $data as $v ) {
    			if(!isset($title[$v["$type"]])){
    				if($type == 'user_lev'){
    					$title[$v["$type"]] = '玩家等级'.($v["$type"]*10).'-'.($v["$type"]*10+9);
    				}else{
    					$title[$v["$type"]]= 'VIP'.$v["$type"];
    				}
    				
    			} 
    			$newdata [$v ['template_id']] ['v_'.$v["$type"]]['stem'] = $v ['stem'];
    			$newdata [$v ['template_id']] ['v_'.$v["$type"]]['cuser'] = $v ['cuser'];
    		}
    		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
    				'status' => 'ok',
    				'data' => array(
						'data'=>$newdata,
						'title'=>$title,
					),
    		] ) );
    	} else {
    		$this->data['hide_end_time'] = true;
    		$this->body = 'ActiveAnalysis/elves';
    		$this->layout ();
    	}
    }

}
