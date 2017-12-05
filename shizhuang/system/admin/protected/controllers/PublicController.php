<?php
/**
 * 公共分类
 * 13-8-14 5:37
 */
class PublicController extends Controller{
	function init(){}
	 function actions(){
		return array(
			'captcha'=>array(
					'class'=>'CCaptchaAction',
					'width'=>80,
					'height'=>35,
					'CodeLength'=>4,
					'offset'=>'1',
			),			
		);
	}
	//登录
	function actionLogin(){
		$manager = new LoginForm();	
		if(isset($_POST['LoginForm'])){	
			$manager->attributes = $_POST['LoginForm'];
			if($manager->validate() && $manager->login()){
 				if(Yii::app()->user->name == 'admin') 
 					$_SESSION['administrator'] = true;
 				$_SESSION[RBAC::$USER_AUTH_KEY] = $manager->userId;
 				Yii::app()->session->add('nickname',$manager->nickname);
 				if(RBAC::saveAccessList())
 					$this->success('登陆成功', $this->createUrl('index/index'));
			} else 
				$this->error(CHtml::errorSummary($manager));
		}
		$this->renderPartial('login',array('manager'=>$manager));
	}
	//退出
	function actionLogout(){
		Yii::app()->user->logout();
		
		$this->display('退出成功，欢迎下次使用！', 1, $this->createUrl('public/login'));
	}
	
	public function actionAbout(){
		
		$this->renderPartial('about', array('name'=>'罗宗林', 'phone'=>'15659752905', 'email'=>'379488366@qq.com'));
	}
	
	
	//系统信息
	public function actionMain() {
		$info = array(
				'操作系统'=>PHP_OS,
				'运行环境'=>$_SERVER["SERVER_SOFTWARE"],
				'PHP运行方式'=>php_sapi_name(),
				'Yii版本'=>'v1.1.13',
				'上传附件限制'=>ini_get('upload_max_filesize'),
				'执行时间限制'=>ini_get('max_execution_time').'秒',
				'服务器时间'=>date("Y年n月j日 H:i:s"),
				'北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
				'服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
				'剩余空间'=>round((@disk_free_space(".")/(1024*1024)),2).'M',
				'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
				'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
				'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
		);
		$this->render('main',array('info'=>$info));
	}
}
