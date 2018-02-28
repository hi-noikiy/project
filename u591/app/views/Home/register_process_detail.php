<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2><?php echo $type_title?></h2>
                </div>
                <div class="table-responsive">
                     <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>小时</th>
                            <th>点击数</th>
                        </tr>
                        </thead>
                        <tbody id="dataTable">
                        <?php foreach ($data as $hour=>$cnt):?>
                            <tr>
                                <td><?=$hour?></td>
                                <td><?=$cnt?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                     </table>
                </div>
            </div>
        </div>
    </div>
</section>
