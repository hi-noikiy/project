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
class Channel extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{channel}}';
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
				'cid'      				=>'渠道编号',
				'name'      			=>'渠道名称',
				'status'				=>'是否显示',
		);
	}
	
	public function getInfo($cid = 0){
	    $where = ($cid != 0) ? "cid in ($cid) and status=1" : 'status=1';
		$sql = "select * from {{channel}} where $where";
		$rs = $this->findAllBySql($sql);
		$arr = array();
        $arr[0] = '充值渠道';
		if(!empty($rs)){
			foreach ($rs as $v){
				$arr[$v->cid] = $v->	name;
			}
		}
		return $arr;
	}
		
	
	public function getCid(){
		$sql = "select max(cid) as cid from {{channel}};";
		$rs = $this->findBySql($sql);
		$num = 0;
		if(!empty($rs))
			$num = $rs->cid + 1;	
		return $num;
	}
}