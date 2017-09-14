<?php
//设置北京时间
date_default_timezone_set('Asia/Shanghai');

//充值渠道
$CPList=array(
"48"=>'lenovo充值',
"9"=>'手工充值',
"22"=>'pp助手充值',
"12"=>'91充值',
"21"=>'当乐充值',
"15"=>'uc充值',
"16"=>'5gwan充值',
"34"=>'华为充值',
"43"=>'360充值',
"25"=>'小米充值',
"27"=>'机锋网充值',
"28"=>'安智充值',
"35"=>'oppo充值',
"24"=>'移动MM充值',
"61"=>'17173充值',
"62"=>'37wan充值',
"63"=>'8849充值',
"64"=>'pptv充值',
"33"=>'点金充值',
"65"=>'万普充值',
"66"=>'禅游充值',
"67"=>'百度充值',
"50"=>'有信充值',
"30"=>'应用汇充值',
"68"=>'itools充值',
//"69"=>'cooguo充值',
"70"=>'vivo充值',
"71"=>'3g门户充值',
"72"=>'博雅科诺充值',
"73"=>'悠悠村充值',
"74"=>'酷动充值',
 "49"=>'丫丫玩充值',
 "75"=>'乐非凡充值',
"76"=>'37wan_ios充值',
"77"=>'搜狗充值',
 "78"=>'迅雷充值',
 "79"=>'快玩充值',
  "80"=>'快用充值',
  "81"=>'新丫丫玩充值',
  "82"=>'云游充值',
   "19"=>'apple充值',
    "83"=>'apple充值-加强版',
    "84"=>'木蚂蚁充值',
     "85"=>'阿波罗充值',
     "86"=>'浩动ios充值',
    "87"=>'浩动android充值',
    //"88"=>'jodo充值',
    "89"=>'有信2充值',
    "90"=>'手盟充值',
     "46"=>'pps充值',
    "91"=>'爱思充值',
    "92"=>'游龙充值',
    "93"=>'起点充值',
    "94"=>'蜗牛充值',
    "95"=>'人人充值',
    "96"=>'平安充值',
    "97"=>'XY 苹果助手',
    "98"=>'海马',
    "99"=>'冒泡',
    "100"=>'wo商城',
     "101"=>'绿岸',
    "102"=>'momo充值',
    "103"=>'豌豆荚充值',
    "104"=>'YY充值',
     "105"=>'海马安卓充值',
     "106"=>'金立充值',
    "107"=>'游酷充值',
    "108"=>'同步充值',
    "109"=>'柴米充值',
    "110"=>'37wan_ios_2',
    "111"=>'baidu_91充值',
    '112'=>'靠谱充值',
);
//充值方式
$pCodeList=array(
"YD"=>"神州行",
"YDQG"=>"神州行全国卡",
"YDZJ"=>"神州行浙江卡",
"YDJS"=>"神州行江苏卡",
"LT"=>"联通",
"DX"=>"电信",
"bank"=>"网银",
"wapbank"=>"wap网银",
"JUN"=>"骏网",
"YDMW"=>"移动梦网",
"SGCZ"=>'手工充值',
"yuanbao"=>'元宝转仙晶'
);
//当乐支持的神州行全国卡金额种类
$dl_szx_qg = array('10','20','30','50','100');
//当乐189hi支持的神州行全国卡金额种类
$dl189hi_szx_qg = array('30','50','100','300','500');
//神州行转换
function szxToPay($PayMoney){
	switch($PayMoney)
	{
	case "1"	: $str="101";	break;
	case "2"	: $str="102";	break;
	case "10"	: $str="201";	break;
	case "20"	: $str="202";	break;
	case "30"	: $str="203";	break;
	case "50"	: $str="205";	break;
	case "100"	: $str="210";	break;
	case "200"	: $str="220";	break;
	case "300"	: $str="230";	break;
	case "500"	: $str="250";	break;
    case "1000"	: $str="2100";	break;
	default		: $str="0";	break;//未定义
	}
	return $str;
}
//银行卡转换
function bankToPay($PayMoney){
	switch($PayMoney)
	{
	case "1"	: $str="101";	break;
	case "10"	: $str="301";	break;
	case "20"	: $str="302";	break;
	case "30"	: $str="303";	break;
	case "50"	: $str="305";	break;
	case "100"	: $str="310";	break;
	case "200"	: $str="320";	break;
	case "300"	: $str="330";	break;
	default		: $str="0";	break;//未定义
	}
	return $str;
}
//充值分类显示
function PayList($str){
	switch($str)
	{
	case "101"	: $str="短信1元";	break;
	case "102"	: $str="短信2元";	break;
	case "201"	: $str="神州10元";	break;
	case "202"	: $str="神州20元";	break;
	case "203"	: $str="神州30元";	break;
	case "205"	: $str="神州50元";	break;
	case "210"	: $str="神州100元";	break;
	case "220"	: $str="神州200元";	break;
	case "230"	: $str="神州300元";	break;
	case "250"	: $str="神州500元";	break;
    case "2100"	: $str="神州1000元";	break;
	case "301"	: $str="网银10元";	break;
	case "302"	: $str="网银20元";	break;
	case "303"	: $str="网银30元";	break;
	case "305"	: $str="网银50元";	break;
	case "310"	: $str="网银100元";	break;
	case "320"	: $str="网银200元";	break;
	case "330"	: $str="网银300元";	break;
	default		: $str="";	break;//未定义
	}
	return $str;
}

