<section id="content">
    <input type="hidden" name='time' id="time" value="">
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
                        <thead id="dataThead">
                        </thead>
                        <tbody id="dataTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?=base_url()?>public/ma/js/layer.js"></script>


<script>
var name;




$(document).on('click', '.fenbu', function () {
    var time = $(this).attr("sid");
    $("#time").val(time);
    layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/gearIframePosition?time='+time
	});
})
//
//}


	$('.cur').click(function(){
		$(this).children().addClass('curs');
		$(this).siblings().children().removeClass('curs');
		$('#searchtype').val($(this).children().attr('id'));
	});
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('PayAnalysis/gearPosition');?>',
        callback: function (result) {
            if (result) {
               /*  if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                } */
                var table_html = '',
                    table_th = '',
                    len = result.data.length;
                var biao =  result.data.res;
                var list =  result.data.ress;

                table_th += '<tr>' +
                         '<th> 时间</th>';
                for (var i in biao) {
                    table_th += '<th></th>';
                }
                table_th += '<th></th>';
                for (var j in list) {
                    var lists = list[j];
                    table_html += '<tr ids='+j+'><td>'+j+'</td>';
                    for (var jd in lists) {
                        var check = lists[jd]['money'];
                        if (lists[jd]['sum'] == undefined) {
                        }else{
                            table_html +=  '<td>'+'金额为：'+check+'元的数量有:'+lists[jd]['sum']+ '</td>';
                        }

                    }
                    table_html +=  '<td><a javascript:void(0); class="fenbu" sid="'+j+'">服务器分布</a></td>';
                    table_html +=  '</tr>';
                }
                table_th += '</tr>' +
                $("#dataTable").html(table_html);
                $("#dataThead").html(table_th);
            }
        }
    };
</script>

   


<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
