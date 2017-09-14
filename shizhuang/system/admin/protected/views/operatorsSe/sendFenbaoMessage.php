<?php $this->renderPartial('/public/header');  ?>
</head>
<body>
    <div class="container-fluid">
        <div id="legend" class="">
            <legend>新增</legend>
        </div>
		<?php echo CHtml::beginForm('','post',array(
				'class'=>'form-horizontal',
				'accept-charset'=>'utf-8',
				'style'=>'margin:0px',
				'onsubmit' => "getElementById('submitButton').disabled=true;return true;",
				));
        ?>     
            <table class="table table-hover">
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">游戏</th>
                    <td>
                        <?=CHtml::dropDownList('game_id', '', $game, array('class'=>'input-medium','id'=>'gameId','required'=>'required')); ?>
                    </td>
                </tr>
                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">区服</th>
                    <td id="serverId"></td>
                </tr>

                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">渠道(单选)</th>
                    <td>
                        <?php
                            $is_blank = CHtml::checkBoxList('dwFenBaoID', '0', $dwfenbaoList,  array('class'=> 'input-medium','separator'=>' '));
                            $is_blank= str_replace("<label", "<span", $is_blank);
                            $is_blank= str_replace("</label", "</span", $is_blank);
                            echo $is_blank;
                        ?>
                    </td>
                </tr>

                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">消息</th>
                    <td>
                        <?=CHtml::textArea('message', '', array('style'=>'width: 300px; height: 80px;', 'required'=>'required'));?>
                    </td>
                </tr>

                <tr>
                    <th style="width:130px;line-height:30px;text-align:right">
                    	<span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span>
                    </th>
                    <td>
                        <button type="submit" id="submitButton" class="btn btn-primary input-small"><i class="icon-save"></i> 生成</button> <button type="reset" class="btn input-small"><i class="icon-refresh"></i> 重置</button> 
                    </td>
                </tr>
            </table>
        <?=CHtml::endForm(); ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>
    <script type="text/javascript">
        $(document).ready(function() {
            var $gameId = $("#gameId");
            var $serverId = $("#serverId");
            $gameId.change(function () {
                var val = $(this).val();
                if (val != 0 && val != '') {
                    $.ajax({
                        type: 'POST',
                        url: "<?=$this->createUrl('ajax/getCheckServerListPre')?>",
                        data: {gameId: val},
                        dataType: 'html',
                        success: function ($data) {
                            $serverId.html($data);
                        },
                    });
                } else {
                    $serverId.html('<option value="">区服</option>');
                }
            });
        });
    </script>
</body>
</html>