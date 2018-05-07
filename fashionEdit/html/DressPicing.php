<link rel="stylesheet" type="text/css" href="../css/fashion_edit.css" />
<link rel="stylesheet" type="text/css" href="../css/DressPicing.css" />
<script src="../js/jquery.js" type="text/javascript" charset="utf-8"></script>
<script src="../js/configure.js" type="text/javascript" charset="utf-8"></script>
<div class="admin_content_DressPricing admin_content_title">
	<div class="admin_content_h1">
		<h1>服饰定价</h1>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>是否定价：</p>
				</div>
				<div class="DressPricingF">
					<select id="DressPricing_dinj">
						<option value="0">未定价</option>
						<option value="1">已定价</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>审核状态：</p>
				</div>
				<div class="DressPricingF">
					<select id="DressPricing_shenh">
						<option value="1">审核通过</option>
						<option value="2">审核未通过</option>
						<option value="0">未审核</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>服饰命名：</p>
				</div>
				<div class="DressPricingF">
					<input type="text" id="name" value="" />
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>icon：</p>
				</div>
				<div class="DressPricingF">
					<input type="text" id="icon" value="" />
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>档次：</p>
				</div>
				<div class="DressPricingF">
					<input onkeyup="value=value.replace(/[^\d]/g,'')" type="text" id="danc" value="" />
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>服饰类型<span style="color: red;">*</span>：</p>
				</div>
				<div class="DressPricingF">
					<select id="sort">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>发布季节：</p>
				</div>
				<div class="DressPricingF">
					<select id="season">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>品牌：</p>
				</div>
				<div class="DressPricingF">
					<input type="text" id="brand" value="" />
					<select id="brandv">
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>风格：</p>
				</div>
				<div class="DressPricingF">
					<select id="style1">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>风格：</p>
				</div>
				<div class="DressPricingF">
					<select id="style2">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>颜色：</p>
				</div>
				<div class="DressPricingF">
					<select id="color1">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>颜色：</p>
				</div>
				<div class="DressPricingF">
					<select id="color2">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>图案：</p>
				</div>
				<div class="DressPricingF">
					<select id="pattern1">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>材质：</p>
				</div>
				<div class="DressPricingF">
					<select id="material1">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>领型：</p>
				</div>
				<div class="DressPricingF">
					<select id="collar">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>版型/款式：</p>
				</div>
				<div class="DressPricingF">
					<select id="model">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF">
					<p>流行元素：</p>
				</div>
				<div class="DressPricingF">
					<select id="pop_element1">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="DressPricing_queryBox">
			<div class="DressPricing_condition">
				<div class="DressPricingF"></div>
				<div class="DressPricingF">
					<button id="search">搜索</button>
					<button id="clear" style="margin-left: 100px;">清除</button>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<div class="DressPricing_content">
		<div class="DressPricing_title">
			<div class="DressPricing_titleF">服饰命名</div>
			<div class="DressPricing_titleF">服饰ID</div>
			<div class="DressPricing_titleF">档次</div>
			<div class="DressPricing_titleF">价格1</div>
			<div class="DressPricing_titleF">价格2</div>
			<div class="DressPricing_titleF">百分比</div>
			<div class="DressPricing_titleF">数量</div>
			<div class="DressPricing_titleF">操作</div>
			<div class="clearfix"></div>
		</div>
		<div class="DressPricing_box">
			<!--<div class="DressPricing_text">
				<div class="DressPricing_textF text_left">101</div>
				<div class="DressPricing_textF text_left">嘻嘻嘻</div>
				<div class="DressPricing_textF text_left">数据</div>
				<div class="DressPricing_textF text_left">数据</div>
				<div class="DressPricing_textF text_left">数据</div>
				<div class="DressPricing_textF text_left">数据</div>
				<div class="DressPricing_textF text_left">数据</div>
				<div class="DressPricing_textF text_left"><label for="r" style="display:block;width: 100%;height: 100%;"><input checked="checked" name="rdo" type="radio" id="r" /></label></div>
				<div class="clearfix"></div>
			</div>-->
		</div>

	</div>
	<div class="DressPricing_imgBox">
		<div class="DressPricing_imgBoxT" id="imgBox_title">
			<span>共有：<span id="all">0</span>件&nbsp;</span>
			<span>已配置：<span id="pz">0</span>&nbsp;</span>
			<span>配置数量：<span id="pzbl">0</span>&nbsp;</span>
		</div>
		<div class="DressPricing_img">
			<!--<label class="DressPricing_imgF">
				<img src="../img/main.png" />
				<p>绿彩色运动背心</p>
				<p>4164654</p>
				<input type="checkbox" name="" id="" value="" /><span>464</span>
			</label>
			<label class="DressPricing_imgF">
				<img src="../img/main.png" />
				<p>绿彩色运动背心</p>
				<p>4164654</p>
				<input type="checkbox" name="" id="" value="" /><span>464</span>
			</label>
			<label class="DressPricing_imgF">
				<img src="../img/main.png" />
				<p>绿彩色运动背心</p>
				<p>4164654</p>
				<input type="checkbox" name="" id="" value="" /><span>464</span>
			</label>
			<label class="DressPricing_imgF">
				<img src="../img/main.png" />
				<p>绿彩色运动背心</p>
				<p>4164654</p>
				<input type="checkbox" name="" id="" value="" /><span>464</span>
			</label>
			<label class="DressPricing_imgF">
				<img src="../img/main.png" />
				<p>绿彩色运动背心</p>
				<p>4164654</p>
				<input type="checkbox" name="" id="" value="" /><span>464</span>
			</label>
			<label class="DressPricing_imgF">
				<img src="../img/main.png" />
				<p>绿彩色运动背心</p>
				<p>4164654</p>
				<input type="checkbox" name="" id="" value="" /><span>464</span>
			</label>
			<label class="DressPricing_imgF">
				<img src="../img/main.png" />
				<p>绿彩色运动背心</p>
				<p>4164654</p>
				<input type="checkbox" name="" id="" value="" /><span>464</span>
			</label>
			<div class="clearfix"></div>-->
		</div>
		<div class="DressPricing_imgBoxT" id="imgBox_tb">
			<span id="tb" style="cursor: pointer;">同步数据</span>
			<span style="color: red;">(配置好数据之后，再点击同步)</span>
		</div>
	</div>
	<script type="text/javascript">
		function Flen() {
			var len = $(".DressPricing_title .DressPricing_titleF").length;
			$(".DressPricing_title .DressPricing_titleF").css("width", 100 / len + "%");
			$(".DressPricing_text .DressPricing_textF").css("width", 100 / len + "%");
		}
		Flen();

		function postFar_id(data) {
			$("#load").css("display", "block");
			var dl0 = $.post(ip + "/interface/index.php/menu/index", data[0], function(data) {
				var data = JSON.parse(data);
				for(var i = 0; i < data.data.length; i++) {
					$("#sort").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			});
			var dl1 = $.post(ip + "/interface/index.php/menu/index", data[1], function(data) {
				var data = JSON.parse(data);
				for(var i = 0; i < data.data.length; i++) {
					$("#season").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			});
			var dl3 = $.post(ip + "/interface/index.php/menu/index", data[2], function(data) {
				var data = JSON.parse(data);
				for(var i = 0; i < data.data.length; i++) {
					$("#style1").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			});
			var dl4 = $.post(ip + "/interface/index.php/menu/index", data[3], function(data) {
				var data = JSON.parse(data);
				for(var i = 0; i < data.data.length; i++) {
					$("#style2").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			});
			var dl5 = $.post(ip + "/interface/index.php/menu/index", data[4], function(data) {
				var data = JSON.parse(data);
				for(var i = 0; i < data.data.length; i++) {
					$("#color1").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			});
			var dl6 = $.post(ip + "/interface/index.php/menu/index", data[5], function(data) {
				var data = JSON.parse(data);
				for(var i = 0; i < data.data.length; i++) {
					$("#color2").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			});
			var dl7 = $.post(ip + "/interface/index.php/menu/index", data[6], function(data) {
				var data = JSON.parse(data);
				for(var i = 0; i < data.data.length; i++) {
					$("#pattern1").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			});
			var dl8 = $.post(ip + "/interface/index.php/menu/index", data[7], function(data) {
				var data = JSON.parse(data);
				for(var i = 0; i < data.data.length; i++) {
					$("#material1").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			});
			var dl9 = $.post(ip + "/interface/index.php/menu/index", data[8], function(data) {
				var data = JSON.parse(data);
				for(var i = 0; i < data.data.length; i++) {
					$("#collar").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			});
			var dl10 = $.post(ip + "/interface/index.php/menu/index", data[9], function(data) {
				var data = JSON.parse(data);
				for(var i = 0; i < data.data.length; i++) {
					$("#model").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			});
			var dl11 = $.post(ip + "/interface/index.php/menu/index", data[10], function(data) {
				var data = JSON.parse(data);
				for(var i = 0; i < data.data.length; i++) {
					$("#pop_element1").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			});
			$.when(dl0, dl1, dl3, dl4, dl5, dl6, dl7, dl8, dl9, dl10, dl11).done(function() {
				$("#load").css("display", "none");
				postFar_id2();
			});
		}

		function postFar_id2() {
			$(".DressPricingF").off('change', "select");
			$(".DressPricingF").on("change", "select", function() {
				var Sthis = $(this);
				var options = $(this).find("option:selected");
				var params = $(this).parent();
				var farid = {
					"far_id": options.val()
				};
				$(this).nextAll().remove();
				if(options.val() == "" || Sthis.attr("id") == "DressPricing_shenh" || Sthis.attr("id") == "DressPricing_dinj") {
					return;
				}
				$("#load").css("display", "block");
				$.post(ip + "/interface/index.php/menu/index", farid, function(data) {
					$("#load").css("display", "none");
					var data = JSON.parse(data);
					//					console.log(data);
					if(data.data.length > 0) {
						var html = '<select><option value="">——请选择——</option>';
						for(var i = 0; i < data.data.length; i++) {
							html += '<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>';
						}
						html += '</select>';
						params.append(html);
					}
				});
				postFar_id2();
			});

		}
		var faridArr = [];
		var farid1 = {
			"far_id": 17
		};
		faridArr.push(farid1);
		var farid2 = {
			"far_id": 18
		};
		faridArr.push(farid2);
		var farid3 = {
			"far_id": 19
		};
		faridArr.push(farid3);
		var farid4 = {
			"far_id": 19
		};
		faridArr.push(farid4);
		var farid5 = {
			"far_id": 20
		};
		faridArr.push(farid5);
		var farid6 = {
			"far_id": 20
		};
		faridArr.push(farid6);
		var farid7 = {
			"far_id": 21
		};
		faridArr.push(farid7);
		var farid8 = {
			"far_id": 22
		};
		faridArr.push(farid8);
		var farid9 = {
			"far_id": 26
		};
		faridArr.push(farid9);
		var farid10 = {
			"far_id": 25
		};
		faridArr.push(farid10);
		var farid11 = {
			"far_id": 95
		};
		faridArr.push(farid11);
		postFar_id(faridArr);
		$("#danc").on("input", function() {
			var value = $(this).val();
			if(value > 10) {
				value = value.substr(0, value.length - 1);
				$(this).val(value);
			}

		});
		var sort1text = "";
		var name = "";
		var icon = "";
		var shenh = "";
		var sort1 = "";
		var sort2 = "";
		var season = "";
		var brand = "";
		var style11 = "";
		var style12 = "";
		var style21 = "";
		var style22 = "";
		var color1 = "";
		var color2 = "";
		var pattern1 = "";
		var material1 = "";
		var collar = "";
		var model = "";
		var pop_element1 = "";
		var dinj = "";
		var danc = "";

		function postSearch(data) {
			$("#load").css("display", "block");
			$.post(ip + "/interface/index.php/price/index", data, function(data) {
				$("#load").css("display", "none");
				if(data != "") {
					var data = JSON.parse(data);
					var html = "";
					var qdata=[];  
					for (var key in data.qdata) {  
					     qdata.push(data.qdata[key]);
					} 
					for(var i = 0; i < qdata.length; i++) {
						html += '<div class="DressPricing_text">' +
							'<div class="DressPricing_textF text_left">' + sort1text + '</div>' +
							'<div class="DressPricing_textF text_left">' + qdata[i].id + '</div>' +
							'<div class="DressPricing_textF text_left">' + qdata[i].level + '</div>' +
							'<div class="DressPricing_textF text_left">' + qdata[i].price_min + '</div>' +
							'<div class="DressPricing_textF text_left">' + qdata[i].price_max + '</div>' +
							'<div class="DressPricing_textF text_left">' + qdata[i].proportion + '%</div>' +
							'<div class="DressPricing_textF text_left">' + qdata[i].num + '</div>';
						if(i == 0) {
							html += '<div class="DressPricing_textF text_left"><label for="r' + i + '" style="display:block;width: 100%;height: 100%;"><input checked="checked" name="rdo" type="radio" id="r' + i + '" /></label></div>';
						} else {
							html += '<div class="DressPricing_textF text_left"><label for="r' + i + '" style="display:block;width: 100%;height: 100%;"><input name="rdo" type="radio" id="r' + i + '" /></label></div>';
						}

						html += '<div class="clearfix"></div></div>';
					}
					$(".DressPricing_box").html(html);
					Flen();
					var html2 = "";
					var countAll = 0;
					for(var i = 0; i < data.data.length; i++) {
						if(dinj == 1) {
							if(data.data[i].level == 1) {
								countAll++;
								html2 += '<label class="DressPricing_imgF" for="g' + i + '">' +
									'<img style="height:200px;" src="' + ip2 + '/shizhuang/upload/itemtypeImage/small_100/' + data.data[i].icon + '.jpg" />' +
									'<p class="img_name" v="' + data.data[i].id + '">' + data.data[i].name + '</p>' +
									'<p class="img_icon">' + data.data[i].icon + '</p>';
								if(data.data[i].snapicon == 1) {
									html2 += '<input type="checkbox"  id="g' + i + '" value="" /><span snapicon="1">' + data.data[i].price + '</span>';
								} else {
									html2 += '<input type="checkbox"  id="g' + i + '" value="" /><span leven="0" snapicon="1"></span>';
								}
								html2 += '</label>';
							}
						} else {
							countAll = data.count;
							html2 += '<label class="DressPricing_imgF" for="g' + i + '">' +
								'<img style="height:200px;" src="' + ip2 + '/shizhuang/upload/itemtypeImage/small_100/' + data.data[i].icon + '.jpg" />' +
								'<p class="img_name" v="' + data.data[i].id + '">' + data.data[i].name + '</p>' +
								'<p class="img_icon">' + data.data[i].icon + '</p>';
							if(data.data[i].snapicon == 1) {
								html2 += '<input type="checkbox"  id="g' + i + '" value="" /><span snapicon="1">' + data.data[i].price + '</span>';
							} else {
								html2 += '<input type="checkbox"  id="g' + i + '" value="" /><span leven="0" snapicon="1"></span>';
							}
							html2 += '</label>';
						}
					}
					html2 += '<div class="clearfix"></div>';
					$('.DressPricing_img').html(html2);
					$("#all").text(countAll);
					for(var i = 0; i < $(".DressPricing_text").length; i++) {
								if($(".DressPricing_text").eq(i).find("input").get(0).checked) {
									$("#pzbl").text($(".DressPricing_text").eq(i).find(".DressPricing_textF").eq(6).text());
								}
							}
					var count = 0;
					$(".DressPricing_img").off("click", ".DressPricing_imgF input");
					$(".DressPricing_img").on("click", ".DressPricing_imgF input", function() {
						var check = $(this).get(0).checked;
						if(check) {
							for(var i = 0; i < $(".DressPricing_text").length; i++) {
								if($(".DressPricing_text").eq(i).find("input").get(0).checked) {
									var max = $(".DressPricing_text").eq(i).find(".DressPricing_textF").eq(4).text();
									var min = $(".DressPricing_text").eq(i).find(".DressPricing_textF").eq(3).text();
									if(dinj == 0) {
										var lev = $(".DressPricing_text").eq(i).find(".DressPricing_textF").eq(2).text();
									} else {
										var lev = 0;
									}

								}
							}
							if(dinj == 0) {
								var value = Math.floor(Math.random() * (Number(max) - Number(min) + 1) + Number(min));
							} else {
								var value = 0;
							}
							if(count >= $("#pzbl").text()) {
								alert("超过配置数量了！");
								$(this).attr("checked", false);
								return;
							}
							$(this).next().text(value);
							$(this).next().attr("leven", lev);
							count++;
							$("#pz").text(count);
						} else {
							$(this).next().text("");
							count--;
							$("#pz").text(count);
						}
					});
					$(".DressPricing_text label input").off("click");
					$(".DressPricing_text label input").on("click", function() {
						$(".DressPricing_imgF input").attr("checked",false);
						count=0;
						for(var i = 0; i < $(".DressPricing_text").length; i++) {
								if($(".DressPricing_text").eq(i).find("input").get(0).checked) {
									$("#pzbl").text($(".DressPricing_text").eq(i).find(".DressPricing_textF").eq(6).text());
								}
							}
						$("#pz").text("0");
						$(".DressPricing_imgF span").text("");
						if(dinj == 1) {
							count = 0;
							var d = $(this).parent().parent().parent().find(".DressPricing_textF").eq(2).text();
							var html2 = "";
							var countAll = 0;
							for(var i = 0; i < data.data.length; i++) {
								if(data.data[i].level == d) {
									countAll++;
									html2 += '<label class="DressPricing_imgF" for="g' + i + '">' +
										'<img style="height:200px" src="' + ip2 + '/shizhuang/upload/itemtypeImage/small_100/' + data.data[i].icon + '.jpg" />' +
										'<p class="img_name" v="' + data.data[i].id + '">' + data.data[i].name + '</p>' +
										'<p class="img_icon">' + data.data[i].icon + '</p>';
									if(data.data[i].snapicon == 1) {
										html2 += '<input type="checkbox"  id="g' + i + '" value="" /><span snapicon="1">' + data.data[i].price + '</span>';
									} else {
										html2 += '<input type="checkbox"  id="g' + i + '" value="" /><span leven="0" snapicon="1"></span>';
									}
									html2 += '</label>';
								}
							}
							html2 += '<div class="clearfix"></div>';
							$('.DressPricing_img').html(html2);
							$("#all").text(countAll);
						}
					});

				}

			});
		};
		// 点击同步
		$("#tb").on("click", function() {
			$("#load").css("display", "block");
			setTimeout(function() {
				var level = 0;
				var countPost = 0;
				var allPost = 0;
				var topc = Number($(".admin_content_h1").height()) + 20;
					topc = topc + Number($(".DressPricing_content").height()) + 84;
					$(".admin_content_title").scrollTop(topc);
				function postUpdate(data) {
					$("#load").css("display", "block");
					$.post(ip + "/interface/index.php/price/update", data, function(data) {
						$("#load").css("display", "none");
						countPost++;
						var data = JSON.parse(data);
						if(data.status == 0) {
							if(countPost == allPost) {
								alert("同步成功!");
								var data2 = {
									"name": name,
									"icon": icon,
									"check_status": shenh,
									"sort": sort1,
									"sort2": sort2,
									"season": season,
									"brand": brand,
									"style1": style11,
									"style21": style12,
									"style2": style21,
									"style22": style22,
									"color1": color1,
									"color2": color2,
									"pattern1": pattern1,
									"material1": material1,
									"collar": collar,
									"model": model,
									"pop_element1": pop_element1,
									"snapicon": dinj,
									"level": danc
								};
								postSearch(data2);
								$("#pz").text("0");
								$("#pzbl").text("0");
							}
						} else {
							alert("同步失败!");
						}

					});
				}
				for(var i = 0; i < $(".DressPricing_imgF input").length; i++) {
					if($(".DressPricing_imgF input").eq(i).get(0).checked) {
						allPost++;
					}
				}
				if(allPost == 0) {
					$("#load").css("display", "none");
				}
				for(var i = 0; i < $(".DressPricing_imgF input").length; i++) {
					if($(".DressPricing_imgF input").eq(i).get(0).checked) {
						var id = $(".DressPricing_imgF input").eq(i).parent().find(".img_name").attr("v");
						var price = $(".DressPricing_imgF input").eq(i).parent().find("span").text();
						var snapicon = $(".DressPricing_imgF input").eq(i).parent().find("span").attr("snapicon");
						level = $(".DressPricing_imgF input").eq(i).parent().find("span").attr("leven");
						var data = {
							"level": level,
							"id": id,
							"snapicon": snapicon,
							"price": price
						};
						postUpdate(data);
					}
				}
			}, 100);
		});
		// 点击搜索
		$("#search").on("click", function() {
			dinj = $("#DressPricing_dinj").val();
			shenh = $("#DressPricing_shenh").val();
			name = $("#name").val();
			icon = $("#icon").val();
			danc = $("#danc").val();
			var sort = $("#sort").parent().find("select");
			if(sort.length >= 2) {
				sort1 = sort.eq(sort.length - 2).find("option:selected").val();
				sort1text = sort.eq(sort.length - 2).find("option:selected").text();
				sort2 = sort.eq(sort.length - 1).find("option:selected").val();
			} else {
				sort1 = sort.eq(sort.length - 1).find("option:selected").val();
				sort1text = sort.eq(sort.length - 2).find("option:selected").text();
				sort2 = "";
			}
			if(sort1 == "") {
				alert("服务类型必选！");
				return;
			}
			season = $("#season").val();
			var brand = $("#brandv").val();
			var style1 = $("#style1").parent().find("select");
			if(style1.length >= 2) {
				style11 = style1.eq(style1.length - 2).find("option:selected").val();
				style12 = style1.eq(style1.length - 1).find("option:selected").val();
			} else {
				style11 = style1.eq(style1.length - 1).find("option:selected").val();
				style12 = "";
			}
			var style2 = $("#style2").parent().find("select");
			if(style2.length >= 2) {
				style21 = style2.eq(style2.length - 2).find("option:selected").val();
				style22 = style2.eq(style2.length - 1).find("option:selected").val();
			} else {
				style21 = style2.eq(style2.length - 1).find("option:selected").val();
				style22 = "";
			}

			color1 = $("#color1").val();
			color2 = $("#color2").val();
			pattern1 = $("#pattern1").val();
			material1 = $("#material1").val();
			collar = $("#collar").val();
			model = $("#model").val();
			pop_element1 = $("#pop_element1").val();
			var data = {
				"name": name,
				"icon": icon,
				"check_status": shenh,
				"sort": sort1,
				"sort2": sort2,
				"season": season,
				"brand": brand,
				"style1": style11,
				"style21": style12,
				"style2": style21,
				"style22": style22,
				"color1": color1,
				"color2": color2,
				"pattern1": pattern1,
				"material1": material1,
				"collar": collar,
				"model": model,
				"pop_element1": pop_element1,
				"snapicon": dinj,
				"level": danc
			};
			$("#load").css("display", "block");
			postSearch(data);

		});
		$("#brand").on("input", function() {
			var name = $(this).val();
			if(name != "") {
				var data = {
					"name": name,
					"limit": 999
				};
				$("#load").css("display", "block");
				$.post(ip + "/interface/index.php/brand/index", data, function(data) {
					$("#load").css("display", "none");
					var data = JSON.parse(data);
					var html = "";
					for(var i = 0; i < data.data.length; i++) {
						html += '<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>';
					}
					$("#brandv").html(html);
				});
			} else {
				$("#brandv").empty();
			}

		});
		// 点击清除
		$("#clear").on("click", function() {
			$("#name").val("");
			$("#icon").val("");
			$("#danc").val("");
			$("#sort").nextAll().remove();
			$("#sort option").attr("selected", false);
			$("#season").nextAll().remove();
			$("#season option").attr("selected", false);
			$("#brand").val("");
			$("#brandv").empty();
			$("#style1").nextAll().remove();
			$("#style1 option").attr("selected", false);
			$("#style2").nextAll().remove();
			$("#style2 option").attr("selected", false);
			$("#color1").nextAll().remove();
			$("#color1 option").attr("selected", false);
			$("#color2").nextAll().remove();
			$("#color2 option").attr("selected", false);
			$("#pattern1").nextAll().remove();
			$("#pattern1 option").attr("selected", false);
			$("#material1").nextAll().remove();
			$("#material1 option").attr("selected", false);
			$("#collar").nextAll().remove();
			$("#collar option").attr("selected", false);
			$("#model").nextAll().remove();
			$("#model option").attr("selected", false);
			$("#pop_element1").nextAll().remove();
			$("#pop_element1 option").attr("selected", false);
		});
		$(".admin_content_title").scroll(function() {
			var topp = $(this).scrollTop();
			var topc = Number($(".admin_content_h1").height()) + 20;
			topc = topc + Number($(".DressPricing_content").height()) + 84;
			var hh = Number($("body").height()) - 30;
			var imgLh = Number($(".DressPricing_img").height()) + 32;
			if(topp >= topc) {
				$("#imgBox_title").css("top", topp - topc + "px");
				if(imgLh > Number($("body").height())) {
					$("#imgBox_tb").css({
						"position": "absolute",
						"top": topp - topc + hh + "px"
					});
				} else {
					$("#imgBox_tb").css({
						"position": "relative",
						"top": "0px"
					});
				}
			} else {
				$("#imgBox_title").css("top", "0px");
				$("#imgBox_tb").css({
					"position": "relative",
					"top": "0px"
				});
			}
		});
		$(window).resize(function() {
			var topp = $(".admin_content_title").scrollTop();
			var topc = Number($(".admin_content_h1").height()) + 20;
			topc = topc + Number($(".DressPricing_content").height()) + 84;
			var hh = Number($("body").height()) - 30;
			var imgLh = Number($(".DressPricing_img").height()) + 32;
			if(topp >= topc) {
				$("#imgBox_title").css("top", topp - topc + "px");
				if(imgLh > Number($("body").height())) {
					$("#imgBox_tb").css({
						"position": "absolute",
						"top": topp - topc + hh + "px"
					});
				} else {
					$("#imgBox_tb").css({
						"position": "relative",
						"top": "0px"
					});
				}
			} else {
				$("#imgBox_title").css("top", "0px");
				$("#imgBox_tb").css({
					"position": "relative",
					"top": "0px"
				});
			}
		});
	</script>
</div>
<div id="load" style="position:fixed;width: 100%;height: 100%;z-index: 1000;top: 0px;left: 0px;display: none;">
	<img style="position: absolute;top: 50%;transform: translate(-50%,-50%);left: 50%;-webkit-transition: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);" src="../img/load.gif" />
</div>