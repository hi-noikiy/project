<?php
class IndexController extends Controller{

	public function init(){
		parent::init();

	}
    public function actionIndex(){

        $this->layout="dwz";
		$this->render('index');
	}
}