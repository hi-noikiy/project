function getXBrowserRef(eltname) 
{
 	return document.all[eltname].style;
}

function hideElement(eltname) { getXBrowserRef(eltname).visibility = 'hidden'; }

var elementParaX;
var elementParaY;

function getElementXY ( elt )
{
	// Find the element's offsetTop and offsetLeft relative to the BODY tag.
	alert(displayElement.offsetLeft);
	var objLeft   = elt.offsetLeft;
	var objTop    = elt.offsetTop;

	objParent = elt;

	while (objParent.tagName.toUpperCase() != "BODY")
	{
		objParent = objParent.offsetParent;
		objLeft  += objParent.offsetLeft;
		objTop   += objParent.offsetTop;
	}

	elementParaX = objLeft;
	elementParaY = objTop;
}

var nowEltName="";

var popFlag = false;

function dateMouseClickFunc()
{
	if ( nowEltName != "" && popFlag == false ) {
		elt = document.all[nowEltName];
		getElementXY ( elt );
		var x = elementParaX;
		var y = elementParaY;

		getElementXY ( event.srcElement );
		var eventx = elementParaX+event.offsetX;
		var eventy = elementParaY+event.offsetY;

		alert ( "ex="+eventx+", ey="+eventy );

		if ( eventx >= x && eventx <= (x+elt.offsetWidth)
		&& eventy >= y && eventy <= (y+elt.offsetHeight) );
		else {
			toggleVisible(nowEltName);
			popFlag=false;
		}
	}
	else
		popFlag = false;
}

function toggleVisible(eltname) 
{
 	elt = getXBrowserRef(eltname);
 	if (elt.visibility == 'visible' || elt.visibility == 'show') 
 	{
   		elt.visibility = 'hidden';
   		nowEltName = "";
 	} 
 	else 
 	{
   		elt.visibility = 'visible';
   		nowEltName = eltname;
		//===========获取控件的相对位置======================================================
			var eT = displayElement.offsetTop;  
			var eH = displayElement.offsetHeight+eT;  
			var dH = window.displayElement.style.pixelHeight;  
			var sT = document.body.scrollTop; 
			var sL = document.body.scrollLeft; 
			event.cancelBubble=true;
			document.all(nowEltName).style.posLeft = event.clientX-event.offsetX+sL-5;  
			document.all(nowEltName).style.posTop = event.clientY-event.offsetY+eH+sT-8;		
   		popFlag = true;
		//===========获取控件的相对位置======================================================
 	}
}

