<?php
/**
 * 2013-9-22 14:58
 * @author Administrator
 *
 */
class Shopcart extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{shopcart}}';
    }
    public function rules(){
        return array(
            array('server_id', 'required','message'=>'区服必须'),
            array('money', 'required','message'=>'金额必须'),
        );
    }
    public function attributeLabels(){
        return array(
            'server_id'   				=>'区服',
            'money'   		=>'金额',
            'type'        			=>'类型',
        );
    }




}