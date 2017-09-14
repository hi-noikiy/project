<?php
// +----------------------------------------------------------------------
// | 包场订单控制器
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: jcjtim
// +----------------------------------------------------------------------
namespace Check\Model;
use Think\Model;

class WholeReserveModel extends Model {	
	/**
	 * 数据库名称
	 * @var 字符串
	 */
	var $tablename="whole_reserve";
	/**
	 * 添加模版
	 * @param array $dataarray
	 * @param int $flag 1为单个添加 2为多条添加
	 * @return mixed
	 */
	private function add_basedata($dataarray,$flag="1") {
		$userbase = M($this->tablename);
		switch ($flag) {
			case 1:
				$ret = $userbase->add($dataarray);
				break;
			case 2:
				$ret = $userbase->addAll($dataarray);
				break;
			default:
				$ret = $userbase->add($dataarray);
				break;
		}
		if($ret) {
			return $ret;//返回用户id
		}else {
			return false;
		}
	}
	/**
	 *
	 *功能：删除信息
	 *返回：成功：true
	 *           失败：false
	 *
	 */
	private function delete_basedata($dataarray) {
		$wherearray['id']=array('EQ',$dataarray['id']);
		$userbase = M($this->tablename);
		$ret=$userbase->where($wherearray)->delete();
		if($ret) {
			return 1;//返回删除成功
		}else {
			return false;
		}
	}
	/**
	 *
	 *功能：修改信息
	 *返回：成功：true
	 *           失败：false
	 *
	 */
	private function update_basedata($dataarray) {
		$wherearray['id']=array('EQ',$dataarray['id']);
		$userbase = M($this->tablename);
		$ret=$userbase->where($wherearray)->save($dataarray);


		if($ret) {
			return 1;
		}else {
			return false;
		}
	}

