<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 游戏表
* ==============================================
* @date: 2016-4-22
* @author: luoxue
* @version:
*/
class Game extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{game}}';
	}
	
	public function rules(){
		return array(
				array('game_id', 'required','message'=>'游戏ID必填！'),
				array('game_name','required','message'=>'游戏名称必填！'),
				//array('','safe')
		);
	}
	
	
	
	public function attributeLabels(){
		return array(
				'game_id'      					=>'游戏ID',
				'game_name'      			=>'游戏名称',
		);
	}
	
	
	public function getGame($gameId = 0, $key = false){
		$where = $gameId ? " where game_id='$gameId'" : '';
		$sql = "select * from {{game}} $where;";
		$rs=Yii::app()->db->createCommand($sql)->query();
		$newArr = array();
		if($key)
			$newArr[0] = $key;
		if(!empty($rs)){
			foreach ($rs as $v){
				$newArr[$v['game_id']]= $v['game_name'];
			}
		}
		return $newArr;
	}
}