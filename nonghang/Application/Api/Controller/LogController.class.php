<?php
/**
 * 注释
 */
namespace Api\Controller;
use Think\Controller;
class LogController extends Controller {
   public function index()
   {
      $content = file_get_contents('./Runtime/testLog/'.date('Y-m-d').'.log');
      echo './Runtine/testLog/'.date('Y-m-d').'.log';
      dump($content);
   }
}