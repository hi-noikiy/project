<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>选择查询条件<small></small></h2>
                </div>
                <div class="card-body card-padding">
                    <div class="row">
                    <form id="search_form" method="get" action="">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="查询开始时间" type="text" name="date1" value="<?php echo $bt?>" class="form-control date-picker" placeholder="查询开始时间">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input  title="查询结束时间" type="text" name="date2" value="<?php echo $et?>" class="form-control date-picker" placeholder="查询结束时间">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="select">
                                <select name="channel_id" class="form-control">
                                    <option value="0">选择广告渠道</option>
                                    <?php foreach($channels as $channel):?>
                                        <option value="<?php echo $channel['media_source']?>"> <?php echo isset($ad_list[$channel['media_source']])?$ad_list[$channel['media_source']]:$channel['media_source'];?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <button type="button" id="submit" class="btn btn-primary btn-sm m-t-10 waves-effect">查询</button>
                    </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>时间</th>
                            <th>注册数</th>
                            <!-- <th>注册递增百分比</th>-->
                            <th>DAU</th>
                            <!-- <th>DAU递增百分比</th> -->
                            <th>次日<br/>留存</th>
                            <th>3日<br/>留存</th>
                            <th>7日<br/>留存</th>
                            <th>15日<br/>留存</th>
                            <th>30日<br/>留存</th>
                            <th>新用户当日总充值金额(美元)</th>
                            <th>新用户当日充值人数</th>
                            <th>新用户当日充值次数</th>
                        </tr>
                        </thead>
                        <tbody id="dataTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--<script>-->
<!--    $("#submit").attr('type', 'submit');-->
<!--</script>-->
<script src="<?=base_url()?>public/ma/js/layer.js"></script>
<script>
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('Home/summary_by_ad');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                //console.log(result.data);

                var table_html = '',
                    len = result.data.length;
                for (var i in result.data) {
                    if (isNaN(i)) continue;
                    //for (var i=0;i<len; i++) {
                    table_html += '<tr>' +
                        '<td>'+result['data'][i]['sday']+'</td>' +
                        '<td>'+result['data'][i]['registernum']+'</td>' +
                            
                        '<td>'+result['data'][i]['usercount']+'</td>' +
                        
                        '<td>'+result['data'][i]['remain_1']+'</td>' +
                        '<td>'+result['data'][i]['remain_3']+'</td>' +
                        '<td>'+result['data'][i]['remain_7']+'</td>' +
                        '<td>'+result['data'][i]['remain_15']+'</td>' +
                        '<td>'+result['data'][i]['remain_30']+'</td>' +
                        '<td>'+result['data'][i]['allmoney']+'</td>' +
                        '<td>'+result['data'][i]['countAccountid']+'</td>' +
                        '<td>'+result['data'][i]['count']+'</td>' +
                        '</tr>';
                }
                $("#dataTable").html(table_html);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
