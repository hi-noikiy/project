<?php
/**
 * 首页
 */

namespace Admin\Controller;
use Think\Controller;
class MarketingController extends AdminController {

    public function goods()
    {
        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
        $count = D ('Buying')->getBuyingCount ($map);
        $allPage = ceil ( $count / $this->limit);

        $curPage = $this->curPage ( $nowPage, $allPage );
        $startLimit = ($curPage - 1) * $this->limit;
        if ($count > $this->limit) {
            $showPage = $this->getPageList ( $count, $this->limit, array() );
        }
        $this->assign('page',$showPage);

        $map['filmStartTime'] = array('gt', date('Y-m-d H:i'));

        if ($count > $this->limit) {
            $showPage = $this->getPageList ( $count, $this->limit, array() );
        }
        $buyingList = D('Buying')->getBuyingList('', $map, ($nowPage - 1) * $this->limit . ',' . $this->limit, 'filmStartTime desc');

        print_r($buyingList);

        $this->assign('buyingList',$buyingList);
        $this->display();
    }


    public function addgoods()
    {

    	if (IS_AJAX) {

    		$data = I('request.data');
    		$upload = new \Think\Upload(); // 实例化上传类
			$upload->maxSize   =     3145728 ;// 设置附件上传大小
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->rootPath  =     '.' . C('__UPLOAD__') . '/'; // 设置附件上传根目录
			$upload->savePath  =     'Buying/'; // 设置附件上传（子）目录
			// 上传文件
			$info   =   $upload->upload();
            $data['preView'] = '';
            $image = new \Think\Image(); 
            if ($info['preView']) {
                $image->open('.' . C('__UPLOAD__') . '/' . $info['preView']['savepath'] . $info['preView']['savename']);
                $image->thumb(135, 180)->save('.' . C('__UPLOAD__') . '/' . $info['preView']['savepath'] . $info['preView']['savename']);
                $data['preView'] = $info['preView']['savepath'] . $info['preView']['savename'];
            }
            $data['seatView'] = '';
            if ($info['seatView']) {
                $image->open('.' . C('__UPLOAD__') . '/' . $info['seatView']['savepath'] . $info['seatView']['savename']);
                $image->thumb(600, 400)->save('.' . C('__UPLOAD__') . '/' . $info['seatView']['savepath'] . $info['seatView']['savename']);
                $data['seatView'] = $info['seatView']['savepath'] . $info['seatView']['savename'];
            }

            if(count($data['seat']) > 1){
                $data['seat'] = json_encode($data['seat']);
            }else{
                 $data['seat'] = '';
            }
            if (!D('Buying')->addCinemaBuying($data)) {
                unlink($data['preView']);
                unlink($data['seatView']);
                $this->error(D('Buying')->errorMsg);
            }else{
                $this->success('添加成功');
            }
    	} else {
    		$this->display('goodsfrom');
    	}
    }

    public function editGoods()
    {
        if (IS_AJAX) {
            $data = I('request.data');
            $upload = new \Think\Upload(); // 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     '.' . C('__UPLOAD__') . '/'; // 设置附件上传根目录
            $upload->savePath  =     'Buying/'; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->upload();
            $image = new \Think\Image(); 
            if ($info['preView']) {
                $image->open('.' . C('__UPLOAD__') . '/' . $info['preView']['savepath'] . $info['preView']['savename']);
                $image->thumb(135, 180)->save('.' . C('__UPLOAD__') . '/' . $info['preView']['savepath'] . $info['preView']['savename']);
                $data['preView'] = $info['preView']['savepath'] . $info['preView']['savename'];
            }
            // $data['seatView'] = '';
            if ($info['seatView']) {
                $image->open('.' . C('__UPLOAD__') . '/' . $info['seatView']['savepath'] . $info['seatView']['savename']);
                $image->thumb(600, 400)->save('.' . C('__UPLOAD__') . '/' . $info['seatView']['savepath'] . $info['seatView']['savename']);
                $data['seatView'] = $info['seatView']['savepath'] . $info['seatView']['savename'];
            }
            if(count($data['seat']) > 1){
                $data['seat'] = json_encode($data['seat']);
            }else{
                 $data['seat'] = '';
            }
            if (!D('Buying')->editCinemaBuying($data, array('buyingId' => $data['buyingId']))) {
                unlink('.' . C('__UPLOAD__') . '/' . $data['preView']);
                unlink('.' . C('__UPLOAD__') . '/' . $data['seatView']);
                $this->error(D('Buying')->errorMsg);
            }else{

                $this->success('添加成功');
            }
        }else{
            $buyingId = I('request.buyingId');
            $buyingInfo = D('Buying')->getBuyingInfoByBuyingId($buyingId);
            $buyingInfo['seat'] = json_decode($buyingInfo['seat'], true);
            // print_r($buyingInfo);
            $this->assign('buyingInfo',$buyingInfo);
            $this->display('goodsfrom');   
        }

    }

