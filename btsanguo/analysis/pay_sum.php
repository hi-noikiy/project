<?php
include 'header.php';
include 'inc/function.php';
include 'inc/func.inc.php';
$PayCode    = safetxt($_REQUEST["PayCode"]);
$CPID       = safetxt($_REQUEST["CPID"]);
$rpCode     = safetxt($_REQUEST["rpCode"]);
$CardNO     = safetxt($_REQUEST["CardNO"]);
$PayName    = safetxt($_REQUEST["PayName"]);
$SvrID      = safetxt($_REQUEST["SvrID"]);
$OrderID    = safetxt($_REQUEST["OrderID"]);
$IsUC       = safetxt($_REQUEST["IsUC"]);
$bt         = isset($_GET['bt'])? safetxt($_REQUEST["bt"]) : date('Y-m-d', strtotime('-7 days'));
$et         = isset($_GET['et'])? safetxt($_REQUEST["et"]) : date('Y-m-d');

$dwFenBaoID = safetxt($_REQUEST["dwFenBaoID"]);
$clienttype = safetxt($_REQUEST["clienttype"]);
$s_money    = intval($_REQUEST["s_money"]);
$e_money    = intval($_REQUEST["e_money"]);
//默认时间
$startTime = $bt . ' 00:00:00';
$endTime   = $et . ' 23:59:59';
$where = "WHERE Add_Time >= '$startTime' And Add_Time <= '$endTime' AND rpCode in ('1','10')";

//统计方式
$cType= intval($_REQUEST["cType"]);

if ($CPID >0) {
    $where .= " And CPID=$CPID";
}
if ($PayCode>0){
    $where .= " And PayCode='$PayCode'";
}
//成功状态

if ($SvrID >0){
    $where .= " And ServerID=$SvrID";
}

if ($dwFenBaoID !==''&&$dwFenBaoID !==null){
    $where .= " And dwFenBaoID='$dwFenBaoID'";
}

//统计充值成功数据
if ($cType==2){
    //按充值渠道
    $sql = "SELECT CPID,sum(PayMoney) as payTota,count(distinct PayID ) count_PayID from pay_log $where";
    $sql .= " group by CPID";
}else{
    //按时间统计
    $sql="select sum(PayMoney) as payTota,CAST(add_Time AS date) as totaDay,count(distinct PayID ) count_PayID from pay_log";
    $sql .= " $where group by totaDay";
    $sql .= " order by add_Time desc";
}

//$sqlTotal = "SELECT COUNT(*) FROM pay_log WHERE 1=1" . $where;
//$stmt = $db_sum->prepare($sqlTotal);
//$stmt->execute();
//$total_rows = $stmt->fetchColumn(0);

//$sql = "SELECT * FROM pay_log WHERE 1=1" . $where . " LIMIT $offset,$pageSize";
// echo $sql;
$stmt = $db_sum->prepare($sql);
$stmt->execute();
$lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <style>
        #searchTbl{
            width: 100%;
        }
        #searchTbl th, #searchTbl td{
            border:1px solid #ccc;
            background-color: #e0f1ff;
            padding: 4px 0;
        }
        #searchTbl th{
            text-align: right;
        }
        #searchTbl td{
            text-align: left;
            padding-left: 3px;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">
                    <form class="form-inline" role="form" action="<?=$action;?>">
                        <table id="searchTbl">
                            <tr>
                                <th>区服：</th>
                                <td>
                                    <?php echo htmlSelect($serversList, 'SvrID', $_GET['SvrID']);?>
                                </td>
                                <th>充值渠道：</th>
                                <td>
                                    <?php echo htmlSelect($CPList, 'CPID', $_GET['CPID']);?>
                                    <?php echo htmlSelect($pCodeList, 'PayCode',$_GET['PayCode']);?>
                                </td>
                                <th>统计方式：</th>
                                <td>
                                    <?php echo htmlSelect(array(1=>'按时间',2=>'按渠道'), 'cType', $_GET['cType']);?>
                                </td>
                            </tr>
                            <tr>
                                <th>充值时间：</th>
                                <td>
                                    <input name="bt" type="text" size="18" value="<?=$bt?>" class="form-control" onfocus="SelectDate(this,'yyyy-MM-dd',0,0)">
                                    --
                                    <input name="et" type="text" size="18" value="<?=$et?>" class="form-control" onfocus="SelectDate(this,'yyyy-MM-dd',0,0)">
                                </td>
                                <th>经销商分包ID：</th>
                                <td>
                                    <input name="dwFenBaoID" type="text" class="form-control" size="8" value="<?=$_GET['dwFenBaoID']?>" >
                                </td>
                                <td colspan="2">
                                    <button type="submit" class="btn btn-primary">查 询</button>
                                </td>
                            </tr>
                        </table>

                    </form>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>编号 </th>
                                <th>日期</th>
                                <th>金额(元)</th>
                                <th>充值渠道</th>
                                <th>充值人数</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                                <?php $I=1;?>
                                <?php foreach($lists as $rs):?>
                                    <tr>
                                        <td><?=$I++?></td>
                                        <td ><?if ($cType==2){
                                                echo $bt." 至 ".$et;
                                            }else{
                                                echo $rs["totaDay"];
                                            }
                                            ?></td>
                                        <td><?=$rs["payTota"]?></td>
                                        <td><?if ($cType==2){
                                                echo $CPList[$rs["CPID"]];
                                            }else{
                                                echo "全部";
                                            }
                                            ?></td>
                                        <td><?=$rs["count_PayID"]?></td>
                                    </tr>
                                    <?php $I ++ ;?>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td colspan="5">抱歉，没有数据。</td></tr>
                            <?php endif;?>
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
<?php include 'footer.php'; ?>