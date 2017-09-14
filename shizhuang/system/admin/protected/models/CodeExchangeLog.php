<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 激活码日志
* ==============================================
* @date: 2016-4-22
* @author: luoxue
* @version:
*/
class CodeExchangeLog extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{code_exchange_log}}';
	}
	
	public function rules(){
		return array(
 				array('account_id', 'required','message'=>'账号ID必填！'),
 				array('code_id', 'required','message'=>'激活码必填！'),
 				array('user_time','required','message'=>'使用日期必填！'),
				array('','safe')
		);
	}
	
	function attributeLabels(){
		return array(
				'code_id'      					=>'激活码',
				'account_id'      				=>'账号ID',
				'user_time'   					=>'使用日期',
		);
	}
}