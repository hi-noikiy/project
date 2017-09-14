<?php
/**
 * 注释
 */
namespace Api\Controller;
use Think\Controller;
class TestController extends Controller {

	/**
	 * 测试
	 */
   function test(){

   /*	$order=M('orderFilm')->find('1062015111252754');
   	 $queryOrderStatusarr=array(
    				'cinemaCode'=>$order['cinemaCode'],
    				'orderCode'=>$order['orderCode'],
    				'serialNum'=>$order['serialNum'],
    				'ticketCount'=>$order['seatCount'],
    		);
   	 $result=D('ZMMove')->queryOrderStatus($queryOrderStatusarr);//查状态
   	 print_r($result);
   	 die();*/
	$arr=array('cinemaCode'=>'35021502');
	$result = D('ZMMove')->getCinema($arr);
   print_r($result);
      die();


      
      $str='123456';
      $strlength=mb_strlen($str);
      $hash = '';
      $chars = 'ABCDEFGHJKLMNPQRSTUVWXYabcdefghjkmnpqrstuvwxy';
      $max = strlen($chars) - 1;
      for($i = 0; $i < 3; $i++) {
         $inof[]=mt_rand(0, $strlength-1);
      }
      $str=str_split($str, 1);
      foreach ($str as $k=>$v){
         if(in_array($k, $inof)){
            $str[$k]=$v.$chars[mt_rand(0, $max)];
         }
      }
      print_r(implode('', $str));
      die();
      /*$cinema=D('cinema')->find(11051601);
      $alipayConfig = json_decode($cinema['alipayConfig'], true);
      echo $alipayConfig['publicKey'];
      echo '<br/>';
      $str=getKeyInfo($alipayConfig['publicKey'],26);
      die($str);*/
      /*$x=new \Think\HfhMove();
      $arr=array(
      'cinemaCode'=>'11051601',
      'link'=>'999',
      'hallNo'=>'5',
      'filmNo'=>'00110516201301',
      'showSeqNo'=>'8',
      'planDate'=>'2015-09-18',//'1442516700',
      'loginNum'=>'9991200000009',
      'password'=>'123456',
      );
      $x->getCinemaPlan($arr);*/
      $url=C('PAY_URL').'order/account_app/orderid/'.$orderid;

      echo $url;
      $result=getCurlResult($url, 1);

      print_r($result);
      die();
   }
}