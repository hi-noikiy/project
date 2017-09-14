
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <?php echo $this->tag->getTitle(); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Your invoices">
        <meta name="author" content="Phalcon Team">
        <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic('web/css/bootstrap.min.css'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic('web/css/bootstrap-responsive.min.css'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic('web/css/jquery-ui-1.10.0.custom.css'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic('web/css/pagination.css'); ?>" />
        <!--<link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic('web/css/fullcalendar.css'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic('web/css/matrix-style.css'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic('web/css/matrix-media.css'); ?>" /> -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic('web/font-awesome/css/font-awesome.css'); ?>"/>
        <!-- <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic('web/css/jquery.gritter.css'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic('web/css/uniform.css'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic('web/css/91ns.css'); ?>" /> -->
        <link type="text/css" rel="stylesheet" href="<?php echo $this->url->getStatic('web/css/91ns-style.css'); ?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo $this->url->getStatic('web/css/91ns-media.css'); ?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo $this->url->getStatic('web/css/91ns-icon.css'); ?>" />
        
        <!--<style type="text/css">
            #header{height: 40px;}
            #header h1 {
                background: transparent;
                height: 31px;
                left: 20px;
                line-height: 31px;
                overflow: hidden;
                position: relative;
                top: 2px;
                width: 191px;
                font-size: 30px;
                
            }
            #header h1 a{color: #FFF;}

            .table th, .table td{ text-align: center;}
        </style>-->

        <script type="text/javascript" src="<?php echo $this->url->getStatic('web/js/jquery.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $this->url->getStatic('web/js/bootstrap.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $this->url->getStatic('web/js/jquery.pagination.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $this->url->getStatic('web/js/91ns.main.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $this->url->getStatic('web/js/jquery-ui-1.10.0.custom.min.js'); ?>"></script>
        <!--<script type="text/javascript" src="<?php echo $this->url->getStatic('web/js/utils.js'); ?>"></script>
        
        <script type="text/javascript" src="<?php echo $this->url->getStatic('web/js/jquery.ui.datepicker-zh-CN.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $this->url->getStatic('web/js/jquery.uniform.js'); ?>"></script>
        
        <script type="text/javascript" src="<?php echo $this->url->getStatic('web/js/matrix.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $this->url->getStatic('web/laydate/laydate.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $this->url->getStatic('web/js/jquery.cookie.js'); ?>"></script>-->
    </head>
    <body>

        <?php echo $this->getContent(); ?>

    </body>
</html>