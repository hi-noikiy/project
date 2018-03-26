<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
                <div id="echart" style="width: 100%;height:400px;"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                            <tr>
                                <th data-column-id="id" >日期</th>
                                <th>新增账号</th>
                                <th>次日留存</th>
                                <th>次日留存率</th>
                                  <th>2日留存</th>
                                <th>2日留存率</th>
                                <th>3日留存</th>
                                <th>3日留存率</th>
                                <th>4日留存</th>
                                <th>4日留存率</th>
                                 <th>5日留存</th>
                                <th>5日留存率</th>
                                 <th>6日留存</th>
                                <th>6日留存率</th>
                                <th>7日留存</th>
                                <th>7日留存率</th>
                               <th>8日留存</th>
                                <th>8日留存率</th>
                                <th>9日留存</th>
                                <th>9日留存率</th>
                                <th>10日留存</th>
                                <th>10日留存率</th>
                                <th>11日留存</th>
                                <th>11日留存率</th>
                                <th>12日留存</th>
                                <th>12日留存率</th>
                                <th>13日留存</th>
                                <th>13日留存率</th>
                                <th>14日留存</th>
                                <th>14日留存率</th>
                                <th>15日留存</th>
                                <th>15日留存率</th>
                                <th>30日留存</th>
                                <th>30日留存率</th>
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
<script>
    var dataOption = {
        title:'用户留存',
        request_url:'<?php echo site_url('PlayerAnalysis/getRemainData');?>',
        callback : function(result){
            if (result['status']=='fail') {
                $("#dataTable").empty();
                return false;
            }
            var tr = '';
            for (var i in result['raw']) {
                if (isNaN(i)) continue;
                //console.log(result['raw'][i].day1);
                tr += '<tr><td>' + i + '</td>'
                    + '<td>' + result['raw'][i]['role']+ '</td>'
                    + '<td>' + result['raw'][i]['day1']+ '</td>'
                    + '<td>' + result['raw'][i]['day1_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day2']+ '</td>'
                    + '<td>' + result['raw'][i]['day2_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day3']+ '</td>'
                    + '<td>' + result['raw'][i]['day3_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day4']+ '</td>'
                    + '<td>' + result['raw'][i]['day4_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day5']+ '</td>'
                    + '<td>' + result['raw'][i]['day5_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day6']+ '</td>'
                    + '<td>' + result['raw'][i]['day6_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day7']+ '</td>'
                    + '<td>' + result['raw'][i]['day7_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day8']+ '</td>'
                    + '<td>' + result['raw'][i]['day8_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day9']+ '</td>'
                    + '<td>' + result['raw'][i]['day9_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day10']+ '</td>'
                    + '<td>' + result['raw'][i]['day10_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day11']+ '</td>'
                    + '<td>' + result['raw'][i]['day11_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day12']+ '</td>'
                    + '<td>' + result['raw'][i]['day12_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day13']+ '</td>'
                    + '<td>' + result['raw'][i]['day13_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day14']+ '</td>'
                    + '<td>' + result['raw'][i]['day14_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day15']+ '</td>'
                    + '<td>' + result['raw'][i]['day15_rate']+ '%</td>'
                    + '<td>' + result['raw'][i]['day30']+ '</td>'
                    + '<td>' + result['raw'][i]['day30_rate']+ '%</td>'
                    + '</tr>';
            }
            $("#dataTable").html(tr);
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/player_analysis_data.js"></script>