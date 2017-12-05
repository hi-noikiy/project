<div class="page">
	<div class="pageContent pageForm">
		<div class="pageFormContent" layoutH="58">
		<table class="list">
		 <tr class="row" >
		  <th colspan="3" class="space">系统信息</th>
		 </tr>
		 <?php foreach ($info as $k=>$v) { ?>
		 <tr class="row" ><td width="15%"><?php echo $k; ?></td><td><?php echo $v; ?></td></tr>
		 <?php } ?>
		</table>
		</div>
		<div class="formBar">
			<ul>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">关闭</button></div></div></li>
			</ul>
		</div>
	</div>
</div>