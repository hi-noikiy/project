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
class EmpAccount extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{emp_account}}';
	}
	
	public function rules(){
		return array(
				array('accountid', 'required','message'=>'账号ID必填！'),
				array('name','required','message'=>'名称必填！'),
				//array('','safe')
		);
	}
	
	
	
	public function attributeLabels(){
		return array(
				'accountid'      					=>'账号ID',
				'name'      			=>'员工名称',
		);
	}
	
}