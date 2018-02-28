<style>
.col-sm-2{
	padding-top:10px;
}
</style>
<div class="card-body card-padding">
    <div class="row">
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <div class="fg-line">
                            <input title="出生日期" type="text" id="birthday" class="form-control" placeholder="出生日期" maxlength="10" value="<?php echo $info['birthday'];?>">出生日期(格式：2000-12-12)
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="手机号" type="text" id="phone" class="form-control" placeholder="手机号" maxlength="11"  value="<?php echo $info['phone'];?>">手机号
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="QQ" type="text" id="qq" class="form-control" placeholder="QQ" maxlength="20"  value="<?php echo $info['qq'];?>">QQ
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="fg-line">
                                <input title="邮箱" type="text" id="email" class="form-control" placeholder="邮箱" maxlength="25"  value="<?php echo $info['email'];?>">邮箱
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                    <input id='button' type="button" value="保存" />
                </div>
            </div>
</div>
</div>
<script src="/public/ma/js/jquery.min.js"></script>
<script src="/public/ma/js/layer.js"></script>
<script>
$('#button').click(function(){
	var birthday = $('#birthday').val();
	var phone = $('#phone').val();
	var qq = $('#qq').val();
	var email = $('#email').val();
	$.get('',{birthday:birthday,phone:phone,qq:qq,email:email},function(){
		parent.layer.closeAll();
	});
});

</script>