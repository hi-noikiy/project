<link rel="stylesheet" type="text/css" href="../css/fashion_edit.css" />
<link rel="stylesheet" type="text/css" href="../css/TaskConditions.css" />
<script src="../js/jquery.js" type="text/javascript" charset="utf-8"></script>
<script src="../js/configure.js" type="text/javascript" charset="utf-8"></script>
<div class="TaskConditions admin_content_title">
	<div class="admin_content_h1">
		<h1 class="admin_titleH">任务条件管理</h1>
		<div class="TaskConditions_queryBox">
			<div class="TaskConditions_condition">
				<div class="TaskConditions_na">
					<p>任务条件名称：</p>
				</div>
				<div>
					<input id="TaskConditions_search" type="text" />
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="TaskConditions_condition">
				<div></div>
				<div>
					<button id="TaskConditions_searchB">搜索</button>
					<button id="TaskConditions_clear">清除</button>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<div class="TaskConditions_content">

	</div>
	<button class="TaskConditions_news">添加</button>
</div>
<script type="text/javascript">
	var whf = window.location.href.split("?")[1];
	$("#load").css("display", "block");
	if(whf == "TaskConditions") {
		$(".admin_titleH").text("任务条件管理");
		$(".TaskConditions_na p").text("任务条件名称");
		var d1 = $.get("task.php", function(data) {
			$(".TaskConditions_content").append(data);
		});
	} else if(whf == "BrandNew") {
		$(".admin_titleH").text("品牌属性");
		$(".TaskConditions_na p").text("名称");
		d1 = $.get("new.php", function(data) {
			$(".TaskConditions_content").append(data);
		});
	}

	function postFar_id(data, n) {
		$("#load").css("display", "block");
		$.post(ip + "/interface/index.php/menu/index", data, function(data) {
			$("#load").css("display", "none");
			var data = JSON.parse(data);
			for(var i = 0; i < data.data.length; i++) {
				$(".param").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
			}
			if(n == "") {
				postFar_id2();
			} else {
				postFar_id3(n);
			}

		});
	}

	function postFar_id2() {
		$(".TaskEntryF").off('change', "select");
		$(".TaskEntryF").on("change", "select", function() {
			var Sthis = $(this);
			var options = $(this).find("option:selected");
			var params = $(this).parent();
			var farid = {
				"far_id": options.val()
			};
			$(this).nextAll().remove();
			if(options.val() == "") {
				return;
			}
			$("#load").css("display", "block");
			if(farid.far_id == 24) {
				var pw = params.attr("id").substr(params.attr("id").length - 1, 1);
				var html = '<input id="brand' + pw + '" type="text" /><select id="brandv' + pw + '"></select>';
				params.append(html);
				$("#load").css("display", "none");
				$("#brand" + pw).on("input", function() {
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
							$("#brandv" + pw).html(html);
						});
					} else {
						$("#brandv" + pw).empty();
					}
				});
			} else {
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
			}
			postFar_id2();
		});

	}

	function postFar_id3(data) {
		if(data.param == 1) {
			var param1 = $("#param1");
			if(data.brand) {
				farid = {
					"id": data.brand
				};
				for(var i = 0; i < param1.find(".param option").length; i++) {
					if(param1.find(".param option").eq(i).val() == 24) {
						param1.find(".param option").eq(i).attr("selected", true);
					}
				}
				$.post(ip + "/interface/index.php/brand/index", farid, function(data) {
					var data = JSON.parse(data);
					var html = '<input id="brand1" type="text" /><select id="brandv1"></select>';
					param1.append(html);
					for(var i = 0; i < data.data.length; i++) {
						$("#brandv1").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
						$("#brand1").val(data.data[i].name);
					}
					$("#load").css("display", "none");
					$("#brand1").on("input", function() {
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
								$("#brandv1").html(html);
							});
						} else {
							$("#brandv1").empty();
						}

					});
				});
				postFar_id2();
			} else {
				var idL = data.id;
				for(var j = 0; j < param1.find(".param option").length; j++) {
					if(param1.find(".param option").eq(j).val() == data.id[0]) {
						param1.find(".param option").eq(j).attr("selected", true);
					}
				}
				for(var i = 0; i < idL.length; i++) {
					farid = {
						"far_id": idL[i]
					};
					farid_id4(farid, param1, data.id[i + 1]);
				}
				postFar_id2();
			}
		} else if(data.param == 2) {
			var param2 = $("#param2");
			if(data.brand) {
				farid = {
					"id": data.brand
				};
				for(var i = 0; i < param2.find(".param option").length; i++) {
					if(param2.find(".param option").eq(i).val() == 24) {
						param2.find(".param option").eq(i).attr("selected", true);
					}
				}
				$.post(ip + "/interface/index.php/brand/index", farid, function(data) {
					var data = JSON.parse(data);
					var html = '<input id="brand2" type="text" /><select id="brandv2"></select>';
					param2.append(html);
					for(var i = 0; i < data.data.length; i++) {
						$("#brandv2").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
						$("#brand2").val(data.data[i].name);
					}
					$("#load").css("display", "none");
					$("#brand2").on("input", function() {
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
								$("#brandv2").html(html);
							});
						} else {
							$("#brandv2").empty();
						}

					});
				});
				postFar_id2();
			} else {
				var idL = data.id;
				for(var j = 0; j < param2.find(".param option").length; j++) {
					if(param2.find(".param option").eq(j).val() == data.id[0]) {
						param2.find(".param option").eq(j).attr("selected", true);
					}
				}
				for(var i = 0; i < idL.length; i++) {
					farid = {
						"far_id": idL[i]
					};
					farid_id4(farid, param2, data.id[i + 1]);
				}
				postFar_id2();
			}
		} else if(data.param == 3) {
			var param3 = $("#param3");
			if(data.brand) {
				farid = {
					"id": data.brand
				};
				for(var i = 0; i < param3.find(".param option").length; i++) {
					if(param3.find(".param option").eq(i).val() == 24) {
						param3.find(".param option").eq(i).attr("selected", true);
					}
				}
				$.post(ip + "/interface/index.php/brand/index", farid, function(data) {
					var data = JSON.parse(data);
					var html = '<input id="brand3" type="text" /><select id="brandv3"></select>';
					param3.append(html);
					for(var i = 0; i < data.data.length; i++) {
						$("#brandv3").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
						$("#brand3").val(data.data[i].name);
					}
					$("#load").css("display", "none");
					$("#brand3").on("input", function() {
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
								$("#brandv3").html(html);
							});
						} else {
							$("#brandv3").empty();
						}

					});
				});
				postFar_id2();
			} else {
				var idL = data.id;
				for(var j = 0; j < param3.find(".param option").length; j++) {
					if(param3.find(".param option").eq(j).val() == data.id[0]) {
						param3.find(".param option").eq(j).attr("selected", true);
					}
				}
				for(var i = 0; i < idL.length; i++) {
					farid = {
						"far_id": idL[i]
					};
					farid_id4(farid, param3, data.id[i + 1]);
				}
				postFar_id2();
			}
		} else if(data.param == 4) {
			var param4 = $("#param4");
			if(data.brand) {
				farid = {
					"id": data.brand
				};
				for(var i = 0; i < param4.find(".param option").length; i++) {
					if(param4.find(".param option").eq(i).val() == 24) {
						param4.find(".param option").eq(i).attr("selected", true);
					}
				}
				$.post(ip + "/interface/index.php/brand/index", farid, function(data) {
					var data = JSON.parse(data);
					var html = '<input id="brand4" type="text" /><select id="brandv4"></select>';
					param4.append(html);
					for(var i = 0; i < data.data.length; i++) {
						$("#brandv4").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
						$("#brand4").val(data.data[i].name);
					}
					$("#load").css("display", "none");
					$("#brand4").on("input", function() {
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
								$("#brandv4").html(html);
							});
						} else {
							$("#brandv4").empty();
						}

					});
				});
				postFar_id2();
			} else {
				var idL = data.id;
				for(var j = 0; j < param4.find(".param option").length; j++) {
					if(param4.find(".param option").eq(j).val() == data.id[0]) {
						param4.find(".param option").eq(j).attr("selected", true);
					}
				}
				for(var i = 0; i < idL.length; i++) {
					farid = {
						"far_id": idL[i]
					};
					farid_id4(farid, param4, data.id[i + 1]);
				}
				postFar_id2();
			}
		} else if(data.param == 5) {
			var param5 = $("#param5");
			if(data.brand) {
				farid = {
					"id": data.brand
				};
				for(var i = 0; i < param5.find(".param option").length; i++) {
					if(param5.find(".param option").eq(i).val() == 24) {
						param5.find(".param option").eq(i).attr("selected", true);
					}
				}
				$.post(ip + "/interface/index.php/brand/index", farid, function(data) {
					var data = JSON.parse(data);
					var html = '<input id="brand5" type="text" /><select id="brandv5"></select>';
					param5.append(html);
					for(var i = 0; i < data.data.length; i++) {
						$("#brandv5").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
						$("#brand5").val(data.data[i].name);
					}
					$("#load").css("display", "none");
					$("#brand5").on("input", function() {
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
								$("#brandv5").html(html);
							});
						} else {
							$("#brandv5").empty();
						}

					});
				});
				postFar_id2();
			} else {
				var idL = data.id;
				for(var j = 0; j < param5.find(".param option").length; j++) {
					if(param5.find(".param option").eq(j).val() == data.id[0]) {
						param5.find(".param option").eq(j).attr("selected", true);
					}
				}
				for(var i = 0; i < idL.length; i++) {
					farid = {
						"far_id": idL[i]
					};
					farid_id4(farid, param5, data.id[i + 1]);
				}
				postFar_id2();
			}
		} else if(data.param == 6) {
			var param6 = $("#param6");
			if(data.brand) {
				farid = {
					"id": data.brand
				};
				for(var i = 0; i < param6.find(".param option").length; i++) {
					if(param6.find(".param option").eq(i).val() == 24) {
						param6.find(".param option").eq(i).attr("selected", true);
					}
				}
				$.post(ip + "/interface/index.php/brand/index", farid, function(data) {
					var data = JSON.parse(data);
					var html = '<input id="brand6" type="text" /><select id="brandv6"></select>';
					param6.append(html);
					for(var i = 0; i < data.data.length; i++) {
						$("#brandv6").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
						$("#brand6").val(data.data[i].name);
					}
					$("#load").css("display", "none");
					$("#brand6").on("input", function() {
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
								$("#brandv6").html(html);
							});
						} else {
							$("#brandv6").empty();
						}

					});
				});
				postFar_id2();
			} else {
				var idL = data.id;
				for(var j = 0; j < param6.find(".param option").length; j++) {
					if(param6.find(".param option").eq(j).val() == data.id[0]) {
						param6.find(".param option").eq(j).attr("selected", true);
					}
				}
				for(var i = 0; i < idL.length; i++) {
					farid = {
						"far_id": idL[i]
					};
					farid_id4(farid, param6, data.id[i + 1]);
				}
				postFar_id2();
			}
		} else if(data.param == 7) {
			var param7 = $("#param7");
			if(data.brand) {
				farid = {
					"id": data.brand
				};
				for(var i = 0; i < param7.find(".param option").length; i++) {
					if(param7.find(".param option").eq(i).val() == 24) {
						param7.find(".param option").eq(i).attr("selected", true);
					}
				}
				$.post(ip + "/interface/index.php/brand/index", farid, function(data) {
					var data = JSON.parse(data);
					var html = '<input id="brand7" type="text" /><select id="brandv7"></select>';
					param7.append(html);
					for(var i = 0; i < data.data.length; i++) {
						$("#brandv7").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
						$("#brand7").val(data.data[i].name);
					}
					$("#load").css("display", "none");
					$("#brand7").on("input", function() {
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
								$("#brandv7").html(html);
							});
						} else {
							$("#brandv7").empty();
						}

					});
				});
				postFar_id2();
			} else {
				var idL = data.id;
				for(var j = 0; j < param7.find(".param option").length; j++) {
					if(param7.find(".param option").eq(j).val() == data.id[0]) {
						param7.find(".param option").eq(j).attr("selected", true);
					}
				}
				for(var i = 0; i < idL.length; i++) {
					farid = {
						"far_id": idL[i]
					};
					farid_id4(farid, param7, data.id[i + 1]);
				}
				postFar_id2();
			}
		} else if(data.param == 8) {
			var param8 = $("#param8");
			if(data.brand) {
				farid = {
					"id": data.brand
				};
				for(var i = 0; i < param8.find(".param option").length; i++) {
					if(param8.find(".param option").eq(i).val() == 24) {
						param8.find(".param option").eq(i).attr("selected", true);
					}
				}
				$.post(ip + "/interface/index.php/brand/index", farid, function(data) {
					var data = JSON.parse(data);
					var html = '<input id="brand8" type="text" /><select id="brandv8"></select>';
					param8.append(html);
					for(var i = 0; i < data.data.length; i++) {
						$("#brandv8").append('<option value="' + data.data[i].id + '">' + data.data[i].name + '</option>');
						$("#brand8").val(data.data[i].name);
					}
					$("#load").css("display", "none");
					$("#brand8").on("input", function() {
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
								$("#brandv8").html(html);
							});
						} else {
							$("#brandv8").empty();
						}

					});
				});
				postFar_id2();
			} else {
				var idL = data.id;
				for(var j = 0; j < param8.find(".param option").length; j++) {
					if(param8.find(".param option").eq(j).val() == data.id[0]) {
						param8.find(".param option").eq(j).attr("selected", true);
					}
				}
				for(var i = 0; i < idL.length; i++) {
					farid = {
						"far_id": idL[i]
					};
					farid_id4(farid, param8, data.id[i + 1]);
				}
				postFar_id2();
			}
		}
	}

	function farid_id4(farid, params, sel) {
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
	$.when(d1).done(function() {
		$("#load").css("display", "none");
		// 点击添加
		$(".TaskConditions_news").on("click", function() {
			$("#load").css("display", "none");
			var whf = window.location.href.split("?")[1];
			//		console.log(whf);
			if(whf == "TaskConditions") {
				$.get("TaskEntry.php", function(data) {
					$(".TaskConditions").addClass("display");
					$("body").append(data);
					var farid = {
						"far_id": 0
					};
					postFar_id(farid, "");
					// 点击X
					$(".admin_X").on("click", function() {
						$(".TaskConditions").removeClass("display");
						$(".admin_content_TaskEntry").remove();
						//						console.log(postPage(data))
						data = {
							"detail": value,
							"page": page
						};
						postPage(data);
					});
					// 点击保存
					$("#Preservation").on("click", function() {
						var paramName = $("#paramName").val();
						var paramNum = $("#paramNum").val();
						if(paramName == "" || paramNum == "") {
							alert("必选项未填!");
							return;
						}
						var param1 = $("#param1 select");
						var param2 = $("#param2 select");
						var param3 = $("#param3 select");
						var param4 = $("#param4 select");
						var param5 = $("#param5 select");
						var param6 = $("#param6 select");
						var param7 = $("#param7 select");
						var param8 = $("#param8 select");
						var len1 = param1.length;
						var len2 = param2.length;
						var len3 = param3.length;
						var len4 = param4.length;
						var len5 = param5.length;
						var len6 = param6.length;
						var len7 = param7.length;
						var len8 = param8.length;
						var value1 = param1.eq(len1 - 1).find("option:selected").val();
						if(len1 == 1) {
							value1 = param1.eq(len1 - 1).find("option:selected").val();
						} else if(value1 == "") {
							value1 = param1.eq(len1 - 2).find("option:selected").val();
						}
						var value2 = param2.eq(len2 - 1).find("option:selected").val();
						if(len2 == 1) {
							value2 = param2.eq(len2 - 1).find("option:selected").val();
						} else if(value2 == "") {
							value2 = param2.eq(len2 - 2).find("option:selected").val();
						}
						var value3 = param3.eq(len3 - 1).find("option:selected").val();
						if(len3 == 1) {
							value3 = param3.eq(len3 - 1).find("option:selected").val();
						} else if(value3 == "") {
							value3 = param3.eq(len3 - 2).find("option:selected").val();
						}
						var value4 = param4.eq(len4 - 1).find("option:selected").val();
						if(len4 == 1) {
							value4 = param4.eq(len4 - 1).find("option:selected").val();
						} else if(value4 == "") {
							value4 = param4.eq(len4 - 2).find("option:selected").val();
						}
						var value5 = param5.eq(len5 - 1).find("option:selected").val();
						if(len5 == 5) {
							value5 = param5.eq(len5 - 1).find("option:selected").val();
						} else if(value5 == "") {
							value5 = param5.eq(len5 - 2).find("option:selected").val();
						}
						var value6 = param6.eq(len6 - 1).find("option:selected").val();
						if(len6 == 1) {
							value6 = param6.eq(len6 - 1).find("option:selected").val();
						} else if(value6 == "") {
							value6 = param6.eq(len6 - 2).find("option:selected").val();
						}
						var value7 = param7.eq(len7 - 1).find("option:selected").val();
						if(len7 == 1) {
							value7 = param7.eq(len7 - 1).find("option:selected").val();
						} else if(value7 == "") {
							value7 = param7.eq(len7 - 2).find("option:selected").val();
						}
						var value8 = param8.eq(len8 - 1).find("option:selected").val();
						if(len8 == 1) {
							value8 = param8.eq(len8 - 1).find("option:selected").val();
						} else if(value8 == "") {
							value8 = param8.eq(len8 - 2).find("option:selected").val();
						}
						var data = {
							"num": paramNum,
							"detail": paramName,
							"param1": value1,
							"param2": value2,
							"param3": value3,
							"param4": value4,
							"param5": value5,
							"param6": value6,
							"param7": value7,
							"param8": value8
						};
						$("#load").css("display", "block");
						$.post(ip + "/interface/index.php/task/add", data, function(data) {
							$("#load").css("display", "none");
							var data = JSON.parse(data);
							alert(data.msg);
						});

					});
				});
			} else if(whf == "BrandNew") {
				$.get("BrandNew.php", function(data) {
					$(".TaskConditions").addClass("display");
					$("body").append(data);
					var paramLogo = $("#BrandNew_logo");
					var paramPro = $("#BrandNew_Pro");
					var LogoData = "";
					var ProData = "";
					// 图片数据流
					fileImg(paramLogo, function(a) {
						LogoData = a;
					});
					fileImg(paramPro, function(a) {
						ProData = a;
					});

					// 点击增加
					$(".BrandNew_condition .BrandNewF button:nth-of-type(1)").on("click", function() {
						var paramName = $("#BrandNew_name").val();
						var paramAbb = $("#BrandNew_Abb").val();
						var paramON = $("#BrandNew_ON").val();
						var paramShop = $("#BrandNew_Shop").val();
						var paramText = $("#BrandNew_text").text();
						var data = {
							"name": paramName,
							"shorthand": paramAbb,
							"logo": LogoData,
							"photo": ProData,
							"website": paramON,
							"shop": paramShop,
							"describe1": paramText
						};
						//						console.log(data);
						$("#load").css("display", "block");
						$.post(ip + "/interface/index.php/brand/add", data, function(data) {
							$("#load").css("display", "none");
							var data = JSON.parse(data);
							alert(data.msg);
							if(data.status == 0) {
								$(".TaskConditions").removeClass("display");
								$(".admin_content_BrandNew").remove();
								data = {
									"name": value,
									"page": page
								};
								postPage(data);
							}
						});

					});
					// 点击取消
					$(".BrandNew_condition .BrandNewF button:nth-of-type(2)").on("click", function() {
						$(".TaskConditions").removeClass("display");
						$(".admin_content_BrandNew").remove();
					});
				});
			}

		});
		// 点击编辑
		$(".TaskConditions_content").on("click", ".TaskConditions_text .TaskConditions_textF span:nth-of-type(1)", function() {
			$("#load").css("display", "block");
			var whf = window.location.href.split("?")[1];
			//			console.log(arrP);
			if(whf == "TaskConditions") {
				var params = $(this).parent().parent().find(".TaskConditions_textF");
				var arrP = [params.eq(0).text(), params.eq(1).text(), params.eq(params.length - 2).text()];
				//				console.log(arrP);
				for(var i = 2; i < params.length - 2; i++) {
					arrP.push(params.eq(i).attr("paramI"));
				}

				$.get("TaskEntry.php", function(data) {
					$("#load").css("display", "none");
					$(".TaskConditions").addClass("display");
					$("body").append(data);
					var paramName = $("#paramName").val(arrP[1]);
					var paramNum = $("#paramNum").val(arrP[2]);
					var id = "";
					var farid = {
						"far_id": 0
					};

					function postIds(id, farid, num) {
						$("#load").css("display", "block");
						$.post(ip + "/interface/index.php/menu/find", id, function(data) {
							$("#load").css("display", "none");
							var data = JSON.parse(data);
							if(data.status == 2) {
								var faridD = {
									"brand": id.id,
									"param": num
								};
								postFar_id(farid, faridD);
							} else if(data.status == 0) {
								var arrI = [];
								var farI = [];
								for(var p in data.data) {
									arrI.push(data.data[p].id);
									farI.push(data.data[p].far_id);
								}
								var faridD = {
									"id": arrI,
									"param": num,
									"far_id": farI
								};
								postFar_id(farid, faridD);
							}

						});
					}
					for(var i = 3; i < arrP.length; i++) {
						id = {
							"id": arrP[i]
						};
						postIds(id, farid, i - 2);
					}
					$(".admin_X").on("click", function() {
						$(".TaskConditions").removeClass("display");
						$(".admin_content_TaskEntry").remove();
					});
					// 点击保存
					$("#Preservation").on("click", function() {
						var paramName = $("#paramName").val();
						var paramNum = $("#paramNum").val();
						if(paramName == "" || paramNum == "") {
							alert("必选项未填!");
							return;
						}
						var param1 = $("#param1 select");
						var param2 = $("#param2 select");
						var param3 = $("#param3 select");
						var param4 = $("#param4 select");
						var param5 = $("#param5 select");
						var param6 = $("#param6 select");
						var param7 = $("#param7 select");
						var param8 = $("#param8 select");
						var len1 = param1.length;
						var len2 = param2.length;
						var len3 = param3.length;
						var len4 = param4.length;
						var len5 = param5.length;
						var len6 = param6.length;
						var len7 = param7.length;
						var len8 = param8.length;
						var value1 = param1.eq(len1 - 1).find("option:selected").val();
						if(len1 == 1) {
							value1 = param1.eq(len1 - 1).find("option:selected").val();
						} else if(value1 == "") {
							value1 = param1.eq(len1 - 2).find("option:selected").val();
						}
						var value2 = param2.eq(len2 - 1).find("option:selected").val();
						if(len2 == 1) {
							value2 = param2.eq(len2 - 1).find("option:selected").val();
						} else if(value2 == "") {
							value2 = param2.eq(len2 - 2).find("option:selected").val();
						}
						var value3 = param3.eq(len3 - 1).find("option:selected").val();
						if(len3 == 1) {
							value3 = param3.eq(len3 - 1).find("option:selected").val();
						} else if(value3 == "") {
							value3 = param3.eq(len3 - 2).find("option:selected").val();
						}
						var value4 = param4.eq(len4 - 1).find("option:selected").val();
						if(len4 == 1) {
							value4 = param4.eq(len4 - 1).find("option:selected").val();
						} else if(value4 == "") {
							value4 = param4.eq(len4 - 2).find("option:selected").val();
						}
						var value5 = param5.eq(len5 - 1).find("option:selected").val();
						if(len5 == 5) {
							value5 = param5.eq(len5 - 1).find("option:selected").val();
						} else if(value5 == "") {
							value5 = param5.eq(len5 - 2).find("option:selected").val();
						}
						var value6 = param6.eq(len6 - 1).find("option:selected").val();
						if(len6 == 1) {
							value6 = param6.eq(len6 - 1).find("option:selected").val();
						} else if(value6 == "") {
							value6 = param6.eq(len6 - 2).find("option:selected").val();
						}
						var value7 = param7.eq(len7 - 1).find("option:selected").val();
						if(len7 == 1) {
							value7 = param7.eq(len7 - 1).find("option:selected").val();
						} else if(value7 == "") {
							value7 = param7.eq(len7 - 2).find("option:selected").val();
						}
						var value8 = param8.eq(len8 - 1).find("option:selected").val();
						if(len8 == 1) {
							value8 = param8.eq(len8 - 1).find("option:selected").val();
						} else if(value8 == "") {
							value8 = param8.eq(len8 - 2).find("option:selected").val();
						}
						var data = {
							"id": arrP[0],
							"num": paramNum,
							"detail": paramName,
							"param1": value1,
							"param2": value2,
							"param3": value3,
							"param4": value4,
							"param5": value5,
							"param6": value6,
							"param7": value7,
							"param8": value8
						};
						//												console.log(data);
						$("#load").css("display", "block");
						$.post(ip + "/interface/index.php/task/update", data, function(data) {
							$("#load").css("display", "none");
							var data = JSON.parse(data);
							alert(data.msg);
							if(data.status == 0) {
								$(".TaskConditions").removeClass("display");
								$(".admin_content_TaskEntry").remove();
								data = {
									"detail": value,
									"page": page
								};
								postPage(data);
							}
						});

					});

				});

			} else if(whf == "BrandNew") {
				var params = $(this).parent().parent().find(".TaskConditions_textF");
				var index = $(this).parent().parent().find(".TaskConditions_textF span:nth-of-type(1)").attr("index");
				var arrP2 = [];
				for(var i = 0; i < params.length - 1; i++) {
					arrP2.push(params.eq(i).text());
				}
				$.get("BrandNew.php", function(data) {
					$(".TaskConditions").addClass("display");
					$("body").append(data);
					$("#load").css("display", "none");
					//					console.log(describe1Arr);
					//					console.log(arrP2[3]);
					//					console.log(arrP2[4]);
					$("#BrandNew_titleH").text("编辑品牌");
					$(".BrandNew_condition .BrandNewF button:nth-of-type(1)").text("修改");
					var paramLogo = $("#BrandNew_logo");
					var paramPro = $("#BrandNew_Pro");
					$("#BrandNew_name").val(arrP2[1]);
					$("#BrandNew_Abb").val(arrP2[2]);
					$("#BrandNew_ON").val(arrP2[5]);
					$("#BrandNew_Shop").val(arrP2[6]);
					$("#BrandNew_text").text(describe1Arr[index]);
					$("#BrandNew_logo").next().text(arrP2[3]);
					$("#BrandNew_Pro").next().text(arrP2[4]);
					var LogoData = "";
					var ProData = "";
					// 图片数据流
					fileImg(paramLogo, function(a) {
						LogoData = a;
					});
					fileImg(paramPro, function(a) {
						ProData = a;
					});

					// 点击编辑
					$(".BrandNew_condition .BrandNewF button:nth-of-type(1)").on("click", function() {
						var paramName = $("#BrandNew_name").val();
						var paramAbb = $("#BrandNew_Abb").val();
						var paramON = $("#BrandNew_ON").val();
						var paramShop = $("#BrandNew_Shop").val();
						var paramText = $("#BrandNew_text").text();
						var data = {
							"id": arrP2[0],
							"name": paramName,
							"shorthand": paramAbb,
							"logo": LogoData,
							"photo": ProData,
							"website": paramON,
							"shop": paramShop,
							"describe1": paramText
						};
						//						console.log(data);
						$("#load").css("display", "block");
						$.post(ip + "/interface/index.php/brand/update", data, function(data) {
							$("#load").css("display", "none");
							var data = JSON.parse(data);
							alert(data.msg);
							if(data.status == 0) {
								$(".TaskConditions").removeClass("display");
								$(".admin_content_BrandNew").remove();
								data = {
									"name": value,
									"page": page
								};
								postPage(data);
							}
						});

					});
					// 点击取消
					$(".BrandNew_condition .BrandNewF button:nth-of-type(2)").on("click", function() {
						$(".TaskConditions").removeClass("display");
						$(".admin_content_BrandNew").remove();
					});
				});
			}
		});
		// 点击删除
		$(".TaskConditions_content").on("click", ".TaskConditions_text .TaskConditions_textF span:nth-of-type(2)", function() {
			var whf = window.location.href.split("?")[1];
			//			console.log(arrP);
			if(whf == "TaskConditions") {
				var params = $(this).parent().parent().find(".TaskConditions_textF");
				var data = {
					"id": params.eq(0).text()
				};
				if(confirm("是否删除")) {
					$("#load").css("display", "block");
					if($(".TaskConditions_text").length == 1) {
						page = Number(page) - 1;
						if(shadowe < 1) {
							return;
						}
					}
					$.post(ip + "/interface/index.php/task/delete", data, function(data) {
						$("#load").css("display", "none");
						var data = JSON.parse(data);
						alert(data.msg);
						if(data.status == 0) {
							data = {
								"detail": value,
								"page": page
							};
							postPage(data);
						}
					});
				}
			} else if(whf == "BrandNew") {
				var params = $(this).parent().parent().find(".TaskConditions_textF");
				var data = {
					"id": params.eq(0).text()
				};
				if(confirm("是否删除")) {
					$("#load").css("display", "block");
					if($(".TaskConditions_text").length == 1) {
						page = Number(page) - 1;
						if(shadowe < 1) {
							return;
						}
					}
					$.post(ip + "/interface/index.php/brand/delete", data, function(data) {
						$("#load").css("display", "none");
						var data = JSON.parse(data);
						alert(data.msg);
						if(data.status == 0) {
							data = {
								"name": value,
								"page": page
							};
							postPage(data);
						}
					});
				}
			}
		});
	});
</script>
<div id="load" style="position:fixed;width: 100%;height: 100%;z-index: 1000;top: 0px;left: 0px;display: none;">
	<img style="position: absolute;top: 50%;transform: translate(-50%,-50%);left: 50%;-webkit-transition: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);" src="../img/load.gif" />
</div>