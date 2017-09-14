<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 充值日志表
* ==============================================
* @date: 2016-4-29
* @author: luoxue
* @version:
*/
class PayLog extends CActiveRecord{
	public $payIdTotal;
	public $payTotal;
	public $today;
	
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	public function tableName(){
		return '{{pay_log}}';
	}
	
	public function rules(){
		return array(
				array('PayID', 'required','message'=>'账号ID必填！'),
				array('PayName','required','message'=>'充值账号必填！'),
				array('ServerID','required','message'=>'游戏区服必填！'),
				array('PayMoney','required','message'=>'金额必填！'),
				array('OrderID','required','message'=>'订单号必填！'),
				array('rpCode','required','message'=>'充值状态必填！'),
				array('rpTime','required','message'=>'充值时间必填！'),
				array('dwFenBaoID','required','message'=>'经销商分包ID必填！'),
				array('SubStat','required','message'=>'提交状态必填！'),
				array('CPID','required','message'=>'充值渠道必填！'),
				array('game_id','required','message'=>'游戏ID必填！'),
					
				array('CardNO, CardPwd, BankID, BankOrderID, PayType, Add_Time, PayCode, IsUC, tag, clienttype, transaction_id','safe'),
		);
	}
	
	
	
	public function attributeLabels(){
		return array(
				'CPID'      =>'充值渠道',
				'PayCode'	=>'充值方式',
				'PayID'		=>'账号ID',
				'PayName'   =>'充值账号',
				'OrderID'	=>'订单号',
				'CardNO'	=>'卡号',
				'CardPwd'	=>'卡密',
				'BankID'	=>'银行编码',
				'game_id'	=>'游戏ID',
				'ServerID'	=>'游戏区服',
				'rpCode'	=>'充值状态', //1成功、2失败、3无回执
				'rpTime'	=>'充值时间',
				'IsUC'		=>'是否补单', //0正常、1补单
				'clienttype'=>'机型',
				'PayMoney'	=>'金额',
				'dwFenBaoID'=>'经销商分包ID',
				'Add_Time'	=>'提交时间',
				'SubStat'	=>'提交状态', //1正常、0
		);
	}
	public function getGameOrderFailInfo(){
	    $sql = "select id,ServerID,PayType,PayID,OrderID,PayMoney,PayCode from {{pay_log}} where IsUC='1';";
        $info = $this->findAllBySql($sql);
        return $info;
    }
}