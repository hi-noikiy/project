<?php
/**
 * 首页
 */

namespace Admin\Controller;
use Think\Controller;
class PriceController extends AdminController {

    public function cardPriceList(){
        $this->limit = 4;


        // print_r($cinemaGroupList);

        $cinemaGroupId = (int)I('request.cinemaGroupId');
        if($cinemaGroupId){
            $map['cinemaGroupId'] = $cinemaGroupId;
            $mapCinemaGroup['id'] = $cinemaGroupId;
            $this->assign('cinemaGroupId',$cinemaGroupId);
        }


        $tempCinemaGroupList = D('Cinema')->getGroup('id,groupName');
        foreach ($tempCinemaGroupList as $key => $value) {
            $cinemaGroupList[$value['id']] = $value;
            $cinemaGroupList[$value['id']]['memberGroupInfo']= D('Cinema')->getMemberGroupInfoById($value['id']);
        }

        // $map['startTime'] = array('GT', time());

        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));

        $count = D ( 'Member' )->getMemberPriceConfigCount ($map);
        $allPage = ceil ( $count / $this->limit);
        $curPage = $this->curPage ( $nowPage, $allPage );
        $startLimit = ($curPage - 1) * $this->limit;
        if ($count > $this->limit) {
            $showPage = $this->getPageList ( $count, $this->limit, array('cinemaGroupId' => $cinemaGroupId) );
        }
        $this->assign('page',$showPage);
        $memberPriceConfigList = D('Member')->getMemberPriceConfigList('', $map, ($nowPage - 1) * $this->limit . ',' . $this->limit);
        $this->assign('memberPriceConfigList',$memberPriceConfigList);



        $this->assign('cinemaGroupList',$cinemaGroupList);
        $this->display();
    }

    public function editCardPrice($id)
    {
        if(IS_AJAX){
            $data['cinemaGroupId'] = I('request.cinemaGroupId');
            $data['startDate'] = I('request.startTime');
            $data['endDate'] = I('request.endTime');
            $data['weeks'] = I('request.weeks');
            $data['startTime'] = I('request.dayStartTime');
            $data['endTime'] = I('request.dayEndTime');
            $data['priceConfig'] = I('request.planPrice');
            $discountType = I('request.discountType');
            $id = intval(I('request.id'));
            if($data['cinemaGroupId'] == ''){
                $this->error('很遗憾，请选择影院分组！');
            }

            if($data['startDate'] == ''){
                $this->error('很遗憾，请开始日期！');
            }

            if($data['endDate'] == ''){
                $this->error('很遗憾，请结束日期！');
            }

            if($data['weeks'] == ''){
                $this->error('很遗憾，请选择星期！');
            }

            if($data['startTime'] == ''){
                $this->error('很遗憾，请开始时间！');
            }

            if($data['endTime'] == ''){
                $this->error('很遗憾，请结束时间！');
            }

            if($data['priceConfig'] == ''){
                $this->error('很遗憾，请设置会员价格！');
            }

            $plantype = I('request.plantype');
            foreach ($plantype as $key => $value) {
                $priceConfig[$key][$value] = $data['priceConfig'][$key];
            }

            $data['priceConfig'] = json_encode($priceConfig);
            
            $data['startDate'] = strtotime($data['startDate']);
            $data['endDate'] = strtotime($data['endDate']);
            $data['startTime'] = strtotime('2000-10-10 ' . $data['startTime'] . ':00');
            $data['endTime'] = strtotime('2000-10-10 ' . $data['endTime'] . ':00');
            if($id != 0){
                if(D('Member')->updateMemberPriceConfig($data, array('id' => $id))){
                    $this->success('恭喜您，会员价格设置修改成功！', $cinemaList);
                }else{
                    $this->error('很遗憾，会员价格设置修改失败！');
                }
            }

        }else{
            $cinemaGroupList = D('Cinema')->getGroup();
            unset($cinemaGroupList[-1]);
            $this->assign('cinemaGroupList',$cinemaGroupList);
            $memberPriceConfigInfo = D('Member')->getMemberPriceConfigInfoById('*',$id);
            $memberPriceConfigInfo['startDate'] = date('Y-m-d', $memberPriceConfigInfo['startDate']);
            $memberPriceConfigInfo['endDate'] = date('Y-m-d', $memberPriceConfigInfo['endDate']);
            $memberPriceConfigInfo['startTime'] = date('H:i', $memberPriceConfigInfo['startTime']);
            $memberPriceConfigInfo['endTime'] = date('H:i', $memberPriceConfigInfo['endTime']);
            $memberPriceConfigInfo['priceConfig'] = json_decode($memberPriceConfigInfo['priceConfig'], true);
            $memberPriceConfigInfo['priceConfig'] = $memberPriceConfigInfo['priceConfig'];
            $this->assign('memberPriceConfigInfo',$memberPriceConfigInfo);

            $this->display('cardpricefrom');
        }
    }

    public function addCardPrice(){

        if(IS_AJAX){
            $data['cinemaGroupId'] = I('request.cinemaGroupId');
            $data['startDate'] = I('request.startTime');
            $data['endDate'] = I('request.endTime');
            $data['weeks'] = I('request.weeks');
            $data['startTime'] = I('request.dayStartTime');
            $data['endTime'] = I('request.dayEndTime');
            $data['priceConfig'] = I('request.planPrice');
            $discountType = I('request.discountType');
            $id = intval(I('request.id'));
            if($data['cinemaGroupId'] == ''){
                $this->error('很遗憾，请选择影院分组！');
            }

            if($data['startDate'] == ''){
                $this->error('很遗憾，请开始日期！');
            }

            if($data['endDate'] == ''){
                $this->error('很遗憾，请结束日期！');
            }

            if($data['weeks'] == ''){
                $this->error('很遗憾，请选择星期！');
            }

            if($data['startTime'] == ''){
                $this->error('很遗憾，请开始时间！');
            }

            if($data['endTime'] == ''){
                $this->error('很遗憾，请结束时间！');
            }

            if($data['priceConfig'] == ''){
                $this->error('很遗憾，请设置会员价格！');
            }
    
            $plantype = I('request.plantype');
            foreach ($plantype as $key => $value) {
                $priceConfig[$key][$value] = $data['priceConfig'][$key];
            }

            $data['priceConfig'] = json_encode($priceConfig);
                        
            $data['startDate'] = strtotime($data['startDate']);
            $data['endDate'] = strtotime($data['endDate']);
            $data['startTime'] = strtotime('2000-10-10 ' . $data['startTime'] . ':00');
            $data['endTime'] = strtotime('2000-10-10 ' . $data['endTime'] . ':00');
            if($id != 0){
                if(D('Member')->updateMemberPriceConfig($data, array('id' => $id))){
                    $this->success('恭喜您，会员价格设置添加成功！', $cinemaList);
                }else{
                    $this->error('很遗憾，，会员价格设置添加失败！');
                }
            }
            if(D('Member')->addMemberPriceConfig($data)){
                $this->success('恭喜您，会员价格设置添加成功！', $cinemaList);
            }else{
                $this->error('很遗憾，，会员价格设置添加失败！');
            }
        }else{
            $cinemaGroupList = D('Cinema')->getGroup();
            unset($cinemaGroupList[-1]);
            $this->assign('cinemaGroupList',$cinemaGroupList);
            $this->display('cardpricefrom'); 
        }
        
    }



    public function ajaxDelMemberPrice($id)
    {
        if(D('Member')->delMemberPriceConfigById($id)){
            $this->success('恭喜您，会员价格设置删除成功！', $cinemaList);
        }else{
            $this->error('很遗憾，，会员价格设置删除失败！');
        }
    }

    public function filmpricelist(){

        if(IS_AJAX){
            $featureAppNo = I('request.featureAppNo');
            $planPrice = I('request.planPrice');
            $discountType = I('request.discountType');
            $cinemaGroupId = I('request.cinemaGroupId');
            $priceType = I('request.plantype');



            $map['featureAppNo'] = array('in', $featureAppNo);
            $planList = D('Plan')->getPlanList('*', $map);
            foreach ($planList as $planKey => $planValue) {
                $priceConfig = json_decode($planValue['priceConfig'], true);
                unset($priceConfig[$cinemaGroupId]);
                foreach ($priceType as $key => $value) {
                    if ($value == 1) {
                        $priceConfig[$cinemaGroupId][$key] = $planPrice[$key];
                        # code...
                    }else{
                        $priceConfig[$cinemaGroupId][$key]  = $planValue['standardPrice'] * ($planPrice[$key] / 10);
                    }
                }


                $data['priceLock'] = 1;
                $data['isAotuPrice'] = 0;
                $data['priceConfig'] = json_encode($priceConfig);
                $map['featureAppNo'] = $planValue['featureAppNo'];
                D('Plan')->setCinemaPlan($data, $map);
            }

            $this->success('恭喜您，排期价格设置成功！', $newArray);
  
        }else{
            $cinemaList = D('Cinema')->getCinemaList();
            $this->assign('cinemaList',$cinemaList);
            $cinemaGroupList = D('Cinema')->getGroup();
            $this->assign('cinemaGroupList',$cinemaGroupList);
            $this->display();  
        }


    }

    public function getCinemaList()
    {
        $cinemaGroupId = I('request.cinemaGroupId');

        $groupInfo = D('Cinema')->getGroupInfo('cinemaList', array('id'=>$cinemaGroupId));

        $cinemaMap['cinemaCode'] = array('IN', $groupInfo['cinemaList']);
        $cinemaList = D('Cinema')->getCinemaList('cinemaCode, cinemaName', $cinemaMap);


        $cinemaGroupId = I('request.cinemaGroupId');
        $memberGroupList = D('Cinema')->getMemberGroupInfoById($cinemaGroupId);

        $array['cinemaList'] = $cinemaList;
        $array['memberGroupList'] = $memberGroupList;


        if(!empty($cinemaList)){
            $this->success('恭喜您，影院列表获取成功！', $array);
        }else{
            $this->error('很遗憾，影院列表获取失败！');
        }
    }

    public function getMemberGroup()
    {   
        $cinemaGroupId = I('request.cinemaGroupId');
        $memberGroupList = D('Cinema')->getMemberGroupInfoById($cinemaGroupId);
        if(!empty($memberGroupList)){
            $this->success('恭喜您，会员列表获取成功！', $memberGroupList);
        }else{
            $this->error('很遗憾，会员列表获取失败！');
        }
    }

    public function getPlanDate()
    {
        $cinemaCode = I('request.cinemaCode');
        $map['startTime'] = array('GT', time());
        if ($cinemaCode) {
            $map['cinemaCode'] = $cinemaCode;
        }
        $planList = D('Plan')->getPlanList('', $map);

        // print_r($planList);

        foreach ($planList as $key => $value) {
            $startTime = date('Y-m-d', $value['startTime']);
            $newArray[strtotime($startTime)] = $startTime;
        }

        if(!empty($newArray)){
            $this->success('恭喜您，排期时间获取成功！', $newArray);
        }else{
            $this->error('很遗憾，排期时间获取失败！');
        }
    }

    public function getPlanList()
    {
        $cinemaCode = I('request.cinemaCode');
        $planDateList = I('request.planDateList');
        $beginTime = I('request.beginTime');
        $endTime = I('request.endTime');
        $hallNo = I('request.hallNo');


        if(!empty($planDateList)){

            foreach ($planDateList as $key => $value) {

                if (!empty($beginTime)) {
                    $sqlBeginTime = ' ' . $beginTime . ':00';
                }else{
                     $sqlBeginTime = ' 00:00:00';
                }


                if (!empty($endTime)) {
                    $sqlEndTime = ' ' . $endTime . ':00';
                }else{
                     $sqlEndTime = ' 23:59:59';
                }

                if(empty($sqlStr)){
                    $sqlStr = ' (startTime>=' . strtotime($value . $sqlBeginTime) . ' and  startTime<=' . strtotime($value . $sqlEndTime) . ') ' ;
                }else{
                    $sqlStr .= ' or (startTime>=' . strtotime($value . $sqlBeginTime) . ' and  startTime<=' . strtotime($value . $sqlEndTime) . ') ' ;
                }
                
            }
            $map['_string'] = $sqlStr;
        }else{
            $this->error('很遗憾，排期列表获取失败！');
        }


        if (!empty($hallNo)) {
            $map['hallNo'] = array('IN', $hallNo);
        }

        // echo $sqlStr;
        $map['startTime'] = array('GT', time());
        if ($cinemaCode) {
            $map['cinemaCode'] = $cinemaCode;
        }
        
        $planList = D('Plan')->getPlanList('', $map, '', 'startTime asc');

        // print_r($planList);
        $tempHallNo = S('tempHallNo' . $cinemaCode);
         foreach ($planList as $key => $value) {
            $startTime = date('Y-m-d', $value['startTime']);
            $newArray['planList'][$value['filmNo']][strtotime($startTime)][] = $value;
            
            if (empty($tempHallNo)) {
                $newArray['hallNo'][$value['hallNo']]['isCheck'] = false;
                $newArray['hallNo'][$value['hallNo']]['value'] = $value['hallName'];
            }
            
        }
        if (empty($tempHallNo)) {
            ksort($newArray['hallNo']);
            S('tempHallNo' . $cinemaCode, $newArray['hallNo'], 3600);
            $tempHallNo = $newArray['hallNo'];
        }

        foreach ($tempHallNo as $key => $value) {
            $flag = false;
            if (in_array($key, $hallNo)) {
                $flag = true;
            }
            $tempHallNo[$key]['isCheck'] = $flag;
        }
        $newArray['hallNo'] = $tempHallNo;
        // print_r($newArray);

        if(!empty($newArray)){

            $this->success('恭喜您，排期列表获取成功！', $newArray);
        }else{
            $this->error('很遗憾，排期列表获取失败！');
        }

    }



}