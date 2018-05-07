<?php
$showtag = true;
$user = new user();
$uid='';
if($_GET['id']){
    $userinfo = $user->getUser($info['uid']);
    $uid = $info['uid'];
}else{
    $userinfo = $user->getUser($_SESSION['ADMIN_ID']);
    $uid = $_SESSION['ADMIN_ID'];
}
$dep = new department();
$depname = $dep->getInfo($userinfo['depId'],'name','pass');
?>
<?php
if($_GET['id']){
    $strs = acTime();
    //$n =  strtotime($info['fromTime'].' '.$info['hour_s'].':'.$info['minute_s'].':00')-$strs;//时间差
    //撤销条件 1，5号前前一个月即n>=0,否则当月 2，本人 3，状态必须是可作废
    $n = 1;
?>
	<tr>
	   <td class="N_title">日期：</td>
	   <td class="N_title" colspan="7">
		   <input type="text"  size="20" value="<?=date("Y-m-d")?>" name="addDate" readonly>
	   <td>
	</tr>
	<tr>
	    <td class="N_title">申请人：</td>
	    <td class="N_title" colspan="7">	
	        <input type="text"  size="20" value="<?=$userinfo['real_name']?>" readonly>
	        <input type="hidden" name="uid" value="<?=$uid?>">
	    </td>
	</tr>
	<tr>
		<td class="N_title">部门：</td>
		<td class="N_title" colspan="7">
			<input type="text"  size="20" value="<?=$depname?>" readonly>
		    <input type="hidden" name="depId" value="<?=$userinfo['depId']?>">	          
		</td>      
	</tr>
	<tr>
		<td class="N_title">加班时间：</td>      
	    <td class="N_title" colspan="7">
			从：
			<input type="text" name="fromTime" size="10"  readonly> 
			<?php addTime('s','disabled',$info['hour_s'],$info['minute_s']);?>
			到：
	        <input type="text" name="toTime" size="10"  readonly>
	        <?php addTime('e','disabled',$info['hour_s'],$info['minute_s']);?>
	        <br>
	        <?php
	            $admin = new admin();
	            $admininfo = $admin->getInfo($info['uid'], '', 'pass');
	            echo '&nbsp;&nbsp;<font color="red">已加班时间:'.number_format($admininfo['totalOverTime']/60,1)."小时</font>";
	        ?>
		</td>
	</tr>    
    <tr>
      <td class="N_title">合计：</td>
      <td class="N_title" colspan="7">
        <input type="text" name="totalTime" size="10"  readonly>小时
      </td>
    </tr>
    <tr>
      <td class="N_title">事由：</td>
      <td class="N_title" colspan="7">
        <?=htmlEdit1('reason',$info['reason'])?>
      </td>
    </tr>
    <tr>
      <td class="N_title">部门主管审核：</td>
      <td class="N_title" colspan="7">
          <?
              $tag = ($_SESSION['role'] == '2' || ($_SESSION['role'] == '1' && $userinfo['depId']==$topDepId))&&$info['depTag']=='0'?'':'disabled';
              if($info['depTag']=='0')$showtag=false;
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
    <tr style="display:none;" id="depshow">
      <td class="N_title">不通过理由：</td>
      <td class="N_title" colspan="7">
      	<?=htmlEdit('noPassDep',$info['noPassDep'])?>
      </td>
    </tr>
    <tr>  	
      	<td class="N_title">人事审核：</td>
      	<td class="N_title" colspan="7">
          	<?
              	$tag = $_SESSION['ADMIN_ID'] == $personnelId&&$info['perTag']=='0'&&$showtag?'':'disabled';
              	if($info['perTag']=='0')$showtag=false;
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
    <tr style="display:none;" id="pershow">
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
    <tr style="display:none;" id="manshow">
		<td class="N_title">不通过理由：</td>
        <td class="N_title" colspan="7">
        	<?=htmlEdit('noPassMan',$info['noPassMan'])?>
        </td>              
   </tr>
   <tr>
       <td class="N_title">门禁时间：</td>
       <td class="N_title" colspan="7">
	       <?
	        	//显示相关打卡时间
	            seartime($info['fromTime'],$info['toTime'],$info['uid']);
	        ?>
        </td>
    </tr>
    <tr>
    	
       	<td class="N_title">指纹时间：</td>
       	<td class="N_title" colspan="7">
	       	<?
	        	//显示相关打卡时间
	            searzhiwentime($info['fromTime'],$info['toTime'],$info['uid']);
	            echo "注:s后缀表异常时间";
	        ?>
        </td>
    </tr>
<?php
}else{
?>
	<tr>
    	<td class="N_title">日期：</td>
    	<td class="N_title" colspan="7">
        	<input type="text"  size="20" value="<?=date("Y-m-d")?>" name="addDate"  readonly>
        </td>
    </tr>
    <tr>
    	<td class="N_title">申请人：</td>
    	<td class="N_title" colspan="7">
        	<input type="text"  size="20" value="<?=$userinfo['real_name']?>" readonly>
            <input type="hidden" name="uid" value="<?=$uid?>">
        </td>
    </tr>
    <tr>
    	<td class="N_title">部门：</td>
    	<td class="N_title" colspan="7">
        	<input type="text"  size="20" value="<?=$depname?>" readonly >
            <input type="hidden" name="depId" value="<?=$userinfo['depId']?>">
        </td>
    </tr>
    <tr>
    	<td class="N_title">加班时间：</td>
    	<td class="N_title" colspan="7">
			从：
            <input type="text" name="fromTime" size="10" id="date_s" onchange="totaltime();"  readonly>
            <?php addTime('s');?>
			到：
        	<input type="text" name="toTime" size="10" id="date_e" onchange="totaltime();"  readonly>
        	<?php addTime('e');?>
        	<input type="hidden" name="nowTime" id="nowTime" value="<?=date('Y-m-d H:i:s')?>">
        	<br />
        	<font color="red">&nbsp;&nbsp;<?php echo C_LANG ?></font>
         </td>
     </tr>  
     <tr>		
         <td class="N_title">合计：</td><td class="N_title" colspan="7">
            <input type="text" name="totalTime" size="10" id="totalTime" readonly>小时
         </td>
     </tr>
     <tr>
         <td class="N_title">事由：</td><td class="N_title" colspan="7">
         	<?=htmlEdit1('reason',$info['reason'])?>
         </td>
     </tr>
<?php
}
?>
<script type="text/javascript">
check_info_result = false;
function checkForm(c_date,class_name){
	var msg='请输入事由';
    var left_time_start = $('#date_s').val()+' '+$('#hour_s').val()+':'+$('#minute_s').val()+":00";
    var left_time_end = $('#date_e').val()+' '+$('#hour_e').val()+':'+$('#minute_e').val()+":00";
    var dep = $('#depTag').val();
    var per = $('#perTag').val();
    var man = $('#manTag').val();

    var left_time_dure = datecompare(left_time_start,left_time_end);
    var nowTime = $('#nowTime').val();
    //审批阶段调用
    if('<?=$_REQUEST['id']?>'!='' && dep!='1' && per!='1' && man!='1'){
    	return timeCheck(<?=$_REQUEST['id']?>);
    }
    //填写申请单
    if('<?=$_REQUEST['id']?>'==''){
        if($('#date_s').val()==''|| $('#date_e').val()==''){    
            alert('请将时间补充完整');
            return false;
        }
        //10080 一周分钟数
        var   now = nowTime.split(" ");
        var   now1=now[0].split("-");
        var   now2=now[1].split(":");
        var   nowdate=new Date(now1[0],now1[1]-1,now1[2],now2[0],now2[1],now2[2]);
        var   monthtd = new Date(now1[0],now1[1]-1,'01','00','00','00');//当月第一天
        var   monthpd = new Date(now1[0],now1[1]-2,'01','00','00','00');//上个月第一天
        if(left_time_dure<=0){
            alert('时间先小后大，请重填');
            return false;
        }
        var   time_start = left_time_start.split(" ");
        var   time_start1=time_start[0].split("-");
        var   time_start2=time_start[1].split(":");
        var   time_start_date=new Date(time_start1[0],time_start1[1]-1,time_start1[2],time_start2[0],time_start2[1],time_start2[2]);
    	var   day=time_start_date.getDay();
    	var   time_minute=nowdate.getTime()-time_start_date.getTime();
        var   time = Math.floor(time_minute / (1000 * 60 * 60 * 24));
        var reason = $('#box').html().trim();
        if(reason==''){
            alert('请输入事由');
            return false;
        }
        reason.replace('<', '&lt;');
        reason.replace('>', '&gt;');
        //alert(class_name);
        //这个判断顺序影响程序
        if(class_name!=''){      
            $.ajax({
          		type : "post",
          		url : "ajax/check_info.php?class_name="+class_name+"&time="+left_time_start,
          		data : "action=check"  ,
          		async : false,
				success : function(data){
					if(data=='ok'){ check_info_result = true;}
				}
          	});
         }
         if(check_info_result==true){
         	return true;
         }
         /* if(nowdate.getDate()>c_date){//5
             var mn1 = monthtd.getFullYear()+"-"+(monthtd.getMonth()+1)+"-"+monthtd.getDate()+" 00:00:00";
             if(datecompare(mn1,left_time_start)<0){ 
                 alert('超出时间期限，请重选');
                 return false;
             }
         }else{
             var mn2 = monthpd.getFullYear()+"-"+(monthpd.getMonth()+1)+"-"+monthpd.getDate()+" 00:00:00";
             if(datecompare(mn2,left_time_start)<0){
                 alert('超出时间期限，请重选');
                 return false;
             }
         } */
	}
	return true;
}

function timeCheck(id){
	var t = false;
	$.ajax({
		type: "POST",
	   	url: "timeCheck.php",
	   	data: "id="+id,
        async: false,
	   	success: function(msg){	   	
			if(msg=='no'){
                if(confirm('打卡记录有异常，确定要提交吗？')){
                	t=true;
                }else{
                	t=false;
                }
            }else{
            	t=true;
            }
	   	}
	});
    return t;
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
</script>