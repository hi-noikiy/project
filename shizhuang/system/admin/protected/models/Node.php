<?php
/**
 * @access 权限节点列表
 * 13-08-20 11:14
 */
class Node extends CActiveRecord{
	public $status;
	public $remark;
	
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{node}}';
	}
	
	public function rules(){
		return array(
				array('name', 'required','message'=>'名称必须'),
				array('title', 'required','message'=>'显示名必须'),
				array('remark,status,pid,level','safe'),
		);
	}
	
	function attributeLabels(){
		return array(
				'name'=>'名称',
				'title'=>'显示名',
				'status'=>'状态',
				'remark'=>'备注',
		);
	}
	
	
	function getOneSql($session){
		if($session==0) return $session;
		else 
			$result=Node::model()->find(array(
						'select'=>'level',
						'condition'=>'id=:pid',
						'params'=>array(':pid'=>$session)
					));
		return $result->level;
	}
	
}