<?php
namespace app\index\model;

use think\Model;

class News extends Model
{
    protected $insert  = ['writer'];

    protected function setWriterAttr($value)
    {
        if ($value){
            return $value;
        }else{
            return cookie('uid');
        }
    }
}