<?php

namespace Common\Model;
use Think\Model;

class ZMMoveModel extends Model {

    private $Move = '';
    private $cinemaInfo = '';
    private $cacheTime = 3600;

    public function __construct($cinemaCode) {
        $this->cinemaInfo = $this->getCinemaInfoBycode('interfaceType, interfaceConfig,link', $cinemaCode);
        if(empty($this->cinemaInfo)){
            return false;
        }
        $this->Move = S('ZMMoveModelInterfaceMove' . $cinemaCode);
        if (empty($this->Move)) {
            if($this->cinemaInfo['interfaceType'] == 'mtx'){
                $this->Move = new \Think\MtxMove($this->cinemaInfo['interfaceConfig']); 
            }elseif ($this->cinemaInfo['interfaceType'] == 'cx') {
                $this->Move = new \Think\CxMove($this->cinemaInfo['interfaceConfig']);
            }elseif ($this->cinemaInfo['interfaceType'] == 'hfh') {
                $this->Move = new \Think\HfhMove($this->cinemaInfo['interfaceConfig']);
            }
            S('ZMMoveModelInterfaceMove' . $cinemaCode, $this->Move, $this->cacheTime);
        }
    }

    /**
     * 确认订单
     */
    function submitOrder($releasearr){
    	$cinemaCode=$releasearr['cinemaCode'];
    	$submitOrder= S('submitOrder' . $cinemaCode . json_encode($releasearr));
    	if(empty($submitOrder)){
    		$this->__construct($cinemaCode);
    		$submitOrder = $this->Move->submitOrder($releasearr);
    		S('submitOrder' . $cinemaCode . json_encode($releasearr),$submitOrder,7200);
    	}
    	return $submitOrder;
    }

    /**
     * 4.2  获取对应影院对应日期的排期
     * 
     * @param 
     *  string cinemaCode 电影院编号
     *  string planDate 获取排期的日期 格式(yyyy-mm-dd)
     *
     * @return array {
     *  string ResultCode 返回结果号,
     *  array CinemaPlans {
     *      array CinemaPlan [{
     *          string FeatureAppNo 排期编号,
     *          string FeatureNo 排期号，应用于会员卡接口,
     *          string StartTime 开始放映日期,
     *          string TotalTime 总时长,
     *          string FilmNo 影片编号,
     *          string FilmName 影片名字,
     *          string HallNo 影厅编号,
     *          string LowestPrice 最底保护价,
     *          string StandardPrice 标准价,
     *          string ListingPrice 标准价,
     *      }]
     *  }
     * } 
     */
	public function getCinemaPlan($arr)
    {   
        $this->__construct($arr['cinemaCode']);
        $cinemaPlanList = $this->Move->getCinemaPlan($arr);
        return $cinemaPlanList;
        // print_r($cinemaPlanList);
    }


    /**
     *  获取对应排期的座位图的状态
     *
     * @param   string  $featureAppNo   排期编号
     * 
     * @return array [GraphRow][GraphCol] [{
     *  string SeatNo 座位编号,
     *  string SeatPieceNo 座区号,
     *  string GraphRow 屏幕行,
     *  string GraphCol 屏幕列,
     *  string SeatRow 坐行,
     *  string SeatCol 坐列,
     *  string SeatState 状态[-1.不可售,0.未售,1.售出,3.预定,4.选中,7.已锁定,9.验收],
     *  string SeatPieceName 座区名称
     * }]
     */
    public function getPlanSiteState($arr) {
        $this->__construct($arr['cinemaCode']);
        $arr['link']=$this->cinemaInfo['link'];
        if($this->cinemaInfo['interfaceType'] == 'hfh'){
            $sectionIds = S('sectionIds' . $arr['cinemaCode'] . '_' . $arr['hallNo']);
            if (empty($sectionIds)) {
                $sectionIds = M('cinemaHallSeat')->field('distinct(sectionId) as sectionId')->where(array('cinemaCode'=>$arr['cinemaCode'],'hallNo'=>$arr['hallNo']))->select();
                S('sectionIds' . $arr['cinemaCode'] . '_' . $arr['hallNo'], $sectionIds, 3600);
            }
        	$arr['sectionIds'] = $sectionIds;
        }
        $planSiteStateList = $this->Move->getPlanSiteState($arr);
        

        if($this->cinemaInfo['interfaceType'] == 'cx'){
            foreach ($planSiteStateList['PlanSiteState'] as $key => $value) {
                $seatInfo = $this->getHallSiteInfoByNo('xCoord, yCoord, groupCode', $value['SeatNo']);
                $planSiteStateList['PlanSiteState'][$key]['GraphRow'] = $seatInfo['yCoord'];
                $planSiteStateList['PlanSiteState'][$key]['GraphCol'] = $seatInfo['xCoord'];
                $planSiteStateList['PlanSiteState'][$key]['groupCode'] = $seatInfo['groupCode'];
            }   
        }else if($this->cinemaInfo['interfaceType'] == 'hfh'){
            if (is_array($planSiteStateList['PlanSiteState'])) {
                foreach ($planSiteStateList['PlanSiteState'] as $key => $value) {
                    $lockSeat[$key] = $value['SeatRow'] . ',' . $value['SeatCol'] . ',' . $value['sectionId'];
                }
            }
            unset($planSiteStateList);
        	foreach ($arr['sectionIds'] as $k=>$val){
				
        		$seatInfo = M('cinemaHallSeat')->where(array('cinemaCode'=>$arr['cinemaCode'],'hallNo'=>$arr['hallNo']))->select();
        		
        		foreach ($seatInfo as $key => $value) {

                    $planSiteStateList['PlanSiteState'][$key]['SeatState'] = 0;
                    // echo $value['rowNum'].','.$value['columnNum'].','.$value['sectionId'] . "\r\n";
                    if (in_array($value['rowNum'].','.$value['columnNum'].','.$value['sectionId'], $lockSeat)) {
                        $planSiteStateList['PlanSiteState'][$key]['SeatState'] = 1;
                    }

        			$planSiteStateList['PlanSiteState'][$key]['SeatNo'] = $value['seatCode'];
        			$planSiteStateList['PlanSiteState'][$key]['SeatRow'] = $value['rowNum'];
        			$planSiteStateList['PlanSiteState'][$key]['SeatCol'] = $value['columnNum'];
        			$planSiteStateList['PlanSiteState'][$key]['GraphRow'] = $value['yCoord'];
        			$planSiteStateList['PlanSiteState'][$key]['GraphCol'] = $value['xCoord'];
        			$planSiteStateList['PlanSiteState'][$key]['groupCode'] = $value['groupCode'];
        			$planSiteStateList['PlanSiteState'][$key]['sectionId'] = $value['sectionId'];
        			$planSiteStateList['PlanSiteState'][$key]['sectionName'] = $value['sectionName'];
        		}
        	}
        }

        return $planSiteStateList;
    }

