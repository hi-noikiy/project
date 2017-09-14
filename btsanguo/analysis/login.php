<?php
define('ROOT_PATH', str_replace('analysis/login.php', '', str_replace('\\', '/', __FILE__)));
define('A_ROOT', ROOT_PATH.'analysis/');
include A_ROOT.'config/config.php';

if (!empty($_POST)) {
    $db = db('analysis');
    $account = trim($_POST['account']);
    $pwd = trim($_POST['password']);
    $sys = new System($db);
    $ret = $sys->UserLogin($account, $pwd);
    if ($ret['status']=='ok') {
        header("Location:index.php");
        exit;
    }
    else {
        echo '<script>alert("'.$ret['msg'].'");</script>';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>海牛游戏——运营后台</title>
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="public/css/sb-admin.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">登录</h3>
                    </div>
                    <div class="panel-body">
                        <form id="frm" role="form" method="post" action="<?=$_SERVER['PHP_SELF']?>">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="账号" name="account" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="密码" name="password" type="password" value="">
                                </div>
                                <button id="btnLogin" type="button" class="btn btn-lg btn-success btn-block">登录</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Scripts - Include with every page -->
    <script src="public/js/jquery-1.10.2.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
    <script src="public/js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- SB Admin Scripts - Include with every page -->
    <script src="public/js/sb-admin.js"></script>
    <script>
        $(document).ready(function(){
            $("#btnLogin").on('click', function(){
                if($("input[name='account']").val()=='') {
                    alert('请输入账号！');
                    return false;
                }
                if($("input[name='password']").val()=='') {
                    alert('请输入密码');
                    return false;
                }
                $("#frm").submit();
            });
        });
    </script>
</body>

</html>
