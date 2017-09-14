<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/13
 * Time: 上午11:24
 */
class ErpLevel extends CActiveRecord{
    public $maxLevel;

    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{erp_level}}';
    }

    public function rules(){
        return array(
            array('title', 'required','message'=>'标题'), //记录审核过程的操作标题比如 通过 不通过
            array('level','required','message'=>'流程等级'), //记录这个操作的控制模块
            //array('','safe')
        );
    }
    public function attributeLabels(){
        return array(
            'title'      			=>'流程标题',
            'level'      			=>'流程等级',
        );
    }
    public function getMaxLevel(){
        $sql = "select max(level) as maxLevel from {{erp_level}} limit 1";
        $rs = $this->findBySql($sql);
        return $rs ? $rs->maxLevel : false;
    }

    public function getInfo(){
        $sql = "select * from {{erp_level}}";
        $array = array();
        $rs = $this->findAllBySql($sql);
        if($rs){
            foreach($rs as $v){
                $array[$v->level] = $v->title;
            }
        }
        return $array;
    }

}