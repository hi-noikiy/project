<?php 
session_start();
$nper = isset($_GET['nper'])?$_GET['nper']:0;
$_SESSION['nper'] = $nper;
?>
<!DOCTYPE html>
<html>
<head>

<style>
body{
width: 100%;
height: 100%;
margin: 0px auto;
font-family: "Microsoft YaHei" ! important;
text-align: center;
overflow:hidden;
}
#showdiv{width:680px;height:380px;position:fixed;left:33%;top:50%;text-align: center;}
#Result{margin:0 auto;text-align:center;width:680px;height:210px;background: url('image/name.png')no-repeat}
#ResultNum{font-size:50pt;font-family:Verdana;white-space:nowrap;line-height: 180px }
#ResultShow{margin:0 auto;text-align:center;width:560px;height:100px;background: url('image/prize.png')no-repeat;cursor: pointer;text-align: center;}
#ResultName{font-size:30pt;font-family:Verdana;white-space:nowrap;color: rgb(122,0,11);line-height: 100px;text-align: center; }
#Button,#hidediv{position:fixed;right:10%;bottom:10%;cursor: pointer;background: url('image/button.png')no-repeat;width:200px;height:200px;}
#hidediv{display: none;z-index: 100;}
#mydiv{color: white;position:fixed;text-align: center;top:5%;margin: 0 auto;width: 100%}
#mydiv div{background-color: rgb(228,59,18);margin-bottom: 10px;padding: 10px 0;word-wrap:break-word;width: 100%}
#sel{appearance:none;border:0;background-color:transparent; border: 0; outline:none;display:block;text-align:center;display:inline-block;
-moz-appearance:none;-webkit-appearance:none;font-size:30pt;font-family:Verdana;white-space:nowrap;color: rgb(122,0,11);line-height: 100px}
#sel option{text-align: center;}
</style>
</head>
<body>
<div id="mydiv"></div>
<audio id="media" src='music/music.mp3'></audio>
<img src='image/redpack.jpg' width="100%">
<div id='showdiv'>

<div id="Result">
	<span id="ResultNum"></span>
</div>
<div id="ResultShow">
	<select id='sel'>
	<?php 
	$config = include 'config/config.php';
	foreach ($config as $k=>$v){ ?>
	<option value="<?php echo $k;?>"><?php echo $v;?></option>
	<?php }?>
	</select>
	
</div>
</div>
<div id="Button" onclick='beginRndNum(this)'></div>
<div id="hidediv"></div>
<div id="setward"></div>
<script src='js/jquery.min.js'></script>
<script src='js/script.js'></script>
</body>
</html>
