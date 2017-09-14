<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-12
 * Time: 上午9:21
 * IOS数据统计——根据渠道统计
 */
include 'header.php';
$noServerFilter = true;
$noFenbaoFilter = true;
$db_ios = db('analysis_ios');
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <?php include 'inc/search_form.inc.php'; ?>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>时间</th>
                                <th>渠道ID</th>
                                <th>注册数</th>
                                <th>创建数</th>
                                <th>创建率</th>
                                <th>DAU</th>
                                <th>WAU</th>
                                <th>MAU</th>
                                <th>新增登录人数</th>
                                <th>充值金额</th>
                                <th>充值人数</th>
                                <th>新增充值人数</th>
                                <th>充值次数</th>
                                <th>付费率</th>
                                <th>充值ARPU</th>
                                <th>注册ARPU</th>
                                <th>次日留存</th>
                                <th>3日留存</th>
                                <th>7日留存</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>时间</td>
                                <td>时间</td>
                                <td>注册数</td>
                                <td>创建数</td>
                                <td>创建率</td>
                                <td>DAU</td>
                                <td>WAU</td>
                                <td>MAU</td>
                                <td>新增登录人数</td>
                                <td>充值金额</td>
                                <td>充值人数</td>
                                <td>新增充值人数</td>
                                <td>充值次数</td>
                                <td>付费率</td>
                                <td>充值ARPU</td>
                                <td>注册ARPU</td>
                                <td>次日留存</td>
                                <td>3日留存</td>
                                <td>7日留存</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <!--                                <td colspan="--><?php //echo $col;?><!--">--><?php //page($total_rows,$currentPage,$pageSize);?><!--</td>-->
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
<?php
include 'footer.php';
?>