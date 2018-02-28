<section id="content">
    <div class="container">
        <div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <?php echo $search_form;?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                
  
                   <table id="data-table-basic" class="table table-striped">
                         
                    <table id="data-table-basic"  class="table table-striped"  border="1">
                   
                     
                        
                  
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



<style>

.left_td{border-left:1px solid pink; background-color: #add;}
</style>



<script>

   
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('Home/summaryByDay');?>',
        callback: function (result) {
            if (result) {
					
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
              //  console.log(result.data);

                var table_html = '',
                    len = result.data.length;
   
          table_html = '<tr><td  rowspan="3"></td><td colspan="4" style="text-align: center;">日期</td></tr>'+               
                
                '<tr>    <td  colspan="2" style="text-align: center;">WG-andriod</td>    <td class="left_td"  colspan="2" style="text-align: center;">WG-IOS</td>  </tr> '+                  
                 '<tr>    <td>日数据</td>   <td>对比昨天</td>    <td class="left_td" > 日数据</td>     <td class="left_td" >对比昨天</td>  </tr>'+                 
                   '<tr>    <td>总账号数</td>   <td>'+result['data']['android']['total_account']+'</td>     <td>'+result['data']['android']['total_account_rate']+'%</td>     <td class="left_td" style="border: solid 1px pink">'+result['data']['ios']['total_account']+'</td>        <td class="left_td" >'+result['data']['ios']['total_account_rate']+'%</td>    </tr>'+                
                   '<tr>    <td>日DAU</td><td>'+result['data']['android']['dau']+'</td>     <td>'+result['data']['android']['dau_rate']+'%</td>     <td class="left_td" style="border: solid 1px pink">'+result['data']['ios']['dau']+'</td>        <td class="left_td" >'+result['data']['ios']['dau_rate']+'%</td>    </tr> '+
                    '<tr>    <td>日净DAU</td>    <td>'+result['data']['android']['clean_dau']+'</td>     <td>'+result['data']['android']['clean_dau_rate']+'%</td>     <td class="left_td" style="border: solid 1px pink">'+result['data']['ios']['clean_dau']+'</td> <td class="left_td" >'+result['data']['ios']['clean_dau_rate']+'%</td>    </tr>'+
                       '<tr>    <td>周活跃账号</td>    <td>'+result['data']['android']['wau']+'</td>     <td>'+result['data']['android']['wau_rate']+'%</td>     <td class="left_td" style="border: solid 1px pink">'+result['data']['ios']['wau']+'</td>  <td class="left_td" >'+result['data']['ios']['wau_rate']+'%</td>    </tr>'+
                      '<tr>    <td>最高在线</td>    <td>'+result['data']['android']['max_online']+'</td>     <td>'+result['data']['android']['max_online_rate']+'%</td>     <td class="left_td" style="border: solid 1px pink">'+'不区分ios与android'+'</td> <td class="left_td" >'+'不区分ios与android'+'</td>    </tr>'+
                       '<tr>  <td>日新增账号</td>    <td>'+result['data']['android']['reg']+'</td>     <td>'+result['data']['android']['reg_rate']+'%</td>     <td class="left_td" style="border: solid 1px pink">'+result['data']['ios']['reg']+'</td><td class="left_td" >'+result['data']['ios']['reg_rate']+'%</td>    </tr>'+
                      '<tr>'+'<td>'+'全渠道次日回头'+'</td>'+'<td>'+result['data']['android']['remain_rate_1']+'</td>'+'<td>'+result['data']['android']['remain_rate_1_rate']+'%</td>'+'<td class="left_td" style="border: solid 1px pink">'+result['data']['ios']['remain_rate_1']+'</td>'+'<td class="left_td">'+result['data']['ios']['remain_rate_1_rate']+'%</td>'+'</tr>';
   
                $("#dataTable").html(table_html);
	           }
        }
      
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
