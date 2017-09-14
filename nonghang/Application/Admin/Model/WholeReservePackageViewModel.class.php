<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class WholeReservePackageViewModel extends ViewModel{
		public $viewFields=array(
				//数组当，每一个元素（下素和值）就是我们要查的一个表，要标就是我们要查的这个表的不带表前缀的表名
				//后面值，也是一个值（数据）  数组当的每一个元素，就是一个字
				'whole_reserve_relation'=>array('id','reserveId','num','relationId','type','_type'=>'LEFT' ),		
				'whole_package_information'=>array('name','detail','price','state', 'storePath' ,'relativePath','oriPrice','_on'=>'whole_reserve_relation.relationId=whole_package_information.id'),
		);
}
?>