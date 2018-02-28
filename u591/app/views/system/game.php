<div class="row">
    <div class="col-lg-6">
        <form class="form-horizontal">
            <div class="form-group">
                <label>游戏名称</label>
                <input class="form-control" value="<?php echo $game['name']?>">
            </div>
            <div class="form-group">
                <label>APPID</label>
                <input class="form-control" value="<?php echo $game['appid']?>">
            </div>
            <div class="form-group">
                <label>SECRET</label>
                <input class="form-control" value="<?php echo $game['secret']?>">
            </div>
            <div class="form-group">
                <label>限制最大请求数</label>
                <input class="form-control" value="<?php echo $game['max_request']?>">
            </div>
            <div class="form-group">
                <label>创建时间</label>
                <input class="form-control" value="<?php echo $game['created_at']?>">
            </div>
        </form>
    </div>
</div>