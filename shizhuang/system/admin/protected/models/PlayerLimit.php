<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 角色封解封解日志
* ==============================================
* @date: 2016-10-24
* @author: luoxue
* @version:
*/
class PlayerLimit extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{player_limit}}';
	}

	public function rules(){
		return array(
				array('operator', 'required','message'=>'操作人必须'),
				array('reason', 'required','message'=>'封解原因必须'),
				array('server_id', 'required','message'=>'服务器ID必须'),	
				array('player_id', 'required','message'=>'角色ID必须'),
				array('player_name', 'required','message'=>'角色名必须'),
			
				array('addtime,status,type','safe'),
		);
	}

	public function attributeLabels(){
		return array(
				
		);
	}
	protected function beforeSave(){
		if(parent::beforeSave()){
			if($this->isNewRecord){
				$this->addtime = date('Y-m-d H:i:s');
			}
			return true;
		}else
			return false;
	}
}