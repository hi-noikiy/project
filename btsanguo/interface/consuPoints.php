<?
include_once 'init.php';



$account_id=$_REQUEST["account_id"];
$point=$_REQUEST["point"];
if(!is_numeric($point) || strpos($point,".")!==false || $point <=0)
{
    echo -2;  //积分必须为正整数
    exit;
}
$leftpoint = searchPoints($account_id);

if($leftpoint>=$point)//判断剩余积分是否足够
{
    $lelt = $leftpoint - $point;
    SetConn(81);//链接账号库
    $sql = "update vippoints set points='$lelt' where account_id='$account_id'";

    if(mysql_query($sql)==false)
    {
        echo -3;  //执行失败
        $str = "error points='$point'".","."id='$account_id'".",".mysql_error().",".date("Y-m-d H:i:s")."\r\n";
        write_log(ROOT_PATH."log","points_consum_err_",$str);//成功日志
        exit;
    }
    else
    {
        echo $lelt;   //扣除成功
        $upsql = "update account set points='$lelt' where id='$account_id'";
        mysql_query($upsql);
        if(mysql_query($upsql)==false)
        {
            $str="error: ".$upsql." sqlerror:".mysql_error().",".date("Y-m-d H:i:s")."\r\n";
            write_log(ROOT_PATH."log","points_syn_err_",$str);
        }
        else
        {
            $str = "s points='$point'".","."leftpoint='$lelt'".","."id='$account_id'".",".date("Y-m-d H:i:s")."\r\n";
            write_log(ROOT_PATH."log","points_consum_s_",$str);//成功日志
        }
        exit;
    }
}
else
{
    echo -1;  //积分不足
    exit;
}
?>