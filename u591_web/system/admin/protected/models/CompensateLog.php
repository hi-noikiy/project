<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/12
 * Time: 下午4:28
 * 玩家物品发放
 */
class CompensateLog extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{compensate_log}}';
    }

    public function rules(){
        return array(
            array('game_id', 'required','message'=>'游戏ID必须.'),
            array('server_id','required','message'=>'区服必须.'),
            array('index_id','required','message'=>'标识必须.'),
            array('begin_time','required','message'=>'补偿开始时间必须.'),
            array('end_time','required','message'=>'补偿结束时间必须.'),
            //array('role_begin_time','required','message'=>'角色创建开始时间必须.'),
            //array('role_end_time','required','message'=>'角色创建结束时间必须.'),
            //array('level_mix','required','message'=>'角色开始等级必须.'),
            //array('level_max','required','message'=>'角色开始等级必须.'),
            array('message','required','message'=>'提示信息必须.'),
            array('type1','required','message'=>'物品1类型必须.'),
            array('amount1','required','message'=>'物品1数量必须.'),

            array('param1,type2,param2,amount2,type3,param3,amount3,type4,param4,amount4,role_begin_time,role_end_time,level_min,level_max,addtime,operator,verify_level,remark,status','safe')
        );
    }

    public function attributeLabels(){
        return array(
            'game_id'      				=>'游戏',
            'server_id'      			=>'区服',
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

}