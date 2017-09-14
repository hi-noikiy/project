<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/12
 * Time: 下午4:28
 * 玩家物品发放
 */
class PlayergoodsLog extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{playergoods_log}}';
    }

    public function rules(){
        return array(
        	array('type','required','message'=>'搜索类型必须.'),
        	array('name','required','message'=>'角色名或角色ID必须.'),
            array('mailtype', 'required','message'=>'邮件类型必须.'),
        	array('mail_theme', 'required','message'=>'邮件主题必须.'),
        	array('mail_describe', 'required','message'=>'邮件内容必须.'),
            array('player_id','required','message'=>'角色ID必须.'),
        	array('awardmoney,awardemoney,awardtired,awardrose,awardlily,awardnarcissus,awarditemtype1,awarditemtype2,awarditemtype3,awarditemtype4,
        			mailtype,mail_theme,mail_describe,addtime,operator,verify_level,remark,status','safe')
        );
    }

    public function attributeLabels(){
        return array(
            'type'				        =>'搜索类型',
            'status'                    =>'状态',//0可用 1作废 2 结束
        );
    }
    protected function beforeSave(){
        if(parent::beforeSave()){
            if($this->isNewRecord){
                $this->addtime = time();
                $this->operator = Yii::app()->user->name;
            }
            return true;
        }else
            return false;
    }

    public function getInfo($status = 0){
        $sql = "select * from {{playergoods_log}} where status=$status";
        $rs = $this->findAllBySql($sql);
        return $rs;
    }
}