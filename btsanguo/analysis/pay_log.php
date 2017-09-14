<?php
include 'header.php';
include 'inc/function.php';
include 'inc/func.inc.php';
$PayCode    = safetxt($_REQUEST["PayCode"]);
$CPID       = safetxt($_REQUEST["CPID"]);
$rpCode     = safetxt($_REQUEST["rpCode"]);
$CardNO     = safetxt($_REQUEST["CardNO"]);
$PayName    = safetxt($_REQUEST["PayName"]);
$SvrID      = intval($_REQUEST["SvrID"]);
$SvrID_2    = intval($_REQUEST["SvrID_2"]);
$OrderID    = safetxt($_REQUEST["OrderID"]);
$IsUC       = safetxt($_REQUEST["IsUC"]);
$bt         = isset($_GET['bt'])? safetxt($_REQUEST["bt"]) : date('Y-m-d 00:00:00');
$et         = isset($_GET['et'])? safetxt($_REQUEST["et"]) : date('Y-m-d h:i:s');
$payid      = safetxt($_GET['accountid']);
$dwFenBaoID = safetxt($_REQUEST["dwFenBaoID"]);
$clienttype = safetxt($_REQUEST["clienttype"]);
$s_money    = intval($_REQUEST["s_money"]);
$e_money    = intval($_REQUEST["e_money"]);
//默认时间

$where = " And Add_Time >= '$bt' And Add_Time <= '$et'";
if ($CPID >0) {
    $where .= " And CPID=$CPID";
}
if ($PayCode>0){
    $where .= " And PayCode='$PayCode'";
}
//成功状态
if (!empty($rpCode)) {
    if ($rpCode == "1") $where .= " And rpCode in ('1','10')";
    if ($rpCode == "2") $where .= " And rpCode not in ('1','10')";
    if ($rpCode == "3") $where .= " And rpCode is null";
}
if (!empty($CardNO)){
    $where .= " And CardNO Like '$CardNO%'";
}
if (!empty($payid)){
    $where .= " And PayID={$payid}";
}
if ($SvrID >0 && $SvrID_2>0){
    $where .= " And ServerID BETWEEN $SvrID AND $SvrID_2";
}
if ($OrderID >0){
    $where .= " And OrderID='$OrderID'";
}
if ($IsUC){
    $where .= " And IsUC=1";
}
if ($dwFenBaoID !==''&&$dwFenBaoID !==null){
    $where .= " And dwFenBaoID='$dwFenBaoID'";
}
if ($clienttype != ""){
    $where .= " And clienttype='$clienttype'";
}
if ($s_money >0){
    $where .= " And PayMoney>='".intval($s_money)."'";
}
if ($e_money >0){
    $where .= " And PayMoney<='".intval($e_money)."'";
}


