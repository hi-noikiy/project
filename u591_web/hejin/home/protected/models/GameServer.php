<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 游戏区服
 * ==============================================
 * @date: 2016-4-29
 * @author: xu
 * @version:
 */
class GameServer extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    public function tableName(){
        return '{{game_server}}';
    }

    public function rules(){
        return array(
            array('game_id', 'required','message'=>'游戏ID必填！'),
            array('server_id', 'required','message'=>'游戏区服ID必填！'),
            array('server_name','required','message'=>'游戏区服名称必填！'),
            //array('link','required','message'=>'IP地址必填！'),
            //array('port','required','message'=>'端口必填！'),
            //array('username','required','message'=>'mysql账号必填！'),
            //array('password','required','message'=>'mysql密码必填！'),
            array('combined_services','safe')
        );
    }

    public function attributeLabels(){
        return array(
            'game_id'      					=>'游戏ID',
            'server_id'      				=>'区服ID',
            'server_name'      			=>'区服名称',
            'link'								=>'IP',
            'port'								=>'端口',
            'username'					=>'账号',
            'password'						=>'密码',
        );
    }

    public function getInfo(){
        $sql = "select * from {{game_server}}";
        $rs = $this->findAllBySql($sql);
        $arr = array();
        if(!empty($rs)){
            foreach ($rs as $k => $v){
                $arr[$v->game_id][$v->server_id] = $v->server_name;
            }
        }
        return $arr;
    }

    public function getInfoList($gameId){
        $sql = "select * from {{game_server}} where game_id=$gameId";
        $rs = $this->findAllBySql($sql);
        return $rs;
    }

}