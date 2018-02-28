<?php
namespace app\admin\validate;

use think\Validate;

class VsLink extends Validate
{
    protected $rule = [
        'name' => 'require',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'name' => '{%name_val}',
        'status' => '{%status_val}',
    ];

    protected $scene = [
        'add'   => ['name', 'status'],
        'edit'  => ['name', 'status'],
        'status' => ['status'],
    ];
}