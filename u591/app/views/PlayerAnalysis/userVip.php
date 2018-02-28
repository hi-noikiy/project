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
                        <thead>
                        <tr>
                
                           
                            <th>类型</th>
                            <th>收入</th>
                            <th> 类型</th>
                           <th>支出 </th>
                    
                         
                        </tr>
                        </thead>
                        <tbody id="dataTable">
             
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        
        
        
        
        
        
        
        
                <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                
                           
                            <th></th>
                            <th></th>
                            <th></th>
                           <th> </th>
                    
                         
                        </tr>
                        </thead>                  
                        
                      <tbody id="dataTable2">
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


function detail(serverid,where){
	layer.open({
		  type: 2,
		  title: 'iframe父子操作',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/sixteen?serverid='+serverid+'&show='+where
		  });
}


$('#btype').change(function(){
	if($(this).val()==4){
		$('#gametype').hide();
	}else{
		$('#gametype').show();
	}
});
    var dataOption = {
        title:'',
        autoload: false,
        request_url:'<?php echo site_url('PlayerAnalysisNew/userVip');?>',
        callback: function (result) {
            if (result) {
                if (result.status!='ok') {
                    $("#dataTable").html('');
                    notify(result.info);
                    return false;
                }
                //console.log(result.data);

                 var table_html='';
                

                  
                                    
table_html ='<tr>'+'<td>'+'V12人数'+'</td>'+'<td>'+result['data']['p1']+'</td>'+'<td>'+''+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'VIP经验'+'</td>'+'<td>'+result['data']['p2']+'</td>'+'<td>'+''+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	钻石消耗	</td>'+'<td>'+result['data']['p3']+'		</td>'+'<td>'+'获得'+'</td>'+'<td>'+result['data']['p39']+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	金钱消耗	</td>'+'<td>'+result['data']['p4']+'		</td>'+'<td>'+'获得'+'</td>'+'<td>'+result['data']['p40']+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	体力消耗	</td>'+'<td>'+result['data']['p5']+'		</td>'+'<td>'+'获得'+'</td>'+'<td>'+result['data']['p41']+'</td>'+'</tr>'+
            /*    '<tr>'+'<td>'+'	消耗	</td>'+'<td>'+result['data']['p6']+'		</td>'+'<td>'+'获得'+'</td>'+'<td>'+result['data']['p42']+'</td>'+'</tr>'+ */
               '<tr>'+'<td>'+'	联盟大赛主动挑战胜利次数/主动挑战次数	</td>'+'<td>'+result['data']['p7']+'/'+result['data']['p7_2']+'	</td>'+'<td>'+'被动挑战胜利次数/被动挑战次数'+'</td>'+'<td>'+result['data']['p43']+'/'+result['data']['p43_2']+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	联盟大赛最高排名	</td>'+'<td>'+result['data']['p8']+'		</td>'+'<td>'+'最低排名'+'</td>'+'<td>'+result['data']['p44']+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	全球对战最高胜点	</td>'+'<td>'+result['data']['p9']+'		</td>'+'<td>'+'最低胜点'+'</td>'+'<td>'+result['data']['p45']+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	全球对战最高段位、星点	</td>'+'<td>'+result['data']['p10']+'/'+result['data']['p10_2']+'		</td>'+'<td>'+'最低段位、星点'+'</td>'+'<td>'+result['data']['p46']+'/'+result['data']['p46_2']+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	世界BOSS获得积分	</td>'+'<td>'+result['data']['p11']+'		</td>'+'<td>'+''+'</td>'+'<td>'+'</td>'+'</tr>'+
              '<tr>'+'<td>'+'	世界BOSS最高排名	</td>'+'<td>'+result['data']['p12']+'		</td>'+'<td>'+''+'</td>'+'<td>'+'</td>'+'</tr>'+ 
               '<tr>'+'<td>'+'	社团宝藏获得积分	</td>'+'<td>'+result['data']['p13']+'		</td>'+'<td>'+''+'</td>'+'<td>'+'</td>'+'</tr>'+
            /*    '<tr>'+'<td>'+'	社团宝藏排名	</td>'+'<td>'+result['data']['p14']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+ */
               '<tr>'+'<td>'+'	社团副本参与次数	</td>'+'<td>'+result['data']['p15']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	社团副本获得积分	</td>'+'<td>'+result['data']['p16']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	冠军之夜是否参赛	</td>'+'<td>'+result['data']['p17']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	冠军之夜最终排名	</td>'+'<td>'+result['data']['p18']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	社团争霸参与轮数	</td>'+'<td>'+result['data']['p19']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	社团争霸社团最终排名	</td>'+'<td>'+result['data']['p20']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	精灵塔通关关卡	</td>'+'<td>'+result['data']['p21']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	精灵塔评分	</td>'+'<td>'+result['data']['p22']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	普通副本参与次数	</td>'+'<td>'+result['data']['p23']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	普通副本最高进度	</td>'+'<td>'+result['data']['p24']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	精灵副本参与次数	</td>'+'<td>'+result['data']['p25']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	精灵副本最高进度	</td>'+'<td>'+result['data']['p26']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
              
               '<tr>'+'<td>'+'	训练师等级	</td>'+'<td>'+result['data']['p30']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	精灵平均星级	</td>'+'<td>'+result['data']['p31']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	精灵图鉴平均等级	</td>'+'<td>'+result['data']['p32']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	精灵亲密度平均等级	</td>'+'<td>'+result['data']['p33']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	精灵努力点平均分配总数	</td>'+'<td>'+result['data']['p34']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>';
         /*       '<tr>'+'<td>'+'	技能专精平均等级	</td>'+'<td>'+result['data']['p35']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>'+
               '<tr>'+'<td>'+'	创世徽章激活数量	</td>'+'<td>'+result['data']['p36']+'		</td>'+'<td>'+'</td>'+'<td>'+'</td>'+'</tr>' */

               
            
                $("#dataTable").html(table_html);


                var table_html2 = '',
                len = result.data2.length;
            for (var i in result.data2) {
                if (isNaN(i)) continue;
                //for (var i=0;i<len; i++) {
                table_html2 += '<tr>' +
                
                    '<td>'+'运营活动'+'</td>' +
                    '<td>'+result['data2'][i]['name']+'</td>' +
                   
                    '</tr>';
            }
            $("#dataTable2").html(table_html2);



                
            }
        }
    };




</script>
<script src="<?=base_url()?>public/ma/js/common_req.js"></script>
