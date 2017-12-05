<?php
/**
 * 角色Controller
 * 13-08-22 15:34 
 */
class RoleController extends Controller{
	function _field(&$field){
		$field=array('id,name,pid,status,remark,create_time,update_time');
	}
    
	function _condition(&$condition){
		$condition=array();
		if (isset($_GET['id']))
			$condition[]="pid={$_GET['id']}";
		else
			$condition[]="pid=0";
	}
	
	//用户列表 
	function actionManager($id){
		$manger=Manager::model()->findAll(array('select'=>'id, login_name, nickname'));
		$roleUser=RoleUser::model()->findAll();
		$datas=array();
		foreach ($manger as $k=> $v){
			$datas[$k]=array_filter($v->attributes,'strlen');
			if(!empty($roleUser)){
				foreach ($roleUser as $val){
					if($val->user_id == $v->id && $val->role_id == $id){
						$datas[$k]['checked']='checked';
						break;
					}else{
						$datas[$k]['checked']="";
					}
			    }
			}else{
				$datas[$k]['checked']="";
			}
		}
		$this->render('manager',array('manager'=>$datas));
	}
	
	function actionSetUser(){
		$userId = isset($_POST['userId']) ? $_POST['userId'] : null;
		$roleId = $_POST['roleId'];
		if(empty($userId))
			$this->error('请选择用户列表！');
		
		$roleUser = new RoleUser();
		if(isset($_POST)){
			$roleUser->delRoleUser($roleId);
			$result = $roleUser->saveRoleUser($roleId, $userId);
 			if($result) 
 				$this->success('更新成功');
 			else 
 				$this->error('更新错误');
		}
	}
	//授权 --应用授权
	function actionApp($id){
		$node=Node::model()->findAll(array(
		           'select'=>'id,title',
		           'condition'=>'level=:level',
		           'params'=>array(':level'=>1),           
		        ));	
		$access=new Access();
		$grounpAccess=$access->getAppList($id);
		$datas=array();
		foreach ($grounpAccess as  $v){
			$datas[$v['id']]=$v['id'];
		}	
		$this->render('app',array('node'=>$node,'datas'=>$datas, 'id' => $id));
	}
	
	function actionSetAPP(){
		$access=new Access();
		if(isset($_POST)){
			$roleId=$_POST['id'];
			isset($_POST['groupAppId']) ? $appId=$_POST['groupAppId'] : $appId="";
			$access->delAppList($roleId);
			$re=$access->saveAppList($roleId,$appId);
			if($re) $this->success('设置成功');
			else $this->error('设置失败');
		}
	}
	//授权--模块授权
	function actionModel($id){
		$appId = isset($_REQUEST['appId']) ? intval($_REQUEST['appId']) : 0;
		$node=Node::model()->findAll(array(
				    'select'=>'id,title',
				    'condition'=>'level=:level and pid=:pid',
				    'params' =>array(':level'=>2, ':pid'=>$appId),
				));
		$access=new Access();
		$grounpAccess=$access->getAppList($id);
		$datas=array();
		foreach ($grounpAccess as  $v){
			$datas[$v['id']]=$v['title'];
		}		
		$model=array();
		$modelList=$access->getModelList($id, $appId);	
			foreach ($modelList as $v ){
				$model[$v['id']]=$v['id'];
		}
		$this->render('model',array('node'=>$node,'datas'=>$datas,'model'=>$model, 'id' => $id, 'appid' =>$appId));
	}
	
	function actionSetModel(){
		$access=new Access();
		if(isset($_POST)){
			$appId = $_POST['appId'];
			$roleId = $_POST['groupId'];
			if(empty($appId))
				$this->error('应用未授权');
				
			$moduleId = isset($_POST['groupModuleId']) ? $_POST['groupModuleId'] : '';
			$access->delModelList($appId, $roleId);
			$re=$access->saveModelList($roleId, $moduleId);
			$url = $this->createUrl('Role/model/id/'.$roleId.'/appId/'.$appId);
			if($re) 
				$this->success('设置成功', $url);
			else $this->error('设置失败');
 		}
	}
	//授权--操作授权
	function actionOperate($id){
		$appId = isset($_POST['appId']) ? intval($_POST['appId']) : (isset($_REQUEST['appId']) ? $_REQUEST['appId'] : 0);
		$moduleId = isset($_POST['moduleId']) ? intval($_POST['moduleId']) : (isset($_REQUEST['moduleId']) ? $_REQUEST['moduleId'] : 0);
		$access=new Access();
		//app
		$grounpAccess=$access->getAppList($id);
		$app=array();
		foreach ($grounpAccess as  $v){
			$app[$v['id']]=$v['title'];
		}
		//model
		$model=array();
		if($appId){
			$modelList=$access->getModelList($id, $appId);
			foreach ($modelList as $v ){
				$model[$v['id']]=$v['title'];
			}
		}
		//operate
		$operate=array();
		if($moduleId){
			$operateList=$access->getOperateList($moduleId);
			$checkedOperate=$access->checkedOperteList($id, $moduleId);
			foreach ($operateList as $k=> $v){
				$operate[$k]['id']=$v['id'];
				$operate[$k]['title']=$v['title'];
 				if(!empty($checkedOperate)){
					foreach ($checkedOperate as $val){		
						if($val['id']==$v['id']){
						    $operate[$k]['checked']="checked";
						    break;
						}else
						   $operate[$k]['checked']="";		
					}
				}else
					$operate[$k]['checked']="";			
			}		
		}    
		$this->render('operate',array('app'=>$app,'model'=>$model,'operate'=>$operate, 'id' => $id, 'appId' =>$appId, 'moduleId'=>$moduleId));
	}
	
	function actionSetOperate(){
		$access=new Access();
		if(isset($_POST)){
			$roleId=$_POST['groupId'];
			$appId = $_POST['appId'];
			$moduleId = isset($_POST['moduleId']) ? intval($_POST['moduleId']) : 0;
			$operateId = isset($_POST['groupActionId']) ? $_POST['groupActionId'] : "";
			if(empty($operateId))
				$this->error('请选择操作！');
			
			
			$access->delOperateList($roleId, $moduleId);
			$re=$access->saveOperateList($roleId,$operateId);
			$url = $this->createUrl('role/operate/id/'.$roleId.'/appId/'.$appId.'/moduleId/'.$moduleId);
			if($re===false) 
				$this->error('设置错误');
			else 
				$this->success('设置成功', $url);
		}
	}
	
}