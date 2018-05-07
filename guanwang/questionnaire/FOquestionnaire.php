<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>问卷形式</title>
		<link rel="stylesheet" type="text/css" href="css/questionnaire.css" />
		<script src="js/jquery.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/template-web.js" type="text/javascript" charset="utf-8"></script>
	</head>

	<body>
		<div class="print">
			<header>
				<p>游戏小调查</p>
			</header>
			<div class="FOqBox">
				<!--<div class="FOqcontent">
					<h1><span>*</span>1、第一题<span>重要</span> (单选)</h1>
					<div>
						<input type="radio" id="g" name="g" value="1" />
						<label for="g">12-13局</label>
					</div>
					<div>
						<input type="radio" id="y" name="g" value="2" />
						<label for="y">12-13局</label>
					</div>
					<div>
						<input type="radio" id="j" name="g" value="3" />
						<label for="j">12-13局</label>
					</div>
					<div>
						<input type="radio" id="r" name="g" value="4" />
						<label for="r">12-13局</label>
					</div>
					<div>
						<input type="radio" id="p" name="g" value="5" />
						<label for="p">12-13局</label>
					</div>
				</div>
				<div class="FOqcontent">
					<h1><span>*</span>2、第二题<span>重要</span> (多选)</h1>
					<div>
						<input type="checkbox" id="d" name="g" value="1" />
						<label for="d">12-13局</label>
					</div>
					<div>
						<input type="checkbox" id="f" name="g" value="2" />
						<label for="f">12-13局</label>
					</div>
					<div>
						<input type="checkbox" id="a" name="g" value="3" />
						<label for="a">12-13局</label>
					</div>
					<div>
						<input type="checkbox" id="s" name="g" value="4" />
						<label for="s">12-13局</label>
					</div>
					<div>
						<p>其他</p>
						<textarea name="" rows="" cols=""></textarea>
					</div>
				</div>-->
				<!--<div class="FOqcontent">
					<div class="FOinput">
						<p>建议</p>
						<textarea name="" rows="" cols=""></textarea>
					</div>
				</div>-->
			</div>
			<div class="FOqSub">
				<a>提交</a>
			</div>
		</div>
		<script id="FOqcontent" type="text/html">
			<div class="FOqcontent">
				{{if type=="radio" || type=="checkbox"}}
					{{include 'FOH1'}}
				{{/if}}
				{{if type=="radio" && input=="y"}}
					{{include 'FORadio'}}
					{{include 'FOProposal'}}
				{{else if type=="checkbox" && input=="y"}}
					{{include 'FOCheckbox'}}
					{{include 'FOProposal'}}
				{{else if type=="radio" && input!="y"}}
					{{include 'FORadio'}}
				{{else if type=="checkbox" && input!="y"}}
					{{include 'FOCheckbox'}}
				{{else if type=="proposal"}}
					{{include 'FOProposal'}}
				{{/if}}
			</div>
		</script>
		<script id="FOH1" type="text/html">
			<h1><span style="color: red;">{{if choice=="choice"}}*{{/if}}</span><span class="tit">内容</span>&nbsp;{{if type=="radio"}}(单选){{else if type=="checkbox"}}(多选){{/if}}</h1>
		</script>
		<script id="FORadio" type="text/html">
			{{each option value i}}
				<div>
					<input type="radio" id="r{{num}}_{{i+1}}" name="r{{num}}" other="{{GetOption(value)[1]}}" {{if i==0}}checked="checked"{{/if}} value="{{num}}-{{i+1}}" />
					<label for="r{{num}}_{{i+1}}">{{GetOption(value)[0]}}</label>
				</div>
			{{/each}}
		</script>
		<script id="FOCheckbox" type="text/html">
			{{each option value i}}
				<div>
					<input type="checkbox" id="c{{num}}_{{i+1}}" other="{{GetOption(value)[1]}}" name="c{{num}}" value="{{num}}-{{i+1}}" />
					<label for="c{{num}}_{{i+1}}">{{GetOption(value)[0]}}</label>
				</div>
			{{/each}}
		</script>
		<script id="FOProposal" type="text/html">
			<div class="FOinput" {{if type=="proposal"}}style="border-top: #000000 solid 2px;margin-top: 20px;"{{/if}}>
				<p {{if type=="proposal"}}id="proposal"{{/if}}>{{inputTitle}}</p>
				<textarea name="t{{num}}" rows="" cols="">{{inputTxt}}</textarea>
			</div>
		</script>
		<script type="text/javascript">
			// 自定义弹出层
			function Alert(e,a) {
				$("body").append('<div id="msg"><div class="model"></div><div class="content"><p>提示</p><p>'+e+'</p><button>确定</button></div></div>');
				$("#msg button").on("click",function(){
					if(typeof a === "function" && a!=undefined) { //是函数    其中 FunName 为函数名称
						a();
		           	}
					$("#msg").remove();
				});
				$("#msg .content").css('transform', 'rotate('+Orientation+'deg)');
				$("#msg .content").css('transform-origin', '50% 50%');
			}
			// 遍历class的函数
			function getElementsClass(classnames) {
				var classobj = new Array(); //定义数组 
				var classint = 0; //定义数组的下标 
				var tags = document.getElementsByTagName("*"); //获取HTML的所有标签 
				for(var i in tags) { //对标签进行遍历 
					if(tags[i].nodeType == 1) { //判断节点类型 
						if(tags[i].getAttribute("class") == classnames) //判断和需要CLASS名字相同的，并组成一个数组 
						{
							classobj[classint] = tags[i];
							classint++;
						}
					}
				}
				return classobj; //返回组成的数组 
			}
			// 旋转监听
			var evt = "onorientationchange" in window ? "orientationchange" : "resize";
			// 旋转角度
			var Orientation = "0";
			window.addEventListener(evt, function() {
				//console.log(evt);
				var width = document.documentElement.clientWidth;
				var height = document.documentElement.clientHeight;
				var print = $('.print');
				//console.log(window.orientation);
				if(window.orientation != undefined){
					if(width > height) {
						Orientation = window.orientation;
						print.width(width);
						print.height(height);
						print.css('top', 0);
						print.css('left', 0);
						print.css('transform', 'none');
						print.css('transform-origin', '50% 50%');
					} else {
						Orientation = window.orientation;
						print.width(height);
						print.height(width);
						print.css('top', "-40%");
						print.css('left', 0 - (height - width) / 2);
						print.css('transform', 'rotate('+window.orientation+'deg)');
						print.css('transform-origin', '50% 50%');
					}
					$("#msg .content").css('transform', 'rotate('+Orientation+'deg)');
					$("#msg .content").css('transform-origin', '50% 50%');
				}else{
					if(width > height) {
						Orientation = "0";
						print.width(width);
						print.height(height);
						print.css('top', 0);
						print.css('left', 0);
						print.css('transform', 'none');
						print.css('transform-origin', '50% 50%');
					} else {
						Orientation = "90";
						print.width(height);
						print.height(width);
						print.css('top', (height - width) / 2);
						print.css('left', 0 - (height - width) / 2);
						print.css('transform', 'rotate(90deg)');
						print.css('transform-origin', '50% 50%');
					}
					$("#msg .content").css('transform', 'rotate('+Orientation+'deg)');
					$("#msg .content").css('transform-origin', '50% 50%');
				}
			}, false);
			
			// 其他选项的方法
			template.defaults.imports.GetOption = function(data) {
				var arr = [];
				var reg = RegExp(/#/);
				if(reg.test(data)){
					data = data.split("#")[1];
					arr.push(data);
					arr.push("true");
				}else{
					arr.push(data);
					arr.push("false");
				}
				return arr;
			}
			// 获取请求单个请求数据
			function GetQueryString(name) {
				var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
				var r = window.location.search.substr(1).match(reg);
				if(r != null) return decodeURI(r[2]);
				return "";
			}
			var subject = "";
			//获取期数
			var periods = GetQueryString("nper");
			// 获取数据
			$.get("json/FOq"+periods+".json", function(data) {
				// 遍历题目
				subject = data;
				var html = "";
//				console.log(subject);
				for(var i = 0; i < subject.length; i++) {
					subject[i].num = i+1;
					if(subject[i].type == "radio") {
						html += template('FOqcontent', subject[i]);
					} else if(subject[i].type == "checkbox") {
						html += template('FOqcontent', subject[i]);
					} else if(subject[i].type == "proposal") {
						html += template('FOqcontent', subject[i]);
					}
				}
				// 拼接数据
				$('.FOqBox').html(html);
				// h1内容添加
				for(var i = 0; i < subject.length; i++) {
					$('.tit').eq(subject[i].num-1).html(subject[i].title);
				}
			});
			// 提交
			var sub = getElementsClass('FOqSub')[0].getElementsByTagName("a")[0];
			sub.addEventListener("click", function() {
				//console.log(subject);
				var data = {
								"userinfo": {
									"serverid": GetQueryString("serverid"),
									"userid": GetQueryString("userid"),
									"nper": periods
								},
								"object": []
						};
				// 遍历题数
				for (var i = 0; i < subject.length; i++) {
					var objectOn = {
									"title": "",
									"option": [],
									"type": "",
									"text": "",
									"content":[]
									};
					var title = $('.FOqcontent').eq(i).find('h1').text();
					//console.log(title);
					if(subject[i].type == "radio") {
						// 单选
						var radio = $("input:radio[name='r"+subject[i].num+"']:checked").val();
						var rc = $("input:radio[name='r"+subject[i].num+"']:checked").parent().find("label").text();
						if(subject[i].choice == "choice" && radio==undefined){
							Alert("必选项未选!");
//							alert("必选项未选!"); 
							return;
						}
//						console.log(radio);
						objectOn.type=1;
						radio = radio.split("-")[1];
						objectOn.option.push(Number(radio));
						objectOn.content.push(rc);
						objectOn.title = title;
						if(subject[i].input == "y"){
							// 输入框值
							var FOinput = $(".FOinput textarea[name='t"+subject[i].num+"']").val();
							// 判断是否选中其他
							var is = $("input:radio[name='r"+subject[i].num+"']:checked").attr("other");
//							console.log(FOinput);
							if(is == "true"){
								objectOn.text = FOinput;
							}else{
								objectOn.text = "";
							}
						}else{
							objectOn.text = "";
						}
					} else if(subject[i].type == "checkbox") {
						var checkArr = [];
						var ccArr = [];
						var is = [];
						// 多选
						var check = $("input:checkbox[name='c"+subject[i].num+"']:checked").map(function(index, elem) {
							var val = $(elem).val().split("-")[1];
							is.push($(elem).attr("other"));
							checkArr.push(Number(val));
						});
						var cc = $("input:checkbox[name='c"+subject[i].num+"']:checked").parent().find("label").map(function(index, elem) {
							ccArr.push($(elem).text());
						});
						//console.log(checkArr.length);
						if(subject[i].choice == "choice" && checkArr.length == 0){
							Alert("必选项未选!");
							//alert("必选项未选!");
							return;
						}
//						console.log("选中的checkbox的值为：" + check);
						objectOn.type=2;
						objectOn.option=checkArr;
						objectOn.content=ccArr;
						objectOn.title = title;
						if(subject[i].input == "y"){
							// 输入框值
							var FOinput = $(".FOinput textarea[name='t"+subject[i].num+"']").val();
							// 判断是否选中其他
							objectOn.text = "";
							for (var i = 0; i < is.length; i++) {
								if(is[i] == "true"){
									objectOn.text = FOinput;
								}
							}
						}else{
							objectOn.text = "";
						}
					} else if(subject[i].type == "proposal") {
						objectOn.type=3;
						objectOn.title = $("#proposal").text();
						// 输入框值
						var FOinput = $(".FOinput textarea[name='t"+subject[i].num+"']").val();
						if(subject[i].choice == "choice" && FOinput==undefined){
//							alert("必选项未选!");
							Alert("必选项未选!");
							return;
						}
//						console.log(FOinput);
						objectOn.text = FOinput;
					}
//					console.log(objectOn);
					data.object.push(objectOn);
				}
				data=JSON.stringify(data);;
				//console.log(data);
//				$.ajax({
//					    url: "http://192.168.1.93/index.php/ApiResearch/index/?appid=10002",
//					    type: "POST",
//					    contentType:"application/text; charset=utf-8",  //  ---->  问题就在这里了
//					    data: data,
//					    dataType:"json",
//					    success: function(data){
//					        //On ajax success do this
//					       	console.log(data);
//					        //Alert(data.content);
//					    },
//					    error: function(data){
//					    	//Alert(data);
//					    }
//				});
				$.ajax({
					    url: "http://poketj.u591776.com:8080/index.php/ApiResearch/index/?appid=10002",
					    type: "POST",
					    contentType:"application/text; charset=utf-8",  //  ---->  问题就在这里了
					    data: data,
					    dataType:"json",
					    success: function(data){
					        //On ajax success do this
					       //console.log(data);
//					        Alert(data.content);
					    },
					    error: function(data){
//					    	Alert(data);
					    }
				});
				$.ajax({
				    url: "/interface/guanwang/question.php",
				    type: "POST",
				    data: {"serverid":GetQueryString("serverid"),"userid":GetQueryString("userid"),"nper":periods},
				    dataType:"json",
				    success: function(data){
				    	window.location.href = "Tpage.html";
				    },
				    error: function(data){
				    	 Alert('失败');
				    }
			});
				
			});
			
		</script>
	</body>

</html>