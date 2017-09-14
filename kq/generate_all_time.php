<?php
include_once('common.inc.php');
$startday = '2011-01-25';
$endday = '2011-01-25';
$starttime = strtotime($startday." 00:00:00");
$endtime = strtotime($endday." 00:00:00");
//$month12 = date('Y-m-d',strtotime("-1 month"));

$admin = new admin();
$admin->wheres = " id!='99' and id!='145' and (depId!='11' or depMax='1')";
$admin->pageReNum = '500';
$adlist = $admin->getArray("pass");

foreach($adlist as $ad)
{
    for($t=$starttime;$t<=$endtime;$t=$t+86400)  //计算开始到结束时间的每天的总上班时间
    {
        $to = date('Y-m-d',$t);
        acAllTotalTime($ad['card_id'],$to);
    }
}
?>