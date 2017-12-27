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
class ManagerController extends Controller{
	public $name;

    private function getErpLevel(){
        $model = new ErpLevel;
        $rs = $model->getInfo();
        return $rs;
    }

	public function _condition(&$condition){
		$condition=array();
		if(isset($_POST['name']) && !empty($_POST['name']))
			$condition[]="nickname = '{$_POST['name']}'";
		$condition['param'] = array('game'=>$this->getGame(),'channelInfo'=>$this->getChannel());
	}
	
	public function _field(&$field){
		$field=array('id,login_name,nickname,login_num,login_time,login_ip,reg_time,status,game_id,channel_id');
	}
	
	public function actionAdd(){
		$model=new Manager;
		if(isset($_POST['Manager'])){
			$model->attributes=$_POST["Manager"];
			$model->login_pass=md5_10($_POST['Manager']['login_pass']);
			if($model->save()) {
				$this->success('添加会员成功');
			}else
				$this->error(CHtml::errorSummary($model));
		}
	
		$this->render('add',array('model'=>$model,'game' =>$this->getGame(),'channelInfo'=>$this->getChannel(),'levelInfo'=>$this->getErpLevel()));
	}

	public function actionEdit(){
		$id = $this->getUserid();
		$model = Manager::model()->findByPk($id);
		$oldPass = $model->login_pass;
		if(isset($_POST['Manager'])){
			$pass = $_POST['Manager']['login_pass'];
			$repass = $_POST['Manager']['repassword'];
			$model->attributes = $_POST['Manager'];
			if(!empty($_POST['Manager']['login_pass'])){
				if($pass != $repass)
					$this->error('确认密码不同');
				if ($oldPass == md5_10($_POST['Manager']['login_pass']))
					$this->error('新旧密码相同');
				$model->login_pass = md5_10($_POST['Manager']['login_pass']);
			}
			if($model->save())
				$this->success('修改信息成功');
			else
				$this->error(CHtml::errorSummary($model));
		}
		
		
		$this->render('edit',array('model'=>$model));
	}
	
	
	public function actionUpdate($id){
		$model=Manager::model()->findByPk($id);
		$oldPass = $model->login_pass;
		if(isset($_POST['Manager'])){
			$pass = $_POST['Manager']['login_pass'];
			$repass = $_POST['Manager']['repassword'];
			$model->attributes = $_POST['Manager'];
			if(!empty($_POST['Manager']['login_pass'])){
				if($pass != $repass)
					$this->error('确认密码不同');
				if ($oldPass == md5_10($_POST['Manager']['login_pass']))
					$this->error('新旧密码相同');
				$model->login_pass = md5_10($_POST['Manager']['login_pass']);
			}
			if($model->save())
				$this->success('修改信息成功');
			else
				$this->error(CHtml::errorSummary($model));
		}
		$this->render('update',array(
		    'model'=>$model,'game'=>$this->getGame(),'channelInfo'=>$this->getChannel(),
            'levelInfo'=>$this->getErpLevel(),'serverInfo'=>$this->getServerPre(),
        ));
	}
	
	public function actionDel($id){
		$model=Manager::model();
		$result=$model->findByPk($id);
		if($result->login_name=='admin')
			$this->display('最高管理员无法删除!', 1);
		
		if($model->deleteByPk($id))
			$this->display('删除信息成功', 1);
		else
			$this->display('删除信息失败', 0);
	
	}
}