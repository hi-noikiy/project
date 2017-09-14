<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* #sort=1(发型)、2(皮肤)、3(妆容)
* ==============================================
* @date: 2016-11-1
* @author: luoxue
* @version:
*/
class VgModel extends CVogueActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	public function tableName(){
		return '{{vg_model}}';
	}
	public function rules(){
		return array(
				array('icon','required','message'=>'图标必须！'),
				array('name','required','message'=>'名称必须！'),
				array('unlocklev, sort','safe')
		);
	}
	public function attributeLabels(){
		return array(
				'name'						=>'名称',
				'icon'							=>'图标',
				'sort'							=>'类型',
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