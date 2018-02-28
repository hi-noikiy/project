<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>渠道:[<?php echo $channel_id?>]<?php echo $channel_title?></h2>
                </div>
                <div class="table-responsive">
                     <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>小时</th>
                            <th>设备激活数量</th>
                            <th>注册数量</th>
                            <th title="注册数量/设备激活数量">注册数量占比</th>
                            <th>创建角色数量</th>
                            <th title="创建角色数量/设备激活数量">创建角色数量占比</th>
                            <th title="创建角色人数/注册人数">注册转化率</th>
                            <th title="活跃玩家数">活跃玩家数</th>
                        </tr>
                        </thead>
                        <tbody id="dataTable">
                        <?php foreach ($data as $hour=>$item):?>
                            <tr>
                                <td><?=$hour?></td>
                                <td><?=$item['device']?></td>
                                <td><?=$item['reg']?></td>
                                <td><?=number_format($item['reg_rate'], 2)?>%</td>
                                <td><?=$item['role']?></td>
                                <td><?=number_format($item['role_rate'], 2)?>%</td>
                                <td><?=number_format($item['trans_rate'], 2)?>%</td>
                                <td><?=$item['active']?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                     </table>
                </div>
            </div>
        </div>
    </div>
</section>
