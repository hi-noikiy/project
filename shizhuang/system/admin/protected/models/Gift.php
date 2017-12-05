<?php
class  Gift extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{gift}}';
    }

    public function rules(){
        return array(
            array('name','required','message'=>'礼包名称必填！'),
            array('desc','required','message'=>'礼包内容描述必填！'),
            array('used_type','required','message'=>'批次必填！'),
            array('status,addtime,sort','safe')
        );
    }

    public function attributeLabels(){
        return array(
            'name'   		    =>'礼包名称',
            'desc'   		    =>'礼包内容描述',
            'used_type'   		=>'批次',
            'addtime'        	=>'新增时间',
            'status'            =>'状态',
        );
    }
    protected function beforeSave(){
        if(parent::beforeSave()){
            if($this->isNewRecord){
                $this->addtime = time();
            }
            return true;
        }else
            return false;
    }
}