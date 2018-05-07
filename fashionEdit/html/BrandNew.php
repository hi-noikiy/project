<link rel="stylesheet" type="text/css" href="../css/BrandNew.css" />
<div class="admin_content_BrandNew admin_content_title">
	<div class="admin_content_h1">
		<h1 id="BrandNew_titleH">新增品牌</h1>
		<div class="BrandNew_queryBox">
			<div class="BrandNew_condition">
				<div class="BrandNewF">
					<p>名称：</p>
				</div>
				<div class="BrandNewF">
					<input id="BrandNew_name" type="text" />
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="BrandNew_condition">
				<div class="BrandNewF">
					<p>简称(大写首字母)：</p>
				</div>
				<div class="BrandNewF">
					<input id="BrandNew_Abb" type="text" />
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="BrandNew_condition">
				<div class="BrandNewF">
					<p>logo：</p>
				</div>
				<div class="BrandNewF">
					<input id="BrandNew_logo" type="file" />
					<span></span>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="BrandNew_condition">
				<div class="BrandNewF">
					<p>宣传图：</p>
				</div>
				<div class="BrandNewF">
					<input id="BrandNew_Pro" type="file" />
					<span></span>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="BrandNew_condition">
				<div class="BrandNewF">
					<p>官网链接：</p>
				</div>
				<div class="BrandNewF">
					<input id="BrandNew_ON" type="text" />
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="BrandNew_condition">
				<div class="BrandNewF">
					<p>商城链接：</p>
				</div>
				<div class="BrandNewF">
					<input id="BrandNew_Shop" type="text" />
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="BrandNew_condition">
				<div class="BrandNewF" style="height: 90px;">
					<p style="line-height: 90px;">描述：</p>
				</div>
				<div class="BrandNewF" style="height: 90px;">
					<textarea id="BrandNew_text" rows="5" cols="60" style="margin-left: 5px;margin-top: 2px;"></textarea>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="BrandNew_condition">
				<div class="BrandNewF"></div>
				<div class="BrandNewF">
					<button>增加</button>
					<button>取消</button>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function fileImg(fileI, fun) {
			fileI.change(function() {
				handleFiles(this.files);
			});

			function handleFiles(files) {
				if(files.length) {
					var file = files[0];
					var reader = new FileReader();
					//				if(/text\/\w+/.test(file.type)) {
					//					reader.onload = function() {
					//						$('<pre>' + this.result + '</pre>').appendTo('body');
					//					}
					//					reader.readAsText(file);
					//				} else 
					if(/image\/\w+/.test(file.type)) {
						reader.onload = function() {
							if(typeof fun === "function" && fun != undefined) { //是函数    其中 FunName 为函数名称
								fun(this.result);
							}
							//						console.log(this.result)
							//              $('<img src="' + this.result + '"/>').appendTo('body');
						}
						reader.readAsDataURL(file);
					} else {
						alert("不是图片或图片太大！");
					}
				}
			}
		}
	</script>
</div>