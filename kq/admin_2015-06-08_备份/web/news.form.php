<?php 
if($_GET["id"]==1){
?>
<tr>
  <td class="N_title">內容：</td><td class="N_title" colspan="7">
    <?=htmlEdit('content',$info['content'])?>
  </td>
</tr>
<?php 
}else{
?>
    <input type="hidden" name="newsdt" value="<?=date('Y-m-d H:i:s')?>">
    <tr>
      <td class="N_title">新聞標題：</td><td class="N_title" colspan="7">
        <input type="text" name="title" size="60">
        <input type="hidden" name="ntype" value="<?php echo $_GET["ntype"];?>">
      </td>
    </tr>
    <tr>
      <td class="N_title">新聞來源：</td><td class="N_title" colspan="7">
        <input type="text" name="formsite" size="40">
      </td>
    </tr>
    <tr>
      <td class="N_title">新聞內容：</td><td class="N_title" colspan="7">
        <?=htmlEdit('content',$info['content'])?>
      </td>
    </tr>
    <tr>
      <td class="N_title">有效性：</td><td class="N_title" colspan="7">
        <select name="showtag">
        	<?=dicOption('tag',1)?>
        </select>
      </td>
    </tr>
<?php 
}
?>
<script>
function checkForm(form){
	var msg='';
	if(msg){
		alert(msg);
		return false;
	}else{
	    return true;
	}
}
</script>
