<?php
namespace app\admin\model;

use think\Model;

class VsNewsType extends Model
{
    public function treeList()
    {
        $list = cache('DB_TREE_NEWSTYPE');
        if(!$list){
            $list = $this->order('sorts ASC,id ASC')->select();
            cache('DB_TREE_NEWSTYPE', $list);
        }
        return $list;
    }
}