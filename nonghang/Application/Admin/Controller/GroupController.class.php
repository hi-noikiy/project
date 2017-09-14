<?php
/**
 * 首页
 */

namespace Admin\Controller;
use Think\Controller;
class GroupController extends AdminController {
    
	/**
	 * 商品列表
	 */
    public function goods() {
    	$data['id']=I('id');
    	$data['sellerNo']=I('sellerNo');
    	$data['goodsName']=I('goodsName');
    	if(!empty($data['id'])){
    		$map['id']=$data['id'];
    	}
    	if(!empty($data['sellerNo'])){
    		$map['sellerNo']=$data['sellerNo'];
    	}
    	if(!empty($data['goodsName'])){
    		$map['goodsName']=array('like','%'.$data['goodsName'].'%');
    	}
    	$count = D ( 'goods' )->countRound($map);
    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
    	$allPage = ceil ( $count / $this->limit);
    	$curPage = $this->curPage ( $nowPage, $allPage );
    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList ( $count, $this->limit, $data);
    		$this->assign('page',$showPage);
    	}
    	$sellers=D('goods')->getSellers();
    	$goods=D('goods')->getRoundGoods($map,$startLimit,$this->limit);
    	$this->assign('data',$data);
    	$this->assign('sellers',$sellers);
    	$this->assign('goods',$goods);
    	$this->display();
    }
    
    /**
     *添加上传图片
     */
    public function addUpload(){
    	$upload = new \Think\Upload(); // 实例化上传类
    	$upload->maxSize   =     3145728 ;// 设置附件上传大小
    	$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    	$upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
    	$upload->savePath  =     'thinkEditor/'; // 设置附件上传（子）目录 // 上传文件
    	$info   =   $upload->upload();
        $key = key($info);
        $data['originalName'] = $info[$key]['name'];
        $data['name'] = $info[$key]['savename'];
        $data['url'] = '/' . $upload->rootPath . $info[$key]['savepath'] . $info[$key]['savename'];
        $data['size'] = $info[$key]['size'];
        $data['type'] = $info[$key]['ext'];
        $data['state'] = 'SUCCESS';
    	die(json_encode($data));
    }
    
    /**
     * 订单记录
     */
    function report(){
    	$data['id']=I('id');
    	$data['loginNum']=I('loginNum');
    	$data['cinemaCode']=I('cinemaCode');
    	$data['start']=I('start');
    	$data['end']=I('end');
    	$data['status']=I('status');
    	if(!empty($data['id'])){
    		$map['id']=$data['id'];
    	}
    	if(!empty($data['loginNum'])){
    		$map['_string']='cardNum='.$data['loginNum'].' or mobileNum='.$data['loginNum'];
    	}
    	if(!empty($data['cinemaCode'])){
    		$map['cinemaCode']=$data['cinemaCode'];
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
    	$count = D ( 'goods' )->countRoundReport ($map);
    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
    	$allPage = ceil ( $count / $this->limit);
    	$curPage = $this->curPage ( $nowPage, $allPage );
    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList ( $count, $this->limit, $data);
    		$this->assign('page',$showPage);
    	}
    	$cinemaList=D('cinema')->getCinemaList();
    	$orders=D('goods')->roundReport('',$map,$startLimit,$this->limit);
    	$allprice = D('goods')->getRoundSum('price', $map);
    	$show['count']=$count;
    	$show['allprice']=round($allprice, 2);
    	$this->assign('show',$show);
    	$this->assign('cinemaList',$cinemaList);
    	$this->assign('data',$data);
    	$this->assign('orders',$orders);
    	$this->display();
    }
    
    /**
     * 添加或编辑卖品
     */
    public function goods_form(){
    	$id=I('id');
    	if(IS_POST){
    		$data=I('data');
    		$upload = new \Think\Upload(); // 实例化上传类
    		$upload->maxSize   =     3145728 ;// 设置附件上传大小
    		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
    		$upload->savePath  =     'goods/'; // 设置附件上传（子）目录
    		// 上传文件
    		$info   =   $upload->upload();
    		if($info['goodsImg']){		
    			$data['goodsImg']=$info['goodsImg']['savepath'].$info['goodsImg']['savename'];
    		}
    		//iconv("GB2312", "UTF-8", $data['explain']);
    		if(!empty($id)){
    			$data['id']=$id;
    			if(D('goodsRound')->save($data)){
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
    			if(D('goodsRound')->add($data)){
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
    		$sellers=D('goods')->getSellers();
    		if(empty($sellers)){
    			die('请先添加商户信息');
    		}
    		if(!empty($id)){
    			$goods=D('goods')->getRoundGood(array('id'=>$id));
    			$this->assign('goods',$goods);
    		}
    		$this->assign('sellers',$sellers);
    		$this->display();
    	}
    }
    
    /**
     * 删除卖品
     */
    function delGoods(){
    	$id=I('id');
    	if(D('goodsRound')->delete($id)){
    		$status=0;
    	}else{
    		$status=1;
    	}
    	echo $status;
    }
    
    /**
     * 商户列表
     */
    public function merchant() {
    	$this->limit=5;
    	$data['id']=I('id');
    	$data['sellerName']=I('sellerName');
    	$data['cinemaCode']=I('cinemaCode');
    	if(!empty($data['id'])){
    		$map['id']=$data['id'];
    	}
    	if(!empty($data['sellerName'])){
    		$map['sellerName']=array('like','%'.$data['sellerName'].'%');
    	}
    	if(!empty($data['cinemaCode'])){
    		$map['cinemaCode']=$data['cinemaCode'];
    	}
    	$count = D ( 'goods' )->countSellers ($map);
    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
    	$allPage = ceil ( $count / $this->limit);
    	$curPage = $this->curPage ( $nowPage, $allPage );
    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList ( $count, $this->limit, $data);
    		$this->assign('page',$showPage);
    	}
    	$sellers=D('goods')->getSellers($map,$startLimit,$this->limit);
    	$cinemaList=D('cinema')->getCinemaList();
    	$this->assign('cinemaList',$cinemaList);
    	$this->assign('data',$data);
    	$this->assign('sellers',$sellers);
    	$this->display();
    }
    
    /**
     * 添加或编辑商户
     */
    public function merchant_form() {
    	$id=I('id');
    	if(IS_POST){
    		$data=I('data');
    		$data['passwd']=chrent_md5($data['passwd']);
    		if(!empty($id)){
    			$data['id']=$id;
    			$seller=D('goodsSeller')->find($id);
    			if($seller['account']!=$data['account']){
    				if(D('goods')->hasSeller($data['account'])){
    					echo '<script>';
    					echo 'parent.alert("已存在该帐号");';
    					echo 'parent.parent.closeIndex();';
    					echo '</script>';
    					exit;
    				}
    			}
    			if(D('goodsSeller')->save($data)){
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
    			if(D('goods')->hasSeller($data['account'])){
    				echo '<script>';
    				echo 'parent.alert("已存在该帐号");';
    				echo 'parent.parent.closeIndex();';
    				echo '</script>';
    				exit;
    			}
    			if(D('goodsSeller')->add($data)){
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
    		$cinemaList=D('cinema')->getCinemaList();
    		if(empty($cinemaList)){
    			die('可编辑影院列表为空');
    		}
    		if(!empty($id)){
    			$seller=D('goodsSeller')->find($id);
    			$this->assign('seller',$seller);
    		}
    		$this->assign('cinemaList',$cinemaList);
    		$this->display();
    	}
    }
    /**
     * 删除商户
     */
    function delMerchant(){
    	$id=I('id');
    	if(D('goodsSeller')->delete($id)){
    		$status=0;
    	}else{
    		$status=1;
    	}
    	echo $status;
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
    			"1"=>"卖品名称",
    			"2"=>"归属商家",
    			"3"=>"支付金额",
    			"4"=>"订单时间",
    			"5"=>"归属影城",
    			"6"=>"支付方式",
    			"7"=>"支付状态",
    			"8"=>"会员卡号/手机号",
    	);
    	$data['id']=I('id');
    	$data['loginNum']=I('loginNum');
    	$data['cinemaCode']=I('cinemaCode');
    	$data['start']=I('start');
    	$data['end']=I('end');
    	$data['status']=I('status');
    	if(!empty($data['id'])){
    		$map['id']=$data['id'];
    	}
    	if(!empty($data['loginNum'])){
    		$map['_string']='cardNum='.$data['loginNum'].' or mobileNum='.$data['loginNum'];
    	}
    	if(!empty($data['cinemaCode'])){
    		$map['cinemaCode']=$data['cinemaCode'];
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
    	$orders=D('goods')->roundReport("id,cardNum,mobileNum,goodsName,sellerName,otherpay,FROM_UNIXTIME(downTime,'%Y-%m-%d %H:%i') as stime,cinemaName,sellerName,payType,status",$map);
    	
    	foreach ($orders as $k=>$v){
    		unset($orders[$k]['cardNum']);
    		unset($orders[$k]['mobileNum']);
    		unset($orders[$k]['otherPayInfo']);
    	}
    	exportexcel($orders,$title);
    }
    
    
}