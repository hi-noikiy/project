<?php
/**
 * 首页
 */

namespace Admin\Controller;
use Think\Controller;
class PlanController extends AdminController {

    public function planList(){
    	$cinemaCode=I('cinemaCode');
    	$filmNo=I('filmNo');
    	$start=I('start');
    	$end=I('end');
    	$pageData=array(
    		'cinemaCode'=>$cinemaCode,
    		'filmNo'=>$filmNo,
    		'start'=>$start,
    		'end'=>$end,
    	);
    	$this->assign('pageData',$pageData);
    	if(!empty($cinemaCode)){
    		$map['cinemaCode']=$cinemaCode;
    	}
    	if(!empty($filmNo)){
    		$map['filmNo']=$filmNo;
    	}
    	$map['startTime']= array('egt',strtotime(date('Ymd',time())));
    	if(!empty($start)&&!empty($end)){
    		$map['startTime']= array(array('egt',strtotime($start)),array('elt',strtotime($end)+24*60*60),$map['startTime']);
    	}elseif(!empty($start)){
    		$map['startTime']= array(array('egt',strtotime($start)),$map['startTime']);
    	}elseif(!empty($end)){
    		$map['startTime']= array(array('elt',strtotime($end)+24*60*60),$map['startTime']);
    	} 	
    	$filmList=D('Plan')->getPlanFilms();
    	$cinemaList=D('cinema')->getCinemaList();
    	$this->assign('cinemaList',$cinemaList);
    	$this->assign('filmList',$filmList);
    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
    	$upPlanDate =date('"Y-m-d"');
    	for ($i=1; $i < 5 ; $i++) { 
    		$upPlanDate .= date(',"Y-m-d"', time() + 3600 * 24 * $i);
    	}


    	$count = D ( 'Plan' )->count ($map);
    	$allPage = ceil ( $count / $this->limit);
    	$curPage = $this->curPage ( $nowPage, $allPage );
    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList ( $count, $this->limit, $pageData );
    	}
    	$this->assign('page',$showPage);
    	$planList = D('Plan')->getPlanList('', $map, ($nowPage - 1) * $this->limit . ',' . $this->limit, 'startTime asc');

    	// print_r($planList);

