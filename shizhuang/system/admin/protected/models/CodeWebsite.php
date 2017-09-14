<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 官网激活码
* ==============================================
* @date: 2016-4-22
* @author: luoxue
* @version:
*/
class CodeWebsite extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{code_website}}';
	}
	
	public function rules(){
		return array(
 				array('code_id', 'required','message'=>'激活码必填！'),
 				//array('code_id', 'unique','message'=>'激活码已被占用！'),
 				array('used_type', 'required','message'=>'批次必填！'),
				array('used_time, account_id','safe')
		);
	}
	
	public function attributeLabels(){
		return array(
			'code_id'      	=>'激活码',
			'used_type'		=>'批次',
            'used_time'		=>'领取时间',
			'account_id'	=>'使用账号',
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

	public function getUsedType(){
	    $array = array();
        $sql = "select used_type from {{code_website}} GROUP by used_type;";
        $rs = $this->findAllBySql($sql);
        $array[] = '批次';
        if($rs){
            foreach ($rs as $v){
                $array[$v->used_type] = $v->used_type;
            }
        }
        return $array;
    }
	
	
}