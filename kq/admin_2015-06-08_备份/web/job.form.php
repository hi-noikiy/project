    <tr>
      <td class="N_title">岗位名称：</td><td class="N_title" colspan="7">
        <input type="text" name="name" size="20">
      </td>
    </tr>
    <tr>
      <td class="N_title">所属部门：</td><td class="N_title" colspan="7">
        <select name="pid">
        	<?=aryOption(dgAry('_web_department',"and id<>'".$info['id']."'"),null,true,false)?>
        </select>
      </td>
    </tr>
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
