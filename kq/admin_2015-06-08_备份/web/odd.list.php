<?php
$admin = new admin();
$sear = $admin->getInfo($_SESSION['ADMIN_ID'],'','pass');
?>
<?php
$from = $_REQUEST['fromTime'];
$to = $_REQUEST['toTime'];
$card_id = $_REQUEST['card_id'];

$record = new record();
$p=$_REQUEST['p'];
if(!$p)$p='1';
if($sear['seartag']!='1')
$record->setWhere("card_id='".$sear['card_id']."'");
elseif($card_id)
{
    $record->setWhere("card_id='".$card_id."'");
}
if(!$from)
{
    $from = date('Y-m')."-01";
}
if(!$to)
{
    $to = date('Y-m-d');
}

$record->setWhere("recorddate>='$from' and recorddate<='$to' and card_id!='4632924'"); //迎宾卡:4632924
$record->pageReNum = "100000";
$list = $record->getList();
$ary = array();
foreach($list as $val)
{
    if(date('N',strtotime($val['recorddate']." 00:00:00"))<6)
    {
       $time1 = strtotime($val['recorddate']." 09:00:00");
       $time2 = strtotime($val['recorddate']." 12:00:00");
       $time3 = strtotime($val['recorddate']." 13:30:00");
       $time4 = strtotime($val['recorddate']." 18:30:00");
       
       $val['addtime'] = preg_replace("/\[\d{2}:\d{2}:\d{2}\s\[进门2\]\]/",'',$val['addtime']);//去除去门2的数据
       preg_match_all("/(\d{2}:\d{2}:\d{2})/",$val['addtime'],$out);

       foreach($out[0] as $v)
       {
           if(strtotime($val['recorddate'].$v)>$time1&&strtotime($val['recorddate'].$v)<$time2 || strtotime($val['recorddate'].$v)>$time3&&strtotime($val['recorddate'].$v)<$time4)
           {
               $ary[] = $val['card_id']."#".$val['recorddate']." ".$v;
           }
       }
    }
}

$pn = '15';
$pageCtrl=getPageInfoHTMLForRecord($ary,$p,'',$pn);
?>
<div class="search">
  打卡异常查询
<form action="odd.php" method="get">
	  	  时间:<input name="fromTime" type="text" size="10" id="date_s" value="<?=$from?>" readonly>
		  到:<input name="toTime" id="date_e" type="text" size="10" value="<?=$to?>" readonly>
            <? if($sear['seartag']=='1'){?>
                  姓名:<select name="card_id">
                    <option value="">全部</option>
                <?php
                    $sql = "select card_id,real_name from _sys_admin";
                    $res = $webdb->getList($sql);
                    foreach($res as $val){
                ?>
                    <option value="<?=$val['card_id']?>" <?php echo $val['card_id']==$_GET['card_id']?'selected':'' ?>><?=$val['real_name']?></option>
                    <?}?>
                      </select>
            <?}?>
		  <input name="type" type="hidden" value="<?=$_GET['type']?>" size="20">
		  <input name="do" type="hidden" value="list" size="20">
		  <input name="cn" type="hidden" value="<?=$className?>" size="20">
		  <input name="sear" type="hidden" value="sear"/>
		  <input type="submit" value="搜索" class="sub2">
</form>
</div>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
        <th scope="col" class="T_title">编号</th>
        <th scope="col" class="T_title">姓名</th>
      <th scope="col" class="T_title">打卡时间</th>

    </tr>
    <?
    //计算第一条
    $first = $pn*($p-1);
    $f =0;
      for($i=0;$i<$pn;$i++){
      $f=$first+$i;
          if($f<=count($ary)-1)
          {
            $vs = $ary[$f];
            $vv = split('#',$vs);
    ?>

    <tr class="Ls2">
        <td class="N_title"><?=($f+1)?></td>
        <td class="N_title">
        <?
            $ad = new admin();
            $ad->setWhere("card_id='".$vv[0]."'");
            $name = $ad->getArray('pass');
            echo $name[0]['real_name'];
        ?>
        </td>
        <td class="N_title"><?=$vv[1]?></td>

    </tr>
    <?
          }
    }
    ?>
  </table>
<div class="news-viewpage"><?=$pageCtrl?></div>