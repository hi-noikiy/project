<?php
namespace Home\Model;
use Think\Model\ViewModel;
class WholePlanViewModel extends ViewModel{
		public $viewFields=array(
				//数组当，每一个元素（下素和值）就是我们要查的一个表，要标就是我们要查的这个表的不带表前缀的表名
				//后面值，也是一个值（数据）  数组当的每一个元素，就是一个字
				'whole_configuration_details'=>array('id','filmNo','price','fullHousePrice','favorablePrice','serviceCharge','_type'=>'LEFT'),
				'whole_plan_number'=>array('time','_on'=>'whole_configuration_details.planNumberId=whole_plan_number.id' ,'_type'=>'LEFT'),		
				'film'=>array('filmName','image','lang','cast', 'duration' ,'version','_on'=>'film.filmNo=whole_configuration_details.filmNo'),
		);
}

?>