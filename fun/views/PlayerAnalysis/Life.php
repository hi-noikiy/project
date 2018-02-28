<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
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
                                <input type="text" style="padding-left:6px;" name="accountid" value="<?php echo $accountid?>" class="form-control" placeholder="账号ID">
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
                    <table id="data-table-basic2" class="table table-striped">
                        <thead>
                            <tr class="table table-striped">
                                <th>注册IP</th>
                                <th>设备</th>
                                <th>mac</th>
                                <th>首付时间</th>
                                <th>末付时间</th>
                                <th>总付费金额</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $common_data['reg_ip']?></td>
                                <td><?php echo $common_data['client']?></td>
                                <td><?php echo $common_data['mac']?></td>
                                <td><?php echo $common_data['first_paytime']?></td>
                                <td><?php echo $common_data['last_paytime']?></td>
                                <td><?php echo $common_data['total_pay']?></td>
                            </tr>
                        </tbody>
                    </table>
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                            <tr>
                                <th data-column-id="id" >玩家ID</th>
                                <th>VIP 等级</th>
                                <th>等级</th>
                                <th>角色名</th>
                                <th>首登时间</th>
                                <th>末登时间</th>
                                <th>总在线天数</th>
                                <th>总登录次数</th>
                                <th>总在线时长</th>
                                <th>最后登录IP</th>
                                <th>渠道ID</th>
                                <th>服务器ID</th>
                            </tr>
                        </thead>
                        <tbody id="dataTable">
                        <?php if (count($data)>0): ?>
                            <?php //print_r($data);exit;?>
                            <?php foreach($data as $userid=>$list):?>
                                <tr>
                                    <td><?php echo $userid?></td>
                                    <td><?php echo $list['viplev']?></td>
                                    <td><?php echo $list['lev']?></td>
                                    <td><?php echo $list['user_name']?></td>
                                    <td><?php echo $list['first_logindate']?></td>
                                    <td><?php echo $list['last_logindate']?></td>
                                    <td><?php echo $list['online_days']?></td>
                                    <td><?php echo $list['login_times']?></td>
                                    <td><?php echo $list['online_time'] / 60?>（分钟）</td>
                                    <td><?php echo $list['last_ip']?></td>
                                    <td><?php echo $list['channel']?></td>
                                    <td><?php echo $list['serverid']?></td>
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

<!--<script src="--><?//=base_url()?><!--public/ma/js/player_analysis_data.js"></script>-->