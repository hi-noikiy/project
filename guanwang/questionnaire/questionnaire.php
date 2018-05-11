<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>问卷界面</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link rel="stylesheet" type="text/css" href="css/questionnaire.css" />
		<script src="js/jquery.js" type="text/javascript" charset="utf-8"></script>
		<!--<script src="js/template-web.js" type="text/javascript" charset="utf-8"></script>-->
	</head>

	<body>
		<div class="print">
			<header>
				<p>游戏小调查</p>
			</header>
			<div class="Qtxt">
				<p>为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！您在本问卷内所提供的任何个人信息，我们都不会泄漏或作广告推销之用。点击“开始填写”进入问卷答题。<span></span></p>
			</div>
			<div class="Qb">
				<a>开始填写</a>
				<p>截止时间：<span></span></p>
			</div>
		</div>
		<script type="text/javascript">
			// 自定义弹出层
			function Alert(e,a) {
				$("body").append('<div id="msg"><div class="model"></div><div class="content"><p>提示</p><p>'+e+'</p><button>确定</button></div></div>');
				var print = $('.print');
				print.css("overflow","hidden");
				$("#msg button").on("click",function(){
					if(typeof a === "function" && a!=undefined) { //是函数    其中 FunName 为函数名称
						a();
		           	}
					print.css("overflow","auto");
					$("#msg").remove();
				});
//				$("#msg .content").css('transform', 'rotate('+Orientation+'deg)');
//				$("#msg .content").css('transform-origin', '50% 50%');
			}
			var evt = "onorientationchange" in window ? "orientationchange" : "resize";
			// 旋转角度
//			var Orientation = "0";
//			window.addEventListener(evt, function() {
//				//console.log(evt);
//				var width = document.documentElement.clientWidth;
//				var height = document.documentElement.clientHeight;
//				var print = $('.print');
//				//console.log(window.orientation);
//				if(window.orientation != undefined){
//					if(width > height) {
//						Orientation = window.orientation;
//						print.width(width);
//						print.height(height);
//						print.css('top', 0);
//						print.css('left', 0);
//						print.css('transform', 'none');
//						print.css('transform-origin', '50% 50%');
//					} else {
//						Orientation = window.orientation;
//						print.width(height);
//						print.height(width);
//						print.css('top', "-40%");
//						print.css('left', 0 - (height - width) / 2);
//						print.css('transform', 'rotate('+window.orientation+'deg)');
//						print.css('transform-origin', '50% 50%');
//					}
//					$("#msg .content").css('transform', 'rotate('+Orientation+'deg)');
//					$("#msg .content").css('transform-origin', '50% 50%');
//				}else{
//					if(width > height) {
//						Orientation = "0";
//						print.width(width);
//						print.height(height);
//						print.css('top', 0);
//						print.css('left', 0);
//						print.css('transform', 'none');
//						print.css('transform-origin', '50% 50%');
//					} else {
//						Orientation = "90";
//						print.width(height);
//						print.height(width);
//						print.css('top', (height - width) / 2);
//						print.css('left', 0 - (height - width) / 2);
//						print.css('transform', 'rotate(90deg)');
//						print.css('transform-origin', '50% 50%');
//					}
//					$("#msg .content").css('transform', 'rotate('+Orientation+'deg)');
//					$("#msg .content").css('transform-origin', '50% 50%');
//				}
//			}, false);
			// 获取请求单个请求数据
			function GetQueryString(name) {
				var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
				var r = window.location.search.substr(1).match(reg);
				if(r != null) return decodeURI(r[2]);
				return "";
			}
//			console.log(window.location);
			var reward = "";
			if(GetQueryString("reward")!=""){
				reward = GetQueryString("reward");
				$('.Qtxt p span').text("("+reward+")");
			}else{
				$('.Qtxt p span').text("");
			}
			
			
			var timer = "";
			if(GetQueryString("timer")!=""){
				timer = GetQueryString("timer").split("-");
				timer = timer[0]+"月"+timer[1]+"日"+timer[2]+"点";
			}
			
			$('.Qb p span').text(timer);
			
			// 点击开始填写
			$('.Qb a').on("click",function(){
				if(GetQueryString("serverid")==""||GetQueryString("userid")==""||GetQueryString("nper")==""){
					Alert("参数获取失败！");
				}else{
					window.location.href="FOquestionnaire.php?serverid="+GetQueryString("serverid")+"&userid="+GetQueryString("userid")+"&nper="+GetQueryString("nper");
				}
			});
		</script>
	</body>

</html>