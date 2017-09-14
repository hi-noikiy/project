<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/3
 * Time: 下午3:44
 */
class Welfare extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{welfare}}';
    }

    public function rules(){
        return array(
            array('real_name', 'required','message'=>'真实姓名必须.'),
            array('player_name','required','message'=>'角色名称必须.'),
            array('emoney','required','message'=>'钻石必须.'),
            array('server_id','required','message'=>'服务器ID必须.'),
            array('player_id','required','message'=>'角色ID必须.'),
            array('pay_date,pay_used_date','safe')
        );
    }



    public function attributeLabels(){
        return array(
            'real_name'      		=>'真实姓名',
            'player_name'      		=>'角色名称',
            'emoney'				=>'钻石',

            'server_id'      		=>'服务器ID',
            'player_id'      		=>'角色ID',
            'pay_date'				=>'起始时间',
        );
    }

    /*protected function beforeSave(){
        if(parent::beforeSave()){
            if($this->isNewRecord){

            }
            return true;
        }else
            return false;
    }*/

}