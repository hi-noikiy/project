<?php
/**
 * 首页
 */

namespace Refresh\Controller;
use Think\Controller;
class PlanController extends Controller {

	/**
	 * 更新所有订单状态
	 * 
	 * @see CxUser::queryOrderStatus()
	 * @see CxUser::memberTransactionCancel()
	 * @see CxUser::queryMemberInfo()
	 */
	public function orderRefresh(){
		$data=D('order')->orderRefresh();
		echo json_encode($data);
	}
	
    public function getPlanList()
    {
		$move = D('ZMMove');
        $cinemaCode = I('request.cinemaCode');
        $planDate = I('request.planDate');

        $cinemaMap['cinemaCode'] = $cinemaCode;
        $cinemaInfo = D('Admin/Cinema')->getCinemaInfoBycode('interfaceType, cinemaName,link', $cinemaCode);

		$tempHallList = S('getHall' . $cinemaCode );
		if(empty($tempHallList)){
			$hallList = $move->getHall(array('cinemaCode'=>$cinemaCode,'link'=>$cinemaInfo['link']));
			if($hallList['ResultCode'] == 0){

    			foreach ($hallList['Halls'] as $key => $value) {
                    $data['cinemaCode'] = $cinemaCode;
                    $data['hallNo'] = $value['HallNo'];
                    $data['hallName'] = $value['HallName'];
                    D('Admin/Cinema')->aotuAddHall($data);
    				$tempHallList[$value['HallNo']] = $value;
    			}
    			S('getHall' . $cinemaCode, $tempHallList, 604800);
    		}
		}



        foreach ($tempHallList as $key => $value) {
            

                    $tempHallSite = S('tempHallSite_' . $cinemaCode . '_' . $value['HallNo']);


                    if (empty($tempHallSite)) {

                        $hallSite = $move->getHallSite(array('cinemaCode'=>$cinemaCode,'link'=>$cinemaInfo['link'],'hallNo'=>$value['HallNo']) );

                        // wlog('刷新影厅座位-影院编号:' .$cinemaCode . ';影厅：' . $value['HallNo'] . json_encode($hallSite), 'hallSite');
                        if($hallSite['ResultCode'] == 0){
                            foreach ($hallSite['ScreenSites']['ScreenSite'] as $siteKey => $siteValue) {
                                $hallData=array();
                                $hallData['seatCode'] = $siteValue['SeatCode'];
                                if(!empty($siteValue['GroupCode'])){
                                    $hallData['groupCode'] = $siteValue['GroupCode'];
                                }
                                $hallData['rowNum'] = $siteValue['RowNum'];
                                $hallData['columnNum'] = $siteValue['ColumnNum'];
                                $hallData['xCoord'] = $siteValue['XCoord'];
                                $hallData['yCoord'] = $siteValue['YCoord'];
                                $hallData['status'] = $siteValue['Status'];
                                $hallData['hallNo'] = $value['HallNo'];
                                $hallData['typeInd'] = $siteValue['typeInd'];
                                if(!empty($siteValue['sectionId'])){
                                    $hallData['sectionId'] = $siteValue['sectionId'];
                                    $hallData['sectionName'] = $siteValue['sectionName'];
                                }
                                $hallData['cinemaCode'] = $cinemaCode;

                                D('Admin/Cinema')->aotuAddHallSite($hallData);
                                S('tempHallSite_' . $cinemaCode . '_' . $value['HallNo'], true, 3600);
                                
                            }
                        }
                    }
        }



		if($cinemaInfo['interfaceType'] == 'cx'){
    		$tempFilmInfo = S('cx_Refresh_getQueryFilmInfo' . $cinemaCode . $planDate);
			if(empty($tempFilmInfo)){
				$filmInfo = $move->getQueryFilmInfo($cinemaCode, $planDate);
				if($filmInfo['ResultCode'] == 0){
					$newFilmInfo = '';
					foreach ($filmInfo['FilmInfo'] as $key => $value) {
						$newFilmInfo[$value['FilmCode']] = $value;
					}
					S('cx_Refresh_getQueryFilmInfo' . $cinemaCode . $planDate, $newFilmInfo, 3600);
                    unset($tempFilmInfo);
                    $tempFilmInfo = $newFilmInfo;
				}else{
					$this->error('获取影片信息失败！' . json_encode($filmInfo));
				}
			}
		}


		$cinemaPlan = $move->getCinemaPlan(array('cinemaCode'=>$cinemaCode,'planDate'=>$planDate,'link'=>$cinemaInfo['link']));
        // print_r($cinemaPlan);
        if($cinemaPlan['ResultCode'] == 0){
			foreach ($cinemaPlan['CinemaPlans'] as $key => $value) {

                $groupList = S('getGroup' . $value['cinemaCode']);

                if (empty($groupList)) {
                    $groupMap['cinemaList'] = array('LIKE', '%' . $value['cinemaCode'] . '%');
                    $groupList = D('Admin/Cinema')->getGroup('id, groupName', $groupMap);
                    S('getGroup' . $value['cinemaCode'], $groupList, 600);
                }
                $data['isClose'] = $value['IsClose'];
				$data['featureAppNo'] = $value['FeatureAppNo'];
				$data['featureNo'] = $value['FeatureNo'];


				$data['startTime'] = strtotime($value['StartTime']);

                $data['lowestPrice'] = $value['LowestPrice'];
                if(empty($data['lowestPrice'] )){
                    $lowestPrice = D('Admin/Film')->getLowestPrice('', array('filmNo'=>$value['FilmNo'], 'cinemaCode' => $cinemaCode ));
                    $data['lowestPrice']=$lowestPrice[$cinemaCode];
                }

               
               /*开始设置默认价格*/
                foreach ($groupList as $groupListKey => $groupListValue) {

                    $startData = strtotime(date('Y-m-d', $data['startTime']));
   
                    $startTime = strtotime('2000-10-10 ' . date('H:i', $value['StartTime']) . ':00');
                    $startWeek = ',' . date('w', $value['StartTime']) . ',';
                    $configMap['cinemaGroupId'] = $groupListValue['id'];
                    $configMap['startDate'] = array('elt', $startData);
                    $configMap['endDate'] = array('egt', $startData);
                    $configMap['startTime'] = array('elt', $startTime);
                    $configMap['endTime'] = array('egt', $startTime);
                    $configMap['_string'] = ' CONCAT(",",weeks,",") like "%' . $startWeek . '%" ';
                    
                    $cardConfig = D('Admin/Cinema')->getMemberPriceConfigInfo('priceConfig', $configMap);

                    if($cardConfig['priceConfig']){
                        $cardConfig = json_decode($cardConfig['priceConfig'], true);

                        foreach ($cardConfig as $configKey => $configValue) {

                            if (!empty($configValue[1])) {
                                $priceConfig[$groupListValue['id']][$configKey] = $configValue[1] > $data['lowestPrice'] ? $configValue[1] : $data['lowestPrice'];
                            }elseif (!empty($configValue[2])) {
                                $configPrice = ($value['StandardPrice'] * $configValue[2]) / 10;
                                $priceConfig[$groupListValue['id']][$configKey] = $configPrice > intval($data['lowestPrice']) ? $configPrice : $data['lowestPrice'];
                            }
                        }


                        
                    }
                }
                $data['priceConfig'] = json_encode($priceConfig);
                $data['isAotuPrice'] = 1;
                /*结束设置默认价格*/

                unset($priceConfig);


				$data['filmNo'] = $value['FilmNo'];
				$data['filmName'] = $value['FilmName'];
				$data['hallNo'] = $value['HallNo'];
				if(!empty($value['otherfilmNo'])){
					$data['otherfilmNo'] = $value['otherfilmNo'];
				}
				$data['hallName'] = $tempHallList[$data['hallNo']]['HallName'];
				if($value['version']=='普通'){
					$films[$key]['version']='2D';
				}elseif($value['version']=='普通立体'){
					$films[$key]['version']='3D';
				}elseif($value['version']=='巨幕立体'){
					$films[$key]['version']='MAX3D';
				}
				$data['copyType'] = $value['CopyType'] ? $value['CopyType'] : $tempFilmInfo[$value['FilmNo']]['Version'];
				if($data['copyType']=='普通'||$data['copyType']=='数字'){
					$data['copyType']='2D';
				}elseif($data['copyType']=='普通立体'){
					$data['copyType']='3D';
				}elseif($data['copyType']=='巨幕立体'){
					$data['copyType']='MAX3D';
				}

                if (strstr($data['hallName'], 'ZMAX')) {
                    if ($data['copyType']=='2D') {
                        $data['copyType']='MAX2D';
                    }elseif ($data['copyType']=='3D') {
                        $data['copyType']='MAX3D';
                    }
                }

				$data['copyLanguage'] = $value['CopyLanguage'];
				$data['totalTime'] = $value['TotalTime'];
				
				$data['standardPrice'] = $value['StandardPrice'];
				$data['listingPrice'] = $value['ListingPrice'];
				$data['cinemaCode'] = $cinemaCode;
                $data['cinemaName'] = $cinemaInfo['cinemaName'];
                $data['setingId'] = '1';

				D('Admin/Plan')->aotuAddCinemaPlan($data);
				unset($data);
			}
			$this->success($planDate . ' "' .$cinemaInfo['cinemaName'] .  '"排期刷新成功！');
		}else{
            $this->success($planDate . ' "' .$cinemaInfo['cinemaName'] .  '"无排期！');
        }
    	
    	
    }

