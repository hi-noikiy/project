var g_Interval = 50;
var g_PersonCount = 500;//参加抽奖人数
var g_Timer;
var stoptime;
var employdata;
var prizedata;
var runstatus  = false;
var media = document.getElementById("media");
function beginRndNum(trigger){
	if(employdata['code'] == 1){
		alert(employdata['data']);return false;
	}
	if(runstatus){
		runstatus = false;
		media.pause();
		updateRndNum(1);
		clearInterval(g_Timer);
		$('#Button').css('background-image',"url('image/button.png')");
		$('#ResultNum').css('color','white');
		getlist();
	}else{
		runstatus = true;
		$('#ResultName').html('');
		$('#ResultNum').html('');
		media.play();
		$('#Button').css('background-image',"url('image/stop.png')");
		$('#ResultNum').css('color','black');
		$(trigger).val("停止");
		beginTimer();
	}
}

function updateRndNum(status){
	var num = employdata['data'][Math.floor(Math.random()*employdata['data'].length)]['ename'];
	if(status == 1){
		num = prizedata['data'][0]['dname']+'  '+prizedata['data'][0]['ename'];
		var shtml = prizedata['data'][0]['levelname']+'(第'+prizedata['data'][0]['curid']+'位)';
		//alert(prizedata['data'][0]['prizename']);
		if(prizedata['data'][0]['prizename'] != undefined)shtml+=prizedata['data'][0]['prizename'];
		$('#ResultName').html(shtml);
	}
	$('#ResultNum').html(num);
}

function beginTimer(){
	$('#ResultName').html();
	$.get('/core/index.php',{act:'start'},function(json){
		prizedata = JSON.parse(json);
	});
	stoptime = 10;
	g_Timer = setInterval(beat, g_Interval);
	
}

function beat() {
	stoptime-=g_Interval/1000;
	if(stoptime >0 ){
		//$('#btn').val(Math.round(stoptime));
		updateRndNum(0);
	}else{
		runstatus = false;
		media.pause();
		updateRndNum(1);
		clearInterval(g_Timer);
		$('#Button').css('background-image',"url('image/button.png')");
		$('#ResultNum').css('color','white');
		getlist();
	}
}
function getlist(){
	$.get('/core/index.php',{act:'getemploy'},function(json){
		employdata = JSON.parse(json);
	});
}