<?php
/**
 * 权限列表
 * 13-08-23 14:19
 */
class Act extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{act}}';
	}	
	public function rules(){
		return array(
				array('gameid', 'required','message'=>'游戏ID必填！'),
                array('serverid', 'required','message'=>'区服必填！'),
				array('starttime','required','message'=>'开始时间必填！'),
				array('endtime','required','message'=>'结束时间必填！'),
				array('textureResPath','required','message'=>'图片资源必填！'),
		);
	}
	
}