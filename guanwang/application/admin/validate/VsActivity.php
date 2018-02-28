<?php
namespace app\admin\validate;

use think\Validate;

class VsActivity extends Validate
{
    protected $rule = [
        'title' => 'require',
        'begin_time' => 'require',
        'end_time' => 'require',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'title' => '{%title_val}',
        'begin_time' => '{%begin_time_val}',
        'end_time' => '{%end_time_val}',
        'status' => '{%status_val}',
    ];

    protected $scene = [
        'add'   => ['title', 'begin_time', 'end_time', 'status'],
        'edit'  => ['title', 'begin_time', 'end_time', 'status'],
        'status' => ['status'],
    ];
}