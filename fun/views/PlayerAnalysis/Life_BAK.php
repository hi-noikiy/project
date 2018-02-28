<section id="content">
    <div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="row">
                    <form action="" method="get">
                    <!--<div class="col-sm-3">-->
                    <!--    <div class="form-group">-->
                    <!--        <div class="fg-line">-->
                    <!--            <input type="text" name="t1"-->
                    <!--                   class="form-control date-picker"-->
                    <!--                   placeholder="查询开始时间">-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="col-sm-3">-->
                    <!--    <div class="form-group">-->
                    <!--        <div class="fg-line">-->
                    <!--            <input type="text" name="t2"-->
                    <!--                   class="form-control date-picker"-->
                    <!--                   placeholder="查询结束时间">-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-sm-3">
                        <div class="form-group">
                            <div class="fg-line">
                                <input type="text" name="accountid" class="form-control" placeholder="账号ID">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" id="submit" class="btn btn-primary btn-sm m-t-10 waves-effect">查询</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                            <tr>
                                <th data-column-id="id" >ID</th>
                                <th>VIP 等级</th>
                                <th>等级</th>
                                <th>角色名</th>
                                <th>首登时间</th>
                                <th>末登时间</th>
                                <th>总在线天数</th>
                                <th>总登录次数</th>
                                <th>总在线时长</th>
                                <th>首付时间</th>
                                <th>末付时间</th>
                                <th>总付费金额</th>
                                <th>注册IP</th>
                                <th>最后登录IP</th>
                                <th>渠道</th>
                                <th>serverID</th>
                                <th>设备</th>
                            </tr>
                        </thead>
                        <tbody id="dataTable">
                            <?php if (count($data)>0): ?>
                                <?php //print_r($data);exit;?>
                            <?php foreach($data as $list):?>
                                <tr>
                                    <td><?php echo $list['accountid']?></td>
                                    <td><?php echo $list['viplev']?></td>
                                    <td><?php echo $list['lev']?></td>
                                    <td><?php echo $list['user_name']?></td>
                                    <td><?php echo $list['first_logindate']?></td>
                                    <td><?php echo $list['last_logindate']?></td>
                                    <td><?php echo $list['online_days']?></td>
                                    <td><?php echo $list['login_times']?></td>
                                    <td><?php echo $list['online_time'] / 60?>（分钟）</td>
                                    <td><?php echo $list['first_paytime']?></td>
                                    <td><?php echo $list['last_paytime']?></td>
                                    <td><?php echo $list['total_pay']?></td>
                                    <td><?php echo $list['reg_ip']?></td>
                                    <td><?php echo $list['last_ip']?></td>
                                    <td><?php echo $list['channel']?></td>
                                    <td><?php echo $list['serverid']?></td>
                                    <td><?php echo $list['client'].'<br/>('.$list['mac'].')'?></td>
                                </tr>
                            <?php endforeach;?>
                            <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?=base_url()?>public/ma/js/player_analysis_data.js"></script>