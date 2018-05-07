<div class="TaskConditions_title">
	<div class="TaskConditions_titleF">编号</div>
	<div class="TaskConditions_titleF">名称</div>
	<div class="TaskConditions_titleF">简称</div>
	<div class="TaskConditions_titleF">logo</div>
	<div class="TaskConditions_titleF">宣传图</div>
	<div class="TaskConditions_titleF">官网链接</div>
	<div class="TaskConditions_titleF">商城链接</div>
	<div class="TaskConditions_titleF">操作</div>
	<div class="clearfix"></div>
</div>
<div class="TaskConditions_textBox">
	<!--<div class="TaskConditions_text">
				<div class="TaskConditions_textF">101</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
				<div class="TaskConditions_textF text_left">数据</div>
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
		var len = $(".TaskConditions_title .TaskConditions_titleF").length;
		//				console.log(len);
		$(".TaskConditions_titleF").css("width", 100 / len + "%");
		$(".TaskConditions_textF").css("width", 100 / len + "%");
		var maxArr = [];
		for(var i = 0; i < $(".TaskConditions_text").length; i++) {
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
	var describe1Arr = [];

	function Dictionaries(val) {
		var count = 0;
		for(var i = 0; i < dictionaries.data.length; i++) {
			if(dictionaries.data[i].id == val) {
				count++;
				return dictionaries.data[i].name;
			}
		}
		if(count == 0) {
			return val;
		}
	}

	function postPage(data) {
		page = data.page;
		$("#load").css("display", "block");
		$.post(ip + "/interface/index.php/brand/index", data, function(data) {
			//		console.log(data);
			$("#load").css("display", "none");
			$(".TaskConditions_textBox").empty();
			if(data != "") {
				var data = JSON.parse(data);
				//console.log(data);
				describe1Arr = [];
				for(var i = 0; i < data.data.length; i++) {
					var html = '<div class="TaskConditions_text">' +
						'<div class="TaskConditions_textF">' + data.data[i].id + '</div>' +
						'<div class="TaskConditions_textF text_left">' + data.data[i].name + '</div>' +
						'<div class="TaskConditions_textF text_left">' + data.data[i].shorthand + '</div>';
					html += '<div class="TaskConditions_textF text_left">' + data.data[i].logo + '</div>';
					html += '<div class="TaskConditions_textF text_left">' + data.data[i].photo + '</div>';
					html += '<div class="TaskConditions_textF text_left">' + data.data[i].website + '</div>' +
						'<div class="TaskConditions_textF text_left">' + data.data[i].shop + '</div>' +
						'<div class="TaskConditions_textF text_left"><span index="' + i + '">编辑</span><span>删除</span></div>' +
						'<div class="clearfix"></div>' +
						'</div>';
					describe1Arr.push(data.data[i].describe1);
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
	postPage(data);
	// 点击搜索
	$("#TaskConditions_searchB").on("click", function() {
		value = $("#TaskConditions_search").val();
		data = {
			"name": value,
			"page": 1
		};
		//		console.log(data);
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
				"name": value,
				"page": 1
			};
			postPage(data);
		}
	});
	// 点击上一页
	$(".TaskConditions_prev").on("click", function() {
		if(!$(this).hasClass("TaskConditions_selectP")) {
			data = {
				"name": value,
				"page": Number(page) - 1
			};
			postPage(data);
		}
	});
	// 点击下一页
	$(".TaskConditions_next").on("click", function() {
		if(!$(this).hasClass("TaskConditions_selectP")) {
			data = {
				"name": value,
				"page": Number(page) + 1
			};
			postPage(data);
		}
	});
	// 点击尾页
	$(".TaskConditions_shadowe").on("click", function() {
		if(!$(this).hasClass("TaskConditions_selectP")) {
			data = {
				"name": value,
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
				"name": value,
				"page": p
			};
			postPage(data);
		}
	});
</script>