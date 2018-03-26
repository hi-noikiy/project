<?php
namespace app\admin\validate;

use think\Validate;

class VsNews extends Validate
{
    protected $rule = [
        'title' => 'require',
        'status' => 'require|in:0,1',
        'type_id' => 'require',
    ];

    protected $message = [
        'title' => '{%title_val}',
        'status' => '{%status_val}',
        'type_id' => '类型',
    ];

    protected $scene = [
        'add'   => ['title', 'status','type_id'],
        'edit'  => ['title', 'status','type_id'],
        'status' => ['status'],
    ];
}