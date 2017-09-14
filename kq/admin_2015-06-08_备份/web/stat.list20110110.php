<?php
$from = $_POST['fromTime'];
$to = $_POST['toTime'];
$uid = $_POST['uid'];

    if(!$_POST['fromTime'])
    {
        $from = mktime(0, 0, 0,date("m")-1,'01',date("Y"));
        $from = date('Y-m-d',$from);
    }
    if(!$_POST['toTime'])
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

    $self = new admin();    //查看权限
    $sear = $self->getInfo($_SESSION['ADMIN_ID'],'seartag','pass');
    //找出员工列表

    $adminList = new admin();
    $adminList->wheres .=" and id!='99' and id!='145' and (depId!='11' or depMax='1' )";

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
        //计算有效上班时间
        $res = $webdb->getValue("select sum(totaltime) as total from _web_record where card_id='".$admin['card_id']."' and recorddate in($timestr)",'total');
        $admin['total'] = $res;
        //计算调休时间
        //$reshugh = $webdb->getValue("select sum(latetime) as late from _web_hugh where  available='1' and addtag='1' and uid='".$admin['id']."' and fromTime in($timestr)",'late');
        $reshugh = acLateTime('',$from,$to,$admin['id']);
        $admin['late'] = $reshugh;
        //加班时间
        $resover = $webdb->getValue("select sum(totalTime) as over from _web_overtime where  available='1' and addtag='1' and uid='".$admin['id']."' and fromTime>='$from' and toTime<='$to' ",'over');
        $admin['over'] = $resover;
        //计算公出时间
        $resout = $webdb->getValue("select sum(totalTime) as outs from _web_outrecord  where  available='1' and manTag='2' and uid='".$admin['id']."' and fromTime>='$from' and toTime<='$to' ",'outs');
        $admin['outs'] = $resout;
        //计算请假时间
        $resleave = $webdb->getValue("select sum(totalTime) as leaves from _web_leave  where  available='1' and manTag='2' and uid='".$admin['id']."' and fromTime>='$from' and toTime<='$to' ",'leaves');
        $admin['leaves'] = $resleave;
        $adminres[$key] = $admin;
    }
?>
<h1 class="title"><span>考勤统计查询</span></h1>
 <div class="pidding_5">
     <div class="search">
         <form action="" method="post">
             时间：<input type="text" name="fromTime" size="10" id="date_s" value="<?=$from?>" readonly> 到：
        <input type="text" name="toTime" size="10" id="date_e" value="<?=$to?>" readonly>
        <?if($sear=='1'){?>
        姓名:<select name="uid">
              <option value="">全部</option>
                <?php
                    $sql = "select id,real_name from _sys_admin where id <>'99'";
                    $res = $webdb->getList($sql);
                    foreach($res as $val){
                ?>
              <option value="<?=$val['id']?>" <?php echo $val['id']==$uid?'selected':'' ?>><?=$val['real_name']?></option>
        <?}?>
              </select>
        <? }?>
        <input type="submit" name="sub" value="查 询" class="sub2">
         </form>
    </div>
     <font color="red">扣考勤 = 需上班时间(8*工作天数) - 正常上班时间(工作日上班时间，即不包括加班时间) - 公出时间 - 调休时间</font>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" class="N_title">编号</th>
      <th scope="col" class="N_title">姓名</th>
      <th scope="col" class="N_title">需上班时间</th>
      <th scope="col" class="N_title">正常上班时间</th>
      <th scope="col" class="N_title">加班时间</th>
      <th scope="col" class="N_title">调休时间</th>
      <th scope="col" class="N_title">请假时间</th>
      <th scope="col" class="N_title">公出时间</th>
      <th scope="col" class="N_title">扣考勤</th>
      <th scope="col" class="N_title">操作</th>
    </tr>
    <tr class="Ls2">
        <td class="N_title" colspan="10">时间段:<? echo $from."~".$to;?></td>
    </tr>
    <?
        $i=0;
        foreach($adminres as $val){
            $i++;
            ?>
    <tr class="Ls2">
        <td class="N_title"><?=$i?></td>
        <td class="N_title"><?=$val['real_name']?></td>
        <td class="N_title"><?=$n*8?>小时</td>
        <td class="N_title"><?=floor($val['total']/60)."小时".($val['total']%60)."分钟"?></td>
        <td class="N_title"><?=$val['over']?$val['over']:'0'?>小时</td>
        <td class="N_title"><?=floor($val['late']/60)."小时".($val['late']%60)."分钟"?></td>
        <td class="N_title"><?=$val['leaves']?$val['leaves']:'0'?>小时</td>
        <td class="N_title"><?=$val['outs']?$val['outs']:'0'?>小时</td>
        <td class="N_title">
        <?
            $left = $n*8*60 - $val['total'] - $val['late'] - $val['outs']*60;
            if($left<0)
            echo "0小时0分钟";
            else
            echo floor($left/60)."小时".($left%60)."分钟";
        ?>
        </td>
        <td class="N_title">
            <a href="detail.php?id=<?=$val['id']?>&from=<?=$from?>&to=<?=$to?>&left=<?=$left?>&total=<?=$val['total']?>&over=<?=$val['over']?>&late=<?=$val['late']?>&outs=<?=$val['outs']?>&leaves=<?=$val['leaves']?>&card_id=<?=$val['card_id']?>" target="_blank">详细查询</a>
        </td>
    </tr>
    <?}?>
  </table>
 </div>