//判断是否当乐推广商玩家
//$fenid玩家推广商id
//$cardNO充值卡卡号，通过卡长度判断是地方卡还是全国卡  长度10位是浙江卡  长度17位为全国卡或广东地方卡（卡号第6,7为19表示广东地方卡）
//$isClient  判断是否客户端调用
function checkDangLe($fenId='',$cardNO='',$isClient=false)
{
    global $tourl;
    global $PayCode;
    global $dl_fenbaoid;
    global $dwFenBaoID;
    global $PayMoney;
    global $PayID;
    global $dl_szx_qg;

    if(in_array($fenId,$dl_fenbaoid))
    {
        if(in_array($PayCode,array('YD','LT','DX')))
        {
            if(in_array($PayCode,array('LT','DX')))
            {
                if($PayCode == 'DX' && substr($cardNO,3,1)!='1')
                {
                    $dwFenBaoID = "16";
                }
                else
                {
                    //当乐只支持电信全国卡50/100
                    if($PayMoney == '50' || $PayMoney=='100')
                    {
                        if(!$isClient)
                        {
                            $tourl = "../dangle/go.php";
                        }
                        else
                        {
                            include("../dangle/client.php");
                        }
                    }
                    else
                    {
                        $dwFenBaoID = "16";
                    }
                }
                if($dwFenBaoID=='16')
                {
                    $strdl = "PayID=$PayID,cardNO=$cardNO,PayCode=$PayCode,PayMoney=$PayMoney,".date("H:i:s");
                    write_log('../log','dangle_others',$strdl."\r\n");
                }
            }
            else
            {
                //浙江卡和全国卡使用当乐充值渠道
                if(in_array(strlen($cardNO),array('17')) && substr($cardNO,5,2)!='19' && in_array($PayMoney,$dl_szx_qg))
                {
                    if(!$isClient)
                    {
                        //wap官网调用
                        if(strlen($cardNO)=='17')
                        {
                            $PayCode = 'YDQG';
                        }
                        else
                        {
                            $PayCode = 'YDZJ';
                        }
                        $tourl = "../dangle/go.php";
                    }
                    else
                    {
                        //游戏客户端调用
                        if(strlen($cardNO)=='17')
                        {
                            $PayCode = 'YDQG';
                        }
                        else
                        {
                            $PayCode = 'YDZJ';
                        }
                        include("../dangle/client.php");
                    }
                }
                else
                {
                    $dwFenBaoID = "16";
                    $strdl = "PayID=$PayID,cardNO=$cardNO,PayCode=$PayCode,PayMoney=$PayMoney,".date("H:i:s");
                    write_log('../log','dangle_others',$strdl."\r\n");
                }
            }
        }
        else
        {
            $dwFenBaoID = "16";
            $strdl = "PayID=$PayID,cardNO=$cardNO,PayCode=$PayCode,PayMoney=$PayMoney,".date("H:i:s");
            write_log('../log','dangle_others',$strdl."\r\n");
        }
    }
}
//判断是否当乐推广商玩家
//$fenid玩家推广商id
//$isClient  判断是否客户端调用
function checkDangLe_189hi($fenId='',$cardNO='',$isClient=false)
{
    global $tourl;
    global $PayCode;
    global $dl_fenbaoid;
    global $dwFenBaoID;
    global $PayMoney;
    global $PayID;
    global $dl189hi_szx_qg;

    if(in_array($fenId,$dl_fenbaoid))
    {
        if(in_array($PayCode,array('YD')))
        {
            //神州行卡充值
            if(in_array($PayMoney,$dl189hi_szx_qg))
            {
                if(!$isClient)
                {
                    $tourl = "../189hi/go.php";
                }
                else
                {
                    //游戏客户端调用
                    include("../189hi/client.php");
                }
            }
            else
            {
                $dwFenBaoID = "16";
                $strdl = "PayID=$PayID,cardNO=$cardNO,PayCode=$PayCode,PayMoney=$PayMoney,".date("H:i:s");
                write_log('../log','dangle_189hi_others',$strdl."\r\n");
            }
        }
        else
        {
            $dwFenBaoID = "16";
            $strdl = "PayID=$PayID,cardNO=$cardNO,PayCode=$PayCode,PayMoney=$PayMoney,".date("H:i:s");
            write_log('../log','dangle_189hi_others',$strdl."\r\n");
        }
    }
}

//信息列表
function NewsList($TypeID,$limit='5',$tag=''){
	 $sql="select ID,NewsTitle from news_data where NewsType=$TypeID and IsHide=0 order by id desc limit $limit";
	$conn=mysql_query($sql);
        $str = '';
	while($rs=mysql_fetch_array($conn)){
            $title = addslashes($rs['NewsTitle']);
            if($tag=='check')
            {
                if(mb_strlen($title,'gb2312')>12)
                $title = mb_substr($title,0,11,'gb2312')."..";
                //else
                //$title = mb_substr($title,0,12,'gb2312');
            }
            $str=$str."<a href=\"news_show.php?ID=".$rs['ID']."\">".$title."</a><br/>\n";
	}
	return $str;
}

function NewsList2($TypeID,$limit='5',$tag=''){
	 $sql="select ID,NewsTitle from news_data where NewsType=$TypeID and IsHide=0 order by id desc limit $limit";
	$conn=mysql_query($sql);
        $str = '';
	while($rs=mysql_fetch_array($conn)){
            $title = addslashes($rs['NewsTitle']);
            if($tag=='check')
            {
                if(mb_strlen($title,'gb2312')>12)
                $title = mb_substr($title,0,11,'gb2312')."..";
                //else
                //$title = mb_substr($title,0,12,'gb2312');
            }
            $str=$str."<a href=\"/news_show.php?ID=".$rs['ID']."\">".$title."</a><br/>\n";
	}
	return $str;
}

//获取ip
function getIP_front(){
	$ip=getenv('REMOTE_ADDR');
	$ip_ = getenv('HTTP_X_FORWARDED_FOR');
	if (($ip_ != "") && ($ip_ != "unknown"))
	{
	$ip=$ip_;
	}
	return $ip;
}

//下拉框跳转
function SeleList($TypeID,$SeleName){
	$sql="select ID,NewsTitle from news_data where NewsType=$TypeID order by Add_Time desc";
	$conn=mysql_query($sql);
	$str="<select name=\"lw\">\n";
	$str=$str."<option selected=\"selected\">".$SeleName."</option>\n";
		while($rs=mysql_fetch_array($conn)){
		//$str=$str."<option onpick=\"news_show.php?ID=".$rs[ID]." ".SeleObject($TypeID,$rs[ID])."\">".$rs[NewsTitle]."</option>\n";
		$str=$str."<option onpick=\"news_show.php?ID=".$rs['ID']."\">".$rs['NewsTitle']."</option>\n";
		}
	$str=$str."</select>";
	return $str;
}
//WAP页面提示
function PayMsg(){
	echo "<br/><br/><a href=\"index.php\">返回上级</a><br/>";
	echo "<a href=\"/../../index.php\">返回首页</a>";
	echo "</p></card></wml>";
	exit;
}
function newPayMsg($PayCode,$game_id=1){
    echo "<br/><br/><a href=\"index.php?PayCode=$PayCode&amp;game_id=$game_id\">返回上级</a><br/>";
	echo "<a href=\"/../../index.php\">返回首页</a>";
	echo "</p></card></wml>";
	exit;
}
//写日志:路径,文件名,内容
function write_log($dirName,$logName,$str){
	$path_name=$dirName."/".date("ym");//年月
	if( !is_dir($path_name)==0 );{
	@mkdir($path_name);
	}
	$fs=fopen($path_name."/".$logName.date("ymd").".txt","a");//年月日
	fwrite($fs,$str);
	fclose($fs);
}
//写日志:路径,文件名,内容  按月为单位
function write_log_pay_admin($dirName,$logName,$str){
	$path_name=$dirName."/".date("y");//年月
	if( !is_dir($path_name)==0 );{
	@mkdir($path_name);
	}
	$fs=fopen($path_name."/".$logName.date("ym").".txt","a");//年月日
	fwrite($fs,$str);
	fclose($fs);
}
/**
 * 元宝充值写入账号库
 * @param <type> $PayMoney
 * @param <type> $PayID
 * @param <type> $OrderID 
 */
