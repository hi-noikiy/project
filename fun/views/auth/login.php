<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Data Analysis System">
  <meta name="author" content="Guengpeng Chen">

  <title>U591 Game Data System</title>

  <!-- Bootstrap Core CSS -->
  <link href="<?=base_url()?>public/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="<?=base_url()?>public/css/sb-admin.css" rel="stylesheet">

  <!-- Morris Charts CSS -->
  <link href="<?=base_url()?>public/css/plugins/morris.css" rel="stylesheet">

  <!-- Custom Fonts -->
  <link href="<?=base_url()?>public/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body>
<div id="wrapper" style="padding-left: 0;">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo lang('login_heading');?></h3>
                    </div>
                    <div class="panel-body">
                        <?php echo form_open("auth/login");?>
                        <fieldset>
                            <div class="form-group">
                                <!--                <input class="form-control" placeholder="账号" name="account" type="text" autofocus>-->
                                <?php echo form_input($identity, '', array('autofous','placeholder'=>'邮箱/用户名','class'=>'form-control'));?>
                            </div>
                            <div class="form-group">
                                <?php echo form_input($password,'', array('placeholder'=>'请输入密码', 'class'=>'form-control'));?>

                                <!--                <input class="form-control" placeholder="密码" name="password" type="password" value="">-->
                            </div>
                            <div class="form-group">
                                <?php echo lang('login_remember_label', 'remember');?>
                                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
                            </div>
                            <p><?php echo form_submit('submit', lang('login_submit_btn'), array('class'=>'btn btn-lg btn-success btn-block'));?></p>
                            <!--              <button id="btnLogin" type="button" class="btn btn-lg btn-success btn-block">登录</button>-->
                            <a href="forgot_password"><?php echo lang('login_forgot_password');?></a>
                        </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
  </div>
</body>
</html>