	/**
	 *
	 *功能：添加信息
	 *
	 */
	public function add_model($dataarray) {
		 
		$ret=$this->add_basedata($dataarray);
		return $ret;
	}
	/**
	 *
	 *删除信息
	 *
	 */
	public function delete_model($dataarray) {
		$ret=$this->delete_basedata($dataarray);
		return $ret;
	}
	/**
	 *
	 *修改信息
	 *
	 */
	public function update_model($dataarray) {
		 
		$ret=$this->update_basedata($dataarray);
		return $ret;
	}
	/**
	 *
	 *功能：查询信息
	 *返回：成功：详细信息
	 *           失败：false
	 *
	 */
	public function getlist($dataarray=array(),$flag="1") {
		$wherearray=array();
		if(isset($dataarray['id'])) {
			$wherearray['id']=array('eq',$dataarray['id']);
		}
		if(isset($dataarray['adminUid'])) {
			$wherearray['adminUid']=array('eq',$dataarray['adminUid']);
		}
		if(isset($dataarray['eltstate'])) {
			$wherearray['state']=array('elt',$dataarray['eltstate']);
		}
		if(isset($dataarray['state'])) {
			$wherearray['state']=array('eq',$dataarray['state']);
		}
		if(isset($dataarray['start_time'])&&isset($dataarray['end_time'])) {
			$starttime=$dataarray['start_time'];
			$endtime=$dataarray['end_time']+86400;
			$wherearray['viewingDate']=array('exp','>='.$starttime.' and viewingDate <= '.$endtime);
		}elseif(isset($dataarray['start_time'])) {
			$starttime=$dataarray['start_time'];
			$wherearray['viewingDate']=array('exp','>='.$starttime);
		}elseif(isset($dataarray['end_time'])) {
			$endtime=$dataarray['end_time']+86400;
			$wherearray['viewingDate']=array('exp','<= '.$endtime);
		}
		if(isset($dataarray['start_checkTime'])&&isset($dataarray['end_checkTime'])) {
			$starttime=$dataarray['start_checkTime'];
			$endtime=$dataarray['end_checkTime'];
			$wherearray['checkTime']=array('exp','>='.$starttime.' and checkTime <= '.$endtime);
		}elseif(isset($dataarray['start_checkTime'])) {
			$starttime=$dataarray['start_checkTime'];
			$wherearray['checkTime']=array('exp','>='.$starttime);
		}elseif(isset($dataarray['end_checkTime'])) {
			$endtime=$dataarray['end_checkTime']+86400;
			$wherearray['checkTime']=array('exp','<= '.$endtime);
		}
		if(isset($dataarray['tel'])) {
			$wherearray['tel']=array('like','%'.$dataarray['tel'].'%');
		}
		if(isset($dataarray['videoId'])) {
			$wherearray['videoId']=array('eq',$dataarray['videoId']);
		}
		if(isset($dataarray['code'])) {
			$wherearray['code']=array('eq',$dataarray['code']);
		}
//		$database = D('WholeReserveView');	
		$database = M('whole_reserve');	
		//条件处理
		$sort='';
		if(isset($dataarray['sort'])) {
			$sort=$dataarray['sort'];
		}
		$firstRow='0';
		if(isset($dataarray['firstRow'])) {
			$firstRow=$dataarray['firstRow'];
		}
		$listRows=20;
		if(isset($dataarray['listRows'])) {
			$listRows=$dataarray['listRows'];
		}
		$getField='*';
		if(isset($dataarray['getField'])) {
			$getField=$dataarray['getField'];
		}
		//        $database = M($this->tablename);
		
		switch($flag) {
			case '1':
				$info=$database->where($wherearray)->order($sort)->select();
				break;
			case '2'://分页操作
				$info=$database->where($wherearray)->order($sort)->limit($firstRow.','.$listRows)->select();
				break;
			case '3'://获取个数
				$info=$database->where($wherearray)->count();
				break;
			case '4'://获取单条
				$info=$database->where($wherearray)->find();
				break;
			case '5'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->select();
				break;
			default:
				$info=$database->where($wherearray)->select();
				break;
		}
		//     echo $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}
	}
	/**
	 *
	 *功能：查询信息
	 *返回：成功：详细信息
	 *           失败：false
	 *
	 */
	public function reserve_relation_package_getlist($dataarray=array(),$flag="1") {
		$wherearray=array();
		if(isset($dataarray['id'])) {
			$wherearray['id']=array('eq',$dataarray['id']);
		}
		if(isset($dataarray['type'])) {
			$wherearray['type']=array('eq',$dataarray['type']);
		}
		if(isset($dataarray['reserveId'])) {
			$wherearray['reserveId']=array('eq',$dataarray['reserveId']);
		}
		//条件处理
		$sort='';
		if(isset($dataarray['sort'])) {
			$sort=$dataarray['sort'];
		}
		$firstRow='0';
		if(isset($dataarray['firstRow'])) {
			$firstRow=$dataarray['firstRow'];
		}
		$listRows=20;
		if(isset($dataarray['listRows'])) {
			$listRows=$dataarray['listRows'];
		}
		$getField='*';
		if(isset($dataarray['getField'])) {
			$getField=$dataarray['getField'];
		}
		//        WholeReservePackageView
		$database = D('WholeReservePackageView');
		switch($flag) {
			case '1':
				$info=$database->where($wherearray)->field($getField)->order($sort)->select();
				break;
			case '2'://分页操作
				$info=$database->where($wherearray)->field($getField)->order($sort)->limit($firstRow.','.$listRows)->select();
				break;
			case '3'://获取个数
				$info=$database->where($wherearray)->count();
				break;
			case '4'://获取单条
				$info=$database->where($wherearray)->field($getField)->find();
				break;
			default:
				$info=$database->where($wherearray)->field($getField)->order($sort)->select();
				break;
		}
		//     echo $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}
	}
	/**
	 *
	 *功能：查询信息
	 *返回：成功：详细信息
	 *           失败：false
	 *
	 */
	public function reserve_relation_service_getlist($dataarray=array(),$flag="1") {
		$wherearray=array();
		if(isset($dataarray['id'])) {
			$wherearray['id']=array('eq',$dataarray['id']);
		}
		if(isset($dataarray['type'])) {
			$wherearray['type']=array('eq',$dataarray['type']);
		}
		if(isset($dataarray['reserveId'])) {
			$wherearray['reserveId']=array('eq',$dataarray['reserveId']);
		}
		//        $database = M($this->tablename);
		$sort='';
		if(isset($dataarray['sort'])) {
			$sort=$dataarray['sort'];
		}
		$firstRow='0';
		if(isset($dataarray['firstRow'])) {
			$firstRow=$dataarray['firstRow'];
		}
		$listRows=20;
		if(isset($dataarray['listRows'])) {
			$listRows=$dataarray['listRows'];
		}
		$getField='*';
		if(isset($dataarray['getField'])) {
			$getField=$dataarray['getField'];
		}
		$database = D('WholeReserveServiceView');
		switch($flag) {
			case '1':
				$info=$database->where($wherearray)->field($getField)->order($sort)->select();
				break;
			case '2'://分页操作
				$info=$database->where($wherearray)->field($getField)->order($sort)->limit($firstRow.','.$listRows)->select();
				break;
			case '3'://获取个数
				$info=$database->where($wherearray)->count();
				break;
			case '4'://获取单条
				$info=$database->where($wherearray)->field($getField)->find();
				break;
			default:
				$info=$database->where($wherearray)->field($getField)->order($sort)->select();
				break;
		}
		//     echo $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}
	}
	/**
	 *
	 *功能：查询信息
	 *返回：成功：详细信息
	 *           失败：false
	 *
	 */
	public function admin_getlist($dataarray=array(),$flag="1") {
		$wherearray=array();
		if(isset($dataarray['id'])) {
			$wherearray['id']=array('eq',$dataarray['id']);
		}

		 

		//        $database = M($this->tablename);
		$sort='';
		if(isset($dataarray['sort'])) {
			$sort=$dataarray['sort'];
		}
		$firstRow='0';
		if(isset($dataarray['firstRow'])) {
			$firstRow=$dataarray['firstRow'];
		}
		$listRows=20;
		if(isset($dataarray['listRows'])) {
			$listRows=$dataarray['listRows'];
		}
		$getField='*';
		if(isset($dataarray['getField'])) {
			$getField=$dataarray['getField'];
		}
		//        WholeReservePackageView
		$database = M('admin');
		switch($flag) {
			case '1':
				$info=$database->where($wherearray)->field($getField)->order($sort)->select();
				break;
			case '2'://分页操作
				$info=$database->where($wherearray)->field($getField)->order($sort)->limit($firstRow.','.$listRows)->select();
				break;
			case '3'://获取个数
				$info=$database->where($wherearray)->count();
				break;
			case '4'://获取单条
				$info=$database->where($wherearray)->field($getField)->find();
				break;
			default:
				$info=$database->where($wherearray)->field($getField)->order($sort)->select();
				break;
		}
		//     echo $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}
	}
	/**
	 *
	 *功能：查询信息
	 *返回：成功：详细信息
	 *           失败：false
	 *
	 */
	public function film_getlist($dataarray=array(),$flag="1") {
		$wherearray=array();

		if(isset($dataarray['filmNo'])) {
			$wherearray['filmNo']=array('eq',$dataarray['filmNo']);
		}
		 

		//        $database = M($this->tablename);
		$sort='';
		if(isset($dataarray['sort'])) {
			$sort=$dataarray['sort'];
		}
		$firstRow='0';
		if(isset($dataarray['firstRow'])) {
			$firstRow=$dataarray['firstRow'];
		}
		$listRows=20;
		if(isset($dataarray['listRows'])) {
			$listRows=$dataarray['listRows'];
		}
		$getField='*';
		if(isset($dataarray['getField'])) {
			$getField=$dataarray['getField'];
		}
		//        WholeReservePackageView
		$database = M('film');
		switch($flag) {
			case '1':
				$info=$database->where($wherearray)->field($getField)->order($sort)->select();
				break;
			case '2'://分页操作
				$info=$database->where($wherearray)->field($getField)->order($sort)->limit($firstRow.','.$listRows)->select();
				break;
			case '3'://获取个数
				$info=$database->where($wherearray)->count();
				break;
			case '4'://获取单条
				$info=$database->where($wherearray)->field($getField)->find();
				break;
			default:
				$info=$database->where($wherearray)->field($getField)->order($sort)->select();
				break;
		}
		//     echo $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}
	}




}