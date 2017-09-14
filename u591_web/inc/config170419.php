<?php
$mdString='fu;djf,jk7g.fk*o3l';
date_default_timezone_set('Asia/Shanghai');
function SetConn($ServerInfo) {
        switch ($ServerInfo) {
                //web database
                case 88:
                        return ConnServer("10.28.37.152", "payor", "u591*hainiu", "u591_hj");
                        break;
                //acount database
                case 81:
                        return ConnServer("106.75.27.74:3316", "accountmyuser", "rf,d5,8.fgig,fj.80rtyuyjj464j", "gunaccount");
                        break;
                case 85:
                        return ConnServer("10.27.224.29:3356", "gameuser", "rio8t89o,690.60fk", "account");
                        break;
                //game server list
                case 9250:
                        return ConnServer("123.59.74.29:3316", "gameuser", "rif,g8td,650.6uj90", "kdgame250");
                        break;
                case 701:
                        return ConnServer("106.75.25.32:3316", "gameuser", "rio8t89o,690.60fk", "gungame1");
                        break;
                case 1102:
                        return ConnServer('123.59.144.183:3316', 'gameuser', 'rio8t89o,690.60fk', 'DxqGame2');
                        break;
                //口袋
                case 1101: //test
                        return ConnServer("123.59.144.183:3356", "gameuser", "rio8t89o,690.60fk", "koudai");
                        break;
                case 3001:
                case 3002:
                case 3003:
                case 3004:
                case 3005:
                case 3006:
                case 3007:
                case 3008:
                case 3009:
                case 3010:
                case 3011:
                case 3012:
                case 3013:
                case 3014:
                case 3015:
                case 3016:
                case 3017:
                case 3018:
                case 3019:
                case 3020:
                case 3021:
                case 3022:
                case 3023:
                case 3024:
                case 3025:
                case 3026:
                case 3027:
                case 3028:
                case 3029:
                case 3030:
                case 3031:
                case 3032:
                case 3033:
                case 3034:
                case 3035:
                case 3036:
                case 3037:
                case 3038:
                case 3039:
                case 3040:

                        return ConnServer("115.159.69.43:3356", "gameuser", "rio8t89o,690.60fk", "pokegametx");
                        break;
                case 5001:
                case 5002:
                case 5003:
                case 5004:
                case 5005:
                case 5006:
                case 5007:
                case 5008:
                case 5009:
                case 5010:
                case 5011:
                case 5012:
                case 5013:
                case 5014:
                case 5015:
                case 5016:
                case 5017:
                case 5018:
                case 5019:
                case 5020:
                case 5021:
                case 5022:
                case 5023:
                case 5024:
                case 5025:
                case 5026:
                case 5027:
                case 5028:
                case 5029:
                        return ConnServer("118.178.125.167:3356", "gameuser", "rio8t89o,690.60fk", "pokegamep800");
                        break;


                case 6001:
                case 6002:
                case 6003:
                case 6004:
                case 6005:
                case 6006:
                case 6007:
                case 6008:
                case 6009:
                case 6010:
                case 6011:
                case 6012:
                case 6013:
                case 6014:
                case 6015:
                case 6016:
                case 6017:
                case 6018:
                case 6019:
                case 6020:
                case 6021:
                case 6022:
                case 6023:
                case 6024:
                case 6025:
                case 6026:
                case 6027:
                case 6028:
                case 6029:
                        return ConnServer("114.55.28.65:3356", "gameuser", "rio8t89o,690.60fk", "pokegamemha");
                        break;
                case 8001:
                case 8002:
                case 8003:
                case 8004:
                case 8005:
                case 8006:
                case 8007:
                case 8008:
                case 8009:
                case 8010:
                case 8011:
                case 8012:
                case 8013:
                case 8014:
                case 8015:
                case 8016:
                case 8017:
                case 8018:
                case 8019:

                        return ConnServer("10.28.38.125:3356", "gameuser", "rio8t89o,690.60fk", "pokegame");
                        break;
                case 8020:
                case 8021:
                case 8022:
                case 8023:
                case 8024:
                case 8025:
                case 8026:
                case 8027:
                case 8028:
                case 8029:
                case 8030:
                case 8031:
                case 8032:
                case 8033:
                case 8034:
                case 8035:
                case 8036:
                case 8037:
                case 8038:
                case 8039:
                case 8040:
                case 8041:
                case 8042:
                case 8043:
                case 8044:
                case 8045:
                case 8046:
                case 8047:
                case 8048:
                case 8049:
                        return ConnServer("10.28.38.32:3356", "gameuser", "rio8t89o,690.60fk", "pokegame2");
                        break;
                default:
                        return false;
                        break;
        }
}

/**
 * 合服函数
 * @param $server
 * @return int
 */
function togetherServer($server){
        switch ($server){
                case 3001:
                case 3002:
                        return 3001;
                        break;
                case 3003:
                case 3004:
                case 3005:
                case 3006:
                case 3007:
                case 3008:
                        return 3003;
                        break;
                case 3009:
                case 3010:
                case 3011:
                case 3012:
                case 3013:
                case 3014:
                case 3015:
                case 3016:
                        return 3009;
                        break;
                case 3017:
                case 3018:
                case 3019:
                case 3020:
                case 3021:
                case 3022:
                case 3023:
                case 3024:
                        return 3017;
                        break;
                case 6001:
                case 6002:
                        return 6001;
                        break;
                case 6003:
                case 6004:
                case 6005:
                case 6006:
                case 6007:
                case 6008:
                case 6009:
                case 6010:
                        return 6003;
                        break;
                case 6011:
                case 6012:
                case 6013:
                case 6014:
                case 6015:
                case 6016:
                case 6017:
                case 6018:
                case 6019:
                case 6020:
                        return 6011;
                        break;
                case 8002:
                case 8003:
                case 8004:
                case 8005:
                        return 8002;
                        break;
                case 8006:
                case 8007:
                case 8008:
                case 8009:
                case 8010:
                case 8011:
                case 8012:
                case 8013:
                case 8014:
                        return 8006;
                        break;
                default :
                        return $server;
                        break;
        }

}



function ConnServer($db_host, $db_user, $db_pass, $db_database){
        $db = @mysqli_connect($db_host,$db_user,$db_pass, $db_database);
        if(!$db){
                $db = @mysqli_connect($db_host,$db_user,$db_pass, $db_database);
        }
        if(!$db){
                write_log(ROOT_PATH."log","mysql_connect_log_","mysql connect error,".mysqli_connect_error().", $db_host,$db_user,$db_pass,$db_database, ".date("Y-m-d H:i:s")."\r\n");
                //exit('mysql connect error.');
                return false;
        }
        return $db;
}
?>
