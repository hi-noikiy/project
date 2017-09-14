<?php
$className=$_GET['cn'];
$classStr=$_type[$className];
if($className=='record')
{
    if(!$_GET['fromTime'])
    {
        $_GET['fromTime'] = date('Y-m')."-01";
    }
    if(!$_GET['toTime'])
    {
        $_GET['toTime'] = date('Y-m-d');
    }
}
$class=new $className();
$class->setKw($_GET);
$class->p=$_GET['p'];

//需要特殊显示的类 配置文件admin/common.inc.php
if(in_array($className,$ar) && $_SESSION['role']=='3')
{
    $class->setWhere(" uid='".$_SESSION['ADMIN_ID']."'");
}
elseif(in_array($className,$ar) && $_SESSION['role']=='2')
{
    $admin = new admin();
    $depIds = $admin->getInfo($_SESSION['ADMIN_ID'], 'depId', 'pass');
    $class->setWhere(" uid='".$_SESSION['ADMIN_ID']."' or depId='$depIds'");
}

if($className=='record')
{
    $admin = new admin();
    $seartag = $admin->getInfo($_SESSION['ADMIN_ID'], '', 'pass');
    if($seartag['seartag']!='1')
    {
        $class->setWhere("card_id = '".$seartag['card_id']."'");
    }
    $class->setWhere("gong_id != '0' and card_id!='5326068'");  //5326068为老大卡
}

if($_GET['order']) $class->setOrder($_GET['order']);
$list=$class->getList();
$pageCtrl=$class->getPageInfoHTML();

?>
 <h1 class="title"><span><?=$classStr?>列表</span></h1>
 <div class="pidding_5">
  <?include($_GET['type'].'/'.$className.'.list.php');?>
  <div class="news-viewpage"><?=$pageCtrl?></div>
 </div>
<script>
function searchFun(){
	var url=$('#searchForm').attr('action');
	$('#searchForm').find(':input[name]').each(function (){
		if($(this).val()){
			url+='&'+$(this).attr('name')+'='+$(this).val();
		}
	});
	window.location.href=url;
	return false;
}
</script>
<script>
$('input[postType]').blur(function (){
	var param={};
	param[$(this).attr('name')]=$(this).val();
	$.post('command.php?action=edit&type='+$(this).attr('postType')+'&id='+$(this).attr('postId'),param,function (){ })
})
</script>