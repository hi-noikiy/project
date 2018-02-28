<section id="content">
    <div class="container">
        <div class="row">
    <div class="col-lg-12">
        <p><?php echo anchor('auth/create_user', lang('index_create_user_link'))?> | <?php echo anchor('auth/create_group', lang('index_create_group_link'))?></p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>用户组名称</th>
                    <th>用户组描述</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($groups as $group):?>
                    <tr>
                        <td><?php echo $group->id?></td>
                        <td><?php echo $group->name?></td>
                        <td><?php echo $group->description?></td>
                        <td>
                            <?php echo anchor("auth/edit_group/".$group->id, '编辑') ;?>
                            |<?php echo anchor("system/edit_permission/".$group->id, '权限配置') ;?>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div>
</section>