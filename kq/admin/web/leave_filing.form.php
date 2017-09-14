<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 报备添加 编辑
* ==============================================
* @date: 2015-5-25
* @author: Administrator
* @return:
*/
$showtag =true;
$user =new user();
$uid='';
if($_GET['id']){
    $userinfo = $user->getUser($info['uid']);
    $uid = $info['uid'];
    $depUser=$user->getDepertmentUser($info['depId']);
} else {
	$userinfo = $user->getUser($_SESSION['ADMIN_ID']);
    $uid = $_SESSION['ADMIN_ID'];
}
$dep = new department();
$depname = $dep->getInfo($userinfo['depId'],'name','pass');
$depList=$dep->getArray();
?>
<input type="hidden"  size="20" value="<?=date("Y-m-d")?>" name="addDate">
<tr>
	<td class="N_title">部门：</td>
    <td class="N_title" colspan="7"> 	
    	<select name="depId" id="departmentSelect">
			<option value=''>请选择...</option>
            <?php 
            	foreach ($depList as $v){
					echo "<option value='{$v['id']}'>{$v['name']}</option>";
            	}
            ?>
		</select>
	</td>            
</tr>
<tr>
	<td class="N_title">姓名：</td>
	<td class="N_title" colspan="7">
		<select name="uid" id="uidSelect">
        	<option value=''>请选择...</option>
            <?php 
            	if (isset($_GET['id'])){
	                foreach ($depUser as $v){
	                	echo "<option value='{$v['id']}'>{$v['real_name']}</option>";
	                }	
				}	 
             ?>
		</select>
	</td>
</tr>
<tr>
	<td class="N_title">时间：</td>
    <td class="N_title" colspan="7">
		从：<input type="text" name="fromTime" size="10" id="date_s" onchange="totaltime();"  readonly> <?php addTime('s');?>
		到：<input type="text" name="toTime" size="10" id="date_e" onchange="totaltime();"  readonly><?php addTime('e');?>
		<input type="hidden" name="nowTime" id="nowTime" value="<?=date('Y-m-d H:i:s')?>"><br />
		<font color="red">&nbsp;&nbsp;<?php echo C_LANG ?></font>
    </td>
</tr>
<? if (empty($_GET['id'])) { ?>
<tr>
	<td class="N_title">是否列表生成：</td>
	<td class="N_title" colspan="7">
		<input type="checkbox" name="isKey" id="isKey" value="1" />
	</td>
</tr>
<? } ?>
<tr>
	<td class="N_title">合计：</td><td class="N_title" colspan="7">
		<input type="text" name="totalTime" size="10" id="totalTime" readonly>小时
	</td>
</tr>
<tr>
	<td class="N_title">事由：</td><td class="N_title" colspan="7">
		<?=htmlEdit('reason',$info['reason'])?>
    </td>
</tr>
<script>
$(function(){
	var $department=$("#departmentSelect");
	var $uidSelect=$("#uidSelect");
	var $isKey=$("#isKey");
	$isKey.click(function(){
		if($(this).attr("checked")==true){
			$(this).parents("tr").next().hide();
		}else{
			$(this).parents("tr").next().show();
		}
	});
	$department.change(function(){
		var depId=$(this).val();
		$.ajax( { 
		    url:'ajax/getUser.php',  
		    data:{depId:depId},    
		    type:'post',    
		    cache:false,    
		    dataType:'json',    
		    success:function(data) {
			    var str="<option value=''>请选择...</option>";
			    for(var i=0; i<data.length; i++){
					str+="<option value='"+data[i].id+"'>"+data[i].real_name+"</option>";
				}
			    $uidSelect.html(str);
		     },    
		     error : function() {
		          alert("异常！");    
		     }    
		});  
	});
});
function checkForm(c_date){
	var msg='请输入事由';
	var $departmentSelect=$("#departmentSelect");
	var $uidSelect=$("#uidSelect");
	if($departmentSelect.val()==''){
		alert('请选择部门！');
		return false;
	}
	if($uidSelect.val()==''){
		alert('请选择姓名！');
		return false;
	}
	
    var left_time_start = $('#date_s').val()+' '+$('#hour_s').val()+':'+$('#minute_s').val()+":00";
    var left_time_end = $('#date_e').val()+' '+$('#hour_e').val()+':'+$('#minute_e').val()+":00";
    var left_time_dure = datecompare(left_time_start,left_time_end);
    var nowTime = $('#nowTime').val();
    if($('#date_s').val()==''|| $('#date_e').val()==''){
    	alert('请将时间补充完整');
        return false;
    }
    var   now = nowTime.split(" ");
    var   now1=now[0].split("-");
    var   now2=now[1].split(":");
    var   nowdate=new Date(now1[0],now1[1]-1,now1[2],now2[0],now2[1],now2[2]);
    var   monthtd = new Date(now1[0],now1[1]-1,'01','00','00','00');//当月第一天
    var   monthpd = new Date(now1[0],now1[1]-2,'01','00','00','00');//上个月第一天
   /*  if(nowdate.getDate()>c_date){//5	
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
    } */
    if(left_time_dure<0){
        alert('时间先小后大，请重填');
        return false;
    }
    /* if(GetContents('reason')=='') {
    	alert(msg);
        return false;
    } */
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
     var startDate= new Date(date);
     var endDate= new Date(date2);
     var df=(endDate.getTime()-startDate.getTime())/(60*1000);
     return df;
}
</script>