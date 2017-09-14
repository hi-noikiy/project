<?php

namespace Refresh\Model;
use Think\Model;

class FilmModel extends Model {

    function find($map=''){
    	$filmprice= M('filmLowestPrice')->where($map)->find();
    	if(empty($filmprice['lowestPrice'])){
    		$filmprice['lowestPrice']=0;
    	}
    	return $filmprice['lowestPrice'];
    }
   
}