<?php
include_once('common.inc.php');

$from = $_GET['from'];
$to = $_GET['to'];
$id = $_GET['id'];
$left = $_GET['left'];
$total = $_GET['total'];
$over = $_GET['over'];
$outs = $_GET['outs'];
$late = $_GET['late'];
$card_id = $_GET['card_id'];

    if(!$_GET['from'])
    {
        $from = mktime(0, 0, 0,date("m")-1,'01',date("Y"));
        $from = date('Y-m-d',$from);
    }
    if(!$_GET['to'])
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
    $arytmp = array();
    foreach($timelist as $v)
    {
        $timestr .= "'".$v['workday']."',";
        $arytmp[$v['workday']] = '';
    }
    $timestr = substr($timestr,0,-1);
    $n = count($timelist);   //上班天数

    $admin = new admin();
    $info = $admin->getInfo($id,'','pass');

        //计算有效上班时间
        $recordlist = $webdb->getList("select * from _web_record where card_id='".$info['card_id']."'  and recorddate in($timestr)");
        //计算调休时间
        $hughlist = $webdb->getList("select *  from _web_hugh where  available='1' and addtag='1' and uid='".$info['id']."' and fromTime>='$from' and fromTime<='$to'");
        //加班时间
        $overlist = $webdb->getList("select *  from _web_overtime where  available='1' and addtag='1' and uid='".$info['id']."' and fromTime>='$from' and toTime<='$to' ");
        //请假时间
        $leavelist = $webdb->getList("select *  from _web_leave where  available='1' and manTag='2' and uid='".$info['id']."' and fromTime>='$from' and toTime<='$to' and leaveType!='年假'");
		//年假时间
        $annuallist = $webdb->getList("select *  from _web_leave where  available='1' and manTag='2' and uid='".$info['id']."' and fromTime>='$from' and toTime<='$to' and leaveType='年假'");
        //公出时间
        $outlist = $webdb->getList("select *  from _web_outrecord where  available='1' and manTag='2' and uid='".$info['id']."' and fromTime>='$from' and toTime<='$to' ");

        foreach($recordlist as $val)
        {
            $arytmp[$val['recorddate']] = $val;
        }

