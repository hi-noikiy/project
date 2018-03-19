<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-12-13
 * Time: 下午8:56
 */
include 'MY_Controller.php';
ini_set ( 'memory_limit', '1024M' );
class DataAnalysis extends MY_Controller {

	/**
	 * 亲密度购买统计
	 *
	 * @author 王涛 --20170628
	 */
	public function Intimacy() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			/*
			 * $date2 = $this->input->get ( 'date1' );
			 * $where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
			 * $where ['enddate'] = date ( 'Ymd', strtotime ( $date2 ) );
			 */
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$where ['typeids'] = [
				119
			];
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
			$this->load->model ( 'GameServerData' );
			$table = 'u_behavior_' . date ( 'Ymd', strtotime ( $date ) );
			// 查询购买人数
			$field = 'vip_level,count(distinct accountid) caccount';
			$group = 'vip_level';
			$data = $this->GameServerData->DataAnalysis ( $table, $where, $field, $group );
			$newdata = array ();
			foreach ( $data as $v ) {
				$newdata [$v ['vip_level']] ['baccount'] = $v ['caccount'];
			}
			// 查询喂养次数
			$field = 'param1,vip_level,COUNT(*) as c';
			$group = 'param1,vip_level';
			$data = $this->GameServerData->DataAnalysis ( $table, $where, $field, $group );
			foreach ( $data as $v ) {
				$newdata [$v ['vip_level']] ['c' . $v ['c']] += 1;
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
			// print_r($newdata);die;

			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
				'status' => 'ok',
				'data' => $newdata
			] ) );
		} else {

			$this->body = 'DataAnalysis/intimacy';
			$this->layout ();
		}
	}
	/**
	 * 创世徽章持有/消耗货币统计
	 *
	 * @author 王涛 --20170531
	 */
	public function Stoneresume() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date1' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			$where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['enddate'] = date ( 'Ymd', strtotime ( $date2 ) );
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
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
			$this->load->model ( 'GameServerData' );
			$field = 'vip_level,count(distinct account_id) caccount,round(avg(currency32)) ihave';
			$group = 'vip_level';
			$table = 'game_currency_' . date ( 'Ym', strtotime ( $date ) );
			$data = $this->GameServerData->DataAnalysis ( $table, $where, $field, $group );

			$newdata = array ();
			foreach ( $data as $v ) {
				$newdata [$v ['vip_level']] ['account'] = $v ['caccount'];
				$newdata [$v ['vip_level']] ['ihave'] = $v ['ihave'];
				$newdata [$v ['vip_level']] ['iresume'] = 0;
				$newdata [$v ['vip_level']] ['iget'] = 0;
			}
			$where ['itemid'] = 10032;
			$where ['begintime'] = strtotime ( $where ['begindate'] );
			$field = 'vip_level,sum(item_num) as sumitem';
			$where ['endtime'] = strtotime ( $date . ' 23:59:59' ); // 只统计当日的数据
			$this->load->model ( 'SystemFunction_model' );
			$where ['type'] = 1; // 消耗
			$consume_data = $this->SystemFunction_model->BehaviorProduceSaleNew ( $where, $field, $group );
			foreach ( $consume_data as $v ) {
				if (! isset ( $newdata [$v ['vip_level']] )) {
					$newdata [$v ['vip_level']] ['account'] = 0;
					$newdata [$v ['vip_level']] ['ihave'] = 0;
					$newdata [$v ['vip_level']] ['iget'] = 0;
				}
				$newdata [$v ['vip_level']] ['iresume'] = $v ['sumitem'];
			}
			$where ['type'] = 0; // 获取
			$get_data = $this->SystemFunction_model->BehaviorProduceSaleNew ( $where, $field, $group );
			foreach ( $get_data as $v ) {
				if (! isset ( $newdata [$v ['vip_level']] )) {
					$newdata [$v ['vip_level']] ['account'] = 0;
					$newdata [$v ['vip_level']] ['ihave'] = 0;
					$newdata [$v ['vip_level']] ['iresume'] = 0;
				}
				$newdata [$v ['vip_level']] ['iget'] = $v ['sumitem'];
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
				'status' => 'ok',
				'data' => $newdata
			] ) );
		} else {

			$this->body = 'DataAnalysis/stoneresume';
			$this->layout ();
		}
	}
	/**
	 * 创世徽章养成统计
	 *
	 * @author 王涛 --20170531
	 */
	public function Stone() {
		if (parent::isAjax ()) {
			$types = array (
				1 => '普通',
				2 => '格斗',
				3 => '飞行',
				4 => '毒系',
				5 => '地面',
				6 => '岩石',
				7 => '虫系',
				8 => '幽灵',
				9 => '钢系',
				10 => '火系',
				11 => '水系',
				12 => '草系',
				13 => '电气',
				14 => '超能',
				15 => '冰系',
				16 => '龙系',
				17 => '恶系',
				18 => '妖精'
			);
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			$where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['enddate'] = date ( 'Ymd', strtotime ( $date2 ) );
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服

			$where ['beginserver'] = $date3 ? date ( 'Ymd', strtotime ( $date3 ) ) : '';
			$where ['endserver'] = $date4 ? date ( 'Ymd', strtotime ( $date4 ) ) : '';

			$this->load->model ( 'GameServerData' );
			$field = 'stonetype,vip_level,round(avg(hp),1) hp,round(avg(attack_p),1) attack_p,round(avg(defense_p),1) defense_p,
					round(avg(attack_s),1) attack_s,round(avg(defense_s),1) defense_s,round(avg(speed),1) speed';
			$group = 'stonetype,vip_level';
			$table = 'game_stone';
			$data = $this->GameServerData->DataAnalysis ( $table, $where, $field, $group, 'stonetype' );
			$newdata = $titlearr = array ();
			foreach ( $data as $v ) {
				$newdata [$v ['stonetype']] ['hp'] [$v ['vip_level']] = $v ['hp'];
				$newdata [$v ['stonetype']] ['attack_p'] [$v ['vip_level']] = $v ['attack_p'];
				$newdata [$v ['stonetype']] ['defense_p'] [$v ['vip_level']] = $v ['defense_p'];
				$newdata [$v ['stonetype']] ['attack_s'] [$v ['vip_level']] = $v ['attack_s'];
				$newdata [$v ['stonetype']] ['defense_s'] [$v ['vip_level']] = $v ['defense_s'];
				$newdata [$v ['stonetype']] ['speed'] [$v ['vip_level']] = $v ['speed'];
			}
			foreach ( $newdata as &$v ) {
				for($i = 0; $i <= 12; $i ++) {
					! isset ( $v ['hp'] [$i] ) && $v ['hp'] [$i] = 0;
					! isset ( $v ['attack_p'] [$i] ) && $v ['attack_p'] [$i] = 0;
					! isset ( $v ['defense_p'] [$i] ) && $v ['defense_p'] [$i] = 0;
					! isset ( $v ['attack_s'] [$i] ) && $v ['attack_s'] [$i] = 0;
					! isset ( $v ['defense_s'] [$i] ) && $v ['defense_s'] [$i] = 0;
					! isset ( $v ['speed'] [$i] ) && $v ['speed'] [$i] = 0;
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
				'status' => 'ok',
				'data' => $newdata,
				'types' => $types
			] ) );
		} else {

			$this->body = 'DataAnalysis/stone';
			$this->layout ();
		}
	}
	/**
	 * 技能专精持有货币统计
	 *
	 * @author 王涛 --20170531
	 */
	public function Currency() {
		if (parent::isAjax ()) {
			$title = array (
				'currency12' => '普通精华',
				'currency13' => '格斗精华',
				'currency14' => '飞行精华',
				'currency15' => '毒系精华',
				'currency16' => '地面精华',
				'currency17' => '岩石精华',
				'currency18' => '虫系精华',
				'currency19' => '幽灵精华',
				'currency20' => '钢系精华',
				'currency21' => '火系精华',
				'currency22' => '水系精华',
				'currency23' => '草系精华',
				'currency24' => '电气精华',
				'currency25' => '超能精华',
				'currency26' => '冰系精华',
				'currency27' => '龙系精华',
				'currency28' => '恶系精华',
				'currency29' => '妖精精华',
				'currency30' => '社团贡献'
			);
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			$where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['enddate'] = date ( 'Ymd', strtotime ( $date2 ) );
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$where ['beginserver'] = $date3 ? date ( 'Ymd', strtotime ( $date3 ) ) : '';
			$where ['endserver'] = $date4 ? date ( 'Ymd', strtotime ( $date4 ) ) : '';
			$this->load->model ( 'GameServerData' );
			$field = 'vip_level,count(distinct account_id) caccount';
			for($i = 12; $i <= 30; $i ++) {
				$field .= ",round(avg(currency$i)) currency$i";
			}
			$group = 'vip_level';
			$table = 'game_currency_' . date ( 'Ym', strtotime ( $date ) );
			$data = $this->GameServerData->DataAnalysis ( $table, $where, $field, $group );
			$newdata = $titlearr = array ();
			foreach ( $data as $v ) {
				$newdata [$v ['vip_level']] = $v;
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
				'status' => 'ok',
				'data' => $newdata,
				'title' => $title
			] ) );
		} else {

			$this->body = 'DataAnalysis/currency';
			$this->layout ();
		}
	}
	/**
	 * 技能专精统计
	 *
	 * @author 王涛 --20170531
	 */
	public function Synscience() {
		if (parent::isAjax ()) {
			$title = array (
				1 => '普通专精平均等级',
				2 => '格斗专精平均等级',
				3 => '飞行专精平均等级',
				4 => '毒系专精平均等级',
				5 => '地面专精平均等级',
				6 => '岩石专精平均等级',
				7 => '虫系专精平均等级',
				8 => '幽灵专精平均等级',
				9 => '钢系专精平均等级',
				10 => '火系专精平均等级',
				11 => '水系专精平均等级',
				12 => '草系专精平均等级',
				13 => '电气专精平均等级',
				14 => '超能专精平均等级',
				15 => '冰系专精平均等级',
				16 => '龙系专精平均等级',
				17 => '恶系专精平均等级',
				18 => '妖精专精平均等级'
			);
			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			$where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['enddate'] = date ( 'Ymd', strtotime ( $date2 ) );
			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$where ['beginserver'] = $date3 ? date ( 'Ymd', strtotime ( $date3 ) ) : '';
			$where ['endserver'] = $date4 ? date ( 'Ymd', strtotime ( $date4 ) ) : '';
			$this->load->model ( 'GameServerData' );
			$field = 'group_id,vip_level,round(avg(level),1) level';
			$group = 'group_id,vip_level';
			$table = 'game_synscience_' . date ( 'Ym', strtotime ( $date ) );
			$data = $this->GameServerData->DataAnalysis ( $table, $where, $field, $group );
			$newdata = $titlearr = array ();
			foreach ( $data as $v ) {
				! isset ( $titlearr [$v ['group_id']] ) && $titlearr [$v ['group_id']] = $title [$v ['group_id']];
				$newdata [$v ['vip_level']] ['group'] [$v ['group_id']] = $v ['level'];
			}
			$field = 'vip_level,count(distinct account_id) caccount';
			$group = 'vip_level';
			$data = $this->GameServerData->DataAnalysis ( $table, $where, $field, $group );
			foreach ( $data as $v ) {
				$newdata [$v ['vip_level']] ['caccount'] = $v ['caccount'];
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
				'status' => 'ok',
				'data' => $newdata,
				'title' => $titlearr
			] ) );
		} else {

			$this->body = 'DataAnalysis/synscience';
			$this->layout ();
		}
	}

	// 技能属性精华购买统计 zzl 20170627
	public function propertyBuy() {
		if (parent::isAjax ()) {

			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';

			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
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

			$where ['begindate'] = strtotime ( $date );
			$where ['enddate'] = strtotime ( $date2 );
			$where ['act_id'] = 118;

			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['channels'] = $this->input->get ( 'channel_id' );

			$this->load->model ( 'Data_analysis_model' );
			$logininfo = $this->Data_analysis_model->viplogin ( $where );

			$this->load->model ( 'PropertyData' );

			$data = array ();
			$field = 'a.vip_level,count(DISTINCT a.accountid)  as buy_num,count(a.accountid) as total_buy_num,sum(b.item_num)  as total';
			$group = 'a.vip_level';

			$table = 'u_behavior_';

			$data = $this->PropertyData->buynumber ( $table, $where, $field, $group, '' );

			$data_new = array ();
			foreach ( $data as $k => &$v ) {

				foreach ( $logininfo ['day0'] as $k2 => $v2 ) {

					if ($v2 ['viplev'] == $v ['vip_level']) {

						$v ['active'] = $v2 ['c'];
					}
				}

				$v ['avg_money'] = intval ( $v ['total'] ) / intval ( $v ['buy_num'] );
				$v ['avg_buy'] = intval ( $v ['total_buy_num'] ) / intval ( $v ['buy_num'] );
			}

			if (! empty ( $data ))
				echo json_encode ( [
					'status' => 'ok',
					'data' => $data
				] );
			else
				echo json_encode ( [
					'status' => 'fail',
					'info' => '未查到数据.'
				] );
		} else {
			$this->data ['hide_end_time'] = true;
			$this->body = 'DataAnalysis/propertyBuy';
			$this->layout ();
		}
	}

	// 活跃玩家钻石途径 zzl 20170628
	public function diamandDistribute() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
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
			$this->load->model ( 'Data_analysis_model' );
			$logininfo = $this->Data_analysis_model->viplogin ( $where );

			$logininfo_new = $logininfo ['day0'];

			$table = '';
			$this->load->model ( 'Player_analysis_model' );
			$data = $this->Player_analysis_model->diamandDistribute ( $where );

			foreach ( $logininfo ['day0'] as $k => &$v ) {

				foreach ( $data ['type0'] as $v2 ) {
					if ($v ['viplev'] == $v2 ['vip_level']) {
						$v ['active'] = $v ['c'];
						$v ['type0'] = $v2 ['type0'];
						$v ['type1'] = $v2 ['type1'];
						$v ['serverid'] = $v2 ['serverid'];
						$v ['serverdate'] = $v2 ['serverdate'];
					}
				}

				$v ['text'] = " <a href='javascript:actdistribute({$v['viplev']},0)'>获得分布</a> <a href='javascript:actdistribute({$v['viplev']},1)'>消耗分布</a>";

				foreach ( $data ['surplus_money'] as $v4 ) {

					if ($v ['viplev'] == $v4 ['vip_level']) {

						$v ['surplus_money'] = $v4 ['surplus_money'];
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
			$this->data ['hide_end_time'] = true;
			$this->body = 'DataAnalysis/diamandDistribute';
			$this->layout ();
		}
	}

	/*
	 * 生命周期价值统计 zzl 20170630
	 */
	public function lifePeriod() {
		if (parent::isAjax ()) {

			$date1 = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';

			$where ['begindate'] = date ( 'Ymd', strtotime ( $date1 ) );
			$where ['enddate'] = date ( 'Ymd', strtotime ( $date2 ) );

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

			$where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
			$this->load->model ( 'Data_analysis_model' );
			$field = 'b.viplev,COUNT(*) as total_role';

			$group = 'viplev';
			$table = 'u_roles';

			$data = $this->Data_analysis_model->lifePeriod ( $table, $where, $field, $group, $order, $limit );

			$leave_num = $this->Data_analysis_model->wastageNum ( $table, $where, $field, $group, $order, $limit );

			$totalDay = $this->Data_analysis_model->totalDay ( $table, $where, $field, $group, $order, $limit );
			$totalPay = $this->Data_analysis_model->totalPay ( $table, $where, $field, $group, $order, $limit );

			foreach ( $data as $k => &$v ) {

				foreach ( $leave_num as $k2 => $v2 ) {
					if ($v ['viplev'] == $v2 ['viplev']) {
						$v ['leave_num'] = $v2 ['leave_num'] ? $v2 ['leave_num'] : 0;
					}
				}

				foreach ( $totalDay as $k3 => $v3 ) {
					if ($v ['viplev'] == $v3 ['viplev']) {
						$v ['total_day'] = ceil ( $v3 ['total_day'] / 86400 );
						$v ['avg_period'] = round ( $v ['total_day'] / $v ['leave_num'] );
					}
				}

				foreach ( $totalPay as $k4 => $v4 ) {
					if ($v ['viplev'] == $v4 ['viplev']) {
						$v ['total_pay'] = $v4 ['total_pay'];

						$v ['leave_percent'] = (round ( $v ['leave_num'] / $v ['total_role'], 4 ) * 100) . '%';

						$v ['avg_pay'] = round ( $v4 ['total_pay'] /  $v ['leave_num'], 1 );
					}
				}
				$v ['total_pay'] = $v ['total_pay'] ? $v ['total_pay'] : 0;
				$v ['leave_percent'] = $v ['leave_percent'] ? $v ['leave_percent'] : 0;
				$v ['avg_pay'] = $v ['avg_pay'] ? $v ['avg_pay'] : 0;
				$v ['total_pay'] = $v ['total_pay'] ? $v ['total_pay'] : 0;
				$v ['avg_period'] = $v ['avg_period'] ? $v ['avg_period'] : 0;
				$v ['total_day'] = $v ['total_day'] ? $v ['total_day'] : 0;
				$v ['leave_num'] = $v ['leave_num'] ? $v ['leave_num'] : 0;
				if($v ['avg_period']<0){$v ['avg_period']=0;}
			}

			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
				'status' => 'ok',
				'data' => $data,
				'title' => $title
			] ) );
		} else {

			$this->body = 'DataAnalysis/lifePeriod';
			$this->layout ();
		}
	}

	/*
	 * 高级波伏蕾购买统计 zzl 0712
	 */
	public function wavePurchase() {
		if (parent::isAjax ()) {

			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';

			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
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

			$where ['begindate'] = strtotime ( $date );
			$where ['enddate'] = strtotime ( $date2 );
			$where ['act_id'] = 121;

			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['channels'] = $this->input->get ( 'channel_id' );

			$this->load->model ( 'Data_analysis_model' );
			$logininfo = $this->Data_analysis_model->viplogin ( $where );

			$this->load->model ( 'Data_analysis_model' );

			$data = array ();
			$field = 'a.vip_level,count(DISTINCT a.accountid)  as buy_num,count(a.accountid) as total_buy_num,sum(b.item_num)  as total';
			$group = 'a.vip_level';

			$table = 'u_behavior_';

			$data = $this->Data_analysis_model->wavePurchase ( $table, $where, $field, $group, '' );

			$data_new = array ();
			foreach ( $data as $k => &$v ) {

				foreach ( $logininfo ['day0'] as $k2 => $v2 ) {

					if ($v2 ['viplev'] == $v ['vip_level']) {

						$v ['active'] = $v2 ['c'];
					}
				}

				$v ['avg_money'] = intval ( $v ['total'] ) / intval ( $v ['buy_num'] );
				$v ['avg_buy'] = intval ( $v ['total_buy_num'] ) / intval ( $v ['buy_num'] );
			}

			if (! empty ( $data ))
				echo json_encode ( [
					'status' => 'ok',
					'data' => $data
				] );
			else
				echo json_encode ( [
					'status' => 'fail',
					'info' => '未查到数据.'
				] );
		} else {
			$this->data ['hide_end_time'] = true;
			$this->body = 'DataAnalysis/wavePurchase';
			$this->layout ();
		}
	}
	public function spirit() {
		if (parent::isAjax ()) {

			$date = $this->input->get ( 'date1' );
			$date2 = $this->input->get ( 'date2' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			$vip = $this->input->get ( 'vip' ) ? $this->input->get ( 'vip', true ) : '';

			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
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

			$where ['vip_level'] = $vip;
			$where ['begindate'] = strtotime ( $date );
			$where ['enddate'] = strtotime ( $date2 );
			$where ['act_id'] = 121;

			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['channels'] = $this->input->get ( 'channel_id' );

			$this->load->model ( 'Data_analysis_model' );
			$logininfo = $this->Data_analysis_model->viplogin ( $where );

			$this->load->model ( 'Data_analysis_model' );

			$data = array ();
			$field = 'count(*) as count_num';
			$group = 'accountid';

			$table = 'u_behavior_';

			$data_result = $this->Data_analysis_model->spirit ( $table, $where, $field, $group, '' );

			$data_new = array ();

			foreach ( $data_result ['act_id_123'] as $k => &$v ) {

				if ($v ['count_num'] > 0 && $v ['count_num'] < 20) {

					$a ++;
				}
				if ($v ['count_num'] > 19 && $v ['count_num'] < 30) {

					$b ++;
				}
				if ($v ['count_num'] > 29 && $v ['count_num'] < 41) {

					$c ++;
				}
				if ($v ['count_num'] > 40) {

					$d ++;
				}
			}

			foreach ( $data_result ['act_117'] as $k => &$v ) {
				if ($v ['count_num'] < 20) {

					$num1 ++;
				}

				if ($v ['count_num'] >= 20 && $v ['count_num'] < 40) {

					$num20 ++;
				}

				if ($v ['count_num'] >= 40 && $v ['count_num'] < 60) {

					$num40 ++;
				}

				if ($v ['count_num'] >= 60 && $v ['count_num'] < 80) {

					$num60 ++;
				}

				if ($v ['count_num'] >= 80 && $v ['count_num'] < 100) {

					$num80 ++;
				}
				if ($v ['count_num'] >= 100) {

					$num100 ++;
				}
			}

			foreach ( $data_result ['act_113'] as $k => &$v ) {
				if ($v ['count_num'] < 20) {

					$count1 ++;
				}

				if ($v ['count_num'] >= 20 && $v ['count_num'] < 30) {

					$count20 ++;
				}

				if ($v ['count_num'] >= 40 && $v ['count_num'] < 50) {

					$count40 ++;
				}

				if ($v ['count_num'] >= 60 && $v ['count_num'] < 70) {

					$count60 ++;
				}

				if ($v ['count_num'] >= 80 && $v ['count_num'] < 100) {

					$count80 ++;
				}
				if ($v ['count_num'] >= 100 && $v ['count_num'] < 130) {

					$count100 ++;
				}
				if ($v ['count_num'] >= 130) {

					$count130 ++;
				}
			}

			$data ['a'] = $a ? $a : 0;
			$data ['b'] = $b ? $b : 0;
			$data ['c'] = $c ? $c : 0;
			$data ['d'] = $d ? $d : 0;
			$data ['total_123'] = $a + $b + $c + $d;
			$data ['total_123'] = $data ['total_123'] ? $data ['total_123'] : 0;

			$data_result ['act_id_123_param'] = $data_result ['act_id_123_param'];
			$data ['param_10001'] = $data_result ['act_id_123_param'] ['total_10001'] ? $data_result ['act_id_123_param'] ['total_10001'] : 0;
			$data ['param_10002'] = $data_result ['act_id_123_param'] ['total_10002'] ? $data_result ['act_id_123_param'] ['total_10002'] : 0;
			$data ['param_10003'] = $data_result ['act_id_123_param'] ['total_10003'] ? $data_result ['act_id_123_param'] ['total_10003'] : 0;
			$data ['param_10004'] = $data_result ['act_id_123_param'] ['total_10004'] ? $data_result ['act_id_123_param'] ['total_10004'] : 0;
			$data ['param_10005'] = $data_result ['act_id_123_param'] ['total_10005'] ? $data_result ['act_id_123_param'] ['total_10005'] : 0;
			// $data['param_total']=$data_result['act_id_123_param']['total_10001']+$data_result['act_id_123_param']['total_10002']+$data_result['act_id_123_param']['total_10003']+$data_result['act_id_123_param']['total_10004'];
			$data ['param_total'] = $data_result ['act_id_123_param'] ['param_total'];

			$data ['num20'] = $num20 ? $num20 : 0;
			$data ['num40'] = $num40 ? $num40 : 0;
			$data ['num60'] = $num60 ? $num60 : 0;
			$data ['num80'] = $num80 ? $num80 : 0;
			$data ['num100'] = $num100 ? $num100 : 0;
			$data ['num_total'] = $num1 + $num20 + $num40 + $num60 + $num80 + $num100;
			$data ['num_total'] = $data ['num_total'] ? $data ['num_total'] : 0;

			$data ['count20'] = $count20 ? $count20 : 0;
			$data ['count40'] = $count40 ? $count40 : 0;
			$data ['count60'] = $count60 ? $count60 : 0;
			$data ['count80'] = $count80 ? $count80 : 0;
			$data ['count100'] = $count100 ? $count100 : 0;
			$data ['count_total'] = $count1 + $count20 + $count40 + $count60 + $count80 + $count100 + $count130;

			if (! empty ( $data ))
				echo json_encode ( [
					'status' => 'ok',
					'data' => $data
				] );
			else
				echo json_encode ( [
					'status' => 'fail',
					'info' => '未查到数据.'
				] );
		} else {
			$this->data ['hide_end_time'] = true;
			$this->body = 'DataAnalysis/spirit';
			$this->layout ();
		}
	}

	// 行为产销统计(多天)
	public function behavior() {
		$types = include APPPATH . '/config/comsume_types.php'; // 统计类型字典
		if (parent::isAjax ()) {
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['typeids'] = $this->input->get ( 'type_id' );
			$where ['userid'] = $this->input->get ( 'userid' );
			$group = 'logdate';
			$type = $this->input->get ( 'searchtype' ); // 分类

			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$where ['begintime'] = strtotime ( $date . ' 00:00:00' );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$where ['endtime'] = strtotime ( $date2 . ' 00:00:00' );
			$channelname = '';
			$servername = '';
			if (! count ( $where ['channels'] ) || count ( $where ['channels'] ) > 1) {
				$channelname = '多个渠道';
			}
			if (! count ( $where ['serverids'] ) || count ( $where ['serverids'] ) > 1) {
				$servername = '多个区服';
			}
			if ($where ['begintime'] <= strtotime ( '-1 days' ) && empty ( $where ['channels'] ) && empty ( $where ['serverids'] ) && empty ( $where ['userid'] ) && ! ($type == 1 && count ( $where ['typeids'] ) == 1 && in_array ( $where ['typeids'] [0], array (
						1,
						41
					) ))) { // 查统计表

				$field = "logdate,typeid as act_id,sum(account_num) as caccountid,sum(consume_money) as scmoney,sum(consume_diamond) as scemoney,sum(consume_tired) as sctired,
    					sum(get_money) as sgmoney,sum(get_diamond) as sgemoney,sum(get_tired) as sgtired";
				$this->load->model ( 'Mydb_sum_model' );
				$data = $this->Mydb_sum_model->sumBehavior ( $where, $field, $group );
			} else {
				$field = 'u.act_id,u.serverid,u.channel,param,';
				$field .= "sum(if(item_id=1&&type=1,item_num,0)) as scmoney,sum(if(item_id=3&&type=1,item_num,0)) as scemoney,sum(if(item_id=2&&type=1,item_num,0)) as sctired,";
				$field .= "sum(if(item_id=1&&type=0,item_num,0)) as sgmoney,sum(if(item_id=3&&type=0,item_num,0)) as sgemoney,sum(if(item_id=2&&type=0,item_num,0)) as sgtired";
				if ($where ['userid'] && count ( $where ['serverids'] ) != 1) {
					echo json_encode ( [
						'status' => 'fail',
						'info' => '请选择一个区服'
					] );
					die ();
				}
				$actflag = 0; // 判断是否运营活动
				$this->load->model ( 'SystemFunction_model' );
				if ($type == 1 && count ( $where ['typeids'] ) == 1 && in_array ( $where ['typeids'] [0], array (
						1,
						41
					) )) { // 统计商店或者运营活动
					if ($where ['typeids'] [0] == 41) {
						$param_types = include APPPATH . '/config/activity_list.php'; // 运营类型字典
					} else {
						$param_types = include APPPATH . '/config/shop_list.php'; // 商店类型字典
					}
					$actflag = 1;
					$group = 'param';
				}
				$data = $this->SystemFunction_model->behaviorProduceSaleMore ( $where, $field, $group );
				$field = "$group,count(distinct(accountid)) as caccountid";
				$countdata = $this->SystemFunction_model->behaviorProduceSaleMore ( $where, $field, $group );
				$newcdata = array ();
				foreach ( $countdata as $v ) {
					$newcdata [$v [$group]] = $v ['caccountid'];
				}
			}
			foreach ( $data as $k => $v ) {
				if (! isset ( $data [$k] ['caccountid'] )) {
					$data [$k] ['caccountid'] = $newcdata [$v [$group]];
				}

				$data [$k] ['typename'] = $types [$v ['act_id']] ? $types [$v ['act_id']] : $v ['act_id'];
				if ($actflag) {
					$data [$k] ['typename'] = $param_types [$v ['param']] ? $param_types [$v ['param']] : $v ['param'];
				}
				$data [$k] ['servername'] = $this->data ['server_list'] [$v ['serverid']] ? $this->data ['server_list'] [$v ['serverid']] : $v ['serverid'];
				$data [$k] ['channelname'] = $this->data ['channel_list'] [$v ['channel']] ? $this->data ['channel_list'] [$v ['channel']] : $v ['channel'];
				$data [$k] ['text'] = '';

				if ($channelname) {
					$data [$k] ['channelname'] = $channelname;
				}
				if ($servername) {
					$data [$k] ['servername'] = $servername;
				}
				$data [$k] ['text'] .= "<a href='javascript:showdetail({$v['logdate']},1)'>等级详细</a> <a href='javascript:showdetail({$v['logdate']},2)'>vip等级详细</a>";
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
				'status' => 'ok',
				'data' => $data,
				'total' => count ( $data )
			] ) );
		} else {
			$this->data ['user_id_filter'] = true;
			$this->data ['hide_type_list'] = 1;
			$this->data ['hide_end_time'] = true;

			$this->data ['type_list'] = $types;
			$this->body = 'DataAnalysis/behavior';
			$this->layout ();
		}
	}

	// 创世元神养成统计 zzl 20170724
	public function genesis() {
		$types = include APPPATH . '/config/comsume_types.php'; // 统计类型字典
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['enddate'] = date ( 'Ymd', strtotime ( $date2 ) );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['type_id'] = $this->input->get ( 'type_id' );

			$this->load->model ( 'Data_analysis_model' );
			$logininfo = $this->Data_analysis_model->viplogin ( $where );

			$table = '';
			$this->load->model ( 'Data_analysis_model' );
			$data = $this->Data_analysis_model->genesis ( $where );

			foreach ( $logininfo ['day0'] as $k => &$v ) {

				$v['avg_stonestep']=0;
				$v['total_stonestep']=0;
				$v ['total']=0;
				$v ['stonestep0'] = 0;
				$v ['stonestep1'] = 0;
				$v ['stonestep2'] = 0;
				$v ['stonestep3'] =  0;
				$v ['stonestep4'] =  0;
				$v ['stonestep5'] = 0;
				$v ['stonestep6'] =  0;
				$v ['stonestep7'] = 0;
				$v ['stonestep8'] = 0;
				$v ['stonestep9'] =  0;
				$v ['stonestep10'] =  0;
				foreach ( $data as $v2 ) {
					if ($v ['viplev'] == $v2 ['vip_level']) {
						$v ['active'] = $v ['c'] ? $v ['c'] : 0;
						$v ['avg_stonestep'] = intval ( intval ( $v2 ['total_stonestep'] ) / intval ( $v2 ['total'] ) );
						$v ['avg_stonestep'] = $v ['avg_stonestep'] ? $v ['avg_stonestep'] : 0;
						$v ['total_stonestep'] = $v2 ['total_stonestep'] ? $v2 ['total_stonestep'] : 0;
						$v ['total'] = $v2 ['total'] ? $v2 ['total'] : 0;

						$v ['stonestep0'] = $v2 ['stonestep0'] ? $v2 ['stonestep0'] : 0;
						$v ['stonestep1'] = $v2 ['stonestep1'] ? $v2 ['stonestep1'] : 0;
						$v ['stonestep2'] = $v2 ['stonestep2'] ? $v2 ['stonestep2'] : 0;
						$v ['stonestep3'] = $v2 ['stonestep3'] ? $v2 ['stonestep3'] : 0;
						$v ['stonestep4'] = $v2 ['stonestep4'] ? $v2 ['stonestep4'] : 0;
						$v ['stonestep5'] = $v2 ['stonestep5'] ? $v2 ['stonestep5'] : 0;
						$v ['stonestep6'] = $v2 ['stonestep6'] ? $v2 ['stonestep5'] : 0;
						$v ['stonestep7'] = $v2 ['stonestep7'] ? $v2 ['stonestep5'] : 0;
						$v ['stonestep8'] = $v2 ['stonestep8'] ? $v2 ['stonestep8'] : 0;
						$v ['stonestep9'] = $v2 ['stonestep9'] ? $v2 ['stonestep8'] : 0;
						$v ['stonestep10'] = $v2 ['stonestep10'] ? $v2 ['stonestep8'] : 0;
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
			$this->body = 'DataAnalysis/genesis';
			$this->layout ();
		}
	}



	public function intimacyCultivate() {

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

			$this->load->model ( 'Data_analysis_model' );
			$logininfo = $this->Data_analysis_model->viplogin ( $where );

			$table = '';
			$this->load->model ( 'Data_analysis_model' );
			$data = $this->Data_analysis_model->intimacyCultivate( $where );

			foreach ( $logininfo ['day0'] as $k => &$v ) {
				$v['life_avg']=0;
				$v ['total']=0;
				$v ['attack_avg'] = 0;
				$v ['defend_avg'] = 0;
				$v ['special_attack_avg'] = 0;
				$v ['special_defend_avg'] =  0;
				$v ['speed_avg'] =  0;
				$v ['reset_num'] = 0;
				$v ['reset_cost'] =  0;
				foreach ( $data['intimacy'] as $v2 ) {
					if ($v ['viplev'] == $v2 ['vip_level']) {
						$v ['active'] = $v ['c'] ? $v ['c'] : 0;
						$v ['life_avg'] = $v2 ['life_avg']/ $v ['c'] ? round($v2 ['life_avg']/ $v ['c'],2) : 0;
						$v ['attack_avg'] = $v2 ['attack_avg']/ $v ['c'] ? round($v2 ['attack_avg']/ $v ['c'],2) : 0;
						$v ['defend_avg'] = $v2 ['defend_avg'] / $v ['c']? round($v2 ['defend_avg'] / $v ['c']): 0;
						$v ['special_attack_avg'] = $v2 ['special_attack_avg']/ $v ['c'] ? round($v2 ['special_attack_avg']/ $v ['c'],2) : 0;
						$v ['special_defend_avg'] = $v2 ['special_defend_avg']/ $v ['c'] ? round($v2 ['special_defend_avg']/ $v ['c'] ,2): 0;
						$v ['speed_avg'] = $v2 ['speed_avg'] / $v ['c']? round($v2 ['speed_avg']/ $v ['c'],2) : 0;

					}
				}

				foreach ( $data['reset_num'] as $v3 ) {
					if ($v ['viplev'] == $v3 ['vip_level']) {
						$v ['reset_num'] = $v3 ['reset_num']?$v3 ['reset_num']: 0;


					}
				}

				foreach ( $data['reset_cost'] as $v4 ) {
					if ($v ['viplev'] == $v4 ['vip_level']) {
						$v ['reset_cost'] = $v4 ['reset_cost']/$v ['reset_num']? round($v4 ['reset_cost']/$v ['reset_num'],2): 0;
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
			$this->body = 'DataAnalysis/intimacyCultivate';
			$this->layout ();
		}
	}


	public  function activityClick(){
		$types_one = include APPPATH . '/config/click_type_one.php';
		$types = include APPPATH . '/config/click_type_two.php';

		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : 0;
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : 0;
			$where['type']=$this->input->get ( 'click_type' ) ;
			$where['param']=$this->input->get ( 'click_type_two' ) ? $this->input->get ( 'click_type_two', true ) :'';


			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['enddate'] = date ( 'Ymd', strtotime ( $date2 ) );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['type_id'] = $this->input->get ( 'type_id' );
			$where ['lev_min'] = $this->input->get ( 'lev_min' )?$this->input->get ( 'lev_min' ):0;
			$where ['lev_max'] = $this->input->get ( 'lev_max' )?$this->input->get ( 'lev_max' ):12;


			$this->load->model ( 'Data_analysis_model' );
			$logininfo = $this->Data_analysis_model->viplogin ( $where );

			$table = '';
			$this->load->model ( 'Data_analysis_model' );
			$data = $this->Data_analysis_model->activityClick( $where );


			foreach ( $logininfo ['day0'] as $k => &$v ) {
				$v ['total_user'] =$v ['total_user'] ? $v ['total_user'] : 0;
				$v ['total_time'] =$v ['total_time'] ? $v ['total_time'] : 0;


				foreach ( $data as $v2 ) {
					if ($v ['viplev'] == $v2 ['viplev']) {
						$v ['active'] = $v ['c'] ? $v ['c'] : 0;
						$v ['total_user'] = $v2 ['total_user'] ? $v2 ['total_user'] : 0;
						$v ['total_time'] = $v2 ['total_time'] ? $v2 ['total_time'] : 0;
						$v ['text'] = " <a href='<a href='javascript:areaClickDistribution({$v2['viplev']},2)'>区服分布</a>";
						//$v ['text'] = " <a href='javascript:vipClickDistribution(1,1)'>vip分布</a> <a href='javascript:areaClickDistribution(1,2)'>区服分布</a>";
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
			$this->data ['click_type_one_list'] = $types_one;
			$this->data ['click_type_list'] = $types['1'];
			$this->data ['lev_filter'] = true;
			$this->data ['click_type_one'] = true;
			$this->data ['click_type_two'] = true;
			$this->data ['hide_end_time'] = true;
			$this->data ['click_type'] = true;
			$this->body = 'DataAnalysis/activityClick';
			$this->layout ();
		}


	}

	/*
	 * 洛托姆养成统计 zzl 20170811
	 */
	public function  rotomCultivate(){

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

			$this->load->model ( 'Data_analysis_model' );
			$logininfo = $this->Data_analysis_model->viplogin ( $where );

			$table = '';
			$this->load->model ( 'Data_analysis_model' );
			$data = $this->Data_analysis_model->rotomCultivate( $where );

			foreach ( $logininfo ['day0'] as $k => &$v ) {

				foreach ( $data['first'] as $v2 ) {
					if ($v ['viplev'] == $v2 ['vip_level']) {
						$v ['active'] = $v ['c'] ? $v ['c'] : 0;
						$v ['avg_grade'] = $v2['avg_grade'];
						$v ['max_grade'] = $v2['max_grade'];
					}
				}
				$v ['max_num']=0;
				foreach ( $data['second'] as $k3=>$v3 ) {
					if ($v ['viplev'] == $v3 ['vip_level']) {
						if($v['max_grade']===$v3['rotom_grade'] && $v3['rotom_grade']>0){
							$v ['max_num']++;
						}
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
			$this->body = 'DataAnalysis/rotomCultivate';
			$this->layout ();
		}
	}




	/*
	 *  二级菜单
	 */
	public function getTypeTwo(){
		$types = include APPPATH . '/config/click_type_two.php';
		$id=$_POST['id'];
		foreach ($types[$id] as $k=>$v){
			$tt .='<option value="'.$k.'">'.$v.'</option>';
		}
		echo "<option value=>请选择</option>".$tt;
	}

	/*
	 * 活跃玩家社团VIP分布  zzl 20170829
	 */
	public function  community(){


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

			$table = '';
			$field="serverid,count(if(viplev=0 and communityid>0,true,null)) as c0,count(if(viplev=0,true,null)),count(if(viplev=0,true,null)) as vc0,count(if(viplev=1 and communityid>0,true,null)) as c1,count(if(viplev=1,true,null)),count(if(viplev=1,true,null)) as vc1,count(if(viplev=2 and communityid>0,true,null)) as c2,count(if(viplev=2,true,null)),count(if(viplev=2,true,null)) as vc2,count(if(viplev=3 and communityid>0,true,null)) as c3,count(if(viplev=3,true,null)),count(if(viplev=3,true,null)) as vc3,count(if(viplev=4 and communityid>0,true,null)) as c4,count(if(viplev=4,true,null)),count(if(viplev=4,true,null)) as vc4,count(if(viplev=5 and communityid>0,true,null)) as c5,count(if(viplev=5,true,null)),count(if(viplev=5,true,null)) as vc5,count(if(viplev=6 and communityid>0,true,null)) as c6,count(if(viplev=6,true,null)),count(if(viplev=6,true,null)) as vc6,count(if(viplev=7 and communityid>0,true,null)) as c7,count(if(viplev=7,true,null)),count(if(viplev=7,true,null)) as vc7,count(if(viplev=8 and communityid>0,true,null)) as c8,count(if(viplev=8,true,null)),count(if(viplev=8,true,null)) as vc8,count(if(viplev=9 and communityid>0,true,null)) as c9,count(if(viplev=9,true,null)),count(if(viplev=9,true,null)) as vc9,count(if(viplev=10 and communityid>0,true,null)) as c10,count(if(viplev=10,true,null)),count(if(viplev=10,true,null)) as vc10,count(if(viplev=11 and communityid>0,true,null)) as c11,count(if(viplev=11,true,null)),count(if(viplev=11,true,null)) as vc11,count(if(viplev=12 and communityid>0,true,null)) as c12,count(if(viplev=12,true,null)),count(if(viplev=12,true,null)) as vc12";
			$this->load->model ( 'Data_analysis_model' );
			$data = $this->Data_analysis_model->community( $where,$field);

			if (! empty ( $data))
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
			$this->data ['type_list'] = $types;
			$this->data ['hide_channel_list'] = true;
			$this->data ['hide_end_time'] = true;
			$this->body = 'DataAnalysis/community';
			$this->layout ();
		}

	}


	/*
	 * 每个服务器的活跃VIP分布   zzl  20170901
	 */

	public function activeVip(){
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
			$where ['merge_server'] = $this->input->get ( 'merge_server' )? $this->input->get ( 'merge_server' ):0;
			
			$table = '';
			$group="serverid";
			$field="serverid,count(if(viplev=0,true,null)) as v0,count(if(viplev=1,true,null)) as v1,count(if(viplev=2,true,null)) as v2,count(if(viplev=3,true,null)) as v3,count(if(viplev=4,true,null)) as v4,count(if(viplev=5,true,null)) as v5,count(if(viplev=6,true,null)) as v6,count(if(viplev=7,true,null)) as v7,count(if(viplev=8,true,null)) as v8,count(if(viplev=9,true,null)) as v9,count(if(viplev=10,true,null)) as v10,count(if(viplev=11,true,null)) as v11,count(if(viplev=12,true,null)) as v12";
			$this->load->model ( 'Data_analysis_model' );
			$data = $this->Data_analysis_model->activeVip( $table, $where, $field, $group, $order, $limit);
			
			if($where ['merge_server']==1){
			$id_serverlist = $this->Data_analysis_model->idServerlist( $table, $where, $field, $group, $order, $limit);
			
		 foreach ($id_serverlist as $v){		 	
	
		  $idserver_list_all.=$v['idserverlist'].',';
		 	 $idserver_title_all.=$v['id'].','; 
		 }
		 $idserver_list_all=rtrim($idserver_list_all,',');
		 $idserver_title_all=rtrim($idserver_title_all,',');

			
			$idserver_list_all_new=explode(',',$idserver_list_all);
			$idserver_title_all_new=explode(',',$idserver_title_all);
			
			$data_chang=$data;
			
	   foreach ($data_chang  as $k=>$v){	 	
	 	
	 		if(in_array($v['serverid'], $idserver_list_all_new) ){ 	
	 			
	 			foreach ($id_serverlist  as $k2=>$v2){
	 				$idserverlist=explode(',',$v2['idserverlist']);
	 				
	 				if(in_array($v['serverid'], $idserverlist)){  
	 					
	 					foreach ($data_chang as $k3=>$v3){
	 						
	 						if($v2['id']==$v3['serverid']){   
	 							
	 							$data_new[$k3]['v0']+=$v3['v0'];
	 							$data_new[$k3]['v1']+=$v3['v1'];
	 							$data_new[$k3]['v2']+=$v3['v2'];
	 							$data_new[$k3]['v3']+=$v3['v3'];
	 							$data_new[$k3]['v4']+=$v3['v4'];
	 							$data_new[$k3]['v5']+=$v3['v5'];
	 							$data_new[$k3]['v6']+=$v3['v6'];
	 							$data_new[$k3]['v7']+=$v3['v7'];
	 							$data_new[$k3]['v8']+=$v3['v8'];
	 							$data_new[$k3]['v9']+=$v3['v9'];
	 							$data_new[$k3]['v10']+=$v3['v10'];
	 							$data_new[$k3]['v11']+=$v3['v11'];
	 							$data_new[$k3]['v12']+=$v3['v12'];								
	 							
	 							
	 						} 						
	 						
	 					}	 					
	 				}			
	 				
	 			}
	 			
	 			
	 		} else {
	 			
	 		$data_new[$k]=$v;
	 			
	 		}
				
				
		
		}   	
 			
		$data=$data_new;
			}

			if (! empty ( $data))
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
			
			$this->data ['merge_server'] = true;
			$this->data ['type_list'] = $types;
			$this->data ['hide_server_list'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->data ['hide_end_time'] = true;
			$this->body = 'DataAnalysis/activeVip';
			$this->layout ();
		}


	}
	/*
	 * 御魂系统统计  zzl 20180129
	 */
	public function soul() {
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
			$date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
			$date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
			$level_vip = $this->input->get ( 'level_vip' ) ? $this->input->get ( 'level_vip', true ) : '1';
			
			$where ['Ym'] = date ( 'Ym', strtotime ( $date ) );
			$where ['date'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
			$where ['enddate'] = date ( 'Ymd', strtotime ( $date2 ) );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			$where ['channels'] = $this->input->get ( 'channel_id' );
			$where ['type_id'] = $this->input->get ( 'type_id' );
			
			$table = '';
			$group = "vip_level,user_level";
		  $field = "vip_level,user_level,sum(equipsouledu_num) s1,sum(soulaverage_level) s2,sum(orangesoul_num) s3,sum(purplesoul_num) s4,sum(bluesoul_num) s5,sum(greensoul_num) s6,sum(two_suit) s7,sum(four_suit) s8";
			$this->load->model ( 'Data_analysis_model' );
			$data = $this->Data_analysis_model->soul ( $table, $where, $field, $group, $order, $limit );
			
			if ($level_vip == 1) {
				
				$data_new = array (
						array (
								'vip_level' => 0,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 1,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 2,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 3,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 4,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 5,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 6,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 7,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 8,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 9,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 10,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 11,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 12,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 13,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 14,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						),
						array (
								'vip_level' => 15,
								's1' => 0,
								's2' => 0,
								's3' => 0,
								's4' => 0,
								's5' => 0,
								's6' => 0,
								's7' => 0,
								's8' => 0 
						) 
				);
				
				foreach ( $data_new as $k => &$v ) {
					
					foreach ( $data as $k2 => $v2 ) {
						
						if ($v ['vip_level'] == $v2 ['vip_level']) {
							
							$v ['s1'] += $v2 ['s1'];
							$v ['s2'] += $v2 ['s2'];
							$v ['s3'] += $v2 ['s3'];
							$v ['s4'] += $v2 ['s4'];
							$v ['s5'] += $v2 ['s5'];
							$v ['s6'] += $v2 ['s6'];
							$v ['s7'] += $v2 ['s7'];
							$v ['s8'] += $v2 ['s8'];
						}
					}
				}
			} else {
				
				foreach ( $data as $k2 => $v2 ) {
					
					if ($v2 ['user_level'] <= 50) {
						$data_new [1] ['vip_level'] = '1-50';
						$data_new [1] ['s1'] += $v2 ['s1'];
						$data_new [1] ['s2'] += $v2 ['s2'];
						$data_new [1] ['s3'] += $v2 ['s3'];
						$data_new [1] ['s4'] += $v2 ['s4'];
						$data_new [1] ['s5'] += $v2 ['s5'];
						$data_new [1] ['s6'] += $v2 ['s6'];
						$data_new [1] ['s7'] += $v2 ['s7'];
						$data_new [1] ['s8'] += $v2 ['s8'];
					} elseif ($v2 ['user_level'] >= 51 && $v2 ['user_level'] <= 60) {
						$data_new [2] ['vip_level'] = '51-60';
						$data_new [2] ['s1'] += $v2 ['s1'];
						$data_new [2] ['s2'] += $v2 ['s2'];
						$data_new [2] ['s3'] += $v2 ['s3'];
						$data_new [2] ['s4'] += $v2 ['s4'];
						$data_new [2] ['s5'] += $v2 ['s5'];
						$data_new [2] ['s6'] += $v2 ['s6'];
						$data_new [2] ['s7'] += $v2 ['s7'];
						$data_new [2] ['s8'] += $v2 ['s8'];
					} elseif ($v2 ['user_level'] >= 61 && $v2 ['user_level'] <= 70) {
						$data_new [3] ['vip_level'] = '51-60';
						$data_new [3] ['s1'] += $v2 ['s1'];
						$data_new [3] ['s2'] += $v2 ['s2'];
						$data_new [3] ['s3'] += $v2 ['s3'];
						$data_new [3] ['s4'] += $v2 ['s4'];
						$data_new [3] ['s5'] += $v2 ['s5'];
						$data_new [3] ['s6'] += $v2 ['s6'];
						$data_new [3] ['s7'] += $v2 ['s7'];
						$data_new [3] ['s8'] += $v2 ['s8'];
					} elseif ($v2 ['user_level'] >= 71 && $v2 ['user_level'] <= 80) {
						$data_new [4] ['vip_level'] = '71-80';
						$data_new [4] ['s1'] += $v2 ['s1'];
						$data_new [4] ['s2'] += $v2 ['s2'];
						$data_new [4] ['s3'] += $v2 ['s3'];
						$data_new [4] ['s4'] += $v2 ['s4'];
						$data_new [4] ['s5'] += $v2 ['s5'];
						$data_new [4] ['s6'] += $v2 ['s6'];
						$data_new [4] ['s7'] += $v2 ['s7'];
						$data_new [4] ['s8'] += $v2 ['s8'];
					} elseif ($v2 ['user_level'] >= 81 && $v2 ['user_level'] <= 90) {
						$data_new [5] ['vip_level'] = '81-90';
						$data_new [5] ['s1'] += $v2 ['s1'];
						$data_new [5] ['s2'] += $v2 ['s2'];
						$data_new [5] ['s3'] += $v2 ['s3'];
						$data_new [5] ['s4'] += $v2 ['s4'];
						$data_new [5] ['s5'] += $v2 ['s5'];
						$data_new [5] ['s6'] += $v2 ['s6'];
						$data_new [5] ['s7'] += $v2 ['s7'];
						$data_new [5] ['s8'] += $v2 ['s8'];
					} elseif ($v2 ['user_level'] >= 91 && $v2 ['user_level'] <= 95) {
						$data_new [6] ['vip_level'] = '91-95';
						$data_new [6] ['s1'] += $v2 ['s1'];
						$data_new [6] ['s2'] += $v2 ['s2'];
						$data_new [6] ['s3'] += $v2 ['s3'];
						$data_new [6] ['s4'] += $v2 ['s4'];
						$data_new [6] ['s5'] += $v2 ['s5'];
						$data_new [6] ['s6'] += $v2 ['s6'];
						$data_new [6] ['s7'] += $v2 ['s7'];
						$data_new [6] ['s8'] += $v2 ['s8'];
					} elseif ($v2 ['user_level'] >= 96 && $v2 ['user_level'] <= 100) {
						$data_new [7] ['vip_level'] = '96-100';
						$data_new [7] ['s1'] += $v2 ['s1'];
						$data_new [7] ['s2'] += $v2 ['s2'];
						$data_new [7] ['s3'] += $v2 ['s3'];
						$data_new [7] ['s4'] += $v2 ['s4'];
						$data_new [7] ['s5'] += $v2 ['s5'];
						$data_new [7] ['s6'] += $v2 ['s6'];
						$data_new [7] ['s7'] += $v2 ['s7'];
						$data_new [7] ['s8'] += $v2 ['s8'];
					}
				}
			}
			
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data_new 
				] );
			else
				echo json_encode ( [ 
						'status' => 'fail',
						'info' => '未查到数据' 
				] );
		} else {
			
			$this->data ['level_vip'] = true;
			$this->data ['type_list'] = $types;
			// $this->data ['hide_server_list'] = true;
			$this->data ['hide_channel_list'] = true;
			$this->data ['hide_end_time'] = true;
			$this->body = 'DataAnalysis/soul';
			$this->layout ();
		}
	}
   

}