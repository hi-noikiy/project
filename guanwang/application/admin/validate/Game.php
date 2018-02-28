<?php
namespace app\admin\validate;

use think\Validate;

class Game extends Validate
{
    protected $rule = [
        'name' => 'require',
        'name_en' => 'require',
        'image_url' => 'require',
        'recommend' => 'require|in:0,1,2',
        'sorts' => 'require|integer|>=:1',
        'show_way' => 'require|in:1,2',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'name' => '{%name_val}',
        'name_en' => '{%name_en_val}',
        'image_url' => '{%image_url_val}',
        'recommend' => '{%recommend_val}',
        'sorts' => '{%sorts_val}',
        'status' => '{%status_val}',
    ];

    protected $scene = [
        'add'   => ['name', 'name_en', 'image_url', 'recommend', 'sorts', 'status'],
        'edit'  => ['name', 'name_en', 'image_url', 'recommend', 'sorts', 'status'],
        'status' => ['status'],
    ];
}