<style>
tbody span{
	cursor: pointer;
}
</style>
<section id="content">
    <div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>客服</th>
                            <th>负责玩家</th>
                            <th>负责人数</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($info as $v){ ?>
                        <tr>
                            <td><?php echo $v['email'];?></td>
                            <td>
                            <?php foreach($v['info'] as $value){ 
                            	echo '<span id="'.$value['id'].'">'.$value['serverid'].':'.$value['username'].'</span> ， ';
                             } ?>
                            </td>
                            <td><?php echo $v['num'];?></td>
                            <td><a href="edit?user_id=<?php echo $v['id'];?>">编辑</a></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$('tbody span').click(function(){
	if(confirm('是否删除')){
		var thi = $(this);
		var vipid = $(this).attr('id');
		$.get('del',{vipid:vipid},function(){
			thi.remove();
		});
	}
});

</script>