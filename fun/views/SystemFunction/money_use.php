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
                <?php echo $search_form;?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <!--<tbody id="dataTable">-->
                        <!--</tbody>-->
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
        request_url:'<?php echo site_url('SystemFunction/money_use');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify("客官,不好意思，没有查到数据!");
                    return false;
                }
                //console.log(result.data);

                //var table_html = '',
                //    len = result.data.length;
                //for (var i in result.data) {
                //    table_html += '<tbody><tr><th>日期</th><td>'+i + '</td>';
                //
                //    table_html += '</tbody>';
                //}
                $("#data-table-basic").html(result.data);
            }
        }
    };
</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
