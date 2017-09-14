<?php

namespace Soap\Model;
use Think\Model;

class MtxModel {
	
    
	private $apiUrl = 'http://ticket.mvtapi.com:8760/ticketapi/services/ticketapi?wsdl';
	private $pTokenID = '1829';
	private $Token = 'abcdef';
	private $pAppCode = 'fzzr';
	private $pAppPwd = 'cmts20140428fzzr';
    private $client = '';
    private $cacheName = '';
    private $cacheValue = '';
    private $param = '';


	public function __construct($param, $methon) {

        if ($methon == 'LiveRealCheckSeatState' || $methon == 'SellTicket') {
            $this->xml = new \Think\SimpleXML();
            $xml = \simplexml_load_string($param['pXmlString']);
            $param =  $this->xml->xml2array($xml);
            unset($param['VerifyInfo']);
        }

        
        $TokenId  = $param['pTokenID'] ? $param['pTokenID'] : $param['TokenID']; //最美TokenId
        $AppCode  = $param['pAppCode'] ? $param['pAppCode'] : $param['AppCode']; //最美接口帐号
        $VerifyInfo = $param['pVerifyInfo'] ?  $param['pVerifyInfo'] :  $param['VerifyInfo'] ; //最美接口参数验证码
        
        if (isset($param['pDesc'])) {
            $oldDesc  = $param['pDesc']; // 接口备注信息
        }

        
        unset($param['pVerifyInfo'], $param['VerifyInfo'], $param['pDesc']);
        $this->cacheName = str_replace(array($param['pTokenID'], $param['pAppCode']), '', $methon . '_' . implode(',', $param));
        $this->cacheValue = S($this->cacheName);
        if ($this->cacheValue) {
            return true;
        }
        //开始验证帐号权限{



        // }4008888080


        if (!empty($param['pTokenID'])) {
            $param['pTokenID'] = $this->pTokenID;
        }elseif (!empty($param['TokenID'])) {
            $param['TokenID'] = $this->pTokenID;
        }

        if (!empty($param['pAppCode'])) {
            $param['pAppCode'] = $this->pAppCode;
        }elseif (!empty($param['AppCode'])) {
            $param['AppCode'] = $this->pAppCode;
        }

        //开始生成满天星验证信息{
        $this->param = $param;
        foreach ($this->param as $k => $v) 
        {   
            if ($k == 'SeatInfos') {
                $strLong .= count($v['SeatInfo']);   
            }else{
                $strLong .= $v;   
            }
                       
        }
        $strLong .= $this->Token . $this->pAppPwd;

        if ($methon == 'LiveRealCheckSeatState' || $methon == 'SellTicket') {
            $this->param['VerifyInfo'] = strtolower(substr(md5(strtolower($strLong)), 8,16));
        }else{
            $this->param['pVerifyInfo'] = strtolower(substr(md5(strtolower($strLong)), 8,16));
        }
        if (isset($oldDesc)) {
            $this->param['pDesc'] = $oldDesc;
        }
        if ($methon == 'LiveRealCheckSeatState' || $methon == 'SellTicket') {
            
            if (!empty($this->param['SeatInfos']['SeatInfo'])) {
                $this->param['SeatInfos'] = $this->xml->xml_encode($this->param['SeatInfos']['SeatInfo'],'utf-8','root','SeatInfo',false);
            }
            $newParam['pXmlString'] = $this->xml->xml_encode($this->param,'utf-8', $methon . 'Parameter');
            unset($this->param);
            $this->param = $newParam;

        }
        // }
		$this->client = new \SoapClient($this->apiUrl, array('connection_timeout' => 60));

	}

    public function GetCinema($param)
    {

    	$this->__construct($param, __FUNCTION__);

        if (!empty($this->cacheValue)) {
            wlog('[缓存]' . __FUNCTION__ . '请求参数：' . json_encode($param) . json_encode($this->cacheValue), 'Soap');
            return (object)$this->cacheValue;
        }

    	$Result = $this->client->GetCinema($this->param);
        S($this->cacheName, $Result, 3600);
    	wlog(__FUNCTION__ . '请求参数：' .json_encode($this->param) . json_encode($Result), 'Soap');

    	return $Result;
    }

