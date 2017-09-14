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
				array('summary', 'required','message'=>'概要必须'),
			    //array('ar_images', 'required','message'=>'缩略图必须'),
				array('title', 'required','message'=>'标题必须'),
				//array('ar_title','unique','message'=>'唯一'),
				//array('ar_content', 'required','message'=>'内容必须'),
				array('come', 'required','message'=>'来源'),
				array('author', 'required','message'=>'作者必须'),
				//array('cate_name', 'required','message'=>'栏目必须'),
				array('seo_title', 'required','message'=>'seo标题必须'),
				array('seo_keyword', 'required','message'=>'seo关键字必须'),
				array('seo_desc', 'required','message'=>'seo描述必须'),
				//array('ar_attribute', 'required','message'=>'属性必须'),
		        array('images','file','allowEmpty'=>true,'types'=>'jpg,gif,png','maxSize'=>1024 * 1024 * 1, 'tooLarge'=>'图片不能超过1M'),
				array('attribute, content, cate_name, images','safe'),
		);
	}
	
	function attributeLabels(){
		return array(
				'ar_summary'  =>'概要',
				'ar_images'   =>'缩略图',
				'ar_title'    =>'标题',
				'ar_content'  =>'内容',
				'ar_come'     =>'来自',
				'ar_author'   =>'作者',
				'ar_visits'   =>'访问次数',
				'cate_name'   =>'栏目',
				'seo_title'   =>'SEO标题',
				'seo_keyword' =>'SEO关键字',
				'seo_desc'    =>'SEO描述',
				'ar_attribute'=>'属性',
			
		);
	}
	protected function beforeSave(){
		if(parent::beforeSave()){
			if($this->isNewRecord){
				$this->ar_visits=0;
				$this->addtime=time();
				$this->status=1;
			}
			return true;
		}else
			return false;
	}
	function GetCateArticle($cate,$limit, $gameId = 8){
		
		$criteria = new CDbCriteria;
		$criteria->select = 'id, images, title, summary,cate_name, addtime';
		$criteria->addCondition("cate_name='$cate' and game_id='$gameId'");
		$criteria->order = 'id desc';
		$criteria->limit = $limit;
		
		$info = $this->findAll($criteria);
		return $info;
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