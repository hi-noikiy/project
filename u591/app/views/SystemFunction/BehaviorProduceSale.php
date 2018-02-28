<style>
    .table-striped th{text-align: left;}
    .table-striped td, .table-striped th{ border:1px solid #c0c0c0 !important;}
</style>
<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php $user_id_filter = true;$account_id_filter=true;?>
                <?php echo $search_form;?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                            <tr>
                                <th>时间</th>
                                <th>统计类型</th>
                                <th>参数</th>
                                <th>消耗的金钱数量</th>
                                <th>消耗的钻石数量</th>
                                <th>消耗的钻石数量</th>
                                <th>消耗货币类型1的数量</th>
                                <th>消耗货币类型2的数量</th>
                                <th>消耗货币类型3的数量</th>
                                <th>消耗货币类型4的数量</th>
                                <th>消耗货币类型5的数量</th>
                                <th>消耗货币类型6的数量</th>
                                <th>消耗货币类型7的数量</th>
                                <th>消耗货币类型8的数量</th>
                                <th>消耗货币类型9的数量</th>
                                <th>消耗货币类型1的数量</th>
                                <th>消耗的道具或者精灵ID</th>
                                <th>消耗的道具和精灵的数量</th>
                                <th>消耗的道具或者精灵ID</th>
                                <th>消耗的道具和精灵的数量</th>
                                <th>消耗的道具或者精灵ID</th>
                                <th>消耗的道具和精灵的数量</th>
                                <th>消耗的道具或者精灵ID</th>
                                <th>消耗的道具和精灵的数量</th>
                                <th>消耗的道具或者精灵ID</th>
                                <th>消耗的道具和精灵的数量</th>
                                <th>消耗的道具或者精灵ID</th>
                                <th>消耗的道具和精灵的数量</th>
                                <th>消耗的道具或者精灵ID</th>
                                <th>消耗的道具和精灵的数量</th>
                                <th>消耗的道具或者精灵ID</th>
                                <th>消耗的道具和精灵的数量</th>
                                <th>消耗的道具或者精灵ID</th>
                                <th>消耗的道具和精灵的数量</th>
                                <th>消耗的道具或者精灵ID</th>
                                <th>消耗的道具和精灵的数量</th>
                                <th>获得的金钱数量</th>
                                <th>获得的钻石数量</th>
                                <th>获得的钻石数量</th>
                                <th>获得货币类型1的数量</th>
                                <th>获得货币类型2的数量</th>
                                <th>获得货币类型3的数量</th>
                                <th>获得货币类型4的数量</th>
                                <th>获得货币类型5的数量</th>
                                <th>获得货币类型6的数量</th>
                                <th>获得货币类型7的数量</th>
                                <th>获得货币类型8的数量</th>
                                <th>获得货币类型9的数量</th>
                                <th>获得货币类型1的数量</th>
                                <th>获得的道具或者精灵ID</th>
                                <th>获得的道具和精灵的数量</th>
                                <th>获得的道具或者精灵ID</th>
                                <th>获得的道具和精灵的数量</th>
                                <th>获得的道具或者精灵ID</th>
                                <th>获得的道具和精灵的数量</th>
                                <th>获得的道具或者精灵ID</th>
                                <th>获得的道具和精灵的数量</th>
                                <th>获得的道具或者精灵ID</th>
                                <th>获得的道具和精灵的数量</th>
                                <th>获得的道具或者精灵ID</th>
                                <th>获得的道具和精灵的数量</th>
                                <th>获得的道具或者精灵ID</th>
                                <th>获得的道具和精灵的数量</th>
                                <th>获得的道具或者精灵ID</th>
                                <th>获得的道具和精灵的数量</th>
                                <th>获得的道具或者精灵ID</th>
                                <th>获得的道具和精灵的数量</th>
                                <th>获得的道具或者精灵ID</th>
                                <th>获得的道具和精灵的数量</th>
                            </tr>
                        </thead>
                        <tbody id="dataTable"> </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--<script>-->
<!--    $("#submit").attr('type', 'submit');-->
<!--</script>-->
<script>
    var dataOption = {
        title:'',
        autoload: true,
        request_url:'<?php echo site_url('SystemFunction/BehaviorProduceSale');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                $("#dataTable").html(result.data);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
