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
                    <th>编号</th>
                    <th>名称</th>
                    <th>描述</th>
                    <th>批次</th>
                    <th>状态</th>

                    <th style="width:50px;text-align:center">编辑</th>
                    <th style="width:50px;text-align:center">下架</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($info as $v){ ?>
                    <tr>
                        <td><?=$v->id; ?></td>
                        <td><?=$v->name;?></td>
                        <td><?=$v->desc; ?></td>
                        <td><?=$v->used_type; ?></td>
                        <td><?=$v->status == 0 ? '<i class="icon-ok"></i>':'<i class="icon-remove"></i>';?></td>
                        <td style="text-align:center"><a href="<?=$this->createUrl('gift/update/id/'.$v->id); ?>"  title="修改"><i class="icon-edit"></i>编辑</a></td>
                        <td style="text-align:center">
                            <?php
                                if($v->status == 0) {
                            ?>
                            <a href="<?=$this->createUrl('gift/forbid/id/'.$v->id); ?>"  title="下架">
                                <i class="icon-move">下架</i>
                            </a>
                            <?php } else { ?>
                                <a href="<?=$this->createUrl('gift/resume/id/'.$v->id); ?>"  title="上架">
                                    <i class="icon-move">上架</i>
                                </a>
                            <?php } ?>
                        </td>

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
                    <input type="text" placeholder="礼包名称" name="name" value="<?=isset($_POST['name'])  ? $_POST['name'] : '';?>" class="input-medium">
                    <input type="text" placeholder="礼包批次" name="used_type" value="<?=isset($_POST['used_type'])  ? $_POST['used_type'] : '';?>" class="input-medium">

                    <button type="submit" class="btn btn-primary input-small" title="查询"><i class="icon-search"></i> 搜索</button>
                    <input type="hidden" id="paginationinput" name="pagination" value="1" />
                    <input type="hidden" id="countinput" name="count" value="<?=$count; ?>" />
                </td>
            </tr>
            </tbody>
        </table>
        <?=CHtml::endForm(); ?>
        <p>
            <small><i class="icon-info-sign"></i> 查询到了<strong><?=$count; ?></strong>条记录</small>
        </p>
        <p>
            <a href="<?=$this->createUrl('gift/add'); ?>" title="新增" class="btn"><i class="icon-plus"></i> 新增</a>
            <a href="<?=$this->createUrl('gift/upload'); ?>" title="上传" class="btn"><i class="icon-upload"></i> 上传</a>
        </p>

        <div id="tablelist">

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