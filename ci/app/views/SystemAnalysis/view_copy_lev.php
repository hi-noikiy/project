<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2><?=$title?><small>副本类型:<?=$copy_type?>, 副本名称:<?=$copy_title?></small></h2>
                </div>
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th data-column-id="id" data-type="numeric">时间</th>
                            <th>玩家账号</th>
                            <th>玩家等级</th>
                        </tr>
                        </thead>
                        <tbody id="dataTable">
                            <?php foreach ($data as $item):?>
                                <tr>
                                    <td><?=date('Y-m-d H:i:s', $item['created_at']);?></td>
                                    <td><?=$item['accountid']?></td>
                                    <td><?=$item['lev']?></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
