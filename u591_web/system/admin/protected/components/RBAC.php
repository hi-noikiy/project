<?php
class RBAC{
	// 默认认证类型 1 登录认证 2 实时认证
	static public $USER_AUTH_TYPE=1;
	// 用户认证SESSION标记
	static public $USER_AUTH_KEY='authId';
	// 默认验证数据表模型
	protected $USER_AUTH_MODEL='Manager';	
	protected $ADMIN_AUTH_KEY ='administrator';
	//模块
	static public $app;
	//控制器
	static public $model;
	//操作
	static public $action;
	
	/* function __construct($app,$model,$action){
		$this->app
	} */
	
	//用于检测用户权限的方法,并保存到Session中
	static function saveAccessList($authId=null){
		 if(null===$authId) {
		 		$authId=Yii::app()->session[self::$USER_AUTH_KEY];	
		 }
		// 如果使用普通权限模式，保存当前用户的访问权限列表
		// 对管理员开发所有权限
		//if($this->USER_AUTH_TYPE !=2 && !$_SESSION[$this->ADMIN_AUTH_KEY] )
			Yii::app()->session['_ACCESS_LIST']	=	RBAC::getAccessList($authId);
		return true;
		
	}
	
	//检查当前操作是否需要认证
	static function checkAccess($USER_AUTH_ON=true,$REQUIRE_AUTH_MODULE='',$NOT_AUTH_MODULE='Public',$REQUIRE_AUTH_ACTION='',$NOT_AUTH_ACTION=''){
		//当前模块
		$MODEL_NAME=self::$model;
		$ACTION_NAME=self::$action;
		//true开启验证，false关闭验证
		if($USER_AUTH_ON){
			if("" !=$REQUIRE_AUTH_MODULE){
				//认证的模块
				$_module['yes'] = explode(',',strtoupper($REQUIRE_AUTH_MODULE));
			}else{
				//无需认证的模块
				$_module['no']  = explode(',', strtoupper($NOT_AUTH_MODULE));
			}
			//检查当前模块是否需要认证
			if((!empty($_module['no']) && !in_array(strtoupper($MODEL_NAME),$_module['no'])) || (!empty($_module['yes']) && in_array(strtoupper($MODEL_NAME),$_module['yes']))) {
				if("" !=$REQUIRE_AUTH_ACTION){
					//需要认证的操作
					$_action['yes'] = explode(',',strtoupper($REQUIRE_AUTH_ACTION));
				}else{
					//无需认证的操作
					$_action['no'] = explode(',',strtoupper($NOT_AUTH_ACTION));
				}
				
				//检查当前操作是否需要认证
				if((!empty($_action['no']) && !in_array(strtoupper($ACTION_NAME),$_action['no'])) || (!empty($_action['yes']) && in_array(strtoupper($ACTION_NAME),$_action['yes']))) {
					return true;
				}else {
					return false;
				}
				
			}else
				return false;
			
		}
		return false;
	}
	
	
	
	//权限认证的过滤器方法
	static public function AccessDecision($appName='admin'){
		if(RBAC::checkAccess()){
		   $MODEL_NAME=self::$model;
		   $ACTION_NAME=self::$action;
		   
		   
			//存在认证识别号，则进行进一步的访问决策
			$accessGuid   =   md5($appName.$MODEL_NAME.$ACTION_NAME);
			if(empty(Yii::app()->session['administrator'])){
				if(self::$USER_AUTH_TYPE==2){
					//加强验证和即时验证模式 更加安全 后台权限修改可以即时生效
					//通过数据库进行访问检查	
					$accessList=RBAC::getAccessList(Yii::app()->session[self::$USER_AUTH_KEY]);
				}else{
					if(isset(Yii::app()->session[$accessGuid]))
						// 如果是管理员或者当前操作已经认证过，无需再次认证
						if(Yii::app()->session[$accessGuid]) {
							return true;
						} 
					//登录验证模式，比较登录后保存的权限访问列表
					$accessList = Yii::app()->session['_ACCESS_LIST'];//$_SESSION['_ACCESS_LIST'];
					
				}
				if(!isset($accessList[strtoupper($appName)][strtoupper($MODEL_NAME)][strtoupper($ACTION_NAME)])) {
					Yii::app()->session[$accessGuid]  =   false;
					return false;
				}
				else {
					Yii::app()->session[$accessGuid]	=	true;
				}
				
			}else{
				//管理员无需认证
				return true;
			}
		}
		return true;
	}
	
	
	/**
     +----------------------------------------------------------
     * 取得当前认证号的所有权限列表
     +----------------------------------------------------------
     * @param integer $authId 用户ID
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
	static function getAccessList($authId){		
		$table = array('role'=>'{{role}}','user'=>'{{role_user}}','access'=>'{{access}}','node'=>'{{node}}');
		//Db方式查询数据
		$db = Yii::app()->db;
		
		$sql    =   "select node.id,node.name from ".
				$table['role']." as role,".
				$table['user']." as user,".
				$table['access']." as access ,".
				$table['node']." as node ".
				"where user.user_id='{$authId}' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=1 and node.status=1";
	    //获取人口权限
		$apps=$db->createCommand($sql)->queryAll();	    
	    $access=array();	    
	    foreach ($apps as $k => $v){
	    	$appId=$apps[$k]['id'];
	    	$appName=$apps[$k]['name'];
	    	// 读取项目的模块权限
	    	$access[strtoupper($appName)]   =  array();
	    	$sql    =   "select node.id,node.name from ".
	    			$table['role']." as role,".
	    			$table['user']." as user,".
	    			$table['access']." as access ,".
	    			$table['node']." as node ".
	    			"where user.user_id='{$authId}' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=2 and node.pid={$appId} and node.status=1";
	    	//获取权限模块
	    	$modules=$db->createCommand($sql)->queryAll();
	    	// 判断是否存在公共模块的权限
	    	$publicAction  = array();
	        foreach ($modules as $k => $v){
	        	$moduleId=$modules[$k]['id'];
	        	$moduleName=$modules[$k]['name'];
	        	if('PUBLIC'==strtoupper($moduleName)){
		        	$sql    =   "select node.id,node.name from ".
		        			$table['role']." as role,".
		        			$table['user']." as user,".
		        			$table['access']." as access ,".
		        			$table['node']." as node ".
		        			"where user.user_id='{$authId}' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=3 and node.pid={$moduleId} and node.status=1";
	                $re=$db->createCommand($sql)->queryAll();
	                foreach ($re as $a){
	                	$publicAction[$a['name']]	 =	 $a['id'];
	                }
	                unset($modules[$key]);
	                break;
	        	}	
	        }

	        foreach ($modules as $k => $v){
	        	$moduleId=$modules[$k]['id'];
	        	$moduleName=$modules[$k]['name'];
	        	$sql    =   "select node.id,node.name from ".
	        			$table['role']." as role,".
	        			$table['user']." as user,".
	        			$table['access']." as access ,".
	        			$table['node']." as node ".
	        			"where user.user_id='{$authId}' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=3 and node.pid={$moduleId} and node.status=1";
	        	$re=$db->createCommand($sql)->queryAll();
	        	$action = array();
	        	foreach ($re as $a){
	        		$action[$a['name']]	 =	 $a['id'];
	        	}
	        	// 和公共模块的操作权限合并
	        	$action += $publicAction;
	        	$access[strtoupper($appName)][strtoupper($moduleName)]   =  array_change_key_case($action,CASE_UPPER);
	        }
	    	
	    }
	    
	    return $access;
	}
}