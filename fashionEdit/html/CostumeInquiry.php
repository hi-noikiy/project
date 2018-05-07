<link rel="stylesheet" type="text/css" href="../css/fashion_edit.css" />
<link rel="stylesheet" type="text/css" href="../css/CostumeInquiry.css" />
<script src="../js/configure.js" type="text/javascript" charset="utf-8"></script>
<script src="../js/jquery.js" type="text/javascript" charset="utf-8"></script>
<div class="admin_content_CostumeInquiry admin_content_title">
	<div class="admin_content_h1">
		<h1>服饰查询</h1>
		<div class="CostumeInquiry_queryBox">
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>审核状态：</p>
				</div>
				<div class="CostumeInquiryF">
					<select id="CostumeInquiry_shenh">
						<option value="1">审核通过</option>
						<option value="2">审核未通过</option>
						<option value="0">未审核</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>服饰命名：</p>
				</div>
				<div class="CostumeInquiryF">
					<input type="text" id="CostumeInquiry_name" />
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>icon：</p>
				</div>
				<div class="CostumeInquiryF">
					<input type="text" id="CostumeInquiry_icon" />
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>服饰类型：</p>
				</div>
				<div class="CostumeInquiryF">
					<select id="dl0" name="">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>发布季节：</p>
				</div>
				<div class="CostumeInquiryF">
					<select id="dl1" name="">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>品牌：</p>
				</div>
				<div class="CostumeInquiryF">
					<input type="text" id="dl2" value="" />
					<select id="dl2v">
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>风格：</p>
				</div>
				<div class="CostumeInquiryF">
					<select id="dl3" name="">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>风格：</p>
				</div>
				<div class="CostumeInquiryF">
					<select id="dl4" name="">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>颜色：</p>
				</div>
				<div class="CostumeInquiryF">
					<select id="dl5" name="">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>颜色：</p>
				</div>
				<div class="CostumeInquiryF">
					<select id="dl11" name="">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>图案：</p>
				</div>
				<div class="CostumeInquiryF">
					<select id="dl6" name="">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>材质：</p>
				</div>
				<div class="CostumeInquiryF">
					<select id="dl7" name="">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>领型：</p>
				</div>
				<div class="CostumeInquiryF">
					<select id="dl8" name="">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>版型/款式：</p>
				</div>
				<div class="CostumeInquiryF">
					<select id="dl9" name="">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF">
					<p>流行元素：</p>
				</div>
				<div class="CostumeInquiryF">
					<select id="dl10" name="">
						<option value="">——请选择——</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_condition">
				<div class="CostumeInquiryF"></div>
				<div class="CostumeInquiryF">
					<button id="CostumeInquiry_search">搜索</button>
					<button id="CostumeInquiry_clear" style="margin-left: 100px;">清除</button>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<div class="CostumeInquiry_content">
		<div class="CostumeInquiry_auto">
			<div class="CostumeInquiry_title">
				<div class="CostumeInquiry_titleF" style="width: 5%;">id</div>
				<div class="CostumeInquiry_titleF" style="width: 7.5%;">服饰命名</div>
				<div class="CostumeInquiry_titleF" style="width: 6%;">类型</div>
				<div class="CostumeInquiry_titleF" style="width: 3%;">季节</div>
				<div class="CostumeInquiry_titleF" style="width: 7.5%;">品牌</div>
				<!--<div class="CostumeInquiry_titleF" style="width: 120px;">领型</div>
				<div class="CostumeInquiry_titleF" style="width: 120px;">版型/款式</div>-->
				<div class="CostumeInquiry_titleF" style="width: 12%;">风格</div>
				<div class="CostumeInquiry_titleF" style="width: 6%;">颜色</div>
				<div class="CostumeInquiry_titleF" style="width: 6%;">图案</div>
				<div class="CostumeInquiry_titleF" style="width: 6%;">材质</div>
				<div class="CostumeInquiry_titleF" style="width: 19%;">流行元素</div>
				<!--<div class="CostumeInquiry_titleF" style="width: 120px;">商店类型</div>
				<div class="CostumeInquiry_titleF" style="width: 120px;">价格</div>
				<div class="CostumeInquiry_titleF" style="width: 120px;">钻石</div>
				<div class="CostumeInquiry_titleF" style="width: 180px;">服饰上架时间</div>
				<div class="CostumeInquiry_titleF" style="width: 120px;">出售状态</div>
				<div class="CostumeInquiry_titleF" style="width: 180px;">新手物品开始时间</div>
				<div class="CostumeInquiry_titleF" style="width: 180px;">新手物品结束时间</div>-->
				<div class="CostumeInquiry_titleF" style="width: 8%;">icon 名</div>
				<!--<div class="CostumeInquiry_titleF" style="width: 120px;">是否定价</div>-->
				<div class="CostumeInquiry_titleF" style="width: 5%;">审核状态</div>
				<!--<div class="CostumeInquiry_titleF" style="width: 180px;">物品入库时间</div>-->
				<div class="CostumeInquiry_titleF" style="width: 9%;">操作</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeInquiry_textBox">
				<!--<div class="CostumeInquiry_text">
					<div class="CostumeInquiry_textF" style="width: 100px;">id</div>
					<div class="CostumeInquiry_textF text_left" style="width: 120px;">服饰命名</div>				
					<div class="CostumeInquiry_textF text_left" style="width: 280px;">类型</div>
					<div class="CostumeInquiry_textF text_left" style="width: 120px;">季节</div>
					<div class="CostumeInquiry_textF text_left" style="width: 120px;">品牌</div>
					<div class="CostumeInquiry_textF text_left" style="width: 120px;">领型</div>
					<div class="CostumeInquiry_textF text_left" style="width: 120px;">版型/款式</div>
					<div class="CostumeInquiry_textF text_left" style="width: 280px;">风格</div>
					<div class="CostumeInquiry_textF text_left" style="width: 280px;">颜色</div>
					<div class="CostumeInquiry_textF text_left" style="width: 280px;">图案</div>
					<div class="CostumeInquiry_textF text_left" style="width: 280px;">材质</div>
					<div class="CostumeInquiry_textF text_left" style="width: 800px;">流行元素</div>
					<div class="CostumeInquiry_textF text_left" style="width: 120px;">商店类型</div>
					<div class="CostumeInquiry_textF text_left" style="width: 120px;">价格</div>
					<div class="CostumeInquiry_textF text_left" style="width: 120px;">钻石</div>
					<div class="CostumeInquiry_textF text_left" style="width: 180px;">服饰上架时间</div>
					<div class="CostumeInquiry_textF text_left" style="width: 120px;">出售状态</div>
					<div class="CostumeInquiry_textF text_left" style="width: 180px;">新手物品开始时间</div>
					<div class="CostumeInquiry_textF text_left" style="width: 180px;">新手物品结束时间</div>
					<div class="CostumeInquiry_textF text_left" style="width: 220px;">美术资源 icon 名</div>
					<div class="CostumeInquiry_textF text_left" style="width: 120px;">是否定价</div>
					<div class="CostumeInquiry_textF text_left" style="width: 120px;">审核状态</div>
					<div class="CostumeInquiry_textF text_left" style="width: 180px;">物品入库时间</div>
					<div class="CostumeInquiry_textF text_left" style="width: 150px;"><span>编辑</span><span>删除</span><span>通过</span></div>
					<div class="clearfix"></div>
				</div>-->
			</div>
		</div>
		<div class="CostumeInquiry_page">
			<span>共<span class="CostumeInquiry_strip">0</span>条，</span>
			<span class="CostumeInquiry_jump" style="color: #000099;cursor: pointer;">跳转</span>
			<span>第<input class="CostumeInquiry_flip" style="width:35px;height: 20px;" onkeyup="value=value.replace(/[^\d]/g,'')" type="text" value="0" />/<span class="CostumeInquiry_total">0</span>页</span>
			<span class="CostumeInquiry_pageSet">
			<span class="CostumeInquiry_selectP CostumeInquiry_index">首页</span>
			<span class="CostumeInquiry_selectP CostumeInquiry_prev">上一页</span>
			<span class="CostumeInquiry_next">下一页</span>
			</span>
			<span class="CostumeInquiry_shadowe">尾页</span>
		</div>
	</div>
	<!--<button class="CostumeInquiry_news">添加</button>-->
</div>
<div id="load" style="position:fixed;width: 100%;height: 100%;z-index: 1000;top: 0px;left: 0px;display: none;">
	<img style="position: absolute;top: 50%;transform: translate(-50%,-50%);left: 50%;-webkit-transition: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);" src="../img/load.gif" />
</div>

