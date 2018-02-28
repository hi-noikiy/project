<?php
namespace app\admin\validate;

use think\Validate;

class News extends Validate
{
    protected $rule = [
        'title' => 'require',
        'title_en' => 'require',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'title' => '{%title_val}',
        'title_en' => '{%title_en_val}',
        'status' => '{%status_val}',
    ];

    protected $scene = [
        'add'   => ['title', 'title_en', 'status'],
        'edit'  => ['title', 'title_en', 'status'],
        'status' => ['status'],
    ];
}