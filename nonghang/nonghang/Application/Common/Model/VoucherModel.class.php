<?php

namespace Common\Model;

use Think\Model;

class VoucherModel extends Model {
	
	public function isVoucher($planInfo)
	{

		if (empty($planInfo['setingId'])) {
			$data['status'] = 1;
			$data['content'] = '无票券配置';
			return $data;
		}

		$setingInfo = M('VoucherSetting')->field('configValue')->where(array('id' => $planInfo['setingId']))->find();
		$setingInfo = unserialize($setingInfo['configValue']);
        
		$startTime = $planInfo['startTime'];
		$startWeek = date('w', $startTime);
		foreach ($setingInfo as $key => $value) {
			if (!empty($value[$startWeek])) {
				foreach ($value[$startWeek]['start'] as $weekKey => $weekValue) {
					if ($value[$startWeek]['start'][$weekKey] <= date('H:i', $startTime) && $value[$startWeek]['end'][$weekKey] >= date('H:i', $startTime)) {
                        if (!($value[$startWeek]['2d'][$weekKey] === '')) {
                            $arrayTypeId[$value['typeCalss']][$key]['2d'] = (float)$value[$startWeek]['2d'][$weekKey];
                        }

                        if (!($value[$startWeek]['3d'][$weekKey] === '')) {
                            $arrayTypeId[$value['typeCalss']][$key]['3d'] = (float)$value[$startWeek]['3d'][$weekKey];
                        }

                        if (!($value[$startWeek]['max'][$weekKey] === '')) {
                            $arrayTypeId[$value['typeCalss']][$key]['max'] = (float)$value[$startWeek]['max'][$weekKey];
                        }
                        break;
					}
				}
			}
			

		}
        // print_r($arrayTypeId);
		return $arrayTypeId;
	}

	public function checkVoucher($voucherNum)
	{
		$voucherInfo = M('VoucherTicket')->field('typeId, voucherNumber, cinemaCode, status, startTime, endTime')->where(array('voucherNumber' => $voucherNum))->find();
		if (empty($voucherInfo)) {
			$data['status'] = 1;
			$data['content'] = '请输入有效票券！';
			return $data;
		}

		if ($voucherInfo['status'] != 1) {
			$data['status'] = 1;
			$data['content'] = '该券当前状态不可使用！';
			return $data;
		}

		if ($voucherInfo['addTime'] >= time()) {
			$data['status'] = 1;
			$data['content'] = '该券当前为生效！';
			return $data;
		}

		if ($voucherInfo['endTime'] < strtotime(date('Y-m-d'))) {
			$data['status'] = 1;
			$data['content'] = '该优惠券已过期无法使用！';
			return $data;
		}

		$voucherTypeInfo = M('VoucherType')->field('typeName, typeValue, typeClass')->where(array('typeId' => $voucherInfo['typeId']))->find();
		$voucherInfo['typeName'] = $voucherTypeInfo['typeName'];
		$voucherInfo['typeValue'] = $voucherTypeInfo['typeValue'];
		$voucherInfo['typeClass'] = $voucherTypeInfo['typeClass'];


		$data['status'] = 0;
		$data['content'] = '该券状态正常';
		$data['data'] = $voucherInfo;
		return $data;


	}


