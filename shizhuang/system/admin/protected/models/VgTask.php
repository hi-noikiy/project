<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 时装任务模块
* ==============================================
* @date: 2016-10-11
* @author: luoxue
* @version:
*/
class VgTask extends CVogueActiveRecord {
	
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	public function tableName(){
		return '{{vg_task}}';
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
				'sort'      					=>'Sort',
				'type'      					=>'Type',
				'name'					=>'名称',
				'name'					=>'名称',
				'subname'				=>'副标题',
				'icon'						=>'示意图名称',
				'bg_icon'				=>'场景图名称',
				'brief_info'				=>'简介',
				'bonus_exp'			=>'完成任务获得经验',
				'tips'						=>'任务提示',
				'Condition1'			=>'条件1',
				'Condition2'			=>'条件2',
				'Condition3'			=>'条件3',
				'Condition4'			=>'条件4',
				'Condition5'			=>'条件5',
				'Condition6'			=>'条件6',
				'Condition7'			=>'条件7',
				'Condition8'			=>'条件8',
				'Intersection12'		=>'1和2是否并集',
				'Intersection23'		=>'2和3是否并集',
				'Intersection34'		=>'3和4是否并集',
				'Intersection45'		=>'4和5是否并集',
				'Intersection56'		=>'5和6是否并集',
				'Intersection67'		=>'6和7是否并集',
				'Intersection78'		=>'7和8是否并集',
				'season'    				=>'季节',
				'need_tiredness'	=>'消耗体力',
				'bonus_money'		=>'完成奖励(金钱)',
				'bonus_item1'		=>'3.5星奖励(服饰)',
				'bonus_emoney4'=>'4星奖励(元宝)',
				'bonus_item2'		=>'4.5星奖励(服饰)',
				'bonus_emoney5'=>'5星奖励(元宝)',
				'time_begin'         	=>'开始时间',
				'time_vote'				=>'投票开始时间',
				'time_end'				=>'结束时间',
		);
	}
	protected function beforeSave(){
		if(parent::beforeSave()){
			if($this->isNewRecord){
				$this->state = 0;
				$this->create_time = date('ymdH', time());
				$this->vote_num = 0;
			}
			return true;
		}else
			return false;
	}
	
	
}