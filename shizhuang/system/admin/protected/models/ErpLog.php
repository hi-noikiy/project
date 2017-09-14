<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/13
 * Time: 上午11:05
 */
class ErpLog extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{erp_log}}';
    }

    public function rules(){
        return array(
            array('title', 'required','message'=>'标题必须.'), //记录审核过程的操作标题比如 通过 不通过
            array('model','required','message'=>'模块必须.'), //记录这个操作的控制模块
            array('pid','required','message'=>'父ID必须.'),
            array('verify_time,operator','safe')
        );
    }

    public function attributeLabels(){
        return array();
    }

    protected function beforeSave(){
        if(parent::beforeSave()){
            if($this->isNewRecord){
                $this->verify_time = time();
                $this->operator = Yii::app()->user->name;
            }
            return true;
        }else
            return false;
    }

    public function saveData($model, $title, $pid){
        $this->model = $model;
        $this->title = $title;
        $this->pid = $pid;
        $this->save();
    }
}