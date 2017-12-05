<?php
/**
 * 2013-9-23 09:02
 * @author Administrator
 *
 */
class Article extends CActiveRecord{
	public $images;
	//public $hit;
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{article}}';
	}
	
	public function rules(){
		return array(
				//array('summary', 'required','message'=>'概要必须'),    
				array('title', 'required','message'=>'标题必须'),
				array('title','unique','message'=>'标题不能重复', 'on'=>'insert'),
				array('author', 'required','message'=>'作者必须'),
				array('cate_name', 'required','message'=>'栏目必须'),
				array('seo_title', 'required','message'=>'seo标题必须'),
				array('seo_keyword', 'required','message'=>'seo关键字必须'),
				array('seo_desc', 'required','message'=>'seo描述必须'),
				//array('ar_attribute', 'required','message'=>'属性必须'),
		        array('images','file','allowEmpty'=>true,'types'=>'jpg,gif,png','maxSize'=>1024 * 1024 * 1, 'tooLarge'=>'图片不能超过1M'),
				array('summary, attribute, come, content, cate_name, images, game_id','safe'),
		);
	}
	
	function attributeLabels(){
		return array(
				'summary'  		=>'概要',
				'images'   			=>'缩略图',
				'title'    				=>'标题',
				'content'  			=>'内容',
				'come'     			=>'来自',
				'author'   			=>'作者',
				'visits'   				=>'访问次数',
				'cate_name'		=>'栏目',
				'seo_title'   		=>'SEO标题',
				'seo_keyword' 	=>'SEO关键字',
				'seo_desc'    		=>'SEO描述',
				'attribute'			=>'属性',
				'game_id'			=>'所属游戏',
			
		);
	}
	protected function beforeSave(){
		if(parent::beforeSave()){
			if($this->isNewRecord){
				$this->visits = 0;
				$this->addtime = time();
				$this->status = 1;
			}
			return true;
		}else
			return false;
	}
	function GetCateArticle($cate,$limit){
		$service=Article::model()->findAll(array(
				'select'=>'id,ar_images,ar_title,ar_summary,addtime',
				'limit'=>$limit,
				'condition'=>"cate_name=:cate_name",
				'params'=>array(':cate_name'=>$cate),
				'order'=>'id desc',
		));
		return $service;
	}
	function getSeo($id){
		$seo = $this->find(array(
				'select'=>'seo_title,seo_keyword,seo_desc',
				'condition'=>'id=:id',
				'params'=>array(':id'=>$id)
				));
			
		return $seo;
	}
	
	function getCate($id){
		$sql="select * from {{category}}  where id=$id";
		$cate=Category::model()->findBySql($sql);
	    return $cate;
	}
	
	function getArticleDetail($id){
		$sql="select title, author, content, visits, cate_name ,addtime from {{article}}  where id=$id limit 1";
		$result=Article::model()->findBySql($sql);
		return $result;
	}
	
	public function SetDataCache($id){
		if(!isset($_COOKIE["hist".$id])){
			$this->updateCounters(array('visits'=>1),"id=:id",array(':id'=>$id));
			setcookie('hist'.$id,'hist',time()+86400);
		}
		
		
		$hit =$this->find(array('select'=>'visits','condition'=>'id=:id','params'=>array(':id'=>$id)));
		
		$info = Yii::app()->cache->get($id);
		
		if(empty($info)){
			$info =$this->getArticleDetail($id);
			Yii::app()->cache->set($id, $info,3600);
		}
		$cate = Category::model()->find(array('select'=>'en_name','condition'=>'name=:name','params'=>array(':name'=>$info->cate_name)));
		
		
		return array($info, strtolower($cate->en_name));
	}
	
}