	public function getOrderPrice($orderId, $userInfo)
    {
        $orderInfo = S('getBuyPaywayOrderInfo'. $orderId);
        if (empty($orderInfo)) {
            $orderInfo = D('Order')->findObj($this->param['orderId']);
            S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
        }
        $otherPayInfo = json_decode($orderInfo['otherPayInfo'], true);
        $seatInfo = json_decode($orderInfo['seatInfo'], true);
        $copyType = strtolower($orderInfo['copyType']);
        if (strstr($copyType, 'max')) {
            $copyType = 'max';
        }
        

        $planInfo = S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo']);
        if (empty($planInfo)) {
            $planInfo = D('Plan')->getplan($orderInfo['featureAppNo']);
            S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo'], $planInfo, 900);
        }
        
        $arraySetingConfig = S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo']);
        if (empty($arraySetingConfig)) {
            $arraySetingConfig = $this->isVoucher($planInfo);
            S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo'], $arraySetingConfig, 900);
        }
        
        // print_r($arraySetingConfig);
        $typeClass = empty($orderInfo['otherPayInfo'][1]) ? 0 : 1; 

        $price = $orderInfo['myPrice'] * count($seatInfo);
        $ticketPrice = $orderInfo['myPrice']; 
        // print_r($otherPayInfo);
        if (!empty($arraySetingConfig[1]) && empty($otherPayInfo[0])) {
            
            $voucerMap['memberId'] = $userInfo['id'];
            $voucerMap['validData'] = array('EGT', strtotime(date('Y-m-d')));
            $voucerMap['isUnlock'] = 0;
            $voucerMap['isUse'] = 0;
            $voucerMap['typeClass'] = 1;
            $memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $voucerMap);
            $useNum = 0;
            $typeClass = 1;
            foreach ($memberVoucherList as $key => $value) {
                foreach ($otherPayInfo[1] as $vKey => $vValue) {
                    foreach ($vValue as $k => $v) {
                        if ($value['voucherNum'] == $v) {
                            $price -= $arraySetingConfig[1][$vKey][$copyType];
                            $useNum++;
                        }
                    }
                    
                }
            }
        }
        
        
        if (!empty($arraySetingConfig[0]) && empty($otherPayInfo[1])) {
            
            $voucerMap['memberId'] = $userInfo['id'];
            $voucerMap['validData'] = array('EGT', strtotime(date('Y-m-d')));
            $voucerMap['isUnlock'] = 0;
            $voucerMap['isUse'] = 0;
            $voucerMap['typeClass'] = 0;
            $memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $voucerMap);
            $useNum = 0;
            $typeClass = 0;
            foreach ($memberVoucherList as $key => $value) {
                foreach ($otherPayInfo[0] as $vKey => $vValue) {
                    foreach ($vValue as $k => $v) {
                        if ($value['voucherNum'] == $v) {
                            $newPrice += $arraySetingConfig[0][$vKey][$copyType];
                            $useNum++;
                        }
                    }
                    
                }
                
            }
            $price = $price - $ticketPrice * $useNum + $newPrice;

