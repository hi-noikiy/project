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
											<p>任务入库</p>
										</div>
										<div class="admin_menu_aggregate">
											<img src="img/son.png" />
											<p>任务待审核</p>
										</div>
										<div class="admin_menu_aggregate">
											<img src="img/son.png" />
											<p>任务查询</p>
										</div>
										<div class="admin_menu_aggregate">
											<img src="img/son.png" />
											<p>任务条件管理</p>
										</div>
										<div class="admin_menu_aggregate">
											<img src="img/son.png" />
											<p>场景图管理</p>
										</div>
									</div>
								</div>
								<!---->
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="admin_content">
				
			</div>
		</div>
		<script type="text/javascript">
			var main = $('.admin_menu_box .admin_menu_main p');
			// 点击主菜单显示或隐藏
			main.on("click", function() {
				var index = $(this).parents().parents().index();
				$('.admin_menu_box').eq(index).find(".admin_menu_son").toggle();
			});
			// 点击子菜单
			var son = $('.admin_menu_box .admin_menu_son .admin_menu_aggregate p');
			son.on("click", function() {
				$('.admin_menu_box .admin_menu_son .admin_menu_aggregate').removeClass("admin_son_active");
				$(this).parent().addClass("admin_son_active");
			});
			// 加载页面
									$(".admin_content").load("html/DressPicing.html");
		</script>
	</body>

</html>