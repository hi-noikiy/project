<?php
namespace app\admin\validate;

use think\Validate;

class VsNews extends Validate
{
    protected $rule = [
        'title' => 'require',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'title' => '{%title_val}',
        'status' => '{%status_val}',
    ];

    protected $scene = [
        'add'   => ['title', 'status'],
        'edit'  => ['title', 'status'],
        'status' => ['status'],
    ];
}