function write_yuanbao($PayMoney,$PayID,$OrderID){
    SetConn(88);
    $sql_log="select tag from trade_pay_log where orderID='$OrderID' and payID ='$PayID'";
	$query_log=mysql_query($sql_log);
    $result_log = mysql_fetch_array($query_log);
    $result_log['tag'];
    if($result_log['tag']==1){
        return;
    }
    $sql_tag = " update trade_pay_log set tag=1 where orderID='$OrderID' and payID ='$PayID'";
    if (mysql_query($sql_tag) == False){
        			//写入失败日志
		$str=$sql_tag.mysql_error()." ".date("Y-m-d H:i:s")."\r\n";
		write_log("../log","trade_log_sql_err_",$str);
        return;
    }


    SetConn(81);//链接账号库
    $time = time();
    $PayMoney = intval($PayMoney);
    $sql="select * from account_money where account_id='$PayID'";
	$query=mysql_query($sql);
    $result = mysql_fetch_array($query);
    if($result){
        $sql = " update account_money set money=money+$PayMoney where  account_id='$PayID'";
    }else{
        $sql = " insert into account_money(account_id,money) values($PayID,$PayMoney)";
    }
    if (mysql_query($sql) == False){
        			//写入失败日志
		$str="PayMoney=".$PayMoney."  PayID=".$PayID."  OrderID=".$OrderID."  ".date("Y-m-d H:i:s")."\r\n";
		write_log("../log","account_money_err_",$str);
    }
}
//充值成功写入游戏库
//参数说明：充值方式,服务区ID,充值类型,帐号ID,定单号
function WriteCard_money($tabType,$ServerID,$money,$PayID,$OrderID,$type=8){
	SetConn($ServerID);//根据SvrID连接服务器
	//$dwChkSum = rand(100000000,999999999);
	$time_stamp=date('ymdHi');
	//判断定单号是否重复
	$sql="select count(*) from u_card where ref_id='$OrderID'";
	$query=mysql_query($sql);
	$RowCount=mysql_result($query,0);
	if ($RowCount == 0){
		$sql="insert into u_card(data,account_id,ref_id,time_stamp,used,type,server_id)";
		$sql=$sql." values('$money',$PayID,'$OrderID',$time_stamp,0,'$type','$ServerID')";
		//echo $sql;
		//write_log(ROOT_PATH."log","card_err_",$sql);
		if (mysql_query($sql) == False){
			//更新pay_log状态
			SetConn(88);//连接u591数据库
			if ($tabType==1){
				$sql="update pay_log set IsUC=1 where OrderID='$OrderID'";
			}else{
				$sql="update pay_sms set IsUC=1 where LinkID='$OrderID'";
			}
			mysql_query($sql);

			//写入失败日志
			$str="ServerID=".$ServerID."  PayMoney=".$money."  PayID=".$PayID."  OrderID=".$OrderID."  ".date("Y-m-d H:i:s")."\r\n";
			write_log(ROOT_PATH."log","card_err_",$str);
		}else{
            $insert_id = mysql_insert_id();
            write_log(ROOT_PATH."log","card_true_","ServerID=".$ServerID.",insert_id=$insert_id,".$sql."  ".date("Y-m-d H:i:s")."\r\n");
        }
	}else{
        //充值返回本来已经成功的情况，由于入库出错，从新记录一次
         SetConn($ServerID);//根据SvrID连接服务器
	     //$dwChkSum = rand(100000000,999999999);
	      $time_stamp=date('ymdHi');
	     //判断定单号是否重复
	      $sql="select count(*) from u_card where ref_id='$OrderID'";
	      $query=mysql_query($sql);
	      $RowCount=mysql_result($query,0);

        if ($RowCount == 0){
		$sql="insert into u_card(data,account_id,ref_id,time_stamp,used,type)";
		$sql=$sql." values('$money',$PayID,'$OrderID',$time_stamp,0,'$type')";
		//echo $sql;
		if (mysql_query($sql) == False){
			//更新pay_log状态
			SetConn(88);//连接u591数据库
			if ($tabType==1){
				$sql="update pay_log set IsUC=1 where OrderID='$OrderID'";
			}else{
				$sql="update pay_sms set IsUC=1 where LinkID='$OrderID'";
			}
			mysql_query($sql);

			//写入失败日志
			$str="ServerID=".$ServerID."  PayMoney=".$money."  PayID=".$PayID."  OrderID=".$OrderID."  ".date("Y-m-d H:i:s")."\r\n";
			write_log(ROOT_PATH."log","card_err_",$str);
		}else{
            $insert_id = mysql_insert_id();
            write_log(ROOT_PATH."log","card_true_","ServerID=".$ServerID.",insert_id=$insert_id,".$sql."  ".date("Y-m-d H:i:s")."\r\n");
        }
	  }

    }
    @yunying_money($OrderID);
}
function yunying_money($order_id){
    SetConn(88);
    $sql = " select * from pay_log where OrderID ='$order_id' limit 1 ";
	$query=mysql_query($sql);
    $result = mysql_fetch_array($query);

    $id = $result['id'];
    $PayID = $result['PayID'];
    $PayName = $result['PayName'];
    $ServerID = $result['ServerID'];
    $PayMoney = $result['PayMoney'];
    $OrderID = $result['OrderID'];
    $CardNO = $result['CardNO'];
    $CardPwd = $result['CardPwd'];
    $BankID = $result['BankID'];
    $BankOrderID = $result['BankOrderID'];
    $rpCode = $result['rpCode'];
    $rpTime = $result['rpTime'];
    $PayType = $result['PayType'];
    $dwFenBaoID = $result['dwFenBaoID'];
    $Add_Time = $result['Add_Time'];
    $PayCode = $result['PayCode'];
    $SubStat = $result['SubStat'];
    $IsUC = $result['IsUC'];
    $CPID = $result['CPID'];
    $tag = $result['tag'];
    $game_id = $result['game_id'];
    $clienttype = $result['clienttype'];
    if(!$id){
        write_log(ROOT_PATH."log","sql_test_",$sql);
        return ;
    }

    SetConn(212);
    $PayType = intval($PayType);
    $IsUC = intval($IsUC);
    $rpTime = $rpTime?$rpTime:date('Y-m-d H:i:s');
    $sql = " insert into pay_log(PayID,PayName,ServerID,PayMoney,OrderID,CardNO,CardPwd,BankID,BankOrderID,rpCode,rpTime,PayType,dwFenBaoID,Add_Time,PayCode,SubStat,IsUC,CPID,tag,game_id,clienttype)
             values('$PayID','$PayName','$ServerID','$PayMoney','$OrderID','$CardNO','$CardPwd','$BankID','$BankOrderID','$rpCode','$rpTime','$PayType','$dwFenBaoID','$Add_Time','$PayCode','$SubStat','$IsUC','$CPID','$tag','$game_id','$clienttype')  ";
      
    if(!mysql_query($sql)){
      write_log(ROOT_PATH."log","sql_test_",$sql."  ".mysql_error()."\r\n");
    }
   
}

