<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/14
 * Time: 下午3:05
 */
class Config extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{config}}';
    }

    public function rules(){
        return array(
            array('TWD', 'required','message'=>'台湾币'),
            array('USD','required','message'=>'美元'),
            //array('','safe')
        );
    }
    public function attributeLabels(){
        return array(
            'TWD'      			=>'台湾币汇率',
            'USD'      			=>'美元汇率',
        );
    }

    public function getInfo($id = 1, $field='TWD,USD'){
        $where = ($id != 1) ? "id='1'" : "id='1'";
        $sql = "select $field from {{config}} where $where";
        $rs = $this->findBySql($sql);
        $arr = array();
        if(!empty($rs)){
            $fieldArr = explode(',', $field);
            foreach ($fieldArr as $v){
                $arr[$v] = $rs->$v;
            }
        }
        return $arr;
    }
}