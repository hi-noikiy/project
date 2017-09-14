<?php
$mdString='fu;djf,jk7g.fk*o3l';
date_default_timezone_set('Asia/Shanghai');
function SetConn($ServerInfo){
        switch ($ServerInfo) {
                //web database
                case 88:
                        //return ConnServer("10.28.37.152","payor","u591*hainiu","u591_hj");
                        return ConnServer("127.0.0.1","root","root","u591_hj");
                        break;
                //acount database
                case 81:
                        return ConnServer("106.75.27.74:3316","accountmyuser","rf,d5,8.fgig,fj.80rtyuyjj464j","gunaccount");
                        break;
                case 85:
                        return ConnServer("10.27.224.29:3356","gameuser","rio8t89o,690.60fk","account");
                        break;
                //game server list
                case 9250:
                        return ConnServer("123.59.74.29:3316","gameuser","rif,g8td,650.6uj90","kdgame250");
                        break;
                case 701:
                        return ConnServer("106.75.25.32:3316","gameuser","rio8t89o,690.60fk","gungame1");
                        break;
                case 1001:
                case 1002:
                case 1003:
                case 1004:
                case 1005:
                case 1006:
                case 1007:
                case 1008:
                case 1009:
                case 1010:
                case 1011:
                case 1012:
                case 1013:
                case 1014:
                case 1015:
                case 1016:
                case 1017:
                case 1018:
                case 1019:
                case 1020:

                        return ConnServer("115.159.188.129:3356","gameuser","rio8t89o,690.60fk","pokegamesky");
                        break;
                case 1998:

                        return ConnServer("121.43.40.76:3356","gameuser","rio8t89o,690.60fk","pokegameskybq");
                        break;               

                case 2001:
                case 2002:
                case 2003:
                case 2004:
                case 2005:
                case 2006:
                case 2007:
                case 2008:
                case 2009:
                case 2010:
                case 2011:
                case 2012:
                case 2013:
                case 2014:
                case 2015:
                case 2016:
                case 2017:
                case 2018:
                case 2019:
                case 2020:

                        return ConnServer("106.75.99.242:3356","gameuser","rio8t89o,690.60fk","pokegamenn");
                        break;
                case 2999:

                        return ConnServer("106.75.99.242:3356","gameuser","rio8t89o,690.60fk","pokegamennsh");
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
                case 3041:
                case 3042:
                case 3043:
                case 3044:
                case 3045:
                case 3046:
                case 3047:
                case 3048:
                case 3049:
                case 3050:
                case 3051:
                case 3052:
                case 3053:
                case 3054:
                case 3055:
                case 3056:
                case 3057:
                case 3058:
                case 3059:
                case 3060:
                case 3061:
                case 3062:
                case 3063:
                case 3064:
                case 3065:
                case 3066:
                case 3067:
                case 3068:
                case 3069:
                case 3070:
                case 3071:
                case 3072:
                case 3073:
                case 3074:
                case 3075:
                case 3076:
                case 3077:
                case 3078:
                case 3079:
                case 3080:
                case 3081:
                case 3082:
                case 3083:
                case 3084:
                case 3085:
                case 3086:
                case 3087:
                case 3088:
                case 3089:
                case 3090:
                        return ConnServer("123.206.111.43:3356","gameuser","rio8t89o,690.60fk","pokegametx");
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
                case 5030:
                case 5031:
                case 5032:
                case 5033:
                case 5034:
                case 5035:
                case 5036:
                case 5037:
                case 5038:
                case 5039:
                case 5040:
                case 5041:
                case 5042:
                case 5043:
                case 5044:
                case 5045:
                case 5046:
                case 5047:
                case 5048:
                case 5049:
                case 5050:
                case 5091:
                case 5092:
                case 5093:
                case 5094:
                case 5095:
                case 5096:
                case 5097:
                case 5098:
                case 5099:
                case 5100:
                case 5181:
                case 5182:
                case 5183:
                case 5184:
                case 5185:
                case 5186:
                case 5187:
                case 5188:
                case 5189:
                case 5190:
                case 5211:
                case 5212:
                case 5213:
                case 5214:
                case 5215:
                case 5216:
                case 5217:
                case 5218:
                case 5219:
                case 5220:
                        return ConnServer("118.178.125.167:3356","gameuser","rio8t89o,690.60fk","pokegamep800");   
                        break;
                case 5051:
                case 5052:
                case 5053:
                case 5054:
                case 5055:
                case 5056:
                case 5057:
                case 5058:
                case 5059:
                case 5060:
                case 5061:
                case 5062:
                case 5063:
                case 5064:
                case 5065:
                case 5066:
                case 5067:
                case 5068:
                case 5069:
                case 5070:
                case 5071:
                case 5072:
                case 5073:
                case 5074:
                case 5075:
                case 5076:
                case 5077:
                case 5078:
                case 5079:
                case 5080:
                case 5081:
                case 5082:
                case 5083:
                case 5084:
                case 5085:
                case 5086:
                case 5087:
                case 5088:
                case 5089:
                case 5090:
                case 5151:
                case 5152:
                case 5153:
                case 5154:
                case 5155:
                case 5156:
                case 5157:
                case 5158:
                case 5159:
                case 5160:
                case 5171:
                case 5172:
                case 5173:
                case 5174:
                case 5175:
                case 5176:
                case 5177:
                case 5178:
                case 5179:
                case 5180:
                case 5191:
                case 5192:
                case 5193:
                case 5194:
                case 5195:
                case 5196:
                case 5197:
                case 5198:
                case 5199:
                case 5200:
                        return ConnServer("114.55.32.236:3356","gameuser","rio8t89o,690.60fk","pokegame2p800");
                        break;
                case 5101:
                case 5102:
                case 5103:
                case 5104:
                case 5105:
                case 5106:
                case 5107:
                case 5108:
                case 5109:
                case 5110:
                case 5111:
                case 5112:
                case 5113:
                case 5114:
                case 5115:
                case 5116:
                case 5117:
                case 5118:
                case 5119:
                case 5120:
                case 5121:
                case 5122:
                case 5123:
                case 5124:
                case 5125:
                case 5126:
                case 5127:
                case 5128:
                case 5129:
                case 5130:
                case 5131:
                case 5132:
                case 5133:
                case 5134:
                case 5135:
                case 5136:
                case 5137:
                case 5138:
                case 5139:
                case 5140:
                case 5141:
                case 5142:
                case 5143:
                case 5144:
                case 5145:
                case 5146:
                case 5147:
                case 5148:
                case 5149:
                case 5150:
                case 5161:
                case 5162:
                case 5163:
                case 5164:
                case 5165:
                case 5166:
                case 5167:
                case 5168:
                case 5169:
                case 5170:
                case 5201:
                case 5202:
                case 5203:
                case 5204:
                case 5205:
                case 5206:
                case 5207:
                case 5208:
                case 5209:
                case 5210:
                        return ConnServer("114.55.8.190:3356","gameuser","rio8t89o,690.60fk","pokegame3p800");
                        break;

                case 5997:
                        return ConnServer("118.178.125.167:3356","gameuser","rio8t89o,690.60fk","pokegamep800bq2");
                        break;


                case 5998:
                        return ConnServer("118.178.125.167:3356","gameuser","rio8t89o,690.60fk","pokegamebq");
                        break;
                case 5999:
                        return ConnServer("118.178.125.167:3356","gameuser","rio8t89o,690.60fk","shenhe170206");
                        break;
                case 15001:
                case 15002:
                case 15003:
                case 15004:
                case 15005:
                case 15006:
                case 15007:
                case 15008:
                case 15009:
                case 15010:
                case 15011:
                case 15012:
                case 15013:
                case 15014:
                case 15015:
                case 15016:
                case 15017:
                case 15018:
                case 15019:
                case 15020:
                case 15021:
                case 15022:
                case 15023:
                case 15024:
                case 15025:
                case 15026:
                case 15027:
                case 15028:
                case 15029:
                case 15030:
                        return ConnServer("118.178.125.167:3356","gameuser","rio8t89o,690.60fk","pokegamep8andr");
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
                case 6030:
                case 6031:
                case 6032:
                case 6033:
                case 6034:
                case 6035:
                case 6036:
                case 6037:
                case 6038:
                case 6039:
                case 6040:
                case 6041:
                case 6042:
                case 6043:
                case 6044:
                case 6045:
                case 6046:
                case 6047:
                case 6048:
                case 6049:
                case 6050:
                case 6051:
                case 6052:
                case 6053:
                case 6054:
                case 6055:
                case 6056:
                case 6057:
                case 6058:
                case 6059:
                case 6060:
                case 6061:
                case 6062:
                case 6063:
                case 6064:
                case 6065:
                case 6066:
                case 6067:
                case 6068:
                case 6069:
                case 6070:
                case 6071:
                case 6072:
                case 6073:
                case 6074:
                case 6075:
                case 6076:
                case 6077:
                case 6078:
                case 6079:
                        return ConnServer("121.196.227.111:3356","gameuser","rio8t89o,690.60fk","pokegamemha");
                        break;
                case 6080:
                case 6081:
                case 6082:
                case 6083:
                case 6084:
                case 6085:
                case 6086:
                case 6087:
                case 6088:
                case 6089:
                case 6090:
                case 6090:
                case 6091:
                case 6092:
                case 6093:
                case 6094:
                case 6095:
                case 6096:
                case 6097:
                case 6098:
                case 6099:
                case 6100:
                case 6101:
                case 6102:
                case 6103:
                case 6104:
                case 6105:
                case 6106:
                case 6107:
                case 6108:
                case 6109:
                case 6110:
                        return ConnServer("120.55.188.203:3356","gameuser","rio8t89o,690.60fk","pokegame2mha");
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
                case 8051:
                case 8052:
                case 8053:
                case 8054:
                case 8055:
                case 8056:
                case 8057:
                case 8058:
                case 8059:
                case 8060:
                case 8091:
                case 8092:
                case 8093:
                case 8094:
                case 8095:
                case 8096:
                case 8097:
                case 8098:
                case 8099:
                case 8100:
                case 8101:
                case 8102:
                case 8103:
                case 8104:
                case 8105:
                case 8106:
                case 8107:
                case 8108:
                case 8109:
                case 8110: 
                case 8121:
                case 8122:
                case 8123:
                case 8124:
                case 8125:
                case 8126:
                case 8127:
                case 8128:
                case 8129:
                case 8130:
                        return ConnServer("10.28.38.125:3356","gameuser","rio8t89o,690.60fk","pokegame");
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
                case 8050:
                case 8061:
                case 8062:
                case 8063:
                case 8064:
                case 8065:
                case 8066:
                case 8067:
                case 8068:
                case 8069:
                case 8070:
                case 8071:
                case 8072:
                case 8073:
                case 8074:
                case 8075:
                case 8076:
                case 8077:
                case 8078:
                case 8079:
                case 8080:
                case 8081:
                case 8082:
                case 8083:
                case 8084:
                case 8085:
                case 8086:
                case 8087:
                case 8088:
                case 8089:
                case 8090:
                case 8111:
                case 8112:
                case 8113:
                case 8114:
                case 8115:
                case 8116:
                case 8117:
                case 8118:
                case 8119:
                case 8120:
                        return ConnServer("10.28.38.32:3356","gameuser","rio8t89o,690.60fk","pokegame2");
                        break;
                case 9001:
                case 9002:
                case 9003:
                case 9004:
                case 9005:
                case 9006:
                case 9007:
                case 9008:
                case 9009:
                case 9010:

                        return ConnServer("10.28.38.125:3356","gameuser","rio8t89o,690.60fk","pokegamehn");
                        break;
                case 9997:
                        return ConnServer("10.28.38.125:3356","gameuser","rio8t89o,690.60fk","pokegamehnbq2");
                        break;
                case 9998:
                        return ConnServer("10.28.38.125:3356","gameuser","rio8t89o,690.60fk","pokegamehnbq");
                        break;
                case 9999:
                        return ConnServer("10.28.38.125:3356","gameuser","rio8t89o,690.60fk","pokegamehnsh");
                        break;
                case 11001:
                case 11002:
                case 11003:
                case 11004:
                case 11005:
                case 11006:
                case 11007:
                case 11008:
                case 11009:
                case 11010:
                case 11011:
                case 11012:
                case 11013:
                case 11014:
                case 11015:
                case 11016:
                case 11017:
                case 11018:
                case 11019:
                case 11020:
                        return ConnServer("106.14.79.124:3356","gameuser","rio8t89o,690.60fk","pokegamehs");
                        break;
                case 11997:
                        return ConnServer("106.14.79.124:3356","gameuser","rio8t89o,690.60fk","pokegamehsbq2");
                        break;
                case 11998:
                        return ConnServer("106.14.79.124:3356","gameuser","rio8t89o,690.60fk","pokegamehsbq");
                        break;
                case 12001:
                case 12002:
                case 12003:
                case 12004:
                case 12005:
                case 12006:
                case 12007:
                case 12008:
                case 12009:
                case 12010:
                case 12011:
                case 12012:
                case 12013:
                case 12014:
                case 12015:
                case 12014:
                case 12015:
                case 12016:
                case 12017:
                case 12018:
                case 12019:
                case 12020:
                        return ConnServer("122.152.213.232:3356","gameuser","rio8t89o,690.60fk","pokegameshenh");
                        break;
                case 12998:
                        return ConnServer("122.152.213.232:3356","gameuser","rio8t89o,690.60fk","pokegameshbq");
                        break;
                case 13001:
                case 13002:
                case 13003:
                case 13004:
                case 13005:
                case 13006:
                case 13007:
                case 13008:
                case 13009:
                case 13010:
                case 13011:
                case 13012:
                case 13013:
                case 13014:
                case 13015:
                case 13014:
                case 13015:
                case 13016:
                case 13017:
                case 13018:
                case 13019:
                case 13020:
                        return ConnServer("120.92.144.213:3356","gameuser","rio8t89o,690.60fk","pokegamecy");
                        break;
                case 14001:
                case 14002:
                case 14003:
                case 14004:
                case 14005:
                case 14006:
                case 14007:
                case 14008:
                case 14009:
                case 14010:
                case 14011:
                case 14012:
                case 14013:
                case 14014:
                case 14015:
                case 14014:
                case 14015:
                case 14016:
                case 14015:
                case 14016:
                case 14017:
                case 14018:
                case 14019:
                case 14020:
                        return ConnServer("120.55.47.54:3356","gameuser","rio8t89o,690.60fk","pokegamext");
                        break;

                case 999001:
                        return ConnServer("123.59.144.183:3356","gameuser","rio8t89o,690.60fk","shpokegame");
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
                        return 3025;
                        break;
                case 3035:
                case 3036:
                case 3037:
                case 3038:
                case 3039:
                case 3040:
                case 3041:
                case 3042:
                        return 3035;
                        break;
                case 3043:
                case 3044:
                case 3045:
                case 3046:
                case 3047:
                        return 3043;
                        break;
                case 3048:
                case 3049:
                case 3050:
                case 3051:
                        return 3048;
                        break;;

                case 3052:
                case 3053:
                case 3054:
                case 3055:
                        return 3052;
                        break;
                case 3056:
                case 3057:
                case 3058:
                case 3059:
                case 3060:
                case 3061:
                case 3062:
                case 3063:

                        return 3056;
                        break;



                case 5002: 
                case 5003:
                case 5004:
                case 5005:
                case 5006:
                case 5007:
                case 5008:
                        return 5002;
                        break;
                case 5009:
                case 5010:
                case 5011:
                case 5012:
                case 5013:
                case 5014:
                        return 5009;
                        break;
                case 5015:
                case 5016:
                case 5017:
                case 5018:
                case 5019:
                case 5020:
                case 5021:
                        return 5015;
                        break;
                case 5022:
                case 5023:
                case 5024:
                case 5025:
                case 5026:
                case 5027:
                        return 5022;
                        break;
                case 5028:
                case 5029:
                case 5030:
                        return 5028;
                        break;
                case 5031:
                case 5032:
                case 5033:
                case 5034:
                case 5035:
                        return 5031;
                        break;
                case 5036:
                case 5037:
                case 5038:
                case 5039:
                case 5040:
                        return 5036;
                        break;
                case 5041:
                case 5042:
                case 5043:
                case 5044:
                case 5045:
                case 5046:
                        return 5041;
                        break;

                case 5047:
                case 5048:
                case 5049:
                case 5050:
                        return 5047;
                        break;
                case 5051:
                case 5052:
                case 5053:
                case 5054:
                        return 5051;
                        break;

                case 5055:
                case 5056:
                case 5057:
                        return 5055;
                        break;

                case 5058:
                case 5059:
                case 5060:
                        return 5058;
                        break;

                case 5061:
                case 5062:
                case 5063:
                case 5064:
                        return 5061;
                        break;

                case 5065:
                case 5066:
                case 5067:
                case 5068:
                        return 5065;
                        break;
               case 5069:
                case 5070:
                case 5071:
                case 5072:
                case 5073:
                case 5074:
                case 5075:
                        return 5069;
                        break;


                case 5076:
                case 5077:
                case 5078:
                case 5079:
                case 5080:
                case 5081:
                case 5082:
                case 5083:
                        return 5076;
                        break;

                case 5084:
                case 5085:
                case 5086:
                case 5087:
                case 5088:
                case 5089:
                case 5090:
                        return 5084;
                        break;
                case 5091:
                case 5092:
                case 5093:
                case 5094:
                case 5095:
                        return 5091;
                        break;

                case 5096:
                case 5097:
                case 5098:
                case 5099:
                case 5100:
                        return 5096;
                        break;

                case 5101:
                case 5102:
                case 5103:
                case 5104:
                case 5105:
                case 5106:
                case 5107:
                        return 5101;
                        break;


                case 5108:
                case 5109:
                case 5110:
                case 5111:
                case 5112:
                case 5113:
                case 5114:
                case 5115:
                case 5116:
                        return 5108;
                        break;



                case 5117:
                case 5118:
                case 5119:
                case 5120:
                case 5121:
                        return 5117;
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
                case 6021:
                case 6022:
                case 6023:
                case 6024:
                case 6025:
                case 6026:
                case 6027:
                case 6028:
                case 6029:
                case 6030:
                        return 6021;
                        break;
                case 6031:
                case 6032:
                case 6033:
                case 6034:
                case 6035:
                case 6036:
                case 6037:
                        return 6031;
                        break;
                case 6038:
                case 6039:
                case 6040:
                case 6041:
                case 6042:
                        return 6038;
                        break;
                case 6043:
                case 6044:
                case 6045:
                case 6046:
                case 6047:
                        return 6043;
                        break;
                case 6048:
                case 6049:
                case 6050:
                case 6051:
                        return 6048;
                        break;

                case 6052:
                case 6053:
                case 6054:
                case 6055:
                        return 6052;
                        break;

                case 6056:
                case 6057:
                case 6058:
                case 6059:
                        return 6056;
                        break;
                case 6060:
                case 6061:
                case 6062:
                case 6063:
                        return 6060;
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
                case 8015:
                case 8016:
                case 8017:
                case 8018:
                case 8019:
                        return 8006;
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
                        return 8020;
                        break;
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
                case 8050:
                        return 8039;
                        break;

                case 8051:
                case 8052:
                case 8053:
                case 8054:
                case 8055:
                case 8056:
                case 8057:
                case 8058:
                case 8059:
                case 8060:
                        return 8051;
                        break;


                case 8061:
                case 8062:
                case 8063:
                case 8064:
                case 8065:
                case 8066:
                case 8067:
                case 8068:
                case 8069:
                case 8070:
                        return 8061;
                        break;
                case 8071:
                case 8072:
                case 8073:
                case 8074:
                case 8075:
                case 8074:
                case 8075:
                case 8076:
                       return 8071;
                        break;
                case 8077:
                case 8078:
                case 8079:
                case 8080:
                case 8081:
                        return 8077;
                        break;
                case 8082:
                case 8083:
                case 8084:
                case 8085:
                case 8086:
                case 8087:
                case 8088:
                case 8089:
                case 8090:
                        return 8082;
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
