<?php
class  Problem extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{problem}}';
    }

    public function rules(){
        return array(
            array('phone','required','message'=>'角色必填！'),
            array('server_id','required','message'=>'角色必填！'),
            array('username','required','message'=>'角色必填！'),
            array('desc','required','message'=>'角色必填！'),
            array('type,model,system,image,operate','safe')
        );
    }

    public function attributeLabels(){
        return array(
            'type'              =>'类型',
            'phone'   		    =>'手机号',
            'server_id'   		=>'服务器',
            'username'        	=>'角色名称',
            'desc'				=>'描述',
            'model'             =>'手机型号',
            'system'            =>'系统版本',
            'operate'           =>'操作系统',
            'image'             =>'图片',
            'addtime'           =>'添加时间',
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