//充值成功写入游戏库
//参数说明：充值方式,服务区ID,充值类型,帐号ID,定单号
function WriteCard($tabType,$ServerID,$PayType,$PayID,$OrderID){
	SetConn($ServerID);//根据SvrID连接服务器
	//$dwChkSum = rand(100000000,999999999);
	$time_stamp=date('ymdHi');
	//判断定单号是否重复
	$sql="select count(0) from u_card where ref_id='$OrderID'";
	$query=mysql_query($sql);
	$RowCount=mysql_result($query,0);
	if ($RowCount == 0){
		$sql="insert into u_card(type,account_id,ref_id,time_stamp,used,server_id)";
		$sql=$sql." values($PayType,$PayID,'$OrderID',$time_stamp,0,'$ServerID')";
		//echo $sql;
		if (mysql_query($sql) == False){
			//更新pay_log状态
			SetConn(88);//连接u591数据库
			if ($tabType==1){
				$sql="update pay_log set IsUC=1 where OrderID='$OrderID'";
			}else{
				$sql="update pay_sms set IsUC=1 where LinkID='$OrderID'";
			}
			mysql_query($sql);
			
			//写入失败日志
			$str="ServerID=".$ServerID."  PayType=".$PayType."  PayID=".$PayID."  OrderID=".$OrderID."  ".date("Y-m-d H:i:s")."\r\n";
			write_log("../log","card_err_",$str);
		}
	}else{
        //充值返回本来已经成功的情况，由于入库出错，从新记录一次
         SetConn($ServerID);//根据SvrID连接服务器
	     //$dwChkSum = rand(100000000,999999999);
	      $time_stamp=date('ymdHi');
	     //判断定单号是否重复
	      $sql="select count(0) from u_card where ref_id='$OrderID'";
	      $query=mysql_query($sql);
	      $RowCount=mysql_result($query,0);

        if ($RowCount == 0){
		$sql="insert into u_card(type,account_id,ref_id,time_stamp,used)";
		$sql=$sql." values($PayType,$PayID,'$OrderID',$time_stamp,0)";
		//echo $sql;
		if (mysql_query($sql) == False){
			//更新pay_log状态
			SetConn(88);//连接u591数据库
			if ($tabType==1){
				$sql="update pay_log set IsUC=1 where OrderID='$OrderID'";
			}else{
				$sql="update pay_sms set IsUC=1 where LinkID='$OrderID'";
			}
			mysql_query($sql);

			//写入失败日志
			$str="ServerID=".$ServerID."  PayType=".$PayType."  PayID=".$PayID."  OrderID=".$OrderID."  ".date("Y-m-d H:i:s")."\r\n";
			write_log("../log","card_err_",$str);
		}
	  }

    }
}
//后台手工充值写入游戏库
//参数说明：充值方式,服务区ID,充值类型,帐号ID,定单号,充值金额,操作员
function WriteCardAdmin($tabType,$ServerID,$PayType,$PayID,$OrderID,$data,$oprea){
	SetConn($ServerID);//根据SvrID连接服务器
	//$dwChkSum = rand(100000000,999999999);
	$time_stamp=date('ymdHi');
	//判断定单号是否重复
	$sql="select count(0) from u_card where ref_id='$OrderID'";
	$query=mysql_query($sql);
	$RowCount=mysql_result($query,0);
	if ($RowCount == 0){
		$sql="insert into u_card(type,account_id,ref_id,time_stamp,used,data,server_id)";
		$sql=$sql." values($PayType,$PayID,'$OrderID',$time_stamp,0,'$data','$ServerID')";
		if (mysql_query($sql) == False){
			//更新pay_log状态
			$str="error:".$sql.mysql_error()." opera:$oprea".date("Y-m-d H:i:s")."\r\n";
			write_log_pay_admin(ROOT_PATH."log","pay_admin_log",$str);
                        echo"<script>alert('fail');history.go(-1);</script>";
                        exit;
		}
                else
                {
                        SetConn(88);//连接u591数据库
			if ($tabType==1){
                            $sqls="update pay_log set rpCode='1' where OrderID='$OrderID'";
			}
                        //更改日志状态失败
			if (mysql_query($sqls) == False)
                        {
                            $strs = "error:充值成功，修改状态失败 ".$sqls.mysql_error()." opera:$oprea".date("Y-m-d H:i:s")."\r\n";
                            write_log_pay_admin(ROOT_PATH."log","pay_admin_log",$strs);
                        }
                    write_log_pay_admin(ROOT_PATH."log","pay_admin_log","s ".$sql." opera:$oprea".date("Y-m-d H:i:s")."\r\n");
                    echo"<script>alert('success');location.href='payByAdmin.php';</script>";
                }
	}
}
//通知客户端消息(充值卡)
//PayStat：0=成功消息,1=失败消息
function WritePayMsg($PayStat,$ServerID,$PayID,$OrderID,$PayMoney,$game_id=1){
        if($game_id==5){
        	SetConn($ServerID);//根据SvrID连接服务器
	        if ($PayStat==0){
	        $msg='您于'.date('Y-m-d H:i').'成功充值'.$PayMoney.'元，祝您游戏愉快！';
	        }else{
	        $msg='您于'.date('Y-m-d H:i').'充值失败，请您核对充值卡号和密码，并确认所选面额与充值卡面额是否一致！';
	        }
	        $time_stamp=date('ymdHi');
	        mysql_query("set names latin1");
	        $sql="insert into u_leavewordrmb(accountid,time,words)";
	        $sql=$sql." values($PayID,$time_stamp,'$msg')";
	        mysql_query($sql);
    }else if($game_id==1){
        	SetConn($ServerID);//根据SvrID连接服务器
	        if ($PayStat==0){
	        $msg='您于'.date('Y-m-d H:i').'成功充值'.$PayMoney.'元，请到长安主城充值兑换员NPC处领取仙晶。';
	        }else{
	        $msg='您于'.date('Y-m-d H:i').'充值失败，请您核对充值卡号和密码，并确认所选面额与充值卡面额是否一致！';
	        }
	        $time_stamp=date('ymdHi');
	        mysql_query("set names latin1");
	        $sql="insert into u_leavewordrmb(accountid,time,words)";
	        $sql=$sql." values($PayID,$time_stamp,'$msg')";
	        mysql_query($sql);
    }else if($game_id==3){
         	SetConn($ServerID);//根据SvrID连接服务器
	        if ($PayStat==0){
	        $msg='您于'.date('Y-m-d H:i').'成功充值'.$PayMoney.'元，主界面打开VIP界面点领取点卷。';
	        }else{
	        $msg='您于'.date('Y-m-d H:i').'充值失败，请您核对充值卡号和密码，并确认所选面额与充值卡面额是否一致！';
	        }
	        $time_stamp=date('ymdHi');
	        mysql_query("set names latin1");
	        $sql="insert into u_leavewordrmb(accountid,time,words)";
	        $sql=$sql." values($PayID,$time_stamp,'$msg')";
	        mysql_query($sql);
    }else{
         	SetConn($ServerID);//根据SvrID连接服务器
	        if ($PayStat==0){
	        $msg='您于'.date('Y-m-d H:i').'成功充值'.$PayMoney.'元，请打开主菜单->仙晶商店->领取仙晶。';
	        }else{
	        $msg='您于'.date('Y-m-d H:i').'充值失败，请您核对充值卡号和密码，并确认所选面额与充值卡面额是否一致！';
	        }
	        $time_stamp=date('ymdHi');
	        mysql_query("set names latin1");
	        $sql="insert into u_leavewordrmb(accountid,time,words)";
	        $sql=$sql." values($PayID,$time_stamp,'$msg')";
	        mysql_query($sql);
    }

}
//通知客户端消息(银行卡卡)
//PayStat：0=成功消息,1=失败消息
function WritePayBankMsg($PayStat,$ServerID,$PayID,$OrderID,$PayMoney,$game_id=1){
    if($game_id==1){
	SetConn($ServerID);//根据SvrID连接服务器
	if ($PayStat==0){
	$msg='您于'.date('Y-m-d H:i').'成功充值'.$PayMoney.'元，请到长安主城充值兑换员NPC处领取仙晶。';
	}else{
	$msg='您于'.date('Y-m-d H:i').'充值失败，请核对您的银行卡是否有足够的余额！';
	}
	$time_stamp=date('ymdHi');
	mysql_query("set names latin1");
	$sql="insert into u_leavewordrmb(accountid,time,words)";
	$sql=$sql." values($PayID,$time_stamp,'$msg')";
	mysql_query($sql);
    }

}
//通知客户端消息(短信充值)
//PayStat：0=成功消息,1=失败消息
function smsPayMsg($PayStat,$ServerID,$PayID,$OrderID,$PayMoney){
	SetConn($ServerID);//根据SvrID连接服务器
	if ($PayStat==0){
	$msg='您于'.date('Y-m-d H:i').'短信成功充值'.$PayMoney.'元，请到长安主城充值兑换员NPC处领取仙晶。';
	}else{
	$msg='您于'.date('Y-m-d H:i').'短信充值失败，请重新尝试!';
	}
	$time_stamp=date('ymdHi');
	mysql_query("set names latin1");
	$sql="insert into u_leavewordrmb(accountid,time,words)";
	$sql=$sql." values($PayID,$time_stamp,'$msg')";
	mysql_query($sql);
}

