<style>
    ul,li{list-style: none;}
    .list-wrap{
        /*float:left;*/
        border-bottom: 1px dashed #ccc;
        padding-bottom: 20px;
        padding-left: 0;
    }
    .list-sub{
        /*float:left;*/
        display: inline-block;
        width:160px;
        padding:4px 10px;
        border:1px solid #ccc;
        margin-bottom: 4px;
    }
    .list-parent{
        display: block;
        background-color: #00a5bb;
        color:#FFF;
        font-weight: bold;
        padding:10px;
        margin-bottom: 10px;
    }
</style>
<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body card-padding">
                        <?php echo form_open(current_url());?>
                        <?php if($submit_btn==true):?>
                            <div class="row">
                                <h2>基本信息</h2>
                                <div class="form-group">
                                    <label>所属游戏</label>
                                    <select id="appid" name="appid" class="form-control">
                                        <option value="0">选择该用户组归属的游戏</option>
                                        <?php foreach($game_list as $game):?>
                                            <option value="<?php echo $game['appid']?>"
                                                <?=$saved_appid==$game['appid'] ? 'selected':''?>> <?php echo $game['name'];?>
                                            </option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>所属渠道</label>
                                    <select id="channel" name="channel[]" class="form-control" multiple="multiple">
                                        <option value="0">选择该分组归属渠道</option>
                                        <?php foreach($channel_list as $channel_id=>$channel_name):?>
                                            <option value="<?php echo $channel_id?>"
                                                <?=in_array($channel_id,$saved_channel) ? 'selected' : ''?>>
                                                <?php echo $channel_id,'--',$channel_name;?>
                                            </option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>所属区服</label>
                                    <select id="serverid" name="serverid[]" class="form-control" multiple="multiple">
                                        <option value="0">选择该分组归属区服</option>
                                        <?php foreach($server_list as $server_id=>$server_name):?>
                                            <option value="<?php echo $server_id?>"
                                                <?=in_array($server_id, $saved_serverids) ? 'selected' : ''?>>
                                                <?php echo $server_id,'--',$server_name;?>
                                            </option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                        <?php endif;?>
                        <div class="row">
                            <h2>权限配置</h2>
                            <?php foreach ($my_menus as $menu):?>
                            <ul class="list-wrap">
                                <li class="list-parent">
                                    <?//=$menu['title']?>
                                    <label><input type="checkbox" name="sub_menus" values="<?=$menu['id']?>"/><?=$menu['title']?></label>
                                </li>
                                <?php foreach($menu['sub_menus'] as $sub_menu):?>
                                    <li class="list-sub">
                                        <label><input type="checkbox" name="sub_menus[]"
                                                      <?php if(in_array($sub_menu['id'], $saved_menus) || count($saved_menus)==0):?>
                                                          checked
                                                        <?php endif;?>
                                                      value="<?=$sub_menu['id']?>"/>
                                            <?=$sub_menu['title']?></label>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                            <?php endforeach;?>
                        </div>
                        <?php if($submit_btn):?>
                            <button type="submit" class="btn btn-primary waves-effect">保存</button>
                        <?php endif;?>
                        <?php echo form_close();?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>