//——————————————————————————————————————
	document.write('<div id="ShowDateTimeYes" style="POSITION: absolute; z-index: 1;"></div>')
    // 初始月份及各月份天数数组
    var months = new Array("一　月", "二　月", "三　月", "四　月", "五　月", "六　月", "七　月", "八　月", "九　月", "十　月", "十一月", "十二月");
   	var daysInMonth = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	var displayMonth;
	var displayYear;
	var today;
	var displayDivName="ShowDateTimeYes";
	var displayElement;

    function getDays(month, year) 
    {
      	//测试选择的年份是否是润年？
        if (1 == month) { return ((0 == year % 4) && (0 != (year % 100))) || (0 == year % 400) ? 29 : 28; }
        else { return daysInMonth[month]; }
    }
    
    function getToday( element) 
    {
		if (element.value=='')
		{
			var time=new Date();
			this.year=time.getYear();
            this.month=time.getMonth();
			this.day=time.getDate();	
		} 
		else{
			var s,ss;
			s=element.value;
			s=s.replace("\/","-");
			s=s.replace("\/","-");
			ss=s.split("-");
			if(ss.length>=3){
				this.year=ss[0];
				this.month=ss[1];
				this.month=this.month-1;
				this.day=ss[2];
			}else{
				var d;                  // 声明变量。
				d=new Date();
				this.year=parseInt(d.getYear());
				this.month=parseInt(d.getMonth()) ;
				this.day=parseInt(d.getDay());
			}
		}
    }

    function newCalendar(eltName,attachedElement) 
    {
	if (attachedElement) 
	{
	       if (displayDivName && displayDivName != eltName) hideElement(displayDivName);
	       displayElement = attachedElement;
	}
	
	displayDivName = eltName;
      
        var parseYear = displayYear;
        var newCal = new Date(parseYear,displayMonth,1);
        var startDayOfWeek = newCal.getDay();
        var day=today.day;
        var intDaysInMonth = getDays(newCal.getMonth(), newCal.getFullYear());
        if ( day > intDaysInMonth ) day=intDaysInMonth;
        var daysGrid = makeDaysGrid(startDayOfWeek,day,intDaysInMonth,newCal,eltName);
	var elt = document.all[eltName];
	elt.innerHTML = daysGrid;
	}

	function incMonth(delta,eltName) 
	{
	   	displayMonth += delta;
	   	if (displayMonth >= 12) 
	   	{
	   		displayMonth = 0;
	     	incYear(1,eltName);
	   	} 
	   	else if (displayMonth <= -1) 
	   	{
	     	displayMonth = 11;
	     	incYear(-1,eltName);
	   	} 
	   	else 
	   	{
	     	newCalendar(eltName);
	   	}
	}

	function incYear(delta,eltName) 
	{
	   	displayYear = parseInt(displayYear + '') + delta;
	   	newCalendar(eltName);
	}

	function makeDaysGrid(startDay,day,intDaysInMonth,newCal,eltName) 
	{
	    var daysGrid;
	    var month = newCal.getMonth();
	    var year = newCal.getFullYear();
	    var isThisYear = (year == new Date().getFullYear());
	    var isThisMonth = (day > -1)
	    daysGrid = '<table width=100 BoderColorLight=#FFFFFF BoderColorDark=#f2f2f2 Border=1 BgColor=#fffff7 cellspacing=0 cellpadding=0 class=\'datedialog\' >';
	    daysGrid += '<tr><td nowrap>';
	    daysGrid += '<a href="javascript:toggleVisible(\'' + eltName + '\')"><Img Src="JS/DateTime/off.gif" border=0 align=absmiddle></a>';
	    daysGrid += '&nbsp;&nbsp;';
	    daysGrid += '<a href="javascript:incMonth(-1,\'' + eltName + '\')">&laquo; </a>';

	    daysGrid += '<b>';
	    if (isThisMonth) { daysGrid += '<b>' + months[month] + '</b>'; }
	    else { daysGrid += months[month]; }
	    daysGrid += '</b>';

	    daysGrid += '<a href="javascript:incMonth(1,\'' + eltName + '\')"> &raquo;</a>';
	    daysGrid += '&nbsp;&nbsp;&nbsp;';
	    daysGrid += '<a href="javascript:incYear(-1,\'' + eltName + '\')">&laquo; </a>';

	    daysGrid += '<b>';
	    if (isThisYear) { daysGrid += '<b>' + year + '</b>'; }
	    else { daysGrid += ''+year; }
	    daysGrid += '</b>';

	    daysGrid += '<a href="javascript:incYear(1,\'' + eltName + '\')"> &raquo;</a></td></tr>';
	    daysGrid += '<tr><td><table width="100%" cellspacing=0 cellpadding=0 class=\'datedialog\' ><tr><td>日</td><td>一</td><td>二</td><td>三</td><td>四</td><td>五</td><td>六</td></tr>';
	    var dayOfMonthOfFirstSunday = (7 - startDay + 1);

	    for (var intWeek = 0; intWeek < 6; intWeek++) 
	    {
	       daysGrid += "<tr>";
	       var dayOfMonth;
	       for (var intDay = 0; intDay < 7; intDay++) 
	       {
	         	dayOfMonth = (intWeek * 7) + intDay + dayOfMonthOfFirstSunday - 7;
	         	
				if (dayOfMonth > 0 && dayOfMonth <= intDaysInMonth) 
		 		{
		 			daysGrid += "<td height='15'>";
		   			var color = "black";
		   			if (day > 0 && day == dayOfMonth) color="red";
		   			daysGrid += "<a href=\"javascript:setDay(";
		   			daysGrid += dayOfMonth;
		   			daysGrid += ",\'" + eltName + "\')\"";
		   			daysGrid += " style=color:" + color + ">";
		   			var dayString = dayOfMonth + "</a> ";
		   			if (dayString.length == 6) dayString = "&nbsp;"+dayString;
		   			daysGrid += dayString;
		   			daysGrid += "</td>"
		 		}
		 		else
		 			daysGrid += "<td height='15'>&nbsp;&nbsp;</td>";
	       }
	       daysGrid +="</tr>";
	    }
	    
	    daysGrid +="</table></td></tr>";
	    daysGrid +="<tr style='text-align: Left' ><td style='text-align: Left'><a href=\"javascript:setDay("+ day + ",\'"+ eltName +"\')\"";
	    daysGrid +="><img border='0' src='JS/DateTime/ok.gif'>确定</a>&nbsp;&nbsp;";
	    daysGrid +="<a href=\"javascript:toggleVisible(\'"+ eltName +"\')\"";
	    daysGrid +="><img border='0' src='JS/DateTime/cancel.gif'>取消</a></td></tr></table>";
	    
	    return daysGrid;
	 }

	 function setDay(day,eltName) 
	 {
	   	var strMonth, strDay;
	   	if ((displayMonth+1) < 10) strMonth = "0" + (displayMonth + 1); else strMonth = (displayMonth +1) + "";
	   	if ( day < 10) strDay = "0" + day; else strDay = day + "";
	   	displayElement.value = displayYear + "-" + strMonth + "-" + strDay;

	   	toggleVisible(eltName);
	 }
	 
function toggleDatePicker(formElt) 
{
  	//var x = formElt.indexOf('.');
  	//var formName = formElt.substring(0,x);
  	//var formEltName = formElt.substring(x+1);
	var objForm=formElt
  	
        today = new getToday(objForm);
        displayYear = today.year;
        displayMonth = today.month;
        
  	newCalendar(displayDivName,objForm);
  	toggleVisible(displayDivName);
}
