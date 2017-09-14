<!DOCTYPE html>
<html>
<head lang="en">
    <title>口袋妖怪VS实名认证</title>
    <meta charset="utf8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <style type="text/css">
    html, body, * {font-family: "Microsoft YaHei" ! important; }
    .header{text-align: center;font-weight: bold;}
    hr{height:1px;border:none;border-top:1px dashed #0066CC;}
    .format-st{font-size: 12px}
    .info－box{margin-top: 2px;}
    .btn-yes {
	background: #0066bC repeat-x;
	display: inline-block;
	padding: 5px 10px 6px;
	color: #fff;
	text-decoration: none;
	-moz-border-radius: 6px;
	-webkit-border-radius: 6px;
	position: relative;
	cursor: pointer
}
.btn-no{
background: #bbbbbb repeat-x;
}
.info－box span{width: 100px;display: inline-block;text-align: right;}
    </style>
    <script type="text/javascript" src="../assets/js/jquery-1.9.1.min.js"></script>
</head>
<body>
<div class="w_wrap">
    <div class="header">
        <div class="main">
            <span>游戏实名认证</span>
        </div>
    </div>

    <div class="main">
        <div class="statement">
            <p>您的游戏帐号需要进行实名认证。按照文化部《网络游戏管理暂行办法》的有关要求及您的个人权益保障，网络游戏用户需要使用有效身份证件进行实名认证，确保安全登录游戏。</p>
        </div>
<hr/>
        <p class="format-st">认证信息只能提交一次，不可修改，请慎重填写。该信息仅用于实名认证不会泄露于任何第三方</p>

        <div class="info－box">
            <span>姓名</span>
            <input type="text"  value="" id="txtName" maxlength="10"><span style="color: red;text-align: left;">*</span>
        </div>
        <!-- <div class="info－box">
            <span>证件类型</span>
            <select id="chaCardType">
                <option value="0">身份证</option>
                <option value="1">港澳台居民往来内地通行证</option>
                <option value="2">港澳台身份证</option>
                <option value="3">护照</option>
                <option value="4">军人/警察身份证</option>
            </select>
        </div> -->
        <div class="info－box">
            <span>身份证</span>
            <input id="txtCardNum" class="warm" type="text" placeholder="" maxlength="18"><span style="color: red;text-align: left;">*</span>
        </div>
        <div class="info－box">
            <span>手机号</span>
            <input id="phone" class="warm" type="tel" placeholder="" maxlength="11"><span style="color: red;text-align: left;">*</span>
        </div>
        <div class="info－box">
            <span>QQ</span>
            <input id="qq" class="warm" type="tel" placeholder="" maxlength="18">
        </div>
		<button class="btn-yes" id="sign" style="left: 74%;">完成认证</button>
        <div class="clear"></div>
    </div>
</div>
<script>
String.prototype.trim=function(){
    return this.replace(/(^\s*)|(\s*$)/g, "");
 }
function checkPhone(phone){ 
    if(!(/^1[34578]\d{9}$/.test(phone))){ 
    	alert("手机号码有误，请重填");  
        return false; 
    } 
    return true;
}
function checkCardId(socialNo){  
	  
    if(socialNo == "")  
    {  
      alert("输入身份证号码不能为空!");  
      return (false);  
    }  

    if (socialNo.length != 15 && socialNo.length != 18)  
    {  
      alert("输入身份证号码格式不正确!");  
      return (false);  
    }  
        
   var area={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"};   
       
     if(area[parseInt(socialNo.substr(0,2))]==null) {  
      alert("身份证号码不正确(地区非法)!");  
          return (false);  
     }   
            
    if (socialNo.length == 15)  
    {  
       pattern= /^\d{15}$/;  
       if (pattern.exec(socialNo)==null){  
          alert("15位身份证号码必须为数字！");  
          return (false);  
      }  
      var birth = parseInt("19" + socialNo.substr(6,2));  
      var month = socialNo.substr(8,2);  
      var day = parseInt(socialNo.substr(10,2));  
      switch(month) {  
          case '01':  
          case '03':  
          case '05':  
          case '07':  
          case '08':  
          case '10':  
          case '12':  
              if(day>31) {  
                  alert('输入身份证号码不格式正确!');  
                  return false;  
              }  
              break;  
          case '04':  
          case '06':  
          case '09':  
          case '11':  
              if(day>30) {  
                  alert('输入身份证号码不格式正确!');  
                  return false;  
              }  
              break;  
          case '02':  
              if((birth % 4 == 0 && birth % 100 != 0) || birth % 400 == 0) {  
                  if(day>29) {  
                      alert('输入身份证号码不格式正确!');  
                      return false;  
                  }  
              } else {  
                  if(day>28) {  
                      alert('输入身份证号码不格式正确!');  
                      return false;  
                  }  
              }  
              break;  
          default:  
              alert('输入身份证号码不格式正确!');  
              return false;  
      }  
      var nowYear = new Date().getYear();  
      if(nowYear - parseInt(birth)<15 || nowYear - parseInt(birth)>100) {  
          alert('输入身份证号码不格式正确!');  
          return false;  
      }  
      return (true);  
    }  
      
    var Wi = new Array(  
              7,9,10,5,8,4,2,1,6,  
              3,7,9,10,5,8,4,2,1  
              );  
    var   lSum        = 0;  
    var   nNum        = 0;  
    var   nCheckSum   = 0;  
      
      for (i = 0; i < 17; ++i)  
      {  
            

          if ( socialNo.charAt(i) < '0' || socialNo.charAt(i) > '9' )  
          {  
              alert("输入身份证号码格式不正确!");  
              return (false);  
          }  
          else  
          {  
              nNum = socialNo.charAt(i) - '0';  
          }  
           lSum += nNum * Wi[i];  
      }  

      
      if( socialNo.charAt(17) == 'X' || socialNo.charAt(17) == 'x')  
      {  
          lSum += 10*Wi[17];  
      }  
      else if ( socialNo.charAt(17) < '0' || socialNo.charAt(17) > '9' )  
      {  
          alert("输入身份证号码格式不正确!");  
          return (false);  
      }  
      else  
      {  
          lSum += ( socialNo.charAt(17) - '0' ) * Wi[17];  
      }  

        
        
      if ( (lSum % 11) == 1 )  
      {  
          return true;  
      }  
      else  
      {  
          alert("输入身份证号码格式不正确!");  
          return (false);  
      }  
        
}  
var name,phone,qq,cardnum,data;
var str=location.href; //取得整个地址栏
var num=str.indexOf("?") ;
if(num != -1){
	str=str.substr(num+1); //取得所有参数   stringvar.substr(start [, length ]
	if(str != ''){
		$('#sign').click(function(){
			var reg = new RegExp("[\\u4E00-\\u9FFF]+","g");
			name = $('#txtName').val();
			phone = $('#phone').val();
			qq = $('#qq').val();
			cardnum = $('#txtCardNum').val();
			if(name.length <2){     
			     alert("名字不小于2个字");  
			     $('#txtName').val(''); 
			     $('#txtName').focus();   
			     return false;
			}
			if(!reg.test(name)){ 
			     alert("请输入中文！");  
			     $('#txtName').val(''); 
			     $('#txtName').focus(); 
			     return false;
			}
			if(!(cardnum.length==15 || cardnum.length==18)){
				alert("身份证填写错误！");  
			     $('#txtCardNum').val(''); 
			     $('#txtCardNum').focus(); 
			     return false;
			}
			if(!checkPhone(phone)){
				$('#phone').val(''); 
			     $('#phone').focus(); 
			     return false;
			}
			/*
			if(!checkCardId(cardnum)){
			$('#txtCardNum').val(''); 
		     $('#txtCardNum').focus(); 
		     return false;
			}
			if(phone.length !=11){
				alert("手机号填写错误！");  
			     $('#phone').val(''); 
			     $('#phone').focus(); 
			     return false;
			}
			if(!checkPhone(phone)){
				
				$('#phone').val(''); 
			    $('#phone').focus(); 
			}*/
			if(name.trim() != ''  && (cardnum.length==15 || cardnum.length==18) && phone.length ==11){
				data = {name:name,phone:phone,qq:qq,cardnum:cardnum,accountId:str};
				$.post('/hejin/realname/verify.php',data,function(json){
					if(json == 1){
						$('#sign').attr('disabled',true);
						$('.btn-yes').addClass('btn-no');
						alert('认证成功');
					}else if(json == 3){
						alert('数据库操作失败');
					}
				});
			}else{
				alert('填写信息有误');
			}
		});
	}
	
	
	
}



</script>
</body>
</html>