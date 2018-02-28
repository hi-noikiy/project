<div class="card-header">
	<h2>
		选择查询条件<small></small>
	</h2>
	<!--<h2><small>输入玩家等级查询</small></h2>-->
</div>
<div class="card-body card-padding">
	<div class="row">
		<form id="search_form" method="get" action="">
			<div class="row">
                <?php if($reg1):?>
                注册开始时间:
                    <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<input title="注册开始时间" type="text" name="reg1" value=""
								class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>"
								placeholder="注册开始时间">
						</div>
					</div>
				</div>
                    <?php endif;?>
                    <?php if($reg2):?>
                    注册结束时间:
                    <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<input title="注册结束时间" type="text" name="reg2" value=""
								class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>"
								placeholder="注册结束时间">
						</div>
					</div>
				</div>
                <?php endif;?>
                <?php if($date1):?>
                    <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<input title="查询开始时间" type="text" name="date1"
								value="<?php echo $bt?>"
								class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>"
								placeholder="查询开始时间">
						</div>
					</div>
				</div>
                <?php endif;?>
                <?php if($date2):?>
                    <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<input title="查询结束时间" type="text" name="date2"
								value="<?php echo $et?>"
								class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>"
								placeholder="查询结束时间">
						</div>
					</div>
				</div>
                <?php endif;?>


                

                <?php if($server_id):?>
                    <?php if(!empty($big_server_list)):?>
                        <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<select id="servertype" class="form-control">
								<option value="0">未选择</option>
                                        <?php foreach($big_server_list as $k=>$v){ ?>
                                            <option
									value="<?php echo $k;?>"><?php echo $v;?></option>
                                        <?php } ?>
                                    </select>
						</div>
					</div>
				</div>
                    <?php endif;?>
                    <div class="col-sm-3">
					<div class="form-group">
						<select multiple='multiple' id="server_id_mul"
							data-name="server_id" class="form-control mul">
							<option value="0">选择区服</option>
                                <?php foreach($server_list as $server_id=>$server_name):?>
                                    <option
								value="<?php echo $server_id?>"> <?php echo $server_name;?></option>
                                <?php endforeach;?>
                            </select>
					</div>
				</div>
                <?php endif;?>


               <?php if($date3):?>
                    <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<input title="开服开始时间" type="text" name="date3"
								value="<?php echo $btserver?>"
								class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>"
								placeholder="开服开始时间">
						</div>
					</div>
				</div>
<?php endif;?>
<?php if($date4):?>
                    <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<input title="开服结束时间" type="text" name="date4"
								value="<?php echo $etserver?>"
								class="form-control <?php echo isset($date_time_picker) ? 'date-time-picker' :'date-picker'?>"
								placeholder="开服结束时间">
						</div>
					</div>
				</div>
                <?php endif;?>





                <?php if($channel_id):?>
                    <div class="col-sm-3"
					<?php echo count($channel_list)==1 ? 'style="display:none"' : ''?>>
					<div class="form-group">
						<select multiple='multiple' id="channel_id_mul"
							data-name="channel_id" class="mul">
							<option value="0">选择渠道</option>
                                <?php foreach($channel_list as $channel_id=>$channel_name):?>
                                    <option
								value="<?php echo $channel_id?>"
								<?php echo count($channel_list)==1 ? 'selected' : ''?>>
                                        <?php echo $channel_name;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
					</div>
				</div>
                <?php endif;?>
               
            </div>
			<div class="row">
                <?php if($accountid):?>
                    <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<input title="accountid" type="text" name="accountid"
								class="form-control" placeholder="账号ID">
						</div>
					</div>
				</div>
                <?php endif;?>
                <?php if($itemid):?>
                    <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<input title="itemid" type="text" name="itemid"
								class="form-control" placeholder="道具ID">
						</div>
					</div>
				</div>
                <?php endif;?>
                <?php if($userid):?>
                    <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<input title="userid" type="text" name="userid"
								class="form-control" placeholder="UserID">
						</div>
					</div>
				</div>
                <?php endif;?>
             
                <?php if($client_version):?>
                    <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<input title="client_version" type="text" name="client_version"
								class="form-control" placeholder="客户端版本">
						</div>
					</div>
				</div>
                <?php endif;?>






                <?php if($start_dan):?>
                    <div class="col-sm-2">
					<div class="form-group">
						<div>
							<input title="开始段位" type="text" name="start_dan" value="1"
								placeholder="开始段位">
						</div>
					</div>
				</div>
 <?php endif;?>
<?php if($end_dan):?>
                    <div class="col-sm-2">
					<div class="form-group">
						<div>
							<input title="结束段位" type="text" name="end_dan" value="10"
								placeholder="结束段位">
						</div>
					</div>
				</div>
                <?php endif;?>


                <?php if($user_start_level):?>

                    <div class="col-sm-2">

					<div class="form-group">
						<div class="fg-line">
							<input title="玩家开始等级" type="text" name="user_start_level"
								value="" placeholder="玩家开始等级">
						</div>
					</div>
				</div>


                <?php endif;?>

				<?php if($user_end_level):?>

                    <div class="col-sm-2">

					<div class="form-group">
						<div class="fg-line">
							<input title="玩家结束等级" type="text" name="user_end_level" value=""
								placeholder="玩家结束等级">
						</div>
					</div>
				</div>


                <?php endif;?>



                <?php if($vip_start_level):?>

                    <div class="col-sm-2">

					<div class="form-group">
						<div class="fg-line">
							<input title="vip开始等级" type="text" name="vip_start_level"
								value="" placeholder="vip开始等级">
						</div>
					</div>
				</div>
                <?php endif;?>
				<?php if($vip_end_level):?>

                    <div class="col-sm-2">

					<div class="form-group">
						<div class="fg-line">
							<input title="vip结束等级" type="text" name="vip_end_level" value=""
								placeholder="vip结束等级">
						</div>
					</div>
				</div>
                <?php endif;?>



                <?php if($eudemon):?>
                    <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<input title="精灵id" type="text" name="eudemon"
								class="form-control" placeholder="精灵id">
						</div>
					</div>
				</div>
                <?php endif;?>



                <?php if($mac):?>
                    <div class="col-sm-2">
					<div class="form-group">
						<div class="fg-line">
							<input title="mac" type="text" name="mac" class="form-control"
								placeholder="mac">
						</div>
					</div>
				</div>
                <?php endif;?>



                <div class="col-sm-2">
					<button type="button" id="submit"
						class="btn btn-primary btn-sm m-t-10 waves-effect">查询</button>
				</div>
			</div>

		</form>
	</div>
</div>