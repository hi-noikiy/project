<?php
include_once('common.inc.php');

if($_POST['action']=='save'){

    $info1 = $_REQUEST['info1'];
    $info2 = $_REQUEST['info2'];
    $ping = $_REQUEST['ping'];
    $beiping= intval($_REQUEST['beiping']);
    $fen = intval($_REQUEST['fen']) ;

    if($ping==$_SESSION['ADMIN_ID']){
        $sql=" select * from kaohe_2012_myinfo where admin_id ='$ping' ";
        $query=$webdb->query($sql);
        $rs=mysql_fetch_assoc($query);
        if($rs['id']){//编辑
            $sql = " update kaohe_2012_myinfo set info1='$info1',info2='$info2'  where id=  ".$rs['id'];
            $webdb->query($sql);
        }else{//添加
            $sql = " insert into kaohe_2012_myinfo(info1,info2,admin_id) values('$info1','$info2','$ping') ";
            $webdb->query($sql);
        }
    }
    if($fen){
        $sql = " select * from kaohe_2012_fen where ping='$ping' and beiping='$beiping' ";
        $query=$webdb->query($sql);
        $rs=mysql_fetch_assoc($query);
        if($rs['id']){
            $update_sql = " update kaohe_2012_fen set fen='$fen'  where ping='$ping' and beiping='$beiping' ";
            $webdb->query($update_sql);
        }else{
            $insert_sql = " insert into kaohe_2012_fen(fen,beiping,ping) values('$fen','$beiping','$ping') ";
            $webdb->query($insert_sql);
        }
    }
}
$beiping= intval($_REQUEST['beiping']);
$ping= intval($_REQUEST['ping']);
$beiping = $beiping?$beiping:$_SESSION['ADMIN_ID'];
$ping = $ping?$ping:$_SESSION['ADMIN_ID'];

$sql=" select * from kaohe_2012_myinfo where admin_id ='$beiping' ";
$query=$webdb->query($sql);
$result=mysql_fetch_assoc($query);

$sql_fen=" select * from kaohe_2012_fen where beiping ='$beiping' and ping='$ping' ";
$query_fen=$webdb->query($sql_fen);
$result_fen=mysql_fetch_assoc($query_fen);
$fen = intval($result_fen['fen']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>海牛考勤管理系统</title>
        <!-- JQuery文件 -->
        <script src="../include/jscode/jquery.js" type="text/javascript"></script>
        <script src="../include/jscode/jquery/jquery.datepick.js" type="text/javascript"></script>
        <script src="../include/jscode/jquery/jquery.datepick-zh-CN.js" type="text/javascript"></script>
        <link href="../include/jscode/jquery/jquery.datepick.css" rel="stylesheet" type="text/css" />
        <!-- Cookie文件 -->
        <script src="../include/jscode/cookie.js" type="text/javascript"></script>
        <!-- 公共JS文件 -->
        <script type="text/javascript" src="../comm/comm.js"></script>
        <script type="text/javascript" src="index.js"></script>
        <link href="../include/jscode/messager.css" rel="stylesheet"  type="text/css" />
        <link href="style/css/admin2.css" rel="stylesheet" type="text/css" />
        <LINK rev=stylesheet media=all href="../images/tree/tree_menu.css" type="text/css" rel=stylesheet />
        <script language="JavaScript" src="../images/tree/tree_menu.js"></script>
        <script src="../include/jscode/jquery.messager.js"></script>
    </head>
    <body>
        <div style="width:100%;">
            <div style="float:left; padding:0 0 5px 0"><img src="admin_logo.jpg" border="0"  width="202" height="45"></div>
            <div style="float:right;padding:5px"><A HREF="login.php?out=yes"><img src="style/images/main_r1_c35.gif" width="16" height="40" border="0" title="登出"></A></div>
            <div style="float:right;padding:20px">欢迎使用：<? $admin = new admin();echo $admin->getInfo($_SESSION['ADMIN_ID'], 'real_name', 'pass')?></div>
        </div>
        <div style="width:100%; height: 90%; float: left;">
            <div id="left">
                <div class="left_box">
                    <?php include('index.menu.php')?>
                </div>
            </div>
            <div id="right">
                <form method="post"  action="">
                    <input type="hidden"  name="action" value="save" />
                    <input type="hidden"  name="beiping" value="<?php echo $beiping; ?>" />
                    <input type="hidden"  name="ping" value="<?php echo $ping; ?>" />
                    <h1 class="title"><span>自我评价/本年度工作回顾</span></h1>
                    <div class="pidding_5">
                        <table cellspacing="0" cellpadding="0" class="Admin_L">

                            <tr>
                                <td>工作业绩<br/>本年度个人工作总体完成情况及重点工作内容完成情况描述（(注：在描述时，请给出有关数量、质量、完成时间或主要成果说明）<br/>本人填写</td>
                                <td> <textarea cols="100" rows="12" name="info1"><?php echo $result['info1']; ?></textarea> </td>
                            </tr>
                            <tr>
                                <td>流程制度与创新/学习成长<br/>本年度个人在流程制度梳理及创新,个人学习成长方面的开展事件(注：流程制度及创新事件描述等)<br/>本人填写</td>
                                <td> <textarea cols="100" rows="12" name="info2"><?php echo $result['info2']; ?></textarea> </td>
                            </tr>
                            <tr>
                                <td>评分 最高分数为 120分</td>
                                <td> <input name="fen" value="<?php echo $fen; ?>" >请输入数字 </td>
                            </tr>
                            <tr class="Ls2">
                                <td class="N_title">&nbsp;</td>
                                <td class="N_title" colspan="7"><?php if($result['admin_id']&&$result['admin_id']!=$_SESSION['ADMIN_ID']){ }else{?> <input class="sub2" type="submit" value="确 定"> <?php }?></td>
                            </tr>

                        </table>
                    </div>
                </form>


            </div>
        </div>

    </body>
</html>
<script>
    $(document).ready(function (){
        $('#date_s').datepick({dateFormat: 'yy-mm-dd'});
        $('#date_e').datepick({dateFormat: 'yy-mm-dd'});
        //时间控件
        //$("input[date]").jSelectDate({ yearEnd: 2010, yearBegin: 1995, disabled : false, css:"select", isShowLabel : true });
    });
</script>
<?if($altmsg || $altmsg=$_GET['altmsg']){?>
<script>alert('<?=$altmsg?>');</script>
<?}?>