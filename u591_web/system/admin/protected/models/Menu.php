<?php
class Menu extends CActiveRecord{
	protected  $s;
	
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{menu}}';
	}
	
	public function rules(){
		return array(
				array('name,m_id,grade,parentid,controller,action,remark,status,home,closeable,sort,ico,lock','safe')
		);
	}
	
	
	public function findMenu($id){
		$result=$this->findAll(array(
				'condition'=>'id=:id and status=:status',
				'order'=>'sort desc',
				'params'=>array(':id'=>$id, ':status'=>1)
				
		));

		return $result;
	}
	
	public function json($id = NULL) {
		$criteria = new CDbCriteria;
		$criteria->addCondition('grade', array(1,2));
		if(!empty($id))
			$criteria->addCondition('id='.$id);
		$array = $this->findAll($criteria);
		
		$array = json_decode(CJSON::encode($array),TRUE);
		$i = 0;
		$json[] = array('v' => '0', 'n' => '一级目录');
		
		foreach ($array as $value) {
			if ($value['grade'] == 1) {
				$i++;
				$json[$i]['v'] = $value['id'];
				$json[$i]['n'] = $value['name'];
				$json[$i]['s'][] = array('v' => '0', 'n' => '二级目录');
				foreach ($array as $var) {
					if ($var['parentid'] === $value['id'])
						$json[$i]['s'][] = array('v' => $var['id'], 'n' => $var['name']);
				}
			}
		}
		return json_encode($json);
	}
	
// 	public function getSqlAll(){
// 		$uid=Yii::app()->session['authId'];
// 		$re=$this->findAll(array(
// 				'select'=>'id,sch_starttime,sch_endtime,sch_workcontent',
// 				'condition'=>'user_id=:uid',
// 				'params'=>array(':uid'=>$uid)
// 		));
// 		return $re;
// 	}
}