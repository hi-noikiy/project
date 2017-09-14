<?php
/**
 * 2013-9-22 14:58
 * @author Administrator
 *
 */
class User extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{user}}';
    }
    public function rules(){
        return array(
            array('username', 'required','message'=>'用户名必须'),
            array('password', 'required','message'=>'密码必须'),
        );
    }
    public function attributeLabels(){
        return array(
            'username'   				=>'用户名',
            'password'   		=>'密码',
            'phone'        			=>'手机号',
            'address'				=>'邮箱地址',
        );
    }




}