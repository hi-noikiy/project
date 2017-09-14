<?php
/**
 * 数据分析
 */

namespace Admin\Controller;
use Think\Controller;
class CouponsController extends AdminController {

	/**
     *抢券列表
     */
    public function couponslist(){
    	
        // if (IS_AJAX) {
        //     echo '111';
        //     die();
        // }else{
        //     $this->couponsList = D('Coupons')->couponsList('','','','couponId desc, buyingStartTime desc');
        //     // print_r($this->couponsList);
        //     $this->display();
        // }


        // $this->limit = 5;


        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));


        $count = D ( 'Coupons' )->getCouponsListCount ($map);
        $allPage = ceil ( $count / $this->limit);
        $curPage = $this->curPage ( $nowPage, $allPage );
        $startLimit = ($curPage - 1) * $this->limit;
        if ($count > $this->limit) {
            $this->page = $this->getPageList ( $count, $this->limit, array());
        }


        $this->couponsList= D('Coupons')->couponsList('', $map, ($nowPage - 1) * $this->limit . ',' . $this->limit,'couponId desc, buyingStartTime desc');
        $this->display();

    	
    }

    public function addCoupons()
    {
        if (IS_AJAX) {
            $data = I('request.data');

            // print_r($data);
            if (empty($data['couponName'])) {
                $this->error('票券商品名称不能为空');
            }

            if (empty($data['voucherStartDate'])) {
                $this->error('票券开始生效时间不能为空');
            }

            if (empty($data['voucherEndDate'])) {
                $this->error('票券到期时间不能为空');
            }

            if (empty($data['couponDescription'])) {
                $this->error('兑换说明不能为空');
            }

            if (empty($data['couponRmark'])) {
                $this->error('兑换备注不能为空');
            }

            if ((int)$data['couponSum'] == 0) {
                $this->error('票券数量不能为空');
            }

            if ((float)$data['oldPrice'] == 0) {
                $this->error('原价不能为空');
            }

            if ((float)$data['newPrice'] == 0) {
                $this->error('售价不能为空');
            }

            // $upload = new \Think\Upload(); // 实例化上传类
            // $upload->maxSize   =     3145728 ;// 设置附件上传大小
            // $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            // $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
            // $upload->savePath  =     'Coupons/'; // 设置附件上传（子）目录 // 上传文件
            // $info   =   $upload->upload();

            // // print_r($upload->uploadOne($_FILES['']));
            // if (!$info) {
            //     $this->error($upload->getError());
            // }

            // $fileName = key($info);
            // $info = $info[$fileName];
            // $data['couponPic'] = $upload->rootPath . $info['savepath'] . $info['savename'];

            $data['buyingStartTime'] = strtotime($data['buyingStartTime']);
            $data['buyingEndTime'] = strtotime($data['buyingEndTime']);
            $data['voucherStartDate'] = strtotime($data['voucherStartDate']);
            $data['voucherEndDate'] = strtotime($data['voucherEndDate']);
            if (D('Coupons')->addCoupons($data)) {
                $this->success('抢券商品添加成功！');
            }else{
                $this->error('抢券商品添加失败！');
            }
            
        }else{
            $this->voucherTypeList = D('Voucher')->getVoucherTypeList('',$map);
            $this->cinemaList = D('Cinema')->getCinemaList();
            $this->display('couponsfrom');
        }
    }


    public function setCoupons($couponId)
    {
        if (IS_AJAX) {
            $data = I('request.data');

            // print_r($data);
            if (empty($data['couponName'])) {
                $this->error('票券商品名称不能为空');
            }

            if (empty($data['voucherStartDate'])) {
                $this->error('票券开始生效时间不能为空');
            }

            if (empty($data['voucherEndDate'])) {
                $this->error('票券到期时间不能为空');
            }

            if (empty($data['couponDescription'])) {
                $this->error('兑换说明不能为空');
            }

            if (empty($data['couponRmark'])) {
                $this->error('兑换备注不能为空');
            }

            if ((int)$data['couponSum'] == 0) {
                $this->error('票券数量不能为空');
            }

            if ((float)$data['oldPrice'] == 0) {
                $this->error('原价不能为空');
            }

            if ((float)$data['newPrice'] == 0) {
                $this->error('售价不能为空');
            }

            $data['buyingStartTime'] = strtotime($data['buyingStartTime']);
            $data['buyingEndTime'] = strtotime($data['buyingEndTime']);
            $data['voucherStartDate'] = strtotime($data['voucherStartDate']);
            $data['voucherEndDate'] = strtotime($data['voucherEndDate']);
            $map['couponId'] = $couponId;
            if (D('Coupons')->setCoupons($data, $map)) {
                $this->success('抢券商品更新成功！');
            }else{
                $this->error('抢券商品更新失败！');
            }
        }else{
            $this->data = D('Coupons')->getCouponsInfoByCouponId($couponId);
            // print_r($this->data);
            $this->voucherTypeList = D('Voucher')->getVoucherTypeList('',$map);
            $this->cinemaList = D('Cinema')->getCinemaList();
            $this->display('couponsfrom'); 
        }
        
    }


	function report(){

        $serachDataCardId = I('request.serachData_cardId');
        if (!empty($serachDataCardId)) {
            $userMap['mobile'] = $serachDataCardId;
            $userInfo = D('Member')->where($userMap)->find();
            // print_r($userInfo);
            $map['userId'] = $userInfo['id'];
            $pageData['serachDataCardId'] = $serachDataCardId;
        }

        $status = I('request.status');
        if ($status != -1) {
            $map['status'] = $status;
            $pageData['status'] = $status;
        }

		$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
		$count = D ( 'orderCoupons' )->where($map)->count ();
		$allPage = ceil ( $count / $this->limit);
		$curPage = $this->curPage ( $nowPage, $allPage );
		$startLimit = ($curPage - 1) * $this->limit;
		if ($count > $this->limit) {
			$showPage = $this->getPageList ( $count, $this->limit, $pageData );
		}
		$this->assign('page',$showPage);
		$orders=D('orderCoupons')->where($map)->order('couponOrderId desc')->limit($startLimit,$this->limit)->select();
		$allprice=D('orderCoupons')->field('sum(couponPrice) as allprice')->where($map)->find();
		foreach ($orders as $k=>$v){
			$orders[$k]['time']=date('Y-m-d H:i:s',$v['orderTime']);
			if($v['status']=='0'){
				$orders[$k]['statuStr']='未完成';
			}elseif($v['status']=='2'){
				$orders[$k]['statuStr']='已取消';
			}elseif($v['status']=='3'){
                $orders[$k]['statuStr']='已完成';
            }
		}
        $this->pageData = $pageData;
		$this->orders=$orders;
		$this->count=$count;
		$this->allprice=round($allprice['allprice'],2);
		$this->display();
	}


    public function reportOut($value='')
    {
        $serachDataCardId = I('request.serachData_cardId');
        if (!empty($serachDataCardId)) {
            $userMap['mobile'] = $serachDataCardId;
            $userInfo = D('Member')->where($userMap)->find();
            // print_r($userInfo);
            $map['userId'] = $userInfo['id'];
            $pageData['serachDataCardId'] = $serachDataCardId;
        }

        $status = I('request.status');
        if ($status != -1) {
            $map['status'] = $status;
            $pageData['status'] = $status;
        }
        $orders=D('orderCoupons')->field('couponOrderId, couponName, voucherType, FROM_UNIXTIME(voucherStartDate,"%Y-%m-%d %H:%i") as voucherStartDate,  FROM_UNIXTIME(voucherEndDate,"%Y-%m-%d %H:%i") as voucherEndDate, couponPrice, couponSum, FROM_UNIXTIME(orderTime,"%Y-%m-%d %H:%i") as orderTime, voucherList, status')->where($map)->order('couponOrderId desc')->select();
        $title = array('订单ID','票券名称','票类型ID','生效时间','到期时间','订单总额','票券数量','下单时间','获取票券','订单状态');
        exportexcel($orders,$title);
    }

    public function delCoupons($couponId)
    {
        if (D('Coupons')->delCoupons($couponId)) {
            $this->success('抢券商品删除成功！');
        }else{
            $this->error('抢券商品删除失败！');
        }
    }


    public function reportinfo($couponOrderId)
    {
        $map['couponOrderId'] = $couponOrderId;
        $map['status'] = 3;
        $orders=D('orderCoupons')->where($map)->order('couponOrderId desc')->find();
        $voucherList = explode(',', $orders['voucherList']);
        foreach ($voucherList as $key => $value) {

            if (!empty($value)) {
            
                $orderMap['status'] = array('in', '3,9');
                $orderMap['otherPayInfo'] = array('LIKE', '%' . $value . '%');
                $orderList = D('Order')->findObj($orderMap);

                // print_r($orderList);

                $newVoucherList[$key]['status'] = '未使用';
                $newVoucherList[$key]['orderCode'] = '---';
                if (!empty($orderList['orderCode'])) {
                   $newVoucherList[$key]['status'] = '已使用';
                   $newVoucherList[$key]['orderCode'] = $orderList['orderCode'];
                }

                $newVoucherList[$key]['voucherInfo'] = $value;
            }

        }
        $this->voucherList = $newVoucherList;
        $this->display();
    }
	

	
}