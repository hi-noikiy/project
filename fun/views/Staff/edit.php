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
                        <input type='hidden' name='user_id' value="<?php echo $_REQUEST['user_id'];?>"/>
                        <div class="row">
                            <h2>角色分配                            <a href='index'>返回</a></h2>
                            <?php foreach ($info as $k=>$v):?>
                            <ul class="list-wrap">
                                <li class="list-parent">
                                    <label><?=$k?></label>
                                </li>
                                <?php foreach($v as $value):?>

                                    <li class="list-sub">
                                        <label><input type="checkbox" name="accounts[]"
                                                      <?php if(isset($myinfo[$value['id']])):?>
                                                          checked
                                                        <?php endif;?>
                                                      value="<?=$value['id']?>"/>
                                            <?=$value['username']?></label>
                                    </li>

                                <?php endforeach;?>
                            </ul>
                            <?php endforeach;?>
                        </div>
                            <button type="submit" class="btn btn-primary waves-effect">保存</button>
                        <?php echo form_close();?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>