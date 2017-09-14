<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class WholeReserveViewModel extends ViewModel{
		public $viewFields=array(
				//数组当，每一个元素（下素和值）就是我们要查的一个表，要标就是我们要查的这个表的不带表前缀的表名
				//后面值，也是一个值（数据）  数组当的每一个元素，就是一个字
				'whole_reserve'=>array('id','viewingDate','num','paymentMethod','filmNo','paymentState','state','changeState','total','prepay','code','paymentTime','tel','detail','uid','_type'=>'LEFT'),
				'whole_video_information'=>array('topicName','videoCode','_on'=>'whole_reserve.videoId=whole_video_information.id' ,'_type'=>'LEFT'),
				'whole_payment_type'=>array('name'=>'paymentypeName','_on'=>'whole_reserve.paymentTypeId=whole_payment_type.id' ,'_type'=>'LEFT'),		
				'film'=>array('filmName','image','lang','cast', 'duration' ,'version','_on'=>'film.filmNo=whole_reserve.filmNo'),
		);
}
?>