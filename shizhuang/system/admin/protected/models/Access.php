<?php
/**
 * 权限列表
 * 13-08-23 14:19
 */
class Access extends CActiveRecord{
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{access}}';
	}	
	//应用
	function getAppList($id){
		$sql="SELECT b.id,b.title,b.name from {{access}} as a, {{node}} as b where a.node_id=b.id and  b.pid=0 and a.role_id='$id';";
		$re=Yii::app()->db->createCommand($sql)->query();
		return $re;
	}	
	function delAppList($id){
		$sql="DELETE FROM {{access}} where role_id='$id' and level=1;";
		$re=Yii::app()->db->createCommand($sql)->query();
		if($re) return true;
		else return false;
	}	
	function saveAppList($roleId,$appId){
		if(empty($appId)) return true;
		$appId=implode(',', $appId);
		$where = 'a.id ='.$roleId.' AND b.id in('.$appId.')';
		$sql="INSERT INTO {{access}} (role_id,node_id,pid,level) SELECT a.id, b.id,b.pid,b.level FROM {{role}} a, {{node}} b where $where;";
		$re =Yii::app()->db->createCommand($sql)->query();
		if($re) return true;
		else return false;
	}
	//模块
	function getModelList($id,$appId){
		$sql="SELECT b.id,b.title,b.name from {{access}} as a, {{node}} as b where a.node_id=b.id and  b.pid='$appId' and a.role_id='$id';";
		$re=Yii::app()->db->createCommand($sql)->query();
		return $re;
	}	
	function delModelList($appId,$roleId){
		$sql="DELETE FROM {{access}} where level=2 and pid='$appId' and role_id='$roleId';";
		$re=Yii::app()->db->createCommand($sql)->query();
		if($re) return true;
		else return false;
	}
	function saveModelList($roleId,$moduleId){
		if(empty($moduleId)) return true;
		$moduleId=implode(',', $moduleId);
		$where = 'a.id ='.$roleId.' AND b.id in('.$moduleId.')';
		$sql="INSERT INTO {{access}} (role_id,node_id,pid,level) SELECT a.id, b.id,b.pid,b.level FROM {{role}} a, {{node}} b WHERE $where;";
		$re=Yii::app()->db->createCommand($sql)->query();
		if($re===false) return false;
		else return true;
	}
	//operate
	function checkedOperteList($roleId,$moduleId){
		    $sql="SELECT b.id,b.title,b.name FROM {{access}} a, {{node}} b WHERE a.node_id=b.id AND  b.pid='$moduleId' AND a.role_id='$roleId'";
            $re=Yii::app()->db->createCommand($sql)->query();
            return $re->readAll();
	}
	function getOperateList($moduleId){
		$sql="SELECT id,title FROM {{node}} where pid='$moduleId';";
		$re=Yii::app()->db->createCommand($sql)->query();
		return $re;
	}
	function delOperateList($roleId,$moduleId){
		$sql="DELETE FROM {{access}} where level=3 and pid='$moduleId' and role_id='$roleId';";
		$re=Yii::app()->db->createCommand($sql)->query();
	    if($re===false) return false;
	    else return true;
	}
	
	function saveOperateList($roleId,$operateId){
		if(empty($operateId)) return true;
		$operateId=implode(',', $operateId);
		$where='a.id='.$roleId.' and b.id in('.$operateId.')';
		$sql="INSERT INTO {{access}} (role_id,node_id,pid,level) SELECT a.id, b.id,b.pid,b.level FROM {{role}} a, {{node}} b WHERE $where";
		$re=Yii::app()->db->createCommand($sql)->query();
		if(false===$re) return false;
		else return true;
	}
	
}