<?php
namespace app\admin\validate;

use think\Validate;

class YfeBanner extends Validate
{
    protected $rule = [
        'name' => 'require',
        'image_url' => 'require',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'name' => '{%name_val}',
        'image_url' => '{%image_url_val}',
        'status' => '{%status_val}',
    ];

    protected $scene = [
        'add'   => ['name', 'image_url', 'status'],
        'edit'  => ['name', 'image_url', 'status'],
        'status' => ['status'],
    ];
}