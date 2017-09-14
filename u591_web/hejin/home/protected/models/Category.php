<?php
/**
 * 2013-9-22 14:58
 * @author Administrator
 *
 */
class Category extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{category}}';
	}
	
	public function rules(){
		return array(
				array('name', 'required','message'=>'名称必须'),
				array('en_name', 'required','message'=>'名称必须'),
				
				array('sort', 'required','message'=>'排序必须'),
				array('seo_title', 'required','message'=>'SEO标题必须'),
				array('seo_keyword', 'required','message'=>'SEO关键字必须'),
				array('seo_desc', 'required','message'=>'SEO描述必须'),
				
				array('status, is_index, addtime, game_id', 'safe'),
		);
	}
	
	public function attributeLabels(){
		return array(
				'name'   				=>'名称',
				'en_name'   		=>'英文名称',
				'sort'        			=>'排序',
				'status'				=>'状态',
				'is_index'			=>'是否首页显示',
				'game_id'			=>'游戏ID',
				'seo_title'   		=>'SEO标题',
				'seo_keyword' 	=>'SEO关键字',
				'seo_desc'    		=>'SEO描述',
		);
	}
	
	public function getCategoryInfo($gameId){
		$sql = "select name, en_name from {{category}} where status='1' and is_index='1' and game_id='$gameId';";
		$info =$this->findAllBySql($sql);
		return $info;
	}
	
	
	public function getSeo($id){
		$sql = "select seo_title, seo_keyword, seo_desc from {{category}} where id='$id' limit 1";
		$info = $this->findBySql($sql);
		return $info;
	} 
}