    	$this->assign('planList',$planList);
    	$this->assign('upPlanDate',$upPlanDate);
        $this->display();
    }

    public function ajaxDelPlan($featureAppNo)
    {
        if(D('Plan')->delPlanByFeatureAppNo($featureAppNo)){
            $this->success('恭喜您，排期删除成功！');
        }else{
            $this->error('很遗憾，排期删除失败！');
        }
    }


    public function setPriceLock($featureAppNo)
    {
        if(I('request.priceLock') == 0){
            $data['priceLock'] = 1;
            $data['isAotuPrice'] = 0;
        }else{
            $data['priceLock'] = 0;
            $data['isAotuPrice'] = 1;
        }
        

        $map['featureAppNo'] = $featureAppNo;
        if(D('Plan')->setCinemaPlan($data, $map)){
            $this->success('恭喜您，排期价格锁定成功！');
        }else{
            $this->error('很遗憾，排期价格锁定失败！');
        }
    }



    public function planPrice()
    {
        if(IS_AJAX){
            $price = I('request.price');
            $featureAppNo = I('request.featureAppNo');
            $data['priceConfig'] = json_encode($price);

            $map['featureAppNo'] = $featureAppNo;
            if(D('Plan')->setCinemaPlan($data, $map)){
                $this->success('恭喜您，排期价格更新成功！');
            }else{
                $this->error('很遗憾，排期价格更新失败！');
            }
        }else{
        	$featureAppNo = I('request.featureAppNo');
        	$planInfo = D('Plan')->getPlanInfo('*', array('featureAppNo' => $featureAppNo));
        	$priceConfig = json_decode($planInfo['priceConfig'], true);


            foreach ($priceConfig as $key => $value) {
                $memberGroup = D('Cinema')->getMemberGroupInfoById($key);
                foreach ($memberGroup as $k => $v) {
                   $memberGroupList[$key][$v['groupId']] = $v;
                }

            }
            $arrayCinemaGroupId = array_keys($memberGroupList);

            $this->cinemaGroup = D('Cinema')->getGroup('id,groupName', array('id'=>array('in', $arrayCinemaGroupId)));
            // print_r($priceConfig);
        	$this->assign('priceConfig',$priceConfig);
            $this->assign('memberGroupList',$memberGroupList);
            $this->assign('planInfo',$planInfo);
        	$this->display();
        }
    }


    public function getCinemaList($value='')
    {
    	if(IS_AJAX){
	    	$cinemaList = D('Cinema')->getCinemaList();
	    	if(empty($cinemaList)){
	    		$this->error('无法取得影院信息！');
	    	}else{
	    		$this->success('获取影院信息成功！', $cinemaList);
	    	}
	    }
    }


    public function setPriceRefresh()
    {
        if(IS_AJAX){
            $cinemaCode = I('request.cinemaCode');
            if (!empty($cinemaCode)) {
                $cinemaMap['cinemaCode'] = $cinemaCode;
            }

            $cinemaList = D('Cinema')->getCinemaList('cinemaCode', $cinemaMap); 

            foreach ($cinemaList as $key => $value) {

                $groupList = S('getGroup' . $value['cinemaCode']);

                if (empty($groupList)) {
                    $groupMap['cinemaList'] = array('LIKE', '%' . $value['cinemaCode'] . '%');
                    $groupList = D('Cinema')->getGroup('id, groupName', $groupMap);
                    S('getGroup' . $value['cinemaCode'], $groupList, 600);
                }
                $filmNo = I('request.filmNo');
                if (!empty($filmNo)) {
                    $map['filmNo'] = $filmNo;
                } 
                $map['cinemaCode'] = $value['cinemaCode'];
                $map['priceLock'] = 0;
                $map['startTime']= array('egt',strtotime(date('Ymd',time())));

                $planList = D('Plan')->getPlanList('featureAppNo, startTime, standardPrice, lowestPrice', $map);

                foreach ($planList as $planKey => $planValue) {
                     /*开始设置默认价格*/
                    foreach ($groupList as $groupListKey => $groupListValue) {

                        $startData = strtotime(date('Y-m-d', $planValue['startTime']));
                        $startTime = strtotime('2000-10-10 ' . date('H:i', $planValue['startTime']) . ':00');
                        $startWeek = ',' . date('w', $planValue['startTime']) . ',';
                        $configMap['cinemaGroupId'] = $groupListValue['id'];
                        $configMap['startDate'] = array('elt', $startData);
                        $configMap['endDate'] = array('egt', $startData);
                        $configMap['startTime'] = array('elt', $startTime);
                        $configMap['endTime'] = array('egt', $startTime);
                        $configMap['_string'] = ' CONCAT(",",weeks,",") like "%' . $startWeek . '%" ';

                        $cardConfig = D('Cinema')->getMemberPriceConfigInfo('priceConfig', $configMap);

                        if($cardConfig['priceConfig']){
                            $cardConfig = json_decode($cardConfig['priceConfig'], true);

                            foreach ($cardConfig as $configKey => $configValue) {

                                if (!empty($configValue[1])) {
                                    $priceConfig[$groupListValue['id']][$configKey] = $configValue[1] > $planValue['lowestPrice'] ? $configValue[1] : $planValue['lowestPrice'];
                                }elseif (!empty($configValue[2])) {
                                    $configPrice = ($planValue['standardPrice'] * $configValue[2]) / 10;
                                    $priceConfig[$groupListValue['id']][$configKey] = $configPrice > intval($planValue['lowestPrice']) ? $configPrice : $planValue['lowestPrice'];
                                }
                            }


                            
                        }
                    }
                    $data['priceConfig'] = json_encode($priceConfig);
                    $data['isAotuPrice'] = 1;
                    /*结束设置默认价格*/
                    $updateMap['featureAppNo'] = $planValue['featureAppNo'];
                    $setCinemaPlanResult = D('Plan')->setCinemaPlan($data, $updateMap);
                    unset($priceConfig);
                }

                
            }

            if(empty($cinemaList)){
                $this->error('影片价格刷新失败！');
            }else{
                $this->success('影片价格刷新成功！', $cinemaList);
            }
        }
    }
 
    public function cxTest()
    {
    	$move = D('ZMMove');
    	$planSiteState = $move->getPlanSiteState('35014046', '7011201508060031');
    	print_r($planSiteState);
    }

    public function mtxTest()
    {
    	$move = D('ZMMove');
    	$planSiteState = $move->getPlanSiteState('35012401', '123901717');
    	print_r($planSiteState);
    }

}