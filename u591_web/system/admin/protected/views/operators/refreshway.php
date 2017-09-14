<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">

        <div id="legend" class="">
            <legend><?=$title?></legend>
        </div>
        <?=CHtml::beginForm('', 'POST', array('class'=>
				'form-inline',
				'id'=>"forminventorysupplier",
         		'accept-charset'=>'utf-8',	
			));?>   
            <table class="table table-hover">
				 <tr>
                        <td>                 
                          	<input type="text" placeholder="区服id" name="serverId" required="required">               
                            <button type="submit" class="btn btn-primary input-small" title="刷新"><i class="icon-search"></i> 刷新</button>
                       	</td>
                    </tr>
            </table>
        	<?=CHtml::endForm(); ?>
    </div>
</body>
</html>