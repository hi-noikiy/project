<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	private $_identity;
	public $verify;
    public $userId;
    public $nickname;
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username', 'required','message'=>'用户名必填'),
			array('password', 'required','message'=>'密码必填'),
			array('password', 'authenticate'),
			//array('verify','captcha','message'=>'验证码错误'),
			array('login_name, login_pass, nickname', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'  =>'用户名',
			'password'  =>'密码',	
			'verify'=>'验证码',	
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate(){
		if(!$this->hasErrors()){	
			$this->_identity=new UserIdentity($this->username,$this->password);
			if($this->_identity->authstatus() === '0'){
				$this->addError('password', '帐号已被禁用!');
			} else if($this->_identity->authstatus() === false){
				$this->addError('password', '用户名或密码不存在.');
			}
		}

	}
	
	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login(){
		
		$this->_identity=new UserIdentity($this->username,$this->password);
		$this->_identity->authenticate();
		if($this->_identity===null){
			
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE){
			
			//$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity);
			$this->userId=$this->_identity->getId();
			$this->nickname=$this->_identity->getNickname();
			return true;
		}
		else
			return false;
				
	}
}
