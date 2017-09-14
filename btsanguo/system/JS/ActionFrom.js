function count_checked_items(){
	var number_checked=0;
	var box_count=document.form1.elements.length;
	if ( box_count==null ){
		if ( document.form1.elements.checked==true ){
		number_checked=1;
		}else{
		number_checked=0;
		}
	}else{
	for ( var i=0; i < (box_count); i++ ){
		if ( document.form1.elements[i].checked==true ){
		number_checked++;
		}
	}
}
return number_checked;
}

function select_ok(){
	if(document.all.check_all.checked){
	select_All(true)
	}else{
	select_All(false)
	} 
}
function select_change(){
	if(document.form1.button_select.value==" 全 选 "){
	document.form1.button_select.value=" 取 消 ";
	select_All(true);
	}else{
	document.form1.button_select.value=" 全 选 ";
	select_All(false);
	}
}
function select_All(checked){
	for (var i=0;i<document.form1.elements.length;i++){
	var e = document.form1.elements[i];
	if (e.name != 'allbox')
		 e.checked = checked;
	}
	document.all.check_all.checked=checked;
}
function OpenWindow(url, width, height) {
var Win = window.open(url,"openScript",'width=' + width + ',height=' + height + ',top=150,left=200,resizable=1,scrollbars=yes,menubar=no,status=yes' );
}