    public function delete($buyingId)
    {
        $buyingInfo = D('Buying')->getBuyingInfoByBuyingId($buyingId);
        if(D('Buying')->delBuying($buyingId)){
            unlink('.' . C('__UPLOAD__') . '/' . $buyingInfo['preView']);
            unlink('.' . C('__UPLOAD__') . '/' . $buyingInfo['seatView']);
            $this->success('删除成功！');
        }else{
            $this->error('删除失败，请重新！');
        }
    }

    public function report()
    {
        $serachData_orderCode = I('request.serachData_orderCode');
        $serachData['serachData_orderCode'] = $serachData_orderCode;

        if(!empty($serachData_orderCode)){
            $map['orderNo'] = $serachData_orderCode;
        }


        $serachData_cardId = I('request.serachData_cardId');
        $serachData['serachData_cardId'] = $serachData_cardId;
        if(!empty($serachData_cardId)){
            $map['_string'] = ' accountCardId = "' . $serachData_cardId . '" or accountMobile = "' . $serachData_cardId . '" ';
        }


        $serachData_mobile = I('request.serachData_mobile');
        $serachData['serachData_mobile'] = $serachData_mobile;
        if(!empty($serachData_mobile)){
            $map['userMobile'] = $serachData_mobile;
        }

        $serachData_status = I('request.serachData_status');
        $serachData['serachData_status'] = $serachData_status;
        if(!empty($serachData_status) && $serachData_status != -1 || $serachData_status === 0){
            $map['status'] = $serachData_status;
        }

        $serachData_start = I('request.serachData_start');
        $serachData['serachData_start'] = $serachData_start;
        if(!empty($serachData_start)){
            $map['addTime'] = array('gt', strtotime($serachData_start . ' 00:00:00'));
        }

        $serachData_end = I('request.serachData_end');
        $serachData['serachData_end'] = $serachData_end;
        if(!empty($serachData_end)){
            $map['addTime'] = array('lt', strtotime($serachData_end . ' 23:59:59'));
        }

        if (!empty($serachData_start) && !empty($serachData_end)) {
            $map['addTime'] = array(array('gt', strtotime($serachData_start . ' 00:00:00')), $map['addTime'] = array('lt', strtotime($serachData_end . ' 23:59:59')), 'and');
        }

        

        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
        $count = D ('Buying')->getBuyingUserOrderCount ($map);
        $allPage = ceil ( $count / $this->limit);

        $curPage = $this->curPage ( $nowPage, $allPage );
        $startLimit = ($curPage - 1) * $this->limit;

        

        // $map['filmStartTime'] = array('gt', date('Y-m-d H:i'));

        if ($count > $this->limit) {
            $showPage = $this->getPageList ( $count, $this->limit, $serachData );
        }
        $userOrderList = D('Buying')->getBuyingUserOrderList('', $map, ($nowPage - 1) * $this->limit . ',' . $this->limit, 'addTime desc');
        // print_r($userOrderList);

        $show['count'] = $count;
        $show['tickNum'] = D('Buying')->getBuyingUserOrderSum('tickNum', $map);
        $show['allprice'] = D('Buying')->getBuyingUserOrderSum('tickNum * tickPrice', $map);

        $this->assign('show',$show);
        $this->assign('page',$showPage);
        $this->assign('pageData',$serachData);
        $this->assign('userOrderList',$userOrderList);
        $this->display();   
    }


    public function cancelOrder($orderNo)
    {

        $orderInfo = D('Buying')->getBuyingUserOrderInfoByOrderNo($orderNo);
        // print_r($orderInfo);
        // die();
        $mtx = new \Think\MtxUser(array('appCode'=>'fzzr' ,'appPwd'=>'cmts20140428fzzr'));

        if($orderInfo['accountPlaceNo'] != '35012401'){
            $memberTransactionCancelData['sellcinemaCode'] = '35012401';
        }
        $memberTransactionCancelData['cinemaCode'] = $orderInfo['accountPlaceNo'];
        $memberTransactionCancelData['cardId'] = $orderInfo['accountCardId'];
        $memberTransactionCancelData['passWord'] = desDecrypt($orderInfo['accountCardPasswd'], '12345678');
        $memberTransactionCancelData['traceNo'] = $orderInfo['payInfo'];
        $memberTransactionCancelData['tracePrice'] = 0;
        $memberTransactionCancelData['price'] = $orderInfo['tickNum'] * $orderInfo['tickPrice'];
        $memberTransactionCancelData['traceMemo'] = '丝绸之路测试退票';
        $mtxPayInfo = $mtx->memberTransactionCancel($memberTransactionCancelData);
        if($mtxPayInfo['ResultCode'] == 0){
            if(D('Buying')->unlockSeat($orderInfo['orderId'])){
                if(D('Buying')->editOrderStatus(9, $orderInfo['orderId'])){
                    $this->success('退票成功！');
                }else{
                    $this->error('修改订单状态失败');
                }
            }else{
                $this->error('解锁座位失败！');
            }
            
        }else{
            $this->error($mtxPayInfo['Message']);
        }
    }
}