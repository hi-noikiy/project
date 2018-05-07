<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>衣范儿后台管理系统</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link rel="stylesheet" type="text/css" href="css/fashion_edit.css" />
		<script src="js/jquery.js" type="text/javascript" charset="utf-8"></script>
		<!--<script src="js/template-web.js" type="text/javascript" charset="utf-8"></script>-->
	</head>

	<body>
		<div class="admin_box">
			<div class="admin_menu">
				<div class="admin_menu_peripheral">
					<div class="admin_menu_within">
						<div class="admin_menu_hidden">
							<div class="admin_menu_content">
								<!--菜单-->
								<div class="admin_menu_box">
									<div class="admin_menu_main">
										<p>任务管理</p>
									</div>
									<div class="admin_menu_son">
										<div class="admin_menu_aggregate">
											<img src="img/son.png" />
											<p>任务条件管理</p>
										</div>
									</div>
								</div>
								<!---->
								<div class="admin_menu_box">
									<div class="admin_menu_main">
										<p>服饰配置</p>
									</div>
									<div class="admin_menu_son">
										<div class="admin_menu_aggregate">
											<img src="img/son.png" />
											<p>服饰定价</p>
										</div>
										<div class="admin_menu_aggregate">
											<img src="img/son.png" />
											<p>品牌属性</p>
										</div>
										<div class="admin_menu_aggregate">
											<img src="img/son.png" />
											<p>服饰查询</p>
										</div>
									</div>
								</div>
								<div class="admin_menu_box">
									<div class="admin_menu_main">
										<p>系统状态</p>
									</div>
									<div class="admin_menu_son">
										<p>当前登录用户:<span style="color: red;"></span></p>
										<p>上次登录日期:<span></span></p>
										<p>上次登录时间:<span></span></p>
										<div class="admin_menu_system">
											<a>网站首页</a>
											<a>退出</a>
											<img src="img/son.png" />
										</div>
										<a id="locS" style="color: #000099;cursor: pointer;font-size: 13px;margin-left: 10px;">清除缓存</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="admin_content">
				<iframe src="html/welcome.php" style="width: 100%;height: 100%;"></iframe>
			</div>
		</div>
		<script type="text/javascript">
			function calc() {
				var w = $(".admin_menu_hidden").width();
				var w2 = $(".admin_box").width();
				$(".admin_menu_content").css("width", w + 17 + "px");
				$(".admin_content").css("width", w2 - 200 + "px");
			}

			$(window).resize(function() {
				calc();
			});
			calc();
			var main = $('.admin_menu_box .admin_menu_main p');
			// 点击主菜单显示或隐藏
			main.on("click", function() {
				var index = $(this).parents().parents().index();
				$('.admin_menu_box').eq(index).find(".admin_menu_son").toggle();
			});
			//			var cookie = window.setCookie();
			// 点击子菜单
			var son = $('.admin_menu_box .admin_menu_son .admin_menu_aggregate p');
			son.on("click", function() {
				$('.admin_menu_box .admin_menu_son .admin_menu_aggregate').removeClass("admin_son_active");
				$(this).parent().addClass("admin_son_active");
				//				$(".admin_content iframe").attr("src","html/BrandNew.php");
				var whf = window.location.href.split("?")[0] + "?";
				if($(this).text() == "任务条件管理") {
					window.location.href = whf + "#TaskConditions";
					$(".admin_content iframe").attr("src", "html/TaskConditions.php?TaskConditions");
				} else if($(this).text() == "服饰定价") {
					window.location.href = whf + "#DressPicing";
					$(".admin_content iframe").attr("src", "html/DressPicing.php");
				} else if($(this).text() == "品牌属性") {
					window.location.href = whf + "#BrandNew";
					$(".admin_content iframe").attr("src", "html/TaskConditions.php?BrandNew");
				} else if($(this).text() == "服饰查询") {
					window.location.href = whf + "#CostumeInquiry";
					$(".admin_content iframe").attr("src", "html/CostumeInquiry.php");
				}
				//				console.log($(this).text());
			});
			(function() {
				var whf = window.location.href.split("#")[1];
				var son = $('.admin_menu_aggregate');
				son.each(function() {
					if($(this).find("p").text() == "任务条件管理" && whf == "TaskConditions") {
						$(this).find("p").parent().addClass("admin_son_active");
					} else if($(this).find("p").text() == "服饰定价" && whf == "DressPicing") {
						$(this).find("p").parent().addClass("admin_son_active");
					} else if($(this).find("p").text() == "品牌属性" && whf == "BrandNew") {
						$(this).find("p").parent().addClass("admin_son_active");
					} else if($(this).find("p").text() == "服饰查询" && whf == "CostumeInquiry") {
						$(this).find("p").parent().addClass("admin_son_active");
					}
				});
				if(whf == "TaskConditions") {
					$(".admin_content iframe").attr("src", "html/TaskConditions.php?TaskConditions");
				} else if(whf == "CostumeInquiry") {
					$(".admin_content iframe").attr("src", "html/CostumeInquiry.php");
				} else if(whf == "DressPicing") {
					$(".admin_content iframe").attr("src", "html/DressPicing.php");
				} else if(whf == "BrandNew") {
					$(".admin_content iframe").attr("src", "html/TaskConditions.php?BrandNew");
				} else {
					$(".admin_content iframe").attr("src", "html/welcome.php");
				}
			})();
			// 点击清除缓存
			$("#locS").on("click",function(){
				if (confirm("是否清除缓存?")) {
					localStorage.clear();
					alert("清除成功!");
				}
			});
		</script>
	</body>

</html>