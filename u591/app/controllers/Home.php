<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-12-13
 * Time: 下午8:56
 */
include 'MY_Controller.php';
ini_set('memory_limit', '1024M');
class Home extends MY_Controller {

    private function permissions()
    {

    }
    public function emptyhtml()
    {
/*$host =	 'http://1.1kpzs.com';

echo preg_match("/[^\.]+\.kpzs\.com$/", $host); die;*/
        $this->layout();
    }
    
    public function index()
    {
    	//if ($this->ion_auth->is_admin()) {
    	//    $user = $this->ion_auth->user()->row();
    	//    print_r($this->ion_auth->groups()->result());
    	//    print_r($this->ion_auth->get_users_groups($this->userData->id)->result());
    	//}
    	$sql = "SELECT * FROM auth_config";
    	if ($this->group_info->appid!=0) {
    		$sql .= " WHERE appid=" . $this->group_info->appid;
    	}
    	$query  = $this->db->query($sql);
    	$games = $query->result_array();
    	$this->load->view('home', ['games'=>$games, 'is_admin'=>$this->ion_auth->is_admin()]);
    }
    /**
     * 掉线情况统计
     *
     * @author 王涛 --20170426
     */
    public function Drops() {
    	if (parent::isAjax ()) {
    		$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
    		$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
    		$where['begintime'] = strtotime($date);
    		$where['endtime'] = strtotime($date2)+86399;
    		$where['client_version'] = $this->input->get('client_version');
    		$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服编号
    		$where['channels'] = $this->input->get('channel_id');
    		$btypename = array(
    				1=>'PVP练习',
    				2=>'PVP天梯',
    				3=>'异步竞技场',
    				4=>'社团战战斗',
    				5=>'全球6v6',
    				6=>'冠军之夜初赛',
    				7=>'冠军之夜淘汰赛'
    		);
    		$this->load->model ( 'GameServerData' );
    		$field = 'count(distinct accountid) caccount';
    		$alldata = $this->GameServerData->drops ( $where ,$field);
    		$allaccount = $alldata?$alldata[0]['caccount']:0;
    		$field = 'btype,count(distinct accountid) caccount';
    		$group = 'btype';
    		$data = $this->GameServerData->drops ( $where ,$field,$group);
    		foreach ($data as &$value){
    			$value['btypename'] = $btypename[$value['btype']];
    		}
    		$field = 'btype,FROM_UNIXTIME(create_time,"%Y%m%d")t,count(distinct accountid) caccount';
    		$group = 'btype,t';
    		$newdata = array();
    		$time = array();//[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23];
    		$chartdata = $this->GameServerData->drops ( $where ,$field,$group);
    		foreach ($chartdata as $v){
    			if(!isset($time[$v['t']])){
    				$time[$v['t']] = $v['t'];
    			}
    			$newdata[$v['btype']][$v['t']] = $v['caccount'];
    		}
    		ksort($time);
    		foreach ($newdata as $k=>$v){ //补充时间段
    			$_day_index = array_keys($v);
    			$diff = array_diff( $time, $_day_index);
    			if ($diff) {
    				foreach($diff as $_diff_day) {
    					$newdata[$k][$_diff_day] = 0;
    				}
    			}
    			ksort($newdata[$k]);
    		}
    		foreach ($newdata as $k=>$v){
    			$legend['data'][] = $btypename[$k];
    			$json_data[] = [
    					'name' => $btypename[$k],
    					'type' => 'line',
    					'data'  => array_values($v),
    			];
    		}
    			
    		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
    				'status' => 'ok',
    				'data' => array(
    						'data'=>$data,
    						'allaccount'=>$allaccount,
    				),
    				'category'=>array_keys($time),
    				'series' => $json_data,
    				'legend' => $legend,
    		] ) );
    	} else {
    		$this->data ['client_filter'] = true;
    		$this->body = 'SystemFunction/drops';
    		$this->layout ();
    	}
    }
    /**
     * 参与度统计
     *
     * @author 王涛 20170330
     */
    public function joinCount()
    {
    	$param_types = include APPPATH .'/config/comsume_types.php'; //商店类型字典
    	if (parent::isAjax()) {
    		$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
    		$dates = array(date('Ymd', strtotime("$date1 -6 days")),date('Ymd', strtotime("$date1 -5 days")),date('Ymd', strtotime("$date1 -4 days")),date('Ymd', strtotime("$date1 -3 days"))
    				,date('Ymd', strtotime("$date1 -2 days")),date('Ymd', strtotime("$date1 -1 days")),date('Ymd', strtotime($date1))
    		);
    		$where['serverids'] = $this->input->get('server_id');
    		$where['typeids'] = $this->input->get('type_id');
    		$this->load->model('Sdk_sum_model');
    		$field = 'act_id,param,logdate,sum(act_count) as allcount,sum(act_account) as allaccount';
    		$group = 'logdate,act_id,param';
    		$params = include APPPATH .'/config/param_type.php'; //商店类型字典
    		$where ['merge_server'] = $this->input->get ( 'merge_server' )? $this->input->get ( 'merge_server' ):0;
    		
    		
    		$d = date('d', strtotime("$date1"));
    		$newdata = array();
    		if($d<7){
    			$where['begindate'] = date('Ymd', strtotime("$date1 -6 days"));
    			$where['enddate'] = date('Ymd', strtotime("$date1 -$d days"));
    			$data = $this->Sdk_sum_model->sumJoin($where,$field,$group);
    			foreach ($data as $v){
    				if(!isset($newdata[$v['act_id']][$v['param']])){
    					$newdata[$v['act_id']][$v['param']]['act_id'] = $v['act_id'];
    					$newdata[$v['act_id']][$v['param']]['param'] = $v['param'];
    					$newdata[$v['act_id']][$v['param']]['actname'] = $param_types[$v['act_id']]?$param_types[$v['act_id']]:$v['act_id'];
    					$newdata[$v['act_id']][$v['param']]['paramname'] = $v['param'].$params[$v['act_id']][$v['param']];
    				}
    				$newdata[$v['act_id']][$v['param']]['act_count'][$v['logdate']] = $v['allcount'];
    				$newdata[$v['act_id']][$v['param']]['act_account'][$v['logdate']]  = $v['allaccount'];
    				$newdata[$v['act_id']][$v['param']]['act_count_'.$v['logdate']] = $v['allcount'];
    				$newdata[$v['act_id']][$v['param']]['act_account_'.$v['logdate']] = $v['allaccount'];
    			}
    			$where['begindate'] = date('Ym01', strtotime("$date1"));
    			$where['enddate'] = date('Ymd', strtotime($date1));
    		}else{
    			$where['begindate'] = date('Ymd', strtotime("$date1 -6 days"));
    			$where['enddate'] = date('Ymd', strtotime($date1));
    		}
    		$data = $this->Sdk_sum_model->sumJoin($where,$field,$group);
    	
    		foreach ($data as $v){
    			if(!isset($newdata[$v['act_id']][$v['param']])){
    				$newdata[$v['act_id']][$v['param']]['act_id'] = $v['act_id'];
    				$newdata[$v['act_id']][$v['param']]['param'] = $v['param'];
    				$newdata[$v['act_id']][$v['param']]['actname'] = $param_types[$v['act_id']]?$param_types[$v['act_id']]:$v['act_id'];
    				$newdata[$v['act_id']][$v['param']]['paramname'] = $v['param'].$params[$v['act_id']][$v['param']];
    			}
    			$newdata[$v['act_id']][$v['param']]['act_count'][$v['logdate']] = $v['allcount'];
    			$newdata[$v['act_id']][$v['param']]['act_account'][$v['logdate']]  = $v['allaccount'];
    			$newdata[$v['act_id']][$v['param']]['act_count_'.$v['logdate']] = $v['allcount'];
    			$newdata[$v['act_id']][$v['param']]['act_account_'.$v['logdate']] = $v['allaccount'];
    		}
    	
    		foreach ($newdata['101'] as $k=>$v){
    			if(!$k) continue;
    			unset($newdata['101'][$k]);
    		}
    		$shows = include APPPATH .'/config/show_list.php'; //排序
    		asort($shows);
    		$showvalue = array();
    		
    	/*  	foreach ($shows as $k=>$v){
    			$showvalue[] = $newdata[$k];
    		}  */
    		
    		foreach ($newdata as $k=>$v){
    		    $showvalue[] = $newdata[$k];
    		}
    	
    		if (!empty($showvalue)) echo json_encode(['status'=>'ok', 'data'=>$showvalue,'dates'=>$dates]);
    		else echo json_encode(['info'=>'无数据']);
    	}
    	else {
    		$this->data ['merge_server'] = true;
    		$this->data['play_pay'] = true;
    		$this->data['hide_type_list'] = 1;
    		$this->data['hide_end_time'] = true;
    		$this->data['hide_channel_list'] = true;
    		$this->data['type_list'] = $param_types;
    		$this->body = 'Home/joincount';
    		$this->layout();
    	}
    }
    /**
     * 服务器剩余金币
     *
     * @author 王涛 20170310
     */
    public function lastMoney()
    {
    	if (parent::isAjax()) {
    		$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
    		$date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d', strtotime('-1 days'));
    		$where['begindate'] = date('Ymd', strtotime($date1));
    		$where['enddate'] = date('Ymd', strtotime($date2));
    		$where['serverids'] = $this->input->get('server_id');
    		$this->load->model('GameEmoney_model');
    		$field = 'logdate,sum(money) as allemoney';
    		$group = 'logdate';
    		$data = $this->GameEmoney_model->serverEmoney($where,$field,$group);
    		if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
    		else echo json_encode(['info'=>'无数据']);
    	}
    	else {
    		$this->data['hide_channel_list'] = true;
    		$this->body = 'Home/lastMoney';
    		$this->layout();
    	}
    }
    /**
     * 服务器剩余钻石
     *
     * @author 王涛 20170303
     */
    public function lastEmoney()
    {
    	if (parent::isAjax()) {
    		$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
    		$date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d', strtotime('-1 days'));
    		$where['begindate'] = date('Ymd', strtotime($date1));
    		$where['enddate'] = date('Ymd', strtotime($date2));
    		$where['serverids'] = $this->input->get('server_id');
    		$this->load->model('GameEmoney_model');
    		$field = 'logdate,sum(emoney) as allemoney';
    		$group = 'logdate';
    		$data = $this->GameEmoney_model->serverEmoney($where,$field,$group);
    		$where['type'] = 1;
    		$adata = $this->GameEmoney_model->serverEmoney($where,$field,$group);
    		$newdata = array();
    		foreach ($adata as $v){
    			$newdata[$v['logdate']] = $v['allemoney'];
    		}
    		if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data,'newdata'=>$newdata]);
    		else echo json_encode(['info'=>'无数据']);
    	}
    	else {
    		$this->data['hide_channel_list'] = true;
    		$this->body = 'Home/lastEmoney';
    		$this->layout();
    	}
    }
    /**
     * 客户端bug
     * 
     * @author 王涛 20170223
     */
    public function clientBug()
    {
    	if (parent::isAjax()) {
    		$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
    		$where['logdate'] = date('Ymd', strtotime($date1));
    		$mac = $this->input->get('mac');
    		if($mac){
    			$where['mac']=$mac;
    		}
    		$source = $this->input->get('source_client');
    		if($source){
    			$where['source_client']=$source;
    		}
    		$this->load->model('Summary_model');
    		$data = $this->Summary_model->getClientBug($where);
    		foreach ($data as &$v){
    			$v['logdate'] = date('Ymd H:i:s',$v['created_at']);
    		}
    		if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
    		else echo json_encode(['info'=>'无数据']);
    	}
    	else {
    		$this->data['hide_channel_list'] = true;
    		$this->data['hide_server_list'] = true;
    		$this->data['hide_end_time'] = true;
    		$this->data['mac_filter'] = true;
    		$this->data['source_filter'] = true;
    		$this->data['bt'] = date('Y-m-d', strtotime('-1 days'));
    		$this->body = 'Home/clientBug';
    		$this->layout();
    	}
    }

    /**
     * 商店销售统计
     *
     * @author 王涛 2017011
     */
    public function shop_count()
    {
    	$items = include APPPATH .'/config/item_types.php'; //道具字典
    	$param_types = include APPPATH .'/config/shop_list.php'; //商店类型字典
    	if (parent::isAjax()) {
    		$field = 'i.item_id,u.serverid,u.channel,sum(item_num) as sumitem';
    		$date = $this->input->get('date1') ?$this->input->get('date1', true) : date('Y-m-d');
    		//$date2 = $this->input->get('date2') ?$this->input->get('date2', true) : date('Y-m-d');
    		$where['channels'] = $this->input->get('channel_id');
    		$where['serverids'] = $this->input->get('server_id');
    		$where['viplev_min'] = $this->input->get('viplev_min');
    		$where['viplev_max'] = $this->input->get('viplev_max');
    		$where['userid'] = $this->input->get('userid');
    		if($where['userid'] && count($where['serverids']) != 1){
    			echo json_encode(['status'=>'fail','info'=>'请选择一个区服']);die;
    		}
    		$where['typeids'] = [0=>1];
    		$where['params'] = $this->input->get('type_id');
    		$group = 'item_id';
    		$where['begintime'] = strtotime($date  . ' 00:00:00');
    		$where['endtime'] = strtotime($date . ' 23:59:59');//只统计当日的数据
    
    		$newdata = array();
    		if(!count($where['channels']) || count($where['channels'])>1){
    			$channelname = '多个渠道';
    		}
    		if(!count($where['serverids']) || count($where['serverids'])>1){
    			$servername = '多个区服';
    		}
    		$this->load->model('SystemFunction_model');
    
    		$where['type'] = 1;//消耗
    		$consume_data = $this->SystemFunction_model->BehaviorProduceSaleNew($where,$field,$group);
    		if($consume_data[0]['item_id']){
    			foreach ($consume_data as $v){
    				if(!$newdata[$v['item_id']]){
    					$newdata[$v['item_id']]['item_id'] = $v['item_id'];
    					$newdata[$v['item_id']]['item_name'] = $items[$v['item_id']]?$items[$v['item_id']]:$v['item_id'];
    					$newdata[$v['item_id']]['servername'] = $servername?$servername:($this->data['server_list'][$v['serverid']]?$this->data['server_list'][$v['serverid']]:$v['serverid']);
    					$newdata[$v['item_id']]['channelname'] = $channelname?$channelname:($this->data['channel_list'][$v['channel']]?$this->data['channel_list'][$v['channel']]:$v['channel']);
    					$newdata[$v['item_id']]['get_num'] = 0;
    				}
    				$newdata[$v['item_id']]['consume_num'] = $v['sumitem'];
    			}
    		}
    		$where['type'] = 0;//获取
    		$get_data = $this->SystemFunction_model->BehaviorProduceSaleNew($where,$field,$group);
    		if($get_data[0]['item_id']){
    			foreach ($get_data as $v){
    				if(!$newdata[$v['item_id']]){
    					$newdata[$v['item_id']]['item_id'] = $v['item_id'];
    					$newdata[$v['item_id']]['item_name'] = $items[$v['item_id']]?$items[$v['item_id']]:$v['item_id'];
    					$newdata[$v['item_id']]['servername'] = $servername?$servername:($this->data['server_list'][$v['serverid']]?$this->data['server_list'][$v['serverid']]:$v['serverid']);
    					$newdata[$v['item_id']]['channelname'] = $channelname?$channelname:($this->data['channel_list'][$v['channel']]?$this->data['channel_list'][$v['channel']]:$v['channel']);
    					$newdata[$v['item_id']]['consume_num'] = 0;
    				}
    				$newdata[$v['item_id']]['get_num'] = $v['sumitem'];
    			}
    		}
    		foreach ( $newdata as $key => $row ){
    			$get[$key] = $row ['get_num'];
    			$consume[$key] = $row ['consume_num'];
    		}
    		array_multisort($consume, SORT_DESC, $get, SORT_DESC, $newdata);
    		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode([
    				'status'=> 'ok' ,
    				'data'  => $newdata,
    		])
    		);
    	}
    	else {
    		$this->data['user_id_filter'] = true;
    		$this->data['viplev_filter'] = true;
    		
    		$this->data['hide_type_list'] = 1;
    		$this->data['hide_end_time'] = true;
    		$this->data['type_list'] = $param_types;
    		$this->body = 'Home/ShopCount';
    		$this->layout();
    	}
    }
    /**
     * 运营相关道具产销
     *
     * @author 王涛 20170111
     */
    public function itemact_use()
    {
    	$types = include APPPATH .'/config/comsume_types.php'; //统计类型字典
    	$items = include APPPATH .'/config/item_types.php'; //统计类型字典
    	$act_types = include APPPATH .'/config/activity_list.php'; //运营类型字典
    	if (parent::isAjax()) {

    		$date = $this->input->get('date1') ?$this->input->get('date1', true) : date('Y-m-d');
    		//$date2 = $this->input->get('date2') ?$this->input->get('date2', true) : date('Y-m-d');
    		$where['channels'] = $this->input->get('channel_id');
    		$where['serverids'] = $this->input->get('server_id');
    		$where['typeids'] = [0=>41];
    		$where['params'] = $this->input->get('type_id');
    		$where['userid'] = $this->input->get('userid');
    		$where['itemid'] = $this->input->get('itemid');
    		$where['begintime'] = strtotime($date  . ' 00:00:00');
    		$newdata = array();
    		if(!count($where['channels']) || count($where['channels'])>1){
    			$channelname = '多个渠道';
    		}
    		if(!count($where['serverids']) || count($where['serverids'])>1){
    			$servername = '多个区服';
    		}
    		if($where['begintime']<=strtotime('-1 days') && empty($where['channels']) && empty($where['serverids']) && empty($where['userid'])){ //查统计表
    			$field = "itemid,sum(consume_num) as consume_num,sum(get_num) as get_num";
    			$group = 'itemid';
    			$this->load->model('Mydb_sum_model');
    			$data = $this->Mydb_sum_model->sumItemByType($where,$field,$group);
    			foreach ($data as $v){
    				$newdata[$v['itemid']]['item_id'] = $v['itemid'];
    				$newdata[$v['itemid']]['item_name'] = $items[$v['itemid']]?$items[$v['itemid']]:$v['itemid'];
    				$newdata[$v['itemid']]['servername'] = $servername;
    				$newdata[$v['itemid']]['channelname'] = $channelname;
    				$newdata[$v['itemid']]['get_num'] = $v['get_num'];
    				$newdata[$v['itemid']]['text'] = "<a href='javascript:showdetail({$v['itemid']},2)'>行为详细</a> <a href='javascript:vipdetail({$v['itemid']},2)'>vip分布</a>  <a href='javascript:areadistribution({$v['itemid']},2)'>区服分布</a> <a href='javascript:leveldistribution({$v['itemid']},2)'>活动档次分布</a>";;
    				$newdata[$v['itemid']]['consume_num'] = $v['consume_num'];
    			}
    		}else{
    			$field = 'i.item_id,u.serverid,u.channel,sum(item_num) as sumitem';
    			if($where['userid'] && count($where['serverids']) != 1){
    				echo json_encode(['status'=>'fail','info'=>'请选择一个区服']);die;
    			}
    			$group = 'item_id';
    			

    			$where['endtime'] = strtotime($date . ' 23:59:59');//只统计当日的数据
    			

    			$this->load->model('SystemFunction_model');
    			
    			$where['type'] = 1;//消耗
    			$consume_data = $this->SystemFunction_model->BehaviorProduceSaleNew($where,$field,$group);
    			if($consume_data[0]['item_id']){
    				foreach ($consume_data as $v){
    					if(!$newdata[$v['item_id']]){
    						$newdata[$v['item_id']]['item_id'] = $v['item_id'];
    						$newdata[$v['item_id']]['item_name'] = $items[$v['item_id']]?$items[$v['item_id']]:$v['item_id'];
    						$newdata[$v['item_id']]['servername'] = $servername?$servername:($this->data['server_list'][$v['serverid']]?$this->data['server_list'][$v['serverid']]:$v['serverid']);
    						$newdata[$v['item_id']]['channelname'] = $channelname?$channelname:($this->data['channel_list'][$v['channel']]?$this->data['channel_list'][$v['channel']]:$v['channel']);
    						$newdata[$v['item_id']]['get_num'] = 0;
    						$newdata[$v['item_id']]['text'] = "<a href='javascript:showdetail({$v['item_id']},2)'>行为详细</a> <a href='javascript:vipdetail({$v['item_id']},2)'>vip分布</a>  <a href='javascript:areadistribution({$v['item_id']},2)'>区服分布</a>  <a href='javascript:leveldistribution({$v['item_id']},2)'>活动档次分布</a>";;
    					}
    					$newdata[$v['item_id']]['consume_num'] = $v['sumitem'];
    				}
    			}
    			$where['type'] = 0;//获取
    			$get_data = $this->SystemFunction_model->BehaviorProduceSaleNew($where,$field,$group);
    			if($get_data[0]['item_id']){
    				foreach ($get_data as $v){
    					if(!$newdata[$v['item_id']]){
    						$newdata[$v['item_id']]['item_id'] = $v['item_id'];
    						$newdata[$v['item_id']]['item_name'] = $items[$v['item_id']]?$items[$v['item_id']]:$v['item_id'];
    						$newdata[$v['item_id']]['servername'] = $servername?$servername:($this->data['server_list'][$v['serverid']]?$this->data['server_list'][$v['serverid']]:$v['serverid']);
    						$newdata[$v['item_id']]['channelname'] = $channelname?$channelname:($this->data['channel_list'][$v['channel']]?$this->data['channel_list'][$v['channel']]:$v['channel']);
    						$newdata[$v['item_id']]['consume_num'] = 0;
    						$newdata[$v['item_id']]['text'] = "<a href='javascript:showdetail({$v['item_id']},2)'>行为详细</a> <a href='javascript:vipdetail({$v['item_id']},2)'>vip分布</a>  <a href='javascript:areadistribution({$v['item_id']},2)'>区服分布</a>  <a href='javascript:leveldistribution({$v['item_id']},2)'>活动档次分布</a>";;
    					}
    					$newdata[$v['item_id']]['get_num'] = $v['sumitem'];
    				}
    			}
    		}
    		
    
    		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode([
    				'status'=> 'ok' ,
    				'data'  => $newdata,
    		])
    		);
    	}
    	else {
    		$this->data['item_id_filter'] = true;
    		$this->data['user_id_filter'] = true;
    		$this->data['hide_type_list'] = 1;
    		$this->data['hide_end_time'] = true;
    		$this->data['type_list'] = $act_types;
    		$this->body = 'Home/act_item_use';
    		$this->layout();
    	}
    }
    /**
     * 道具产销
     *
     * @author 王涛 20170103
     */
    public function item_use()
    {
    	$types = include APPPATH .'/config/comsume_types.php'; //统计类型字典
    	$items = include APPPATH .'/config/item_types.php'; //统计类型字典
    	if (parent::isAjax()) {
    		$date = $this->input->get('date1') ?$this->input->get('date1', true) : date('Y-m-d');
    		//$date2 = $this->input->get('date2') ?$this->input->get('date2', true) : date('Y-m-d');
    		$where['channels'] = $this->input->get('channel_id');
    		$where['serverids'] = $this->input->get('server_id');
    		$where['typeids'] = $this->input->get('type_id');
    		$where['userid'] = $this->input->get('userid');
    		$where['itemid'] = $this->input->get('itemid');
    		$text = "";
    		if(strpos($where['itemid'], ',')!==false){
    				$text = "<a href='javascript:paredetail()'>对比详细</a>";
    		}
    		$where['begintime'] = strtotime($date  . ' 00:00:00');
    		$newdata = array();
    		if(!count($where['channels']) || count($where['channels'])>1){
    			$channelname = '多个渠道';
    		}
    		if(!count($where['serverids']) || count($where['serverids'])>1){
    			$servername = '多个区服';
    		}
    		if($where['begintime']<=strtotime('-1 days') && empty($where['channels']) && empty($where['serverids']) && empty($where['userid'])){ //查统计表
    			$field = "itemid,sum(consume_num) as consume_num,sum(get_num) as get_num";
    			$group = 'itemid';
    			$this->load->model('Mydb_sum_model');
    			$data = $this->Mydb_sum_model->sumItem($where,$field,$group);
    			foreach ($data as $v){
    				$newdata[$v['itemid']]['item_id'] = $v['itemid'];
    				$newdata[$v['itemid']]['item_name'] = $items[$v['itemid']]?$items[$v['itemid']]:$v['itemid'];
    				$newdata[$v['itemid']]['servername'] = $servername;
    				$newdata[$v['itemid']]['channelname'] = $channelname;
    				$newdata[$v['itemid']]['get_num'] = $v['get_num'];
    				$newdata[$v['itemid']]['text'] = $text." <a href='javascript:showdetail({$v['itemid']},1)'>行为详细</a> <a href='javascript:vipdetail({$v['itemid']},1)'>vip分布</a> <a href='javascript:areadistribution({$v['itemid']},2)'>区服分布</a>";;
    				$newdata[$v['itemid']]['consume_num'] = $v['consume_num'];
    			}
    		}else{
    			$field = 'i.item_id,u.serverid,u.channel,sum(item_num) as sumitem';
    			if($where['userid'] && count($where['serverids']) != 1){
    				echo json_encode(['status'=>'fail','info'=>'请选择一个区服']);die;
    			}
    			$group = 'item_id';
    			
    			
    			$where['endtime'] = strtotime($date . ' 23:59:59');//只统计当日的数据
    			
    			
    			$this->load->model('SystemFunction_model');
    			
    			$where['type'] = 1;//消耗
    			$consume_data = $this->SystemFunction_model->BehaviorProduceSaleNew($where,$field,$group);
    			if($consume_data[0]['item_id']){
    				foreach ($consume_data as $v){
    					if(!$newdata[$v['item_id']]){
    						$newdata[$v['item_id']]['item_id'] = $v['item_id'];
    						$newdata[$v['item_id']]['item_name'] = $items[$v['item_id']]?$items[$v['item_id']]:$v['item_id'];
    						$newdata[$v['item_id']]['servername'] = $servername?$servername:($this->data['server_list'][$v['serverid']]?$this->data['server_list'][$v['serverid']]:$v['serverid']);
    						$newdata[$v['item_id']]['channelname'] = $channelname?$channelname:($this->data['channel_list'][$v['channel']]?$this->data['channel_list'][$v['channel']]:$v['channel']);
    						$newdata[$v['item_id']]['get_num'] = 0;
    						$newdata[$v['item_id']]['text'] = $text." <a href='javascript:showdetail({$v['item_id']},1)'>行为详细</a> <a href='javascript:vipdetail({$v['item_id']},1)'>vip分布</a>";;
    					}
    					$newdata[$v['item_id']]['consume_num'] = $v['sumitem'];
    				}
    			}
    			$where['type'] = 0;//获取
    			$get_data = $this->SystemFunction_model->BehaviorProduceSaleNew($where,$field,$group);
    			if($get_data[0]['item_id']){
    				foreach ($get_data as $v){
    					if(!$newdata[$v['item_id']]){
    						$newdata[$v['item_id']]['item_id'] = $v['item_id'];
    						$newdata[$v['item_id']]['item_name'] = $items[$v['item_id']]?$items[$v['item_id']]:$v['item_id'];
    						$newdata[$v['item_id']]['servername'] = $servername?$servername:($this->data['server_list'][$v['serverid']]?$this->data['server_list'][$v['serverid']]:$v['serverid']);
    						$newdata[$v['item_id']]['channelname'] = $channelname?$channelname:($this->data['channel_list'][$v['channel']]?$this->data['channel_list'][$v['channel']]:$v['channel']);
    						$newdata[$v['item_id']]['consume_num'] = 0;
    						$newdata[$v['item_id']]['text'] = $text." <a href='javascript:showdetail({$v['item_id']},1)'>行为详细</a> <a href='javascript:showdetail({$v['item_id']},2)'>vip分布</a>";;
    					}
    					$newdata[$v['item_id']]['get_num'] = $v['sumitem'];
    				}
    			}
    		}
    		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode([
    				'status'=> 'ok' ,
    				'data'  => $newdata,
    		])
    		);
    	}
    	else {
    		$this->data['user_id_filter'] = true;
    		$this->data['item_id_filter'] = true;
    		$this->data['hide_type_list'] = 1;
    		$this->data['hide_end_time'] = true;
    		$this->data['type_list'] = $types;
    		$this->body = 'SystemFunction/item_use';
    		$this->layout();
    	}
    }
    
    /**
     * 行为产销统计
     * @author 王涛  20161230
     */
    public function ActionCount()
    {    	$types = include APPPATH .'/config/comsume_types.php'; //统计类型字典
    	if (parent::isAjax()) {
    		$where['channels'] = $this->input->get('channel_id');
    		$where['serverids'] = $this->input->get('server_id');
    		$where['typeids'] = $this->input->get('type_id');
    		$where['userid'] = $this->input->get('userid');    	
    		$group = '';
    		$type = $this->input->get('searchtype'); //分类
    		if($type == 1){ //按统计类型
    			$group = 'act_id';
    		}elseif($type == 2){ //按区服
    			$group = 'serverid';
    		}elseif($type == 3){ //按渠道
    			$group = 'channel';
    		}    		
    		$date = $this->input->get('date1') ?$this->input->get('date1', true) : date('Y-m-d');
    		$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
    		$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';    	
    	 	$reg1 = $this->input->get ( 'reg1' ) ? $this->input->get ( 'reg1', true ) : '';
    		$reg2 = $this->input->get ( 'reg2' ) ? $this->input->get ( 'reg2', true ) : '';  
    		
    		
    		if (empty ( $date3 )) {
    			$where ['beginserver'] = '';
    		} else {
    			$where ['beginserver'] = date ( 'Ymd', strtotime ( $date3 ) );
    		}
    			
    		if (empty ( $date3 )) {
    			$where ['endserver'] = '';
    		} else {
    			$where ['endserver'] = date ( 'Ymd', strtotime ( $date4 ) );
    		}
    		$where['begintime'] = strtotime($date  . ' 00:00:00');
    	
    		if(!empty($reg1)){
    		    $where ['reg1'] = date ( 'Ymd', strtotime ( $reg1 ) );
    		}
    		if(!empty($reg2)){
    		    $where ['reg2'] = date ( 'Ymd', strtotime ( $reg2 ) );
    		}
 
    		$channelname = '';
    		$servername = '';
    		if(!count($where['channels']) || count($where['channels'])>1){
    			$channelname = '多个渠道';
    		}
    		if(!count($where['serverids']) || count($where['serverids'])>1){
    			$servername = '多个区服';
    		}
    		  
    		if($where['begintime']<=strtotime('-1 days') && empty($where['channels']) && empty($where['serverids']) && empty($where['userid']) && 
    				!($type == 1 && count($where['typeids'])==1 && in_array($where['typeids'][0], array(1,41))) && empty($where['beginserver']) && empty($where ['reg1']) && empty($where ['reg2'])){ //查统计表
    		    
    			if($type == 1){ //按统计类型
    				$where['type'] = 0;
    			}elseif($type == 2){ //按区服
    				$where['type'] = 1;
    			}elseif($type == 3){ //按渠道
    				$where['type'] = 2;
    			}
    			$field = "typeid as $group,account_num as caccountid,consume_money as scmoney,consume_diamond as scemoney,consume_tired as sctired,
    					get_money as sgmoney,get_diamond as sgemoney,get_tired as sgtired";
    			$this->load->model('Mydb_sum_model');
    			$data = $this->Mydb_sum_model->sumAct($where,$field,$group);
    		}else{
    			$field = 'u.act_id,u.serverid,u.channel,param,';
    			$field .= "sum(if(item_id=1&&type=1,item_num,0)) as scmoney,sum(if(item_id=3&&type=1,item_num,0)) as scemoney,sum(if(item_id=2&&type=1,item_num,0)) as sctired,";
    			$field .= "sum(if(item_id=1&&type=0,item_num,0)) as sgmoney,sum(if(item_id=3&&type=0,item_num,0)) as sgemoney,sum(if(item_id=2&&type=0,item_num,0)) as sgtired";
    			if($where['userid'] && count($where['serverids']) != 1){
    				echo json_encode(['status'=>'fail','info'=>'请选择一个区服']);die;
    			}
    			$actflag = 0;//判断是否运营活动
    			$this->load->model('SystemFunction_model');
    			if($type == 1 && count($where['typeids'])==1 && in_array($where['typeids'][0], array(1,41))){ //统计商店或者运营活动
    				if($where['typeids'][0]==41){
    					$param_types = include APPPATH .'/config/activity_list.php'; //运营类型字典
    				}else{
    					$param_types = include APPPATH .'/config/shop_list.php'; //商店类型字典
    				}
    				$actflag = 1;
    				$group = 'param';
    			}
    			$data = $this->SystemFunction_model->ActionProduceSaleNew($where,$field,$group);
    			$field = "$group,count(distinct(accountid)) as caccountid";
    			$countdata = $this->SystemFunction_model->ActionProduceSaleBybehavior($where,$field,$group);    			
    		
    			
    			$newcdata = array();
    			foreach ($countdata as $v){
    				$newcdata[$v[$group]] = $v['caccountid'];
    			}
    		}
    		foreach ($data as $k=>$v){
    			if(!isset($data[$k]['caccountid'])){
    				$data[$k]['caccountid']= $newcdata[$v[$group]];
    			}
    			 
    			$data[$k]['typename'] = $types[$v['act_id']]?$types[$v['act_id']]:$v['act_id'];
    			if($actflag){
    				$data[$k]['typename'] = $param_types[$v['param']]?$param_types[$v['param']]:$v['param'];
    			}
    			$data[$k]['servername'] = $this->data['server_list'][$v['serverid']]?$this->data['server_list'][$v['serverid']]:$v['serverid'];
    			$data[$k]['channelname'] = $this->data['channel_list'][$v['channel']]?$this->data['channel_list'][$v['channel']]:$v['channel'];
    			$data[$k]['text'] = '';
    			if($type == '1'){ //按统计类型
    				if($channelname){
    					$data[$k]['channelname'] = $channelname;
    				}
    				if($servername){
    					$data[$k]['servername'] = $servername;
    				}
    				$data[$k]['text'] .= "<a href='javascript:showdetail({$v['act_id']},1)'>等级详细</a> <a href='javascript:showdetail({$v['act_id']},2)'>vip等级详细</a>";
    				if($v['act_id'] == 22){
    					$data[$k]['text'] .= " <a href='javascript:actiondetail({$v['act_id']})'>行为详细</a>";
    				}
    			}elseif($type == '2'){ //按区服
    				if($channelname){
    					$data[$k]['channelname'] = $channelname;
    				}
    			}elseif($type == '3'){ //按渠道
    				if($servername){
    					$data[$k]['servername'] = $servername;
    				}
    			}
    			if($v['act_id'] == 62){
    				$this->load->model('SystemFunction_model');
    				$field = "sum(item_num) as scin";
    				$where['item_id'] = 10005;
    				$where['type'] = 1;
    				$where['typeids'] = [62];
    				$adata = $this->SystemFunction_model->ActionProduceSaleNew($where,$field,$group);
    				$data[$k]['text'] .= "消耗积分:".($adata[0]['scin']?$adata[0]['scin']:0);
    				//echo $data[$k]['text'];die;
    			}
    		}
    		
    		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode([
    				'status'=> 'ok' ,
    				'data'  => $data,
    				'total' => count($data),
    		])
    		);
    	}
    	else {
    		$this->data['user_id_filter'] = true;
    		$this->data['hide_type_list'] = 1;
    		$this->data['hide_end_time'] = true;
    		$this->data['show_server_date'] = true;
    		//$this->data['show_register_start_date'] = true;
    		
    		
    		$this->data['register_time'] = true;    		
    		$this->data['type_list'] = $types;
            $this->body = 'Home/action_count';
            $this->layout();
    	}
    }
    /**
     * 按区服汇总数据
     *
     * @author 王涛 2017.1.4
     */
    public function summary_by_server()
    {
    	if (parent::isAjax()) {
    		$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
    		$date1 = date('Ymd', strtotime($date1));
    		$date2 = $date1;
    
    		$serveid = $this->input->get('server_id');
    		$channel = $this->input->get('channel_id');
    
    		$this->load->model('Summary_model');
    		$data = $this->Summary_model->getDataByServer($this->appid, $date1, $date2, $serveid, $channel);
    		$output = [];
    		foreach($data as $serverid=>$item) {
    			$output[] = array(
    					'serverid'  => $item['serverid'],
    					//'reg'   => isset($item['reg']) ? $item['reg'] : 0,
    					'role'   => isset($item['new_role']) ? $item['new_role'] : 0,
    					//'trans_rate'   => isset($item['role']) && isset($item['reg'])? number_format($item['role'] / $item['reg'], 2) * 100 : 0,
    					'dau'   => isset($item['dau']) ? $item['dau'] : 0,
    					'wau'   => isset($item['wau']) ? $item['wau'] : 0,
    					'mau'   => isset($item['mau']) ? $item['mau'] : 0,
    					/*'remain_1'   => isset($item['remain_1']) ? $item['remain_1'] . '|' .($item['reg']>0 ? round(($item['remain_1'] / $item['reg']),2)*100 : 0) . '%': 0,
    					 'remain_3'   => isset($item['remain_3']) ? $item['remain_3'] . '|' .($item['reg']>0 ? round(($item['remain_3'] / $item['reg']),2)*100  : 0) . '%' : 0,
    			'remain_7'   => isset($item['remain_7']) ? $item['remain_7'] . '|' .($item['reg']>0 ? round(($item['remain_7'] / $item['reg']),2)*100  : 0) . '%' : 0,
    			'remain_15'   => isset($item['remain_15']) ? $item['remain_15'] . '|' .($item['reg']>0 ? round(($item['remain_15'] / $item['reg']),2)*100  : 0) . '%' : 0,
    			'remain_30'   => isset($item['remain_30']) ? $item['remain_30'] . '|' .($item['reg']>0 ? round(($item['remain_30'] / $item['reg']),2)*100  : 0) . '%' : 0,*/
    			);
    		}
    		if (!empty($output)) echo json_encode(['status'=>'ok', 'data'=>$output]);
    		else echo json_encode(['status'=>'fail']);
    	}
    	else {
    		$this->data['hide_channel_list'] = true;
    		$this->data['hide_end_time'] = true;
    		$this->data['bt'] = date('Y-m-d', strtotime('-1 days'));
    		$this->body = 'Home/summary_by_server';
    		$this->layout();
    	}
    	//$this->data['data'] = $data;
    
    	//print_r($data);
    }
    /**
     * 按平台汇总
     */
    public function summary_by_platform()
    {
    	    	
        if (parent::isAjax()) {
            $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
            $date1 = date('Ymd', strtotime($date1));
            $date2 = $date1;

            $serveid = $this->input->get('server_id');
            $channel = $this->input->get('channel_id');

            $this->load->model('Summary_model');
            $res = $this->Summary_model->getDataByChannel($this->appid, $date1, $date2, $serveid, $channel);
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
            //留存
            //print_r($res['remain']);exit;
            foreach ($res['remain'] as $item) {
                $data[$item['channel']]['remain_1'] = $item['day1'];
                $data[$item['channel']]['remain_3'] = $item['day3'];
                $data[$item['channel']]['remain_7'] = $item['day7'];
                $data[$item['channel']]['remain_15'] = $item['day15'];
                $data[$item['channel']]['remain_30'] = $item['day30'];
            }
            //注册
            foreach ($res['reg'] as $item) {
                $data[$item['channel']]['reg'] = $item['cnt'];
            }
            ksort($data);
            $output = [];
            foreach($data as $channel=>$item) {
                $output[] = array(
                    'channel'  => $channel,//$this->data['channel_list'][$channel]?$this->data['channel_list'][$channel]:$channel,
                	'device'=>isset($item['device']) ? $item['device'] : 0,
                	'macregister'=>isset($item['macregister']) ? $item['macregister'] : 0,
                    'reg'   => isset($item['reg']) ? $item['reg'] : 0,
                    'role'   => isset($item['role']) ? $item['role'] : 0,
                    //'trans_rate'   => isset($item['role']) && isset($item['reg'])? number_format($item['role'] / $item['reg'], 2) * 100 : 0,
                    'dau'   => isset($item['dau']) ? $item['dau'] : 0,
                    'wau'   => isset($item['wau']) ? $item['wau'] : 0,
                    'mau'   => isset($item['mau']) ? $item['mau'] : 0,
                    'remain_1'   => isset($item['remain_1']) ? $item['remain_1'] : 0,
                    'remain_3'   => isset($item['remain_3']) ? $item['remain_3'] : 0,
                    'remain_7'   => isset($item['remain_7']) ? $item['remain_7']: 0,
                    'remain_15'   => isset($item['remain_15']) ? $item['remain_15'] : 0,
                    'remain_30'   => isset($item['remain_30']) ? $item['remain_30'] : 0,
                	'remain_1_rate'   =>$item['reg']>0 ? round(($item['remain_1'] / $item['reg']),2)*100  : 0,
                	'remain_3_rate'   =>$item['reg']>0 ? round(($item['remain_3'] / $item['reg']),2)*100  : 0,
                	'remain_7_rate'   =>$item['reg']>0 ? round(($item['remain_7'] / $item['reg']),2)*100  : 0,
                	'remain_15_rate'   => $item['reg']>0 ? round(($item['remain_15'] / $item['reg']),2)*100  : 0,
                	'remain_30_rate'   => $item['reg']>0 ? round(($item['remain_30'] / $item['reg']),2)*100  : 0,
                		//'remain_30'   => isset($item['remain_30']) ? $item['remain_30'] . '|' .($item['reg']>0 ? round(($item['remain_30'] / $item['reg']),2)*100  : 0) : 0,
                );
            }
            $outputs = array();
            if($output){
            	$outputs['ios']['channel'] = 'ios';
            	$outputs['android']['channel'] = '安卓';
            	if($this->config->item('plat')=='h5'){  //h5加一项
            		$outputs['pc']['channel'] = 'pc';
            	}
            	
            	
            }
            
            
            if($this->config->item('plat')=='h5'){  
            	//   h5用     
            	foreach ($output as $v){
            		if(in_array(substr($v['channel'], 0,1), array(4,5,6,7,8,9))){
            			if(substr($v['channel'], 0,1) == 6 || substr($v['channel'], 0,1) == 7){ //android
            				$name = 'android';
            			}
            			elseif(substr($v['channel'], 0,1) == 8 || substr($v['channel'], 0,1) == 9){
            				$name ='pc';
            			}
            			else{ //ios
            				$name = 'ios';
            			}
            			$outputs[$name]['device'] += $v['device'];
            			$outputs[$name]['macregister'] += $v['macregister'];
            			$outputs[$name]['reg'] += $v['reg'];
            			$outputs[$name]['role'] += $v['role'];
            			//$outputs[$name]['trans_rate'] += $v['trans_rate'];
            			$outputs[$name]['dau'] += $v['dau'];
            			$outputs[$name]['wau'] += $v['wau'];
            			$outputs[$name]['mau'] += $v['mau'];
            			$outputs[$name]['remain_1'] += $v['remain_1'];
            			$outputs[$name]['remain_3'] += $v['remain_3'];
            			$outputs[$name]['remain_7'] += $v['remain_7'];
            			$outputs[$name]['remain_15'] += $v['remain_15'];
            			$outputs[$name]['remain_30'] += $v['remain_30'];
            		}
            	
            	}
            }  else {
            //通用
            	 foreach ($output as $v){
            	 if(in_array(substr($v['channel'], 0,1), array(5,6,7,8,9))){
            	 if(substr($v['channel'], 0,1) == 6 || substr($v['channel'], 0,1) == 7){ //android
            	 $name = 'android';
            	 }else{ //ios
            	 $name = 'ios';
            	 }
            	 $outputs[$name]['device'] += $v['device'];
            	 $outputs[$name]['macregister'] += $v['macregister'];
            	 $outputs[$name]['reg'] += $v['reg'];
            	 $outputs[$name]['role'] += $v['role'];
            	 //$outputs[$name]['trans_rate'] += $v['trans_rate'];
            	 $outputs[$name]['dau'] += $v['dau'];
            	 $outputs[$name]['wau'] += $v['wau'];
            	 $outputs[$name]['mau'] += $v['mau'];
            	 $outputs[$name]['remain_1'] += $v['remain_1'];
            	 $outputs[$name]['remain_3'] += $v['remain_3'];
            	 $outputs[$name]['remain_7'] += $v['remain_7'];
            	 $outputs[$name]['remain_15'] += $v['remain_15'];
            	 $outputs[$name]['remain_30'] += $v['remain_30'];
            	 }
            	
            	 } 
            	
            }
           

              
            foreach ($outputs as $k =>$item){
            	$outputs[$k]['trans_rate'] =   $item['reg']>0? number_format($item['role'] / $item['reg'], 2) * 100 : 0;
            	$outputs[$k]['rare'] =  ($item['device']>0 ? round(($item['macregister'] / $item['device']),2)*100  : 0).'%' ;
            	$outputs[$k]['remain_rate_1'] =  $item['remain_1'] . '|' .($item['reg']>0 ? round(($item['remain_1'] / $item['reg']),2)*100  : 0).'%' ;
            	$outputs[$k]['remain_rate_3'] =  $item['remain_3'] . '|' .($item['reg']>0 ? round(($item['remain_3'] / $item['reg']),2)*100  : 0).'%' ;
            	$outputs[$k]['remain_rate_7'] =  $item['remain_7'] . '|' .($item['reg']>0 ? round(($item['remain_7'] / $item['reg']),2)*100  : 0).'%' ;
            	$outputs[$k]['remain_rate_14'] =  $item['remain_15'] . '|' .($item['reg']>0 ? round(($item['remain_15'] / $item['reg']),2)*100  : 0) .'%';
            	$outputs[$k]['remain_rate_30'] =  $item['remain_30'] . '|' .($item['reg']>0 ? round(($item['remain_30'] / $item['reg']),2)*100  : 0) .'%';
            }
            if (!empty($outputs)) echo json_encode(['status'=>'ok', 'data'=>$outputs]);
            else echo json_encode(['status'=>'fail']);
        }
        else {
            $this->data['hide_server_list'] = true;
            $this->data['hide_channel_list'] = true;
            $this->data['hide_end_time'] = true;
            $this->data['bt'] = date('Y-m-d', strtotime('-1 days'));
            $this->body = 'Home/summary_by_platform';
            $this->layout();
        }
        //$this->data['data'] = $data;

        //print_r($data);
    }
    
    public function test($a,$b,$c){
    	
    return 	($a+$b)/$c;
    	
    }
    
    public function summary()
    {
        if (parent::isAjax()) {
            $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
            $date2 = $this->input->get('date2') ?  $this->input->get('date2', true) : date('Y-m-d');
            $date1 = date('Ymd', strtotime($date1)-86400);  //多取一天的值，不然第一个递增百分比为0
            $date2 = date('Ymd', strtotime($date2));
            $serveid = $this->input->get('server_id');
            $channel = $this->input->get('channel_id');
            !$channel && $this->data['all_channel_list'] && $channel =array_keys($this->data['channel_list']);
           if(!$channel){
            	$where['begindate'] = date('Ymd', strtotime($date1));
            	$where['enddate'] = date('Ymd', strtotime($date2));
            	$this->load->model('Mydb_sum_model');
            	$output = $this->Mydb_sum_model->summary($where);
            	$this->load->model('Player_analysis_model');
            	$remain =  $this->Player_analysis_model->getRemainData($this->appid, $where['begindate'], $where['enddate'], $serverid, $channel);
            	foreach ($remain as $item) {
            		$data[$item['sday']]['remain_1'] = $item['day1'];
            		$data[$item['sday']]['remain_3'] = $item['day3'];
            		$data[$item['sday']]['remain_7'] = $item['day7'];
            		$data[$item['sday']]['remain_15'] = $item['day15'];
            		$data[$item['sday']]['remain_30'] = $item['day30'];
            	}      
           	foreach ($output as $k=>&$v){
            		$v['remain_1']   = isset($data[$v['date']]['remain_1']) ? $data[$v['date']]['remain_1'] . '|' .($v['reg']>0 ? round(($data[$v['date']]['remain_1'] / $v['reg']),2)*100 : 0) . '%': 0;
            		$v['remain_3']   = isset($data[$v['date']]['remain_3']) ? $data[$v['date']]['remain_3'] . '|' .($v['reg']>0 ? round(($data[$v['date']]['remain_3'] / $v['reg']),2)*100  : 0) . '%' : 0;
            		$v['remain_7']   = isset($data[$v['date']]['remain_7']) ? $data[$v['date']]['remain_7'] . '|' .($v['reg']>0 ? round(($data[$v['date']]['remain_7'] / $v['reg']),2)*100  : 0) . '%' : 0;
            		$v['remain_15']   = isset($data[$v['date']]['remain_15']) ? $data[$v['date']]['remain_15'] . '|' .($v['reg']>0 ? round(($data[$v['date']]['remain_15'] / $v['reg']),2)*100  : 0) . '%' : 0;
            		$v['remain_30']   = isset($data[$v['date']]['remain_30']) ? $data[$v['date']]['remain_30'] . '|' .($v['reg']>0 ? round(($data[$v['date']]['remain_30'] / $v['reg']),2)*100  : 0) . '%' : 0;
            		$v['reg_rate'] =  $output[$k-1]['reg']?round(($v['reg']-$output[$k-1]['reg'])/$output[$k-1]['reg'],4)*100:0;  
            		$v['dau_rate'] =  $output[$k-1]['dau']?round(($v['dau']-$output[$k-1]['dau'])/$output[$k-1]['dau'],4)*100:0;
            		$v['text'] = "<a href='javascript:showdetail({$v['date']},2)'>vip</a>";
           	}        	
                 	
  
            }else{
            	$where['channels'] = $channel;
            	$where['begindate'] = date('Ymd', strtotime($date1));
            	$where['enddate'] = date('Ymd', strtotime($date2));
            	$this->load->model('Mydb_sum_model');
            	$field = 'date,sum(device) device,sum(macregister) macregister,sum(reg) reg,sum(role) role,sum(dau) dau,wau,mau';
            	$group='date';
            	$output = $this->Mydb_sum_model->summarybychannel($where,$field,$group,'date');
            	$this->load->model('Player_analysis_model');
            	$remain =  $this->Player_analysis_model->getRemainData($this->appid, $where['begindate'], $where['enddate'], $serverid, $where['channels']);
            	foreach ($remain as $item) {
            		$data[$item['sday']]['remain_1'] = $item['day1'];
            		$data[$item['sday']]['remain_3'] = $item['day3'];
            		$data[$item['sday']]['remain_7'] = $item['day7'];
            		$data[$item['sday']]['remain_15'] = $item['day15'];
            		$data[$item['sday']]['remain_30'] = $item['day30'];
            	}
            	
          	foreach ($output as $k=>&$v){
            		$v['rare'] = $v['device']?(round($v['macregister']/$v['device'],2)*100).'%':'0%';          
            		$v['trans_rate'] = $v['reg']?round($v['role']/$v['reg'],2)*100:0;
            		$v['max_online'] = $v['avg_online_cnt'] = $v['avg_online'] =0;
            		$v['remain_1']   = isset($data[$v['date']]['remain_1']) ? $data[$v['date']]['remain_1'] . '|' .($v['reg']>0 ? round(($data[$v['date']]['remain_1'] / $v['reg']),2)*100 : 0) . '%': 0;
            		$v['remain_3']   = isset($data[$v['date']]['remain_3']) ? $data[$v['date']]['remain_3'] . '|' .($v['reg']>0 ? round(($data[$v['date']]['remain_3'] / $v['reg']),2)*100  : 0) . '%' : 0;
            		$v['remain_7']   = isset($data[$v['date']]['remain_7']) ? $data[$v['date']]['remain_7'] . '|' .($v['reg']>0 ? round(($data[$v['date']]['remain_7'] / $v['reg']),2)*100  : 0) . '%' : 0;
            		$v['remain_15']   = isset($data[$v['date']]['remain_15']) ? $data[$v['date']]['remain_15'] . '|' .($v['reg']>0 ? round(($data[$v['date']]['remain_15'] / $v['reg']),2)*100  : 0) . '%' : 0;
            		$v['remain_30']   = isset($data[$v['date']]['remain_30']) ? $data[$v['date']]['remain_30'] . '|' .($v['reg']>0 ? round(($data[$v['date']]['remain_30'] / $v['reg']),2)*100  : 0) . '%' : 0;
            		
            		$v['reg'] = $v['reg']?$v['reg']:$v['macregister'];
            		$v['reg_rate'] =  $output[$k-1]['macregister']?round(($v['macregister']-$output[$k-1]['macregister'])/$output[$k-1]['macregister'],4)*100:0;
            		
            		//$v['reg_rate'] =  $output[$k-1]['reg']?round(($v['reg']-$output[$k-1]['reg'])/$output[$k-1]['reg'],4)*100:0;
            		$v['dau_rate'] =  $output[$k-1]['dau']?round(($v['dau']-$output[$k-1]['dau'])/$output[$k-1]['dau'],4)*100:0;
            		$v['text'] = "<a href='javascript:showdetail({$v['date']},2)'>vip</a>";
          	}
            

          
         
    
            	
            	/* $this->load->model('Summary_model');
            	$res = $this->Summary_model->getData($this->appid, $date1, $date2, $serveid, $channel);
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
            	//留存
            	foreach ($res['remain'] as $item) {
            		$data[$item['sday']]['remain_1'] = $item['day1'];
            		$data[$item['sday']]['remain_3'] = $item['day3'];
            		$data[$item['sday']]['remain_7'] = $item['day7'];
            		$data[$item['sday']]['remain_15'] = $item['day15'];
            		$data[$item['sday']]['remain_30'] = $item['day30'];
            		$data[$item['sday']]['dau']= (isset($data[$item['sday']]['dau'])) ?$data[$item['sday']]['dau']:0;
            		$data[$item['sday']]['wau']= (isset($data[$item['sday']]['wau'])) ?$data[$item['sday']]['wau']:0;
            		$data[$item['sday']]['mau']= (isset($data[$item['sday']]['mau'])) ?$data[$item['sday']]['mau']:0;
            		$data[$item['sday']]['role']= (isset($data[$item['sday']]['role'])) ?$data[$item['sday']]['role']:0;
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
            	ksort($data);
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
            				'remain_1'   => isset($item['remain_1']) ? $item['remain_1'] . '|' .($item['reg']>0 ? round(($item['remain_1'] / $item['reg']),2)*100 : 0) . '%': 0,
            				'remain_3'   => isset($item['remain_3']) ? $item['remain_3'] . '|' .($item['reg']>0 ? round(($item['remain_3'] / $item['reg']),2)*100  : 0) . '%' : 0,
            				'remain_7'   => isset($item['remain_7']) ? $item['remain_7'] . '|' .($item['reg']>0 ? round(($item['remain_7'] / $item['reg']),2)*100  : 0) . '%' : 0,
            				'remain_15'   => isset($item['remain_15']) ? $item['remain_15'] . '|' .($item['reg']>0 ? round(($item['remain_15'] / $item['reg']),2)*100  : 0) . '%' : 0,
            				'remain_30'   => isset($item['remain_30']) ? $item['remain_30'] . '|' .($item['reg']>0 ? round(($item['remain_30'] / $item['reg']),2)*100  : 0) . '%' : 0,
            		);
            	} */
            	
            	
           }
            

           $this->load->model('Mydb_sum_model');
           $field = 'date,sum(device) device,sum(macregister) macregister,sum(reg) reg,sum(role) role,sum(dau) dau,wau,mau';
           $group='date';
           $this->load->model('Mydb_sum_model');
           $output_max = $this->Mydb_sum_model->summary($where);
            
           $this->load->model('player_analysis_model');
           $data_Real_Dau = $this->player_analysis_model->getRealAuData($this->appid, $where['begindate']-1, $where['enddate']+1, 0, $channel, $by_channel);
           foreach ($output as &$v){
               foreach ($data_Real_Dau as $k2=>$v2){
                   if($v['date']==$v2['sday']){
                       $v['clean_dau']=$v2['clean_dau'];
                       $v['clean_dau_rate']=$data_Real_Dau[$k2-1]['clean_dau']?round(($v2['clean_dau']-$data_Real_Dau[$k-1]['clean_dau'])/$data_Real_Dau[$k2-1]['clean_dau'],4)*100:0;
                    
                   }
               }
               if(empty($channel)) {
                   foreach ($output_max as $v3){
                       if($v['date']==$v3['date']){
                           $v['max_online']=$v3['max_online'];
                           $v['avg_online_cnt'] = $v3['avg_online_cnt'];
                       }
                   }
               } else {
                   $v['max_online']=0;
                   $v['avg_online_cnt']=0;
                   }
                   if(empty($v['device_old_account']))$v['device_old_account']=0;
                   if(empty($v['install_old_account']))$v['install_old_account']=0;
                   
    
                   if(empty($v['pay_dau'])){
                   	$v['pay_dau']=0;
                   }
               
           }
           
           
        

            unset($output[0]); //删去多加的一天
            if (!empty($output)) echo json_encode(['status'=>'ok', 'data'=>$output]);
            else echo json_encode(['status'=>'fail']);
        }
        else {
            $this->data['hide_server_list'] = true;         
           //$this->data['hide_channel_list'] = true;
            $this->body = 'Home/summary';
            $this->layout();
        }
        //$this->data['data'] = $data;

        //print_r($data);
    }

    
    /*
     * 一天汇总
     */
    public function summaryByDay()
    {
        if (parent::isAjax()) {
            
            $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
            $date1 = date('Ymd', strtotime($date1));
            $date2 = $date1;
            $date_pre=$date1-1;
            
            $serveid = $this->input->get('server_id');
            $channel = $this->input->get('channel_id');
            
            $this->load->model('Summary_model');
            $res = $this->Summary_model->getDataByChannel($this->appid, $date1, $date2, $serveid, $channel);
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
            //留存
            //print_r($res['remain']);exit;
            foreach ($res['remain'] as $item) {
                $data[$item['channel']]['remain_1'] = $item['day1'];
        
            }
            //注册
            foreach ($res['reg'] as $item) {
                $data[$item['channel']]['reg'] = $item['cnt'];
            }
            ksort($data);
            $output = [];
            foreach($data as $channel=>$item) {
                $output[] = array(
                    'channel'  => $channel,
                    'device'=>isset($item['device']) ? $item['device'] : 0,
                    'macregister'=>isset($item['macregister']) ? $item['macregister'] : 0,
                    'reg'   => isset($item['reg']) ? $item['reg'] : 0,
                    'role'   => isset($item['role']) ? $item['role'] : 0,
           
                    'dau'   => isset($item['dau']) ? $item['dau'] : 0,
                    'wau'   => isset($item['wau']) ? $item['wau'] : 0,
                    'mau'   => isset($item['mau']) ? $item['mau'] : 0,
                    'remain_1'   => isset($item['remain_1']) ? $item['remain_1'] : 0,
     
                );
            }
            $outputs = array();
            if($output){
                $outputs['ios']['channel'] = 'ios';
                $outputs['android']['channel'] = '安卓';
            }
            
            foreach ($output as $v){
                if(in_array(substr($v['channel'], 0,1), array(5,6,7,8,9))){
                    if(substr($v['channel'], 0,1) == 6 || substr($v['channel'], 0,1) == 7){ //android
                        $name = 'android';
                    }else{ //ios
                        $name = 'ios';
                    }
                    $outputs[$name]['device'] += $v['device'];
                    $outputs[$name]['macregister'] += $v['macregister'];
                    $outputs[$name]['reg'] += $v['reg'];
                    $outputs[$name]['role'] += $v['role'];          
                    $outputs[$name]['dau'] += $v['dau'];
                    $outputs[$name]['wau'] += $v['wau'];
                    $outputs[$name]['mau'] += $v['mau'];
                    $outputs[$name]['remain_1'] += $v['remain_1'];
   
                }
            
            }
            foreach ($outputs as $k =>$item){
                $outputs[$k]['trans_rate'] =   $item['reg']>0? number_format($item['role'] / $item['reg'], 2) * 100 : 0;
                $outputs[$k]['rare'] =  ($item['device']>0 ? round(($item['macregister'] / $item['device']),2)*100  : 0).'%' ;
                $outputs[$k]['remain_rate_1'] =  $item['remain_1'] . '|' .($item['reg']>0 ? round(($item['remain_1'] / $item['reg']),2)*100  : 0).'%' ;
                $outputs[$k]['remain_rate_1_new'] =  $item['remain_1'] . ($item['reg']>0 ? round(($item['remain_1'] / $item['reg']),2)*100  : 0) ;

            }
            

       
             //最大在线
            $field="date,device,reg,role,dau,wau,mau,max_online,avg_online_cnt";
            $where['begindate']=$date1;
            $where['enddate']=$date1;
            
            $this->load->model('Mydb_sum_model');
            $summary_info = $this->Mydb_sum_model->summary($where,$field,$group,$order);
            
      
            $where['begindate']= $date_pre;
            $where['enddate']= $date_pre;
            $summary_info_pre = $this->Mydb_sum_model->summary($where,$field,$group,$order);
         
            
         
      
            
            
         //前一天的统计
            $serveid = $this->input->get('server_id');
            $channel = $this->input->get('channel_id');
            
            $this->load->model('Summary_model');
            $res = $this->Summary_model->getDataByChannel($this->appid, $date1-1, $date2-1, $serveid, $channel);
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
            //留存
            //print_r($res['remain']);exit;
            foreach ($res['remain'] as $item) {
                $data[$item['channel']]['remain_1'] = $item['day1'];
 
            }
            //注册
            foreach ($res['reg'] as $item) {
                $data[$item['channel']]['reg'] = $item['cnt'];
            }
            ksort($data);
            $output = [];
            foreach($data as $channel=>$item) {
                $output[] = array(
                    'channel'  => $channel,
                    'device'=>isset($item['device']) ? $item['device'] : 0,
                    'macregister'=>isset($item['macregister']) ? $item['macregister'] : 0,
                    'reg'   => isset($item['reg']) ? $item['reg'] : 0,
                    'role'   => isset($item['role']) ? $item['role'] : 0,                 
                    'dau'   => isset($item['dau']) ? $item['dau'] : 0,
                    'wau'   => isset($item['wau']) ? $item['wau'] : 0,
                    'mau'   => isset($item['mau']) ? $item['mau'] : 0,
                    'remain_1'   => isset($item['remain_1']) ? $item['remain_1'] : 0,
       
                  
                );
            }
            $outputs_pre = array();
            if($output){
                $outputs_pre['ios']['channel'] = 'ios';
                $outputs_pre['android']['channel'] = '安卓';
            }
            
            foreach ($output as $v){
                if(in_array(substr($v['channel'], 0,1), array(5,6,7,8,9))){
                    if(substr($v['channel'], 0,1) == 6 || substr($v['channel'], 0,1) == 7){ //android
                        $name = 'android';
                    }else{ //ios
                        $name = 'ios';
                    }
                    $outputs_pre[$name]['device'] += $v['device'];
                    $outputs_pre[$name]['macregister'] += $v['macregister'];
                    $outputs_pre[$name]['reg'] += $v['reg'];
                    $outputs_pre[$name]['role'] += $v['role'];              
                    $outputs_pre[$name]['dau'] += $v['dau'];
                    $outputs_pre[$name]['wau'] += $v['wau'];
                    $outputs_pre[$name]['mau'] += $v['mau'];
                    $outputs_pre[$name]['remain_1'] += $v['remain_1'];
      
                }
            
            }
            foreach ($outputs_pre as $k =>$item){
                $outputs_pre[$k]['trans_rate'] =   $item['reg']>0? number_format($item['role'] / $item['reg'], 2) * 100 : 0;
                $outputs_pre[$k]['rare'] =  ($item['device']>0 ? round(($item['macregister'] / $item['device']),2)*100  : 0).'%' ;
                $outputs_pre[$k]['remain_rate_1'] =  $item['remain_1'] . '|' .($item['reg']>0 ? round(($item['remain_1'] / $item['reg']),2)*100  : 0).'%' ;
                $outputs_pre[$k]['remain_rate_1_new'] =  $item['remain_1'] . ($item['reg']>0 ? round(($item['remain_1'] / $item['reg']),2)*100  : 0) ;
     
            }
  
            //新增账号
            $group="channel";
            $this->load->model('register_model');
            $new_account = $this->register_model->getRegDetail($this->appid, $date1, $date1, $serverid=null, $channel=null, $table='sum_register_day',$by_channel,$group);  
            $new_account_pre = $this->register_model->getRegDetail($this->appid, $date1-1, $date1-1, $serverid=null, $channel=null, $table='sum_register_day',$by_channel,$group);

            foreach ($new_account_pre as $v){
                if(substr($v['channel'], 0,1) == 6 || substr($v['channel'], 0,1) == 7){ //android
                    $outputs['android']['reg_pre']+=$v['cnt'];
                     
                }else{ //ios
                    $outputs['ios']['reg_pre']+=$v['cnt'];
                }
            }
            
            
            
              //总账号
            $where['date1']=  $date1;
            $where['date_pre']=  $date1-1;
            $this->load->model('Player_analysis_model');
            $total_account = $this->Player_analysis_model->totalAccount($table, $where, $field, $group, $order, $limit);      
            
            foreach ($total_account['date1'] as $v){
                if(substr($v['channel'], 0,1) == 6 || substr($v['channel'], 0,1) == 7){ //android                 
                    $outputs['android']['total_account']+=$v['total'];      
                     
                }else{ //ios                    
                    $outputs['ios']['total_account']+=$v['total'];
                }                 
            }
            
            
            foreach ($total_account['date_pre'] as $v){
                if(substr($v['channel'], 0,1) == 6 || substr($v['channel'], 0,1) == 7){ //android                   
                    $outputs['android']['total_account_pre']+=$v['total'];                    
                }else{ //ios                   
                    $outputs['ios']['total_account_pre']+=$v['total'];
                }                 
            }
          
            // dau
            $group="channel";
            $this->load->model('player_analysis_model');
            $data_Real_Dau = $this->player_analysis_model->getRealDau($this->appid, $where,$group);
             
            $where['date1']= $where['date_pre'];
            $data_Real_Dau_pre = $this->player_analysis_model->getRealDau($this->appid, $where,$group);            
           
            foreach ($data_Real_Dau as $v){
                if(substr($v['channel'], 0,1) == 6 || substr($v['channel'], 0,1) == 7){ //android
                    $outputs['android']['clean_dau']+=$v['clean_dau'];
                    $outputs['android']['dau_pre']+=$v['clean_dau'];
                }else{ //ios
                    $outputs['ios']['clean_dau']+=$v['clean_dau'];
                    $outputs['ios']['dau_pre']+=$v['clean_dau'];
                  
                }
            }               
             
            foreach ($data_Real_Dau_pre as $v){
                if(substr($v['channel'], 0,1) == 6 || substr($v['channel'], 0,1) == 7){ //android
                    $outputs['android']['clean_dau_pre']+=$v['clean_dau'];
                    $outputs['android']['wau_pre']+=$v['wau'];
                }else{ //ios
                    $outputs['ios']['clean_dau_pre']+=$v['clean_dau'];
                    $outputs['ios']['wau_pre']+=$v['wau'];
                }
            }
    
            
             $outputs['android']['total_account_rate']=round((($outputs['android']['total_account']- $outputs['android']['total_account_pre'])/ $outputs['android']['total_account_pre']),6)*100;
             $outputs['ios']['total_account_rate']=(round(( $outputs['ios']['total_account']- $outputs['ios']['total_account_pre'])/ $outputs['ios']['total_account'],6))*100;
     
              $outputs['android']['dau_rate']=round((($outputs['android']['dau']- $summary_info_pre[0]['dau'])/ $outputs['android']['dau']),6)*100;
              $outputs['ios']['dau_rate']=round((($outputs['ios']['dau']- $summary_info_pre[0]['dau'])/ $outputs['ios']['dau']),6)*100; 
             //
             $outputs['android']['clean_dau_rate']=round((($outputs['android']['clean_dau']- $outputs['android']['clean_dau_pre'])/ $outputs['android']['clean_dau']),6)*100;
             $outputs['ios']['clean_dau_rate']=round((($outputs['ios']['clean_dau']- $outputs_pre['ios']['clean_dau_pre'])/ $outputs['ios']['dau']),6)*100;
             
             $outputs['android']['wau_rate']=round((($outputs['android']['wau']- $outputs['android']['wau_pre'])/ $outputs['android']['wau']),6)*100;
             $outputs['ios']['wau_rate']=round((($outputs['ios']['wau']- $outputs['ios']['wau_pre'])/ $outputs['ios']['wau']),6)*100;
             
             $outputs['android']['reg_rate']=round((($outputs['android']['reg']- $outputs['android']['reg_pre'])/ $outputs['android']['reg']),6)*100;
             $outputs['ios']['reg_rate']=round((($outputs['ios']['reg']- $outputs['ios']['reg_pre'])/ $outputs['ios']['reg']),6)*100;
     
             //最高在线不分ios android
             $outputs['android']['max_online']=$summary_info[0]['max_online'];
             $outputs['ios']['max_online']=$summary_info[0]['max_online'];
             $outputs['android']['max_online_rate']=round((($summary_info[0]['max_online']- $summary_info_pre[0]['max_online'])/ $summary_info[0]['max_online']),6)*100;
             $outputs['ios']['max_online_rate']=round((($summary_info[0]['max_online']- $summary_info_pre[0]['max_online'])/ $summary_info[0]['max_online']),6)*100;
       
             

             $outputs['android']['remain_rate_1_rate']=round((($outputs['android']['remain_rate_1_new']- $outputs_pre['android']['remain_rate_1_new'])/ $outputs['android']['remain_rate_1_new']),6)*100;
             $outputs['ios']['remain_rate_1_rate']=round((($outputs['ios']['remain_rate_1_new']- $outputs_pre['ios']['remain_rate_1_new'])/ $outputs['ios']['remain_rate_1_new']),6)*100;
              
            if (!empty($outputs)) echo json_encode(['status'=>'ok', 'data'=>$outputs]);
            else echo json_encode(['status'=>'fail']);
            
            
        
        }
        else {
            $this->data['hide_server_list'] = true;
            $this->data['hide_channel_list'] = true;
            $this->data['hide_end_time'] = true;
            $this->data['bt'] = date('Y-m-d', strtotime('-1 days'));
            $this->body = 'Home/summaryByDay';
            $this->layout();
        }
   

    
    }
    /**
     * 按渠道汇总数据
     */
    public function summary_by_channel()
    {
        if (parent::isAjax()) {
            $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
            $date1 = date('Ymd', strtotime($date1));
            $date2 = $date1;
            $where['channels'] = $this->input->get('channel_id');
            $where['begindate'] = date('Ymd', strtotime($date1));
            $where['enddate'] = date('Ymd', strtotime($date2));
            $this->load->model('Mydb_sum_model');
            $output = $this->Mydb_sum_model->summarybychannel($where);
            $this->load->model('Player_analysis_model');
            $remain =  $this->Player_analysis_model->getRemainData($this->appid, $where['begindate'], $where['enddate'], $serverid, $where['channels'], 1, 1);
            foreach ($remain as $item) {
            	$data[$item['channel']]['remain_1'] = $item['day1'];
            	$data[$item['channel']]['remain_3'] = $item['day3'];
            	$data[$item['channel']]['remain_7'] = $item['day7'];
            	$data[$item['channel']]['remain_15'] = $item['day15'];
            	$data[$item['channel']]['remain_30'] = $item['day30'];
            }

            foreach ($output as &$v){
            	$v['remain_1']   = isset($data[$v['channel']]['remain_1']) ? $data[$v['channel']]['remain_1'] . '|' .($v['reg']>0 ? round(($data[$v['channel']]['remain_1'] / $v['reg']),2)*100 : 0) . '%': 0;
            	$v['remain_3']   = isset($data[$v['channel']]['remain_3']) ? $data[$v['channel']]['remain_3'] . '|' .($v['reg']>0 ? round(($data[$v['channel']]['remain_3'] / $v['reg']),2)*100  : 0) . '%' : 0;
            	$v['remain_7']   = isset($data[$v['channel']]['remain_7']) ? $data[$v['channel']]['remain_7'] . '|' .($v['reg']>0 ? round(($data[$v['channel']]['remain_7'] / $v['reg']),2)*100  : 0) . '%' : 0;
            	$v['remain_15']   = isset($data[$v['channel']]['remain_15']) ? $data[$v['channel']]['remain_15'] . '|' .($v['reg']>0 ? round(($data[$v['channel']]['remain_15'] / $v['reg']),2)*100  : 0) . '%' : 0;
            	$v['remain_30']   = isset($data[$v['channel']]['remain_30']) ? $data[$v['channel']]['remain_30'] . '|' .($v['reg']>0 ? round(($data[$v['channel']]['remain_30'] / $v['reg']),2)*100  : 0) . '%' : 0;
            	$v['channel']  = $this->data['channel_list'][$v['channel']]?$this->data['channel_list'][$v['channel']]:$v['channel'];
            }
            /*$serveid = $this->input->get('server_id');
            $channel = $this->input->get('channel_id');

            $this->load->model('Summary_model');
            $res = $this->Summary_model->getDataByChannel($this->appid, $date1, $date2, $serveid, $channel);
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
            //留存
            //print_r($res['remain']);exit;
 $res_pre       foreach ($res['remain'] as $item) {
                $data[$item['channel']]['remain_1'] = $item['day1'];
                $data[$item['channel']]['remain_3'] = $item['day3'];
                $data[$item['channel']]['remain_7'] = $item['day7'];
                $data[$item['channel']]['remain_15'] = $item['day15'];
                $data[$item['channel']]['remain_30'] = $item['day30'];
            }
            //注册
            foreach ($res['reg'] as $item) {
                $data[$item['channel']]['reg'] = $item['cnt'];
            }
            ksort($data);
            $output = [];
            foreach($data as $channel=>$item) {
                $output[] = array(
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
                    'remain_1'   => isset($item['remain_1']) ? $item['remain_1'] . '|' .($item['reg']>0 ? round(($item['remain_1'] / $item['reg']),2)*100 : 0) . '%': 0,
                    'remain_3'   => isset($item['remain_3']) ? $item['remain_3'] . '|' .($item['reg']>0 ? round(($item['remain_3'] / $item['reg']),2)*100  : 0) . '%' : 0,
                    'remain_7'   => isset($item['remain_7']) ? $item['remain_7'] . '|' .($item['reg']>0 ? round(($item['remain_7'] / $item['reg']),2)*100  : 0) . '%' : 0,
                    'remain_15'   => isset($item['remain_15']) ? $item['remain_15'] . '|' .($item['reg']>0 ? round(($item['remain_15'] / $item['reg']),2)*100  : 0) . '%' : 0,
                    'remain_30'   => isset($item['remain_30']) ? $item['remain_30'] . '|' .($item['reg']>0 ? round(($item['remain_30'] / $item['reg']),2)*100  : 0) . '%' : 0,
                );
            }*/
            if (!empty($output)) echo json_encode(['status'=>'ok', 'data'=>$output]);
            else echo json_encode(['status'=>'fail']);
        }
        else {
            $this->data['hide_server_list'] = true;
            $this->data['hide_end_time'] = true;
            $this->data['bt'] = date('Y-m-d', strtotime('-1 days'));
            $this->body = 'Home/summary_by_channel';
            $this->layout();
        }
        //$this->data['data'] = $data;

        //print_r($data);
    }
	
	/**
     * 渠道注册数据排行
     */
    public function ChannelRegisterRanking()
    {
        if (parent::isAjax()) {
            $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
            $date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d', strtotime('0 days'));
            $date1 = date('Ymd', strtotime($date1));
            $date2 = date('Ymd', strtotime($date2));
            $where['begindate'] = date('Ymd', strtotime($date1));
            $where['enddate'] = date('Ymd', strtotime($date2));
            $this->load->model('Mydb_sum_model');
            $output = $this->Mydb_sum_model->summarybytimechannel($where);

            foreach ($output as &$v){
            	$v['rare']   = round(($v['macregister'] / $v['device']),2)*100 . '%';
            	$v['channel']  = $this->data['channel_list'][$v['channel']]?$this->data['channel_list'][$v['channel']]:$v['channel'];
            }
            if (!empty($output)) echo json_encode(['status'=>'ok', 'data'=>$output]);
            else echo json_encode(['status'=>'fail']);
        }
        else {
			$this->data ['hide_server_list'] = true;
			$this->data ['hide_channel_list'] = true;      
            $this->data['bt'] = date('Y-m-d', strtotime('-1 days'));
            $this->body = 'Home/channel_register_ranking';
            $this->layout();
        }
    }
	
	
    public function bugReport()
    {

        $this->data['hide_channel_list'] = true;
        $this->body = 'Home/bugReport';
        $this->layout();
        //print_r($data);
    }

    /**
     * 渠道注册统计
     *
     * @return array
     */
    public function ChannelRegisterProcess()
    {
        if (parent::isAjax() || $this->input->get('debug')) {
            $date1 = $this->input->get('date1') ?$this->input->get('date1', true) :
                date('Y-m-d 00:00:00');
            $date1 = strtotime($date1);
            $date2 = strtotime("+1 day", $date1);//只统计当日的数据
            $channel = $this->input->get('channel_id');
            $this->load->model('RegFlow_model');
            $initData = [];
            $initData = $this->RegFlow_model->initData($date1,$channel, false);
            $ret = $this->RegFlow_model->RegFlow($this->appid, $date1, $date2, $channel, false);
            if ($this->input->get('debug')) {
                print_r($ret);
            }

            //var_dump($ret);
            //print_r($initData);exit;
            //print_r($ret);exit;
            //$output = [];
            if (isset($ret['register_his']) && count($ret['register_his'])) {
                foreach($ret['register_his'] as $item) {
                    $initData[$item['channel']]['register_his'] += $item['cnt'];
                }
            }

            if (isset($ret['active']) && count($ret['active'])) {
                foreach($ret['active'] as $item) {
                    $initData[$item['channel']]['active'] += $item['cnt'];
                }
            }
            if (isset($ret['device']) && count($ret['device'])) {
                foreach($ret['device'] as $item) {
                    $initData[$item['channel']]['device'] += $item['cnt'];
                }
            }

            if (isset($ret['register']) && count($ret['register'])) {
                foreach($ret['register'] as $item) {
                    $initData[$item['channel']]['reg']        += $item['cnt'];
                    if ($initData[$item['channel']]['device']>0) {
                        $initData[$item['channel']]['reg_rate'] += ($item['cnt'] / $initData[$item['channel']]['device']) * 100;
                    } else {
                        $initData[$item['channel']]['reg_rate'] += 0;
                    }
                }
            }
            if (isset($ret['role']) && count($ret['role'])) {
                foreach($ret['role'] as $item) {
                    //$m = self::MinuteLevel($item['minute']);
                    $initData[$item['channel']]['role']       += $item['cnt'];
                    if ($initData[$item['channel']]['device']>0) {
                        $initData[$item['channel']]['role_rate']   += ($item['cnt'] / $initData[$item['channel']]['device']) * 100 ;
                    }
                    else {
                        $initData[$item['channel']]['role_rate']   += 0;
                    }
                    if ($initData[$item['channel']]['reg']>0) {
                        $initData[$item['channel']]['trans_rate']  += ($item['cnt'] / $initData[$item['channel']]['reg']) * 100 ;
                    }
                    else {
                        $initData[$item['channel']]['trans_rate']  += 0;
                    }
                }
            }
            if (isset($ret['device_role']) && count($ret['device_role'])) {
                foreach($ret['device_role'] as $item) {
                    $initData[$item['channel']]['device_role']       += $item['cnt'];
                }
            }
            if (isset($ret['device_reg']) && count($ret['device_reg'])) {
                foreach($ret['device_reg'] as $item) {
                    $initData[$item['channel']]['device_reg']       += $item['cnt'];
                }
            }
            if ($this->input->get('debug')) {
                print_r($initData);
            }
            //print_r($initData);
            //exit;
            if (!empty($initData)) echo json_encode(['status'=>'ok', 'data'=>$initData, 't'=>$date1]);
            else echo json_encode(['status'=>'fail']);
        }
        else {
            $this->data['hide_end_time']= true;
            //$this->data['date_time_picker']= true;
            $this->data['hide_server_list']= true;
            $this->data['bt'] = date('Y-m-d g:0 A');
            $this->body = 'Home/channel_register_process';
            $this->layout();
        }

    }

    public function RegisterProcess()
    {
        $events = include APPPATH .'/config/event_click_config.php';
        $this->data['events'] = $events[$this->appid];
        $this->data['bt'] = date('Y-m-d');
        $this->body = 'Home/register_process';
        $this->layout();
    }

    /**
     * 数据汇总
     */
    public function getSummaryData(){}

    /**
     * Bug反馈
     */
    public function getBugReport()
    {
        $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
        $date2 = $this->input->get('date2') ?  $this->input->get('date2', true) : date('Y-m-d');
        $date1 = strtotime($date1);
        $date2 = strtotime($date2 . ' 23:59:59');
        $serveid = $this->input->get('server_id');      
        $this->load->model('BugReport_model');
        
         
        $output = $this->BugReport_model->getData($this->appid, $date1, $date2, $serveid);
        foreach($output as $key=>$item) {
            $url= $this->config->item('images_url');
            $output[$key]['date'] = date('Y-m-d H:i:s', $item['created_at']);
            if($item['imgfile']){
                $url=$url.$item['imgfile'];
                $output[$key]['flag']="<a href='javascript:showImages({$item['id']},123)'>图片</a>";
            }
            else {
                $url="";
                $output[$key]['flag']="";
            }   
            
          if(empty($item['telno'])){
          	$output[$key]['telno']=0;
          }
          if(empty($item['qq'])){
          	$output[$key]['qq']=0;
          }
          if(empty($item['bug_type'])){
          	$output[$key]['bug_type']=0;
          }
           $output[$key]['imgfile']=$url;           
          $output[$key]['text']="<a href='javascript:showdetail({$item['id']},123)'>回复</a>";
     
           
        }
        if (!empty($output)) echo json_encode(['status'=>'ok', 'data'=>$output]);
        else echo json_encode(['status'=>'fail']);
    }

    /**
     * 渠道数据分析-每小时
     */
    public function getRegFlowDataDetail()
    {
        $date1 = $this->input->get('date1');
        $date2 = strtotime("+1 day", $date1);//只统计当日的数据
        $channel = $this->input->get('channel_id');
        $this->load->model('RegFlow_model');
        $initData = [];
        $initData = $this->RegFlow_model->initData($date1, $channel, true);
        $ret = $this->RegFlow_model->RegFlow($this->appid, $date1, $date2, $channel, true);
        if (isset($ret['active']) && count($ret['active'])) {
            foreach($ret['active'] as $item) {
                $initData[$item['hour']]['active'] += $item['cnt'];
            }
        }
        if (isset($ret['device']) && count($ret['device'])) {
            foreach($ret['device'] as $item) {
                $initData[$item['hour']]['device'] += $item['cnt'];
            }
        }

        if (isset($ret['register']) && count($ret['register'])) {
            foreach($ret['register'] as $item) {
                $initData[$item['hour']]['reg']        += $item['cnt'];
                if ($initData[$item['hour']]['device']>0) {
                    $initData[$item['hour']]['reg_rate'] += @($item['cnt'] / $initData[$item['channel']]['device']) * 100;
                } else {
                    $initData[$item['hour']]['reg_rate'] += 0;
                }
            }
        }
        if (isset($ret['role']) && count($ret['role'])) {
            foreach($ret['role'] as $item) {
                $initData[$item['hour']]['role']       += $item['cnt'];
                if ($initData[$item['hour']]['device']>0) {
                    $initData[$item['hour']]['role_rate']   += ($item['cnt'] / $initData[$item['hour']]['device']) * 100 ;
                }
                else {
                    $initData[$item['hour']]['role_rate']   += 0;
                }
                if ($initData[$item['hour']]['reg']>0) {
                    $initData[$item['hour']]['trans_rate']  += ($item['cnt'] / $initData[$item['hour']]['reg']) * 100 ;
                }
                else {
                    $initData[$item['hour']]['trans_rate']  += 0;
                }
            }
        }
        ksort($initData);
        //print_r($initData);

        $this->data['channel_id']       = $this->input->get('channel_id');
        $this->data['channel_title']    = $this->input->get('t');
        $this->data['data']             = $initData;
        $this->body = 'Home/regflow_detail';
        $this->layout();
    }


    public function getRegisterProcessData()
    {
        $date = $this->input->get('date1') ?$this->input->get('date1', true) : date('Y-m-d');
        $channel_id = $this->input->get('channel_id');
        $version = $this->input->get('version');

        $date1 = strtotime($date);
        $date2 = strtotime("+1 day", $date1);//只统计当日的数据
        $typeid_list = $this->input->get('type_id');
        if (!$typeid_list) $typeid_list = [0,24];
        $typeid_list = array_unique(array_merge([0,24], $typeid_list));
        $this->load->model('RegisterProcess_model');
        $events = include APPPATH .'/config/event_click_config.php';
        //$events = $events[$this->appid];
        $header = $data = [];
        $loop_hour = $date==date('Y-m-d') ? date('G') : 24;
        foreach ($typeid_list as $type_id) {
            $header[$type_id] = $events[$this->appid][$type_id];
            for ($i=$loop_hour; $i>=0; $i--) {
                $data[$i][$type_id] =  array(
                    'cnt'   => 0,
                    'rate'  => 0,
                );
                $total[$i] = 0;
            }
        }
        $total = [];
        $output = $this->RegisterProcess_model->summary($this->appid, $date1, $date2, $typeid_list, $channel_id , $version);
        //print_r($output);exit;
        foreach ($output as $item) {
            //if ($item['type_id']==1 || $item['type_id']==2) $total[$item['hour']] += $item['cnt'];
            if ($item['type_id']==0) $total[$item['hour']] += $item['cnt'];
        }
        //print_r($total);exit;

        foreach ($output as $item) {
            $data[$item['hour']][$item['type_id']] = array(
                'cnt'   => $item['cnt'],
                'rate'  => number_format($item['cnt'] / $total[$item['hour']] * 100),
            );
        }

        krsort($data);
        $sortd = array_keys($header);
        if (!empty($output)) echo json_encode(['status'=>'ok', 'data'=>$data, 't'=>$date1, 'header'=>$header,'sort'=>$sortd]);
        else echo json_encode(['status'=>'fail']);
    }
    public function getRegisterProcessDetail()
    {
        $date1 = $this->input->get('date1');
        $date2 = strtotime("+1 day", $date1);//只统计当日的数据
        $typeid = $this->input->get('typeid');
        $this->load->model('RegisterProcess_model');
        $data = $this->RegisterProcess_model->detail($this->appid, $date1, $date2, $typeid);
        for ($i=0; $i<24; $i++) {
            $k = str_pad($i, 2, '0', STR_PAD_LEFT);
            $output[$k] = 0;
        }
        foreach ($data as $item) {
            $output[$item['hour']] = $item['cnt'];
        }
        ksort($output);
        $this->data['type_title']    = $this->input->get('t');
        $this->data['data']          = $output;
        $this->body = 'Home/register_process_detail';
        $this->layout();
    }

    public function FoolBird()
    {
        $events = include APPPATH .'/config/fool_bird.php';
        if (parent::isAjax()) {
            $date = $this->input->get('date1') ?$this->input->get('date1', true) : date('Y-m-d');
            $date2 = $this->input->get('date2') ?$this->input->get('date2', true) : date('Y-m-d');
            $channel_id = $this->input->get('channel_id');
            $typeid_list = $this->input->get('type_id');
            $date1 = strtotime($date  . ' 00:00:00');
            $date2 = strtotime($date2 . ' 23:59:59');//只统计当日的数据
            $Ym1 = date('Ym',$date1);
            $Ym2 = date('Ym',$date2);
            if($Ym1 != $Ym2){
            	echo json_encode(['status'=>'fail','info'=>'不能跨月查询']);die;
            }
            $this->load->model('SystemFunction_model');
            $this->SystemFunction_model->setAppid($this->appid);
            $data = $this->SystemFunction_model->FoolBird($date1, $date2, $channel_id, $typeid_list);
            $html = '';
            //print_r($data['data'] );
            //<td><span class="chart" data-percent="'
            //                +result.data[hour][i]['rate']+'"><span class="percent">' +
            //                '</span></span></td>
            foreach ($data['data'] as $process_index=>$item) {
                $idx = 0;
                $cnt = count($item);
                if (!isset($events[$process_index]['c'])) {
                    $events[$process_index]['c'][1] = '胜利';
                    $events[$process_index]['c'][2] = '失败';
                }
                foreach($item as $key=>$val) {
                    if ($idx == 0)  {
                        $html .= "<tr><td rowspan='{$cnt}'>[{$process_index}]{$events[$process_index]['t']}</td>";
                        $html .= "<td>[{$key}]{$events[$process_index]['c'][$key]}</td>";
                        $html .= "<td>{$val['cnt']}</td>";
                        $html .= "<td><span class=\"chart\" data-percent=\"{$val['per']}\"><span class=\"percent\"></span></span></td></tr>";
                    }
                    else {
                        $html .= "<td>[{$key}]{$events[$process_index]['c'][$key]}</td>";
                        $html .= "<td>{$val['cnt']}</td>";
                        $html .= "<td><span class=\"chart\" data-percent=\"{$val['per']}\"><span class=\"percent\"></span></span></td></tr>";
                    }
                    $idx += 1;
                }
            }
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                        'status'=> 'ok' ,
                        'data'  => $html,
                        'total' => $data['total'],
                    ])
                );
        }
        else {
            $events_fool_bird = $this->handlerFoolBirdType($events);
            $this->data['events'] = $events_fool_bird;
            $this->data['bt'] = date('Y-m-d');
            $this->data['et'] = date('Y-m-d');
            $this->body = 'Home/fool_bird';
            $this->layout();
        }
    }
    
 
    
    private function handlerFoolBirdType($data)
    {
        $output = [];
        foreach ($data as $key=>$item) {
            $output[$key] = $item['t'];
        }
        return $output;
    }
    private function MinuteLevel($minute)
    {
       return ceil($minute / 10);
    }
    public function Wait()
    {
        $this->body = 'layout/wait';
        $this->layout();
    }
    
    // 类型分类     1玩法     2付费
    public function getPlayPay()
    {
    	$type_id= include APPPATH .'/config/type_id_classify.php';
    	$id = intval($this->input->get('id'));
	    if($id==1){
	    	$data=$type_id[1];
	    } else {
	    	$data=$type_id[2];
	    }
    	
    	$this->output
    	->set_content_type('application/json')
    	->set_output(json_encode([
    			'status'=> 'ok' ,
    			'data'  => $data,    		
    	])
    			);
    
    }
    
    /**
     * 按渠道汇总数据
     */
    public function summary_by_ad()
    {
    	if (parent::isAjax()) {
    		$date1 = $this->input->get('date1');
    		$date2 = $this->input->get('date2');
    		$where['channels'] = $this->input->get('channel_id');
    		$where['begindate'] = date('Ymd', strtotime($date1));
    		$where['enddate'] = date('Ymd', strtotime($date2));
    		
    		$this->load->model('Mydb_sum_model');
    		$field = 'sday,sum(registernum) registernum,sum(usercount) usercount,sum(day1) day1,sum(day3) day3,sum(day7) day7,sum(day15) day15,sum(day30) day30';
    		$group = 'sday';
    		$output = $this->Mydb_sum_model->summarybyad($where,$field,$group);
    		$this->load->model('Sdk_sum_model');
    		$output1 = $this->Sdk_sum_model->recharge($where);
    		$datarr = array();
    		foreach ($output1 as $value){
    			$datarr[$value['day']] = $value;
    		}
    		foreach ($output as &$v){
    			$v['remain_1']   = $v['day1'] . '|' .($v['registernum']>0 ? round(($v['day1'] / $v['registernum']),2)*100 : 0) . '%';
    			$v['remain_3']   = $v['day3'] . '|' .($v['registernum']>0 ? round(($v['day3'] / $v['registernum']),2)*100 : 0) . '%';
    			$v['remain_7']   = $v['day7'] . '|' .($v['registernum']>0 ? round(($v['day7'] / $v['registernum']),2)*100 : 0) . '%';
    			$v['remain_15']   = $v['day15'] . '|' .($v['registernum']>0 ? round(($v['day15'] / $v['registernum']),2)*100 : 0) . '%';
    			$v['remain_30']   = $v['day30'] . '|' .($v['registernum']>0 ? round(($v['day30'] / $v['registernum']),2)*100 : 0) . '%';
    			$v['allmoney'] = isset($datarr[$v['sday']])?$datarr[$v['sday']]['allmoney']:0;
    			$v['countAccountid'] = isset($datarr[$v['sday']])?$datarr[$v['sday']]['countAccountid']:0;
    			$v['count'] = isset($datarr[$v['sday']])?$datarr[$v['sday']]['count']:0;
    		}
    		
    		if (!empty($output)) echo json_encode(['status'=>'ok', 'data'=>$output]);
    		else echo json_encode(['status'=>'fail']);
    	}
    	else {
    		$sdk = $this->load->database('sdk',true);
    		$query = $sdk->query('SELECT distinct media_source FROM ad_register');
    		$channels = array();
    		if ($query) $channels = $query->result_array();
    		$this->data['ad_list'] = include APPPATH .'/config/ad_list.php';
    		$this->data['hide_server_list'] = true;     
    		$this->data['channels'] = $channels;
    		$this->body = 'Home/summary_by_ad';
    		$this->layout();
    	}
    }
    
    /**
     * vip人数统计
	 * @author 陈威 --20180126
     */
    public function vip()
    {
        if (parent::isAjax()) {
        	// 时间选择
        	$date1 = $this->input->get('date1');
			// 渠道选择
    		$channels = $this->input->get('channel_id');
			// 区服选择
			$serveid = $this->input->get('server_id');
			// 遍历区服
			if($serveid){
				$html .= ' and serverid in('.implode(',', $serveid).')';
			}
			
			// 遍历渠道
			if($channels){
				$html .= ' and channel in('.implode(',', $channels).')';
			}
			
			$date1 = date('Ymd', strtotime($date1));
			// 遍历渠道
			/*for($i=0;$i < count($channels);$i++){
				if($i == count($channels)-1){
					$h .= 'channel='.$channels[$i];
				}else{
					$h .= 'channel='.$channels[$i]." OR ";
				}
			}
			
			// 遍历区服
			for($i=0;$i < count($serveid);$i++){
				if($i == count($serveid)-1){
					$s .= 'serverid='.$serveid[$i];
				}else{
					$s .= 'serverid='.$serveid[$i]." OR ";
				}
			}
			
			if(count($channels)==0&&count($serveid)!=0){
				$html = 'WHERE '.$s;
			}elseif(count($serveid)==0&&count($channels)!=0){
				$html = 'WHERE '.$h;
			}elseif(count($channels)==0&&count($serveid)==0){
				$html = '';
			}else{
				$html = 'WHERE ('.$h.') AND ('.$s.')';
			}*/
			
			
			// 查询总人数
			$sql1 = "SELECT viplev,COUNT(*) zc FROM u_last_login where accountid>1000 AND 1=1 $html GROUP BY viplev";
			$query = $this->load->database ( 'sdk', TRUE )->query ($sql1);
			if ($query) $asr[1] =  $query->result_array();
			// 查询活跃人数
			$sql2 = "SELECT viplev,COUNT(*) hc FROM u_login_$date1 where accountid>1000 AND 1=1 $html GROUP BY viplev";
			$query = $this->load->database ( 'sdk', TRUE )->query ($sql2);
			if ($query) $asr[2] =  $query->result_array();
			// 查询新增人数
			$sql3 = "SELECT b.vip_level,COUNT(*) xz from (SELECT accountid,vip_level from (select accountid,vip_level from u_behavior_$date1 where accountid>1000 AND act_id=167 $html ORDER BY accountid,vip_level desc) AS a GROUP BY a.accountid ) b GROUP BY b.vip_level";
			$query = $this->load->database ( 'sdk', TRUE )->query ($sql3);
			if ($query) $asr[3] =  $query->result_array();
			$output = array();
			foreach($asr as $a)
			{
			$output[] = $a;
			}	
    		if (count($output[0])>0) echo json_encode(['status'=>'ok','data'=>$output]);
    		else echo json_encode(['status'=>'fail']);
        }
        else {
            $this->data['hide_end_time'] = true;
            $this->data['bt'] = date('Y-m-d', strtotime('-1 days'));
            $this->body = 'Home/vip';
            $this->layout();
        }
        //$this->data['data'] = $data;

        //print_r($data);
    }

    /**
     * VIP人数流失统计
	 * @author 陈威 --20180131
     */
     public function SELECT_u_last_login($where){
    		$sql = "SELECT viplev,COUNT(*) conts FROM u_last_login WHERE  1=1 AND accountid > 1000 ".$where." GROUP BY viplev";
			$query = $this->load->database ( 'sdk', TRUE )->query ($sql);
			if ($query) $row =  $query->result_array();
			return $row;
    }
    
    public function vip_loss()
    {
        if (parent::isAjax()) {
        	// 时间选择
        	$date1 = $this->input->get('date1').' 24:00:00';
			$date1 = strtotime($date1);
			
			// 渠道选择
    		$channels = $this->input->get('channel_id');
			// 区服选择
			$serveid = $this->input->get('server_id');
			// 遍历区服
			if($serveid){
				$html .= ' and serverid in('.implode(',', $serveid).')';
			}
			
			// 遍历渠道
			if($channels){
				$html .= ' and channel in('.implode(',', $channels).')';
			}
			
			$btype = $this->input->get ( 'btype' );
			
			if($btype == 0){
				// 所有的
				$output = $this->SELECT_u_last_login($html);
				if(!$output){
					echo json_encode(array('code'=>'fail','info'=>'未查到数据'));die;
				}
			}elseif($btype == 1){
				// 超过15天
				$sql = 'and '.$date1.' - last_login_time > 1296000'.$html;
				$output = $this->SELECT_u_last_login($sql);
			}elseif($btype == 2){
				// 超过30天
				$sql = 'and '.$date1.' - last_login_time > 2592000'.$html;
				$output = $this->SELECT_u_last_login($sql);
			}elseif($btype == 3){
				// 超过40天
				$sql = 'and '.$date1.' - last_login_time > 3456000'.$html;
				$output = $this->SELECT_u_last_login($sql);
			}elseif($btype == 4){
				// 超过50天
				$sql = 'and '.$date1.' - last_login_time > 4320000'.$html;
				$output = $this->SELECT_u_last_login($sql);
			}elseif($btype == 5){
				// 超过60天
				$sql = 'and '.$date1.' - last_login_time > 5184000'.$html;
				$output = $this->SELECT_u_last_login($sql);
			}
			 
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [ 
					'status' => 'ok',
					'data' => $output 
			] ) );
			
        }
        else {
            $this->data['hide_end_time'] = true;
            $this->data['bt'] = date('Y-m-d', strtotime('-1 days'));
            $this->body = 'Home/vip_loss';
            $this->layout();
        }
        //$this->data['data'] = $data;

        //print_r($data);
    }
    


}