<?php
ini_set ( 'display_errors', 'On' );

include 'MY_Controller.php';
ini_set ( 'memory_limit', '1024M' );
class SystemFunctionNew extends MY_Controller {
	/**
	 *
	 * @var $SystemFunction_model SystemFunction_Model
	 */
	public $SystemFunctionNew_model;
	public function __construct() {
		parent::__construct ();
		 $this->load->model('Player_analysis_new_model');
		$this->load->model ( 'SystemFunctionNew_model' );
		$this->SystemFunctionNew_model->setAppid ( $this->appid );
	}
	/**
	 *    	多人对战-匹配时间查询 
	 */
	public function multiplayerMatchTime() {
        

	    if (parent::isAjax ()) {
	        $date = $this->input->get ( 'date1' );
	        $date2 = $this->input->get ( 'date2' );
	        $where ['begindate'] = date('Ymd',strtotime ( $date ));
	        $where ['enddate'] = date('Ymd',strtotime ( $date2 ));
	        $viplev_min= $this->input->get('viplev_min');
	        $viplev_max= $this->input->get('viplev_max');
	       // $where['gametype'] = $this->input->get ( 'gametype' );//对战类型
	        $where['gametype']=5;
	        $where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
	        $where ['dan'] = $this->input->get ( 'dan' ); //段位
	        $where ['danend'] = $this->input->get ( 'danend' ); //结束段位
	        
	        $where['viplev_min']=$viplev_min;
	        $where['viplev_max']=$viplev_max;
	        $this->load->model ( 'GameServerData' );
			$field = 'matchtime,count(*) c';
			$group  = 'matchtime';
			$data = $this->GameServerData->match ( $where,$field,$group );
	        $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
	            'status' => 'ok',
	            'data' => $data
	        ] ) );
	    } else {
	        $this->data['page_title']="系统功能统计>多人对战-匹配时间查询";
	        $this->body = 'SystemFunction/multiplayerMatchTime';
	 /*        $this->data ['viplev_filter'] = true;
	        $this->data ['hide_channel_list'] = true;
	        $this->body = 'SystemFunction/multiplayerMatchTime'; */
	        $this->layout ();
	    }	    

    }

 
    //系统功能统计-多人对战战斗回合
    public function multiplayerBout(){



        if (parent::isAjax()) {
            $date = $this->input->get('date1') ;
            $date2 = $this->input->get('date2');
             
            $viplev_min= $this->input->get('viplev_min');
            $viplev_max= $this->input->get('viplev_max');
        
            $where['date']=date('Ymd',strtotime ( $date ));
            $where['date2']=date('Ymd',strtotime ( $date2 ));
            $where['viplev_min']=$viplev_min;
            $where['viplev_max']=$viplev_max;
             
             
            $group="turn_num";
            $order="turn_num";
            $field="turn_num";
            $where['serverids'] = $this->input->get('server_id');
            $this->load->model ( 'SystemFunctionNew_model' );
            $data = $this->SystemFunctionNew_model->multiplayerBout($table , $where, $field, $group, $order, $limit);
            
            
            
            foreach ($data as $k=>&$v){            
                foreach ($data['more'] as $k2=>$v2){
                if($v['turn_num']==$v2['turn_num']){
                    $v['total']++;
                }            
                
                }                
            }
        
        
        
        
            if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
            else echo json_encode(['status'=>'fail','info'=>'未查到数据']);
        }else{
        
            $this->data['page_title']="系统功能统计>多人对战战斗回合";
         //   $this->data ['hide_server_list'] = true;
           //   $this->data ['viplev_filter'] = true;
            $this->data ['hide_channel_list'] = true;
            $this->body = 'SystemFunction/multiplayerBout';
            $this->layout();
        }
        
        
        
        
    }
    
    // 	多人对战-技能使用次数统计
    public function multiplayerSkill(){

          
            $skill_type=array(1=>"物理",2=>"特殊",3=>"变化");
            $target_type=array(1=>"敌方单体",2=>"自身",3=>"敌方场地",4=>"我方场地",5=>"天气",6=>"我方单体",7=>"敌方全体",8=>"我方全体",9=>"全体",10=>"全场地");
        
        
        if (parent::isAjax()) {
            $date = $this->input->get('date1') ;
            $date2 = $this->input->get('date2');
             
            $viplev_min= $this->input->get('viplev_min');
            $viplev_max= $this->input->get('viplev_max');
            
            $where['viplev_min']=$viplev_min;
            $where['viplev_max']=$viplev_max;
        
            $where['date']=date('Ymd',strtotime ( $date ));
            $where['date2']=date('Ymd',strtotime ( $date2 ));
             
             
            $group="u.magic_id";
            $order="u.magic_id";
            $field="u.magic_id,count(*) as cnt,s.name,s.system,s.target";
            $where['serverids'] = $this->input->get('server_id');
            $this->load->model ( 'SystemFunctionNew_model' );
            $data = $this->SystemFunctionNew_model->multiplayerSkill($table , $where, $field, $group, $order, $limit);
            
            foreach ($data as &$v){
                $v['system']=$skill_type[$v[system]];
                $v['target']=$target_type[$v[target]];
                
            }
        
        
        
        
            if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
            else echo json_encode(['status'=>'fail','info'=>'未查到数据']);
        }else{
        
            $this->data['page_title']="多人对战>多人对战技能使用率";
            $this->data ['hide_server_list'] = true;
          //  $this->data ['viplev_filter'] = true;
            $this->data ['hide_channel_list'] = true;
            $this->body = 'SystemFunction/multiplayerSkill';
            $this->layout();
        }
       
    }
    
    /*
     * 洛托姆强化   zzl 20171202
     */
    public function intensify(){
        if (parent::isAjax()) {
            $date = $this->input->get('date1') ;
            $date2 = $this->input->get('date2');
         
            $where['date']=date('Ymd',strtotime ( $date ));
            $where['date_table']=date('Ym',strtotime ( $date ));
            $where['date2']=date('Ymd',strtotime ( $date2 ));
             
             
            $group="Rotom_class";
            $order="";
            $field="Rotom_class,COUNT(DISTINCT accountid) cnt";
            $where['serverids'] = $this->input->get('server_id');
            $this->load->model ( 'SystemFunctionNew_model' );
            $data_result = $this->SystemFunctionNew_model->intensify($table , $where, $field, $group, $order, $limit);            
 
            
            $field_user="COUNT(DISTINCT accountid) cnt";
            $group_user='';
            $this->load->model ( 'Data_analysis_model' );
            $logininfo = $this->Data_analysis_model->activeVip($table , $where, $field_user, $group_user, $order, $limit);
            
            
        
            foreach ($data_result as $k=>$v){
             $k_new=$k+3;
                foreach ($data_result['more'] as $v2){
                    if($v['Rotom_class']==$v2['Rotom_class']) {
   
                    }                
                }
                if(empty($v['cnt'])){$v['cnt']=0;}
                $v['Rotom_class']=$class=$v['Rotom_class']?$v['Rotom_class']:0;
                $v['name']="开启".$class."阶洛托姆玩家数";
                $v['text1']="<a href='javascript:classDetail({$v[Rotom_class]}, $where[date])'>服务器分布</a>";
                $v['text2']="<a href='javascript:vipDetail({$v[Rotom_class]}, $where[date])'>VIP分布</a>";
             $data_1[$k]=$v;
            }         
     
            $data_1[10]['name']="平均强化等级";
            $data_1[10]['cnt']=$data_result['more2'][0]['avg'];
            $data_1[10]['text1']="<a href='javascript:classDetail(100,{$where['date']})'>服务器分布</a>";
            $data_1[10]['text2']="<a href='javascript:vipDetail(100,{$where['date']})'>VIP分布</a>";          
            
          
            $data_2[11]['name']="活跃玩家数";
            $data_2[11]['cnt']=  $logininfo[0]['cnt'];
            $data_2[11]['text1']="";
            $data_2[11]['text2']="";  
          
            $data=array_merge($data_2,$data_1);
        
            if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
            else echo json_encode(['status'=>'fail','info'=>'未查到数据']);
        }else{
        
            $this->data['page_title']="洛托姆强化";
            $this->data ['hide_server_list'] = true;
            $this->data ['hide_end_time'] = true;            
           
            $this->data ['hide_channel_list'] = true;
            $this->body = 'SystemFunction/intensify';
            $this->layout();
        }
        
    
    }
		
		/*
	 * 黑卡查询 zzl 20180109   
	 */
	public function blackCard() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$where ['mac'] = $this->input->get ( 'mac' );
			
			$where ['date'] = strtotime ( $date );
			$where ['date_table'] = date ( 'Ym', strtotime($date));
			$where ['date2'] = strtotime ( $date2 );
			
			$group = "";
			$order = "";
			$field = "";
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$this->load->model ( 'SystemFunctionNew_model' );
			$data_1 = $this->SystemFunctionNew_model->blackCard ( $table, $where, $field, $group, $order, $limit );
			
			$dataJoin = $this->SystemFunctionNew_model->blackCardPayJoin ( $table, $where, $field, $group, $order, $limit );
			
			$data_login = $this->SystemFunctionNew_model->blackCardPayLogin( $table, $where, $field, $group, $order, $limit );
			

	         foreach ($data_1 as $k=>$v){
	         
	         	foreach ($data_login as $k2=>$v2){
	         
	         			
	         		if($v['mac']==$v2['mac'] && $v2['total']<10 ){
	         			$v ['text'] = "<a href='javascript:vipDetail($v[id], $v[id])'>查询</a>";
	         			//$data[$k]=$v;break;
	         			$data[$k]=$v;
	         		}
	         		
	         	}
	         		
	         }
			
	
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'status' => 'fail',
						'info' => '未查到数据' 
				] );
		} else {
			
			$this->data ['page_title'] = "系统功能统计>黑卡查询";
			$this->data ['hide_server_list'] = true;
			// $this->data ['hide_end_time'] = true;
			// $this->data ['hide_start_time'] = true;
			
			$this->data ['show_mac'] = true;
			
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/blackCard';
			$this->layout ();
		}
	}

	/*
	 * 系统功能统计-全球技能使用率   zzl 
	 */
	public function skillRate() {
		$skill_type = array (
				1 => "物理",
				2 => "特殊",
				3 => "变化" 
		);
		$target_type = array (
				1 => "敌方单体",
				2 => "自身",
				3 => "敌方场地",
				4 => "我方场地",
				5 => "天气",
				6 => "我方单体",
				7 => "敌方全体",
				8 => "我方全体",
				9 => "全体",
				10 => "全场地" 
		);
		
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$where ['dan_s'] = $this->input->get ( 'dan_s' ) ? $this->input->get ( 'dan_s' ) : 1;
			$where ['dan_e'] = $this->input->get ( 'dan_e' ) ? $this->input->get ( 'dan_e' ) : 10;
			
			$where ['combattype'] = $this->input->get ( 'combattype' );
			
			$viplev_min = $this->input->get ( 'viplev_min' );
			$viplev_max = $this->input->get ( 'viplev_max' );
			
			$where ['viplev_min'] = $viplev_min;
			$where ['viplev_max'] = $viplev_max;
			
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['date2'] = date ( 'Ymd', strtotime ( $date2 ) );
			
			$group = "skillid";
			$order = "";
			$field = "*";
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$this->load->model ( 'SystemFunctionNew_model' );
			
			$magic_type = $this->SystemFunctionNew_model->magicType ();
			
			$data = $this->SystemFunctionNew_model->skillRate ( $table, $where, $field, $group, $order, $limit );
			
			$result_data = array_merge ( $data [1], $data [2], $data [3], $data [4], $data [5], $data [6], $data [7], $data [8], $data [9], $data [10] );
			
			$result_swap = $result_data;
			
			$newArr = array ();
			foreach ( $result_data as $v ) {
				$total_bout += $v ['bout'];
				if (array_key_exists ( $v ['skillid'], $newArr )) {
					$newArr [$v ['skillid']] ['bout'] += $v ['bout'];
					$newArr [$v ['skillid']] ['total'] += $v ['total'];
				} else {
					$newArr [$v ['skillid']] = $v;
				}
			}
			
			$data = $newArr;
			$total_bout *= 2;
			foreach ( $data as $k => &$v ) {
				$v ['rate'] = 100 * (round ( $v ['total'] / ($total_bout), 4 )) . '%';
				foreach ( $magic_type as $v2 ) {
					
					if ($v ['skillid'] == $v2 ['id']) {
						$v ['skillid'] = $v2 ['name'];
					}
				}
				
				$rating [$k] = $v ['rate'];
			}
			
			array_multisort ( $rating,SORT_DESC,$data );
			
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'status' => 'fail',
						'info' => '未查到数据' 
				] );
		} else {
			
			$this->data ['page_title'] = "全球对战-技能使用率统计";
			
			$this->data ['show_dan_list'] = true;
			$this->data ['hide_server_list'] = true;
			$this->data ['show_combat_type'] = true;
			$this->data ['hide_server_list'] = true;
			// $this->data ['viplev_filter'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/skillRate';
			$this->layout ();
		}
	}
    
    
    public function transcript() {
        if (parent::isAjax()) {
            $date = $this->input->get('date1');
            $date2 = $this->input->get('date2');
            
            $where['serverids'] = $this->input->get('server_id');
            $where['typeids'] = $this->input->get('type_id');
            $where['behavior_type'] = $this->input->get('behavior_type')? $this->input->get('behavior_type'):1;
            $where ['channels'] = $this->input->get ( 'channel_id' );
            
            if ($date) {
                $where['date'] = date('Ymd', strtotime($date));
            }
            
            $this->load->model('SystemFunctionNew_model');
            
            $field = "";
            $order = '';
            
            $group = "vip_level";
            
            $data = $this->SystemFunctionNew_model->transcript($table, $where, $field, $group, $order, $limit);
            
         
             foreach ($data['more'] as $k=>$v){
             	if($v['vip_level']==0){$data_new[0]['total']+=$v['cnt'];}
             	if($v['vip_level']==1){$data_new[1]['total']+=$v['cnt'];}
             	if($v['vip_level']==2){$data_new[2]['total']+=$v['cnt'];}
             	if($v['vip_level']==3){$data_new[3]['total']+=$v['cnt'];}
             	if($v['vip_level']==4){$data_new[4]['total']+=$v['cnt'];}
             	if($v['vip_level']==5){$data_new[5]['total']+=$v['cnt'];}
             	if($v['vip_level']==6){$data_new[6]['total']+=$v['cnt'];}
             	if($v['vip_level']==7){$data_new[7]['total']+=$v['cnt'];}
             	if($v['vip_level']==8){$data_new[8]['total']+=$v['cnt'];}
             	if($v['vip_level']==9){$data_new[9]['total']+=$v['cnt'];}
             	if($v['vip_level']==10){$data_new[10]['total']+=$v['cnt'];}
             	if($v['vip_level']==11){$data_new[11]['total']+=$v['cnt'];}
             	if($v['vip_level']==12){$data_new[12]['total']+=$v['cnt'];}
             	if($v['vip_level']==13){$data_new[13]['total']+=$v['cnt'];}
             	if($v['vip_level']==14){$data_new[14]['total']+=$v['cnt'];}
             	if($v['vip_level']==15){$data_new[15]['total']+=$v['cnt'];}
             }
           
            
             unset($data['more']);             
       
            $this->load->model('Data_analysis_model');
            $logininfo = $this->Data_analysis_model->viplogin($where);
            

             
            foreach ($data as $k2 => &$v2) {
              foreach ($logininfo['day0'] as $v3) {
                    
                    if ($v2['vip_level'] == $v3['viplev']) {
                        $v2['c'] = $v3['c'];
                    }
        
                }
            }
            
           
            
            foreach ($data as $k2 => &$v2) {
     
            	$v2['castle_2']=round($v2['total']/$v2['cnt'],1);
            	$v2['castle_3']=$v2['cnt'];
            	$v2['castle_4']=100*round($v2['total']/$v2['total_success'],3);
            	
            	foreach ($data_new as $k5=>$v5){
            		if($v2['vip_level']==$k5){
            			
            			$v2['castle_1']=round($v5['total']/$v2['cnt'],1);
            		}
            	}
            }
            
       
            
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'status' => 'ok',
                'data' => $data,
                'data2' => $data2,
                'cgid' => $cgid
            ]));
        } else {
            
            $this->data['page_title']="系统功能统计 >组队副本数据统计";           	
        	$this->data['behavior_type'] = true;
            $this->data['hide_end_time'] = true;
            $this->body = 'SystemFunction/transcript';
            $this->layout();
        }
    }
		
		/*
	 * 系统功能统计-全球段位分布统计 zzl 2018124
	 */
	public function danGrading() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
			$type = $this->input->get ( 'processtype' ); // 副本
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$where ['season'] = $this->input->get ( 'season' ); // 赛季
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$this->load->model ( 'GameServerData' );
			
		
			$this->load->model ( 'SystemFunctionNew_model' );
			
			if ($type == 1) { // 普通
				$field = 'com_ranklev ranklev,count(DISTINCT account_id) cnt,vip_level';
			} else {
				$field = 'elite_ranklev ranklev,count(DISTINCT account_id) cnt,vip_level';
			}
			$group = 'vip_level,ranklev';
			
			$data = $this->SystemFunctionNew_model->danGrading ( $table, $where, $field, $group, $order, $limit );
			
			$more_data [0] ['vip_level'] = 0;
			$more_data [1] ['vip_level'] = 1;
			$more_data [2] ['vip_level'] = 2;
			$more_data [3] ['vip_level'] = 3;
			$more_data [4] ['vip_level'] = 4;
			$more_data [5] ['vip_level'] = 5;
			$more_data [6] ['vip_level'] = 6;
			$more_data [7] ['vip_level'] = 7;
			$more_data [8] ['vip_level'] = 8;
			$more_data [9] ['vip_level'] = 9;
			$more_data [10] ['vip_level'] = 10;
			$more_data [11] ['vip_level'] = 11;
			$more_data [12] ['vip_level'] = 12;
			$more_data [13] ['vip_level'] = 13;
			$more_data [14] ['vip_level'] = 14;
			$more_data [15] ['vip_level'] = 15;
			
			foreach ( $more_data as $k => &$v ) {
				
				foreach ( $data ['more'] as $v2 ) {
					
					if ($v ['vip_level'] == $v2 ['vip_level']) {
						
						if ($v2 ['ranklev'] == 1) {
							$v ['total1'] += $v2 ['cnt'];
						}
						if ($v2 ['ranklev'] > 1 && $v2 ['ranklev'] <= 5) {
							$v ['total2'] += $v2 ['cnt'];
						}
						if ($v2 ['ranklev'] >= 6 && $v2 ['ranklev'] <= 10) {
							$v ['total3'] += $v2 ['cnt'];
						}
						if ($v2 ['ranklev'] >= 11 && $v2 ['ranklev'] <= 15) {
							$v ['total4'] += $v2 ['cnt'];
						}
						if ($v2 ['ranklev'] >= 16 && $v2 ['ranklev'] <= 20) {
							$v ['total5'] += $v2 ['cnt'];
						}
						if ($v2 ['ranklev'] >= 21 && $v2 ['ranklev'] <= 24) {
							$v ['total6'] += $v2 ['cnt'];
						}
					
					}
				}
			}
			
			unset ( $data ['more'] );
			
			foreach ( $more_data as $k => &$v ) {
				$v['total_cnt']=$v['total1']+$v['total2']+$v['total3']+$v['total4']+$v['total5']+$v['total6'];
				if (empty ( $v ['total1'] )) {
					$v ['total1'] = 0;
				}
				if (empty ( $v ['total2'] )) {
					$v ['total2'] = 0;
				}
				if (empty ( $v ['total3'] )) {
					$v ['total3'] = 0;
				}
				if (empty ( $v ['total4'] )) {
					$v ['total4'] = 0;
				}
			}
			
			foreach ( $data as $k => &$v ) {
				
				foreach ( $more_data as $k2 => $v2 ) {
					
					if ($v ['vip_level'] == $v2 ['vip_level']) {
						$v ['p1'] = $v2 ['total1'];
						$v ['p2'] = $v2 ['total2'];
						$v ['p3'] = $v2 ['total3'];
						$v ['p4'] = $v2 ['total4'];
						$v ['p5'] = $v2 ['total5'];
						$v ['p6'] = $v2 ['total6'];
						
						$v ['total1'] = 100 * (round ( $v2 ['total1'] / $v2 ['total_cnt'], 4 )) . '%';
						$v ['total2'] = 100 * (round ( $v2 ['total2'] / $v2 ['total_cnt'], 4 )) . '%';
						$v ['total3'] = 100 * (round ( $v2 ['total3'] / $v2 ['total_cnt'], 4 )) . '%';
						$v ['total4'] = 100 * (round ( $v2 ['total4'] / $v2 ['total_cnt'], 4 )) . '%';
						$v ['total5'] = 100 * (round ( $v2 ['total5'] / $v2 ['total_cnt'], 4 )) . '%';
						$v ['total6'] = 100 * (round ( $v2 ['total6'] / $v2 ['total_cnt'], 4 )) . '%';
					}
				}
			}
			
			if ($type == 1) { // 普通
				$field2 = 'com_ranklev ranklev,vip_level';
			} else {
				$field2 = 'elite_ranklev ranklev,vip_level';
			}
			
			$data_group = $this->SystemFunctionNew_model->danGradingGroup ( $table, $where, $field2, $group, $order, $limit );
			
			$dan_days = $this->SystemFunctionNew_model->danDays( $table, $where, $field2, $group, $order, $limit );
			
			
			$group = array ();
			
			$group[3]['total_days_1']=0;
			$group[3]['total_days_2']=0;
			$group[3]['total_days_3']=0;
			$group[3]['total_days_4']=0;
			$group[3]['total_days_5']=0;
			$group[3]['total_days_6']=0;
			
			
	
			foreach ( $dan_days as $v2 ) {
			
				if ($v2['ranklev'] == 1) {
					$group[3]['total_days_1'] += $v2['active'];
					$group[3]['total_days_p1']+=1;
				
				}
				if ($v2['ranklev'] >= 1 && $v2 ['ranklev'] <= 5) {
						$group[3]['total_days_2'] += $v2['active'];
						$group[3]['total_days_p2']+=1;
				}
				if ($v2['ranklev'] >= 6 && $v2 ['ranklev'] <= 10) {
						$group [3]['total_days_3'] += $v2 ['active'];
						$group[3]['total_days_p3']+=1;
				}
				if ($v2['ranklev'] >= 11 && $v2 ['ranklev'] <= 15) {
					$group[3]['total_days_4'] += $v2['active'];
					$group[3]['total_days_p4']+=1;
				}
				if ($v2['ranklev'] >= 16 && $v2 ['ranklev'] <= 20) {
					$group [3]['total_days_5'] += $v2['active'];
					$group[3]['total_days_p5']+=1;
				}
				if ($v2['ranklev'] >= 21 && $v2 ['ranklev'] <= 24) {
					$group[3]['total_days_6']+=$v2['active'];
					$group[3]['total_days_p6']+=1;
				}
			}
			
			
			
			
		
			foreach ( $data_group as $v2 ) {
				
				if ($v2 ['ranklev'] == 1) {
					$group [1] ['total1'] += $v2 ['vip_level'];
					$group [2] ['total1'] += $v2 ['level'];
					$group [1] ['people1'] +=1;
					$group [2] ['people1'] +=1;
				}
				if ($v2 ['ranklev'] >= 1 && $v2 ['ranklev'] <= 5) {
					$group [1] ['total2'] += $v2 ['vip_level'];
					$group [2] ['total2'] += $v2 ['level'];
					$group [1] ['people2'] +=1;
					$group [2] ['people2'] +=1;
				}
				if ($v2 ['ranklev'] >= 6 && $v2 ['ranklev'] <= 10) {
					$group [1] ['total3'] += $v2 ['vip_level'];
					$group [2] ['total3'] += $v2 ['level'];
					$group [1] ['people3'] +=1;
					$group [2] ['people3'] +=1;
				}
				if ($v2 ['ranklev'] >= 11 && $v2 ['ranklev'] <= 15) {
					$group [1] ['total4'] += $v2 ['vip_level'];
					$group [2] ['total4'] += $v2 ['level'];
					$group [1] ['people4'] +=1;
					$group [2] ['people4'] +=1;
				}
				if ($v2 ['ranklev'] >= 16 && $v2 ['ranklev'] <= 20) {
					$group [1] ['total5'] += $v2 ['vip_level'];
					$group [2] ['total5'] += $v2 ['level'];
					$group [1] ['people5'] +=1;
					$group [2] ['people5'] +=1;
				}
				if ($v2 ['ranklev'] >= 21 && $v2 ['ranklev'] <= 24) {
					$group [1] ['total6'] += $v2 ['vip_level'];
					$group [2] ['total6'] += $v2 ['level'];
					$group [1] ['people6'] +=1;
					$group [2] ['people6'] +=1;
				}
			}
			
			$group [1] ['title'] = "VIP";
			$group [2] ['title'] = "等级";
			$group [3] ['title'] = "天数";
			$total_people = $data_group ['more'] [0] ['total'];
			
			$group [1] ['value1'] = (round ( $group [1] ['total1'] / $group [1] ['people1'], 4 ));
			$group [1] ['value2'] = (round ( $group [1] ['total2'] / $group [1] ['people2'], 4 ));
			$group [1] ['value3'] =  (round ( $group [1] ['total3'] / $group [1] ['people3'], 4 ));
			$group [1] ['value4'] =(round ( $group [1] ['total4'] / $group [1] ['people4'], 4 ));
			$group [1] ['value5'] =  (round ( $group [1] ['total5'] / $group [1] ['people5'], 4 ));
			$group [1] ['value6'] = (round ( $group [1] ['total6'] / $group [1] ['people6'], 4 ));
			
			$group [2] ['value1'] =(round ( $group [2] ['total1'] / $group [2] ['people1'], 4 ));
			$group [2] ['value2'] = (round ( $group [2] ['total2'] / $group [2] ['people2'], 4 ));
			$group [2] ['value3'] = (round ( $group [2] ['total3'] / $group [2] ['people3'], 4 ));
			$group [2] ['value4'] = (round ( $group [2] ['total4'] / $group [2] ['people4'], 4 ));
			$group [2] ['value5'] =  (round ( $group [2] ['total5'] / $group [2] ['people5'], 4 ));
			$group [2] ['value6'] =  (round ( $group [2] ['total6'] / $group [2] ['people6'], 4 ));
			
			foreach ( $data_group ['more3'] as $v2 ) {
				
				if ($v2 ['ranklev'] == 1) {
					$group [3] ['total1'] += $v2 ['day_number'];
				}
				if ($v2 ['ranklev'] >= 1 && $v2 ['ranklev'] <= 5) {
					$group [3] ['total2'] += $v2 ['day_number'];
				}
				if ($v2 ['ranklev'] >= 6 && $v2 ['ranklev'] <= 10) {
					$group [3] ['total3'] += $v2 ['day_number'];
				}
				if ($v2 ['ranklev'] >= 11 && $v2 ['ranklev'] <= 15) {
					$group [3] ['total4'] += $v2 ['day_number'];
				}
				if ($v2 ['ranklev'] >= 16 && $v2 ['ranklev'] <= 20) {
					$group [3] ['total5'] += $v2 ['day_number'];
				}
				if ($v2 ['ranklev'] >= 21 && $v2 ['ranklev'] <= 24) {
					$group [3] ['total6'] += $v2 ['day_number'];
				}
			}
			

			 $group [3] ['value1'] = (round ( ($group[3]['total_days_1'] )/$group[3]['total_days_p1'],4 ));
			 $group [3] ['value2'] =  (round ( ($group [3] ['total_days_2'] ) / $group[3]['total_days_p2'], 4 ));
			 $group [3] ['value3'] = (round ( ($group [3] ['total_days_3'] )  / $group[3]['total_days_p3'], 4 ));
			 $group [3] ['value4'] = (round ( ($group [3] ['total_days_4'] ) / $group[3]['total_days_p4'], 4 ));
			 $group [3] ['value5'] =  (round ( ($group [3] ['total_days_5'] ) / $group[3]['total_days_p5'], 4 ));
			 $group [3] ['value6'] = (round ( ($group [3] ['total_days_6'] ) / $group[3]['total_days_p6'], 4 ));
			
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $data,
					'data2' => $group,
					'maxdan' => $maxdan 
			] ) );
		} else {
			$this->data ['page_title'] = "系统功能统计 >全球段位分布统计";
			$this->data ['hide_end_time'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/danGrading';
			$this->layout ();
		}
	}
	
	
	
	public function danSearch() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['date'] = date ( 'Ym', strtotime ( $date ) );
			
			$where ['date_table']=$where ['date'];
		
			$type = $this->input->get ( 'processtype' ); // 副本
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$where ['season'] = $this->input->get ( 'season' ); // 赛季
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			
			$where ['lev_min'] = $this->input->get ( 'lev_min' );
			$where ['lev_max'] = $this->input->get ( 'lev_max' );
			
			$where ['days'] = $this->input->get ( 'days' );
			
		
				
			$this->load->model ( 'GameServerData' );
				
			$this->load->model ( 'SystemFunctionNew_model' );
				
			if ($type == 1) { // 普通
				$field = 'com_ranklev ranklev,count(DISTINCT account_id) cnt,vip_level';
			} else {
				$field = 'elite_ranklev ranklev,count(DISTINCT account_id) cnt,vip_level';
			}
			$group = '';
				
			$data = $this->SystemFunctionNew_model->danSearch( $table, $where, $field, $group, $order, $limit );
			
		//	var_dump($data);
			
			foreach ($data as  $v2){
				
			
			
			
			if ($v2 ['dan'] == 1) {
				$data2['value1'] += $v2 ['total'];
				$data2['total']+=$v2['total'];
				
			}
			if ($v2 ['dan'] >= 1 && $v2 ['dan'] <= 5) {
				$data2['value2'] += $v2 ['total'];
				$data2['total']+=$v2['total'];
				
			}
			if ($v2 ['dan'] >= 6 && $v2 ['dan'] <= 10) {
				$data2['value3'] += $v2 ['total'];
				$data2['total']+=$v2['total'];
				
			}
			if ($v2 ['dan'] >= 11 && $v2 ['dan'] <= 15) {
				$data2['value4'] += $v2 ['total'];
				$data2['total']+=$v2['total'];
				
			}
			if ($v2 ['dan'] >= 16 && $v2 ['dan'] <= 20) {
				$data2['value5'] += $v2 ['total'];
				$data2['total']+=$v2['total'];
				
			}
			if ($v2 ['dan'] >= 21 && $v2 ['dan'] <= 24) {
				$data2['value6'] += $v2 ['total'];
				$data2['total']+=$v2['total'];
			
			}	
			
			}
			
		
			$data2['r1']=100*(round($data2['value1']/$data2['total'],4))."%";
			$data2['r2']=100*(round($data2['value2']/$data2['total'],4))."%";
			$data2['r3']=100*(round($data2['value3']/$data2['total'],4))."%";
			$data2['r4']=100*(round($data2['value4']/$data2['total'],4))."%";
			$data2['r5']=100*(round($data2['value5']/$data2['total'],4))."%";
			$data2['r6']=100*(round($data2['value6']/$data2['total'],4))."%";
			
			
			
		
				
		
				
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',					
					'data2' => $data2,
					'maxdan' => $maxdan
			] ) );
		} else {
			$this->data ['page_title'] = "系统功能统计 >全球段位分布查询";
			
			$this->data ['viplev_filter'] = true;
			$this->data ['hide_end_time'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->body = 'SystemFunction/danSearch';
			$this->layout ();
		}
	}
	
	
	public function serverStart(){
		
		$server_start = $this->input->post ( 'server_start' ) ? $this->input->post ( 'server_start', true ) : '';
		$server_end = $this->input->post ( 'server_end' ) ? $this->input->post ( 'server_end', true ) :'';

		
		 $where['server_start']=$server_start?date ( 'Ymd', strtotime ( $server_start ) ): '';
		 $where['server_end']=$server_end?date ( 'Ymd', strtotime ( $server_end ) ):'';
		
		$this->load->model ( 'SystemFunctionNew_model' );	
		$data = $this->SystemFunctionNew_model->serverStart( $table, $where, $field, $group, $order, $limit );
	
		foreach ($data as $k=>$v){
			$tt .=$v['serverid'].',';
		}
		$tt=rtrim($tt,',');
		
		echo $tt;

	}
	
    
    
}
