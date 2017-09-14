<?php

namespace Admin\Model;
use Think\Model;

class FilmModel extends Model {
	function getFilmList($field = '*', $map = '', $limit = '', $order = 'updateTime desc'){
        $filmList = M('Film')->field($field)->limit($limit)->where($map)->order($order)->select();
		// echo M('Film')->_sql();
        return $filmList;
	}


    function getBindFilmList($find, $map){
         return M('Film')->field($find)->where($map)->select();
    }

    public function getFilmCount($map)
    {
        return M('Film')->where($map)->count();
    }

    public function getLowestPrice($find, $map)
    {
        $filmLowestPrice = M('filmLowestPrice')->field($find)->where($map)->select();
        $tempLowestPrice = '';
        foreach ($filmLowestPrice as $key => $value) {
            $tempLowestPrice[$value['cinemaCode']] = $value['lowestPrice'];
        }
        return $tempLowestPrice;
    }

    public function aotuSetLowestPrice($data)
    {
         $mod = M('filmLowestPrice');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->add($data);
            // echo $mod->_sql();
            if($id){
               return $id;
            }else{
                $map['cinemaCode'] = $data['cinemaCode'];
                $map['filmNo'] = $data['filmNo'];
                return $mod->where($map)->data($data)->save();
            }
        }
    }
}