//XML字符串解析
//实例
//$xmlHttp='<xml><hRet>我是谁</hRet><point>100</point></xml>';
//echo xmlToStr($xmlHttp,"hRet");
function xmlToStr($xmlHttp,$xmlStr){
	//if ($xmlStr=="") exit;
	$iPos=strpos($xmlHttp,'<'.$xmlStr);
	//if ($iPos=="") exit;

	$iPos=strpos($xmlHttp,'<'.$xmlStr)+strlen('<'.$xmlStr)+1;
	$ePos=strpos($xmlHttp,'</'.$xmlStr);
	$str=substr($xmlHttp,$iPos,$ePos-$iPos);
	return $str;
}
//函数check_add_point判断该订单是否加过积分,每次充值的时候调用
//$id订单号
//$way支付方式短信(dx)和非短信(f_dx)
function checkHasAddPoint($id,$way)
{
    SetConn(88);
    if($way == 'f_dx')
    $selsql = "select tag from pay_log where OrderID='".$id."'";
    elseif($way=='dx')
    $selsql = "select tag from pay_sms where LinkID='".$id."'";
    $selret = mysql_query($selsql);
    $tag = @mysql_result($selret,0);
    return $tag;
}
//函数updatePoints加积分,每次充值的时候调用,前后台共用
//$PayID玩家账号ID
//$PayMoney充值金额 1元=1分制
//$way支付方式短信(dx)和非短信(f_dx)
//$oid订单号
//$front判断前后台
function updatePoints($PayID,$PayMoney,$way,$oid,$front='')
{
    $ROOT_paths= str_replace('inc/function.php', '', str_replace('\\', '/', __FILE__));
    if(!checkHasAddPoint($oid,$way))
    {
        SetConn(81);
        //判断vipPoints表是否有该账号，无则追加
        $check_has_account_id = "select account_id from vipPoints where account_id='".$PayID."'";
        $rethas = mysql_query($check_has_account_id);
        if(@mysql_num_rows($rethas)<=0)
        {
            mysql_query("insert into `vipPoints`(`account_id`,`NAME`,`uprankdate`) select `id`,`NAME`,current_date() from `account` where id='".$PayID."' limit 0,1");
        }
        //加积分
        $addsql_v = "update vipPoints set points=points+".$PayMoney.",pointstotal=pointstotal+".$PayMoney." where account_id='".$PayID."'";
        $ret_v = mysql_query($addsql_v);
        $paths = $ROOT_paths.'log';
//        if($way=='dx')
//        $paths = $ROOT_paths.'log';
//        if($front=='admin'&&$way=='dx')
//        $paths = '../sms/log';
//        elseif($front=='admin'&&$way=='f_dx')
//        $paths = $ROOT_paths.'newpay/log';
        $flag = 'ok';
        if(!$ret_v)
        {
            $flag .= 'add_err';
            write_log($paths,'add_point_v_log_error',$addsql_v.$paths."\r\n");
        }
        else
        {
          //积分日志
           write_log($paths,'add_point_success',$PayID.' '.$PayMoney.' '.$way.' '.$oid.' '."\r\n");
          
          ////同步积分到账号库
            $mypointsql = "select points from vipPoints where account_id='".$PayID."' limit 0,1";
            $mypoint = mysql_query($mypointsql);
            $pointmy = @mysql_result($mypoint,0);
            $addsql_a = "update account set points='$pointmy' where id='".$PayID."'";
            $ret_a = mysql_query($addsql_a);
            if(!$ret_a)
            {
                write_log($paths,'add_point_a_log_error',$addsql_a.$paths.date('Y-m-d H:i:s')."\r\n");
            }
            SetConn(88);
            if($way == 'f_dx')
                $upsql = "update pay_log set tag='1' where OrderID='".$oid."'";
            elseif($way=='dx')
                $upsql = "update pay_sms set tag='1' where LinkID='".$oid."'";
            $rets = mysql_query($upsql);
            if(!$rets)
            {
                $flag .= 'uptag_err';
                write_log($paths,'update_tag_log_error',$upsql.$paths."\r\n");
            }
        }
    }
    return $flag;
}
//addPointsByAdmin函数 后台管理员手工给玩家添加积分
//$account玩家账号
//$points积分...积分须大于0
//$admin管理员
//$reason加分理由
function addPointsByAdmin($account,$points,$admin,$reason)
{
    //链接账号库
    SetConn(81);
        //判断是否存在的账号
        $check_account_sql = "select id from account where NAME='".$account."'";
        $account_res = mysql_query($check_account_sql);
        if($account_id = @mysql_result($account_res,0))
        {
            //判断vipPoints表是否有该账号，无责追加
            $check_has_account_id = "select account_id from vipPoints where account_id='".$account_id."'";
            $rethas = mysql_query($check_has_account_id);
            if(@mysql_num_rows($rethas)<=0)
            {
                mysql_query("insert into `vipPoints`(`account_id`,`NAME`,`uprankdate`) select `id`,`NAME`,current_date() from `account` where id='".$account_id."' limit 0,1");
            }
            $add_points_sql = "insert into add_points_log(`account_id`,`NAME`,`points`,`addTime`,`reason`,`byWho`)values('$account_id','$account','$points',current_date(),'$reason','$admin')";
            $ret_add = mysql_query($add_points_sql);
            //加积分日志成功，给vipPoints表加积分
            if($ret_add)
            {
                $addsql_v = "update vipPoints set points=points+".$points.",pointstotal=pointstotal+".$points." where account_id='".$account_id."'";
                //exit($addsql);
                $ret_v = mysql_query($addsql_v);
                $mypointsql = "select points from vipPoints where account_id='".$account_id."' limit 0,1";
                $mypoint = mysql_query($mypointsql);
                $pointmy = @mysql_result($mypoint,0);
                $addsql_a = "update account set points='$pointmy' where id='".$account_id."'";
                $ret_a = mysql_query($addsql_a);
                if(!$ret_v)
                {
                    write_log('../newpay/log','add_points_admin_error',$addsql_v.mysql_error().date('Y-m-d H:i:s')."\r\n");
                    echo"<script>alert('写入积分表失败');history.go(-1);</script>";
                    exit;
                }
                else
                {
                    $strpoints = "account=$account,points=$points,who=$admin,reason=$reason";
                    write_log('../log','add_points_admin_log',$strpoints.date('Y-m-d H:i:s')."\r\n");
                }

                updateRankUp($account_id,'f_dx','admin');          //修改等级
                if(!$ret_a)
                {
                    write_log('../newpay/log','add_points_sym_admin_error',$addsql_a.mysql_error().date('Y-m-d H:i:s')."\r\n");
                    echo"<script>alert('同步积分失败');history.go(-1);</script>";
                    exit;
                }
            }
        }
        else
        {
            echo"<script>alert('该账号不存在');history.go(-1);</script>";
            exit;
        }
}

