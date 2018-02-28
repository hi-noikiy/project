var g_Interval = 50;
var windata =new Array();
var g_Timer;
var ran,lenth;
var stoptime;
var employdata,employdata2;
var prizedata;
var html;
var runstatus = false;
var media = document.getElementById("media");
function beginRndNum(trigger) {
	g_Interval = 50;
	if (!employdata) {
		alert('等待数据加载');
		return false;
	}
	if (employdata['code'] == 1) {
		alert(employdata['data']);
		return false;
	}
	/*employdata2 = employdata;
	lenth = employdata2['data'].length;
	ran = Math.floor(Math.random()*lenth);
	windata[0] = employdata2['data'][ran];
	employdata['data'].splice(ran,1);
	ran = Math.ceil(Math.random()*lenth-1);
	windata[1] = employdata2['data'][ran];
	employdata['data'].splice(ran,1);
	ran = Math.ceil(Math.random()*lenth-2);
	windata[2] = employdata2['data'][ran];
	employdata['data'].splice(ran,1);
	ran = Math.ceil(Math.random()*lenth-3);
	windata[3] = employdata2['data'][ran];
	employdata['data'].splice(ran,1);
	ran = Math.ceil(Math.random()*lenth-4);
	windata[4] = employdata2['data'][ran];*/
	if (runstatus) {
		runstatus = false;
		clearInterval(g_Timer);
		media.pause();
		setWinner();
		$('#Button').css('background-image', "url('image/button.png')");
		$('#ResultNum').css('color', 'white');
	} else {
		runstatus = true;
		$('#ResultName').html('');
		$('#ResultNum').html('');
		media.play();
		$('#Button').css('background-image', "url('image/stop.png')");
		$('#ResultNum').css('color', 'black');
		$(trigger).val("停止");
		g_Timer = setInterval(updateRndNum, g_Interval);
	}
}

function updateRndNum(status) {
	var mran = Math.floor(Math.random()* employdata['data'].length);
	var eid = employdata['data'][mran]['id'];
	var num = employdata['data'][mran]['ename'];
	$('#ResultNum').html(num);
	$('#ResultNum').attr('eid',eid);
	$('#ResultNum').attr('mran',mran);
}

function setWinner() {
	$('#ResultName').html();
	var type = $('#sel').val();
	var eid = $('#ResultNum').attr('eid');
	var mran = $('#ResultNum').attr('mran');
	$.get('/core/gambling.php', {
		act : 'setWinner',
		type : type,
		eid : eid
	}, function(json) {
		prizedata = JSON.parse(json);
		employdata['data'].splice(mran,1);
		getinfo();
	});
}
function beginTimer() {
//	stoptime = 10;
	g_Timer = setInterval(updateRndNum(), g_Interval);

}

function beat() {
	stoptime -= g_Interval / 1000;
	updateRndNum();
	if (stoptime <= 0) {
		runstatus = false;
		media.pause();
		setWinner();
		clearInterval(g_Timer);
		$('#Button').css('background-image', "url('image/button.png')");
		$('#ResultNum').css('color', 'white');
	}
}
function getinfo() {
	$.get('/core/gambling.php', {
		act : 'getinfo'
	}, function(json) {
		html = '';
		result = JSON.parse(json);
		if (result['code'] == 1) {
			return false;
		}
		for ( var i in result.data) {
			if (result['data'].hasOwnProperty(i)) {
				html += '<div>' + result['data'][i] + '</div>';
			}
		}
		$('#mydiv').html(html);
	});
}
function getlist() {
	var type = $('#sel').val();
	$.ajax({
		type : "get",
		url : "/core/gambling.php",
		data : "act=getemploy&type=" + type,
		async : false,
		success : function(data) {
			employdata = JSON.parse(data);
		}
	});
}
getlist();
getinfo();
setInterval(function(){getinfo();}, 5000);
