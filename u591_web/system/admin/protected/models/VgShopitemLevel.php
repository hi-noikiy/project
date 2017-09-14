<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 服饰档次记录表(定价)
* ==============================================
* @date: 2016-10-23
* @author: luoxue
* @version:
*/
class VgShopitemLevel extends CVogueActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	public function tableName(){
		return '{{vg_shopitem_level}}';
	}
	public function rules(){
		return array(
				array('menuid','required','message'=>'menuID必须.'),
				array('shopitem_id','required','message'=>'服饰ID必须.'),
				array('level','required','message'=>'档次必须.'),
				array('price','required','message'=>'价格必须.'),
				array('addtime, snapicon','safe')
		);
	}
	public function attributeLabels(){
		return array(
				'menuid'						=>'服饰类型',
				'shopitem_id'			=>'服饰ID名称',
				'price'							=>'价格', 
				'level'							=>'档次',
				'snapicon'					=>'是否定价',
					
		);
	}
	protected function beforeSave(){
		if(parent::beforeSave()){
			if($this->isNewRecord){
				$this->addtime = time();
				$this->snapicon = 0;
			}
			return true;
		}else
			return false;
	}
	
	
}