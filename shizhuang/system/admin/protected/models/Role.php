<?php
/**
 * 角色Controller
 * 13-08-22 15:34
 */
class Role extends CActiveRecord{
	public $parent_id;
	
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{role}}';
	}
	
	public function rules(){
		return array(
				array('name', 'required','message'=>'组名必须'),
				array('status,pid,remark','safe'),
		);
	}
	
	function attributeLabels(){
		return array(
				'name'=>'组名',
				'pid' =>'上级组',  
				'status'=>'状态',
				'remark'=>'备注',
		);
	}
	
	protected function beforeSave(){
		if(parent::beforeSave()){
			if($this->isNewRecord){
				$this->create_time=time();
			    $this->update_time=time();
			    $this->pid=$_POST['parent_id'];
			} else 
				$this->update_time=time();
			return true;
		}else
			return false;
	}
	
	public function getAllSql(){
		$arr=array();
		$re=$this->findAll(array('select'=>'name'));
		$arr['-1']="请选择角色";
		foreach ($re as $v) {
			$arr[$v->name]=$v->name;
		}
		return $arr;
	}
}