    /**
     * 4.4  获取影院对应的影厅信息
     * 
     * @param array $para_list
     * {
     *  string pCinemaID 电影院编号
     * }
     * @return array {
     *  string ResultCode 返回结果号,
     *  array Halls {
     *      array Hall [{
     *          string HallNo 影厅编号,
     *          string HallName 影厅名称
     *      }]
     *  }
     * }
     */
    public function getHall($arr) {
        $this->__construct($arr['cinemaCode']);
        // $hallList = S('ZMMoveModelgetHall' . $arr['cinemaCode']);
        if(empty($hallList)){
            $hallList = $this->Move->getHall($arr);
            S('ZMMoveModelgetHall' . $arr['cinemaCode'], $hallList, 7200);
        }
        return $hallList;
    }


    public function getCinema($arr)
    {
        $this->__construct($arr['cinemaCode']);
        // $hallList = S('ZMMoveModelgetHall' . $arr['cinemaCode']);
        if(empty($hallList)){
            $hallList = $this->Move->getCinema($arr);
            S('ZMMoveModelgetCinema' . $arr['cinemaCode'], $hallList, 7200);
        }
        return $hallList;
    }


    /**
     * 4.24  检查需要定票的座位状态情况，并定票锁定实时座位
     *
     * @param array $para_list
     * {
     *  string FeatureAppNo 排期编号
     *  string SerialNum 合作商方定单号（流水号）,
     *  string PayType 付费类型[0.其他 70.会员卡支付],
     *  string RecvMobilePhone 接收二维码手机号码,
     *  array SeatInfos {
     *      array SeatInfo[{
     *          string SeatNo 影厅座位号[-1.自动选座,其他座位号],
     *          string TicketPrice 显示票价,
     *          string Handlingfee 服务费
     *      }]
     *  }
     * }
     * @return array {
     *  string ResultCode 返回结果号,
     *  string OrderNo 订单编号,
     *  array SeatInfos {
     *      array SeatInfo [{
     *          string SeatNo 锁定的座位编号,
     *          string TicketPrice 锁定的座位价格,
     *          string SeatRow 锁定的座位行,
     *          string SeatCol 锁定的座位列,
     *          string SeatPieceNo 锁定的座位座区号,
     *      }]
     *  }
     * }
     */
    public function checkSeatState($arr) {
        $this->__construct($arr['cinemaCode']);
        $arr['serialNum'] = time() . rand(111111, 99999);
        if($this->cinemaInfo['interfaceType'] == 'mtx'){
            foreach ($arr['seatInfos'] as $key => $value) {
                $tempSeatInfo[] = array(
                    'SeatNo'      => $value['seatNo'],
                    'TicketPrice' => $value['ticketPrice'],
                    'Handlingfee' => '0'
                );
            }
        }elseif($this->cinemaInfo['interfaceType'] == 'cx'){
            foreach ($arr['seatInfos'] as $key => $value) {
                $tempSeatInfo[] = $value['seatNo'];
            }
        }elseif($this->cinemaInfo['interfaceType'] == 'hfh'){
           $tempSeatInfo=$arr['seatstr'];
        }
        $arr['seatInfos']=$tempSeatInfo;
        $checkSeatStateInfo = $this->Move->checkSeatState($arr);
        $checkSeatStateInfo['serialNum']=$arr['serialNum'];
        
        return $checkSeatStateInfo;
    }
	/**
	 * 退票
	 */
    function backTicket($data){
    	$this->__construct($data['cinemaCode']);
    	return $this->Move->backTicket($data);
    }
    /**
     * 查询订单状态
     */
    function queryOrderStatus($releasearr){
    	$cinemaCode=$releasearr['cinemaCode'];
    	//$queryOrderStatus= S('queryOrderStatus' . $cinemaCode . json_encode($releasearr));
    	if(empty($queryOrderStatus)){
    		$this->__construct($cinemaCode);
    		$releasearr['link']=$this->cinemaInfo['link'];
    		$queryOrderStatus = $this->Move->queryOrderStatus($releasearr);
    		S('queryOrderStatus' . $cinemaCode . json_encode($releasearr),$queryOrderStatus,7200);
    	}
    	return $queryOrderStatus;
    }
    /**
     * 4.5  获取影厅对应的所有座位信息
     * 
     * @param array $para_list
     * {
     *  string pCinemaID 电影院编号
     *  string pHallID 影厅编号
     * }
     * @return array {
     *  string ResultCode 返回结果号,
     *  array HallSites {
     *      array HallSite [{
     *          string SeatNo 座位编号,
     *          string SeatPieceNo 座区号,
     *          string GraphRow 屏幕行,
     *          string GraphCol 屏幕列,
     *          string SeatRow 坐行,
     *          string SeatCol 坐列
     *      }]
     *  }
     * }
     */

