<script type="text/javascript" src="<?=ASSETS_URL; ?>bootstrap/js/bootstrap.min.js"></script>    
<script type="text/javascript" src="<?=ASSETS_URL; ?>bootstrap/js/sco.js"></script>    
<script type="text/javascript" src="<?=ASSETS_URL; ?>js/jquery.dragsort.min.js"></script>    
<script type="text/javascript" src="<?=ASSETS_URL; ?>js/jquery.cxselect.min.js"></script>    


<script type="text/javascript" src="<?=ASSETS_URL; ?>js/jqPaginator.min.js"></script>    
<script type="text/javascript" src="<?=ASSETS_URL; ?>js/jquery.qrcode.min.js"></script>    
<script type="text/javascript" src="<?=ASSETS_URL; ?>js/jquery-barcode.js"></script>    
<script type="text/javascript" src="<?=ASSETS_URL; ?>js/jquery.cookie.js"></script>    
<script type="text/javascript" src="<?=ASSETS_URL; ?>js/toastr.min.js"></script>    
<script type="text/javascript" src="<?=ASSETS_URL; ?>js/jquery.websocket.js"></script>    

<script type="text/javascript" src="<?=ASSETS_URL; ?>js/sisyphus.min.js"></script>    
<script type="text/javascript" src="<?=ASSETS_URL; ?>js/common.js"></script>    
<script type="text/javascript" src="<?=ASSETS_URL; ?>js/LodopFuncs.js"></script>    
<script type="text/javascript" src="<?=ASSETS_URL; ?>js/jquery.autocomplete.min.js"></script>    

<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>  
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed> 
</object> 
<script type="text/javascript">
    function post_sisyphus() {
        $("form").sisyphus({
            customKeySuffix: "{md5($this->router->get_path_info()).'u'.$member.uid}",
            onRestore: function () {
                window.parent.toastr.info('已恢复历史数据');
            }
        });
    }
    function PrintNoBorderTable(url, title) {
        LODOP = getLodop();
        LODOP.PRINT_INIT(title);
        LODOP.ADD_PRINT_TBURL(20, 5, "100%", "100%", url);
        LODOP.SET_PRINT_STYLEA(0, "LinkedItem", -1);
        LODOP.SET_PREVIEW_WINDOW(2, 2, 0, 760, 540, "");
        LODOP.PREVIEW();
    }
    function PrintBarCodeNoBorderTable(url, title, BarCodeType, Top, Left, Width, Height, BarCodeValue) {
        LODOP = getLodop();
        LODOP.PRINT_INIT(title);
        LODOP.ADD_PRINT_TBURL(20, 5, "100%", "100%", url);
        LODOP.SET_PRINT_STYLEA(0, "LinkedItem", -1);
        LODOP.ADD_PRINT_BARCODE(Top, Left, Width, Height, BarCodeType, BarCodeValue);
        LODOP.SET_PREVIEW_WINDOW(2, 2, 0, 760, 540, "");
        LODOP.PREVIEW();
    }
</script>