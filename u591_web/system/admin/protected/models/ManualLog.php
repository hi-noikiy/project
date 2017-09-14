<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/12
 * Time: 下午4:28
 * 手工充值流程示意图
 */
class ManualLog extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{manual_log}}';
    }

    public function rules(){
        return array(
            array('game_id', 'required','message'=>'游戏ID必须.'),
            array('server_id','required','message'=>'区服ID必须.'),
            array('type','required','message'=>'搜索类型必须.'),
            array('name','required','message'=>'账号或角色必须.'),
            array('order_id','required','message'=>'订单号必须.'),
            array('dwFenBaoID','required','message'=>'分包ID必须.'),
            array('emoney','required','message'=>'金额必须.'),
            array('account_id','required','message'=>'账号ID必须.'),
            array('account_name','required','message'=>'账号必须.'),

            array('addtime,operator,verify_time,verify_level,remark,status','safe')
        );
    }

    public function attributeLabels(){
        return array(
            'game_id'      				=>'游戏',
            'server_id'      			=>'区服',
            'type'				        =>'搜索类型',
            'status'                    =>'状态',//-1退回 0可用 1作废 2 结束
        );
    }

    protected function beforeSave(){
        if(parent::beforeSave()){
            if($this->isNewRecord){
                $this->addtime = time();
                $this->operator = Yii::app()->user->name;
            }
            return true;
        }else
            return false;
    }

}