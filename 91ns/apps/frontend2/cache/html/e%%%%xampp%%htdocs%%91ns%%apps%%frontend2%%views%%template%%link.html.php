<link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic("web/css20160317/base.css"); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $this->url->getStatic("web/css20160317/icon/style-icon.css"); ?>"/>

<script type="text/javascript">
    var nsConfig = {
        userUid:'<?php echo (empty($ns_userUid) ? (0) : ($ns_userUid)); ?>',
        pre:'<?php echo $this->url->getStatic(''); ?>',
        iscn:'<?php echo (empty($ns_iscn) ? (0) : ($ns_iscn)); ?>',
        ns_source_login:'<?php echo (empty($ns_source_login) ? (0) : ($ns_source_login)); ?>',
        sourceType:'<?php echo (empty($ns_source) ? (0) : ($ns_source)); ?>',
        channelType:"<?php echo $webType['channelType']; ?>",
        domain:'<?php echo $webType['domain']; ?>',
        name:'<?php echo $webType['name']; ?>',
        logoURL:'<?php echo $this->url->getStatic($webType['logoURL']); ?>',
        roomLogoURL:'<?php echo $this->url->getStatic($webType['roomLogoURL']); ?>',
        roomLoadingURL:'<?php echo $this->url->getStatic($webType['roomLoadingURL']); ?>',
        jsURL:'<?php echo $this->url->getStatic($jsURL); ?>',
        cssURL:'<?php echo $this->url->getStatic($cssURL); ?>',
        GMQQ:['<?php echo $GMQQ[0]; ?>', '<?php echo $GMQQ[1]; ?>', '<?php echo $GMQQ[2]; ?>']
    };
    function requireInit(){
        require.config({
            baseUrl:nsConfig.pre + '<?php echo $jsURL; ?>',
            urlArgs:1,
            paths: {
                jquery:     'tool/jquery-1.11.3.min',
                JSON:       'tool/json2',
                md5:        'tool/md5',
                com:        'tool/com',
                utils:      'tool/utils',
                rankModule: 'module/rank',
                swfobject:  'tool/swfobject'
            }
        });
    };
</script>

