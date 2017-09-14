<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 时装服饰模块
* ==============================================
* @date: 2016-10-17
* @author: luoxue
* @version:
*/
class VgShopitem extends CVogueActiveRecord{
	public $selected;
	public $checked;
	
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	public function tableName(){
		return '{{vg_shopitem}}';
	}
	public function rules(){
		return array(
				array('cid', 'required','message'=>'渠道编号'),
				array('name','required','message'=>'渠道名称'),
				array('status','safe')
		);
	}
	public function attributeLabels(){
		return array(
				'sort'      						=>'服饰类型',
				'sort2'      					=>'服饰类型',
				'name'						=>'服饰名称',
				'icon'							=>'图标',
				'season'						=>'发布季节',
				'brand'						=>'品牌',
				'collar'						=>'领型',
				'model'						=>'版型/款式',
				'style1'						=>'风格1',
				'style21'						=>'风格1',
				'style2'						=>'风格2',
				'style22'						=>'风格2',
				'style3'						=>'风格3',
				'style23'						=>'风格3',			
				'color1'						=>'颜色1',
				'color2'						=>'颜色2',
				'pattern1'					=>'图案1',
				'pattern2'					=>'图案2',
				'material1'					=>'材质1',
				'material2'					=>'材质2',
				'pop_element1'			=>'流行元素1',
				'pop_element2'			=>'流行元素2',
				'pop_element3'			=>'流行元素3',
				'pop_element4'    		=>'流行元素4',
				'pop_element5'			=>'流行元素5',
				'pop_element6'			=>'流行元素6',
				'pop_element7'			=>'流行元素7',
				'pop_element8'			=>'流行元素8',
				'pop_element9'			=>'流行元素9',
				'pop_element10'		=>'流行元素10',
				'price'         				=>'出售价格', //金钱
				'emoney'					=>'出售价格',//钻石
				'shelves_time'			=>'上架时间',//ymdH
		);
	}
	protected function beforeSave(){
		if(parent::beforeSave()){
			if($this->isNewRecord){
				$this->sale_status = 0;
				$this->create_time = time();
			}
			return true;
		}else
			return false;
	}
}