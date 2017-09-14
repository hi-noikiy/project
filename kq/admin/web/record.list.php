<div class="search">
  	<p style="margin: 5px 0;">打卡查询</p>
	<form action="index.php" method="get">
	  	<span>时间:</span>
	  	<input name="fromTime" type="text" size="10" id="date_s" value="<?=$fromTime?>" readonly>
		<span>到:</span>
		<input name="toTime" id="date_e" type="text" size="10" value="<?=$toTime?>" readonly>
        <?
        	$admin = new admin();
            $sear = $admin->getInfo($_SESSION['ADMIN_ID'],'seartag','pass');
            $is_sear=mysql_fetch_assoc(mysql_query("select id from _sys_group_perm where perm_id='77' and admin_id='".$_SESSION['ADMIN_ID']."'"));
        ?>
        <? if($sear=='1'||$is_sear['id']){?>             
	        <span>部门:</span>
		  	<select name="depId" id="departmentSelect">
		  		<option value=''>请选择...</option>
		    	<? foreach ($depList as $v){?>
					<option value='<?=$v['id'] ?>' <? if($depId==$v['id']){ echo 'selected';} ?>><?=$v['name']?></option>
				<? }?>
			</select>
			<span>姓名:</span> 
		    <select name="uid" id="uidSelect">
		        <option value=''>请选择...</option>
		       	<? foreach ($userList as $v){ ?>
		        	<option value='<?=$v['id']?>' <? if($uid==$v['id']){ echo 'selected';} ?>><?=$v['real_name'] ?></option>
		        <? } ?>
			</select>             
        <? }?>
        <input name="type" type="hidden" value="<?=$_GET['type']?>" size="20">
        <input name="do" type="hidden" value="list" size="20">
        <input name="cn" type="hidden" value="<?=$className?>" size="20">
        <input name="sear" type="hidden" value="sear"/>
		<input type="submit" value="搜索" class="sub2">
        <p style="margin-top:5px;">注：上班时间指：工作日期间，在公司上班的时间（按指纹统计,不包括加班时间）。缺失打卡记录的，须先通过申请异常单补充打卡记录。s后缀表异常单时间。</p>
	</form>
</div>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" class="T_title">日期</th>
      <th scope="col" class="T_title">姓名</th>
      <th scope="col" >门禁卡</th>
      <th scope="col" >指纹卡</th>
      <th scope="col" >上班时间</th>
      <th scope="col" >总上班时间</th>
    </tr>
    <?foreach($list as $val){?>
    <tr class="Ls2">
        <td class="N_title"><?=$val['recorddate']?></td>
        <td class="N_title">
        <?
            $ad = new admin();
            $ad->setWhere("card_id='".$val['card_id']."'");
            $name = $ad->getArray('pass');

            echo $name[0]['real_name']?$name[0]['real_name']:"迎宾卡";
        ?>
        </td>
        <td>
            <?
                $val['addtime'] = preg_replace('/\[\d{2}:\d{2}:\d{2}\s\[进门2\]\]\s/i','',$val['addtime']);
                $val['addtime'] = preg_replace('/\s\[\d{2}:\d{2}:\d{2}\s\[进门2\]\]/i','',$val['addtime']);
                $val['addtime'] = preg_replace('/\[\d{2}:\d{2}:\d{2}\s\[进门2\]\]/i','',$val['addtime']);
                $ad = explode(' ',$val['addtime']);
                //print_r($ad);
                $i=0;
                foreach($ad as $v)
                {
                    $i++;
                    echo $v;
                    if($i%4=='0')
                    echo "<br>";
                }
            ?>
        </td>
        <td><?=$val['addtime_ex']?$val['addtime_ex']:"&nbsp;"?></td>
        <td><?=floor($val['totaltime']/60)."小时".($val['totaltime']%60)."分钟"?></td>
        <td><?=floor($val['totalall']/60)."小时".($val['totalall']%60)."分钟"?></td>
    </tr>
    <?}?>
  </table>