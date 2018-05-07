<div class="TaskConditions_title">
	<div class="TaskConditions_titleF">编号</div>
	<div class="TaskConditions_titleF TaskConditions_name">名称</div>
	<div class="TaskConditions_titleF">参数1</div>
	<div class="TaskConditions_titleF">参数2</div>
	<div class="TaskConditions_titleF">参数3</div>
	<div class="TaskConditions_titleF">参数4</div>
	<div class="TaskConditions_titleF">参数5</div>
	<div class="TaskConditions_titleF">参数6</div>
	<div class="TaskConditions_titleF">参数7</div>
	<div class="TaskConditions_titleF">参数8</div>
	<div class="TaskConditions_titleF">数量</div>
	<div class="TaskConditions_titleF">操作</div>
	<div class="clearfix"></div>
</div>
<div class="TaskConditions_textBox">
	<!--<div class="TaskConditions_text">
				<div class="TaskConditions_textF">101</div>
				<div class="TaskConditions_textF text_left TaskConditions_name">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">1</div>
				<div class="TaskConditions_textF text_left"><span>编辑</span><span>删除</span></div>
				<div class="clearfix"></div>
			</div>-->
</div>
<div class="TaskConditions_page">
	<span>共<span class="TaskConditions_strip">0</span>条，</span>
	<span class="TaskConditions_jump" style="color: #000099;cursor: pointer;">跳转</span>
	<span>第<input class="TaskConditions_flip" style="width:35px;height: 20px;" onkeyup="value=value.replace(/[^\d]/g,'')" type="text" value="0" />/<span class="TaskConditions_total">0</span>页</span>
	<span class="TaskConditions_pageSet">
								<span class="TaskConditions_selectP TaskConditions_index">首页</span>
	<span class="TaskConditions_selectP TaskConditions_prev">上一页</span>
	<span class="TaskConditions_next">下一页</span>
	</span>
	<span class="TaskConditions_shadowe">尾页</span>
