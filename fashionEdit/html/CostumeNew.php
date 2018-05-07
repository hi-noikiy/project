<link rel="stylesheet" type="text/css" href="../css/CostumeNew.css" />
<div class="admin_content_CostumeNew admin_content_title">
	<div class="admin_content_h1">
		<h1 id="CostumeNew_titleH">新增服饰</h1>
		<div class="CostumeNew_queryBox">
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>命名：</p>
				</div>
				<div class="CostumeNewF">
					<input id="CostumeNew_name" type="text" />
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>icon：</p>
				</div>
				<div class="CostumeNewF">
					<input type="text" id="ndliconText" />
					<!--<select id="ndlicon" name="">
					</select>-->
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>服饰类型：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl1" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>发布季节：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl2" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>品牌：</p>
				</div>
				<div class="CostumeNewF">
					<input type="text" id="ndl3text" value="" />
					<select id="ndl3" name="">
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>价格：</p>
				</div>
				<div class="CostumeNewF">
					<input type="text" id="price" onkeyup="value=value.replace(/[^\d]/g,'')" value="" />
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>钻石：</p>
				</div>
				<div class="CostumeNewF">
					<input type="text" id="emoney" onkeyup="value=value.replace(/[^\d]/g,'')" value="" />
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>风格1：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl4" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>风格2：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl5" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>颜色1：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl6" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>颜色2：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl7" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>图案1：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl8" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>图案2：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl9" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>材质1：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl10" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>材质2：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl11" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>领型：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl12" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>版型/款式：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl13" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>流行元素1：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl14" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>流行元素2：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl15" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>流行元素3：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl16" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>流行元素4：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl17" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>流行元素5：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl18" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>流行元素6：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl19" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>流行元素7：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl20" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>流行元素8：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl21" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>流行元素9：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl22" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF">
					<p>流行元素10：</p>
				</div>
				<div class="CostumeNewF">
					<select id="ndl23" name="">
						<option value="">—请选择—</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="CostumeNew_condition">
				<div class="CostumeNewF"></div>
				<div class="CostumeNewF">
					<button id="CostumeNew_new">增加</button>
					<button id="CostumeNew_x">取消</button>
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
	<div class="CostumeNew_imgBox">
		<div class="CostumeNew_imgIcon">
			<!--<img src="../img/timg.gif"/>
			<p>名称：<span>5264</span></p>-->
		</div>
		<div class="CostumeNew_imgFragment">
			<!--<div class="CostumeNew_imgFragmentContent">
				<img src="../img/timg.gif"/>
				<p>名称：<span>146341641655</span></p>
				<p>区域尺寸宽：<span>5264</span>&nbsp;区域尺寸高：<span>5485</span></p>
			<p>整图坐标x：<span>5264</span>&nbsp;整图坐标y：<span>5485</span>&nbsp;区域坐标x：<span>544</span>&nbsp;区域坐标y：<span>544</span></p>
			</div>
			<div class="CostumeNew_imgFragmentContent">
				<img src="../img/timg.gif"/>
				<p>名称：<span>146341641655</span></p>
				<p>区域尺寸宽：<span>5264</span>&nbsp;区域尺寸高：<span>5485</span></p>
			<p>整图坐标x：<span>5264</span>&nbsp;整图坐标y：<span>5485</span>&nbsp;区域坐标x：<span>544</span>&nbsp;区域坐标y：<span>544</span></p>
			</div>
			<div class="CostumeNew_imgFragmentContent">
				<img src="../img/timg.gif"/>
				<p>名称：<span>146341641655</span></p>
				<p>区域尺寸宽：<span>5264</span>&nbsp;区域尺寸高：<span>5485</span></p>
			<p>整图坐标x：<span>5264</span>&nbsp;整图坐标y：<span>5485</span>&nbsp;区域坐标x：<span>544</span>&nbsp;区域坐标y：<span>544</span></p>
			</div>
			<div class="clearfix"></div>-->
		</div>

	</div>
</div>