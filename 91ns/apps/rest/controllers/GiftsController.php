<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class GiftsController extends ControllerBase
{
    //获取用户的礼物列表
    public function getList() {
        $returnStatusCode = $this->status->getStatus('OK');
    }

    //送礼接口
}