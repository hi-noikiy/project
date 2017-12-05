<?php
/**
 * @access 权限节点列表
 * 13-08-20 11:14
 */
class NodeController extends Controller{
	function actionIndex(){		
		isset($_GET['id']) ? $pid=$_GET['id'] : $pid=0; 
		$_SESSION['nodePid']=$pid;
		$model=Node::model();
		$info=$model->findAll("pid=:pid",array(":pid"=>$pid)); 
		$this->render('index',array('model'=>$info));		
	}
	
	function _field(&$field){
		$field=array('id,name,title,status,remark');
	}

	function actionAdd(){
		$model=new Node();
		if(isset($_POST['Node'])){
			$model->attributes=$_POST['Node'];
			if($model->save())
				$this->success('添加节点成功');
			else 
				$this->error('添加失败');
		} 
		$this->render('add',array('model'=>$model,'pid'=>$model->getOneSql($_SESSION['nodePid'])));
	} 
	
	function actionDel($id){
		$model=Node::model();
		if($model->deleteAll('pid=:pid or id=:id',array(':pid'=>$id,':id'=>$id)))
			//$this->success('删除节点成功');
			$this->display('删除节点成功', 1 );
		else 
			$this->display('删除失败', 0);
	}
	
}