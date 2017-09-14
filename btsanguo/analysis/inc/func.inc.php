<?php
    function ismobile($tel) {
        $arr_num = array('18','13','15','14');
        $sb = substr($tel,0,2);
        if(strlen($tel)!=11 || !in_array($sb,$arr_num)) {
            return false;
        }
        return true;
    }
    function plus_key($val,$key, $nw) {
        $nw[0][$key+1] = $val;
    }
    function isphone($tel) {
        $phone="/^(\d{3,4}-?)?\d{7,8}$/";
        return preg_match($phone, $tel);
    }
    function min_by_key($arr, $key){
        $min = array();
        foreach ($arr as $val) {
            if(!is_numeric($val[$key])) continue;
            if (!isset($val[$key]) and is_array($val)) {
                $min2 = min_by_key($val, $key);
                $min[$min2] = 1;
            } elseif (!isset($val[$key]) and !is_array($val)) {
                return false;
            } elseif (isset($val[$key])) {
                $min[$val[$key]] = 1;
            }
        }
        if(count($min)>0) {
            return min( array_keys($min) );
        }
        return 0;
    }
//判断联系电话
    function is_tel($tel)
    {
        /**
         * 中国移动：China Mobile
         * 134[0-8],135,136,137,138,139,150,151,157,158,159,182,183,187,188
         */
        $mobile = "/^1(34[0-8]|(3[5-9]|5[017-9]|8[2378])\\d)\\d{7}$/";
        /**
         * 中国联通：China Unicom
         * 130,131,132,152,155,156,185,186
         */
        $unicom = "/^1(3[0-2]|5[256]|8[56])\\d{8}$/";
        /**
         * 中国电信：China Telecom
         * 133,1349,153,180,189
         */
        $tele = "/^1((33|53|8[09])[0-9]|349)\\d{7}$/";
        /**
        *固定电话
        */
        $phone="/^(\d{3,4}-)?\d{7,8}$/";
        if(preg_match($mobile,$tel)||preg_match($unicom,$tel)||preg_match($tele,$tel)||preg_match($phone,$tel)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 判断email格式是否正确
     */
    function is_email($email) {
        return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
    }
    /**
     * 验证年月日
     */
    function checkData($mydate)
    {
        list($yy,$mm,$dd)=explode("-",$mydate);
        if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd)){ 
            return checkdate($mm,$dd,$yy); 
        } 
        return false;            
    } 
    /**
    *创建多级目录
    */
    function mkdirs($dir)  
    {  
        if(!is_dir($dir))  
        {  
            if(!mkdirs(dirname($dir))){  
                return false;
            }  
            if(!mkdir($dir,0777)){  
                return false;
            }
            chmod($dir, 0777); 
        }  
        return true;  
    }

    /**
    *函数: sqlUpdate()
    * 功能: Update更新数据的函数
    * 参数: $taname 要插入数据的表名
    * 参数: $row 要插入的内容 (数组)
    * 参数: $where 要插入的内容 的条件
    * 返回: Update语句
    */
    function sqlUpdate($tbname, $row, $where) {
        $sqlud='';
        foreach ( $row as $key => $value ) {
        $sqlud.=add_special_char($key).'=\''.mysql_real_escape_string($value).'\',';
        }
        return "UPDATE `".$tbname."` SET " . substr ( $sqlud, 0, - 1 )." WHERE " . $where;
    }
    /**
    *函数: sqlInsert()
    * 功能: Insert更新数据的函数
    * 参数: $taname 要插入数据的表名
    * 参数: $row 要插入的内容 (数组)
    * 返回: Insert语句
    */
    function sqlInsert($tbname,$row)
    {
        $sqlInsert='insert '.$tbname.' set ';
        $sqlVal='';
        foreach($row as $k =>$v)
        {
            $sqlVal.=add_special_char($k).'= \''.mysql_real_escape_string($v).'\',';
        }
        $sqlInsert.=substr($sqlVal,0,-1);
        return $sqlInsert;
    }
function request_post($url, $data) {
    $context = stream_context_create(array(
        'http'=>array(
            'method'=>'POST',
            'header'=>
            "Accept-language: en\r\n".
                "Content-type: application/x-www-form-urlencoded\r\n",
            'content'=>http_build_query($data)
        )
    ));
    $fp = fopen($url,'r',false,$context);
    if(!$fp) {
        throw new Exception("Problem With $url, $php_errormsg");
    }
    $response = stream_get_contents($fp);
    if ($response === false) {
        throw new Exception("Problem reading data from $url, $php_errormsg");
    }
    return $response;
}

function chkPassword($p1, $p2) {

    $len = mb_strlen($p1,'utf-8');
    if($p1!=$p2) {
        return "两次密码输入不一致";
    }
    elseif($len<6 || $len>20) {
        return "密码长度必须大于6小于20" . $len;
    }
    elseif(preg_match('/\s/', $p1)){
        return "密码仅支持英文、数字和字符，不支持空格";
    }
    return 1;
}

