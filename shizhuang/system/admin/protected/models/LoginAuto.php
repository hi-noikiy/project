<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 自动登录模块
* ==============================================
* @date: 2016-10-21
* @author: luoxue
* @version:
*/
class LoginAuto extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{login_auto}}';
	}
	
	public function rules(){
		return array(
				array('game_id', 'required','message'=>'gameId必填！'),
				array('channel', 'required','message'=>'渠道必填！'),
				//array('account_id', 'required','message'=>'密码必填！', 'on'=>'insert'),
				//array('repassword','compare','compareAttribute'=>'login_pass','message'=>'两次密码不一致！','on'=>'update'),
				array('account_id','required','message'=>'accountId必填！'),
				array('expired_time','required','message'=>'过期时间必填！'),
					
				array('addtime,mac','safe')
		);
	}
	
	function attributeLabels(){
		return array(
				'game_id'      	=>'游戏ID',
				'channel'      	=>'渠道',
				'account_id'    =>'账号ID',
				'expired_time'  =>'过期时间',
                'mac'           =>'设备码',
		);
	}
	
	protected function beforeSave(){
		if(parent::beforeSave()){
			if($this->isNewRecord){
				$this->addtime = time();
			}
			return true;
		}else
			return false;
	}
}