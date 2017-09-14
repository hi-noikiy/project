<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/20
 * Time: 下午3:44
 * 表权限控制
 */
class AccessTable extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{access_table}}';
    }

    public function rules(){
        return array(
            array('title', 'required','message'=>'表名'),
            array('name', 'required','message'=>'字段名'),
            array('manager_access','safe')
        );
    }
    public function attributeLabels(){
        return array(
            'title'         =>'表名',
            'name'          =>'字段名',
        );
    }

    public function getAccess($table, $accountId){
        $sql = "select * from {{access_table}} where title='$table'";
        $rs = $this->findAllBySql($sql);
        $fieldArr = array();
        if($rs){
            foreach ($rs as $v){
                $accessArr = explode(',', $v->manager_access);
                if(in_array($accountId, $accessArr)){
                    $nameArr = explode(',', $v->name);
                    foreach ($nameArr as $field){
                        $fieldArr[] = $field;
                    }
                }
            }
        }
        $fieldArr = array_unique($fieldArr);
        return array_values($fieldArr);
    }
}