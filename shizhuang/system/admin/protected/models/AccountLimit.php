<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 账号封解日志
* ==============================================
* @date: 2016-6-3
* @author: luoxue
* @version:
*/
class AccountLimit extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{account_limit}}';
	}

	public function rules(){
		return array(
				array('operator', 'required','message'=>'操作人必须'),
				array('reason', 'required','message'=>'封解原因必须'),
				array('account', 'required','message'=>'账号ID必须'),	
				array('addtime, status, type,server_id','safe'),
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