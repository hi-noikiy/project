<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 游戏区服
* ==============================================
* @date: 2016-4-29
* @author: luoxue
* @version:
*/
class GameServer extends CActiveRecord{
    public $serverid;

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
				//array('passwor','required','message'=>'mysql密码必填！'),
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
	
	public function getInfo($pre = 0, $gameId = 8){
	    $array = array();
	    if($gameId)
            $array[] = "game_id='$gameId'";
        if($pre){
            $preArr = explode(',', $pre);
            if(count($preArr) == 1){
                $pre = $preArr[0];
                $array[] = "server_id like '$pre%'";
            } else {
                $likeArr = array();
                foreach ($preArr as $v){
                    $likeArr[] = "server_id like '$v%'";
                }
                $array[] = "(".implode(' or ', $likeArr).")";
            }
        }

        $where = empty($array) ? '' : 'where '.implode(' and ', $array);
		$sql = "select * from {{game_server}} $where order by server_id";
		$rs = $this->findAllBySql($sql);
		$arr = array();
		if(!empty($rs)){
			foreach ($rs as $k => $v){
				$arr[$v->game_id][$v->server_id] = $v->server_name;
			}
		}
		return $arr;
	}

    public function getInfoList($gameId, $pre=0){
        $where = "game_id='$gameId'";

        if($pre) {
            $preArr = explode(',', $pre);
            if(count($preArr) == 1){
                $pre = $preArr[0];
                $where .= " and server_id like '$pre%'";
            } else {
                $likeArr = array();
                foreach ($preArr as $v){
                    $likeArr[] = "server_id like '$v%'";
                }
                $w = "(".implode(' or ', $likeArr).")";
                $where .=" and $w";
            }
        }
        //$where .= $pre ? " and server_id like '$pre%'" : '';
        $sql = "select * from {{game_server}} where $where order by server_id";
        $rs = $this->findAllBySql($sql);
        return $rs;
    }

	public function getServer($gameId){
        $sql = "select LEFT (server_id, 1) as serverid from {{game_server}} where game_id=$gameId group by serverid";
        $rs = $this->findAllBySql($sql);
        $arr = array();
        $arr[0] = '区服';
        if(!empty($rs)){
            foreach ($rs as $k => $v){
                $arr[$v->serverid] = $v->serverid;
            }
        }
        return $arr;
    }
}