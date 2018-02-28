<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card" >
                <div class="card-header">
                    <h2>选择查询条件<small></small></h2>
                </div>
                <div class="card-body card-padding" >
                    <div class="row">
                        <form id="search_form" method="get" action="">
                            <div class="col-sm-2" style="display:none;">
                        <div class="form-group">
                            <div class="fg-line">
                            <select  name="type" class="form-control">
                                <?php foreach($big_server_list as $k=>$v){ ?>
                                <option value="<?php echo $k;?>"><?php echo $v;?></option>
                                <?php } ?>
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
                            <th> 服务器ID</th>
                            <th> 全球对战普通赛比赛服id </th>
                            <th> 全球对战精英赛比赛服id</th>
                            <th>pkeliteserver</th>
                            <th> 冠军之夜比赛服id</th>
                            <th>冠军之夜比赛时间 </th>
                            <th>社团争霸比赛服id</th>
                            <th>社团争霸比赛开启配置</th>
                            <th>全球对战练习赛比赛服id</th>
                            <th>端口</th>
                            <th>创建时间</th>
                             <th> 数据更新时间</th>
                             
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
        autoload: true,
        request_url:"<?php echo site_url('SystemAnalysis/crossServer');?>",
        callback: function (result) {
            if (result) {
                //console.log(result.data);

                var table_html = '';
                  //  len = result.data.length;
                if(result['data']!=''){
                for(var i in result['data']){
                    if(!isNaN(i))
                	table_html += '<tr>' +
                    '<td>'+result['data'][i]['server_id']+'</td>' +
                    '<td>'+result['data'][i]['pkexeserver']+'</td>' +
                    '<td>'+result['data'][i]['pkcomserver']+'</td>' +
                    '<td>'+result['data'][i]['pkeliteserver']+'</td>' +
                    
                    '<td>'+result['data'][i]['pkweekendserver']+'</td>' +
                    '<td>'+result['data'][i]['openweekendtime']+'</td>' +
                    '<td>'+result['data'][i]['pksyngameserver']+'</td>' +
                    '<td>'+result['data'][i]['opensyngameflag']+'</td>' +
                    '<td>'+result['data'][i]['pkgameserver']+'</td>' +
                    '<td>'+result['data'][i]['port']+'</td>' +

                    '<td>'+result['data'][i]['created_at']+'</td>' +
                    '<td>'+result['data'][i]['updatetime']+'</td>' +
                   
                     '</tr>';
                    }
               
                $("#dataTable").html(table_html);
                }
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
