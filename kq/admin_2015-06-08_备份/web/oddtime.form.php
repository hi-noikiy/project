<?php
$showtag = true;
$user = new user ();
$uid = '';
if ($_GET ['id']) {
	$userinfo = $user->getUser ( $info ['uid'] );
	$uid = $info ['uid'];
} else {
	$userinfo = $user->getUser ( $_SESSION ['ADMIN_ID'] );
	$uid = $_SESSION ['ADMIN_ID'];
}
$dep = new department ();
$depname = $dep->getInfo ( $userinfo ['depId'], 'name', 'pass' );
?>
<?php
if ($_GET ['id']) {
	$strs = acTime (); // 计算时间戳
	// 时间差
	// $n = 1;
	// 撤销条件 1，5号前前一个月即n>=0,否则当月 2，本人 3，状态必须是可作废
	$n = strtotime ( $info ['supdate'] . ' ' . $info ['from'] ) - $strs;
?>
<tr>
	<td class="N_title">日期：</td>
	<td class="N_title" colspan="7"><input type="text" size="20" value="<?=date("Y-m-d")?>" name="addDate" readonly></td>
</tr>
<tr>
	<td class="N_title">申请人：</td>
	<td class="N_title" colspan="7">
		<input type="text" size="20" value="<?=$userinfo['real_name']?>" readonly>
		<input type="hidden" name="uid" value="<?=$uid?>">
	</td>
</tr>
<tr>
	<td class="N_title">部门：</td>
	<td class="N_title" colspan="7">
		<input type="text" size="20" value="<?=$depname?>" readonly>
		<input type="hidden" name="depId" value="<?=$userinfo['depId']?>">
	</td>
</tr>
<tr>
	<td class="N_title">时间段：</td>
	<td class="N_title" colspan="7">
		日期:<input type="text" name="supdate" size="10" readonly><br>&nbsp;&nbsp;
		早上上班:<input type="text" name="amstart" value="<?=$info['amstart']?>" size="5" disabled><br> &nbsp;&nbsp;
		早上下班:<input type="text" name="amend" value="<?=$info['amend']?>" size="5" disabled><br>&nbsp;&nbsp;
		下午上班:<input type="text" name="pmstart" value="<?=$info['pmstart']?>" size="5" disabled><br> &nbsp;&nbsp;
		下午下班:<input type="text" name="pmend" value="<?=$info['pmend']?>" size="5" disabled>
	</td>
</tr>
<tr>
	<td class="N_title">原由：</td>
	<td class="N_title" colspan="7">
        <?=htmlEdit('reason',$info['reason'])?>
    </td>
</tr>
<tr>
	<td class="N_title">部门主管审核：</td>
	<td class="N_title" colspan="7">
	    <?
			$tag = ($_SESSION ['role'] == '2' || ($_SESSION ['role'] == '1' && $userinfo ['depId'] == $topDepId)) && $info ['depTag'] == '0' ? '' : 'disabled';
			if ($info ['depTag'] == '0')
				$showtag = false;
		?>
        <select name="depTag" <?php echo $tag;?> onchange="shows()" id="depTag">
			<option value="2" <?php echo $info['depTag']=='2'?'selected':'' ?>>通过</option>
			<option value="1" <?php echo $info['depTag']=='1'?'selected':'' ?>>不通过</option>
			<option value="0" <?php echo $info['depTag']=='0'?'selected':'' ?>>未审核</option>
		</select>&nbsp;
		<?php if($n>=0 &&$info['available']=='1'&&($_SESSION['role'] == '2' || ($_SESSION['role'] == '1' && $userinfo['depId']==$topDepId))&&$info['depTag']!='0'){ ?>
			<a href="javascript:;" onclick="voidCancle('<?=$className?>','<?=$info['id']?>','dep')">撤销</a> 
		<?php }?>&nbsp;
		<?php echo $info['depTime']?>
      </td>
</tr>
<tr style="display: none;" id="depshow">
	<td class="N_title">不通过理由：</td>
	<td class="N_title" colspan="7">
    	<?=htmlEdit('noPassDep',$info['noPassDep'])?>
    </td>
</tr>
<tr>
	<td class="N_title">人事审核：</td>
	<td class="N_title" colspan="7">
	    <?
			$tag = $_SESSION ['ADMIN_ID'] == $personnelId && $info ['perTag'] == '0' && $showtag ? '' : 'disabled';
			if ($info ['perTag'] == '0')
				$showtag = false;
		?>
        <select name="perTag" <?php echo $tag;?> onchange="shows()" id="perTag">
			<option value="2" <?php echo $info['perTag']=='2'?'selected':'' ?>>通过</option>
			<option value="1" <?php echo $info['perTag']=='1'?'selected':'' ?>>不通过</option>
			<option value="0" <?php echo $info['perTag']=='0'?'selected':'' ?>>未审核</option>
		</select>&nbsp;
		<?php if($n>=0 &&$info['available']=='1'&&$_SESSION['ADMIN_ID'] == $personnelId&&$info['perTag']!='0'){ ?>
			<a href="javascript:;" onclick="voidCancle('<?=$className?>','<?=$info['id']?>','per')">撤销</a>
		<?php }?>&nbsp;
		<?php echo $info['perTime']?>
      </td>
</tr>
<tr style="display: none;" id="pershow">
	<td class="N_title">不通过理由：</td>
	<td class="N_title" colspan="7">
    	<?=htmlEdit('noPassPer',$info['noPassPer'])?>
    </td>
</tr>
<tr>
	<td class="N_title">总经理：</td>
	<td class="N_title" colspan="7">
		<select name="manTag" <?php echo $_SESSION['role'] == '1'&&$info['manTag']=='0'&&$showtag?'':'disabled';?> onchange="shows()" id="manTag">
			<option value="2" <?php echo $info['manTag']=='2'?'selected':'' ?>>通过</option>
			<option value="1" <?php echo $info['manTag']=='1'?'selected':'' ?>>不通过</option>
			<option value="0" <?php echo $info['manTag']=='0'?'selected':'' ?>>未审核</option>
		</select>&nbsp;
		<?php if($n>=0 &&$info['available']=='1'&&$_SESSION['role'] == '1'&&$info['manTag']!='0'){ ?>
			<a href="javascript:;" onclick="voidCancle('<?=$className?>','<?=$info['id']?>','man')">撤销</a>
		<?php }?>&nbsp;
		<?php echo $info['manTime']?>
      </td>
</tr>
<tr style="display: none;" id="manshow">
	<td class="N_title">不通过理由：</td>
	<td class="N_title" colspan="7">
    	<?=htmlEdit('noPassMan',$info['noPassMan'])?>
    </td>
</tr>
<tr>
	<td class="N_title">门禁时间：</td>
	<td class="N_title" colspan="7">
		<?
			// 显示相关打卡时间
			seartime ( $info ['supdate'], $info ['supdate'], $info ['uid'] );
		?>
    </td>
</tr>
<tr>
	<td class="N_title">指纹时间：</td>
	<td class="N_title" colspan="7">
		<?
			// 显示相关打卡时间
			searzhiwentime ( $info ['supdate'], $info ['supdate'], $info ['uid'] );
			echo "注:s后缀表异常时间";
		?>
    </td>
</tr>
<?php
} else {
?>
<tr>
	<td class="N_title">日期：</td>
	<td class="N_title" colspan="7">
		<input type="text" size="20" value="<?=date("Y-m-d")?>" name="addDate" readonly>
	</td>
</tr>
<tr>
	<td class="N_title">申请人：</td>
	<td class="N_title" colspan="7">
		<input type="text" size="20" value="<?=$userinfo['real_name']?>" readonly>
		<input type="hidden" name="uid" value="<?=$uid?>">
	</td>
</tr>
<tr>
	<td class="N_title">部门：</td>
	<td class="N_title" colspan="7">
		<input type="text" size="20" value="<?=$depname?>" readonly>
		<input type="hidden" name="depId" value="<?=$userinfo['depId']?>">
	</td>
</tr>
<tr>
	<td class="N_title">时间段：</td>
	<td class="N_title" colspan="7">
		日期:<input type="text" name="supdate" size="10" id="date_s" readonly><br> &nbsp;&nbsp;
		早上上班:<input type="checkbox" name="ck" value="amstart" class="tp" onclick="change_status(this)">
		<input type="text" name="amstart" id="amstart" value="09:00" maxlength="5" size="5" disabled><br>&nbsp;&nbsp;
		早上下班:<input type="checkbox" name="ck" value="amend" class="tp" onclick="change_status(this)">
		<input type="text" name="amend" id="amend" value="12:00" maxlength="5" size="5" disabled><br>&nbsp;&nbsp;
		下午上班:<input type="checkbox" name="ck" value="pmstart" class="tp" onclick="change_status(this)">
		<input type="text" name="pmstart" id="pmstart" value="13:30" maxlength="5" size="5" disabled><br> &nbsp;&nbsp;
		下午下班:<input type="checkbox" name="ck" value="pmend" class="tp" onclick="change_status(this)">
		<input type="text" name="pmend" id="pmend" value="18:30" maxlength="5" size="5" disabled>
		<input type="hidden" name="nowTime" id="nowTime" value="<?=date('Y-m-d H:i:s')?>"> <br />
		<font color="red">&nbsp;&nbsp;<?php echo C_LANG ?></font>
	</td>
</tr>
<tr>
	<td class="N_title">原由：</td>
	<td class="N_title" colspan="7">
		<?=htmlEdit('reason',$info['reason'])?>
	</td>
</tr>
<?php
}
?>
<script>
function checkForm(c_date){
    var msg='请输入原由';
    var left_time_start = $('#date_s').val()+" 00:00:00";
    var nowTime = $('#nowTime').val();

    if('<?=$_REQUEST['id']?>'==''){//填写申请单
    	var n = 0;
        $(".tp").each(function(){
            if($(this).attr('checked')==true){
                n++;    
			}
        });
		if(n<1){
			alert("请填至少一个时间点");
            return false;
        }
        if($('#date_s').val()==''){
			alert('请将时间补充完整');
            return false;
        }
        //10080 一周分钟数
        var   now = nowTime.split(" ");
        var l = datecompare(left_time_start, now[0]+" 00:00:00"); //不能写当天的异常单
        if(l<=0){  
            alert("日期有误，请修改");
            return false;
        }
        var   now1=now[0].split("-");
        var   now2=now[1].split(":");
        var   nowdate=new Date(now1[0],now1[1]-1,now1[2],now2[0],now2[1],now2[2]);
        var   monthtd = new Date(now1[0],now1[1]-1,'01','00','00','00');//当月第一天
        var   monthpd = new Date(now1[0],now1[1]-2,'01','00','00','00');//上个月第一天
        if(nowdate.getDate()>c_date){//5
            var mn1 = monthtd.getFullYear()+"-"+(monthtd.getMonth()+1)+"-"+monthtd.getDate()+" 00:00:00";
            if(datecompare(mn1,left_time_start)<0){    
                alert('超出时间期限，请重选');
                return false;
            }
       	} else {
            var mn2 = monthpd.getFullYear()+"-"+(monthpd.getMonth()+1)+"-"+monthpd.getDate()+" 00:00:00";
            if(datecompare(mn2,left_time_start)<0){
                alert('超出时间期限，请重选');
                return false;
            }
        }

        if(GetContents('reason')==''){
            alert(msg);
            return false;
        }
    }
	return true;
}

function datecompare(date1, date2){
	var   arr=date1.split(" ");
	var   arr1=arr[0].split("-");
    var   arr2=arr[1].split(":");
    var   date=new Date(arr1[0],arr1[1]-1,arr1[2],arr2[0],arr2[1],arr2[2]);
    var   arr=date2.split(" ");
    var   arr1=arr[0].split("-");
    var   arr2=arr[1].split(":");
    var   date2=new Date(arr1[0],arr1[1]-1,arr1[2],arr2[0],arr2[1],arr2[2]);
    var df=(date2.getTime()-date.getTime())/(60*1000);
    return df;
}

function change_status(val){
    var name = val.value;
    if(val.checked==true)
    $("#"+name).attr("disabled",false);
    else
    $("#"+name).attr("disabled",true);
}
</script>