Header("Content-type:   application/octet-stream");
Header("Accept-Ranges:   bytes");
Header("Content-type:application/vnd.ms-excel");
Header("Content-Disposition:attachment;filename=kq_detail".$from."-".$to.".xls");
?>
<table cellspacing="0" cellpadding="0" class="Admin_L" border="1">
    <tr>
      <th scope="col" class="N_title">日期</th>
      <th scope="col" class="N_title" width="30%">门禁记录</th>
      <th scope="col" class="N_title" >指纹记录</th>
      <th scope="col" class="N_title">正常上班</th>
      <th scope="col" class="N_title">扣考勤</th>
      <th scope="col" class="N_title">已调休</th>
      <th scope="col" class="N_title">已请假</th>
	  <th scope="col" class="N_title">年假</th>
    </tr>
    <tr class="Ls2">
        <td class="N_title" colspan="7"><font color="red">正常上班时间:<?=floor($total/60)."小时".($total%60)."分钟"?></font></td>
    </tr>
    <?foreach($arytmp as $key=>$val){
        $ctag = '';
        if($val)
        {
            $adid = $webdb->getValue("select id from _sys_admin where card_id='".$val['card_id']."'",'id');
            //$lt = $webdb->getValue("select sum(latetime) as la from _web_hugh where uid='$adid' and available='1' and addtag='1' and fromTime='".$val['recorddate']."'",'la');
            $lt = acLateTime('',$key,$key,$adid);
            if($lt<$val['latetime'])$ctag = true;
    ?>

    <tr class="Ls2">
        <td class="N_title"><?=$val['recorddate']?></td>
        <td class="N_title">
            <?
                $val['addtime'] = preg_replace('/\[\d{2}:\d{2}:\d{2}\s\[进门2\]\]/i','',$val['addtime']);
                echo $val['addtime']?$val['addtime']:'&nbsp;'
            ?>
        </td>
        <td class="N_title"><?=$val['addtime_ex']?$val['addtime_ex']:'&nbsp;'?></td>
        <td class="N_title"><?=floor($val['totaltime']/60)."小时".($val['totaltime']%60)."分钟"?></td>
        <td class="N_title">
            <?
                if($ctag)
                echo $val['latetime']>0?"<font color='red'>".(floor($val['latetime']/60)."小时".($val['latetime']%60)."分钟")."</font>":'&nbsp';
                else
                echo $val['latetime']>0?(floor($val['latetime']/60)."小时".($val['latetime']%60)."分钟"):'&nbsp';
            ?>
        </td>
        <td class="N_title">
            <?
                if($ctag)
                echo $lt>0?"<font color='red'>".(floor($lt/60)."小时".($lt%60)."分钟")."</font>":'&nbsp;';
                else
                echo $lt>0?(floor($lt/60)."小时".($lt%60)."分钟"):'&nbsp;';
            ?>
        </td>
        <td class="N_title">
            <?
                $le = $webdb->getValue("select sum(totalTime) as le from _web_leave where uid='$adid' and available='1' and manTag='2' and fromTime='".$val['recorddate']."' and leaveType!='年假'",'le');
                echo $le>0?"<font color='red'>$le 小时</font>":'&nbsp;';
            ?>
        </td>
		<td class="N_title">
            <?
                $ld = $webdb->getValue("select sum(totalTime) as le from _web_leave where uid='$adid' and available='1' and manTag='2' and fromTime='".$val['recorddate']."' and leaveType='年假'",'le');
                echo $ld>0?"<font color='red'>$ld 小时</font>":'&nbsp;';
            ?>
        </td>
    </tr>
    <?
        }
        else
        {
            $adid = $webdb->getValue("select id from _sys_admin where card_id='".$card_id."'",'id');
            $lt = acLateTime('',$key,$key,$adid);
            $c = 8*60;
            if($lt<$c)$ctag = true;
        ?>
            <tr class="Ls2">
        <td class="N_title"><?=$key?></td>
        <td class="N_title">&nbsp;</td>
        <td class="N_title">&nbsp;</td>
        <td class="N_title">&nbsp;</td>
        <td class="N_title">
            <?
                if($ctag)echo "<font color='red'>8小时0分钟</font>";
                else echo "8小时0分钟";
            ?>
        </td>
        <td class="N_title">
            <?
                if($ctag)
                echo $lt>0?"<font color='red'>".(floor($lt/60)."小时".($lt%60)."分钟")."</font>":'&nbsp;';
                else
                echo $lt>0?(floor($lt/60)."小时".($lt%60)."分钟"):'&nbsp;';
            ?>
        </td>
        <td class="N_title">
            <?
                $le = $webdb->getValue("select sum(totalTime) as le from _web_leave where uid='$adid' and available='1' and manTag='2' and fromTime='".$key."'",'le');
                echo $le>0?"<font color='red'>$le 小时</font>":'&nbsp;';
            ?>
        </td>
    </tr>
        <?
        }
     }?>
  </table>
     <table cellspacing="0" cellpadding="0" class="Admin_L" border="1">
    <tr>
      <th scope="col" class="N_title">姓名</th>
      <th scope="col" class="N_title">调休时间</th>
      <th scope="col" class="N_title">合计</th>
    </tr>
    <tr class="Ls2">
        <td class="N_title" colspan="3"><font color="red">调休时间:<?=floor($late/60)."小时".($late%60)."分钟"?></font></td>
    </tr>
    <?foreach($hughlist as $val){?>
    <tr class="Ls2">
        <td class="N_title"><?=$info['real_name']?></td>
        <td class="N_title"><?=$val['fromTime'].' '.$val['hour_s'].':'.$val['minute_s']."~".$val['toTime'].' '.$val['hour_e'].':'.$val['minute_e']?></td>
        <td class="N_title">
        <?
            $tt = acLateTime($val['id']);    //抵考勤时间
            echo floor($tt/60)."小时".($tt%60)."分钟"
        ?>
        </td>
    </tr>
    <?}?>
  </table>
     <table cellspacing="0" cellpadding="0" class="Admin_L" border="1">
    <tr>
      <th scope="col" class="N_title">姓名</th>
      <th scope="col" class="N_title">加班时间</th>
      <th scope="col" class="N_title">合计</th>
    </tr>
    <tr class="Ls2">
        <td class="N_title" colspan="3"><font color="red">加班时间:<?=$over?$over:'0'?>小时</font></td>
    </tr>
    <?foreach($overlist as $val){?>
    <tr class="Ls2">
        <td class="N_title"><?=$info['real_name']?></td>
        <td class="N_title"><?=$val['fromTime'].' '.$val['hour_s'].':'.$val['minute_s']."~".$val['toTime'].' '.$val['hour_e'].':'.$val['minute_e']?></td>
        <td class="N_title"><?=$val['totalTime']?>小时</td>
    </tr>
    <?}?>
  </table>
     <table cellspacing="0" cellpadding="0" class="Admin_L" border="1">
    <tr>
      <th scope="col" class="N_title">姓名</th>
      <th scope="col" class="N_title">请假时间</th>
      <th scope="col" class="N_title">合计</th>
    </tr>
    <tr class="Ls2">
        <td class="N_title" colspan="3"><font color="red">请假时间:<?=$leaves?$leaves:'0'?>小时</font></td>
    </tr>
    <?foreach($leavelist as $val){?>
    <tr class="Ls2">
        <td class="N_title"><?=$info['real_name']?></td>
        <td class="N_title"><?=$val['fromTime'].' '.$val['hour_s'].':'.$val['minute_s']."~".$val['toTime'].' '.$val['hour_e'].':'.$val['minute_e']?></td>
        <td class="N_title"><?=$val['totalTime']?>小时</td>
    </tr>
    <?}?>
  </table>
<table cellspacing="0" cellpadding="0" class="Admin_L" border="1">
    <tr>
      <th scope="col" class="N_title">姓名</th>
      <th scope="col" class="N_title">公出时间</th>
      <th scope="col" class="N_title">合计</th>
    </tr>
    <tr class="Ls2">
        <td class="N_title" colspan="3"><font color="red">公出时间:<?=$outs?floor($outs/60)."小时".($outs%60)."分钟":'0小时'?></font></td>
    </tr>
    <?foreach($outlist as $val){?>
    <tr class="Ls2">
        <td class="N_title"><?=$info['real_name']?></td>
        <td class="N_title"><?=$val['fromTime'].' '.$val['hour_s'].':'.$val['minute_s']."~".$val['toTime'].' '.$val['hour_e'].':'.$val['minute_e']?></td>
        <td class="N_title"><?=$val['totalTime']?>小时</td>
    </tr>
    <?}?>
  </table>