<?php $this->renderPartial('/public/header');  ?>
<?php if(!empty($var['url'])) { ?>
<script type="text/javascript">
    function Jump() {
        window.location.href = '<?php echo $var['url']; ?>';
    }
    document.onload = setTimeout("Jump()", <?php echo $var['time']; ?> * 1000);
</script>
<?php } ?>
</head>
<body>
    <div class="container-fluid">
        <?php if($var['status']==1){ ?>
        <div class="Prompt">
            <div class="Prompt_top"></div>
            <div class="Prompt_con">
                <dl>
                    <dt>提示信息</dt>
                    <dd><span class="Prompt_ok"></span></dd>
                    <dd>
                        <h2><?php echo $var['message']; ?></h2>        
                        <?php if(!empty($var['url'])) { ?>
                        <p>系统将在 <span style="color:blue;font-weight:bold"><?php echo $var['time']; ?></span> 秒后自动关闭，如果不想等待,直接点击 <A HREF="<?php echo $var['url']; ?>">这里</A> 关闭</p>
                        <?php } ?>
                    </dd>
                </dl>
                <div class="c"></div>
            </div>
            <div class="Prompt_btm"></div>
        </div>
        <?php } else { ?>
        <div class="Prompt">
            <div class="Prompt_top"></div>
            <div class="Prompt_con">
                <dl>
                    <dt>提示信息</dt>
                    <dd><span class="Prompt_x"></span></dd>
                    <dd>
                        <h2 style="color:red"><?php echo $var['message']; ?></h2>
                        <?php if(!empty($var['url'])) { ?>
                        <p>系统将在 <span style="color:blue;font-weight:bold"><?php echo $var['time']; ?></span> 秒后自动关闭，如果不想等待,直接点击 <A HREF="<?php echo $var['url']; ?>">这里</A> 关闭</p>
                        <?php } ?>
                </dl>
                <div class="c"></div>
            </div>
            <div class="Prompt_btm"></div>
        </div>
       <?php } ?>
    </div>
    <?php $this->renderPartial('/public/js');  ?>

</body>
</html>