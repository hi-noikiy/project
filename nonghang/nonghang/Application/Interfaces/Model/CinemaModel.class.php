<?php

namespace Interfaces\Model;
use Think\Model;

class CinemaModel extends Model {
   
	/**
	 * 影厅差价
	 * @param unknown $map
	 * @return mixed
	 */
	function findHallPrice($map){
		$hall=M('cinemaHall')->where($map)->find();
		if(empty($hall['price'])){
			$hall['price']=array('0'=>0);
		}
		return json_decode($hall['price'],true);
	}
	
	/**
	 * 获取座位补贴价
	 */
	function getseatprice($map,$sectionId){
		$prices=M('CinemaHall')->where($map)->find();
		$pricess=json_decode($prices['price'],true);
		if(!empty($pricess[$sectionId])){
			$price=$pricess[$sectionId];
		}else{
			$price=0;
		}
		return $price;
	}
   /**
    * 获取影院列表
    * @param array();
    * @return array();
    * @author 宇
    */
   public function getCinemaList($field, $map){
       $cinemaList = M('Cinema')->field($field)->where($map)->select();
       // echo $cinemaList;
       return $cinemaList ? $cinemaList : '';
   }

	/**
	* 获取影院信息
	* @param array();
	* @return array();
	* @author 宇
	*/
	public function getCinemaInfo($field, $map)
	{
		$cinemaInfo = M('Cinema')->field($field)->where($map)->find();
		// echo M('Cinema')->_sql();
		// echo $cinemaList;
		return $cinemaInfo;
	}


	 /**
	* 根据分组获取影院列表
	* @param array();
	* @return array();
	* @author 宇
	*/
	 public function getCinemaListByCinemaGroupId($field, $cinemaGroupId)
	 {
		 return $this->getCinemaList($field, array('cinemaGroupId' => $cinemaGroupId));
	 }


	 /**
	* 根据分组获取影院列表
	* @param array();
	* @return array();
	* @author 宇
	*/
	 public function getCinemaInfoBycinemaCode($field, $cinemaCode)
	 {
		 return $this->getCinemaInfo($field, array('cinemaCode' => $cinemaCode));
	 }

	 /**
	  * 是否在影院列表
	  * @param unknown $cinemaCode
	  * @return number
	  */
	 function isInCinemas($appInfo,$businessCode){

	 	$cinemaList = ',' . $appInfo['cinemaGroupInfo']['cinemaList'] . ',';
	 	return strstr($cinemaList, ',' . $businessCode . ',') ? 0 : 1;
	 }
}