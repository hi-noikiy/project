<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 */
include 'header.php';
$sys = new System($db_sum);
$files = $sys->FileList();
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <a href="s_user_add.php" class="btn btn-primary">添加用户</a>
                <a href="s_user_grp_add.php" class="btn btn-primary">添加用户组</a>
                <a href="s_file_add.php" class="btn btn-primary">添加文件权限</a>
                <span id="msg" style="display: none;"></span>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th style="width:5%;">排序</th>
                            <th>文件组</th>
                            <th>文件标题-中文</th>
                            <th>文件标题-英文</th>
                            <th>文件名（路径）</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($files as $file):?>
                                <tr>
                                    <td><?=$file['id']?></td>
                                    <td><input data-fid="<?=$file['id']?>" name="fsort" text="text" class="form-control" value="<?=$file['fsort']?>"/></td>
                                    <td><?=$GLOBALS['lang'][$navGrp[$file['gid']]]?></td>
                                    <td><?=$file['ftitle_zh']?></td>
                                    <td><?=$file['ftitle_en']?></td>
                                    <td><?=$file['fpath']?></td>
                                    <td>
                                        <a href="s_file_edit.php?fid=<?=$file['id']?>">修改</a>
                                        <a href="s_file_edit.php?fid=<?=$file['id']?>&action=visable&status=<?=$file['fstatus']?>"><?=$file['fstatus']==1?'关闭':'显示'?></a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
