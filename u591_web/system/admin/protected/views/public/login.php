<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=Yii::app()->name; ?></title>
        <link href="<?=ASSETS_URL; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=ASSETS_URL; ?>bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=ASSETS_URL; ?>plugin/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
		<link href="<?=ASSETS_URL; ?>bootstrap/css/todc-bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="<?=ASSETS_URL; ?>bootstrap/css/scojs.css" rel="stylesheet" type="text/css" />
		<link href="<?=ASSETS_URL; ?>css/toastr.min.css" rel="stylesheet" type="text/css" />
		<link href="<?=ASSETS_URL; ?>css/common.css" rel="stylesheet" type="text/css" />
		<link href="<?=ASSETS_URL; ?>css/font-awesome.min.css" rel="stylesheet" type="text/css" /> 
        <style>
            body{padding-top:8%;
                 font-size:12px;color: #666666;
                 text-decoration: none;}
            table th,table td {
                text-align: left;
                vertical-align: top;
            }
        </style>
        <script type="text/javascript">
            var closeHandler = function () {
                ferosClient.close();
            }
            addEventListener("ferosClientReady", closeHandler);
        </script>
    </head>
    <body>
        <div class="container-fluid">
            <table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" valign="middle">
                        <table width="646" height="425" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td colspan="3">
                                    <img src="<?=ASSETS_URL; ?>login/images/login_01.gif" width="646" height="114" alt="仓库管理软件"></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="<?=ASSETS_URL; ?>login/images/login_02.gif" width="88" height="311" alt=""></td>
                                <td width="476" height="311" valign="top" background="<?=ASSETS_URL; ?>login/images/login_03.gif">
                                    
                                      
                                        <?php $form=$this->beginWidget('CActiveForm');?>
                                        <table>  
                                            <tr>
                                                <td style="width:120px;line-height:30px;text-align:right"><?=$form->label($manager,'username'); ?></td>
                                                <td><?=$form->textField($manager,'username',array('id'=>'account','size'=>'20')); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height:30px;text-align:right"><?=$form->label($manager,'password'); ?></td>
                                                <td><?=$form->passwordField($manager,'password',array('id'=>'password','size'=>'20')); ?></td>
                                            </tr>
                                         
                                            <tr>
                                                <td style="line-height:30px;text-align:right"><span class="loading"><img src="<?=ASSETS_URL; ?>img/loading.gif"></span></td><td>
                                                    <button type="submit" class="btn btn-primary" style="width:190px"><i class="icon-save"></i> 登录</button>
                                                    <button type="button"  title="关闭" class="btn" onclick="ferosClient.close()"><i class="icon-remove"></i></button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                            </tr>
                                        </table>
                                        <?php $this->endWidget(); ?>	
                             

                                <td>
                                    <img src="<?=ASSETS_URL; ?>login/images/login_04.gif" width="82" height="311" alt=""></td>
                            </tr>
                        </table>
                    </td></tr></table>
        </div>
        <script type="text/javascript" src="<?=ASSETS_URL?>js/jquery-1.8.1.min.js"></script>
        <script type="text/javascript" src="<?=ASSETS_URL?>login/js/login.js"></script>
        <script type="text/javascript" src="<?=ASSETS_URL?>bootstrap/js/sco.js"></script>
        <script type="text/javascript" src="<?=ASSETS_URL?>js/toastr.min.js"></script>
        <script type="text/javascript" src="<?=ASSETS_URL?>js/jquery.qrcode.min.js"></script>
        <script type="text/javascript" src="<?=ASSETS_URL?>js/common.js"></script>

    </body>
</html>
