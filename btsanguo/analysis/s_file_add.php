<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午3:49
 */
include 'header.php';

?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <a href="s_file.php" class="btn btn-primary">返回文件权限列表</a>
            </div>
            <div class="panel-body">
                <form id="frm" role="form" method="post">
                    <input value="FileAdd" name="action" type="hidden"/>
                    <div class="form-group">
                        <label class="control-label" for="fpath">文件路径</label>
                        <input type="text" name="file[fpath]" id="fpath" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="gid">文件组</label>
                        <select class="form-control" name="file[gid]" id="gid">
                            <option value="0">--选择--</option>
                            <?php foreach ($navGrp as $gid=>$gname):?>
                            <option value="<?=$gid?>"><?=$GLOBALS['lang'][$gname]?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="ftitle_zh">页面标题-中文</label>
                        <input type="text" name="file[ftitle_zh]" id="ftitle_zh" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="ftitle_en">页面标题-英文</label>
                        <input type="text" name="file[ftitle_en]" id="ftitle_en" class="form-control">
                    </div>
                    <div class="form-group">
                        <button id="btnSave" type="button" class="btn btn-primary btn-lg">保 存</button>
                        <button type="button" class="btn btn-primary btn-lg">取 消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
