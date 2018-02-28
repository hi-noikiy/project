<?php
namespace app\admin\model;

use think\Model;

class Archive extends Model
{
    protected $insert  = ['description', 'writer'];
    protected $update = [];
    
    public function arctype()
    {
        return $this->hasOne('Arctype', 'id', 'typeid')->field('typename, jumplink');
    }
    
    public function User()
    {
        return $this->hasOne('User', 'id', 'writer')->field('name');
    }
    
    protected function setDescriptionAttr($value)
    {
        return auto_description($value, input('param.content'));
    }
    
    protected function setWriterAttr($value)
    {
        if ($value){
            return $value;
        }else{
            return cookie('uid');
        }
    }
    
    public function getStatusTurnAttr($value, $data)
    {
        $turnArr = [0=>lang('status0'), 1=>lang('status1')];
        return $turnArr[$data['status']];
    }
}