<?php
include_once('common.inc.php');
$workClass = new workday();
for($i=2;$i<4;$i++)   //定制下第$i个月的工作日
{
    $time = mktime(0, 0, 0,date("m")+$i,'01',date("Y"));
    $year = date('Y',$time);
    $month = date('m',$time);
    $date = date('Y-m-d',$time);
    $lastdate = date('t',$time);   //计算当月天数
    $workClass->wheres = "workday='$date'";
    $workClass->orders = 'id desc';
    $workClass->pageReNum = 1;
    $list = $workClass->getList();
    //echo $workClass->querySql;
    //var_dump($list);
	if(!$list)
    {
        for($m=1;$m<=$lastdate;$m++)
        {
          $day = $year."-".$month."-".$m;        //生成日期
          $today = date('N',strtotime($day));    //找出星期几
          if($today<6)  //周一到周五
          {
            $workClass->add(array("workday"=>$day,"tag"=>"1"));
          }
          else//周六\日
          {
            $workClass->add(array("workday"=>$day,"tag"=>"0"));
          }
        }
    }
}
?>