<?
//  select i.id ,i.name, sum(e.emoney) num,i.emoney  from u_emoney1 e left join u_itemtype i on i.id = e.itemType where e.type=10 and  i.emoney !=0  group by i.id
//  select i.id ,i.name, sum(e.emoney) num ,i.emoney from u_emoney1 e left join u_itemtype i on i.id = e.itemType where  i.emoney !=0  and  i.id in(select itemtype_id  from u_vipgoods )  group by i.id
//
//include("inc/CheckUser.php");
include("inc/config.php");
//ini_set("display_errors",false);
include("inc/function.php");

//$db_host="192.168.1.141";   //
//$db_user="root";
//$db_pass="u591,889";
//$db_pass="1";
//$db_database="cpa-1";

$db=mysql_connect($db_host,$db_user,$db_pass) or die("数据库连接失败，请检查！");//打开MySQL服务器连接
mysql_select_db($db_database,$db) or die("数据库出错！");//链接数据库

//mysql_query("set names gbk");
date_default_timezone_set('Asia/Shanghai');



//echo date('Y/m/d H:i:s',910192223);
//function rectStat($statName,$labelAry,$dataAry)
//{
//    $idx = 0;
//    $lenAry = array();
//    $sum = array_sum($dataAry);
//
//    $strHTML  = "<table width='".(($direct=="H") ? "800" : "98%")."' border='0' cellspacing='1' cellpadding='1' bgcolor='#CCCCCC' align='center'>\n<tr><td bgcolor='#FFFFFF'>\n";
//    $strHTML .= "<table width='100%' border='0' cellspacing='2' cellpadding='2'>\n";
//
//        $strHTML .= "<tr><td colspan='2' align='center'><b>".$statName."</b></td></tr>\n";
//
//         while (list ($key, $val) = each ($dataAry))
//         {
//            if($val!='0')
//            $strHTML .= "<tr><td width='45%' align='right'>".$labelAry[$key]."</td><td width='55%'><img src='Images/h_line2.jpg' border=0 height='7' width='".(($val/$sum)*400)."'>&nbsp;".$dataAry[$key]."</td></tr>\n";
//            else
//            $strHTML .= "<tr><td width='45%' align='right'>".$labelAry[$key]."</td><td width='55%'>&nbsp;".$dataAry[$key]."</td></tr>\n";
//            $idx++;
//            //echo $idx;
//         }
//
//
//
//	$strHTML .= "<tr><td width='16%' align='right'>总计:</td><td width='84%'>".$sum."</td></tr>\n";
//    $strHTML .= "</table>\n";
//    $strHTML .= "</td></tr></table>\n";
//
//     return $strHTML;
//}

function rectStat2($arr){
    echo  "<table width='"."800" ."' border='0' cellspacing='1' cellpadding='1' bgcolor='#CCCCCC' align='center'>\n<tr><td bgcolor='#FFFFFF'>\n";
    echo  "<table width='100%' border='0' cellspacing='2' cellpadding='2'>\n";

    foreach($arr as $key=>$value){
         $sum = $sum +$value['t_emoney'];
     }

     foreach($arr as $key=>$value){
          echo "<tr><td width='45%' align='right'>".$value['id'].'&nbsp;&nbsp;'.$value['name']."</td><td width='55%'><img src='Images/h_line2.jpg' border=0 height='7' width='".($value['t_emoney']/$sum*400)."'>&nbsp;".$value['num'].'&nbsp;&nbsp;'.$value['emoney'].'&nbsp;&nbsp;'.$value['t_emoney']."</td></tr>\n";
     }

    echo "<tr><td width='16%' align='right'>总计:</td><td width='84%'>".$sum."</td></tr>\n";
    echo "</table>\n";
    echo "</td></tr></table>\n";
}

?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
<script language="javascript" src="JS/ActionFrom.js"></script>