$sqlTotal = "SELECT COUNT(*) FROM pay_log WHERE 1=1" . $where;
$stmt = $db_sum->prepare($sqlTotal);
$stmt->execute();
$total_rows = $stmt->fetchColumn(0);
$sql = "SELECT * FROM pay_log WHERE 1=1" . $where . " LIMIT $offset,$pageSize";
//echo $sql;
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
                                    <?php echo htmlSelect($serversList, 'SvrID', $_GET['SvrID']);?>至
                                    <?php echo htmlSelect($serversList, 'SvrID_2', $_GET['SvrID_2']);?>
                                </td>
                                <th>充值渠道：</th>
                                <td>
                                    <?php if( $_SESSION['uid']==17):?>
                                       权限不够
                                    <?php else:?>
                                        <?php echo htmlSelect($CPList, 'CPID', $_GET['CPID']);?>
                                        <?php echo htmlSelect($pCodeList, 'PayCode',$_GET['PayCode']);?>
                                    <?php endif;?>
                                </td>
                                <th>充值状态：</th>
                                <td>
                                    <?php echo htmlSelect(array(1=>'成功',2=>'失败',3=>'无回执'), 'rpCode', $_GET['rpCode']);?>
                                    <label>补单<input type="checkbox" name="IsUC" value="1" <?=$_GET['IsUC']==1? 'checked':''?>></label>
                                </td>
                                <th>金额(范围)：</th>
                                <td>
                                    <input name="s_money" type="text" class="form-control" size="4" value="<?=$_GET['s_money']?>" >
                                    --<input name="e_money" type="text" class="form-control" size="4" value="<?=$_GET['e_money']?>" >
                                </td>
                            </tr>
                            <tr>
                                <th>充值时间：</th>
                                <td>
                                    <input name="bt" type="text" size="18" value="<?=$bt?>" class="form-control" onfocus="SelectDate(this,'yyyy-MM-dd hh:mm:ss',0,0)">
                                    --
                                    <input name="et" type="text" size="18" value="<?=$et?>" class="form-control" onfocus="SelectDate(this,'yyyy-MM-dd hh:mm:ss',0,0)">
                                </td>
                                <th>订单号：</th>
                                <td> <input name="OrderID" type="text" class="form-control" size="18" value="<?=$_GET['OrderID']?>" size="18" ></td>
                                <th>卡号：</th>
                                <td><input name="CardNO" type="text" class="form-control" size="18" value="<?=$_GET['CardNO']?>"></td>
                                <th>经销商分包ID：</th>
                                <td>
                                    <input name="dwFenBaoID" type="text" class="form-control" size="8" value="<?=$_GET['dwFenBaoID']?>" >
                                </td>
                            </tr>
                            <tr>
                                <th>账号ID:</th>
                                <td>
                                    <input name="accountid" type="text" size="10" value="<?=$_GET['accountid']?>" class="form-control">
                                </td>
                                <td colspan="6">
                                    <button type="submit" class="btn btn-primary">查 询</button>
                                </td>
                            </tr>
                        </table>
                        <p>

                        </p>
                        
                    </form>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>编号 </th>
                                <th>充值渠道</th>
                                <th>帐号</th>
                                <th>玩家帐号</th>
                                <th>金额</th>
                                <th>卡号</th>
                                <th>银行编码</th>
                                <th>服务区</th>
                                <th>订单号</th>
                                <th>充值状态</th>
                                <th>提交时间</th>
                                <th>提交状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($lists)):?>
                                <?php $I=1;?>
                                <?php foreach($lists as $rs):?>
                                    <tr>
                                        <td><?=$I+($currentPage-1)*$pageSize?></td>
                                        <td><?=$CPList[$rs["CPID"]]?></td>
                                        <td><?=mb_convert_encoding($rs["PayName"],'utf-8')?></td>
                                        <td><?=$rs["PayID"]?></td>
                                        <td><?=$rs["PayMoney"]?></td>
                                        <td><?=$rs["CardNO"]?></td>
                                        <td><?=$rs["BankID"]?></td>
                                        <td>[<?=$rs["ServerID"]?>]<?=$serversList[$rs["ServerID"]]?><?if($rs["ServerID"]==$sidyd)echo '移动梦网'?></td>
                                        <td><?if ($rs["IsUC"]==1){
                                                echo "<font color=#ff0000>".$rs["OrderID"]."</font>";
                                            }else{
                                                echo $rs["OrderID"];
                                            }?></td>
                                        <td><?if ($rs["rpCode"]==1 or $rs["rpCode"]==10){
                                                echo "成功";
                                            }else{
                                                echo $rs["rpCode"];
                                            }?></td>
                                        <td><?=$rs["Add_Time"]?></td>
                                        <td><?=$rs["SubStat"]?></td>
                                    </tr>
                                    <?php $I ++ ;?>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr><td colspan="11">抱歉，没有数据。</td></tr>
                            <?php endif;?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="11"><?php page($total_rows,$currentPage,$pageSize);?></td>
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
<?php include 'footer.php'; ?>