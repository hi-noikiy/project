<?php
namespace app\admin\validate;

use think\Validate;

class Job extends Validate
{
    protected $rule = [
        'name' => 'require',
        'name_en' => 'require',
        'need' => 'require|integer|>=:1',
        'sorts' => 'require|integer|>=:1',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'name' => '{%name_val}',
        'name_en' => '{%name_en_val}',
        'need' => '{%need_val}',
        'sorts' => '{%sorts_val}',
        'status' => '{%status_val}',
    ];

    protected $scene = [
        'add'   => ['name', 'need', 'sorts', 'status'],
        'edit'  => ['name', 'need', 'sorts', 'status'],
        'status' => ['status'],
    ];
}