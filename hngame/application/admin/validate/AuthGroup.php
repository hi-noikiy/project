<?php
namespace app\admin\validate;

use think\Validate;

class AuthGroup extends Validate
{
    protected $rule = [
        'title' => 'require',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'title' => '{%title_val}',
        'level' => '{%level_val}',
        'status' => '{%status_val}',
        'module' => '{%module_val}',
    ];

    protected $scene = [
        'add'   => ['title', 'level', 'status', 'module'],
        'edit'  => ['title', 'level', 'status', 'module'],
        'status' => ['status'],
        'title' => ['title'],
        'level' => ['level'],
        'notation' => ['notation'],
    ];
}