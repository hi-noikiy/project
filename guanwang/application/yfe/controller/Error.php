<?php
namespace app\yfe\controller;

use think\Request;
use think\Controller;

class Error extends Common
{
    public function index(Request $request)
    {
        $modelName = $request->controller();
        return $this->fetch('/'.$modelName);
    }
}