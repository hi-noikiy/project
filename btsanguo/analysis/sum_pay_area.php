<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 充值区间
* ==============================================
* @date: 2016-3-28
* @author: luoxue
* @version:
*/
include 'header.php';
include 'inc/function.php';
$CPID = intval($_GET['CPID']);
$dis = new PayNew($db_sum, $bt, $et, $serverids, $CPID);
$data = $dis->payArea();
?>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <?php include 'inc/search_form_pay.inc.php'; ?>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>额度</th>
                                <th>金额</th>
                                <th>数量</th>
                             
                            </tr>
                            </thead>
                            <tbody>
                            <? $total=$count = 0?>
							<?php foreach($data['list'] as  $k => $v):?>
								<?php 
									$total+= $v['sum_money']; 
									$count += $v['sum_count'];
								?>
                                <tr>
                                    <td><?=$k?></td>
                                    <td><?=$v['sum_money']?></td>
                                    <td><?=$v['sum_count']?></td>
                                </tr>
                            <?php endforeach; ?>
								<tr>
                                    <td>汇总</td>
                                    <td><?=$total?></td>
                                    <td><?=$count?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
				<div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>额度</th>
                                <th>金额</th>
                                <th>数量</th>
                             
                            </tr>
                            </thead>
                            <tbody>
                            <? $total=$count = 0?>
							<?php foreach($data['list2'] as  $k => $v):?>
								<?php 
									$total+= $v['sum_money']; 
									$count += $v['sum_count'];
								?>
                                <tr>
                                    <td><?=$k?></td>
                                    <td><?=$v['sum_money']?></td>
                                    <td><?=$v['sum_count']?></td>
                                </tr>
                            <?php endforeach; ?>
								<tr>
                                    <td>汇总</td>
                                    <td><?=$total?></td>
                                    <td><?=$count?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
				<div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>单笔充值额度</th>
                                <th>金额</th>
                                <th>数量</th>
                             
                            </tr>
                            </thead>
                            <tbody>
                            <? $total=$count = 0?>
							<?php foreach($data['list4'] as  $k => $v):?>
								<?php 
									$total+= $v['sum_money']; 
									$count += $v['sum_count'];
								?>
                                <tr>
                                    <td><?=$k?></td>
                                    <td><?=$v['sum_money']?></td>
                                    <td><?=$v['sum_count']?></td>
                                </tr>
                            <?php endforeach; ?>
								<tr>
                                    <td>汇总</td>
                                    <td><?=$total?></td>
                                    <td><?=$count?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
				<div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>IOS额度</th>
                                <th>金额</th>
                                <th>笔数</th>
                             
                            </tr>
                            </thead>
                            <tbody>
                            <? $total=$count = 0?>
							<?php foreach($data['list3'] as  $k => $v):?>
								<?php 
									$total+= $v['sum_money']; 
									$count += $v['sum_count'];
								?>
                                <tr>
                                    <td><?=$k?></td>
                                    <td><?=$v['sum_money']?></td>
                                    <td><?=$v['sum_count']?></td>
                                </tr>
                            <?php endforeach; ?>
								<tr>
                                    <td>汇总</td>
                                    <td><?=$total?></td>
                                    <td><?=$count?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
<?php include 'footer.php';?>