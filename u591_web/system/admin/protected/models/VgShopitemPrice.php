<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 服饰定价配置模块
* ==============================================
* @date: 2016-10-17
* @author: luoxue
* @version:
*/
class VgShopitemPrice extends CVogueActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	public function tableName(){
		return '{{vg_shopitem_price}}';
	}
	public function rules(){
		return array(
				array('menuid','required','message'=>'menuID必须.'),
				array('name','required','message'=>'名称必须.'),
				array('price1, price2, level, percent','safe')
		);
	}
	public function attributeLabels(){
		return array(
				'menuid'						=>'服饰类型',
				'name'						=>'服饰名称',
				'price1'						=>'价格', //起始
				'price2'						=>'价格', //结束
				'level'							=>'档次',
				'percent'						=>'百分比',
			
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