//上升等级更新 updateRankUp,前后台共用
//$PayID玩家账号ID
function updateRankUp($PayID,$way,$front='')
{
     $ROOT_paths= str_replace('inc/function.php', '', str_replace('\\', '/', __FILE__));
    SetConn(81);
    $infosql = "select NAME,pointstotal,vip,topVip,uprankdate from vipPoints where account_id=$PayID";
    $infores = mysql_query($infosql);
    $paths = $ROOT_paths.'log';
//    if($way=='dx')
//    $paths = './log';
//    if($front=='admin'&&$way=='dx')
//    $paths = '../sms/log';
//    elseif($front=='admin'&&$way=='f_dx')
//    $paths = '../newpay/log';
    if(!$info = mysql_fetch_array($infores))
    {   
        write_log($paths,'updateRankUp_log_error'.date('Y-m-d'),$infosql.$front."\r\n");
    }
    else
    {
        $name = $info['NAME'];
        $pointstotal = $info['pointstotal'];
        $olduptime = $info['uprankdate'];
        $oldtimestr = $olduptime.' 00:00:00';
        $nowtoptag = $info['topVip'];          //历史最高等级
        $rank = $info['vip'];           //当前等级
        $newtag = 0;                     //新等级
        $date = date('Y-m-d');
        if($pointstotal>0 && $pointstotal<500)
        {
            $newtag = 0;
        }
        elseif($pointstotal>=500 && $pointstotal<1500)
        {
            $newtag = 1;
        }
        elseif($pointstotal>=1500 && $pointstotal<3000)
        {
            $newtag = 2;
        }
        elseif($pointstotal>=3000 && $pointstotal<5000)
        {
            $newtag = 3;
        }
        elseif($pointstotal>=5000&& $pointstotal<10000)
        {
            $newtag = 4;
        }
       
        elseif($pointstotal>=10000 && $pointstotal<20000)
        {
            $newtag = 5;
        }
        elseif($pointstotal>=20000 && $pointstotal<30000)
        {
            $newtag = 6;
        }elseif($pointstotal>=30000 && $pointstotal<40000)
        {
            $newtag = 7;
        }elseif($pointstotal>=40000 && $pointstotal<50000)
        {
            $newtag = 8;
        }elseif($pointstotal>=50000 )
        {
            $newtag = 9;
        }
         /* 需要使用5，6级时开发
        elseif($pointstotal>=15000)
        {
            $newtag = 6;
        }
         *
         */
        //判断等级是否上升
        //判断新等级是否比历史等级高，高则直接更新
        if($newtag>$nowtoptag)
        {
            $upranksql = "update vipPoints set uprankdate= '$date',vip='$newtag',topVip = '$newtag' where account_id=$PayID";
            $upret = mysql_query($upranksql);
            if(!$upret)
            {
                 write_log($paths,'updateRankUp_v_log_error'.$date,$upranksql."\r\n");
            }
            $upranksql_a = "update account set vip='$newtag' where id=$PayID";
            $upret_a = mysql_query($upranksql_a);
            if(!$upret_a)
            {
                 write_log($paths,'updateRankUp_a_log_error'.$date,$upranksql."\r\n");
            }
        }
      /*  需要使用5，6级时使用
        else
        {
            //链接充值库
            SetConn(88);
            $payinfosql = "select sum(PayMoney) as c from pay_log where (`rpCode`='1' or `rpCode`='10') and `Add_Time`>='$oldtimestr' and PayID='$PayID' limit 0,1";
            $payinfores = mysql_query($payinfosql);
            $paytotal = mysql_result($payinfores,0);

            //链接账号库
            SetConn(81);
            //当前等级小于历史最高等级
            if($nowtoptag>$rank)
            {
                    if($nowtoptag == '6')
                    {
                        if(($rank == '5' && $paytotal >= '1200') || ($rank == '4' && $paytotal >= '2200'))        //4,5升6
                        {
                            $uprankto6 = "update account set uprankdate= '$date',rank='6' where id=$PayID";
                            $upretto6 = mysql_query($uprankto6);
                            $rank = 6;
                            if(!$upretto6)
                            {
                                 write_log($paths,'updateRankUp_log_error'.$date,$uprankto6." $rank 升6 \r\n");
                            }
                        }
                    }
                    if($nowtoptag == '6' || $nowtoptag == '5')
                    {
                        if($rank == '4' && $paytotal >= '1000')        //4升5
                        {
                            $uprank4to5 = "update account set uprankdate= '$date',rank='5' where id=$PayID";
                            $upret4to5 = mysql_query($uprank4to5);
                            $rank = 5;
                            if(!$upret4to5)
                            {
                                 write_log($paths,'updateRankUp_log_error'.$date,$upret4to5."4升5 tag=$nowtoptag \r\n");
                            }
                        }
                    }
            }
            //echo $payinfosql;
            //echo $paytotal;exit;
        }*/
       // updateBbsVip($name,$newtag);
    }
}

