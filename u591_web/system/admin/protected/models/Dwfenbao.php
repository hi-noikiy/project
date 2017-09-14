<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 分包ID
* ==============================================
* @date: 2016-11-30
* @author: luoxue
* @version:
*/
class Dwfenbao extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{dwFenbao}}';
	}
	
	public function rules(){
		return array(
				array('fenbao_id', 'required','message'=>'分包ID必须.'),
                array('fenbao_id', 'unique','message'=>'分包ID已被占用！'),
				array('name','required','message'=>'名称必须.'),
				array('income,income_split,tariff,channel_cost,deal_date,remark','safe')
		);
	}
	public function attributeLabels(){
		return array(
				'fenbao_id'     =>'分包ID',
				'name'      	=>'名称',
                'income'        =>'流水',
                'income_split'  =>'分成比例',
                'tariff'        =>'税费',
                'channel_cost'  =>'渠道成本比率',
                'deal_date'     =>'合同时间',
                'remark'        =>'备注',
		);
	}
	public function getInfo($fenbaoId = 0){
	    $where = $fenbaoId ? "where fenbao_id in($fenbaoId)" : '';
		$sql = "select * from {{dwFenbao}} $where order by fenbao_id asc";
		$rs = $this->findAllBySql($sql);
		$arr = array();
		$arr[0] = '请选择...';
		if(!empty($rs)){
			foreach ($rs as $v){
				$arr[$v->fenbao_id] = $v->name;
			}
		}
		return $arr;
	}

	public function getLikeInfo($name = ''){
	    if(!$name) return false;
        $sql = "select * from {{dwFenbao}} where name like '$name%' order by fenbao_id asc";
        $rs = $this->findAllBySql($sql);
        $arr = array();
        if(!empty($rs)){
            foreach ($rs as $v){
                $arr[] = $v->fenbao_id;
            }
        }
        return $arr;
    }
}