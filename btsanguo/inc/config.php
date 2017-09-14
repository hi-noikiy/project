<?php
$mdString='fu;djf,jk7g.fk*o3l';
//设置北京时间
date_default_timezone_set('Asia/Shanghai');
function SetConn($ServerInfo){
	switch ($ServerInfo) {
	case 212://运营
		#ConnServer("123.59.74.49","u591_pay","u591_pay","u591");
		ConnServer("127.0.0.1","root","root","u591");
		break;
        case 49://运营
		#ConnServer("123.59.74.49","u591_pay","u591_pay","u591");
		#ConnServer("10.10.132.122","u591_pay_inside","u591_pay","u591");
		break;
	case 86://运营
		ConnServer("127.0.0.1:3316","root","t,i7.8fg6sh,5i","kdgame");
		break;
	case 87://WAP网站
		ConnServer("127.0.0.1","root","hainiu591","union");
		break;
	case 88://WAP网站
	        ConnServer("127.0.0.1","root","root","u591");
		break;
	  case 89://
		ConnServer("127.0.0.1","root","root","discuz");
		break;
	  case 81://帐号库
  	   // ConnServer("14.17.105.216:3316","gameaccuser","rif,g8td,650.6uj90","account");
		ConnServer("kdbtacc.u591776.com:3316","gameaccuser","rif,g8td,650.6uj90","account");
		break;
	case 301:	
		ConnServer("123.59.74.36:3316","gameuser","rif,g8td,650.6uj90","ylgame1");
		break;
	case 501:	
	case 502:
	case 503:	
	case 504:	
	case 505:	
	case 506:	
	case 507:
	case 508:
		ConnServer("123.59.74.35:3316","gameuser","rif,g8td,650.6uj90","cygame1");
		break;



	case 601:	
		ConnServer("123.59.74.35:3316","gameuser","rif,g8td,650.6uj90","iosgame1");
		break;
	case 701:	
		 ConnServer("123.59.74.26:3316","gameuser","rif,g8td,650.6uj90","iosgame1");
		break;
	  case 901:	
	  case 902:	
	  case 903:	
  	  case 904:	
	  case 905:	
 	  case 906:	
          case 907:	
	case 908:	
	case 909:	
	case 910:	
	case 911:	
	case 912:
	case 913:
	case 914:	
	case 915:
	case 916:
	case 917:
	case 918:
	case 919:
	case 920:
	case 921:
		ConnServer("123.59.74.34:3316","gameuser","rif,g8td,650.6uj90","kdgame1");
		break;

   		
	
	case 922:	
	case 923:	
	case 924:	
	case 925:
	case 926:
	case 927:
	case 928:
	case 929:	
	case 930:
	case 931:
	case 932:	
	case 933:	
	case 934:	
	case 935:
	case 936:	
	case 937:	
	case 938:	
	case 939:
	case 940:	
	case 941:
	case 942:
	case 943:
	case 944:	
	case 945:	
	case 946:	
	case 947:
	case 948:	
	case 949:	
	case 950:	
	case 951:		

		ConnServer("123.59.58.240:3316","gameuser","rif,g8td,650.6uj90","kdgame22");
		break;

	

	case 952:	
	case 953:
	case 954:
	case 955:
	case 956:	
	case 957:
	case 958:
	case 959:
	case 960:	
	case 961:
	case 962:
	case 963:
	case 964:	
	case 965:
	case 966:
	case 967:
	case 968:	
	case 969:	
	case 970:
	case 971:	
	case 972:	
	case 973:
	case 974:
	case 975:
	case 976:	
	case 977:
	case 978:
	case 979:
	case 980:
	case 981:	
	case 982:	
	case 983:
		ConnServer("123.59.72.106:3316","gameuser","rif,g8td,650.6uj90","kdgame52");
		break;



	
	case 984:	
	case 985:
	case 986:
	case 987:
	case 988:
	case 989:
	case 990:
	case 991:	
	case 992:
	case 993:	
	case 994:	
	case 995:		
	case 996:
	case 997:	
	case 998:	
	case 999:	
	case 9100:	
	case 9101:	
	case 9102:	
	case 9103:
	case 9104:	
	case 9105:	
	case 9106:	
	case 9107:	
	case 9108:	
	case 9109:	
	case 9110:	
	case 9111:	
	case 9112:	
	case 9113:
	case 9114:	
	case 9115:
		ConnServer("123.59.74.34:3316","gameuser","rif,g8td,650.6uj90","kdgame84");
		break;
		




	case 9116:	
	case 9117:	
	case 9118:
	case 9119:	
	case 9120:	
	case 9121:	
	case 9122:	
	case 9123:
	case 9124:	
	case 9125:	
	case 9126:	
	case 9127:	
	case 9128:	
	case 9129:
	case 9130:	
	case 9131:
	case 9132:
	case 9133:	
	case 9134:	
	case 9135:	
	case 9136:	
	case 9137:	
	case 9138:	
	case 9139:	
	case 9140:
	case 9141:	
	case 9142:	
	case 9143:	
	case 9144:	
	case 9145:
	case 9146:	
	case 9147:
	case 9148:
	case 9149:	
	case 9150:	
	case 9151:
	case 9152:	
	case 9153:
		ConnServer("123.59.74.19:3316","gameuser","rif,g8td,650.6uj90","kdgame116");
		break;



	case 9154:	
	case 9155:
	case 9156:
	case 9157:	
	case 9158:	
	case 9159:
	case 9160:	
	case 9161:	
	case 9162:
	case 9163:	
	case 9164:	
	case 9165:
	case 9166:	
	case 9167:
	case 9168:	
	case 9169:	
	case 9170:	
	case 9171:	
	case 9172:	
	case 9173:	
	case 9174:	
	case 9175:	
	case 9176:
	case 9177:	
	case 9178:	
	case 9179:	
	case 9180:	
	case 9181:	
	case 9182:	
	case 9183:	
	case 9184:	
	case 9185:	
	case 9186:
		ConnServer("123.59.74.26:3316","gameuser","rif,g8td,650.6uj90","kdgame154");
		break;





	case 9187:
	case 9188:	
	case 9189:		
	case 9190:	
	case 9191:	
	case 9192:
	case 9193:	
	case 9194:
	case 9195:
	case 9196:	
	case 9197:
	case 9198:
	case 9199:	
	case 9200:	
	case 9201:	
	case 9202:
	case 9203:	
	case 9204:	
	case 9205:	
	case 9206:	
	case 9207:	
	case 9208:	
	case 9209:	
	case 9210:
	case 9211:
	case 9212:
		ConnServer("123.59.74.35:3316","gameuser","rif,g8td,650.6uj90","kdgame187");
		break;

	
	case 9213:
	case 9214:	
	case 9215:	
	case 9216:
	case 9217:	
	case 9218:	
	case 9219:	
	case 9220:	
	case 9221:	
	case 9222:	
	case 9223:	
	case 9224:
		ConnServer("123.59.74.37:3316","gameuser","rif,g8td,650.6uj90","kdgame213");
		break;

	case 9225:	
	case 9226:	
	case 9227:	
	case 9228:
	case 9229:	
	case 9230:	
	case 9231:	
	case 9232:	
	case 9233:	
	case 9234:	
	case 9235:	
	case 9236:
		ConnServer("123.59.58.240:3316","gameuser","rif,g8td,650.6uj90","kdgame225");
		break;
	

	case 9237:	
	case 9238:	
	case 9239:	
	case 9240:
	case 9241:	
	case 9242:	
	case 9243:
	case 9244:
	case 9245:	
	case 9246:	
	case 9247:	
	case 9248:	
		ConnServer("123.59.74.29:3316","gameuser","rif,g8td,650.6uj90","kdgame237");
		break;	



	case 9249:
	case 9250:	
	case 9251:
	case 9252:
	case 9253:	
	case 9254:	
	case 9255:
	case 9256:
	case 9257:	
	case 9258:
	case 9259:	
	case 9260:	
		ConnServer("123.59.74.26:3316","gameuser","rif,g8td,650.6uj90","kdgame249");
		break;


	case 9261:	
	case 9262:
	case 9263:
	case 9264:
	case 9265:	
	case 9266:	
	case 9267:	
	case 9268:
	case 9269:	
	case 9270:
	case 9271:	
	case 9272:
	case 9273:	
	case 9274:
	case 9275:
	case 9276:
		ConnServer("123.59.58.240:3316","gameuser","rif,g8td,650.6uj90","kdgame261");
		break;



	case 9277:	
	case 9278:
	case 9279:	
	case 9280:
	case 9281:	
	case 9282:
		ConnServer("123.59.74.37:3316","gameuser","rif,g8td,650.6uj90","kdgame277");
		break;

	case 9283:
	case 9284:
	case 9285:	
	case 9286:	
	case 9287:	
	case 9288:
	case 9289:
	case 9290:	
	case 9291:
		ConnServer("123.59.72.106:3316","gameuser","rif,g8td,650.6uj90","kdgame283");
		break;


	case 9292:
	case 9293:
	case 9294:
	case 9295:
	case 9296:
	case 9297:
	case 9298:	
	case 9299:
	case 9300:	

		ConnServer("123.59.74.37:3316","gameuser","rif,g8td,650.6uj90","kdgame292");
		break;

	case 9301:	
	case 9302:
	case 9303:
	case 9304:	
	case 9305:	
	case 9306:
	case 9307:	
	case 9308:
	case 9309:	
		ConnServer("123.59.74.29:3316","gameuser","rif,g8td,650.6uj90","kdgame301");
		break;

	case 9310:
	case 9311:
	case 9312:
	case 9313:	
	case 9314:	
	case 9315:
	case 9316:	
	case 9317:
	case 9318:
		ConnServer("123.59.74.35:3316","gameuser","rif,g8td,650.6uj90","kdgame310");
		break;	



	case 9319:
	case 9320:	
	case 9321:
		ConnServer("123.59.74.37:3316","gameuser","rif,g8td,650.6uj90","kdgame319");
		break;
	case 9322:	
	case 9323:
	case 9324:
		ConnServer("123.59.74.19:3316","gameuser","rif,g8td,650.6uj90","kdgame322");
		break;
	case 9325:	
	case 9326:	
	case 9327:
		ConnServer("123.59.74.19:3316","gameuser","rif,g8td,650.6uj90","kdgame325");
		break;
	case 9328:
	case 9329:	
	case 9330:	
		ConnServer("123.59.74.34:3316","gameuser","rif,g8td,650.6uj90","kdgame328");
		break;
	case 9331:
	case 9332:
	case 9333:	
		ConnServer("123.59.72.106:3316","gameuser","rif,g8td,650.6uj90","kdgame331");
		break;

	case 9334:	
	case 9335:
	case 9336:
		ConnServer("123.59.74.29:3316","gameuser","rif,g8td,650.6uj90","kdgame334");
		break;
	case 9337:
	case 9338:
	case 9339:	
		ConnServer("123.59.72.106:3316","gameuser","rif,g8td,650.6uj90","kdgame337");
		break;

	case 9340:
	case 9341:
	case 9342:
		ConnServer("123.59.58.240:3316","gameuser","rif,g8td,650.6uj90","kdgame340");
		break;

	case 9343:
	case 9344:
	case 9345:
		ConnServer("123.59.74.26:3316","gameuser","rif,g8td,650.6uj90","kdgame343");
		break;

	case 9346:
	case 9347:
	case 9348:
		ConnServer("123.59.74.35:3316","gameuser","rif,g8td,650.6uj90","kdgame346");
		break;	

	case 9349:
	case 9350:
	case 9351:	
		ConnServer("123.59.74.26:3316","gameuser","rif,g8td,650.6uj90","kdgame349");
		break;


	case 9352:	
	case 9353:
	case 9354:	
		ConnServer("123.59.74.19:3316","gameuser","rif,g8td,650.6uj90","kdgame352");
		break;
	case 9355:
	case 9356:	
	case 9357:
		ConnServer("123.59.58.240:3316","gameuser","rif,g8td,650.6uj90","kdgame355");
		break;
	case 9358:
	case 9359:	
	case 9360:
		ConnServer("123.59.58.240:3316","gameuser","rif,g8td,650.6uj90","kdgame358");
		break;
	case 9361:	
	case 9362:	
	case 9363:
		ConnServer("123.59.74.29:3316","gameuser","rif,g8td,650.6uj90","kdgame361");
		break;
	case 9364:
	case 9365:
	case 9366:
		ConnServer("123.59.74.34:3316","gameuser","rif,g8td,650.6uj90","kdgame364");
		break;

	case 9367:
		ConnServer("123.59.74.34:3316","gameuser","rif,g8td,650.6uj90","kdgame367");
		break;	
	case 9368:
		ConnServer("123.59.72.106:3316","gameuser","rif,g8td,650.6uj90","kdgame368");
		break;
	case 9369:
		ConnServer("123.59.74.34:3316","gameuser","rif,g8td,650.6uj90","kdgame369");
		break;	
	case 9370:	
		ConnServer("123.59.74.19:3316","gameuser","rif,g8td,650.6uj90","kdgame370");
		break;
	case 9371:
		ConnServer("123.59.58.240:3316","gameuser","rif,g8td,650.6uj90","kdgame371");
		break;
	case 9372:
		ConnServer("123.59.72.106:3316","gameuser","rif,g8td,650.6uj90","kdgame372");
		break;
	case 9373:	
		ConnServer("123.59.74.29:3316","gameuser","rif,g8td,650.6uj90","kdgame373");
		break;
	case 9374:
		ConnServer("123.59.74.35:3316","gameuser","rif,g8td,650.6uj90","kdgame374");
		break;
	case 9375:
		ConnServer("123.59.72.106:3316","gameuser","rif,g8td,650.6uj90","kdgame375");
		break;	
	case 9376:	
		ConnServer("123.59.74.26:3316","gameuser","rif,g8td,650.6uj90","kdgame376");
		break;
	case 9377:	
		ConnServer("123.59.74.26:3316","gameuser","rif,g8td,650.6uj90","kdgame377");
		break;
	case 9378:	
		ConnServer("123.59.74.29:3316","gameuser","rif,g8td,650.6uj90","kdgame378");
		break;
	case 9379:
		ConnServer("123.59.74.35:3316","gameuser","rif,g8td,650.6uj90","kdgame379");
		break;
	case 9380:
		ConnServer("123.59.74.37:3316","gameuser","rif,g8td,650.6uj90","kdgame380");
		break;
	case 9381:	
		ConnServer("123.59.74.26:3316","gameuser","rif,g8td,650.6uj90","kdgame381");
		break;
	case 9382:	
		ConnServer("123.59.74.19:3316","gameuser","rif,g8td,650.6uj90","kdgame382");
		break;
	case 9383:
		ConnServer("123.59.72.106:3316","gameuser","rif,g8td,650.6uj90","kdgame383");
		break;	
	case 9384:	
		ConnServer("123.59.74.29:3316","gameuser","rif,g8td,650.6uj90","kdgame384");
		break;
	case 9385:
		ConnServer("123.59.74.35:3316","gameuser","rif,g8td,650.6uj90","kdgame385");
		break;
	case 9386:	
		ConnServer("123.59.74.19:3316","gameuser","rif,g8td,650.6uj90","kdgame386");
		break;
	case 9387:	
		ConnServer("123.59.74.26:3316","gameuser","rif,g8td,650.6uj90","kdgame387");
		break;
	case 9388:	
		ConnServer("123.59.74.19:3316","gameuser","rif,g8td,650.6uj90","kdgame388");
		break;
	case 9389:
		ConnServer("123.59.74.37:3316","gameuser","rif,g8td,650.6uj90","kdgame389");
		break;
	case 9390:
		ConnServer("123.59.58.240:3316","gameuser","rif,g8td,650.6uj90","kdgame390");
		break;
	case 9391:	
		ConnServer("123.59.74.29:3316","gameuser","rif,g8td,650.6uj90","kdgame391");
		break;
	case 9392:
		ConnServer("123.59.74.37:3316","gameuser","rif,g8td,650.6uj90","kdgame392");
		break;
	case 9393:
		ConnServer("123.59.72.106:3316","gameuser","rif,g8td,650.6uj90","kdgame393");
		break;	

	case 9981:
		ConnServer("123.59.144.183:3316","gameuser","rif,g8td,650.6uj90","btkdgame1");
		break;	

	}
}
function ConnServer($db_host,$db_user,$db_pass,$db_database)
{
	//连接数据库失败不提醒，用于充值接口记录失败记录
	$db=@mysql_connect($db_host,$db_user,$db_pass);
    if(!$db){
       $db=@mysql_connect($db_host,$db_user,$db_pass);
    }
   
    if(!$db){
       write_log(ROOT_PATH."log","mysql_connect_log_","mysql连接异常,".mysql_error().",   $db_host,$db_user,$db_pass,$db_database, ".date("Y-m-d H:i:s")."\r\n");
    }
	mysql_select_db($db_database,$db);
}

define('ROOT_PATH', str_replace('inc/config.php', '', str_replace('\\', '/', __FILE__)));
?>