//更新BBS会员等级为vip等级
//$name玩家账号
//$newtag玩家等级
function updateBbsVip($name,$newtag)
{
    global $discuzpre;
    if($newtag>=1)
    {
        SetConn(89);
        $upbbssql = "update ".$discuzpre."members set groupid='16' where username='$name' and groupid not in(1,2,3,16)";//1,2,3表示管理员组
        //echo $upbbssql;exit;
        mysql_query($upbbssql);
    }
}

//降等级更新 updateRankDown
//每天执行一次
function updateRankDown()
{
//        $str = strtotime("-3 month");
//	$startdate = date('Y-m-d',$str);
//	SetConn(81);  //链接游戏账号库
//	$Sqlrank="SELECT id,rank FROM account WHERE rank >='5' and uprankdate<='".$startdate."'";
//        //echo $Sqlrank;
//	$rankres = mysql_query($Sqlrank);
//	$aryrank = array();
//	while($rs = mysql_fetch_array($rankres))
//	{
//		$aryrank[$rs['id']]['id'] = $rs['id'];
//                $aryrank[$rs['id']]['rank'] = $rs['rank'];
//	}
//	//print_r($aryrank);exit;
//        SetConn(88);  //链接充值日志库
//        $startdatetime = $startdate." 00:00:00";
//        $ary_point = array();
//        foreach($aryrank as $val)
//        {
//            $paytotalsql = "select sum(PayMoney) from pay_log where (`rpCode`='1' or `rpCode`='10') and `Add_Time`>='$startdatetime' and PayID='".$val['id']."' limit 0,1";
//            //echo $paytotalsql."<br>";
//            $totalres = mysql_query($paytotalsql);
//            $ary_point[$val['id']]['id'] = $val['id'];
//            $ary_point[$val['id']]['rank'] = $val['rank'];
//            $ary_point[$val['id']]['points'] = mysql_result($totalres,0);
//        }
//        //print_r($ary_point);exit;
//        $date = date('Y-m-d');
//        SetConn(81);  //链接游戏账号库
//        foreach($ary_point as $val)
//        {
//            $downsql = "update account set rank = rank-1,uprankdate='$date' where id='".$val['id']."'";
//            if($val['rank'] == 6 && $val['points'] < 2400)
//            {
//                $ret = mysql_query($downsql);
//                if(!$ret)
//                {
//                    write_log('log','updateDown_log_err_',$downsql." tag=6".'  '.$date."\r\n");
//                }
//            }
//            elseif($val['rank'] == 5 && $val['points'] < 1500)
//            {
//                $ret = mysql_query($downsql);
//                if(!$ret)
//                {
//                    write_log('log','updateDown_log_err_',$downsql." tag=5".'  '.$date."\r\n");
//                }
//            }
//        }
}

