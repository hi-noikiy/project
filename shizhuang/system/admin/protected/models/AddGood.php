<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 物品表
* ==============================================
* @date: 2016-06-06
* @author: luoxue
* @version:
*/
class AddGood extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{add_good}}';
	}
	
	public function rules(){
		return array(
				array('game_id', 'required','message'=>'游戏ID必填！'),
                array('name', 'required','message'=>'物品名称必填！'),
				array('itemtype_id','required','message'=>'物品ID必填！'),
				array('amount_limit','required','message'=>'物品数量限制必填！'),
				array('sql_string_1, sql_string_2, sql_string_3','safe')
		);
	}
	
	
	
	public function attributeLabels(){
		return array(
			'game_id'       =>'游戏ID',
			'itemtype_id'   =>'物品ID',
            'name'          =>'物品名称',
            'amount_limit'  =>'限制数量',
		);
	}
}