function genCsrfToken()
{
    $token = md5(genRandomString(10).$_SESSION['REQUEST_TIME']);
    $_SESSION['token'] = $token;
    return '<input type="hidden" name="csrf_token" id="csrf_token" value="'.$token.'"/>';
}
/**
 * 获取图片地址
 * @param $string
 * @return array
 */
    function getImgUrl($string) {
        $ext = 'gif|jpg|jpeg|bmp|png';
        preg_match_all("/(href|src)=([\"|']?)([^ \"'>]+\.($ext))\\2/i", $string, $matches);//带引号
        $new_arr=array_unique($matches[0]);//去除数组中重复的值
        return $new_arr;
    }
    //验证价格函数
    function isPrice($num)
    {
        $price_reg='/^([1-9]|0)[0-9]*(\.[0-9]{0,2}+|[0-9]*)$/';//价格必须大于0的数字，且小数点后只能有两位小数
         if(preg_match($price_reg, $num))  {
             return true;
         }
         else {
             return false;
         }
    }
    /**
     * 对字段两边加反引号，以保证数据库安全
     * @param $value 数组值
     */
    function add_special_char(&$value) {
        if('*' == $value || false !== strpos($value, '(')
            || false !== strpos($value, '.') || false !== strpos ( $value, '`'))
        {
            //不处理包含* 或者 使用了sql方法。
        }
        else {
            $value = '`'.trim($value).'`';
        }
        return $value;
    }
    /**
     * 对字段值两边加引号，以保证数据库安全
     * @param $value 数组值
     * @param $key 数组key
     * @param $quotation 
     */
    function escape_string(&$value, $key='', $quotation = 1) {
        if ($quotation) {
            $q = '\'';
        } else {
            $q = '';
        }
        $value = $q.$value.$q;
        return $value;
    }

   function create_small_img($src_url,$target_url,$src_type,$width = 240,$height = 160){
        $filename = $src_url;
        list($width_orig, $height_orig) = getimagesize($filename);
        $image_p = imagecreatetruecolor($width, $height);
        switch($src_type){
        case "image/pjpeg":
        $image = imagecreatefromjpeg($filename);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
            imagejpeg($image_p, $target_url, 100);
            break;
        case "image/jpeg":
        $image = imagecreatefromjpeg($filename);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
            imagejpeg($image_p, $target_url, 100);
            break;
        case "image/gif":
        $image = imagecreatefromgif($filename);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
            imagegif($image_p, $target_url, 100);
            break;
        case "image/x-png":
        $image = imagecreatefrompng($filename);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
            imagepng($image_p, $target_url, 9);
            break;
        }
    }
//纯文本输入
function safetxt($text){
    $text = trim($text);
    $text = strip_tags ( $text );
    $text = htmlspecialchars ( $text, ENT_QUOTES,"UTF-8");
    $text = str_replace ( "'", "", $text );

    return $text;
}
//输入安全的html，针对存入数据库中的数据进行的过滤和转义
function safehtml($text){
    $text = trim ( $text );
    $text = htmlspecialchars ( $text,ENT_QUOTES,"UTF-8" );
    $text = addslashes ( $text );
    return $text;
}
/**
* 获取当前页面网站url
* 
* @author    cgp
* @return    string    $pageURL
*/
function curPageURL(){
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on"){
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80"){
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } 
    else 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
function get_address_from_ip($ip)
{
    $url='http://www.youdao.com/smartresult-xml/search.s?type=ip&q=';
    $xml=file_get_contents($url.$ip);
    $data=simplexml_load_string($xml);
    return $data->product->location;
}
if (!function_exists('genrandomstring')) {
    function genRandomString($len)
    {
        $chars = array(1,2,3,4,5,6,7,8,9,0);
        $charsLen = count($chars) - 1;
        shuffle($chars);    // 将数组打乱
        $output = "";
        for ($i=0; $i<$len; $i++)
        {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }
}


/**
 * 验证输入是否为中文
 * @param $str
 * @return bool
 */
function is_chinese($str)
{
    if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$str)){
        return true;
    }
    else{
        return false;
    }
}
function encrypt($data, $key) {
    $prep_code = serialize($data);
    $block = mcrypt_get_block_size('des', 'ecb');
    if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
        $prep_code .= str_repeat(chr($pad), $pad);
    }
    $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB);
    return base64_encode($encrypt);
}
/**
 * 获取请求ip
 *
 * @return ip地址
 */
function ip() {
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
}

if(!function_exists('GetHtmlSelect')) {
    /**
     * 创建HTML的select元素
     *
     * @param Array $lists 待输出的元素
     * @param string $name select的name属性
     * @param string $selected 默认选中项
     * @param string $default 默认
     * @param array $attrs 其他属性
     * @return bool|string
     */
    function GetHtmlSelect(Array $lists,$name,$selected='', $default='', $attrs=array()) {
        $output = "<select name='{$name}'";
        if(count($attrs)) {
            foreach($attrs as $attr=>$val) {
                $output .= " {$attr}='{$val}'";
            }
        }
        $output .= '>';
        if(is_array($default)) {
            $output .= "<option value='{$default['k']}'>{$default['v']}</option>";
        }
        elseif(is_bool($default) && $default==true) {
            $output .= "<option value=''>--请选择--</option>";
        }

        if(is_array($lists)){
            foreach($lists as $key=>$list) {
                $output .= "<option value='{$key}'" . ($key == $selected && is_numeric($selected) ? "selected='selected'" : '');
                $output .= ">{$list}</option>";
            }
            $output .= "</select>";
            return $output;
        }
        return false;
    }
}


function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

