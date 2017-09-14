<?php

namespace Common\Model;

use Think\Model;

class ZMUserModel extends Model {
	private $User = '';
	private $cinemaInfo = '';
	private $cacheTime = 0;
	public function __construct($cinemaCode) {
		$this->cinemaInfo = $this->getCinemaInfoBycode ( 'interfaceType, interfaceConfig, link, cinemaCode', $cinemaCode );
		if (empty ( $this->cinemaInfo )) {
			return false;
		}
		//$this->User = S ( 'InterfaceUser' . $cinemaCode );
		if (empty ( $this->User )) {
			if ($this->cinemaInfo ['interfaceType'] == 'mtx') {
				$this->User = new \Think\MtxUser ( $this->cinemaInfo ['interfaceConfig'] );
			} elseif ($this->cinemaInfo ['interfaceType'] == 'cx') {
				$this->User = new \Think\CxUser ( $this->cinemaInfo ['interfaceConfig'] );
			}elseif ($this->cinemaInfo['interfaceType'] == 'hfh') {
                $this->User = new \Think\HfhUser($this->cinemaInfo['interfaceConfig']);
            }
			S ( 'InterfaceUser' . $cinemaCode, $this->User, $this->cacheTime );
		}
	}
	
	/**
	 * 充值
	 * 
	 * @param unknown $releasearr        	
	 */
	function memberCharge($data) {
		$this->__construct ( $data ['cinemaCode'] );
		return $this->User->memberCharge ( $data );
	}
	
	/**
	 * 登录
	 */
	function verifyMemberLogin($arr) {
		$this->__construct ( $arr['cinemaCode'] );
		
		return $this->User->verifyMemberLogin ( $arr);
	}
	
	/**
	 * 消费
	 */
	function memberConsume($data) {
		$this->__construct ( $data ['cinemaCode'] );
		return $this->User->memberConsume ( $data );
	}
	
	/**
	 * 消费记录
	 */
	function queryMemberFlowInfo($data) {
		$this->__construct ( $data ['cinemaCode'] );
		$queryMemberFlowInfos = S('ZMMoveModelgetHall' . json_encode($data));
		if(empty($queryMemberFlowInfos)){
			$queryMemberFlowInfos=$this->User->queryMemberFlowInfo ( $data );
			S('ZMMoveModelgetHall' . json_encode($data),$queryMemberFlowInfos,30);
		}
		return $queryMemberFlowInfos;
	}
	
	/**
	 * 退款
	 */
	function memberTransactionCancel($data) {
		$this->__construct ( $data ['cinemaCode'] );
		return $this->User->memberTransactionCancel ( $data );
	}
	
	/**
	 * 修改密码
	 */
	function modifyMemberPassword($data) {
		$this->__construct ( $data ['cinemaCode'] );
		return $this->User->modifyMemberPassword ( $data );
	}

	/**
	 * 根据cinemaCode获取影院会员信息
	 * 
	 * @param cinemaCode 影院编号;
	 * @return array();
	 * @author 宇
	 */
	function getCardType($cinemaCode) {
		$this->__construct ( $cinemaCode );
		$data['link'] = $this->cinemaInfo['link'];
		$data['cinemaCode'] = $this->cinemaInfo['cinemaCode'];
		$cardType =  $this->User->getCardType ( $data );
		return $cardType;
	}
	
	
	
	
	
	/**
	 * 根据cinemaCode获取影院信息
	 * 
	 * @param cinemaCode 影院编号;
	 * @return array();
	 * @author 宇
	 */
	public function getCinemaInfoBycode($field, $cinemaCode) {
		$cinemaInfo = S ( 'getCinemaInfoBycode' . $cinemaCode . str_replace ( ',', '_', $field ) );
		if (empty ( $cinemaInfo )) {
			$cinemaInfo = M ( 'Cinema' )->field ( $field )->where ( array (
					'cinemaCode' => $cinemaCode 
			) )->find ();
			$cinemaInfo ['interfaceConfig'] = json_decode ( $cinemaInfo ['interfaceConfig'], true );
			S ( 'getCinemaInfoBycode' . $cinemaCode . str_replace ( ',', '_', $field ), $cinemaInfo, $this->cacheTime );
		}
		return $cinemaInfo;
	}
}