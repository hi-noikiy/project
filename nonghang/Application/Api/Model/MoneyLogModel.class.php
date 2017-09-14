<?php

namespace Api\Model;
use Think\Model;

class MoneyLogModel extends Model {
	function getList($field='',$map=array(),$type='',$start=0,$limit=99999){
		if($type=='1'){  //购票
			$map['type']=array('in','0,4,5,6,8');
		}elseif($type=='2'){  //充值
			$map['type']=array('in','2,3,9');
		}elseif($type=='3'){ //商品
			$map['type']=array('in','10,11,12,13,14,15,16');
		}
		$moneys=M('MoneyLog')->field($field)->where($map)->order('createTime desc')->limit($start,$limit)->select();
		foreach ($moneys as $k=>$v){
			$str='';
			if($v['type']=='0'){
				$str.='会员卡购票';
				$moneys[$k]['icon']='icon1';
			}elseif($v['type']=='1'){
				$str.='退款到会员卡';
				$moneys[$k]['icon']='tk';
			}elseif($v['type']=='2'){
				$str.='微信充值';
				$moneys[$k]['icon']='weixin';
			}elseif($v['type']=='3'){
				$str.='支付宝充值';
				$moneys[$k]['icon']='zfb';
			}elseif($v['type']=='4'){
				$str.='微信/余额购票';
				$moneys[$k]['icon']='weixin';
			}elseif($v['type']=='5'){
				$str.='支付宝/余额购票';
				$moneys[$k]['icon']='zfb';
			}elseif($v['type']=='6'){
				$str.='手机余额购票';
				$moneys[$k]['icon']='icon1';
			}elseif($v['type']=='7'){
				$str.='退款到余额';
				$moneys[$k]['icon']='icon1';
			}elseif($v['type']=='8'){
				$str.='银联购票';
				$moneys[$k]['icon']='icon1';
			}elseif($v['type']=='9'){
				$str.='银联充值';
				$moneys[$k]['icon']='icon1';
			}elseif($v['type']=='10'){
				$str.='其他方式购物';
				$moneys[$k]['icon']='icon1';
			}elseif($v['type']=='11'){
				$str.='微信购物';
				$moneys[$k]['icon']='icon1';
			}elseif($v['type']=='12'){
				$str.='支付宝购物';
				$moneys[$k]['icon']='zfb';
			}elseif($v['type']=='13'){
				$str.='银联购物';
				$moneys[$k]['icon']='icon1';
			}elseif($v['type']=='14'){
				$str.='微信团购';
				$moneys[$k]['icon']='icon1';
			}elseif($v['type']=='15'){
				$str.='支付宝团购';
				$moneys[$k]['icon']='icon1';
			}elseif($v['type']=='16'){
				$str.='银联团购';
				$moneys[$k]['icon']='icon1';
			}
			if(in_array($v['type'], array(0,4,5,6,8))){
				$moneys[$k]['payName']='购票';
			}elseif(in_array($v['type'], array(2,3,9))){
				$moneys[$k]['payName']='充值';
			}elseif(in_array($v['type'], array(10,11,12,13,14,15,16))){
				$moneys[$k]['payName']='购物';
			}
			$v['money']=number_format($v['money'],1 ,'.','');
			if(!in_array($v['type'], array('1','2','3','7','9'))){
				$moneys[$k]['payMoney']='支出：￥'.$v['money'];
			}elseif(in_array($v['type'], array('1','2','3','7','9'))){
				$moneys[$k]['payMoney']='收入：￥'.$v['money'];
			}
			if($v['incIntegral']>0){
				$v['incIntegral']='+'.$v['incIntegral'];
			}
			$moneys[$k]['payIntegral']='积分：'.$v['incIntegral'];
			$moneys[$k]['time']=date('Y-m-d H:i',$v['createTime']);
			$moneys[$k]['typestr']=$str;
		}
		return $moneys;
	}
}