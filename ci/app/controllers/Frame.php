<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 15/12/27
 * Time: 14:50
 */
class Frame extends CI_Controller {
	protected $json; // 接收到的数据
	protected $data; // 插入的数据
	protected $data_multi; // 插入的数据，多维数组
	
	/**
	 * 匹配时长详细
	 *
	 * @author 王涛 --20170512
	 */
	public function matchDetail() {
		if ($this::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['enddate'] = date ( 'Ymd', strtotime ( $date2 ) );
			$where ['gametype'] = $this->input->get ( 'gametype' ); // 对战类型
			$where ['dan'] = $this->input->get ( 'dan' ); // 段位
			$where ['matchtime'] = $this->input->get ( 'time' );
			$type = $this->input->get ( 'type' );
			$this->load->model ( 'GameServerData' );
			$field = 'count(*) c';
			if ($type == 1) {
				$field .= ",dan as showa";
			} else {
				$field .= ",serverid as showa";
			}
			$group = 'showa';
			$data = $this->GameServerData->match ( $where, $field, $group );
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'SystemFunction/matchdetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 行为产销vip详细
	 *
	 * @author 王涛 --20170510
	 */
	public function vipDetail() {
		if ($this::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['begintime'] = strtotime ( $date );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['typeids'] = $this->input->get ( 'type_id' );
			$where ['userid'] = $this->input->get ( 'userid' );
			$where ['type'] = $this->input->get ( 'type' );
			$where ['itemid'] = 3;
			$this->load->model ( 'SystemFunction_model' );
			$field = 'act_id,vip_level,sum(item_num) num,count(DISTINCT accountid) caccountid';
			$group = 'act_id,vip_level';
			$data = $this->SystemFunction_model->BehaviorProduceSaleNew ( $where, $field, $group );
			$vips = $newdata = $acts = array ();
			if ($data) {
				$comsume_types = include APPPATH . '/config/comsume_types.php'; // 行为
				foreach ( $data as $v ) {
					! isset ( $vips [$v ['vip_level']] ) && $vips [$v ['vip_level']] = $v ['vip_level'];
					$acts [$v ['act_id']] = $comsume_types [$v ['act_id']] ? $comsume_types [$v ['act_id']] : $v ['act_id'];
					$newdata [$v ['act_id'] . '_' . $v ['vip_level']] = $v ['num'] . '_' . $v ['caccountid'];
				}
			}
			
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $newdata,
						'vips' => $vips,
						'acts' => $acts 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'SystemFunction/vipdetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 精灵塔玩家详细
	 *
	 * @author 王涛 --20170508
	 */
	public function towerDetail() {
		if ($this::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$where ['typeids'] = [ 
					$this->input->get ( 'act_id' ) 
			];
			$where ['params'] = [ 
					$this->input->get ( 'param' ) 
			];
			$this->load->model ( 'SystemFunction_model' );
			$field = 'accountid,userid,serverid,vip_level';
			$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group, $order, 100 );
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'SystemFunction/towerdetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 关卡进度详细
	 *
	 * @author 王涛 --20170508
	 */
	public function processDetail() {
		if ($this::isAjax ()) {
			$type = $this->input->get ( "processtype" );
			$date1 = $this->input->get ( 'date1' ); // ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
			$where ['date'] = date ( 'Ymd', strtotime ( $date1 ) );
			if ($type != 2) {
				$field = "vip_level,maxGroup mg,progress_num pn,process_status ps,account_id,name,player_id,serverid";
			} else {
				$field = "vip_level,maxGroup2 mg,progress_num2 pn,process_status2 ps,account_id,name,player_id,serverid";
			}
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['outtime'] = $this->input->get ( 'lostday' ) ? $this->input->get ( 'lostday' ) : 7;
			$vip_status = explode ( '-', $this->input->get ( 'vip_status' ) );
			$where ['viplev_min'] = $where ['viplev_max'] = $vip_status [0];
			$where ['ps'] = $vip_status [1];
			if ($where ['ps'] == 2) {
				unset ( $where ['ps'] );
			}
			$title = explode ( '-', $this->input->get ( 'title' ) );
			$where ['chapter_min'] = $where ['chapter_max'] = $title [0];
			$where ['pn'] = $title [1];
			$this->load->model ( 'GameServerData' );
			$data = $this->GameServerData->process ( $where, $field, $group, $order, 100 ); // 获取数据
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'GameServerData/processdetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 对战详细
	 *
	 * @author 王涛 --20170505
	 */
	public function gameDetail() {
		if ($this::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['begintime'] = date ( 'ymdHi', strtotime ( $date . ' 00:00:00' ) );
			$where ['endtime'] = date ( 'ymdHi', strtotime ( $date . ' 00:00:00' ) + 86400 );
			$this->load->model ( 'GameServerData' );
			$where ['serverids'] = [ 
					$this->input->get ( 'serverid' ) 
			];
			$where ['accountid'] = $this->input->get ( 'accountid' );
			$field = 'c.endTime,c.type,a.serverid,a.name,a.status ustatus,a.dan,a.viplevel,a.level,c.continuous,b.eudemon,b.status estatus,b.hp,b.skills1,b.skills2,b.skills3,b.skills4,
    				b.pp1,b.pp2,b.pp3,b.pp4,b.abilities,b.fruit,b.equip,b.kidney';
			$data = $this->GameServerData->worldDataNew ( $where, $field );
			if ($data) {
				$item_types = include APPPATH . '/config/item_types.php';
				$types = array (
						0 => '普通',
						1 => '练习',
						2 => '天梯普通',
						3 => '天梯神兽场',
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
				foreach ( $data as &$v ) {
					$v ['type'] = $types [$v ['type']];
					$v ['ustatus'] = $utypes [$v ['ustatus']];
					$v ['estatus'] = $etypes [$v ['estatus']];
					$v ['eudemon'] = $item_types [$v ['eudemon']] ? $item_types [$v ['eudemon']] : $v ['eudemon'];
					$v ['allpp'] = $v ['pp1'] + $v ['pp2'] + $v ['pp3'] + $v ['pp4'];
					$v ['skills1'] = ($item_types [$v ['skills1']] ? $item_types [$v ['skills1']] : $v ['skills1']) . '-' . $v ['pp1'];
					$v ['skills2'] = ($item_types [$v ['skills2']] ? $item_types [$v ['skills2']] : $v ['skills2']) . '-' . $v ['pp2'];
					$v ['skills3'] = ($item_types [$v ['skills3']] ? $item_types [$v ['skills3']] : $v ['skills3']) . '-' . $v ['pp3'];
					$v ['skills4'] = ($item_types [$v ['skills4']] ? $item_types [$v ['skills4']] : $v ['skills4']) . '-' . $v ['pp4'];
					/*
					 * $v['abilities'] = $item_types[$v['abilities']]?$item_types[$v['abilities']]:$v['abilities'];
					 * $v['fruit'] = $item_types[$v['fruit']]?$item_types[$v['fruit']]:$v['fruit'];
					 * $v['equip'] = $item_types[$v['equip']]?$item_types[$v['equip']]:$v['equip'];
					 * $v['kidney'] = $item_types[$v['kidney']]?$item_types[$v['kidney']]:$v['kidney'];
					 */
				}
			}
			
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'SystemFunction/gamedetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 精灵详细
	 *
	 * @author 王涛 --20170505
	 */
	public function eudemonDetail() {
		if ($this::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['serverids'] = [ 
					$this->input->get ( 'serverid' ) 
			];
			$where ['accountid'] = $this->input->get ( 'accountid' );
			$field = "eud,b.name,b.serverid,ex1,ex2,intilv,booklv";
			$group = "b.serverid,b.account_id,eud";
			$this->load->model ( 'GameServerData' );
			$data = $this->GameServerData->daneudemon ( $where, $field, $group );
			if ($data) {
				$itemtypes = include APPPATH . '/config/item_types.php'; // 道具
				foreach ( $data as &$v ) {
					$v ['eud'] = $itemtypes [$v ['eud']] ? $itemtypes [$v ['eud']] : $v ['eud'];
				}
			}
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'SystemFunction/eudemondetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 机型掉线情况统计
	 *
	 * @author 王涛 --20170426
	 */
	public function DropsmacDetail() {
		if ($this::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$where ['begintime'] = strtotime ( $date );
			$where ['endtime'] = strtotime ( $date2 ) + 86399;
			$where ['btype'] = $this->input->get ( 'btype' );
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服编号
			$field = 'client_type,count(distinct accountid) caccount';
			$group = 'client_type';
			$this->load->model ( 'GameServerData' );
			$data = $this->GameServerData->drops ( $where, $field, $group );
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'SystemFunction/dropsmacdetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 玩家掉线情况统计
	 *
	 * @author 王涛 --20170426
	 */
	public function DropsDetail() {
		if ($this::isAjax ()) {
			$btypename = array (
					1 => 'PVP练习',
					2 => 'PVP天梯',
					3 => '异步竞技场',
					4 => '社团战战斗',
					5 => '全球6v6',
					6 => '冠军之夜初赛',
					7 => '冠军之夜淘汰赛' 
			);
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$where ['begintime'] = strtotime ( $date );
			$where ['endtime'] = strtotime ( $date2 ) + 86399;
			$where ['btype'] = $this->input->get ( 'btype' );
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服编号
			$this->load->model ( 'GameServerData' );
			$data = $this->GameServerData->drops ( $where );
			foreach ( $data as &$v ) {
				$v ['btypename'] = $btypename [$v ['btype']];
				$v ['create_time'] = date ( 'Ymd H:i:s', $v ['create_time'] );
			}
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'SystemFunction/dropsdetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 段位分布玩家详细
	 *
	 * @author 王涛 --20170505
	 */
	public function danuserDetail() {
		if ($this::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
			$type = $this->input->get ( 'processtype' ); // 副本
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$where ['season'] = $this->input->get ( 'season' ); // 赛季
			$where ['ranklev'] = $this->input->get ( 'ranklev' ); // 段位
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$this->load->model ( 'GameServerData' );
			if ($type == 1) { // 普通
				$field = 'vip_level,com_ranklev ranklev,account_id,serverid,name,playerid';
			} else {
				$field = 'vip_level,elite_ranklev ranklev,account_id,serverid,name,playerid';
			}
			$group = 'serverid,account_id';
			$data = $this->GameServerData->dan ( $where, $field, $group );
			
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data,
						'allaccount' => $allcount 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'SystemFunction/danuserdetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 段位分布vip详细
	 *
	 * @author 王涛 --20170419
	 */
	public function danDetail() {
		if ($this::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
			$type = $this->input->get ( 'processtype' ); // 副本
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$where ['season'] = $this->input->get ( 'season' ); // 赛季
			$where ['ranklev'] = $this->input->get ( 'ranklev' ); // 段位
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$this->load->model ( 'GameServerData' );
			if ($type == 1) { // 普通
				$field = 'com_ranklev ranklev,count(*) caccount';
			} else {
				$field = 'elite_ranklev ranklev,count(*) caccount';
			}
			$cdata = $this->GameServerData->dan ( $where, $field, 'ranklev' );
			if ($cdata && $cdata [0] ['caccount'] > 0) {
				$allcount = $cdata [0] ['caccount'];
			} else {
				echo json_encode ( array (
						'status' => 'fail',
						'info' => '暂无数据' 
				) );
				die ();
			}
			if ($type == 1) { // 普通
				$field = 'vip_level,com_ranklev ranklev,count(*) caccount';
			} else {
				$field = 'vip_level,elite_ranklev ranklev,count(*) caccount';
			}
			$group = 'ranklev,vip_level';
			$data = $this->GameServerData->dan ( $where, $field, $group );
			foreach ( $data as &$v ) {
				$v ['rare'] = round ( $v ['caccount'] / $allcount * 100, 2 );
			}
			
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data,
						'allaccount' => $allcount 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'SystemFunction/dandetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 参与度详细
	 *
	 * @author 王涛 --20170414
	 */
	public function joinDetail() {
		if ($this::isAjax ()) {
			$date1 = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d', strtotime ( '-1 days' ) );
			$type = $this->input->get ( 'type' );
			$fg = 'vip_level';
			$day = 7; // 默认显示天数
			if ($type == 'server') {
				$fg = 'serverid';
				$day = 28;
			}
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['typeids'] = [ 
					$this->input->get ( 'act_id' ) 
			];
			$where ['param'] = $this->input->get ( 'param' );
			$this->load->model ( 'Sdk_sum_model' );
			$field = "logdate,$fg,sum(act_count) as allcount,sum(act_account) as allaccount";
			$group = "logdate,$fg";
			$d = date ( 'd', strtotime ( "$date1" ) );
			$newdata = $dates = array ();
			if ($d < $day) {
				$where ['begindate'] = date ( 'Ymd', strtotime ( "$date1 -" . ($day - 1) . " days" ) );
				$where ['enddate'] = date ( 'Ymd', strtotime ( "$date1 -$d days" ) );
				$data = $this->Sdk_sum_model->sumJoin ( $where, $field, $group );
				foreach ( $data as $v ) {
					if (! isset ( $newdata [$v [$fg]] )) {
						$newdata [$v [$fg]] [$fg] = $v [$fg];
					}
					$newdata [$v [$fg]] ['act_count_' . $v ['logdate']] = $v ['allcount'];
					$newdata [$v [$fg]] ['act_account_' . $v ['logdate']] = $v ['allaccount'];
					$dates [$v ['logdate']] = $v ['logdate'];
				}
				$where ['begindate'] = date ( 'Ym01', strtotime ( "$date1" ) );
				$where ['enddate'] = date ( 'Ymd', strtotime ( $date1 ) );
			} else {
				$where ['begindate'] = date ( 'Ymd', strtotime ( "$date1 -6 days" ) );
				$where ['enddate'] = date ( 'Ymd', strtotime ( $date1 ) );
			}
			$data = $this->Sdk_sum_model->sumJoin ( $where, $field, $group );
			foreach ( $data as $v ) {
				if (! isset ( $newdata [$v [$fg]] )) {
					$newdata [$v [$fg]] [$fg] = $v [$fg];
				}
				$newdata [$v [$fg]] ['act_count_' . $v ['logdate']] = $v ['allcount'];
				$newdata [$v [$fg]] ['act_account_' . $v ['logdate']] = $v ['allaccount'];
				$dates [$v ['logdate']] = $v ['logdate'];
			}
			ksort ( $dates );
			if (! empty ( $newdata ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $newdata,
						'dates' => $dates 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'Home/joindetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 阵容详细
	 *
	 * @author 王涛 --20170315
	 */
	public function squadDetail() {
		if ($this::isAjax ()) {
			$where ['template_id'] = $this->input->get ( 'template_id' ); // 层数
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 道具编号
			$this->load->model ( 'GameServerData' );
			$order = 'totalpower';
			$limit = 10;
			$data = $this->GameServerData->squaddetail ( $where, $field, $group, $order, $limit );
			$items = include APPPATH . '/config/item_types.php'; // 道具字典
			foreach ( $data as &$v ) {
				$v ['eud1'] = $v ['eud_id1'] . $items [$v ['eud_id1']];
				$v ['eud2'] = $v ['eud_id2'] . $items [$v ['eud_id2']];
				$v ['eud3'] = $v ['eud_id3'] . $items [$v ['eud_id3']];
				$v ['eud4'] = $v ['eud_id4'] . $items [$v ['eud_id4']];
				$v ['eud5'] = $v ['eud_id5'] . $items [$v ['eud_id5']];
				$v ['eud6'] = $v ['eud_id6'] . $items [$v ['eud_id6']];
			}
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'SystemFunction/squaddetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 扭蛋详细
	 *
	 * @author 王涛 --20170314
	 */
	public function niuDetail() {
		if ($this::isAjax ()) {
			$field = 'vip_level,sum(if(param=1,1,0)) p1,sum(if(param=2,1,0)) p2,sum(if(param=3,1,0)) p3';
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['userid'] = $this->input->get ( 'userid' );
			$where ['typeids'] = [ 
					22 
			];
			$group = 'vip_level';
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$where ['endtime'] = strtotime ( $date . ' 23:59:59' ); // 只统计当日的数据
			$newdata = array ();
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $data,
					'name' => $name 
			] );
		} else {
			$this->body = 'Home/niudetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 钻石排名详细
	 *
	 * @author 王涛 --20170310
	 */
	public function emoneyRank() {
		if ($this::isAjax ()) {
			$where ['logdate'] = $this->input->get ( 'logdate' );
			$where ['serverid'] = $this->input->get ( 'serverid' );
			$limit = 100;
			if ($where ['serverid']) {
				$limit = 50;
			}
			$this->load->model ( 'GameEmoney_model' );
			$field = 'emoney,accountid,serverid';
			$order = 'emoney desc';
			$data = $this->GameEmoney_model->rankEmoney ( $where, $field, $group, $order, $limit );
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'Home/emoneyrank';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 金币排名详细
	 *
	 * @author 王涛 --20170310
	 */
	public function moneyRank() {
		if ($this::isAjax ()) {
			$where ['logdate'] = $this->input->get ( 'logdate' );
			$where ['serverid'] = $this->input->get ( 'serverid' );
			$limit = 100;
			if ($where ['serverid']) {
				$limit = 50;
			}
			$this->load->model ( 'GameEmoney_model' );
			$field = 'money,accountid,serverid';
			$order = 'money desc';
			$data = $this->GameEmoney_model->rankMoney ( $where, $field, $group, $order, $limit );
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'Home/moneyrank';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 剩余金币详细
	 *
	 * @author 王涛 --20170303
	 */
	public function moneyDetail() {
		if ($this::isAjax ()) {
			$where ['begindate'] = $where ['enddate'] = $this->input->get ( 'logdate' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$this->load->model ( 'GameEmoney_model' );
			$field = 'logdate,sum(money) as allemoney,serverid';
			$group = 'serverid';
			$data = $this->GameEmoney_model->serverEmoney ( $where, $field, $group );
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'Home/moneydetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 剩余钻石详细
	 *
	 * @author 王涛 --20170303
	 */
	public function emoneyDetail() {
		if ($this::isAjax ()) {
			$where ['begindate'] = $where ['enddate'] = $this->input->get ( 'logdate' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$this->load->model ( 'GameEmoney_model' );
			$field = 'logdate,sum(emoney) as allemoney,serverid';
			$group = 'serverid';
			$where ['type'] = 1;
			$data = $this->GameEmoney_model->serverEmoney ( $where, $field, $group );
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'info' => '无数据' 
				] );
		} else {
			$this->body = 'Home/emoneydetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 对比详细
	 *
	 * @author 王涛 --20170301
	 */
	public function PareDetail() {
		if ($this::isAjax ()) {
			$field = "item_id,sum(if(type=0,item_num,0)) as getnum,sum(if(type=1,item_num,0)) as consumenum";
			$types = include APPPATH . '/config/comsume_types.php'; // 统计类型字典
			$itemtypes = include APPPATH . '/config/item_types.php';
			$field .= ",act_id as showid";
			$where ['typeids'] = $this->input->get ( 'type_id' );
			$group = 'act_id,item_id';
			$order = 'act_id,item_id';
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['userid'] = $this->input->get ( 'userid' );
			$where ['itemid'] = $this->input->get ( 'itemid' );
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$where ['endtime'] = strtotime ( $date . ' 23:59:59' ); // 只统计当日的数据
			$newdata = array ();
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->BehaviorProduceSaleNew ( $where, $field, $group, $order );
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $data,
					'types' => $types,
					'itemtypes' => $itemtypes 
			] );
		} else {
			$this->body = 'SystemFunction/paredetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 活跃角色统计详细
	 *
	 * @author 王涛 --20170221
	 */
	public function ActiveDetail() {
		if ($this::isAjax ()) {
			$field = "sum(vip_role) as vip_role,serverid,SUM(dau) AS dau";
			$where ['sday'] = $this->input->get ( 'sday' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$group = 'serverid';
			$oData = array ();
			$this->load->model ( 'player_analysis_model' );
			$data = $this->player_analysis_model->getActiveDataNew ( $where, $field, $group );
			$types = include APPPATH . '/config/server_list.php'; // 统计类型字典
			if (isset ( $data [0] ['serverid'] )) {
				foreach ( $data as $_data ) {
					$oData [$_data ['serverid']] = $_data;
					$oData [$_data ['serverid']] ['servername'] = $types [$_data ['serverid']] ? $types [$_data ['serverid']] : $_data ['serverid'];
					$oData [$_data ['serverid']] ['novip'] = $_data ['dau'] - $_data ['vip_role'];
					;
					$oData [$_data ['serverid']] ['m1'] = 0;
					$oData [$_data ['serverid']] ['m2'] = 0;
					$oData [$_data ['serverid']] ['m3'] = 0;
				}
			}
			$where ['begindate'] = $where ['sday'];
			$where ['enddate'] = $where ['sday'];
			$where ['serverid'] = $server_id;
			$where ['channel'] = $channel_id;
			$field = "serverid,sum(if(online/60>5,1,0)) m1,sum(if(online/60>120,1,0)) m2,sum(if(online/60>500,1,0)) m3";
			$group = 'serverid';
			$this->load->model ( 'Online_analysis_model' );
			$result = $this->Online_analysis_model->getOnlineData ( $where, $field, $group );
			foreach ( $result as $_data ) {
				$oData [$_data ['serverid']] ['m1'] = $_data ['m1'];
				$oData [$_data ['serverid']] ['m2'] = $_data ['m2'];
				$oData [$_data ['serverid']] ['m3'] = $_data ['m3'];
			}
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $oData 
			] );
		} else {
			$this->body = 'PlayerAnalysis/activedetail';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 道具产销VIP统计详细
	 *
	 * @author 王涛 --20170220
	 */
	public function VipItemDetail() {
		if ($this::isAjax ()) {
			$show = $this->input->get ( 'show' );
			$field = "vip_level,count(distinct accountid) as cuid,item_id,sum(if(type=0,item_num,0)) as getnum,sum(if(type=1,item_num,0)) as consumenum";
			if ($show == 1) {
				$where ['typeids'] = $this->input->get ( 'type_id' );
			} else {
				$where ['typeids'] = [ 
						0 => 41 
				];
				$where ['params'] = $this->input->get ( 'type_id' );
			}
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['userid'] = $this->input->get ( 'userid' );
			$where ['itemid'] = $this->input->get ( 'itemid' );
			$where['date']= date('Ymd',strtotime($date));
			$group = 'vip_level';
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$where ['endtime'] = strtotime ( $date . ' 23:59:59' ); // 只统计当日的数据
			$newdata = array ();
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->BehaviorProduceSaleNew ( $where, $field, $group );			
			$data_vip=$this->SystemFunction_model->vipDistribution ( $where, $field, $group );		
			foreach ($data as $k=>&$v){			
				foreach ($data_vip['day0'] as $k2=>$v2){
					if($v['vip_level']==$v2['viplev']){
						$v['active']=$v2['accountid_total'];
						$v['rate']=(round($v['cuid']/$v2['accountid_total'],4)*100).'%';
					}
				}			
			}
			if (isset ( $data [0] ['item_id'] )) {
				$itemtypes = include APPPATH . '/config/item_types.php'; // 统计类型字典
				$name = $itemtypes [$data [0] ['item_id']];
			}
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $data,
					'name' => $name 
			] );
		} else {
			$this->body = 'SystemFunction/vipitemdetail';
			$this->load->view ( $this->body );
		}
	}
	
	/**
	 * 道具产销统计详细
	 *
	 * @author 王涛 --20170220
	 */
	public function ItemDetail() {
		if ($this::isAjax ()) {
			$show = $this->input->get ( 'show' );
			$field = "item_id,sum(if(type=0,item_num,0)) as getnum,sum(if(type=1,item_num,0)) as consumenum";
			if ($show == 1) {
				$types = include APPPATH . '/config/comsume_types.php'; // 统计类型字典
				$field .= ",act_id as showid";
				$where ['typeids'] = $this->input->get ( 'type_id' );
				$group = 'act_id';
			} else {
				$types = include APPPATH . '/config/activity_list.php'; // 运营类型字典
				$field .= ",param as showid";
				$where ['typeids'] = [ 
						0 => 41 
				];
				$where ['params'] = $this->input->get ( 'type_id' );
				$group = 'param';
			}
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['userid'] = $this->input->get ( 'userid' );
			$where ['itemid'] = $this->input->get ( 'itemid' );
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$where ['endtime'] = strtotime ( $date . ' 23:59:59' ); // 只统计当日的数据
			$newdata = array ();
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->BehaviorProduceSaleNew ( $where, $field, $group );
			if (isset ( $data [0] ['item_id'] )) {
				$itemtypes = include APPPATH . '/config/item_types.php';
				$name = $itemtypes [$data [0] ['item_id']];
			}
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $data,
					'types' => $types,
					'name' => $name 
			] );
		} else {
			$this->body = 'SystemFunction/itemdetail';
			$this->load->view ( $this->body );
		}
	}
	
	/*
	 * 运营道具增加区服详细 zzl 20170704
	 */
	public function areaDistribution() {
		if ($this::isAjax ()) {
			$show = $this->input->get ( 'show' );
			$field = "vip_level,count(distinct accountid) as cuid,item_id,sum(if(type=0,item_num,0)) as getnum,sum(if(type=1,item_num,0)) as consumenum,serverid";
			if ($show == 1) {
				$where ['typeids'] = $this->input->get ( 'type_id' );
			} else {
				$where ['typeids'] = [ 
						0 => 41 
				];
				$where ['params'] = $this->input->get ( 'type_id' );
			}
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['userid'] = $this->input->get ( 'userid' );
			$where ['itemid'] = $this->input->get ( 'itemid' );
			$group = 'serverid';
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$where ['endtime'] = strtotime ( $date . ' 23:59:59' ); // 只统计当日的数据
			$newdata = array ();
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->areaDistribution ( $where, $field, $group );
			if (isset ( $data [0] ['item_id'] )) {
				$itemtypes = include APPPATH . '/config/item_types.php'; // 统计类型字典
				$name = $itemtypes [$data [0] ['item_id']];
			}
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $data,
					'name' => $name 
			] );
		} else {
			$this->body = 'SystemFunction/areadistribution';
			$this->load->view ( $this->body );
		}
	}
	
	/*
	 * 运营道具增加活动档次详细 zzl 20170704
	 */
	public function levelDistribution() {
		if ($this::isAjax ()) {
			$show = $this->input->get ( 'show' );
			$field = "param1,item_id,sum(if(type=0,item_num,0)) as getnum,sum(if(type=1,item_num,0)) as consumenum,serverid";
			if ($show == 1) {
				$where ['typeids'] = $this->input->get ( 'type_id' );
			} else {
				$where ['typeids'] = [ 
						0 => 41 
				];
				$where ['params'] = $this->input->get ( 'type_id' );
			}
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['userid'] = $this->input->get ( 'userid' );
			$where ['itemid'] = $this->input->get ( 'itemid' );
			$group = 'param1';
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$where ['endtime'] = strtotime ( $date . ' 23:59:59' ); // 只统计当日的数据
			$newdata = array ();
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->levelDistribution ( $where, $field, $group );
			if (isset ( $data [0] ['item_id'] )) {
				$itemtypes = include APPPATH . '/config/item_types.php'; // 统计类型字典
				$name = $itemtypes [$data [0] ['item_id']];
			}
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $data,
					'name' => $name 
			] );
		} else {
			$this->body = 'SystemFunction/leveldistribution';
			$this->load->view ( $this->body );
		}
	}
	
	/**
	 * 行为产销统计详细
	 *
	 * @author 王涛 --20170220
	 */
	public function ActionDetail() {
		if ($this::isAjax ()) {
			$field = 'act_id,count(id) cid,count(distinct accountid) cuid,vip_level';
			$show = $this->input->get ( 'show' );
			if ($show == 1) {
				$field .= ',user_level as level';
			} else {
				$field .= ',vip_level as level';
			}
		    $date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['userid'] = $this->input->get ( 'userid' );
			$where ['act_id'] = $this->input->get ( 'act_id' );
			$where ['typeids'] = [ 
					$this->input->get ( 'act_id' ) 
			];
			$group = 'level';
			$where['date']= date('Ymd',strtotime($date));
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$where ['endtime'] = strtotime ( $date . ' 23:59:59' ); // 只统计当日的数据
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
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
			$newdata = array ();
			$this->load->model ( 'SystemFunction_model' );
			$this->load->model ( 'Sdk_sum_model' );
			$data = $this->SystemFunction_model->ActionProduceSaleByBehavior ( $where, $field, $group );	
			$data_vip=$this->SystemFunction_model->vipDistribution ( $where, $field, $group );
			
			foreach ($data as $k=>$v){			
				foreach ($data_vip['day0'] as $k2=>$v2){
					if($v['vip_level']==$v2['viplev']){
						$v['active']=$v2['accountid_total'];
						$v['rate']=(round($v['cuid']/$v2['accountid_total'],4)*100).'%';
					}
				}					
				$data_new[$k]=$v;
			}
			
			$data=array();
			$data=$data_new;
			
			if (isset ( $data [0] ['act_id'] )) {
				$types = include APPPATH . '/config/comsume_types.php'; // 统计类型字典
				$name = $types [$data [0] ['act_id']];
			}
			if ($show == 1) {
				$field = 'count(*) caccount,lev as level';
			} else {
				$field = 'count(*) caccount,viplev as level';
			}
			$logindata = $this->Sdk_sum_model->login ( $where, $field, $group );
			foreach ( $logindata as $v ) {
				$newdata [$v ['level']] = $v ['caccount'];
			}
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $data,
					'name' => $name,
					'logindata' => $newdata 
			] );
		} else {
			$this->body = 'Home/actiondetail';
			$this->load->view ( $this->body );
		}
	}
	
	/**
	 * 行为产销统计详细(多天)
	 *
	 * @author zzl --20170721
	 */
	public function ActionDetailMore() {
		if ($this::isAjax ()) {
			$field = 'act_id,count(id) cid,count(distinct accountid) cuid';
			$show = $this->input->get ( 'show' );
			if ($show == 1) {
				$field .= ',user_level as level';
			} else {
				$field .= ',vip_level as level';
			}
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['userid'] = $this->input->get ( 'userid' );
			
			$where ['typeids'] = [ 
					$this->input->get ( 'act_id' ) 
			];
			$where ['logdate'] = $this->input->get ( 'logdate' );
			
			$group = 'level';
			$date = $where ['logdate'];
			$where ['begintime'] = strtotime ( $where ['logdate'] . ' 00:00:00' );
			$where ['endtime'] = strtotime ( $where ['logdate'] . ' 23:59:59' );
			
			$newdata = array ();
			$this->load->model ( 'SystemFunction_model' );
			$this->load->model ( 'Sdk_sum_model' );
			$data = $this->SystemFunction_model->ActionProduceSaleByBehaviorMore ( $where, $field, $group );
			if (isset ( $data [0] ['act_id'] )) {
				$types = include APPPATH . '/config/comsume_types.php'; // 统计类型字典
				$name = $types [$data [0] ['act_id']];
			}
			if ($show == 1) {
				$field = 'count(*) caccount,lev as level';
			} else {
				$field = 'count(*) caccount,viplev as level';
			}
			$logindata = $this->Sdk_sum_model->login ( $where, $field, $group );
			foreach ( $logindata as $v ) {
				$newdata [$v ['level']] = $v ['caccount'];
			}
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $data,
					'name' => $name,
					'logindata' => $newdata 
			] );
		} else {
			$this->body = 'DataAnalysis/actiondetailmore';
			$this->load->view ( $this->body );
		}
	}
	/**
	 * 固定交换统计详细
	 *
	 * @author 王涛 --20170119
	 */
	public function FixchangeDetail() {
		if ($this::isAjax ()) {
			$field = 'param,ceil(user_level/10) as level,count(i.id) cid';
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['viplev_min'] = $this->input->get ( 'viplev_min' );
			$where ['viplev_max'] = $this->input->get ( 'viplev_max' );
			$where ['typeids'] = [ 
					$this->input->get ( 'act_id' ) 
			];
			$group = 'param,level';
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$where ['endtime'] = strtotime ( $date . ' 23:59:59' ); // 只统计当日的数据
			$newdata = array ();
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->BehaviorProduceSaleNew ( $where, $field, $group );
			if (isset ( $data [0] ['param'] )) {
				$items = include APPPATH . '/config/item_types.php'; // 道具字典
				foreach ( $data as $v ) {
					if (! $newdata [$v ['param']]) {
						$newdata [$v ['param']] ['param'] = $v ['param'];
						$newdata [$v ['param']] ['paramName'] = $items [$v ['param']] ? $items [$v ['param']] : $v ['param'];
						for($i = 1; $i <= 10; $i ++)
							$newdata [$v ['param']] ['level_' . $i] = 0;
					}
					if ($v ['level'] >= 10) {
						$v ['level'] = 10;
						$newdata [$v ['param']] ['level_' . $v ['level']] += $v ['cid'];
					} else {
						$newdata [$v ['param']] ['level_' . $v ['level']] = $v ['cid'];
					}
				}
			}
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $newdata 
			] );
		} else {
			$this->body = 'SystemFunction/fixchangedetail';
			$this->load->view ( $this->body );
		}
	}
	
	/*
	 * 获得分布/消耗分布
	 * @author zzl 20170629
	 *
	 */
	public function actDistribute() {
		if ($this::isAjax ()) {
			
			$field = "a.act_id,sum(b.item_num) as total_item";
			
			$where ['type'] = $this->input->get ( 'type' );
			$where ['vip_level'] = $this->input->get ( 'vip_level' );
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date = date ( 'Ymd', strtotime ( $date ) );
			$where ['date'] = $date;
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$group = 'a.act_id';
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$where ['endtime'] = strtotime ( $date . ' 23:59:59' ); // 只统计当日的数据
			
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->actDistribute ( $where, $field, $group );
			$types = include APPPATH . '/config/comsume_types.php'; // 统计类型字典
			foreach ( $data as &$v ) {
				
				foreach ( $types as $k2 => $v2 ) {
					if ($v ['act_id'] == $k2) {
						$v ['act_id'] = $v2;
					}
				}
			}
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $data 
			] );
		} else {
			$this->body = 'SystemFunction/actdistribute';
			$this->load->view ( $this->body );
		}
	}

	// 付费数据 服务器分布
	public function serverDistribute() {
		if ($this::isAjax ()) {
			$where ['day'] = $this->input->get ( 'day' );
			$json_data = $outputData = $legend = $xAxis = [ ];
			$bt = strtotime ( $this->input->get ( 'day' ) );
			$et = strtotime ( $this->input->get ( 'day' ) );
			
			$where ['begindate'] = strtotime ( $where ['day'] . '00:00:00' );
			$where ['enddate'] = strtotime ( $where ['day'] . '23:59:59' );
			$where ['appid'] = 10002;
			$this->load->model ( 'Paylog_model' );
			$tableData = $this->Paylog_model->getDataDetail ( $serverId, $channelId, $where );
			
			$this->load->model ( 'player_analysis_model' );
			$date1 = date ( 'Ymd', $bt );
			$date2 = date ( 'Ymd', $et );
			$group = "serverid";
			
			$data2 = $this->player_analysis_model->getActiveDataDetail ( 10002, $date1, $date2, $serverId, $channelId, $group );
			
			$outputData = array ();
			foreach ( $data2 as $v ) {
				$sday = date ( 'Y-m-d', strtotime ( $v ['sday'] ) );
				$outputData [$sday] ['dau'] = $v ['dau'];
			}
			
			$this->load->model ( 'Mydb_sum_model' );
			$field = 'date,sum(dau) as dau';
			$group = 'serverid';
			$where ['begindate'] = $date1;
			$where ['enddate'] = $date2;
			$output = $this->Mydb_sum_model->summarybychannel ( $where, $field, $group, 'date' );
			
			foreach ( $tableData as $k => $v ) {
				$dauSum = $outputData [$v ['day']] ['dau'];
				$day = date ( "Ymd", strtotime ( $v ['day'] ) );
				foreach ( $output as $k2 => $v2 ) {
					if ($v ['serverid'] == $v2 ['serverid']) {
						$tableData [$k] ['dau'] = $v2 ['dau'];
					}
				}
				$tableData [$k] ['arppu'] = round ( $v ['allmoney'] / $v ['countAccountid'], 2 );
				$tableData [$k] ['arpu'] = ($dauSum > 0) ? round ( $v ['allmoney'] / $dauSum, 2 ) : '--';
			}
			
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $tableData 
			] );
		} else {
			$this->body = 'SystemFunction/serverDistribute';
			$this->load->view ( $this->body );
		}
	}
	
	
	//  vip分布  zzl 2017.8.2
	public function  vipDistribution(){		
		if ($this::isAjax ()) {
			$where['date'] = $this->input->get ( 'date' ) ? $this->input->get ( 'date', true ) : date ( 'Ymd' );
			
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->vipDistribution ( $where, $field, $group );		
			
			echo json_encode ( [
					'status' => 'ok',
					'data' => $data['day0'],
					'types' => $types,
					'name' => $name
			] );
		} else {
			$this->body = 'SystemFunction/vipDistribution';
			$this->load->view ( $this->body );
		}
	
	}
	
	/*
	 *点击 区服分布  zzl   20170810
	 */
	public  function areaClickDistribution(){

		if ($this::isAjax ()) {
			$where['date'] = $this->input->get ( 'date' ) ? $this->input->get ( 'date', true ) : date ( 'Ymd' );
				
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->areaClickDistribution ( $where, $field, $group );
				
			echo json_encode ( [
					'status' => 'ok',
					'data' => $data,				
			] );
		} else {
			$this->body = 'SystemFunction/areaClickDistribution';
			$this->load->view ( $this->body );
		}	
	}
	
	
	public function danDistribution(){		


		if ($this::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			if ($date) {
				$where ['begintime'] = date ( 'ymdHi', strtotime ( $date . ' 00:00:00' ) );
			}
			if ($date2) {
				$where ['endtime'] = date ( 'ymdHi', strtotime ( $date2 . ' 00:00:00' ) + 86400 );
			}
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['type'] = $this->input->get ( 'gametype' );			
			$where['continuous'] = $this->input->get ( 'continuous' ) ? $this->input->get ( 'continuous', true ) : 1;
			$where ['dan_s'] = $this->input->get ( 'dan_s' );
			$where ['dan_e'] = $this->input->get ( 'dan_e' );
			$where ['btype'] =1;
			$group="dan";
		
			$field="dan,continuous,count(DISTINCT accountid) total";
			$this->load->model ( 'SystemFunction_model' );
			$data = $this->SystemFunction_model->danDistribution ( $where, $field, $group );
		
			echo json_encode ( [
					'status' => 'ok',
					'data' => $data,
			] );
		} else {
			$this->body = 'SystemFunction/danDistribution';
			$this->load->view ( $this->body );
		}
		
		
	}
	
	
	/*
	 *  积分消耗分布  zzl 20170908
	 */
	public function bonusDistribution(){
	
	
	    if ($this::isAjax ()) {
	        $date = $this->input->get ( 'date1' );
	        $date2 = $this->input->get ( 'date2' );
	        if ($date) {
	            $where ['begintime'] = date ( 'ymdHi', strtotime ( $date . ' 00:00:00' ) );
	        }
	        if ($date2) {
	            $where ['endtime'] = date ( 'ymdHi', strtotime ( $date2 . ' 00:00:00' ) + 86400 );
	        }
	        
	        $where ['serverids'] = $this->input->get ( 'server_id' );	 
	        $where ['vip_level'] = $this->input->get ( 'vip_level' );
	        $where ['date']= date ( 'Ymd', strtotime ( $date) );
	 
	  
	   
	
	        $field="vip_level,serverid,sum(if(type=1,item_num,0)) as consume_point";
	        $group="serverid";
	        $this->load->model ( 'SystemFunction_model' );
	        $data = $this->SystemFunction_model->bonusDistribution ( $where, $field, $group );
	
	        echo json_encode ( [
	            'status' => 'ok',
	            'data' => $data,
	        ] );
	    } else {
	        $this->body = 'SystemFunction/bonusDistribution';
	        $this->load->view ( $this->body );
	    }
	
	
	}
	
	
	public static function isAjax() {
		$r = isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) ? strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) : '';
		return $r === 'xmlhttprequest';
	}
}