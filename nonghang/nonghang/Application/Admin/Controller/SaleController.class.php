<?php
/**
 * 卖品
 */

namespace Admin\Controller;
use Think\Controller;
class SaleController extends AdminController {
	
	/**
	 * 商品列表
	 */
    public function goods() {
    	$data['goodsName']=I('goodsName');
    	$data['cinemaCode']=I('cinemaCode');
    	if(!empty($data['goodsName'])){
     		$map['goodsName']=array('like','%'.$data['goodsName'].'%');
     	}
    	if(!empty($data['cinemaCode'])){
    		$map['cinemaCode']=$data['cinemaCode'];
    	}
    	$count = D ( 'goods' )->countGoods($map);
    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
    	$allPage = ceil ( $count / $this->limit);
    	$curPage = $this->curPage ( $nowPage, $allPage );
    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList ( $count, $this->limit, $data);
    		$this->assign('page',$showPage);
    	}
    	$goods=D('goods')->getCinemaGoods($map,$startLimit,$this->limit);
    	$cinemaList=D('cinema')->getCinemaList();
    	$this->assign('data',$data);
    	$this->assign('cinemaList',$cinemaList);
    	$this->assign('goods',$goods);
    	$this->display();
    }
    
    /**
     * 添加或编辑卖品
     */
    public function goods_form(){
    	$id=I('id');
    	$userInfo = session('adminUserInfo');
    	if(IS_POST){
    		$data=I('data');
    		$cinemaGroupId=I('cinemaGroupId');
    		$upload = new \Think\Upload(); // 实例化上传类
    		$upload->maxSize   =     3145728 ;// 设置附件上传大小
    		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
    		$upload->savePath  =     'goods/'; // 设置附件上传（子）目录
    		// 上传文件
    		$info   =   $upload->upload();
    		if(empty($cinemaGroupId)&&$userInfo['cinemaGroup'] != '-1'){
    			$cinemaGroupId=$userInfo['cinemaGroup'];
    		}
    		$data['cinemaGroupId']=$cinemaGroupId;
    		if($info['image']){		
    			$data['goodsImg']=$info['image']['savepath'].$info['image']['savename'];
    		}
    		if(!empty($id)){
    			$data['id']=$id;
    			if(D('goods')->save($data)){
    				echo '<script>';
    				echo 'parent.parent.location.reload();';
    				echo 'parent.parent.closeIndex();';
    				echo '</script>';
    				exit;
    			}else{
    				echo '<script>';
    				echo 'parent.alert("修改数据失败");';
    				echo 'parent.parent.closeIndex();';
    				echo '</script>';
    				exit;
    			}
    		}else{
    			if(D('goods')->add($data)){
    				echo '<script>';
    				echo 'parent.parent.location.reload();';
    				echo 'parent.parent.closeIndex();';
    				echo '</script>';
    				exit;
    			}else{
    				echo '<script>';
    				echo 'parent.alert("添加数据失败");';
    				echo 'parent.parent.closeIndex();';
    				echo '</script>';
    				exit;
    			}
    		}
    	}else{
    		
    		if($userInfo['cinemaGroup'] == '-1'){
    			$cinemagroups=D('cinemaGroup')->where()->select();
    			$this->assign('cinemagroups',$cinemagroups);
				$cinemaList=D('cinema')->_getCinemaList('',array('id'=>$cinemagroups[0]['id']));
    		}else{
    			$cinemaList=D('cinema')->_getCinemaList('',array('id'=>$userInfo['cinemaGroup']));
    		}
    		if(empty($cinemaList)){
    			die('可编辑影院列表为空');
    		}
    		if(!empty($id)){
    			$goods=D('goods')->find($id);
    			$this->assign('goods',$goods);
    		}
    		$this->assign('cinemaList',$cinemaList);
    		$this->display();
    	}
    }
    
    function getCinemas(){
    	$cinemaList=D('cinema')->_getCinemaList('',array('id'=>I('cinemaGroupId')));
    	$this->success('',$cinemaList);
    }
    /**
     * 删除卖品
     */
     function delGoods(){
     	$id=I('id');
     	if(D('goods')->delete($id)){
     		$status=0;
     	}else{
     		$status=1;
     	}
     	echo $status;
     }

     /**
      * 订单记录
      */
     function report(){
     	$data['id']=I('id');
     	$data['cinemaCode']=I('cinemaCode');
     	$data['loginNum']=I('loginNum');
     	$data['start']=I('start');
     	$data['end']=I('end');
     	$data['status']=I('status');
     	if(!empty($data['id'])){
     		$map['id']=$data['id'];
     	}
     	if(!empty($data['cinemaCode'])){
     		$map['cinemaCode']=$data['cinemaCode'];
     	}
     	if(!empty($data['loginNum'])){
     		$map['_string']='cardNum="'.$data['loginNum'].'" or mobileNum="'.$data['loginNum'].'"';
     	}
     	$start=$data['start'];
     	$end=$data['end'];
     	if(!empty($start)&&!empty($end)){
     		$map['downTime']= array(array('egt',strtotime($start)),array('elt',strtotime($end)+24*60*60));
     	}elseif(!empty($start)){
     		$map['downTime']= array(array('egt',strtotime($start)));
     	}elseif(!empty($end)){
     		$map['downTime']= array(array('elt',strtotime($end)+24*60*60));
     	}
    	 if(($data['status']!='-1'&&!empty($data['status']))||$data['status']=='0'){
    		$map['status']=$data['status'];
    	}
    	$count = D ( 'goods' )->countGoodsReport ($map);
    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
    	$allPage = ceil ( $count / $this->limit);
    	$curPage = $this->curPage ( $nowPage, $allPage );
    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList ( $count, $this->limit, $data);
    		$this->assign('page',$showPage);
    	}
     	$orders=D('goods')->goodsReport('',$map,$startLimit,$this->limit);
     	$cinemaList=D('cinema')->getCinemaList();
     	$allprice = D('goods')->getGoodsSum('price', $map);
     	$show['count']=$count;
     	$show['allprice']=round($allprice, 2);
     	$this->assign('show',$show);
     	$this->assign('cinemaList',$cinemaList);
     	$this->assign('data',$data);
     	$this->assign('orders',$orders);
     	$this->display();
     }
     /**
      * 打印订单信息
      *
      * @param string $id
      * @param string $cardId
      * @param string $cinemaCode
      * @param string $status
      */
     public function salePort(){
     	$title=array(
     			"0"=>"订单号",
     			"1"=>"订单时间",
     			"2"=>"归属影城",
     			"3"=>"积分抵值",
     			"4"=>"支付金额",
     			"5"=>"支付方式",
     			"6"=>"支付状态",
     			"7"=>"兑换状态",
     			"8"=>"会员卡号/手机号",
     			"9"=>"卖品抵值券",
     			"10"=>"卖品名称",
     	);
     	$data['id']=I('id');
     	$data['cinemaCode']=I('cinemaCode');
     	$data['loginNum']=I('loginNum');
     	$data['start']=I('start');
     	$data['end']=I('end');
     	$data['status']=I('status');
     	if(!empty($data['id'])){
     		$map['id']=$data['id'];
     	}
     	if(!empty($data['cinemaCode'])){
     		$map['cinemaCode']=$data['cinemaCode'];
     	}
     	if(!empty($data['loginNum'])){
     		$map['_string']='cardNum="'.$data['loginNum'].'" or mobileNum="'.$data['loginNum'].'"';
     	}
     	$start=$data['start'];
     	$end=$data['end'];
     	if(!empty($start)&&!empty($end)){
     		$map['downTime']= array(array('egt',strtotime($start)),array('elt',strtotime($end)+24*60*60));
     	}elseif(!empty($start)){
     		$map['downTime']= array(array('egt',strtotime($start)));
     	}elseif(!empty($end)){
     		$map['downTime']= array(array('elt',strtotime($end)+24*60*60));
     	}
    	 if(($data['status']!='-1'&&!empty($data['status']))||$data['status']=='0'){
    		$map['status']=$data['status'];
    	}
     	$orders=D('goods')->goodsReport("id,cardNum,mobileNum,FROM_UNIXTIME(downTime,'%Y-%m-%d %H:%i') as stime,cinemaName,otherPayInfo,integral,balance,payType,status,exstatus",$map);
     	foreach ($orders as $k=>$v){
     		unset($orders[$k]['cardNum']);
     		unset($orders[$k]['mobileNum']);
     		unset($orders[$k]['goodsName']);
     		unset($orders[$k]['otherPayInfo']);
     	}
     	exportexcel($orders,$title);
     }
}