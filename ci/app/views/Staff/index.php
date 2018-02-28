<section id="content">
    <div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>客服</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($info as $v){ ?>
                        <tr>
                            <td><?php echo $v['email'];?></td>
                            <td><a href="edit?user_id=<?php echo $v['id'];?>">编辑</a></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