<script type="text/javascript">
	function Flen() {
		var len = $(".CostumeInquiry_title .CostumeInquiry_titleF").length;
		var maxArr = [];
		for(var i = 0; i < $(".CostumeInquiry_text").length; i++) {
			var H1 = $(".CostumeInquiry_text").eq(i).find(".CostumeInquiry_textF").eq(0).height();
			maxArr.push(H1);
			for(var j = 0; j < $(".CostumeInquiry_text").eq(i).find(".CostumeInquiry_textF").length; j++) {
				var H2 = $(".CostumeInquiry_text").eq(i).find(".CostumeInquiry_textF").eq(j).height();
				if(maxArr[i] < H2) {
					maxArr[i] = H2;
				}
			}
			for(var j = 0; j < $(".CostumeInquiry_text").eq(i).find(".CostumeInquiry_textF").length; j++) {
				$(".CostumeInquiry_text").eq(i).find(".CostumeInquiry_textF").eq(j).css("height", maxArr[i] + "px");
			}
		}
	}
	Flen();

	function postFar_id(data) {
		$("#load").css("display", "block");
		var dl0 = $.post(ip + "/interface/index.php/menu/index", data[0], function(data) {
			var data = JSON.parse(data);
			for(var i = 0; i < data.data.length; i++) {
				$("#dl0").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
			}
		});
		var dl1 = $.post(ip + "/interface/index.php/menu/index", data[1], function(data) {
			var data = JSON.parse(data);
			for(var i = 0; i < data.data.length; i++) {
				$("#dl1").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
			}
		});
		var dl3 = $.post(ip + "/interface/index.php/menu/index", data[2], function(data) {
			var data = JSON.parse(data);
			for(var i = 0; i < data.data.length; i++) {
				$("#dl3").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
			}
		});
		var dl4 = $.post(ip + "/interface/index.php/menu/index", data[3], function(data) {
			var data = JSON.parse(data);
			for(var i = 0; i < data.data.length; i++) {
				$("#dl4").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
			}
		});
		var dl5 = $.post(ip + "/interface/index.php/menu/index", data[4], function(data) {
			var data = JSON.parse(data);
			for(var i = 0; i < data.data.length; i++) {
				$("#dl5").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
			}
		});
		var dl6 = $.post(ip + "/interface/index.php/menu/index", data[5], function(data) {
			var data = JSON.parse(data);
			for(var i = 0; i < data.data.length; i++) {
				$("#dl6").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
			}
		});
		var dl7 = $.post(ip + "/interface/index.php/menu/index", data[6], function(data) {
			var data = JSON.parse(data);
			for(var i = 0; i < data.data.length; i++) {
				$("#dl7").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
			}
		});
		var dl8 = $.post(ip + "/interface/index.php/menu/index", data[7], function(data) {
			var data = JSON.parse(data);
			for(var i = 0; i < data.data.length; i++) {
				$("#dl8").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
			}
		});
		var dl9 = $.post(ip + "/interface/index.php/menu/index", data[8], function(data) {
			var data = JSON.parse(data);
			for(var i = 0; i < data.data.length; i++) {
				$("#dl9").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
			}
		});
		var dl10 = $.post(ip + "/interface/index.php/menu/index", data[9], function(data) {
			var data = JSON.parse(data);
			for(var i = 0; i < data.data.length; i++) {
				$("#dl10").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
			}
		});
		var dl11 = $.post(ip + "/interface/index.php/menu/index", data[10], function(data) {
			var data = JSON.parse(data);
			for(var i = 0; i < data.data.length; i++) {
				$("#dl11").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
			}
		});
		$.when(dl0, dl1, dl3, dl4, dl5, dl6, dl7, dl8, dl9, dl10, dl11).done(function() {
			$("#load").css("display", "none");
			postFar_id2();
		});
	}

	function postFar_id2() {
		$(".CostumeInquiryF").off('change', "select");
		$(".CostumeInquiryF").on("change", "select", function() {
			var Sthis = $(this);
			var options = $(this).find("option:selected");
			var params = $(this).parent();
			var farid = {
				"far_id": options.val()
			};
			$(this).nextAll().remove();
			if(options.val() == "" || Sthis.attr("id") == "CostumeInquiry_shenh") {
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

	var page = 1;
	var shadowe = 0;
	var data = {
		"page": page,
		"check_status": 1
	};
	var name = "";
	var dictionaries = "";
	var brandD = ""
	var icon = "";
	var shenh = 1;
	var dl0v1 = "";
	var dl0v2 = "";
	var dl1v = "";
	var dl2v = "";
	var dl3v1 = "";
	var dl3v2 = "";
	var dl4v1 = "";
	var dl4v2 = "";
	var dl5v = "";
	var dl6v = "";
	var dl7v = "";
	var dl8v = "";
	var dl9v = "";
	var dl10v = "";
	var dl11v = "";
	var dataEmit = [];

	function Dictionaries(val) {
		var count = 0;
		for(var i = 0; i < dictionaries.data.length; i++) {
			if(dictionaries.data[i].id == val) {
				count++;
				return dictionaries.data[i].name
			}
		}
		if(count == 0) {
			if(val == 0) {
				return "";
			} else {
				return val;
			}

		}
	}

	function BrandD(val) {
		var count = 0;
		for(var i = 0; i < brandD.data.length; i++) {
			if(brandD.data[i].id == val) {
				count++;
				return brandD.data[i].name
			}
		}
		if(count == 0) {
			if(val == 0) {
				return "";
			} else {
				return val;
			}

		}
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
	var farid4 = {
		"far_id": 19
	};
	faridArr.push(farid4);
	var farid5 = {
		"far_id": 19
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
	var farid12 = {
		"far_id": 20
	};
	faridArr.push(farid12);
	postFar_id(faridArr);

	function postPage(data) {
		page = data.page;
		//				console.log(data);
		$("#load").css("display", "block");
		$.post(ip + "/interface/index.php/shopitem/index", data, function(data) {
			$("#load").css("display", "none");
			$(".CostumeInquiry_textBox").empty();
			if(data != "") {
				var data = JSON.parse(data);
				//		console.log(data);
				dataEmit = data.data;
				for(var i = 0; i < data.data.length; i++) {
					var html = '<div class="CostumeInquiry_text">' +
						'<div class="CostumeInquiry_textF" style="width: 5%;">' + data.data[i].id + '</div>' +
						'<div class="CostumeInquiry_textF text_left" style="width: 7.5%;">' + data.data[i].name + '</div>' +
						'<div class="CostumeInquiry_textF text_left" style="width: 6%;"><p style="display: inline;" v="' + data.data[i].sort + '">' + Dictionaries(data.data[i].sort) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].sort2 + '">' + Dictionaries(data.data[i].sort2) + '</p></div>' +
						'<div class="CostumeInquiry_textF text_left" style="width: 3%;"><p style="display: inline;" v="' + data.data[i].season + '">' + Dictionaries(data.data[i].season) + '</p></div>' +
						'<div class="CostumeInquiry_textF text_left" style="width: 7.5%;"><p style="display: inline;" v="' + data.data[i].brand + '">' + BrandD(data.data[i].brand) + '</p></div>' +
						//							'<div class="CostumeInquiry_textF text_left" style="width: 120px;"><p style="display: inline;" v="' + data.data[i].collar + '">' + Dictionaries(data.data[i].collar) + '</p></div>' +
						//							'<div class="CostumeInquiry_textF text_left" style="width: 120px;"><p style="display: inline;" v="' + data.data[i].model + '">' + Dictionaries(data.data[i].model) + '</p></div>' +
						'<div class="CostumeInquiry_textF text_left" style="width: 12%;"><p style="display: inline;" v="' + data.data[i].style1 + '">' + Dictionaries(data.data[i].style1) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].style21 + '">' + Dictionaries(data.data[i].style21) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].style2 + '">' + Dictionaries(data.data[i].style2) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].style22 + '">' + Dictionaries(data.data[i].style22) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].style23 + '">' + Dictionaries(data.data[i].style3) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].style23 + '">' + Dictionaries(data.data[i].style23) + '</p></div>' +
						'<div class="CostumeInquiry_textF text_left" style="width: 6%;"><p style="display: inline;" v="' + data.data[i].color1 + '">' + Dictionaries(data.data[i].color1) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].color2 + '">' + Dictionaries(data.data[i].color2) + '</p></div>' +
						'<div class="CostumeInquiry_textF text_left" style="width: 6%;"><p style="display: inline;" v="' + data.data[i].pattern1 + '">' + Dictionaries(data.data[i].pattern1) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].pattern2 + '">' + Dictionaries(data.data[i].pattern2) + '</p></div>' +
						'<div class="CostumeInquiry_textF text_left" style="width: 6%;"><p style="display: inline;" v="' + data.data[i].material1 + '">' + Dictionaries(data.data[i].material1) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].material2 + '">' + Dictionaries(data.data[i].material2) + '</p></div>' +
						'<div class="CostumeInquiry_textF text_left" style="width: 19%;"><p style="display: inline;" v="' + data.data[i].pop_element1 + '">' + Dictionaries(data.data[i].pop_element1) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].pop_element2 + '">' + Dictionaries(data.data[i].pop_element2) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].pop_element3 + '">' + Dictionaries(data.data[i].pop_element3) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].pop_element4 + '">' + Dictionaries(data.data[i].pop_element4) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].pop_element5 + '">' + Dictionaries(data.data[i].pop_element5) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].pop_element6 + '">' + Dictionaries(data.data[i].pop_element6) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].pop_element7 + '">' + Dictionaries(data.data[i].pop_element7) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].pop_element8 + '">' + Dictionaries(data.data[i].pop_element8) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].pop_element9 + '">' + Dictionaries(data.data[i].pop_element9) + '</p>&#x3000;<p style="display: inline;" v="' + data.data[i].pop_element10 + '">' + Dictionaries(data.data[i].pop_element10) + '</p></div>' +
						//							'<div class="CostumeInquiry_textF text_left" style="width: 120px;">' + data.data[i].shoptype + '</div>' +
						//							'<div class="CostumeInquiry_textF text_left" style="width: 120px;">' + data.data[i].price + '</div>' +
						//							'<div class="CostumeInquiry_textF text_left" style="width: 120px;">' + data.data[i].emoney + '</div>' +
						//							'<div class="CostumeInquiry_textF text_left" style="width: 180px;">' + data.data[i].shelves_time + '</div>' +
						//							'<div class="CostumeInquiry_textF text_left" style="width: 120px;">' + data.data[i].sale_status + '</div>' +
						//							'<div class="CostumeInquiry_textF text_left" style="width: 180px;">' + data.data[i].begin_time + '</div>' +
						//							'<div class="CostumeInquiry_textF text_left" style="width: 180px;">' + data.data[i].end_time + '</div>' +
						'<div class="CostumeInquiry_textF text_left" style="width: 8%;">' + data.data[i].icon + '</div>';
					//							if (data.data[i].snapicon == 0) {
					//								html+='<div class="CostumeInquiry_textF text_left" style="width: 120px;">未定价</div>';
					//							}else{
					//								html+='<div class="CostumeInquiry_textF text_left" style="width: 120px;">已定价</div>';
					//							}
					if(data.data[i].check_status == 2) {
						html += '<div class="CostumeInquiry_textF text_left" style="width: 5%;">审核不通过</div>';
					} else if(data.data[i].check_status == 1) {
						html += '<div class="CostumeInquiry_textF text_left" style="width: 5%;">审核通过</div>';
					} else {
						html += '<div class="CostumeInquiry_textF text_left" style="width: 5%;">未审核</div>';
					}

					//							html+='<div class="CostumeInquiry_textF text_left" style="width: 180px;">' + data.data[i].create_time + '</div>';
					if(data.data[i].check_status == 2) {
						html += '<div class="CostumeInquiry_textF text_left" style="width: 9%;"><span index="' + i + '">编辑</span><span>删除</span><span id="tg">通过</span></div>';
					} else if(data.data[i].check_status == 1) {
						html += '<div class="CostumeInquiry_textF text_left" style="width: 9%;"><span index="' + i + '">编辑</span><span>删除</span><span id="btg">不通过</span></div>';
					} else {
						html += '<div class="CostumeInquiry_textF text_left" style="width: 9%;"><span index="' + i + '">编辑</span><span>删除</span><span id="tg">通过</span><span id="btg">不通过</span></div>';
					}
					html += '<div class="clearfix"></div>' +
						'</div>';
					$(".CostumeInquiry_textBox").append(html);
				}
				Flen();
				$(".CostumeInquiry_strip").text(data.count);
				$(".CostumeInquiry_total").text(data.allpage);
				shadowe = data.allpage;
				if(shadowe == 0) {
					page = 0;
					$(".CostumeInquiry_flip").val(0);
				} else {
					$(".CostumeInquiry_flip").val(page);
				}
				if(page <= 1) {
					$(".CostumeInquiry_prev").addClass("CostumeInquiry_selectP");
					$(".CostumeInquiry_index").addClass("CostumeInquiry_selectP");
				} else {
					$(".CostumeInquiry_prev").removeClass("CostumeInquiry_selectP");
					$(".CostumeInquiry_index").removeClass("CostumeInquiry_selectP");
				}
				if(page == shadowe) {
					$(".CostumeInquiry_shadowe").addClass("CostumeInquiry_selectP");
					$(".CostumeInquiry_next").addClass("CostumeInquiry_selectP");
				} else {
					$(".CostumeInquiry_shadowe").removeClass("CostumeInquiry_selectP");
					$(".CostumeInquiry_next").removeClass("CostumeInquiry_selectP");
				}
			} else {
				shadowe = 0;
				page = 0;
				$(".CostumeInquiry_flip").val(0);
				$(".CostumeInquiry_strip").text("0");
				$(".CostumeInquiry_total").text("0");
				$(".CostumeInquiry_prev").addClass("CostumeInquiry_selectP");
				$(".CostumeInquiry_index").addClass("CostumeInquiry_selectP");
				$(".CostumeInquiry_shadowe").addClass("CostumeInquiry_selectP");
				$(".CostumeInquiry_next").addClass("CostumeInquiry_selectP");
			}
		});

	}
	$("#load").css("display", "block");
	var dlz = $.get(ip + "/interface/index.php/menu/index", function(data) {
		dictionaries = JSON.parse(data);
	});
	var dlz2 = $.post(ip + "/interface/index.php/brand/index", {
		"limit": 999
	}, function(data) {
		brandD = JSON.parse(data);
	});
	$.when(dlz, dlz2).done(function() {
		$("#load").css("display", "none");
		postPage(data);
	});
	$("#dl2").on("input", function() {
		var name = $(this).val();
		if(name != "") {
			var data = {
				"name": name,
				"limit": 999
			};
			$.post(ip + "/interface/index.php/brand/index", data, function(data) {
				var data = JSON.parse(data);
				var html = "";
				for(var i = 0; i < data.data.length; i++) {
					html += '<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>';
				}
				$("#dl2v").html(html);
			});
		} else {
			$("#dl2v").empty();
		}

	});
	// 点击搜索
	$("#CostumeInquiry_search").on("click", function() {
		//			$("#load").css("display","block");
		shenh = $("#CostumeInquiry_shenh").find("option:selected").val();
		name = $("#CostumeInquiry_name").val();
		icon = $("#CostumeInquiry_icon").val();
		var dl0 = $("#dl0").parent().find("select");
		var dl1 = $("#dl1").parent().find("select");
		dl2v = $("#dl2v").val();
		var dl3 = $("#dl3").parent().find("select");
		var dl4 = $("#dl4").parent().find("select");
		var dl5 = $("#dl5").parent().find("select");
		var dl6 = $("#dl6").parent().find("select");
		var dl7 = $("#dl7").parent().find("select");
		var dl8 = $("#dl8").parent().find("select");
		var dl9 = $("#dl9").parent().find("select");
		var dl10 = $("#dl10").parent().find("select");
		var dl11 = $("#dl11").parent().find("select");
		if(dl0.length >= 2) {
			dl0v1 = dl0.eq(dl0.length - 2).find("option:selected").val();
			dl0v2 = dl0.eq(dl0.length - 1).find("option:selected").val();
		} else {
			dl0v1 = dl0.eq(dl0.length - 1).find("option:selected").val();
			dl0v2 = "";
		}
		dl1v = dl1.eq(dl1.length - 1).find("option:selected").val();
		if(dl3.length >= 2) {
			dl3v1 = dl3.eq(dl3.length - 2).find("option:selected").val();
			dl3v2 = dl3.eq(dl3.length - 1).find("option:selected").val();
		} else {
			dl3v1 = dl3.eq(dl3.length - 1).find("option:selected").val();
			dl3v2 = "";
		}
		if(dl4.length >= 2) {
			dl4v1 = dl4.eq(dl4.length - 2).find("option:selected").val();
			dl4v2 = dl4.eq(dl4.length - 1).find("option:selected").val();
		} else {
			dl4v1 = dl4.eq(dl4.length - 1).find("option:selected").val();
			dl4v2 = "";
		}
		dl5v = dl5.eq(dl5.length - 1).find("option:selected").val();
		dl6v = dl6.eq(dl6.length - 1).find("option:selected").val();
		dl7v = dl7.eq(dl7.length - 1).find("option:selected").val();
		dl8v = dl8.eq(dl8.length - 1).find("option:selected").val();
		dl9v = dl9.eq(dl9.length - 1).find("option:selected").val();
		dl10v = dl10.eq(dl10.length - 1).find("option:selected").val();
		dl11v = dl11.eq(dl11.length - 1).find("option:selected").val();
		data = {
			"name": name,
			"icon": icon,
			"page": 1,
			"check_status": shenh,
			"sort": dl0v1,
			"sort2": dl0v2,
			"season": dl1v,
			"brand": dl2v,
			"style1": dl3v1,
			"style21": dl3v2,
			"style2": dl4v1,
			"style22": dl4v2,
			"color1": dl5v,
			"color2": dl11v,
			"pattern1": dl6v,
			"material1": dl7v,
			"collar": dl8v,
			"model": dl9v,
			"pop_element1": dl10v
		};
		postPage(data);
	});
	// 点击清除
	$("#CostumeInquiry_clear").on("click", function() {
		$("#CostumeInquiry_name").val("");
		$("#CostumeInquiry_icon").val("");
		$("#dl0").nextAll().remove();
		$("#dl0 option").attr("selected", false);
		$("#dl1").nextAll().remove();
		$("#dl1 option").attr("selected", false);
		$("#dl2").val("");
		$("#dl3").nextAll().remove();
		$("#dl3 option").attr("selected", false);
		$("#dl4").nextAll().remove();
		$("#dl4 option").attr("selected", false);
		$("#dl5").nextAll().remove();
		$("#dl5 option").attr("selected", false);
		$("#dl6").nextAll().remove();
		$("#dl6 option").attr("selected", false);
		$("#dl7").nextAll().remove();
		$("#dl7 option").attr("selected", false);
		$("#dl8").nextAll().remove();
		$("#dl8 option").attr("selected", false);
		$("#dl9").nextAll().remove();
		$("#dl9 option").attr("selected", false);
		$("#dl10").nextAll().remove();
		$("#dl10 option").attr("selected", false);
		$("#dl11").nextAll().remove();
		$("#dl11 option").attr("selected", false);
		$("#dl2v").empty();
	});
	// 点击首页
	$(".CostumeInquiry_index").on("click", function() {
		$("#load").css("display", "block");
		if(!$(this).hasClass("CostumeInquiry_selectP")) {
			data = {
				"name": name,
				"icon": icon,
				"page": 1,
				"check_status": shenh,
				"sort": dl0v1,
				"sort2": dl0v2,
				"season": dl1v,
				"brand": dl2v,
				"style1": dl3v1,
				"style21": dl3v2,
				"style2": dl4v1,
				"style22": dl4v2,
				"color1": dl5v,
				"color2": dl11v,
				"pattern1": dl6v,
				"material1": dl7v,
				"collar": dl8v,
				"model": dl9v,
				"pop_element1": dl10v
			};
			postPage(data);
		}
	});
	// 点击上一页
	$(".CostumeInquiry_prev").on("click", function() {
		$("#load").css("display", "block");
		if(!$(this).hasClass("CostumeInquiry_selectP")) {
			data = {
				"name": name,
				"icon": icon,
				"page": Number(page) - 1,
				"check_status": shenh,
				"sort": dl0v1,
				"sort2": dl0v2,
				"season": dl1v,
				"brand": dl2v,
				"style1": dl3v1,
				"style21": dl3v2,
				"style2": dl4v1,
				"style22": dl4v2,
				"color1": dl5v,
				"color2": dl11v,
				"pattern1": dl6v,
				"material1": dl7v,
				"collar": dl8v,
				"model": dl9v,
				"pop_element1": dl10v
			};
			postPage(data);
		}
	});
	// 点击下一页
	$(".CostumeInquiry_next").on("click", function() {
		$("#load").css("display", "block");
		if(!$(this).hasClass("CostumeInquiry_selectP")) {
			data = {
				"name": name,
				"icon": icon,
				"page": Number(page) + 1,
				"check_status": shenh,
				"sort": dl0v1,
				"sort2": dl0v2,
				"season": dl1v,
				"brand": dl2v,
				"style1": dl3v1,
				"style21": dl3v2,
				"style2": dl4v1,
				"style22": dl4v2,
				"color1": dl5v,
				"color2": dl11v,
				"pattern1": dl6v,
				"material1": dl7v,
				"collar": dl8v,
				"model": dl9v,
				"pop_element1": dl10v
			};
			postPage(data);
		}
	});
	// 点击尾页
	$(".CostumeInquiry_shadowe").on("click", function() {
		$("#load").css("display", "block");
		if(!$(this).hasClass("CostumeInquiry_selectP")) {
			data = {
				"name": name,
				"icon": icon,
				"page": shadowe,
				"check_status": shenh,
				"sort": dl0v1,
				"sort2": dl0v2,
				"season": dl1v,
				"brand": dl2v,
				"style1": dl3v1,
				"style21": dl3v2,
				"style2": dl4v1,
				"style22": dl4v2,
				"color1": dl5v,
				"color2": dl11v,
				"pattern1": dl6v,
				"material1": dl7v,
				"collar": dl8v,
				"model": dl9v,
				"pop_element1": dl10v
			};
			postPage(data);
		}
	});
	// 页数输入
	$(".CostumeInquiry_flip").on("input", function() {
		var p = $(this).val();
		if(p > shadowe) {
			$(this).val(shadowe);
		}
	});
	// 点击跳转
	$(".CostumeInquiry_jump").on("click", function() {
		$("#load").css("display", "block");
		var p = $(".CostumeInquiry_flip").val();
		if(p >= 1) {
			data = {
				"name": name,
				"icon": icon,
				"page": p,
				"check_status": shenh,
				"sort": dl0v1,
				"sort2": dl0v2,
				"season": dl1v,
				"brand": dl2v,
				"style1": dl3v1,
				"style21": dl3v2,
				"style2": dl4v1,
				"style22": dl4v2,
				"color1": dl5v,
				"color2": dl11v,
				"pattern1": dl6v,
				"material1": dl7v,
				"collar": dl8v,
				"model": dl9v,
				"pop_element1": dl10v
			};
			postPage(data);
		}
	});

	function postFar_id_n(data, val) {
		$("#load").css("display", "block");
		var ndl1 = $.post(ip + "/interface/index.php/menu/index", data[0], function(data) {
			var data = JSON.parse(data);
			if(val.sort) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.sort == data.data[i].id) {
						$("#ndl1").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl1").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
				farid_id4_n({
					"far_id": val.sort
				}, $("#ndl1").parent(), val.sort2);
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl1").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl2 = $.post(ip + "/interface/index.php/menu/index", data[1], function(data) {
			var data = JSON.parse(data);
			if(val.season) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.season == data.data[i].id) {
						$("#ndl2").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl2").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl2").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}

		});
		var ndl4 = $.post(ip + "/interface/index.php/menu/index", data[2], function(data) {
			var data = JSON.parse(data);
			if(val.style1) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.style1 == data.data[i].id) {
						$("#ndl4").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl4").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
				farid_id4_n({
					"far_id": val.style1
				}, $("#ndl4").parent(), val.style21);
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl4").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl5 = $.post(ip + "/interface/index.php/menu/index", data[3], function(data) {
			var data = JSON.parse(data);
			if(val.style2) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.style2 == data.data[i].id) {
						$("#ndl5").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl5").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
				farid_id4_n({
					"far_id": val.style2
				}, $("#ndl5").parent(), val.style22);
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl5").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl6 = $.post(ip + "/interface/index.php/menu/index", data[4], function(data) {
			var data = JSON.parse(data);
			if(val.color1) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.color1 == data.data[i].id) {
						$("#ndl6").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl6").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl6").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl7 = $.post(ip + "/interface/index.php/menu/index", data[5], function(data) {
			var data = JSON.parse(data);
			if(val.color2) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.color2 == data.data[i].id) {
						$("#ndl7").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl7").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl7").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl8 = $.post(ip + "/interface/index.php/menu/index", data[6], function(data) {
			var data = JSON.parse(data);
			if(val.pattern1) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.pattern1 == data.data[i].id) {
						$("#ndl8").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl8").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl8").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl9 = $.post(ip + "/interface/index.php/menu/index", data[7], function(data) {
			var data = JSON.parse(data);
			if(val.pattern2) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.pattern2 == data.data[i].id) {
						$("#ndl9").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl9").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl9").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl10 = $.post(ip + "/interface/index.php/menu/index", data[8], function(data) {
			var data = JSON.parse(data);
			if(val.material1) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.material1 == data.data[i].id) {
						$("#ndl10").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl10").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl10").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}

		});
		var ndl11 = $.post(ip + "/interface/index.php/menu/index", data[9], function(data) {
			var data = JSON.parse(data);
			if(val.material2) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.material2 == data.data[i].id) {
						$("#ndl11").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl11").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl11").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl12 = $.post(ip + "/interface/index.php/menu/index", data[10], function(data) {
			var data = JSON.parse(data);
			if(val.collar) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.collar == data.data[i].id) {
						$("#ndl12").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl12").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl12").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl13 = $.post(ip + "/interface/index.php/menu/index", data[11], function(data) {
			var data = JSON.parse(data);
			if(val.model) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.model == data.data[i].id) {
						$("#ndl13").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl13").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl13").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl14 = $.post(ip + "/interface/index.php/menu/index", data[12], function(data) {
			var data = JSON.parse(data);
			if(val.pop_element1) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.pop_element1 == data.data[i].id) {
						$("#ndl14").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl14").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl14").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl15 = $.post(ip + "/interface/index.php/menu/index", data[13], function(data) {
			var data = JSON.parse(data);
			if(val.pop_element2) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.pop_element2 == data.data[i].id) {
						$("#ndl15").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl15").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl15").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl16 = $.post(ip + "/interface/index.php/menu/index", data[14], function(data) {
			var data = JSON.parse(data);
			if(val.pop_element3) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.pop_element3 == data.data[i].id) {
						$("#ndl16").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl16").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl16").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl17 = $.post(ip + "/interface/index.php/menu/index", data[15], function(data) {
			var data = JSON.parse(data);
			if(val.pop_element4) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.pop_element4 == data.data[i].id) {
						$("#ndl17").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl17").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl17").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl18 = $.post(ip + "/interface/index.php/menu/index", data[16], function(data) {
			var data = JSON.parse(data);
			if(val.pop_element5) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.pop_element5 == data.data[i].id) {
						$("#ndl18").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl18").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl18").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl19 = $.post(ip + "/interface/index.php/menu/index", data[17], function(data) {
			var data = JSON.parse(data);
			if(val.pop_element6) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.pop_element6 == data.data[i].id) {
						$("#ndl19").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl19").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl19").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl20 = $.post(ip + "/interface/index.php/menu/index", data[18], function(data) {
			var data = JSON.parse(data);
			if(val.pop_element7) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.pop_element7 == data.data[i].id) {
						$("#ndl20").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl20").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl20").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl21 = $.post(ip + "/interface/index.php/menu/index", data[19], function(data) {
			var data = JSON.parse(data);
			if(val.pop_element8) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.pop_element8 == data.data[i].id) {
						$("#ndl21").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl21").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl21").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl22 = $.post(ip + "/interface/index.php/menu/index", data[20], function(data) {
			var data = JSON.parse(data);
			if(val.pop_element9) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.pop_element9 == data.data[i].id) {
						$("#ndl22").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl22").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl22").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		var ndl23 = $.post(ip + "/interface/index.php/menu/index", data[21], function(data) {
			var data = JSON.parse(data);
			if(val.pop_element10) {
				for(var i = 0; i < data.data.length; i++) {
					if(val.pop_element10 == data.data[i].id) {
						$("#ndl23").append('<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					} else {
						$("#ndl23").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					}
				}
			} else {
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl23").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
				}
			}
		});
		if(data[22]) {
			$("#ndliconText").val(data[22].icon);
			var icon = "";
			var m = data[22].icon.substr(data[22].icon.length - 4, data[22].icon.length - 2);
			if(m == "-m") {
				icon = data[22].icon.substr(0, data[22].icon.length - 4);
			} else {
				icon = data[22].icon.substr(0, data[22].icon.length - 2);
			}
			var html = "";
			html += '<img src="' + ip2 + '/shizhuang/upload/itemtypeImage/small_100/' + data[22].icon + '.jpg"/>' +
				'<p>大图标</p>' +
				'<p>名称：<span>' + data[22].icon + '</span></p>';
			$(".CostumeNew_imgIcon").html(html);
			var ndlicon = "";
			var ndlicon = $.post(ip + "/interface/index.php/icon/index", {
				"icon": icon
			}, function(data) {
				var data = JSON.parse(data);
				var html = "";
				for(var i = 0; i < data.data.length; i++) {
					html += '<div class="CostumeNew_imgFragmentContent">' +
						'<img src="' + ip2 + '/shizhuang/upload/itemtypeImage/big_100/' + data.data[i].icon + '.png"/>' +
						'<p>名称：<span>' + data.data[i].icon + '</span></p>' +
						'<p>区域尺寸宽：<span>' + data.data[i].width + '</span>&nbsp;区域尺寸高：<span>' + data.data[i].height + '</span></p>' +
						'<p>整图坐标x：<span>' + data.data[i].x + '</span>&nbsp;整图坐标y：<span>' + data.data[i].y + '</span></p>' +
						'<p>区域坐标x：<span>' + data.data[i].px + '</span>&nbsp;区域坐标y：<span>' + data.data[i].py + '</span></p>' +
						'</div>';
				}
				html += '<div class="clearfix"></div>';
				$(".CostumeNew_imgFragment").html(html);
			});
			//				var ndlicon = $.post(ip + "/interface/index.php/icon/index", data[22], function(data) {
			//					var data = JSON.parse(data);
			//					var icon = "";
			//					for(var i = 0; i < data.data.length; i++) {
			//						if (i==0) {
			//							icon = data.data[i].icon;
			//						}
			//						$("#ndlicon").append('<option value="' + data.data[i].id + '">' + data.data[i].icon + '</option>');
			//					}
			//					var m = icon.substr(icon.length-4,icon.length-2);
			//					if (m == "-m") {
			//						icon = icon.substr(0,icon.length-4);
			//					}else{
			//						icon = icon.substr(0,icon.length-2);
			//					}
			//					$.post(ip + "/interface/index.php/icon/index", {"icon":icon}, function(data) {
			//						var data = JSON.parse(data);
			//						var html = "";
			//						html+='<img src="' + ip2 + '/upload2/itemtypeImage/big_100/' + data.data[0].icon + '.jpg"/>'+
			//						'<p>区域尺寸宽：<span>'+data.data[0].width+'</span>&nbsp;区域尺寸高：<span>'+data.data[0].height+'</span></p>'+
			//						'<p>整图坐标x：<span>'+data.data[0].x+'</span>&nbsp;整图坐标y：<span>'+data.data[0].y+'</span>&nbsp;区域坐标x：<span>'+data.data[0].px+'</span>&nbsp;区域坐标y：<span>'+data.data[0].py+'</span></p>';
			//						$(".CostumeNew_imgIcon").html(html);
			//					});
			//				});
		} else {
			var ndlicon = "";
		}
		if(data[23]) {
			var ndlbrand = $.post(ip + "/interface/index.php/brand/index", data[23], function(data) {
				var data = JSON.parse(data);
				for(var i = 0; i < data.data.length; i++) {
					$("#ndl3").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
					$("#ndl3text").val(data.data[i].name);
				}
			});
		} else {
			var ndlbrand = "";
		}
		$.when(ndl1, ndl2, ndl4, ndl5, ndl6, ndl7, ndl8, ndl9, ndl10, ndl11, ndl12, ndl13, ndl14, ndl15, ndl16, ndl17, ndl18, ndl19, ndl20, ndl21, ndl22, ndl23, ndlicon, ndlbrand).done(function() {
			$("#load").css("display", "none");
			postFar_id2_n();
		});
	}

	function postFar_id2_n() {
		$(".CostumeNewF").off('change', "select");
		$(".CostumeNewF").on("change", "select", function() {
			var Sthis = $(this);
			var options = $(this).find("option:selected");
			var params = $(this).parent();
			var farid = {
				"far_id": options.val()
			};
			$(this).nextAll().remove();
			if(options.val() == "" || Sthis.attr("id") == "ndlicon" || Sthis.attr("id") == "ndl3") {
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
					farid = {
						"far_id": Sthis.next().find("option:selected").val()
					};
					if(Sthis.next().find("option:selected").val() != "") {
						//						console.log(farid);
						$("#load").css("display", "block");
						$.post(ip + "/interface/index.php/menu/index", farid, function(data) {
							$("#load").css("display", "none");
							var data = JSON.parse(data);
							if(data.data.length > 0) {
								var html = '<select>';
								for(var i = 0; i < data.data.length; i++) {
									html += '<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>';
								}
								html += '</select>';
								params.append(html);
							}
						});
					}

				}
			});
			postFar_id2_n();
		});

	}

	function farid_id4_n(farid, params, sel) {
		//		console.log(sel)
		$("#load").css("display", "block");
		$.post(ip + "/interface/index.php/menu/index", farid, function(data) {
			$("#load").css("display", "none");
			var data = JSON.parse(data);
			if(data.data.length > 0) {
				//						console.log(sel);
				var html = '<select><option value="">——请选择——</option>';
				for(var i = 0; i < data.data.length; i++) {
					if(data.data[i].id == sel) {
						html += '<option selected="selected" value="' + data.data[i].id + '">' + data.data[i].name + '</option>';
					} else {
						html += '<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>';
					}
				}
				html += '</select>';
				params.append(html);
			}
		});
	}
	// 点击添加
	//		$(".CostumeInquiry_news").on("click", function() {
	//			$("#load").css("display", "block");
	//			$.get("CostumeNew.php", function(data) {
	//				$(".admin_content_CostumeInquiry").addClass("display");
	//				$("body").append(data);
	//				var faridArr = [];
	//				var farid2 = {
	//					"far_id": 17
	//				};
	//				faridArr.push(farid2);
	//				var farid3 = {
	//					"far_id": 18
	//				};
	//				faridArr.push(farid3);
	//				var farid5 = {
	//					"far_id": 19
	//				};
	//				faridArr.push(farid5);
	//				var farid6 = {
	//					"far_id": 19
	//				};
	//				faridArr.push(farid6);
	//				var farid7 = {
	//					"far_id": 20
	//				};
	//				faridArr.push(farid7);
	//				var farid8 = {
	//					"far_id": 20
	//				};
	//				faridArr.push(farid8);
	//				var farid9 = {
	//					"far_id": 21
	//				};
	//				faridArr.push(farid9);
	//				var farid10 = {
	//					"far_id": 21
	//				};
	//				faridArr.push(farid10);
	//				var farid11 = {
	//					"far_id": 22
	//				};
	//				faridArr.push(farid11);
	//				var farid12 = {
	//					"far_id": 22
	//				};
	//				faridArr.push(farid12);
	//				var farid13 = {
	//					"far_id": 26
	//				};
	//				faridArr.push(farid13);
	//				var farid14 = {
	//					"far_id": 25
	//				};
	//				faridArr.push(farid14);
	//				var farid15 = {
	//					"far_id": 95
	//				};
	//				faridArr.push(farid15);
	//				var farid16 = {
	//					"far_id": 95
	//				};
	//				faridArr.push(farid16);
	//				var farid17 = {
	//					"far_id": 95
	//				};
	//				faridArr.push(farid17);
	//				var farid18 = {
	//					"far_id": 95
	//				};
	//				faridArr.push(farid18);
	//				var farid19 = {
	//					"far_id": 95
	//				};
	//				faridArr.push(farid19);
	//				var farid20 = {
	//					"far_id": 95
	//				};
	//				faridArr.push(farid20);
	//				var farid21 = {
	//					"far_id": 95
	//				};
	//				faridArr.push(farid21);
	//				var farid22 = {
	//					"far_id": 95
	//				};
	//				faridArr.push(farid22);
	//				var farid23 = {
	//					"far_id": 95
	//				};
	//				faridArr.push(farid23);
	//				var farid24 = {
	//					"far_id": 95
	//				};
	//				faridArr.push(farid24);
	//				postFar_id_n(faridArr, "");
	//				$("#ndliconText").on("input", function() {
	//					var name = $(this).val();
	//					if(name != "") {
	//						var data = {
	//							"icon": name
	//						};
	//						$.post(ip + "/interface/index.php/icon/index", data, function(data) {
	//							var data = JSON.parse(data);
	//							var html = "";
	//							var icon = "";
	//							for(var i = 0; i < data.data.length; i++) {
	//								if (i==0) {
	//									icon = data.data[i].icon;
	//								}
	//								html += '<option value="' + data.data[i].icon + '">' + data.data[i].icon + '</option>';
	//							}
	//							$("#ndlicon").html(html);
	//							var m = icon.substr(icon.length-4,icon.length-2);
	//							if (m == "-m") {
	//								icon = icon.substr(0,icon.length-4);
	//							}else{
	//								icon = icon.substr(0,icon.length-2);
	//							}
	//							$.post(ip + "/interface/index.php/icon/index", {"icon":icon}, function(data) {
	//								var data = JSON.parse(data);
	//								var html = "";
	//								html+='<img src="' + ip2 + '/upload2/itemtypeImage/big_100/' + data.data[0].icon + '.jpg"/>'+
	//								'<p>区域尺寸宽：<span>'+data.data[0].width+'</span>&nbsp;区域尺寸高：<span>'+data.data[0].height+'</span></p>'+
	//								'<p>整图坐标x：<span>'+data.data[0].x+'</span>&nbsp;整图坐标y：<span>'+data.data[0].y+'</span>&nbsp;区域坐标x：<span>'+data.data[0].px+'</span>&nbsp;区域坐标y：<span>'+data.data[0].py+'</span></p>';
	//								$(".CostumeNew_imgIcon").html(html);
	//							});
	//						});
	//					} else {
	//						$("#ndlicon").empty();
	//					}
	//
	//				});
	//				$("#ndl3text").on("input", function() {
	//					var name = $(this).val();
	//					if(name != "") {
	//						var data = {
	//							"name": name,
	//							"limit": 999
	//						};
	//						$.post(ip + "/interface/index.php/brand/index", data, function(data) {
	//							var data = JSON.parse(data);
	//							var html = "";
	//							for(var i = 0; i < data.data.length; i++) {
	//								html += '<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>';
	//							}
	//							$("#ndl3").html(html);
	//						});
	//					} else {
	//						$("#ndl3").empty();
	//					}
	//
	//				});
	//				// icon改变
	//				$("#ndlicon").on("change",function(){
	//					var icon = $(this).val();
	//					var m = icon.substr(icon.length-4,icon.length-2);
	//					if (m == "-m") {
	//						icon = icon.substr(0,icon.length-4);
	//					}else{
	//						icon = icon.substr(0,icon.length-2);
	//					}
	//					$.post(ip + "/interface/index.php/icon/index", {"icon":icon}, function(data) {
	//						var data = JSON.parse(data);
	//						var html = "";
	//						html+='<img src="' + ip2 + '/upload2/itemtypeImage/big_100/' + data.data[0].icon + '.jpg"/>'+
	//						'<p>区域尺寸宽：<span>'+data.data[0].width+'</span>&nbsp;区域尺寸高：<span>'+data.data[0].height+'</span></p>'+
	//						'<p>整图坐标x：<span>'+data.data[0].x+'</span>&nbsp;整图坐标y：<span>'+data.data[0].y+'</span>&nbsp;区域坐标x：<span>'+data.data[0].px+'</span>&nbsp;区域坐标y：<span>'+data.data[0].py+'</span></p>';
	//						$(".CostumeNew_imgIcon").html(html);
	//					});
	//				});
	//				
	//				// 点击新增
	//				$("#CostumeNew_new").on("click", function() {
	//					var CostumeNew_name = $("#CostumeNew_name").val();
	//					var ndlicon = $("#ndlicon").parent().find("select");
	//					var ndliconv = ndlicon.eq(ndlicon.length - 1).find("option:selected").val();
	//					var ndl1 = $("#ndl1").parent().find("select");
	//					var ndl1v1 = "";
	//					var ndl1v2 = "";
	//					if(ndl1.length >= 2) {
	//						ndl1v1 = ndl1.eq(ndl1.length - 2).find("option:selected").val();
	//						ndl1v2 = ndl1.eq(ndl1.length - 1).find("option:selected").val();
	//					} else {
	//						ndl1v1 = ndl1.eq(ndl1.length - 1).find("option:selected").val();
	//					}
	//					var ndl2 = $("#ndl2").parent().find("select");
	//					var ndl2v = ndl2.eq(ndl2.length - 1).find("option:selected").val();
	//					var ndl3 = $("#ndl3").parent().find("select");
	//					var ndl3v = ndl3.eq(ndl3.length - 1).find("option:selected").val();
	//					var ndl4 = $("#ndl4").parent().find("select");
	//					var ndl4v1 = "";
	//					var ndl4v2 = "";
	//					if(ndl4.length >= 2) {
	//						ndl4v1 = ndl4.eq(ndl4.length - 2).find("option:selected").val();
	//						ndl4v2 = ndl4.eq(ndl4.length - 1).find("option:selected").val();
	//					} else {
	//						ndl4v1 = ndl4.eq(ndl4.length - 1).find("option:selected").val();
	//					}
	//					var ndl5 = $("#ndl5").parent().find("select");
	//					var ndl5v1 = "";
	//					var ndl5v2 = "";
	//					if(ndl5.length >= 2) {
	//						ndl5v1 = ndl5.eq(ndl5.length - 2).find("option:selected").val();
	//						ndl5v2 = ndl5.eq(ndl5.length - 1).find("option:selected").val();
	//					} else {
	//						ndl5v1 = ndl5.eq(ndl5.length - 1).find("option:selected").val();
	//					}
	//					var ndl6 = $("#ndl6").parent().find("select");
	//					var ndl6v = ndl6.eq(ndl6.length - 1).find("option:selected").val();
	//					var ndl7 = $("#ndl7").parent().find("select");
	//					var ndl7v = ndl7.eq(ndl7.length - 1).find("option:selected").val();
	//					var ndl8 = $("#ndl8").parent().find("select");
	//					var ndl8v = ndl8.eq(ndl8.length - 1).find("option:selected").val();
	//					var ndl9 = $("#ndl9").parent().find("select");
	//					var ndl9v = ndl9.eq(ndl9.length - 1).find("option:selected").val();
	//					var ndl10 = $("#ndl10").parent().find("select");
	//					var ndl10v = ndl10.eq(ndl10.length - 1).find("option:selected").val();
	//					var ndl11 = $("#ndl11").parent().find("select");
	//					var ndl11v = ndl11.eq(ndl11.length - 1).find("option:selected").val();
	//					var ndl12 = $("#ndl12").parent().find("select");
	//					var ndl12v = ndl12.eq(ndl12.length - 1).find("option:selected").val();
	//					var ndl13 = $("#ndl13").parent().find("select");
	//					var ndl13v = ndl13.eq(ndl13.length - 1).find("option:selected").val();
	//					var ndl14 = $("#ndl14").parent().find("select");
	//					var ndl14v = ndl14.eq(ndl14.length - 1).find("option:selected").val();
	//					var ndl15 = $("#ndl15").parent().find("select");
	//					var ndl15v = ndl15.eq(ndl15.length - 1).find("option:selected").val();
	//					var ndl16 = $("#ndl16").parent().find("select");
	//					var ndl16v = ndl16.eq(ndl16.length - 1).find("option:selected").val();
	//					var ndl17 = $("#ndl17").parent().find("select");
	//					var ndl17v = ndl17.eq(ndl17.length - 1).find("option:selected").val();
	//					var ndl18 = $("#ndl18").parent().find("select");
	//					var ndl18v = ndl18.eq(ndl18.length - 1).find("option:selected").val();
	//					var ndl19 = $("#ndl19").parent().find("select");
	//					var ndl19v = ndl19.eq(ndl19.length - 1).find("option:selected").val();
	//					var ndl20 = $("#ndl20").parent().find("select");
	//					var ndl20v = ndl20.eq(ndl20.length - 1).find("option:selected").val();
	//					var ndl21 = $("#ndl21").parent().find("select");
	//					var ndl21v = ndl21.eq(ndl21.length - 1).find("option:selected").val();
	//					var ndl22 = $("#ndl22").parent().find("select");
	//					var ndl22v = ndl22.eq(ndl22.length - 1).find("option:selected").val();
	//					var ndl23 = $("#ndl23").parent().find("select");
	//					var ndl23v = ndl23.eq(ndl23.length - 1).find("option:selected").val();
	//					var data = {
	//						"name": CostumeNew_name,
	//						"icon": ndliconv,
	//						"sort": ndl1v1,
	//						"sort2": ndl1v2,
	//						"season": ndl2v,
	//						"brand": ndl3v,
	//						"style1": ndl4v1,
	//						"style21": ndl4v2,
	//						"style2": ndl5v1,
	//						"style22": ndl5v2,
	//						"color1": ndl6v,
	//						"color2": ndl7v,
	//						"pattern1": ndl8v,
	//						"pattern2": ndl9v,
	//						"material1": ndl10v,
	//						"material2": ndl11v,
	//						"collar": ndl12v,
	//						"model": ndl13v,
	//						"pop_element1": ndl14v,
	//						"pop_element2": ndl15v,
	//						"pop_element3": ndl16v,
	//						"pop_element4": ndl17v,
	//						"pop_element5": ndl18v,
	//						"pop_element6": ndl19v,
	//						"pop_element7": ndl20v,
	//						"pop_element8": ndl21v,
	//						"pop_element9": ndl22v,
	//						"pop_element10": ndl23v
	//					};
	//					$("#load").css("display", "block");
	//					$.post(ip + "/interface/index.php/shopitem/add", data, function(data) {
	//						$("#load").css("display", "none");
	//						var data = JSON.parse(data);
	//						alert(data.msg);
	//						if(data.status == 0) {
	//							$(".admin_content_CostumeInquiry").removeClass("display");
	//							$(".admin_content_CostumeNew").remove();
	//							data = {
	//								"name": name,
	//								"icon": icon,
	//								"page": page,
	//								"check_status": shenh,
	//								"sort": dl0v1,
	//								"sort2": dl0v2,
	//								"season": dl1v,
	//								"brand": dl2v,
	//								"style1": dl3v1,
	//								"style21": dl3v2,
	//								"style2": dl4v1,
	//								"style22": dl4v2,
	//								"color1": dl5v,
	//								"color2": dl11v,
	//								"pattern1": dl6v,
	//								"material1": dl7v,
	//								"collar": dl8v,
	//								"model": dl9v,
	//								"pop_element1": dl10v
	//							};
	//							postPage(data);
	//						}
	//					});
	//				});
	//				$("#CostumeNew_x").on("click", function() {
	//					$(".admin_content_CostumeInquiry").removeClass("display");
	//					$(".admin_content_CostumeNew").remove();
	//				});
	//
	//			});
	//		});
	// 点击编辑
	$(".CostumeInquiry_content").on("click", ".CostumeInquiry_text .CostumeInquiry_textF span:nth-of-type(1)", function() {
		$("#load").css("display", "block");
		var index = $(this).attr("index");
		var params = $(this).parent().parent().find(".CostumeInquiry_textF");
		var arrI = dataEmit[index];
		$.get("CostumeNew.php", function(data) {
			$(".admin_content_CostumeInquiry").addClass("display");
			$("body").append(data);
			$("#CostumeNew_titleH").text("编辑服饰");
			$("#CostumeNew_name").val(arrI.name);
			$("#price").val(arrI.price);
			$("#emoney").val(arrI.emoney);
			$("#CostumeNew_new").text("修改");
			$("#ndliconText").attr("disabled", true);
			var faridArr = [];
			var farid2 = {
				"far_id": 17
			};
			faridArr.push(farid2);
			var farid3 = {
				"far_id": 18
			};
			faridArr.push(farid3);
			var farid5 = {
				"far_id": 19
			};
			faridArr.push(farid5);
			var farid6 = {
				"far_id": 19
			};
			faridArr.push(farid6);
			var farid7 = {
				"far_id": 20
			};
			faridArr.push(farid7);
			var farid8 = {
				"far_id": 20
			};
			faridArr.push(farid8);
			var farid9 = {
				"far_id": 21
			};
			faridArr.push(farid9);
			var farid10 = {
				"far_id": 21
			};
			faridArr.push(farid10);
			var farid11 = {
				"far_id": 22
			};
			faridArr.push(farid11);
			var farid12 = {
				"far_id": 22
			};
			faridArr.push(farid12);
			var farid13 = {
				"far_id": 26
			};
			faridArr.push(farid13);
			var farid14 = {
				"far_id": 25
			};
			faridArr.push(farid14);
			var farid15 = {
				"far_id": 95
			};
			faridArr.push(farid15);
			var farid16 = {
				"far_id": 95
			};
			faridArr.push(farid16);
			var farid17 = {
				"far_id": 95
			};
			faridArr.push(farid17);
			var farid18 = {
				"far_id": 95
			};
			faridArr.push(farid18);
			var farid19 = {
				"far_id": 95
			};
			faridArr.push(farid19);
			var farid20 = {
				"far_id": 95
			};
			faridArr.push(farid20);
			var farid21 = {
				"far_id": 95
			};
			faridArr.push(farid21);
			var farid22 = {
				"far_id": 95
			};
			faridArr.push(farid22);
			var farid23 = {
				"far_id": 95
			};
			faridArr.push(farid23);
			var farid24 = {
				"far_id": 95
			};
			faridArr.push(farid24);
			var farid25 = {
				"icon": arrI.icon
			};
			faridArr.push(farid25);
			var farid26 = {
				"id": arrI.brand
			};
			faridArr.push(farid26);
			var faridD = {
				"sort": arrI.sort,
				"sort2": arrI.sort2,
				"season": arrI.season,
				"style1": arrI.style1,
				"style21": arrI.style21,
				"style2": arrI.style2,
				"style22": arrI.style22,
				"style3": arrI.style3,
				"style23": arrI.style23,
				"color1": arrI.color1,
				"color2": arrI.color2,
				"pattern1": arrI.pattern1,
				"pattern2": arrI.pattern2,
				"material1": arrI.material1,
				"material2": arrI.material2,
				"collar": arrI.collar,
				"model": arrI.model,
				"pop_element1": arrI.pop_element1,
				"pop_element2": arrI.pop_element2,
				"pop_element3": arrI.pop_element3,
				"pop_element4": arrI.pop_element4,
				"pop_element5": arrI.pop_element5,
				"pop_element6": arrI.pop_element6,
				"pop_element7": arrI.pop_element7,
				"pop_element8": arrI.pop_element8,
				"pop_element9": arrI.pop_element9,
				"pop_element10": arrI.pop_element10
			};
			postFar_id_n(faridArr, faridD);
			$("#ndliconText").on("input", function() {
				var name = $(this).val();
				if(name != "") {
					var data = {
						"name": name
					};
					$.post(ip + "/interface/index.php/icon/index", data, function(data) {
						var data = JSON.parse(data);
						var html = "";
						for(var i = 0; i < data.data.length; i++) {
							html += '<option value="' + data.data[i].icon + '">' + data.data[i].name + '</option>';
						}
						$("#ndlicon").html(html);
					});
				} else {
					$("#ndlicon").empty();
				}

			});
			$("#ndl3text").on("input", function() {
				var name = $(this).val();
				if(name != "") {
					var data = {
						"name": name,
						"limit": 999
					};
					$.post(ip + "/interface/index.php/brand/index", data, function(data) {
						var data = JSON.parse(data);
						var html = "";
						for(var i = 0; i < data.data.length; i++) {
							html += '<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>';
						}
						$("#ndl3").html(html);
					});
				} else {
					$("#ndl3").empty();
				}

			});
			// 点击修改
			$("#CostumeNew_new").on("click", function() {
				$("#load").css("display", "block");
				var CostumeNew_name = $("#CostumeNew_name").val();
				//					var ndlicon = $("#ndlicon").parent().find("select");
				//					var ndliconv = ndlicon.eq(ndlicon.length - 1).find("option:selected").val();
				var ndl1 = $("#ndl1").parent().find("select");
				var ndl1v1 = "";
				var ndl1v2 = "";
				if(ndl1.length >= 2) {
					ndl1v1 = ndl1.eq(ndl1.length - 2).find("option:selected").val();
					ndl1v2 = ndl1.eq(ndl1.length - 1).find("option:selected").val();
				} else {
					ndl1v1 = ndl1.eq(ndl1.length - 1).find("option:selected").val();
				}
				var ndl2 = $("#ndl2").parent().find("select");
				var ndl2v = ndl2.eq(ndl2.length - 1).find("option:selected").val();
				var ndl3 = $("#ndl3").parent().find("select");
				var ndl3v = ndl3.eq(ndl3.length - 1).find("option:selected").val();
				var ndl4 = $("#ndl4").parent().find("select");
				var ndl4v1 = "";
				var ndl4v2 = "";
				if(ndl4.length >= 2) {
					ndl4v1 = ndl4.eq(ndl4.length - 2).find("option:selected").val();
					ndl4v2 = ndl4.eq(ndl4.length - 1).find("option:selected").val();
				} else {
					ndl4v1 = ndl4.eq(ndl4.length - 1).find("option:selected").val();
				}
				var ndl5 = $("#ndl5").parent().find("select");
				var ndl5v1 = "";
				var ndl5v2 = "";
				if(ndl5.length >= 2) {
					ndl5v1 = ndl5.eq(ndl5.length - 2).find("option:selected").val();
					ndl5v2 = ndl5.eq(ndl5.length - 1).find("option:selected").val();
				} else {
					ndl5v1 = ndl5.eq(ndl5.length - 1).find("option:selected").val();
				}
				var ndl6 = $("#ndl6").parent().find("select");
				var ndl6v = ndl6.eq(ndl6.length - 1).find("option:selected").val();
				var ndl7 = $("#ndl7").parent().find("select");
				var ndl7v = ndl7.eq(ndl7.length - 1).find("option:selected").val();
				var ndl8 = $("#ndl8").parent().find("select");
				var ndl8v = ndl8.eq(ndl8.length - 1).find("option:selected").val();
				var ndl9 = $("#ndl9").parent().find("select");
				var ndl9v = ndl9.eq(ndl9.length - 1).find("option:selected").val();
				var ndl10 = $("#ndl10").parent().find("select");
				var ndl10v = ndl10.eq(ndl10.length - 1).find("option:selected").val();
				var ndl11 = $("#ndl11").parent().find("select");
				var ndl11v = ndl11.eq(ndl11.length - 1).find("option:selected").val();
				var ndl12 = $("#ndl12").parent().find("select");
				var ndl12v = ndl12.eq(ndl12.length - 1).find("option:selected").val();
				var ndl13 = $("#ndl13").parent().find("select");
				var ndl13v = ndl13.eq(ndl13.length - 1).find("option:selected").val();
				var ndl14 = $("#ndl14").parent().find("select");
				var ndl14v = ndl14.eq(ndl14.length - 1).find("option:selected").val();
				var ndl15 = $("#ndl15").parent().find("select");
				var ndl15v = ndl15.eq(ndl15.length - 1).find("option:selected").val();
				var ndl16 = $("#ndl16").parent().find("select");
				var ndl16v = ndl16.eq(ndl16.length - 1).find("option:selected").val();
				var ndl17 = $("#ndl17").parent().find("select");
				var ndl17v = ndl17.eq(ndl17.length - 1).find("option:selected").val();
				var ndl18 = $("#ndl18").parent().find("select");
				var ndl18v = ndl18.eq(ndl18.length - 1).find("option:selected").val();
				var ndl19 = $("#ndl19").parent().find("select");
				var ndl19v = ndl19.eq(ndl19.length - 1).find("option:selected").val();
				var ndl20 = $("#ndl20").parent().find("select");
				var ndl20v = ndl20.eq(ndl20.length - 1).find("option:selected").val();
				var ndl21 = $("#ndl21").parent().find("select");
				var ndl21v = ndl21.eq(ndl21.length - 1).find("option:selected").val();
				var ndl22 = $("#ndl22").parent().find("select");
				var ndl22v = ndl22.eq(ndl22.length - 1).find("option:selected").val();
				var ndl23 = $("#ndl23").parent().find("select");
				var ndl23v = ndl23.eq(ndl23.length - 1).find("option:selected").val();
				var price = $("#price").val();
				var emoney = $("#emoney").val();
				var data = {
					"id": arrI.id,
					"name": CostumeNew_name,
					"sort": ndl1v1,
					"sort2": ndl1v2,
					"season": ndl2v,
					"brand": ndl3v,
					"style1": ndl4v1,
					"style21": ndl4v2,
					"style2": ndl5v1,
					"style22": ndl5v2,
					"color1": ndl6v,
					"color2": ndl7v,
					"pattern1": ndl8v,
					"pattern2": ndl9v,
					"material1": ndl10v,
					"material2": ndl11v,
					"collar": ndl12v,
					"model": ndl13v,
					"pop_element1": ndl14v,
					"pop_element2": ndl15v,
					"pop_element3": ndl16v,
					"pop_element4": ndl17v,
					"pop_element5": ndl18v,
					"pop_element6": ndl19v,
					"pop_element7": ndl20v,
					"pop_element8": ndl21v,
					"pop_element9": ndl22v,
					"pop_element10": ndl23v,
					"price": price,
					"emoney": emoney
				};
				$("#load").css("display", "block");
				$.post(ip + "/interface/index.php/shopitem/update", data, function(data) {
					$("#load").css("display", "none");
					var data = JSON.parse(data);
					alert(data.msg);
					if(data.status == 0) {
						$(".admin_content_CostumeInquiry").removeClass("display");
						$(".admin_content_CostumeNew").remove();
						data = {
							"page": page
						};
						postPage(data);
					}
				});
			});
			$("#CostumeNew_x").on("click", function() {
				$(".admin_content_CostumeInquiry").removeClass("display");
				$(".admin_content_CostumeNew").remove();
			});

		});
	});
	// 点击删除
	$(".CostumeInquiry_content").on("click", ".CostumeInquiry_text .CostumeInquiry_textF span:nth-of-type(2)", function() {
		var params = $(this).parent().parent().find(".CostumeInquiry_textF");
		var data = {
			"id": params.eq(0).text()
		};
		if(confirm("是否删除")) {
			//				console.log(data);
			$("#load").css("display", "block");
			if($(".CostumeInquiry_text").length <= 1) {
				page = Number(page) - 1;
				if(shadowe < 1) {
					return;
				}
			}
			$.post(ip + "/interface/index.php/shopitem/delete", data, function(data) {
				$("#load").css("display", "none");
				var data = JSON.parse(data);
				alert(data.msg);
				if(data.status == 0) {
					data = {
						"name": name,
						"icon": icon,
						"page": page,
						"check_status": shenh,
						"sort": dl0v1,
						"sort2": dl0v2,
						"season": dl1v,
						"brand": dl2v,
						"style1": dl3v1,
						"style21": dl3v2,
						"style2": dl4v1,
						"style22": dl4v2,
						"color1": dl5v,
						"color2": dl11v,
						"pattern1": dl6v,
						"material1": dl7v,
						"collar": dl8v,
						"model": dl9v,
						"pop_element1": dl10v
					};
					postPage(data);
				}
			});
		}
	});
	// 点击通不通过
	$(".CostumeInquiry_content").on("click", ".CostumeInquiry_text .CostumeInquiry_textF #tg", function() {
		var params = $(this).parent().parent().find(".CostumeInquiry_textF");
		var check = $(this).text();
		var check_status = 1;
		var data = {
			"id": params.eq(0).text(),
			"check_status": check_status
		};
		if(confirm("确定" + check + "吗?")) {
			//				console.log(data);
			$("#load").css("display", "block");
			if($(".CostumeInquiry_text").length <= 1) {
				page = Number(page) - 1;
				if(shadowe < 1) {
					return;
				}
			}
			$.post(ip + "/interface/index.php/shopitem/update", data, function(data) {
				$("#load").css("display", "none");
				var data = JSON.parse(data);
				alert(data.msg);
				if(data.status == 0) {
					data = {
						"name": name,
						"icon": icon,
						"page": page,
						"check_status": shenh,
						"sort": dl0v1,
						"sort2": dl0v2,
						"season": dl1v,
						"brand": dl2v,
						"style1": dl3v1,
						"style21": dl3v2,
						"style2": dl4v1,
						"style22": dl4v2,
						"color1": dl5v,
						"color2": dl11v,
						"pattern1": dl6v,
						"material1": dl7v,
						"collar": dl8v,
						"model": dl9v,
						"pop_element1": dl10v
					};
					postPage(data);
				}
			});
		}
	});
	$(".CostumeInquiry_content").on("click", ".CostumeInquiry_text .CostumeInquiry_textF #btg", function() {
		var params = $(this).parent().parent().find(".CostumeInquiry_textF");
		var check = $(this).text();
		var check_status = 2;
		var data = {
			"id": params.eq(0).text(),
			"check_status": check_status
		};
		if(confirm("确定" + check + "吗?")) {
			//				console.log(data);
			$("#load").css("display", "block");
			if($(".CostumeInquiry_text").length <= 1) {
				page = Number(page) - 1;
				if(shadowe < 1) {
					return;
				}
			}
			$.post(ip + "/interface/index.php/shopitem/update", data, function(data) {
				$("#load").css("display", "none");
				var data = JSON.parse(data);
				alert(data.msg);
				if(data.status == 0) {
					data = {
						"name": name,
						"icon": icon,
						"page": page,
						"check_status": shenh,
						"sort": dl0v1,
						"sort2": dl0v2,
						"season": dl1v,
						"brand": dl2v,
						"style1": dl3v1,
						"style21": dl3v2,
						"style2": dl4v1,
						"style22": dl4v2,
						"color1": dl5v,
						"color2": dl11v,
						"pattern1": dl6v,
						"material1": dl7v,
						"collar": dl8v,
						"model": dl9v,
						"pop_element1": dl10v
					};
					postPage(data);
				}
			});
		}
	});
</script>