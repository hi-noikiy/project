<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 时装菜单模块
* ==============================================
* @date: 2016-10-17
* @author: luoxue
* @version:
*/
class VgMenu extends CVogueActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	public function tableName(){
		return '{{vg_menu}}';
	}
	public function rules(){
		return array(
				array('name','required','message'=>'渠道名称'),
				array('layer, far_id, pos, is_show','safe')
		);
	}
	public function attributeLabels(){
		return array(
				'name'						=>'名称',
				'layer'							=>'层级',
				'far_id'						=>'所属分类',
				'pos'							=>'编号',
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
		$sql = "select id, name from {{vg_menu}}";
		$rs = $this->findAllBySql($sql);
		$arr = array();
		$arr[0] = ''; 
		if(!empty($rs)){
			foreach ($rs as $v){
				$arr[$v->id] = $v->name;
			}
		}
		return $arr;
	}
}