var g_Interval = 50;
var g_PersonCount = 500;//参加抽奖人数
var g_Timer;
var stoptime;
var employdata;
var prizedata;
var html;
var runstatus  = false;
var media = document.getElementById("media");
function beginRndNum(trigger){
	if(!runstatus){
		getlist();
	}
	 if (!employdata) {
		 alert('等待数据加载');
		 return false;
	 }
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
	}else{
		runstatus = true;
		$('#ResultName').html('');
		$('#ResultNum').html('');
		media.play();
		$('#Button').css('background-image',"url('image/stop.png')");
		$('#hidediv').css('display',"block");
		$('#ResultNum').css('color','black');
		$(trigger).val("停止");
		beginTimer();
	}
}

function updateRndNum(status){
	var num = employdata['data'][Math.floor(Math.random()*employdata['data'].length)]['ename'];
	if(status == 1){
		num = prizedata['data'][0]['dname']+'  '+prizedata['data'][0]['ename'];
		getinfo();
	}
	$('#ResultNum').html(num);
}

function beginTimer(){
	$('#ResultName').html();
	var type = $('#sel').val();
	$.get('/core/index.php',{act:'start',type:type},function(json){
		prizedata = JSON.parse(json);
	});
	stoptime = 10;
	g_Timer = setInterval(beat, g_Interval);
	
}

function beat() {
	stoptime-=g_Interval/1000;
	if(stoptime >0 ){
		if(stoptime<=8){
			$('#hidediv').css('display',"none");
		}
		updateRndNum(0);
	}else{
		runstatus = false;
		media.pause();
		updateRndNum(1);
		clearInterval(g_Timer);
		$('#Button').css('background-image',"url('image/button.png')");
		$('#ResultNum').css('color','white');
	}
}
function getlist(){
	var type = $('#sel').val();
	$.ajax({
		type : "get",
		url : "/core/index.php",
		data : "act=getemploy&type="+type,
		async : false,
		success : function(data){
			employdata = JSON.parse(data);
		}
	});
}
function getinfo(){
	$.get('/core/index.php',{act:'getinfo'},function(json){
		html = '';
		result = JSON.parse(json);
		if(result['code'] == 1){
			return false;
		}
		for(var i in result.data){
			if(result['data'].hasOwnProperty(i)){
				html += '<div>'+result['data'][i]+'</div>';
			}
		}
		$('#mydiv').html(html);
	});
}
getinfo();