//获取会员等级
//$rid为 rank字段的值
function getRank($rid){
	switch($rid)
	{
		case 0:
			$str = "普通会员";
			break;
		case 1:
			$str = "1星级VIP";
			break;
		case 2:
			$str = "2星级VIP";
			break;
		case 3:
			$str = "3星级VIP";
			break;
                case 4:
			$str = "4星级VIP";
			break;
                case 5:
			$str = "5星级VIP";
			break;
                case 6:
			$str = "6星级VIP";
			break;
		default:
			$str = "普通会员";
	}
	return $str;
}

 function send($smtpemailto,$NAME,$mailsubject,$mailbody)
    {
        //##########################################
        $smtpserver = "smtp.163.com";//SMTP服务器
        $smtpserverport =25;//SMTP服务器端口
        //
        //$smtpemailto = "418209006@163.com";//发送给谁
//        $smtpuser = "418209006@163.com";//SMTP服务器的用户帐号
//        $smtppass = "fdk87ta6hy09g3z";//"hmj520";//SMTP服务器的用户密码

        $smtpusermail = "hainiu591@163.com";//SMTP服务器的用户邮箱
        $smtpuser = "hainiu591@163.com";//SMTP服务器的用户帐号
        $smtppass = "hai!@##9jfg&*%(";//"hmj520";//SMTP服务器的用户密码

        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件

        $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = FALSE;//是否显示发送的调试信息
        // $smtpemailto. $smtpusermail. $mailsubject. $mailbody. $mailtype;exit;
       $result =  $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);


    }

//随即字符串
function genRandomString($len)
{
    $chars = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
        "k","l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v","w",
        "x", "y", "z", "A", "B", "C", "D", "E", "F", "G","H", "I",
        "J", "K", "L", "M", "N", "O", "P", "Q", "R","S", "T", "U",
        "V", "W", "X", "Y", "Z", "0", "1", "2","3", "4", "5", "6",
        "7", "8", "9"
    );
$charsLen = count($chars) - 1;
shuffle($chars);
$output = "";
for ($i=0; $i<$len; $i++)
{
    $output .= $chars[mt_rand(0, $charsLen)];
}
return $output;
}

function data_check($val){
	if(is_array($val)){
		foreach($val as $k=>$v)	$val[$k]=data_check($v);
	}else{
		if(!get_magic_quotes_gpc()){
			$val=addslashes($val);
		}
		$dstr='select|insert|update|delete|union|into|load_file|outfile';
		$val=eregi_replace($dstr, '', $val);
	}
	return $val;
}
//查询剩余积分
//参数:账号ID
function searchPoints($acid)
{
    SetConn(81);//连接账号库

    $result=mysql_query("select points from vippoints Where account_id='$acid'");
	if(mysql_num_rows($result)>0){
    	$points=mysql_result($result,0);
    }
    $points = $points?$points:'0';
    return $points;
}
function payby91($tabType,$ServerID,$PayType,$PayID,$OrderID){
    SetConn($ServerID);
    $time_stamp=date('ymdHi');
    $sql="select count(0) from u_card where ref_id='$OrderID'";
    $query=mysql_query($sql);
    $RowCount=mysql_result($query,0);
    if ($RowCount == 0){
        $sql="insert into u_card(type,account_id,ref_id,time_stamp,used)";
        $sql=$sql." values('$PayType',$PayID,'$OrderID',$time_stamp,0)";
        if (mysql_query($sql) == False){
            //更新pay_log状态
            $str="error:".$sql.mysql_error().date("Y-m-d H:i:s")."\r\n";
            write_log(ROOT_PATH."log","pay_91_sql_log",$str);
            
            SetConn(88);//连接u591数据库
            if ($tabType==1){
				$sqls="update pay_log set IsUC=1 where OrderID='$OrderID'";
			}else{
				$sqls="update pay_sms set IsUC=1 where LinkID='$OrderID'";
			}
            //更改日志状态失败
            if (mysql_query($sqls) == False)
            {
                $strs = "error:充值成功，修改状态失败 ".$sqls.mysql_error().date("Y-m-d H:i:s")."\r\n";
                write_log(ROOT_PATH."log","pay_91_sql_log",$strs);
            }

            exit;
        }

    }
}
//目前支持 http post 提交 xml
function  xml_postData($url, $data)
{
    $ch = curl_init();

    $timeout = 300;

    $header[] = "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
    $header[] = "Cache-Control: max-age=0";
    $header[] = "Keep-Alive: 300";
    $header[] = "Accept-Charset: utf-8;q=0.7,*;q=0.7";
    $header[] = "Accept-Language: en-us,en;q=0.5";
    $header[] = "Content-type: text/xml";//定义content-type为xml

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    $handles = curl_exec($ch);

    curl_close($ch);
    return $handles;
}

function random_common() {
    $hash = '';
    $array=array('~','!','@','#','$','%','^','&','*','(');
    shuffle($array);
    for($i = 0; $i < 3; $i++) {
        $hash .= uniqid(rand()).$array[$i];
    }
    return md5(md5($hash));
}
function common_json_post($url,$data ){
    $curl = curl_init($url); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_HTTPHEADER,
        array(
 'Content-Type: application/json' ,
 'Content-Length: ' . strlen($data)
        ));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl);
    if (curl_errno($curl)) {
        return 'Errno' .curl_error($curl);
    }
    curl_close($curl);
    return $tmpInfo;
}

function https_post($url,$data ){
    $str ;
    if($data){
       foreach($data as $key=>$value){
         $str .= $key."=".$value."&";
       }
    }



    $curl = curl_init($url); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
   // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    if($str){
    curl_setopt($curl, CURLOPT_POSTFIELDS, $str); // Post提交的数据包
    }
    curl_setopt($curl, CURLOPT_TIMEOUT, 5); // 设置超时限制防止死循环
   // curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
//    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $tmpInfo = curl_exec($curl);
    if (curl_errno($curl)) {
       return   https_post_2($url,$data );
    }
    curl_close($curl);
    return $tmpInfo;
}

function https_post_2($url,$data ){
    $str ;
    if($data){
       foreach($data as $key=>$value){
         $str .= $key."=".$value."&";
       }
    }



    $curl = curl_init($url); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
   // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POSTFIELDS, $str); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 5); // 设置超时限制防止死循环
   // curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
//    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $tmpInfo = curl_exec($curl);
    if (curl_errno($curl)) {
         return   https_post_3($url,$data );
    }
    curl_close($curl);
    return $tmpInfo;
}
function https_post_3($url,$data ){
    $str ;
    if($data){
       foreach($data as $key=>$value){
         $str .= $key."=".$value."&";
       }
    }



    $curl = curl_init($url); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
   // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POSTFIELDS, $str); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 5); // 设置超时限制防止死循环
   // curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
//    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $tmpInfo = curl_exec($curl);
    if (curl_errno($curl)) {
        $error = 'Errno' .curl_error($curl);
        curl_close($curl);
        return $error;
    }
    curl_close($curl);
    return $tmpInfo;
} 

?>