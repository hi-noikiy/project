<section id="content">
    <div class="container">
    	<div class="block-header">
            <h2><?php echo $page_title;?></h2>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>区服</th>
                            <th>渠道</th>
                            <th>角色名</th>
                            <th>等级</th>
                            <th>客户端类型</th>
                            <th>最近登陆时间</th>
                            <th>最近登陆ip</th>
                            <th>生日</th>
                            <th>今日充值金额</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($info as $v){ ?>
                        <tr>
                            <td><?php echo $v['serverid'];?></td>
                            <td><?php echo $v['channel'];?></td>
                            <td><?php echo $v['username'];?></td>
                            <td><?php echo $v['lev'];?></td>
                            <td><?php echo $v['client_type'];?></td>
                            <td><?php echo $v['last_login_time'];?></td>
                            <td><?php echo $v['last_login_ip'];?></td>
                            <td <?php if($v['nearly']){ ?>style='color:red'<?php }?>><?php echo $v['birthday'];?></td>
                            <td><?php echo $v['curpay'];?></td>
                            <td><a href='javascript:;' onclick="showdetail(<?php echo $v['id'];?>)">充值记录</a>
                            <a href='javascript:;' onclick="userdetail(<?php echo $v['id'];?>)">编辑</a></td>
                        </tr>
                        <?php } ?>
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
function showdetail(vipid){
	layer.open({
		  type: 2,
		  title: '充值记录',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/rechargeDetail?vipid='+vipid
		  });
}
function userdetail(vipid){
	layer.open({
		  type: 2,
		  title: '编辑用户信息',
		  maxmin: true,
		  shadeClose: true, //点击遮罩关闭层
		  area : ['800px' , '520px'],
		  content: '../frame/userDetail?vipid='+vipid
		  });
}
</script>
