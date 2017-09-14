<?php
$className=$_REQUEST['cn'];
$from = $_REQUEST['fromTime'];
$to = $_REQUEST['toTime'];
$deptag = $_REQUEST['depTag'];
$pertag = $_REQUEST['perTag'];
$mantag = $_REQUEST['manTag'];
$uid = $_REQUEST['uid'];

    if(!$_REQUEST['fromTime'])
    {
        $from = date('Y-m')."-01";
    }
    if(!$_REQUEST['toTime'])
    {
        $to = date('Y-m-d');
    }

$admin = new admin();
$sear = $admin->getInfo($_SESSION['ADMIN_ID'],'seartag','pass');

if($className)
{
    $class=new $className();
    if($_SESSION['ADMIN_ID']!='99' && !$sear)  //总经理账号99
    {
        $class->wheres .=" and uid='".$_SESSION['ADMIN_ID']."'";
    }
    if($from)
    {
        if($className=='sign')
        $class->wheres .=" and addDate>='$from'";
        elseif($className=='oddtime')
        $class->wheres .=" and supdate>='$from'";
        else
        $class->wheres .=" and fromtime>='$from'";
    }
    if($to)
    {
        if($className=='sign')
        $class->wheres .=" and addDate<='$to'";
        elseif($className=='oddtime')
        $class->wheres .=" and supdate<='$to'";
        else
        $class->wheres .=" and toTime<='$to'";
    }
    if($uid)
    {
        $class->wheres .=" and uid = '$uid'";
    }
    if(isset($deptag)&&$deptag!='')
    {
        $class->wheres .=" and depTag='$deptag'";
    }
    if(isset($pertag)&&$pertag!='')
    {
        $class->wheres .=" and perTag='$pertag'";
    }
    if(isset($mantag)&&$mantag!='')
    {
        $class->wheres .=" and manTag='$mantag'";
    }
    $class->p=$_REQUEST['p'];

    $list=$class->getList();
    $pageCtrl=$class->getPageInfoHTML('0',"search.php?type=web&cn=$className&fromTime=$from&toTime=$to&depTag=$deptag&perTag=$pertag&manTag=$manTag&uid=$uid&p=");
}
?>
<h1 class="title"><span>审核结果查询</span></h1>
 <div class="pidding_5">
     <div class="search">
         <form action="" method="post">
             类型:<select name="cn">
                 <option value="">请选择</option>
                 <option value="overtime" <?php echo $className=='overtime'?'selected':'' ?>>加班</option>
                 <option value="hugh" <?php echo $className=='hugh'?'selected':'' ?>>调休</option>
                 <option value="leave" <?php echo $className=='leave'?'selected':'' ?>>请假</option>
                 <option value="sign" <?php echo $className=='sign'?'selected':'' ?>>签呈</option>
                 <option value="outrecord" <?php echo $className=='outrecord'?'selected':'' ?>>公出</option>
                 <option value="oddtime" <?php echo $className=='oddtime'?'selected':'' ?>>异常单</option>
             </select>
             时间：<input type="text" name="fromTime" size="10" id="date_s" value="<?=$from?>"  readonly > 到：
        <input type="text" name="toTime" size="10" id="date_e" value="<?=$to?>" readonly >
        部门审核:
        <select name="depTag">
              <option value="">全部</option>
              <option value="0" <?php echo $deptag=='0'?'selected':'' ?>>未审核</option>
              <option value="1" <?php echo $deptag=='1'?'selected':'' ?>>不通过</option>
              <option value="2" <?php echo $deptag=='2'?'selected':'' ?>>通过</option>
          </select>
        人事审核:
        <select name="perTag">
              <option value="">全部</option>
              <option value="0" <?php echo $pertag=='0'?'selected':'' ?>>未审核</option>
              <option value="1" <?php echo $pertag=='1'?'selected':'' ?>>不通过</option>
              <option value="2" <?php echo $pertag=='2'?'selected':'' ?>>通过</option>
          </select>
        总经理审核:
        <select name="manTag">
              <option value="">全部</option>
              <option value="0" <?php echo $mantag=='0'?'selected':'' ?>>未审核</option>
              <option value="1" <?php echo $mantag=='1'?'selected':'' ?>>不通过</option>
              <option value="2" <?php echo $mantag=='2'?'selected':'' ?>>通过</option>
          </select>
        <?if($sear=='1'){?>
        姓名:<select name="uid">
              <option value="">全部</option>
                <?php
                    $sql = "select id,real_name from _sys_admin where id <>'99'";
                    $res = $webdb->getList($sql);
                    foreach($res as $val){
                ?>
              <option value="<?=$val['id']?>" <?php echo $val['id']==$uid?'selected':'' ?>><?=$val['real_name']?></option>
              <?php }?>
          </select>
        <?}?>
        <input type="submit" name="sub" value="查 询" class="sub2">
         </form>
    </div>
   <?php if($className){?>
  <?include($_GET['type'].'/'.$className.'.list.php');?>
  <div class="news-viewpage"><?=$pageCtrl?></div>
  <?php }?>
 </div>