    public function GetCinemaPlan($param)
    {
        $this->__construct($param, __FUNCTION__);

        if (!empty($this->cacheValue)) {
            wlog('[缓存]' . __FUNCTION__ . '请求参数：' . json_encode($param) . json_encode($this->cacheValue), 'Soap');
            return (object)$this->cacheValue;
        }
        $this->__construct($param, __FUNCTION__);
        $Result = $this->client->GetCinemaPlan($this->param);
        S(__FUNCTION__ . json_encode($param), $Result, 300);
        wlog(__FUNCTION__ . '请求参数：' .json_encode($param) . json_encode($Result), 'Soap');

        return $Result;
    }


    // 4.3 GetCinemaAllPlan获取对应影院所有可读排期
    public function GetCinemaAllPlan($param)
    {
        $this->__construct($param, __FUNCTION__);

        if (!empty($this->cacheValue)) {
            wlog('[缓存]' . __FUNCTION__ . '请求参数：' . json_encode($param) . json_encode($this->cacheValue), 'Soap');
            return (object)$this->cacheValue;
        }
        $this->__construct($param, __FUNCTION__);
        $Result = $this->client->GetCinemaAllPlan($this->param);
        S(__FUNCTION__ . json_encode($param), $Result, 300);
        wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');

        return $Result;
    }

    // 4.4 GetHall获取影院对应的影厅信息
    public function GetHall($param)
    {
        $this->__construct($param, __FUNCTION__);

        if (!empty($this->cacheValue)) {
            wlog('[缓存]' . __FUNCTION__ . '请求参数：' . json_encode($param) . json_encode($this->cacheValue), 'Soap');
            return (object)$this->cacheValue;
        }
        $this->__construct($param, __FUNCTION__);
        $Result = $this->client->GetHall($this->param);
        S(__FUNCTION__ . json_encode($param), $Result, 3600);
        wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');

        return $Result;
    }


    // 4.5 GetHallSite获取影厅对应的所有座位信息
    public function GetHallSite($param)
    {
        $this->__construct($param, __FUNCTION__);

        if (!empty($this->cacheValue)) {
            wlog('[缓存]' . __FUNCTION__ . '请求参数：' . json_encode($param) . json_encode($this->cacheValue), 'Soap');
            return (object)$this->cacheValue;
        }
        $this->__construct($param, __FUNCTION__);
        $Result = $this->client->GetHallSite($this->param);
        S(__FUNCTION__ . json_encode($param), $Result, 3600);
        wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');

        return $Result;
    }

    // 4.6 GetHallSiteArea获取影厅对应的所有座区信息
    public function GetHallSiteArea($param)
    {
        $this->__construct($param, __FUNCTION__);

        if (!empty($this->cacheValue)) {
            wlog('[缓存]' . __FUNCTION__ . '请求参数：' . json_encode($param) . json_encode($this->cacheValue), 'Soap');
            return (object)$this->cacheValue;
        }
        $this->__construct($param, __FUNCTION__);
        $Result = $this->client->GetHallSiteArea($this->param);
        S(__FUNCTION__ . json_encode($param), $Result, 3600);
        wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');

        return $Result;
    }

    // 4.7 GetPlanSiteStatistic获取排期座位统计数据

    public function GetPlanSiteStatistic($param)
    {
        $this->__construct($param, __FUNCTION__);

        if (!empty($this->cacheValue)) {
            wlog('[缓存]' . __FUNCTION__ . '请求参数：' . json_encode($param) . json_encode($this->cacheValue), 'Soap');
            return (object)$this->cacheValue;
        }
        $this->__construct($param, __FUNCTION__);
        $Result = $this->client->GetPlanSiteStatistic($this->param);
        S(__FUNCTION__ . json_encode($param), $Result, 10);
        wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');

        return $Result;
    }

    // 4.8 GetPlanSiteState获取对应排期的座位图的状态
    public function GetPlanSiteState($param)
    {
        $this->__construct($param, __FUNCTION__);

        if (!empty($this->cacheValue)) {
            wlog('[缓存]' . __FUNCTION__ . '请求参数：' . json_encode($param) . json_encode($this->cacheValue), 'Soap');
            return (object)$this->cacheValue;
        }
        $this->__construct($param, __FUNCTION__);
        $Result = $this->client->GetPlanSiteState($this->param);
        S(__FUNCTION__ . json_encode($param), $Result, 10);
        wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');

        return $Result;
    }