</div>
<script src="../js/configure.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	function Flen() {
		$(".TaskConditions_titleF").css("width", 88 / 11 + "%");
		$(".TaskConditions_textF").css("width", 88 / 11 + "%");
		$(".TaskConditions_titleF").eq(1).css("width", "12%");
		var maxArr = [];
		for(var i = 0; i < $(".TaskConditions_text").length; i++) {
			$(".TaskConditions_text").eq(i).find(".TaskConditions_textF").eq(1).css("width", "12%");
			var H1 = $(".TaskConditions_text").eq(i).find(".TaskConditions_textF").eq(0).height();
			maxArr.push(H1);
			for(var j = 0; j < $(".TaskConditions_text").eq(i).find(".TaskConditions_textF").length; j++) {
				var H2 = $(".TaskConditions_text").eq(i).find(".TaskConditions_textF").eq(j).height();
				if(maxArr[i] < H2) {
					maxArr[i] = H2;
				}
			}
			for(var j = 0; j < $(".TaskConditions_text").eq(i).find(".TaskConditions_textF").length; j++) {
				$(".TaskConditions_text").eq(i).find(".TaskConditions_textF").eq(j).css("height", maxArr[i] + "px");
			}
		}
	}
	Flen();
	var page = 1;
	var shadowe = 0;
	var data = {
		"page": page
	};
	var value = "";
	var dictionaries = "";
	var brandD = "";

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

	function Dictionaries(val) {
		var count = 0;
		for(var i = 0; i < dictionaries.data.length; i++) {
			if(dictionaries.data[i].id == val) {
				count++;
				return dictionaries.data[i].name
			}
		}
		if(count == 0) {
			return BrandD(val);
		}
	}

	function postPage(data) {
		page = data.page;
		$("#load").css("display", "block");
		$.post(ip + "/interface/index.php/task/index", data, function(data) {
			//		console.log(data);
			$("#load").css("display", "none");
			$(".TaskConditions_textBox").empty();
			if(data != "") {
				var data = JSON.parse(data);
				//		console.log(data);
				for(var i = 0; i < data.data.length; i++) {
					var html = '<div class="TaskConditions_text">' +
						'<div class="TaskConditions_textF">' + data.data[i].id + '</div>' +
						'<div class="TaskConditions_textF text_left TaskConditions_name">' + data.data[i].detail + '</div>' +
						'<div class="TaskConditions_textF text_left" paramI="' + data.data[i].param1 + '">' + Dictionaries(data.data[i].param1) + '</div>' +
						'<div class="TaskConditions_textF text_left" paramI="' + data.data[i].param2 + '">' + Dictionaries(data.data[i].param2) + '</div>' +
						'<div class="TaskConditions_textF text_left" paramI="' + data.data[i].param3 + '">' + Dictionaries(data.data[i].param3) + '</div>' +
						'<div class="TaskConditions_textF text_left" paramI="' + data.data[i].param4 + '">' + Dictionaries(data.data[i].param4) + '</div>' +
						'<div class="TaskConditions_textF text_left" paramI="' + data.data[i].param5 + '">' + Dictionaries(data.data[i].param5) + '</div>' +
						'<div class="TaskConditions_textF text_left" paramI="' + data.data[i].param6 + '">' + Dictionaries(data.data[i].param6) + '</div>' +
						'<div class="TaskConditions_textF text_left" paramI="' + data.data[i].param7 + '">' + Dictionaries(data.data[i].param7) + '</div>' +
						'<div class="TaskConditions_textF text_left" paramI="' + data.data[i].param8 + '">' + Dictionaries(data.data[i].param8) + '</div>' +
						'<div class="TaskConditions_textF text_left">' + data.data[i].num + '</div>' +
						'<div class="TaskConditions_textF text_left"><span>编辑</span><span>删除</span></div>' +
						'<div class="clearfix"></div>' +
						'</div>';
					$(".TaskConditions_textBox").append(html);
				}
				Flen();
				$(".TaskConditions_strip").text(data.count);
				$(".TaskConditions_total").text(data.allpage);
				shadowe = data.allpage;
				if(shadowe == 0) {
					page = 0;
					$(".TaskConditions_flip").val(0);
				} else {
					$(".TaskConditions_flip").val(page);
				}
				if(page <= 1) {
					$(".TaskConditions_prev").addClass("TaskConditions_selectP");
					$(".TaskConditions_index").addClass("TaskConditions_selectP");
				} else {
					$(".TaskConditions_prev").removeClass("TaskConditions_selectP");
					$(".TaskConditions_index").removeClass("TaskConditions_selectP");
				}
				if(page == shadowe) {
					$(".TaskConditions_shadowe").addClass("TaskConditions_selectP");
					$(".TaskConditions_next").addClass("TaskConditions_selectP");
				} else {
					$(".TaskConditions_shadowe").removeClass("TaskConditions_selectP");
					$(".TaskConditions_next").removeClass("TaskConditions_selectP");
				}
			} else {
				shadowe = 0;
				page = 0;
				$(".TaskConditions_flip").val(0);
				$(".TaskConditions_strip").text("0");
				$(".TaskConditions_total").text("0");
				$(".TaskConditions_prev").addClass("CostumeInquiry_selectP");
				$(".TaskConditions_index").addClass("CostumeInquiry_selectP");
				$(".TaskConditions_shadowe").addClass("CostumeInquiry_selectP");
				$(".TaskConditions_next").addClass("CostumeInquiry_selectP");
			}
		});

	}
	$("#load").css("display", "block");
	var dlz = $.get(ip + "/interface/index.php/menu/index", function(data) {
		$("#load").css("display", "none");
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
	})

	// 点击搜索
	$("#TaskConditions_searchB").on("click", function() {
		value = $("#TaskConditions_search").val();
		data = {
			"detail": value,
			"page": 1
		};
		postPage(data);
	});
	// 点击清除
	$("#TaskConditions_clear").on("click", function() {
		$("#TaskConditions_search").val("");
	});
	// 点击首页
	$(".TaskConditions_index").on("click", function() {
		if(!$(this).hasClass("TaskConditions_selectP")) {
			data = {
				"detail": value,
				"page": 1
			};
			postPage(data);
		}
	});
	// 点击上一页
	$(".TaskConditions_prev").on("click", function() {
		if(!$(this).hasClass("TaskConditions_selectP")) {
			data = {
				"detail": value,
				"page": Number(page) - 1
			};
			postPage(data);
		}
	});
	// 点击下一页
	$(".TaskConditions_next").on("click", function() {
		if(!$(this).hasClass("TaskConditions_selectP")) {
			data = {
				"detail": value,
				"page": Number(page) + 1
			};
			postPage(data);
		}
	});
	// 点击尾页
	$(".TaskConditions_shadowe").on("click", function() {
		if(!$(this).hasClass("TaskConditions_selectP")) {
			data = {
				"detail": value,
				"page": shadowe
			};
			postPage(data);
		}
	});
	// 页数输入
	$(".TaskConditions_flip").on("input", function() {
		var p = $(this).val();
		if(p > shadowe) {
			$(this).val(shadowe);
		}
	});
	// 点击跳转
	$(".TaskConditions_jump").on("click", function() {
		var p = $(".TaskConditions_flip").val();
		if(p >= 1) {
			data = {
				"detail": value,
				"page": p
			};
			postPage(data);
		}
	});
</script>