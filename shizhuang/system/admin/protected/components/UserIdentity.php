<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	private $_id;
	private $_name;
	
	public function authenticate(){

		$userModel=Manager::model()->find('login_name=:name',array(':name'=>$this->username));
		if($userModel==null){
			$this->errorCode=self::ERROR_USERNAME_INVALID;
			return false;
		}elseif($userModel->login_pass !== md5_10($this->password)){
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
			return false;
		}/*elseif($userModel->status==0 || $userModel->status==2){
			return $userModel->status;
		}*/else{
			$this->_id=$userModel->id;
			$this->_name=$userModel->nickname;
			$this->errorCode=self::ERROR_NONE;
				
			$userModel->login_num += 1;
			$userModel->login_ip = Yii::app()->request->getUserHostAddress();
			$userModel->login_time = time();
			$userModel->update();

			return true;
		}
		
	}
	
	public function authstatus(){
		$userModel=Manager::model()->find('login_name=:name',array(':name'=>$this->username));

		if($userModel == null) {

            return false;
        }else if($userModel->login_pass === md5_10($this->password)) {

            return $userModel->status;
        } else {
            return false;
        }
	}
	
	
	public function getId(){
		return $this->_id;
	}
	
	public function getNickname(){
		return $this->_name;
	}
}