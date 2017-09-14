
<html>
<head>

</head>
<body>
<textarea rows="3" cols="20" id='num'></textarea>
<button id='zip'>转ip</button>
<button id='znum'>转数字</button>
<textarea rows="3" cols="20" id='ip'></textarea>
<script src='js/jquery.min.js'></script>
<script type="text/javascript">
var num,ip;
$('#zip').click(function(){
	num = $("#num").val();
	$.post('/core/ip2long.php',{num:num,act:'toip'},function(json){
		$("#ip").text(json);
		});
});
$('#znum').click(function(){
	num = $("#num").val();
	$.post('/core/ip2long.php',{ip:num,act:'tonum'},function(json){
		$("#ip").text(json);
		});
});

</script>
</body>
</html>
