<?php
namespace app\admin\validate;

use think\Validate;

class WebsiteInfo extends Validate
{
    protected $rule = [
        'title' => 'require',
        'title_en' => 'require',
        'type' => 'require|integer|>=:1',
        //'image_url' => 'require',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'title' => '{%title_val}',
        'title_en' => '{%title_en_val}',
        'type' => '{%type_val}',
        'image_url' => '{%image_url_val}',
        'status' => '{%status_val}',
    ];

    protected $scene = [
        'add'   => ['title', 'title_en', 'type', 'image_url', 'status'],
        'edit'  => ['title', 'title_en', 'type', 'image_url', 'status'],
    ];
}