    // 4.9 GetPlanCannotSellSeat获取对应排期的不可网售座位信息
    public function GetPlanCannotSellSeat($param)
    {
        $this->__construct($param, __FUNCTION__);

        if (!empty($this->cacheValue)) {
            wlog('[缓存]' . __FUNCTION__ . '请求参数：' . json_encode($param) . json_encode($this->cacheValue), 'Soap');
            return (object)$this->cacheValue;
        }
        $this->__construct($param, __FUNCTION__);
        $Result = $this->client->GetPlanCannotSellSeat($this->param);
        S(__FUNCTION__ . json_encode($param), $Result, 10);
        wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');

        return $Result;
    }


    // 4.10 GetJsonSiteState获取不可网售座位信息json格式
    public function GetJsonSiteState($param)
    {
        $this->__construct($param, __FUNCTION__);

        if (!empty($this->cacheValue)) {
            wlog('[缓存]' . __FUNCTION__ . '请求参数：' . json_encode($param) . json_encode($this->cacheValue), 'Soap');
            return (object)$this->cacheValue;
        }
        $this->__construct($param, __FUNCTION__);
        $Result = $this->client->GetJsonSiteState($this->param);
        S(__FUNCTION__ . json_encode($param), $Result, 10);
        wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');

        return $Result;
    }


    // 4.11 LiveRealCheckSeatState检查需要定票的座位状态情况，并定票锁定实时座位
    public function LiveRealCheckSeatState($param)
    {
    	$this->__construct($param, __FUNCTION__);
    	$Result = $this->client->LiveRealCheckSeatState($this->param);
    	wlog(__FUNCTION__ . '请求参数：' .json_encode($this->param), 'Soap');
    	return $Result;
    }


    // 4.12 ModifyOrderPayPrice 修改订单价格
    public function ModifyOrderPayPrice($param)
    {
    	$this->__construct($param, __FUNCTION__);
    	$Result = $this->client->ModifyOrderPayPrice($this->param);
    	wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');
    	return $Result;
    }


    // 4.13 SellTicket卖常规票(带座位票)
    public function SellTicket($param)
    {
    	$this->__construct($param, __FUNCTION__);
    	$Result = $this->client->SellTicket($this->param);
    	wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');
    	return $Result;
    }


    // 4.14 GetOrderStatus查询定单的售票结果
    public function GetOrderStatus($param)
    {
        $this->__construct($param, __FUNCTION__);

        if (!empty($this->cacheValue)) {
            wlog('[缓存]' . __FUNCTION__ . '请求参数：' . json_encode($param) . json_encode($this->cacheValue), 'Soap');
            return (object)$this->cacheValue;
        }
        $this->__construct($param, __FUNCTION__);
        $Result = $this->client->GetOrderStatus($this->param);
        S(__FUNCTION__ . json_encode($param), $Result, 10);
        wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');

        return $Result;
    }


    // 4.15 GetOrderState查询定单的售票结果(带退票信息)
    public function GetOrderState($param)
    {
    	$this->__construct($param, __FUNCTION__);
    	$Result = $this->client->GetOrderState($this->param);
    	wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');
    	return $Result;
    }


    // 4.16 UnLockOrderCenCin实时解锁座位
    public function UnLockOrderCenCin($param)
    {
    	$this->__construct($param, __FUNCTION__);
    	$Result = $this->client->UnLockOrderCenCin($this->param);
    	wlog(__FUNCTION__ . '请求参数：' .json_encode($this->param) , 'Soap');
    	return $Result;
    }


    // 4.17 BackTicket退票
    public function BackTicket($param)
    {
    	$this->__construct($param, __FUNCTION__);
    	$Result = $this->client->BackTicket($this->param);
    	wlog(__FUNCTION__ . '请求参数：' .json_encode($param).json_encode($this->param) . json_encode($Result), 'Soap');
    	return $Result;
    }


    // 4.18 AppPrintTicket合作商打票
    public function AppPrintTicket($param)
    {
    	$this->__construct($param, __FUNCTION__);
    	$Result = $this->client->AppPrintTicket($this->param);
    	wlog(__FUNCTION__ . '请求参数：' .json_encode($param), 'Soap');
    	return $Result;
    }

}