    public function getHallSite($arr)
    {
        // $hallSite = S('ZMMoveModelgetHallSite' . $arr['cinemaCode'] . $arr['hallNo']);
        if(empty($hallSite)){
            $this->__construct($arr['cinemaCode']);
            $hallSite = $this->Move->getHallSite($arr);
            S('ZMMoveModelgetHallSite' . $arr['cinemaCode'] . $arr['hallNo'], $hallSite, 7200);
        }
        return $hallSite;
    }


    /**
     * 4.14  查询在售影片信息  --------辰星
     * @param array [{
     *     string   cinemaCode   电影院编号
     *     string   planDate     放映日期，格式(yyyy-mm-dd)，以自然日为准
     * 
     * }]
     * @return array {
     *  string ResultCode 返回结果号,
     *  array FilmInfoVOs {
     *      array FilmInfo [{
     *          string FilmCode 影片编号,
     *          string FilmName 影片名称
     *          string Version 巨幕立体
     *          string Duration 影片时长
     *          string PublishDate 上映时间
     *          string Publisher 发行商
     *          string Producer 制作人
     *          string Director 导演 
     *          string Cast 演员
     *          string Introduction 简介
     *      }]
     *  }
     * }
     */

    public function getQueryFilmInfo($cinemaCode, $planDate)
    {
        $filmInfo = S('ZMMoveModelgetQueryFilmInfo' . $cinemaCode . $planDate);
        if(empty($filmInfo)){
            $this->__construct($cinemaCode);
            $filmInfo = $this->Move->getQueryFilmInfo($cinemaCode, $planDate);
            S('ZMMoveModelgetQueryFilmInfo' . $cinemaCode . $planDate, $filmInfo, 3600);
        }
        return $filmInfo;
    }


    /**
    * 根据cinemaCode获取影院信息
    * @param cinemaCode 影院编号;
    * @return array();
    * @author 宇
    */
    public function getCinemaInfoBycode($field, $cinemaCode)
    {

        $cinemaInfo = S('ZMMoveModelgetCinemaInfoBycode' . $cinemaCode . str_replace(',', '_', $field));

        if(empty($cinemaInfo)){
            $cinemaInfo = M('Cinema')->field($field)->where(array('cinemaCode' => $cinemaCode))->find();
            if(!empty($cinemaInfo)){
                $cinemaInfo['interfaceConfig'] = json_decode($cinemaInfo['interfaceConfig'], true);
                S('ZMMoveModelgetCinemaInfoBycode' . $cinemaCode . str_replace(',', '_', $field), $cinemaInfo, $this->cacheTime);   
            }

        }
        return $cinemaInfo;
    }

    /**
    * 查询影厅座位信息
    * @param array();
    * @return true/false
    * @author 宇
    */
    function getHallSiteInfoByNo($field, $map){

        $hallSiteInfo = S('ZMMoveModelgetHallSiteInfoByNo' . json_encode($map) . str_replace(array(',', ' '), '_', $field));
        if(empty($hallSiteInfo)){
            $hallSiteInfo = M('CinemaHallSeat')->field($field)->where($map)->find();
            S('ZMMoveModelgetHallSiteInfoByNo' . json_encode($map) . str_replace(array(',', ' '), '_', $field), $hallSiteInfo, $this->cacheTime);
        }
        return $hallSiteInfo;
    }
	/**
	 * 解锁
	 * @param unknown $cinemaCode
	 * @param unknown $releasearr
	 * @return mixed
	 */
    function releaseSeat($releasearr){
            $this->__construct($releasearr['cinemaCode']);
            return $this->Move->releaseSeat($releasearr);
    }

}