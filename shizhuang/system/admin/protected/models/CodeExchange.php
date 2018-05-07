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
class CodeExchange extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{code_exchange}}';
	}
	
	public function rules(){
		return array(
 				//array('code_id', 'required','message'=>'激活码必填！'),
 				//array('code_id', 'unique','message'=>'激活码已被占用！'),
 				array('game_type', 'required','message'=>'游戏必填！'),	
 				array('param','required','message'=>'物品ID必填！'),
				array('time_limit, time_stamp, register_time, used_type, used, account_id, used_time_stamp, is_limit_one, number, number_used','safe')
		);
	}
	
	function attributeLabels(){
		return array(
				'code_id'      					=>'激活码',
				'game_type'      				=>'所属游戏',
				'time_stamp'   					=>'生成时间',
				'time_limit'      				=>'过期时间',
				'dwFenBaoID'                   	=>'所属平台',
				'param'							=>'掉落ID',
				'register_time'					=>'注册时间限制',
				'used_type'						=>'批次',
				'used'							=>'使用状态', //1已使用、0未使用
				'account_id'					=>'使用账号',
				'used_time_stamp'				=>'使用时间', 
				'number'						=>'兑换次数',
				'number_used'					=>'已使用次数',
				'is_limit_one'					=>'是否无限', // 0一个、1无限
		);
	}
	
	protected function beforeSave(){
		if(parent::beforeSave()){
			if($this->isNewRecord){
	
			}
			return true;
		}else
			return false;
	}
	
	
}