<?php
include_once('common.inc.php');

$from = $_GET['fromTime'];
$to = $_GET['toTime'];
$uid = $_GET['uid'];

    if(!$_GET['fromTime'])
    {
        $from = mktime(0, 0, 0,date("m")-1,'01',date("Y"));
        $from = date('Y-m-d',$from);
    }
    if(!$_GET['toTime'])
    {
        $lastdate = date('t',$from);   //计算当月天数
        $to = mktime(0, 0, 0,date("m")-1,$lastdate,date("Y"));
        $to = date('Y-m-d',$to);
    }

    //找出有效上班日期
    $workClass = new workday();
    $workClass->setWhere(" workday>='$from' and workday<='$to' and tag='1'");
    $workClass->pageReNum = "1000";
    $timelist = $workClass->getArray('pass');
    $timestr = "";

    foreach($timelist as $v)
    {
        $timestr .= "'".$v['workday']."',";
    }
    $timestr = substr($timestr,0,-1);
    $n = count($timelist);   //上班天数
    $n = 20;
    $self = new admin();    //查看权限
    $sear = $self->getInfo($_SESSION['ADMIN_ID'],'seartag','pass');
    //找出员工列表

    $adminList = new admin();
    $adminList->wheres .=" and id!='99' and id!='145' and (depId!='11' or depMax='1' )";
	$adminList->setOrder('depId');
    if($_SESSION['ADMIN_ID']!='99' && !$sear)  //总经理账号99
    {
        $adminList->wheres .=" and id='".$_SESSION['ADMIN_ID']."'";
    }
    if($uid)
    {
        $adminList->wheres .=" and id='$uid'";
    }
    $adminList->pageReNum = "500";
    $adminres = $adminList->getArray("pass");

    foreach($adminres as $key=>$admin)
    {
    	//8月29号补班
    	$fillTime = $webdb->getValue("select totaltime from _web_record where card_id='".$admin['card_id']."' and recorddate ='2015-08-29'",'totaltime');
    	//8月29号调休
    	$hugh_0829 = $webdb->getValue("select sum(totalTime) as totalTime from _web_hugh where uid='{$admin['id']}' and fromTime='2015-08-29' and depTag='2' and perTag='2' and manTag='2'", 'totalTime');
    	$hugh_0829 = $hugh_0829*60;
    	//8月29号总的上班时间
    	$work_0829 = (($fillTime+$hugh_0829) > 480)? '480': ($fillTime+$hugh_0829);
    	
        $res = 0;
        $reshugh = 0;
        $resout = 0;
        foreach($timelist as $vd)
        {
        	if($vd['workday'] == '2015-08-29')
        		continue;
            //计算有效上班时间
            $res = $webdb->getValue("select totaltime from _web_record where card_id='".$admin['card_id']."' and recorddate ='".$vd['workday']."'",'totaltime');
            
        	if($vd['workday'] == '2015-08-18') {
            
            	$tmpRes = $res;
            	//18号调休的宗时间
            	$hugh_0818 = $webdb->getValue("select sum(totalTime) as totalTime from _web_hugh where uid='{$admin['id']}' and fromTime='2015-08-18' and depTag='2' and perTag='2' and manTag='2'", 'totalTime');
            	$hugh_0818 = $hugh_0818*60;
            	//小于400分钟 说明下午没有调休
            	if($hugh_0818 < 300){ 
            		if($res)
            			$res = $fillTime>400 ? $res+400:$res+$fillTime;
            		if($res> 480)
            			$res = 480;
            		
            		 
            	}
            	//29号补休剩余的时间
            	if ($work_0829)
            		$work_0829 = $work_0829 - (480-$tmpRes-$hugh_0818);
            }
            	
            if($vd['workday'] == '2015-08-20') {
            	
            	if($res != 480)
            		$res = $res+$work_0829;
            	if($res > 480)
            		$res = 480;
            }
            
            $admin['total'] += $res;
            //计算调休时间
            $reshugh = acLateTime('',$vd['workday'],$vd['workday'],$admin['id']);
            $admin['late'] += $reshugh;
            //计算公出时间
            $resout = $webdb->getValue("select sum(totalM) as outs from _web_outrecord  where  available='1' and manTag='2' and uid='".$admin['id']."' and fromTime>='".$vd['workday']."' and toTime<='".$vd['workday']."' ",'outs');
            $admin['outs'] += $resout;
            $tmp = 8*60 - $reshugh - $res - $resout;
            if($tmp>0)
            $admin['dis'] += $tmp;
        }
        //计算总上班时间
        $resall = $webdb->getValue("select sum(totalall) as totalall from _web_record where card_id='".$admin['card_id']."' and recorddate>='$from' and recorddate<='$to' ",'totalall');
        $admin['totalall'] = $resall;
        //加班时间
        $resover = $webdb->getValue("select sum(totalTime) as over from _web_overtime where  available='1' and addtag='1' and uid='".$admin['id']."' and fromTime>='$from' and toTime<='$to' ",'over');
        $admin['over'] = $resover;

        //计算请假时间
        $resleave = $webdb->getValue("select sum(totalTime) as leaves from _web_leave  where  available='1' and manTag='2' and uid='".$admin['id']."' and fromTime>='$from' and toTime<='$to' ",'leaves');
        $admin['leaves'] = $resleave;
        $adminres[$key] = $admin;
    }

