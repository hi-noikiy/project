<?php
class MenuController extends Controller {
	
	public function actionIndex() {
		$criteria = new CDbCriteria;
		$criteria->order="sort asc";

		$info = Menu::model()->findAll($criteria);
		$result = json_decode(CJSON::encode($info),TRUE);
		
		$info = $this->gen_tree($result, 'id', 'parentid');
		
		$cookie=Yii::app()->request->getCookies();
		
		$this->render('index', array('info'=>$info, 'menuCookie'=>$cookie['systemmeunnavtabs']));
	}
	
	
	public function actionAdd($m_id=NULL, $parentid=NULL) {
		$model = new Menu();
		if(isset($_POST['Menu'])) {
			if(!$parentid)
				$_POST['Menu']['parentid'] = $m_id;
			$_POST['Menu']['m_id'] = intval($m_id);
			
			$_POST['Menu']['grade'] = 3;
			if($m_id == 0)
				$_POST['Menu']['grade'] = 1;
			if($m_id != 0 &&  $parentid == 0)
				$_POST['Menu']['grade'] = 2;
			
			$model->attributes=$_POST['Menu'];
		
			if($model->save()){
				$this->success('添加信息成功');
			}else 
				$this->error(CHtml::errorSummary($model));
		}
			
			
		$this->render('add', array('m_id'=>$m_id, 'parentid'=>$parentid, 'model'=>$model));
	}
	
	function actionUpdate($id){
		$model=Menu::model();
		$result=$model->findByPk($id);		
		if(isset($_POST["Menu"])){	
			$result->attributes=$_POST["Menu"];
			if($result->save())
				$this->success('更新信息成功');
			else
				$this->error(CHtml::errorSummary($model));
		}
	
		$this->render('update',array('model'=>$result));
	}
	
	
	
	

}