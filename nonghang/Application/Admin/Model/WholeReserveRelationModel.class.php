<?php

namespace Admin\Model;
use Think\Model;

class WholeReserveRelationModel extends Model {
	var $tablename="whole_reserve_relation";
    /**
     *
     *功能：添加信息
     *
     */
    private function add_basedata($dataarray,$flag="1") {
        $userbase = M($this->tablename);
        switch ($flag) {
            case 1:
                $ret = $userbase->add($dataarray);
                break;
            case 2:
                $ret = $userbase->addAll($dataarray);
                break;
            default:
                $ret = $userbase->add($dataarray);
                break;
        }
        if($ret) {
            return $ret;//返回用户id
        }else {
            return false;
        }
    }
    /**
     *
     *功能：删除信息
     *返回：成功：true
     *           失败：false
     *
     */
    private function delete_basedata($dataarray) {
        $wherearray['id']=array('EQ',$dataarray['id']);
        $userbase = M($this->tablename);
        $ret=$userbase->where($wherearray)->delete();
        if($ret) {
            return 1;//返回删除成功
        }else {
            return false;
        }
    }
    /**
     *
     *功能：修改信息
     *返回：成功：true
     *           失败：false
     *
     */
    private function update_basedata($dataarray) {
        $wherearray['id']=array('EQ',$dataarray['id']);
        $userbase = M($this->tablename);
        $ret=$userbase->where($wherearray)->save($dataarray);
        if($ret) {
            return 1;
        }else {
            return false;
        }
    }

    /**
     *
     *功能：添加信息
     *
     */
    public function add_model($dataarray) {
    	
        $ret=$this->add_basedata($dataarray);
        return $ret;
    }
    /**
     *
     *删除信息
     *
     */
    public function delete_model($dataarray) {
        $ret=$this->delete_basedata($dataarray);
        return $ret;
    }
    /**
     *
     *修改信息
     *
     */
    public function update_model($dataarray) {
        $ret=$this->update_basedata($dataarray);
        return $ret;
    }
    /**
     *
     *功能：查询信息
     *返回：成功：详细信息
     *           失败：false
     *
     */
    public function getlist($dataarray=array(),$flag="1") {
        $wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }

        $database = M($this->tablename);
		// $database = D('WholeReserveView');
        switch($flag) {
            case '1':
                $info=$database->where($wherearray)->order($dataarray['sort'])->select();
                break;
            case '2'://分页操作
                $info=$database->where($wherearray)->order($dataarray['sort'])->limit($dataarray['firstRow'].','.$dataarray['listRows'])->select();
                break;
            case '3'://获取个数
                $info=$database->where($wherearray)->count();
                break;
            case '4'://获取单条
                $info=$database->where($wherearray)->find();
                break;
            case '5'://选择字段查询操作
                $info=$database->where($wherearray)->field($dataarray['getField'])->select();
                break;
            default:
                $info=$database->where($wherearray)->select();
                break;
        }
//     echo $database->getlastsql();
        if($info) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
}