<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 游服物品补偿递增表
* ==============================================
* @date: 2016-12-2
* @author: luoxue
* @version:
*/
class IndexId extends CActiveRecord{
	protected $indexId;
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{index_id}}';
	}

	public function rules(){
		return array(
				//array('game_id','required','message'=>'游戏ID必须'),
				array('game_id','safe')
		);
	}
	public function attributeLabels(){
		return array(
				'game_id'				=>'游戏ID',
		);
	}
	
	public function getIndexId(){
		$sql = "select max(id) as indexId from {{index_id}}";
		$rs = $this->findBySql($sql);
		$num = ($rs->indexId ? $rs->indexId : 0) + 1;
		return $num;
	}
	
	public function insertIndexId($indexId){
		$rs  = $this->findByPk($indexId);
		if(!$rs) {
			$addtime = time();
			$sql = "insert into {{index_id}} (id, addtime) values ('$indexId', '$addtime')";
			$connection = Yii::app()->db;
			$command = $connection->createCommand($sql);
			$command->execute();
		}
	}
}