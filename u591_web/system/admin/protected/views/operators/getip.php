<?php if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) { ?>
	<?php if(empty($info)){ ?>
	<div class="alert alert-block">
	    <button type="button" class="close" data-dismiss="alert">&times;</button>
	    <h4>Warning!</h4>
	    暂时没有相关数据
	</div>
	<?php }else { ?>
	<div class="container-fluid">
	
	<table class="table table-hover table-striped">
	    <thead>
	        <tr>
	            <th>区服</th>
	       		<th>ip</th>
	       		<th>端口</th>
	       		<th>合服信息</th>
	        </tr>
	    </thead>
	    <tbody>
	       	<?php foreach($info as $v){ ?>
	        <tr>
				<td><?=$v['id']; ?></td>
				<td><?=$v['ip']; ?></td>
				<td><?=$v['port']; ?></td>
				<td><?=$v['idserverlist']; ?></td>
	        </tr>
	       <?php } ?>     
	    </tbody>
	</table>
	</div>
	<?php } ?>
<?php } else { ?>
<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
	    <div class="container-fluid">
   
       <?=CHtml::beginForm('', 'POST', array('class'=>
				'form-inline',
				'id'=>"forminventorysupplier",
         		'accept-charset'=>'utf-8',	
			));?>   
            <table class="table table-hover">
                <tbody>

                    <tr>
                        <td>
                        <select class="input-medium" required="required" name="serverid" >
                        	<option value='11001' >火树</option>
                        	<option value='5001' >p8ios</option>
                        	<option value='15001' >p8安卓</option>
                        	<option value='8001' >混服</option>
                        	<option value='3001' >应用宝</option>
                        	<option value='6001' >硬核</option>
                        	<option value='12001' >深海</option>
                        	<option value='14001' >虎牙</option>
                        	<option value='13001' >创游</option>
                        </select>
                            <button type="submit" class="btn btn-primary input-small" title="查询"><i class="icon-search"></i> 搜索</button>
                            <input type="hidden" id="paginationinput" name="pagination" value="1" />
                            <input type="hidden" id="countinput" name="count" value="<?=$count; ?>" />
                    	</td>
                    </tr>
                </tbody>
            </table>
        <?=CHtml::endForm(); ?>
        
	
		<div id="tablelist">
	<table class="table table-hover table-striped">
	    <thead>
	        <tr>
	            <th>区服</th>
	       		<th>ip</th>
	       		<th>端口</th>
	       		<th>合服信息</th>
	        </tr>
	    </thead>
	    <tbody>
	       	<?php foreach($info as $v){ ?>
	        <tr>
				<td><?=$v['id']; ?></td>
				<td><?=$v['ip']; ?></td>
				<td><?=$v['port']; ?></td>
				<td><?=$v['idserverlist']; ?></td>
	        </tr>
	       <?php } ?>     
	    </tbody>
	</table>
	 	</div>
	    <div class="pagination pagination-centered">
	    	<ul id='pagination'>
	
	        </ul>
	    </div>
    </div>
   <?php $this->renderPartial('/public/js');  ?>
   <script type="text/javascript">	
   		$(document).ready(function() {
	        <?php if (!empty($count)) { ?>
	        $('#pagination').jqPaginator({
	        totalCounts: <?=$count; ?>,
	                pageSize:<?=$pages->pageSize; ?>,
	                currentPage: 1,
	                onPageChange: function(num, type) {
		                $('#paginationinput').val(num);
		                $.post($('#forminventorysupplier').attr('action'), $('#forminventorysupplier').serialize(), function(data) {
		                	$('#tablelist').html(data);
	                 	});
	              	 }
	        });
	        <?php } ?>
        });
    </script>
</body>
</html>
<?php } ?>