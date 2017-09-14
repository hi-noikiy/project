<?php
class  CodeWebsite extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{code_website}}';
    }

    public function rules(){
        return array(
            array('code', 'required','message'=>'激活码必填！'),
            array(' used, ','safe')
        );
    }

    public function attributeLabels(){
        return array(
            'code_id'   		=>'激活码',
            'username'   		=>'用户名称',
            'password'        	=>'密码',
            'used'				=>'使用状态', //1已使用、0未使用
        );
    }
    protected function beforeSave(){
        if(parent::beforeSave()){
            if($this->isNewRecord){

            }
            return true;
        }else
            return false;
    }
}