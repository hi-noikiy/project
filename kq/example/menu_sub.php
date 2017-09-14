<?php
include('common.inc.php');
$nowid=$_GET['nowid'];
$menu_html=implode('',dgHtml('_web_prodtype','<div menu_pid="%pid" menu_id="%id" id="menu" style="display:none;"><a href="'.$rooturl.'prodlist.php?id=%id">%ex%name</a></div>'));
?>
<script src="<?=$rooturl?>include/jscode/jquery.js" type="text/javascript"></script>
<img src="images/product_11.jpg" width="220" height="38" />
<?PHP echo $menu_html?>
<script>
$('div[menu_pid]').hide();
$('div[menu_pid=0]').show();
$('div[menu_pid]').each(function (){
	var obj=$('div[menu_pid='+$(this).attr('menu_id')+']');
	if(obj.length>0){
		$(this).find('a').attr('href','javascript:;');
		$(this).click(function (){
			$('div[menu_pid='+$(this).attr('menu_id')+']').show();
		});
	}
})
var nowid='<?=$nowid?>';
while($('div[menu_id='+nowid+']').attr('menu_pid')){
	$('div[menu_id='+nowid+']').show();
	nowid=$('div[menu_id='+nowid+']').attr('menu_pid');
	$('div[menu_pid='+nowid+']').show();
}
</script>