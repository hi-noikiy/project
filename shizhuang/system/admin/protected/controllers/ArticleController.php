<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 文章管理系统
* ==============================================
* @date: 2016-6-1
* @author: luoxue
* @version:
*/
class ArticleController extends Controller{
	
	
	public function actionAdd(){
		$gameList = Game::model()->getGame($this->mangerInfo['game_id']);
        $cateList = Category::model()->getCate($this->mangerInfo['game_id']);
		$model = new Article;
		if(isset($_POST['Article'])){
			$model->attributes=$_POST['Article'];
			$file = CUploadedFile::getInstance($model, 'images');
			
			if(is_object($file) && get_class($file) === 'CUploadedFile'){   // 判断实例化是否成功
				$date=date("Ymd",time());
				$path1 = $this->uploadPath.'/'.$this->getId();
				if(!is_dir($path1))
					@mkdir($path1, 0777);
				$path2 = $path1.'/'.$date;
				if(!is_dir($path2))
					@mkdir($path2, 0777);
				$model->images = $path2.'/'.time().'_'.rand(0,9999).'.'.$file->extensionName;   //定义文件保存的名称	
			}	
			if($model->save()) {
				if(is_object($file) && get_class($file) === 'CUploadedFile')
					$file->saveAs($model->images);    // 上传图片
				$this->display('添加信息成功', 1);
			} else
				$this->display(CHtml::errorSummary($model), 0);
		}

		$this->render('add',array('model'=>$model, 'gameList'=>$gameList, 'cateList' => $cateList));
	}
	
	public function actionUpdate($id){
		$gameList = Game::model()->getGame();
		
		$model = Article::model();
		$result = $model->findByPk($id);
		$oldImages = $result->images;
		$cateList = Category::model()->getCate($result->game_id);
		
		if(isset($_POST['Article'])){
			$result->attributes = $_POST['Article'];
			$file = CUploadedFile::getInstance($result, 'images');		
			if(is_object($file) && get_class($file) === 'CUploadedFile'){   // 判断实例化是否成功
				$date=date("Ymd",time());
				$path1 = $this->uploadPath.'/'.$this->getId();
				if(!is_dir($path1))
					@mkdir($path1, 0777);
				$path2 = $path1.'/'.$date;
				if(!is_dir($path2))
					@mkdir($path2, 0777);
				$result->images = $path2.'/'.time().'_'.rand(0,9999).'.'.$file->extensionName;   //定义文件保存的名称
			}
			if($result->save()) {
				if(is_object($file) && get_class($file) === 'CUploadedFile'){
					@unlink($oldImages);
					$file->saveAs($result->images);    // 上传图片
				}
				$this->display('添加信息成功', 1);
			} else
				$this->display(CHtml::errorSummary($model), 0);
		}
		
		$this->render('update',array('model'=>$result, 'gameList'=>$gameList, 'cateList' => $cateList));
	}
	
	public function actionDel($id){
		$model = Article::model();
		$rs = $model->findByPk($id);
		if($model->deleteByPk($id)){
			if($rs->images)
				@unlink($rs->images);
			$this->display('删除信息成功', 1);
		}else
			$this->display('删除信息失败', 0);
	}
	
}
