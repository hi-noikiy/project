<div class="row">
    <div class="col-lg-12">
        <p><?php echo anchor('system/create_game', '添加新游戏')?> </p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>添加时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($games as $game):?>
                    <tr>
                        <td><?php echo $game->id;?></td>
                        <td><?php echo $game->name?></td>
                        <td><?php echo $game->created_at?></td>
                        <td>
                            <a href="javascript:;"
                               class="rm btn btn-danger"
                               data-id="<?php echo $game->id;?>">
                                <i class="fa fa-fw fa-trash"></i> 删除
                            </a>

                            <?php echo anchor('system/game/'.$game->id,
                                '<i class="fa fa-fw fa-info"></i>详情',
                                [
                                'class'=>'btn btn-primary',
                                'target'=>'_blank'
                            ])?>

                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>