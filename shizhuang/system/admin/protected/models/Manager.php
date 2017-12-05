<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* ERP管理系统
* ==============================================
* @date: 2015-12-11
* @author: luoxue
* @version:
*/
class Manager extends CActiveRecord{
	public $repassword;
	
	
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{manager}}';
	}
	
	public function rules(){
		return array(
				array('login_name', 'required','message'=>'用户名必填！'),
				array('login_name', 'unique','message'=>'用户名已被占用！'),
				array('login_pass', 'required','message'=>'密码必填！', 'on'=>'insert'),
				//array('repassword','compare','compareAttribute'=>'login_pass','message'=>'两次密码不一致！','on'=>'update'),
				
				array('nickname','required','message'=>'昵称必填！'),
			
				array('status,login_num,login_time,reg_time,login_ip,
				        reg_ip,game_id,level,channel_id,dwFenbao,server_id','safe')
		);
	}
	
	function attributeLabels(){
		return array(
				'login_name'      		=>'用户名',
				'login_pass'      		=>'密码',
				'repassword'   			=>'确认密码',
				'nickname'      		=>'昵称',
				'game_id'				=>'游戏ID',
                'status'                =>'状态',
                'level'                 =>'erp等级',
                'channel_id'            =>'渠道',
                'dwFenbao'              =>'分包设置',
                'server_id'             =>'区服',
		);
	}
	
	protected function beforeSave(){
		if(parent::beforeSave()){
			if($this->isNewRecord){
				$this->status = 1; //0禁用、1开启
				$this->reg_time = time();
				$this->reg_ip  = Yii::app()->request->getUserHostAddress();
			}
			return true;
		}else
			return false;
	}

	public function getAllManagerInfo($status = 1){
	    $sql = "select id,login_name,nickname from web_manager where status='$status';";
        $rs = $this->findAllBySql($sql);
        return $rs;
    }
}