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
#ResultShow{margin:0 auto;text-align:center;width:560px;height:100px;background: url('image/prize.png')no-repeat}
#ResultName{font-size:30pt;font-family:Verdana;white-space:nowrap;color: rgb(122,0,11);line-height: 100px }
#Button{position:fixed;right:10%;bottom:10%;cursor: pointer;background: url('image/button.png')no-repeat;width:200px;height:200px;}


</style>
</head>
<body>
<audio id="media" src='music/music.mp3'></audio>
<img src='image/redpack.jpg' width="100%">
<div id='showdiv'>
<div id="Result">
	<span id="ResultNum"></span>
</div>
<div id="ResultShow">
	<span id="ResultName"></span>
</div>
</div>
<div id="Button" onclick='beginRndNum(this)'>

</div>
<script src='js/jquery.min.js'></script>
<script src='js/script.js'></script>
<script>
getlist();
</script>
</body>
</html>
