<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 报备导出excel
* ==============================================
* @date: 2015-6-2
* @author: Administrator
* @return:
*/
include_once('common.inc.php');

$from = $_GET['fromTime'];
$to = $_GET['toTime'];
$uid = intval($_GET['uid']);

if(empty($from) || empty($to)){
	echo '<script>alert("请查询时间段之后才能使用导出功能")</script>';
	go('index.php?type=web&do=list&cn=leave_filing');
	exit ();
}
/* if(!$_GET['fromTime']){
	$from = mktime(0, 0, 0,date("m")-1,'01',date("Y"));
	$from = date('Y-m-d',$from);
}
if(!$_GET['toTime']){
	$lastdate = date('t',$from);   //计算当月天数
    $to = mktime(0, 0, 0,date("m")-1,$lastdate,date("Y"));
    $to = date('Y-m-d',$to);
} */
$sql="select sum(a.totalTime) as sumtotalTime, a.*, b.real_name from _web_leave_filing a 
		left join _sys_admin b on a.uid=b.id  where a.fromTime>='$from' and a.fromTime<='$to' and a.available=1 group by a.uid,a.fromTime order by a.fromTime asc;";
$filingList=$webdb->getList($sql);
if(!empty($filingList)){
	foreach ($filingList as $k=>$v){
		$filingList[$k]['depName']=$webdb->getValue("select name from _web_department where id='{$v['depId']}'", 'name');
		
		$count=$webdb->getValue("select count(*) as count from _web_leave_filing where uid='{$v['uid']}' and fromTime='{$v['fromTime']}'", 'count');
		if($count<=1){
			if($v['sumtotalTime']=='8')
				$filingList[$k]['startEndTime']='09:00~18:30';
			else
				$filingList[$k]['startEndTime']=$v['hour_s'].':'.$v['minute_s'].'~'.$v['hour_e'].':'.$v['minute_e'];
		} else {
			//处理特殊报备情况的
			$list=$webdb->getList("select fromTime,hour_s,minute_s,toTime,hour_e,minute_e from _web_leave_filing where uid='{$v['uid']}' and fromTime='{$v['fromTime']}'");
			foreach ($list as $v){
				$filingList[$k]['startEndTime'].=$v['hour_s'].':'.$v['minute_s'].'~'.$v['hour_e'].':'.$v['minute_e'].'  ';
			}
		}
	}
}

header("Content-type:application/octet-stream");
header("Accept-Ranges:bytes");
header("Content-type:application/vnd.ms-excel");
header("Content-Disposition:attachment;filename=kq_filing".$from."-".$to.".xls");
?>
<table border="1">
    <tr>
      <th width="60">编号</th>
      <th width="150">姓名</th>
      <th width="150">部门</th>   
      <th width="150">报备日期</th>
      <th width="150">请假日期</th>
      <th width="250">开始、结束时间</th>
      <th width="150">请假总时间</th>
    </tr>
    <tr class="Ls2">
        <td class="N_title" colspan="6">时间段:<? echo $from."~".$to;?></td>
    </tr>
    <?
        $i=0;
        if(!empty($filingList)){
        foreach($filingList as $val){
            $i++;
    ?>
    <tr align="center">
        <td><?=$i?></td>
        <td><?=$val['real_name']?></td>
        <td><?=$val['depName']?></td>       
        <td><?=$val['addDate'] ?></td>
        <td><?=$val['fromTime']?></td>
        <td><?=$val['startEndTime']?></td>
        <td><?=$val['sumtotalTime']?>小时</td>
    </tr>
    <?}}?>
  </table>