            if ($useNum != count($seatInfo) && $useNum != 0 ) {
            	$data['status'] = 1;
				$data['content'] = '座位数与兑换券数量不一致，还需使用' . (count($seatInfo) - $useNum) . '张';
				$data['data'] = $voucherInfo;
				return $data;
            }

        }

        if ($price == 0) {
            return 0;
        }
        unset($otherPayInfo[0], $otherPayInfo[0]);


        foreach ($otherPayInfo as $key => $value) {
            if ($key == 'integral' && $price != 0) {
                if (!empty($otherPayInfo['integral'])) {
                    $allIntegral = $price * $this->userInfo['proportion'];
                    if ($allIntegral >= $userInfo['integral']) {
                        $price -= round($userInfo['integral'] / $this->userInfo['proportion'],2);
                    }else{
                        $price = 0;
                    }
                    
                }                
            }elseif ($key == 'account' && $price != 0) {
                if (!empty($otherPayInfo['account'])) {
                    if ($price >= $userInfo['userMoney']) {
                        $price -= $userInfo['userMoney'];
                    }else{
                        $price = 0;
                    }
                }
            }

        }

        return $price;
        
        
    }
    
    /**
     * 获取扣除积分余额后的应付的钱
     * @param unknown $orderId
     * @param unknown $userInfo
     * @return unknown|number
     */
    public function getMyOrderPrice($orderId, $userInfo, $proportion = 0)
    {
    	$orderInfo = S('getBuyPaywayOrderInfo'. $orderId);
    	if (empty($orderInfo)) {
    		$orderInfo = D('Order')->findObj($orderId);
    		S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
    	}
    	$otherPayInfo = json_decode($orderInfo['otherPayInfo'], true);


    	$seatInfo = json_decode($orderInfo['seatInfo'], true);
    	$copyType = strtolower($orderInfo['copyType']);
    	if (strstr($copyType, 'max')) {
    		$copyType = 'max';
    	}
    
    
    	$planInfo = S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo']);
    	if (empty($planInfo)) {
    		$planInfo = D('Plan')->getplan($orderInfo['featureAppNo']);
    		S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo'], $planInfo, 900);
    	}
    
    	$arraySetingConfig = S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo']);
    	if (empty($arraySetingConfig)) {
    		$arraySetingConfig = $this->isVoucher($planInfo);
    		S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo'], $arraySetingConfig, 900);
    	}
    
    	// print_r($arraySetingConfig);
    	$typeClass = empty($orderInfo['otherPayInfo'][1]) ? 0 : 1;
    
    	$price = $orderInfo['myPrice'] * count($seatInfo);

    	$ticketPrice = $orderInfo['myPrice'];
    	// print_r($otherPayInfo);
    	if (!empty($arraySetingConfig[1]) && empty($otherPayInfo[0])) {
    
    		$voucerMap['memberId'] = $userInfo['id'];
    		$voucerMap['validData'] = array('EGT', strtotime(date('Y-m-d')));
    		$voucerMap['isUnlock'] = 0;
    		$voucerMap['isUse'] = 0;
    		$voucerMap['typeClass'] = 1;
    		$memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $voucerMap);
    		$useNum = 0;
    		$typeClass = 1;
    		foreach ($memberVoucherList as $key => $value) {
    			foreach ($otherPayInfo[1] as $vKey => $vValue) {
    				foreach ($vValue as $k => $v) {
    					if ($value['voucherNum'] == $v) {
    						$price -= $arraySetingConfig[1][$vKey][$copyType];
    						$useNum++;
    					}
    				}
    
    			}
    		}
    	}
    
    
    	if (!empty($arraySetingConfig[0]) && empty($otherPayInfo[1])) {
    
    		$voucerMap['memberId'] = $userInfo['id'];
    		$voucerMap['validData'] = array('EGT', strtotime(date('Y-m-d')));
    		$voucerMap['isUnlock'] = 0;
    		$voucerMap['isUse'] = 0;
    		$voucerMap['typeClass'] = 0;
    		$memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $voucerMap);
    		$useNum = 0;
    		$typeClass = 0;
    		foreach ($memberVoucherList as $key => $value) {
    			foreach ($otherPayInfo[0] as $vKey => $vValue) {
    				foreach ($vValue as $k => $v) {
    					if ($value['voucherNum'] == $v) {
    						$newPrice += $arraySetingConfig[0][$vKey][$copyType];
    						$useNum++;
    					}
    				}
    
    			}
    
    		}
    		$price = $price - $ticketPrice * $useNum + $newPrice;
    
    		if ($useNum != count($seatInfo) && $useNum != 0 ) {
    			$data['status'] = 1;
    			$data['content'] = '座位数与兑换券数量不一致，还需使用' . (count($seatInfo) - $useNum) . '张';
    			$data['data'] = $voucherInfo;
    			return $data;
    		}
    
    	}
        
    	if ($price == 0) {
    		return 0;
    	}
    	unset($otherPayInfo[0], $otherPayInfo[0]);
    	foreach ($otherPayInfo as $key => $value) {
    		if ($key == 'integral' && $price != 0) {
    			if (!empty($otherPayInfo['integral'])) {
    				$allIntegral = $price * $proportion;
    				if ($allIntegral >= $userInfo['integral']) {
    					$price -= round($userInfo['integral'] / $proportion,2);
    				}else{
    					$price = 0;
    				}
    			}
    		}
    	}
    
    	return $price;
    
    
    }


    /**
    * 计算影票订单补差金额
    * @param null;
    * @return null
    * @author 宇
    */
    public function getGoodOrderPrice($orderId, $userInfo)
    {


        $orderInfo = S('getBuyPaywayGoodsOrderInfo' . $orderId);
        if (empty($goodOrderInfo)) {
            $orderInfo=D('orderGoods')->find($orderId);
            S('getBuyPaywayGoodsOrderInfo' . $orderId, $orderInfo, 900);
        }
        $otherPayInfo = json_decode($orderInfo['otherPayInfo'], true);
        $price = $orderInfo['price'];
        $typeClass = 2;
        if (!empty($otherPayInfo[$typeClass])) {
            foreach ($otherPayInfo[$typeClass] as $key => $value) {
                foreach ($value as $k => $v) {
                    $voucherInfo = $this->checkVoucher($v);
                    // print_r($voucherInfo);
                    $price -= floatval($voucherInfo['data']['typeValue']);
                }
            }
        }

        if ($price != 0) {
            unset($otherPayInfo[2]);
            foreach ($otherPayInfo as $key => $value) {
                if ($key == 'integral' && $price != 0) {
                    if (!empty($otherPayInfo['integral'])) {
                        $allIntegral = $price * $this->userInfo['proportion'];
                        if ($allIntegral > $userInfo['integral']) {
                            $useIntegral = $userInfo['integral'];
                            $price -= round($userInfo['integral'] / $this->userInfo['proportion'],2);
                        }else{
                            $useIntegral = $allIntegral;
                            $price = 0;
                        }
                    }
                }elseif ($key == 'account' && $price != 0) {
                	if (!empty($otherPayInfo['account'])) {
                		if ($price >= $userInfo['mmoney']) {
                			$price -= $userInfo['mmoney'];
                		}else{
                			$price = 0;
                		}
                	}
				}
            }
        }
        return $price;        
    }
    
    public function backVoucher($orderId, $userInfo, $logPath)
    {
    	$orderInfo = S('getBuyPaywayOrderInfo'. $orderId);
    	if (empty($orderInfo)) {
    		$orderInfo = D('Order')->findObj(array('orderCode' => $orderId));
    		S('getBuyPaywayOrderInfo' . $orderId, $orderInfo, 900);
    	}
    	$otherPayInfo = json_decode($orderInfo['otherPayInfo'], true);
    
    	$typeClass = empty($orderInfo['otherPayInfo'][0]) ? 1 : 0;
    
    	wlog('支付列表' . json_encode($otherPayInfo) . '订单号：' . $orderId, $logPath);
    	if (!empty($otherPayInfo[$typeClass])) {
    		foreach ($otherPayInfo[$typeClass] as $key => $value) {
    			foreach ($value as $k => $v) {
    				$voucherList[] = $v;
    			}
    		}
    	}
    	wlog('开始退券' . json_encode($voucherList) . '订单号：' . $orderId, $logPath);
    
    	$useData['status'] = 1;
    	$useData['useTime'] = '';
    	$useData['useOrderId'] = '';
    	$useMap['voucherNumber'] = array('in', $voucherList);
    	$useNum = $this->confirmVoucher($useData, $useMap);
    
    	$memberVoucherData['isUse'] = 0;
    	$memberVoucherData['useDateTime'] = '';
    	$memberVoucherMap['memberId'] = $userInfo['id'];
    	$memberVoucherMap['voucherNum'] = array('in', $voucherList);
    	$this->editMemberVoucher($memberVoucherData, $memberVoucherMap);
    
    }
    public function confirmVoucher($data, $map)
    {
    	$mod = M('VoucherTicket');
    	if (!$mod->create($data)){
    		return false;
    	}else{
    		$svaeResult = $mod->where($map)->data($data)->save();
    		if($svaeResult){
    			return $svaeResult;
    		}else{
    			return false;
    		}
    	}
    }
    public function editMemberVoucher($data, $map)
    {
    	$mod = M('MemberVoucher');
    	if (!$mod->create($data)){
    		return false;
    	}else{
    		$svaeResult = $mod->where($map)->data($data)->save();
    		if($svaeResult){
    			return $svaeResult;
    		}else{
    			return false;
    		}
    	}
    }
}