<?php
namespace app\yfe\controller;

use think\Controller;
use app\yfe\model\Arctype;
use app\yfe\model\Archive;

class Index extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function archiveList($code='',$count=0,$num=10)
    {
        $arctype = new Arctype();
        $arcData = $arctype->where(['code' => $code])->find();
        $archive = new Archive();
        $dataList = $archive->where(['typeid' => $arcData['id'],'status' => 1])->order('create_time desc')->limit($count.', '.$num)->select();
        return json($dataList);
    }

    public function detail($id=1){
        $archive = new \app\yfe\model\Archive();
        $data=$archive->get($id);
        return $data;
    }
    
}