</head>
<body class="main">
<?
if($_REQUEST["fid"])
{
//	$name=CheckStr($_REQUEST["name"]);
	$fid=CheckStr($_REQUEST["fid"]);
	$StarTime=($_REQUEST["StarTime"]);
	$EndsTime=CheckStr($_REQUEST["EndsTime"]);
	
	$st = strtotime($StarTime);
	$et = strtotime($EndsTime);
	
	$st = strtotime($StarTime."000000");
	$et = strtotime($EndsTime."235959");
	
	$re_st = date('ymdHi',$st);
	$re_et = date('ymdHi',$et);
	
	
	if ($StarTime != "" ){
		$sql=$sql." And e.time_stamp > '$re_st'";
	   //	$PURL=$PURL."StarTime=$StarTime&EndsTime=$EndsTime&";
	}
    if($EndsTime != ""){
        $sql=$sql."  And e.time_stamp < '$re_et' ";
    }

    $sql1 = ' select i.id ,i.name, sum(e.number) t_emoney,i.emoney  from u_emoney'.$fid.' e left join u_itemtype i on i.id = e.itemType where e.type=10 and  i.emoney !=0 '.$sql.' group by i.id  ';
    $result1=mysql_query($sql1);
	while($rs1 = mysql_fetch_array($result1))
	{
        $result[$rs1['id']]['name'] = $rs1['name'];
        $result[$rs1['id']]['id'] = $rs1['id'];
        $result[$rs1['id']]['t_emoney'] = $rs1['t_emoney'];
        $result[$rs1['id']]['emoney'] = intval($rs1['emoney']);
        $result[$rs1['id']]['num'] = $rs1['t_emoney']/$rs1['emoney'];
	}

    $sql2 = ' select itemtype_id  from u_vipgoods ';
    $result2=mysql_query($sql2);
    while($rs2 = mysql_fetch_array($result2))
	{
         $strall .= $rs2['itemtype_id'].",";
	}
        $strall = substr($strall,0,-1);
   
    $sql3 = ' select i.id ,i.name, sum(e.number) t_emoney ,i.emoney from u_emoney'.$fid.' e left join u_itemtype i on i.id = e.itemType where  i.emoney !=0  and  i.id in('.$strall.')   '.$sql.'  group by i.id ';

    $result3=mysql_query($sql3);
    while($rs3 = mysql_fetch_array($result3))
	{
        $result[$rs3['id']]['name'] = $rs3['name'];
        $result[$rs3['id']]['id'] = $rs3['id'];
        $result[$rs3['id']]['t_emoney'] = $rs3['t_emoney'];//这个num指的是  该交易消费的仙晶
        $result[$rs3['id']]['emoney'] = intval($rs3['emoney']);
        $result[$rs3['id']]['num'] = $rs3['t_emoney']/$rs3['emoney'];
	}


////	if ($name != ""){
////		$sql=$sql." And name Like '%$name%' ";
////		$PURL=$PURL."name=$name&";
////	}
//
//	$SqlStr="select A.*,B.id as bid,B.name as na,sum(A.number) as num,B.price as pri,B.type as t from u_vipgoods B Left Join u_emoney$fid A On A.itemType=B.id Where 1=1".$sql." and A.type='10' and B.name != '' group by bid Order By B.id Desc";
//	$result=mysql_query($SqlStr);
//	//echo $SqlStr;
//	$labelAry = array();
//	$dataAry = array();
//	$strall = "";
//	while($rs = mysql_fetch_array($result))
//	{
//		$labelAry[$rs['bid']] = $rs['bid']." ".$rs['t']." ".$rs['na']." ".($rs['num']/$rs['pri'])." ".$rs['pri'];
//		//$dataAry[$rs['bid']] = $rs['num']*$rs['pri'];
//		$dataAry[$rs['bid']] = $rs['num'];
//		$strall .= $rs['bid'].",";
//	}
//        //echo count($dataAry);
//        //echo count($labelAry);
//        //$sqlall = "select DISTINCT itemType from u_emoney$fid where type='10'";
//        //$resall = mysql_query($sqlall);
//        //echo mysql_num_rows($resall);
//
//        //while($rsall = mysql_fetch_array($resall))
//        //{
//         //   $strall .= $rsall['itemType'].",";
//        //}
//        $strall = substr($strall,0,-1);
//        //echo $strall;
//	$sql_null = "select id,name,type from u_vipgoods where id not in ($strall)";
//        $resnull = mysql_query($sql_null);
//        $strnull = "";
//
//        while($rsnull = mysql_fetch_array($resnull))
//        {
//            $labelAry[$rsnull['id']] = $rsnull['id']." ".$rsnull['type']." ".$rsnull['name']." 0";
//		//$dataAry[$rs['bid']] = $rs['num']*$rs['pri'];
//            $dataAry[$rsnull['id']] = '0';
//        }
}
//echo count($labelAry);
//print_r($labelAry);exit;

$statName = "商品销售统计图(单位:仙晶)";
//$labelAry = array("中国","美国","日本","韩国","印度","法国","英国","朝鲜","加拿大","瑞典","澳大利亚","南非","捷克","沙特","俄罗斯");
//$dataAry = array(13321,7432,123,425,577,5321,6432,123,5256,577,321,32,123,556,1577);

?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr> 
      <th height="22" colspan="2" align="center">管理</th>
    </tr>
    <!--
    <tr> 
      <td width="15%" align="right" class="forumRow">商品名称：</td>
      <td width="85%" class="forumRow"><input name="name" type="text" value="<?=$name?>"> 
      </td>
    </tr>
    -->
    <tr> 
      <td align="right" class="forumRow">服务器：</td>
      <td width="85%" class="forumRow">
	  	 <select name="fid">
		 	<option value="1" <?php if($fid==1)echo "selected"?>>1区-天地问情</option>  
		 	<option value="2" <?php if($fid==2)echo "selected"?>>2区-凤凰涅?</option>  
		 	<option value="7" <?php if($fid==7)echo "selected"?>>7区-傲世无双</option>
		 	<option value="8" <?php if($fid==8)echo "selected"?>>8区-逐鹿苍穹</option>
		 	<option value="10" <?php if($fid==10)echo "selected"?>>10区</option>
                        <option value="11" <?php if($fid==11)echo "selected"?>>11区</option>
                        <option value="12" <?php if($fid==12)echo "selected"?>>12区</option>
                        <option value="13" <?php if($fid==13)echo "selected"?>>13区</option>
                        <option value="14" <?php if($fid==14)echo "selected"?>>14区</option>
                        <option value="15" <?php if($fid==15)echo "selected"?>>15区</option>
		 </select>
		   
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">时间：</td>
      <td class="forumRow"><input name="StarTime" type="text" size="12" value="<?=$StarTime?>" onClick="javascript:toggleDatePicker(this)">
        ～ 
        <input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>" onClick="javascript:toggleDatePicker(this)"> 
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 "></td>
    </tr>
  </form>
</table>

<?php
	if($_REQUEST['fid']){
         rectStat2($result);
    }
	
?>
</body>
</html>