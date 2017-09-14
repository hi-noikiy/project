<?php

namespace Admin\Model;
use Think\Model;

class SeatModel extends Model {
	function getList($map=array(),$order='id',$start=0,$limit=999999999){
		 return M('Seat')->where($map)->select();
	}
    function find($map=''){
    	return M('Seat')->where($map)->find();
    }
    function save($map=''){
    	return M('Seat')->save($map);
    }
    function add($map=''){
    	return M('Seat')->add($map);
    }
    function delete($map=''){
    	return M('Seat')->where($map)->delete();
    }
    function count($map=''){
    	return M('Seat')->where($map)->count();
    }
}