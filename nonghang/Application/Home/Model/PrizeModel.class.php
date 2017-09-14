<?php

namespace Home\Model;
use Think\Model;

class PrizeModel extends Model {
	
	function getList($field='*',$map=array(),$start=0,$limit=9999,$order='id'){
		$prizes=M('prizeZr')->field($field)->where($map)->limit($start,$limit)->order($order)->select();
		// echo M('prizeZr')->_sql();
		return $prizes;
	}
	
	function getPrize($field='*',$map=array()){
		$prize=M('prizeZr')->field($field)->where($map)->find();
		return $prize;
	}
	
	function getLog($field='*',$map=array()){
		$log=M('prizeLogZr')->field($field)->where($map)->find();
		return $log;
	}
	
	function saveLog($data){
		return M('prizeLogZr')->save($data);
	}
	
	function addLog($data){
		return M('prizeLogZr')->add($data);
	}

	    /**
    * 增加用户卡包信息
    * @param null
    * @return null
    * @author 宇
    */
    public function addAccountCardPackage($data)
    {


    	$this->db(1, 'mysqli://zhongrui:sdss23sd891@rdsr5x259n94m3l51yces.mysql.rds.aliyuncs.com:3306/zhongrui');

        $data['createdDatetime'] = date('Y-m-d H:i:s');
        $mod = $this->db(1)->table("AccountCardPackage");
        if (!$mod->create($data)){
            return $mod->getError();
        }else{
            $result = $mod->add($data);
            return $result;
        }
    }
	
	
}