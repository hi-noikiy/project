<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 服饰品牌模块
* ==============================================
* @date: 2016-10-17
* @author: luoxue
* @version:
*/
class VgBrand extends CVogueActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	public function tableName(){
		return '{{vg_brand}}';
	}
	public function rules(){
		return array(
				array('name','required','message'=>'品牌名称必须.'),
				array('shorthand, logo, photo, describe, website, shop, is_show','safe')
		);
	}
	public function attributeLabels(){
		return array(
				'name'						=>'名称',
				'shorthand'				=>'简称(大写首字母)',
				'logo'							=>'LOGO',
				'photo'						=>'宣传图',
				'describe'					=>'描述',
				'website'					=>'官网链接',
				'shop'							=>'商场链接',
				'is_show'					=>'是否显示',
		);
	}
	protected function beforeSave(){
		if(parent::beforeSave()){
			if($this->isNewRecord){
				$this->is_show = 1;
			}
			return true;
		}else
			return false;
	}
	
	public function getInfo(){
		$sql = "select id, name from {{vg_brand}}";
		$rs = $this->findAllBySql($sql);
		$arr = array();
		if(!empty($rs)){
			foreach ($rs as $v){
				$arr[$v->id] = $v->name;
			}
		}
		return $arr;
	}
}