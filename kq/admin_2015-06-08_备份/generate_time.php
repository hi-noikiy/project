<?php
include_once('common.inc.php');
$astart = '';
$bstart = '';
$cstart = '';
$dstart = '';
$estart = '';
$aend = '';
$bend = '';
$cend = '';
$dend = '';
$eend = '';

$workClass = new workday();
$fromtime = $_REQUEST['fromtime'];
$totime = $_REQUEST['totime'];
if(!$fromtime)
$fromtime = date("Y-m-d",strtotime("-4 days"));
if(!$totime)
{
    $totime = date("Y-m-d",strtotime("-1 days"));
}
$workClass->setWhere(" workday>='$fromtime' and workday<='$totime' ");
$workClass->pageReNum = "1000";
$timelist = $workClass->getList();
$recordClass = new record();
$recordClass->pageReNum = "500";
foreach($timelist as $val)
{
    $recordClass->wheres = " recorddate='".$val['workday']."' and gong_id!='0' ";
    $recordClass->orders = " recorddate desc";
    $recordlist = $recordClass->getList();
    //print_r($recordlist);
    foreach($recordlist as $recordval)
    {
        $timeary = array();
        if($recordval["addtime_ex"])
        $timeary[$recordval["id"]] = explode(",",$recordval['addtime_ex']);
        //print_r($timeary);
        getTotalTime($timeary[$recordval["id"]],$recordval["id"],$recordval["recorddate"]);
        //print_r($timeary);
    }
}

//计算有效上班时间
//$ary为时间数组，$id为考勤记录id,$date当天日期
function getTotalTime($ary,$id,$date)
{
    global $astart;
    global $bstart;
    global $cstart;
    global $dstart;
    global $estart;
    global $aend;
    global $bend;
    global $cend;
    global $dend;
    global $eend;
    $total = "0";
    $length = count($ary);      
    if($length>1)
    {
        $time09 = strtotime($date." 09:00:00");
        $time12 = strtotime($date." 12:00:00");
        $time13 = strtotime($date." 13:30:00");
        $time18 = strtotime($date." 18:30:00");
        //echo $date.",";
        for($i=0;$i<$length;)
        {
            if($length>$i+1)
            {
                $thistime = strtotime($date." ".$ary[$i].":00");
                if($thistime<$time09)
                $astart = $thistime;
                elseif($thistime>=$time09 && $thistime<$time12)
                $bstart = $thistime;
                elseif($thistime>=$time12 && $thistime<=$time13)
                $cstart = $thistime;
                elseif($thistime>$time13 && $thistime<$time18)
                $dstart = $thistime;
                elseif($thistime>=$time18)
                $estart = $thistime;

                $thattime = strtotime($date." ".$ary[$i+1].":00");
                if($thattime<$time09)
                $aend = $thattime;
                elseif($thattime>=$time09 && $thattime<$time12)
                $bend = $thattime;
                elseif($thattime>=$time12 && $thattime<=$time13)
                $cend = $thattime;
                elseif($thattime>$time13 && $thattime<$time18)
                $dend = $thattime;
                elseif($thattime>=$time18)
                $eend = $thattime;

                echo "as".$astart."bs".$bstart."cs".$cstart."ds".$dstart."es".$estart."vv"."ae".$aend."be".$bend."ce".$cend."de".$dend."ee".$eend."<br>";
                
                if($astart && $aend)
                {
                    //不执行
                    $i++;
                    $astart = '';
                    $aend = '';
                }
                elseif($astart && $bend)
                {
                    $total += $bend - $time09;
                    $i += 2;
                    $astart = '';
                    $bend = '';
                }
                elseif($astart && $cend)
                {
                    $total += 3*60*60;
                    $i += 2;
                    $astart = '';
                    $cend = '';
                }
                elseif($astart && $dend)
                {
                    $astart = '';
                    $dend = '';
                    break;   //跳出循环
                }
                elseif($astart && $eend)
                {
                    $astart = '';
                    $eend = '';
                    break;   //跳出循环
                }
                elseif($bstart && $bend)
                {
                    $total += $bend - $bstart;
                    $i += 2;
                    $bstart = '';
                    $bend = '';
                }
                elseif($bstart && $cend)
                {
                    $total += $time12 - $bstart;
                    $i += 2;
                    $bstart = '';
                    $cend = '';
                }
                elseif($bstart && $dend)
                {
                    $bstart = '';
                    $dend = '';
                    break;   //跳出循环
                }
                elseif($bstart && $eend)
                {
                    $bstart = '';
                    $eend = '';
                    break;   //跳出循环
                }
                elseif($cstart && $cend)
                {
                    $i++;           //调到下一个循环
                    $cstart = '';
                    $cend = '';

                }
                elseif($cstart && $dend)
                {
                    $total += $dend - $time13;
                    $i += 2;
                    $cstart = '';
                    $dend = '';
                }
                elseif($cstart && $eend)
                {
                    $total += 5*60*60;
                    $i = $length;
                    $cstart = '';
                    $eend = '';
                }
                elseif($dstart && $dend)
                {
                    $total += $dend - $dstart;
                    $i += 2;
                    $dstart = '';
                    $dend = '';
                }
                elseif($dstart && $eend)
                {
                    $total += $time18 - $dstart;
                    $dstart = '';
                    $eend = '';
                    break;   //跳出循环
                }
                elseif($estart && $eend)
                {
                    $estart = '';
                    $eend = '';
                    break;   //跳出循环
                }
                else
                {
                    break;
                }
            }
            else{
                break;
            }
        }
    }
    $totaltime = $total/60;
    $latetime = 480 - $totaltime;
    $recordC = new record();
    $recordC->edit(array('latetime'=>$latetime,'totaltime'=>$totaltime), $id);
}
//判断打卡在哪个范围
function ckTime($time,$day,$tag)
{
    global $astart;
    global $bstart;
    global $cstart;
    global $dstart;
    global $estart;
    global $aend;
    global $bend;
    global $cend;
    global $dend;
    global $eend;
    
    $time09 = strtotime($day." 09:00:00");
    $time12 = strtotime($day." 12:00:00");
    $time13 = strtotime($day." 13:30:00");
    $time18 = strtotime($day." 18:30:00");
    $thistime = strtotime($day." ".$time);
    if($tag=='start')
    {
        if($thistime<$time09)
        $astart = $thistime;
        elseif($thistime>=$time09 && $thistime<$time12)
        $bstart = $thistime;
        elseif($thistime>=$time12 && $thistime<=$time13)
        $cstart = $thistime;
        elseif($thistime>$time13 && $thistime<$time18)
        $dstart = $thistime;
        elseif($thistime>=$time18)
        $estart = $thistime;
    }
    elseif($tag=='end')
    {
        if($thistime<$time09)
        $aend = $thistime;
        elseif($thistime>=$time09 && $thistime<$time12)
        $bend = $thistime;
        elseif($thistime>=$time12 && $thistime<=$time13)
        $cend = $thistime;
        elseif($thistime>$time13 && $thistime<$time18)
        $dend = $thistime;
        elseif($thistime>=$time18)
        $eend = $thistime;
    }
}
?>