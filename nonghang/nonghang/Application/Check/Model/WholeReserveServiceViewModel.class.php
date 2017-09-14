<?php
// +----------------------------------------------------------------------
// | 包场订单附加服务视图列表
// +----------------------------------------------------------------------
// | 包场系统
// +----------------------------------------------------------------------
// | Author: jcjtim
// +----------------------------------------------------------------------
namespace Check\Model;
use Think\Model\ViewModel;
class WholeReserveServiceViewModel extends ViewModel{
		public $viewFields=array(
				//数组当，每一个元素（下素和值）就是我们要查的一个表，要标就是我们要查的这个表的不带表前缀的表名
				//后面值，也是一个值（数据）  数组当的每一个元素，就是一个字
				'whole_reserve_relation'=>array('id','reserveId','relationId','type','_type'=>'LEFT' ),		
				'whole_accessorial_service'=>array('name','price','num','_on'=>'whole_reserve_relation.relationId=whole_accessorial_service.id'),
		);
}
?>