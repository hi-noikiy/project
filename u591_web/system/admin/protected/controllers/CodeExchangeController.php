<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 激活码
* ==============================================
* @date: 2016-4-22
* @author: luoxue
* @version:
*/
class CodeExchangeController extends Controller{
	public function _field(&$field){
		$field=array('code_id, game_type, time_stamp, time_limit, register_time, used_type, used, account_id, used_time_stamp, is_limit_one, number,number_used');
	}
	
	public function _condition(&$condition){
		
		$condition=array();
		if (!empty($_POST['code_id']))
			$condition[] = 'code_id="'.trim($_POST['code_id']).'"';
		if($this->mangerInfo['game_id'])
			$condition[] = 'game_type='.$this->mangerInfo['game_id'];
		if (!empty($_POST['used_type']))
			$condition[] = 'used_type='.intval($_POST['used_type']);	
		if (!empty($_POST['time_stamp'])){
			$bt1 = date('ymdHi', strtotime($_POST['time_stamp']));
			//$bt2 = date('ymdHi', strtotime($_POST['time_stamp']));
			$condition[] = "time_stamp='$bt1'";
		}
		if (!empty($_POST['time_limit']))
			$condition[] = 'time_limit='.strtotime($_POST['time_limit']);
		
		if(isset($_POST['used']) && $_POST['used'] != -1)
			$condition[] = 'used='.intval($_POST['used']);
        $condition['textLoad'] = array('field'=>'code_id');
	}

	public function actionAdd() {
		$model = new CodeExchange;
		$dwfenbaoList = Dwfenbao::model()->getInfo();	
		$gameList = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        $gameId = isset($_POST['CodeExchange']['game_type']) ? intval($_POST['CodeExchange']['game_type']) : $this->mangerInfo['game_id'];

		if(isset($_POST['CodeExchange'])) {
            set_time_limit(30);
			$genarate_limit = 50000;
			$num = intval($_POST['number']);
			
			$game_type = $gameId;
            $dwFenBaoID = $_POST['dwFenBaoID'];
            if(count($dwFenBaoID) > 1){
                if( false !== $key = array_search(0,$dwFenBaoID))
                    unset($dwFenBaoID[$key]);
                $type = implode(',', $dwFenBaoID);
            } else {
                $type = implode(',', $dwFenBaoID);
            }
			$param = $_POST['CodeExchange']['param'];
			if(!$game_type)
				$this->error('请选择所属游戏');
			if($num > $genarate_limit)
				$this->error('生成数量超出');
			if(!$param)
				$this->error('请填写物品ID');
			
			$time_stamp = date('ymdHi');
			$time_limit = $_POST['CodeExchange']['time_limit'] ? strtotime($_POST['CodeExchange']['time_limit']) : 0;
			$is_limit_one = $_POST['CodeExchange']['is_limit_one'];
			$number = $_POST['CodeExchange']['number'];
			
			$register_type = intval($_POST['CodeExchange']['register_type']);
			$register_time = $register_type == 1 ? date('Ymd', strtotime($_POST['CodeExchange']['register_time'])) : 0;
			
			$sn_base_codes = array (
					'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
					'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
					'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
					'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f',
					'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
					'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
					'w', 'x', 'y', 'z', '0', '1', '2', '3',
					'4', '5', '6', '7', '8', '9'
			);
			$snCodes = array();
			$len = count($sn_base_codes)-1;
			for ($k=0; $k<$num; $k++) {
				$arr = array();
				for($i=0; $i<16; $i++) {
					$arr[] = $sn_base_codes[mt_rand(0, $len)].mt_rand(0,99).microtime();
				}
				$snCodes[] = substr(md5(implode('', $arr)), 0, 16);
			}
			$used_type = $_POST['used_type'];
			if($used_type == 0){
				$sql = "SELECT MAX(used_type) as used_type from {{code_exchange}} where game_type='$game_type'";//
				$rs = $model->findBySql($sql);
					
				$used_type = !$rs['used_type'] ? $game_type*1000 : $rs['used_type'] + 1;
			}
			$sql = "insert into {{code_exchange}} (code_id, type,param, time_stamp, time_limit, game_type, register_type, register_time,dwFenBaoID, used_type, is_limit_one, number) VALUES";
			$values = '';
			foreach ($snCodes as $key => $code){
				$comma = ($key+1 == $num) ? ';' : ',';
				$values .= "('$code', 1, '$param',$time_stamp, $time_limit, $game_type, $register_type, $register_time, '$type', $used_type, $is_limit_one, $number)$comma";
			}
			$connection = Yii::app()->db;
			$command = $connection->createCommand($sql.$values);
			$result = $command->execute();

			if($result)
				$this->success('生成'.$result.'条批次为'.$used_type);
			else 
				$this->error('生成失败');	
		}
			
		$this->render('add', array('gameList'=>$gameList, 'gameId'=>$gameId,'model'=>$model,'dwfenbaoList'=>$dwfenbaoList));
	}

	public function actionCodeLog(){
	    $codeId = Yii::app()->request->getParam('codeId');
        $model = new CodeExchangeLog;
        $criteria = new CDbCriteria;

        $condition = array();
        $condition[] = "code_id='$codeId'";
        if(isset($_POST['accountId']) && !empty($_POST['accountId']))
            $condition[] = 'account_id='.intval($_POST['accountId']);
        $criteria->condition = implode(' and ', $condition);
        $count = $model->count($criteria); //统计
        $pages=new CPagination($count);
        $pages->pageSize=20;
        $pages->applyLimit($criteria);
        $criteria->limit=$pages->pageSize;
        if(isset($_POST['pagination'])){
            $pagination=$_POST['pagination'];
            $criteria->offset=($_POST['pagination']-1)*$criteria->limit;
        }else {
            $pagination=1;
            $criteria->offset=0;
        }
        //排序
        $criteria->order="id desc";
        $info=$model->findAll($criteria);
        //print_r(CJSON::encode($info));
        $this->renderPartial('codeLog', array('codeId'=>$codeId,'info'=>$info,'pages'=>$pages,'count'=>$count,'pagination'=>$pagination));
    }

}