Header("Content-type:   application/octet-stream");
Header("Accept-Ranges:   bytes");
Header("Content-type:application/vnd.ms-excel");
Header("Content-Disposition:attachment;filename=kq_stat".$from."-".$to.".xls");
?>
<table cellspacing="0" cellpadding="0" border="1">
    <tr>
      <th scope="col" class="N_title">编号</th>
      <th scope="col" class="N_title">姓名</th>
      <th scope="col" class="N_title">总上班时间</th>
      <th scope="col" class="N_title">需上班时间</th>
      <th scope="col" class="N_title">正常上班时间</th>
      <th scope="col" class="N_title">加班时间</th>
      <th scope="col" class="N_title">调休时间</th>
      <th scope="col" class="N_title">请假时间</th>
      <th scope="col" class="N_title">公出时间</th>
      <th scope="col" class="N_title">扣考勤</th>
      <th scope="col" class="N_title">扣考勤(小时)</th>
    </tr>
    <tr class="Ls2">
        <td class="N_title" colspan="10">时间段:<? echo $from."~".$to;?></td>
    </tr>
    <?
        $i=0;
        //print_r($adminres);
        foreach($adminres as $val){
            $i++;
            ?>
    <tr class="Ls2">
        <td class="N_title"><?=$i?></td>
        <td class="N_title"><?=$val['real_name']?></td>
        <td class="N_title"><?=floor($val['totalall']/60)."小时".($val['totalall']%60)."分钟"?></td>
        <td class="N_title"><?=$n*8?>小时</td>
        <td class="N_title"><?=floor($val['total']/60)."小时".($val['total']%60)."分钟"?></td>
        <td class="N_title"><?=$val['over']?$val['over'].'小时':'&nbsp;'?></td>
        <td class="N_title"><?=$val['late']>0?floor($val['late']/60)."小时".($val['late']%60)."分钟":'&nbsp;'?></td>
        <td class="N_title"><?=$val['leaves']?$val['leaves'].'小时':'&nbsp;'?></td>
        <td class="N_title"><?=$val['outs']?floor($val['outs']/60)."小时".($val['outs']%60)."分钟":'&nbsp;'?></td>
        <td class="N_title">
        <?
            $left = $val['dis'];
            if($left<0)
            echo "0小时0分钟";
            else
            echo floor($left/60)."小时".($left%60)."分钟";
        ?>
        </td>      
           <td class="N_title">
        <?
            $left = $val['dis'];
            if($left<0)
            echo "0";
            else
            echo round($left/60,1);
        ?>
        </td>       
    </tr>
    <?}?>
  </table>
