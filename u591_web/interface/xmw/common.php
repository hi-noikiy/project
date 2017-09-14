<?php
/*
|--------------------------------------------------------------------------
| 熊猫玩开放平台 SDK调用示例 公共文件
|--------------------------------------------------------------------------
|
| 本示例仅供参考，不建议直接使用。
| 请根据游戏方具体业务及应用环境参照本示例对接。
|
*/
// 配置本示例根目录
define('XMW_SECRET_SERVER_ROOT', dirname(__FILE__));

// 定义自动加载
function xmwanLoad($className)
{
    static $loadedClassList = array();
    if(!empty($loadedClassList[$className]))
    {
        return;
    }
    $fileName = $className . '.php';
    require_once XMW_SECRET_SERVER_ROOT . '/libraries/' . $fileName;
    $loadedClassList[$className] = true;
}
spl_autoload_register('xmwanLoad');
