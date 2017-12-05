<?php
/**
 * 角色管理员关联表
 * 13-08-22 17:00
 */
class RoleUser extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{role_user}}';
	}
	
	
	function delRoleUser($roleId){
		if(empty($roleId)) return false;
		$re=RoleUser::model()->deleteAll('role_id=:id',array(':id'=>$roleId));
		/* if($re) 
			return true;
		else 
			return false; */
	}
	function saveRoleUser($roleId,$userId){
		if(empty($userId)) return true;
		foreach ($userId as $v){
			$sql="INSERT INTO {{role_user}} value('$roleId','$v')";
			
			Yii::app()->db->createCommand($sql)->execute();
		}
		return true;
	}
	
}