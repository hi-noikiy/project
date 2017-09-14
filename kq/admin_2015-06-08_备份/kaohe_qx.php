<?php
include_once('common.inc.php');


$sql=" select * from _sys_admin order by  depId ";
$query=$webdb->query($sql);
while ($rs=mysql_fetch_assoc($query)){
    $list[]=$rs;
}
$admin_user = $_REQUEST['admin_user'];
$action = $_POST['action'];
if($action=='save'){
    $admin_user_id = $_REQUEST['admin_user_id'];
    if($admin_user_id){
        $beiping_arr = $_POST['beiping_arr'];
        //清空了 老记录
        $del_sql = " delete from kaohe_2012_fen where ping ='$admin_user_id' ";
        $webdb->query($del_sql);

        for($i=0;$i<count($beiping_arr);$i++){
            $insert_sql = " insert into kaohe_2012_fen(beiping,ping) values('".$beiping_arr[$i]."','$admin_user_id') ";
            $webdb->query($insert_sql);
        }
    }

}
if($admin_user){
    $sql = " select beiping from  kaohe_2012_fen where ping ='$admin_user'  ";
    $query=$webdb->query($sql);
    while ($rs=mysql_fetch_assoc($query)){
        $beiping_arr_old[]=$rs['beiping'];
    }
}




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
                    <h1 class="title"><span>设置员工可以评价的人员</span></h1>
                    <table cellspacing="0" cellpadding="0" class="Admin_L">
                        <tr>
                            <td>人员:
                                <select name="admin_user_id"   >
                                    <?php for($i=0;$i<count($list);$i++){
                                        ?>
                                        <?php if($list[$i]['id']==$admin_user){ ?> 
                                    <option value="<?php if($list[$i]['id']==$admin_user){echo $list[$i]['id'];} ?> " <?php if($list[$i]['id']==$admin_user){echo "selected";} ?>  > <?php if($list[$i]['id']==$admin_user){echo $list[$i]['real_name'];} ?>  </option>
                                         <?php }?>
                                   <?php   }?>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <div class="pidding_5">
                        <table cellspacing="0" cellpadding="0" class="Admin_L">
                            <tr>
                                <td>可评价人员</td>
                                <td>
                                    <?php for($i=0;$i<count($list);$i++){ ?>
                                    <input type="checkbox" name="beiping_arr[]" <?php if(in_array($list[$i]['id'], $beiping_arr_old)){ echo "checked";} ?>   value="<?php echo $list[$i]['id']; ?>"><?php echo $list[$i]['real_name']; ?>&nbsp;&nbsp;&nbsp;
                                    <?php   }?>
                                </td>
                            </tr>
                            <tr class="Ls2">
                                <td ></td>
                                <td class="N_title" colspan="7"> <input class="sub2" type="submit" value="确 定"> </td>
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