    // public function test()
    // {
        
    //     $tempFilmInfo = S('4dcfa1080f093f19e5fb7d1c3b92eef5');

    //     print_r($tempFilmInfo);

    //     $move = D('ZMMove');
    //     $filmInfo = $move->getQueryFilmInfo('35014046', '2015-08-23');

    //     if($filmInfo['ResultCode'] == 0){
    //         $newFilmInfo = '';


    //         foreach ($filmInfo['FilmInfo'] as $key => $value) {
    //             $newFilmInfo[$value['FilmCode']] = $value;
    //         }

    //         S('4dcfa1080f093f19e5fb7d1c3b92eef5', $newFilmInfo, 3600);
    //         unset($tempFilmInfo);
    //         $tempFilmInfo = $newFilmInfo;
    //     }else{
    //         $this->error('获取影片信息失败！' . json_encode($filmInfo));
    //     }

    //     print_r($filmInfo);
        
    // }

    public function getCinema()
    {

        $cinemaList = D('Cinema')->getCinemaList('cinemaCode');
        foreach ($cinemaList as $key => $value) {
            echo $key . "\n";
        }

    }

    public function success($content, $dataList = array())
    {
        $data['status']  = 0;
        $data['content'] = $content;
        $data['data'] = $dataList;
        $this->ajaxReturn($data);
    }

    public function error($content, $status = 1)
    {
        $data['status']  = $status;
        $data['content'] = $content;
        $this->ajaxReturn($data);
    }

}