<?php
$workClass = new workday();
$year = date("Y");
$lastyear = $year-1;
$thisyear = $year;
$nextyear = $year+1;
$month = date("m");
if($_REQUEST['year'])
$year = $_REQUEST['year'];
if($_REQUEST['month'])
$month = $_REQUEST['month'];
$firstday = $year."-".$month."-01";
$totalday = date('t',strtotime($firstday." 00:00:00"));  //当月天数
$lastday = $year."-".$month."-".$totalday;
$workClass->setWhere(" workday>='$firstday' and workday<='$lastday' ");
$workClass->pageReNum = "31";
$list = $workClass->getList();
?>
<h1 class="title"><span>工作日管理</span></h1>
 <div class="pidding_5">
     <div class="search">
         <form action="" method="post">
             年份:<select name="year">
                 <option value="<?=$lastyear?>" <?php echo $lastyear==$year?'selected':'' ?>><?=$lastyear?></option>
                 <option value="<?=$thisyear?>" <?php echo $thisyear==$year?'selected':'' ?>><?=$thisyear?></option>
                 <option value="<?=$nextyear?>" <?php echo $nextyear==$year?'selected':'' ?>><?=$nextyear?></option>
             </select>
             月份:<select name="month">
                 <?
                    for($i=1;$i<13;$i++){
                        if($i<10)
                        $j = "0".$i;
                        else
                        $j = $i;
                 ?>
                 <option value="<?=$j?>" <?php echo $month==$j?'selected':'' ?>><?=$j?></option>
                 <? }?>
             </select>
        <input type="submit" name="sub" value="查 询" class="sub2">
         </form>
    </div>
   <?php if($list){?>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" class="N_title">星期一</th>
      <th scope="col" class="N_title">星期二</th>
      <th scope="col" class="N_title">星期三</th>
      <th scope="col" class="N_title">星期四</th>
      <th scope="col" class="N_title">星期五</th>
      <th scope="col" class="N_title">星期六</th>
      <th scope="col" class="N_title">星期日</th>
    </tr>
<?
    $whf = date('N',strtotime($firstday." 00:00:00"));   //第一天是星期几
    $whl = date('N',strtotime($lastday." 00:00:00"));   //最后一天是星期几
    //echo $whl;
    for($i=1;$i<$whf;$i++)
    {
        array_unshift($list,array('id'=>'0','workday'=>'0','tag'=>'0'));
    }
    
    for($i=1;$i<=7-$whl;$i++)
    {
        array_push($list,array('id'=>'0','workday'=>'0','tag'=>'0'));
    }
    
    for($i=0;$i<count($list);$i=$i+7)
    {
?>
    <tr class="Ls2">
        <td class="N_title">
        <?
            if($list[$i]['id']!="0")
            {
                $chs = $list[$i]['tag']=='1'?'checked':'';
                echo $list[$i]['workday']." <input type='checkbox' onclick='chk(this);' name=works[] value='".$list[$i]['id']."' $chs>";
            }
            else
            echo "&nbsp;";
        ?>
        </td>
        <td class="N_title">
        <?
            if($list[$i+1]['id']!="0")
            {
                $chs1 = $list[$i+1]['tag']=='1'?'checked':'';
                echo $list[$i+1]['workday']." <input type='checkbox' onclick='chk(this);'  name=works[] value='".$list[$i+1]['id']."' $chs1>";
            }
            else
            echo "&nbsp;";
        ?>
        </td>
        <td class="N_title">
        <?
            if($list[$i+2]['id']!="0")
            {
                $chs2 = $list[$i+2]['tag']=='1'?'checked':'';
                echo $list[$i+2]['workday']." <input type='checkbox' onclick='chk(this);'  name=works[] value='".$list[$i+2]['id']."' $chs2>";
            }
            else
            echo "&nbsp;";
        ?>
        </td>
        <td class="N_title">
        <?
            if($list[$i+3]['id']!="0")
            {
                $chs3 = $list[$i+3]['tag']=='1'?'checked':'';
                echo $list[$i+3]['workday']." <input type='checkbox' onclick='chk(this);'  name=works[] value='".$list[$i+3]['id']."' $chs3>";
            }
            else
            echo "&nbsp;";
        ?>
        </td>
        <td class="N_title">
        <?
            if($list[$i+4]['id']!="0")
            {
                $chs4 = $list[$i+4]['tag']=='1'?'checked':'';
                echo $list[$i+4]['workday']." <input type='checkbox' onclick='chk(this);'  name=works[] value='".$list[$i+4]['id']."' $chs4>";
            }
            else
            echo "&nbsp;";
        ?>
        </td>
        <td class="N_title">
        <?
            if($list[$i+5]['id']!="0")
            {
                $chs5 = $list[$i+5]['tag']=='1'?'checked':'';
                echo $list[$i+5]['workday']." <input type='checkbox' onclick='chk(this);'  name=works[] value='".$list[$i+5]['id']."' $chs5>";
            }
            else
            echo "&nbsp;";
        ?>
        </td>
        <td class="N_title">
        <?
            if($list[$i+6]['id']!="0")
            {
                $chs6 = $list[$i+6]['tag']=='1'?'checked':'';
                echo $list[$i+6]['workday']." <input type='checkbox' onclick='chk(this);'  name=works[] value='".$list[$i+6]['id']."' $chs6>";
            }
            else
            echo "&nbsp;";
        ?>
        </td>
    </tr>
    <? }?>
  </table>
  <?php }else echo "未生成数据";?>
 </div>
<script>
    function chk(val)
    {
       var tags = "";
       var id = val.value;
       if(val.checked)
           tags = "1";
       else
           tags = "0";
       $.ajax({
	   type: "POST",
	   url: "updatetag.php",
	   data: "id="+id+"&tag="+tags,
           async: false,
	   success: function(msg){
                if(msg=='yes')
                {
                    alert("更新成功");
                }
                else
                {
                    alert("更新失败,请重试");
                }
	   }
	});
    }
</script>