<?php
namespace app\pokemon\model;

use think\Model;

class VsActivity extends Model
{
    protected $insert  = ['writer'];

    public function User()
    {
        return $this->hasOne('User', 'id', 'writer')->field('name');
    }

    protected function setWriterAttr($value)
    {
        if ($value){
            return $value;
        }else{
            return cookie('uid');
        }
    }
}