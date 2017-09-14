//提交表单
function subForm(fid){
	$('#'+fid).submit();
}
//把表单变成可以提交用的params,不支持文件
function formToParam(form){
	var params={};
	$('#'+form+' input').each(function (){
		if($(this).attr('name')){
			params[$(this).attr('name')]=$(this).val();
		}
	});
	$('#'+form+' select').each(function (){
		if($(this).attr('name')){
			params[$(this).attr('name')]=$(this).val();
		}
	});
	$('#'+form+' textarea').each(function (){
		if($(this).attr('name')){
			params[$(this).attr('name')]=$(this).val();
		}
	});
	$('#'+form+' button').each(function (){
		if($(this).attr('name')){
			params[$(this).attr('name')]=$(this).val();
		}
	});
	return params;
}
///////////////////////////////////后台部分////////////////////
/*
 * 根据值获得select的Text
 */
function selectText(obj){
	var val=obj.val();
	var str='';
	obj.find('option').each(function (){
		if($(this).attr('value')==val) str=$(this).html();
	})
	return str;
}
/*
 * 设定修改表单的数据
 */
function editFunEmpty(obj){
	var fieldName='';
	$(':input').each(function (){
		fieldName=$(this).attr('name');
		if(fieldName && obj[fieldName] && !$(this).val()){
			switch($(this).attr('type')){
				case 'checkbox'	:
								if(obj[fieldName]>0)
									$(this).attr('checked','checked');
								break;
				case 'file'		:
								break;
				default			:
								$(this).val(obj[fieldName]);
			}
		}
	})
}
function editFun(obj){
	var fieldName='';
	$(':input').each(function (){
		fieldName=$(this).attr('name');
		if(fieldName && obj[fieldName]){
			switch($(this).attr('type')){
				case 'checkbox'	:
								if(obj[fieldName]>0)
									$(this).attr('checked','checked');
								break;
				case 'file'		:
								break;
				default			:
								$(this).val(obj[fieldName]);
			}
		}
	})
}
/*
 * 跳转到某个页面
 */
function go(url){
	window.location.href=url;
}
