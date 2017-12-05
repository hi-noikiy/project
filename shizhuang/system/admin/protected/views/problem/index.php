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
                    <th>类型</th>
                    <th>手机号</th>
                    <th>服务器</th>
                    <th>角色名称</th>
                    <th>描述</th>

                    <th>手机型号</th>
                    <th>操作系统</th>
                    <th>录入时间</th>
                    <th style="width:50px;text-align:center">查看</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($info as $v){ ?>
                    <tr>
                        <td><?=$v->id; ?></td>
                        <td><?=$v->type == 0 ? '问题反馈':'意见收集';?></td>
                        <td><?=$v->phone; ?></td>
                        <td><?=$v->server_id; ?></td>
                        <td><?=$v->username; ?></td>
                        <td><?=$v->desc; ?></td>

                        <td><?=$v->model; ?></td>
                        <td><?=$v->system; ?></td>
                        <td><?=date('Y-m-d H:i:s', $v->addtime); ?></td>
                        <td style="text-align:center"><a href="<?=$this->createUrl('problem/update/id/'.$v->id); ?>"  title="修改"><i class="icon-edit"></i>查看</a></td>
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
                    <input type="text" placeholder="角色名称" name="username" value="<?=isset($_POST['username'])  ? $_POST['username'] : '';?>" class="input-small">

                    <input type="text" placeholder="手机号" name="phone" value="<?=isset($_POST['phone'])  ? $_POST['phone'] : '';?>" class="input-medium">

                    <select name="type" class="input-small">
                        <option value="">类型</option>
                        <option value="0" <?=(isset($_POST['type']) && $_POST['type'] == 0) ? 'selected': '';?>>问题反馈</option>
                        <option value="1" <?=(isset($_POST['type']) && $_POST['type'] == 1) ? 'selected': '';?>>意见收集</option>
                    </select>

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